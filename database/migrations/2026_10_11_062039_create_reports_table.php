<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('place_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('category', ['CROWD', 'WIFI', 'DAMAGE', 'CLEANLINESS', 'AVAILABILITY', 'OTHER']);
            $table->text('description');
            $table->enum('status', ['PENDING', 'VERIFIED', 'RESOLVED', 'REJECTED'])->default('PENDING');
            $table->timestamps();

            $table->index(['place_id', 'status']);
            $table->index(['user_id', 'created_at']);
            $table->index(['category', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};