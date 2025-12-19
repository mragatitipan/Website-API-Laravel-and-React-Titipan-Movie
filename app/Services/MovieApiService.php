<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MovieApiService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
        $this->baseUrl = config('services.tmdb.base_url');
    }

    /**
     * Fetch popular movies from TMDB
     */
    public function fetchPopularMovies($page = 1)
    {
        try {
            $response = Http::get("{$this->baseUrl}/movie/popular", [
                'api_key' => $this->apiKey,
                'page' => $page,
                'language' => 'en-US'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('TMDB API Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('TMDB API Exception', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get all movie genres
     */
    public function getGenres()
    {
        try {
            $response = Http::get("{$this->baseUrl}/genre/movie/list", [
                'api_key' => $this->apiKey,
                'language' => 'en-US'
            ]);

            if ($response->successful()) {
                return collect($response->json()['genres'] ?? [])
                    ->pluck('name', 'id')
                    ->toArray();
            }

            return [];
        } catch (\Exception $e) {
            Log::error('TMDB Genre API Exception', [
                'message' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Test API connection
     */
    public function testConnection()
    {
        try {
            $response = Http::get("{$this->baseUrl}/configuration", [
                'api_key' => $this->apiKey
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
