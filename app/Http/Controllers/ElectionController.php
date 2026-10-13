<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\ElectionCandidate;
use App\Models\ElectionVoter;
use App\Models\ElectionVote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ElectionController extends Controller
{
    /**
     * Display a listing of the elections (with optional filtering).
     */
    public function index(Request $request)
    {
        $query = Election::query()->withCount(['positions', 'voters']);

        // Real-time Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search Input Filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $elections = $query->latest()->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $elections,
            ]);
        }

        return view('committees.election-app.index', compact('elections'));
    }

    /**
     * Store a newly created election in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,closed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $election = Election::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Election created successfully!',
                'data' => $election,
            ]);
        }

        return redirect()->route('committees.election.index')->with('success', 'Election created successfully!');
    }

    /**
     * Load the dedicated election management page.
     */
    public function manage(Election $election)
    {
        $positions = $election->positions()
            ->with(['candidates' => function($q) {
                $q->withCount('votes')->orderByDesc('votes_count');
            }])
            ->withCount('votes')
            ->get();

        $divisions = $election->voters()
            ->whereNotNull('division')
            ->where('division', '!=', '')
            ->distinct()
            ->pluck('division')
            ->toArray();

        $genders = $election->voters()
            ->whereNotNull('gender')
            ->where('gender', '!=', '')
            ->distinct()
            ->pluck('gender')
            ->toArray();

        $stats = $this->calculateElectionStats($election);

        // Paginate initial voters list at 100 per page for fast rendering with 6000+ voters
        $voters = $election->voters()
            ->with('user')
            ->orderBy('voted_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(100);

        return view('committees.election-app.manage', compact('election', 'positions', 'voters', 'divisions', 'genders', 'stats'));
    }

    /**
     * Calculate comprehensive statistics and regional breakdown for an election.
     */
    private function calculateElectionStats(Election $election)
    {
        // Lightweight voter stats data to avoid loading 6000+ full models and user relations
        $voterStats = $election->voters()
            ->select('gender', 'age', 'voted_at', 'division')
            ->get();

        // Calculate Demographic and Turnout Statistics
        $genderStats = [
            'Male' => ['count' => 0, 'voted' => 0],
            'Female' => ['count' => 0, 'voted' => 0],
            'LGBTQ' => ['count' => 0, 'voted' => 0],
            'Others' => ['count' => 0, 'voted' => 0, 'details' => []]
        ];

        $ageStats = [
            '18-25' => ['count' => 0, 'voted' => 0],
            '26-35' => ['count' => 0, 'voted' => 0],
            '36-45' => ['count' => 0, 'voted' => 0],
            '46-60' => ['count' => 0, 'voted' => 0],
            '61+' => ['count' => 0, 'voted' => 0],
        ];

        foreach ($voterStats as $voter) {
            $g = $voter->gender;
            $hasVoted = $voter->voted_at !== null;

            // Gender Breakdown
            if (in_array($g, ['Male', 'Female', 'LGBTQ'])) {
                $genderStats[$g]['count']++;
                if ($hasVoted) {
                    $genderStats[$g]['voted']++;
                }
            } elseif (!empty($g)) {
                $genderStats['Others']['count']++;
                if ($hasVoted) {
                    $genderStats['Others']['voted']++;
                }
                if (!isset($genderStats['Others']['details'][$g])) {
                    $genderStats['Others']['details'][$g] = 0;
                }
                $genderStats['Others']['details'][$g]++;
            }

            // Age Breakdown
            $age = (int)$voter->age;
            if ($age >= 18 && $age <= 25) {
                $ageStats['18-25']['count']++;
                if ($hasVoted) $ageStats['18-25']['voted']++;
            } elseif ($age >= 26 && $age <= 35) {
                $ageStats['26-35']['count']++;
                if ($hasVoted) $ageStats['26-35']['voted']++;
            } elseif ($age >= 36 && $age <= 45) {
                $ageStats['36-45']['count']++;
                if ($hasVoted) $ageStats['36-45']['voted']++;
            } elseif ($age >= 46 && $age <= 60) {
                $ageStats['46-60']['count']++;
                if ($hasVoted) $ageStats['46-60']['voted']++;
            } elseif ($age >= 61) {
                $ageStats['61+']['count']++;
                if ($hasVoted) $ageStats['61+']['voted']++;
            }
        }

        $allAges = $voterStats->pluck('age')->filter()->toArray();
        $avgAgeAll = count($allAges) > 0 ? round(array_sum($allAges) / count($allAges), 1) : 0;

        $votedAges = $voterStats->whereNotNull('voted_at')->pluck('age')->filter()->toArray();
        $avgAgeVoted = count($votedAges) > 0 ? round(array_sum($votedAges) / count($votedAges), 1) : 0;

        $queuedAges = $voterStats->whereNull('voted_at')->pluck('age')->filter()->toArray();
        $avgAgeQueued = count($queuedAges) > 0 ? round(array_sum($queuedAges) / count($queuedAges), 1) : 0;

        // Group voters by Division / Workplace
        $divisionMap = [];
        foreach ($voterStats as $voter) {
            $div = trim((string)$voter->division);
            if ($div === '') {
                $div = 'General / Unassigned';
            }

            if (!isset($divisionMap[$div])) {
                $divisionMap[$div] = [
                    'division' => $div,
                    'total' => 0,
                    'voted' => 0,
                    'queued' => 0,
                    'turnout_rate' => 0.0,
                    'status' => 'inactive',
                ];
            }

            $divisionMap[$div]['total']++;
            if ($voter->voted_at !== null) {
                $divisionMap[$div]['voted']++;
            } else {
                $divisionMap[$div]['queued']++;
            }
        }

        // Calculate rates & status per division
        $divisionItems = [];
        $activeDivisionsCount = 0;
        $completedDivisionsCount = 0;

        foreach ($divisionMap as $divName => $item) {
            $turnout = $item['total'] > 0 ? round(($item['voted'] / $item['total']) * 100, 1) : 0.0;
            $item['turnout_rate'] = $turnout;

            if ($turnout >= 100.0) {
                $item['status'] = 'completed';
                $completedDivisionsCount++;
            } elseif ($turnout >= 75.0) {
                $item['status'] = 'high';
            } elseif ($turnout >= 40.0) {
                $item['status'] = 'moderate';
            } elseif ($turnout > 0) {
                $item['status'] = 'low';
            } else {
                $item['status'] = 'inactive';
            }

            if ($item['voted'] > 0) {
                $activeDivisionsCount++;
            }

            $divisionItems[] = $item;
        }

        // Sort divisions by turnout rate descending, then total voters descending
        usort($divisionItems, function($a, $b) {
            if ($b['turnout_rate'] != $a['turnout_rate']) {
                return $b['turnout_rate'] <=> $a['turnout_rate'];
            }
            return $b['total'] <=> $a['total'];
        });

        // Determine top and lowest performing divisions
        $activeOnly = array_values(array_filter($divisionItems, fn($d) => $d['total'] > 0));
        $topDivision = !empty($activeOnly) ? $activeOnly[0] : null;
        $lowestDivision = !empty($activeOnly) ? end($activeOnly) : null;

        $totalDivisionsCount = count($divisionItems);
        $avgDivisionTurnout = $totalDivisionsCount > 0 ? round(array_sum(array_column($divisionItems, 'turnout_rate')) / $totalDivisionsCount, 1) : 0;

        $divisionStats = [
            'items' => $divisionItems,
            'total_divisions' => $totalDivisionsCount,
            'active_divisions' => $activeDivisionsCount,
            'completed_divisions' => $completedDivisionsCount,
            'top_division' => $topDivision,
            'lowest_division' => $lowestDivision,
            'avg_division_turnout' => $avgDivisionTurnout,
        ];

        return [
            'total_voters' => $voterStats->count(),
            'voted_voters' => $voterStats->whereNotNull('voted_at')->count(),
            'queued_voters' => $voterStats->whereNull('voted_at')->count(),
            'turnout_rate' => $voterStats->count() > 0 ? round(($voterStats->whereNotNull('voted_at')->count() / $voterStats->count()) * 100, 1) : 0,
            'gender' => $genderStats,
            'age' => $ageStats,
            'avg_age_all' => $avgAgeAll,
            'avg_age_voted' => $avgAgeVoted,
            'avg_age_queued' => $avgAgeQueued,
            'divisions' => $divisionStats,
        ];
    }

    /**
     * Update the specified election in storage.
     */
    public function update(Request $request, Election $election)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,closed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $election->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Election updated successfully!',
                'data' => $election,
            ]);
        }

        return redirect()->route('committees.election.index')->with('success', 'Election updated successfully!');
    }

    /**
     * Remove the specified election from storage.
     */
    public function destroy(Election $election)
    {
        $election->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Election deleted successfully!',
            ]);
        }

        return redirect()->route('committees.election.index')->with('success', 'Election deleted successfully!');
    }

    /**
     * Store a newly created position under an election.
     */
    public function storePosition(Request $request, Election $election)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_votes' => 'required|integer|min:1',
            'sort_order' => 'nullable|integer',
        ]);

        // Default sort order if not set
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = $election->positions()->count() + 1;
        }

        $position = $election->positions()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Position created successfully!',
            'data' => $position,
        ]);
    }

    /**
     * Update the specified position.
     */
    public function updatePosition(Request $request, ElectionPosition $position)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_votes' => 'required|integer|min:1',
            'sort_order' => 'required|integer',
        ]);

        $position->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Position updated successfully!',
            'data' => $position,
        ]);
    }

    /**
     * Remove the specified position.
     */
    public function destroyPosition(ElectionPosition $position)
    {
        $position->delete();

        return response()->json([
            'success' => true,
            'message' => 'Position deleted successfully!',
        ]);
    }

    /**
     * Store a newly created candidate under a position.
     */
    public function storeCandidate(Request $request, ElectionPosition $position)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'party_affiliation' => 'nullable|string|max:255',
            'avatar_file' => 'nullable|image|max:2048',
            'avatar_path' => 'nullable|string|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        $avatarUrl = $validated['avatar_path'] ?? null;

        if ($request->hasFile('avatar_file')) {
            $path = $request->file('avatar_file')->store('candidates', 's3');
            $avatarUrl = Storage::disk('s3')->url($path);
        }

        // Default sort order if not set
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = $position->candidates()->count() + 1;
        }

        $candidate = $position->candidates()->create([
            'name' => $validated['name'],
            'party_affiliation' => $validated['party_affiliation'],
            'avatar_path' => $avatarUrl,
            'sort_order' => $validated['sort_order'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Candidate created successfully!',
            'data' => $candidate,
        ]);
    }

    /**
     * Update the specified candidate.
     */
    public function updateCandidate(Request $request, ElectionCandidate $candidate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'party_affiliation' => 'nullable|string|max:255',
            'avatar_file' => 'nullable|image|max:2048',
            'avatar_path' => 'nullable|string|max:2048',
            'sort_order' => 'required|integer',
        ]);

        $avatarUrl = $validated['avatar_path'] ?? $candidate->avatar_path;

        if ($request->hasFile('avatar_file')) {
            // Cleanup the old S3 avatar if it exists in our bucket
            $bucketName = env('AWS_BUCKET');
            if ($candidate->avatar_path && !empty($bucketName) && str_contains($candidate->avatar_path, $bucketName)) {
                $parsedUrl = parse_url($candidate->avatar_path);
                $oldPath = ltrim($parsedUrl['path'] ?? '', '/');
                
                if (str_starts_with($oldPath, $bucketName . '/')) {
                    $oldPath = substr($oldPath, strlen($bucketName . '/'));
                }

                if (!empty($oldPath) && Storage::disk('s3')->exists($oldPath)) {
                    Storage::disk('s3')->delete($oldPath);
                }
            }

            $path = $request->file('avatar_file')->store('candidates', 's3');
            $avatarUrl = Storage::disk('s3')->url($path);
        }

        $candidate->update([
            'name' => $validated['name'],
            'party_affiliation' => $validated['party_affiliation'],
            'avatar_path' => $avatarUrl,
            'sort_order' => $validated['sort_order'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Candidate updated successfully!',
            'data' => $candidate,
        ]);
    }

    /**
     * Remove the specified candidate.
     */
    public function destroyCandidate(ElectionCandidate $candidate)
    {
        // Delete candidate's image from S3 if it exists in our bucket
        $bucketName = env('AWS_BUCKET');
        if ($candidate->avatar_path && !empty($bucketName) && str_contains($candidate->avatar_path, $bucketName)) {
            $parsedUrl = parse_url($candidate->avatar_path);
            $path = ltrim($parsedUrl['path'] ?? '', '/');
            
            if (str_starts_with($path, $bucketName . '/')) {
                $path = substr($path, strlen($bucketName . '/'));
            }

            if (!empty($path) && Storage::disk('s3')->exists($path)) {
                Storage::disk('s3')->delete($path);
            }
        }

        $candidate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Candidate deleted successfully!',
        ]);
    }

    // ==========================================
    // PHASE 3: PUBLIC VOTING PORTAL FLOW (ISO-VOTER ISOLATION)
    // ==========================================

    /**
     * Retrieve the verified voter email from either the Auth guard or the isolated voter session.
     */
    private function getVoterEmail()
    {
        if (auth()->check()) {
            return strtolower(auth()->user()->email);
        }

        if (session()->has('voter_email')) {
            return strtolower(session('voter_email'));
        }

        return null;
    }

    /**
     * Public gateway to start the Google SSO flow for a voter.
     */
    public function voterLogin(Election $election)
    {
        if ($election->status !== 'active') {
            abort(403, 'Access Denied: This election is currently not active.');
        }

        // If the voter has an active session, bypass Google SSO and route them immediately
        $email = $this->getVoterEmail();
        if ($email) {
            $voter = $election->voters()->where('email', $email)->first();
            if ($voter && $voter->voted_at !== null) {
                return view('committees.election-app.voter_success', [
                    'election' => $election,
                    'message' => 'You have already successfully cast your vote in this election!'
                ]);
            }
            return redirect()->route('elections.voter.setup', $election->id);
        }

        // Save election ID to session before going to Google
        session(['voter_for_election' => $election->id]);

        return redirect()->route('auth.google');
    }

    /**
     * Show the Voter Setup Profile screen.
     */
    public function showVoterSetup(Election $election)
    {
        $email = $this->getVoterEmail();
        if (!$email) {
            return redirect()->route('elections.voter.login', $election->id);
        }

        $isMlhuillier = str_ends_with($email, '@mlhuillier.com');
        $superAdminEmail = config('app.super_admin_email');
        $isSuperAdmin = ($superAdminEmail && $email === $superAdminEmail);
        if (!$isMlhuillier && !$isSuperAdmin) {
            abort(403, 'Access Denied: An authorized email domain is required to participate in cooperative elections.');
        }

        // Check if election is active
        if ($election->status !== 'active') {
            abort(403, 'Access Denied: This election is currently not accepting votes.');
        }

        // Check if already voted
        $voter = $election->voters()->where('email', $email)->first();
        if ($voter && $voter->voted_at !== null) {
            return view('committees.election-app.voter_success', [
                'election' => $election,
                'message' => 'You have already successfully cast your vote in this election!'
            ]);
        }

        // If profiling is already completed but not yet voted, redirect to ballot
        if ($voter && $voter->division !== null && $voter->gender !== null && $voter->age !== null) {
            return redirect()->route('elections.ballot', $election->id);
        }

        return view('committees.election-app.voter_setup', compact('election'));
    }

    /**
     * Handle submission of the Voter Setup Profile.
     */
    public function submitVoterSetup(Request $request, Election $election)
    {
        $email = $this->getVoterEmail();
        if (!$email) {
            return redirect()->route('elections.voter.login', $election->id);
        }

        $isMlhuillier = str_ends_with($email, '@mlhuillier.com');
        $superAdminEmail = config('app.super_admin_email');
        $isSuperAdmin = ($superAdminEmail && $email === $superAdminEmail);
        if (!$isMlhuillier && !$isSuperAdmin) {
            abort(403, 'Access Denied: An authorized email domain is required to participate in cooperative elections.');
        }

        if ($election->status !== 'active') {
            abort(403, 'Access Denied: This election is currently not active.');
        }

        $validated = $request->validate([
            'division' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'gender_other' => 'nullable|required_if:gender,Others|string|max:255',
            'age' => 'required|integer|min:18|max:120',
        ]);

        $genderValue = $validated['gender'];
        if ($genderValue === 'Others' && !empty($validated['gender_other'])) {
            $genderValue = $validated['gender_other'];
        }

        $election->voters()->updateOrCreate(
            ['email' => $email],
            [
                'user_id' => auth()->id(), // keeps it null for isolated guests!
                'division' => $validated['division'],
                'gender' => $genderValue,
                'age' => $validated['age'],
            ]
        );

        return redirect()->route('elections.ballot', $election->id);
    }

    /**
     * Show the interactive Voting Ballot booth.
     */
    public function showBallot(Election $election)
    {
        $email = $this->getVoterEmail();
        if (!$email) {
            return redirect()->route('elections.voter.login', $election->id);
        }

        $isMlhuillier = str_ends_with($email, '@mlhuillier.com');
        $superAdminEmail = config('app.super_admin_email');
        $isSuperAdmin = ($superAdminEmail && $email === $superAdminEmail);
        if (!$isMlhuillier && !$isSuperAdmin) {
            abort(403, 'Access Denied: An authorized email domain is required to participate in cooperative elections.');
        }

        if ($election->status !== 'active') {
            abort(403, 'Access Denied: This election is currently not active.');
        }

        // Ensure setup has been completed
        $voter = $election->voters()->where('email', $email)->first();
        if (!$voter || $voter->division === null || $voter->gender === null || $voter->age === null) {
            return redirect()->route('elections.voter.setup', $election->id);
        }

        // Ensure they haven't voted yet
        if ($voter->voted_at !== null) {
            return view('committees.election-app.voter_success', [
                'election' => $election,
                'message' => 'You have already successfully cast your vote in this election!'
            ]);
        }

        $positions = $election->positions()->with('candidates')->get();

        return view('committees.election-app.ballot', compact('election', 'positions', 'voter'));
    }

    /**
     * Securely submit and record the voter's ballot selections.
     */
    public function submitBallot(Request $request, Election $election)
    {
        $email = $this->getVoterEmail();
        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated voter session.'
            ], 401);
        }

        $isMlhuillier = str_ends_with($email, '@mlhuillier.com');
        $superAdminEmail = config('app.super_admin_email');
        $isSuperAdmin = ($superAdminEmail && $email === $superAdminEmail);
        if (!$isMlhuillier && !$isSuperAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Access Denied: An authorized email domain is required.'
            ], 403);
        }

        if ($election->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'This election is currently not accepting votes.'
            ], 403);
        }

        $voter = $election->voters()->where('email', $email)->first();
        if (!$voter || $voter->division === null || $voter->gender === null || $voter->age === null) {
            return response()->json([
                'success' => false,
                'message' => 'Voter profiling setup is incomplete.'
            ], 403);
        }

        if ($voter->voted_at !== null) {
            return response()->json([
                'success' => false,
                'message' => 'Double-voting protection: You have already cast your ballot in this election.'
            ], 403);
        }

        try {
            DB::transaction(function () use ($election, $voter, $request) {
                $votes = $request->input('votes', []);

                foreach ($election->positions as $position) {
                    $candIds = isset($votes[$position->id]) ? (array) $votes[$position->id] : [];

                    // Strip null/empty items
                    $candIds = array_filter($candIds);

                    // 1. Enforce max votes validation
                    if (count($candIds) > $position->max_votes) {
                        throw new \Exception("Voting limit exceeded: You can select up to {$position->max_votes} candidates for the position of {$position->name}.");
                    }

                    // 2. Insert secure records
                    foreach ($candIds as $candId) {
                        // Ensure candidate belongs to this position
                        $candidateExists = $position->candidates()->where('id', $candId)->exists();
                        if (!$candidateExists) {
                            throw new \Exception("Security Exception: Candidate does not belong to the selected position.");
                        }

                        ElectionVote::create([
                            'voter_id' => $voter->id,
                            'position_id' => $position->id,
                            'candidate_id' => $candId,
                        ]);
                    }
                }

                // 3. Complete ballot casting securely
                $voter->update([
                    'voted_at' => now()
                ]);
            });

            // 4. Send Official Email Receipt (Isolated & Robust)
            try {
                $votes = $request->input('votes', []);
                $selectionsPayload = [];
                
                foreach ($election->positions as $position) {
                    $candIds = isset($votes[$position->id]) ? (array)$votes[$position->id] : [];
                    $candIds = array_filter($candIds);

                    if (count($candIds) > 0) {
                        $candidateNames = $position->candidates()
                            ->whereIn('id', $candIds)
                            ->pluck('name')
                            ->toArray();

                        $selectionsPayload[$position->name] = $candidateNames;
                    }
                }

                \Illuminate\Support\Facades\Mail::to($email)->send(
                    new \App\Mail\ElectionReceipt($election, $voter, $selectionsPayload)
                );
            } catch (\Exception $mailEx) {
                // Log the exception but do not block the submission flow if mail servers are unconfigured or down
                \Illuminate\Support\Facades\Log::error("Election receipt email failed: " . $mailEx->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Ballot submitted and recorded successfully! Thank you for voting.',
                'redirect' => route('elections.ballot', $election->id)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Export the election results as a downloadable CSV.
     */
    public function exportResults(Election $election)
    {
        $email = $this->getVoterEmail();
        if (!$email) {
            abort(401, 'Unauthenticated session.');
        }

        $isMlhuillier = str_ends_with($email, '@mlhuillier.com');
        $superAdminEmail = config('app.super_admin_email');
        $isSuperAdmin = ($superAdminEmail && $email === $superAdminEmail);
        if (!$isMlhuillier && !$isSuperAdmin) {
            abort(403, 'Access Denied: An authorized email domain is required.');
        }

        $positions = $election->positions()
            ->with(['candidates' => function($q) {
                $q->withCount('votes')->orderByDesc('votes_count');
            }])
            ->withCount('votes')
            ->get();

        $filename = "election_results_" . str_replace(' ', '_', strtolower($election->title)) . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($positions) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, ['Ballot Position', 'Candidate Name', 'Party Affiliation', 'Votes Received', 'Total Position Votes', 'Vote Share Percentage']);

            foreach ($positions as $position) {
                $totalVotes = $position->votes_count;

                foreach ($position->candidates as $candidate) {
                    $votes = $candidate->votes_count;
                    $percentage = $totalVotes > 0 ? round(($votes / $totalVotes) * 100, 2) . '%' : '0%';

                    fputcsv($file, [
                        $position->name,
                        $candidate->name,
                        $candidate->party_affiliation ?? 'Independent',
                        $votes,
                        $totalVotes,
                        $percentage
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export the election results as a downloadable PDF using DomPDF.
     */
    public function exportPDF(Election $election)
    {
        $email = $this->getVoterEmail();
        if (!$email) {
            abort(401, 'Unauthenticated session.');
        }

        $isMlhuillier = str_ends_with($email, '@mlhuillier.com');
        $superAdminEmail = config('app.super_admin_email');
        $isSuperAdmin = ($superAdminEmail && $email === $superAdminEmail);
        if (!$isMlhuillier && !$isSuperAdmin) {
            abort(403, 'Access Denied: An authorized email domain is required.');
        }

        $positions = $election->positions()
            ->with(['candidates' => function($q) {
                $q->withCount('votes')->orderByDesc('votes_count');
            }])
            ->withCount('votes')
            ->get();

        $voterCount = $election->voters()->count();

        // Load premium print-ready PDF template with election data
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('committees.election-app.results_pdf', compact('election', 'positions', 'voterCount'));

        $filename = "election_results_" . str_replace(' ', '_', strtolower($election->title)) . ".pdf";

        return $pdf->stream($filename);
    }

    /**
     * Export the election statistics & division audit report as a clean downloadable PDF.
     */
    public function exportStatisticsPDF(Election $election)
    {
        $email = $this->getVoterEmail();
        if (!$email) {
            abort(401, 'Unauthenticated session.');
        }

        $isMlhuillier = str_ends_with($email, '@mlhuillier.com');
        $superAdminEmail = config('app.super_admin_email');
        $isSuperAdmin = ($superAdminEmail && $email === $superAdminEmail);
        if (!$isMlhuillier && !$isSuperAdmin) {
            abort(403, 'Access Denied: An authorized email domain is required.');
        }

        $stats = $this->calculateElectionStats($election);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'committees.election-app.pdf_formats.clean_statistics_pdf',
            compact('election', 'stats')
        );

        $filename = "election_audit_report_" . str_replace(' ', '_', strtolower($election->title)) . ".pdf";

        return $pdf->stream($filename);
    }

    /**
     * Get voters for an election with AJAX filtering, searching, and sorting.
     */
    public function getVoters(Request $request, Election $election)
    {
        $query = $election->voters()->with('user');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('division', 'like', "%{$search}%")
                  ->orWhere('gender', 'like', "%{$search}%")
                  ->orWhere('age', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Division Filter
        if ($request->filled('division') && $request->input('division') !== 'all') {
            $query->where('division', $request->input('division'));
        }

        // Gender Filter
        if ($request->filled('gender') && $request->input('gender') !== 'all') {
            $query->where('gender', $request->input('gender'));
        }

        // Status Filter (All, Voted, In Queue)
        if ($request->filled('status') && $request->input('status') !== 'all') {
            if ($request->input('status') === 'voted') {
                $query->whereNotNull('voted_at');
            } elseif ($request->input('status') === 'queue') {
                $query->whereNull('voted_at');
            }
        }

        // Sorting by Submission Time (voted_at) or created_at (as a backup/default)
        $sortOrder = $request->input('sort', 'desc');
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }
        
        // Sort by voted_at first, then by id
        $query->orderBy('voted_at', $sortOrder)
              ->orderBy('id', $sortOrder);

        $perPage = 100;
        $page = (int) $request->input('page', 1);
        $paginated = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'voters' => $paginated->map(function($voter) {
                return [
                    'id' => $voter->id,
                    'name' => $voter->display_name,
                    'raw_name' => $voter->name ?? ($voter->user ? $voter->user->name : ''),
                    'email' => $voter->email,
                    'division' => $voter->division ?? '-',
                    'raw_division' => $voter->division ?? '',
                    'gender' => $voter->gender ?? '-',
                    'raw_gender' => $voter->gender ?? '',
                    'age' => $voter->age ?? '-',
                    'raw_age' => $voter->age ?? '',
                    'voted_at' => $voter->voted_at ? $voter->voted_at->format('M d, Y • h:i A') : null,
                    'voted_at_formatted' => $voter->voted_at ? $voter->voted_at->format('M d, Y • h:i A') : '-',
                    'status' => $voter->voted_at ? 'Voted' : 'In Queue',
                    'is_voted' => $voter->voted_at !== null,
                ];
            }),
            'pagination' => [
                'total' => $paginated->total(),
                'per_page' => $paginated->perPage(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'from' => $paginated->firstItem() ?? 0,
                'to' => $paginated->lastItem() ?? 0,
            ]
        ]);
    }

    /**
     * Store a newly created voter under an election.
     */
    public function storeVoter(Request $request, Election $election)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('election_voters')->where(function ($query) use ($election) {
                    return $query->where('election_id', $election->id);
                }),
            ],
            'division' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:18|max:120',
            'status' => 'nullable|string|in:in_queue,voted',
        ]);

        $user = User::where('email', $validated['email'])->first();
        $votedAt = ($request->input('status') === 'voted') ? now() : null;

        $voter = $election->voters()->create([
            'user_id' => $user ? $user->id : null,
            'name' => $validated['name'] ?? ($user ? $user->name : null),
            'email' => strtolower($validated['email']),
            'division' => $validated['division'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'age' => $validated['age'] ?? null,
            'voted_at' => $votedAt,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voter enrolled successfully!',
            'voter' => [
                'id' => $voter->id,
                'name' => $voter->display_name,
                'email' => $voter->email,
                'division' => $voter->division ?? '-',
                'gender' => $voter->gender ?? '-',
                'age' => $voter->age ?? '-',
                'status' => $voter->voted_at ? 'Voted' : 'In Queue',
                'voted_at_formatted' => $voter->voted_at ? $voter->voted_at->format('M d, Y • h:i A') : '-',
            ]
        ]);
    }

    /**
     * Display the specified voter and their ballot audit information.
     */
    public function showVoter(ElectionVoter $voter)
    {
        $voter->load(['user', 'votes.position', 'votes.candidate']);

        return response()->json([
            'success' => true,
            'voter' => [
                'id' => $voter->id,
                'name' => $voter->display_name,
                'raw_name' => $voter->name ?? ($voter->user ? $voter->user->name : ''),
                'email' => $voter->email,
                'division' => $voter->division ?? '-',
                'raw_division' => $voter->division ?? '',
                'gender' => $voter->gender ?? '-',
                'raw_gender' => $voter->gender ?? '',
                'age' => $voter->age ?? '-',
                'raw_age' => $voter->age ?? '',
                'status' => $voter->voted_at ? 'Voted' : 'In Queue',
                'is_voted' => $voter->voted_at !== null,
                'voted_at' => $voter->voted_at ? $voter->voted_at->format('M d, Y • h:i A') : null,
                'created_at' => $voter->created_at ? $voter->created_at->format('M d, Y • h:i A') : null,
                'votes' => $voter->votes->map(function ($vote) {
                    return [
                        'position' => $vote->position ? $vote->position->name : 'Unknown Position',
                        'candidate' => $vote->candidate ? $vote->candidate->name : 'Unknown Candidate',
                    ];
                }),
            ]
        ]);
    }

    /**
     * Update the specified voter in storage.
     */
    public function updateVoter(Request $request, ElectionVoter $voter)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('election_voters')->where(function ($query) use ($voter) {
                    return $query->where('election_id', $voter->election_id);
                })->ignore($voter->id),
            ],
            'division' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:18|max:120',
            'status' => 'required|string|in:in_queue,voted',
        ]);

        $user = User::where('email', $validated['email'])->first();

        $data = [
            'name' => $validated['name'] ?? ($user ? $user->name : null),
            'email' => strtolower($validated['email']),
            'division' => $validated['division'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'age' => $validated['age'] ?? null,
            'user_id' => $user ? $user->id : $voter->user_id,
        ];

        // Handle Status Transition
        if ($validated['status'] === 'in_queue') {
            // Reset voting timestamp and clear any recorded votes so they can cast again
            $data['voted_at'] = null;
            $voter->votes()->delete();
        } elseif ($validated['status'] === 'voted' && $voter->voted_at === null) {
            $data['voted_at'] = now();
        }

        $voter->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Voter updated successfully!',
            'voter' => [
                'id' => $voter->id,
                'name' => $voter->display_name,
                'email' => $voter->email,
                'division' => $voter->division ?? '-',
                'gender' => $voter->gender ?? '-',
                'age' => $voter->age ?? '-',
                'status' => $voter->voted_at ? 'Voted' : 'In Queue',
                'voted_at_formatted' => $voter->voted_at ? $voter->voted_at->format('M d, Y • h:i A') : '-',
            ]
        ]);
    }

    /**
     * Remove the specified voter from storage.
     */
    public function destroyVoter(ElectionVoter $voter)
    {
        // Delete any associated cast votes
        $voter->votes()->delete();
        $voter->delete();

        return response()->json([
            'success' => true,
            'message' => 'Voter deleted successfully!'
        ]);
    }

    /**
     * Export the voter registry as a downloadable CSV.
     */
    public function exportVoters(Election $election)
    {
        $email = $this->getVoterEmail();
        if (!$email) {
            abort(401, 'Unauthenticated session.');
        }

        $isMlhuillier = str_ends_with($email, '@mlhuillier.com');
        $superAdminEmail = config('app.super_admin_email');
        $isSuperAdmin = ($superAdminEmail && $email === $superAdminEmail);
        if (!$isMlhuillier && !$isSuperAdmin) {
            abort(403, 'Access Denied: An authorized email domain is required.');
        }

        $voters = $election->voters()->with('user')->orderBy('voted_at', 'desc')->get();

        $filename = "voter_registry_" . str_replace(' ', '_', strtolower($election->title)) . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($voters) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, ['Voter Name', 'Email Account', 'Division / Workplace', 'Gender', 'Age', 'Voting Status', 'Submission Time']);

            foreach ($voters as $voter) {
                fputcsv($file, [
                    $voter->display_name,
                    $voter->email,
                    $voter->division ?? '-',
                    $voter->gender ?? '-',
                    $voter->age ?? '-',
                    $voter->voted_at ? 'Voted' : 'In Queue',
                    $voter->voted_at ? $voter->voted_at->format('Y-m-d H:i:s') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
