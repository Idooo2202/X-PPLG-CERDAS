<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>X PPLG C</title>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;700;800&family=Nunito:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/Draggable.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <link rel="manifest" href="{{ asset('manifest.json') }}">
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register("{{ asset('sw.js') }}");
    }
</script>

    {{-- ========== SEMUA CSS DARI HOMESCREEN KAMU (TIDAK BERUBAH) ========== --}}
    <style>
        /* PASTE SELURUH CSS DARI HOME SCREEN KAMU DI SINI */
        /* (Salin dari :root { hingga .rv.on { transform: none } }) */
        /* ============================================================
   TOKENS
============================================================ */
        :root {
            --sandy: #F5DEB3;
            --sandy-dk: #E2C47A;
            --ocean: #0077be;
            --ocean-dk: #005a8e;
            --ocean-deep: #001f3f;
            --turq: #40E0D0;
            --turq-dk: #2bb8a9;
            --seafoam: #93E9BE;
            --cream: #FFF9F0;
            --coral: #FF7B6B;
            --deep: #0a2540;
            --glass: rgba(255, 255, 255, 0.14);
            --glass-b: rgba(255, 255, 255, 0.28);
            --fd: 'Baloo 2', cursive;
            --fb: 'Nunito', sans-serif;
            --r: 20px;
            --sh: 0 8px 32px rgba(0, 119, 190, 0.18);
        }

        /* ============================================================
   RESET
============================================================ */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        html {
            scroll-behavior: smooth
        }

        body {
            font-family: var(--fb);
            background: var(--cream);
            color: var(--deep);
            overflow-x: hidden;
            transition: background-color 0.4s ease;
        }

        ::selection {
            background: var(--turq);
            color: var(--deep)
        }

        ::-webkit-scrollbar {
            width: 7px
        }

        ::-webkit-scrollbar-track {
            background: #d6f0ff
        }

        ::-webkit-scrollbar-thumb {
            background: var(--ocean);
            border-radius: 4px
        }

        /* ============================================================
   DEEP-DIVE LAYERS  (fixed overlay, pointer-events:none)
============================================================ */
        #diveVignette {
            position: fixed;
            inset: 0;
            z-index: 9997;
            pointer-events: none;
            background: radial-gradient(ellipse at 50% 40%,
                    transparent 30%,
                    rgba(0, 20, 50, 0.35) 65%,
                    rgba(0, 10, 30, 0.75) 100%);
            opacity: 0;
        }

        #diveBlue {
            position: fixed;
            inset: 0;
            z-index: 9996;
            pointer-events: none;
            background: linear-gradient(180deg,
                    rgba(0, 40, 100, 0) 0%,
                    rgba(0, 40, 100, 0.5) 100%);
            opacity: 0;
        }

        #diveBubbles {
            position: fixed;
            inset: 0;
            z-index: 9998;
            pointer-events: none;
            overflow: hidden;
        }

        .dbub {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(64, 224, 208, 0.5);
            background: rgba(64, 224, 208, 0.12);
            animation: dbubUp linear infinite;
            opacity: 0;
        }

        @keyframes dbubUp {
            from {
                transform: translateY(0) scale(0.4)
            }

            to {
                transform: translateY(-110vh) scale(1)
            }
        }

        /* ============================================================
   NAVBAR
============================================================ */
        #navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 5%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--glass-b);
            transition: background 0.4s, box-shadow 0.4s;
        }

        #navbar.scrolled {
            background: rgba(0, 41, 90, 0.507);
            box-shadow: 0 4px 24px rgba(0, 20, 60, 0.548);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 9px;
            font-family: var(--fd);
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
            text-decoration: none;
            white-space: nowrap;
        }

        .nav-brand .anchor {
            display: inline-block;
            animation: anchorBob 2.5s ease-in-out infinite
        }

        .nav-brand span {
            color: var(--sandy-dk);
        }

        @keyframes anchorBob {

            0%,
            100% {
                transform: translateY(0) rotate(0)
            }

            50% {
                transform: translateY(-5px) rotate(10deg)
            }
        }

        .nav-links {
            display: flex;
            gap: 4px;
            list-style: none
        }

        .nav-links a {
            font-family: var(--fd);
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--deep);
            text-decoration: none;
            padding: 7px 14px;
            border-radius: 50px;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .nav-links a:hover {
            background: var(--ocean);
            color: #fff;
            transform: translateY(-2px)
        }

        /* Hamburger */
        .nav-ham {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 6px;
            flex-shrink: 0
        }

        .nav-ham span {
            display: block;
            width: 24px;
            height: 2.5px;
            background: var(--ocean);
            border-radius: 2px;
            transition: all 0.3s
        }

        .nav-ham.open span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px)
        }

        .nav-ham.open span:nth-child(2) {
            opacity: 0
        }

        .nav-ham.open span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px)
        }

        @media(max-width:768px) {
            .nav-ham {
                display: flex
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                flex-direction: column;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                padding: 14px;
                gap: 2px;
                border-bottom: 1px solid var(--glass-b);
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.14);
            }

            .nav-links.open {
                display: flex
            }

            .nav-links a {
                color: var(--ocean);
                padding: 11px 16px;
                border-radius: 12px
            }

            .nav-links a:hover {
                background: rgba(0, 119, 190, 0.1);
                transform: none
            }

            .nav-brand {
                font-size: 1.2rem
            }
        }

        /* ============================================================
   HERO
============================================================ */
        #hero {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 20px 60px;
            background: linear-gradient(180deg, #b8e4ff 0%, #7ec8e3 35%, #3eb5c7 70%, #1a9aab 100%);
        }

        /* Ambient bubbles */
        .hero-bg {
            position: absolute;
            inset: 0;
            pointer-events: none
        }

        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.22);
            border: 1px solid rgba(255, 255, 255, 0.35);
            animation: bUp linear infinite;
        }

        @keyframes bUp {
            from {
                transform: translateY(100vh) scale(0.4);
                opacity: 0.8
            }

            to {
                transform: translateY(-10vh) scale(1);
                opacity: 0
            }
        }

        /* Sun */
        .hero-sun {
            position: absolute;
            top: 55px;
            right: 7%;
            width: 88px;
            height: 88px;
            background: radial-gradient(circle, #FFE54C, #FFA500);
            border-radius: 50%;
            box-shadow: 0 0 60px rgba(255, 210, 0, 0.55);
            animation: sunP 4s ease-in-out infinite;
        }

        @keyframes sunP {

            0%,
            100% {
                box-shadow: 0 0 60px rgba(255, 210, 0, 0.5)
            }

            50% {
                box-shadow: 0 0 110px rgba(255, 210, 0, 0.85)
            }
        }

        .hero-sun::after {
            content: '';
            position: absolute;
            inset: -22px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 220, 0, 0.26), transparent 70%);
            animation: sunP 4s ease-in-out infinite reverse;
        }

        /* Seagulls */
        .gull {
            position: absolute;
            animation: gullFly linear infinite;
            font-size: 1.3rem;
            opacity: 0.7
        }

        @keyframes gullFly {
            from {
                transform: translateX(-100px) scaleX(1)
            }

            to {
                transform: translateX(calc(100vw + 100px)) scaleX(1)
            }
        }

        /* Hero text block — this is overridden below in the officers block */
        .hero-center-placeholder {
            display: none
        }

        .hero-tag {
            display: inline-block;
            background: rgba(255, 255, 255, 0.28);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: #004a7a;
            font-family: var(--fd);
            font-weight: 700;
            font-size: 0.82rem;
            padding: 5px 18px;
            border-radius: 50px;
            margin-bottom: 14px;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .hero-title {
            font-family: var(--fd);
            font-size: clamp(2.8rem, 10vw, 6.5rem);
            font-weight: 800;
            color: #fff;
            text-shadow: 0 4px 24px rgba(0, 60, 120, 0.4);
            line-height: 1;
            margin-bottom: 8px;
        }

        .hero-title span {
            color: var(--sandy)
        }

        .hero-sub {
            font-size: clamp(0.85rem, 2.2vw, 1.08rem);
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 32px;
            line-height: 1.5;
        }

        .hero-cta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--sandy);
            color: var(--ocean-dk);
            font-family: var(--fd);
            font-weight: 700;
            font-size: 0.98rem;
            padding: 12px 28px;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
            text-decoration: none;
        }

        .hero-cta:hover {
            transform: translateY(-4px) scale(1.04);
            box-shadow: 0 14px 36px rgba(0, 0, 0, 0.28)
        }

        /* Officers scatter wrap — sits BEHIND hero text, covers full section */
        .officers-wrap {
            position: absolute;
            inset: 0;
            z-index: 4;
            /* below hero-center (z-index:10) */
            pointer-events: none;
            /* let clicks pass through to hero-center */
            overflow: hidden;
        }

        /* Individual polaroid */
        .polaroid {
            border-radius: 10px;
            position: absolute;
            background: #fff;
            padding: 8px 8px 10px;
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.22);
            cursor: pointer;
            will-change: transform;
            transition: box-shadow 0.3s;
            width: 130px;
            pointer-events: all;
        }



        .polaroid .pol-img {
            width: 114px;
            height: 114px;
            background: linear-gradient(135deg, var(--turq), var(--ocean));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            pointer-events: none;
        }

        .polaroid .pol-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            pointer-events: none;
            user-select: none;
        }

        .polaroid .pol-name {
            text-align: center;
            font-family: var(--fd);
            font-weight: 700;
            font-size: 0.6rem;
            color: var(--ocean);
            margin-top: 5px;
            line-height: 1.3;
            pointer-events: none;
        }

        .polaroid .pol-role {
            font-size: 0.55rem;
            color: #aaa;
            font-family: var(--fb);
            font-weight: 600
        }

        .polaroid:hover {
            box-shadow: 0 14px 40px rgba(0, 0, 0, 0.32);
            z-index: 60 !important
        }

        /* Hero text block must sit above the polaroids */
        .hero-center {
            position: relative;
            z-index: 10;
            /* above officers-wrap (z-index:4) */
            text-align: center;
            max-width: 520px;
            width: 100%;
            /* Push text to center column, away from polaroids on sides */
            margin: 0 auto;
        }

        /* ── MOBILE: polaroids become a neat grid BELOW hero text ── */
        @media(max-width:768px) {
            #hero {
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
                padding-top: 90px;
                padding-bottom: 100px;
                min-height: auto;
            }

            /* Officers wrap: leave absolute positioning, become normal flow block */
            .officers-wrap {
                position: static !important;
                /* kick out of absolute stacking */
                inset: auto !important;
                top: auto !important;
                left: auto !important;
                right: auto !important;
                bottom: auto !important;
                width: 100%;
                max-width: 480px;
                display: grid !important;
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 19px;
                padding: 14px;
                margin-top: 20px;
                overflow: visible;
                pointer-events: all;
                z-index: 10;
            }

            /* Individual polaroid: kill ALL inline positioning */
            .polaroid {
                position: static !important;
                /* overrides JS inline top/left */
                top: auto !important;
                left: auto !important;
                right: auto !important;
                bottom: auto !important;
                /* keep transform so JS can apply rotation on mobile */
                width: 100% !important;
                will-change: auto;
            }

            .polaroid .pol-img {
                width: 100% !important;
                height: auto !important;
                aspect-ratio: 1;
            }

            .hero-center {
                order: -1;
                margin-bottom: 20px;
                margin-top: 100px;
                max-width: 100%;
                padding: 0 10px;
            }
        }

        /* Shore wave */
        .hero-shore {
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            z-index: 9
        }

        .hero-shore svg {
            display: block;
            width: 100%
        }

        /* ============================================================
   SECTION UTILITIES
============================================================ */
        section {
            position: relative
        }

        .sec-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 5%
        }

        .sec-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--ocean);
            color: #fff;
            font-family: var(--fd);
            font-weight: 700;
            font-size: 0.8rem;
            padding: 5px 16px;
            border-radius: 50px;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }

        .sec-title {
            font-family: var(--fd);
            font-size: clamp(1.7rem, 4vw, 2.8rem);
            font-weight: 800;
            color: var(--ocean-dk);
            margin-bottom: 8px;
        }

        .sec-title span {
            color: var(--cream);
        }

        .sec-title .sec-title-gallery {
            color: var(--ocean);
        }

        .sec-desc {
            color: #ffffff;
            max-width: 560px;
            line-height: 1.75
        }

        /* ============================================================
   ABOUT
============================================================ */
        #about {
            padding: 100px 0 90px;
            background: linear-gradient(180deg, var(--ocean) 0%, var(--ocean-dk) 100%);
        }

        /* ID cards */
        .id-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-top: 32px;
        }

        @media(max-width:900px) {
            .id-grid {
                grid-template-columns: repeat(2, 1fr)
            }
        }

        @media(max-width:440px) {
            .id-grid {
                grid-template-columns: 1fr 1fr
            }
        }

        .id-card {
            background: #ffffff;
            border-radius: var(--r);
            padding: 20px;
            box-shadow: var(--sh);
            border: 2px solid transparent;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .id-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--ocean), var(--turq));
        }

        .id-card:hover {
            transform: translateY(-5px);
            border-color: var(--turq);
            box-shadow: 0 16px 40px rgba(0, 119, 190, 0.2)
        }

        .id-icon {
            font-size: 1.7rem;
            margin-bottom: 7px
        }

        .id-lbl {
            font-size: 0.72rem;
            color: #bbb;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px
        }

        .id-val {
            font-family: var(--fd);
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--deep)
        }

        /* Schedule */
        .sch-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-top: 32px;
        }

        @media(max-width:700px) {
            .sch-grid {
                grid-template-columns: 1fr
            }
        }

        .sch-card {
            background: #fff;
            border-radius: var(--r);
            padding: 24px;
            box-shadow: var(--sh)
        }

        .sch-title {
            font-family: var(--fd);
            font-weight: 700;
            font-size: 1rem;
            color: var(--ocean);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .live-clock {
            font-family: var(--fd);
            font-size: 1.9rem;
            font-weight: 800;
            color: var(--deep);
            letter-spacing: 2px
        }

        .live-day {
            font-size: 0.82rem;
            color: #bbb;
            font-weight: 600;
            margin-top: 2px
        }

        .lbox {
            margin-top: 12px;
            padding: 12px;
            border-radius: 12px;
            color: #fff;
            background: linear-gradient(135deg, var(--ocean), var(--turq-dk))
        }

        .lbox.nxt {
            background: var(--seafoam);
            color: var(--deep)
        }

        .ll {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.82
        }

        .ln {
            font-family: var(--fd);
            font-weight: 700;
            font-size: 0.92rem;
            margin-top: 2px
        }

        .lt {
            font-size: 0.76rem;
            opacity: 0.88;
            margin-top: 1px
        }

        .piket-badge {
            display: inline-block;
            background: var(--ocean);
            color: #fff;
            font-family: var(--fd);
            font-weight: 700;
            font-size: 0.8rem;
            padding: 3px 13px;
            border-radius: 50px;
            margin-bottom: 11px;
        }

        .piket-list {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 6px
        }

        .piket-list li {
            background: var(--sandy);
            color: var(--ocean-dk);
            font-family: var(--fd);
            font-weight: 600;
            font-size: 0.78rem;
            padding: 4px 11px;
            border-radius: 50px;
        }

        .piket-list li::before {
            content: '🐚 '
        }

        .full-sch {
            margin-top: 18px
        }

        .si {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 7px 10px;
            border-radius: 9px;
            margin-bottom: 3px;
            font-size: 0.78rem;
            background: #f5f9ff;
        }

        .si.active {
            background: linear-gradient(90deg, rgba(0, 119, 190, 0.13), rgba(64, 224, 208, 0.13));
            border-left: 3px solid var(--ocean);
            font-weight: 700;
            color: var(--ocean);
        }

        .si.brk {
            background: #fff9f4;
            color: #c08060;
            font-style: italic
        }

        .si-t {
            color: #ccc;
            font-size: 0.72rem;
            flex-shrink: 0;
            margin-left: 8px
        }

        /* ============================================================
   LOKASI / MAP SECTION  — FIXED
   ============================================================ */
#lokasi {
    /* padding: 100px 0 0px; */
    background: linear-gradient(180deg, var(--ocean-dk) 0%, var(--ocean-deep) 100%);
    position: relative;
    overflow: hidden;
}

#lokasi .sec-title { color: #fff; }
#lokasi .sec-title span { color: var(--turq); }
#lokasi .sec-desc { color: rgba(255,255,255,0.72); }

/* Dot-grid noise */
#lokasi::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(64,224,208,0.07) 1px, transparent 1px);
    background-size: 44px 44px;
    pointer-events: none;
    z-index: 0;
}

/* ── NEW: outer wrapper handles the glow ring positioning ── */
.map-outer {
    position: relative;
    margin-top: 36px;
    /* Glow ring needs space to breathe beyond map edge */
    padding: 30px;
    margin-left: -30px;
    margin-right: -30px;
}

/* Pulsing glow ring — now lives in .map-outer, NOT clipped */
.map-glow-ring {
    position: absolute;
    inset: 0;
    border-radius: 40px;
    background: radial-gradient(ellipse at 50% 50%,
        rgba(64,224,208,0.10) 0%,
        rgba(0,119,190,0.06) 40%,
        transparent 70%);
    pointer-events: none;
    animation: mapGlowPulse 4s ease-in-out infinite alternate;
    z-index: 0;
}

@keyframes mapGlowPulse {
    from { opacity: 0.5; transform: scale(0.97); }
    to   { opacity: 1;   transform: scale(1.03); }
}

/* Map card — clips map tiles to rounded corners */
.map-wrap {
    position: relative;
    border-radius: 24px;
    overflow: hidden;           /* clips Leaflet tiles cleanly */
    box-shadow:
        0 0 0 1px rgba(64,224,208,0.22),
        0 20px 60px rgba(0,0,0,0.50),
        0 0 100px rgba(64,224,208,0.10); /* glow on the box itself */
    z-index: 1;
}

/* Leaflet map div */
#leafletMap {
    position: relative;
    z-index: 1;
    width: 100%;
    height: 450px;
}

/* Override Leaflet container background */
.leaflet-container {
    background: #001f3f !important;
    font-family: var(--fb) !important;
}

/* Vignette overlay — sits above map, below UI controls */
.map-wrap::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(
        ellipse at center,
        transparent 50%,
        rgba(0, 31, 63, 0.50) 100%
    );
    pointer-events: none;
    z-index: 2;
}

/* Leaflet zoom controls */
.leaflet-control-zoom {
    border: none !important;
    box-shadow: 0 4px 16px rgba(0,0,0,0.4) !important;
}

.leaflet-control-zoom a {
    background: rgba(0,31,63,0.90) !important;
    color: var(--turq) !important;
    border: 1px solid rgba(64,224,208,0.25) !important;
    font-weight: 700 !important;
    font-size: 1rem !important;
    line-height: 28px !important;
    transition: all 0.2s;
}

.leaflet-control-zoom a:hover {
    background: var(--ocean) !important;
    color: #fff !important;
    border-color: var(--turq) !important;
}

/* Attribution */
.leaflet-control-attribution {
    background: rgba(0,20,50,0.80) !important;
    color: rgba(255,255,255,0.45) !important;
    font-size: 0.62rem !important;
    backdrop-filter: blur(6px);
}

.leaflet-control-attribution a { color: var(--turq) !important; }

/* Custom popup */
.leaflet-popup-content-wrapper {
    background: rgba(0,20,50,0.95) !important;
    border: 1px solid rgba(64,224,208,0.35) !important;
    border-radius: 16px !important;
    box-shadow: 0 8px 32px rgba(0,0,0,0.6), 0 0 24px rgba(64,224,208,0.12) !important;
    backdrop-filter: blur(14px) !important;
    padding: 0 !important;
}

.leaflet-popup-content {
    margin: 0 !important;
    padding: 16px 18px !important;
    font-family: var(--fb) !important;
    color: rgba(255,255,255,0.92) !important;
    font-size: 0.85rem !important;
    line-height: 1.6 !important;
    min-width: 200px;
}

.leaflet-popup-tip {
    background: rgba(0,20,50,0.95) !important;
}

.leaflet-popup-close-button {
    color: rgba(64,224,208,0.8) !important;
    font-size: 1.1rem !important;
    top: 8px !important;
    right: 10px !important;
    padding: 0 !important;
    transition: color 0.2s;
}

.leaflet-popup-close-button:hover { color: #fff !important; }

/* Popup inner classes */
.mp-title {
    font-family: var(--fd);
    font-weight: 800;
    font-size: 1rem;
    color: var(--turq);
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.mp-sub {
    font-size: 0.78rem;
    color: rgba(255,255,255,0.55);
    margin-bottom: 10px;
    line-height: 1.5;
}

.mp-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: var(--fd);
    font-weight: 700;
    font-size: 0.74rem;
    color: var(--turq);
    text-decoration: none;
    border: 1px solid rgba(64,224,208,0.3);
    padding: 3px 10px;
    border-radius: 50px;
    transition: all 0.2s;
    margin-bottom: 8px;
    display: inline-block;
}

.mp-link:hover {
    background: rgba(64,224,208,0.15);
    color: #fff;
}

.mp-tag {
    display: inline-block;
    background: rgba(64,224,208,0.12);
    border: 1px solid rgba(64,224,208,0.28);
    color: var(--turq);
    font-family: var(--fd);
    font-weight: 700;
    font-size: 0.65rem;
    padding: 2px 9px;
    border-radius: 50px;
    margin-right: 4px;
    margin-top: 4px;
}

/* Info card overlay */
.map-info-card {
    position: absolute;
    bottom: 18px;
    left: 18px;
    z-index: 500;
    background: rgba(0,15,40,0.92);
    border: 1px solid rgba(64,224,208,0.28);
    border-radius: 16px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    backdrop-filter: blur(16px);
    box-shadow: 0 8px 28px rgba(0,0,0,0.45);
    max-width: 260px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.map-info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 36px rgba(0,0,0,0.55);
}

.mic-icon { font-size: 1.6rem; flex-shrink: 0; line-height: 1; }

.mic-title {
    font-family: var(--fd);
    font-weight: 800;
    font-size: 0.88rem;
    color: #fff;
    line-height: 1.3;
}

.mic-sub {
    font-size: 0.72rem;
    color: rgba(255,255,255,0.50);
    margin-top: 2px;
}

.mic-link {
    display: inline-block;
    margin-top: 6px;
    font-family: var(--fd);
    font-weight: 700;
    font-size: 0.72rem;
    color: var(--turq);
    text-decoration: none;
    transition: color 0.2s;
}

.mic-link:hover { color: #fff; }

/* ── Responsive ── */
@media (max-width: 1024px) {
    #leafletMap { height: 400px; }
}

@media (max-width: 768px) {
    #leafletMap { height: 320px; }
    .map-outer { padding: 20px; margin-left: -20px; margin-right: -20px; }
    .map-info-card {
        bottom: 10px; left: 10px;
        max-width: 195px; padding: 10px 12px; gap: 8px;
    }
    .mic-icon { font-size: 1.2rem; }
    .mic-title { font-size: 0.78rem; }
    .mic-sub { font-size: 0.66rem; }
}

@media (max-width: 480px) {
    #leafletMap { height: 270px; }
    .map-outer { padding: 16px; margin-left: -16px; margin-right: -16px; }
    .map-info-card { display: none; } /* too cramped on tiny screens */
}

/* ── Fix #randomizer gradient seam ── */
/* BEFORE: linear-gradient(180deg, var(--ocean-dk), var(--ocean-deep))  ← jarring jump */
/* AFTER:  stays at ocean-deep, seamless continuation from #lokasi       */

        /* ============================================================
   WHIRLPOOL RANDOMIZER — CSS SNIPPET
   Tambahkan ke file style.css Anda (misalnya setelah blok #about)
   ============================================================ */

        /* ── Section wrapper ─────────────────────────────────── */
        #randomizer {
            padding: 90px 0 0;
            background: linear-gradient(180deg, var(--ocean-deep) 0%, var(--ocean-deep) 100%);
            position: relative;
            overflow: hidden;
        }

        /* Subtle star/bubble background noise */
        #randomizer::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle, rgba(64, 224, 208, 0.08) 1px, transparent 1px),
                radial-gradient(circle, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 40px 40px, 70px 70px;
            pointer-events: none;
        }

        /* ── Description text ─────────────────────────────────── */
        .rand-desc {
            color: rgba(255, 255, 255, 0.72);
            font-size: clamp(0.88rem, 2vw, 1rem);
            max-width: 520px;
            line-height: 1.7;
            margin-bottom: 40px;
        }

        /* ── Center layout (whirlpool + form side by side on desktop) ── */
        .rand-center {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 48px;
            flex-wrap: wrap;
            margin-bottom: 48px;
        }

        /* ── Whirlpool SVG wrapper ────────────────────────────── */
        .whirlpool-wrap {
            position: relative;
            width: 200px;
            height: 200px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Pulsing ring decorations */
        .wp-ring {
            position: absolute;
            border-radius: 50%;
            border: 2px solid rgba(64, 224, 208, 0.2);
            animation: wpRingPulse ease-in-out infinite alternate;
            pointer-events: none;
        }

        .wp-ring-1 {
            width: 220px;
            height: 220px;
            animation-duration: 3s;
            animation-delay: 0s;
        }

        .wp-ring-2 {
            width: 260px;
            height: 260px;
            animation-duration: 3.8s;
            animation-delay: 0.6s;
        }

        .wp-ring-3 {
            width: 300px;
            height: 300px;
            animation-duration: 4.5s;
            animation-delay: 1.2s;
        }

        @keyframes wpRingPulse {
            from {
                transform: scale(0.92);
                opacity: 0.6;
            }

            to {
                transform: scale(1.05);
                opacity: 0.2;
            }
        }

        .whirlpool-svg {
            width: 200px;
            height: 200px;
            filter: drop-shadow(0 0 20px rgba(64, 224, 208, 0.35));
            /* idle slow spin */
            animation: wpIdleSpin 12s linear infinite;
        }

        @keyframes wpIdleSpin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* ── Input form area ─────────────────────────────────── */
        .rand-form {
            display: flex;
            flex-direction: column;
            gap: 14px;
            max-width: 360px;
            width: 100%;
        }

        .rand-label {
            font-family: var(--fd);
            font-size: 1rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.5;
        }

        .rand-input-row {
            display: flex;
            gap: 10px;
            align-items: stretch;
        }

        .rand-input {
            width: 90px;
            flex-shrink: 0;
            padding: 12px 14px;
            font-family: var(--fd);
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--ocean-deep);
            background: #fff;
            border: 3px solid var(--turq);
            border-radius: 16px;
            outline: none;
            text-align: center;
            transition: border-color 0.3s, box-shadow 0.3s;
            appearance: textfield;
            -moz-appearance: textfield;
            /* hide spinners Firefox */
        }

        .rand-input::-webkit-outer-spin-button,
        .rand-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .rand-input:focus {
            border-color: #fff;
            box-shadow: 0 0 0 3px rgba(64, 224, 208, 0.4);
        }

        .rand-btn {
            flex: 1;
            padding: 12px 18px;
            background: linear-gradient(135deg, var(--turq), var(--turq-dk));
            color: var(--ocean-deep);
            font-family: var(--fd);
            font-weight: 800;
            font-size: 0.95rem;
            border: none;
            border-radius: 16px;
            cursor: pointer;
            box-shadow: 0 6px 24px rgba(64, 224, 208, 0.35);
            transition: all 0.3s;
            line-height: 1.3;
        }

        .rand-btn:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 12px 32px rgba(64, 224, 208, 0.5);
            background: linear-gradient(135deg, #5af0e0, var(--turq));
        }

        .rand-btn:active {
            transform: scale(0.97);
        }

        /* Disabled state during animation */
        .rand-btn:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .rand-note {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        /* ── Group results grid ──────────────────────────────── */
        .group-results {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 18px;
            padding-bottom: 60px;
        }

        /* Each group card — styled like a bubble/shell */
        .group-card {
            background: rgba(255, 255, 255, 0.06);
            border: 1.5px solid rgba(64, 224, 208, 0.3);
            border-radius: 24px;
            padding: 20px 16px 16px;
            backdrop-filter: blur(8px);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .group-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
            border-color: rgba(64, 224, 208, 0.6);
        }

        /* Shimmer highlight at the top of each card */
        .group-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 24px 24px 0 0;
        }

        /* Cycle through a few accent colours for visual variety */
        .group-card:nth-child(6n+1)::before {
            background: linear-gradient(90deg, #40E0D0, #0077be);
        }

        .group-card:nth-child(6n+2)::before {
            background: linear-gradient(90deg, #93E9BE, #40E0D0);
        }

        .group-card:nth-child(6n+3)::before {
            background: linear-gradient(90deg, #FFB347, #FF7B6B);
        }

        .group-card:nth-child(6n+4)::before {
            background: linear-gradient(90deg, #b48aff, #6A5ACD);
        }

        .group-card:nth-child(6n+5)::before {
            background: linear-gradient(90deg, #FF7B6B, #FFB347);
        }

        .group-card:nth-child(6n+6)::before {
            background: linear-gradient(90deg, #0077be, #93E9BE);
        }

        /* Card header */
        .group-card-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .group-card-icon {
            font-size: 1.3rem;
            line-height: 1;
        }

        .group-card-title {
            font-family: var(--fd);
            font-weight: 800;
            font-size: 0.95rem;
            color: var(--turq);
            line-height: 1.2;
        }

        .group-card-count {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 600;
            margin-left: auto;
            background: rgba(255, 255, 255, 0.07);
            padding: 2px 8px;
            border-radius: 50px;
        }

        /* Divider */
        .group-card-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.08);
            margin-bottom: 12px;
        }

        /* Student name list */
        .group-member-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .group-member {
            display: flex;
            align-items: center;
            gap: 7px;
            font-family: var(--fb);
            font-size: 0.8rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
            padding: 5px 8px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            transition: background 0.2s;
        }

        .group-member:hover {
            background: rgba(64, 224, 208, 0.1);
            color: #fff;
        }

        .group-member::before {
            content: '🐚';
            font-size: 0.7rem;
            flex-shrink: 0;
        }

        /* Captain badge (first member of each group) */
        .group-member.captain {
            background: rgba(64, 224, 208, 0.12);
            color: var(--turq);
            font-weight: 700;
        }

        .group-member.captain::before {
            content: '⚓';
        }

        /* ── Bottom decorative wave ───────────────────────────── */
        .rand-wave {
            margin-top: 0;
            line-height: 0;
        }

        .rand-wave svg {
            display: block;
            width: 100%;
        }

        /* ── Empty state ─────────────────────────────────────── */
        .rand-empty {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px 20px;
            color: rgba(255, 255, 255, 0.3);
            font-family: var(--fd);
            font-size: 1rem;
        }

        .rand-empty span {
            display: block;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        /* ── Responsive ──────────────────────────────────────── */
        @media (max-width: 768px) {
            .rand-center {
                flex-direction: column;
                gap: 28px;
                align-items: center;
            }

            .rand-form {
                width: 100%;
                max-width: 100%;
            }

            .rand-input-row {
                flex-direction: column;
            }

            .rand-input {
                width: 100%;
                font-size: 1.1rem;
            }

            .rand-btn {
                width: 100%;
                font-size: 1rem;
                padding: 14px;
            }

            /* Single column on mobile */
            .group-results {
                grid-template-columns: repeat(2, 1fr);
                gap: 14px;
            }

            .whirlpool-wrap {
                width: 160px;
                height: 160px;
            }

            .whirlpool-svg {
                width: 160px;
                height: 160px;
            }

            .wp-ring-1 {
                width: 180px;
                height: 180px;
            }

            .wp-ring-2 {
                width: 210px;
                height: 210px;
            }

            .wp-ring-3 {
                width: 240px;
                height: 240px;
            }
        }

        /* ============================================================
   GALLERY SECTION
============================================================ */
        #gallery {
            padding: 100px 0 90px;
            background: linear-gradient(180deg, var(--ocean-deep), var(--ocean-deep));
        }

        .phase-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-top: 42px;
        }

        @media(max-width:640px) {
            .phase-grid {
                grid-template-columns: 1fr;
                gap: 14px
            }
        }

        .phase-card {
            border-radius: 24px;
            padding: 38px 18px 28px;
            text-align: center;
            cursor: pointer;
            transition: all 0.35s;
            position: relative;
            overflow: hidden;
            color: #fff;
            font-family: var(--fd);
        }

        .phase-card:nth-child(1) {
            background: linear-gradient(145deg, var(--ocean-dk), #800080)
        }

        .phase-card:nth-child(2) {
            background: linear-gradient(145deg, #800080, #6A5ACD)
        }

        .phase-card:nth-child(3) {
            background: linear-gradient(145deg, #4B0082, #800080)
        }

        .phase-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.08);
            transition: background 0.3s;
        }

        .phase-card:hover::before {
            background: rgba(255, 255, 255, 0.15)
        }

        .phase-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.28)
        }

        .phase-card:active {
            transform: scale(0.97)
        }

        .pci {
            font-size: 2.6rem;
            margin-bottom: 10px;
            display: block
        }

        .pcl {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            opacity: 0.82
        }

        .pcn {
            font-size: 1.9rem;
            font-weight: 800;
            margin-top: 3px
        }

        .pcc {
            font-size: 0.76rem;
            opacity: 0.76;
            margin-top: 5px
        }

        .phase-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0
        }

        .phase-wave svg {
            display: block;
            width: 100%
        }

        /* ============================================================
   GALLERY FULL PAGE
============================================================ */
        #galleryPage {
            position: fixed;
            inset: 0;
            z-index: 2000;
            display: none;
            flex-direction: column;
            overflow: hidden;
            background: linear-gradient(180deg, #001a35 0%, #003060 40%, #00548a 80%, #007aa0 100%);
        }

        #galleryPage.open {
            display: flex
        }

        /* Caustic lights */
        .caustic {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            background: radial-gradient(circle, rgba(64, 224, 208, 0.1), transparent 70%);
            animation: causMove ease-in-out infinite alternate;
        }

        @keyframes causMove {
            from {
                transform: scale(1) translate(0, 0)
            }

            to {
                transform: scale(1.4) translate(18px, -18px)
            }
        }

        /* Gallery bubbles */
        .gbub {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            animation: bUp linear infinite;
        }

        .gp-top {
            position: relative;
            z-index: 10;
            flex-shrink: 0;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 18px 5%;
            gap: 12px;
        }

        .gp-title {
            font-family: var(--fd);
            font-size: clamp(1.2rem, 3vw, 1.5rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.2
        }

        .gp-title span {
            color: var(--turq)
        }

        .gp-sub {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 600;
            margin-top: 3px
        }

        .gp-close {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 1.1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
            flex-shrink: 0;
        }

        .gp-close:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: rotate(90deg)
        }

        .gp-hint {
            position: relative;
            z-index: 10;
            flex-shrink: 0;
            text-align: center;
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.76rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        /* Canvas = where photos float */
        .gp-canvas {
            position: relative;
            z-index: 10;
            flex: 1;
            min-height: 0;
        }

        /* Desktop (and mobile) – absolute positioned photos so they can be dragged */
        .gp-photo {
            border-radius: 8px;
            position: absolute;
            background: #fff;
            padding: 8px 8px 34px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.45);
            cursor: grab;
            user-select: none;
            /* touch-action prevents the browser from hijacking
       finger movements for scrolling/zooming while you drag */
            touch-action: none;
        }

        .gp-photo:active {
            cursor: grabbing
        }

        .gp-photo:hover {
            box-shadow: 0 18px 52px rgba(0, 0, 0, 0.6);
            z-index: 200 !important
        }

        .gp-photo-img {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            aspect-ratio: 4/3;
            pointer-events: none;
        }

        .gp-canvas img {
            /* make the image completely fill whatever box it's in */
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center -60px;
            pointer-events: none;
            user-select: none;
        }

        .gp-photo-cap {
            text-align: center;
            font-family: var(--fd);
            font-size: 0.62rem;
            font-weight: 700;
            color: #666;
            margin-top: 5px;
            pointer-events: none;
        }

        /* Mobile gallery: scroll grid */
        @media(max-width:768px) {
            .gp-photo-img {
                font-size: 1.8rem
            }

            .gp-canvas img {
                /* make the image completely fill whatever box it's in */
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center -60px;
                pointer-events: none;
                user-select: none;
            }
        }

        /* ============================================================
   CONTACT
============================================================ */
        #contact {
            padding: 90px 0;
            background: linear-gradient(180deg, var(--ocean-deep), var(--ocean-deep));
            text-align: center;
        }

        #contact .sec-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px)
        }

        #contact .sec-title {
            color: #fff
        }

        #contact .sec-desc {
            color: rgba(255, 255, 255, 0.82);
            margin: 0 auto
        }

        .bottle-btn {
            display: inline-flex;
            align-items: center;
            gap: 11px;
            background: var(--sandy);
            color: var(--ocean-dk);
            font-family: var(--fd);
            font-weight: 700;
            font-size: 1rem;
            padding: 13px 32px;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.22);
            transition: all 0.3s;
            margin-top: 26px;
            animation: botBob 3s ease-in-out infinite;
        }

        @keyframes botBob {

            0%,
            100% {
                transform: translateY(0) rotate(-3deg)
            }

            50% {
                transform: translateY(-10px) rotate(3deg)
            }
        }

        .bottle-btn:hover {
            animation: none;
            transform: translateY(-5px) scale(1.05) rotate(0);
            background: #fff;
            box-shadow: 0 18px 48px rgba(0, 0, 0, 0.28)
        }

        /* Modal */
        .cmodal {
            position: fixed;
            inset: 0;
            z-index: 3000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .cmodal.open {
            display: flex
        }

        .cmbg {
            position: absolute;
            inset: 0;
            background: rgba(0, 20, 60, 0.72);
            backdrop-filter: blur(8px);
        }

        .cmbox {
            position: relative;
            z-index: 1;
            background: #fff;
            border-radius: 26px;
            padding: 34px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.32);
        }

        @media(max-width:480px) {
            .cmbox {
                padding: 22px;
                border-radius: 18px
            }
        }

        .cm-close {
            position: absolute;
            top: 14px;
            right: 14px;
            background: #f0f0f0;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .cm-close:hover {
            background: #e0e0e0;
            transform: rotate(90deg)
        }

        .cm-title {
            font-family: var(--fd);
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--ocean);
            margin-bottom: 3px
        }

        .cm-sub {
            font-size: 0.83rem;
            color: #aaa;
            margin-bottom: 22px
        }

        .fg {
            margin-bottom: 14px
        }

        .fg label {
            display: block;
            font-weight: 700;
            font-size: 0.8rem;
            color: var(--deep);
            margin-bottom: 4px
        }

        .fg input,
        .fg textarea {
            width: 100%;
            padding: 10px 13px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-family: var(--fb);
            font-size: 0.88rem;
            color: var(--deep);
            outline: none;
            resize: none;
            transition: border 0.3s;
        }

        .fg input:focus,
        .fg textarea:focus {
            border-color: var(--ocean)
        }

        .form-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, var(--ocean), var(--turq-dk));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: var(--fd);
            font-weight: 700;
            font-size: 0.96rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .form-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(0, 119, 190, 0.35)
        }

        /* ============================================================
   FOOTER
============================================================ */
        footer {
            background: var(--ocean-deep);
            padding: 0 5% 22px;
            text-align: center
        }

        .fw {
            margin-bottom: 0
        }

        .fw svg {
            display: block;
            width: 100%
        }

        .fl {
            font-family: var(--fd);
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--turq);
            margin-bottom: 5px
        }

        .fc {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 16px
        }

        .socials {
            display: flex;
            justify-content: center;
            gap: 10px
        }

        .soc {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 37px;
            height: 37px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s;
            animation: sBob 2.2s ease-in-out infinite;
        }

        .soc:nth-child(1) {
            animation-delay: 0s
        }

        .soc:nth-child(2) {
            animation-delay: 0.25s
        }

        .soc:nth-child(3) {
            animation-delay: 0.5s
        }

        .soc:nth-child(4) {
            animation-delay: 0.75s
        }

        @keyframes sBob {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-6px)
            }
        }

        .soc:hover {
            background: var(--ocean);
            animation: none;
            transform: translateY(-4px) scale(1.15)
        }

        /* ============================================================
   SCROLL REVEAL HELPER
============================================================ */
        .rv {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.65s ease, transform 0.65s ease
        }

        .rv.on {
            opacity: 1;
            transform: none
        }

        /* ── TAMBAHAN: Login Button di Navbar ── */
        .nav-login-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--sandy);
            color: var(--ocean-dk);
            font-family: var(--fd);
            font-weight: 700;
            font-size: 0.88rem;
            padding: 7px 20px;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }

        .nav-login-btn:hover {
            background: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.18);
        }

        /* ── Login Modal ── */
        .login-modal {
            position: fixed;
            inset: 0;
            z-index: 5000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-modal.open {
            display: flex;
        }

        .lmbg {
            position: absolute;
            inset: 0;
            background: rgba(0, 20, 60, 0.75);
            backdrop-filter: blur(10px);
        }

        .lmbox {
            position: relative;
            z-index: 1;
            background: #fff;
            border-radius: 28px;
            padding: 38px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35);
            animation: lmIn 0.4s ease;
        }

        @keyframes lmIn {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.94);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .lm-brand {
            text-align: center;
            font-family: var(--fd);
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--ocean);
            margin-bottom: 4px;
        }

        .lm-sub {
            text-align: center;
            font-size: 0.83rem;
            color: #aaa;
            margin-bottom: 26px;
        }

        .lm-close {
            position: absolute;
            top: 14px;
            right: 14px;
            background: #f0f0f0;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .lm-close:hover {
            background: #e0e0e0;
            transform: rotate(90deg);
        }

        .lm-group {
            margin-bottom: 16px;
        }

        .lm-group label {
            display: block;
            font-weight: 700;
            font-size: 0.8rem;
            color: var(--deep);
            margin-bottom: 5px;
        }

        .lm-group input {
            width: 100%;
            padding: 11px 14px;
            border: 2px solid #eee;
            border-radius: 12px;
            font-family: var(--fb);
            font-size: 0.9rem;
            color: var(--deep);
            outline: none;
            transition: border 0.3s;
        }

        .lm-group input:focus {
            border-color: var(--ocean);
        }

        .lm-err {
            background: #fff0f0;
            border: 1px solid #ffcdd2;
            color: #c0392b;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 14px;
            display: none;
        }

        .lm-err.show {
            display: block;
        }

        .lm-btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(90deg, var(--ocean), var(--turq-dk));
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: var(--fd);
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .lm-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(0, 119, 190, 0.35);
        }

        .lm-btn:active {
            transform: scale(0.98);
        }
    </style>
</head>

<body>

    {{-- DIVE LAYERS (tidak berubah) --}}
    <div id="diveVignette"></div>
    <div id="diveBlue"></div>
    <div id="diveBubbles"></div>

    {{-- ====== NAVBAR (DIMODIFIKASI: Login + Dashboard) ====== --}}
    <nav id="navbar">
        <a href="#hero" class="nav-brand"><span class="anchor">⚓</span> X <span>PPLG</span> C</a>
        <div style="display:flex; gap:8px; align-items:center;">
            @if(session('user_id'))
            <a href="{{ route('dashboard') }}" class="nav-login-btn" style="background:var(--turq); color:#003060;">📊 Dashboard</a>
            @endif
            <button class="nav-login-btn" onclick="openLogin()">🔑 Login</button>
        </div>
    </nav>

    {{-- SEMUA SECTION HERO, ABOUT, RANDOMIZER, GALLERY, CONTACT, FOOTER --}}
    {{-- Paste seluruh HTML dari homescreen kamu di sini TANPA PERUBAHAN --}}
    <section id="hero">
        <div class="hero-bg" id="heroBg"></div>
        <div class="hero-sun"></div>

        <!-- Officers scattered behind hero text (desktop: absolute, mobile: grid below) -->
        <div class="officers-wrap" id="officersWrap"></div>

        <div class="hero-center" id="heroCenter">
            <div class="hero-tag">🌊 SMKN 1 PADAHERANG 🌊</div>
            <h1 class="hero-title">X <span>PPLG</span> C</h1>
            <p class="hero-sub">Pengembangan Perangkat Lunak dan Gim</p>
            <a href="#about" class="hero-cta">🏄 Explore Website</a>
        </div>
        <!-- Hero text — z-index:10, on top of polaroids -->

        <!-- Shore wave -->
        <div class="hero-shore">
            <svg viewBox="0 0 1440 90" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M0,45 C200,90 400,10 600,52 C800,90 1000,15 1200,45 C1320,62 1390,28 1440,45 L1440,90 L0,90Z"
                    fill="rgba(0,119,190,0.6)" />
                <path d="M0,62 C280,30 520,82 760,55 C960,32 1200,75 1440,60 L1440,90 L0,90Z" fill="rgba(0,119,190,0.6)" />
            </svg>
        </div>
    </section>

    <!-- ======================================================
     ABOUT
====================================================== -->
    <section id="about">
        <div class="sec-inner">
            <div class="sec-badge">🐠 About Us</div>
            <h2 class="sec-title">About <span>Our Class</span></h2>
            <p class="sec-desc">Kami adalah kelas X PPLG C — sekelompok developer muda yang siap membuat gebrakan di dunia
                teknologi dan gim!</p>

            <div class="id-grid">
                <div class="id-card rv">
                    <div class="id-icon">🏫</div>
                    <div class="id-lbl">Kelas</div>
                    <div class="id-val">X PPLG C</div>
                </div>
                <div class="id-card rv">
                    <div class="id-icon">💻</div>
                    <div class="id-lbl">Jurusan</div>
                    <div class="id-val">PPLG</div>
                </div>
                <div class="id-card rv">
                    <div class="id-icon">👩‍💻</div>
                    <div class="id-lbl">Jumlah Siswa</div>
                    <div class="id-val">35 Siswa</div>
                </div>
                <div class="id-card rv">
                    <div class="id-icon">👩‍🏫</div>
                    <div class="id-lbl">Wali Kelas</div>
                    <div class="id-val">Bp Patah Yasin</div>
                </div>
            </div>

            <div class="sch-grid">
                <!-- Live clock + lesson -->
                <div class="sch-card rv">
                    <div class="sch-title">⏰ Jadwal Sekarang</div>
                    <div class="live-clock" id="liveClock">--:--:--</div>
                    <div class="live-day" id="liveDay">---</div>
                    <div class="lbox" id="lessonNow">
                        <div class="ll">📚 Sekarang</div>
                        <div class="ln" id="nowName">Memuat…</div>
                        <div class="lt" id="nowTime"></div>
                    </div>
                    <div class="lbox nxt" id="lessonNext">
                        <div class="ll">⏭ Selanjutnya</div>
                        <div class="ln" id="nextName">Memuat…</div>
                        <div class="lt" id="nextTime"></div>
                    </div>
                </div>
                <!-- Piket + full schedule -->
                <div class="sch-card rv">
                    <div class="sch-title">🧹 Piket Hari Ini</div>
                    <div class="piket-badge" id="piketDay">---</div>
                    <ul class="piket-list" id="piketList"></ul>
                    <div style="margin-top:18px">
                        <div class="sch-title">📋 Jadwal Lengkap</div>
                        <div class="full-sch" id="fullSch"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================================================
     LOKASI SEKOLAH — Leaflet Map  [FIXED]
============================================================ -->
<section id="lokasi">
    <div class="sec-inner">
        <div class="sec-badge">📍 Lokasi Kami</div>
        <h2 class="sec-title">Temukan <span>Kami</span></h2>
        <p class="sec-desc">SMKN 1 Padaherang — Jl. Raya Padaherang Km.1, Desa Karangsari, Kab. Pangandaran, Jawa Barat.</p>

        <!-- .map-outer: scroll-reveal wrapper, hosts the glow ring OUTSIDE overflow:hidden -->
        <div class="map-outer rv">

            <!-- Glow ring — lives here so it's NOT clipped by .map-wrap's overflow:hidden -->
            <div class="map-glow-ring"></div>

            <!-- .map-wrap: overflow:hidden clips Leaflet tiles to rounded corners -->
            <div class="map-wrap">

                <!-- Info card overlay (bottom-left) -->
                <div class="map-info-card">
                    <div class="mic-icon">🏫</div>
                    <div class="mic-body">
                        <div class="mic-title">SMKN 1 Padaherang</div>
                        <div class="mic-sub">Kab. Pangandaran, Jawa Barat</div>
                        <!-- FIXED: correct coordinates in Google Maps link -->
                        <a class="mic-link"
                           href="https://maps.google.com/?q=-7.5565,108.6945"
                           target="_blank" rel="noopener">
                            Buka di Google Maps ↗
                        </a>
                    </div>
                </div>

                <!-- Leaflet map container -->
                <div id="leafletMap"></div>

            </div><!-- /.map-wrap -->
        </div><!-- /.map-outer -->
    </div>
</section>


    <!-- ============================================================
     WHIRLPOOL RANDOMIZER — HTML SNIPPET
     Letakkan section ini SETELAH section #about dan SEBELUM section #gallery
     ============================================================ -->

    <section id="randomizer">
        <div class="sec-inner">

            <!-- Header -->
            <div class="sec-badge" style="background:rgba(255,255,255,0.15)">🌪️ Pusaran Air Pengacak</div>
            <h2 class="sec-title" style="color:#fff">Sistem Acak <span style="color:var(--turq)">Kelompok</span></h2>
            <p class="rand-desc">Masukkan jumlah kelompok, lalu buat pusaran air nama-nama akan tersedot dan dibagi secara
                acak dan adil! <i><strong>(ketua kelompok ditandai emoji "⚓️")</strong></i></p>

            <!-- Whirlpool visual + form -->
            <div class="rand-center">

                <!-- SVG Whirlpool -->
                <div class="whirlpool-wrap">
                    <svg id="whirlpoolSVG" class="whirlpool-svg" viewBox="0 0 220 220" xmlns="http://www.w3.org/2000/svg">
                        <!-- Outer rings -->
                        <circle cx="110" cy="110" r="100" fill="none" stroke="rgba(64,224,208,0.18)" stroke-width="2" />
                        <circle cx="110" cy="110" r="80" fill="none" stroke="rgba(64,224,208,0.25)" stroke-width="2" />
                        <circle cx="110" cy="110" r="60" fill="none" stroke="rgba(64,224,208,0.35)" stroke-width="2" />
                        <circle cx="110" cy="110" r="40" fill="none" stroke="rgba(64,224,208,0.5)" stroke-width="3" />
                        <circle cx="110" cy="110" r="20" fill="none" stroke="rgba(64,224,208,0.7)" stroke-width="3" />
                        <!-- Spiral arms (path arcs that suggest rotation) -->
                        <g id="spiralArms" stroke="var(--turq)" stroke-linecap="round" fill="none" opacity="0.7">
                            <path d="M110,10  Q180,40  180,110" stroke-width="3" />
                            <path d="M210,110 Q180,180 110,180" stroke-width="2.5" />
                            <path d="M110,210 Q40,180  40,110" stroke-width="2" />
                            <path d="M10,110  Q40,40   110,40" stroke-width="1.5" />
                        </g>
                        <!-- Inner dot -->
                        <circle cx="110" cy="110" r="10" fill="var(--turq)" opacity="0.8" />
                        <circle cx="110" cy="110" r="5" fill="#fff" />
                        <!-- Floating mini bubbles around whirlpool -->
                        <circle cx="55" cy="65" r="5" fill="rgba(255,255,255,0.4)" />
                        <circle cx="170" cy="80" r="4" fill="rgba(255,255,255,0.3)" />
                        <circle cx="160" cy="160" r="6" fill="rgba(255,255,255,0.3)" />
                        <circle cx="55" cy="155" r="4" fill="rgba(255,255,255,0.35)" />
                    </svg>
                    <!-- Decorative waves around whirlpool -->
                    <div class="wp-ring wp-ring-1"></div>
                    <div class="wp-ring wp-ring-2"></div>
                    <div class="wp-ring wp-ring-3"></div>
                </div>

                <!-- Input form -->
                <div class="rand-form">
                    <label class="rand-label" for="groupCount">
                        Mau dibagi jadi berapa kelompok?
                    </label>
                    <div class="rand-input-row">
                        <input type="number" id="groupCount" class="rand-input" min="2" max="10" placeholder="2 – 10" value="5" />
                        <button class="rand-btn" id="randBtn" onclick="runRandomizer()">
                            🌪️ Buat Pusaran Air
                        </button>
                    </div>
                    <p class="rand-note">Min 2 kelompok · Max 10 kelompok · Total 35 siswa</p>
                </div>
            </div>

            <!-- Results grid (populated by JS) -->
            <div id="groupResults" class="group-results"></div>

        </div>

        <!-- Decorative bottom wave -->
        <div class="rand-wave">
            <svg viewBox="0 0 1440 60" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            </svg>
        </div>
    </section>

    <!-- ======================================================
     GALLERY
====================================================== -->
    <section id="gallery">
        <div class="sec-inner">
            <div class="sec-badge">🪸 Our Memory's</div>
            <h2 class="sec-title">Our <span class="sec-title-gallery">Gallery</span></h2>
            <p class="sec-desc">Buka peti kenangan dari setiap fase. Seret & geser foto seperti ombak yang membawa kenangan ke
                tepi pantai.</p>

            <div class="phase-grid">
                <!-- Phase X -->
                <div class="phase-card rv" onclick="openGallery('X')">
                    <span class="pci">🐣</span>
                    <div class="pcl">Phase</div>
                    <div class="pcn">X</div>
                    <div class="pcc">7 Kenangan</div>
                    <div class="phase-wave">
                        <svg viewBox="0 0 300 36" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                            <path d="M0,18 C60,36 100,5 160,18 C210,30 260,8 300,18 L300,36 L0,36Z" fill="rgba(255,255,255,0.3)" />
                        </svg>
                    </div>
                </div>
                <!-- Phase XI -->
                <div class="phase-card rv" onclick="">
                    <span class="pci">🌱</span>
                    <div class="pcl">Phase</div>
                    <div class="pcn">XI</div>
                    <div class="pcc">COMMING SOON</div>
                    <div class="phase-wave">
                        <svg viewBox="0 0 300 36" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                            <path d="M0,12 C70,32 130,4 190,20 C230,32 265,10 300,18 L300,36 L0,36Z" fill="rgba(255,255,255,0.3)" />
                        </svg>
                    </div>
                </div>
                <!-- Phase XII -->
                <div class="phase-card rv" onclick="">
                    <span class="pci">🎓️</span>
                    <div class="pcl">Phase</div>
                    <div class="pcn">XII</div>
                    <div class="pcc">COMMING SOON</div>
                    <div class="phase-wave">
                        <svg viewBox="0 0 300 36" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                            <path d="M0,22 C80,6 140,34 210,14 C255,4 280,28 300,18 L300,36 L0,36Z" fill="rgba(255,255,255,0.3)" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ======================================================
     GALLERY FULL PAGE
====================================================== -->
    <div id="galleryPage">
        <!-- Caustics + bubbles injected by JS -->
        <div class="gp-top">
            <div>
                <div class="gp-title">Kelas <span id="gpPhase"></span> — <span style="color:var(--seafoam)">Our Memory's</span>
                </div>
                <div class="gp-sub" id="gpSub">🖐 Drag fotonya untuk menyusun memory</div>
            </div>
            <button class="gp-close" onclick="closeGallery()">✕</button>
        </div>
        <div class="gp-hint">🌊 Memory yang akan terus mengambang dipikiran</div>
        <div class="gp-canvas" id="gpCanvas"></div>
    </div>

    <!-- ======================================================
     CONTACT
====================================================== -->
    <section id="contact">
        <div class="sec-inner">
            <div class="sec-badge" style="background:rgba(255,255,255,0.2)">🍶 Message in a Bottle</div>
            <h2 class="sec-title">Hi, Im <span style="color:var(--turq)">Ridho!</span> developer of this website :D</h2>
            <p class="sec-desc" style="color:rgba(255,255,255,0.82);margin:8px auto 0">
                Ingin kerja sama atau memberi pesan? Tulis & lempar botolnya ke ombak — Saya akan menunggunya di tepi pantai 🏖️
            </p>
            <div><button class="bottle-btn" onclick="openContact()">🍾 Kirim Pesan di Botol</button></div>
        </div>
    </section>

    <!-- Contact Modal -->
    <div class="cmodal" id="cmodal">
        <div class="cmbg" onclick="closeContact()"></div>
        <div class="cmbox">
            <button class="cm-close" onclick="closeContact()">✕</button>
            <form action="https://formspree.io/f/xpqjyzpw" method="POST">
                <div class="cm-title">🍾 Drop a Message!</div>
                <div class="cm-sub">Tulis pesanmu, kami siap membalasnya segera~</div>
                <div class="fg"><label>Nama : </label><input type="text" placeholder="e.g. Budi Santoso…" name="name"></div>
                <div class="fg"><label>Email : </label><input type="email" placeholder="hello@example.com" name="email"></div>
                <div class="fg"><label>Pesan : </label><textarea rows="4" placeholder="Tuliskan pesanmu di sini…"
                        name="message"></textarea></div>
                <button class="form-btn" id="sendBtn" onclick="submitForm()" type="submit">🌊 Lempar surat ke Laut!</button>
            </form>
        </div>
    </div>

    <!-- ======================================================
     FOOTER
====================================================== -->
    <footer>
        <div class="fw">
            <svg viewBox="0 0 1440 65" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M0,32 C240,65 480,5 720,32 C960,60 1200,10 1440,32 L1440,65 L0,65Z" fill="#001f3f" />
            </svg>
        </div>
        <div style="padding-top:8px">
            <div class="fl">⚓ X PPLG C</div>
            <div class="fc">© 2026 Idooo2202 · Coastal Edition · All rights reserved</div>
            <div class="socials">
                <a href="https://instagram.com/xpplg.c_" class="soc" title="Instagram">📸</a>
                <a href="https://github.com/Idooo2202" class="soc" title="GitHub">💻</a>
                <a href="#" class="soc" title="YouTube">🎬</a>
                <a href="#" class="soc" title="TikTok">🎵</a>
            </div>
        </div>
    </footer>

    {{-- (mulai dari <section id="hero"> sampai </footer>) --}}

    {{-- ====== LOGIN MODAL ====== --}}
    <div class="login-modal" id="loginModal">
        <div class="lmbg" onclick="closeLogin()"></div>
        <div class="lmbox">
            <button class="lm-close" onclick="closeLogin()">✕</button>
            <div class="lm-brand">⚓ X PPLG C</div>
            <div class="lm-sub">Masuk ke dashboard kelasmu</div>

            {{-- Tampilkan error jika login gagal --}}
            @if(session('error'))
            <div class="lm-err show">{{ session('error') }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf
                <div class="lm-group">
                    <label>👤 Username</label>
                    <input type="text" name="username" placeholder="Masukkan username..."
                        value="{{ old('username') }}" required autocomplete="username">
                </div>
                <div class="lm-group">
                    <label>🔒 Password</label>
                    <input type="password" name="password" placeholder="Masukkan password..."
                        required autocomplete="current-password">
                </div>
                <button type="submit" class="lm-btn">🌊 Masuk ke Dashboard</button>
            </form>
        </div>
    </div>

    {{-- Data attribute untuk session error --}}
    @if(session('error'))
    <script>
        window.__sessionError = true;
    </script>
    @endif

    {{-- SEMUA JS DARI HOMESCREEN KAMU (PASTE DI SINI, TIDAK BERUBAH) --}}
    <script>
        // ─── LOGIN MODAL JS ──────────────────────────────
        function openLogin() {
            document.getElementById('loginModal').classList.add('open');
            // Fokus ke input username
            setTimeout(() => {
                document.querySelector('#loginModal input[name="username"]')?.focus();
            }, 200);
        }

        function closeLogin() {
            document.getElementById('loginModal').classList.remove('open');
        }

        // Jika ada error session → buka modal otomatis
        if (window.__sessionError) {
            document.addEventListener('DOMContentLoaded', () => openLogin());
        }

        // ESC tutup modal login
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeLogin();
        });
        gsap.registerPlugin(ScrollTrigger, Draggable);

        /* ─── DATA: OFFICERS ────────────────────────────────────
           Edit name, role, emoji per officer.
           To use photos: replace emoji with <img src="..."> markup.
        */
        const OFFICERS = [{
                name: 'Alfino',
                role: 'Ketua Kelas',
                emoji: '<img src="img/STRUKTUR/km.png" alt="Foto">'
            },
            {
                name: 'Bp Patah Yasin',
                role: 'Wali Kelas',
                emoji: '<img src="img/STRUKTUR/walikelas.png" alt="Foto">'
            },
            {
                name: 'Fitria',
                role: 'Wakil KM',
                emoji: '<img src="img/STRUKTUR/wakil_km.png" alt="Foto">'
            },
            {
                name: 'Regita',
                role: 'Sekretaris I',
                emoji: '<img src="img/STRUKTUR/sek1.png" alt="Foto">'
            },
            {
                name: 'Kiran',
                role: 'Sekretaris II',
                emoji: '<img src="img/STRUKTUR/sek2.png" alt="Foto">'
            },
            {
                name: 'Nabila',
                role: 'Bendahara I',
                emoji: '<img src="img/STRUKTUR/bendahara1.png" alt="Foto">'
            },
            {
                name: 'Zaskya',
                role: 'Bendahara II',
                emoji: '<img src="img/STRUKTUR/bendahara2.png" alt="Foto">'
            },
            {
                name: 'Windi',
                role: 'Seksi Olahraga I',
                emoji: '<img src="img/STRUKTUR/olahraga1.png" alt="Foto">'
            },
            {
                name: 'Zein',
                role: 'Seksi Olahraga II',
                emoji: '<img src="img/STRUKTUR/olahraga2.png" alt="Foto">'
            },
            {
                name: 'Wulan',
                role: 'Seksi Kebersihan I',
                emoji: '<img src="img/STRUKTUR/kebersihan1.png" alt="Foto">'
            },
            {
                name: 'Sri',
                role: 'Seksi Kebersihan II',
                emoji: '<img src="img/STRUKTUR/kebersihan2.png" alt="Foto">'
            },
            {
                name: 'Evita',
                role: 'Seksi Kemanan I',
                emoji: '<img src="img/STRUKTUR/keamanan1.png" alt="Foto">'
            },
            {
                name: 'Renita',
                role: 'Seksi Kemanan II',
                emoji: '<img src="img/STRUKTUR/keamanan2.png" alt="Foto">'
            },
            {
                name: 'Meli',
                role: 'Seksi Humas I',
                emoji: '<img src="img/STRUKTUR/humas1.png" alt="Foto">'
            },
            {
                name: 'Amelia',
                role: 'Seksi Humas II',
                emoji: '<img src="img/STRUKTUR/humas2.png" alt="Foto">'
            },
        ];

        /* Desktop scatter positions [top%, left%, rotation°]
           Left column: left 1–14%   |   Right column: left 76–90%
           This keeps the center zone (roughly 20–75%) clear for the hero text.
        */
        const SCATTER = [
            ['67%', '52%', 6],
            ['12%', '46.7%', 0],
            ['67%', '42%', -6],
            ['18%', '30%', 8],
            ['18%', '65%', -7],
            ['36%', '12%', -8],
            ['36%', '78%', 5],
            ['65%', '15%', -6],
            ['65%', '86%', 10],
            ['10%', '6%', 6],
            ['50%', '66%', 5],
            ['10%', '18%', -10],
            ['10%', '76%', 10],
            ['50%', '26%', -4],
            ['10%', '86%', -5],
        ];

        /* ─── DATA: PIKET ──────────────────────────────────────
           key = getDay() value: 1=Mon … 5=Fri
        */
        const PIKET = {
            1: ['Rido Ganteng', 'Evita', 'Asroh', 'Aisyah', 'Alfino', 'Risha', 'Yunisa', 'Nayla'],
            2: ['Arda', 'Amel', 'Rezza', 'Dea', 'Rafi', 'Cantika', 'Zaskya', 'Regita', 'Keyina'],
            3: ['Farhan', 'Kiran', 'Kustian', 'Nabila', 'Renita', 'Wulan', 'Windi', 'Meli'],
            4: ['EKOLOGI'],
            5: ['Ahyar', 'Zein', 'Faris', 'Early', 'Vina', 'Fitria', 'Mila', 'Livia', 'Fauzan'],
        };

        /* ─── DATA: LESSON SCHEDULE ────────────────────────────
           Adjust lesson names & times to match your school.
           isBreak:true = styled as break (no lesson content)
        */
        const LESSONS = {
            /* SENIN */
            1: [{
                    name: 'Upacara',
                    start: '06:30',
                    end: '07:15'
                },
                {
                    name: 'PJOK',
                    start: '07:15',
                    end: '09:30'
                },
                {
                    name: '☕ Istirahat I',
                    start: '09:30',
                    end: '09:45',
                    isBreak: true
                },
                {
                    name: 'Matematika',
                    start: '09:45',
                    end: '10:30'
                },
                {
                    name: 'Mulok 1',
                    start: '10:30',
                    end: '12:00'
                },
                {
                    name: '☕ Istirahat II',
                    start: '12:00',
                    end: '12:45',
                    isBreak: true
                },
                {
                    name: 'Mulok 1',
                    start: '12:45',
                    end: '13:30'
                },
                {
                    name: 'DPK(Pa Iip)',
                    start: '13:30',
                    end: '15:00'
                },
            ],
            /* SELASA */
            2: [{
                    name: 'WK/BK/PB',
                    start: '06:30',
                    end: '07:15'
                },
                {
                    name: 'Bahasa Indonesia',
                    start: '07:15',
                    end: '08:45'
                },
                {
                    name: 'PAIBP',
                    start: '08:45',
                    end: '09:00'
                },
                {
                    name: '☕ Istirahat I',
                    start: '09:30',
                    end: '09:45',
                    isBreak: true
                },
                {
                    name: 'PAIBP',
                    start: '09:45',
                    end: '10:30'
                },
                {
                    name: 'PIPAS',
                    start: '10:30',
                    end: '12:00'
                },
                {
                    name: '☕ Istirahat II',
                    start: '12:00',
                    end: '12:45',
                    isBreak: true
                },
                {
                    name: 'PIPAS',
                    start: '12:45',
                    end: '13:30'
                },
                {
                    name: 'Bahasa Inggris',
                    start: '13:30',
                    end: '15:00'
                },
            ],
            /* RABU */
            3: [{
                    name: 'Matematika',
                    start: '06:30',
                    end: '08:00'
                },
                {
                    name: 'DPK (Pa Iip)',
                    start: '08:00',
                    end: '09:30'
                },
                {
                    name: '☕ Istirahat I',
                    start: '09:30',
                    end: '09:45',
                    isBreak: true
                },
                {
                    name: 'DPK (Bu Yeni)',
                    start: '09:45',
                    end: '12:00'
                },
                {
                    name: '☕ Istirahat II',
                    start: '12:00',
                    end: '12:45',
                    isBreak: true
                },
                {
                    name: 'DPK (Bu Yeni)',
                    start: '12:45',
                    end: '13:30'
                },
                {
                    name: 'Bahasa Inggris',
                    start: '13:30',
                    end: '15:00'
                },
            ],
            /* KAMIS */
            4: [{
                    name: 'DPK (Pa Aldhi)',
                    start: '06:30',
                    end: '09:30'
                },
                {
                    name: '☕ Istirahat I',
                    start: '09:30',
                    end: '09:45',
                    isBreak: true
                },
                {
                    name: 'Seni Budaya',
                    start: '09:45',
                    end: '11:15'
                },
                {
                    name: 'Pendidikan Pancasila(PP)',
                    start: '11:15',
                    end: '12:00'
                },
                {
                    name: '☕ Istirahat II',
                    start: '12:00',
                    end: '12:45',
                    isBreak: true
                },
                {
                    name: 'Pendidikan Pancasila(PP)',
                    start: '12:45',
                    end: '13:30'
                },
                {
                    name: 'Informatika',
                    start: '13:30',
                    end: '15:00'
                },
            ],
            /* JUMAT */
            5: [{
                    name: 'Duha',
                    start: '06:30',
                    end: '07:15'
                },
                {
                    name: 'Sejarah',
                    start: '07:15',
                    end: '08:45'
                },
                {
                    name: 'Informatika',
                    start: '08:45',
                    end: '09:00'
                },
                {
                    name: '☕ Istirahat I',
                    start: '09:30',
                    end: '09:45',
                    isBreak: true
                },
                {
                    name: 'Informatika',
                    start: '09:45',
                    end: '10:30'
                },
                {
                    name: 'PIPAS',
                    start: '10:30',
                    end: '12:00'
                },
                {
                    name: '🕌 Jum\'atan / ☕ Istirahat II',
                    start: '12:00',
                    end: '12:45',
                    isBreak: true
                },
                {
                    name: 'PIPAS',
                    start: '12:45',
                    end: '13:30'
                },
                {
                    name: 'Bahasa Indonesia',
                    start: '13:30',
                    end: '15:00'
                },
            ],
        };

        /* ─── DATA: GALLERY PHOTOS ─────────────────────────────
           Replace emoji with actual img URLs when ready.
           bg: [gradient start, gradient end]
        */
        const GALLERY_DATA = {
            X: [{
                    emoji: '<img src="img/X/foto1.png" alt="Foto">',
                    cap: 'Hari Pertama Sekolah',
                    bg: ['#0077be', '#40E0D0']
                },
                {
                    emoji: '<img src="img/X/foto2.png" alt="Foto">',
                    cap: 'MPLS Seru',
                    bg: ['#005a8e', '#2bb8a9']
                },
                {
                    emoji: '<img src="img/X/foto3.png" alt="Foto">',
                    cap: 'Momen Seru',
                    bg: ['#40E0D0', '#93E9BE']
                },
                {
                    emoji: '<img src="img/X/foto4.png" alt="Foto">',
                    cap: 'Fotbar Sekelas',
                    bg: ['#2bb8a9', '#0077be']
                },
                {
                    emoji: '<img src="img/X/foto5.png" alt="Foto">',
                    cap: 'Fotbar cewe',
                    bg: ['#93E9BE', '#005a8e']
                },
                {
                    emoji: '<img src="img/X/foto6.png" alt="Foto">',
                    cap: 'yalil yalili',
                    bg: ['#FF7B6B', '#FFB347']
                },
                {
                    emoji: '<img src="img/X/foto7.png" alt="Foto">',
                    cap: 'yalil yalili',
                    bg: ['#FFB347', '#FF7B6B']
                },
            ],
            XI: [{
                    emoji: '<img src="img/XI/" alt="Foto">',
                    cap: 'Praktik Industri',
                    bg: ['#2bb8a9', '#005f54']
                },
                {
                    emoji: '<img src="img/XI/" alt="Foto">',
                    cap: 'Game Dev Project',
                    bg: ['#0077be', '#40E0D0']
                },
                {
                    emoji: '<img src="img/XI/" alt="Foto">',
                    cap: 'Camping Pramuka',
                    bg: ['#93E9BE', '#2bb8a9']
                },
                {
                    emoji: '<img src="img/XI/" alt="Foto">',
                    cap: 'App Launch!',
                    bg: ['#40E0D0', '#0077be']
                },
                {
                    emoji: '<img src="img/XI/" alt="Foto">',
                    cap: 'Juara Hackathon',
                    bg: ['#FF7B6B', '#FF4040']
                },
                {
                    emoji: '<img src="img/XI/" alt="Foto">',
                    cap: 'Pentas Seni',
                    bg: ['#005f54', '#2bb8a9']
                },
                {
                    emoji: '<img src="img/XI/" alt="Foto">',
                    cap: 'Library Day',
                    bg: ['#005a8e', '#0077be']
                },
            ],
            XII: [{
                    emoji: '<img src="img/XII/" alt="Foto">',
                    cap: 'Proyek Akhir',
                    bg: ['#0077be', '#004080']
                },
                {
                    emoji: '<img src="img/XII/" alt="Foto">',
                    cap: 'Persiapan Wisuda',
                    bg: ['#FFB347', '#FF7B6B']
                },
                {
                    emoji: '<img src="img/XII/" alt="Foto">',
                    cap: 'Hari Terakhir Bareng',
                    bg: ['#40E0D0', '#005a8e']
                },
                {
                    emoji: '<img src="img/XII/" alt="Foto">',
                    cap: 'Ujian Kompetensi',
                    bg: ['#2bb8a9', '#40E0D0']
                },
                {
                    emoji: '<img src="img/XII/" alt="Foto">',
                    cap: 'Masa Depan Cerah',
                    bg: ['#FFB347', '#FF8C00']
                },
                {
                    emoji: '<img src="img/XII/" alt="Foto">',
                    cap: 'Alumni Gathering',
                    bg: ['#93E9BE', '#0077be']
                },
                {
                    emoji: '<img src="img/XII/" alt="Foto">',
                    cap: 'Kenangan Indah',
                    bg: ['#FF7B6B', '#2bb8a9']
                },
            ],
        };

        /* ─── HELPERS ────────────────────────────────────────── */
        const DAYS = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const pad = n => String(n).padStart(2, '0');
        const t2m = t => {
            const [h, m] = t.split(':').map(Number);
            return h * 60 + m;
        };

        function getWIB() {
            const now = new Date();
            return new Date(now.getTime() + now.getTimezoneOffset() * 60000 + 7 * 3600000);
        }
        const isMob = () => window.innerWidth <= 768;

        /* ─── BUBBLES FACTORY ────────────────────────────────── */
        function makeBubbles(parent, count, cls = 'bubble') {
            for (let i = 0; i < count; i++) {
                const b = document.createElement('div');
                b.className = cls;
                const sz = Math.random() * 34 + 7;
                b.style.cssText = `
      width:${sz}px;height:${sz}px;
      left:${Math.random() * 100}%;
      bottom:${Math.random() * 25}%;
      animation-duration:${Math.random() * 9 + 5}s;
      animation-delay:${-Math.random() * 9}s;
      opacity:${Math.random() * 0.4 + 0.08};
    `;
                parent.appendChild(b);
            }
        }

        /* ─── HERO SETUP ─────────────────────────────────────── */
        const heroBg = document.getElementById('heroBg');
        makeBubbles(heroBg, 15);

        // Seagulls
        ['☁️', '🐦', '🕊️', '☁️', '🐦', '☁️', '🕊️', '☁️', '☁️', '☁️'].forEach((g, i) => {
            const el = document.createElement('div');
            el.className = 'gull';
            el.textContent = g;
            el.style.cssText = `top:${14 + i * 11}%;animation-duration:${5 + i * 3}s;animation-delay:${-i * 6}s`;
            heroBg.appendChild(el);
        });

        /* ─── OFFICERS ───────────────────────────────────────── */
        const owrap = document.getElementById('officersWrap');
        const polaroids = [];
        const onMobile = window.innerWidth <= 768;

        OFFICERS.forEach((o, i) => {
            const p = SCATTER[i] ?? [`${10 + i * 9}%`, `${4 + i * 10}%`, i % 2 === 0 ? 6 : -6];
            const div = document.createElement('div');
            div.className = 'polaroid';
            div.innerHTML = `<div class="pol-img">${o.emoji}</div>
    <div class="pol-name">${o.name}<br><span class="pol-role">${o.role}</span></div>`;

            // always apply a base rotation so photos appear tilted on mobile too
            div.style.transform = `rotate(${p[2]}deg)`;

            div.addEventListener('mouseenter', () =>
                gsap.to(div, {
                    rotation: 0,
                    scale: 1.18,
                    duration: 0.3,
                    ease: 'power2.out',
                    zIndex: 60
                }));
            div.addEventListener('mouseleave', () =>
                gsap.to(div, {
                    rotation: p[2],
                    scale: 1,
                    duration: 0.4,
                    ease: 'power2.out',
                    zIndex: i + 1
                }));
            if (!onMobile) {
                // Desktop only: set absolute scatter positions as inline styles
                div.style.top = p[0];
                div.style.left = p[1];

            }
            // Mobile: NO inline styles — CSS grid handles layout entirely

            owrap.appendChild(div);
            polaroids.push({
                el: div,
                baseRot: p[2]
            });
        });

        // Parallax on scroll (desktop only — never run on mobile)
        if (!onMobile) {
            polaroids.forEach(({
                el
            }, i) => {
                const yV = [-70, -100, -50, -85, -65, -95, -45, -80, -60][i] ?? -70;
                const rV = i % 2 === 0 ? 8 : -8;
                gsap.to(el, {
                    y: yV,
                    rotation: `+=${rV}`,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: '#hero',
                        start: 'top top',
                        end: 'bottom top',
                        scrub: 2
                    },
                });
            });
        }

        /* ─── SMART SCHEDULE ─────────────────────────────────── */
        function updateSchedule() {
            const wib = getWIB();
            const h = wib.getHours(),
                m = wib.getMinutes(),
                s = wib.getSeconds();
            const day = wib.getDay(),
                cur = h * 60 + m;

            document.getElementById('liveClock').textContent = `${pad(h)}:${pad(m)}:${pad(s)}`;
            document.getElementById('liveDay').textContent = `${DAYS[day]} · WIB`;

            const sch = LESSONS[day];
            if (!sch) {
                document.getElementById('nowName').textContent = '🏖️ Libur ey';
                document.getElementById('nowTime').textContent = 'Full Molor~';
                document.getElementById('nextName').textContent = 'Jadwal Hari Senin';
                document.getElementById('nextTime').textContent = '';
            } else {
                let nowL = null,
                    nxtL = null;
                for (let i = 0; i < sch.length; i++) {
                    const s0 = t2m(sch[i].start),
                        e0 = t2m(sch[i].end);
                    if (cur >= s0 && cur < e0) {
                        nowL = sch[i];
                        nxtL = sch[i + 1] || null;
                        break;
                    }
                    if (cur < s0 && !nxtL) {
                        nxtL = sch[i];
                        break;
                    }
                }
                const last = sch[sch.length - 1];
                if (!nowL && cur >= t2m(last.end)) {
                    document.getElementById('nowName').textContent = 'Jam selesai';
                    document.getElementById('nowTime').textContent = 'Sampai jumpa besok~';
                } else if (!nowL) {
                    document.getElementById('nowName').textContent = 'Belum mulai';
                    document.getElementById('nowTime').textContent = `Mulai pukul ${sch[0].start} WIB`;
                } else {
                    document.getElementById('nowName').textContent = nowL.name;
                    document.getElementById('nowTime').textContent = `${nowL.start} – ${nowL.end} WIB`;
                }
                if (nxtL) {
                    document.getElementById('nextName').textContent = nxtL.name;
                    document.getElementById('nextTime').textContent = `${nxtL.start} – ${nxtL.end} WIB`;
                } else {
                    document.getElementById('nextName').textContent = '—';
                    document.getElementById('nextTime').textContent = 'Tidak ada lagi';
                }
                // Full schedule
                const fs = document.getElementById('fullSch');
                fs.innerHTML = '';
                sch.forEach(L => {
                    const active = cur >= t2m(L.start) && cur < t2m(L.end);
                    const d = document.createElement('div');
                    d.className = `si${L.isBreak ? ' brk' : ''}${active ? ' active' : ''}`;
                    d.innerHTML = `<span>${L.name}</span><span class="si-t">${L.start}–${L.end}</span>`;
                    fs.appendChild(d);
                });
            }
            // Piket
            document.getElementById('piketDay').textContent = DAYS[day];
            const pl = document.getElementById('piketList');
            pl.innerHTML = '';
            (PIKET[day] || ['Libur — tidak ada piket']).forEach(n => {
                const li = document.createElement('li');
                li.textContent = n;
                pl.appendChild(li);
            });
        }
        updateSchedule();
        setInterval(updateSchedule, 1000);

        /* ─── DEEP-DIVE SCROLL EFFECT ────────────────────────── */
        // Create dive bubbles (hidden until depth > 0)
        const diveBubCont = document.getElementById('diveBubbles');
        for (let i = 0; i < 22; i++) {
            const b = document.createElement('div');
            b.className = 'dbub';
            const sz = Math.random() * 22 + 5;
            b.style.cssText = `
    width:${sz}px;height:${sz}px;
    left:${Math.random() * 100}%;bottom:${Math.random() * 15}%;
    animation-duration:${Math.random() * 10 + 7}s;
    animation-delay:${-Math.random() * 10}s;
    opacity:0;
  `;
            diveBubCont.appendChild(b);
        }
        const diveBubs = diveBubCont.querySelectorAll('.dbub');

        const diveVig = document.getElementById('diveVignette');
        const diveBlue = document.getElementById('diveBlue');
        let ticking = false;

        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(onScroll);
                ticking = true;
            }
        }, {
            passive: true
        });

        function onScroll() {
            ticking = false;
            const prog = Math.min(window.scrollY / (document.documentElement.scrollHeight - window.innerHeight), 1);

            // Vignette: darkens edges as you "dive"
            diveVig.style.opacity = prog * 0.78;
            // Blue tint overlay
            diveBlue.style.opacity = prog * 0.55;

            // Body background darkens toward deep ocean
            const rC = Math.round(255 - prog * 240);
            const gC = Math.round(249 - prog * 205);
            const bC = Math.round(240 - prog * 160);
            document.body.style.backgroundColor = `rgb(${rC},${gC},${bC})`;

            // Dive bubbles: appear progressively
            diveBubs.forEach((b, i) => {
                const threshold = (i / diveBubs.length) * 0.25;
                const local = Math.max(0, Math.min(1, (prog - threshold) / 0.75));
                b.style.opacity = String(local * 0.5);
                b.style.animationPlayState = prog > 0.04 ? 'running' : 'paused';
            });

            // Navbar
            document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 60);
        }

        /* ─── GSAP HERO ENTRANCE ─────────────────────────────── */
        gsap.from('#heroCenter', {
            y: 70,
            opacity: 0,
            duration: 1.1,
            ease: 'power2.out',
            delay: 0.2
        });

        /* ─── SCROLL REVEAL ──────────────────────────────────── */
        const revObs = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (!e.isIntersecting) return;
                // Stagger siblings
                const siblings = [...(e.target.parentElement?.querySelectorAll('.rv') || [])];
                siblings.forEach((s, i) => setTimeout(() => s.classList.add('on'), i * 90));
                revObs.unobserve(e.target);
            });
        }, {
            threshold: 0.15
        });
        document.querySelectorAll('.rv').forEach(el => revObs.observe(el));

        /* ─── GALLERY OPEN/CLOSE ─────────────────────────────── */

        /* Natural wave-drift scatter positions [left%, top%, rotation°]
           Arranged so photos look like they washed ashore — spread evenly
           but with organic offsets. Nothing overlaps on desktop. */
        const PHOTO_POS = [
            [3, 4, -9],
            [23, 16, 6],
            [44, 3, -5],
            [63, 20, 9],
            [80, 5, -7],
            [10, 52, 7],
            [52, 48, -8],
        ];

        function openGallery(phase) {
            const page = document.getElementById('galleryPage');
            const canvas = document.getElementById('gpCanvas');
            document.getElementById('gpPhase').textContent = phase;
            canvas.innerHTML = '';

            // Remove old transient elements
            page.querySelectorAll('.gbub,.caustic').forEach(x => x.remove());

            // Add underwater ambience
            makeBubbles(page, 18, 'gbub');
            for (let i = 0; i < 5; i++) {
                const c = document.createElement('div');
                c.className = 'caustic';
                const sz = Math.random() * 300 + 160;
                c.style.cssText = `width:${sz}px;height:${sz}px;left:${Math.random() * 90}%;top:${Math.random() * 90}%;animation-duration:${Math.random() * 5 + 4}s;animation-delay:${Math.random() * 3}s`;
                page.appendChild(c);
            }

            page.classList.add('open');
            document.body.style.overflow = 'hidden';

            const photos = GALLERY_DATA[phase] || [];
            const mobile = isMob();

            photos.forEach((photo, i) => {
                const div = document.createElement('div');
                div.className = 'gp-photo';

                const pos = PHOTO_POS[i] ?? [5 + i * 12, 5 + i * 9, i % 2 === 0 ? 8 : -8];

                // if (!mobile) {
                //     div.style.cssText = `left:${pos[0]}%;top:${pos[1]}%;transform:rotate(${pos[2]}deg);width:8px;z-index:${i + 2};`;
                // }
                const size = mobile ? '36vw' : '168px';
                div.style.cssText = `left:${pos[0]}%;top:${pos[1]}%;transform:rotate(${pos[2]}deg);width:${size};z-index:${i + 2};`;

                div.innerHTML = `
      <div class="gp-photo-img" style="background:linear-gradient(135deg,${photo.bg[0]},${photo.bg[1]})">${photo.emoji}</div>
      <div class="gp-photo-cap">${photo.cap}</div>
    `;
                canvas.appendChild(div);

                // Entrance: float in from below (wave-washed effect)
                gsap.from(div, {
                    y: 90,
                    opacity: 0,
                    scale: 0.72,
                    rotation: pos[2] + (Math.random() * 22 - 11),
                    duration: 0.68,
                    ease: 'power2.out',
                    delay: i * 0.1,
                });

                // GSAP Draggable (desktop + mobile now)
                Draggable.create(div, {
                    type: 'x,y',
                    bounds: canvas,
                    edgeResistance: 0.75,
                    onDragStart() {
                        // don't scale on mobile to keep size consistent
                        if (!isMob()) {
                            gsap.to(this.target, {
                                scale: 1.18,
                                zIndex: 200,
                                duration: 0.2,
                                ease: 'power2.out'
                            });
                        }
                    },
                    onDragEnd() {
                        if (!isMob()) {
                            gsap.to(this.target, {
                                scale: 1,
                                duration: 0.35,
                                ease: 'power2.out'
                            });
                        }
                    },
                });

                // Gentle float (mimics water drift) — runs continuously
                gsap.to(div, {
                    y: `+=${Math.random() * 16 + 7}`,
                    rotation: `+=${Math.random() * 4 - 2}`,
                    duration: Math.random() * 3 + 2.5,
                    ease: 'sine.inOut',
                    yoyo: true,
                    repeat: -1,
                    delay: Math.random() * 2,
                });

            });

            // Page entrance
            gsap.from(page, {
                opacity: 0,
                duration: 0.45,
                ease: 'power2.out'
            });
        }

        function closeGallery() {
            const page = document.getElementById('galleryPage');
            gsap.to(page, {
                opacity: 0,
                duration: 0.38,
                ease: 'power2.in',
                onComplete: () => {
                    page.classList.remove('open');
                    page.style.opacity = '';
                    document.body.style.overflow = '';
                }
            });
        }

        /* ─── CONTACT ──────────────🕊️──────────────────────────── */
        function openContact() {
            document.getElementById('cmodal').classList.add('open');
            gsap.from('.cmbox', {
                y: 60,
                opacity: 0,
                scale: 0.92,
                duration: 0.48,
                ease: 'power2.out'
            });
        }

        function closeContact() {
            document.getElementById('cmodal').classList.remove('open');
        }

        function submitForm() {
            const btn = document.getElementById('sendBtn');
            btn.textContent = '🍾 Botol telah dilempar ke Laut. Terima kasih~';
            btn.style.background = 'linear-gradient(90deg,#27ae60,#2ecc71)';
            setTimeout(() => {
                closeContact();
                btn.textContent = 'Kirim ke Pantai!';
                btn.style.background = '';
            }, 2000);
        }

        /* ─── NAVBAR ─────────────────────────────────────────── */
        const ham = document.getElementById('ham');
        const navLinks = document.getElementById('navLinks');
        if (ham && navLinks) {
            ham.addEventListener('click', () => {
                ham.classList.toggle('open');
                navLinks.classList.toggle('open');
            });
            document.querySelectorAll('.nav-links a').forEach(a => a.addEventListener('click', () => {
                ham.classList.remove('open');
                navLinks.classList.remove('open');
            }));
        }

        /* ─── ESC key closes overlays ────────────────────────── */
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeGallery();
                closeContact();
            }
        });

        /* ============================================================
           WHIRLPOOL RANDOMIZER — JS SNIPPET
           Tambahkan ke file script.js Anda (cukup paste di bagian bawah)
           ============================================================ */

        /* ─── DATA: DAFTAR SISWA ──────────────────────────────────────
           Ganti nama dummy di bawah dengan nama asli siswa X PPLG C.
           Pastikan jumlahnya tetap 35 (atau sesuaikan konstanta TOTAL_SISWA).
           ─────────────────────────────────────────────────────────── */
        const siswaXPPLGC = [
            'Ahyar', 'Aisyah', 'Alfino', 'Amelia', 'Arda',
            'Asroh', 'Cantika', 'Dea', 'Early', 'Evita',
            'Farhan', 'Faris', 'Fauzan', 'Fitria', 'Kiran',
            'Kustian', 'Livia', 'Meli', 'Mila', 'Nabila',
            'Nayla', 'Rafi', 'Regita', 'Renita', 'Rezza',
            'Rido Ganteng', 'Risha', 'Sri', 'Vina', 'Windi',
            'Wulan', 'Yunisa', 'Zaskya', 'Zein', 'Keyinaa Cantikk',
        ];
        const TOTAL_SISWA = siswaXPPLGC.length; // 35

        /* ─── ICON SET PER GROUP ──────────────────────────────────────
           Ikon kartun bertema laut untuk masing-masing kartu kelompok.
           ─────────────────────────────────────────────────────────── */
        const GROUP_ICONS = ['⛵', '🚢', '🛥️', '🪸', '🐡', '🦑', '🐙', '🦀', '🐠', '🦈'];

        /* ─── FISHER-YATES SHUFFLE ────────────────────────────────────
           Algoritma pengacakan yang adil dan merata (unbiased).
           Berbeda dari Math.random() sort biasa yang bisa bias.

           Cara kerja:
           1. Mulai dari elemen terakhir array (index = n-1)
           2. Pilih indeks acak antara 0 s/d index saat ini
           3. Tukar (swap) elemen saat ini dengan elemen di indeks acak tadi
           4. Mundur satu langkah (index--), ulangi sampai index = 0
           ─────────────────────────────────────────────────────────── */
        function fisherYatesShuffle(arr) {
            // Buat salinan agar array asli tidak berubah (immutable approach)
            const shuffled = [...arr];

            for (let i = shuffled.length - 1; i > 0; i--) {
                // Pilih indeks acak dari 0 hingga i (inklusif)
                const randomIndex = Math.floor(Math.random() * (i + 1));

                // Swap: tukar posisi elemen i dengan elemen randomIndex
                [shuffled[i], shuffled[randomIndex]] = [shuffled[randomIndex], shuffled[i]];
            }

            return shuffled;
        }

        /* ─── BAGI ARRAY KE KELOMPOK ──────────────────────────────────
           Membagi array hasil shuffle secara adil ke sejumlah kelompok.

           Contoh: 35 siswa, 6 kelompok
           - Pembagian dasar  : Math.floor(35/6) = 5 siswa per kelompok
           - Sisa (remainder) : 35 % 6 = 5 (ada 5 sisa)
           - Kelompok 1-5 mendapat 6 siswa (5+1), kelompok ke-6 mendapat 5 siswa
           ─────────────────────────────────────────────────────────── */
        function splitIntoGroups(arr, numGroups) {
            const groups = [];

            // Ukuran dasar tiap kelompok (dibulatkan ke bawah)
            const baseSize = Math.floor(arr.length / numGroups);
            // Sisa siswa yang harus didistribusikan
            const remainder = arr.length % numGroups;

            let cursor = 0; // Penanda posisi dalam array

            for (let g = 0; g < numGroups; g++) {
                // Kelompok pertama sebanyak 'remainder' mendapat 1 siswa ekstra
                const extra = g < remainder ? 1 : 0;
                const groupSize = baseSize + extra;

                // Ambil irisan array dari posisi cursor sebanyak groupSize
                groups.push(arr.slice(cursor, cursor + groupSize));

                // Geser cursor ke posisi berikutnya
                cursor += groupSize;
            }

            return groups;
        }

        /* ─── RENDER KARTU KELOMPOK KE DOM ────────────────────────── */
        function renderGroupCards(groups) {
            const container = document.getElementById('groupResults');
            container.innerHTML = ''; // Bersihkan hasil sebelumnya

            groups.forEach((members, groupIndex) => {
                // Buat card element
                const card = document.createElement('div');
                card.className = 'group-card';
                card.setAttribute('data-group', groupIndex); // untuk GSAP target

                // Ikon kapal/laut bergilir
                const icon = GROUP_ICONS[groupIndex % GROUP_ICONS.length];

                // Buat isi card
                card.innerHTML = `
      <div class="group-card-header">
        <span class="group-card-icon">${icon}</span>
        <span class="group-card-title">Grup ${groupIndex + 1}</span>
        <span class="group-card-count">${members.length} orng</span>
      </div>
      <div class="group-card-divider"></div>
      <ul class="group-member-list">
        ${members.map((name, i) => `
          <li class="group-member ${i === 0 ? 'captain' : ''}">
            ${name}
          </li>
        `).join('')}
      </ul>
    `;

                container.appendChild(card);
            });
        }

        /* ─── ANIMASI GSAP ────────────────────────────────────────────
           1. Whirlpool berputar cepat (spin 1080°) + sedikit membesar
           2. Setelah selesai: kartu kelompok muncul satu per satu (stagger)
           ─────────────────────────────────────────────────────────── */
        function animateRandomizer(groups) {
            const svgEl = document.getElementById('whirlpoolSVG');
            const btn = document.getElementById('randBtn');

            // Nonaktifkan tombol selama animasi berlangsung
            btn.disabled = true;

            // Hentikan animasi idle (agar tidak bentrok dengan animasi GSAP)
            svgEl.style.animation = 'none';

            // --- Fase 1: Pusaran berputar kencang & membesar ---
            gsap.to(svgEl, {
                rotation: 1080, // 3 putaran penuh searah jarum jam
                scale: 1.25,
                duration: 1.8,
                ease: 'power3.in', // Makin cepat di akhir (seperti tersedot)
                onComplete() {

                    // Kembalikan skala, lalu muncilkan kartu
                    gsap.to(svgEl, {
                        scale: 1,
                        duration: 0.4,
                        ease: 'elastic.out(1, 0.5)',
                        onComplete() {
                            // Kembalikan rotasi ke 0 secara instan (modular)
                            gsap.set(svgEl, {
                                rotation: 0
                            });
                            // Nyalakan kembali animasi idle CSS
                            svgEl.style.animation = '';
                        }
                    });

                    // Render kartu ke DOM terlebih dahulu agar ada yang dianimasi
                    renderGroupCards(groups);

                    // --- Fase 2: Kartu muncul satu per satu (stagger) ---
                    gsap.from('.group-card', {
                        y: 60,
                        opacity: 0,
                        scale: 0.85,
                        duration: 0.55,
                        ease: 'back.out(1.7)', // Efek sedikit bouncy/elastic
                        stagger: 0.09, // Jeda 90ms antar kartu
                        onComplete() {
                            // Aktifkan kembali tombol setelah semua animasi selesai
                            btn.disabled = false;
                        }
                    });
                }
            });
        }

        /* ─── MAIN FUNCTION: runRandomizer() ─────────────────────────
           Dipanggil saat tombol "Aduk Pusaran Air!" diklik.
           ─────────────────────────────────────────────────────────── */
        function runRandomizer() {
            const input = document.getElementById('groupCount');
            const numGroups = parseInt(input.value, 10);

            /* ── Validasi Input ── */
            if (isNaN(numGroups) || input.value.trim() === '') {
                showRandAlert('Eh, isi dulu dong jumlah kelompoknya!');
                return;
            }
            if (numGroups < 2) {
                showRandAlert('⛵ Minimal 2 kelompok ya, masa sendirian di laut?');
                return;
            }
            if (numGroups > 10) {
                showRandAlert('🦑 Kebanyakan kelompoknya! Maksimal 10 kelompok aja.');
                return;
            }
            if (numGroups > TOTAL_SISWA) {
                showRandAlert(`🐙 Jumlah kelompok (${numGroups}) melebihi jumlah siswa (${TOTAL_SISWA})!`);
                return;
            }

            /* ── Proses Pengacakan ── */
            // 1. Acak array siswa dengan Fisher-Yates
            const shuffled = fisherYatesShuffle(siswaXPPLGC);
            // 2. Bagi ke dalam kelompok
            const groups = splitIntoGroups(shuffled, numGroups);
            // 3. Jalankan animasi GSAP, lalu render hasil
            animateRandomizer(groups);
        }

        /* ─── HELPER: Alert lucu bertema laut ───────────────────────── */
        function showRandAlert(msg) {
            // Cari atau buat elemen alert kustom
            let alertEl = document.getElementById('randAlert');

            if (!alertEl) {
                alertEl = document.createElement('div');
                alertEl.id = 'randAlert';
                alertEl.style.cssText = `
      position: fixed;
      top: 80px;
      left: 50%;
      transform: translateX(-50%) translateY(-20px);
      background: linear-gradient(135deg, #001f3f, #003060);
      color: #fff;
      font-family: 'Baloo 2', cursive;
      font-weight: 700;
      font-size: 0.9rem;
      padding: 14px 24px;
      border-radius: 50px;
      border: 1.5px solid rgba(64,224,208,0.5);
      box-shadow: 0 8px 32px rgba(0,0,0,0.4);
      z-index: 9999;
      white-space: nowrap;
      opacity: 0;
      pointer-events: none;
    `;
                document.body.appendChild(alertEl);
            }

            alertEl.textContent = msg;

            // Animasi masuk & keluar
            gsap.killTweensOf(alertEl);
            gsap.fromTo(alertEl, {
                opacity: 0,
                y: -30
            }, {
                opacity: 1,
                y: 0,
                duration: 0.4,
                ease: 'power2.out',
                onComplete() {
                    gsap.to(alertEl, {
                        opacity: 0,
                        y: -20,
                        delay: 2.5,
                        duration: 0.4,
                        ease: 'power2.in',
                    });
                }
            });
        }

        /* ─── Allow pressing Enter in the input ─────────────────────── */
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('groupCount');
            if (input) {
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') runRandomizer();
                });
            }
        });
    </script>

    <!-- ============================================================
     LEAFLET MAP — SMKN 1 Padaherang
    ============================================================ -->
    <!-- ============================================================
     LEAFLET MAP — SMKN 1 Padaherang  [FIXED]
============================================================ -->
<script>
(function () {
    /* ─────────────────────────────────────────────────────────
       VERIFIED COORDINATES — SMKN 1 Padaherang
       Source: data.sekolah-kita.net (Kemdikbud official data)
       Address: Jl. Raya Padaherang Km.1, Desa Karangsari,
                Kec. Padaherang, Kab. Pangandaran, Jawa Barat
    ───────────────────────────────────────────────────────── */
    const LAT  = -7.5565;   // FIXED: was -7.4167 (wrong by ~15 km)
    const LNG  = 108.6945;  // FIXED: was 108.4833 (wrong by ~23 km)
    const ZOOM = 16;        // slightly closer for campus-level view

    /* ── School marker icon ── */
    const schoolIcon = L.divIcon({
        className: '',
        html: `
            <div style="
                width:46px; height:46px;
                background: linear-gradient(135deg, #0077be, #40E0D0);
                border-radius: 50% 50% 50% 0;
                transform: rotate(-45deg);
                box-shadow: 0 4px 20px rgba(0,119,190,0.6),
                            0 0 0 3px rgba(64,224,208,0.35),
                            0 0 0 6px rgba(64,224,208,0.12);
                display: flex;
                align-items: center;
                justify-content: center;
            ">
                <span style="
                    transform: rotate(45deg);
                    font-size: 1.3rem;
                    line-height: 1;
                    display: block;
                    margin-top: 2px;
                ">🏫</span>
            </div>`,
        iconSize:    [46, 46],
        iconAnchor:  [23, 46],
        popupAnchor: [0, -50],
    });

    /* ── Class marker icon ── */
    const classIcon = L.divIcon({
        className: '',
        html: `
            <div style="
                width:36px; height:36px;
                background: linear-gradient(135deg, #40E0D0, #2bb8a9);
                border-radius: 50% 50% 50% 0;
                transform: rotate(-45deg);
                box-shadow: 0 4px 14px rgba(64,224,208,0.55),
                            0 0 0 2px rgba(64,224,208,0.3);
                display: flex;
                align-items: center;
                justify-content: center;
            ">
                <span style="
                    transform: rotate(45deg);
                    font-size: 1rem;
                    line-height: 1;
                    display: block;
                    margin-top: 1px;
                ">💻</span>
            </div>`,
        iconSize:    [36, 36],
        iconAnchor:  [18, 36],
        popupAnchor: [0, -40],
    });

    /* ── Popup HTML builder ── */
    function buildPopup(icon, title, sub, tags, mapsUrl) {
        const tagHtml = tags.map(t => `<span class="mp-tag">${t}</span>`).join('');
        const linkHtml = mapsUrl
            ? `<br><a class="mp-link" href="${mapsUrl}" target="_blank" rel="noopener">🗺 Buka Google Maps ↗</a>`
            : '';
        return `
            <div class="mp-title">${icon} ${title}</div>
            <div class="mp-sub">${sub}</div>
            ${linkHtml}
            <div style="margin-top:6px">${tagHtml}</div>
        `;
    }

    /* ── Init map after DOM is ready ── */
    document.addEventListener('DOMContentLoaded', function () {
        const mapEl = document.getElementById('leafletMap');
        if (!mapEl) return;

        const map = L.map('leafletMap', {
            center:             [LAT, LNG],
            zoom:               ZOOM,
            zoomControl:        true,
            scrollWheelZoom:    false,  /* prevent accidental scroll hijack */
            attributionControl: true,
        });

        /* ── Tile layer — OpenStreetMap ── */
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19,
        }).addTo(map);

        /* ── SMKN 1 Padaherang main marker ── */
        const schoolMarker = L.marker([LAT, LNG], { icon: schoolIcon })
            .addTo(map)
            .bindPopup(
                buildPopup(
                    '🏫',
                    'SMKN 1 Padaherang',
                    'Jl. Raya Padaherang Km.1, Desa Karangsari,<br>Kec. Padaherang, Kab. Pangandaran, Jawa Barat',
                    ['SMK Negeri', 'Padaherang', 'Pangandaran'],
                    'https://maps.google.com/?q=-7.5565,108.6945'
                ),
                { maxWidth: 280, className: '' }
            );

        /* Open popup after a short delay so tiles load first */
        setTimeout(() => schoolMarker.openPopup(), 600);

        /* ── X PPLG C class marker (small offset from school) ── */
        L.marker([LAT - 0.0004, LNG + 0.0007], { icon: classIcon })
            .addTo(map)
            .bindPopup(
                buildPopup(
                    '💻',
                    'Kelas X PPLG C',
                    'Pengembangan Perangkat Lunak dan Gim',
                    ['PPLG', 'Kelas X', '35 Siswa'],
                    null
                ),
                { maxWidth: 240 }
            );

        /* ── Campus radius circle ── */
        L.circle([LAT, LNG], {
            radius:      100,
            color:       '#40E0D0',
            fillColor:   '#0077be',
            fillOpacity: 0.07,
            weight:      1.5,
            dashArray:   '6 5',
            opacity:     0.5,
        }).addTo(map);

        /* ── Fade-in when section enters viewport ── */
        const section = document.getElementById('lokasi');
        if (section && 'IntersectionObserver' in window) {
            /* Start invisible and slightly below */
            mapEl.style.opacity   = '0';
            mapEl.style.transform = 'translateY(20px)';

            const obs = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;
                    mapEl.style.transition = 'opacity 0.85s ease, transform 0.85s ease';
                    mapEl.style.opacity    = '1';
                    mapEl.style.transform  = 'translateY(0)';
                    /* Tell Leaflet the element is now visible so it renders tiles correctly */
                    setTimeout(() => map.invalidateSize(), 120);
                    obs.unobserve(section);
                });
            }, { threshold: 0.12 });

            obs.observe(section);
        } else {
            /* Fallback for browsers without IntersectionObserver */
            setTimeout(() => map.invalidateSize(), 400);
        }
    });
})();
</script>
</body>

</html>