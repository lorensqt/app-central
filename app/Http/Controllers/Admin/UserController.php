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
            'pin' => 'required|string|size:6|regex:/^[0-9]{6}$/',
        ], [
            'pin.required' => 'A 6-digit PIN is required.',
            'pin.size' => 'The PIN must be exactly 6 digits.',
            'pin.regex' => 'The PIN must consist of numbers only.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'title_id' => $validated['title_id'] ?? null,
            'pin' => $validated['pin'],
            'password' => null,
        ]);

        return redirect()->route('admin.index', ['tab' => 'users'])->with('status', 'User registered successfully with PIN code and granted access privileges.');
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        if ($user->isSuperAdmin() && strtolower($request->input('email')) !== 'castillojohnlaurence0@gmail.com') {
            return redirect()->route('admin.index', ['tab' => 'users'])->with('status', 'The super administrator email address cannot be changed.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'title_id' => 'nullable|exists:titles,id',
            'pin' => 'required|string|size:6|regex:/^[0-9]{6}$/',
        ], [
            'pin.required' => 'A 6-digit PIN is required.',
            'pin.size' => 'The PIN must be exactly 6 digits.',
            'pin.regex' => 'The PIN must consist of numbers only.',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'title_id' => $validated['title_id'] ?? null,
            'pin' => $validated['pin'],
        ]);

        return redirect()->route('admin.index', ['tab' => 'users'])->with('status', 'User details and PIN code updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.index', ['tab' => 'users'])->with('status', 'The super administrator user cannot be deleted.');
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
