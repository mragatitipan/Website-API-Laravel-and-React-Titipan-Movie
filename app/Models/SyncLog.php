<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'synced_at',
        'records_fetched',
        'records_created',
        'records_updated',
        'records_failed',
        'status',
        'message',
        'error_details',
        'sync_type',
        'duration',
    ];

    protected $casts = [
        'synced_at' => 'datetime',
        'records_fetched' => 'integer',
        'records_created' => 'integer',
        'records_updated' => 'integer',
        'records_failed' => 'integer',
        'duration' => 'integer',
    ];

    // ===== ACCESSORS =====

    /**
     * Get error details as array
     */
    public function getErrorDetailsArrayAttribute()
    {
        if (!$this->error_details) {
            return [];
        }
        $decoded = json_decode($this->error_details, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration || $this->duration == 0) {
            return 'N/A';
        }
        
        if ($this->duration < 60) {
            return $this->duration . 's';
        }
        
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        
        if ($minutes > 0 && $seconds > 0) {
            return $minutes . 'm ' . $seconds . 's';
        } elseif ($minutes > 0) {
            return $minutes . 'm';
        } else {
            return $seconds . 's';
        }
    }

    /**
     * Get success rate percentage
     */
    public function getSuccessRateAttribute()
    {
        if ($this->records_fetched == 0) {
            return 0;
        }
        
        $successful = $this->records_created + $this->records_updated;
        return round(($successful / $this->records_fetched) * 100, 2);
    }

    /**
     * Get total processed records
     */
    public function getTotalProcessedAttribute()
    {
        return $this->records_created + $this->records_updated;
    }

    /**
     * Get status badge color (Bootstrap)
     */
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'success':
                return 'success';
            case 'partial':
                return 'warning';
            case 'failed':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    /**
     * Get status icon (FontAwesome)
     */
    public function getStatusIconAttribute()
    {
        switch ($this->status) {
            case 'success':
                return 'fa-check-circle';
            case 'partial':
                return 'fa-exclamation-triangle';
            case 'failed':
                return 'fa-times-circle';
            default:
                return 'fa-question-circle';
        }
    }

    /**
     * Get sync type badge color (Bootstrap)
     */
    public function getSyncTypeColorAttribute()
    {
        switch ($this->sync_type) {
            case 'manual':
                return 'primary';
            case 'scheduled':
                return 'info';
            case 'auto':
                return 'secondary';
            default:
                return 'secondary';
        }
    }

    /**
     * Check if sync was successful
     */
    public function getIsSuccessfulAttribute()
    {
        return $this->status === 'success';
    }

    /**
     * Check if sync failed
     */
    public function getIsFailedAttribute()
    {
        return $this->status === 'failed';
    }

    /**
     * Check if sync was partial
     */
    public function getIsPartialAttribute()
    {
        return $this->status === 'partial';
    }

    /**
     * Get human readable time ago
     */
    public function getTimeAgoAttribute()
    {
        return $this->synced_at ? $this->synced_at->diffForHumans() : 'Never';
    }

    // ===== SCOPES =====

    /**
     * Scope: Get last sync
     */
    public function scopeLastSync($query)
    {
        return $query->latest('synced_at')->first();
    }

    /**
     * Scope: Get last successful sync
     */
    public function scopeLastSuccess($query)
    {
        return $query->where('status', 'success')
                     ->latest('synced_at')
                     ->first();
    }

    /**
     * Scope: Recent logs
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('synced_at', 'desc')->limit($limit);
    }

    /**
     * Scope: Successful syncs
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope: Failed syncs
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope: Partial syncs
     */
    public function scopePartial($query)
    {
        return $query->where('status', 'partial');
    }

    /**
     * Scope: By sync type
     */
    public function scopeBySyncType($query, $type)
    {
        return $query->where('sync_type', $type);
    }

    /**
     * Scope: Manual syncs
     */
    public function scopeManual($query)
    {
        return $query->where('sync_type', 'manual');
    }

    /**
     * Scope: Scheduled syncs
     */
    public function scopeScheduled($query)
    {
        return $query->where('sync_type', 'scheduled');
    }

    /**
     * Scope: Auto syncs
     */
    public function scopeAuto($query)
    {
        return $query->where('sync_type', 'auto');
    }

    /**
     * Scope: Today's syncs
     */
    public function scopeToday($query)
    {
        return $query->whereDate('synced_at', today());
    }

    /**
     * Scope: This week's syncs
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('synced_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope: This month's syncs
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('synced_at', now()->month)
                     ->whereYear('synced_at', now()->year);
    }

    // ===== STATIC METHODS =====

    /**
     * Get sync statistics
     */
    public static function getStatistics()
    {
        return [
            'total_syncs' => self::count(),
            'successful_syncs' => self::successful()->count(),
            'failed_syncs' => self::failed()->count(),
            'partial_syncs' => self::partial()->count(),
            'total_records_fetched' => self::sum('records_fetched'),
            'total_records_created' => self::sum('records_created'),
            'total_records_updated' => self::sum('records_updated'),
            'total_records_failed' => self::sum('records_failed'),
            'average_duration' => round(self::avg('duration'), 2),
            'last_sync' => self::lastSync(),
            'last_successful_sync' => self::lastSuccess(),
        ];
    }
}
