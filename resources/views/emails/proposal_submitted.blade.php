<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Proposal Submission Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .header {
            text-align: center;
            border-bottom: 1px solid #eaeaea;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #2b6cb0;
            font-size: 24px;
            margin: 0;
        }
        .content p {
            line-height: 1.6;
        }
        .content strong {
            color: #2c5282;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Proposal Submitted</h1>
        </div>
        <div class="content">
            <p>Hi {{ $proposal->submitted_by }},</p>

            <p>Thank you for submitting your proposal titled <strong>"{{ $proposal->title }}"</strong>.</p>

            <p>Our team will review it and notify you as it progresses through the evaluation stages.</p>

            <p>If you have any questions, feel free to contact us at <a href="mailto:support@onelove.org">support@onelove.org</a>.</p>

            <p>Warm regards,<br><strong>OneLove Proposal Team</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} OneLove Initiative. All rights reserved.
        </div>
    </div>
</body>
</html>
