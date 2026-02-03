<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mail Configuration Test</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #10B981, #3B82F6); color: white; padding: 30px; text-align: center; border-radius: 10px; }
        .content { background: #f8f9fa; padding: 30px; border-radius: 10px; margin: 20px 0; }
        .success { background: #d1f7c4; border: 2px solid #10B981; padding: 20px; border-radius: 8px; text-align: center; }
        .footer { text-align: center; margin-top: 20px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 28px;">🎉 Mail Test Successful!</h1>
            <p style="margin: 10px 0 0 0; font-size: 18px;">Safari Uganda Mail Configuration</p>
        </div>

        <div class="content">
            <div class="success">
                <h2 style="color: #10B981; margin-top: 0;">✅ Email Configuration Working!</h2>
                <p>Your Safari Uganda website can now send emails successfully.</p>
            </div>

            <h3>Configuration Details:</h3>
            <ul>
                <li><strong>Mail Driver:</strong> SMTP (Gmail)</li>
                <li><strong>Host:</strong> smtp.gmail.com</li>
                <li><strong>Port:</strong> 587 (TLS)</li>
                <li><strong>From:</strong> {{ config('mail.from.name') }} &lt;{{ config('mail.from.address') }}&gt;</li>
                <li><strong>Test Time:</strong> {{ now()->format('F j, Y g:i A T') }}</li>
            </ul>

            <h3>What This Means:</h3>
            <ul>
                <li>✅ Booking confirmations will be sent to customers</li>
                <li>✅ Admin notifications will work properly</li>
                <li>✅ Password reset emails will be delivered</li>
                <li>✅ Contact form submissions will be sent</li>
            </ul>

            <div style="background: white; padding: 20px; border-radius: 8px; margin-top: 20px; text-align: center;">
                <h4 style="color: #3B82F6;">Ready for Production! 🚀</h4>
                <p>Your email system is properly configured and ready to handle customer communications.</p>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Safari Uganda. All rights reserved.</p>
            <p>This is an automated test email from your website's mail configuration.</p>
        </div>
    </div>
</body>
</html>