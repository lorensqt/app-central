<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Committee;
use App\Models\Event;
use App\Models\Title;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Display the unified system administration cockpit.
     */
    public function index()
    {
        $users = User::with('title')->get();
        $titles = Title::withCount('users')->latest()->get();
        $committees = Committee::latest()->get();
        $events = Event::with(['committee', 'registrations'])->latest()->get();

        // Group titles by their respective corporate groups for easy dropdown grouping
        $groupedTitles = $titles->groupBy('group');

        return view('admin.index', compact('users', 'titles', 'committees', 'groupedTitles', 'events'));
    }
}
