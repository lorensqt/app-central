<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Title;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of manually added users.
     */
    public function index()
    {
        $users = User::with('title')->get();
        $titles = Title::all();

        // Group titles by their respective corporate groups for easy dropdown grouping
        $groupedTitles = $titles->groupBy('group');

        return view('admin.users', compact('users', 'groupedTitles'));
    }

    /**
     * Store a newly added user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'title_id' => 'nullable|exists:titles,id',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'title_id' => $validated['title_id'] ?? null,
            'pin' => null,
            'password' => null,
        ]);

        return redirect()->route('admin.index', ['tab' => 'users'])->with('status', 'User registered successfully and granted access privileges. They will be prompted to set up their 6-digit access PIN upon their first login.');
    }

    /**
     * Store a newly added administrator in storage.
     */
    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'title_id' => null,
            'pin' => null,
            'is_admin' => true,
            'password' => null,
        ]);

        return redirect()->route('admin.index', ['tab' => 'users'])->with('status', 'Administrator whitelisted successfully! They will be prompted to set up their 6-digit access PIN upon their first login.');
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        if ($user->email === 'castillojohnlaurence0@gmail.com' && strtolower($request->input('email')) !== 'castillojohnlaurence0@gmail.com') {
            return redirect()->route('admin.index', ['tab' => 'users'])->with('status', 'The primary super administrator email address cannot be changed.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'title_id' => 'nullable|exists:titles,id',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'title_id' => $validated['title_id'] ?? null,
        ]);

        return redirect()->route('admin.index', ['tab' => 'users'])->with('status', 'User details updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        if ($user->email === 'castillojohnlaurence0@gmail.com') {
            return redirect()->route('admin.index', ['tab' => 'users'])->with('status', 'The primary super administrator user cannot be deleted.');
        }

        $user->delete();

        return redirect()->route('admin.index', ['tab' => 'users'])->with('status', 'User deleted successfully. Access has been revoked.');
    }

    /**
     * Reset the user's PIN to null (forcing a re-configuration on next login).
     */
    public function resetPin(User $user)
    {
        $user->update([
            'pin' => null,
        ]);

        return redirect()->route('admin.index', ['tab' => 'users'])->with('status', "The user's 6-digit access PIN has been successfully reset. They will be prompted to configure a new PIN on next login.");
    }
}
