@php
    // Sort registrations by division alphabetically, placing unspecified/blank divisions at the bottom
    $registrations = $registrations->sortBy(function($reg) {
        return empty($reg->division) ? 'zzzzz' : strtolower($reg->division);
    });

    // --- Age Demographics ---
    $kids = 0;       // < 12
    $youth = 0;      // 12-17
    $youngAdults = 0;// 18-30
    $adults = 0;     // 31-59
    $seniors = 0;    // 60+
    $unspecifiedAge = 0;

    foreach ($registrations as $reg) {
        if ($reg->birthday) {
            $age = $reg->age;
            if ($age === null) {
                $unspecifiedAge++;
            } elseif ($age < 12) {
                $kids++;
            } elseif ($age <= 17) {
                $youth++;
            } elseif ($age <= 30) {
                $youngAdults++;
            } elseif ($age <= 59) {
                $adults++;
            } else {
                $seniors++;
            }
        } else {
            $unspecifiedAge++;
        }
    }

    $kidsPct = $total > 0 ? round(($kids / $total) * 100) : 0;
    $youthPct = $total > 0 ? round(($youth / $total) * 100) : 0;
    $yaPct = $total > 0 ? round(($youngAdults / $total) * 100) : 0;
    $adultPct = $total > 0 ? round(($adults / $total) * 100) : 0;
    $seniorPct = $total > 0 ? round(($seniors / $total) * 100) : 0;

    // --- Division Distribution ---
    $divisions = [];
    foreach ($registrations as $reg) {
        $div = trim($reg->division ?? '');
        if ($div === '') {
            $div = 'Unspecified';
        }
        if (!isset($divisions[$div])) {
            $divisions[$div] = 0;
        }
        $divisions[$div]++;
    }
    arsort($divisions); // sort by count descending

    // --- Load App Logo Base64 ---
    $logoPath = base_path('resources/views/imgs/letter-s.png');
    $logoBase64 = '';
    if (file_exists($logoPath)) {
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Summary Report - {{ $event->title }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.55;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #8b5cf6;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .logo-badge {
            background-color: #8b5cf6;
            color: #ffffff;
            font-weight: 800;
            font-size: 16px;
            padding: 8px 14px;
            border-radius: 8px;
            display: inline-block;
            text-align: center;
        }
        .report-title {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            margin-top: 5px;
        }
        .report-subtitle {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 1px;
            margin: 0;
        }
        .metadata-table {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .metadata-td {
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
        }
        .card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .card-title {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
            margin-top: 0;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }
        .meta-item {
            margin-bottom: 8px;
        }
        .meta-label {
            font-weight: 700;
            color: #64748b;
            font-size: 10px;
            text-transform: uppercase;
            display: block;
        }
        .meta-val {
            font-size: 12px;
            color: #0f172a;
            font-weight: 600;
        }
        .stat-grid {
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            width: 25%;
            padding: 10px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            text-align: center;
        }
        .stat-num {
            font-size: 22px;
            font-weight: 800;
            color: #8b5cf6;
            margin: 0;
        }
        .stat-label {
            font-size: 9px;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            margin-top: 3px;
        }
        .table-title {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 25px;
            margin-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 700;
            font-size: 10px;
            text-transform: uppercase;
            padding: 8px 12px;
            border-bottom: 1px solid #cbd5e1;
            text-align: left;
        }
        .data-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
            color: #334155;
            vertical-align: middle;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            border-radius: 4px;
        }
        .badge-approved {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-declined {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .text-right {
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
        .progress-bar-bg {
            background-color: #e2e8f0;
            border-radius: 4px;
            height: 8px;
            width: 100%;
            margin-top: 4px;
        }
        .progress-bar-fill {
            background-color: #8b5cf6;
            border-radius: 4px;
            height: 8px;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td style="vertical-align: middle;">
                <p class="report-subtitle">SAKO Central • {{ $event->committee ? $event->committee->name : 'General Events' }}</p>
                <h1 class="report-title">Event Summary Report</h1>
            </td>
            <td style="text-align: right; vertical-align: middle; width: 120px;">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" style="height: 40px; width: auto;" alt="SAKO Logo">
                @else
                    <span class="logo-badge">SC</span>
                @endif
            </td>
        </tr>
    </table>

    <!-- Event Metadata Blocks -->
    <table class="metadata-table">
        <tr>
            <td class="metadata-td">
                <div class="card" style="height: 190px;">
                    <h4 class="card-title">Assembly Details</h4>
                    <div class="meta-item">
                        <span class="meta-label">Assembly Title</span>
                        <span class="meta-val">{{ $event->title }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Committee / Division</span>
                        <span class="meta-val">{{ $event->committee ? $event->committee->name : 'General Events' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Location / Platform</span>
                        <span class="meta-val">{{ $event->location }}</span>
                    </div>
                </div>
            </td>
            <td class="metadata-td" style="padding-right: 0;">
                <div class="card" style="height: 190px;">
                    <h4 class="card-title">Schedule & Deadlines</h4>
                    <div class="meta-item">
                        <span class="meta-label">Event Date & Time</span>
                        <span class="meta-val">{{ $event->formatted_date_range }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Registration Deadline</span>
                        <span class="meta-val">{{ $event->registration_deadline ? $event->registration_deadline->format('M j, Y • g:i A') : 'No Deadline' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Report Generated On</span>
                        <span class="meta-val">{{ now()->format('F j, Y • g:i A') }}</span>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Stat Metrics Row -->
    <h4 class="table-title">Performance Metrics Summary</h4>
    <table class="stat-grid" style="border-collapse: separate; border-spacing: 10px 0; margin-left: -10px; width: 103%;">
        <tr>
            <td class="stat-box">
                <p class="stat-num">{{ $total }}</p>
                <p class="stat-label">Submissions</p>
            </td>
            <td class="stat-box">
                <p class="stat-num" style="color: #10b981;">{{ $approved }}</p>
                <p class="stat-label">Approved</p>
            </td>
            <td class="stat-box">
                <p class="stat-num" style="color: #f59e0b;">{{ $pending }}</p>
                <p class="stat-label">Pending</p>
            </td>
            <td class="stat-box">
                <p class="stat-num" style="color: #ef4444;">{{ $declined }}</p>
                <p class="stat-label">Declined</p>
            </td>
        </tr>
    </table>

    <table class="metadata-table" style="margin-top: 15px;">
        <tr>
            <td class="metadata-td">
                <div class="card" style="height: 160px;">
                    <h4 class="card-title">Seat Occupancy Rates</h4>
                    <div class="meta-item" style="margin-bottom: 12px;">
                        <span class="meta-label">Approval Rate ({{ $approved }} / {{ $total }})</span>
                        <span class="meta-val">{{ $total > 0 ? round(($approved / $total) * 100) : 0 }}%</span>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill" style="width: {{ $total > 0 ? round(($approved / $total) * 100) : 0 }}%; background-color: #10b981;"></div>
                        </div>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Attendance Rate ({{ $attended }} / {{ $approved ?: 1 }})</span>
                        <span class="meta-val">{{ $approved > 0 ? round(($attended / $approved) * 100) : 0 }}%</span>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill" style="width: {{ $approved > 0 ? round(($attended / $approved) * 100) : 0 }}%; background-color: #8b5cf6;"></div>
                        </div>
                    </div>
                </div>
            </td>
            <td class="metadata-td" style="padding-right: 0;">
                <div class="card" style="height: 160px;">
                    <h4 class="card-title">Gender Demographics</h4>
                    <table style="width: 100%; font-size: 11px;">
                        <tr>
                            <td style="color: #64748b; font-weight: 700; padding: 3px 0;">Male:</td>
                            <td class="text-right" style="font-weight: 700; color: #0f172a;">{{ $males }} ({{ $total > 0 ? round(($males / $total) * 100) : 0 }}%)</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b; font-weight: 700; padding: 3px 0;">Female:</td>
                            <td class="text-right" style="font-weight: 700; color: #0f172a;">{{ $females }} ({{ $total > 0 ? round(($females / $total) * 100) : 0 }}%)</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b; font-weight: 700; padding: 3px 0;">LGBTQ+:</td>
                            <td class="text-right" style="font-weight: 700; color: #0f172a;">{{ $lgbtq }} ({{ $total > 0 ? round(($lgbtq / $total) * 100) : 0 }}%)</td>
                        </tr>
                        @if($others > 0)
                        <tr>
                            <td style="color: #64748b; font-weight: 700; padding: 3px 0;">Others:</td>
                            <td class="text-right" style="font-weight: 700; color: #0f172a;">{{ $others }} ({{ $total > 0 ? round(($others / $total) * 100) : 0 }}%)</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <table class="metadata-table" style="margin-top: 15px;">
        <tr>
            <td class="metadata-td">
                <div class="card" style="height: 175px;">
                    <h4 class="card-title">Age Distribution</h4>
                    <table style="width: 100%; font-size: 11px;">
                        <tr>
                            <td style="color: #64748b; font-weight: 700; padding: 2px 0;">Kids (&lt;12):</td>
                            <td class="text-right" style="font-weight: 700; color: #0f172a;">{{ $kids }} ({{ $kidsPct }}%)</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b; font-weight: 700; padding: 2px 0;">Youth (12-17):</td>
                            <td class="text-right" style="font-weight: 700; color: #0f172a;">{{ $youth }} ({{ $youthPct }}%)</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b; font-weight: 700; padding: 2px 0;">Young Adults (18-30):</td>
                            <td class="text-right" style="font-weight: 700; color: #0f172a;">{{ $youngAdults }} ({{ $yaPct }}%)</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b; font-weight: 700; padding: 2px 0;">Adults (31-59):</td>
                            <td class="text-right" style="font-weight: 700; color: #0f172a;">{{ $adults }} ({{ $adultPct }}%)</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b; font-weight: 700; padding: 2px 0;">Seniors (60+):</td>
                            <td class="text-right" style="font-weight: 700; color: #0f172a;">{{ $seniors }} ({{ $seniorPct }}%)</td>
                        </tr>
                    </table>
                </div>
            </td>
            <td class="metadata-td" style="padding-right: 0;">
                <div class="card" style="height: 175px;">
                    <h4 class="card-title">Division Distribution</h4>
                    <table style="width: 100%; font-size: 11px;">
                        @php $divCount = 0; @endphp
                        @forelse($divisions as $divName => $count)
                            @if($divCount < 5)
                                @php
                                    $divPct = $total > 0 ? round(($count / $total) * 100) : 0;
                                @endphp
                                <tr>
                                    <td style="color: #64748b; font-weight: 700; padding: 2.5px 0; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $divName }}:</td>
                                    <td class="text-right" style="font-weight: 700; color: #0f172a;">{{ $count }} ({{ $divPct }}%)</td>
                                </tr>
                                @php $divCount++; @endphp
                            @endif
                        @empty
                            <tr>
                                <td colspan="2" style="color: #64748b; font-style: italic; padding: 10px 0;">No division data available.</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Attendee List Table Section (Page Break to fit beautifully) -->
    <div class="page-break"></div>

    <table class="header-table">
        <tr>
            <td style="vertical-align: middle;">
                <p class="report-subtitle">SAKO Central • {{ $event->committee ? $event->committee->name : 'General Events' }}</p>
                <h1 class="report-title">Attendee Registration Directory</h1>
            </td>
            <td style="text-align: right; vertical-align: middle; width: 120px;">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" style="height: 40px; width: auto;" alt="SAKO Logo">
                @else
                    <span class="logo-badge">AC</span>
                @endif
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Attendee Profile</th>
                <th style="width: 15%;">Ticket Code</th>
                <th style="width: 10%; text-align: center;">Age</th>
                <th style="width: 15%;">Gender</th>
                <th style="width: 15%;">Division</th>
                <th style="width: 10%; text-align: center;">Status</th>
                <th style="width: 10%; text-align: right;">Attendance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $reg)
                <tr>
                    <td style="border-bottom: none; padding-bottom: 2px;">
                        <span style="font-weight: 700; color: #0f172a; display: block;">{{ $reg->name }}</span>
                        <span style="font-family: monospace; font-size: 9px; color: #64748b; display: block; margin-top: 2px;">{{ $reg->email }}</span>
                    </td>
                    <td style="font-family: monospace; font-weight: 700; color: #8b5cf6; border-bottom: none; padding-bottom: 2px;">{{ $reg->ticket_code ?? '—' }}</td>
                    <td style="text-align: center; font-weight: 600; border-bottom: none; padding-bottom: 2px;">{{ $reg->birthday ? $reg->age . ' yrs' : '—' }}</td>
                    <td style="border-bottom: none; padding-bottom: 2px;">
                        <span class="badge" style="background-color: #f1f5f9; color: #475569;">{{ $reg->gender ?? 'Unspecified' }}</span>
                    </td>
                    <td style="border-bottom: none; padding-bottom: 2px;">
                        @if($reg->division)
                            <span class="badge" style="background-color: #f3e8ff; color: #6b21a8;">{{ $reg->division }}</span>
                        @else
                            <span style="color: #94a3b8;">—</span>
                        @endif
                    </td>
                    <td style="text-align: center; border-bottom: none; padding-bottom: 2px;">
                        @if($reg->status === 'approved')
                            <span class="badge badge-approved">Approved</span>
                        @elseif($reg->status === 'declined')
                            <span class="badge badge-declined">Declined</span>
                        @else
                            <span class="badge badge-pending">Pending</span>
                        @endif
                    </td>
                    <td class="text-right" style="font-weight: 600; border-bottom: none; padding-bottom: 2px;">
                        @if($reg->status === 'approved')
                            @if($reg->attended)
                                <span style="color: #10b981;">✓ Attended</span>
                            @else
                                <span style="color: #64748b;">✗ Absent</span>
                            @endif
                        @else
                            <span style="color: #94a3b8; font-style: italic;">Awaiting Review</span>
                        @endif
                    </td>
                </tr>
                @if(!empty($reg->custom_fields) && is_array($reg->custom_fields))
                    <tr>
                        <td colspan="7" style="padding-top: 2px; padding-bottom: 8px; border-bottom: 1px solid #e2e8f0; background-color: #faf5ff;">
                            <div style="font-size: 9px; color: #6b21a8; font-weight: bold; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; padding-left: 12px;">
                                Questionnaire Responses:
                            </div>
                            <table style="width: 100%; margin-left: 12px; border-collapse: collapse;">
                                @foreach($reg->custom_fields as $key => $val)
                                    @if($val !== null && $val !== '')
                                        <tr>
                                            <td style="width: 30%; font-weight: bold; color: #475569; font-size: 9px; padding: 2px 0; border: none;">{{ $key }}:</td>
                                            <td style="width: 70%; color: #1e293b; font-size: 9px; padding: 2px 0; border: none;">
                                                @if(strtolower($key) === 'birthday' || strtolower($key) === 'birth date')
                                                    {{ \Carbon\Carbon::parse($val)->format('M j, Y') }}
                                                @else
                                                    {{ $val }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="7" style="border-bottom: 1px solid #e2e8f0; font-size: 9px; color: #64748b; font-style: italic; padding-top: 2px; padding-bottom: 8px; padding-left: 12px;">
                            No questionnaire responses supplied.
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #64748b; font-style: italic; padding: 30px;">
                        No registrations have been logged for this event.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>