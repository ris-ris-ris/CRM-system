<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_account_id')->nullable()->constrained('email_accounts')->onDelete('set null');
            $table->string('message_id')->unique(); // Уникальный ID письма
            $table->string('thread_id')->nullable()->index(); // Для группировки переписки
            $table->string('from_email');
            $table->string('from_name')->nullable();
            $table->text('to_email'); // Может быть несколько получателей
            $table->text('to_name')->nullable();
            $table->text('cc')->nullable();
            $table->text('bcc')->nullable();
            $table->string('subject');
            $table->text('body_html')->nullable();
            $table->text('body_text')->nullable();
            $table->boolean('is_read')->default(false);
            $table->boolean('is_starred')->default(false);
            $table->enum('direction', ['incoming', 'outgoing'])->default('incoming');
            $table->timestamp('received_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->integer('attachments_count')->default(0);
            $table->timestamps();
            $table->index(['email_account_id', 'direction']);
            $table->index(['thread_id', 'sent_at']);
            $table->index('from_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
