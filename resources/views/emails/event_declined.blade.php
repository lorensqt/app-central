<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Update on Your RSVP Request</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
            overflow: hidden;
        }
        .header {
            background-color: #0f172a;
            padding: 32px 40px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }
        .header p {
            color: #94a3b8;
            margin: 8px 0 0 0;
            font-size: 14px;
        }
        .content {
            padding: 40px;
        }
        .greeting {
            font-size: 16px;
            color: #1e293b;
            font-weight: 600;
            margin-top: 0;
        }
        .message {
            font-size: 14px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .ticket {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
        }
        .ticket-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 16px 0;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 12px;
        }
        .detail-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .detail-label {
            display: table-cell;
            width: 120px;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .detail-value {
            display: table-cell;
            font-size: 14px;
            color: #1e293b;
            font-weight: 500;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            border-radius: 6px;
            letter-spacing: 0.05em;
        }
        .badge-declined {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 24px 40px;
            text-align: center;
        }
        .footer p {
            color: #94a3b8;
            font-size: 11px;
            margin: 0;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>RSVP Status Update</h1>
            <p>App Central Division Workspace</p>
        </div>

        <!-- Body Content -->
        <div class="content">
            <p class="greeting">Hello {{ $registration->name }},</p>
            <p class="message">
                We are writing to update you on your registration request for the upcoming assembly outlined below. Unfortunately, your RSVP request has been <strong>Declined</strong> by the committee secretariat.
            </p>

            <!-- Ticket details -->
            <div class="ticket">
                <h4 class="ticket-title">RSVP REQUEST SUMMARY</h4>
                
                <div class="detail-row">
                    <div class="detail-label">Assembly:</div>
                    <div class="detail-value" style="font-weight: 700; color: #0f172a;">{{ $registration->event->title }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Committee:</div>
                    <div class="detail-value">{{ $registration->event->committee ? $registration->event->committee->name : 'General Division' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Date & Time:</div>
                    <div class="detail-value">{{ $registration->event->event_date->format('l, F j, Y \a\t g:i A') }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Location:</div>
                    <div class="detail-value">{{ $registration->event->location }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Registered Email:</div>
                    <div class="detail-value" style="font-family: monospace; font-size: 13px;">{{ $registration->email }}</div>
                </div>

                <div class="detail-row" style="margin-top: 14px;">
                    <div class="detail-label" style="vertical-align: middle;">RSVP Status:</div>
                    <div class="detail-value" style="vertical-align: middle;">
                        <span class="badge badge-declined">Declined</span>
                    </div>
                </div>
            </div>

            <p class="message" style="margin-bottom: 0;">
                RSVP requests are typically declined due to reaching the maximum seating capacity limit of the venue, or if the attendee registration criteria for this specific division assembly were not met. If you believe this was in error, please contact the host committee directly.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} App Central. All rights reserved.</p>
            <p style="margin-top: 4px;">You received this automated security notice because your email was used to register for a secure committee assembly.</p>
        </div>
    </div>
</body>
</html>
