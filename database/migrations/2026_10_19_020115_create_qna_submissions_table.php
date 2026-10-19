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
        Schema::create('qna_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->default('Anonim');
            $table->string('email_or_phone')->nullable();
            $table->text('pertanyaan');
            $table->text('jawaban')->nullable();
            $table->string('status')->default('pending'); // pending, answered, published
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qna_submissions');
    }
};
