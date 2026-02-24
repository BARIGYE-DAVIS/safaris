<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Custom Safari Request Received — Calm Africa Safaris</title>
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
        <div style="background:rgba(201,168,76,0.15);border:1px solid rgba(201,168,76,0.3);border-radius:50%;width:68px;height:68px;line-height:68px;text-align:center;font-size:30px;margin:0 auto 16px;">🗺️</div>
        <div style="color:#c9a84c;font-size:11px;letter-spacing:4px;text-transform:uppercase;margin-bottom:10px;">CALM AFRICA SAFARIS</div>
        <h1 style="margin:0;color:#fff;font-size:30px;font-weight:400;">Your Dream Safari Request</h1>
        <p style="margin:10px 0 0;color:rgba(255,255,255,0.5);font-size:12px;letter-spacing:3px;text-transform:uppercase;">Custom Tour Confirmation</p>
        <div style="margin:22px auto 0;width:50px;height:1px;background:linear-gradient(90deg,transparent,#c9a84c,transparent);"></div>
      </td></tr>
    </table>
  </td></tr>

  {{-- ── STATUS ── --}}
  <tr><td style="background:#0d2d1f;padding:16px;text-align:center;">
    <span style="display:inline-block;background:linear-gradient(135deg,#1a5c35,#0f3d22);border:1px solid rgba(74,222,128,0.3);color:#4ade80;font-size:12px;letter-spacing:3px;text-transform:uppercase;padding:10px 28px;border-radius:30px;">✓ &nbsp;Request Received</span>
  </td></tr>

  {{-- ── GREETING ── --}}
  <tr><td style="background:#fff;padding:44px 48px 0;">
    <p style="margin:0 0 6px;color:#64748b;font-size:11px;letter-spacing:3px;text-transform:uppercase;">Dear Traveler,</p>
    <h2 style="margin:0 0 16px;color:#0a1628;font-size:24px;font-weight:400;line-height:1.3;">Hello <strong style="color:#1a5c35;">{{ $tourRequest->name }}</strong>,</h2>
    <p style="margin:0 0 24px;color:#475569;font-size:14px;line-height:1.9;">
      Thank you for reaching out to Calm Africa Safaris! We have received your custom safari request and our expert team will craft a personalised itinerary for you within <strong>24–48 hours</strong>. Below is a summary of everything you submitted.
    </p>
    {{-- Reference number --}}
    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-left:4px solid #c9a84c;border-radius:0 8px 8px 0;padding:14px 18px;margin-bottom:36px;">
      <div style="font-size:11px;color:#94a3b8;letter-spacing:2px;text-transform:uppercase;">Request Reference</div>
      <div style="font-family:'Courier New',monospace;font-size:20px;color:#0a1628;font-weight:700;letter-spacing:2px;margin-top:4px;">{{ $tourRequest->reference_number }}</div>
    </div>
  </td></tr>

  {{-- ── YOUR DETAILS ── --}}
  <tr><td style="background:#fff;padding:0 48px 32px;">
    <p style="margin:0 0 14px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;font-weight:700;">◆ &nbsp;Your Details</p>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
      <tr style="background:#f8fafc;">
        <td colspan="2" style="padding:14px 18px;border-bottom:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Full Name</div>
          <div style="font-size:16px;color:#0a1628;font-weight:700;">{{ $tourRequest->name }}</div>
        </td>
      </tr>
      <tr>
        <td width="50%" style="padding:12px 18px;border-bottom:1px solid #e2e8f0;border-right:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Email</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ $tourRequest->email }}</div>
        </td>
        <td width="50%" style="padding:12px 18px;border-bottom:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Phone</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ $tourRequest->phone ?? 'Not provided' }}</div>
        </td>
      </tr>
      <tr>
        <td width="50%" style="padding:12px 18px;border-right:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Country</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ $tourRequest->country ?? 'Not specified' }}</div>
        </td>
        <td width="50%" style="padding:12px 18px;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Language</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ $tourRequest->language ?? 'English' }}</div>
        </td>
      </tr>
    </table>
  </td></tr>

  {{-- ── TRAVEL DETAILS ── --}}
  <tr><td style="background:#fff;padding:0 48px 32px;">
    <p style="margin:0 0 14px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;font-weight:700;">◆ &nbsp;Travel Details</p>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
      <tr>
        <td width="50%" style="padding:12px 18px;border-bottom:1px solid #e2e8f0;border-right:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Start Date</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">
            {{ $tourRequest->travel_date_from ? \Carbon\Carbon::parse($tourRequest->travel_date_from)->format('F j, Y') : 'Flexible' }}
          </div>
        </td>
        <td width="50%" style="padding:12px 18px;border-bottom:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">End Date</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">
            {{ $tourRequest->travel_date_to ? \Carbon\Carbon::parse($tourRequest->travel_date_to)->format('F j, Y') : 'Flexible' }}
          </div>
        </td>
      </tr>
      <tr>
        <td width="50%" style="padding:12px 18px;border-bottom:1px solid #e2e8f0;border-right:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Duration</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ $tourRequest->duration_days ? $tourRequest->duration_days . ' Days' : 'TBD' }}</div>
        </td>
        <td width="50%" style="padding:12px 18px;border-bottom:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Flexible Dates?</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ $tourRequest->flexible_dates ? 'Yes (±3 days)' : 'No' }}</div>
        </td>
      </tr>
      <tr>
        <td width="50%" style="padding:12px 18px;border-right:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Adults</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ $tourRequest->adults_count }}</div>
        </td>
        <td width="50%" style="padding:12px 18px;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Children / Infants</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ $tourRequest->children_count ?? 0 }} / {{ $tourRequest->infants_count ?? 0 }}</div>
        </td>
      </tr>
    </table>
  </td></tr>

  {{-- ── DESTINATIONS ── --}}
  @if(!empty($tourRequest->destinations) && count($tourRequest->destinations) > 0)
  <tr><td style="background:#fff;padding:0 48px 32px;">
    <p style="margin:0 0 14px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;font-weight:700;">◆ &nbsp;Selected Destinations</p>
    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:18px 20px;">
      @foreach($destinations as $destination)
      <div style="display:inline-block;background:#0d2d1f;color:#4ade80;font-size:12px;font-weight:600;padding:6px 14px;border-radius:20px;margin:4px 4px 4px 0;">
        🗺️ {{ $destination->name }}
      </div>
      @endforeach
    </div>
  </td></tr>
  @endif

  {{-- ── ACTIVITIES ── --}}
  @if(!empty($tourRequest->activities) && count($tourRequest->activities) > 0)
  <tr><td style="background:#fff;padding:0 48px 32px;">
    <p style="margin:0 0 14px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;font-weight:700;">◆ &nbsp;Selected Activities</p>
    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:18px 20px;">
      @foreach($activities as $activity)
      <div style="display:inline-block;background:linear-gradient(135deg,#1a3a2a,#0d2d1f);color:#c9a84c;font-size:12px;font-weight:600;padding:6px 14px;border-radius:20px;margin:4px 4px 4px 0;">
        🦁 {{ $activity->name }}
      </div>
      @endforeach
    </div>
  </td></tr>
  @endif

  {{-- ── PREFERENCES ── --}}
  <tr><td style="background:#fff;padding:0 48px 32px;">
    <p style="margin:0 0 14px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;font-weight:700;">◆ &nbsp;Preferences</p>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
      <tr>
        <td width="50%" style="padding:12px 18px;border-bottom:1px solid #e2e8f0;border-right:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Budget Category</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ ucfirst($tourRequest->budget_category ?? 'Not specified') }}</div>
        </td>
        <td width="50%" style="padding:12px 18px;border-bottom:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Approx. Budget</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ $tourRequest->approximate_budget ?? 'Not specified' }}</div>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="padding:12px 18px;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Accommodation Preference</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ $tourRequest->accommodation_preference ?? 'No preference' }}</div>
        </td>
      </tr>
    </table>
  </td></tr>

  {{-- ── SPECIAL REQUIREMENTS ── --}}
  @if(!empty($tourRequest->special_requirements) || $tourRequest->dietary_restrictions || $tourRequest->medical_conditions || $tourRequest->special_requests)
  <tr><td style="background:#fff;padding:0 48px 32px;">
    <p style="margin:0 0 14px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;font-weight:700;">◆ &nbsp;Special Requirements</p>

    @if(!empty($tourRequest->special_requirements))
    <div style="margin-bottom:14px;">
      @foreach($tourRequest->special_requirements as $req)
      <span style="display:inline-block;background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;font-size:12px;font-weight:600;padding:5px 12px;border-radius:20px;margin:3px 3px 3px 0;">✓ {{ $req }}</span>
      @endforeach
    </div>
    @endif

    @if($tourRequest->dietary_restrictions)
    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:14px 18px;margin-bottom:10px;">
      <div style="font-size:11px;color:#92400e;font-weight:700;letter-spacing:1px;text-transform:uppercase;margin-bottom:4px;">🍽️ Dietary Restrictions</div>
      <div style="font-size:13px;color:#78350f;line-height:1.7;">{{ $tourRequest->dietary_restrictions }}</div>
    </div>
    @endif

    @if($tourRequest->medical_conditions)
    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:14px 18px;margin-bottom:10px;">
      <div style="font-size:11px;color:#92400e;font-weight:700;letter-spacing:1px;text-transform:uppercase;margin-bottom:4px;">🏥 Medical / Accessibility</div>
      <div style="font-size:13px;color:#78350f;line-height:1.7;">{{ $tourRequest->medical_conditions }}</div>
    </div>
    @endif

    @if($tourRequest->special_requests)
    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:14px 18px;">
      <div style="font-size:11px;color:#92400e;font-weight:700;letter-spacing:1px;text-transform:uppercase;margin-bottom:4px;">💬 Additional Comments</div>
      <div style="font-size:13px;color:#78350f;line-height:1.7;font-style:italic;">"{{ $tourRequest->special_requests }}"</div>
    </div>
    @endif
  </td></tr>
  @endif

  <tr><td style="background:#fff;height:8px;"></td></tr>

  {{-- ── WHAT'S NEXT ── --}}
  <tr><td style="background:#fff;padding:0 48px 48px;">
    <div style="background:linear-gradient(135deg,#0a1628,#0d2d1f);border-radius:14px;padding:30px;">
      <div style="font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;margin-bottom:6px;">What Happens Next</div>
      <h3 style="margin:0 0 14px;color:#fff;font-size:20px;font-weight:400;">We're crafting your safari plan</h3>
      <p style="margin:0 0 16px;color:rgba(255,255,255,0.65);font-size:14px;line-height:1.8;">
        Our specialists will review your request and send you a tailored itinerary with pricing within <strong style="color:#4ade80;">24–48 hours</strong>. We'll reach you at:
      </p>
      <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:18px;">
        <tr><td style="padding:10px 14px;background:rgba(255,255,255,0.06);border-radius:8px;font-size:14px;color:rgba(255,255,255,0.8);">📧 &nbsp;{{ $tourRequest->email }}</td></tr>
        @if($tourRequest->phone)
        <tr><td style="height:8px;"></td></tr>
        <tr><td style="padding:10px 14px;background:rgba(255,255,255,0.06);border-radius:8px;font-size:14px;color:rgba(255,255,255,0.8);">📞 &nbsp;{{ $tourRequest->phone }}</td></tr>
        @endif
      </table>
      <p style="margin:0;color:rgba(255,255,255,0.45);font-size:13px;line-height:1.7;">Need faster assistance? Call us at <strong style="color:#c9a84c;">+256 752 088 768</strong> or reply to this email.</p>
    </div>
    <p style="margin:24px 0 0;color:#475569;font-size:14px;line-height:1.8;text-align:center;">
      Best regards,<br>
      <strong style="color:#0a1628;">Calm Africa Safaris — Custom Safari Team</strong><br>
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
        <p style="margin:0 0 4px;font-size:11px;color:rgba(255,255,255,0.3);letter-spacing:2px;text-transform:uppercase;">Reference: {{ $tourRequest->reference_number }}</p>
        <p style="margin:0;font-size:11px;color:rgba(255,255,255,0.2);">&copy; {{ date('Y') }} Calm Africa Safaris. All rights reserved.</p>
      </td></tr>
    </table>
  </td></tr>

</table>
</td></tr>
</table>
</body>
</html>