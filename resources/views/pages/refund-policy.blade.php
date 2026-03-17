@extends('layouts.app')

@section('title', 'Refund Policy | Calm Africa Safaris')
@section('meta_description', 'Understand the refund and cancellation policy for Calm Africa Safaris. Clear information on deposits, cancellation windows, and refund procedures.')

@section('page-header')
<header class="relative bg-gradient-to-r from-amber-600 to-orange-500 py-16 md:py-24">
    <div class="absolute inset-0 bg-black opacity-30"></div>
    <div class="relative z-10 container mx-auto px-4 text-center text-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Refund Policy</h1>
        <p class="text-lg text-white/80 max-w-2xl mx-auto">Transparent information about cancellations, refunds, and your options</p>
        <p class="text-sm text-amber-200 mt-4">Last updated: {{ date('F d, Y') }}</p>
        <nav class="text-sm mt-6">
            <ol class="flex justify-center items-center space-x-2 text-amber-200">
                <li><a href="{{ route('index') }}" class="hover:text-white transition-colors">Home</a></li>
                <li>/</li>
                <li class="text-white font-medium">Refund Policy</li>
            </ol>
        </nav>
    </div>
</header>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Intro --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6">
            <p class="text-gray-700 leading-relaxed text-lg">
                At Calm Africa Safaris, we understand that plans can change. This Refund Policy sets out clearly what
                happens when a booking is cancelled  whether by you or by us  and how any refunds are processed.
                We encourage all clients to read this policy carefully and to purchase comprehensive travel insurance
                to protect against unforeseen cancellations.
            </p>
        </div>

        {{-- Key Highlights --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-green-50 border border-green-100 rounded-2xl p-6 text-center">
                <div class="text-3xl mb-2"><i class="fas fa-percentage"></i></div>
                <div class="font-bold text-gray-900 mb-1">30% Deposit</div>
                <div class="text-sm text-gray-600">Required to confirm your booking</div>
            </div>
            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-6 text-center">
                <div class="text-3xl mb-2"><i class="fas fa-calendar-day"></i></div>
                <div class="font-bold text-gray-900 mb-1">60-Day Balance</div>
                <div class="text-sm text-gray-600">Full payment due 60 days before departure</div>
            </div>
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 text-center">
                <div class="text-3xl mb-2"><i class="fas fa-shield-alt"></i></div>
                <div class="font-bold text-gray-900 mb-1">Insurance Required</div>
                <div class="text-sm text-gray-600">Comprehensive travel cover is mandatory</div>
            </div>
        </div>

        {{-- Cancellation Schedule --}}
        <div id="cancellation-schedule" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6 scroll-mt-24">
            <div class="flex items-start gap-4 mb-6">
                <span class="text-4xl font-black text-amber-100 leading-none select-none">01</span>
                <h2 class="text-2xl font-bold text-gray-900 pt-1">Cancellation Schedule</h2>
            </div>
            <p class="text-gray-700 mb-6">All cancellation requests must be submitted in writing to <a href="mailto:bookings@safaritours.com" class="text-amber-600 hover:underline font-medium">bookings@safaritours.com</a>. The cancellation date is the date we receive your written request. The following charges apply:</p>

            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="w-full text-sm">
                    <thead class="bg-amber-50">
                        <tr>
                            <th class="text-left p-4 font-semibold text-gray-800 border-b border-gray-200">Days Before Departure</th>
                            <th class="text-left p-4 font-semibold text-gray-800 border-b border-gray-200">Cancellation Fee</th>
                            <th class="text-left p-4 font-semibold text-gray-800 border-b border-gray-200">Refund Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 font-medium text-gray-800">More than 90 days</td>
                            <td class="p-4 text-gray-700">Deposit only (30%)</td>
                            <td class="p-4"><span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">70% refunded</span></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 font-medium text-gray-800">60 – 90 days</td>
                            <td class="p-4 text-gray-700">50% of total cost</td>
                            <td class="p-4"><span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">50% refunded</span></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 font-medium text-gray-800">30 – 59 days</td>
                            <td class="p-4 text-gray-700">75% of total cost</td>
                            <td class="p-4"><span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-semibold">25% refunded</span></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 font-medium text-gray-800">Less than 30 days</td>
                            <td class="p-4 text-gray-700">100% of total cost</td>
                            <td class="p-4"><span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">No refund</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Non-refundable items --}}
        <div id="non-refundable" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6 scroll-mt-24">
            <div class="flex items-start gap-4 mb-5">
                <span class="text-4xl font-black text-amber-100 leading-none select-none">02</span>
                <h2 class="text-2xl font-bold text-gray-900 pt-1">Non-Refundable Items</h2>
            </div>
            <p class="text-gray-700 mb-4">Regardless of when a cancellation is made, the following items are strictly non-refundable and non-transferable once purchased on your behalf:</p>
            <ul class="space-y-3 text-gray-700">
                @foreach([
                    'Gorilla trekking permits (Uganda Wildlife Authority)',
                    'Chimpanzee tracking permits',
                    
                    'Non-refundable hotel or lodge deposits',
                    'Visa fees or government levies',
                    'Travel insurance premiums',
                ] as $item)
                <li class="flex items-start gap-3 bg-red-50 rounded-lg p-3">
                    <span class="text-red-400 font-bold text-lg leading-none mt-0.5">✗</span>
                    <span>{{ $item }}</span>
                </li>
                @endforeach
            </ul>
        </div>

        {{-- Date changes --}}
        <div id="date-changes" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6 scroll-mt-24">
            <div class="flex items-start gap-4 mb-5">
                <span class="text-4xl font-black text-amber-100 leading-none select-none">03</span>
                <h2 class="text-2xl font-bold text-gray-900 pt-1">Date Changes & Amendments</h2>
            </div>
            <p class="text-gray-700 mb-4">If you wish to change the dates of your booking rather than cancel, we will do our best to accommodate you subject to availability. Please note:</p>
            <ul class="space-y-2 text-gray-700">
                <li class="flex items-start gap-2"><span class="text-amber-500 font-bold mt-0.5">✓</span><span>Date change requests must be submitted in writing at least <strong>60 days before</strong> your original departure date.</span></li>
                <li class="flex items-start gap-2"><span class="text-amber-500 font-bold mt-0.5">✓</span><span>An amendment fee of <strong>USD 50 per person</strong> applies to all date changes.</span></li>
                <li class="flex items-start gap-2"><span class="text-amber-500 font-bold mt-0.5">✓</span><span>Any price difference between the original and new booking dates will be charged or refunded accordingly.</span></li>
                <li class="flex items-start gap-2"><span class="text-amber-500 font-bold mt-0.5">✓</span><span>Date changes are not possible within 30 days of departure — these are treated as cancellations.</span></li>
            </ul>
        </div>

        {{-- Cancellation by us --}}
        <div id="our-cancellation" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6 scroll-mt-24">
            <div class="flex items-start gap-4 mb-5">
                <span class="text-4xl font-black text-amber-100 leading-none select-none">04</span>
                <h2 class="text-2xl font-bold text-gray-900 pt-1">Cancellation by Calm Africa Safaris</h2>
            </div>
            <p class="text-gray-700 mb-4">In the unlikely event that we need to cancel your tour, we will notify you as soon as possible and offer you one of the following:</p>
            <ul class="space-y-2 text-gray-700 mb-4">
                <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span>An alternative tour of equivalent or greater value at no extra cost</span></li>
                <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span>A full credit note valid for 24 months</span></li>
                <li class="flex items-start gap-2"><span class="text-green-500 font-bold mt-0.5">✓</span><span>A full cash refund of all amounts paid to us</span></li>
            </ul>
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                <p class="text-amber-800 text-sm"><strong>Force Majeure:</strong> We are not liable to provide refunds if cancellations are caused by events beyond our reasonable control, including natural disasters, pandemic-related restrictions, war, civil unrest, or government travel bans. In such cases, we will issue a credit note valid for 24 months.</p>
            </div>
        </div>

        {{-- Refund process --}}
        <div id="refund-process" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6 scroll-mt-24">
            <div class="flex items-start gap-4 mb-5">
                <span class="text-4xl font-black text-amber-100 leading-none select-none">05</span>
                <h2 class="text-2xl font-bold text-gray-900 pt-1">How Refunds Are Processed</h2>
            </div>
            <ul class="space-y-3 text-gray-700">
                <li class="flex items-start gap-2"><span class="text-amber-500 font-bold mt-0.5">✓</span><span>Approved refunds are processed within <strong>14 business days</strong> of the cancellation being confirmed in writing.</span></li>
                <li class="flex items-start gap-2"><span class="text-amber-500 font-bold mt-0.5">✓</span><span>Refunds are issued via the same payment method used for the original transaction.</span></li>
                <li class="flex items-start gap-2"><span class="text-amber-500 font-bold mt-0.5">✓</span><span>International bank transfer fees are deducted from the refund amount.</span></li>
                <li class="flex items-start gap-2"><span class="text-amber-500 font-bold mt-0.5">✓</span><span>Refunds are issued in USD. Exchange rate fluctuations are not our responsibility.</span></li>
                <li class="flex items-start gap-2"><span class="text-amber-500 font-bold mt-0.5">✓</span><span>We cannot refund costs incurred by third parties (airlines, visa agencies, insurance providers).</span></li>
            </ul>
        </div>

        {{-- Travel Insurance reminder --}}
        <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-2xl p-8 mb-6 text-white">
            <h2 class="text-2xl font-bold mb-4">🛡️ Protect Your Investment — Get Travel Insurance</h2>
            <p class="text-white/90 mb-4">A good travel insurance policy can reimburse you for non-refundable tour costs if you need to cancel for a covered reason (illness, bereavement, redundancy, etc.). We strongly recommend purchasing insurance at the time of booking.</p>
            <p class="text-white/80 text-sm">Look for a policy that includes: trip cancellation & curtailment · medical & evacuation · baggage loss · personal liability.</p>
        </div>

        {{-- Contact --}}
        <div id="contact" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6 scroll-mt-24">
            <div class="flex items-start gap-4 mb-5">
                <span class="text-4xl font-black text-amber-100 leading-none select-none">06</span>
                <h2 class="text-2xl font-bold text-gray-900 pt-1">Questions About a Refund?</h2>
            </div>
            <p class="text-gray-700 mb-5">Our team is happy to talk you through your options. Please don't hesitate to get in touch:</p>
            <div class="bg-amber-50 rounded-xl p-6 space-y-3 text-gray-700">
                <div class="flex items-center gap-3"><span class="text-amber-600 font-bold"><i class="fas fa-map-marker-alt"></i></span><span><strong>Calm Africa Safaris</strong> <span class="text-amber-600">—</span> Kansanga, Kampala, Uganda</span></div>
                <div class="flex items-center gap-3"><span class="text-amber-600 font-bold"><i class="fas fa-envelope"></i></span><a href="mailto:bookings@calmafricasafaris.com" class="text-amber-600 hover:underline">bookings@calmafricasafaris.com</a></div>
                <div class="flex items-center gap-3"><span class="text-amber-600 font-bold"><i class="fas fa-phone"></i></span><a href="tel:+256777143020" class="text-amber-600 hover:underline">+256 777 143 020</a></div>
                <div class="flex items-center gap-3"><span class="text-amber-600 font-bold"><i class="fas fa-comments"></i></span><a href="https://wa.me/256752088768" class="text-amber-600 hover:underline">WhatsApp us anytime</a></div>
            </div>
        </div>

    </div>
</div>
@endsection