<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('file_attachments', function (Blueprint $table) {
            $table->id();
            $table->morphs('attachable');
            $table->string('path');
            $table->string('original_name');
            $table->unsignedBigInteger('size')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('file_attachments'); }
};
