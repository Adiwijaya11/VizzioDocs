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
        Schema::create('code_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable()->default('Untitled');
            $table->longText('content')->nullable();
            $table->string('file_name')->nullable()->default('untitled.txt');
            $table->string('language')->nullable()->default('plaintext');
            $table->string('status')->default('draft'); // draft, saved, published
            $table->timestamp('last_saved_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_drafts');
    }
};
