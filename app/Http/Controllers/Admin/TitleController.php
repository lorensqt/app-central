<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Committee;
use App\Models\Title;
use Illuminate\Http\Request;

class TitleController extends Controller
{
    /**
     * Display a listing of corporate titles.
     */
    public function index()
    {
        $titles = Title::withCount('users')->latest()->get();
        $committees = Committee::latest()->get();

        return view('admin.titles', compact('titles', 'committees'));
    }

    /**
     * Store a newly created corporate title.
     */
    public function store(Request $request)
    {
        $committees = Committee::pluck('name')->toArray();
        $allowedGroups = array_merge(['Board of Directors', 'Management'], $committees);

        $validated = $request->validate([
            'group' => 'required|in:'.implode(',', $allowedGroups),
            'title' => 'required|string|max:100',
        ]);

        Title::create($validated);

        return redirect()->route('admin.index', ['tab' => 'titles'])->with('status', 'Corporate title created successfully.');
    }

    /**
     * Update the specified corporate title.
     */
    public function update(Request $request, Title $title)
    {
        $committees = Committee::pluck('name')->toArray();
        $allowedGroups = array_merge(['Board of Directors', 'Management'], $committees);

        $validated = $request->validate([
            'group' => 'required|in:'.implode(',', $allowedGroups),
            'title' => 'required|string|max:100',
        ]);

        $title->update($validated);

        return redirect()->route('admin.index', ['tab' => 'titles'])->with('status', 'Corporate title updated successfully.');
    }

    /**
     * Remove the specified corporate title.
     */
    public function destroy(Title $title)
    {
        $title->delete();

        return redirect()->route('admin.index', ['tab' => 'titles'])->with('status', 'Corporate title deleted successfully.');
    }
}
