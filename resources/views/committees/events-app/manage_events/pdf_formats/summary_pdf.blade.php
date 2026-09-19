<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Summary Report - {{ $event->title }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 12px;
            line-height: 1.5;
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
                <p class="report-subtitle">App Central Executive Analytics</p>
                <h1 class="report-title">Event Summary Report</h1>
            </td>
            <td style="text-align: right; vertical-align: middle; width: 120px;">
                <span class="logo-badge">{{ $event->committee ? substr($event->committee->name, 0, 2) : 'EM' }}</span>
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

    <!-- Attendee List Table Section (Page Break to fit beautifully) -->
    <div class="page-break"></div>

    <table class="header-table">
        <tr>
            <td style="vertical-align: middle;">
                <p class="report-subtitle">App Central Executive Analytics</p>
                <h1 class="report-title">Attendee Registration Directory</h1>
            </td>
            <td style="text-align: right; vertical-align: middle; width: 120px;">
                <span class="logo-badge">{{ $event->committee ? substr($event->committee->name, 0, 2) : 'EM' }}</span>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 35%;">Attendee Profile</th>
                <th style="width: 25%;">Email Address</th>
                <th style="width: 15%;">Ticket Code</th>
                <th style="width: 10%; text-align: center;">Status</th>
                <th style="width: 15%; text-align: right;">Attendance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $reg)
                <tr>
                    <td style="font-weight: 700; color: #0f172a;">{{ $reg->name }}</td>
                    <td style="font-family: monospace; font-size: 10px;">{{ $reg->email }}</td>
                    <td style="font-family: monospace; font-weight: 700; color: #8b5cf6;">{{ $reg->ticket_code ?? 'N/A' }}</td>
                    <td style="text-align: center;">
                        @if($reg->status === 'approved')
                            <span class="badge badge-approved">Approved</span>
                        @elseif($reg->status === 'declined')
                            <span class="badge badge-declined">Declined</span>
                        @else
                            <span class="badge badge-pending">Pending</span>
                        @endif
                    </td>
                    <td class="text-right" style="font-weight: 600;">
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
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #64748b; font-style: italic; padding: 30px;">
                        No registrations have been logged for this event.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>