<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamRequest;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TeamController extends Controller
{
    /**
     * Display a paginated listing of team members.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $teams = Team::query()
            ->when(
                $search,
                fn ($q) => $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('role', 'like', "%{$search}%");
                })
            )
            ->when(
                $status !== null && $status !== '',
                fn ($q) => $q->where('status', $status)
            )
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.teams.index', compact('teams', 'search', 'status'));
    }

    /**
     * Show the form for creating a new team member.
     */
    public function create(): View
    {
        return view('admin.teams.create');
    }

    /**
     * Store a newly created team member in storage.
     */
    public function store(StoreTeamRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('teams', 'public');
        }

        Team::create($validated);

        return redirect()
            ->route('admin.teams.index')
            ->with('success', "Anggota tim \"{$validated['name']}\" berhasil ditambahkan.");
    }

    /**
     * Redirect show to edit — admin has no separate show view.
     */
    public function show(int $id): RedirectResponse
    {
        return redirect()->route('admin.teams.edit', $id);
    }

    /**
     * Show the form for editing the specified team member.
     */
    public function edit(int $id): View
    {
        $team = Team::findOrFail($id);

        return view('admin.teams.edit', compact('team'));
    }

    /**
     * Update the specified team member in storage.
     */
    public function update(StoreTeamRequest $request, int $id): RedirectResponse
    {
        $team = Team::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            if ($team->photo && Storage::disk('public')->exists($team->photo)) {
                Storage::disk('public')->delete($team->photo);
            }

            $validated['photo'] = $request->file('photo')
                ->store('teams', 'public');
        }

        $team->update($validated);

        return redirect()
            ->route('admin.teams.index')
            ->with('success', "Anggota tim \"{$team->name}\" berhasil diperbarui.");
    }

    /**
     * Remove the specified team member from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $team = Team::findOrFail($id);

        if ($team->photo && Storage::disk('public')->exists($team->photo)) {
            Storage::disk('public')->delete($team->photo);
        }

        $name = $team->name;
        $team->delete();

        return redirect()
            ->route('admin.teams.index')
            ->with('success', "Anggota tim \"{$name}\" berhasil dihapus.");
    }

    /**
     * Toggle the published/unpublished status of a team member.
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $team = Team::findOrFail($id);

        $newStatus = $team->status === 'published' ? 'unpublished' : 'published';

        $team->update(['status' => $newStatus]);

        $label = $newStatus === 'published' ? 'ditampilkan' : 'disembunyikan';

        return back()->with('success', "Anggota tim \"{$team->name}\" berhasil {$label}.");
    }
}
