<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Uganda Safari Tourist Information Guide — Calm Africa Safaris</title>
<style>
    /* ── Reset & Base ───────────────────────────────────────── */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        font-size: 9pt;
        color: #1f2937;
        line-height: 1.5;
    }

    /* ── Page layout ────────────────────────────────────────── */
    @page {
        margin: 22mm 15mm 18mm 15mm;
        size: A4 portrait;
    }

    /* ── Header (repeats on every page via Dompdf) ──────────── */
    header {
        position: fixed;
        top: -18mm;
        left: 0; right: 0;
        height: 14mm;
        background-color: #166534;
        color: #fff;
        padding: 0 10mm;
        display: block;
    }
    .hdr-inner {
        display: table;
        width: 100%;
        height: 14mm;
    }
    .hdr-left, .hdr-right {
        display: table-cell;
        vertical-align: middle;
    }
    .hdr-left  { font-size: 9pt; font-weight: bold; }
    .hdr-right { text-align: right; font-size: 7.5pt; color: #bbf7d0; }

    /* ── Footer ─────────────────────────────────────────────── */
    footer {
        position: fixed;
        bottom: -14mm;
        left: 0; right: 0;
        height: 11mm;
        background-color: #f3f4f6;
        border-top: 1px solid #e5e7eb;
        padding: 0 10mm;
        display: block;
    }
    .ftr-inner {
        display: table;
        width: 100%;
        height: 11mm;
    }
    .ftr-left, .ftr-right {
        display: table-cell;
        vertical-align: middle;
        font-size: 7pt;
        color: #9ca3af;
    }
    .ftr-right { text-align: right; }

    /* ── Cover ──────────────────────────────────────────────── */
    .cover {
        background-color: #14532d;
        color: #fff;
        padding: 18mm 14mm;
        text-align: center;
        margin-bottom: 8mm;
        border-radius: 4px;
    }
    .cover-brand  { font-size: 10pt; color: #86efac; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 5mm; }
    .cover-title  { font-size: 26pt; font-weight: bold; line-height: 1.2; margin-bottom: 4mm; }
    .cover-sub    { font-size: 11pt; color: #bbf7d0; margin-bottom: 3mm; }
    .cover-date   { font-size: 8pt;  color: #6ee7b7; font-style: italic; }

    /* ── Table of Contents ──────────────────────────────────── */
    .toc-title { font-size: 11pt; font-weight: bold; color: #166534; margin-bottom: 3mm; text-transform: uppercase; letter-spacing: 1px; }
    .toc-table { width: 100%; border-collapse: collapse; margin-bottom: 8mm; }
    .toc-table tr:nth-child(even) { background: #f9fafb; }
    .toc-table td { padding: 3mm 3mm; font-size: 8.5pt; border-bottom: 0.3pt solid #e5e7eb; }
    .toc-num  { color: #15803d; font-weight: bold; width: 8mm; }
    .toc-link { color: #166534; }

    /* ── Section banner ─────────────────────────────────────── */
    .section-banner {
        background-color: #166534;
        color: #fff;
        padding: 4mm 6mm;
        margin-bottom: 5mm;
        margin-top: 8mm;
        border-radius: 3px;
    }
    .section-banner.teal   { background-color: #0f766e; }
    .section-banner.blue   { background-color: #1d4ed8; }
    .section-banner.amber  { background-color: #b45309; }
    .section-banner.red    { background-color: #b91c1c; }
    .section-banner.purple { background-color: #7c3aed; }
    .section-banner.sky    { background-color: #0369a1; }
    .section-title { font-size: 12pt; font-weight: bold; }
    .section-sub   { font-size: 8pt; color: #bbf7d0; margin-top: 1mm; }

    /* ── Alert boxes ─────────────────────────────────────────── */
    .alert { padding: 3.5mm 5mm; border-radius: 3px; margin-bottom: 5mm; }
    .alert-red    { background: #fee2e2; border-left: 3px solid #dc2626; }
    .alert-green  { background: #dcfce7; border-left: 3px solid #16a34a; }
    .alert-amber  { background: #fef3c7; border-left: 3px solid #d97706; }
    .alert-teal   { background: #ccfbf1; border-left: 3px solid #0f766e; }
    .alert-title  { font-weight: bold; font-size: 9pt; margin-bottom: 1.5mm; }
    .alert-red .alert-title   { color: #991b1b; }
    .alert-green .alert-title { color: #166534; }
    .alert-amber .alert-title { color: #92400e; }
    .alert-teal .alert-title  { color: #134e4a; }
    .alert p { font-size: 8.5pt; color: #374151; }

    /* ── Stat boxes ─────────────────────────────────────────── */
    .stat-row { width: 100%; margin-bottom: 5mm; }
    .stat-row td { width: 33%; text-align: center; padding: 4mm; border-radius: 3px; }
    .stat-green  { background: #dcfce7; }
    .stat-teal   { background: #ccfbf1; }
    .stat-value  { font-size: 18pt; font-weight: bold; color: #166534; display: block; }
    .stat-teal .stat-value { color: #0f766e; }
    .stat-label  { font-size: 7.5pt; color: #6b7280; }

    /* ── Data tables ─────────────────────────────────────────── */
    .data-table { width: 100%; border-collapse: collapse; margin-bottom: 5mm; font-size: 8.5pt; }
    .data-table th {
        background-color: #166534;
        color: #fff;
        padding: 3mm 4mm;
        text-align: left;
        font-weight: bold;
    }
    .data-table th.teal   { background-color: #0f766e; }
    .data-table th.blue   { background-color: #1d4ed8; }
    .data-table th.amber  { background-color: #b45309; }
    .data-table th.red    { background-color: #b91c1c; }
    .data-table th.purple { background-color: #7c3aed; }
    .data-table th.sky    { background-color: #0369a1; }
    .data-table td { padding: 2.5mm 4mm; border-bottom: 0.3pt solid #e5e7eb; vertical-align: top; }
    .data-table tr:nth-child(even) td { background: #f9fafb; }
    .data-table tr:nth-child(even).teal-stripe td { background: #f0fdfa; }
    .data-table .label-col { font-weight: bold; color: #374151; }
    .data-table .badge { display: inline-block; padding: 0.5mm 2.5mm; border-radius: 8px; font-size: 7.5pt; font-weight: bold; }
    .badge-red    { background: #fee2e2; color: #991b1b; }
    .badge-orange { background: #ffedd5; color: #9a3412; }
    .badge-amber  { background: #fef3c7; color: #92400e; }
    .badge-yellow { background: #fefce8; color: #854d0e; }
    .badge-blue   { background: #dbeafe; color: #1e40af; }
    .badge-gray   { background: #f3f4f6; color: #4b5563; }

    /* ── Two-column layout ───────────────────────────────────── */
    .two-col { width: 100%; margin-bottom: 5mm; }
    .two-col td { width: 50%; vertical-align: top; padding: 0; }
    .col-box { padding: 4mm; border-radius: 3px; margin: 0 1.5mm; }
    .col-green  { background: #dcfce7; border: 0.5pt solid #86efac; }
    .col-red    { background: #fee2e2; border: 0.5pt solid #fca5a5; }
    .col-blue   { background: #dbeafe; border: 0.5pt solid #93c5fd; }
    .col-amber  { background: #fef3c7; border: 0.5pt solid #fcd34d; }
    .col-box h4 { font-size: 9pt; font-weight: bold; margin-bottom: 3mm; }
    .col-green h4  { color: #166534; }
    .col-red h4    { color: #991b1b; }
    .col-blue h4   { color: #1e3a8a; }
    .col-amber h4  { color: #92400e; }

    /* ── Bullet list ─────────────────────────────────────────── */
    .bullet-list { margin: 0 0 4mm 0; padding: 0; list-style: none; }
    .bullet-list li { padding: 1mm 0 1mm 5mm; font-size: 8.5pt; color: #374151; border-bottom: 0.3pt solid #f3f4f6; position: relative; }
    .bullet-list li:before { content: "•"; position: absolute; left: 0; color: #16a34a; font-weight: bold; }
    .bullet-list.cross li:before { content: "x"; color: #dc2626; }
    .bullet-list.arrow li:before { content: ">"; color: #6b7280; }

    /* ── Step list ───────────────────────────────────────────── */
    .steps { list-style: none; padding: 0; margin-bottom: 5mm; }
    .steps li { display: table; width: 100%; margin-bottom: 3mm; }
    .step-num { display: table-cell; width: 8mm; vertical-align: top; }
    .step-circle { width: 6mm; height: 6mm; background: #0f766e; color: #fff; border-radius: 50%; text-align: center; font-size: 7.5pt; font-weight: bold; line-height: 6mm; }
    .step-body { display: table-cell; padding-left: 3mm; vertical-align: top; font-size: 8.5pt; color: #374151; }
    .step-body strong { color: #0f766e; }

    /* ── Phrase grid ─────────────────────────────────────────── */
    .phrase-table { width: 100%; border-collapse: collapse; margin-bottom: 5mm; }
    .phrase-table td { width: 20%; padding: 2.5mm; text-align: center; border: 0.5pt solid #e9d5ff; font-size: 8pt; }
    .phrase-table tr:nth-child(odd) td { background: #f5f3ff; }
    .phrase-en { color: #6b7280; font-size: 7pt; }
    .phrase-lg { font-weight: bold; color: #7c3aed; }

    /* ── Info rows (key/value) ───────────────────────────────── */
    .info-row { width: 100%; border-collapse: collapse; margin-bottom: 5mm; }
    .info-row td { padding: 3mm 5mm; border-bottom: 0.3pt solid #e5e7eb; font-size: 8.5pt; vertical-align: top; }
    .info-row tr:nth-child(even) td { background: #f0fdfa; }
    .info-row .key { width: 35%; font-weight: bold; color: #374151; }

    /* ── Month table ─────────────────────────────────────────── */
    .month-table { width: 100%; border-collapse: collapse; margin-bottom: 5mm; font-size: 8pt; }
    .month-table th { background: #374151; color: #fff; padding: 3mm 3mm; text-align: left; }
    .month-table td { padding: 2.5mm 3mm; border-bottom: 0.3pt solid #e5e7eb; }
    .month-table tr:nth-child(even) td { background: #fefce8; }
    .stars { color: #d97706; }

    /* ── Region table ────────────────────────────────────────── */
    .region-table { width: 100%; border-collapse: collapse; margin-bottom: 5mm; font-size: 8.5pt; }
    .region-table th { background: #0369a1; color: #fff; padding: 3mm 4mm; text-align: left; }
    .region-table td { padding: 3mm 4mm; border-bottom: 0.3pt solid #e5e7eb; vertical-align: top; }
    .region-table tr:nth-child(even) td { background: #f0f9ff; }
    .temp { font-weight: bold; color: #0369a1; }

    /* ── FAQ ─────────────────────────────────────────────────── */
    .faq-item { margin-bottom: 4mm; background: #f9fafb; border-left: 2.5pt solid #16a34a; padding: 3mm 4mm; border-radius: 2px; }
    .faq-q { font-weight: bold; font-size: 8.5pt; color: #166534; margin-bottom: 1.5mm; }
    .faq-a { font-size: 8.5pt; color: #374151; }

    /* ── Contact / back cover ───────────────────────────────── */
    .back-cover {
        background: #14532d;
        color: #fff;
        padding: 8mm 10mm;
        text-align: center;
        border-radius: 4px;
        margin-top: 8mm;
    }
    .back-cover h2 { font-size: 14pt; margin-bottom: 3mm; }
    .back-cover p  { font-size: 9pt; color: #bbf7d0; margin-bottom: 2mm; }
    .back-cover .small { font-size: 7.5pt; color: #6ee7b7; font-style: italic; margin-top: 4mm; }

    /* ── Utilities ───────────────────────────────────────────── */
    h2 { font-size: 11pt; font-weight: bold; color: #1f2937; margin: 4mm 0 2.5mm; }
    h3 { font-size: 9.5pt; font-weight: bold; color: #166534; margin: 3mm 0 2mm; }
    p  { font-size: 8.5pt; color: #374151; margin-bottom: 2.5mm; }
    .mb { margin-bottom: 5mm; }
    .page-break { page-break-after: always; }
    a { color: #0f766e; text-decoration: none; }
</style>
</head>
<body>

{{-- ═══ HEADER (fixed, repeats on every page) ═════════════════════ --}}
<header>
    <div class="hdr-inner">
        <div class="hdr-left">CALM AFRICA SAFARIS</div>
        <div class="hdr-right">Uganda &amp; East Africa Tourist Information Guide</div>
    </div>
</header>

{{-- ═══ FOOTER (fixed, repeats on every page) ════════════════════ --}}
<footer>
    <div class="ftr-inner">
        <div class="ftr-left">info@safaritours.com  |  +256 777 143 020  |  www.calmafrica.com</div>
        <div class="ftr-right">Calm Africa Safaris · Kampala, Uganda</div>
    </div>
</footer>

{{-- ═══════════════════════════════════════════════════════════════
     COVER PAGE
════════════════════════════════════════════════════════════════ --}}
<div class="cover">
    <div class="cover-brand">Calm Africa Safaris</div>
    <div class="cover-title">Uganda &amp; East Africa<br>Tourist Information Guide</div>
    <div class="cover-sub">Everything you need to know before your safari</div>
    <div class="cover-date">Comprehensive travel guide · Updated {{ date('Y') }}</div>
</div>

{{-- TOC --}}
<div class="toc-title">Contents</div>
<table class="toc-table">
    @foreach([
        ['01','Visa Requirements — Uganda'],
        ['02','East Africa Tourist Visa (EATV) — Full Details'],
        ['03','Health &amp; Vaccinations'],
        ['04','Best Time to Visit'],
        ['05','Money &amp; Currency'],
        ['06','What to Pack'],
        ['07','Safety in Uganda'],
        ['08','Getting There &amp; Transport'],
        ['09','Culture &amp; Etiquette'],
        ['10','Climate &amp; Weather'],
        ['11','Frequently Asked Questions'],
    ] as [$n,$t])
    <tr>
        <td class="toc-num">{{ $n }}</td>
        <td class="toc-link">{{ $t }}</td>
    </tr>
    @endforeach
</table>

<div class="page-break"></div>

{{-- ═══════════════════════════════════════════════════════════════
     01 · VISA REQUIREMENTS
════════════════════════════════════════════════════════════════ --}}
<div class="section-banner">
    <div class="section-title">01 &mdash; Visa Requirements</div>
    <div class="section-sub">Entry requirements for Uganda</div>
</div>

<div class="alert alert-red">
    <div class="alert-title">&#9888; MANDATORY — Yellow Fever Certificate</div>
    <p>A Yellow Fever vaccination certificate is <strong>legally required</strong> to enter Uganda.
    You must carry the <strong>original certificate</strong> — photocopies are not accepted.
    Get vaccinated at least <strong>10 days before departure</strong>.</p>
</div>

<h2>Uganda Single-Entry Visa</h2>
<table class="stat-row">
    <tr>
        <td class="stat-green"><span class="stat-value">$50</span><span class="stat-label">Per person (USD)</span></td>
        <td class="stat-green"><span class="stat-value">90</span><span class="stat-label">Days maximum stay</span></td>
        <td class="stat-green"><span class="stat-value">1</span><span class="stat-label">Country — Uganda only</span></td>
    </tr>
</table>

<ul class="bullet-list">
    <li>Apply online at <strong>visas.immigration.go.ug</strong> or on arrival at Entebbe International Airport (EBB)</li>
    <li>Apply at least <strong>2 weeks before travel</strong> when applying online</li>
    <li>Payment by credit or debit card for online applications</li>
    <li>Print your e-visa approval letter and carry it alongside your passport</li>
</ul>

<h2>Documents Required at Entry</h2>
<table class="data-table">
    <tr><th style="width:30%">Document</th><th>Details</th></tr>
    @foreach([
        ['Passport','Valid for at least 6 months beyond your intended stay, with at least 2 blank pages'],
        ['Yellow Fever Certificate','MANDATORY — original required, photocopies refused'],
        ['Return / Onward Ticket','Proof of travel out of Uganda required at immigration'],
        ['Proof of Accommodation','Hotel booking confirmation, lodge voucher, or safari booking letter'],
        ['Financial Means','Bank statement showing sufficient funds (~$50 USD per day minimum)'],
        ['E-visa Approval Letter','Printed approval email if you applied online'],
        ['Passport Photographs','2 recent passport-size photos (white background)'],
        ['Travel Insurance','Proof of comprehensive cover including medical evacuation'],
    ] as [$k,$v])
    <tr><td class="label-col">{{ $k }}</td><td>{{ $v }}</td></tr>
    @endforeach
</table>

<div class="page-break"></div>

{{-- ═══════════════════════════════════════════════════════════════
     02 · EAST AFRICA TOURIST VISA (EATV)
════════════════════════════════════════════════════════════════ --}}
<div class="section-banner teal">
    <div class="section-title">02 &mdash; East Africa Tourist Visa (EATV)</div>
    <div class="section-sub">Uganda &middot; Kenya &middot; Rwanda — one visa, three countries</div>
</div>

<table class="stat-row">
    <tr>
        <td class="stat-teal"><span class="stat-value">$100</span><span class="stat-label">Visa fee (USD)</span></td>
        <td class="stat-teal"><span class="stat-value">90 Days</span><span class="stat-label">Maximum stay</span></td>
        <td class="stat-teal"><span class="stat-value">3 Countries</span><span class="stat-label">Uganda · Kenya · Rwanda</span></td>
    </tr>
</table>

<p>The <strong>East Africa Tourist Visa (EATV)</strong> allows you to travel freely between <strong>Uganda, Kenya, and Rwanda</strong>
on a single visa for USD $100. It permits <strong>multiple entries</strong> between the three countries within its 90-day
validity — ideal for multi-country itineraries such as gorilla trekking in Uganda combined with a Maasai Mara safari in Kenya.</p>

<table class="two-col">
    <tr>
        <td>
            <div class="col-box col-green">
                <h4>&#10003; Key Benefits</h4>
                <ul class="bullet-list">
                    <li>Single application for three countries</li>
                    <li>Multiple entries between Uganda, Kenya &amp; Rwanda</li>
                    <li>Perfect for multi-country safari itineraries</li>
                    <li>Same cost or cheaper than 3 individual visas</li>
                    <li>Apply through any of the three countries' portals</li>
                </ul>
            </div>
        </td>
        <td>
            <div class="col-box col-amber">
                <h4>&#9888; Important Limitations</h4>
                <ul class="bullet-list cross">
                    <li>NOT valid for Tanzania — separate $50 visa required</li>
                    <li>NOT valid for Burundi, South Sudan, or other EAC states</li>
                    <li>Must first enter through Uganda, Kenya, or Rwanda</li>
                    <li>Cannot be extended beyond 90 days</li>
                </ul>
            </div>
        </td>
    </tr>
</table>

<h2>EATV — Full Requirements &amp; Details</h2>
<table class="info-row">
    @foreach([
        ['Visa Fee',           'USD $100 — non-refundable. Payable by credit or debit card online.'],
        ['Entry Points',       'Must enter through Uganda, Kenya, or Rwanda. Cannot be used for Tanzania, Burundi, or other EAC states.'],
        ['Maximum Stay',       '90 days total across all three countries combined. Not extendable.'],
        ['Validity Period',    '90 days from the date the visa is issued — not from your arrival date.'],
        ['Passport Validity',  'Minimum 6 months beyond intended stay. At least 2 blank pages.'],
        ['Yellow Fever',       'MANDATORY for Uganda and Kenya entry. Must be the original certificate.'],
        ['Onward Ticket',      'Proof of travel out of the East Africa region required at immigration.'],
        ['Accommodation',      'Hotel bookings, safari confirmation letter, or host invitation.'],
        ['Financial Means',    'Bank statement showing sufficient funds (~$50 USD per day minimum).'],
        ['Photographs',        '2 recent passport-size photographs (white background).'],
        ['Application Portals','visas.immigration.go.ug (Uganda)   |   evisa.go.ke (Kenya)   |   irembo.gov.rw (Rwanda)'],
        ['Processing Time',    '3–10 business days online. On-arrival EATV available at Entebbe, Nairobi JKIA, and Kigali airports.'],
    ] as [$k,$v])
    <tr><td class="key">{{ $k }}</td><td>{{ $v }}</td></tr>
    @endforeach
</table>

<h2>How to Apply — Step by Step</h2>
<ol class="steps">
    @foreach([
        ['Choose your portal','Visit visas.immigration.go.ug, evisa.go.ke, or irembo.gov.rw — choose the country you enter East Africa through first.'],
        ['Create an account','Register with a valid email address to track your application.'],
        ['Select East Africa Tourist Visa','Select EATV — not single-entry. If only visiting Uganda, single-entry ($50) is cheaper.'],
        ['Complete the form','Fill in personal details, passport info, travel dates. Attach your passport bio page, photo, Yellow Fever certificate, and accommodation proof.'],
        ['Pay USD $100','By Visa or Mastercard. Keep your payment receipt and reference number.'],
        ['Wait for approval','3–10 business days. You will receive an approval email.'],
        ['Print your e-visa','Print the approval letter and present it alongside your passport at immigration.'],
    ] as [$t,$d])
    <li>
        <div class="step-num"><div class="step-circle">{{ $loop->index + 1 }}</div></div>
        <div class="step-body"><strong>{{ $t }}:</strong> {{ $d }}</div>
    </li>
    @endforeach
</ol>

<div class="page-break"></div>

{{-- ═══════════════════════════════════════════════════════════════
     03 · HEALTH & VACCINATIONS
════════════════════════════════════════════════════════════════ --}}
<div class="section-banner blue">
    <div class="section-title">03 &mdash; Health &amp; Vaccinations</div>
    <div class="section-sub">Stay healthy on your safari</div>
</div>

<div class="alert alert-red">
    <div class="alert-title">&#9888; Mandatory: Yellow Fever Certificate</div>
    <p>Required by law to enter Uganda. Carry the original. Get vaccinated at least 10 days before departure.</p>
</div>

<table class="data-table">
    <tr>
        <th class="blue" style="width:22%">Vaccine</th>
        <th class="blue" style="width:22%">Status</th>
        <th class="blue">Notes</th>
    </tr>
    @foreach([
        ['Yellow Fever',         'badge-red',    'MANDATORY',           'Required for entry. Original certificate essential.'],
        ['Malaria prophylaxis',  'badge-orange', 'Critical',            'Consult your doctor — Malarone, Doxycycline, or Lariam. Start before travel.'],
        ['Hepatitis A &amp; B',  'badge-amber',  'Strongly Recommended','Food and waterborne disease risk.'],
        ['Typhoid',              'badge-amber',  'Recommended',         'Food and waterborne disease.'],
        ['Rabies',               'badge-blue',   'Recommended',         'For trekking and wildlife activities.'],
        ['Meningitis',           'badge-blue',   'Recommended',         'Especially October–April.'],
        ['Tetanus &amp; Diphtheria','badge-gray','Ensure up to date',   'Standard booster if overdue.'],
        ['COVID-19',             'badge-gray',   'Check requirements',  'Verify current rules before travel.'],
    ] as [$v,$badge,$s,$n])
    <tr>
        <td class="label-col">{!! $v !!}</td>
        <td><span class="badge {{ $badge }}">{{ $s }}</span></td>
        <td>{{ $n }}</td>
    </tr>
    @endforeach
</table>

<table class="two-col">
    <tr>
        <td>
            <div class="col-box col-blue">
                <h4>Malaria Prevention</h4>
                <ul class="bullet-list">
                    <li>Take anti-malarials — complete the full course after returning</li>
                    <li>DEET repellent 50%+ on all exposed skin at dawn/dusk</li>
                    <li>Wear long sleeves and trousers during high-risk periods</li>
                    <li>Sleep under a mosquito net (provided at reputable lodges)</li>
                    <li>Carry a rapid malaria test kit for remote areas</li>
                    <li>Seek medical help if fever develops within 3 months of return</li>
                </ul>
            </div>
        </td>
        <td>
            <div class="col-box col-green">
                <h4>General Health Tips</h4>
                <ul class="bullet-list">
                    <li>Drink bottled or purified water only — never tap water or ice</li>
                    <li>Apply SPF 50+ sunscreen daily</li>
                    <li>Carry all prescription meds in hand luggage with doctor's letter</li>
                    <li>Gorilla trekking: visitors with colds or flu are denied access</li>
                    <li>Altitude sickness possible on Rwenzori and Elgon treks</li>
                </ul>
            </div>
        </td>
    </tr>
</table>

{{-- ═══ 04 · BEST TIME ════════════════════════════════════════════ --}}
<div class="section-banner amber">
    <div class="section-title">04 &mdash; Best Time to Visit</div>
    <div class="section-sub">When to plan your safari for the best experience</div>
</div>

<table class="month-table">
    <tr>
        <th>Month</th><th>Weather</th><th>Wildlife</th><th>Crowds</th><th>Rating</th>
    </tr>
    @foreach([
        ['Jan','Dry','Excellent','Low','★★★★★'],['Feb','Dry','Excellent','Low','★★★★★'],
        ['Mar','Rains begin','Good','Very Low','★★★'],['Apr','Wet','Good','Very Low','★★★'],
        ['May','Wet','Good','Very Low','★★★'],['Jun','Dry','Excellent','High','★★★★★'],
        ['Jul','Dry','Excellent','Peak','★★★★★'],['Aug','Dry','Excellent','Peak','★★★★★'],
        ['Sep','Dry','Excellent','High','★★★★★'],['Oct','Light rains','Very Good','Moderate','★★★★'],
        ['Nov','Light rains','Very Good','Low','★★★★'],['Dec','Dry','Excellent','Moderate','★★★★★'],
    ] as [$m,$w,$wl,$cr,$r])
    <tr>
        <td><strong>{{ $m }}</strong></td>
        <td>{{ $w }}</td><td>{{ $wl }}</td><td>{{ $cr }}</td>
        <td class="stars">{{ $r }}</td>
    </tr>
    @endforeach
</table>

<div class="page-break"></div>

{{-- ═══ 05 · MONEY ════════════════════════════════════════════════ --}}
<div class="section-banner">
    <div class="section-title">05 &mdash; Money &amp; Currency</div>
    <div class="section-sub">Managing your finances in Uganda</div>
</div>

<table class="two-col">
    <tr>
        <td>
            <div class="col-box col-green">
                <h4>Money Tips</h4>
                <ul class="bullet-list">
                    <li>Bring USD cash in small denominations ($1, $5, $10, $20)</li>
                    <li><strong>Only USD notes printed after 2006 are accepted</strong></li>
                    <li>ATMs in Kampala and major towns — rare in remote parks</li>
                    <li>Visa and Mastercard accepted at most upmarket lodges</li>
                    <li>Notify your bank before travel to avoid blocked cards</li>
                    <li>Use licensed forex bureaus — better rates than hotels</li>
                </ul>
            </div>
        </td>
        <td>
            <div class="col-box col-blue">
                <h4>Tipping Guide (USD)</h4>
                <table style="width:100%; font-size:8pt; border-collapse:collapse;">
                    @foreach([
                        ['Safari Guide','$10–20 / day'],['Safari Driver','$5–10 / day'],
                        ['Gorilla Guide/Tracker','$10–20 / trek'],['Porter (trekking)','$5–10 / trek'],
                        ['Lodge / Hotel Staff','$1–5 / day'],['Restaurant','10% of bill'],
                        ['Airport Transfer','$3–5'],
                    ] as [$r,$a])
                    <tr style="border-bottom:0.3pt solid #dbeafe;">
                        <td style="padding:1.5mm 0;">{{ $r }}</td>
                        <td style="padding:1.5mm 0; text-align:right; font-weight:bold; color:#166534;">{{ $a }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </td>
    </tr>
</table>

{{-- ═══ 06 · PACKING ══════════════════════════════════════════════ --}}
<div class="section-banner amber">
    <div class="section-title">06 &mdash; What to Pack</div>
    <div class="section-sub">Your complete safari packing checklist</div>
</div>

<div class="alert alert-amber">
    <p><strong>Clothing colours:</strong> Neutral only — khaki, beige, olive, brown, grey.
    Avoid bright colours (attract tsetse flies) and white (shows dust).
    <strong>Camouflage is illegal in Uganda.</strong></p>
</div>

@foreach([
    ['Clothing', [
        'Lightweight long-sleeved shirts × 3–4 (neutral colours)',
        'Long trousers neutral × 2–3 pairs  |  Comfortable shorts × 2',
        'Fleece or light jacket  |  Waterproof rain jacket or poncho',
        'Wide-brimmed sun hat  |  Warm layer for gorilla trekking altitude',
        'Sturdy waterproof hiking boots (broken in!)  |  Sandals for lodge evenings',
        'Swimwear (most lodges have pools)',
    ]],
    ['Health &amp; Hygiene', [
        'Yellow Fever certificate — MANDATORY original',
        'Anti-malarial tablets (complete full course)  |  DEET repellent 50%+',
        'Sunscreen SPF 50+  |  SPF lip balm',
        'Basic first aid kit  |  Oral rehydration sachets  |  Imodium',
        'Prescription medications (excess supply + doctor\'s letter)',
        'Hand sanitiser  |  Wet wipes',
    ]],
    ['Documents &amp; Money', [
        'Passport (6+ months validity)  |  Visa / EATV approval letter (printed)',
        'Travel insurance documents + emergency number',
        'Flight tickets — printed and digital copies',
        'Hotel and tour booking confirmations',
        'USD cash in small denominations (post-2006 notes only)',
        'Credit/debit cards (notify your bank before travel)',
    ]],
    ['Safari Essentials', [
        'Binoculars (8×42 or 10×42)  |  Camera with zoom lens (200mm+ ideal)',
        'Extra memory cards  |  Spare batteries  |  Portable power bank',
        'Universal adaptor — Uganda uses Type G (UK plug, 240V/50Hz)',
        'Daypack for game drives  |  Reusable water bottle (2 litres)',
        'Headlamp with spare batteries  |  Bandana or buff (dust)',
    ]],
    ['Gorilla Trek Specific', [
        'Sturdy waterproof hiking boots — ankle support essential',
        'Long trousers and long-sleeved shirt (protection from nettles)',
        'Waterproof jacket  |  Thick gardening gloves (grip vegetation)',
        'Small backpack only — no large bags allowed in the forest',
        'Walking stick (can be hired at the park gate)',
        'Must be 15+ years old (Uganda Wildlife Authority rule)  |  No colds/flu',
    ]],
] as [$heading, $items])
<h3>{!! $heading !!}</h3>
<ul class="bullet-list">
    @foreach($items as $item)
    <li>{{ $item }}</li>
    @endforeach
</ul>
@endforeach

<div class="page-break"></div>

{{-- ═══ 07 · SAFETY ═══════════════════════════════════════════════ --}}
<div class="section-banner red">
    <div class="section-title">07 &mdash; Safety in Uganda</div>
    <div class="section-sub">Travel with confidence</div>
</div>

<div class="alert alert-green">
    <div class="alert-title">&#10003; Uganda Is a Safe Destination</div>
    <p>Violent crime against tourists is rare. With sensible precautions, the vast majority of visitors enjoy a completely trouble-free safari experience.</p>
</div>

<table class="two-col">
    <tr>
        <td>
            <div class="col-box col-blue">
                <h4>In Kampala &amp; Towns</h4>
                <ul class="bullet-list">
                    <li>Avoid walking alone at night — use taxis or Bolt/SafeBoda</li>
                    <li>Keep valuables out of sight in public places</li>
                    <li>Use ATMs inside banks or shopping malls</li>
                    <li>Carry a passport photocopy; keep original in hotel safe</li>
                    <li>Be aware of pickpockets in crowded markets</li>
                    <li>Only use registered or app-based taxis</li>
                </ul>
            </div>
        </td>
        <td>
            <div class="col-box col-green">
                <h4>In the Parks &amp; Bush</h4>
                <ul class="bullet-list">
                    <li>Always follow your ranger guide's instructions exactly</li>
                    <li>Maintain required distances from wildlife at all times</li>
                    <li>Never feed or approach animals</li>
                    <li>Stay in the vehicle unless the guide confirms it's safe</li>
                    <li>Carry a charged phone with emergency numbers saved</li>
                    <li>Gorilla trekking: stay calm if charged — crouch and look away</li>
                </ul>
            </div>
        </td>
    </tr>
</table>

<h2>Emergency Numbers</h2>
<table class="data-table">
    <tr><th class="red" style="width:45%">Service</th><th class="red">Number</th></tr>
    @foreach([
        ['Police',                         '999 / 112'],
        ['Ambulance',                      '0800 199 000'],
        ['Fire Brigade',                   '0800 199 399'],
        ['Norvik Hospital (Kampala)',       '+256 414 259 027'],
        ['Entebbe Airport Security',       '+256 414 320 512'],
        ['Calm Africa Safaris 24/7',       '+256 752 088 768'],
    ] as [$s,$n])
    <tr><td class="label-col">{{ $s }}</td><td><strong>{{ $n }}</strong></td></tr>
    @endforeach
</table>

{{-- ═══ 08 · TRANSPORT ════════════════════════════════════════════ --}}
<div class="section-banner" style="background-color:#4338ca;">
    <div class="section-title">08 &mdash; Getting There &amp; Transport</div>
    <div class="section-sub">Flights, transfers, and getting around Uganda</div>
</div>

<p>The main entry point is <strong>Entebbe International Airport (EBB)</strong>, located 37km south of Kampala on the shores of Lake Victoria.</p>

<table class="data-table">
    <tr><th style="width:30%;background:#4338ca;">Airline / Route</th><th style="background:#4338ca;">Flight Time from Hub</th></tr>
    @foreach([
        ['Kenya Airways (Nairobi)','1h 15min'],
        ['Ethiopian Airlines (Addis Ababa)','2h 30min'],
        ['Emirates (Dubai)','5h 30min'],
        ['Qatar Airways (Doha)','5h 45min'],
        ['KLM (Amsterdam)','9h'],
        ['British Airways (London)','8h 45min'],
        ['RwandAir (Kigali)','1h'],
        ['Turkish Airlines (Istanbul)','7h'],
    ] as [$a,$t])
    <tr><td class="label-col">{{ $a }}</td><td>{{ $t }}</td></tr>
    @endforeach
</table>

{{-- ═══ 09 · CULTURE ══════════════════════════════════════════════ --}}
<div class="section-banner purple">
    <div class="section-title">09 &mdash; Culture &amp; Etiquette</div>
    <div class="section-sub">Respect and engage with Uganda's people and traditions</div>
</div>

<table class="two-col">
    <tr>
        <td>
            <div class="col-box col-green">
                <h4>&#10003; Do's</h4>
                <ul class="bullet-list">
                    <li>Greet people warmly — greetings are very important in Ugandan culture</li>
                    <li>Use both hands or right hand when giving/receiving items</li>
                    <li>Dress modestly in rural areas and religious sites</li>
                    <li>Ask permission before photographing people</li>
                    <li>Remove shoes before entering a mosque or traditional home</li>
                    <li>Bargain respectfully at markets — it's expected</li>
                    <li>Learn a few Luganda words — always appreciated</li>
                </ul>
            </div>
        </td>
        <td>
            <div class="col-box col-red">
                <h4>&#10007; Don'ts</h4>
                <ul class="bullet-list cross">
                    <li>Do NOT photograph military, government buildings, or airports — illegal</li>
                    <li>Do NOT wear camouflage — illegal for civilians in Uganda</li>
                    <li>Avoid public displays of affection beyond holding hands</li>
                    <li>Do not pass items with your left hand in traditional settings</li>
                    <li>Do not raise your voice or show impatience</li>
                    <li>Do not litter — strict laws apply in Uganda</li>
                    <li>Do NOT bring plastic bags — they are banned in Uganda</li>
                </ul>
            </div>
        </td>
    </tr>
</table>

<h2>Useful Luganda Phrases</h2>
<table class="phrase-table">
    @foreach([['Hello','Oli otya'],['Good morning','Wasuze otya'],['Thank you','Webale nyo'],['You\'re welcome','Kale'],['Yes','Yee'],['No','Nedda'],['Please','Mwattu'],['How much?','Semanga?'],['Welcome','Tukusanyukidde'],['Good','Bulungi']] as [$en,$lg])
    <td><div class="phrase-en">{{ $en }}</div><div class="phrase-lg">{{ $lg }}</div></td>
    @if($loop->index % 5 === 4 && !$loop->last) </tr><tr> @endif
    @endforeach
</table>

<div class="page-break"></div>

{{-- ═══ 10 · CLIMATE ══════════════════════════════════════════════ --}}
<div class="section-banner sky">
    <div class="section-title">10 &mdash; Climate &amp; Weather</div>
    <div class="section-sub">What to expect from Uganda's weather</div>
</div>

<table class="region-table">
    <tr>
        <th style="width:30%">Region</th>
        <th style="width:15%">Temperature</th>
        <th>Notes</th>
    </tr>
    @foreach([
        ['Kampala &amp; Central Uganda','24–29°C','Warm and humid. Two wet seasons (March–May, Oct–Nov). Pleasant year-round.'],
        ['Murchison Falls (North)','25–35°C','Hotter and drier. Best game viewing in dry season (June–Sept).'],
        ['Queen Elizabeth NP','20–27°C','Moderate temperatures. Rainfall spread evenly throughout the year.'],
        ['Bwindi Forest (Gorillas)','7–20°C','Cool to cold at altitude (1,190–2,607m). Warm layer essential. Rain year-round.'],
        ['Rwenzori Mountains','0–25°C','Extreme range. Snow above 4,000m. Prepare for all conditions.'],
        ['Lake Victoria Region','20–27°C','Humid. Afternoon thunderstorms common April–May.'],
    ] as [$r,$t,$n])
    <tr>
        <td class="label-col">{!! $r !!}</td>
        <td class="temp">{{ $t }}</td>
        <td>{{ $n }}</td>
    </tr>
    @endforeach
</table>

{{-- ═══ 11 · FAQ ═══════════════════════════════════════════════════ --}}
<div class="section-banner">
    <div class="section-title">11 &mdash; Frequently Asked Questions</div>
    <div class="section-sub">Quick answers for travellers</div>
</div>

@foreach([
    ['Is Uganda safe for solo travellers?','Yes. Uganda is one of East Africa\'s safer destinations. Solo travellers are warmly welcomed. Stay in reputable lodges and book guided activities — going alone into parks is not permitted.'],
    ['Do I need to speak Swahili or Luganda?','No. English is Uganda\'s official language, widely spoken in hotels, lodges, parks, and cities.'],
    ['Does the EATV cover Tanzania?','No. The EATV covers Uganda, Kenya, and Rwanda only. Tanzania requires a separate visa ($50 USD, available on arrival or at immigration.go.tz).'],
    ['How far in advance should I book gorilla permits?','At least 3–6 months for peak season (June–Sept), 1–3 months for the off-season. Only 8 visitors per gorilla family per day.'],
    ['What is the minimum age for gorilla trekking?','15 years old — Uganda Wildlife Authority rule. No exceptions.'],
    ['Is tap water safe to drink?','No. Drink only bottled or purified water. All reputable lodges provide safe drinking water.'],
    ['What electrical plugs does Uganda use?','Type G — three square pins, same as the UK. 240V/50Hz. Bring a universal adaptor.'],
    ['How much spending money beyond the tour cost?','Budget roughly $50–100 USD/day for tips, drinks, and souvenirs. Gorilla permits ($800/person) are usually included in your package.'],
    ['Does the EATV allow me to re-enter Uganda after visiting Kenya?','Yes. The EATV allows multiple entries between Uganda, Kenya, and Rwanda throughout the 90-day validity period.'],
    ['What if I get sick during the safari?','Your guide is first-aid trained and our Kampala office provides 24/7 support. Comprehensive travel insurance with medical evacuation cover is mandatory for all clients.'],
] as [$q,$a])
<div class="faq-item">
    <div class="faq-q">Q: {{ $q }}</div>
    <div class="faq-a">{{ $a }}</div>
</div>
@endforeach

{{-- ═══ BACK COVER ════════════════════════════════════════════════ --}}
<div class="back-cover">
    <h2>Calm Africa Safaris</h2>
    <p>Your Trusted East Africa Safari Partner</p>
    <p>info@calm-africa-safaris.com &nbsp;|&nbsp; +256 777 143 020 &nbsp;|&nbsp; +256 752 088 768</p>
    <p>Kansanga, Kampala, Uganda</p>
    <div class="small">This guide is provided for information purposes. Visa requirements, health regulations, and travel conditions change frequently.
    Always verify current requirements with the relevant embassy or official government website before travel.
    Calm Africa Safaris accepts no liability for decisions made based on this document.</div>
</div>

</body>
</html>