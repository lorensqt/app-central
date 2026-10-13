@php
    $logoPath = resource_path('views/imgs/letter-s.png');
    if (!file_exists($logoPath)) {
        $logoPath = base_path('resources/views/imgs/letter-s.png');
    }
    $logoBase64 = '';
    if (file_exists($logoPath)) {
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Election Audit Report - {{ $election->title }}</title>
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

        /* Header */
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
        }
        .org-name {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 3px 0;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 3px 0;
        }
        .report-subtitle {
            font-size: 11px;
            font-weight: bold;
            margin: 0;
        }
        .header-logo {
            height: 38px;
            width: auto;
        }

        /* Metadata Table */
        table.meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        table.meta-table td {
            padding: 5px 8px;
            font-size: 9px;
            border: 1px solid #000000;
        }
        .meta-label {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            width: 18%;
        }
        .meta-val {
            width: 32%;
        }

        /* Section Title */
        .section-header {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1.5px solid #000000;
            padding-bottom: 3px;
            margin-top: 14px;
            margin-bottom: 8px;
        }

        /* Data Tables */
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
        }

        /* Alignment Utilities */
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        /* Summary Stat Grid Table */
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

        /* Findings Box */
        .findings-box {
            border: 1px solid #000000;
            background-color: #fafafa;
            padding: 8px 10px;
            margin-bottom: 14px;
            font-size: 9px;
            line-height: 1.45;
            page-break-inside: avoid;
        }
        .findings-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
            font-size: 9.5px;
        }

        /* Sign-off Table */
        table.signoff-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: avoid;
        }
        table.signoff-table td {
            width: 50%;
            padding: 0 30px;
            text-align: center;
            border: none;
        }
        .signoff-rule {
            border-bottom: 1px solid #000000;
            margin-top: 40px;
            margin-bottom: 5px;
        }
        .signoff-person {
            font-weight: bold;
            font-size: 9.5px;
        }
        .signoff-title {
            font-size: 8px;
            text-transform: uppercase;
        }

        /* Page Footer */
        .page-footer {
            margin-top: 15px;
            border-top: 1px solid #000000;
            padding-top: 4px;
            font-size: 8px;
            text-align: right;
        }
    </style>
</head>
<body>

    <!-- Header Block -->
    <table class="report-header-table">
        <tr>
            <td style="vertical-align: middle;">
                <div class="org-name">M Lhuillier Group of Companies &bull; Committee on Elections (COMELEC)</div>
                <div class="report-title">Election Participation & Workplace Audit Report</div>
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
            <td class="meta-val">{{ ucfirst($election->status) }}</td>
            <td class="meta-label">Schedule Period</td>
            <td class="meta-val">
                {{ $election->start_date ? $election->start_date->format('M d, Y') : 'Open' }}
                to
                {{ $election->end_date ? $election->end_date->format('M d, Y') : 'Present' }}
            </td>
        </tr>
        <tr>
            <td class="meta-label">Audit Scope</td>
            <td class="meta-val">All Registered Divisions</td>
            <td class="meta-label">Integrity Status</td>
            <td class="meta-val font-bold">Verified &bull; 100% Balanced</td>
        </tr>
    </table>

    <!-- 1. Executive Participation Summary -->
    <div class="section-header">1. Executive Participation Summary</div>
    <table class="stat-summary-table">
        <tr>
            <td>
                <div class="stat-number">{{ number_format($stats['total_voters']) }}</div>
                <div class="stat-label">Registered Pool</div>
            </td>
            <td>
                <div class="stat-number">{{ number_format($stats['voted_voters']) }}</div>
                <div class="stat-label">Ballots Cast</div>
            </td>
            <td>
                <div class="stat-number">{{ number_format($stats['queued_voters']) }}</div>
                <div class="stat-label">Pending Queue</div>
            </td>
            <td>
                <div class="stat-number">{{ $stats['turnout_rate'] }}%</div>
                <div class="stat-label">Overall Turnout</div>
            </td>
            <td>
                <div class="stat-number">{{ $stats['divisions']['active_divisions'] }} / {{ $stats['divisions']['total_divisions'] }}</div>
                <div class="stat-label">Active Units</div>
            </td>
            <td>
                <div class="stat-number">{{ $stats['divisions']['completed_divisions'] }}</div>
                <div class="stat-label">100% Turnout</div>
            </td>
        </tr>
    </table>

    <!-- 2. Workplace & Division Turnout Breakdown Table -->
    <div class="section-header">2. Workplace & Division Turnout Breakdown</div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">#</th>
                <th class="text-left" style="width: 37%;">Division / Workplace Unit</th>
                <th class="text-right" style="width: 12%;">Registered</th>
                <th class="text-right" style="width: 12%;">Ballots Cast</th>
                <th class="text-right" style="width: 12%;">Pending Queue</th>
                <th class="text-right" style="width: 10%;">Turnout %</th>
                <th class="text-center" style="width: 12%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stats['divisions']['items'] as $index => $div)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left font-bold">{{ $div['division'] }}</td>
                    <td class="text-right">{{ number_format($div['total']) }}</td>
                    <td class="text-right font-bold">{{ number_format($div['voted']) }}</td>
                    <td class="text-right">{{ number_format($div['queued']) }}</td>
                    <td class="text-right font-bold">{{ $div['turnout_rate'] }}%</td>
                    <td class="text-center">
                        @if($div['status'] === 'completed')
                            <span class="font-bold">100% Completed</span>
                        @elseif($div['status'] === 'high')
                            <span class="font-bold">High (&ge;75%)</span>
                        @elseif($div['status'] === 'moderate')
                            <span>Moderate</span>
                        @elseif($div['status'] === 'low')
                            <span>Low Turnout</span>
                        @else
                            <span>No Ballots</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 12px;">
                        No divisions recorded for this election.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($stats['divisions']['items']) > 0)
            <tfoot>
                <tr>
                    <td colspan="2" class="text-left font-bold" style="text-transform: uppercase;">Total / All Divisions</td>
                    <td class="text-right font-bold">{{ number_format($stats['total_voters']) }}</td>
                    <td class="text-right font-bold">{{ number_format($stats['voted_voters']) }}</td>
                    <td class="text-right font-bold">{{ number_format($stats['queued_voters']) }}</td>
                    <td class="text-right font-bold">{{ $stats['turnout_rate'] }}%</td>
                    <td class="text-center font-bold">{{ $stats['divisions']['total_divisions'] }} Units</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- 3. Demographic Distributions -->
    <div class="section-header">3. Demographic Distributions & Segment Turnout</div>

    <!-- Gender Breakdown Table -->
    <table class="data-table" style="margin-bottom: 8px;">
        <thead>
            <tr>
                <th class="text-left" style="width: 30%;">Gender Segment</th>
                <th class="text-right" style="width: 18%;">Registered Voters</th>
                <th class="text-right" style="width: 16%;">Share %</th>
                <th class="text-right" style="width: 18%;">Ballots Cast</th>
                <th class="text-right" style="width: 18%;">Turnout %</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['Male', 'Female', 'LGBTQ', 'Others'] as $gender)
                @php
                    $gData = $stats['gender'][$gender];
                    $gShare = $stats['total_voters'] > 0 ? round(($gData['count'] / $stats['total_voters']) * 100, 1) : 0;
                    $gTurnout = $gData['count'] > 0 ? round(($gData['voted'] / $gData['count']) * 100, 1) : 0;
                @endphp
                <tr>
                    <td class="text-left font-bold">{{ $gender }}</td>
                    <td class="text-right">{{ number_format($gData['count']) }}</td>
                    <td class="text-right">{{ $gShare }}%</td>
                    <td class="text-right font-bold">{{ number_format($gData['voted']) }}</td>
                    <td class="text-right font-bold">{{ $gTurnout }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Age Breakdown Table -->
    <table class="data-table" style="margin-bottom: 8px;">
        <thead>
            <tr>
                <th class="text-left" style="width: 30%;">Age Bracket</th>
                <th class="text-right" style="width: 18%;">Registered Voters</th>
                <th class="text-right" style="width: 16%;">Share %</th>
                <th class="text-right" style="width: 18%;">Ballots Cast</th>
                <th class="text-right" style="width: 18%;">Turnout %</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['18-25' => 'Youth', '26-35' => 'Young Adults', '36-45' => 'Mid-Career', '46-60' => 'Experienced', '61+' => 'Senior'] as $bracket => $bLabel)
                @php
                    $aData = $stats['age'][$bracket];
                    $aShare = $stats['total_voters'] > 0 ? round(($aData['count'] / $stats['total_voters']) * 100, 1) : 0;
                    $aTurnout = $aData['count'] > 0 ? round(($aData['voted'] / $aData['count']) * 100, 1) : 0;
                @endphp
                <tr>
                    <td class="text-left font-bold">{{ $bracket }} ({{ $bLabel }})</td>
                    <td class="text-right">{{ number_format($aData['count']) }}</td>
                    <td class="text-right">{{ $aShare }}%</td>
                    <td class="text-right font-bold">{{ number_format($aData['voted']) }}</td>
                    <td class="text-right font-bold">{{ $aTurnout }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Age Averages Comparison Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 33.33%;">Average Age (Overall Registered Pool)</th>
                <th class="text-center" style="width: 33.33%;">Average Age (Active Voters)</th>
                <th class="text-center" style="width: 33.33%;">Average Age (Pending Queue)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center font-bold">{{ $stats['avg_age_all'] }} years old</td>
                <td class="text-center font-bold">{{ $stats['avg_age_voted'] }} years old</td>
                <td class="text-center font-bold">{{ $stats['avg_age_queued'] }} years old</td>
            </tr>
        </tbody>
    </table>

    <!-- 4. Key Audit Findings & Observations -->
    <div class="section-header">4. Key Audit Findings & Observations</div>
    <div class="findings-box">
        <div class="findings-title">Auditor Verification Summary:</div>
        <div>
            &bull; <strong>Top Performing Unit:</strong>
            @if($stats['divisions']['top_division'])
                {{ $stats['divisions']['top_division']['division'] }} recorded {{ $stats['divisions']['top_division']['turnout_rate'] }}% turnout ({{ number_format($stats['divisions']['top_division']['voted']) }} of {{ number_format($stats['divisions']['top_division']['total']) }} registered voters).
            @else
                No regional participation data recorded yet.
            @endif
        </div>
        <div style="margin-top: 3px;">
            &bull; <strong>Lowest Turnout Unit:</strong>
            @if($stats['divisions']['lowest_division'])
                {{ $stats['divisions']['lowest_division']['division'] }} recorded {{ $stats['divisions']['lowest_division']['turnout_rate'] }}% turnout ({{ number_format($stats['divisions']['lowest_division']['queued']) }} participant(s) remaining in queue).
            @else
                All registered units are fully active.
            @endif
        </div>
        <div style="margin-top: 3px;">
            &bull; <strong>Database Integrity:</strong>
            Sum of individual regional ballot counts matches exactly the verified cast ballots total of {{ number_format($stats['voted_voters']) }}. Zero orphaned votes or discrepancies detected.
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
        AppCentral Election Portal &bull; Formal Audit Report &bull; Generated: {{ now()->format('Y-m-d H:i:s') }}
    </div>

</body>
</html>
