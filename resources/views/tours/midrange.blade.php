@extends('layouts.app')

@section('title', 'Mid-Range Safaris & Comfortable Tour Packages – Best Value African Experience')

@section('meta_description', 'Explore our mid-range safari packages across East Africa. Comfortable lodges, semi-private vehicles and professional guides — the perfect balance between value and comfort.')

@section('meta_keywords', 'mid-range safaris, comfortable safari packages, East Africa tours, Uganda safari, Kenya safari, Tanzania safari, affordable luxury safari, mid-range wildlife tours')

@push('styles')
{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;900&family=DM+Sans:wght@300;400;500;600&display=swap');

    :root {
        --amber:        #b07d2e;
        --amber-light:  #faf3e0;
        --amber-dark:   #7a5418;
        --teal:         #1e5f6b;
        --teal-light:   #e6f4f6;
        --ink:          #1a1a1a;
        --mist:         #f8f6f2;
        --card-border:  #ede8df;
    }

    .midrange-page { font-family: 'DM Sans', sans-serif; background: var(--mist); }

    /* ── HERO ── */
    .mr-hero {
        position: relative;
        background:
            linear-gradient(155deg, rgba(26,26,26,.68) 0%, rgba(30,95,107,.6) 100%),
            url('https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=1600&q=80') center/cover no-repeat;
        min-height: 440px;
        display: flex;
        align-items: flex-end;
        padding: 3rem 1.5rem 2.5rem;
    }
    .mr-hero__inner { max-width: 820px; }
    .mr-hero__eyebrow {
        display: inline-flex; align-items: center; gap: .5rem;
        font-size: .72rem; letter-spacing: .15em; text-transform: uppercase;
        color: var(--amber); font-weight: 600;
        background: rgba(255,255,255,.12); backdrop-filter: blur(6px);
        padding: .35rem .9rem; border-radius: 999px;
        border: 1px solid rgba(176,125,46,.4); margin-bottom: 1rem;
    }
    .mr-hero__title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.2rem, 5vw, 3.6rem);
        font-weight: 900; color: #fff; line-height: 1.1; margin-bottom: 1rem;
    }
    .mr-hero__title span { color: #f0c96e; }
    .mr-hero__sub {
        font-size: 1.05rem; color: rgba(255,255,255,.85);
        max-width: 580px; line-height: 1.65; font-weight: 300;
    }
    .mr-hero__badge {
        margin-top: 1.6rem;
        display: inline-flex; align-items: center; gap: .6rem;
        background: var(--amber); color: #fff;
        font-size: .82rem; font-weight: 600; padding: .6rem 1.2rem;
        border-radius: 999px; text-decoration: none; transition: background .2s;
    }
    .mr-hero__badge:hover { background: var(--amber-dark); }

    /* ── TRUST STRIP ── */
    .trust-strip {
        background: var(--teal); color: #fff;
        display: flex; flex-wrap: wrap; justify-content: center;
    }
    .trust-strip__item {
        display: flex; align-items: center; gap: .65rem;
        padding: .9rem 2rem; font-size: .82rem; font-weight: 500;
        border-right: 1px solid rgba(255,255,255,.12);
    }
    .trust-strip__item:last-child { border-right: none; }
    .trust-strip__item i { color: #f0c96e; font-size: 1rem; width: 18px; text-align: center; }

    /* ── SHARED HELPERS ── */
    .section-label {
        font-size: .7rem; letter-spacing: .18em; text-transform: uppercase;
        color: var(--amber); font-weight: 700; margin-bottom: .6rem;
    }
    .section-heading {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 700;
        color: var(--ink); margin-bottom: 1rem; line-height: 1.2;
    }

    /* ── WHAT IS MID-RANGE ── */
    .what-section { background: #fff; padding: 3.5rem 1.5rem; }
    .what-section__inner { max-width: 920px; margin: auto; }
    .what-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 1.6rem; margin-top: 2rem;
    }
    @media(max-width:640px){ .what-grid { grid-template-columns: 1fr; } }
    .what-card {
        background: var(--mist); border-radius: 14px;
        padding: 1.5rem; border-left: 4px solid var(--amber);
        display: flex; gap: 1rem; align-items: flex-start;
    }
    .what-card__icon {
        width: 42px; height: 42px; border-radius: 10px;
        background: var(--amber-light); display: flex; align-items: center;
        justify-content: center; flex-shrink: 0;
    }
    .what-card__icon i { color: var(--amber); font-size: 1.1rem; }
    .what-card__title {
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem; font-weight: 700; color: var(--ink); margin-bottom: .35rem;
    }
    .what-card__text { font-size: .88rem; color: #555; line-height: 1.6; margin: 0; }

    /* ── COMPARISON ── */
    .compare-section { padding: 3.5rem 1.5rem; background: var(--mist); }
    .compare-section__inner { max-width: 980px; margin: auto; }
    .compare-grid {
        display: grid; grid-template-columns: repeat(3,1fr); gap: 1.5rem; margin-top: 2rem;
    }
    @media(max-width:700px){ .compare-grid { grid-template-columns: 1fr; } }
    .compare-card {
        background: #fff; border-radius: 16px; padding: 1.8rem;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        position: relative; overflow: hidden;
        border: 1.5px solid transparent;
    }
    .compare-card--active {
        border-color: var(--amber);
        box-shadow: 0 4px 24px rgba(176,125,46,.15);
    }
    .compare-card--active::before {
        content: 'YOU ARE HERE';
        position: absolute; top: 1rem; right: 1rem;
        font-size: .6rem; letter-spacing: .12em;
        background: var(--amber); color: #fff;
        padding: .2rem .65rem; border-radius: 999px; font-weight: 700;
    }
    .compare-card__icon-wrap {
        width: 46px; height: 46px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 1rem;
    }
    .compare-card__icon-wrap--budget  { background: #eaf3e8; }
    .compare-card__icon-wrap--mid     { background: var(--amber-light); }
    .compare-card__icon-wrap--luxury  { background: #f0ecf8; }
    .compare-card__icon-wrap i { font-size: 1.2rem; }
    .compare-card__icon-wrap--budget  i { color: #2d5a27; }
    .compare-card__icon-wrap--mid     i { color: var(--amber); }
    .compare-card__icon-wrap--luxury  i { color: #6b3fa0; }
    .compare-card__title {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem; font-weight: 700; margin-bottom: .25rem; color: var(--ink);
    }
    .compare-card__price { font-size: .8rem; color: #999; font-weight: 500; margin-bottom: 1rem; }
    .compare-card__list { list-style: none; padding: 0; margin: 0; }
    .compare-card__list li {
        font-size: .84rem; color: #555; padding: .32rem 0;
        border-bottom: 1px solid #f4f0eb;
        display: flex; gap: .55rem; align-items: flex-start;
    }
    .compare-card__list li:last-child { border-bottom: none; }
    .compare-card__list li i { color: var(--teal); font-size: .75rem; margin-top: .22rem; flex-shrink: 0; }

    /* ── TOURS SECTION ── */
    .tours-section { padding: 3.5rem 1.5rem; background: #fff; }
    .tours-section__inner { max-width: 1080px; margin: auto; }
    .tours-header {
        display: flex; align-items: flex-end; justify-content: space-between;
        flex-wrap: wrap; gap: 1rem; margin-bottom: 2.5rem;
    }
    .tours-count {
        font-size: .82rem; font-weight: 500;
        background: var(--teal-light); color: var(--teal);
        padding: .35rem .9rem; border-radius: 999px;
        border: 1px solid rgba(30,95,107,.2);
    }
    .tour-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.8rem;
    }

    /* Tour card */
    .tour-card {
        background: #fff; border-radius: 18px; overflow: hidden;
        box-shadow: 0 3px 16px rgba(0,0,0,.07);
        display: flex; flex-direction: column;
        transition: transform .25s, box-shadow .25s;
        border: 1px solid var(--card-border);
    }
    .tour-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 36px rgba(0,0,0,.11);
    }
    .tour-card__img-wrap {
        position: relative; height: 205px; background: #e8e0d0; overflow: hidden;
    }
    .tour-card__img-wrap img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform .4s ease;
    }
    .tour-card:hover .tour-card__img-wrap img { transform: scale(1.05); }
    .tour-card__badge {
        position: absolute; top: .8rem; left: .8rem;
        background: var(--amber); color: #fff;
        font-size: .65rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
        padding: .25rem .7rem; border-radius: 999px;
        display: flex; align-items: center; gap: .35rem;
    }
    .tour-card__badge i { font-size: .65rem; }
    .tour-card__days {
        position: absolute; bottom: .8rem; right: .8rem;
        background: rgba(26,26,26,.72); color: #fff;
        font-size: .75rem; font-weight: 600; padding: .25rem .7rem;
        border-radius: 999px; backdrop-filter: blur(4px);
        display: flex; align-items: center; gap: .35rem;
    }
    .tour-card__days i { font-size: .7rem; opacity: .85; }

    .tour-card__body { padding: 1.25rem 1.4rem; flex: 1; display: flex; flex-direction: column; }
    .tour-card__title {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem; font-weight: 700; color: var(--ink);
        text-decoration: none; line-height: 1.25; margin-bottom: .4rem; display: block;
    }
    .tour-card__title:hover { color: var(--amber); }
    .tour-card__meta {
        font-size: .75rem; color: #999; margin-bottom: .7rem;
        display: flex; align-items: center; gap: .4rem;
    }
    .tour-card__meta i { color: var(--amber); font-size: .75rem; }
    .tour-card__desc {
        font-size: .85rem; color: #666; line-height: 1.55;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;
        overflow: hidden; flex: 1; margin-bottom: 1rem;
    }
    .tour-card__footer {
        display: flex; align-items: center; justify-content: space-between;
        padding-top: .9rem; border-top: 1px solid var(--card-border);
    }
    .tour-card__price { font-size: .82rem; font-weight: 600; color: var(--teal); }
    .tour-card__price strong { font-size: 1.1rem; color: var(--amber); }
    .tour-card__price span { font-size: .72rem; color: #aaa; font-weight: 400; }
    .tour-card__cta {
        display: inline-flex; align-items: center; gap: .35rem;
        background: var(--teal); color: #fff;
        font-size: .78rem; font-weight: 600; padding: .45rem 1rem;
        border-radius: 999px; text-decoration: none; transition: background .2s;
    }
    .tour-card__cta:hover { background: var(--amber); }
    .tour-card__cta i { font-size: .7rem; }

    /* ── HIGHLIGHTS ── */
    .highlights-section { background: var(--mist); padding: 3.5rem 1.5rem; }
    .highlights-section__inner { max-width: 960px; margin: auto; }
    .highlights-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1.2rem; margin-top: 2rem;
    }
    .highlight-card {
        background: #fff; border-radius: 14px; padding: 1.4rem 1.2rem;
        text-align: center; border: 1px solid var(--card-border);
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
        transition: box-shadow .2s, transform .2s;
    }
    .highlight-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.09); transform: translateY(-3px); }
    .highlight-card__icon {
        width: 52px; height: 52px; border-radius: 14px;
        background: var(--amber-light); display: flex; align-items: center;
        justify-content: center; margin: 0 auto .9rem;
    }
    .highlight-card__icon i { color: var(--amber); font-size: 1.3rem; }
    .highlight-card__title { font-weight: 700; font-size: .9rem; color: var(--ink); margin-bottom: .3rem; }
    .highlight-card__text { font-size: .8rem; color: #777; line-height: 1.5; }

    /* ── TIPS ── */
    .tips-section { background: var(--teal); padding: 3.5rem 1.5rem; }
    .tips-section__inner { max-width: 960px; margin: auto; }
    .tips-section .section-label { color: #f0c96e; }
    .tips-section .section-heading { color: #fff; }
    .tips-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.2rem; margin-top: 2rem;
    }
    .tip-card {
        background: rgba(255,255,255,.08); border-radius: 14px; padding: 1.3rem;
        border: 1px solid rgba(255,255,255,.12);
    }
    .tip-card__icon {
        width: 38px; height: 38px; border-radius: 10px;
        background: rgba(240,201,110,.15); display: flex; align-items: center;
        justify-content: center; margin-bottom: .8rem;
    }
    .tip-card__icon i { color: #f0c96e; font-size: 1rem; }
    .tip-card__title { color: #fff; font-weight: 600; font-size: .88rem; margin-bottom: .35rem; }
    .tip-card__text { color: rgba(255,255,255,.65); font-size: .82rem; line-height: 1.55; }

    /* ── EMPTY STATE ── */
    .empty-state { text-align: center; padding: 5rem 2rem; }
    .empty-state__icon-wrap {
        width: 80px; height: 80px; border-radius: 50%;
        background: var(--amber-light); display: flex; align-items: center;
        justify-content: center; margin: 0 auto 1.2rem;
    }
    .empty-state__icon-wrap i { color: var(--amber); font-size: 2rem; }
    .empty-state__title {
        font-family: 'Playfair Display', serif;
        font-size: 1.6rem; color: var(--ink); margin-bottom: .6rem;
    }
    .empty-state__text { color: #777; margin-bottom: 1.5rem; max-width: 420px; margin-inline: auto; }
    .empty-state__cta {
        display: inline-flex; align-items: center; gap: .5rem;
        background: var(--amber); color: #fff;
        padding: .75rem 1.8rem; border-radius: 999px; font-weight: 600;
        font-size: .9rem; text-decoration: none; transition: background .2s;
    }
    .empty-state__cta:hover { background: var(--amber-dark); }

    /* ── CTA BANNER ── */
    .cta-banner { background: var(--amber-light); padding: 3.5rem 1.5rem; text-align: center; }
    .cta-banner__title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.5rem, 3vw, 2.2rem); color: var(--ink); margin-bottom: .7rem;
    }
    .cta-banner__text { color: #666; max-width: 500px; margin: 0 auto 1.8rem; line-height: 1.65; }
    .cta-banner__btn {
        display: inline-flex; align-items: center; gap: .5rem;
        background: var(--amber); color: #fff;
        padding: .85rem 2.2rem; border-radius: 999px; font-weight: 700;
        font-size: .95rem; text-decoration: none; transition: background .2s;
    }
    .cta-banner__btn:hover { background: var(--amber-dark); }
    .cta-banner__btn i { font-size: .85rem; }
</style>
@endpush

@section('content')
<div class="midrange-page">

    {{-- ── HERO ── --}}
    <section class="mr-hero">
        <div class="mr-hero__inner">
            <div class="mr-hero__eyebrow">
                <i class="fa-solid fa-star-half-stroke"></i> East Africa · Mid-Range Collection
            </div>
            <h1 class="mr-hero__title">
                Comfort Meets<br><span>the Wild.</span>
            </h1>
            <p class="mr-hero__sub">
                Our mid-range safaris strike the sweet spot — comfortable en-suite lodges, good food,
                and professional guides — without the luxury price tag. More comfort, same adventure.
            </p>
            <a href="#tours" class="mr-hero__badge">
                <i class="fa-solid fa-arrow-down"></i> Explore packages
            </a>
        </div>
    </section>

    {{-- ── TRUST STRIP ── --}}
    <div class="trust-strip">
        <div class="trust-strip__item">
            <i class="fa-solid fa-hotel"></i> Comfortable en-suite lodges
        </div>
        <div class="trust-strip__item">
            <i class="fa-solid fa-van-shuttle"></i> Semi-private 4×4 vehicles
        </div>
        <div class="trust-strip__item">
            <i class="fa-solid fa-utensils"></i> Most meals included
        </div>
        <div class="trust-strip__item">
            <i class="fa-solid fa-user-tie"></i> Expert naturalist guides
        </div>
        <div class="trust-strip__item">
            <i class="fa-solid fa-shield-halved"></i> No hidden costs
        </div>
    </div>

    {{-- ── WHAT IS MID-RANGE ── --}}
    <section class="what-section">
        <div class="what-section__inner">
            <p class="section-label">What to expect</p>
            <h2 class="section-heading">The comfort upgrade that changes everything.</h2>
            <p style="color:#555;line-height:1.7;max-width:700px;">
                Mid-range safaris sit in the sweet spot of African travel. You get genuinely comfortable
                lodges with en-suite bathrooms, well-located camps with proper amenities, and often a
                smaller group size — all at a price that still makes sense. The wildlife is identical;
                the experience is simply more relaxed.
            </p>
            <div class="what-grid">
                <div class="what-card">
                    <div class="what-card__icon">
                        <i class="fa-solid fa-bed"></i>
                    </div>
                    <div>
                        <div class="what-card__title">En-suite lodges & tented camps</div>
                        <p class="what-card__text">
                            Sleep in well-appointed rooms or spacious canvas tents with private
                            bathrooms, hot showers and comfortable beds — positioned inside or
                            right beside the parks.
                        </p>
                    </div>
                </div>
                <div class="what-card">
                    <div class="what-card__icon">
                        <i class="fa-solid fa-binoculars"></i>
                    </div>
                    <div>
                        <div class="what-card__title">Smaller groups, better sightings</div>
                        <p class="what-card__text">
                            Mid-range departures typically run with 4–8 guests, meaning less time
                            waiting at sightings and more flexibility in the field with your guide.
                        </p>
                    </div>
                </div>
                <div class="what-card">
                    <div class="what-card__icon">
                        <i class="fa-solid fa-fork-knife"></i>
                    </div>
                    <div>
                        <div class="what-card__title">Most meals included</div>
                        <p class="what-card__text">
                            Breakfast is always included; most packages cover lunch and dinner too —
                            so your budget stays predictable from day one.
                        </p>
                    </div>
                </div>
                <div class="what-card">
                    <div class="what-card__icon">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <div>
                        <div class="what-card__title">Flexible & customisable</div>
                        <p class="what-card__text">
                            Every itinerary can be extended, re-routed or upgraded. Want a private
                            vehicle or an extra night? We price it clearly and honestly.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── COMPARISON ── --}}
    <section class="compare-section">
        <div class="compare-section__inner">
            <p class="section-label">How do we compare?</p>
            <h2 class="section-heading">Budget vs Mid-Range vs Luxury</h2>
            <div class="compare-grid">

                <div class="compare-card">
                    <div class="compare-card__icon-wrap compare-card__icon-wrap--budget">
                        <i class="fa-solid fa-tent" style="color:#2d5a27"></i>
                    </div>
                    <div class="compare-card__title">Budget</div>
                    <div class="compare-card__price">Best-value entry point</div>
                    <ul class="compare-card__list">
                        <li><i class="fa-solid fa-check"></i> Simple guesthouses &amp; budget camps</li>
                        <li><i class="fa-solid fa-check"></i> Shared group vehicles</li>
                        <li><i class="fa-solid fa-check"></i> Professional certified guide</li>
                        <li><i class="fa-solid fa-check"></i> All major park fees included</li>
                        <li><i class="fa-solid fa-check"></i> Ideal for students &amp; backpackers</li>
                    </ul>
                </div>

                <div class="compare-card compare-card--active">
                    <div class="compare-card__icon-wrap compare-card__icon-wrap--mid">
                        <i class="fa-solid fa-star-half-stroke" style="color:var(--amber)"></i>
                    </div>
                    <div class="compare-card__title">Mid-Range</div>
                    <div class="compare-card__price">Comfort &amp; value balanced</div>
                    <ul class="compare-card__list">
                        <li><i class="fa-solid fa-check"></i> En-suite lodges &amp; tented camps</li>
                        <li><i class="fa-solid fa-check"></i> Semi-private or private vehicles</li>
                        <li><i class="fa-solid fa-check"></i> Good locations with amenities &amp; pool</li>
                        <li><i class="fa-solid fa-check"></i> Most meals included</li>
                        <li><i class="fa-solid fa-check"></i> Smaller group sizes (4–8 guests)</li>
                        <li><i class="fa-solid fa-check"></i> Ideal for couples &amp; families</li>
                    </ul>
                </div>

                <div class="compare-card">
                    <div class="compare-card__icon-wrap compare-card__icon-wrap--luxury">
                        <i class="fa-solid fa-gem" style="color:#6b3fa0"></i>
                    </div>
                    <div class="compare-card__title">Luxury</div>
                    <div class="compare-card__price">Exclusive &amp; personalised</div>
                    <ul class="compare-card__list">
                        <li><i class="fa-solid fa-check"></i> Exclusive lodges &amp; premium camps</li>
                        <li><i class="fa-solid fa-check"></i> Fully private vehicles &amp; guides</li>
                        <li><i class="fa-solid fa-check"></i> Gourmet dining, all-inclusive</li>
                        <li><i class="fa-solid fa-check"></i> High staff-to-guest ratio</li>
                        <li><i class="fa-solid fa-check"></i> Bespoke tailored experiences</li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    {{-- ── TOUR CARDS ── --}}
    <section class="tours-section" id="tours">
        <div class="tours-section__inner">
            <div class="tours-header">
                <div>
                    <p class="section-label">Browse packages</p>
                    <h2 class="section-heading" style="margin-bottom:0">Mid-Range Safari Packages</h2>
                </div>
                @if($tours->count())
                    <span class="tours-count">
                        <i class="fa-solid fa-layer-group"></i>
                        {{ $tours->total() }} tour{{ $tours->total() != 1 ? 's' : '' }} available
                    </span>
                @endif
            </div>

            @if($tours->count())
                <div class="tour-grid">
                    @foreach($tours as $tour)
                        @php
                            // ── image ──
                            $img = null;
                            if (!empty($tour->featured_image)) {
                                $img = Str::startsWith($tour->featured_image, 'http')
                                    ? $tour->featured_image
                                    : asset('storage/' . $tour->featured_image);
                            } elseif ($tour->images->isNotEmpty()) {
                                $first = $tour->images->first();
                                $img = $first->url ?? ($first->path ? asset('storage/' . $first->path) : null);
                            }
                            $img ??= asset('images/placeholder-wide.jpg');

                            // ── price ──
                            $minPrice = $tour->prices->min('price');
                            $currency = optional($tour->prices->first())->currency ?? 'USD';

                            // ── duration ──
                            $days = $tour->itineraries->count();

                            // ── url ──
                            $tourUrl = $tour->slug ? route('tours.show', $tour->slug) : '#';
                        @endphp

                        <article class="tour-card">

                            <a href="{{ $tourUrl }}" class="tour-card__img-wrap" tabindex="-1">
                                <img src="{{ $img }}" alt="{{ $tour->title }}" loading="lazy">
                                <span class="tour-card__badge">
                                    <i class="fa-solid fa-star-half-stroke"></i> Mid-Range
                                </span>
                                @if($days)
                                    <span class="tour-card__days">
                                        <i class="fa-regular fa-calendar"></i> {{ $days }} day{{ $days != 1 ? 's' : '' }}
                                    </span>
                                @endif
                            </a>

                            <div class="tour-card__body">
                                <a href="{{ $tourUrl }}" class="tour-card__title">
                                    {{ $tour->title }}
                                </a>

                                @if($tour->destinations)
                                    <p class="tour-card__meta">
                                        <i class="fa-solid fa-location-dot"></i>
                                        {{ $tour->destinations }}
                                    </p>
                                @endif

                                @if($tour->description)
                                    <p class="tour-card__desc">
                                        {{ Str::limit(strip_tags($tour->description), 140) }}
                                    </p>
                                @else
                                    <p class="tour-card__desc" style="color:#ccc;font-style:italic;">
                                        Itinerary details available on request.
                                    </p>
                                @endif

                                <div class="tour-card__footer">
                                    <div class="tour-card__price">
                                        @if($minPrice)
                                            From <strong>{{ $currency }} {{ number_format($minPrice) }}</strong>
                                            <span>/ person</span>
                                        @else
                                            <span style="color:#bbb">Price on request</span>
                                        @endif
                                    </div>
                                    <a href="{{ $tourUrl }}" class="tour-card__cta">
                                        View itinerary <i class="fa-solid fa-arrow-right"></i>
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
                    <div class="empty-state__icon-wrap">
                        <i class="fa-solid fa-compass"></i>
                    </div>
                    <h3 class="empty-state__title">More packages coming soon</h3>
                    <p class="empty-state__text">
                        We're finalising our mid-range safari listings. In the meantime, contact us
                        and we'll design a comfortable itinerary around your dates and budget.
                    </p>
                    <a href="{{ route('contact') }}" class="empty-state__cta">
                        <i class="fa-solid fa-envelope"></i> Request a custom safari
                    </a>
                </div>
            @endif

        </div>
    </section>

    {{-- ── HIGHLIGHTS ── --}}
    <section class="highlights-section">
        <div class="highlights-section__inner">
            <p class="section-label">What's included</p>
            <h2 class="section-heading">What every mid-range package covers</h2>
            <div class="highlights-grid">
                <div class="highlight-card">
                    <div class="highlight-card__icon"><i class="fa-solid fa-bed"></i></div>
                    <div class="highlight-card__title">En-Suite Accommodation</div>
                    <p class="highlight-card__text">Private bathroom, hot shower and comfortable beds every night.</p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-card__icon"><i class="fa-solid fa-van-shuttle"></i></div>
                    <div class="highlight-card__title">4×4 Safari Vehicle</div>
                    <p class="highlight-card__text">Pop-top roof for standing game drives and great photography.</p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-card__icon"><i class="fa-solid fa-user-tie"></i></div>
                    <div class="highlight-card__title">Expert Guide</div>
                    <p class="highlight-card__text">Licensed, experienced naturalist guide throughout your trip.</p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-card__icon"><i class="fa-solid fa-utensils"></i></div>
                    <div class="highlight-card__title">Meals Included</div>
                    <p class="highlight-card__text">Breakfast daily; most packages include lunch and dinner too.</p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-card__icon"><i class="fa-solid fa-ticket"></i></div>
                    <div class="highlight-card__title">All Park Fees</div>
                    <p class="highlight-card__text">Every national park and reserve entry fee paid upfront.</p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-card__icon"><i class="fa-solid fa-plane-arrival"></i></div>
                    <div class="highlight-card__title">Airport Transfers</div>
                    <p class="highlight-card__text">Comfortable pick-up and drop-off at your arrival airport.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── TIPS ── --}}
    <section class="tips-section">
        <div class="tips-section__inner">
            <p class="section-label">Travel smarter</p>
            <h2 class="section-heading">Tips for making the most of a mid-range safari</h2>
            <div class="tips-grid">
                <div class="tip-card">
                    <div class="tip-card__icon"><i class="fa-solid fa-calendar-days"></i></div>
                    <div class="tip-card__title">Time your visit right</div>
                    <p class="tip-card__text">Dry season (June–October) offers the best game viewing as animals gather at water sources.</p>
                </div>
                <div class="tip-card">
                    <div class="tip-card__icon"><i class="fa-solid fa-camera"></i></div>
                    <div class="tip-card__title">Bring a good camera</div>
                    <p class="tip-card__text">Mid-range vehicles have open roofs and windows — perfect conditions for wildlife photography.</p>
                </div>
                <div class="tip-card">
                    <div class="tip-card__icon"><i class="fa-solid fa-suitcase-rolling"></i></div>
                    <div class="tip-card__title">Pack for the climate</div>
                    <p class="tip-card__text">Mornings and evenings can be cold even near the equator. Layers are essential in the bush.</p>
                </div>
                <div class="tip-card">
                    <div class="tip-card__icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <div class="tip-card__title">Book 3–6 months ahead</div>
                    <p class="tip-card__text">Popular mid-range lodges fill fast in peak season. Early booking secures the best rooms.</p>
                </div>
                <div class="tip-card">
                    <div class="tip-card__icon"><i class="fa-solid fa-coins"></i></div>
                    <div class="tip-card__title">Budget for tips &amp; extras</div>
                    <p class="tip-card__text">Gratuities for guides and lodge staff are customary and appreciated. Budget ~$10–15/day.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── CTA BANNER ── --}}
    <section class="cta-banner">
        <h2 class="cta-banner__title">Ready to plan your mid-range safari?</h2>
        <p class="cta-banner__text">
            Can't find the exact itinerary you're after? Every package can be customised —
            different parks, extra nights, private vehicle. Tell us what you need.
        </p>
        <a href="{{ route('contact') }}" class="cta-banner__btn">
            <i class="fa-solid fa-paper-plane"></i> Plan my safari
        </a>
    </section>

</div>
@endsection