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
        Schema::create('email_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('email');
            $table->text('password_encrypted'); // Зашифрованный пароль
            $table->string('provider')->default('custom'); // gmail, mailru, yandex, custom
            $table->string('imap_host')->nullable();
            $table->integer('imap_port')->default(993);
            $table->boolean('imap_ssl')->default(true);
            $table->string('smtp_host')->nullable();
            $table->integer('smtp_port')->default(587);
            $table->boolean('smtp_ssl')->default(true);
            $table->string('smtp_username')->nullable();
            $table->text('smtp_password_encrypted')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_accounts');
    }
};
