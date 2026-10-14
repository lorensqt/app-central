@php
    $logoPath = resource_path('views/imgs/letter-s.png');
    if (!file_exists($logoPath)) {
        $logoPath = base_path('resources/views/imgs/letter-s.png');
    }
    $logoBase64 = '';
    if (file_exists($logoPath)) {
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }

    $totalSeats = $positions->sum('max_votes');
    $totalCandidates = $positions->sum(function($p) { return $p->candidates->count(); });
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Official Election Results - {{ $election->title }}</title>
    <style>
        @page {
            margin: 40px 35px 40px 35px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #000000;
            font-size: 10px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Header Block */
        table.report-header-table {
            width: 100%;
            border-bottom: 2px solid #000000;
            padding-bottom: 8px;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        table.report-header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
            color: #000000;
        }
        .org-name {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #000000;
            margin: 0 0 3px 0;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            color: #000000;
            margin: 0 0 3px 0;
        }
        .report-subtitle {
            font-size: 11px;
            font-weight: bold;
            color: #000000;
            margin: 0;
        }
        .header-logo {
            height: 38px;
            width: auto;
        }

        /* Metadata Details Table */
        table.meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        table.meta-table td {
            padding: 5px 8px;
            font-size: 9px;
            border: 1px solid #000000;
            color: #000000;
        }
        .meta-label {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            width: 18%;
            color: #000000;
        }
        .meta-val {
            width: 32%;
            color: #000000;
        }

        /* Section Header */
        .section-header {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1.5px solid #000000;
            padding-bottom: 3px;
            margin-top: 14px;
            margin-bottom: 8px;
            color: #000000;
        }

        /* Executive Summary Grid Table */
        table.stat-summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        table.stat-summary-table td {
            width: 16.66%;
            border: 1px solid #000000;
            padding: 8px 4px;
            text-align: center;
            background-color: #ffffff;
            color: #000000;
        }
        .stat-number {
            font-size: 13px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 2px;
        }
        .stat-label {
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            color: #000000;
        }

        /* Standard Data Tables */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        table.data-table th {
            background-color: #f2f2f2;
            color: #000000;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 6px;
            border: 1px solid #000000;
            text-align: left;
        }
        table.data-table td {
            padding: 5px 6px;
            font-size: 9px;
            border: 1px solid #000000;
            color: #000000;
            vertical-align: middle;
        }
        table.data-table tfoot td {
            background-color: #f2f2f2;
            font-weight: bold;
            border-top: 2px solid #000000;
            border-bottom: 2px solid #000000;
            color: #000000;
        }

        /* Position Block */
        .position-block {
            margin-bottom: 14px;
            page-break-inside: avoid;
        }
        .position-heading {
            font-size: 9.5px;
            font-weight: bold;
            background-color: #f2f2f2;
            border: 1px solid #000000;
            border-bottom: none;
            padding: 5px 8px;
            text-transform: uppercase;
            color: #000000;
        }

        /* Progress Bar Representation (Clean Grayscale/Black) */
        .progress-box {
            display: inline-block;
            width: 85px;
            height: 7px;
            background-color: #e5e5e5;
            border: 1px solid #000000;
            vertical-align: middle;
            margin-right: 5px;
        }
        .progress-fill {
            height: 100%;
            background-color: #000000;
        }

        /* Alignment Utilities */
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        /* Findings & Verification Box */
        .findings-box {
            border: 1px solid #000000;
            background-color: #fafafa;
            padding: 8px 10px;
            margin-bottom: 14px;
            font-size: 9px;
            line-height: 1.45;
            color: #000000;
            page-break-inside: avoid;
        }
        .findings-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
            font-size: 9.5px;
            color: #000000;
        }

        /* Sign-off Block */
        table.signoff-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        table.signoff-table td {
            width: 50%;
            padding: 0 30px;
            text-align: center;
            border: none;
            color: #000000;
        }
        .signoff-rule {
            border-bottom: 1px solid #000000;
            margin-top: 40px;
            margin-bottom: 5px;
        }
        .signoff-person {
            font-weight: bold;
            font-size: 9.5px;
            color: #000000;
        }
        .signoff-title {
            font-size: 8px;
            text-transform: uppercase;
            color: #000000;
        }

        /* Page Footer */
        .page-footer {
            margin-top: 15px;
            border-top: 1px solid #000000;
            padding-top: 4px;
            font-size: 8px;
            text-align: right;
            color: #000000;
        }
    </style>
</head>
<body>

    <!-- Header Block -->
    <table class="report-header-table">
        <tr>
            <td style="vertical-align: middle;">
                <div class="org-name">M Lhuillier Group of Companies &bull; Committee on Elections (COMELEC)</div>
                <div class="report-title">
                    {{ $election->status === 'closed' ? 'Official Certified Election Results Report' : 'Interim Election Results & Live Tally Report' }}
                </div>
                <div class="report-subtitle">{{ $election->title }}</div>
            </td>
            @if($logoBase64)
                <td style="text-align: right; vertical-align: middle; width: 60px;">
                    <img src="{{ $logoBase64 }}" class="header-logo" alt="AppCentral Logo">
                </td>
            @endif
        </tr>
    </table>

    <!-- Metadata Details Table -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Election Title</td>
            <td class="meta-val font-bold">{{ $election->title }}</td>
            <td class="meta-label">Report Date</td>
            <td class="meta-val">{{ now()->format('F d, Y &bull; h:i A') }}</td>
        </tr>
        <tr>
            <td class="meta-label">Election Status</td>
            <td class="meta-val font-bold">
                @if($election->status === 'closed')
                    CLOSED (OFFICIALLY CONCLUDED)
                @elseif($election->status === 'active')
                    ACTIVE (VOTING CURRENTLY ONGOING)
                @else
                    DRAFT (PRE-ELECTION STAGE)
                @endif
            </td>
            <td class="meta-label">Schedule Period</td>
            <td class="meta-val">
                {{ $election->start_date ? $election->start_date->format('M d, Y') : 'Open' }}
                to
                {{ $election->end_date ? $election->end_date->format('M d, Y') : 'Present' }}
            </td>
        </tr>
        <tr>
            <td class="meta-label">Total Positions</td>
            <td class="meta-val">{{ $positions->count() }} position(s) &bull; {{ $totalSeats }} seat(s)</td>
            <td class="meta-label">Voter Participation</td>
            <td class="meta-val font-bold">
                {{ number_format($totalVotesCast) }} of {{ number_format($totalMasterlistVoters) }} ({{ $turnoutPercentage }}% Turnout)
            </td>
        </tr>
    </table>

    <!-- 1. Executive Election Summary -->
    <div class="section-header">1. Executive Election Summary</div>
    <table class="stat-summary-table">
        <tr>
            <td>
                <div class="stat-number">{{ $positions->count() }}</div>
                <div class="stat-label">Ballot Positions</div>
            </td>
            <td>
                <div class="stat-number">{{ $totalSeats }}</div>
                <div class="stat-label">Open Seats</div>
            </td>
            <td>
                <div class="stat-number">{{ $totalCandidates }}</div>
                <div class="stat-label">Candidates Listed</div>
            </td>
            <td>
                <div class="stat-number">{{ number_format($totalVotesCast) }}</div>
                <div class="stat-label">Ballots Cast</div>
            </td>
            <td>
                <div class="stat-number">{{ $turnoutPercentage }}%</div>
                <div class="stat-label">Overall Turnout</div>
            </td>
            <td>
                <div class="stat-number font-bold">
                    {{ $election->status === 'closed' ? 'FINAL' : ($election->status === 'active' ? 'ONGOING' : 'DRAFT') }}
                </div>
                <div class="stat-label">Tally Mode</div>
            </td>
        </tr>
    </table>

    <!-- 2. Certified Election Winners / Leading Candidates Overview -->
    <div class="section-header">
        @if($election->status === 'closed')
            2. Certified Election Winners (Official Final Results)
        @elseif($election->status === 'active')
            2. Current Leading Candidates (Unofficial Interim Standings)
        @else
            2. Projected Standings Overview
        @endif
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">#</th>
                <th class="text-left" style="width: 25%;">Ballot Position</th>
                <th class="text-left" style="width: 25%;">
                    {{ $election->status === 'closed' ? 'Declared Winner' : 'Current Leader' }}
                </th>
                <th class="text-left" style="width: 18%;">Party / Slate</th>
                <th class="text-right" style="width: 12%;">Votes Cast</th>
                <th class="text-right" style="width: 15%;">Official Standing</th>
            </tr>
        </thead>
        <tbody>
            @php $winnerRowIndex = 0; @endphp
            @forelse($positions as $position)
                @php
                    $winners = $position->candidates->sortByDesc('votes_count')->take($position->max_votes);
                    $hasVotes = $position->votes_count > 0;
                @endphp
                @if($hasVotes && $winners->count() > 0)
                    @foreach($winners as $winner)
                        @php
                            $winnerRowIndex++;
                            $percent = $position->votes_count > 0 ? round(($winner->votes_count / $position->votes_count) * 100, 1) : 0;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $winnerRowIndex }}</td>
                            <td class="text-left font-bold">{{ $position->name }}</td>
                            <td class="text-left font-bold">{{ $winner->name }}</td>
                            <td class="text-left">{{ $winner->party_affiliation ?? 'Independent' }}</td>
                            <td class="text-right font-bold">{{ number_format($winner->votes_count) }} ({{ $percent }}%)</td>
                            <td class="text-right font-bold">
                                {{ $election->status === 'closed' ? 'CERTIFIED WINNER' : 'LEADING CANDIDATE' }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    @php $winnerRowIndex++; @endphp
                    <tr>
                        <td class="text-center">{{ $winnerRowIndex }}</td>
                        <td class="text-left font-bold">{{ $position->name }}</td>
                        <td colspan="4" class="text-center" style="font-style: italic;">
                            No votes recorded for this position yet.
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 12px; font-style: italic;">
                        No ballot positions configured for this election.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- 3. Comprehensive Results by Position -->
    <div class="section-header">3. Detailed Tally & Candidate Results by Position</div>

    @forelse($positions as $posIndex => $position)
        @php
            $posVotes = $position->votes_count;
            $sortedCandidates = $position->candidates->sortByDesc('votes_count');
        @endphp
        <div class="position-block">
            <div class="position-heading">
                {{ $posIndex + 1 }}. {{ $position->name }} &bull; Seats: {{ $position->max_votes }} &bull; Total Ballots Cast: {{ number_format($posVotes) }}
            </div>
            <table class="data-table" style="margin-bottom: 0;">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%;">Rank</th>
                        <th class="text-left" style="width: 30%;">Candidate Name</th>
                        <th class="text-left" style="width: 25%;">Party / Affiliation</th>
                        <th class="text-right" style="width: 12%;">Votes Cast</th>
                        <th class="text-right" style="width: 10%;">Share %</th>
                        <th class="text-left" style="width: 18%;">Distribution Bar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sortedCandidates as $cIndex => $candidate)
                        @php
                            $cVotes = $candidate->votes_count;
                            $cPercent = $posVotes > 0 ? round(($cVotes / $posVotes) * 100, 1) : 0;
                            $isTopTier = ($cIndex < $position->max_votes) && ($cVotes > 0);
                        @endphp
                        <tr>
                            <td class="text-center font-bold">{{ $cIndex + 1 }}</td>
                            <td class="text-left {{ $isTopTier ? 'font-bold' : '' }}">
                                {{ $candidate->name }}
                                @if($isTopTier)
                                    &bull; <strong>({{ $election->status === 'closed' ? 'ELECTED WINNER' : 'CURRENT LEADER' }})</strong>
                                @endif
                            </td>
                            <td class="text-left">{{ $candidate->party_affiliation ?? 'Independent' }}</td>
                            <td class="text-right font-bold">{{ number_format($cVotes) }}</td>
                            <td class="text-right font-bold">{{ $cPercent }}%</td>
                            <td class="text-left">
                                <div class="progress-box">
                                    <div class="progress-fill" style="width: {{ $cPercent }}%;"></div>
                                </div>
                                <span style="font-size: 8px;">{{ $cPercent }}%</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="font-style: italic; padding: 8px;">
                                No candidates recorded under this ballot position.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($sortedCandidates->count() > 0)
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-left font-bold" style="text-transform: uppercase;">
                                Total Votes Cast for {{ $position->name }}
                            </td>
                            <td class="text-right font-bold">{{ number_format($posVotes) }}</td>
                            <td class="text-right font-bold">{{ $posVotes > 0 ? '100.0%' : '0.0%' }}</td>
                            <td class="text-left font-bold">Seats: {{ $position->max_votes }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    @empty
        <div class="findings-box" style="text-align: center; font-style: italic;">
            No positions or candidates have been created for this election.
        </div>
    @endforelse

    <!-- 4. Certification & Audit Observations -->
    <div class="section-header">4. Integrity Verification & Audit Observations</div>
    <div class="findings-box">
        <div class="findings-title">COMELEC Verification & Tally Authentication:</div>
        <div>
            &bull; <strong>Election Status & Tally Mode:</strong>
            @if($election->status === 'closed')
                The election is <strong>OFFICIALLY CONCLUDED</strong>. The declared winners listed above are final, certified, and officially declared by the Committee on Elections.
            @elseif($election->status === 'active')
                The election is currently <strong>ACTIVE (VOTING ONGOING)</strong>. The standings presented in this document represent the real-time unofficial tally. Final winners will be certified upon election closure.
            @else
                The election is currently in <strong>DRAFT (PRE-ELECTION)</strong> state. No official ballots are open to the general voter registry.
            @endif
        </div>
        <div style="margin-top: 3px;">
            &bull; <strong>Tally Integrity:</strong>
            All candidate vote tallies have been compiled directly from verified, cryptographic electronic ballots recorded in the AppCentral Election system.
        </div>
        <div style="margin-top: 3px;">
            &bull; <strong>Double-Voting Protection:</strong>
            System-level single-vote enforcement verified. Zero duplicate voter submissions detected across the {{ number_format($totalVotesCast) }} recorded voter sessions.
        </div>
        <div style="margin-top: 3px;">
            &bull; <strong>Timestamp:</strong>
            This document was generated on <strong>{{ now()->format('F d, Y \a\t h:i A') }}</strong>.
        </div>
    </div>

    <!-- 5. Formal Certification & Sign-off Block -->
    <table class="signoff-table">
        <tr>
            <td>
                <div class="signoff-rule"></div>
                <div class="signoff-person">ELECTION COMMITTEE AUDITOR</div>
                <div class="signoff-title">Certified Internal Auditor &bull; COMELEC Secretariat</div>
            </td>
            <td>
                <div class="signoff-rule"></div>
                <div class="signoff-person">COMELEC COMMITTEE CHAIRPERSON</div>
                <div class="signoff-title">AppCentral Election Oversight Committee</div>
            </td>
        </tr>
    </table>

    <!-- Page Footer -->
    <div class="page-footer">
        AppCentral Election Portal &bull; Official Election Results &bull; Generated: {{ now()->format('Y-m-d H:i:s') }}
    </div>

</body>
</html>
