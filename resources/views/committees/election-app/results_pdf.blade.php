<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Official Election Results: {{ $election->title }}</title>
    <style>
        @page {
            margin: 50px 40px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #7c3aed;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header-title {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 4px 0;
        }
        .header-subtitle {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #7c3aed;
            margin: 0 0 8px 0;
        }
        .header-meta {
            font-size: 10px;
            color: #64748b;
        }
        .summary-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 25px;
        }
        .summary-box table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        .summary-box td {
            width: 33.33%;
            text-align: center;
            border: none;
            padding: 0;
        }
        .summary-val {
            font-size: 18px;
            font-weight: bold;
            color: #7c3aed;
        }
        .summary-lbl {
            font-size: 9px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            margin-top: 2px;
        }

        /* Winners Showcase Styles */
        .winners-container {
            background-color: #faf5ff;
            border: 2px solid #ddd6fe;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .winners-title {
            font-size: 14px;
            font-weight: bold;
            color: #5b21b6;
            margin-bottom: 12px;
            border-bottom: 1px solid #e9d5ff;
            padding-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table.winners-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.winners-table td {
            padding: 8px 0;
            vertical-align: top;
            border-bottom: 1px solid #f3e8ff;
        }
        table.winners-table tr:last-child td {
            border-bottom: none;
            padding-bottom: 0;
        }
        .win-pos-name {
            font-weight: bold;
            font-size: 11px;
            color: #0f172a;
        }
        .win-pos-meta {
            font-size: 8.5px;
            color: #64748b;
            margin-top: 1px;
        }

        /* Results List Styles */
        .position-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .position-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            background-color: #f1f5f9;
            padding: 6px 10px;
            border-left: 4px solid #7c3aed;
            margin-bottom: 10px;
        }
        .position-meta {
            font-size: 9.5px;
            color: #64748b;
            font-weight: normal;
            margin-left: 8px;
        }
        table.results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.results-table th, table.results-table td {
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        table.results-table th {
            background-color: #faf5ff;
            color: #7c3aed;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .cand-name {
            font-weight: bold;
            color: #0f172a;
        }
        .cand-party {
            font-size: 9.5px;
            color: #64748b;
            margin-top: 1px;
        }
        .progress-bg {
            background-color: #f1f5f9;
            height: 8px;
            border-radius: 4px;
            width: 120px;
            display: inline-block;
            vertical-align: middle;
            margin-right: 6px;
        }
        .progress-bar {
            background-color: #7c3aed;
            height: 8px;
            border-radius: 4px;
        }
        .percentage-text {
            font-weight: bold;
            font-size: 10px;
            color: #1e293b;
            display: inline-block;
            vertical-align: middle;
            width: 35px;
            text-align: right;
        }
        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8.5px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-subtitle">Official Cooperative Election Results Report</div>
        <div class="header-title">{{ $election->title }}</div>
        <div class="header-meta">
            Generated on: {{ now()->format('F d, Y • h:i A') }} &nbsp;&bull;&nbsp; Status: {{ ucfirst($election->status) }}
        </div>
    </div>

    <div class="summary-box">
        <table>
            <tr>
                <td>
                    <div class="summary-val">{{ $positions->count() }}</div>
                    <div class="summary-lbl">Total Positions</div>
                </td>
                <td>
                    <div class="summary-val">{{ $voterCount }}</div>
                    <div class="summary-lbl">Verified Ballots Cast</div>
                </td>
                <td>
                    <div class="summary-val">{{ ucfirst($election->status) }}</div>
                    <div class="summary-lbl">Election State</div>
                </td>
            </tr>
        </table>
    </div>

    @if($election->status === 'closed')
        <!-- CERTIFIED ELECTION WINNERS SHOWCASE -->
        <div class="winners-container">
            <div class="winners-title">🏆 Official Certified Winners</div>
            <table class="winners-table">
                @foreach($positions as $position)
                    @php
                        $winners = $position->candidates->sortByDesc('votes_count')->take($position->max_votes);
                        $hasVotes = $position->votes_count > 0;
                    @endphp
                    <tr>
                        <td class="winners-position" style="width: 35%; padding-right: 15px;">
                            <div class="win-pos-name">{{ $position->name }}</div>
                            <div class="win-pos-meta">Seats: {{ $position->max_votes }} &bull; Total Votes: {{ $position->votes_count }}</div>
                        </td>
                        <td class="winners-list" style="width: 65%;">
                            @if($hasVotes)
                                <table style="width: 100%; margin: 0; border: none;">
                                    @foreach($winners as $winner)
                                        @php
                                            $percent = $position->votes_count > 0 ? round(($winner->votes_count / $position->votes_count) * 100, 1) : 0;
                                        @endphp
                                        <tr style="border: none;">
                                            <td style="padding: 2px 0; border: none; font-weight: bold; color: #0f172a; font-size: 10.5px;">
                                                ⭐ {{ $winner->name }}
                                                <span style="font-weight: normal; font-size: 8.5px; color: #64748b;">({{ $winner->party_affiliation ?? 'Independent' }})</span>
                                            </td>
                                            <td style="padding: 2px 0; border: none; text-align: right; font-weight: bold; color: #7c3aed; width: 120px; font-size: 10px;">
                                                {{ $winner->votes_count }} votes ({{ $percent }}%)
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            @else
                                <div style="color: #94a3b8; font-style: italic; font-size: 10px; padding: 2px 0;">No votes recorded.</div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endif

    @foreach($positions as $position)
        <div class="position-section">
            <div class="position-title">
                {{ $position->name }}
                <span class="position-meta">(Total Position Votes: {{ $position->votes_count }} &bull; Seats: {{ $position->max_votes }})</span>
            </div>

            <table class="results-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Candidate Name & Party</th>
                        <th style="width: 20%; text-align: right;">Votes Count</th>
                        <th style="width: 30%; text-align: right;">Vote Share Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalPositionVotes = $position->votes_count; @endphp
                    @forelse($position->candidates as $candidate)
                        @php
                            $votes = $candidate->votes_count;
                            $percent = $totalPositionVotes > 0 ? round(($votes / $totalPositionVotes) * 100, 1) : 0;
                        @endphp
                        <tr>
                            <td>
                                <div class="cand-name">{{ $candidate->name }}</div>
                                <div class="cand-party">{{ $candidate->party_affiliation ?? 'Independent' }}</div>
                            </td>
                            <td style="text-align: right; font-weight: bold; font-size: 12px; color: #0f172a;">
                                {{ $votes }}
                            </td>
                            <td style="text-align: right; white-space: nowrap; padding-top: 10px;">
                                <div class="progress-bg">
                                    <div class="progress-bar" style="width: {{ $percent }}%;"></div>
                                </div>
                                <span class="percentage-text">{{ $percent }}%</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: #94a3b8; font-style: italic;">
                                No candidates declared for this position.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="footer">
        Independent Group Election Committee &bull; Corporate Registry Consensus Report
    </div>

</body>
</html>
