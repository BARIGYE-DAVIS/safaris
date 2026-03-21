<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Message</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; line-height: 1.6; color:#111;">
    <h2 style="margin:0 0 12px;">New Contact Message Received</h2>

    <p style="margin:0 0 10px;">
        <strong>Subject:</strong>
        {{ $contact->subject ?? 'N/A' }}
    </p>

    <hr style="border:none;border-top:1px solid #e5e7eb;margin:14px 0;">

    <p style="margin:0 0 6px;"><strong>Name:</strong> {{ $contact->name }}</p>
    <p style="margin:0 0 6px;"><strong>Email:</strong> {{ $contact->email }}</p>
    <p style="margin:0 0 6px;"><strong>Phone:</strong> {{ $contact->phone ?? 'N/A' }}</p>
    <p style="margin:0 0 6px;"><strong>Country:</strong> {{ $contact->country }}</p>

    <div style="margin-top:14px;">
        <p style="margin:0 0 6px;"><strong>Message:</strong></p>
        <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:12px;white-space:pre-wrap;">
            {{ $contact->message }}
        </div>
    </div>

    <p style="margin-top:16px;color:#6b7280;font-size:12px;">
        Sent from {{ config('app.name') }} contact form.
    </p>
</body>
</html>