<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — X PPLG C</title>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;700;800&family=Nunito:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        /* ============================================================
       CSS TOKENS
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
            --r: 16px;
            --sh: 0 4px 20px rgba(0, 119, 190, 0.14);
            --nav-w: 240px;
            --bot-h: 65px;
            /* Light mode body vars */
            --body-bg: #f0f7ff;
            --card-bg: #ffffff;
            --card-border: rgba(0,119,190,0.08);
            --text-main: #0a2540;
            --text-muted: #888;
            --input-border: #eee;
            --input-bg: #fff;
            --row-bg: #f8fbff;
            --row-hover: #eaf4ff;
            --nav-bg-start: #001f3f;
            --nav-bg-end: #002855;
            --topbar-bg: linear-gradient(90deg, #001f3f, #002855);
            --bottom-nav-bg: rgba(255,255,255,0.96);
            --bottom-nav-border: rgba(0,119,190,0.12);
            --flash-success-bg: #e8f8f0;
            --flash-success-color: #1a7a4a;
            --flash-success-border: #a8e6c4;
            --flash-error-bg: #fff0f0;
            --flash-error-color: #c0392b;
            --flash-error-border: #ffcdd2;
        }

        /* ============================================================
       DARK MODE TOKENS — Premium Dashboard (Reference Image Style)
       Deep navy/charcoal base · Soft cyan/blue glow accents
       Inspired by: #2C3E50 → #4CA1AF identity
    ============================================================ */
        body.dark-mode {
            /* Core palette */
            --dm-bg-base:        #0d1117;
            --dm-bg-surface:     #161b27;
            --dm-bg-elevated:    #1c2333;
            --dm-bg-card:        #1e2a3a;
            --dm-bg-card-hover:  #243040;
            --dm-border-subtle:  rgba(255,255,255,0.06);
            --dm-border-medium:  rgba(76,161,175,0.18);
            --dm-border-accent:  rgba(76,161,175,0.35);
            --dm-glow-cyan:      rgba(76,161,175,0.15);
            --dm-glow-blue:      rgba(44,62,80,0.4);
            --dm-accent-primary: #4CA1AF;
            --dm-accent-secondary: #2C3E50;
            --dm-accent-cyan:    #5bc8d4;
            --dm-accent-purple:  #7c6fcd;
            --dm-text-primary:   #e2e8f0;
            --dm-text-secondary: #94a3b8;
            --dm-text-muted:     #64748b;
            --dm-text-accent:    #5bc8d4;
            /* Mapped tokens */
            --body-bg:           #0d1117;
            --card-bg:           #1e2a3a;
            --card-border:       rgba(76,161,175,0.15);
            --text-main:         #e2e8f0;
            --text-muted:        #94a3b8;
            --input-border:      rgba(76,161,175,0.25);
            --input-bg:          rgba(255,255,255,0.04);
            --row-bg:            rgba(255,255,255,0.03);
            --row-hover:         rgba(76,161,175,0.08);
            --nav-bg-start:      #0f1923;
            --nav-bg-end:        #162030;
            --topbar-bg:         linear-gradient(90deg, #0f1923, #162030);
            --bottom-nav-bg:     rgba(13,17,23,0.97);
            --bottom-nav-border: rgba(76,161,175,0.15);
            --flash-success-bg:  rgba(26,122,74,0.15);
            --flash-success-color: #4ade80;
            --flash-success-border: rgba(26,122,74,0.3);
            --flash-error-bg:    rgba(239,68,68,0.12);
            --flash-error-color: #f87171;
            --flash-error-border: rgba(239,68,68,0.25);
            --sh:                0 4px 24px rgba(0,0,0,0.5), 0 1px 0 rgba(255,255,255,0.04);
            --r:                 14px;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--fb);
            background: var(--body-bg);
            color: var(--text-main);
            overflow-x: hidden;
            transition: background 0.4s ease, color 0.4s ease;
        }

        body.dark-mode {
            background: #0d1117;
            min-height: 100vh;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #d6f0ff;
        }

        body.dark-mode ::-webkit-scrollbar-track {
            background: rgba(13,17,23,0.8);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--ocean);
            border-radius: 4px;
        }

        body.dark-mode ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #4CA1AF, #2C3E50);
            border-radius: 4px;
        }

        /* ============================================================
       DARK MODE TOGGLE SWITCH — Premium Style
    ============================================================ */
        .theme-toggle {
            display: flex;
            align-items: center;
            gap: 9px;
            cursor: pointer;
            user-select: none;
            padding: 6px 10px;
            border-radius: 10px;
            transition: background 0.2s;
        }

        .theme-toggle:hover {
            background: rgba(255,255,255,0.06);
        }

        .theme-toggle-label {
            font-family: var(--fd);
            font-size: 0.72rem;
            font-weight: 700;
            color: rgba(255,255,255,0.55);
            white-space: nowrap;
            transition: color 0.3s;
        }

        .toggle-track {
            position: relative;
            width: 46px;
            height: 25px;
            background: rgba(255,255,255,0.1);
            border-radius: 50px;
            border: 1.5px solid rgba(255,255,255,0.15);
            transition: all 0.35s cubic-bezier(0.4,0,0.2,1);
            flex-shrink: 0;
        }

        .toggle-track.on {
            background: linear-gradient(135deg, #2C3E50, #4CA1AF);
            border-color: rgba(76,161,175,0.6);
            box-shadow: 0 0 12px rgba(76,161,175,0.3), inset 0 1px 0 rgba(255,255,255,0.1);
        }

        .toggle-thumb {
            position: absolute;
            top: 2.5px;
            left: 2.5px;
            width: 18px;
            height: 18px;
            background: #fff;
            border-radius: 50%;
            transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        .toggle-track.on .toggle-thumb {
            transform: translateX(21px);
            box-shadow: 0 2px 8px rgba(76,161,175,0.4);
        }

        /* Desktop toggle — bottom of side nav */
        .side-nav-toggle {
            padding: 12px 16px 16px;
            border-top: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
            margin-top: auto;
        }

        /* Mobile toggle — in topbar */
        .topbar-toggle {
            display: flex;
            align-items: center;
        }

        /* ============================================================
       SIDE NAVBAR — Desktop (>1024px)
    ============================================================ */
        .side-nav {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--nav-w);
            height: 100vh;
            background: linear-gradient(180deg, var(--nav-bg-start) 0%, var(--nav-bg-end) 100%);
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, background 0.4s ease;
        }

        body.dark-mode .side-nav {
            background: linear-gradient(180deg, #0f1923 0%, #111d2b 60%, #0f1923 100%);
            box-shadow: 4px 0 32px rgba(0,0,0,0.6), 1px 0 0 rgba(76,161,175,0.08);
            border-right: 1px solid rgba(76,161,175,0.08);
        }

        .side-nav-brand {
            padding: 22px 20px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .side-nav-brand .brand-name {
            font-family: var(--fd);
            font-weight: 800;
            font-size: 1.2rem;
            color: #fff;
        }

        .side-nav-brand .brand-name span {
            color: var(--sandy-dk);
        }

        .side-nav-brand .brand-role {
            font-size: 0.72rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .side-nav-user {
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .side-nav-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--turq), var(--ocean));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.95rem;
            color: #fff;
            flex-shrink: 0;
        }

        .side-nav-info .info-name {
            font-family: var(--fd);
            font-weight: 700;
            font-size: 0.88rem;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        .side-nav-info .info-role {
            font-size: 0.68rem;
            color: var(--turq);
            font-weight: 600;
            text-transform: capitalize;
        }

        .side-nav-menu {
            flex: 1;
            padding: 12px 12px;
            display: flex;
            flex-direction: column;
            gap: 3px;
            overflow-y: auto;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 14px;
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.65);
            text-decoration: none;
            font-family: var(--fd);
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.25s;
            cursor: pointer;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            transform: translateX(3px);
        }

        .nav-item.active {
            background: linear-gradient(90deg, var(--ocean), rgba(0, 119, 190, 0.7));
            color: #fff;
            box-shadow: 0 4px 14px rgba(0, 119, 190, 0.35);
        }

        body.dark-mode .nav-item:hover {
            background: rgba(76,161,175,0.1);
            color: #e2e8f0;
        }

        body.dark-mode .nav-item.active {
            background: linear-gradient(135deg, rgba(44,62,80,0.9), rgba(76,161,175,0.4));
            color: #fff;
            box-shadow: 0 4px 20px rgba(76,161,175,0.2), inset 0 1px 0 rgba(255,255,255,0.08);
            border: 1px solid rgba(76,161,175,0.25);
        }

        body.dark-mode .nav-item.active .ni-icon {
            filter: drop-shadow(0 0 6px rgba(76,161,175,0.6));
        }

        .nav-item .ni-icon {
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .nav-item .ni-badge {
            margin-left: auto;
            background: var(--coral);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 50px;
            min-width: 20px;
            text-align: center;
        }

        .nav-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.3);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 12px 14px 4px;
        }

        /* ============================================================
       BOTTOM NAVBAR — Tablet & Mobile (≤1024px)
    ============================================================ */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--bot-h);
            background: var(--bottom-nav-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid var(--bottom-nav-border);
            z-index: 100;
            box-shadow: 0 -4px 24px rgba(0, 0, 0, 0.1);
            padding: 0 4px;
            align-items: stretch;
            justify-content: space-around;
            gap: 2px;
            transition: background 0.4s ease;
        }

        .bot-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            gap: 3px;
            color: #aaa;
            text-decoration: none;
            font-family: var(--fd);
            font-weight: 600;
            font-size: 0.6rem;
            padding: 6px 2px;
            border-radius: 12px;
            transition: all 0.25s;
            position: relative;
            cursor: pointer;
            border: none;
            background: transparent;
        }

        body.dark-mode .bot-item {
            color: rgba(255,255,255,0.4);
        }

        body.dark-mode .bot-item.active {
            color: #4CA1AF;
        }

        body.dark-mode .bot-item.active .bot-icon-wrap {
            background: rgba(76,161,175,0.15);
            box-shadow: 0 0 12px rgba(76,161,175,0.2);
        }

        .bot-item.active {
            color: var(--ocean);
        }

        .bot-item.active .bot-icon-wrap {
            background: rgba(0, 119, 190, 0.12);
            transform: translateY(-4px) scale(1.1);
        }

        .bot-icon-wrap {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            transition: all 0.25s;
        }

        .bot-label {
            line-height: 1;
        }

        .bot-badge {
            position: absolute;
            top: 4px;
            right: calc(50% - 18px);
            background: var(--coral);
            color: #fff;
            font-size: 0.6rem;
            font-weight: 800;
            padding: 1px 5px;
            border-radius: 50px;
            min-width: 16px;
            text-align: center;
        }

        /* ============================================================
       MAIN CONTENT WRAPPER
    ============================================================ */
        .dash-main {
            margin-left: var(--nav-w);
            min-height: 100vh;
            padding: 24px;
            padding-bottom: 40px;
        }

        /* Top bar untuk mobile (menggantikan side navbar header) */
        .dash-topbar {
            display: none;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: var(--topbar-bg);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 99;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.2);
            transition: background 0.4s ease;
        }

        body.dark-mode .dash-topbar {
            background: linear-gradient(90deg, #0f1923, #111d2b) !important;
            box-shadow: 0 1px 0 rgba(76,161,175,0.1), 0 4px 20px rgba(0,0,0,0.5);
            border-bottom: 1px solid rgba(76,161,175,0.08);
        }

        .topbar-brand {
            font-family: var(--fd);
            font-weight: 800;
            font-size: 1.1rem;
            color: #fff;
        }

        .topbar-brand span {
            color: var(--sandy-dk);
        }

        .topbar-user {
            font-family: var(--fd);
            font-weight: 700;
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Flash messages */
        .flash-msg {
            padding: 12px 18px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-weight: 600;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .flash-msg.success {
            background: var(--flash-success-bg);
            color: var(--flash-success-color);
            border: 1px solid var(--flash-success-border);
        }

        .flash-msg.error {
            background: var(--flash-error-bg);
            color: var(--flash-error-color);
            border: 1px solid var(--flash-error-border);
        }

        /* Card universal */
        .d-card {
            background: var(--card-bg);
            border-radius: var(--r);
            padding: 22px;
            box-shadow: var(--sh);
            border: 1px solid var(--card-border);
            transition: background 0.4s ease, border-color 0.4s ease, box-shadow 0.4s ease;
        }

        body.dark-mode .d-card {
            background: #1e2a3a;
            border: 1px solid rgba(76,161,175,0.1);
            box-shadow: 0 4px 24px rgba(0,0,0,0.4), 0 1px 0 rgba(255,255,255,0.04), inset 0 1px 0 rgba(255,255,255,0.03);
        }

        body.dark-mode .d-card:hover {
            border-color: rgba(76,161,175,0.2);
            box-shadow: 0 8px 32px rgba(0,0,0,0.5), 0 0 0 1px rgba(76,161,175,0.1);
        }

        .d-card-title {
            font-family: var(--fd);
            font-weight: 700;
            font-size: 1rem;
            color: var(--ocean);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        body.dark-mode .d-card-title {
            color: #5bc8d4;
        }

        /* ============================================================
       RESPONSIVE BREAKPOINTS
    ============================================================ */
        /* Tablet (768px – 1024px) → bottom nav */
        @media (max-width: 1024px) {
            .side-nav {
                display: none;
            }

            .bottom-nav {
                display: flex;
            }

            .dash-topbar {
                display: flex;
            }

            .dash-main {
                margin-left: 0;
                padding: 16px;
                padding-top: 70px;
                /* clear topbar */
                padding-bottom: calc(var(--bot-h) + 16px);
                /* clear bottom nav */
            }
        }

        /* ============================================================
       WHIRLPOOL RANDOMIZER — Dashboard Styles
    ============================================================ */
        .dash-whirlpool-section {
            background: linear-gradient(180deg, var(--ocean-dk), var(--ocean-deep));
            border-radius: var(--r);
            padding: 28px 24px 24px;
            margin-bottom: 22px;
            position: relative;
            overflow: hidden;
        }

        body.dark-mode .dash-whirlpool-section {
            background: linear-gradient(135deg, #1a2535 0%, #1e2d3d 100%);
            border: 1px solid rgba(76,161,175,0.15);
            box-shadow: 0 4px 24px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.04);
        }

        .dash-whirlpool-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle, rgba(64,224,208,0.07) 1px, transparent 1px),
                radial-gradient(circle, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 36px 36px, 60px 60px;
            pointer-events: none;
        }

        .dw-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .dw-title {
            font-family: var(--fd);
            font-weight: 800;
            font-size: 1.05rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dw-toggle-btn {
            background: rgba(255,255,255,0.12);
            border: 1.5px solid rgba(64,224,208,0.4);
            color: var(--turq);
            font-family: var(--fd);
            font-weight: 700;
            font-size: 0.78rem;
            padding: 6px 14px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.25s;
        }

        .dw-toggle-btn:hover {
            background: rgba(64,224,208,0.15);
        }

        .dw-body {
            display: none;
        }

        .dw-body.open {
            display: block;
        }

        .dw-center {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 32px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .dw-whirlpool-wrap {
            position: relative;
            width: 160px;
            height: 160px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dw-wp-ring {
            position: absolute;
            border-radius: 50%;
            border: 2px solid rgba(64,224,208,0.2);
            animation: wpRingPulse ease-in-out infinite alternate;
            pointer-events: none;
        }

        .dw-wp-ring-1 { width: 180px; height: 180px; animation-duration: 3s; }
        .dw-wp-ring-2 { width: 210px; height: 210px; animation-duration: 3.8s; animation-delay: 0.6s; }
        .dw-wp-ring-3 { width: 240px; height: 240px; animation-duration: 4.5s; animation-delay: 1.2s; }

        @keyframes wpRingPulse {
            from { transform: scale(0.92); opacity: 0.6; }
            to { transform: scale(1.05); opacity: 0.2; }
        }

        .dw-whirlpool-svg {
            width: 160px;
            height: 160px;
            filter: drop-shadow(0 0 16px rgba(64,224,208,0.35));
            animation: wpIdleSpin 12s linear infinite;
        }

        @keyframes wpIdleSpin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .dw-form {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-width: 320px;
            width: 100%;
        }

        .dw-label {
            font-family: var(--fd);
            font-size: 0.92rem;
            font-weight: 700;
            color: rgba(255,255,255,0.9);
            line-height: 1.5;
        }

        .dw-input-row {
            display: flex;
            gap: 10px;
            align-items: stretch;
        }

        .dw-input {
            width: 80px;
            flex-shrink: 0;
            padding: 10px 12px;
            font-family: var(--fd);
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--ocean-deep);
            background: #fff;
            border: 3px solid var(--turq);
            border-radius: 14px;
            outline: none;
            text-align: center;
            transition: border-color 0.3s, box-shadow 0.3s;
            appearance: textfield;
            -moz-appearance: textfield;
        }

        .dw-input::-webkit-outer-spin-button,
        .dw-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

        .dw-input:focus {
            border-color: #fff;
            box-shadow: 0 0 0 3px rgba(64,224,208,0.4);
        }

        .dw-btn {
            flex: 1;
            padding: 10px 16px;
            background: linear-gradient(135deg, var(--turq), var(--turq-dk));
            color: var(--ocean-deep);
            font-family: var(--fd);
            font-weight: 800;
            font-size: 0.88rem;
            border: none;
            border-radius: 14px;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(64,224,208,0.3);
            transition: all 0.3s;
            line-height: 1.3;
        }

        .dw-btn:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 10px 28px rgba(64,224,208,0.45);
        }

        .dw-btn:active { transform: scale(0.97); }

        .dw-btn:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .dw-note {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.4);
            font-weight: 600;
        }

        .dw-group-results {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 14px;
        }

        .dw-group-card {
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(64,224,208,0.3);
            border-radius: 18px;
            padding: 16px 14px 14px;
            backdrop-filter: blur(8px);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .dw-group-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.3);
            border-color: rgba(64,224,208,0.6);
        }

        .dw-group-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 18px 18px 0 0;
        }

        .dw-group-card:nth-child(6n+1)::before { background: linear-gradient(90deg,#40E0D0,#0077be); }
        .dw-group-card:nth-child(6n+2)::before { background: linear-gradient(90deg,#93E9BE,#40E0D0); }
        .dw-group-card:nth-child(6n+3)::before { background: linear-gradient(90deg,#FFB347,#FF7B6B); }
        .dw-group-card:nth-child(6n+4)::before { background: linear-gradient(90deg,#b48aff,#6A5ACD); }
        .dw-group-card:nth-child(6n+5)::before { background: linear-gradient(90deg,#FF7B6B,#FFB347); }
        .dw-group-card:nth-child(6n+6)::before { background: linear-gradient(90deg,#0077be,#93E9BE); }

        .dw-card-header {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 10px;
        }

        .dw-card-icon { font-size: 1.1rem; line-height: 1; }

        .dw-card-title {
            font-family: var(--fd);
            font-weight: 800;
            font-size: 0.88rem;
            color: var(--turq);
        }

        .dw-card-count {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.4);
            font-weight: 600;
            margin-left: auto;
            background: rgba(255,255,255,0.07);
            padding: 2px 7px;
            border-radius: 50px;
        }

        .dw-card-divider {
            height: 1px;
            background: rgba(255,255,255,0.08);
            margin-bottom: 10px;
        }

        .dw-member-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .dw-member {
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: var(--fb);
            font-size: 0.76rem;
            font-weight: 600;
            color: rgba(255,255,255,0.82);
            padding: 4px 7px;
            border-radius: 8px;
            background: rgba(255,255,255,0.05);
            transition: background 0.2s;
        }

        .dw-member:hover { background: rgba(64,224,208,0.1); color: #fff; }
        .dw-member::before { content: '🐚'; font-size: 0.65rem; flex-shrink: 0; }

        .dw-member.captain {
            background: rgba(64,224,208,0.12);
            color: var(--turq);
            font-weight: 700;
        }

        .dw-member.captain::before { content: '⚓'; }

        @media (max-width: 768px) {
            .dw-center {
                flex-direction: column;
                gap: 20px;
                align-items: center;
            }

            .dw-form {
                width: 100%;
                max-width: 100%;
            }

            .dw-input-row {
                flex-direction: column;
            }

            .dw-input {
                width: 100%;
                font-size: 1rem;
            }

            .dw-btn {
                width: 100%;
                padding: 12px;
            }

            .dw-group-results {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
        }
    </style>
    <style>
        /* ============================================================
       GLOBAL DARK MODE OVERRIDES — Premium Reference Image Style
       Deep navy #0d1117 base · Elevated cards #1e2a3a
       Soft cyan/blue glow · #2C3E50 → #4CA1AF identity
    ============================================================ */

        /* ── Typography ── */
        body.dark-mode h1, body.dark-mode h2,
        body.dark-mode h3, body.dark-mode h4 { color: #e2e8f0 !important; }
        body.dark-mode p { color: #94a3b8; }
        body.dark-mode hr { border-color: rgba(255,255,255,0.06) !important; }

        /* ── Inputs / Selects / Textareas ── */
        body.dark-mode input:not([type="submit"]):not([type="button"]):not([type="file"]),
        body.dark-mode select,
        body.dark-mode textarea {
            background: rgba(255,255,255,0.04) !important;
            border: 1.5px solid rgba(76,161,175,0.2) !important;
            color: #e2e8f0 !important;
        }
        body.dark-mode input::placeholder,
        body.dark-mode textarea::placeholder { color: #475569 !important; }
        body.dark-mode input:focus,
        body.dark-mode select:focus,
        body.dark-mode textarea:focus {
            border-color: rgba(76,161,175,0.5) !important;
            box-shadow: 0 0 0 3px rgba(76,161,175,0.1), 0 0 12px rgba(76,161,175,0.06) !important;
            outline: none !important;
        }
        body.dark-mode select option { background: #1e2a3a; color: #e2e8f0; }

        /* ── Flash messages ── */
        body.dark-mode .flash-msg.success {
            background: rgba(74,222,128,0.08) !important;
            color: #4ade80 !important;
            border-color: rgba(74,222,128,0.2) !important;
        }
        body.dark-mode .flash-msg.error {
            background: rgba(248,113,113,0.08) !important;
            color: #f87171 !important;
            border-color: rgba(248,113,113,0.2) !important;
        }

        /* ── Stat cards ── */
        body.dark-mode .stat-card {
            background: #1e2a3a !important;
            border-top: 1px solid rgba(76,161,175,0.08) !important;
            border-right: 1px solid rgba(76,161,175,0.08) !important;
            border-bottom: 1px solid rgba(76,161,175,0.08) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.35) !important;
        }
        body.dark-mode .stat-val { color: #5bc8d4 !important; }
        body.dark-mode .stat-lbl { color: #64748b !important; }

        /* ── Schedule / Clock ── */
        body.dark-mode .live-clock { color: #e2e8f0 !important; }
        body.dark-mode .live-day { color: #64748b !important; }
        body.dark-mode .lbox {
            background: linear-gradient(135deg, rgba(44,62,80,0.8), rgba(76,161,175,0.25)) !important;
            border: 1px solid rgba(76,161,175,0.15) !important;
        }
        body.dark-mode .lbox.nxt {
            background: rgba(147,233,190,0.07) !important;
            color: #93E9BE !important;
            border: 1px solid rgba(147,233,190,0.12) !important;
        }
        body.dark-mode .piket-badge {
            background: rgba(76,161,175,0.2) !important;
            color: #5bc8d4 !important;
        }
        body.dark-mode .piket-list li {
            background: rgba(255,255,255,0.04) !important;
            color: #94a3b8 !important;
            border: 1px solid rgba(255,255,255,0.05) !important;
        }
        body.dark-mode .si {
            background: rgba(255,255,255,0.03) !important;
            color: #94a3b8 !important;
        }
        body.dark-mode .si.active {
            background: linear-gradient(90deg, rgba(44,62,80,0.6), rgba(76,161,175,0.15)) !important;
            border-left-color: #4CA1AF !important;
            color: #5bc8d4 !important;
        }
        body.dark-mode .si.brk {
            background: rgba(192,128,96,0.07) !important;
            color: #c08060 !important;
        }
        body.dark-mode .si-t { color: #475569 !important; }

        /* ── Kas page ── */
        body.dark-mode .ks-card {
            background: #1e2a3a !important;
            border: 1px solid rgba(76,161,175,0.1) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.35) !important;
        }
        body.dark-mode .ks-lbl { color: #64748b !important; }
        body.dark-mode .trx-item {
            background: rgba(255,255,255,0.03) !important;
            border: 1px solid rgba(255,255,255,0.04) !important;
        }
        body.dark-mode .trx-item.pengeluaran {
            background: rgba(248,113,113,0.05) !important;
            border-color: rgba(248,113,113,0.1) !important;
        }
        body.dark-mode .trx-badge.masuk { color: #4ade80 !important; }
        body.dark-mode .trx-badge.keluar { color: #f87171 !important; }
        body.dark-mode .pay-row {
            background: rgba(255,255,255,0.03) !important;
            border: 1px solid rgba(255,255,255,0.04) !important;
        }
        body.dark-mode .pname { color: #e2e8f0 !important; }
        body.dark-mode .pay-toggle.paid {
            background: rgba(74,222,128,0.1) !important;
            color: #4ade80 !important;
            border: 1px solid rgba(74,222,128,0.18) !important;
        }
        body.dark-mode .pay-toggle.unpaid {
            background: rgba(248,113,113,0.08) !important;
            color: #f87171 !important;
            border: 1px solid rgba(248,113,113,0.15) !important;
        }

        /* ── Chart container ── */
        body.dark-mode .chart-container {
            background: #1e2a3a !important;
            border: 1px solid rgba(76,161,175,0.1) !important;
            box-shadow: 0 4px 24px rgba(0,0,0,0.4) !important;
        }
        body.dark-mode .chart-title { color: #5bc8d4 !important; }
        body.dark-mode .chart-mode-selector {
            background: rgba(255,255,255,0.04) !important;
            border: 1px solid rgba(255,255,255,0.06) !important;
        }
        body.dark-mode .chart-mode-btn { color: #64748b !important; }
        body.dark-mode .chart-mode-btn.active {
            background: linear-gradient(135deg, #2C3E50, #4CA1AF) !important;
            color: #fff !important;
            box-shadow: 0 4px 14px rgba(76,161,175,0.25) !important;
        }
        body.dark-mode .chart-mode-btn:hover:not(.active) {
            background: rgba(76,161,175,0.08) !important;
            color: #5bc8d4 !important;
        }
        body.dark-mode .chart-legend-item { color: #94a3b8 !important; }

        /* ── Kehadiran page ── */
        body.dark-mode .kh-row {
            background: rgba(255,255,255,0.03) !important;
            border: 1px solid rgba(255,255,255,0.04) !important;
        }
        body.dark-mode .kh-name { color: #e2e8f0 !important; }
        body.dark-mode .kh-sel-wrap select {
            background: rgba(255,255,255,0.04) !important;
            border-color: rgba(76,161,175,0.2) !important;
            color: #e2e8f0 !important;
        }
        body.dark-mode .stat-mini {
            background: #1e2a3a !important;
            border: 1px solid rgba(76,161,175,0.1) !important;
            box-shadow: 0 4px 16px rgba(0,0,0,0.3) !important;
        }
        body.dark-mode .stat-mini-lbl { color: #64748b !important; }
        body.dark-mode .st-hadir { background: rgba(74,222,128,0.1) !important; color: #4ade80 !important; }
        body.dark-mode .st-izin { background: rgba(251,191,36,0.1) !important; color: #fbbf24 !important; }
        body.dark-mode .st-sakit { background: rgba(96,165,250,0.1) !important; color: #60a5fa !important; }
        body.dark-mode .st-alpha { background: rgba(248,113,113,0.1) !important; color: #f87171 !important; }

        /* ── Leaderboard page ── */
        body.dark-mode .lb-table-row {
            background: rgba(255,255,255,0.03) !important;
            border: 1px solid rgba(255,255,255,0.04) !important;
        }
        body.dark-mode .lb-table-row:hover {
            background: rgba(76,161,175,0.06) !important;
            border-color: rgba(76,161,175,0.15) !important;
        }
        body.dark-mode .lb-table-row.me {
            background: linear-gradient(90deg, rgba(44,62,80,0.5), rgba(76,161,175,0.1)) !important;
            border: 1px solid rgba(76,161,175,0.2) !important;
        }
        body.dark-mode .lb-uname { color: #e2e8f0 !important; }
        body.dark-mode .lb-usub { color: #64748b !important; }
        body.dark-mode .lb-poin { color: #5bc8d4 !important; }
        body.dark-mode .lb-rank { color: #475569 !important; }
        body.dark-mode .lb-rank.gold { color: #fbbf24 !important; }
        body.dark-mode .lb-rank.silver { color: #94a3b8 !important; }
        body.dark-mode .lb-rank.bronze { color: #b45309 !important; }
        body.dark-mode .lb-pod-name { color: #e2e8f0 !important; }
        body.dark-mode .lb-pod-poin { color: #5bc8d4 !important; }
        body.dark-mode .t-sultan { background: rgba(251,191,36,0.15) !important; color: #fbbf24 !important; }
        body.dark-mode .t-kaya { background: rgba(148,163,184,0.12) !important; color: #94a3b8 !important; }
        body.dark-mode .t-normal { background: rgba(76,161,175,0.15) !important; color: #5bc8d4 !important; }
        body.dark-mode .t-kelas_bawah { background: rgba(255,255,255,0.06) !important; color: #64748b !important; }

        /* ── Pesan page ── */
        body.dark-mode .p-item {
            background: rgba(255,255,255,0.03) !important;
            border: 1px solid rgba(255,255,255,0.04) !important;
            border-left: 3px solid transparent !important;
        }
        body.dark-mode .p-item:hover {
            background: rgba(76,161,175,0.06) !important;
            border-left-color: rgba(76,161,175,0.3) !important;
        }
        body.dark-mode .p-item.unread {
            background: rgba(76,161,175,0.07) !important;
            border-left-color: #4CA1AF !important;
        }
        body.dark-mode .p-item.broadcast { border-left-color: #f87171 !important; }
        body.dark-mode .p-judul { color: #e2e8f0 !important; }
        body.dark-mode .p-meta { color: #64748b !important; }
        body.dark-mode .p-isi { color: #94a3b8 !important; }

        /* ── Profile page ── */
        body.dark-mode .prof-header {
            background: linear-gradient(135deg, #1a2535, #1e3040) !important;
            border: 1px solid rgba(76,161,175,0.15) !important;
            box-shadow: 0 4px 24px rgba(0,0,0,0.4) !important;
        }
        body.dark-mode .form-group label { color: #94a3b8 !important; }
        body.dark-mode .ps-v { color: #e2e8f0 !important; }
        body.dark-mode .ps-l { color: #64748b !important; }

        /* ── Users page ── */
        body.dark-mode .u-row {
            background: rgba(255,255,255,0.03) !important;
            border: 1px solid rgba(255,255,255,0.04) !important;
        }
        body.dark-mode .u-name { color: #e2e8f0 !important; }
        body.dark-mode .u-sub { color: #64748b !important; }
        body.dark-mode .u-avatar {
            background: linear-gradient(135deg, rgba(44,62,80,0.9), rgba(76,161,175,0.5)) !important;
        }
        body.dark-mode .rb-wali_kelas { background: rgba(21,101,192,0.15) !important; color: #60a5fa !important; }
        body.dark-mode .rb-bendahara { background: rgba(46,125,50,0.15) !important; color: #4ade80 !important; }
        body.dark-mode .rb-sekretaris { background: rgba(198,40,40,0.15) !important; color: #f87171 !important; }
        body.dark-mode .rb-siswa { background: rgba(106,27,154,0.15) !important; color: #c084fc !important; }

        /* ── History page ── */
        body.dark-mode .history-item {
            background: rgba(255,255,255,0.03) !important;
            border: 1px solid rgba(255,255,255,0.04) !important;
        }
        body.dark-mode .history-desc { color: #e2e8f0 !important; }
        body.dark-mode .history-aksi { color: #64748b !important; }
        body.dark-mode .history-time { color: #475569 !important; }

        /* ── Nav section labels ── */
        body.dark-mode .nav-section-label { color: rgba(76,161,175,0.35) !important; }

        /* ── Side nav brand & user ── */
        body.dark-mode .side-nav-brand { border-bottom-color: rgba(76,161,175,0.07) !important; }
        body.dark-mode .side-nav-user { border-bottom-color: rgba(76,161,175,0.05) !important; }
        body.dark-mode .side-nav-avatar {
            background: linear-gradient(135deg, #2C3E50, #4CA1AF) !important;
            box-shadow: 0 0 14px rgba(76,161,175,0.25) !important;
        }
        body.dark-mode .info-role { color: #4CA1AF !important; }

        /* ── Tier badges ── */
        body.dark-mode .tier-badge.tier-kelas_bawah {
            background: rgba(255,255,255,0.07) !important;
            color: #94a3b8 !important;
        }

        /* ── Pagination ── */
        body.dark-mode .pagination .page-link {
            background: #1e2a3a !important;
            border-color: rgba(76,161,175,0.12) !important;
            color: #94a3b8 !important;
        }
        body.dark-mode .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #2C3E50, #4CA1AF) !important;
            border-color: transparent !important;
            color: #fff !important;
        }
        body.dark-mode .pagination .page-link:hover {
            background: rgba(76,161,175,0.1) !important;
            color: #5bc8d4 !important;
        }

        /* ── Whirlpool group cards ── */
        body.dark-mode .dw-group-card {
            background: rgba(255,255,255,0.03) !important;
            border-color: rgba(76,161,175,0.18) !important;
        }
        body.dark-mode .dw-group-card:hover {
            background: rgba(76,161,175,0.05) !important;
            border-color: rgba(76,161,175,0.35) !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4), 0 0 20px rgba(76,161,175,0.06) !important;
        }

        /* ── Inline style overrides ── */
        body.dark-mode [style*="color: var(--ocean-deep)"],
        body.dark-mode [style*="color:var(--ocean-deep)"] { color: #e2e8f0 !important; }
        body.dark-mode [style*="color: #aaa"],
        body.dark-mode [style*="color:#aaa"] { color: #64748b !important; }
        body.dark-mode [style*="color: #888"],
        body.dark-mode [style*="color:#888"] { color: #64748b !important; }
        body.dark-mode [style*="background: #fff"],
        body.dark-mode [style*="background:#fff"],
        body.dark-mode [style*="background: white"] { background: #1e2a3a !important; }
        body.dark-mode [style*="background: #f8fbff"],
        body.dark-mode [style*="background:#f8fbff"] { background: rgba(255,255,255,0.03) !important; }
        body.dark-mode [style*="border-top: 1px solid #eee"],
        body.dark-mode [style*="border-top:1px solid #eee"] { border-top-color: rgba(255,255,255,0.06) !important; }
        body.dark-mode [style*="color: var(--deep)"],
        body.dark-mode [style*="color:var(--deep)"] { color: #e2e8f0 !important; }

        /* ── Leaderboard "Posisimu" card ── */
        body.dark-mode .d-card[style*="background: linear-gradient(90deg, var(--ocean-deep)"] {
            background: linear-gradient(90deg, #1a2535, #1e3040) !important;
            border: 1px solid rgba(76,161,175,0.2) !important;
        }

        /* ── Pelanggaran status ── */
        body.dark-mode [style*="background:#fff3cd"] {
            background: rgba(251,191,36,0.1) !important;
            color: #fbbf24 !important;
        }

        /* ── Smooth transitions ── */
        body.dark-mode * {
            transition: background-color 0.3s ease, border-color 0.3s ease,
                        color 0.3s ease, box-shadow 0.3s ease !important;
        }
        body.dark-mode svg *, body.dark-mode canvas,
        body.dark-mode .dw-whirlpool-svg, body.dark-mode .whirlpool-svg {
            transition: none !important;
        }
    </style>

    @yield('extra-styles')
</head>

<body>
    @php
        // #region agent log
        @file_put_contents(
            base_path('debug-f13595.log'),
            json_encode([
                'sessionId' => 'f13595',
                'runId' => 'audit-7-issues',
                'hypothesisId' => 'H16',
                'location' => 'resources/views/layouts/dashboard.blade.php:body',
                'message' => 'Dashboard nav rendering snapshot',
                'data' => [
                    'path' => request()->path(),
                    'role' => session('user_role'),
                    'desktopHasManageUsers' => session('user_role') === 'wali_kelas',
                    'bottomNavItems' => ['dashboard', 'kas', 'kehadiran', 'leaderboard', 'profile'],
                    'bottomHasManageUsers' => false,
                    'bottomHasLogout' => false,
                ],
                'timestamp' => (int) round(microtime(true) * 1000),
            ], JSON_UNESCAPED_SLASHES) . PHP_EOL,
            FILE_APPEND
        );
        logger()->info('debug-f13595 H16 dashboard nav snapshot', [
            'sessionId' => 'f13595',
            'runId' => 'audit-7-issues',
            'hypothesisId' => 'H16',
            'location' => 'resources/views/layouts/dashboard.blade.php:body',
            'path' => request()->path(),
            'role' => session('user_role'),
            'desktopHasManageUsers' => session('user_role') === 'wali_kelas',
            'bottomHasManageUsers' => false,
            'bottomHasLogout' => false,
        ]);
        // #endregion
    @endphp
    @php
        $notifUnread = \App\Models\Pesan::where(function ($q) {
            $q->where('ke_user_id', session('user_id'))
                ->orWhere(function ($q2) {
                    $q2->where('is_broadcast', true)
                        ->where('dari_user_id', '!=', session('user_id'));
                });
        })->where('is_read', false)->count();
    @endphp

    {{-- ========== SIDE NAVBAR (Desktop) ========== --}}
    <nav class="side-nav">
        <div class="side-nav-brand">
            <div class="brand-name">⚓ X <span>PPLG</span> C</div>
            <div class="brand-role">{{ session('user_role') === 'wali_kelas' ? 'Wali Kelas' : (session('user_role') === 'bendahara' ? 'Bendahara' : (session('user_role') === 'sekretaris' ? 'Sekretaris' : 'Siswa')) }}</div>
        </div>
        <div class="side-nav-user">
            <div class="side-nav-avatar">{{ strtoupper(substr(session('user_name', 'U'), 0, 1)) }}</div>
            <div class="side-nav-info">
                <div class="info-name">{{ session('user_name') }}</div>
                <div class="info-role">{{ str_replace('_', ' ', session('user_role')) }}</div>
            </div>
        </div>
        <div class="side-nav-menu">
            <div class="nav-section-label">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="ni-icon">🏠</span> Beranda
            </a>
            <a href="{{ route('kas.index') }}" class="nav-item {{ request()->routeIs('kas.*') ? 'active' : '' }}">
                <span class="ni-icon">💰</span> Kas Kelas
            </a>
            <a href="{{ route('kehadiran.index') }}" class="nav-item {{ request()->routeIs('kehadiran.*') ? 'active' : '' }}">
                <span class="ni-icon">📋</span> Kehadiran
            </a>
            <a href="{{ route('leaderboard.index') }}" class="nav-item {{ request()->routeIs('leaderboard.*') ? 'active' : '' }}">
                <span class="ni-icon">🏆</span> Leaderboard
            </a>
            <a href="{{ route('history.index') }}" class="nav-item {{ request()->routeIs('history.*') ? 'active' : '' }}">
                <span class="ni-icon">📜</span> History
            </a>
            <a href="{{ route('pesan.index') }}" class="nav-item {{ request()->routeIs('pesan.*') ? 'active' : '' }}">
                <span class="ni-icon">💬</span> Pesan
                @if($notifUnread > 0)
                <span class="ni-badge">{{ $notifUnread }}</span>
                @endif
            </a>

            {{-- Menu Wali Kelas saja --}}
            @if(session('user_role') === 'wali_kelas')
            <div class="nav-section-label">Manajemen</div>
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <span class="ni-icon">👥</span> Kelola User
            </a>
            @endif

            <div class="nav-section-label">Akun</div>
            <a href="{{ route('profile.index') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <span class="ni-icon">👤</span> Profil
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-item" style="color:rgba(255,100,100,0.8)">
                    <span class="ni-icon">🚪</span> Logout
                </button>
            </form>
        </div>

        {{-- Dark/Light Mode Toggle — Desktop (bottom of side nav) --}}
        <div class="side-nav-toggle">
            <div class="theme-toggle" onclick="toggleTheme()" id="sideToggle">
                <div class="toggle-track" id="sideToggleTrack">
                    <div class="toggle-thumb" id="sideToggleThumb">☀️</div>
                </div>
                <span class="theme-toggle-label" id="sideToggleLabel">Light Mode</span>
            </div>
        </div>
    </nav>

    {{-- ========== TOP BAR (Mobile/Tablet) ========== --}}
    <div class="dash-topbar">
        <div class="topbar-brand">⚓ X <span>PPLG</span> C</div>
        <div style="display:flex; align-items:center; gap:12px;">
            {{-- Dark/Light Mode Toggle — Mobile/Tablet (in topbar) --}}
            <div class="topbar-toggle">
                <div class="theme-toggle" onclick="toggleTheme()" id="topToggle">
                    <div class="toggle-track" id="topToggleTrack">
                        <div class="toggle-thumb" id="topToggleThumb">☀️</div>
                    </div>
                </div>
            </div>
            <div class="topbar-user">{{ session('user_name') }}</div>
        </div>
    </div>

    {{-- ========== MAIN CONTENT ========== --}}
    <main class="dash-main">
        @if(session('success'))
        <div class="flash-msg success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="flash-msg error">❌ {{ session('error') }}</div>
        @endif
        @if($notifUnread > 0 && !request()->routeIs('pesan.*'))
        <div class="flash-msg success">🔔 Ada {{ $notifUnread }} notifikasi/pengumuman baru. Cek menu Pesan.</div>
        @endif

        {{-- ========== WHIRLPOOL RANDOMIZER (All Dashboards) ========== --}}
        <div class="dash-whirlpool-section">
            <div class="dw-header">
                <div class="dw-title">🌪️ Pusaran Air Pengacak Kelompok</div>
                <button class="dw-toggle-btn" id="dwToggleBtn" onclick="dwToggleOpen()">🌪️ Buka Pengacak</button>
            </div>
            <div class="dw-body" id="dwBody">
                <div class="dw-center">
                    {{-- SVG Whirlpool --}}
                    <div class="dw-whirlpool-wrap">
                        <svg id="dwWhirlpoolSVG" class="dw-whirlpool-svg" viewBox="0 0 220 220" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="110" cy="110" r="100" fill="none" stroke="rgba(64,224,208,0.18)" stroke-width="2"/>
                            <circle cx="110" cy="110" r="80" fill="none" stroke="rgba(64,224,208,0.25)" stroke-width="2"/>
                            <circle cx="110" cy="110" r="60" fill="none" stroke="rgba(64,224,208,0.35)" stroke-width="2"/>
                            <circle cx="110" cy="110" r="40" fill="none" stroke="rgba(64,224,208,0.5)" stroke-width="3"/>
                            <circle cx="110" cy="110" r="20" fill="none" stroke="rgba(64,224,208,0.7)" stroke-width="3"/>
                            <g stroke="var(--turq)" stroke-linecap="round" fill="none" opacity="0.7">
                                <path d="M110,10 Q180,40 180,110" stroke-width="3"/>
                                <path d="M210,110 Q180,180 110,180" stroke-width="2.5"/>
                                <path d="M110,210 Q40,180 40,110" stroke-width="2"/>
                                <path d="M10,110 Q40,40 110,40" stroke-width="1.5"/>
                            </g>
                            <circle cx="110" cy="110" r="10" fill="var(--turq)" opacity="0.8"/>
                            <circle cx="110" cy="110" r="5" fill="#fff"/>
                            <circle cx="55" cy="65" r="5" fill="rgba(255,255,255,0.4)"/>
                            <circle cx="170" cy="80" r="4" fill="rgba(255,255,255,0.3)"/>
                            <circle cx="160" cy="160" r="6" fill="rgba(255,255,255,0.3)"/>
                            <circle cx="55" cy="155" r="4" fill="rgba(255,255,255,0.35)"/>
                        </svg>
                        <div class="dw-wp-ring dw-wp-ring-1"></div>
                        <div class="dw-wp-ring dw-wp-ring-2"></div>
                        <div class="dw-wp-ring dw-wp-ring-3"></div>
                    </div>
                    {{-- Form --}}
                    <div class="dw-form">
                        <label class="dw-label" for="dwGroupCount">Mau dibagi jadi berapa kelompok?</label>
                        <div class="dw-input-row">
                            <input type="number" id="dwGroupCount" class="dw-input" min="2" max="10" placeholder="2–10" value="5"/>
                            <button class="dw-btn" id="dwRandBtn" onclick="dwRunRandomizer()">🌪️ Buat Pusaran Air</button>
                        </div>
                        <p class="dw-note">Min 2 kelompok · Max 10 kelompok · Total 35 siswa</p>
                    </div>
                </div>
                {{-- Results --}}
                <div id="dwGroupResults" class="dw-group-results"></div>
            </div>
        </div>

        @yield('content')
    </main>

    {{-- ========== BOTTOM NAVBAR (Mobile/Tablet) ========== --}}
    <nav class="bottom-nav">
        <a href="{{ route('dashboard') }}" class="bot-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <div class="bot-icon-wrap">🏠</div>
            <span class="bot-label">Beranda</span>
        </a>
        <a href="{{ route('kas.index') }}" class="bot-item {{ request()->routeIs('kas.*') ? 'active' : '' }}">
            <div class="bot-icon-wrap">💰</div>
            <span class="bot-label">Kas</span>
        </a>
        <a href="{{ route('kehadiran.index') }}" class="bot-item {{ request()->routeIs('kehadiran.*') ? 'active' : '' }}">
            <div class="bot-icon-wrap">📋</div>
            <span class="bot-label">Hadir</span>
        </a>
        <a href="{{ route('leaderboard.index') }}" class="bot-item {{ request()->routeIs('leaderboard.*') ? 'active' : '' }}">
            <div class="bot-icon-wrap">🏆</div>
            <span class="bot-label">Rank</span>
        </a>
        <a href="{{ route('profile.index') }}" class="bot-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <div class="bot-icon-wrap">👤</div>
            <span class="bot-label">Profil</span>
        </a>
        <a href="{{ route('pesan.index') }}" class="bot-item {{ request()->routeIs('pesan.*') ? 'active' : '' }}">
            <div class="bot-icon-wrap">💬</div>
            <span class="bot-label">Pesan</span>
            @if($notifUnread > 0)
            <span class="bot-badge">{{ $notifUnread }}</span>
            @endif
        </a>
        @if(session('user_role') === 'wali_kelas')
        <a href="{{ route('users.index') }}" class="bot-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <div class="bot-icon-wrap">👥</div>
            <span class="bot-label">User</span>
        </a>
        @endif
    </nav>

    @yield('scripts')

    {{-- ========== DARK/LIGHT MODE + WHIRLPOOL JS ========== --}}
    <script>
        /* ─── DARK / LIGHT MODE ─────────────────────────────── */
        (function() {
            const saved = localStorage.getItem('dashTheme') || 'light';
            if (saved === 'dark') {
                document.body.classList.add('dark-mode');
            }
        })();

        function toggleTheme() {
            const isDark = document.body.classList.toggle('dark-mode');
            localStorage.setItem('dashTheme', isDark ? 'dark' : 'light');
            updateToggleUI(isDark);
        }

        function updateToggleUI(isDark) {
            const tracks = document.querySelectorAll('.toggle-track');
            const thumbs = document.querySelectorAll('.toggle-thumb');
            const label = document.getElementById('sideToggleLabel');

            tracks.forEach(t => t.classList.toggle('on', isDark));
            thumbs.forEach(th => { th.textContent = isDark ? '🌙' : '☀️'; });
            if (label) label.textContent = isDark ? 'Dark Mode' : 'Light Mode';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.body.classList.contains('dark-mode');
            updateToggleUI(isDark);
        });

        /* ─── WHIRLPOOL RANDOMIZER ───────────────────────────── */
        const DW_SISWA = [
            'Ahyar', 'Aisyah', 'Alfino', 'Amelia', 'Arda',
            'Asroh', 'Cantika', 'Dea', 'Early', 'Evita',
            'Farhan', 'Faris', 'Fauzan', 'Fitria', 'Kiran',
            'Kustian', 'Livia', 'Meli', 'Mila', 'Nabila',
            'Nayla', 'Rafi', 'Regita', 'Renita', 'Rezza',
            'Rido Ganteng', 'Risha', 'Sri', 'Vina', 'Windi',
            'Wulan', 'Yunisa', 'Zaskya', 'Zein', 'Keyinaa Cantikk',
        ];

        const DW_ICONS = ['⛵','🚢','🛥️','🪸','🐡','🦑','🐙','🦀','🐠','🦈'];

        function dwToggleOpen() {
            const body = document.getElementById('dwBody');
            const btn = document.getElementById('dwToggleBtn');
            if (!body) return;
            const isOpen = body.classList.toggle('open');
            btn.textContent = isOpen ? '▲ Tutup' : '🌪️ Buka Pengacak';
        }

        function dwShuffle(arr) {
            const s = [...arr];
            for (let i = s.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [s[i], s[j]] = [s[j], s[i]];
            }
            return s;
        }

        function dwSplitGroups(arr, n) {
            const groups = [];
            const base = Math.floor(arr.length / n);
            const rem = arr.length % n;
            let cur = 0;
            for (let g = 0; g < n; g++) {
                const size = base + (g < rem ? 1 : 0);
                groups.push(arr.slice(cur, cur + size));
                cur += size;
            }
            return groups;
        }

        function dwRenderCards(groups) {
            const container = document.getElementById('dwGroupResults');
            if (!container) return;
            container.innerHTML = '';
            groups.forEach((members, gi) => {
                const icon = DW_ICONS[gi % DW_ICONS.length];
                const card = document.createElement('div');
                card.className = 'dw-group-card';
                card.innerHTML = `
                    <div class="dw-card-header">
                        <span class="dw-card-icon">${icon}</span>
                        <span class="dw-card-title">Grup ${gi + 1}</span>
                        <span class="dw-card-count">${members.length} orng</span>
                    </div>
                    <div class="dw-card-divider"></div>
                    <ul class="dw-member-list">
                        ${members.map((name, i) => `<li class="dw-member ${i === 0 ? 'captain' : ''}">${name}</li>`).join('')}
                    </ul>
                `;
                container.appendChild(card);
            });

            // Simple CSS animation fallback (no GSAP dependency)
            container.querySelectorAll('.dw-group-card').forEach((card, i) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = `opacity 0.4s ease ${i * 0.07}s, transform 0.4s ease ${i * 0.07}s`;
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    });
                });
            });
        }

        function dwRunRandomizer() {
            const input = document.getElementById('dwGroupCount');
            const btn = document.getElementById('dwRandBtn');
            const svg = document.getElementById('dwWhirlpoolSVG');
            if (!input || !btn || !svg) return;

            const n = parseInt(input.value, 10);
            if (isNaN(n) || input.value.trim() === '') { dwAlert('Isi dulu jumlah kelompoknya!'); return; }
            if (n < 2) { dwAlert('⛵ Minimal 2 kelompok ya!'); return; }
            if (n > 10) { dwAlert('🦑 Maksimal 10 kelompok!'); return; }
            if (n > DW_SISWA.length) { dwAlert(`🐙 Melebihi jumlah siswa (${DW_SISWA.length})!`); return; }

            btn.disabled = true;
            svg.style.animation = 'none';
            svg.style.transition = 'transform 1.8s cubic-bezier(0.55,0,1,0.45)';
            svg.style.transform = 'rotate(1080deg) scale(1.25)';

            setTimeout(() => {
                svg.style.transform = 'rotate(1080deg) scale(1)';
                setTimeout(() => {
                    svg.style.transition = '';
                    svg.style.transform = '';
                    svg.style.animation = '';
                    const shuffled = dwShuffle(DW_SISWA);
                    const groups = dwSplitGroups(shuffled, n);
                    dwRenderCards(groups);
                    btn.disabled = false;
                }, 400);
            }, 1800);
        }

        function dwAlert(msg) {
            let el = document.getElementById('dwAlert');
            if (!el) {
                el = document.createElement('div');
                el.id = 'dwAlert';
                el.style.cssText = 'position:fixed;top:80px;left:50%;transform:translateX(-50%) translateY(-20px);background:linear-gradient(135deg,#001f3f,#003060);color:#fff;font-family:"Baloo 2",cursive;font-weight:700;font-size:0.88rem;padding:12px 22px;border-radius:50px;border:1.5px solid rgba(64,224,208,0.5);box-shadow:0 8px 28px rgba(0,0,0,0.4);z-index:9999;white-space:nowrap;opacity:0;pointer-events:none;transition:opacity 0.3s,transform 0.3s;';
                document.body.appendChild(el);
            }
            el.textContent = msg;
            el.style.opacity = '1';
            el.style.transform = 'translateX(-50%) translateY(0)';
            clearTimeout(el._t);
            el._t = setTimeout(() => {
                el.style.opacity = '0';
                el.style.transform = 'translateX(-50%) translateY(-20px)';
            }, 2800);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('dwGroupCount');
            if (input) {
                input.addEventListener('keydown', e => { if (e.key === 'Enter') dwRunRandomizer(); });
            }
        });
    </script>
</body>

</html>