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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'premium_purchase', 'coupon_redemption'
            $table->string('description');
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->string('duration_label')->nullable(); // e.g. '1 Hari', '7 Hari', '30 Hari'
            $table->integer('duration_days')->nullable();
            $table->timestamp('premium_expires_at')->nullable();
            $table->string('status')->default('completed'); // 'completed', 'expired', 'cancelled'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
