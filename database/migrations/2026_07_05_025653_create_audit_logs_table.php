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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('tab'); // audit, activity, api
            $table->string('action'); // e.g. "Hapus User", "/pdf-crop/crop", "Compress PDF"
            $table->text('details')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('status')->default('success'); // success, failed, 200 OK, 500 Error
            $table->string('latency')->nullable(); // for API log
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
