@extends('layouts.app')

@section('title', 'Luxury & High-End Safaris – Exclusive African Tour Packages')

@section('meta_description', 'Discover our luxury and high-end safari packages across East Africa. Private vehicles, exclusive lodges, gourmet dining and bespoke itineraries — the ultimate African safari experience.')

@section('meta_keywords', 'luxury safaris, high end safari packages, exclusive African safari, Uganda luxury safari, Kenya luxury safari, Tanzania luxury safari, private safari, premium wildlife tours, luxury East Africa tours')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;900&family=DM+Sans:wght@300;400;500;600&display=swap');

    /* ── EXACT SAME PALETTE AS MID-RANGE ── */
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

    .luxury-page { font-family: 'DM Sans', sans-serif; background: var(--mist); }

    /* ── HERO ── */
    .lux-hero {
        position: relative;
        background:
            linear-gradient(155deg, rgba(26,26,26,.68) 0%, rgba(30,95,107,.6) 100%),
            url('https://images.unsplash.com/photo-1585016495481-91613a3fd217?w=1600&q=80') center/cover no-repeat;
        min-height: 460px;
        display: flex; align-items: flex-end;
        padding: 3rem 1.5rem 2.5rem;
    }
    .lux-hero__inner { max-width: 820px; }
    .lux-hero__eyebrow {
        display: inline-flex; align-items: center; gap: .5rem;
        font-size: .72rem; letter-spacing: .15em; text-transform: uppercase;
        color: var(--amber); font-weight: 600;
        background: rgba(255,255,255,.12); backdrop-filter: blur(6px);
        padding: .35rem .9rem; border-radius: 999px;
        border: 1px solid rgba(176,125,46,.4); margin-bottom: 1rem;
    }
    .lux-hero__title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.2rem, 5vw, 3.6rem);
        font-weight: 900; color: #fff; line-height: 1.1; margin-bottom: 1rem;
    }
    .lux-hero__title span { color: #f0c96e; }
    .lux-hero__sub {
        font-size: 1.05rem; color: rgba(255,255,255,.85);
        max-width: 580px; line-height: 1.65; font-weight: 300;
    }
    .lux-hero__actions {
        margin-top: 1.6rem; display: flex; gap: .9rem; flex-wrap: wrap;
    }
    .lux-btn-primary {
        display: inline-flex; align-items: center; gap: .6rem;
        background: var(--amber); color: #fff;
        font-size: .82rem; font-weight: 600; padding: .65rem 1.4rem;
        border-radius: 999px; text-decoration: none; transition: background .2s;
    }
    .lux-btn-primary:hover { background: var(--amber-dark); }
    .lux-btn-ghost {
        display: inline-flex; align-items: center; gap: .6rem;
        color: #fff; border: 1px solid rgba(255,255,255,.4);
        font-size: .82rem; font-weight: 500; padding: .65rem 1.4rem;
        border-radius: 999px; text-decoration: none; backdrop-filter: blur(4px);
        transition: border-color .2s, background .2s;
    }
    .lux-btn-ghost:hover { border-color: var(--amber); background: rgba(176,125,46,.15); }

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
    .section-heading--light { color: #fff; }

    /* ── PHILOSOPHY ── */
    .philosophy-section { background: #fff; padding: 3.5rem 1.5rem; }
    .philosophy-section__inner { max-width: 920px; margin: auto; }
    .philosophy-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 3.5rem;
        align-items: start; margin-top: 2rem;
    }
    @media(max-width:720px){ .philosophy-grid { grid-template-columns: 1fr; gap: 2rem; } }
    .philosophy-text p {
        font-size: .95rem; color: #555; line-height: 1.8; margin-bottom: 1rem; font-weight: 300;
    }
    .philosophy-text p strong { color: var(--ink); font-weight: 600; }
    .philosophy-pillars { display: flex; flex-direction: column; gap: 1.1rem; }
    .pillar {
        display: flex; gap: 1rem; align-items: flex-start;
        padding: 1.2rem; background: var(--mist); border-radius: 14px;
        border-left: 4px solid var(--amber);
    }
    .pillar__icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: var(--amber-light); display: flex; align-items: center;
        justify-content: center; flex-shrink: 0;
    }
    .pillar__icon i { color: var(--amber); font-size: 1rem; }
    .pillar__title { font-weight: 600; font-size: .9rem; color: var(--ink); margin-bottom: .2rem; }
    .pillar__text { font-size: .83rem; color: #777; line-height: 1.55; margin: 0; }

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
        padding: .22rem .65rem; border-radius: 999px; font-weight: 700;
    }
    .compare-card__icon-wrap {
        width: 46px; height: 46px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;
    }
    .compare-card__icon-wrap--budget  { background: #eaf3e8; }
    .compare-card__icon-wrap--mid     { background: var(--amber-light); }
    .compare-card__icon-wrap--luxury  { background: var(--teal-light); }
    .compare-card__title {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem; font-weight: 700; margin-bottom: .25rem; color: var(--ink);
    }
    .compare-card__price { font-size: .78rem; color: #aaa; font-weight: 500; margin-bottom: 1.2rem; }
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
        display: inline-flex; align-items: center; gap: .45rem;
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
        width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease;
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
    .tour-card__features {
        display: flex; gap: .45rem; flex-wrap: wrap; margin-bottom: 1rem;
    }
    .tour-card__feature-tag {
        display: inline-flex; align-items: center; gap: .3rem;
        font-size: .68rem; color: var(--teal); font-weight: 600;
        background: var(--teal-light); padding: .22rem .65rem;
        border-radius: 999px; border: 1px solid rgba(30,95,107,.2);
    }
    .tour-card__feature-tag i { font-size: .65rem; }
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

    /* Empty state */
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
    .empty-state__text { color: #777; margin-bottom: 1.5rem; max-width: 420px; margin-inline: auto; line-height: 1.65; }

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

    /* ── SIGNATURE EXPERIENCES (teal bg — same as trust strip) ── */
    .experiences-section { background: var(--teal); padding: 3.5rem 1.5rem; }
    .experiences-section__inner { max-width: 960px; margin: auto; }
    .experiences-section .section-label { color: #f0c96e; }
    .experiences-section .section-heading { color: #fff; }
    .experiences-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
        gap: 1.2rem; margin-top: 2rem;
    }
    .exp-card {
        background: rgba(255,255,255,.08); border-radius: 14px; padding: 1.3rem;
        border: 1px solid rgba(255,255,255,.12); transition: background .2s, transform .2s;
    }
    .exp-card:hover { background: rgba(255,255,255,.14); transform: translateY(-3px); }
    .exp-card__icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: rgba(240,201,110,.15); display: flex; align-items: center;
        justify-content: center; margin-bottom: .8rem;
    }
    .exp-card__icon i { color: #f0c96e; font-size: 1rem; }
    .exp-card__title { color: #fff; font-weight: 600; font-size: .88rem; margin-bottom: .35rem; }
    .exp-card__text { color: rgba(255,255,255,.65); font-size: .82rem; line-height: 1.55; }

    /* ── TIPS ── */
    .tips-section { background: #fff; padding: 3.5rem 1.5rem; }
    .tips-section__inner { max-width: 960px; margin: auto; }
    .tips-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.2rem; margin-top: 2rem;
    }
    .tip-card {
        background: var(--mist); border-radius: 14px; padding: 1.4rem;
        border: 1px solid var(--card-border);
    }
    .tip-card__icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: var(--amber-light); display: flex; align-items: center;
        justify-content: center; margin-bottom: .8rem;
    }
    .tip-card__icon i { color: var(--amber); font-size: 1rem; }
    .tip-card__title { font-weight: 600; font-size: .88rem; color: var(--ink); margin-bottom: .35rem; }
    .tip-card__text { font-size: .82rem; color: #777; line-height: 1.55; }

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
<div class="luxury-page">

    {{-- ── HERO ── --}}
    <section class="lux-hero">
        <div class="lux-hero__inner">
            <div class="lux-hero__eyebrow">
                <i class="fa-solid fa-crown"></i> Luxury Collection · East Africa
            </div>
            <h1 class="lux-hero__title">
                Africa at its<br><span>most extraordinary.</span>
            </h1>
            <p class="lux-hero__sub">
                Private guides. Exclusive camps. Curated moments that cannot be replicated.
                Our luxury safaris are designed for travellers who want Africa entirely on their own terms.
            </p>
            <div class="lux-hero__actions">
                <a href="#tours" class="lux-btn-primary">
                    <i class="fa-solid fa-arrow-down"></i> View packages
                </a>
                <a href="{{ route('contact') }}" class="lux-btn-ghost">
                    <i class="fa-solid fa-paper-plane"></i> Request bespoke itinerary
                </a>
            </div>
        </div>
    </section>

    {{-- ── TRUST STRIP ── --}}
    <div class="trust-strip">
        <div class="trust-strip__item">
            <i class="fa-solid fa-car-side"></i> Fully private vehicles
        </div>
        <div class="trust-strip__item">
            <i class="fa-solid fa-gem"></i> Exclusive lodge selection
        </div>
        <div class="trust-strip__item">
            <i class="fa-solid fa-champagne-glasses"></i> All-inclusive dining &amp; drinks
        </div>
        <div class="trust-strip__item">
            <i class="fa-solid fa-user-tie"></i> Personal safari consultant
        </div>
        <div class="trust-strip__item">
            <i class="fa-solid fa-infinity"></i> Fully bespoke itineraries
        </div>
    </div>

    {{-- ── PHILOSOPHY ── --}}
    <section class="philosophy-section">
        <div class="philosophy-section__inner">
            <p class="section-label">The luxury difference</p>
            <h2 class="section-heading">Not just a safari — an experience crafted for you.</h2>
            <div class="philosophy-grid">
                <div class="philosophy-text">
                    <p>
                        A luxury safari is not defined by thread-counts or champagne at sunset
                        — though you will have both. It is defined by <strong>access, exclusivity
                        and intention</strong>. Your guide knows your interests before you arrive.
                        Your vehicle goes where others cannot. Your schedule bends to yours.
                    </p>
                    <p>
                        We partner exclusively with lodges and camps that sit inside or on the
                        boundary of the parks — so your game drive begins the moment you step
                        out of your tent. <strong>No shared vehicles. No fixed departure times.
                        No crowds.</strong>
                    </p>
                    <p>
                        Every itinerary at this level is reviewed by a dedicated safari
                        consultant before it reaches you.
                    </p>
                </div>
                <div class="philosophy-pillars">
                    <div class="pillar">
                        <div class="pillar__icon"><i class="fa-solid fa-lock-open"></i></div>
                        <div>
                            <div class="pillar__title">Exclusive access</div>
                            <p class="pillar__text">Private conservancies, after-dark drives and walking safaris unavailable on standard tours.</p>
                        </div>
                    </div>
                    <div class="pillar">
                        <div class="pillar__icon"><i class="fa-solid fa-sliders"></i></div>
                        <div>
                            <div class="pillar__title">Total flexibility</div>
                            <p class="pillar__text">Start early, stay late, detour on a whim. Your guide and vehicle are entirely yours.</p>
                        </div>
                    </div>
                    <div class="pillar">
                        <div class="pillar__icon"><i class="fa-solid fa-utensils"></i></div>
                        <div>
                            <div class="pillar__title">Gourmet dining</div>
                            <p class="pillar__text">Bush breakfasts, sundowner cocktails and candlelit dinners under the stars — all included.</p>
                        </div>
                    </div>
                    <div class="pillar">
                        <div class="pillar__icon"><i class="fa-solid fa-headset"></i></div>
                        <div>
                            <div class="pillar__title">White-glove support</div>
                            <p class="pillar__text">Dedicated consultant from first enquiry to final transfer. 24/7 in-destination support.</p>
                        </div>
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
                        <i class="fa-solid fa-tent" style="color:#2d5a27;font-size:1.2rem"></i>
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

                <div class="compare-card">
                    <div class="compare-card__icon-wrap compare-card__icon-wrap--mid">
                        <i class="fa-solid fa-star-half-stroke" style="color:var(--amber);font-size:1.2rem"></i>
                    </div>
                    <div class="compare-card__title">Mid-Range</div>
                    <div class="compare-card__price">Comfort &amp; value balanced</div>
                    <ul class="compare-card__list">
                        <li><i class="fa-solid fa-check"></i> En-suite lodges &amp; tented camps</li>
                        <li><i class="fa-solid fa-check"></i> Semi-private vehicles</li>
                        <li><i class="fa-solid fa-check"></i> Pool &amp; better amenities</li>
                        <li><i class="fa-solid fa-check"></i> Most meals included</li>
                        <li><i class="fa-solid fa-check"></i> Smaller groups (4–8 guests)</li>
                    </ul>
                </div>

                <div class="compare-card compare-card--active">
                    <div class="compare-card__icon-wrap compare-card__icon-wrap--luxury">
                        <i class="fa-solid fa-crown" style="color:var(--teal);font-size:1.2rem"></i>
                    </div>
                    <div class="compare-card__title">Luxury</div>
                    <div class="compare-card__price">Exclusive &amp; fully personalised</div>
                    <ul class="compare-card__list">
                        <li><i class="fa-solid fa-check"></i> Exclusive premier lodges &amp; camps</li>
                        <li><i class="fa-solid fa-check"></i> Fully private vehicle &amp; guide</li>
                        <li><i class="fa-solid fa-check"></i> Gourmet dining, fully all-inclusive</li>
                        <li><i class="fa-solid fa-check"></i> High staff-to-guest ratio</li>
                        <li><i class="fa-solid fa-check"></i> Bespoke tailored itineraries</li>
                        <li><i class="fa-solid fa-check"></i> Private conservancy access</li>
                        <li><i class="fa-solid fa-check"></i> Dedicated safari consultant</li>
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
                    <p class="section-label">Exclusive packages</p>
                    <h2 class="section-heading" style="margin-bottom:0">Luxury Safari Collection</h2>
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

                            $minPrice = $tour->prices->min('price');
                            $currency = optional($tour->prices->first())->currency ?? 'USD';
                            $days     = $tour->itineraries->count();
                            $tourUrl  = $tour->slug ? route('tours.show', $tour->slug) : '#';
                        @endphp

                        <article class="tour-card">

                            <a href="{{ $tourUrl }}" class="tour-card__img-wrap" tabindex="-1">
                                <img src="{{ $img }}" alt="{{ $tour->title }}" loading="lazy">
                                <span class="tour-card__badge">
                                    <i class="fa-solid fa-crown"></i> Luxury
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
                                        Bespoke itinerary details available on request.
                                    </p>
                                @endif

                                <div class="tour-card__features">
                                    <span class="tour-card__feature-tag">
                                        <i class="fa-solid fa-car-side"></i> Private vehicle
                                    </span>
                                    <span class="tour-card__feature-tag">
                                        <i class="fa-solid fa-utensils"></i> All-inclusive
                                    </span>
                                    <span class="tour-card__feature-tag">
                                        <i class="fa-solid fa-user-tie"></i> Private guide
                                    </span>
                                </div>

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
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <h3 class="empty-state__title">Curating exceptional experiences</h3>
                    <p class="empty-state__text">
                        Our luxury collection is being carefully curated. Every package is
                        reviewed personally before it goes live. We'd love to design
                        something bespoke just for you in the meantime.
                    </p>
                    <a href="{{ route('contact') }}" class="lux-btn-primary">
                        <i class="fa-solid fa-paper-plane"></i> Request a bespoke itinerary
                    </a>
                </div>
            @endif

        </div>
    </section>

    {{-- ── WHAT'S INCLUDED ── --}}
    <section class="highlights-section">
        <div class="highlights-section__inner">
            <p class="section-label">What's included</p>
            <h2 class="section-heading">Every luxury package covers</h2>
            <div class="highlights-grid">
                <div class="highlight-card">
                    <div class="highlight-card__icon"><i class="fa-solid fa-bed"></i></div>
                    <div class="highlight-card__title">Premier Accommodation</div>
                    <p class="highlight-card__text">Exclusive lodges and tented camps with spacious suites and private bathrooms.</p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-card__icon"><i class="fa-solid fa-car-side"></i></div>
                    <div class="highlight-card__title">Private 4×4 Vehicle</div>
                    <p class="highlight-card__text">Your own pop-top safari vehicle — go where you want, when you want.</p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-card__icon"><i class="fa-solid fa-user-tie"></i></div>
                    <div class="highlight-card__title">Private Expert Guide</div>
                    <p class="highlight-card__text">Your dedicated licensed naturalist — expert, passionate and entirely yours.</p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-card__icon"><i class="fa-solid fa-champagne-glasses"></i></div>
                    <div class="highlight-card__title">Fully All-Inclusive</div>
                    <p class="highlight-card__text">All meals, selected drinks, park fees and activities included.</p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-card__icon"><i class="fa-solid fa-ticket"></i></div>
                    <div class="highlight-card__title">All Park Fees</div>
                    <p class="highlight-card__text">Every national park and conservancy entry fee paid — nothing hidden.</p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-card__icon"><i class="fa-solid fa-plane-arrival"></i></div>
                    <div class="highlight-card__title">Private Transfers</div>
                    <p class="highlight-card__text">Private airport pick-up, drop-off and all in-country transfers included.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── SIGNATURE EXPERIENCES ── --}}
    <section class="experiences-section">
        <div class="experiences-section__inner">
            <p class="section-label">Only at this level</p>
            <h2 class="section-heading section-heading--light">Signature luxury experiences</h2>
            <div class="experiences-grid">
                <div class="exp-card">
                    <div class="exp-card__icon"><i class="fa-solid fa-person-hiking"></i></div>
                    <div class="exp-card__title">Walking safaris</div>
                    <p class="exp-card__text">Track wildlife on foot with an armed ranger — raw, intimate and unforgettable.</p>
                </div>
                <div class="exp-card">
                    <div class="exp-card__icon"><i class="fa-solid fa-water-ladder"></i></div>
                    <div class="exp-card__title">Private plunge pools</div>
                    <p class="exp-card__text">Suites with private decks and pools overlooking the bush or waterhole.</p>
                </div>
                <div class="exp-card">
                    <div class="exp-card__icon"><i class="fa-solid fa-sun"></i></div>
                    <div class="exp-card__title">Bush sundowners</div>
                    <p class="exp-card__text">Cocktails as the sun sinks over the savanna, deep in the wilderness.</p>
                </div>
                <div class="exp-card">
                    <div class="exp-card__icon"><i class="fa-solid fa-helicopter"></i></div>
                    <div class="exp-card__title">Helicopter transfers</div>
                    <p class="exp-card__text">Arrive in style — aerial transfers between parks reveal Africa from above.</p>
                </div>
                <div class="exp-card">
                    <div class="exp-card__icon"><i class="fa-solid fa-moon"></i></div>
                    <div class="exp-card__title">Night game drives</div>
                    <p class="exp-card__text">Spot nocturnal predators in private conservancies off-limits to standard vehicles.</p>
                </div>
                <div class="exp-card">
                    <div class="exp-card__icon"><i class="fa-solid fa-spa"></i></div>
                    <div class="exp-card__title">In-bush spa</div>
                    <p class="exp-card__text">Rejuvenate after a game drive with a massage in an open-air bush spa.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── TIPS ── --}}
    <section class="tips-section">
        <div class="tips-section__inner">
            <p class="section-label">Expert advice</p>
            <h2 class="section-heading">Making the most of a luxury safari</h2>
            <div class="tips-grid">
                <div class="tip-card">
                    <div class="tip-card__icon"><i class="fa-solid fa-calendar-check"></i></div>
                    <div class="tip-card__title">Book 6–12 months ahead</div>
                    <p class="tip-card__text">The best luxury camps have very few rooms and fill a year in advance, especially in peak season.</p>
                </div>
                <div class="tip-card">
                    <div class="tip-card__icon"><i class="fa-solid fa-camera-retro"></i></div>
                    <div class="tip-card__title">Invest in a good lens</div>
                    <p class="tip-card__text">A 200–500mm telephoto lens transforms your wildlife photography from good to extraordinary.</p>
                </div>
                <div class="tip-card">
                    <div class="tip-card__icon"><i class="fa-solid fa-suitcase"></i></div>
                    <div class="tip-card__title">Pack light in soft bags</div>
                    <p class="tip-card__text">Charter flights between camps have strict 15kg limits. Soft-sided bags are required on many aircraft.</p>
                </div>
                <div class="tip-card">
                    <div class="tip-card__icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="tip-card__title">Comprehensive travel insurance</div>
                    <p class="tip-card__text">Medical evacuation cover is essential at this level of investment. Don't travel without it.</p>
                </div>
                <div class="tip-card">
                    <div class="tip-card__icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
                    <div class="tip-card__title">Tip generously</div>
                    <p class="tip-card__text">Your guide and camp staff are exceptional professionals. Budget $20–30/day per person in gratuities.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── CTA BANNER ── --}}
    <section class="cta-banner">
        <h2 class="cta-banner__title">Every detail considered. Every moment intentional.</h2>
        <p class="cta-banner__text">
            Our luxury itineraries are never off-the-shelf. Tell us who you are travelling with,
            what moves you and what you have always dreamed of seeing — our consultants will
            build something extraordinary around you.
        </p>
        <a href="{{ route('contact') }}" class="cta-banner__btn">
            <i class="fa-solid fa-paper-plane"></i> Begin your bespoke journey
        </a>
    </section>

</div>
@endsection