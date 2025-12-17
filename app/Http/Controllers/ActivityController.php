<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $q = Activity::query();
        $user = auth()->user();
        
        if ($dealId = $request->query('deal_id')) $q->where('deal_id', $dealId);
        if ($contactId = $request->query('contact_id')) $q->where('contact_id', $contactId);
        
        // Обычные пользователи видят только свои задачи и назначенные им
        if ($user && !$user->is_admin && $user->role !== 'manager') {
            $q->where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere(function($q) use ($user) {
                          // Задачи, назначенные менеджером этому пользователю
                          $q->whereNotNull('user_id')
                            ->where('user_id', $user->id);
                      });
            });
        }
        // Менеджеры и админы видят все задачи, включая назначенные пользователям
        
        return response()->json($q->with(['user', 'createdBy'])->orderBy('due_at','asc')->paginate((int)$request->query('per_page', 15)));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'deal_id' => 'nullable|exists:deals,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'nullable|string|max:50',
            'subject' => 'nullable|string',
            'due_at' => 'nullable|date',
            'done' => 'boolean',
        ]);
        
        $user = auth()->user();
        $data['created_by_id'] = $user->id;
        
        if (!isset($data['user_id'])) {
            $data['user_id'] = $user->id;
        }
        
        $activity = Activity::create($data);
        return response()->json($activity->load(['user', 'createdBy']), 201);
    }

    public function show(Activity $activity)
    {
        return response()->json($activity);
    }

    public function update(Request $request, Activity $activity)
    {
        $data = $request->validate([
            'deal_id' => 'nullable|exists:deals,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'nullable|string|max:50',
            'subject' => 'nullable|string',
            'due_at' => 'nullable|date',
            'done' => 'boolean',
        ]);
        
        // Если user_id не указан и пользователь не админ/менеджер, оставляем текущего владельца
        $user = auth()->user();
        if (!isset($data['user_id']) && $user && !$user->is_admin && $user->role !== 'manager') {
            $data['user_id'] = $user->id;
        }
        
        // Сохраняем создателя при обновлении, если его нет
        if (!$activity->created_by_id) {
            $data['created_by_id'] = auth()->id();
        }
        
        $activity->update($data);
        return response()->json($activity->load(['user', 'createdBy']));
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return response()->json(['deleted' => true]);
    }
}
