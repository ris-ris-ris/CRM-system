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
        Schema::create('email_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_id')->constrained('emails')->onDelete('cascade');
            $table->string('related_type'); // Deal, Company, Contact
            $table->unsignedBigInteger('related_id');
            $table->timestamps();
            $table->index(['email_id', 'related_type', 'related_id']);
            $table->index(['related_type', 'related_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_relations');
    }
};
