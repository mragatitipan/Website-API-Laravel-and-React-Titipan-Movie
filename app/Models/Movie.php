<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Movie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'api_id',
        'title',
        'original_title',
        'original_language',
        'overview',
        'tagline',
        'poster_path',
        'backdrop_path',
        'release_date',
        'genre',
        'vote_average',
        'vote_count',
        'popularity',
        'runtime',
        'status',
        'budget',
        'revenue',
        'production_companies',
        'production_countries',
        'spoken_languages',
        'adult',
        'video',
        'homepage',
        'imdb_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'release_date' => 'date',
        'vote_average' => 'float',
        'vote_count' => 'integer',
        'popularity' => 'float',
        'runtime' => 'integer',
        'budget' => 'integer',
        'revenue' => 'integer',
        'adult' => 'boolean',
        'video' => 'boolean',
        'api_id' => 'integer',
    ];

    // ===== ACCESSORS (COMPUTED ATTRIBUTES) =====
    
    /**
     * Get full poster URL from TMDB
     * 
     * @return string|null
     */
    public function getPosterUrlAttribute()
    {
        if (!$this->poster_path) {
            return null;
        }
        
        // If already full URL, return as is
        if (strpos($this->poster_path, 'http') === 0) {
            return $this->poster_path;
        }
        
        // Build TMDB image URL
        return 'https://image.tmdb.org/t/p/w500' . $this->poster_path;
    }

    /**
     * Get full backdrop URL from TMDB
     * 
     * @return string|null
     */
    public function getBackdropUrlAttribute()
    {
        if (!$this->backdrop_path) {
            return null;
        }
        
        // If already full URL, return as is
        if (strpos($this->backdrop_path, 'http') === 0) {
            return $this->backdrop_path;
        }
        
        // Build TMDB image URL (original quality for backdrop)
        return 'https://image.tmdb.org/t/p/original' . $this->backdrop_path;
    }

    /**
     * Get release year from release_date
     * 
     * @return string|null
     */
    public function getReleaseYearAttribute()
    {
        return $this->release_date ? $this->release_date->format('Y') : null;
    }

    /**
     * Get release month from release_date
     * 
     * @return string|null
     */
    public function getReleaseMonthAttribute()
    {
        return $this->release_date ? $this->release_date->format('m') : null;
    }

    /**
     * Get release month name from release_date
     * 
     * @return string|null
     */
    public function getReleaseMonthNameAttribute()
    {
        return $this->release_date ? $this->release_date->format('F') : null;
    }

    /**
     * Get genres as array (for React frontend)
     * Converts comma-separated string to array of objects
     * 
     * @return array
     */
    public function getGenresAttribute()
    {
        if (!$this->attributes['genre']) {
            return [];
        }
        
        return collect(explode(',', $this->attributes['genre']))
            ->map(function($name) {
                return trim($name);
            })
            ->filter()
            ->values()
            ->toArray();
    }

    /**
     * Get production companies as array
     * Parses JSON string to array
     * 
     * @return array
     */
    public function getProductionCompaniesArrayAttribute()
    {
        if (!$this->production_companies) {
            return [];
        }
        
        $decoded = json_decode($this->production_companies, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Get production countries as array
     * Parses JSON string to array
     * 
     * @return array
     */
    public function getProductionCountriesArrayAttribute()
    {
        if (!$this->production_countries) {
            return [];
        }
        
        $decoded = json_decode($this->production_countries, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Get spoken languages as array
     * Parses JSON string to array
     * 
     * @return array
     */
    public function getSpokenLanguagesArrayAttribute()
    {
        if (!$this->spoken_languages) {
            return [];
        }
        
        $decoded = json_decode($this->spoken_languages, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Format runtime to human-readable format (e.g., "2h 30m")
     * 
     * @return string
     */
    public function getFormattedRuntimeAttribute()
    {
        if (!$this->runtime || $this->runtime == 0) {
            return 'N/A';
        }
        
        $hours = floor($this->runtime / 60);
        $minutes = $this->runtime % 60;
        
        if ($hours > 0 && $minutes > 0) {
            return $hours . 'h ' . $minutes . 'm';
        } elseif ($hours > 0) {
            return $hours . 'h';
        } else {
            return $minutes . 'm';
        }
    }

    /**
     * Format budget to human-readable format
     * 
     * @return string
     */
    public function getFormattedBudgetAttribute()
    {
        if (!$this->budget || $this->budget == 0) {
            return 'N/A';
        }
        
        if ($this->budget >= 1000000000) {
            return '$' . number_format($this->budget / 1000000000, 2) . 'B';
        } elseif ($this->budget >= 1000000) {
            return '$' . number_format($this->budget / 1000000, 2) . 'M';
        } elseif ($this->budget >= 1000) {
            return '$' . number_format($this->budget / 1000, 2) . 'K';
        }
        
        return '$' . number_format($this->budget);
    }

    /**
     * Format revenue to human-readable format
     * 
     * @return string
     */
    public function getFormattedRevenueAttribute()
    {
        if (!$this->revenue || $this->revenue == 0) {
            return 'N/A';
        }
        
        if ($this->revenue >= 1000000000) {
            return '$' . number_format($this->revenue / 1000000000, 2) . 'B';
        } elseif ($this->revenue >= 1000000) {
            return '$' . number_format($this->revenue / 1000000, 2) . 'M';
        } elseif ($this->revenue >= 1000) {
            return '$' . number_format($this->revenue / 1000, 2) . 'K';
        }
        
        return '$' . number_format($this->revenue);
    }

    /**
     * Calculate profit (revenue - budget)
     * 
     * @return int
     */
    public function getProfitAttribute()
    {
        if (!$this->revenue || !$this->budget) {
            return 0;
        }
        return $this->revenue - $this->budget;
    }

    /**
     * Get formatted profit with +/- prefix
     * 
     * @return string
     */
    public function getFormattedProfitAttribute()
    {
        $profit = $this->profit;
        
        if ($profit == 0) {
            return 'N/A';
        }
        
        $prefix = $profit > 0 ? '+' : '';
        $absProfit = abs($profit);
        
        if ($absProfit >= 1000000000) {
            return $prefix . '$' . number_format($absProfit / 1000000000, 2) . 'B';
        } elseif ($absProfit >= 1000000) {
            return $prefix . '$' . number_format($absProfit / 1000000, 2) . 'M';
        } elseif ($absProfit >= 1000) {
            return $prefix . '$' . number_format($absProfit / 1000, 2) . 'K';
        }
        
        return $prefix . '$' . number_format($absProfit);
    }

    /**
     * Calculate profit percentage (ROI)
     * 
     * @return float
     */
    public function getProfitPercentageAttribute()
    {
        if (!$this->budget || $this->budget == 0) {
            return 0;
        }
        return round(($this->profit / $this->budget) * 100, 2);
    }

    /**
     * Get IMDB URL
     * 
     * @return string|null
     */
    public function getImdbUrlAttribute()
    {
        if (!$this->imdb_id) {
            return null;
        }
        return 'https://www.imdb.com/title/' . $this->imdb_id;
    }

    /**
     * Get TMDB URL
     * 
     * @return string|null
     */
    public function getTmdbUrlAttribute()
    {
        if (!$this->api_id) {
            return null;
        }
        return 'https://www.themoviedb.org/movie/' . $this->api_id;
    }

    /**
     * Get rating color class for Bootstrap badges
     * 
     * @return string
     */
    public function getRatingColorAttribute()
    {
        if (!$this->vote_average) {
            return 'secondary';
        }
        
        if ($this->vote_average >= 8) {
            return 'success';
        } elseif ($this->vote_average >= 6) {
            return 'warning';
        } elseif ($this->vote_average >= 4) {
            return 'info';
        } else {
            return 'danger';
        }
    }

    /**
     * Get status badge color for Bootstrap
     * 
     * @return string
     */
    public function getStatusColorAttribute()
    {
        $status = strtolower($this->status ?? '');
        
        switch ($status) {
            case 'released':
                return 'success';
            case 'post production':
                return 'info';
            case 'in production':
                return 'warning';
            case 'planned':
                return 'secondary';
            case 'rumored':
                return 'light';
            case 'canceled':
            case 'cancelled':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    // ===== QUERY SCOPES =====

    /**
     * Scope: Search by title, original title, or overview
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }
        
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('original_title', 'like', "%{$search}%")
              ->orWhere('overview', 'like', "%{$search}%");
        });
    }

    /**
     * Scope: Filter by genre (comma-separated string)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $genre
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByGenre($query, $genre)
    {
        if (!$genre) {
            return $query;
        }
        
        // Search for genre in comma-separated string
        return $query->where('genre', 'like', "%{$genre}%");
    }

    /**
     * Scope: Filter by release year
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $year
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByYear($query, $year)
    {
        if (!$year) {
            return $query;
        }
        
        return $query->whereYear('release_date', $year);
    }

    /**
     * Scope: Filter by release month
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $month
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByMonth($query, $month)
    {
        if (!$month) {
            return $query;
        }
        
        return $query->whereMonth('release_date', $month);
    }

    /**
     * Scope: Filter by date range
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $from
     * @param string|null $to
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateRange($query, $from = null, $to = null)
    {
        if ($from) {
            $query->where('release_date', '>=', $from);
        }
        if ($to) {
            $query->where('release_date', '<=', $to);
        }
        return $query;
    }

    /**
     * Scope: Filter by minimum rating
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $rating
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMinRating($query, $rating)
    {
        if (!$rating) {
            return $query;
        }
        
        return $query->where('vote_average', '>=', $rating);
    }

    /**
     * Scope: Filter by status
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        if (!$status) {
            return $query;
        }
        
        return $query->where('status', $status);
    }

    /**
     * Scope: Get popular movies (sorted by popularity)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->orderBy('popularity', 'desc')->limit($limit);
    }

    /**
     * Scope: Get top rated movies (with minimum vote count)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTopRated($query, $limit = 10)
    {
        return $query->where('vote_count', '>', 100)
                     ->orderBy('vote_average', 'desc')
                     ->limit($limit);
    }

    /**
     * Scope: Get recent releases (sorted by release date)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('release_date', 'desc')->limit($limit);
    }

    /**
     * Scope: Get recently added movies (sorted by created_at)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecentlyAdded($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Scope: Get recently updated movies (sorted by updated_at)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecentlyUpdated($query, $limit = 10)
    {
        return $query->orderBy('updated_at', 'desc')->limit($limit);
    }

    /**
     * Scope: Get high budget movies
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $minBudget
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHighBudget($query, $minBudget = 100000000)
    {
        return $query->where('budget', '>=', $minBudget)
                     ->orderBy('budget', 'desc');
    }

    /**
     * Scope: Get profitable movies (revenue > budget)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeProfitable($query)
    {
        return $query->whereNotNull('budget')
                     ->whereNotNull('revenue')
                     ->whereRaw('revenue > budget')
                     ->orderByRaw('(revenue - budget) DESC');
    }

    /**
     * Scope: Get movies with losses (revenue < budget)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnprofitable($query)
    {
        return $query->whereNotNull('budget')
                     ->whereNotNull('revenue')
                     ->whereRaw('revenue < budget')
                     ->orderByRaw('(budget - revenue) DESC');
    }

    /**
     * Scope: Get adult content only
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdultOnly($query)
    {
        return $query->where('adult', true);
    }

    /**
     * Scope: Get family friendly content only
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFamilyFriendly($query)
    {
        return $query->where('adult', false);
    }

    /**
     * Scope: Get movies with video available
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithVideo($query)
    {
        return $query->where('video', true);
    }

    /**
     * Scope: Get movies by language
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $language
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByLanguage($query, $language)
    {
        if (!$language) {
            return $query;
        }
        
        return $query->where('original_language', $language);
    }

    /**
     * Scope: Get trending movies (recent + popular)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTrending($query, $limit = 10)
    {
        return $query->where('release_date', '>=', Carbon::now()->subMonths(6))
                     ->orderBy('popularity', 'desc')
                     ->limit($limit);
    }

    /**
     * Scope: Get upcoming movies (future release dates)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->where('release_date', '>', Carbon::now())
                     ->orderBy('release_date', 'asc');
    }

    /**
     * Scope: Get now playing movies (released in last 3 months)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNowPlaying($query)
    {
        return $query->whereBetween('release_date', [
                         Carbon::now()->subMonths(3),
                         Carbon::now()
                     ])
                     ->orderBy('release_date', 'desc');
    }

    // ===== HELPER METHODS =====

    /**
     * Check if movie is released
     * 
     * @return bool
     */
    public function isReleased()
    {
        return $this->release_date && $this->release_date->isPast();
    }

    /**
     * Check if movie is upcoming
     * 
     * @return bool
     */
    public function isUpcoming()
    {
        return $this->release_date && $this->release_date->isFuture();
    }

    /**
     * Check if movie is profitable
     * 
     * @return bool
     */
    public function isProfitable()
    {
        return $this->profit > 0;
    }

    /**
     * Get age of movie in years
     * 
     * @return int|null
     */
    public function getAge()
    {
        if (!$this->release_date) {
            return null;
        }
        
        return Carbon::now()->diffInYears($this->release_date);
    }

    /**
     * Get days until release (for upcoming movies)
     * 
     * @return int|null
     */
    public function getDaysUntilRelease()
    {
        if (!$this->release_date || $this->isReleased()) {
            return null;
        }
        
        return Carbon::now()->diffInDays($this->release_date);
    }
}
