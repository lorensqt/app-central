<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the sitemap returns a successful XML response.
     */
    public function test_sitemap_returns_successful_xml_response(): void
    {
        // 1. Create a mock event manually consistent with workspace test convention
        $event = Event::create([
            'title' => 'Test Event',
            'description' => 'A public test event description.',
            'event_date' => now()->addDays(2),
            'location' => 'Main Hall',
        ]);

        // 2. Request the sitemap
        $response = $this->get('/sitemap.xml');

        // 3. Assert HTTP status and headers
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');

        // 4. Assert content contains public paths
        $response->assertSee(route('login'));
        $response->assertSee($event->public_url);
    }

    /**
     * Test that the sitemap caches its response and invalidates correctly.
     */
    public function test_sitemap_caches_and_invalidates_cache_on_event_changes(): void
    {
        // Assert cache is empty/fresh initially
        Cache::forget('sitemap_xml');

        // Request sitemap to populate cache
        $this->get('/sitemap.xml');

        // Assert sitemap XML is now cached
        $this->assertTrue(Cache::has('sitemap_xml'));

        // Trigger cache invalidation by creating a new Event manually
        Event::create([
            'title' => 'Another Event',
            'description' => 'Another public test event description.',
            'event_date' => now()->addDays(3),
            'location' => 'Secondary Hall',
        ]);

        // Assert cache is cleared after saving a new Event
        $this->assertFalse(Cache::has('sitemap_xml'));
    }
}
