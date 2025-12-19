<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DataManagementController extends Controller
{
    /**
     * Show movies list page (web view)
     */
    public function index()
    {
        return view('movies.index');
    }

    /**
     * ========================================================================
     * PREVIEW / READ SINGLE MOVIE (Enhanced with Full Details)
     * ========================================================================
     * Get single movie with ALL details for preview/read mode
     * Returns comprehensive movie information including computed fields
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $movie = Movie::find($id);

            if (!$movie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Movie not found'
                ], 404);
            }

            // Return comprehensive movie data for preview
            return response()->json([
                'success' => true,
                'data' => [
                    // === BASIC INFORMATION ===
                    'id' => $movie->id,
                    'api_id' => $movie->api_id,
                    'title' => $movie->title,
                    'original_title' => $movie->original_title,
                    'original_language' => $movie->original_language,
                    'overview' => $movie->overview,
                    'tagline' => $movie->tagline,
                    
                    // === IMAGES ===
                    'poster_path' => $movie->poster_path,
                    'poster_url' => $movie->poster_url,
                    'backdrop_path' => $movie->backdrop_path,
                    'backdrop_url' => $movie->backdrop_url,
                    
                    // === RELEASE DATE ===
                    'release_date' => $movie->release_date ? $movie->release_date->format('Y-m-d') : null,
                    'release_date_formatted' => $movie->release_date ? $movie->release_date->format('F d, Y') : null,
                    'release_year' => $movie->release_year,
                    'release_month' => $movie->release_month,
                    'release_month_name' => $movie->release_month_name,
                    
                    // === GENRES ===
                    'genre' => isset($movie->attributes['genre']) ? $movie->attributes['genre'] : $movie->genre,
                    'genres' => $movie->genres ?? [],
                    
                    // === RATINGS & POPULARITY ===
                    'vote_average' => $movie->vote_average,
                    'vote_count' => $movie->vote_count,
                    'popularity' => $movie->popularity,
                    'rating_color' => $movie->rating_color,
                    'rating_percentage' => $movie->vote_average ? round($movie->vote_average * 10, 1) : 0,
                    
                    // === RUNTIME ===
                    'runtime' => $movie->runtime,
                    'formatted_runtime' => $movie->formatted_runtime,
                    
                    // === STATUS ===
                    'status' => $movie->status,
                    'status_color' => $movie->status_color,
                    'is_released' => $movie->isReleased(),
                    'is_upcoming' => $movie->isUpcoming(),
                    'age_years' => $movie->getAge(),
                    
                    // === FINANCIAL ===
                    'budget' => $movie->budget,
                    'formatted_budget' => $movie->formatted_budget,
                    'revenue' => $movie->revenue,
                    'formatted_revenue' => $movie->formatted_revenue,
                    'profit' => $movie->profit,
                    'formatted_profit' => $movie->formatted_profit,
                    'profit_percentage' => $movie->profit_percentage,
                    'is_profitable' => $movie->isProfitable(),
                    
                    // === PRODUCTION ===
                    'production_companies' => $movie->production_companies_array ?? [],
                    'production_countries' => $movie->production_countries_array ?? [],
                    'spoken_languages' => $movie->spoken_languages_array ?? [],
                    
                    // === FLAGS ===
                    'adult' => $movie->adult,
                    'video' => $movie->video,
                    
                    // === EXTERNAL LINKS ===
                    'homepage' => $movie->homepage,
                    'imdb_id' => $movie->imdb_id,
                    'imdb_url' => $movie->imdb_url,
                    'tmdb_url' => $movie->tmdb_url,
                    
                    // === TIMESTAMPS ===
                    'created_at' => $movie->created_at->toDateTimeString(),
                    'updated_at' => $movie->updated_at->toDateTimeString(),
                    'created_at_human' => $movie->created_at->diffForHumans(),
                    'updated_at_human' => $movie->updated_at->diffForHumans(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching movie details: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch movie: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ========================================================================
     * CREATE NEW MOVIE (Enhanced Validation)
     * ========================================================================
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Enhanced validation rules
        $validator = Validator::make($request->all(), [
            'api_id' => 'nullable|integer|unique:movies,api_id',
            'title' => 'required|string|max:255',
            'original_title' => 'nullable|string|max:255',
            'original_language' => 'nullable|string|max:10',
            'overview' => 'nullable|string',
            'tagline' => 'nullable|string',
            'poster_path' => 'nullable|string|max:255',
            'backdrop_path' => 'nullable|string|max:255',
            'release_date' => 'required|date',
            'genre' => 'required|string',
            'vote_average' => 'nullable|numeric|between:0,10',
            'vote_count' => 'nullable|integer|min:0',
            'popularity' => 'nullable|numeric|min:0',
            'runtime' => 'nullable|integer|min:0',
            'status' => 'nullable|string|max:50',
            'budget' => 'nullable|integer|min:0',
            'revenue' => 'nullable|integer|min:0',
            'production_companies' => 'nullable|string',
            'production_countries' => 'nullable|string',
            'spoken_languages' => 'nullable|string',
            'adult' => 'nullable|boolean',
            'video' => 'nullable|boolean',
            'homepage' => 'nullable|url|max:255',
            'imdb_id' => 'nullable|string|max:20',
        ], [
            'title.required' => 'Movie title is required',
            'release_date.required' => 'Release date is required',
            'release_date.date' => 'Release date must be a valid date',
            'genre.required' => 'At least one genre is required',
            'vote_average.between' => 'Rating must be between 0 and 10',
            'homepage.url' => 'Homepage must be a valid URL',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create movie
            $movie = Movie::create($request->all());

            Log::info('Movie created successfully', [
                'id' => $movie->id, 
                'title' => $movie->title,
                'api_id' => $movie->api_id,
                'user_ip' => $request->ip()
            ]);

            // Return created movie with full details
            return response()->json([
                'success' => true,
                'message' => 'Movie created successfully',
                'data' => $movie->fresh() // Reload to get all accessors
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating movie: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create movie: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ========================================================================
     * UPDATE EXISTING MOVIE (Enhanced with Better Error Handling)
     * ========================================================================
     * Supports both PUT (full update) and PATCH (partial update)
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $movie = Movie::find($id);

            if (!$movie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Movie not found'
                ], 404);
            }

            // Store original data for comparison
            $originalData = $movie->toArray();

            // Enhanced validation rules
            $validator = Validator::make($request->all(), [
                'api_id' => 'nullable|integer|unique:movies,api_id,' . $id,
                'title' => 'sometimes|required|string|max:255',
                'original_title' => 'nullable|string|max:255',
                'original_language' => 'nullable|string|max:10',
                'overview' => 'nullable|string',
                'tagline' => 'nullable|string',
                'poster_path' => 'nullable|string|max:255',
                'backdrop_path' => 'nullable|string|max:255',
                'release_date' => 'sometimes|required|date',
                'genre' => 'sometimes|required|string',
                'vote_average' => 'nullable|numeric|between:0,10',
                'vote_count' => 'nullable|integer|min:0',
                'popularity' => 'nullable|numeric|min:0',
                'runtime' => 'nullable|integer|min:0',
                'status' => 'nullable|string|max:50',
                'budget' => 'nullable|integer|min:0',
                'revenue' => 'nullable|integer|min:0',
                'production_companies' => 'nullable|string',
                'production_countries' => 'nullable|string',
                'spoken_languages' => 'nullable|string',
                'adult' => 'nullable|boolean',
                'video' => 'nullable|boolean',
                'homepage' => 'nullable|url|max:255',
                'imdb_id' => 'nullable|string|max:20',
            ], [
                'title.required' => 'Movie title is required',
                'release_date.required' => 'Release date is required',
                'release_date.date' => 'Release date must be a valid date',
                'genre.required' => 'At least one genre is required',
                'vote_average.between' => 'Rating must be between 0 and 10',
                'homepage.url' => 'Homepage must be a valid URL',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update only provided fields
            $updateData = $request->all();
            $movie->update($updateData);

            // Get updated movie with all accessors
            $updatedMovie = $movie->fresh();

            // Determine what changed
            $changedFields = [];
            foreach ($updateData as $key => $value) {
                if (isset($originalData[$key]) && $originalData[$key] != $value) {
                    $changedFields[] = $key;
                }
            }

            Log::info('Movie updated successfully', [
                'id' => $movie->id, 
                'title' => $movie->title,
                'changed_fields' => $changedFields,
                'user_ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Movie updated successfully',
                'data' => $updatedMovie,
                'changed_fields' => $changedFields
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating movie: ' . $e->getMessage(), [
                'id' => $id,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update movie: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ========================================================================
     * DELETE MOVIE (Enhanced with Soft Delete Option)
     * ========================================================================
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $movie = Movie::find($id);

            if (!$movie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Movie not found'
                ], 404);
            }

            // Store movie info before deletion
            $movieInfo = [
                'id' => $movie->id,
                'title' => $movie->title,
                'api_id' => $movie->api_id,
                'release_year' => $movie->release_year
            ];

            // Delete movie
            $movie->delete();

            Log::info('Movie deleted successfully', $movieInfo);

            return response()->json([
                'success' => true,
                'message' => 'Movie deleted successfully',
                'deleted_movie' => $movieInfo
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting movie: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete movie: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ========================================================================
     * GET MOVIES LIST (Enhanced with Advanced Filtering)
     * ========================================================================
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMoviesList(Request $request)
    {
        try {
            $query = Movie::query();

            // === FILTERS ===
            
            // Search by title/overview
            if ($request->filled('search')) {
                $search = is_array($request->search) ? $request->search['value'] : $request->search;
                $query->search($search);
            }

            // Filter by genre
            if ($request->filled('genre')) {
                $query->byGenre($request->genre);
            }

            // Filter by year
            if ($request->filled('year')) {
                $query->byYear($request->year);
            }

            // Filter by month
            if ($request->filled('month')) {
                $query->byMonth($request->month);
            }

            // Filter by minimum rating
            if ($request->filled('min_rating')) {
                $query->minRating($request->min_rating);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->byStatus($request->status);
            }

            // Filter by language
            if ($request->filled('language')) {
                $query->byLanguage($request->language);
            }

            // Filter adult content
            if ($request->has('adult')) {
                $query->where('adult', filter_var($request->adult, FILTER_VALIDATE_BOOLEAN));
            }

            // Filter by video availability
            if ($request->has('video')) {
                $query->where('video', filter_var($request->video, FILTER_VALIDATE_BOOLEAN));
            }

            // === SORTING ===
            
            $sortBy = $request->get('sort_by', 'updated_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            $allowedSortFields = [
                'id', 'title', 'release_date', 'vote_average', 
                'vote_count', 'popularity', 'runtime', 'budget', 
                'revenue', 'created_at', 'updated_at'
            ];
            
            if (!in_array($sortBy, $allowedSortFields)) {
                $sortBy = 'updated_at';
            }
            
            $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc';
            
            $query->orderBy($sortBy, $sortOrder);

            // === PAGINATION ===
            
            $perPage = min($request->get('per_page', 12), 100);
            $movies = $query->paginate($perPage);

            // === TRANSFORM DATA ===
            
            $transformedData = [];
            
            foreach ($movies->items() as $movie) {
                $transformedData[] = [
                    'id' => $movie->id,
                    'api_id' => $movie->api_id,
                    'title' => $movie->title,
                    'original_title' => $movie->original_title,
                    'overview' => $movie->overview ? (strlen($movie->overview) > 200 ? substr($movie->overview, 0, 200) . '...' : $movie->overview) : null,
                    'poster_path' => $movie->poster_path,
                    'poster_url' => $movie->poster_url,
                    'backdrop_path' => $movie->backdrop_path,
                    'backdrop_url' => $movie->backdrop_url,
                    'release_date' => $movie->release_date ? $movie->release_date->format('Y-m-d') : null,
                    'release_year' => $movie->release_year,
                    'release_month' => $movie->release_month,
                    'genre' => isset($movie->attributes['genre']) ? $movie->attributes['genre'] : $movie->genre,
                    'genres' => $movie->genres ?? [],
                    'vote_average' => $movie->vote_average,
                    'vote_count' => $movie->vote_count,
                    'popularity' => $movie->popularity,
                    'rating_color' => $movie->rating_color,
                    'runtime' => $movie->runtime,
                    'formatted_runtime' => $movie->formatted_runtime,
                    'status' => $movie->status,
                    'status_color' => $movie->status_color,
                    'formatted_budget' => $movie->formatted_budget,
                    'formatted_revenue' => $movie->formatted_revenue,
                    'formatted_profit' => $movie->formatted_profit,
                    'is_profitable' => $movie->isProfitable(),
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $transformedData,
                'meta' => [
                    'current_page' => $movies->currentPage(),
                    'last_page' => $movies->lastPage(),
                    'per_page' => $movies->perPage(),
                    'total' => $movies->total(),
                    'from' => $movies->firstItem(),
                    'to' => $movies->lastItem(),
                ],
                'filters' => [
                    'search' => $request->get('search'),
                    'genre' => $request->get('genre'),
                    'year' => $request->get('year'),
                    'status' => $request->get('status'),
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching movies list: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch movies: ' . $e->getMessage(),
                'data' => [],
                'meta' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 12,
                    'total' => 0,
                ]
            ], 500);
        }
    }

    /**
     * ========================================================================
     * FILTER METHODS (Metadata for Dropdowns)
     * ========================================================================
     */

    /**
     * Get all unique genres
     */
    public function getGenres()
    {
        try {
            $genres = DB::table('movies')
                ->select('genre')
                ->whereNotNull('genre')
                ->where('genre', '!=', '')
                ->distinct()
                ->get()
                ->pluck('genre')
                ->flatMap(function($genre) {
                    return explode(',', $genre);
                })
                ->map(function($genre) {
                    return trim($genre);
                })
                ->filter()
                ->unique()
                ->sort()
                ->values();
            
            if ($genres->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No genres found. Please sync movies from TMDB first.'
                ]);
            }
            
            $formattedGenres = [];
            $index = 1;
            foreach ($genres as $name) {
                $formattedGenres[] = [
                    'id' => $index++,
                    'name' => $name
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $formattedGenres,
                'total' => count($formattedGenres)
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading genres: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load genres: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get all unique years
     */
    public function getYears()
    {
        try {
            $years = Movie::selectRaw('DISTINCT YEAR(release_date) as year')
                ->whereNotNull('release_date')
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->filter()
                ->values();

            return response()->json([
                'success' => true,
                'data' => $years,
                'total' => $years->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading years: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load years: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get all unique statuses
     */
    public function getStatuses()
    {
        try {
            $statuses = Movie::select('status')
                ->distinct()
                ->whereNotNull('status')
                ->where('status', '!=', '')
                ->orderBy('status')
                ->pluck('status');

            return response()->json([
                'success' => true,
                'data' => $statuses,
                'total' => $statuses->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading statuses: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statuses: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get all unique languages
     */
    public function getLanguages()
    {
        try {
            $languages = Movie::select('original_language')
                ->distinct()
                ->whereNotNull('original_language')
                ->where('original_language', '!=', '')
                ->orderBy('original_language')
                ->pluck('original_language');

            return response()->json([
                'success' => true,
                'data' => $languages,
                'total' => $languages->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading languages: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load languages: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * ========================================================================
     * SEARCH & FILTER
     * ========================================================================
     */

    /**
     * Quick search movies (autocomplete)
     */
    public function search(Request $request)
    {
        try {
            $query = Movie::query();
            
            if ($request->filled('q')) {
                $query->search($request->q);
            }
            
            $limit = min($request->get('limit', 10), 50);
            $movies = $query->select('id', 'title', 'release_date', 'poster_path', 'vote_average')
                           ->limit($limit)
                           ->get();
            
            $transformedMovies = [];
            foreach ($movies as $movie) {
                $transformedMovies[] = [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'release_year' => $movie->release_year,
                    'poster_url' => $movie->poster_url,
                    'vote_average' => $movie->vote_average,
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $transformedMovies,
                'total' => count($transformedMovies)
            ]);
        } catch (\Exception $e) {
            Log::error('Error searching movies: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Advanced filter (alias for getMoviesList)
     */
    public function filter(Request $request)
    {
        return $this->getMoviesList($request);
    }

    /**
     * ========================================================================
     * BULK OPERATIONS
     * ========================================================================
     */

    /**
     * Bulk delete movies
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:movies,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $ids = $request->ids;
            
            // Get movie titles before deletion
            $movies = Movie::whereIn('id', $ids)->get(['id', 'title']);
            $deletedTitles = $movies->pluck('title')->toArray();
            
            // Delete movies
            $count = Movie::whereIn('id', $ids)->delete();

            Log::info('Bulk delete completed', [
                'count' => $count, 
                'ids' => $ids,
                'titles' => $deletedTitles
            ]);

            return response()->json([
                'success' => true,
                'message' => $count . ' movie(s) deleted successfully',
                'deleted_count' => $count,
                'deleted_titles' => $deletedTitles
            ]);
        } catch (\Exception $e) {
            Log::error('Error bulk deleting movies: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete movies: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ========================================================================
     * EXPORT
     * ========================================================================
     */

    /**
     * Export movies to CSV
     */
    public function exportCsv(Request $request)
    {
        try {
            $query = Movie::query();

            // Export specific IDs or all movies
            if ($request->filled('ids') && is_array($request->ids)) {
                $query->whereIn('id', $request->ids);
            }

            // Apply filters
            if ($request->filled('search')) {
                $query->search($request->search);
            }
            if ($request->filled('genre')) {
                $query->byGenre($request->genre);
            }
            if ($request->filled('year')) {
                $query->byYear($request->year);
            }

            $movies = $query->orderBy('title')->get();
            
            $filename = 'movies_export_' . date('Y-m-d_His') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() use ($movies) {
                $file = fopen('php://output', 'w');
                
                // UTF-8 BOM for Excel compatibility
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // CSV Header
                fputcsv($file, [
                    'ID', 'TMDB ID', 'Title', 'Original Title', 'Language',
                    'Genre', 'Release Date', 'Year', 'Rating', 'Votes', 
                    'Popularity', 'Runtime', 'Status', 'Budget', 'Revenue', 
                    'Profit', 'ROI %', 'IMDB ID', 'Homepage', 'Overview'
                ]);
                
                // CSV Data
                foreach ($movies as $movie) {
                    fputcsv($file, [
                        $movie->id,
                        $movie->api_id,
                        $movie->title,
                        $movie->original_title,
                        $movie->original_language,
                        isset($movie->attributes['genre']) ? $movie->attributes['genre'] : $movie->genre,
                        $movie->release_date ? $movie->release_date->format('Y-m-d') : '',
                        $movie->release_year,
                        $movie->vote_average,
                        $movie->vote_count,
                        $movie->popularity,
                        $movie->runtime,
                        $movie->status,
                        $movie->budget,
                        $movie->revenue,
                        $movie->profit,
                        $movie->profit_percentage,
                        $movie->imdb_id,
                        $movie->homepage,
                        $movie->overview ? substr($movie->overview, 0, 500) : '',
                    ]);
                }
                
                fclose($file);
            };

            Log::info('CSV export initiated', ['count' => $movies->count()]);

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting CSV: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ========================================================================
     * STATISTICS
     * ========================================================================
     */

    /**
     * Get dashboard statistics
     */
    public function getStatistics()
    {
        try {
            // Basic counts
            $totalMovies = Movie::count();
            $totalReleased = Movie::where('status', 'Released')->count();
            $totalUpcoming = Movie::upcoming()->count();
            
            // Genre count
            $genresCount = DB::table('movies')
                ->select('genre')
                ->whereNotNull('genre')
                ->where('genre', '!=', '')
                ->distinct()
                ->get()
                ->pluck('genre')
                ->flatMap(function($g) {
                    return explode(',', $g);
                })
                ->map(function($g) {
                    return trim($g);
                })
                ->filter()
                ->unique()
                ->count();
            
            // Averages
            $averageRating = round(Movie::avg('vote_average') ?? 0, 2);
            $averageRuntime = round(Movie::avg('runtime') ?? 0, 0);
            
            // Financial totals
            $totalBudget = Movie::sum('budget') ?? 0;
            $totalRevenue = Movie::sum('revenue') ?? 0;
            $totalProfit = $totalRevenue - $totalBudget;
            
            // Top movies
            $topRatedMovies = Movie::topRated(5)->get();
            $topRated = [];
            foreach ($topRatedMovies as $movie) {
                $topRated[] = [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'vote_average' => $movie->vote_average,
                    'poster_url' => $movie->poster_url,
                ];
            }
            
            $mostPopularMovies = Movie::popular(5)->get();
            $mostPopular = [];
            foreach ($mostPopularMovies as $movie) {
                $mostPopular[] = [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'popularity' => $movie->popularity,
                    'poster_url' => $movie->poster_url,
                ];
            }
            
            $recentReleasesMovies = Movie::recent(5)->get();
            $recentReleases = [];
            foreach ($recentReleasesMovies as $movie) {
                $recentReleases[] = [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'release_date' => $movie->release_date ? $movie->release_date->format('Y-m-d') : null,
                    'poster_url' => $movie->poster_url,
                ];
            }

            $mostProfitableMovies = Movie::profitable()->limit(5)->get();
            $mostProfitable = [];
            foreach ($mostProfitableMovies as $movie) {
                $mostProfitable[] = [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'profit' => $movie->profit,
                    'formatted_profit' => $movie->formatted_profit,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'total_movies' => $totalMovies,
                    'total_released' => $totalReleased,
                    'total_upcoming' => $totalUpcoming,
                    'total_genres' => $genresCount,
                    'average_rating' => $averageRating,
                    'average_runtime' => $averageRuntime,
                    'total_budget' => $totalBudget,
                    'total_revenue' => $totalRevenue,
                    'total_profit' => $totalProfit,
                    'formatted_budget' => '$' . number_format($totalBudget / 1000000, 2) . 'M',
                    'formatted_revenue' => '$' . number_format($totalRevenue / 1000000, 2) . 'M',
                    'formatted_profit' => ($totalProfit >= 0 ? '+' : '') . '$' . number_format($totalProfit / 1000000, 2) . 'M',
                    'top_rated' => $topRated,
                    'most_popular' => $mostPopular,
                    'recent_releases' => $recentReleases,
                    'most_profitable' => $mostProfitable,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching statistics: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
