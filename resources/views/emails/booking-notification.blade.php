<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Booking Request</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: #dc2626; color: white; padding: 30px; text-align: center; border-radius: 10px;">
            <h1 style="margin: 0; font-size: 28px;">🚨 New Booking Request</h1>
            <p style="margin: 10px 0 0 0; font-size: 18px;">Immediate Action Required</p>
        </div>

        <div style="background: #f8f9fa; padding: 30px; border-radius: 10px; margin: 20px 0;">
            <h2 style="color: #dc2626; margin-top: 0;">Tour: {{ $booking->tour->title }}</h2>
            
            <div style="background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #dc2626;">
                <h3 style="margin-top: 0; color: #333;">Customer Details</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Name:</td>
                        <td style="padding: 8px 0;">{{ $booking->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Email:</td>
                        <td style="padding: 8px 0;"><a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">WhatsApp:</td>
                        <td style="padding: 8px 0;"><a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $booking->whatsapp) }}">{{ $booking->whatsapp }}</a></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Country:</td>
                        <td style="padding: 8px 0;">{{ $booking->country }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Group Size:</td>
                        <td style="padding: 8px 0;">{{ $booking->group_size }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Travel Date:</td>
                        <td style="padding: 8px 0;">{{ $booking->travel_date->format('F j, Y') }}</td>
                    </tr>
                    @if($booking->total_cost)
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Estimated Revenue:</td>
                        <td style="padding: 8px 0; color: #10B981; font-weight: bold; font-size: 18px;">${{ number_format($booking->total_cost) }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            @if($booking->message)
            <div style="background: white; padding: 20px; border-radius: 8px; margin-top: 20px;">
                <h4 style="margin-top: 0; color: #333;">Customer Message:</h4>
                <p style="margin: 0; font-style: italic;">"{{ $booking->message }}"</p>
            </div>
            @endif

            <div style="text-align: center; margin-top: 30px;">
                <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $booking->whatsapp) }}" 
                   style="background: #25D366; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; margin: 0 10px; display: inline-block; font-weight: bold;">
                    📱 Contact via WhatsApp
                </a>
                <a href="mailto:{{ $booking->email }}" 
                   style="background: #3B82F6; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; margin: 0 10px; display: inline-block; font-weight: bold;">
                    📧 Send Email
                </a>
            </div>
        </div>

        <div style="background: #fef3c7; padding: 20px; border-radius: 8px; border-left: 4px solid #f59e0b;">
            <h4 style="margin-top: 0; color: #92400e;">⏰ Response Required</h4>
            <p style="margin: 0; color: #92400e;">Please respond to this customer within 24 hours to maintain our service standards.</p>
        </div>

        <div style="text-align: center; margin-top: 20px; color: #666; font-size: 14px;">
            <p>Booking ID: #{{ $booking->id }} | {{ $booking->created_at->format('F j, Y g:i A') }}</p>
        </div>
    </div>
</body>
</html>