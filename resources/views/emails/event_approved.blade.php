<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Registration Confirmed</title>
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
            <h1>Event Registration Confirmed</h1>
            <p>App Central Division Workspace</p>
        </div>

        <!-- Body Content -->
        <div class="content">
            <p class="greeting">Hello {{ $registration->name }},</p>
            <p class="message">
                Your registration request has been approved! We are pleased to confirm your reservation and attendance seat for the upcoming committee assembly outlined below.
            </p>

            <!-- Ticket details -->
            <div class="ticket">
                <h4 class="ticket-title">OFFICIAL CONFIRMATION CARD</h4>
                
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
                    <div class="detail-label">Invitee Email:</div>
                    <div class="detail-value" style="font-family: monospace; font-size: 13px;">{{ $registration->email }}</div>
                </div>

                @if($registration->ticket_code)
                    <div class="detail-row">
                        <div class="detail-label">Ticket Code:</div>
                        <div class="detail-value" style="font-family: monospace; font-size: 15px; font-weight: 700; color: #7c3aed;">{{ $registration->ticket_code }}</div>
                    </div>
                @endif
            </div>

            <!-- Call to Action -->
            <div style="text-align: center; margin: 32px 0 24px 0;">
                <a href="{{ URL::signedRoute('events.manage_ticket', ['registration' => $registration->id]) }}" 
                   style="display: inline-block; background-color: #7c3aed; color: #ffffff; padding: 14px 28px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 14px; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.15);">
                    View & Manage Your Ticket
                </a>
            </div>

            <p class="message" style="margin-bottom: 0;">
                If there are any sudden schedule conflicts, you can use the link above to manage or cancel your RSVP pass before the registration deadline. We look forward to your active participation.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} App Central. All rights reserved.</p>
            <p style="margin-top: 4px;">You received this automated security confirmation because your email was used to register for a secure committee assembly.</p>
        </div>
    </div>
</body>
</html>
