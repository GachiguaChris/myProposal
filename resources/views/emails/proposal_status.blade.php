<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Proposal Review Update</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        h2 {
            color: #2b6cb0;
            text-align: center;
            margin-bottom: 25px;
        }
        p {
            line-height: 1.6;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            margin: 8px 0;
        }
        strong {
            color: #2c5282;
        }
        .status-message {
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Proposal Review Update</h2>

        <p>Dear {{ $proposal->submitted_by->name ?? 'Contributor' }},</p>

        <p>We would like to inform you that your proposal titled <strong>"{{ $proposal->title }}"</strong> has been reviewed. Below are the current details:</p>

        <ul>
            <li><strong>Stage:</strong> {{ $proposal->stage ?? 'N/A' }}</li>
            <li><strong>Status:</strong> {{ ucfirst($proposal->status) }}</li>
        </ul>

        @if($proposal->status === 'pending')
            <p class="status-message">⏳ Your proposal is still under review. We appreciate your patience.</p>
        @elseif($proposal->status === 'approved' || $proposal->status === 'accepted')
            <p class="status-message">✅ Congratulations! Your proposal has been approved. We look forward to the next steps.</p>
        @elseif($proposal->status === 'rejected')
            <p class="status-message">❌ Unfortunately, your proposal was not approved at this time. We encourage you to revise and resubmit if applicable.</p>
        @endif

        <p>If you have any questions, please reach out to our team at <a href="mailto:support@onelove.org">support@onelove.org</a>.</p>

        <p>Warm regards,<br><strong>OneLove Proposal Team</strong></p>

        <div class="footer">
            &copy; {{ date('Y') }} OneLove Initiative. All rights reserved.
        </div>
    </div>
</body>
</html>
