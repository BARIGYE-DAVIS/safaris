<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: linear-gradient(135deg, #10B981, #3B82F6); color: white; padding: 30px; text-align: center; border-radius: 10px;">
            <h1 style="margin: 0; font-size: 28px;">Safari Uganda</h1>
            <p style="margin: 10px 0 0 0; font-size: 18px;">Booking Confirmation</p>
        </div>

        <div style="background: #f8f9fa; padding: 30px; border-radius: 10px; margin: 20px 0;">
            <h2 style="color: #10B981; margin-top: 0;">Hello {{ $booking->name }}!</h2>
            
            <p>Thank you for your interest in our <strong>{{ $booking->tour->title }}</strong> tour. We have received your booking request and will get back to you within 24 hours with detailed information.</p>

            <div style="background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #10B981;">
                <h3 style="margin-top: 0; color: #333;">Booking Details</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Tour:</td>
                        <td style="padding: 8px 0;">{{ $booking->tour->title }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Group Size:</td>
                        <td style="padding: 8px 0;">{{ $booking->group_size }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Travel Date:</td>
                        <td style="padding: 8px 0;">{{ $booking->travel_date->format('F j, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Country:</td>
                        <td style="padding: 8px 0;">{{ $booking->country }}</td>
                    </tr>
                    @if($booking->total_cost)
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Estimated Cost:</td>
                        <td style="padding: 8px 0; color: #10B981; font-weight: bold;">${{ number_format($booking->total_cost) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Booking ID:</td>
                        <td style="padding: 8px 0;">#{{ $booking->id }}</td>
                    </tr>
                </table>
            </div>

            @if($booking->message)
            <div style="background: white; padding: 20px; border-radius: 8px; margin-top: 20px;">
                <h4 style="margin-top: 0; color: #333;">Your Message:</h4>
                <p style="margin: 0;">{{ $booking->message }}</p>
            </div>
            @endif

            <div style="background: #e7f5f0; padding: 20px; border-radius: 8px; margin-top: 20px; text-align: center;">
                <h3 style="margin-top: 0; color: #10B981;">What's Next?</h3>
                <p>Our travel experts will review your request and contact you via:</p>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin: 10px 0;">📧 Email: {{ $booking->email }}</li>
                    <li style="margin: 10px 0;">📱 WhatsApp: {{ $booking->whatsapp }}</li>
                </ul>
                <p><strong>Response time:</strong> Within 24 hours</p>
            </div>
        </div>

        <div style="background: #333; color: white; padding: 20px; text-align: center; border-radius: 10px;">
            <h3 style="margin-top: 0;">Contact Us</h3>
            <p style="margin: 5px 0;">📞 +256 700 000 000</p>
            <p style="margin: 5px 0;">📧 info@safariuganda.com</p>
            <p style="margin: 5px 0;">📍 Kampala, Uganda</p>
            
            <div style="margin-top: 20px;">
                <a href="https://wa.me/256700000000" style="background: #25D366; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 0 5px;">WhatsApp</a>
                <a href="mailto:info@safariuganda.com" style="background: #10B981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 0 5px;">Email Us</a>
            </div>
        </div>

        <div style="text-align: center; margin-top: 20px; color: #666; font-size: 14px;">
            <p>&copy; {{ date('Y') }} Safari Uganda. All rights reserved.</p>
        </div>
    </div>
</body>
</html>