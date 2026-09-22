<?php

namespace App\Providers;

use App\Models\Event;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Clear sitemap cache when events are created, updated, or deleted
        Event::saved(fn () => Cache::forget('sitemap_xml'));
        Event::deleted(fn () => Cache::forget('sitemap_xml'));
    }
}
