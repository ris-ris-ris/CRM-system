<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('industry')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
            $table->index(['name','email']);
        });
    }
    public function down(): void { Schema::dropIfExists('companies'); }
};
