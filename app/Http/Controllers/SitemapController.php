<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Generate the XML sitemap.
     */
    public function index(): Response
    {
        // Cache the XML output for 24 hours to prevent DB overhead from search engine crawlers
        $sitemapXml = Cache::remember('sitemap_xml', now()->addDay(), function () {
            $urls = [];

            // 1. Add static landing / login page
            $urls[] = [
                'loc' => route('login'),
                'lastmod' => now()->startOfDay()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ];

            // 2. Fetch all public events and map them dynamically
            // Note: Update to Atom format string for SEO compatibility
            $events = Event::latest()->get();

            foreach ($events as $event) {
                $urls[] = [
                    'loc' => $event->public_url,
                    'lastmod' => $event->updated_at->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            }

            return view('sitemap', compact('urls'))->render();
        });

        return response($sitemapXml, 200)
            ->header('Content-Type', 'text/xml');
    }
}
