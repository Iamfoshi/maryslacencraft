<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't track admin panel visits, API calls, or asset requests
        if ($this->shouldTrack($request)) {
            $this->trackVisit($request);
        }

        return $next($request);
    }

    /**
     * Determine if this request should be tracked
     */
    protected function shouldTrack(Request $request): bool
    {
        // Only track GET requests to actual pages
        if ($request->method() !== 'GET') {
            return false;
        }

        $path = $request->path();
        
        // Don't track these paths
        $excludedPaths = [
            'admin',
            'livewire',
            'api',
            '_debugbar',
            'sanctum',
            'storage',
            'build',
            'favicon.ico',
            'vendor',
        ];

        foreach ($excludedPaths as $excluded) {
            if (str_starts_with($path, $excluded)) {
                return false;
            }
        }

        // Don't track bots
        $userAgent = strtolower($request->userAgent() ?? '');
        $bots = ['bot', 'crawler', 'spider', 'slurp', 'googlebot', 'bingbot'];
        
        foreach ($bots as $bot) {
            if (str_contains($userAgent, $bot)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Track the visit
     */
    protected function trackVisit(Request $request): void
    {
        try {
            $userAgent = $request->userAgent() ?? '';
            
            Visitor::create([
                'ip_address' => $request->ip(),
                'user_agent' => substr($userAgent, 0, 255),
                'page_visited' => '/' . $request->path(),
                'referrer' => $request->header('referer') ? substr($request->header('referer'), 0, 255) : null,
                'device_type' => $this->getDeviceType($userAgent),
                'browser' => $this->getBrowser($userAgent),
                'os' => $this->getOS($userAgent),
                'visit_date' => now()->toDateString(),
            ]);
        } catch (\Exception $e) {
            // Silently fail - don't break the site if tracking fails
            \Log::error('Visitor tracking failed: ' . $e->getMessage());
        }
    }

    /**
     * Detect device type from user agent
     */
    protected function getDeviceType(string $userAgent): string
    {
        $userAgent = strtolower($userAgent);
        
        if (preg_match('/mobile|android|iphone|ipod|blackberry|windows phone/i', $userAgent)) {
            return 'Mobile';
        }
        
        if (preg_match('/tablet|ipad|kindle|silk/i', $userAgent)) {
            return 'Tablet';
        }
        
        return 'Desktop';
    }

    /**
     * Detect browser from user agent
     */
    protected function getBrowser(string $userAgent): string
    {
        if (preg_match('/edge|edg/i', $userAgent)) return 'Edge';
        if (preg_match('/chrome/i', $userAgent)) return 'Chrome';
        if (preg_match('/safari/i', $userAgent) && !preg_match('/chrome/i', $userAgent)) return 'Safari';
        if (preg_match('/firefox/i', $userAgent)) return 'Firefox';
        if (preg_match('/msie|trident/i', $userAgent)) return 'IE';
        if (preg_match('/opera|opr/i', $userAgent)) return 'Opera';
        
        return 'Other';
    }

    /**
     * Detect OS from user agent
     */
    protected function getOS(string $userAgent): string
    {
        if (preg_match('/windows/i', $userAgent)) return 'Windows';
        if (preg_match('/macintosh|mac os/i', $userAgent)) return 'macOS';
        if (preg_match('/linux/i', $userAgent)) return 'Linux';
        if (preg_match('/android/i', $userAgent)) return 'Android';
        if (preg_match('/iphone|ipad|ipod/i', $userAgent)) return 'iOS';
        
        return 'Other';
    }
}
