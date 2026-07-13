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
        Schema::table('guest_tool_usages', function (Blueprint $table) {
            // Drop the old unique constraint on (ip_address, usage_date)
            $table->dropUnique(['ip_address', 'usage_date']);

            // Add new unique constraint on (ip_address, tool_name, usage_date)
            // so each guest gets 1 try per tool per day
            $table->unique(['ip_address', 'tool_name', 'usage_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guest_tool_usages', function (Blueprint $table) {
            $table->dropUnique(['ip_address', 'tool_name', 'usage_date']);
            $table->unique(['ip_address', 'usage_date']);
        });
    }
};
