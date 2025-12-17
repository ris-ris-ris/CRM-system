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
        Schema::create('email_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_id')->constrained('emails')->onDelete('cascade');
            $table->string('filename');
            $table->string('path');
            $table->integer('size')->default(0); // в байтах
            $table->string('mime_type')->nullable();
            $table->timestamps();
            $table->index('email_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_attachments');
    }
};
