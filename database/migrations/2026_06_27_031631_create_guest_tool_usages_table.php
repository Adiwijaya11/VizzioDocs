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
        Schema::create('guest_tool_usages', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('tool_name', 100)->nullable();
            $table->date('usage_date');
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->unique(['ip_address', 'usage_date']);
            $table->index('ip_address');
            $table->index('usage_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_tool_usages');
    }
};
