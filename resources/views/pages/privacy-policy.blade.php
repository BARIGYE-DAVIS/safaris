@extends('layouts.app')

@section('title', 'Privacy Policy | Calm Africa Safaris')
@section('meta_description', 'Read our Privacy Policy to understand how Calm Africa Safaris collects, uses, and protects your personal information.')

@section('page-header')
<header class="relative bg-gradient-to-r from-green-700 to-teal-600 py-16 md:py-24">
    <div class="absolute inset-0 bg-black opacity-30"></div>
    <div class="relative z-10 container mx-auto px-4 text-center text-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Privacy Policy</h1>
        <p class="text-lg text-white/80 max-w-2xl mx-auto">How we collect, use, and protect your personal information</p>
        <p class="text-sm text-green-300 mt-4">Last updated: {{ date('F d, Y') }}</p>
        <nav class="text-sm mt-6">
            <ol class="flex justify-center items-center space-x-2 text-green-200">
                <li><a href="{{ route('index') }}" class="hover:text-white transition-colors">Home</a></li>
                <li>/</li>
                <li class="text-white font-medium">Privacy Policy</li>
            </ol>
        </nav>
    </div>
</header>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Quick Nav --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Contents</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                @foreach([
                    '#information-we-collect'   => '1. Information We Collect',
                    '#how-we-use'               => '2. How We Use Your Information',
                    '#information-sharing'      => '3. Information Sharing',
                    '#data-security'            => '4. Data Security',
                    '#cookies'                  => '5. Cookies',
                    '#your-rights'              => '6. Your Rights',
                    '#third-party'              => '7. Third-Party Links',
                    '#children'                 => '8. Children\'s Privacy',
                    '#changes'                  => '9. Changes to This Policy',
                    '#contact'                  => '10. Contact Us',
                ] as $anchor => $label)
                    <a href="{{ $anchor }}" class="text-green-600 hover:text-green-800 hover:underline transition-colors py-1">{{ $label }}</a>
                @endforeach
            </div>
        </div>

        {{-- Intro --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6">
            <p class="text-gray-700 leading-relaxed text-lg">
                Calm Africa Safaris ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy
                explains how we collect, use, disclose, and safeguard your information when you visit our website or book
                a tour with us. Please read this policy carefully. By using our services, you agree to the practices
                described here.
            </p>
        </div>

        @php
        $sections = [
            [
                'id'      => 'information-we-collect',
                'number'  => '01',
                'title'   => 'Information We Collect',
                'content' => '
                    <p class="text-gray-700 mb-4">We collect information you provide directly to us, including:</p>
                    <ul class="space-y-2 text-gray-700 mb-4">
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span><strong>Personal identification:</strong> Name, email address, phone number, nationality, and date of birth.</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span><strong>Booking information:</strong> Travel dates, tour preferences, accommodation choices, dietary requirements, and emergency contacts.</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span><strong>Payment information:</strong> Credit/debit card details, billing address, and transaction history (processed securely via third-party payment processors).</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span><strong>Communications:</strong> Any messages, enquiries, or feedback you send us.</span></li>
                    </ul>
                    <p class="text-gray-700 hidden">We also automatically collect certain technical data when you visit our website, including IP address, browser type, pages viewed, time spent on pages, and referring URLs.</p>
                ',
            ],
            [
                'id'      => 'how-we-use',
                'number'  => '02',
                'title'   => 'How We Use Your Information',
                'content' => '
                    <p class="text-gray-700 mb-4">We use the information we collect to:</p>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span>Process and manage your safari bookings and payments.</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span>Communicate with you about your reservations, itineraries, and travel documents.</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span>Send you relevant travel updates, newsletters, and promotional offers (with your consent).</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span>Improve our website, services, and customer experience.</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span>Comply with legal obligations and resolve any disputes.</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span>Protect against fraudulent or unauthorised activity.</span></li>
                    </ul>
                ',
            ],
            [
                'id'      => 'information-sharing',
                'number'  => '03',
                'title'   => 'Information Sharing',
                'content' => '
                    <p class="text-gray-700 mb-4">We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:</p>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span><strong>Service providers:</strong> Lodges, airlines, ground handlers, and activity operators who need your information to fulfil your booking.</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span><strong>Payment processors:</strong> Secure third-party payment gateways to complete financial transactions.</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span><strong>Legal requirements:</strong> When required by law, court order, or government authority.</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span><strong>Business transfers:</strong> In the event of a merger, acquisition, or sale of our business assets.</span></li>
                    </ul>
                ',
            ],
            [
                'id'      => 'data-security',
                'number'  => '04',
                'title'   => 'Data Security',
                'content' => '
                    <p class="text-gray-700 mb-4">We implement industry-standard security measures to protect your personal information, including:</p>
                    <ul class="space-y-2 text-gray-700 mb-4">
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span>SSL/TLS encryption for all data transmitted to and from our website.</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span>Secure, access-controlled servers hosted in certified data centres.</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span>Regular security audits and staff training on data protection.</span></li>
                    </ul>
                    <p class="text-gray-700">While we take all reasonable precautions, no method of internet transmission is 100% secure. We cannot guarantee absolute security but will notify you promptly in the event of a data breach affecting your information.</p>
                ',
            ],
            [
                'id'      => 'cookies',
                'number'  => '05',
                'title'   => 'Cookies',
                'content' => '
                    <p class="text-gray-700 mb-4">Our website uses cookies — small text files stored on your device — to enhance your browsing experience. We use:</p>
                    <ul class="space-y-2 text-gray-700 mb-4">
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span><strong>Essential cookies:</strong> Required for the website to function correctly.</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span><strong>Analytics cookies:</strong> To understand how visitors interact with our website (e.g. Google Analytics).</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span><strong>Marketing cookies:</strong> To show you relevant ads on other platforms (only with your consent).</span></li>
                    </ul>
                    <p class="text-gray-700">You can control cookies through your browser settings. Disabling cookies may affect some features of our website. See our <a href="{{ route(\'cookie-policy\') }}" class="text-green-600 hover:underline">Cookie Policy</a> for full details.</p>
                ',
            ],
            [
                'id'      => 'your-rights',
                'number'  => '06',
                'title'   => 'Your Rights',
                'content' => '
                    <p class="text-gray-700 mb-4">Depending on your location, you may have the following rights regarding your personal data:</p>
                    <ul class="space-y-2 text-gray-700 mb-4">
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span><strong>Access:</strong> Request a copy of the personal data we hold about you.</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span><strong>Correction:</strong> Ask us to correct any inaccurate or incomplete information.</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span><strong>Deletion:</strong> Request that we delete your personal data, subject to legal obligations.</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span><strong>Objection:</strong> Object to the processing of your data for marketing purposes at any time.</span></li>
                        <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span><strong>Portability:</strong> Request your data in a structured, machine-readable format.</span></li>
                    </ul>
                    <p class="text-gray-700">To exercise any of these rights, please contact us at <a href="mailto:info@calmafricasafaris.com" class="text-green-600 hover:underline">info@calmafricasafaris.com</a>. We will respond within 30 days.</p>
                ',
            ],
            [
                'id'      => 'third-party',
                'number'  => '07',
                'title'   => 'Third-Party Links',
                'content' => '
                    <p class="text-gray-700">Our website may contain links to third-party websites (e.g. social media, partner lodges, payment portals). We are not responsible for the privacy practices of those websites and encourage you to review their privacy policies before providing any personal information.</p>
                ',
            ],
            [
                'id'      => 'children',
                'number'  => '08',
                'title'   => 'Children\'s Privacy',
                'content' => '
                    <p class="text-gray-700">Our services are not directed to children under the age of 18. We do not knowingly collect personal information from minors. If you believe we have inadvertently collected information from a child, please contact us immediately and we will promptly delete it.</p>
                ',
            ],
            [
                'id'      => 'changes',
                'number'  => '09',
                'title'   => 'Changes to This Policy',
                'content' => '
                    <p class="text-gray-700">We may update this Privacy Policy from time to time to reflect changes in our practices or applicable laws. We will notify you of significant changes by posting the updated policy on this page with a revised "Last Updated" date. Your continued use of our services after any changes constitutes acceptance of the updated policy.</p>
                ',
            ],
            [
                'id'      => 'contact',
                'number'  => '10',
                'title'   => 'Contact Us',
                'content' => '
                    <p class="text-gray-700 mb-4">If you have any questions, concerns, or requests regarding this Privacy Policy or your personal data, please contact us:</p>
                    <div class="bg-green-50 rounded-xl p-6 space-y-3 text-gray-700">
                        <div class="flex items-center gap-3"><span class="text-green-600 font-bold"><i class="fas fa-map-marker-alt"></i></span><span><strong>Calm Africa Safaris</strong> — Kansanga, Kampala, Uganda</span></div>
                        <div class="flex items-center gap-3"><span class="text-green-600 font-bold"><i class="fas fa-envelope"></i></span><a href="mailto:info@calmafricasafaris.com" class="text-green-600 hover:underline">info@calmafricasafaris.com</a></div>
                        <div class="flex items-center gap-3"><span class="text-green-600 font-bold"><i class="fas fa-phone"></i></span><a href="tel:+256752088768" class="text-green-600 hover:underline">+256 752 088 768</a></div>
                    </div>
                ',
            ],
        ];
        @endphp

        @foreach($sections as $section)
        <div id="{{ $section['id'] }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6 scroll-mt-24">
            <div class="flex items-start gap-4 mb-5">
                <span class="text-4xl font-black text-green-100 leading-none select-none">{{ $section['number'] }}</span>
                <h2 class="text-2xl font-bold text-gray-900 pt-1">{{ $section['title'] }}</h2>
            </div>
            <div>{!! $section['content'] !!}</div>
        </div>
        @endforeach

    </div>
</div>
@endsection