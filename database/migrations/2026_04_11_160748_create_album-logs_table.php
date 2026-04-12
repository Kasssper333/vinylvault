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
        Schema::create('album-logs', function (Blueprint $table) {
             $table->id();
            
            // Внешние ключи
            $table->foreignId('album_id')
                  ->constrained('albums')
                  ->onDelete('set null');
            
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('set null');
            
            // Логируемые данные
            $table->enum('action', ['created', 'updated', 'deleted', 'restored'])
                  ->default('created');
            
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            
            $table->timestamps();

            $table->index('action');
            $table->index(['album_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('album-logs');
    }
};
