<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('still_valid');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['report_id', 'user_id']);
            $table->index(['report_id', 'still_valid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_confirmations');
    }
};