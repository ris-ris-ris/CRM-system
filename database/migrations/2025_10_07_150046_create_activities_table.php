<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->nullable()->constrained('deals')->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->string('type')->default('call');
            $table->text('subject')->nullable();
            $table->dateTime('due_at')->nullable();
            $table->boolean('done')->default(false);
            $table->timestamps();
            $table->index(['deal_id','contact_id','due_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('activities'); }
};
