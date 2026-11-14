<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\ElectionCandidate;
use App\Models\ElectionVoter;
use App\Models\ElectionVote;
use App\Models\User;
use App\Mail\VoterLoginOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;

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
            'sort_order' => 'nullable|integer',
        ]);

        if (!isset($validated['sort_order']) || $validated['sort_order'] === null) {
            $validated['sort_order'] = $position->sort_order ?? 1;
        }

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
            'background' => 'nullable|string',
            'poster_file' => 'nullable|image|max:10240',
            'avatar_file' => 'nullable|image|max:5120',
            'avatar_path' => 'nullable|string|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        $avatarUrl = $validated['avatar_path'] ?? null;

        if ($request->hasFile('avatar_file')) {
            $path = $request->file('avatar_file')->store('candidates', 's3');
            $avatarUrl = Storage::disk('s3')->url($path);
        }

        $posterUrl = $validated['background'] ?? null;

        if ($request->hasFile('poster_file')) {
            $posterPath = $request->file('poster_file')->store('candidate-posters', 's3');
            $posterUrl = Storage::disk('s3')->url($posterPath);
        }

        // Default sort order if not set
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = $position->candidates()->count() + 1;
        }

        $candidate = $position->candidates()->create([
            'name' => $validated['name'],
            'party_affiliation' => $validated['party_affiliation'] ?? null,
            'avatar_path' => $avatarUrl,
            'background' => $posterUrl,
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
            'background' => 'nullable|string',
            'poster_file' => 'nullable|image|max:10240',
            'avatar_file' => 'nullable|image|max:5120',
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

        $posterUrl = $candidate->background;

        if ($request->hasFile('poster_file')) {
            // Cleanup the old S3 poster if it exists in our bucket
            $bucketName = env('AWS_BUCKET');
            if ($candidate->background && !empty($bucketName) && str_contains($candidate->background, $bucketName)) {
                $parsedUrl = parse_url($candidate->background);
                $oldPath = ltrim($parsedUrl['path'] ?? '', '/');
                
                if (str_starts_with($oldPath, $bucketName . '/')) {
                    $oldPath = substr($oldPath, strlen($bucketName . '/'));
                }

                if (!empty($oldPath) && Storage::disk('s3')->exists($oldPath)) {
                    Storage::disk('s3')->delete($oldPath);
                }
            }

            $posterPath = $request->file('poster_file')->store('candidate-posters', 's3');
            $posterUrl = Storage::disk('s3')->url($posterPath);
        } elseif ($request->has('background')) {
            $posterUrl = $validated['background'];
        }

        $candidate->update([
            'name' => $validated['name'],
            'party_affiliation' => array_key_exists('party_affiliation', $validated) ? $validated['party_affiliation'] : $candidate->party_affiliation,
            'avatar_path' => $avatarUrl,
            'background' => $posterUrl,
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

        // Delete candidate's poster from S3 if it exists in our bucket
        if ($candidate->background && !empty($bucketName) && str_contains($candidate->background, $bucketName)) {
            $parsedUrl = parse_url($candidate->background);
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
     * Display the public election landing page with live anonymous tally and How To Vote instructions.
     */
    public function showPublicLanding(Request $request, Election $election)
    {
        // Canonical redirect if requested by numeric ID instead of slug
        if ($election->slug && is_numeric($request->segment(2))) {
            return redirect()->route('elections.public_show', $election->slug);
        }

        $positions = $election->positions()
            ->with(['candidates' => function($q) {
                $q->withCount('votes')->orderByDesc('votes_count')->orderBy('id');
            }])
            ->withCount('votes')
            ->get();

        $totalMasterlistVoters = $election->voters()->count();
        $totalVotesCast = $election->voters()->whereNotNull('voted_at')->count();
        $turnoutPercentage = $totalMasterlistVoters > 0
            ? round(($totalVotesCast / $totalMasterlistVoters) * 100, 1)
            : 0;

        $email = $this->getVoterEmail();
        $currentVoter = null;
        $isMasterlistEnrolled = false;
        $hasVoted = false;
        $isOtpVerified = (bool) session('voter_otp_verified_' . $election->id);

        if ($email) {
            $currentVoter = $election->voters()->where('email', strtolower($email))->first();
            if ($currentVoter) {
                $isMasterlistEnrolled = true;
                $hasVoted = $currentVoter->voted_at !== null;
            }
        }

        return view('committees.election-app.public', compact(
            'election',
            'positions',
            'totalMasterlistVoters',
            'totalVotesCast',
            'turnoutPercentage',
            'email',
            'currentVoter',
            'isMasterlistEnrolled',
            'hasVoted',
            'isOtpVerified'
        ));
    }

    /**
     * Retrieve the verified voter email from either the Auth guard or the isolated voter session.
     */
    private function getVoterEmail()
    {
        if (session()->has('voter_email')) {
            return strtolower(session('voter_email'));
        }

        if (auth()->check()) {
            return strtolower(auth()->user()->email);
        }

        return null;
    }

    /**
     * Helper to mask an email address for privacy (e.g. j***n@mlhuillier.com).
     */
    private function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1] ?? '';

        $len = strlen($name);
        if ($len <= 2) {
            $masked = substr($name, 0, 1) . '*';
        } else {
            $masked = substr($name, 0, 2) . str_repeat('*', max(3, $len - 3)) . substr($name, -1);
        }

        return $masked . '@' . $domain;
    }

    /**
     * Public gateway for voter authentication.
     */
    public function voterLogin(Election $election)
    {
        if ($election->status !== 'active') {
            return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                ->with('error', 'This election is currently not active.');
        }

        // If voter has already completed Email OTP verification for this election
        if (session('voter_otp_verified_' . $election->id)) {
            $email = $this->getVoterEmail();
            if ($email) {
                $voter = $election->voters()->where('email', strtolower($email))->first();
                if ($voter && $voter->voted_at !== null) {
                    return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                        ->with('voter_info', 'You have already successfully cast your vote in this election!');
                }
            }

            if (session('voter_accepted_terms_' . $election->id)) {
                return redirect()->route('elections.ballot', $election->slug ?: $election->id);
            }

            return redirect()->route('elections.voter.setup', $election->slug ?: $election->id);
        }

        $prefilledEmail = $this->getVoterEmail();

        return view('committees.election-app.voter_login', compact('election', 'prefilledEmail'));
    }

    /**
     * Dedicated initiation for voter Google SSO (strictly isolated to election voting portal).
     */
    public function redirectToVoterGoogle(Election $election)
    {
        if ($election->status !== 'active') {
            return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                ->with('error', 'This election is currently not active.');
        }

        session([
            'voter_for_election' => $election->id,
            'voter_for_election_slug' => $election->slug ?: $election->id,
        ]);

        return Socialite::driver('google')
            ->stateless()
            ->with(['state' => 'voter_election_' . $election->id])
            ->redirect();
    }

    /**
     * Send a 6-digit OTP to the voter's masterlist email.
     */
    public function sendVoterOtp(Request $request, Election $election)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        if ($election->status !== 'active') {
            return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                ->with('error', 'This election is currently not active.');
        }

        $email = strtolower(trim($validated['email']));

        // Check if email exists in the election's official masterlist
        $voter = $election->voters()->where('email', $email)->first();

        if (!$voter) {
            return back()->withInput()->with('masterlist_error', "Your email ({$email}) is not registered on the official voter masterlist for this election. Please contact the SAKO Election Committee.");
        }

        // Check if voter has already voted
        if ($voter->voted_at !== null) {
            return back()->withInput()->with('voter_info', "You have already successfully cast your ballot in this election on " . $voter->voted_at->format('M d, Y h:i A') . ".");
        }

        // Generate a 6-digit cryptographically secure OTP
        $otp = sprintf('%06d', random_int(0, 999999));

        // Store OTP in cache for 10 minutes with zero attempt counter
        $cacheKey = "voter_otp_{$election->id}_{$email}";
        Cache::put($cacheKey, [
            'code' => $otp,
            'attempts' => 0,
            'generated_at' => now(),
        ], now()->addMinutes(10));

        // Set pending voter in session
        session([
            'voter_pending_otp_election' => $election->id,
            'voter_pending_otp_email' => $email,
            'voter_pending_otp_name' => $voter->display_name,
        ]);

        // Send Email OTP
        try {
            Mail::to($email)->send(new VoterLoginOtp($election, $voter, $otp));
        } catch (\Throwable $e) {
            Log::error('Failed to send voter login OTP: ' . $e->getMessage());
        }

        return redirect()->route('elections.voter.verify_otp', $election->slug ?: $election->id)
            ->with('status', 'We sent a 6-digit verification code to ' . $this->maskEmail($email) . '. Please enter it below.');
    }

    /**
     * Show the OTP verification screen.
     */
    public function showVoterVerifyOtp(Election $election)
    {
        if ($election->status !== 'active') {
            return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                ->with('error', 'This election is currently not active.');
        }

        $pendingElectionId = session('voter_pending_otp_election');
        $email = session('voter_pending_otp_email');

        if (!$email || $pendingElectionId != $election->id) {
            return redirect()->route('elections.voter.login', $election->slug ?: $election->id)
                ->with('error', 'Please enter your registered email address first.');
        }

        $maskedEmail = $this->maskEmail($email);
        $voterName = session('voter_pending_otp_name', 'Voter');

        return view('committees.election-app.voter_verify_otp', compact('election', 'email', 'maskedEmail', 'voterName'));
    }

    /**
     * Submit and verify the 6-digit OTP.
     */
    public function submitVoterVerifyOtp(Request $request, Election $election)
    {
        $validated = $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $pendingElectionId = session('voter_pending_otp_election');
        $email = session('voter_pending_otp_email');

        if (!$email || $pendingElectionId != $election->id) {
            return redirect()->route('elections.voter.login', $election->slug ?: $election->id)
                ->with('error', 'Your session has expired. Please enter your email to receive a new code.');
        }

        $submittedOtp = trim($validated['otp']);
        $cacheKey = "voter_otp_{$election->id}_{$email}";
        $otpData = Cache::get($cacheKey);

        if (!$otpData) {
            return back()->with('error', 'Your verification code has expired. Please click "Resend Code" to receive a new one.');
        }

        if ($otpData['attempts'] >= 5) {
            Cache::forget($cacheKey);
            return back()->with('error', 'Too many incorrect attempts. For security, please request a new verification code.');
        }

        if ($otpData['code'] !== $submittedOtp) {
            $otpData['attempts']++;
            Cache::put($cacheKey, $otpData, now()->addMinutes(10));
            $remaining = max(0, 5 - $otpData['attempts']);
            return back()->with('error', "Invalid verification code. Please check your email carefully. ({$remaining} attempts remaining)");
        }

        // OTP is correct! Clear from cache
        Cache::forget($cacheKey);

        // Fetch and verify voter record
        $voter = $election->voters()->where('email', $email)->first();
        if (!$voter) {
            return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                ->with('masterlist_error', 'Your email is not on the official voter masterlist.');
        }

        if ($voter->voted_at !== null) {
            return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                ->with('voter_info', 'You have already cast your ballot in this election.');
        }

        // Set session state
        session([
            'voter_otp_verified_' . $election->id => true,
            'voter_email' => $email,
            'voter_name' => $voter->display_name,
            'voter_verified_at_' . $election->id => now()->toIso8601String(),
        ]);

        // Clean up pending state
        session()->forget(['voter_pending_otp_election', 'voter_pending_otp_email', 'voter_pending_otp_name']);

        if (auth()->check() && $voter->user_id === null) {
            $voter->update(['user_id' => auth()->id()]);
        }

        if (session('voter_accepted_terms_' . $election->id)) {
            return redirect()->route('elections.ballot', $election->slug ?: $election->id);
        }

        return redirect()->route('elections.voter.setup', $election->slug ?: $election->id);
    }

    /**
     * Resend a fresh 6-digit OTP code to the pending voter.
     */
    public function resendVoterOtp(Request $request, Election $election)
    {
        $pendingElectionId = session('voter_pending_otp_election');
        $email = session('voter_pending_otp_email');

        if (!$email || $pendingElectionId != $election->id) {
            return redirect()->route('elections.voter.login', $election->slug ?: $election->id);
        }

        $voter = $election->voters()->where('email', $email)->first();
        if (!$voter) {
            return redirect()->route('elections.voter.login', $election->slug ?: $election->id);
        }

        // Generate new OTP
        $otp = sprintf('%06d', random_int(0, 999999));
        $cacheKey = "voter_otp_{$election->id}_{$email}";
        Cache::put($cacheKey, [
            'code' => $otp,
            'attempts' => 0,
            'generated_at' => now(),
        ], now()->addMinutes(10));

        try {
            Mail::to($email)->send(new VoterLoginOtp($election, $voter, $otp));
        } catch (\Throwable $e) {
            Log::error('Failed to resend voter login OTP: ' . $e->getMessage());
        }

        return back()->with('status', 'A new 6-digit verification code has been dispatched to your email (' . $this->maskEmail($email) . ').');
    }

    /**
     * Logout/clear voter session for this election.
     */
    public function voterLogout(Election $election)
    {
        session()->forget([
            'voter_otp_verified_' . $election->id,
            'voter_accepted_terms_' . $election->id,
            'voter_email',
            'voter_name',
            'voter_pending_otp_email',
            'voter_pending_otp_election',
            'voter_pending_otp_name',
        ]);

        return redirect()->route('elections.public_show', $election->slug ?: $election->id)
            ->with('voter_info', 'You have signed out of this election session.');
    }

    /**
     * Show the Voter Setup Profile screen.
     */
    public function showVoterSetup(Election $election)
    {
        $email = $this->getVoterEmail();
        if (!$email || !session('voter_otp_verified_' . $election->id)) {
            return redirect()->route('elections.voter.login', $election->slug ?: $election->id)
                ->with('error', 'Please verify your identity with an Email OTP to access this election.');
        }

        // Check if election is active
        if ($election->status !== 'active') {
            return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                ->with('error', 'This election is currently not accepting votes.');
        }

        // Verify if email is on the masterlist
        $voter = $election->voters()->where('email', strtolower($email))->first();
        if (!$voter) {
            return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                ->with('masterlist_error', 'Your email (' . $email . ') is not on the official masterlist for this election. Please contact the SAKO Election Committee.');
        }

        // Check if already voted
        if ($voter->voted_at !== null) {
            return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                ->with('voter_info', 'You have already successfully cast your vote in this election!');
        }

        // If already accepted terms in this session, redirect directly to ballot
        if (session('voter_accepted_terms_' . $election->id)) {
            return redirect()->route('elections.ballot', $election->slug ?: $election->id);
        }

        return view('committees.election-app.voter_setup', compact('election', 'voter'));
    }

    /**
     * Handle submission of the Voter Setup Profile.
     */
    public function submitVoterSetup(Request $request, Election $election)
    {
        $email = $this->getVoterEmail();
        if (!$email || !session('voter_otp_verified_' . $election->id)) {
            return redirect()->route('elections.voter.login', $election->slug ?: $election->id);
        }

        if ($election->status !== 'active') {
            return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                ->with('error', 'This election is currently not active.');
        }

        $voter = $election->voters()->where('email', strtolower($email))->first();
        if (!$voter) {
            return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                ->with('masterlist_error', 'Your email (' . $email . ') is not on the official masterlist for this election. Please contact the SAKO Election Committee.');
        }

        if ($voter->voted_at !== null) {
            return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                ->with('voter_info', 'You have already cast your ballot in this election.');
        }

        if (auth()->check()) {
            $voter->update([
                'user_id' => auth()->id(),
                'name' => $voter->name ?: auth()->user()->name,
            ]);
        }

        session(['voter_accepted_terms_' . $election->id => true]);

        return redirect()->route('elections.ballot', $election->slug ?: $election->id);
    }

    /**
     * Show the interactive Voting Ballot booth.
     */
    public function showBallot(Election $election)
    {
        $email = $this->getVoterEmail();
        if (!$email || !session('voter_otp_verified_' . $election->id)) {
            return redirect()->route('elections.voter.login', $election->slug ?: $election->id)
                ->with('error', 'Please verify your identity with an Email OTP to access this election.');
        }

        if ($election->status !== 'active') {
            return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                ->with('error', 'This election is currently not active.');
        }

        // Ensure voter record exists on the masterlist
        $voter = $election->voters()->where('email', strtolower($email))->first();
        if (!$voter) {
            return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                ->with('masterlist_error', 'Your email (' . $email . ') is not on the official masterlist for this election. Please contact the SAKO Election Committee.');
        }

        // Ensure terms have been accepted
        if (!session('voter_accepted_terms_' . $election->id)) {
            return redirect()->route('elections.voter.setup', $election->slug ?: $election->id);
        }

        // Ensure they haven't voted yet
        if ($voter->voted_at !== null) {
            return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                ->with('voter_info', 'You have already successfully cast your vote in this election!');
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
        if (!$email || !session('voter_otp_verified_' . $election->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated voter session. Please verify your identity with an Email OTP.'
            ], 401);
        }

        if ($election->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'This election is currently not accepting votes.'
            ], 403);
        }

        $voter = $election->voters()->where('email', strtolower($email))->first();
        if (!$voter) {
            return response()->json([
                'success' => false,
                'message' => 'Your email is not on the official masterlist for this election. Please contact the SAKO Election Committee.'
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

                // 3. Complete ballot casting securely with client IP audit
                $clientIp = $request->header('CF-Connecting-IP') ?? $request->header('X-Forwarded-For') ?? $request->ip();
                if ($clientIp && str_contains($clientIp, ',')) {
                    $clientIp = trim(explode(',', $clientIp)[0]);
                }

                $voter->update([
                    'voted_at' => now(),
                    'ip_address' => $clientIp,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Ballot submitted and recorded successfully! Thank you for voting.',
                'redirect' => route('elections.ballot', $election->slug ?: $election->id)
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
     * Export the official election results as a clean, print-ready PDF using DomPDF.
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
                $q->withCount('votes')->orderByDesc('votes_count')->orderBy('id');
            }])
            ->withCount('votes')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $totalMasterlistVoters = $election->voters()->count();
        $totalVotesCast = $election->voters()->whereNotNull('voted_at')->count();
        $turnoutPercentage = $totalMasterlistVoters > 0
            ? round(($totalVotesCast / $totalMasterlistVoters) * 100, 1)
            : 0;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'committees.election-app.pdf_formats.clean_results_pdf',
            compact('election', 'positions', 'totalMasterlistVoters', 'totalVotesCast', 'turnoutPercentage')
        );

        $filename = "official_election_results_" . str_replace(' ', '_', strtolower($election->title)) . ".pdf";

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
                    'ip_address' => $voter->ip_address,
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
                'ip_address' => $voter->ip_address,
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
     * Download a clean sample CSV template for masterlist voter import.
     */
    public function downloadSampleVotersCsv(Election $election)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="sako_voters_masterlist_template.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM so Excel opens it with proper encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header Row (Exact column format matching the Voter Register)
            fputcsv($file, ['Voter / Account', 'Division / Workplace', 'Gender', 'Age']);
            
            // Sample Data Rows
            fputcsv($file, ['juan.delacruz@mlhuillier.com', 'Head Office - ICT', 'Male', '32']);
            fputcsv($file, ['maria.santos@mlhuillier.com', 'Luzon Area Operations', 'Female', '28']);
            fputcsv($file, ['alex.reyes@mlhuillier.com', 'Visayas Regional Office', 'Others', '35']);
            fputcsv($file, ['carmen.ramos@mlhuillier.com', 'Mindanao Regional Office', 'Female', '41']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import a CSV file into the election voter masterlist with strict column and data validation.
     */
    public function importVoters(Request $request, Election $election)
    {
        $request->validate([
            'csv_file' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('csv_file');
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['csv', 'txt'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file format: Please upload a valid .csv file.'
            ], 422);
        }

        $path = $file->getRealPath();
        $handle = fopen($path, 'r');
        if (!$handle) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to read the uploaded CSV file. Please verify the file and try again.'
            ], 422);
        }

        // Read first line (Row 1 Header)
        $rawHeaders = fgetcsv($handle);
        if (!$rawHeaders || empty(array_filter($rawHeaders))) {
            fclose($handle);
            return response()->json([
                'success' => false,
                'message' => 'The uploaded CSV file is empty. Please ensure Row 1 contains table headers.'
            ], 422);
        }

        // Strip UTF-8 BOM from the first header if present
        $rawHeaders[0] = preg_replace('/[\x{FEFF}\x{EF}\x{BB}\x{BF}]/u', '', $rawHeaders[0]);

        // Normalize header strings
        $normalizedHeaders = [];
        foreach ($rawHeaders as $idx => $h) {
            $normalized = strtolower(trim((string)$h));
            $normalized = preg_replace('/[^a-z0-9\/_]/', ' ', $normalized);
            $normalized = trim(preg_replace('/\s+/', ' ', $normalized));
            $normalizedHeaders[$idx] = $normalized;
        }

        // Backend Column Checker: Look for required and optional columns
        $emailColIdx = null;
        $nameColIdx = null;
        $divisionColIdx = null;
        $genderColIdx = null;
        $ageColIdx = null;

        foreach ($normalizedHeaders as $idx => $header) {
            if ($emailColIdx === null && (
                str_contains($header, 'voter / account') ||
                str_contains($header, 'voter account') ||
                str_contains($header, 'email account') ||
                str_contains($header, 'email') ||
                str_contains($header, 'account') ||
                str_contains($header, 'voter')
            )) {
                $emailColIdx = $idx;
            }

            if ($nameColIdx === null && (
                str_contains($header, 'voter name') ||
                str_contains($header, 'full name') ||
                $header === 'name'
            )) {
                $nameColIdx = $idx;
            }

            if ($divisionColIdx === null && (
                str_contains($header, 'division') ||
                str_contains($header, 'workplace')
            )) {
                $divisionColIdx = $idx;
            }

            if ($genderColIdx === null && (
                str_contains($header, 'gender') ||
                str_contains($header, 'sex')
            )) {
                $genderColIdx = $idx;
            }

            if ($ageColIdx === null && str_contains($header, 'age')) {
                $ageColIdx = $idx;
            }
        }

        // Enforce required column: Voter / Account (or Email)
        if ($emailColIdx === null) {
            fclose($handle);
            $foundColumnsList = implode(', ', array_filter(array_map('trim', $rawHeaders)));
            return response()->json([
                'success' => false,
                'message' => "CSV Column Validation Failed: Missing required column 'Voter / Account' (or 'Email') in Row 1. Found headers: [{$foundColumnsList}]. Row 1 must match the required columns: Voter / Account, Division / Workplace, Gender, Age."
            ], 422);
        }

        $importedNew = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $warnings = [];
        $rowNumber = 1;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                // Skip completely empty lines
                if (empty(array_filter($row, fn($val) => trim((string)$val) !== ''))) {
                    continue;
                }

                $rawVoterVal = isset($row[$emailColIdx]) ? trim((string)$row[$emailColIdx]) : '';
                if ($rawVoterVal === '') {
                    $skippedCount++;
                    $warnings[] = "Row {$rowNumber}: Missing voter email address.";
                    continue;
                }

                // Extract valid email address from raw value
                $email = null;
                $extractedName = null;

                if (filter_var($rawVoterVal, FILTER_VALIDATE_EMAIL)) {
                    $email = strtolower($rawVoterVal);
                } elseif (preg_match('/<([^>]+)>/', $rawVoterVal, $matches) && filter_var($matches[1], FILTER_VALIDATE_EMAIL)) {
                    $email = strtolower($matches[1]);
                    $extractedName = trim(str_replace($matches[0], '', $rawVoterVal));
                } elseif (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $rawVoterVal, $matches)) {
                    $email = strtolower($matches[0]);
                    $extractedName = trim(str_replace($matches[0], '', $rawVoterVal));
                }

                if (!$email) {
                    $skippedCount++;
                    $warnings[] = "Row {$rowNumber}: '{$rawVoterVal}' is not a valid email address.";
                    continue;
                }

                // Determine Name
                $name = null;
                if ($nameColIdx !== null && isset($row[$nameColIdx]) && trim((string)$row[$nameColIdx]) !== '') {
                    $name = trim((string)$row[$nameColIdx]);
                } elseif ($extractedName) {
                    $name = $extractedName;
                }

                // Determine Division
                $division = null;
                if ($divisionColIdx !== null && isset($row[$divisionColIdx])) {
                    $divVal = trim((string)$row[$divisionColIdx]);
                    $division = ($divVal !== '' && $divVal !== '-') ? substr($divVal, 0, 255) : null;
                }

                // Determine Gender
                $gender = null;
                if ($genderColIdx !== null && isset($row[$genderColIdx])) {
                    $genVal = trim((string)$row[$genderColIdx]);
                    $gender = ($genVal !== '' && $genVal !== '-') ? substr($genVal, 0, 255) : null;
                }

                // Determine Age
                $age = null;
                if ($ageColIdx !== null && isset($row[$ageColIdx])) {
                    $ageVal = trim((string)$row[$ageColIdx]);
                    if (is_numeric($ageVal) && (int)$ageVal >= 18 && (int)$ageVal <= 120) {
                        $age = (int)$ageVal;
                    }
                }

                // Check if voter already exists for this election
                $existingVoter = $election->voters()->where('email', $email)->first();

                if ($existingVoter) {
                    $updatePayload = [];
                    if ($name && empty($existingVoter->name)) $updatePayload['name'] = $name;
                    if ($division) $updatePayload['division'] = $division;
                    if ($gender) $updatePayload['gender'] = $gender;
                    if ($age) $updatePayload['age'] = $age;

                    if (!empty($updatePayload)) {
                        $existingVoter->update($updatePayload);
                    }
                    $updatedCount++;
                } else {
                    $election->voters()->create([
                        'email' => $email,
                        'name' => $name,
                        'division' => $division,
                        'gender' => $gender,
                        'age' => $age,
                    ]);
                    $importedNew++;
                }
            }

            DB::commit();
            fclose($handle);

            $totalProcessed = $importedNew + $updatedCount;

            return response()->json([
                'success' => true,
                'message' => "CSV Import Completed: {$totalProcessed} total voters processed ({$importedNew} newly enrolled, {$updatedCount} updated on masterlist).",
                'stats' => [
                    'new' => $importedNew,
                    'updated' => $updatedCount,
                    'skipped' => $skippedCount,
                    'warnings' => array_slice($warnings, 0, 5)
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return response()->json([
                'success' => false,
                'message' => 'Import Error: ' . $e->getMessage()
            ], 500);
        }
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
            fputcsv($file, ['Voter Name', 'Email Account', 'Division / Workplace', 'Gender', 'Age', 'Voting Status', 'Submission Time', 'IP Address']);

            foreach ($voters as $voter) {
                fputcsv($file, [
                    $voter->display_name,
                    $voter->email,
                    $voter->division ?? '-',
                    $voter->gender ?? '-',
                    $voter->age ?? '-',
                    $voter->voted_at ? 'Voted' : 'In Queue',
                    $voter->voted_at ? $voter->voted_at->format('Y-m-d H:i:s') : '-',
                    $voter->ip_address ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
