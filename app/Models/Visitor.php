<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Visitor extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'page_visited',
        'referrer',
        'country',
        'city',
        'device_type',
        'browser',
        'os',
        'visit_date',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    /**
     * Get total visitors count
     */
    public static function getTotalVisitors(): int
    {
        return self::count();
    }

    /**
     * Get unique visitors count (by IP)
     */
    public static function getUniqueVisitors(): int
    {
        return self::distinct('ip_address')->count('ip_address');
    }

    /**
     * Get today's visitors
     */
    public static function getTodayVisitors(): int
    {
        return self::whereDate('visit_date', today())->count();
    }

    /**
     * Get today's unique visitors
     */
    public static function getTodayUniqueVisitors(): int
    {
        return self::whereDate('visit_date', today())
            ->distinct('ip_address')
            ->count('ip_address');
    }

    /**
     * Get this week's visitors
     */
    public static function getThisWeekVisitors(): int
    {
        return self::whereBetween('visit_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
    }

    /**
     * Get this month's visitors
     */
    public static function getThisMonthVisitors(): int
    {
        return self::whereMonth('visit_date', now()->month)
            ->whereYear('visit_date', now()->year)
            ->count();
    }

    /**
     * Get visitors by day for the last N days
     */
    public static function getVisitorsByDay(int $days = 7): array
    {
        return self::select(DB::raw('DATE(visit_date) as date'), DB::raw('COUNT(*) as count'))
            ->where('visit_date', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();
    }

    /**
     * Get top pages
     */
    public static function getTopPages(int $limit = 5): array
    {
        return self::select('page_visited', DB::raw('COUNT(*) as count'))
            ->groupBy('page_visited')
            ->orderByDesc('count')
            ->limit($limit)
            ->pluck('count', 'page_visited')
            ->toArray();
    }

    /**
     * Get device breakdown
     */
    public static function getDeviceBreakdown(): array
    {
        return self::select('device_type', DB::raw('COUNT(*) as count'))
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->pluck('count', 'device_type')
            ->toArray();
    }

    /**
     * Get browser breakdown
     */
    public static function getBrowserBreakdown(): array
    {
        return self::select('browser', DB::raw('COUNT(*) as count'))
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderByDesc('count')
            ->limit(5)
            ->pluck('count', 'browser')
            ->toArray();
    }
}
