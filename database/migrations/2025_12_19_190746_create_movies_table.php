<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            
            // ===== DATA DARI TMDB API =====
            $table->integer('api_id')->unique()->comment('ID dari TMDB API');
            
            // Basic Info
            $table->string('title');
            $table->string('original_title')->nullable();
            $table->string('original_language', 10)->nullable();
            $table->text('overview')->nullable();
            $table->text('tagline')->nullable();
            
            // Images
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            
            // WAJIB: TANGGAL (untuk filter & chart)
            $table->date('release_date');
            
            // WAJIB: KATEGORI (untuk pie chart)
            $table->string('genre')->comment('Genre: Action, Drama, dll (comma-separated)');
            
            // Ratings & Popularity
            $table->decimal('vote_average', 3, 1)->default(0)->comment('Rating 0-10');
            $table->integer('vote_count')->default(0);
            $table->decimal('popularity', 10, 3)->default(0);
            
            // Additional Info
            $table->integer('runtime')->nullable()->comment('Duration in minutes');
            $table->string('status', 50)->nullable()->comment('Released, Post Production, etc');
            $table->bigInteger('budget')->nullable()->comment('Production budget in USD');
            $table->bigInteger('revenue')->nullable()->comment('Box office revenue in USD');
            
            // Production
            $table->text('production_companies')->nullable()->comment('JSON array of companies');
            $table->text('production_countries')->nullable()->comment('JSON array of countries');
            $table->text('spoken_languages')->nullable()->comment('JSON array of languages');
            
            // Adult Content
            $table->boolean('adult')->default(false);
            $table->boolean('video')->default(false);
            
            // Homepage & IMDB
            $table->string('homepage')->nullable();
            $table->string('imdb_id', 20)->nullable();
            
            $table->timestamps(); // created_at, updated_at
            
            // ===== INDEXES untuk PERFORMA =====
            $table->index('release_date');
            $table->index('genre');
            $table->index('vote_average');
            $table->index('popularity');
            $table->index('status');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
