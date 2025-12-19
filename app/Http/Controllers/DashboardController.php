<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\SyncLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show dashboard page
     */
    public function index()
    {
        return view('dashboard.index');
    }

    /**
     * Get dashboard statistics
     */
    public function getStatistics()
    {
        // Basic statistics
        $totalMovies = Movie::count();
        $totalGenres = $this->getTotalGenres();
        $averageRating = Movie::avg('vote_average');
        $totalVotes = Movie::sum('vote_count');
        
        // Financial statistics
        $totalBudget = Movie::sum('budget');
        $totalRevenue = Movie::sum('revenue');
        $totalProfit = $totalRevenue - $totalBudget;
        $profitMargin = $totalBudget > 0 ? round(($totalProfit / $totalBudget) * 100, 2) : 0;
        
        // Latest movie
        $latestMovie = Movie::latest('release_date')->first();
        $latestMovieData = null;
        if ($latestMovie) {
            $latestMovieData = [
                'id' => $latestMovie->id,
                'title' => $latestMovie->title,
                'release_date' => $latestMovie->release_date->format('Y-m-d'),
                'release_year' => $latestMovie->release_year,
                'poster_url' => $latestMovie->poster_url,
                'vote_average' => $latestMovie->vote_average,
                'genre' => $latestMovie->genre,
            ];
        }
        
        // Highest rated movie
        $highestRated = Movie::orderBy('vote_average', 'desc')
            ->orderBy('vote_count', 'desc')
            ->first();
        $highestRatedData = null;
        if ($highestRated) {
            $highestRatedData = [
                'id' => $highestRated->id,
                'title' => $highestRated->title,
                'vote_average' => $highestRated->vote_average,
                'vote_count' => $highestRated->vote_count,
                'poster_url' => $highestRated->poster_url,
                'rating_color' => $highestRated->rating_color,
            ];
        }
        
        // Most popular movie
        $mostPopular = Movie::orderBy('popularity', 'desc')->first();
        $mostPopularData = null;
        if ($mostPopular) {
            $mostPopularData = [
                'id' => $mostPopular->id,
                'title' => $mostPopular->title,
                'popularity' => $mostPopular->popularity,
                'poster_url' => $mostPopular->poster_url,
                'vote_average' => $mostPopular->vote_average,
            ];
        }
        
        // Highest grossing movie
        $highestGrossing = Movie::orderBy('revenue', 'desc')->first();
        $highestGrossingData = null;
        if ($highestGrossing) {
            $highestGrossingData = [
                'id' => $highestGrossing->id,
                'title' => $highestGrossing->title,
                'revenue' => $highestGrossing->revenue,
                'formatted_revenue' => $highestGrossing->formatted_revenue,
                'poster_url' => $highestGrossing->poster_url,
                'profit' => $highestGrossing->profit,
                'formatted_profit' => $highestGrossing->formatted_profit,
            ];
        }
        
        // Last sync info
        $lastSync = SyncLog::latest('synced_at')->first();
        $lastSyncData = null;
        if ($lastSync) {
            $lastSyncData = [
                'id' => $lastSync->id,
                'synced_at' => $lastSync->synced_at->toDateTimeString(),
                'time_ago' => $lastSync->time_ago,
                'status' => $lastSync->status,
                'status_color' => $lastSync->status_color,
                'status_icon' => $lastSync->status_icon,
                'records_created' => $lastSync->records_created,
                'records_updated' => $lastSync->records_updated,
                'records_failed' => $lastSync->records_failed,
                'success_rate' => $lastSync->success_rate,
                'duration' => $lastSync->duration,
                'formatted_duration' => $lastSync->formatted_duration,
            ];
        }
        
        // Sync statistics
        $syncStats = SyncLog::getStatistics();
        
        // Movies by status
        $moviesByStatus = Movie::select('status', DB::raw('COUNT(*) as count'))
            ->whereNotNull('status')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        
        // Movies by language
        $moviesByLanguage = Movie::select('original_language', DB::raw('COUNT(*) as count'))
            ->whereNotNull('original_language')
            ->groupBy('original_language')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        $stats = [
            // Basic stats
            'total_movies' => $totalMovies,
            'total_genres' => $totalGenres,
            'average_rating' => round($averageRating, 2),
            'total_votes' => $totalVotes,
            
            // Financial stats
            'total_budget' => $totalBudget,
            'formatted_budget' => $this->formatCurrency($totalBudget),
            'total_revenue' => $totalRevenue,
            'formatted_revenue' => $this->formatCurrency($totalRevenue),
            'total_profit' => $totalProfit,
            'formatted_profit' => $this->formatCurrency($totalProfit),
            'profit_margin' => $profitMargin,
            
            // Featured movies
            'latest_movie' => $latestMovieData,
            'highest_rated' => $highestRatedData,
            'most_popular' => $mostPopularData,
            'highest_grossing' => $highestGrossingData,
            
            // Sync info
            'last_sync' => $lastSyncData,
            'sync_statistics' => $syncStats,
            
            // Distribution
            'movies_by_status' => $moviesByStatus,
            'movies_by_language' => $moviesByLanguage,
            
            // Additional stats
            'movies_with_budget' => Movie::whereNotNull('budget')->where('budget', '>', 0)->count(),
            'movies_with_revenue' => Movie::whereNotNull('revenue')->where('revenue', '>', 0)->count(),
            'adult_movies' => Movie::where('adult', true)->count(),
            'movies_this_year' => Movie::whereYear('release_date', date('Y'))->count(),
            'movies_this_month' => Movie::whereYear('release_date', date('Y'))
                ->whereMonth('release_date', date('m'))
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get movies by genre (for Pie Chart)
     */
    public function getMoviesByGenre()
    {
        $movies = Movie::select('genre')->get();
        
        $genreCounts = [];
        
        foreach ($movies as $movie) {
            $genres = explode(', ', $movie->genre);
            foreach ($genres as $genre) {
                $genre = trim($genre);
                if (!isset($genreCounts[$genre])) {
                    $genreCounts[$genre] = 0;
                }
                $genreCounts[$genre]++;
            }
        }

        // Sort by count descending
        arsort($genreCounts);

        // Format for chart
        $chartData = [];
        foreach ($genreCounts as $genre => $count) {
            $chartData[] = [
                'genre' => $genre,
                'count' => $count,
                'percentage' => round(($count / Movie::count()) * 100, 2)
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }

    /**
     * Get movies by release date (for Column Chart)
     */
    public function getMoviesByDate(Request $request)
    {
        // Default: last 12 months
        $months = $request->get('months', 12);
        $startDate = Carbon::now()->subMonths($months)->startOfMonth();
        
        $moviesByMonth = Movie::select(
                DB::raw('DATE_FORMAT(release_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('release_date', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Format for chart
        $chartData = [];
        foreach ($moviesByMonth as $item) {
            $chartData[] = [
                'month' => Carbon::createFromFormat('Y-m', $item->month)->format('M Y'),
                'count' => $item->count,
                'year_month' => $item->month
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }

    /**
     * Get movies by year (alternative for Column Chart)
     */
    public function getMoviesByYear(Request $request)
    {
        $years = $request->get('years', 10);
        $startYear = Carbon::now()->subYears($years)->year;
        
        $moviesByYear = Movie::select(
                DB::raw('YEAR(release_date) as year'),
                DB::raw('COUNT(*) as count'),
                DB::raw('AVG(vote_average) as avg_rating'),
                DB::raw('SUM(budget) as total_budget'),
                DB::raw('SUM(revenue) as total_revenue')
            )
            ->whereRaw('YEAR(release_date) >= ?', [$startYear])
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        $chartData = [];
        foreach ($moviesByYear as $item) {
            $chartData[] = [
                'year' => $item->year,
                'count' => $item->count,
                'avg_rating' => round($item->avg_rating, 2),
                'total_budget' => $item->total_budget,
                'total_revenue' => $item->total_revenue,
                'profit' => $item->total_revenue - $item->total_budget,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }

    /**
     * Get top rated movies
     */
    public function getTopRated(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        $topRated = Movie::orderBy('vote_average', 'desc')
            ->orderBy('vote_count', 'desc')
            ->limit($limit)
            ->get();

        $data = [];
        foreach ($topRated as $movie) {
            $data[] = [
                'id' => $movie->id,
                'title' => $movie->title,
                'poster_url' => $movie->poster_url,
                'vote_average' => $movie->vote_average,
                'vote_count' => $movie->vote_count,
                'rating_color' => $movie->rating_color,
                'release_year' => $movie->release_year,
                'genre' => $movie->genre,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get most popular movies
     */
    public function getMostPopular(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        $mostPopular = Movie::orderBy('popularity', 'desc')
            ->limit($limit)
            ->get();

        $data = [];
        foreach ($mostPopular as $movie) {
            $data[] = [
                'id' => $movie->id,
                'title' => $movie->title,
                'poster_url' => $movie->poster_url,
                'popularity' => $movie->popularity,
                'vote_average' => $movie->vote_average,
                'release_year' => $movie->release_year,
                'genre' => $movie->genre,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get recent movies
     */
    public function getRecentMovies(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        $recentMovies = Movie::orderBy('release_date', 'desc')
            ->limit($limit)
            ->get();

        $data = [];
        foreach ($recentMovies as $movie) {
            $data[] = [
                'id' => $movie->id,
                'title' => $movie->title,
                'poster_url' => $movie->poster_url,
                'backdrop_url' => $movie->backdrop_url,
                'release_date' => $movie->release_date->format('Y-m-d'),
                'release_year' => $movie->release_year,
                'vote_average' => $movie->vote_average,
                'genre' => $movie->genre,
                'overview' => substr($movie->overview, 0, 150) . '...',
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get highest grossing movies
     */
    public function getHighestGrossing(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        $highestGrossing = Movie::whereNotNull('revenue')
            ->where('revenue', '>', 0)
            ->orderBy('revenue', 'desc')
            ->limit($limit)
            ->get();

        $data = [];
        foreach ($highestGrossing as $movie) {
            $data[] = [
                'id' => $movie->id,
                'title' => $movie->title,
                'poster_url' => $movie->poster_url,
                'revenue' => $movie->revenue,
                'formatted_revenue' => $movie->formatted_revenue,
                'budget' => $movie->budget,
                'formatted_budget' => $movie->formatted_budget,
                'profit' => $movie->profit,
                'formatted_profit' => $movie->formatted_profit,
                'profit_percentage' => $movie->profit_percentage,
                'release_year' => $movie->release_year,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get movies statistics by genre
     */
    public function getGenreStatistics()
    {
        $movies = Movie::all();
        
        $genreStats = [];
        
        foreach ($movies as $movie) {
            $genres = explode(', ', $movie->genre);
            foreach ($genres as $genre) {
                $genre = trim($genre);
                if (!isset($genreStats[$genre])) {
                    $genreStats[$genre] = [
                        'genre' => $genre,
                        'count' => 0,
                        'avg_rating' => 0,
                        'total_votes' => 0,
                        'ratings_sum' => 0,
                        'total_budget' => 0,
                        'total_revenue' => 0,
                    ];
                }
                $genreStats[$genre]['count']++;
                $genreStats[$genre]['ratings_sum'] += $movie->vote_average;
                $genreStats[$genre]['total_votes'] += $movie->vote_count;
                $genreStats[$genre]['total_budget'] += $movie->budget ?? 0;
                $genreStats[$genre]['total_revenue'] += $movie->revenue ?? 0;
            }
        }

        // Calculate averages and format
        $formattedStats = [];
        foreach ($genreStats as $stat) {
            $avgRating = round($stat['ratings_sum'] / $stat['count'], 2);
            $profit = $stat['total_revenue'] - $stat['total_budget'];
            
            $formattedStats[] = [
                'genre' => $stat['genre'],
                'count' => $stat['count'],
                'avg_rating' => $avgRating,
                'total_votes' => $stat['total_votes'],
                'total_budget' => $stat['total_budget'],
                'formatted_budget' => $this->formatCurrency($stat['total_budget']),
                'total_revenue' => $stat['total_revenue'],
                'formatted_revenue' => $this->formatCurrency($stat['total_revenue']),
                'profit' => $profit,
                'formatted_profit' => $this->formatCurrency($profit),
            ];
        }

        // Sort by count
        usort($formattedStats, function($a, $b) {
            return $b['count'] - $a['count'];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedStats
        ]);
    }

    /**
     * Get rating distribution
     */
    public function getRatingDistribution()
    {
        $distribution = [
            '0-1' => Movie::whereBetween('vote_average', [0, 1])->count(),
            '1-2' => Movie::whereBetween('vote_average', [1, 2])->count(),
            '2-3' => Movie::whereBetween('vote_average', [2, 3])->count(),
            '3-4' => Movie::whereBetween('vote_average', [3, 4])->count(),
            '4-5' => Movie::whereBetween('vote_average', [4, 5])->count(),
            '5-6' => Movie::whereBetween('vote_average', [5, 6])->count(),
            '6-7' => Movie::whereBetween('vote_average', [6, 7])->count(),
            '7-8' => Movie::whereBetween('vote_average', [7, 8])->count(),
            '8-9' => Movie::whereBetween('vote_average', [8, 9])->count(),
            '9-10' => Movie::whereBetween('vote_average', [9, 10])->count(),
        ];

        $chartData = [];
        foreach ($distribution as $range => $count) {
            $chartData[] = [
                'range' => $range,
                'count' => $count
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }

    /**
     * Get budget vs revenue comparison
     */
    public function getBudgetVsRevenue(Request $request)
    {
        $limit = $request->get('limit', 20);
        
        $movies = Movie::whereNotNull('budget')
            ->whereNotNull('revenue')
            ->where('budget', '>', 0)
            ->where('revenue', '>', 0)
            ->orderBy('revenue', 'desc')
            ->limit($limit)
            ->get();

        $chartData = [];
        foreach ($movies as $movie) {
            $chartData[] = [
                'title' => $movie->title,
                'budget' => $movie->budget,
                'revenue' => $movie->revenue,
                'profit' => $movie->profit,
                'profit_percentage' => $movie->profit_percentage,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }

    /**
     * Get movies by status
     */
    public function getMoviesByStatus()
    {
        $moviesByStatus = Movie::select('status', DB::raw('COUNT(*) as count'))
            ->whereNotNull('status')
            ->groupBy('status')
            ->orderBy('count', 'desc')
            ->get();

        $chartData = [];
        foreach ($moviesByStatus as $item) {
            $chartData[] = [
                'status' => $item->status,
                'count' => $item->count
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }

    /**
     * Get movies by language
     */
    public function getMoviesByLanguage(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        $moviesByLanguage = Movie::select('original_language', DB::raw('COUNT(*) as count'))
            ->whereNotNull('original_language')
            ->groupBy('original_language')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();

        $chartData = [];
        foreach ($moviesByLanguage as $item) {
            $chartData[] = [
                'language' => $item->original_language,
                'count' => $item->count
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }

    /**
     * Helper: Get total unique genres
     */
    private function getTotalGenres()
    {
        $movies = Movie::select('genre')->get();
        $uniqueGenres = [];
        
        foreach ($movies as $movie) {
            $genres = explode(', ', $movie->genre);
            foreach ($genres as $genre) {
                $uniqueGenres[trim($genre)] = true;
            }
        }
        
        return count($uniqueGenres);
    }

    /**
     * Helper: Format currency
     */
    private function formatCurrency($amount)
    {
        if ($amount >= 1000000000) {
            return '$' . number_format($amount / 1000000000, 2) . 'B';
        } elseif ($amount >= 1000000) {
            return '$' . number_format($amount / 1000000, 2) . 'M';
        } elseif ($amount >= 1000) {
            return '$' . number_format($amount / 1000, 2) . 'K';
        } else {
            return '$' . number_format($amount, 2);
        }
    }

    /**
     * Get complete dashboard data (all in one)
     */
    public function getDashboardData(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'statistics' => $this->getStatistics()->getData()->data,
                'movies_by_genre' => $this->getMoviesByGenre()->getData()->data,
                'movies_by_date' => $this->getMoviesByDate($request)->getData()->data,
                'movies_by_year' => $this->getMoviesByYear($request)->getData()->data,
                'top_rated' => $this->getTopRated($request)->getData()->data,
                'most_popular' => $this->getMostPopular($request)->getData()->data,
                'recent_movies' => $this->getRecentMovies($request)->getData()->data,
                'highest_grossing' => $this->getHighestGrossing($request)->getData()->data,
                'genre_statistics' => $this->getGenreStatistics()->getData()->data,
                'rating_distribution' => $this->getRatingDistribution()->getData()->data,
                'budget_vs_revenue' => $this->getBudgetVsRevenue($request)->getData()->data,
                'movies_by_status' => $this->getMoviesByStatus()->getData()->data,
                'movies_by_language' => $this->getMoviesByLanguage($request)->getData()->data,
            ]
        ]);
    }
}
