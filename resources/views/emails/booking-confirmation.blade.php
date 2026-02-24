<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Booking Confirmation — Calm Africa Safaris</title>
</head>
<body style="margin:0;padding:0;background:#0a1628;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#0a1628;padding:40px 20px;">
<tr><td align="center">
<table width="640" cellpadding="0" cellspacing="0" border="0" style="max-width:640px;width:100%;">

  {{-- ── HEADER ── --}}
  <tr><td style="background:linear-gradient(135deg,#1a3a2a,#0d2d1f,#0a1628);border-radius:16px 16px 0 0;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr><td style="height:4px;background:linear-gradient(90deg,#c9a84c,#f0d080,#c9a84c);"></td></tr>
    </table>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr><td align="center" style="padding:44px 40px 34px;">
        <div style="background:rgba(201,168,76,0.15);border:1px solid rgba(201,168,76,0.3);border-radius:50%;width:68px;height:68px;line-height:68px;text-align:center;font-size:30px;margin:0 auto 16px;">🌿</div>
        <div style="color:#c9a84c;font-size:11px;letter-spacing:4px;text-transform:uppercase;margin-bottom:10px;">CALM AFRICA SAFARIS</div>
        <h1 style="margin:0;color:#fff;font-size:30px;font-weight:400;">Your Safari Awaits</h1>
        <p style="margin:10px 0 0;color:rgba(255,255,255,0.5);font-size:12px;letter-spacing:3px;text-transform:uppercase;">Booking Confirmation</p>
        <div style="margin:22px auto 0;width:50px;height:1px;background:linear-gradient(90deg,transparent,#c9a84c,transparent);"></div>
      </td></tr>
    </table>
  </td></tr>

  {{-- ── STATUS ── --}}
  <tr><td style="background:#0d2d1f;padding:16px;text-align:center;">
    <span style="display:inline-block;background:linear-gradient(135deg,#1a5c35,#0f3d22);border:1px solid rgba(74,222,128,0.3);color:#4ade80;font-size:12px;letter-spacing:3px;text-transform:uppercase;padding:10px 28px;border-radius:30px;">✓ &nbsp;Request Received</span>
  </td></tr>

  {{-- ── GREETING ── --}}
  <tr><td style="background:#fff;padding:44px 48px 32px;">
    <p style="margin:0 0 6px;color:#64748b;font-size:11px;letter-spacing:3px;text-transform:uppercase;">Dear Traveler,</p>
    <h2 style="margin:0 0 16px;color:#0a1628;font-size:24px;font-weight:400;line-height:1.3;">Hello <strong style="color:#1a5c35;">{{ $booking->name }}</strong>,</h2>
    <p style="margin:0 0 24px;color:#475569;font-size:14px;line-height:1.9;">Thank you for choosing Calm Africa Safaris. We have received your booking request for <strong style="color:#0a1628;">{{ $booking->tour->title }}</strong> and our expert team will reach out within <strong>24 hours</strong>. Below is your complete safari itinerary and booking details.</p>
    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-left:4px solid #c9a84c;border-radius:0 8px 8px 0;padding:14px 18px;">
      <div style="font-size:11px;color:#94a3b8;letter-spacing:2px;text-transform:uppercase;">Booking Reference</div>
      <div style="font-family:'Courier New',monospace;font-size:20px;color:#0a1628;font-weight:700;letter-spacing:2px;margin-top:4px;">#{{ $booking->id }}</div>
    </div>
  </td></tr>

  {{-- ── BOOKING SUMMARY ── --}}
  <tr><td style="background:#fff;padding:0 48px 32px;">
    <p style="margin:0 0 14px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;font-weight:700;">◆ &nbsp;Booking Summary</p>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
      <tr style="background:#f8fafc;">
        <td colspan="2" style="padding:14px 18px;border-bottom:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Tour</div>
          <div style="font-size:15px;color:#0a1628;font-weight:600;">{{ $booking->tour->title }}</div>
        </td>
      </tr>
      <tr>
        <td width="50%" style="padding:14px 18px;border-bottom:1px solid #e2e8f0;border-right:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Travel Date</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ \Carbon\Carbon::parse($booking->travel_date)->format('F j, Y') }}</div>
        </td>
        <td width="50%" style="padding:14px 18px;border-bottom:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Group Size</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ $booking->group_size }}</div>
        </td>
      </tr>
      <tr>
        <td width="50%" style="padding:14px 18px;border-right:1px solid #e2e8f0;{{!empty($booking->total_cost)?'border-bottom:1px solid #e2e8f0;':''}}">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Country</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ $booking->country }}</div>
        </td>
        <td width="50%" style="padding:14px 18px;{{!empty($booking->total_cost)?'border-bottom:1px solid #e2e8f0;':''}}">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Duration</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ $booking->tour->itinerary->count() }} Days</div>
        </td>
      </tr>
      @if(!empty($booking->total_cost))
      <tr style="background:#f0fdf4;">
        <td colspan="2" style="padding:16px 18px;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:4px;">Estimated Cost</div>
          <span style="font-size:26px;color:#1a5c35;font-weight:700;">${{ number_format($booking->total_cost, 2) }}</span>
          <span style="font-size:12px;color:#94a3b8;margin-left:6px;">per person</span>
        </td>
      </tr>
      @endif
    </table>
  </td></tr>

  {{-- ── CUSTOMER MESSAGE ── --}}
  @if($booking->message)
  <tr><td style="background:#fff;padding:0 48px 32px;">
    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:18px 20px;">
      <div style="font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#92400e;font-weight:700;margin-bottom:8px;">Your Message</div>
      <div style="color:#78350f;font-size:14px;line-height:1.8;font-style:italic;">"{{ $booking->message }}"</div>
    </div>
  </td></tr>
  @endif

  {{-- ── INCLUDED & EXCLUDED ── --}}
  {{--
      $booking->tour->included and $booking->tour->excluded are stored as
      multi-line text. We split on newlines so each item renders as its own row.
      e.g.:
        "Park entrance fees
         Accommodation
         All meals"
      becomes three separate green/red rows.
  --}}
  @if($booking->tour->included || $booking->tour->excluded)
  <tr><td style="background:#fff;padding:0 48px 32px;">
    <p style="margin:0 0 14px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;font-weight:700;">◆ &nbsp;What's Included &amp; Excluded</p>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>

        {{-- INCLUDED column --}}
        @if($booking->tour->included)
        <td width="48%" valign="top" style="padding-right:8px;">
          <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;overflow:hidden;">
            <div style="background:#16a34a;padding:10px 16px;">
              <span style="font-size:11px;font-weight:700;color:#fff;letter-spacing:1px;">✓ &nbsp;INCLUDED</span>
            </div>
            @foreach(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $booking->tour->included))) as $index => $line)
            <div style="padding:10px 16px;font-size:13px;color:#166534;line-height:1.6;{{ $index > 0 ? 'border-top:1px solid #dcfce7;' : '' }}display:flex;align-items:flex-start;gap:8px;">
              <span style="color:#16a34a;font-weight:700;flex-shrink:0;margin-top:1px;">✓</span>
              <span>{{ $line }}</span>
            </div>
            @endforeach
          </div>
        </td>
        @endif

        {{-- EXCLUDED column --}}
        @if($booking->tour->excluded)
        <td width="48%" valign="top" style="padding-left:8px;">
          <div style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;overflow:hidden;">
            <div style="background:#dc2626;padding:10px 16px;">
              <span style="font-size:11px;font-weight:700;color:#fff;letter-spacing:1px;">✕ &nbsp;EXCLUDED</span>
            </div>
            @foreach(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $booking->tour->excluded))) as $index => $line)
            <div style="padding:10px 16px;font-size:13px;color:#991b1b;line-height:1.6;{{ $index > 0 ? 'border-top:1px solid #fee2e2;' : '' }}display:flex;align-items:flex-start;gap:8px;">
              <span style="color:#dc2626;font-weight:700;flex-shrink:0;margin-top:1px;">✕</span>
              <span>{{ $line }}</span>
            </div>
            @endforeach
          </div>
        </td>
        @endif

      </tr>
    </table>
  </td></tr>
  @endif

  {{-- ── DAY BY DAY ITINERARY ── --}}
  @if($booking->tour->itinerary && $booking->tour->itinerary->count() > 0)
  <tr><td style="background:#fff;padding:0 48px 8px;">
    <p style="margin:0 0 20px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;font-weight:700;">◆ &nbsp;Day by Day Itinerary</p>

    @foreach($booking->tour->itinerary->sortBy('day_number') as $day)
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #d1d5db;border-radius:12px;overflow:hidden;margin-bottom:14px;">
      {{-- Green→Blue gradient header --}}
      <tr>
        <td style="background:linear-gradient(90deg,#22c55e,#3b82f6);padding:14px 18px;">
          <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td valign="middle" width="54" style="padding-right:14px;">
                <div style="background:rgba(255,255,255,0.2);border-radius:50%;width:44px;height:44px;text-align:center;line-height:44px;font-size:16px;font-weight:700;color:#fff;">{{ $day->day_number }}</div>
              </td>
              <td valign="middle">
                <div style="font-size:10px;color:rgba(255,255,255,0.8);letter-spacing:2px;text-transform:uppercase;margin-bottom:3px;">Day {{ $day->day_number }}</div>
                <div style="font-size:15px;font-weight:700;color:#fff;line-height:1.3;">{{ $day->day_title }}</div>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      {{-- White body --}}
      <tr>
        <td style="background:#fff;padding:18px 20px;">
          <p style="margin:0;font-size:14px;color:#374151;line-height:1.9;">{{ $day->activity }}</p>
          @if($day->accommodation || $day->meals)
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:14px;padding-top:12px;border-top:1px solid #f1f5f9;">
            <tr>
              @if($day->accommodation)
              <td width="50%" valign="top" style="font-size:13px;color:#4b5563;padding-right:10px;">
                <span style="color:#16a34a;font-weight:700;">🏕️ Accommodation:</span><br>{{ $day->accommodation }}
              </td>
              @endif
              @if($day->meals)
              <td width="50%" valign="top" style="font-size:13px;color:#4b5563;">
                <span style="color:#16a34a;font-weight:700;">🍽️ Meals:</span><br>{{ $day->meals }}
              </td>
              @endif
            </tr>
          </table>
          @endif
        </td>
      </tr>
    </table>
    @endforeach

  </td></tr>
  @endif

  <tr><td style="background:#fff;height:36px;"></td></tr>

  {{-- ── WHAT'S NEXT ── --}}
  <tr><td style="background:#fff;padding:0 48px 48px;">
    <div style="background:linear-gradient(135deg,#0a1628,#0d2d1f);border-radius:14px;padding:30px;">
      <div style="font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;margin-bottom:6px;">What Happens Next</div>
      <h3 style="margin:0 0 14px;color:#fff;font-size:20px;font-weight:400;">We'll be in touch shortly</h3>
      <p style="margin:0 0 16px;color:rgba(255,255,255,0.65);font-size:14px;line-height:1.8;">Our team will contact you within <strong style="color:#4ade80;">24 hours</strong> via:</p>
      <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:18px;">
        <tr><td style="padding:10px 14px;background:rgba(255,255,255,0.06);border-radius:8px;font-size:14px;color:rgba(255,255,255,0.8);">📧 &nbsp;{{ $booking->email }}</td></tr>
        <tr><td style="height:8px;"></td></tr>
        <tr><td style="padding:10px 14px;background:rgba(255,255,255,0.06);border-radius:8px;font-size:14px;color:rgba(255,255,255,0.8);">💬 &nbsp;{{ $booking->whatsapp ?? 'WhatsApp not provided' }}</td></tr>
      </table>
      <p style="margin:0;color:rgba(255,255,255,0.45);font-size:13px;line-height:1.7;">Need faster assistance? Call us at <strong style="color:#c9a84c;">+256 752 088 768</strong> or reply to this email.</p>
    </div>
    <p style="margin:24px 0 0;color:#475569;font-size:14px;line-height:1.8;text-align:center;">
      Best regards,<br>
      <strong style="color:#0a1628;">Calm Africa Safaris — Booking &amp; Conservation Team</strong><br>
      <a href="mailto:calmafricasafaris@gmail.com" style="color:#1a5c35;text-decoration:none;">calmafricasafaris@gmail.com</a> &nbsp;·&nbsp; +256 752 088 768
    </p>
  </td></tr>

  {{-- ── FOOTER ── --}}
  <tr><td style="background:#0a1628;border-radius:0 0 16px 16px;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr><td style="height:1px;background:linear-gradient(90deg,transparent,rgba(201,168,76,0.5),transparent);"></td></tr>
    </table>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr><td align="center" style="padding:24px 40px;">
        <p style="margin:0 0 4px;font-size:11px;color:rgba(255,255,255,0.3);letter-spacing:2px;text-transform:uppercase;">Booking ID #{{ $booking->id }}</p>
        <p style="margin:0;font-size:11px;color:rgba(255,255,255,0.2);">&copy; {{ date('Y') }} Calm Africa Safaris. All rights reserved.</p>
      </td></tr>
    </table>
  </td></tr>

</table>
</td></tr>
</table>
</body>
</html>