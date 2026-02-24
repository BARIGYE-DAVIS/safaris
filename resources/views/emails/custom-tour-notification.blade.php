<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>New Custom Tour Request — Calm Africa Safaris Admin</title>
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
        <div style="background:rgba(201,168,76,0.15);border:1px solid rgba(201,168,76,0.3);border-radius:50%;width:68px;height:68px;line-height:68px;text-align:center;font-size:30px;margin:0 auto 16px;">🔔</div>
        <div style="color:#c9a84c;font-size:11px;letter-spacing:4px;text-transform:uppercase;margin-bottom:10px;">CALM AFRICA SAFARIS — ADMIN</div>
        <h1 style="margin:0;color:#fff;font-size:30px;font-weight:400;">New Custom Safari Request</h1>
        <p style="margin:10px 0 0;color:rgba(255,255,255,0.5);font-size:12px;letter-spacing:3px;text-transform:uppercase;">Immediate Action Required</p>
        <div style="margin:22px auto 0;width:50px;height:1px;background:linear-gradient(90deg,transparent,#c9a84c,transparent);"></div>
      </td></tr>
    </table>
  </td></tr>

  {{-- ── STATUS ── --}}
  <tr><td style="background:#0d2d1f;padding:16px;text-align:center;">
    <span style="display:inline-block;background:linear-gradient(135deg,#1a5c35,#0f3d22);border:1px solid rgba(74,222,128,0.3);color:#4ade80;font-size:12px;letter-spacing:3px;text-transform:uppercase;padding:10px 28px;border-radius:30px;">⚡ &nbsp;New Request — Respond Within 48h</span>
  </td></tr>

  {{-- ── REFERENCE BANNER ── --}}
  <tr><td style="background:#fff;padding:32px 48px 0;">
    <div style="background:linear-gradient(135deg,#0d2d1f,#1a3a2a);border-radius:12px;padding:18px 22px;margin-bottom:28px;">
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td>
            <div style="font-size:11px;letter-spacing:3px;color:rgba(255,255,255,0.5);text-transform:uppercase;margin-bottom:4px;">Request Reference</div>
            <div style="font-family:'Courier New',monospace;color:#c9a84c;font-size:20px;font-weight:700;letter-spacing:2px;">{{ $tourRequest->reference_number }}</div>
          </td>
          <td align="right" valign="middle" style="white-space:nowrap;padding-left:16px;">
            <div style="background:rgba(255,255,255,0.08);border:1px solid rgba(201,168,76,0.3);border-radius:8px;padding:10px 16px;text-align:center;">
              <div style="font-size:10px;color:#c9a84c;text-transform:uppercase;letter-spacing:1px;margin-bottom:2px;">Submitted</div>
              <div style="font-size:13px;font-weight:700;color:#fff;">{{ \Carbon\Carbon::parse($tourRequest->created_at)->format('M j, Y · g:i A') }}</div>
            </div>
          </td>
        </tr>
      </table>
    </div>
  </td></tr>

  {{-- ── CLIENT DETAILS ── --}}
  <tr><td style="background:#fff;padding:0 48px 28px;">
    <p style="margin:0 0 14px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;font-weight:700;">◆ &nbsp;Client Details</p>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
      <tr style="background:#f8fafc;">
        <td colspan="2" style="padding:14px 18px;border-bottom:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Full Name</div>
          <div style="font-size:18px;color:#0a1628;font-weight:700;">{{ $tourRequest->name }}</div>
        </td>
      </tr>
      <tr>
        <td width="50%" style="padding:12px 18px;border-bottom:1px solid #e2e8f0;border-right:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Email</div>
          <a href="mailto:{{ $tourRequest->email }}" style="font-size:14px;color:#1a5c35;font-weight:600;text-decoration:none;">{{ $tourRequest->email }}</a>
        </td>
        <td width="50%" style="padding:12px 18px;border-bottom:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Phone</div>
          @if($tourRequest->phone)
            <a href="tel:{{ $tourRequest->phone }}" style="font-size:14px;color:#1a5c35;font-weight:600;text-decoration:none;">{{ $tourRequest->phone }}</a>
          @else
            <span style="font-size:14px;color:#94a3b8;">Not provided</span>
          @endif
        </td>
      </tr>
      <tr>
        <td width="50%" style="padding:12px 18px;border-right:1px solid #e2e8f0;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Country</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ $tourRequest->country ?? 'Not specified' }}</div>
        </td>
        <td width="50%" style="padding:12px 18px;">
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Heard From</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ $tourRequest->heard_from ?? 'Not specified' }}</div>
        </td>
      </tr>
    </table>
  </td></tr>

  {{-- ── TRAVEL DETAILS ── --}}
  <tr><td style="background:#fff;padding:0 48px 28px;">
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
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Flexible?</div>
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
  <tr><td style="background:#fff;padding:0 48px 28px;">
    <p style="margin:0 0 14px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;font-weight:700;">◆ &nbsp;Requested Destinations</p>
    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:18px 20px;">
      @foreach($destinations as $destination)
      <div style="display:inline-block;background:#0d2d1f;color:#4ade80;font-size:12px;font-weight:600;padding:6px 14px;border-radius:20px;margin:4px 4px 4px 0;">
        🗺️ {{ $destination->name }}@if($destination->country) — {{ $destination->country->name }}@endif
      </div>
      @endforeach
    </div>
  </td></tr>
  @endif

  {{-- ── ACTIVITIES ── --}}
  @if(!empty($tourRequest->activities) && count($tourRequest->activities) > 0)
  <tr><td style="background:#fff;padding:0 48px 28px;">
    <p style="margin:0 0 14px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;font-weight:700;">◆ &nbsp;Requested Activities</p>
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
  <tr><td style="background:#fff;padding:0 48px 28px;">
    <p style="margin:0 0 14px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;font-weight:700;">◆ &nbsp;Budget &amp; Preferences</p>
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
          <div style="font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Accommodation</div>
          <div style="font-size:14px;color:#1e293b;font-weight:600;">{{ $tourRequest->accommodation_preference ?? 'No preference' }}</div>
        </td>
      </tr>
    </table>
  </td></tr>

  {{-- ── SPECIAL REQUIREMENTS ── --}}
  @if(!empty($tourRequest->special_requirements) || $tourRequest->dietary_restrictions || $tourRequest->medical_conditions || $tourRequest->special_requests)
  <tr><td style="background:#fff;padding:0 48px 28px;">
    <p style="margin:0 0 14px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;font-weight:700;">◆ &nbsp;Special Requirements</p>

    @if(!empty($tourRequest->special_requirements))
    <div style="margin-bottom:12px;">
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

  {{-- ── QUICK ACTIONS ── --}}
  <tr><td style="background:#fff;padding:0 48px 28px;">
    <p style="margin:0 0 14px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;font-weight:700;">◆ &nbsp;Quick Actions</p>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td align="center" style="padding-right:6px;">
          <a href="mailto:{{ $tourRequest->email }}" style="display:block;background:linear-gradient(135deg,#1a5c35,#0f3d22);color:#fff;font-size:13px;font-weight:700;letter-spacing:1px;text-decoration:none;padding:13px 8px;border-radius:10px;text-align:center;">📧 Email Client</a>
        </td>
        @if($tourRequest->phone)
        <td align="center" style="padding:0 6px;">
          <a href="tel:{{ $tourRequest->phone }}" style="display:block;background:linear-gradient(135deg,#0d2d1f,#1a3a2a);color:#fff;font-size:13px;font-weight:700;letter-spacing:1px;text-decoration:none;padding:13px 8px;border-radius:10px;text-align:center;">📞 Call Client</a>
        </td>
        @endif
        <td align="center" style="padding-left:6px;">
          <a href="{{ config('app.url') }}/admin/custom-tour-requests/{{ $tourRequest->id }}" style="display:block;background:linear-gradient(135deg,#c9a84c,#a8863d);color:#0a1628;font-size:13px;font-weight:700;letter-spacing:1px;text-decoration:none;padding:13px 8px;border-radius:10px;text-align:center;">🔎 Open Request</a>
        </td>
      </tr>
    </table>
  </td></tr>

  {{-- ── REMINDER ── --}}
  <tr><td style="background:#fff;padding:0 48px 44px;">
    <div style="background:linear-gradient(135deg,#0a1628,#0d2d1f);border-radius:14px;padding:28px;">
      <div style="font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#c9a84c;margin-bottom:6px;">Reminder</div>
      <h3 style="margin:0 0 12px;color:#fff;font-size:18px;font-weight:400;">Respond within 24–48 hours</h3>
      <p style="margin:0;color:rgba(255,255,255,0.65);font-size:14px;line-height:1.8;">
        Please send <strong style="color:#4ade80;">{{ $tourRequest->name }}</strong> a personalised itinerary and quote. They submitted their request on <strong style="color:#c9a84c;">{{ \Carbon\Carbon::parse($tourRequest->created_at)->format('F j, Y \a\t g:i A') }}</strong>.
      </p>
    </div>
  </td></tr>

  {{-- ── FOOTER ── --}}
  <tr><td style="background:#0a1628;border-radius:0 0 16px 16px;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr><td style="height:1px;background:linear-gradient(90deg,transparent,rgba(201,168,76,0.5),transparent);"></td></tr>
    </table>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr><td align="center" style="padding:24px 40px;">
        <p style="margin:0 0 4px;font-size:11px;color:rgba(255,255,255,0.3);letter-spacing:2px;text-transform:uppercase;">
          Ref: {{ $tourRequest->reference_number }} &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($tourRequest->created_at)->format('F j, Y g:i A') }}
        </p>
        <p style="margin:6px 0 0;font-size:11px;color:rgba(255,255,255,0.2);">&copy; {{ date('Y') }} Calm Africa Safaris — Internal Notification</p>
      </td></tr>
    </table>
  </td></tr>

</table>
</td></tr>
</table>
</body>
</html>