@extends('layouts.app')

@section('title', 'Tourist Information | Uganda Safari Travel Guide | Calm Africa Safaris')
@section('meta_description', 'Complete Uganda safari travel guide. Visa requirements, East Africa Tourist Visa, health, best time to visit, packing, safety and more.')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous" referrerpolicy="no-referrer"/>
<style>
    .section-anchor { scroll-margin-top: 5rem; }
    .eatv-gradient { background: linear-gradient(135deg,#0f766e 0%,#0d9488 100%); }
    @media print { .no-print{display:none!important} }
</style>
@endpush

@section('page-header')
<header class="relative bg-gradient-to-r from-green-800 via-green-700 to-emerald-600 py-20 md:py-28 overflow-hidden">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/5 rounded-full pointer-events-none"></div>
    <div class="absolute -bottom-12 -left-12 w-64 h-64 bg-white/5 rounded-full pointer-events-none"></div>

    <div class="relative z-10 container mx-auto px-4 text-center text-white">
        <span class="inline-flex items-center gap-2 bg-white/20 backdrop-blur text-white px-5 py-2 rounded-full text-sm font-medium mb-6">
            <i class="fa-solid fa-circle-info"></i> Essential Travel Information
        </span>
        <h1 class="text-4xl md:text-6xl font-extrabold mb-5 tracking-tight">Before You Travel</h1>
        <p class="text-xl text-white/85 max-w-3xl mx-auto mb-10">
            Everything you need to know before your East Africa safari — visas, EATV, health, money, packing, safety, and more.
        </p>

        {{-- PDF Download CTA --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-10 no-print">
            <a href="{{ route('tourist-info.download-pdf') }}"
               class="inline-flex items-center gap-3 bg-white text-green-800 font-bold px-7 py-3.5 rounded-xl shadow-xl hover:bg-green-50 hover:scale-105 transition-all duration-300 text-base">
                <i class="fa-solid fa-file-pdf text-red-500 text-xl"></i>
                Download Full Guide (PDF)
                <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded-full">Free</span>
            </a>
            <span class="text-white/60 text-sm">
                <i class="fa-solid fa-shield-check mr-1 text-green-300"></i>
                Includes visa requirements, EATV checklist, packing lists &amp; health guide
            </span>
        </div>

        {{-- Section quick-jump --}}
        <div class="flex flex-wrap justify-center gap-2">
            @foreach([
                ['#visa',      'fa-passport',         'Visa'],
                ['#eatv',      'fa-earth-africa',     'EATV'],
                ['#health',    'fa-syringe',          'Health'],
                ['#best-time', 'fa-calendar-days',    'Best Time'],
                ['#money',     'fa-money-bill-wave',  'Money'],
                ['#packing',   'fa-bag-shopping',     'Packing'],
                ['#safety',    'fa-shield-halved',    'Safety'],
                ['#transport', 'fa-plane',            'Getting There'],
                ['#culture',   'fa-handshake',        'Culture'],
                ['#climate',   'fa-cloud-sun',        'Climate'],
                ['#faq',       'fa-circle-question',  'FAQ'],
            ] as [$href, $icon, $label])
                <a href="{{ $href }}"
                   class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/30 backdrop-blur text-white px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 hover:scale-105">
                    <i class="fa-solid {{ $icon }} text-xs opacity-80"></i> {{ $label }}
                </a>
            @endforeach
        </div>

        <nav class="text-sm mt-8">
            <ol class="flex justify-center items-center space-x-2 text-green-300">
                <li><a href="{{ route('index') }}" class="hover:text-white transition-colors">Home</a></li>
                <li><i class="fa-solid fa-chevron-right text-xs"></i></li>
                <li class="text-white font-medium">Tourist Information</li>
            </ol>
        </nav>
    </div>
</header>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen">

    {{-- Sticky download bar --}}
    <div id="sticky-bar"
         class="no-print hidden fixed top-0 inset-x-0 z-50 bg-green-900/95 backdrop-blur shadow-lg py-2 px-4">
        <div class="max-w-5xl mx-auto flex items-center justify-between gap-4">
            <span class="text-white text-sm font-medium hidden sm:block">
                <i class="fa-solid fa-circle-info mr-1 text-green-300"></i> Uganda Safari Tourist Information Guide
            </span>
            <a href="{{ route('tourist-info.download-pdf') }}"
               class="inline-flex items-center gap-2 bg-white text-green-800 font-bold px-5 py-1.5 rounded-lg text-sm hover:bg-green-50 transition-colors ml-auto">
                <i class="fa-solid fa-file-pdf text-red-500"></i> Download PDF Guide
            </a>
        </div>
    </div>

    {{-- ═══ 01 · VISA ════════════════════════════════════════════ --}}
    <section id="visa" class="py-16 bg-white section-anchor">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-sm">
                    <i class="fa-solid fa-passport text-green-700 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Visa Requirements</h2>
                    <p class="text-gray-500 mt-1">Entry requirements for Uganda</p>
                </div>
            </div>

            {{-- Mandatory warning --}}
            <div class="bg-red-50 border border-red-300 rounded-2xl p-5 mb-6 flex gap-4">
                <i class="fa-solid fa-triangle-exclamation text-red-500 text-2xl flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="font-bold text-red-800 mb-1">Mandatory — Yellow Fever Certificate</p>
                    <p class="text-red-700 text-sm">A Yellow Fever vaccination certificate is <strong>legally required</strong> to enter Uganda.
                    Carry the <strong>original certificate</strong> — photocopies are refused. Get vaccinated at least 10 days before departure.</p>
                </div>
            </div>

            {{-- Uganda single-entry --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm mb-6">
                <h3 class="flex items-center gap-2 text-xl font-bold text-gray-900 mb-5">
                    <i class="fa-solid fa-flag text-green-600"></i> Uganda Single-Entry Visa
                </h3>
                <div class="grid grid-cols-3 gap-4 mb-5">
                    @foreach([['$50','Per person (USD)'],['90 Days','Maximum stay'],['1 Country','Uganda only']] as [$v,$l])
                    <div class="bg-green-50 rounded-xl p-4 text-center">
                        <div class="text-2xl sm:text-3xl font-black text-green-700">{{ $v }}</div>
                        <div class="text-xs text-gray-600 mt-1">{{ $l }}</div>
                    </div>
                    @endforeach
                </div>
                <ul class="space-y-2 text-sm text-gray-700">
                    @foreach([
                        ['fa-globe',          'Apply online at <a href="https://visas.immigration.go.ug" target="_blank" class="text-green-600 hover:underline font-medium">visas.immigration.go.ug</a> or on arrival at Entebbe Airport'],
                        ['fa-clock',          'Apply at least <strong>2 weeks before travel</strong> when applying online'],
                        ['fa-credit-card',    'Payment by credit/debit card for online applications'],
                        ['fa-print',          'Print your e-visa approval letter and carry it alongside your passport'],
                    ] as [$ico,$txt])
                    <li class="flex items-start gap-3 bg-gray-50 rounded-lg p-3">
                        <i class="fa-solid {{ $ico }} text-green-500 mt-0.5 flex-shrink-0 w-4"></i>
                        <span>{!! $txt !!}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Documents required --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h3 class="flex items-center gap-2 text-xl font-bold text-gray-900 mb-5">
                    <i class="fa-solid fa-folder-open text-amber-500"></i> Documents Required at Entry
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                    @php
                    $docs = [
                        ['fa-passport',        'green',  'Passport valid for 6+ months beyond your intended stay'],
                        ['fa-book-open',       'green',  'At least 2 blank pages in your passport'],
                        ['fa-syringe',         'red',    'Yellow Fever certificate — MANDATORY, original only'],
                        ['fa-plane-departure', 'blue',   'Return or onward flight ticket out of Uganda'],
                        ['fa-hotel',           'blue',   'Proof of accommodation or hotel booking confirmation'],
                        ['fa-money-bills',     'green',  'Sufficient funds — bank statement (~$50 USD/day)'],
                        ['fa-file-lines',      'amber',  'Printed e-visa approval letter (if applying online)'],
                        ['fa-image-portrait',  'gray',   '2 passport-size photographs (white background)'],
                        ['fa-shield-halved',   'purple', 'Proof of comprehensive travel insurance'],
                    ];
                    $cmap = [
                        'green'  => 'bg-green-50 text-green-600',
                        'red'    => 'bg-red-50 text-red-600',
                        'blue'   => 'bg-blue-50 text-blue-600',
                        'amber'  => 'bg-amber-50 text-amber-600',
                        'purple' => 'bg-purple-50 text-purple-600',
                        'gray'   => 'bg-gray-100 text-gray-500',
                    ];
                    @endphp
                    @foreach($docs as [$ico,$col,$txt])
                    <div class="flex items-start gap-3 bg-gray-50 rounded-xl p-3">
                        <div class="w-8 h-8 rounded-lg {{ $cmap[$col] }} flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid {{ $ico }} text-sm"></i>
                        </div>
                        <span class="text-gray-700 text-sm pt-1.5">{{ $txt }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ 02 · EAST AFRICA TOURIST VISA ════════════════════════ --}}
    <section id="eatv" class="py-16 bg-gray-50 section-anchor">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 eatv-gradient rounded-2xl flex items-center justify-center flex-shrink-0 shadow-sm">
                    <i class="fa-solid fa-earth-africa text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">East Africa Tourist Visa <span class="text-teal-600">(EATV)</span></h2>
                    <p class="text-gray-500 mt-1">Uganda · Kenya · Rwanda — one visa, three countries</p>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4 mb-8">
                @foreach([['$100','Visa fee (USD)','fa-money-bill-wave'],['90 Days','Maximum stay','fa-calendar-days'],['3 Countries','Uganda · Kenya · Rwanda','fa-flag']] as [$v,$l,$i])
                <div class="eatv-gradient rounded-2xl p-5 text-center text-white shadow">
                    <i class="fa-solid {{ $i }} text-teal-200 text-lg mb-2"></i>
                    <div class="text-xl sm:text-3xl font-black">{{ $v }}</div>
                    <div class="text-xs text-teal-200 mt-1">{{ $l }}</div>
                </div>
                @endforeach
            </div>

            {{-- What is it --}}
            <div class="bg-white border border-teal-200 rounded-2xl p-6 shadow-sm mb-6">
                <h3 class="flex items-center gap-2 text-xl font-bold text-gray-900 mb-4">
                    <i class="fa-solid fa-circle-info text-teal-600"></i> What is the East Africa Tourist Visa?
                </h3>
                <p class="text-gray-700 leading-relaxed mb-0">
                    The <strong>East Africa Tourist Visa (EATV)</strong> is a joint visa allowing you to travel freely between
                    <strong>Uganda, Kenya, and Rwanda</strong> on a single visa for <strong>USD $100</strong>.
                    It's perfect for multi-country itineraries — combine a gorilla trek in Uganda with a Maasai Mara
                    safari in Kenya or a Kigali city visit in Rwanda. The EATV permits <strong>multiple entries</strong>
                    between the three partner countries throughout its 90-day validity.
                </p>
            </div>

            {{-- Benefits & Limitations --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900 mb-4">
                        <i class="fa-solid fa-star text-teal-500"></i> Key Benefits
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        @foreach([
                            'Single application — no separate visas for each country',
                            'Multiple entries between Uganda, Kenya & Rwanda within 90 days',
                            'Perfect for multi-country safari itineraries',
                            'Same cost or cheaper than buying 3 individual visas',
                            'Apply through any of the three countries\' portals',
                        ] as $item)
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check text-teal-500 mt-0.5 flex-shrink-0 text-xs"></i>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-amber-800 mb-4">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500"></i> Important Limitations
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        @foreach([
                            'NOT valid for Tanzania — separate $50 Tanzania visa required',
                            'NOT valid for Burundi, South Sudan, or other EAC states',
                            'Must first enter through Uganda, Kenya, or Rwanda',
                            'Cannot be extended beyond 90 days',
                        ] as $item)
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-xmark text-amber-600 mt-0.5 flex-shrink-0 text-xs"></i>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Full requirements table --}}
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm mb-6">
                <div class="bg-teal-700 px-6 py-4 flex items-center gap-3">
                    <i class="fa-solid fa-table-list text-teal-200"></i>
                    <h3 class="text-lg font-bold text-white">EATV — Full Requirements & Details</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach([
                        ['fa-money-bill-wave',  'Visa Fee',           'USD $100 — non-refundable. Payable by credit/debit card online.'],
                        ['fa-door-open',        'Entry Points',       'Must enter through Uganda, Kenya, or Rwanda. Cannot use for Tanzania, Burundi, or other EAC states.'],
                        ['fa-calendar-days',    'Maximum Stay',       '90 days total across all three countries combined. Not extendable.'],
                        ['fa-calendar-check',   'Validity Period',    '90 days from the date the visa is issued — not from your arrival date.'],
                        ['fa-passport',         'Passport Validity',  'Minimum 6 months beyond intended stay. At least 2 blank pages.'],
                        ['fa-syringe',          'Yellow Fever',       'MANDATORY for Uganda and Kenya entry. Must be the original certificate.'],
                        ['fa-plane-departure',  'Onward Ticket',      'Proof of travel out of the East Africa region at immigration.'],
                        ['fa-hotel',            'Accommodation',      'Hotel bookings, safari confirmation letter, or host invitation.'],
                        ['fa-money-bills',      'Financial Means',    'Bank statement showing sufficient funds (~$50 USD/day minimum).'],
                        ['fa-image-portrait',   'Photographs',        '2 recent passport-size photographs (white background).'],
                        ['fa-globe',            'Application Portals','visas.immigration.go.ug (UG)  |  evisa.go.ke (KE)  |  irembo.gov.rw (RW)'],
                        ['fa-clock',            'Processing Time',    '3–10 business days online. On-arrival EATV at Entebbe, Nairobi JKIA, and Kigali airports.'],
                    ] as [$ico,$lbl,$val])
                    <div class="grid grid-cols-1 sm:grid-cols-3 hover:bg-teal-50 transition-colors">
                        <div class="sm:col-span-1 flex items-start gap-3 px-5 py-4 bg-gray-50 sm:bg-transparent border-b sm:border-b-0 sm:border-r border-gray-100">
                            <i class="fa-solid {{ $ico }} text-teal-500 mt-0.5 w-4 flex-shrink-0"></i>
                            <span class="font-semibold text-gray-800 text-sm">{{ $lbl }}</span>
                        </div>
                        <div class="sm:col-span-2 px-5 py-4 text-sm text-gray-700">{{ $val }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Step by step --}}
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="bg-teal-700 px-6 py-4 flex items-center gap-3">
                    <i class="fa-solid fa-list-ol text-teal-200"></i>
                    <h3 class="text-lg font-bold text-white">How to Apply — Step by Step</h3>
                </div>
                <div class="p-6 space-y-5">
                    @php
                    $steps = [
                        ['Choose your portal', 'Visit <a href="https://visas.immigration.go.ug" target="_blank" class="text-teal-600 hover:underline">visas.immigration.go.ug</a>, <a href="https://evisa.go.ke" target="_blank" class="text-teal-600 hover:underline">evisa.go.ke</a>, or <a href="https://irembo.gov.rw" target="_blank" class="text-teal-600 hover:underline">irembo.gov.rw</a>. Choose the country you enter East Africa through first.'],
                        ['Create an account', 'Register with a valid email address. You\'ll use this to track your application.'],
                        ['Select "East Africa Tourist Visa"', 'Make sure to select EATV — not single-entry. If you\'re only visiting Uganda, single-entry ($50) is cheaper.'],
                        ['Complete the form', 'Fill in personal details, passport info, travel dates. Attach your passport bio page, photo, Yellow Fever certificate, and proof of accommodation.'],
                        ['Pay the $100 USD fee', 'Pay by Visa or Mastercard. Keep your payment receipt and reference number.'],
                        ['Wait for approval (3–10 days)', 'You\'ll receive an approval email when ready.'],
                        ['Print your e-visa', 'Print the approval letter and present it alongside your passport at immigration.'],
                    ];
                    @endphp
                    @foreach($steps as $i => [$title, $desc])
                    <div class="flex gap-4">
                        <div class="w-8 h-8 bg-teal-600 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 mt-0.5">{{ $i+1 }}</div>
                        <div>
                            <div class="font-semibold text-gray-900 text-sm">{{ $title }}</div>
                            <div class="text-gray-600 text-sm mt-0.5">{!! $desc !!}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ 03 · HEALTH ═══════════════════════════════════════════ --}}
    <section id="health" class="py-16 bg-white section-anchor">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-syringe text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Health & Vaccinations</h2>
                    <p class="text-gray-500 mt-1">Stay healthy on your safari</p>
                </div>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-2xl p-5 mb-6 flex gap-3">
                <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl flex-shrink-0 mt-0.5"></i>
                <p class="text-sm text-red-800"><strong>Mandatory:</strong> Yellow Fever certificate required for entry into Uganda. Vaccinate at least 10 days before departure and carry the original.</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm mb-6">
                <div class="bg-blue-700 px-5 py-3 flex items-center gap-2">
                    <i class="fa-solid fa-notes-medical text-blue-200"></i>
                    <span class="font-bold text-white text-base">Vaccinations Guide</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-blue-50 border-b border-blue-100">
                            <tr>
                                <th class="text-left p-3 font-semibold text-gray-700 w-1/3">Vaccine</th>
                                <th class="text-left p-3 font-semibold text-gray-700 w-1/5">Status</th>
                                <th class="text-left p-3 font-semibold text-gray-700">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach([
                                ['Yellow Fever',         'MANDATORY',           'bg-red-100 text-red-700',     'Required by law for entry. Original certificate essential.'],
                                ['Malaria prophylaxis',  'Critical',            'bg-orange-100 text-orange-700','Consult your doctor — Malarone, Doxycycline, or Lariam. Start before travel.'],
                                ['Hepatitis A & B',      'Strongly Recommended','bg-amber-100 text-amber-700', 'Food/waterborne disease risk.'],
                                ['Typhoid',              'Recommended',         'bg-yellow-100 text-yellow-700','Food/waterborne disease.'],
                                ['Rabies',               'Recommended',         'bg-blue-100 text-blue-700',   'For trekking and wildlife activities.'],
                                ['Meningitis',           'Recommended',         'bg-blue-100 text-blue-700',   'Especially October–April.'],
                                ['Tetanus & Diphtheria', 'Ensure up to date',   'bg-gray-100 text-gray-600',   'Standard booster if overdue.'],
                                ['COVID-19',             'Check requirements',  'bg-gray-100 text-gray-600',   'Verify current rules before travel.'],
                            ] as [$vac,$status,$badgeCls,$notes])
                            <tr class="hover:bg-blue-50 transition-colors">
                                <td class="p-3 font-medium text-gray-900">{{ $vac }}</td>
                                <td class="p-3"><span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeCls }}">{{ $status }}</span></td>
                                <td class="p-3 text-gray-600">{{ $notes }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900 mb-4">
                        <i class="fa-solid fa-mosquito text-orange-500"></i> Malaria Prevention
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        @foreach([
                            'Take anti-malarials — complete the full course including after returning',
                            'DEET insect repellent 50%+ on all exposed skin at dawn/dusk',
                            'Wear long sleeves and trousers during high-risk periods',
                            'Sleep under a mosquito net (provided at reputable lodges)',
                            'Carry a rapid malaria test kit for remote areas',
                            'Seek medical help if fever develops within 3 months of return',
                        ] as $tip)
                        <li class="flex items-start gap-2"><i class="fa-solid fa-circle-dot text-orange-400 text-xs mt-1.5 flex-shrink-0"></i><span>{{ $tip }}</span></li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900 mb-4">
                        <i class="fa-solid fa-kit-medical text-blue-500"></i> General Health Tips
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        @foreach([
                            'Drink bottled or purified water only — never tap water or ice',
                            'Apply SPF 50+ sunscreen daily — the equatorial sun is intense',
                            'Carry all prescription meds in hand luggage with a doctor\'s letter',
                            'Gorilla trekking: visitors with colds or flu are denied access (gorilla protection)',
                            'Altitude sickness possible on Rwenzori/Elgon treks — acclimatise gradually',
                        ] as $tip)
                        <li class="flex items-start gap-2"><i class="fa-solid fa-circle-dot text-blue-400 text-xs mt-1.5 flex-shrink-0"></i><span>{{ $tip }}</span></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ 04 · BEST TIME ════════════════════════════════════════ --}}
    <section id="best-time" class="py-16 bg-gray-50 section-anchor">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-calendar-days text-yellow-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Best Time to Visit</h2>
                    <p class="text-gray-500 mt-1">When to plan your safari</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-yellow-800 mb-3">
                        <i class="fa-solid fa-sun text-yellow-500"></i> Dry Seasons (Peak)
                    </h3>
                    @foreach([['June – September','Long dry season. Best game viewing. Ideal gorilla trekking — drier trails. Peak season, book early.'],['December – February','Short dry season. Excellent wildlife and birdwatching. Quieter with slightly lower prices.']] as [$p,$d])
                    <div class="bg-white rounded-xl p-4 mb-3 last:mb-0">
                        <div class="font-bold text-gray-900 text-sm mb-1"><i class="fa-regular fa-calendar mr-1 text-yellow-500"></i>{{ $p }}</div>
                        <p class="text-gray-600 text-sm">{{ $d }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-blue-800 mb-3">
                        <i class="fa-solid fa-cloud-rain text-blue-500"></i> Wet Seasons (Value)
                    </h3>
                    @foreach([['March – May','Long rains. Lush landscapes, excellent birdwatching. Lower prices and far fewer tourists.'],['October – November','Short rains. Brief afternoon showers. Wildlife still excellent. Good shoulder-season value.']] as [$p,$d])
                    <div class="bg-white rounded-xl p-4 mb-3 last:mb-0">
                        <div class="font-bold text-gray-900 text-sm mb-1"><i class="fa-regular fa-calendar mr-1 text-blue-500"></i>{{ $p }}</div>
                        <p class="text-gray-600 text-sm">{{ $d }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="bg-gray-800 px-5 py-3 flex items-center gap-2">
                    <i class="fa-solid fa-table text-gray-400"></i>
                    <span class="font-bold text-white text-sm">Month-by-Month Quick Guide</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left p-3 font-semibold text-gray-700">Month</th>
                                <th class="text-left p-3 font-semibold text-gray-700">Weather</th>
                                <th class="text-left p-3 font-semibold text-gray-700">Wildlife</th>
                                <th class="text-left p-3 font-semibold text-gray-700">Crowds</th>
                                <th class="text-left p-3 font-semibold text-gray-700">Rating</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach([
                                ['Jan','Dry','Excellent','Low','★★★★★'],['Feb','Dry','Excellent','Low','★★★★★'],
                                ['Mar','Rains begin','Good','Very Low','★★★'],['Apr','Wet','Good','Very Low','★★★'],
                                ['May','Wet','Good','Very Low','★★★'],['Jun','Dry','Excellent','High','★★★★★'],
                                ['Jul','Dry','Excellent','Peak','★★★★★'],['Aug','Dry','Excellent','Peak','★★★★★'],
                                ['Sep','Dry','Excellent','High','★★★★★'],['Oct','Light rains','Very Good','Moderate','★★★★'],
                                ['Nov','Light rains','Very Good','Low','★★★★'],['Dec','Dry','Excellent','Moderate','★★★★★'],
                            ] as [$m,$w,$wl,$cr,$r])
                            <tr class="hover:bg-yellow-50 transition-colors">
                                <td class="p-3 font-bold text-gray-800">{{ $m }}</td>
                                <td class="p-3 text-gray-600">{{ $w }}</td>
                                <td class="p-3 text-gray-600">{{ $wl }}</td>
                                <td class="p-3 text-gray-600">{{ $cr }}</td>
                                <td class="p-3 text-amber-500 text-xs">{{ $r }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ 05 · MONEY ════════════════════════════════════════════ --}}
    <section id="money" class="py-16 bg-white section-anchor">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-money-bill-wave text-green-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Money & Currency</h2>
                    <p class="text-gray-500 mt-1">Managing your finances in Uganda</p>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900 mb-4">
                        <i class="fa-solid fa-lightbulb text-yellow-500"></i> Money Tips
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        @foreach([
                            'Bring USD cash in small denominations ($1, $5, $10, $20)',
                            '<strong>Only USD notes printed after 2006 are accepted</strong> — older notes refused',
                            'ATMs in Kampala and major towns (Stanbic, Centenary, Dfcu are reliable)',
                            'ATMs rare in remote parks — withdraw before leaving the city',
                            'Visa and Mastercard accepted at most upmarket lodges',
                            'Notify your bank before travel to avoid cards being blocked',
                            'Use licensed forex bureaus — better rates than hotels',
                        ] as $tip)
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check text-green-500 mt-0.5 flex-shrink-0 text-xs"></i>
                            <span>{!! $tip !!}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900 mb-4">
                        <i class="fa-solid fa-hand-holding-dollar text-green-500"></i> Tipping Guide (USD)
                    </h3>
                    @foreach([
                        ['Safari Guide','$10–20 / day'],['Safari Driver','$5–10 / day'],
                        ['Gorilla Guide/Tracker','$10–20 / trek'],['Porter (trekking)','$5–10 / trek'],
                        ['Lodge/Hotel Staff','$1–5 / day'],['Restaurant','10% of bill'],['Airport Transfer','$3–5'],
                    ] as [$role,$amount])
                    <div class="flex justify-between items-center text-sm py-2 border-b border-gray-100 last:border-0">
                        <span class="text-gray-700 flex items-center gap-2"><i class="fa-solid fa-user text-gray-300 w-4"></i>{{ $role }}</span>
                        <span class="font-bold text-green-600">{{ $amount }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ 06 · PACKING ══════════════════════════════════════════ --}}
    <section id="packing" class="py-16 bg-gray-50 section-anchor">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-bag-shopping text-amber-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">What to Pack</h2>
                    <p class="text-gray-500 mt-1">Your complete safari packing checklist</p>
                </div>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 flex gap-3">
                <i class="fa-solid fa-palette text-amber-500 flex-shrink-0 mt-0.5"></i>
                <p class="text-amber-800 text-sm"><strong>Clothing colours:</strong> Neutral only — khaki, beige, olive, brown, grey. Avoid bright colours (tsetse flies) and white (dust).
                <strong>Camouflage is illegal in Uganda.</strong></p>
            </div>
            @php
            $cats = [
                ['fa-shirt',       'amber',   'Clothing', [
                    'Lightweight long-sleeved shirts × 3–4 (neutral colours)',
                    'Long trousers, neutral × 2–3 pairs','Comfortable shorts × 2','Fleece or light jacket',
                    'Waterproof rain jacket or poncho','Wide-brimmed sun hat',
                    'Warm layer for gorilla trekking altitude','Sturdy waterproof hiking boots (broken in)',
                    'Sandals for lodge evenings','Swimwear',
                ]],
                ['fa-kit-medical', 'blue',    'Health & Hygiene', [
                    'Yellow Fever certificate — MANDATORY original','Anti-malarial tablets (full course)',
                    'DEET insect repellent 50%+','Sunscreen SPF 50+ and SPF lip balm',
                    'Basic first aid kit','Oral rehydration sachets + Imodium',
                    'Prescription meds (excess supply + doctor\'s letter)','Hand sanitiser and wet wipes',
                ]],
                ['fa-camera',      'purple',  'Photography & Tech', [
                    'Camera with zoom lens (200mm+ recommended)','Extra memory cards and batteries',
                    'Portable power bank','Universal adaptor (Uganda uses Type G — UK plug)',
                    'Binoculars (8×42 or 10×42)','Waterproof bag for electronics',
                    'Headlamp with spare batteries','Smartphone with offline maps downloaded',
                ]],
                ['fa-file-lines',  'green',   'Documents & Money', [
                    'Passport (6+ months validity)','Visa / EATV approval letter (printed)',
                    'Travel insurance documents + emergency number','Flight tickets printed and digital',
                    'Hotel and tour booking confirmations','USD cash (small denominations, post-2006 notes)',
                    'Credit/debit cards (notify your bank)','Copies of all documents stored separately',
                ]],
                ['fa-binoculars',  'teal',    'Safari Essentials', [
                    'Dust-proof daypack for game drives','Reusable water bottle (2 litres minimum)',
                    'Snacks for long game drives','Field guidebook (birds and mammals)',
                    'Notebook and pen','Bandana or buff (dust protection)',
                ]],
                ['fa-tree',        'emerald', 'Gorilla Trek Specific', [
                    'Sturdy waterproof hiking boots — ankle support essential',
                    'Long trousers and long-sleeved shirt (nettles)','Waterproof jacket',
                    'Thick gardening gloves (grip vegetation)','Small backpack only — no large bags',
                    'Walking stick (hire at the gate)','Must be 15+ years old — UWA rule',
                    'No colds or flu — health check at briefing',
                ]],
            ];
            $cmap = [
                'amber'  =>['hdr'=>'bg-amber-600',  'bg'=>'bg-amber-50',  'border'=>'border-amber-200',  'dot'=>'text-amber-500'],
                'blue'   =>['hdr'=>'bg-blue-700',   'bg'=>'bg-blue-50',   'border'=>'border-blue-200',   'dot'=>'text-blue-500'],
                'purple' =>['hdr'=>'bg-purple-700', 'bg'=>'bg-purple-50', 'border'=>'border-purple-200', 'dot'=>'text-purple-500'],
                'green'  =>['hdr'=>'bg-green-700',  'bg'=>'bg-green-50',  'border'=>'border-green-200',  'dot'=>'text-green-500'],
                'teal'   =>['hdr'=>'bg-teal-700',   'bg'=>'bg-teal-50',   'border'=>'border-teal-200',   'dot'=>'text-teal-500'],
                'emerald'=>['hdr'=>'bg-emerald-700','bg'=>'bg-emerald-50','border'=>'border-emerald-200','dot'=>'text-emerald-500'],
            ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($cats as [$ico,$col,$title,$items])
                @php $c=$cmap[$col]; @endphp
                <div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-2xl overflow-hidden shadow-sm">
                    <div class="{{ $c['hdr'] }} px-4 py-3 flex items-center gap-2">
                        <i class="fa-solid {{ $ico }} text-white/80 text-sm"></i>
                        <span class="font-bold text-white text-sm">{{ $title }}</span>
                    </div>
                    <ul class="p-4 space-y-1.5">
                        @foreach($items as $item)
                        <li class="flex items-start gap-2 text-xs text-gray-700">
                            <i class="fa-solid fa-check {{ $c['dot'] }} mt-0.5 flex-shrink-0 text-xs"></i>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══ 07 · SAFETY ═══════════════════════════════════════════ --}}
    <section id="safety" class="py-16 bg-white section-anchor">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-shield-halved text-red-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Safety in Uganda</h2>
                    <p class="text-gray-500 mt-1">Travel with confidence</p>
                </div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-2xl p-5 mb-6 flex gap-3">
                <i class="fa-solid fa-circle-check text-green-500 text-xl flex-shrink-0 mt-0.5"></i>
                <p class="text-sm text-gray-700"><strong class="text-green-800">Uganda is a safe destination.</strong> Violent crime against tourists is rare. With sensible precautions, the vast majority of visitors have a completely trouble-free experience.</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                @foreach([
                    ['fa-city','text-blue-500','In Kampala & Towns',[
                        'Avoid walking alone at night — use taxis or ride-hailing apps (Bolt, SafeBoda)',
                        'Keep valuables out of sight in public','Use ATMs inside banks or shopping malls',
                        'Carry a passport photocopy; keep original in hotel safe',
                        'Be aware of pickpockets in crowded markets',
                        'Only use registered taxis or app-based transport',
                    ]],
                    ['fa-tree','text-green-500','In the Parks & Bush',[
                        'Always follow your ranger guide\'s instructions exactly',
                        'Maintain required distances from wildlife at all times',
                        'Never feed or approach animals','Stay in the vehicle unless guide says otherwise',
                        'Carry a charged phone with emergency numbers saved',
                        'Gorilla trekking: stay calm if charged — crouch and look away',
                    ]],
                ] as [$ico,$cls,$heading,$tips])
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900 mb-4">
                        <i class="fa-solid {{ $ico }} {{ $cls }}"></i> {{ $heading }}
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        @foreach($tips as $tip)
                        <li class="flex items-start gap-2"><i class="fa-solid fa-angles-right {{ $cls }} mt-1 flex-shrink-0 text-xs"></i><span>{{ $tip }}</span></li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl p-6  hidden shadow-sm">
                <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900 mb-5">
                    <i class="fa-solid fa-phone text-red-500"></i> Emergency Numbers
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
                    @foreach([
                        ['fa-siren-on','Police','999 / 112'],
                        ['fa-truck-medical','Ambulance','0800 199 000'],
                        ['fa-fire-extinguisher','Fire Brigade','0800 199 399'],
                     
                    
                        ['fa-headset','Calm Africa 24/7','+256 752 088 768'],
                    ] as [$ico,$lbl,$num])
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <i class="fa-solid {{ $ico }} text-red-500 text-xl mb-2"></i>
                        <div class="text-xs font-semibold text-gray-700">{{ $lbl }}</div>
                        <div class="font-bold text-green-700 text-sm mt-1">{{ $num }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ 08 · TRANSPORT ════════════════════════════════════════ --}}
    <section id="transport" class="py-16 bg-gray-50 section-anchor">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-plane text-indigo-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Getting There & Getting Around</h2>
                    <p class="text-gray-500 mt-1">Flights, transfers, and transport in Uganda</p>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900 mb-4">
                        <i class="fa-solid fa-plane-arrival text-indigo-500"></i> Flying to Uganda
                    </h3>
                    <p class="text-gray-600 text-sm mb-4">Main entry: <strong>Entebbe International Airport (EBB)</strong>, 37km south of Kampala.</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach(['Kenya Airways','Ethiopian Airlines','Emirates','Qatar Airways','KLM','British Airways','RwandAir','Turkish Airlines','Fly Dubai'] as $al)
                        <span class="bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded-full text-xs font-medium">{{ $al }}</span>
                        @endforeach
                    </div>
                    <ul class="space-y-1 text-sm text-gray-700">
                        @foreach(['Nairobi → 1h 15min','Addis Ababa → 2h 30min','Dubai → 5h 30min','Amsterdam / London → 8–9h'] as $route)
                        <li class="flex items-center gap-2"><i class="fa-solid fa-arrow-right text-indigo-400 text-xs"></i>{{ $route }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900 mb-4">
                        <i class="fa-solid fa-van-shuttle text-indigo-500"></i> Getting Around Uganda
                    </h3>
                    <div class="space-y-3">
                        @foreach([
                            ['fa-car-side','Safari 4WD','Your guide drives you — included in all Calm Africa tours'],
                            ['fa-plane','Domestic Flights','Charters to Kihihi (Bwindi), Kasese, Murchison available'],
                            ['fa-shuttle-van','Airport Transfers','All pick-ups and drop-offs arranged by us'],
                            ['fa-ship','Boat Transfers','For lodge access and lake crossings'],
                        ] as [$ico,$mode,$note])
                        <div class="flex gap-3 bg-indigo-50 rounded-lg p-3">
                            <i class="fa-solid {{ $ico }} text-indigo-500 mt-0.5 flex-shrink-0 w-4"></i>
                            <div>
                                <div class="font-semibold text-gray-900 text-sm">{{ $mode }}</div>
                                <div class="text-gray-500 text-xs mt-0.5">{{ $note }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ 09 · CULTURE ══════════════════════════════════════════ --}}
    <section id="culture" class="py-16 bg-white section-anchor">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-handshake text-purple-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Culture & Etiquette</h2>
                    <p class="text-gray-500 mt-1">Respect and engage with Uganda's people</p>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-green-50 border border-green-200 rounded-2xl p-6">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-green-800 mb-4">
                        <i class="fa-solid fa-thumbs-up text-green-500"></i> Do's
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        @foreach(['Greet people warmly — greetings are very important in Ugandan culture','Use both hands or right hand when giving/receiving','Dress modestly in rural areas and religious sites','Ask permission before photographing people','Remove shoes before entering a mosque or traditional home','Bargain respectfully at markets — expected','Learn a few Luganda words — always appreciated'] as $d)
                        <li class="flex items-start gap-2"><i class="fa-solid fa-check text-green-500 mt-0.5 flex-shrink-0 text-xs"></i><span>{{ $d }}</span></li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-red-800 mb-4">
                        <i class="fa-solid fa-thumbs-down text-red-400"></i> Don'ts
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        @foreach(['Do NOT photograph military, government buildings, or airports — illegal','Do NOT wear camouflage — illegal for civilians','Avoid public displays of affection beyond holding hands','Do not pass items with your left hand','Do not raise your voice or show impatience','Do not litter — strict laws apply','Do NOT bring plastic bags — banned in Uganda'] as $d)
                        <li class="flex items-start gap-2"><i class="fa-solid fa-xmark text-red-400 mt-0.5 flex-shrink-0 text-xs"></i><span>{{ $d }}</span></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="bg-purple-50 border border-purple-200 rounded-2xl p-6">
                <h3 class="flex items-center gap-2 text-lg font-bold text-purple-800 mb-4">
                    <i class="fa-solid fa-comments text-purple-500"></i> Useful Luganda Phrases
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 text-sm">
                    @foreach(['Hello'=>'Oli otya','Good morning'=>'Wasuze otya','Thank you'=>'Webale nyo','You\'re welcome'=>'Kale','Yes'=>'Yee','No'=>'Nedda','Please'=>'Mwattu','How much?'=>'Semanga?','Welcome'=>'Tukusanyukidde','Good'=>'Bulungi'] as $en=>$lg)
                    <div class="bg-white rounded-xl p-3 text-center shadow-sm">
                        <div class="text-xs text-gray-500">{{ $en }}</div>
                        <div class="font-bold text-purple-700 mt-0.5">{{ $lg }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ 10 · CLIMATE ══════════════════════════════════════════ --}}
    <section id="climate" class="py-16 bg-gray-50 section-anchor">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-sky-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-cloud-sun text-sky-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Climate & Weather</h2>
                    <p class="text-gray-500 mt-1">What to expect from Uganda's weather</p>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="bg-sky-700 px-5 py-3 flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-sky-200"></i>
                    <span class="font-bold text-white text-sm">Regional Climate Guide</span>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach([
                        ['Kampala & Central Uganda','24–29°C','Warm and humid. Two wet seasons (March–May, Oct–Nov). Pleasant year-round.'],
                        ['Murchison Falls (North)','25–35°C','Hotter and drier. Best game viewing in dry season (June–Sept).'],
                        ['Queen Elizabeth NP','20–27°C','Moderate. Rainfall spread evenly throughout the year.'],
                        ['Bwindi Forest (Gorillas)','7–20°C','Cool to cold at altitude (1,190–2,607m). Warm layer essential. Rain year-round.'],
                        ['Rwenzori Mountains','0–25°C','Extreme range. Snow above 4,000m. Prepare for all conditions.'],
                        ['Lake Victoria Region','20–27°C','Humid. Afternoon thunderstorms common April–May.'],
                    ] as [$r,$t,$n])
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-0 hover:bg-sky-50 transition-colors px-5 py-4">
                        <div class="sm:w-1/3 font-semibold text-gray-900 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-sky-400 text-xs flex-shrink-0"></i>{{ $r }}
                        </div>
                        <div class="sm:w-1/6 font-bold text-sky-600 text-sm">{{ $t }}</div>
                        <div class="sm:flex-1 text-gray-600 text-sm">{{ $n }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ 11 · FAQ ═══════════════════════════════════════════════ --}}
    <section id="faq" class="py-16 bg-white section-anchor">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-circle-question text-green-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Frequently Asked Questions</h2>
                    <p class="text-gray-500 mt-1">Quick answers for travellers</p>
                </div>
            </div>
            <div class="space-y-3">
                @foreach([
                    ['Is Uganda safe for solo travellers?','Yes. Uganda is one of East Africa\'s safer destinations. Solo travellers are warmly welcomed. Stay in reputable lodges and book guided activities — going alone into parks is not permitted.'],
                    ['Do I need to speak Swahili or Luganda?','No. English is Uganda\'s official language, widely spoken in hotels, lodges, parks, and cities.'],
                    ['Does the EATV cover Tanzania?','No. The EATV covers Uganda, Kenya, and Rwanda only. Tanzania requires a separate visa ($50, available on arrival or at immigration.go.tz).'],
                    ['How far in advance should I book gorilla permits?','Permits are extremely limited (only 8 visitors per gorilla family per day). Book 3–6 months ahead for peak season (June–Sept), 1–3 months off-season.'],
                    ['What is the minimum age for gorilla trekking?','The Uganda Wildlife Authority requires all participants to be at least 15 years old. No exceptions.'],
                    ['Can I use my mobile phone in Uganda?','Yes. MTN and Airtel have good coverage in cities and main roads. Coverage is patchy in remote parks. Buy a local SIM at the airport.'],
                    ['Is tap water safe to drink?','No. Drink only bottled or purified water. All reputable lodges provide safe drinking water.'],
                    ['What electrical plugs does Uganda use?','Type G — three square pins, same as the UK. 240V/50Hz. Bring a universal adaptor.'],
                    ['How much spending money beyond the tour cost?','Budget roughly $50–100 USD/day for tips, drinks, and souvenirs. Gorilla permits ($800/person) are usually included in your Calm Africa package.'],
                    ['What if I get sick during the safari?','Your guide is first-aid trained and our Kampala office provides 24/7 support. Comprehensive travel insurance with medical evacuation is mandatory for all clients.'],
                ] as $i => [$q,$a])
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                    <button onclick="toggleFaq({{ $i }})"
                            class="w-full text-left px-5 py-4 flex items-center justify-between gap-4 hover:bg-gray-50 transition-colors"
                            id="faq-btn-{{ $i }}">
                        <span class="font-semibold text-gray-900 text-sm">
                            <i class="fa-solid fa-circle-question text-green-400 mr-2"></i>{{ $q }}
                        </span>
                        <i id="faq-icon-{{ $i }}" class="fa-solid fa-chevron-down text-green-500 flex-shrink-0 text-sm transition-transform duration-300"></i>
                    </button>
                    <div id="faq-body-{{ $i }}" class="hidden px-5 pb-5 text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                        {{ $a }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══ CTA ════════════════════════════════════════════════════ --}}
    <section class="bg-gradient-to-r from-green-800 to-emerald-600 py-16">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready to Plan Your Safari?</h2>
            <p class="text-xl text-white/80 mb-8 max-w-2xl mx-auto">
                Download our complete guide and contact our team — available 7 days a week from Kampala.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center flex-wrap">
                <a href="{{ route('tourist-info.download-pdf') }}"
                   class="inline-flex items-center gap-2 bg-white text-green-800 font-bold px-7 py-4 rounded-xl hover:bg-gray-100 transition-all shadow-lg hover:scale-105">
                    <i class="fa-solid fa-file-pdf text-red-500 text-lg"></i> Download Full PDF Guide
                </a>
                <a href="{{ route('tours.index') }}"
                   class="inline-flex items-center gap-2 border-2 border-white text-white font-bold px-7 py-4 rounded-xl hover:bg-white hover:text-green-800 transition-all">
                    <i class="fa-solid fa-binoculars"></i> Browse Our Tours
                </a>
                <a href="https://wa.me/256752088768"
                   class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-400 text-white font-bold px-7 py-4 rounded-xl transition-all">
                    <i class="fa-brands fa-whatsapp text-lg"></i> WhatsApp Us
                </a>
            </div>
        </div>
    </section>

</div>

@push('scripts')
<script>
function toggleFaq(index) {
    const body = document.getElementById('faq-body-' + index);
    const icon = document.getElementById('faq-icon-' + index);
    const isOpen = !body.classList.contains('hidden');
    document.querySelectorAll('[id^="faq-body-"]').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('[id^="faq-icon-"]').forEach(el => el.style.transform = 'rotate(0deg)');
    if (!isOpen) {
        body.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    }
}
// Sticky bar
(function(){
    const bar  = document.getElementById('sticky-bar');
    const hero = document.querySelector('header');
    if (!bar || !hero) return;
    new IntersectionObserver(e => bar.classList.toggle('hidden', e[0].isIntersecting), { threshold: 0 }).observe(hero);
})();
</script>
@endpush
@endsection