<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Committee;
use App\Models\Title;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    /**
     * Store a newly created custom committee.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:committees,name|not_in:Board of Directors,Management',
        ], [
            'name.not_in' => 'The committee name cannot conflict with default corporate groups.',
            'name.unique' => 'This committee has already been created.',
        ]);

        Committee::create($validated);

        return redirect()->route('admin.index', ['tab' => 'titles'])->with('status', 'Custom Committee created successfully.');
    }

    /**
     * Update the specified custom committee.
     */
    public function update(Request $request, Committee $committee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:committees,name,' . $committee->id . '|not_in:Board of Directors,Management',
        ], [
            'name.not_in' => 'The committee name cannot conflict with default corporate groups.',
            'name.unique' => 'This committee name has already been taken.',
        ]);

        $oldName = $committee->name;
        $newName = $validated['name'];

        $committee->update($validated);

        // Update any associated titles to point to the new group name
        if ($oldName !== $newName) {
            Title::where('group', $oldName)->update(['group' => $newName]);
        }

        return redirect()->route('admin.index', ['tab' => 'titles'])->with('status', 'Custom Committee updated successfully.');
    }

    /**
     * Remove the specified custom committee.
     */
    public function destroy(Committee $committee)
    {
        // Unlinking or deleting titles under this group is handled if needed, or we can let them remain or delete them.
        // Let's delete the titles under this group automatically so the DB remains consistent.
        Title::where('group', $committee->name)->delete();

        $committee->delete();

        return redirect()->route('admin.index', ['tab' => 'titles'])->with('status', 'Custom Committee and all its associated titles deleted successfully.');
    }
}
