<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Election Verification Code</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 580px;
            margin: 36px auto;
            background-color: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #581c87 0%, #4c1d95 50%, #3730a3 100%);
            padding: 32px 36px;
            text-align: center;
        }
        .badge {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.15);
            color: #f3e8ff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 9999px;
            margin-bottom: 12px;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.025em;
        }
        .header p {
            color: #e9d5ff;
            margin: 8px 0 0 0;
            font-size: 13px;
            font-weight: 500;
        }
        .content {
            padding: 36px 36px 28px 36px;
        }
        .greeting {
            font-size: 16px;
            color: #0f172a;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .message {
            font-size: 14px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .otp-box {
            background: #faf5ff;
            border: 2px dashed #c084fc;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            margin: 28px 0;
        }
        .otp-label {
            font-size: 11px;
            font-weight: 700;
            color: #7e22ce;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 8px;
        }
        .otp-code {
            font-family: 'Courier New', Courier, monospace, monospace;
            font-size: 38px;
            font-weight: 900;
            letter-spacing: 10px;
            color: #581c87;
            margin: 6px 0;
            user-select: all;
        }
        .otp-expiry {
            font-size: 12px;
            color: #6b21a8;
            font-weight: 500;
            margin-top: 8px;
        }
        .details-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 24px;
        }
        .detail-item {
            font-size: 12px;
            color: #334155;
            margin: 6px 0;
        }
        .detail-item strong {
            color: #0f172a;
        }
        .security-notice {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 12px;
            color: #92400e;
            line-height: 1.5;
            margin-bottom: 16px;
        }
        .footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 24px 36px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.6;
        }
        .footer p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <span class="badge">SAKO Cooperative • Secret Ballot</span>
            <h1>Voter Verification Code</h1>
            <p>{{ $election->title }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2 class="greeting">Hello, {{ $voter->display_name }}!</h2>
            <p class="message">
                A sign-in request was initiated on the official SAKO Election Portal using your whitelisted email address. Use the 6-digit One-Time PIN (OTP) below to authenticate your identity and proceed to cast your vote.
            </p>

            <!-- OTP Highlight Card -->
            <div class="otp-box">
                <div class="otp-label">Your One-Time Login Code</div>
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-expiry">⏱ Valid for the next <strong>10 minutes</strong></div>
            </div>

            <!-- Details Card -->
            <div class="details-card">
                <div class="detail-item"><strong>Election:</strong> {{ $election->title }}</div>
                <div class="detail-item"><strong>Registered Email:</strong> {{ $voter->email }}</div>
                @if($voter->division)
                    <div class="detail-item"><strong>Division:</strong> {{ $voter->division }}</div>
                @endif
                <div class="detail-item"><strong>Request Time:</strong> {{ now()->format('M d, Y h:i A') }} (UTC)</div>
            </div>

            <!-- Security Notice -->
            <div class="security-notice">
                <strong>Security Reminder:</strong> Never share this verification code with anyone. SAKO Election Committee staff and administrators will never ask for your one-time code. If you did not initiate this sign-in, you may safely disregard this email or report it to the election committee.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>SAKO Cooperative Secret Ballot Booth</strong></p>
            <p>Certified democratic election system operating in full compliance with RA 9520.</p>
            <p>&copy; {{ date('Y') }} SAKO Cooperative. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
