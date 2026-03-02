@extends('layouts.app')

@section('title', 'Budget Safaris & Affordable Tours – Best Value African Packages')

@section('meta_description', 'Discover budget safaris and affordable tour packages in East Africa. Explore Uganda, Kenya and Tanzania without breaking the bank. Real wildlife, real guides, real value.')

@section('meta_keywords', 'budget safaris, budget tours, affordable safari packages, cheap African safaris, budget East Africa tours, Uganda budget safari, Kenya budget safari, Tanzania budget safari, low cost safari packages')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;900&family=DM+Sans:wght@300;400;500;600&display=swap');

    :root {
        --savanna: #c8823a;
        --savanna-light: #f5e6d0;
        --forest:  #2d5a27;
        --forest-light: #eaf3e8;
        --soil:    #7a4f2b;
        --sky:     #e8f4f8;
        --ink:     #1a1a1a;
        --mist:    #f7f4f0;
    }

    .budget-page { font-family: 'DM Sans', sans-serif; background: var(--mist); }

    /* ── HERO ── */
    .budget-hero {
        position: relative;
        background:
            linear-gradient(160deg, rgba(26,26,26,.72) 0%, rgba(45,90,39,.55) 100%),
            url('https://images.unsplash.com/photo-1516426122078-c23e76319801?w=1600&q=80') center/cover no-repeat;
        min-height: 420px;
        display: flex;
        align-items: flex-end;
        padding: 3rem 1.5rem 2.5rem;
    }
    .budget-hero__inner { max-width: 820px; }
    .budget-hero__eyebrow {
        display: inline-flex; align-items: center; gap: .5rem;
        font-size: .72rem; letter-spacing: .15em; text-transform: uppercase;
        color: var(--savanna); font-weight: 600;
        background: rgba(255,255,255,.12); backdrop-filter: blur(6px);
        padding: .35rem .9rem; border-radius: 999px;
        border: 1px solid rgba(200,130,58,.35); margin-bottom: 1rem;
    }
    .budget-hero__title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.2rem, 5vw, 3.6rem);
        font-weight: 900; color: #fff; line-height: 1.1;
        margin-bottom: 1rem;
    }
    .budget-hero__title span { color: var(--savanna); }
    .budget-hero__sub {
        font-size: 1.05rem; color: rgba(255,255,255,.85);
        max-width: 580px; line-height: 1.65; font-weight: 300;
    }
    .budget-hero__badge {
        margin-top: 1.6rem;
        display: inline-flex; align-items: center; gap: .6rem;
        background: var(--savanna); color: #fff;
        font-size: .82rem; font-weight: 600; padding: .6rem 1.2rem;
        border-radius: 999px; text-decoration: none;
        transition: background .2s;
    }
    .budget-hero__badge:hover { background: var(--soil); }

    /* ── TRUST STRIP ── */
    .trust-strip {
        background: var(--forest);
        color: #fff;
        display: flex; flex-wrap: wrap; justify-content: center; gap: 0;
    }
    .trust-strip__item {
        display: flex; align-items: center; gap: .6rem;
        padding: .9rem 2rem;
        font-size: .82rem; font-weight: 500;
        border-right: 1px solid rgba(255,255,255,.12);
    }
    .trust-strip__item:last-child { border-right: none; }
    .trust-strip__icon { font-size: 1.2rem; }

    /* ── WHAT IS BUDGET ── */
    .what-section {
        background: #fff;
        padding: 3.5rem 1.5rem;
    }
    .what-section__inner { max-width: 900px; margin: auto; }
    .section-label {
        font-size: .7rem; letter-spacing: .18em; text-transform: uppercase;
        color: var(--savanna); font-weight: 700; margin-bottom: .6rem;
    }
    .section-heading {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 700;
        color: var(--ink); margin-bottom: 1rem; line-height: 1.2;
    }
    .what-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;
    }
    @media(max-width:640px){ .what-grid { grid-template-columns: 1fr; } }
    .what-card {
        background: var(--mist); border-radius: 14px;
        padding: 1.5rem; border-left: 4px solid var(--savanna);
    }
    .what-card__icon { font-size: 1.8rem; margin-bottom: .6rem; }
    .what-card__title {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem; font-weight: 700; color: var(--ink); margin-bottom: .4rem;
    }
    .what-card__text { font-size: .9rem; color: #555; line-height: 1.6; }

    /* ── COMPARISON ── */
    .compare-section { padding: 3.5rem 1.5rem; background: var(--mist); }
    .compare-section__inner { max-width: 960px; margin: auto; }
    .compare-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-top: 2rem;
    }
    @media(max-width:700px){ .compare-grid { grid-template-columns: 1fr; } }
    .compare-card {
        background: #fff; border-radius: 16px; padding: 1.8rem;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        position: relative; overflow: hidden;
    }
    .compare-card--active { border: 2px solid var(--savanna); }
    .compare-card--active::before {
        content: 'YOU ARE HERE';
        position: absolute; top: 1rem; right: 1rem;
        font-size: .6rem; letter-spacing: .12em;
        background: var(--savanna); color: #fff;
        padding: .2rem .6rem; border-radius: 999px; font-weight: 700;
    }
    .compare-card__icon { font-size: 2rem; margin-bottom: .8rem; }
    .compare-card__title {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem; font-weight: 700; margin-bottom: .3rem;
    }
    .compare-card__price {
        font-size: .8rem; color: #888; margin-bottom: 1rem;
        font-weight: 500;
    }
    .compare-card__list { list-style: none; padding: 0; margin: 0; }
    .compare-card__list li {
        font-size: .85rem; color: #555; padding: .3rem 0;
        border-bottom: 1px solid #f0f0f0; display: flex; gap: .5rem; align-items: flex-start;
    }
    .compare-card__list li:last-child { border-bottom: none; }
    .compare-card__list li::before { content: '✓'; color: var(--forest); font-weight: 700; flex-shrink: 0; }

    /* ── TOURS SECTION ── */
    .tours-section { padding: 3.5rem 1.5rem; background: #fff; }
    .tours-section__inner { max-width: 1080px; margin: auto; }
    .tours-header {
        display: flex; align-items: flex-end; justify-content: space-between;
        flex-wrap: wrap; gap: 1rem; margin-bottom: 2.5rem;
    }
    .tours-count {
        font-size: .82rem; color: #999; font-weight: 500;
        background: var(--forest-light); color: var(--forest);
        padding: .35rem .9rem; border-radius: 999px; border: 1px solid rgba(45,90,39,.2);
    }

    /* Card grid */
    .tour-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.8rem;
    }

    /* Tour card */
    .tour-card {
        background: #fff; border-radius: 18px; overflow: hidden;
        box-shadow: 0 3px 16px rgba(0,0,0,.08);
        display: flex; flex-direction: column;
        transition: transform .25s, box-shadow .25s;
        border: 1px solid #f0ece6;
    }
    .tour-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 36px rgba(0,0,0,.12);
    }

    /* Image */
    .tour-card__img-wrap {
        position: relative; height: 200px; background: #e8e0d6; overflow: hidden;
    }
    .tour-card__img-wrap img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform .4s ease;
    }
    .tour-card:hover .tour-card__img-wrap img { transform: scale(1.05); }
    .tour-card__badge {
        position: absolute; top: .8rem; left: .8rem;
        background: var(--savanna); color: #fff;
        font-size: .65rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
        padding: .25rem .7rem; border-radius: 999px;
    }
    .tour-card__days {
        position: absolute; bottom: .8rem; right: .8rem;
        background: rgba(26,26,26,.75); color: #fff;
        font-size: .75rem; font-weight: 600; padding: .25rem .7rem; border-radius: 999px;
        backdrop-filter: blur(4px);
    }

    /* Body */
    .tour-card__body { padding: 1.25rem 1.4rem; flex: 1; display: flex; flex-direction: column; }
    .tour-card__title {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem; font-weight: 700; color: var(--ink);
        text-decoration: none; line-height: 1.25; margin-bottom: .4rem;
        display: block;
    }
    .tour-card__title:hover { color: var(--savanna); }
    .tour-card__meta {
        font-size: .75rem; color: #999; margin-bottom: .7rem;
        display: flex; align-items: center; gap: .3rem;
    }
    .tour-card__meta svg { opacity: .6; }
    .tour-card__desc {
        font-size: .85rem; color: #666; line-height: 1.55;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;
        overflow: hidden; flex: 1; margin-bottom: 1rem;
    }
    .tour-card__footer {
        display: flex; align-items: center; justify-content: space-between;
        padding-top: .9rem; border-top: 1px solid #f0ece6;
    }
    .tour-card__price {
        font-size: .82rem; font-weight: 600; color: var(--forest);
    }
    .tour-card__price strong { font-size: 1.1rem; color: var(--savanna); }
    .tour-card__price span { font-size: .72rem; color: #aaa; font-weight: 400; }
    .tour-card__cta {
        display: inline-flex; align-items: center; gap: .3rem;
        background: var(--forest); color: #fff;
        font-size: .78rem; font-weight: 600; padding: .45rem 1rem;
        border-radius: 999px; text-decoration: none;
        transition: background .2s;
    }
    .tour-card__cta:hover { background: var(--savanna); }

    /* Empty */
    .empty-state {
        text-align: center; padding: 5rem 2rem;
    }
    .empty-state__icon { font-size: 4rem; margin-bottom: 1rem; }
    .empty-state__title {
        font-family: 'Playfair Display', serif;
        font-size: 1.6rem; color: var(--ink); margin-bottom: .6rem;
    }
    .empty-state__text { color: #777; margin-bottom: 1.5rem; }
    .empty-state__cta {
        display: inline-block; background: var(--savanna); color: #fff;
        padding: .7rem 1.8rem; border-radius: 999px; font-weight: 600;
        text-decoration: none; font-size: .9rem; transition: background .2s;
    }
    .empty-state__cta:hover { background: var(--soil); }

    /* Pagination override */
    .pagination { margin-top: 2.5rem; display: flex; justify-content: center; gap: .4rem; }

    /* ── TIPS SECTION ── */
    .tips-section { background: var(--forest); padding: 3.5rem 1.5rem; }
    .tips-section__inner { max-width: 960px; margin: auto; }
    .tips-section .section-label { color: var(--savanna); }
    .tips-section .section-heading { color: #fff; }
    .tips-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.2rem; margin-top: 2rem;
    }
    .tip-card {
        background: rgba(255,255,255,.07); border-radius: 14px;
        padding: 1.3rem; border: 1px solid rgba(255,255,255,.12);
    }
    .tip-card__icon { font-size: 1.6rem; margin-bottom: .6rem; }
    .tip-card__title { color: #fff; font-weight: 600; font-size: .9rem; margin-bottom: .4rem; }
    .tip-card__text { color: rgba(255,255,255,.65); font-size: .82rem; line-height: 1.55; }

    /* ── CTA BANNER ── */
    .cta-banner {
        background: var(--savanna-light); padding: 3.5rem 1.5rem;
        text-align: center;
    }
    .cta-banner__title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.5rem, 3vw, 2.2rem); color: var(--ink); margin-bottom: .7rem;
    }
    .cta-banner__text { color: #666; max-width: 500px; margin: 0 auto 1.8rem; line-height: 1.6; }
    .cta-banner__btn {
        display: inline-block; background: var(--savanna); color: #fff;
        padding: .85rem 2.2rem; border-radius: 999px; font-weight: 700;
        font-size: .95rem; text-decoration: none; transition: background .2s;
    }
    .cta-banner__btn:hover { background: var(--soil); }
</style>
@endpush

@section('content')
<div class="budget-page">

    {{-- ── HERO ── --}}
    <section class="budget-hero">
        <div class="budget-hero__inner">
            <div class="budget-hero__eyebrow">
                🌍 East Africa
            </div>
            <h1 class="budget-hero__title">
                Real Safari.<br><span>Real Value.</span>
            </h1>
            <p class="budget-hero__sub">
                Our budget safaris put you in the same national parks, around the same campfires
                and face-to-face with the same wildlife as any luxury trip — at a price that
                makes sense for real travellers.
            </p>
            <a href="#tours" class="budget-hero__badge">
                ↓ Explore packages
            </a>
        </div>
    </section>

    {{-- ── TRUST STRIP ── --}}
    <div class="trust-strip">
        <div class="trust-strip__item">
            <span class="trust-strip__icon">🏕️</span> Hand-picked lodges & camps
        </div>
        <div class="trust-strip__item">
            <span class="trust-strip__icon">🚐</span> Professional, licensed guides
        </div>
        <div class="trust-strip__item">
            <span class="trust-strip__icon">🎟️</span> All park fees included
        </div>
        <div class="trust-strip__item">
            <span class="trust-strip__icon">💬</span> 24/7 in-destination support
        </div>
        <div class="trust-strip__item">
            <span class="trust-strip__icon">💵</span> No hidden costs
        </div>
    </div>

    {{-- ── WHAT IS A BUDGET SAFARI ── --}}
    <section class="what-section">
        <div class="what-section__inner">
            <p class="section-label">Budget Safaris with Calm Africa Safaris</p>
            <h2 class="section-heading">Affordable African Adventures Without Compromise</h2>
            <p style="color:#555;line-height:1.7;max-width:700px;">
                At Calm Africa Safaris, we believe that everyone deserves to experience the magic of Africa.
                 A safari should not be a luxury reserved for a few — it should be accessible, authentic, 
                 and unforgettable.
            </p>

            <p>


            Our carefully crafted budget safaris in Uganda and East Africa are designed for travelers who want real
             wildlife encounters, breathtaking landscapes, and cultural experiences at an affordable price — without
              sacrificing safety, comfort, or professionalism.
            </p>

            <p>
                We focus on value for money, smart itinerary planning, and authentic experiences 
                that give you more adventure for every dollar spent.
            </p>

        
        </div>
    </section>

    <section>

    <h2 class="text-" >
      Why Choose Calm Africa Safaris for Budget Travel?
    </h2>
    </section>


    {{-- ── TOUR CARDS ── --}}
    <section class="tours-section" id="tours">
        <div class="tours-section__inner">
            <div class="tours-header">
                <div>
                    <p class="section-label">Browse packages</p>
                    <h2 class="section-heading" style="margin-bottom:0">Budget Safari Packages</h2>
                </div>
                @if($tours->count())
                    <span class="tours-count">
                        {{ $tours->total() }} tour{{ $tours->total() != 1 ? 's' : '' }} available
                    </span>
                @endif
            </div>

            @if($tours->count())
                <div class="tour-grid">
                    @foreach($tours as $tour)
                        @php
                            // ── resolve image ──────────────────────────────────────────────
                            $img = null;
                            if (!empty($tour->featured_image)) {
                                $img = Str::startsWith($tour->featured_image, 'http')
                                    ? $tour->featured_image
                                    : asset('storage/' . $tour->featured_image);
                            } elseif ($tour->images->isNotEmpty()) {
                                $first = $tour->images->first();
                                $img = $first->url ?? ($first->path
                                    ? asset('storage/' . $first->path)
                                    : null);
                            }
                            $img ??= asset('images/placeholder-wide.jpg');

                            // ── resolve price ──────────────────────────────────────────────
                            $minPrice = $tour->prices->min('price');
                            $currency = optional($tour->prices->first())->currency ?? 'USD';

                            // ── resolve duration ──────────────────────────────────────────
                            $days = $tour->itineraries->count();

                            // ── resolve slug / route ──────────────────────────────────────
                            $tourUrl = $tour->slug
                                ? route('tours.show', $tour->slug)
                                : '#';
                        @endphp

                        <article class="tour-card">

                            <a href="{{ $tourUrl }}" class="tour-card__img-wrap" tabindex="-1">
                                <img src="{{ $img }}" alt="{{ $tour->title }}" loading="lazy">
                                <span class="tour-card__badge">Budget Safari</span>
                                @if($days)
                                    <span class="tour-card__days">{{ $days }} day{{ $days != 1 ? 's' : '' }}</span>
                                @endif
                            </a>

                            <div class="tour-card__body">
                                <a href="{{ $tourUrl }}" class="tour-card__title">
                                    {{ $tour->title }}
                                </a>

                                @if($tour->destinations)
                                    <p class="tour-card__meta">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        {{ $tour->destinations }}
                                    </p>
                                @endif

                                @if($tour->description)
                                    <p class="tour-card__desc">
                                        {{ Str::limit(strip_tags($tour->description), 140) }}
                                    </p>
                                @else
                                    <p class="tour-card__desc" style="color:#bbb;font-style:italic;">
                                        Itinerary details available on request.
                                    </p>
                                @endif

                                <div class="tour-card__footer">
                                    <div class="tour-card__price">
                                        @if($minPrice)
                                            From <strong>{{ $currency }} {{ number_format($minPrice) }}</strong>
                                            <span>/ person</span>
                                        @else
                                            <span style="color:#aaa">Price on request</span>
                                        @endif
                                    </div>
                                    <a href="{{ $tourUrl }}" class="tour-card__cta">
                                        View itinerary →
                                    </a>
                                </div>
                            </div>

                        </article>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $tours->withQueryString()->links() }}
                </div>

            @else
                <div class="empty-state">
                    <div class="empty-state__icon">🦁</div>
                    <h3 class="empty-state__title">More packages coming soon</h3>
                    <p class="empty-state__text">
                        We're putting the finishing touches on our budget safari listings.
                        In the meantime, get in touch and we'll build a custom itinerary just for you.
                    </p>
                    <a href="{{ route('contact') }}" class="empty-state__cta">
                        Request a custom safari
                    </a>
                </div>
            @endif

        </div>
    </section>

    {{-- ── MONEY-SAVING TIPS ── --}}
    <section class="tips-section">
        <div class="tips-section__inner">
            <p class="section-label">Travel smarter</p>
            <h2 class="section-heading">Tips for getting the most from a budget safari</h2>
            <div class="tips-grid">
                <div class="tip-card">
                    <div class="tip-card__icon"></div>
                    <div class="tip-card__title">Travel in shoulder season</div>
                    <p class="tip-card__text">April–May and November often have lower rates with excellent wildlife viewing.</p>
                </div>
                <div class="tip-card">
                    <div class="tip-card__icon">👥</div>
                    <div class="tip-card__title">Join a group departure</div>
                    <p class="tip-card__text">Sharing a vehicle cuts your costs dramatically without reducing the experience.</p>
                </div>
                <div class="tip-card">
                    <div class="tip-card__icon">🎒</div>
                    <div class="tip-card__title">Pack light & smart</div>
                    <p class="tip-card__text">Small aircraft in East Africa have strict baggage rules — soft bags save fees.</p>
                </div>
                <div class="tip-card">
                    <div class="tip-card__icon">🏦</div>
                    <div class="tip-card__title">Book early</div>
                    <p class="tip-card__text">Early bookings lock in lower prices and guarantee your preferred dates.</p>
                </div>
                <div class="tip-card">
                    <div class="tip-card__icon">🍽️</div>
                    <div class="tip-card__title">Eat local</div>
                    <p class="tip-card__text">Ugandan, Kenyan and Tanzanian cuisine is delicious and genuinely affordable.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── BOTTOM CTA ── --}}
    <section class="cta-banner">
        <h2 class="cta-banner__title">Can't find exactly what you need?</h2>
        <p class="cta-banner__text">
            Every itinerary we list can be adjusted — more days, different parks, private
            departure. Tell us your budget and we'll make it work.
        </p>
        <a href="{{ route('contact') }}" class="cta-banner__btn">
            Plan my custom safari →
        </a>
    </section>

</div>
@endsection