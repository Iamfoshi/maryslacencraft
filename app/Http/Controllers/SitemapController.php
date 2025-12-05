<?php

namespace App\Http\Controllers;

use App\Models\SeoSetting;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate sitemap.xml
     */
    public function sitemap(): Response
    {
        $urls = [
            [
                'loc' => config('app.url'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '1.0',
            ],
            [
                'loc' => config('app.url') . '/#about',
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => config('app.url') . '/#products',
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ],
            [
                'loc' => config('app.url') . '/#gallery',
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ],
            [
                'loc' => config('app.url') . '/#contact',
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * Generate robots.txt
     */
    public function robots(): Response
    {
        $seo = SeoSetting::getActive();
        
        if ($seo && $seo->robots_txt) {
            $content = $seo->robots_txt;
        } else {
            $content = "User-agent: *\n";
            $content .= "Allow: /\n";
            $content .= "Disallow: /admin/\n";
            $content .= "Disallow: /api/\n";
            $content .= "\n";
            $content .= "Sitemap: " . config('app.url') . "/sitemap.xml\n";
        }

        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
