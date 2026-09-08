<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Official Secret Ballot Receipt</title>
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
            background-color: #581c87; /* Corporate purple */
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
            color: #d8b4fe;
            margin: 8px 0 0 0;
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
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
        .receipt {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
        }
        .receipt-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 16px 0;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .detail-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .detail-label {
            display: table-cell;
            width: 160px;
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .detail-value {
            display: table-cell;
            font-size: 13px;
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
        .status-badge {
            background-color: #d1fae5;
            color: #065f46;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 9999px;
            text-transform: uppercase;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>M Lhuillier Corporate Registry</h1>
            <p>Official Secret Ballot Receipt</p>
        </div>
        
        <div class="content">
            <p class="greeting">Hello Employee,</p>
            <p class="message">
                This email confirms that you have successfully cast your secret ballot in the election <strong>{{ $election->title }}</strong>. 
                To protect the absolute privacy of your vote, your candidate selections are encrypted and recorded anonymously in our database. 
                Below is your official registry verification details.
            </p>
            
            <div class="receipt">
                <h4 class="receipt-title">Registry Details</h4>
                
                <div class="detail-row">
                    <span class="detail-label">Election Title:</span>
                    <span class="detail-value" style="font-weight: 700;">{{ $election->title }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Verified Email:</span>
                    <span class="detail-value">{{ $voter->email }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Division:</span>
                    <span class="detail-value">{{ $voter->division }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Current Position:</span>
                    <span class="detail-value">{{ $voter->current_position }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Cast Timestamp:</span>
                    <span class="detail-value">{{ $voter->voted_at ? $voter->voted_at->format('F d, Y • h:i:A') : now()->format('F d, Y • h:i:A') }}</span>
                </div>

                <div class="detail-row" style="margin-top: 14px;">
                    <span class="detail-label">Ballot Status:</span>
                    <span class="detail-value">
                        <span class="status-badge">Confirmed & Encrypted</span>
                    </span>
                </div>
            </div>

            <div class="receipt">
                <h4 class="receipt-title">Your Ballot Selections</h4>
                @foreach($selections as $posName => $candidateNames)
                    <div class="detail-row" style="margin-bottom: 12px; border-bottom: 1px dashed #f1f5f9; padding-bottom: 8px;">
                        <span class="detail-label" style="text-transform: none; letter-spacing: normal; font-weight: 700; color: #1e293b; display: block; margin-bottom: 4px;">
                            {{ $posName }}
                        </span>
                        <span class="detail-value" style="color: #581c87; font-weight: 600; font-size: 14px; display: block;">
                            {{ implode(', ', $candidateNames) }}
                        </span>
                    </div>
                @endforeach
            </div>

            <p class="message" style="font-size: 12px; color: #64748b; font-style: italic; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                Note: In compliance with secret ballot guidelines, this system enforces zero traceability between your verified registry credentials and your candidate selections. Under no circumstances can your ballot selections be modified or cast again.
            </p>
        </div>
        
        <div class="footer">
            <p>M Lhuillier Standing Committee Elections Portal</p>
            <p style="margin-top: 4px;">© 2026 M Lhuillier Financial Services Inc. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>
