@extends('layouts.app')

@section('title', 'Terms of Service | Calm Africa Safaris')
@section('meta_description', 'Read the Terms of Service for Calm Africa Safaris. Understand your rights and obligations when booking a safari with us.')

@section('page-header')
<header class="relative bg-gradient-to-r from-blue-700 to-indigo-600 py-16 md:py-24">
    <div class="absolute inset-0 bg-black opacity-30"></div>
    <div class="relative z-10 container mx-auto px-4 text-center text-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Terms of Service</h1>
        <p class="text-lg text-white/80 max-w-2xl mx-auto">Please read these terms carefully before booking a safari with us</p>
        <p class="text-sm text-blue-300 mt-4">Last updated: {{ date('F d, Y') }}</p>
        <nav class="text-sm mt-6">
            <ol class="flex justify-center items-center space-x-2 text-blue-200">
                <li><a href="{{ route('index') }}" class="hover:text-white transition-colors">Home</a></li>
                <li>/</li>
                <li class="text-white font-medium">Terms of Service</li>
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
                    '#acceptance'       => '1. Acceptance of Terms',
                    '#bookings'         => '2. Bookings & Reservations',
                    '#payments'         => '3. Payments',
                    '#cancellations'    => '4. Cancellations',
                    '#itinerary'        => '5. Itinerary Changes',
                    '#liability'        => '6. Limitation of Liability',
                    '#health-safety'    => '7. Health & Safety',
                    '#conduct'          => '8. Traveller Conduct',
                    '#insurance'        => '9. Travel Insurance',
                    '#governing-law'    => '10. Governing Law',
                    '#contact'          => '11. Contact Us',
                ] as $anchor => $label)
                    <a href="{{ $anchor }}" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors py-1">{{ $label }}</a>
                @endforeach
            </div>
        </div>

        {{-- Intro --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6">
            <p class="text-gray-700 leading-relaxed text-lg">
                Welcome to Calm Africa Safaris. These Terms of Service govern your use of our website and the purchase
                of safari tours, travel packages, and related services. By booking with us, you confirm that you have
                read, understood, and agree to be bound by these terms. If you are booking on behalf of others, you
                accept these terms on their behalf as well.
            </p>
        </div>

        @php
        $sections = [
            [
                'id'     => 'acceptance',
                'number' => '01',
                'title'  => 'Acceptance of Terms',
                'content'=> '
                    <p class="text-gray-700 mb-4">By accessing our website, making an enquiry, or confirming a booking, you agree to these Terms of Service in full. We reserve the right to update these terms at any time. Continued use of our services constitutes acceptance of any changes.</p>
                    <p class="text-gray-700">These terms apply to all travellers, including those who book on behalf of a group. The lead booker is responsible for ensuring all members of the party are aware of and agree to these terms.</p>
                ',
            ],
            [
                'id'     => 'bookings',
                'number' => '02',
                'title'  => 'Bookings & Reservations',
                'content'=> '
                    <p class="text-gray-700 mb-4">A booking is confirmed only once we have received your deposit and issued a written booking confirmation. Until that point, all prices and availability are subject to change.</p>
                    <ul class="space-y-2 text-gray-700 mb-4">
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>A non-refundable deposit of <strong>30% of the total tour cost</strong> is required to secure your booking.</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>The remaining balance is due <strong>60 days before</strong> your departure date.</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>For bookings made within 60 days of departure, the full amount is due at the time of booking.</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Prices are quoted in USD and are per person unless stated otherwise.</span></li>
                    </ul>
                    <p class="text-gray-700">You are responsible for ensuring that all traveller details (names, passport numbers, nationalities, dietary requirements) provided to us are accurate and match official travel documents.</p>
                ',
            ],
            [
                'id'     => 'payments',
                'number' => '03',
                'title'  => 'Payments',
                'content'=> '
                    <p class="text-gray-700 mb-4">We accept the following payment methods:</p>
                    <ul class="space-y-2 text-gray-700 mb-4">
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Bank transfer (USD, GBP, EUR)</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Credit/debit card via our secure payment gateway</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Mobile money (MTN, Airtel — Uganda-based clients)</span></li>
                    </ul>
                    <p class="text-gray-700 mb-4">All bank transfer fees are the responsibility of the client. Payments must be received in full — net of any transfer charges — for the booking to be considered settled.</p>
                    <p class="text-gray-700">We reserve the right to correct any pricing errors even after a booking has been confirmed. In such cases, you will be given the option to proceed at the corrected price or receive a full refund.</p>
                ',
            ],
            [
                'id'     => 'cancellations',
                'number' => '04',
                'title'  => 'Cancellations',
                'content'=> '
                    <p class="text-gray-700 mb-4">All cancellations must be submitted in writing to <a href="mailto:bookings@safaritours.com" class="text-blue-600 hover:underline">bookings@safaritours.com</a>. The following cancellation charges apply:</p>
                    <div class="overflow-x-auto mb-4">
                        <table class="w-full text-sm text-gray-700 border border-gray-200 rounded-xl overflow-hidden">
                            <thead class="bg-blue-50">
                                <tr>
                                    <th class="text-left p-4 font-semibold text-gray-800">Notice Period Before Departure</th>
                                    <th class="text-left p-4 font-semibold text-gray-800">Cancellation Fee</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50"><td class="p-4">More than 90 days</td><td class="p-4">Deposit only (30%)</td></tr>
                                <tr class="hover:bg-gray-50"><td class="p-4">60 – 90 days</td><td class="p-4">50% of total tour cost</td></tr>
                                <tr class="hover:bg-gray-50"><td class="p-4">30 – 59 days</td><td class="p-4">75% of total tour cost</td></tr>
                                <tr class="hover:bg-gray-50"><td class="p-4">Less than 30 days</td><td class="p-4">100% of total tour cost (no refund)</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="text-gray-700">Gorilla trekking permits are non-refundable and non-transferable once issued. We strongly recommend purchasing comprehensive travel insurance to cover cancellation costs.</p>
                ',
            ],
            [
                'id'     => 'itinerary',
                'number' => '05',
                'title'  => 'Itinerary Changes',
                'content'=> '
                    <p class="text-gray-700 mb-4">We reserve the right to modify itineraries due to circumstances beyond our control, including but not limited to:</p>
                    <ul class="space-y-2 text-gray-700 mb-4">
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Adverse weather conditions or natural disasters</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Park closures or government-imposed restrictions</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Road conditions, flight delays, or infrastructure disruptions</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Wildlife behaviour and ranger guidance</span></li>
                    </ul>
                    <p class="text-gray-700">We will always endeavour to provide an alternative of equal or greater value. Where this is not possible, we will offer a partial refund or credit note at our discretion.</p>
                ',
            ],
            [
                'id'     => 'liability',
                'number' => '06',
                'title'  => 'Limitation of Liability',
                'content'=> '
                    <p class="text-gray-700 mb-4">Calm Africa Safaris acts as an agent for hotels, lodges, airlines, and other service providers. While we take great care in selecting our partners, we cannot be held responsible for:</p>
                    <ul class="space-y-2 text-gray-700 mb-4">
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Acts or omissions of third-party suppliers</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Loss, injury, damage, or delay arising from circumstances beyond our control (force majeure)</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Wildlife encounters — safaris involve inherent risks in nature</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Loss or theft of personal belongings</span></li>
                    </ul>
                    <p class="text-gray-700">Our maximum liability shall not exceed the total amount paid by the client for the specific tour in question.</p>
                ',
            ],
            [
                'id'     => 'health-safety',
                'number' => '07',
                'title'  => 'Health & Safety',
                'content'=> '
                    <p class="text-gray-700 mb-4">You are responsible for ensuring you are medically fit to participate in the activities included in your tour. We strongly advise you to:</p>
                    <ul class="space-y-2 text-gray-700 mb-4">
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Consult your doctor or travel health clinic before departure.</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Obtain recommended vaccinations (including yellow fever, which is mandatory for Uganda entry).</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Disclose any medical conditions or mobility limitations at the time of booking.</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Carry any prescription medication in your hand luggage with a doctor\'s letter.</span></li>
                    </ul>
                    <p class="text-gray-700">Guides have the authority to remove any traveller from an activity if they deem it necessary for that person\'s safety or the safety of the group. No refund will be issued in such circumstances.</p>
                ',
            ],
            [
                'id'     => 'conduct',
                'number' => '08',
                'title'  => 'Traveller Conduct',
                'content'=> '
                    <p class="text-gray-700 mb-4">All travellers are expected to behave responsibly and respectfully toward wildlife, local communities, fellow travellers, and our staff. The following are strictly prohibited:</p>
                    <ul class="space-y-2 text-gray-700 mb-4">
                        <li class="flex items-start gap-2"><span class="text-red-400 font-bold mt-0.5">✗</span><span>Feeding, touching, or harassing wildlife</span></li>
                        <li class="flex items-start gap-2"><span class="text-red-400 font-bold mt-0.5">✗</span><span>Disrespecting local customs, traditions, or sacred sites</span></li>
                        <li class="flex items-start gap-2"><span class="text-red-400 font-bold mt-0.5">✗</span><span>Littering or causing environmental damage</span></li>
                        <li class="flex items-start gap-2"><span class="text-red-400 font-bold mt-0.5">✗</span><span>Behaviour that endangers the safety of others</span></li>
                        <li class="flex items-start gap-2"><span class="text-red-400 font-bold mt-0.5">✗</span><span>Purchasing or trafficking in illegal wildlife products</span></li>
                    </ul>
                    <p class="text-gray-700">We reserve the right to terminate the participation of any traveller who violates these standards, without refund.</p>
                ',
            ],
            [
                'id'     => 'insurance',
                'number' => '09',
                'title'  => 'Travel Insurance',
                'content'=> '
                    <p class="text-gray-700 mb-4">Comprehensive travel insurance is <strong>mandatory</strong> for all clients travelling with Calm Africa Safaris. Your policy must cover, at a minimum:</p>
                    <ul class="space-y-2 text-gray-700 mb-4">
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Medical expenses and emergency evacuation</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Trip cancellation and curtailment</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Loss of personal belongings and baggage</span></li>
                        <li class="flex items-start gap-2"><span class="text-blue-500 font-bold mt-0.5">✓</span><span>Personal liability</span></li>
                    </ul>
                    <p class="text-gray-700">Proof of travel insurance may be requested before or at the start of your tour. Calm Africa Safaris accepts no liability for any costs arising from inadequate insurance coverage.</p>
                ',
            ],
            [
                'id'     => 'governing-law',
                'number' => '10',
                'title'  => 'Governing Law',
                'content'=> '
                    <p class="text-gray-700">These Terms of Service shall be governed by and construed in accordance with the laws of the Republic of Uganda. Any disputes arising from these terms or your use of our services shall be subject to the exclusive jurisdiction of the courts of Uganda.</p>
                ',
            ],
            [
                'id'     => 'contact',
                'number' => '11',
                'title'  => 'Contact Us',
                'content'=> '
                    <p class="text-gray-700 mb-4">For any questions about these Terms of Service, please get in touch:</p>
                    <div class="bg-blue-50 rounded-xl p-6 space-y-3 text-gray-700">
                        <div class="flex items-center gap-3"><span class="text-blue-600 font-bold"> <i class="fas fa-map-marker-alt"></i> </span><span><strong>Calm Africa Safaris</strong> — Kansanga, Kampala, Uganda</span></div>
                        <div class="flex items-center gap-3"><span class="text-blue-600 font-bold"> <i class="fas fa-envelope"></i> </span><a href="mailto:info@calmafricasafaris.com" class="text-blue-600 hover:underline">info@calmafricasafaris.com</a></div>
                        <div class="flex items-center gap-3"><span class="text-blue-600 font-bold"> <i class="fas fa-phone"></i> </span><a href="tel:+256752088768" class="text-blue-600 hover:underline">+256 752 088 768</a></div>
                    </div>
                ',
            ],
        ];
        @endphp

        @foreach($sections as $section)
        <div id="{{ $section['id'] }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6 scroll-mt-24">
            <div class="flex items-start gap-4 mb-5">
                <span class="text-4xl font-black text-blue-100 leading-none select-none">{{ $section['number'] }}</span>
                <h2 class="text-2xl font-bold text-gray-900 pt-1">{{ $section['title'] }}</h2>
            </div>
            <div>{!! $section['content'] !!}</div>
        </div>
        @endforeach

    </div>
</div>
@endsection