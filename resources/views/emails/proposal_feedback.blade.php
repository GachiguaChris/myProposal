<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Proposal Feedback Notification</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px; margin: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: auto; background-color: #ffffff; border: 1px solid #dee2e6; border-radius: 8px;">
        <tr>
            <td style="padding: 20px; text-align: center;">
                <img src="" alt="OneLove NGO" style="height: 60px; margin-bottom: 15px;">
            </td>
        </tr>

        <tr>
            <td style="padding: 20px;">
                <h2 style="color: #343a40;">Hello {{ $proposal->user->name }},</h2>

                <p style="font-size: 16px; color: #495057;">
                    You have received <strong>new feedback</strong> on your proposal:
                </p>

                <p style="font-size: 18px; font-weight: bold; color: #212529;">
                    "{{ $proposal->title }}"
                </p>

                <p style="font-size: 16px; color: #495057;"><strong>Feedback Type:</strong> {{ ucfirst($feedback->type) }}</p>

                <p style="font-size: 16px; color: #495057;"><strong>Reviewer’s Feedback:</strong></p>
                <blockquote style="background-color: #f1f3f5; padding: 15px; border-left: 4px solid #0d6efd; margin: 10px 0; color: #212529;">
                    {{ $feedback->feedback }}
                </blockquote>

                <div style="text-align: center; margin: 25px 0;">
                    <a href="{{ route('proposals.show', $proposal->id) }}" style="background-color: #0d6efd; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                        View Proposal
                    </a>
                </div>

                <p style="font-size: 16px; color: #495057;">
                    Please review and revise your proposal as needed.
                </p>

                <p style="margin-top: 30px; font-size: 16px; color: #495057;">
                    Regards,<br>
                    <strong>The Review Team</strong>
                </p>
            </td>
        </tr>

        <tr>
            <td style="background-color: #e9ecef; padding: 10px; text-align: center; font-size: 13px; color: #6c757d;">
                &copy; {{ date('Y') }} OneLove NGO. All rights reserved.
            </td>
        </tr>
    </table>
</body>
</html>
