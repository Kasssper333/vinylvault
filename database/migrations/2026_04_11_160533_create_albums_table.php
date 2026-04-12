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
        Schema::create('albums', function (Blueprint $table) {
             $table->id();
            
            $table->string('title', 255);
            $table->string('artist', 255);
            $table->text('description')->nullable();
            $table->string('cover_url', 512)->nullable();
            $table->string('lastfm_url', 512)->nullable();
            
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->onDelete('restrict');
            
            $table->foreignId('updated_by')
                  ->constrained('users')
                  ->onDelete('restrict');
            
            $table->softDeletes(); 
            
            $table->index('title');
            $table->index('artist');
            $table->index(['artist', 'title']);
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
