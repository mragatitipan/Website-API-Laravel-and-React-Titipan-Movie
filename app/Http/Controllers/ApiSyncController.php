<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\SyncLog;
use App\Services\MovieApiService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiSyncController extends Controller
{
    protected $movieApi;

    public function __construct(MovieApiService $movieApi)
    {
        $this->movieApi = $movieApi;
    }

    /**
     * Show sync page
     */
    public function index()
    {
        return view('sync.index');
    }

    /**
     * Show sync history page
     */
    public function history()
    {
        $history = SyncLog::orderBy('synced_at', 'desc')->paginate(20);
        return view('sync.history', compact('history'));
    }

    /**
     * Sync movies from TMDB API
     */
    public function sync(Request $request)
    {
        $startTime = microtime(true);
        $created = 0;
        $updated = 0;
        $fetched = 0;
        $failed = 0;
        $errors = [];

        try {
            // Test connection first
            if (!$this->movieApi->testConnection()) {
                throw new \Exception('Cannot connect to TMDB API. Please check your API key.');
            }

            // Get genres mapping
            $genres = $this->movieApi->getGenres();
            
            if (empty($genres)) {
                throw new \Exception('Failed to fetch genres from TMDB API.');
            }

            // Fetch popular movies (bisa ditambah page jika mau lebih banyak)
            $pages = $request->get('pages', 1); // default 1 page = 20 movies
            
            for ($page = 1; $page <= $pages; $page++) {
                $moviesData = $this->movieApi->fetchPopularMovies($page);
                
                if (!$moviesData || !isset($moviesData['results'])) {
                    throw new \Exception('Failed to fetch movies from TMDB API.');
                }

                $fetched += count($moviesData['results']);

                foreach ($moviesData['results'] as $movieData) {
                    try {
                        // Map genre IDs to genre names
                        $genreNames = collect($movieData['genre_ids'] ?? [])
                            ->map(function($id) use ($genres) {
                                return $genres[$id] ?? 'Unknown';
                            })
                            ->filter(function($name) {
                                return $name !== 'Unknown';
                            })
                            ->implode(', ');

                        // Skip if no valid genre
                        if (empty($genreNames)) {
                            $genreNames = 'Uncategorized';
                        }

                        // Prepare movie data
                        $moviePayload = [
                            'title' => $movieData['title'] ?? 'Unknown',
                            'original_title' => $movieData['original_title'] ?? null,
                            'original_language' => $movieData['original_language'] ?? null,
                            'overview' => $movieData['overview'] ?? null,
                            'poster_path' => $movieData['poster_path'] ?? null,
                            'backdrop_path' => $movieData['backdrop_path'] ?? null,
                            'release_date' => !empty($movieData['release_date']) 
                                ? $movieData['release_date'] 
                                : now()->format('Y-m-d'),
                            'genre' => $genreNames,
                            'vote_average' => $movieData['vote_average'] ?? 0,
                            'vote_count' => $movieData['vote_count'] ?? 0,
                            'popularity' => $movieData['popularity'] ?? 0,
                            'adult' => $movieData['adult'] ?? false,
                            'video' => $movieData['video'] ?? false,
                        ];

                        // Create or update movie
                        $movie = Movie::updateOrCreate(
                            ['api_id' => $movieData['id']],
                            $moviePayload
                        );

                        $movie->wasRecentlyCreated ? $created++ : $updated++;

                    } catch (\Exception $e) {
                        $failed++;
                        $errors[] = [
                            'movie_id' => $movieData['id'] ?? 'unknown',
                            'title' => $movieData['title'] ?? 'unknown',
                            'error' => $e->getMessage()
                        ];
                    }
                }

                // Add delay to avoid rate limiting
                if ($page < $pages) {
                    usleep(250000); // 0.25 second delay
                }
            }

            $endTime = microtime(true);
            $duration = round($endTime - $startTime);

            // Determine status
            $status = 'success';
            if ($failed > 0 && $failed < $fetched) {
                $status = 'partial';
            } elseif ($failed === $fetched) {
                $status = 'failed';
            }

            // Log sync
            SyncLog::create([
                'synced_at' => now(),
                'records_fetched' => $fetched,
                'records_created' => $created,
                'records_updated' => $updated,
                'records_failed' => $failed,
                'status' => $status,
                'message' => "Synced {$fetched} movies: {$created} created, {$updated} updated, {$failed} failed",
                'error_details' => !empty($errors) ? json_encode($errors) : null,
                'sync_type' => $request->get('sync_type', 'manual'),
                'duration' => $duration,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sync completed successfully',
                'data' => [
                    'fetched' => $fetched,
                    'created' => $created,
                    'updated' => $updated,
                    'failed' => $failed,
                    'duration' => $duration,
                    'last_sync' => now()->toDateTimeString(),
                    'errors' => $errors
                ]
            ]);

        } catch (\Exception $e) {
            $endTime = microtime(true);
            $duration = round($endTime - $startTime);

            // Log failed sync
            SyncLog::create([
                'synced_at' => now(),
                'records_fetched' => $fetched,
                'records_created' => $created,
                'records_updated' => $updated,
                'records_failed' => $failed,
                'status' => 'failed',
                'message' => $e->getMessage(),
                'error_details' => json_encode([
                    'error' => $e->getMessage(), 
                    'trace' => $e->getTraceAsString()
                ]),
                'sync_type' => $request->get('sync_type', 'manual'),
                'duration' => $duration,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage(),
                'data' => [
                    'fetched' => $fetched,
                    'created' => $created,
                    'updated' => $updated,
                    'failed' => $failed,
                ]
            ], 500);
        }
    }


    /**
     * Test API connection
     */
    public function testConnection()
    {
        $isConnected = $this->movieApi->testConnection();
        
        return response()->json([
            'success' => $isConnected,
            'message' => $isConnected 
                ? 'Connection to TMDB API successful!' 
                : 'Failed to connect to TMDB API. Please check your API key.'
        ]);
    }

    /**
     * Get last sync information
     */
    public function getLastSync()
    {
        $lastSync = SyncLog::latest('synced_at')->first();

        if (!$lastSync) {
            return response()->json([
                'success' => true,
                'message' => 'No sync history found',
                'data' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $lastSync->id,
                'synced_at' => $lastSync->synced_at->toDateTimeString(),
                'time_ago' => $lastSync->time_ago,
                'records_fetched' => $lastSync->records_fetched,
                'records_created' => $lastSync->records_created,
                'records_updated' => $lastSync->records_updated,
                'records_failed' => $lastSync->records_failed,
                'total_processed' => $lastSync->total_processed,
                'success_rate' => $lastSync->success_rate,
                'status' => $lastSync->status,
                'status_color' => $lastSync->status_color,
                'status_icon' => $lastSync->status_icon,
                'message' => $lastSync->message,
                'sync_type' => $lastSync->sync_type,
                'sync_type_color' => $lastSync->sync_type_color,
                'duration' => $lastSync->duration,
                'formatted_duration' => $lastSync->formatted_duration,
                'error_details' => $lastSync->error_details_array,
            ]
        ]);
    }

    /**
     * Get sync history
     */
    public function getSyncHistory(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $status = $request->get('status');
        $syncType = $request->get('sync_type');
        
        $query = SyncLog::query();

        if ($status) {
            $query->where('status', $status);
        }

        if ($syncType) {
            $query->where('sync_type', $syncType);
        }

        $history = $query->orderBy('synced_at', 'desc')->paginate($perPage);

        // Transform data - PHP 7.4 compatible
        $transformedData = [];
        foreach ($history->items() as $log) {
            $transformedData[] = [
                'id' => $log->id,
                'synced_at' => $log->synced_at->toDateTimeString(),
                'time_ago' => $log->time_ago,
                'records_fetched' => $log->records_fetched,
                'records_created' => $log->records_created,
                'records_updated' => $log->records_updated,
                'records_failed' => $log->records_failed,
                'total_processed' => $log->total_processed,
                'success_rate' => $log->success_rate,
                'status' => $log->status,
                'status_color' => $log->status_color,
                'status_icon' => $log->status_icon,
                'message' => $log->message,
                'sync_type' => $log->sync_type,
                'sync_type_color' => $log->sync_type_color,
                'duration' => $log->duration,
                'formatted_duration' => $log->formatted_duration,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $transformedData,
            'meta' => [
                'current_page' => $history->currentPage(),
                'last_page' => $history->lastPage(),
                'per_page' => $history->perPage(),
                'total' => $history->total(),
            ]
        ]);
    }

    /**
     * Get sync status
     */
    public function getSyncStatus()
    {
        $lastSync = SyncLog::latest('synced_at')->first();
        $totalMovies = Movie::count();
        $statistics = SyncLog::getStatistics();
        
        return response()->json([
            'success' => true,
            'data' => [
                'last_sync' => $lastSync ? [
                    'synced_at' => $lastSync->synced_at->toDateTimeString(),
                    'time_ago' => $lastSync->time_ago,
                    'status' => $lastSync->status,
                    'status_color' => $lastSync->status_color,
                    'records_created' => $lastSync->records_created,
                    'records_updated' => $lastSync->records_updated,
                    'success_rate' => $lastSync->success_rate,
                ] : null,
                'total_movies' => $totalMovies,
                'statistics' => $statistics,
                'is_syncing' => false,
            ]
        ]);
    }

    /**
     * Get sync statistics
     */
    public function getStatistics()
    {
        $statistics = SyncLog::getStatistics();
        
        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Delete sync log
     */
    public function deleteSyncLog($id)
    {
        $log = SyncLog::find($id);

        if (!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Sync log not found'
            ], 404);
        }

        $log->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sync log deleted successfully'
        ]);
    }

    /**
     * Clear all sync logs
     */
    public function clearSyncLogs()
    {
        $count = SyncLog::count();
        SyncLog::truncate();

        return response()->json([
            'success' => true,
            'message' => "{$count} sync logs cleared successfully"
        ]);
    }
}
