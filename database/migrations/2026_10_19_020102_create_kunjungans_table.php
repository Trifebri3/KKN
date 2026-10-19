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
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code')->unique()->nullable();
            $table->string('nama')->default('Anonymous');
            $table->string('no_telpon')->nullable();
            $table->string('jenis_kunjungan')->default('Kunjungan');
            $table->string('instansi')->nullable();
            $table->text('keperluan')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};
