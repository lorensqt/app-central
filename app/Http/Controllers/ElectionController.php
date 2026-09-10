<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\ElectionCandidate;
use App\Models\ElectionVoter;
use App\Models\ElectionVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            
        $voters = $election->voters()->with('user')->get();

        $divisions = $election->voters()
            ->whereNotNull('division')
            ->where('division', '!=', '')
            ->distinct()
            ->pluck('division')
            ->toArray();

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

        foreach ($voters as $voter) {
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

        $allAges = $voters->pluck('age')->filter()->toArray();
        $avgAgeAll = count($allAges) > 0 ? round(array_sum($allAges) / count($allAges), 1) : 0;

        $votedAges = $voters->whereNotNull('voted_at')->pluck('age')->filter()->toArray();
        $avgAgeVoted = count($votedAges) > 0 ? round(array_sum($votedAges) / count($votedAges), 1) : 0;

        $queuedAges = $voters->whereNull('voted_at')->pluck('age')->filter()->toArray();
        $avgAgeQueued = count($queuedAges) > 0 ? round(array_sum($queuedAges) / count($queuedAges), 1) : 0;

        $stats = [
            'total_voters' => $voters->count(),
            'voted_voters' => $voters->whereNotNull('voted_at')->count(),
            'queued_voters' => $voters->whereNull('voted_at')->count(),
            'turnout_rate' => $voters->count() > 0 ? round(($voters->whereNotNull('voted_at')->count() / $voters->count()) * 100, 1) : 0,
            'gender' => $genderStats,
            'age' => $ageStats,
            'avg_age_all' => $avgAgeAll,
            'avg_age_voted' => $avgAgeVoted,
            'avg_age_queued' => $avgAgeQueued,
        ];

        return view('committees.election-app.manage', compact('election', 'positions', 'voters', 'divisions', 'stats'));
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
            'avatar_path' => 'nullable|string|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        // Default sort order if not set
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = $position->candidates()->count() + 1;
        }

        $candidate = $position->candidates()->create($validated);

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
            'avatar_path' => 'nullable|string|max:2048',
            'sort_order' => 'required|integer',
        ]);

        $candidate->update($validated);

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
        $isSuperAdmin = ($email === 'castillojohnlaurence0@gmail.com');
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
        $isSuperAdmin = ($email === 'castillojohnlaurence0@gmail.com');
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
        $isSuperAdmin = ($email === 'castillojohnlaurence0@gmail.com');
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
        $isSuperAdmin = ($email === 'castillojohnlaurence0@gmail.com');
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
        $isSuperAdmin = ($email === 'castillojohnlaurence0@gmail.com');
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
        $isSuperAdmin = ($email === 'castillojohnlaurence0@gmail.com');
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
     * Get voters for an election with AJAX filtering, searching, and sorting.
     */
    public function getVoters(Request $request, Election $election)
    {
        $query = $election->voters()->with('user');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
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
        
        // Sort by voted_at first, then by created_at or id
        $query->orderBy('voted_at', $sortOrder)
              ->orderBy('id', $sortOrder);

        $voters = $query->get();

        return response()->json([
            'success' => true,
            'voters' => $voters->map(function($voter) {
                return [
                    'id' => $voter->id,
                    'name' => $voter->user ? $voter->user->name : 'Anonymous Voter',
                    'email' => $voter->email,
                    'division' => $voter->division ?? '-',
                    'gender' => $voter->gender ?? '-',
                    'age' => $voter->age ?? '-',
                    'voted_at' => $voter->voted_at ? $voter->voted_at->format('M d, Y • h:i A') : null,
                    'voted_at_formatted' => $voter->voted_at ? $voter->voted_at->format('M d, Y • h:i A') : '-',
                    'status' => $voter->voted_at ? 'Voted' : 'In Queue',
                ];
            })
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
        $isSuperAdmin = ($email === 'castillojohnlaurence0@gmail.com');
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
                    $voter->user ? $voter->user->name : 'Anonymous Voter',
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
