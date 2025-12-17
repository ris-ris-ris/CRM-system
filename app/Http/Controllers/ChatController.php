<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class ChatController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            // Check if table exists, create if not
            if (!Schema::hasTable('chat_messages')) {
                try {
                    $this->createChatTable();
                    // Double check after creation
                    if (!Schema::hasTable('chat_messages')) {
                        Log::error('Chat table creation failed - table still does not exist');
                        return response()->json(['error' => 'Failed to create chat table'], 500);
                    }
                } catch (\Exception $e) {
                    Log::error('Error creating chat table: ' . $e->getMessage());
                    return response()->json(['error' => 'Database error'], 500);
                }
                // Return empty array if table was just created
                return response()->json([], 200);
            }
            
            $query = ChatMessage::with('user:id,name,email')
                ->orderBy('created_at', 'asc');
            
            // Filter messages after specific ID if provided
            if ($request->has('after') && $request->after > 0) {
                $query->where('id', '>', $request->after);
            }
            
            $messages = $query->get()
                ->map(function ($message) {
                    if (!$message->user) {
                        return null;
                    }
                    return [
                        'id' => $message->id,
                        'user_id' => $message->user_id,
                        'user_name' => $message->user->name ?? 'Неизвестный',
                        'user_email' => $message->user->email ?? '',
                        'message' => $message->message,
                        'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                        'time' => $message->created_at->format('H:i'),
                        'is_own' => $message->user_id === Auth::id(),
                    ];
                })
                ->filter(); // Remove nulls

            return response()->json($messages->values(), 200);
        } catch (\Exception $e) {
            Log::error('Chat index error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Failed to load messages: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            // Check if table exists, create if not
            if (!Schema::hasTable('chat_messages')) {
                $this->createChatTable();
            }
            
            $request->validate([
                'message' => 'required|string|max:1000',
            ]);

            $message = ChatMessage::create([
                'user_id' => Auth::id(),
                'message' => trim($request->message),
            ]);

            $message->load('user:id,name,email');

            if (!$message->user) {
                return response()->json(['error' => 'User not found'], 500);
            }

            return response()->json([
                'id' => $message->id,
                'user_id' => $message->user_id,
                'user_name' => $message->user->name,
                'user_email' => $message->user->email,
                'message' => $message->message,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                'time' => $message->created_at->format('H:i'),
                'is_own' => true,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            Log::error('Chat store error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }
    
    private function createChatTable(): void
    {
        try {
            if (Schema::hasTable('chat_messages')) {
                return;
            }
            
            // Try Schema builder first
            try {
                Schema::create('chat_messages', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id');
                    $table->text('message');
                    $table->timestamps();
                    
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    $table->index('created_at');
                });
                return;
            } catch (\Exception $e) {
                Log::warning('Schema builder failed, trying raw SQL: ' . $e->getMessage());
            }
            
            // Fallback to raw SQL without foreign key (simpler, more compatible)
            try {
                DB::statement("CREATE TABLE IF NOT EXISTS `chat_messages` (
                    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `user_id` BIGINT UNSIGNED NOT NULL,
                    `message` TEXT NOT NULL,
                    `created_at` TIMESTAMP NULL DEFAULT NULL,
                    `updated_at` TIMESTAMP NULL DEFAULT NULL,
                    INDEX `idx_created_at` (`created_at`),
                    INDEX `idx_user_id` (`user_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            } catch (\Exception $e2) {
                Log::error('Raw SQL table creation also failed: ' . $e2->getMessage());
                throw $e2;
            }
        } catch (\Exception $e) {
            Log::error('Failed to create chat_messages table: ' . $e->getMessage());
            throw $e;
        }
    }
}


