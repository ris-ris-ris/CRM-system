<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script>
        // Enable JS-driven animations BEFORE first paint (scroll reveal, counts, etc.)
        document.documentElement.classList.add('js');
    </script>
    <title>CRM</title>
    <link rel="icon" href="/logo.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        html {
            width: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }
        
        body { 
            overflow-x: hidden; 
            /* background should exist immediately (no white flash) */
            background: linear-gradient(180deg, #000000 0%, #0a0a0f 50%, #000000 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            width: 100%;
            margin: 0;
            padding: 0;
            max-width: 100%;
            position: relative;
        }

        /* =========================================================
           Opening animation (NO white overlay, NO loader):
           Stable “cinematic fade + lift” (no clip-path = no flicker).
           ========================================================= */
        .site-reveal{
            position: relative;
            min-height: 100vh;
            z-index: 1; /* keep UI above background layers */
            opacity: 0;
            transform: translateY(18px) scale(0.992);
            filter: blur(10px);
            animation: introIn .95s cubic-bezier(.2,.9,.2,1) .06s forwards;
            will-change: transform, opacity, filter;
        }
        @keyframes introIn{
            0%{ opacity: 0; transform: translateY(18px) scale(0.992); filter: blur(10px); }
            70%{ opacity: 1; }
            100%{ opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
        }

        /* subtle “glint” sweep on first load, doesn’t block UI */
        body::after{
            content:'';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 2;
            opacity: 0;
            transform: translateX(-130%);
            background: linear-gradient(90deg,
                transparent 0%,
                rgba(255,255,255,0.06) 42%,
                rgba(255,255,255,0.16) 50%,
                rgba(255,255,255,0.06) 58%,
                transparent 100%);
            mix-blend-mode: overlay;
            filter: blur(0.6px);
            animation: introGlint .85s ease .14s both;
        }
        @keyframes introGlint{
            0%{ opacity: 0; transform: translateX(-130%); }
            18%{ opacity: 0.9; }
            100%{ opacity: 0; transform: translateX(130%); }
        }

        /* If JS never runs, don't hide content permanently */
        html:not(.js) .site-reveal{ opacity: 1; transform: none; filter: none; animation: none !important; }
        /* NOTE: do not disable animations here — landing is designed to be animated */
        
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(139, 92, 246, 0.45) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(34, 197, 94, 0.4) 0%, transparent 50%),
                radial-gradient(circle at 50% 20%, rgba(139, 92, 246, 0.35) 0%, transparent 50%),
                radial-gradient(circle at 60% 60%, rgba(34, 197, 94, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 30% 70%, rgba(255, 107, 53, 0.25) 0%, transparent 50%),
                radial-gradient(circle at 70% 30%, rgba(139, 92, 246, 0.3) 0%, transparent 50%),
                linear-gradient(180deg, #000000 0%, #0a0a0f 50%, #000000 100%);
            background-size: 200% 200%, 200% 200%, 200% 200%, 200% 200%, 200% 200%, 200% 200%, 100% 100%;
            animation: bgShift 8s ease-in-out infinite;
            overflow: hidden;
        }

        /* cinematic vignette + subtle grain (cheap) */
        .animated-bg::before {
            content: '';
            position: absolute;
            inset: -20%;
            background:
                radial-gradient(circle at 50% 35%, rgba(255,255,255,0.04) 0%, transparent 55%),
                radial-gradient(circle at 50% 50%, transparent 0%, rgba(0,0,0,0.55) 65%, rgba(0,0,0,0.82) 100%);
            pointer-events: none;
            mix-blend-mode: multiply;
            opacity: 1;
        }

        .animated-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                repeating-linear-gradient(
                    0deg,
                    rgba(255,255,255,0.018) 0px,
                    rgba(255,255,255,0.018) 1px,
                    rgba(0,0,0,0) 2px,
                    rgba(0,0,0,0) 4px
                );
            opacity: 0.10;
            pointer-events: none;
            mix-blend-mode: overlay;
            animation: grainShift 6s steps(2) infinite;
        }

        @keyframes grainShift {
            0%, 100% { transform: translate3d(0,0,0); }
            25% { transform: translate3d(-1%, 0.5%, 0); }
            50% { transform: translate3d(0.8%, -0.6%, 0); }
            75% { transform: translate3d(-0.4%, -0.2%, 0); }
        }
        
        #three-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            opacity: 0.72;
            filter: saturate(1.15) contrast(1.05);
        }
        
        @keyframes bgShift {
            0%, 100% {
                background-position: 0% 50%, 100% 50%, 50% 0%, 40% 60%, 70% 30%, 30% 70%, 0% 0%;
            }
            20% {
                background-position: 30% 30%, 70% 70%, 30% 70%, 50% 50%, 50% 50%, 60% 40%, 0% 0%;
            }
            40% {
                background-position: 50% 50%, 50% 50%, 50% 50%, 60% 40%, 30% 70%, 40% 60%, 0% 0%;
            }
            60% {
                background-position: 70% 70%, 30% 30%, 70% 30%, 40% 60%, 50% 50%, 50% 50%, 0% 0%;
            }
            80% {
                background-position: 80% 20%, 20% 80%, 20% 80%, 70% 30%, 60% 40%, 70% 20%, 0% 0%;
            }
        }
        
        .container {
            min-height: 100vh;
            max-width: 1600px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 4rem 2rem;
            position: relative;
            z-index: 1;
            box-sizing: border-box;
            opacity: 1;
            transform: none;
        }
        
        /* убрали expandIn, чтобы заголовок не "сползал" при открытии */
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(60px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .hero-title {
            font-size: clamp(4rem, 12vw, 10rem);
            font-weight: 900;
            margin-bottom: 2rem;
            letter-spacing: -0.05em;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 25%, #ff8c42 50%, #ff6b35 75%, #f7931e 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 4s ease infinite, heroBloom 1.15s ease-out .02s both;
            position: relative;
            line-height: 1.1;
            z-index: 2;
            transform: translateY(0);
            opacity: 1;
        }

        /* startup glow expansion around “CRM” */
        @keyframes heroBloom{
            0%{
                filter:
                    drop-shadow(0 0 0 rgba(255,107,53,0))
                    drop-shadow(0 0 0 rgba(247,147,30,0))
                    drop-shadow(0 0 0 rgba(139,92,246,0));
            }
            55%{
                filter:
                    drop-shadow(0 26px 44px rgba(255,107,53,0.34))
                    drop-shadow(0 40px 78px rgba(247,147,30,0.26))
                    drop-shadow(0 54px 110px rgba(139,92,246,0.20));
            }
            100%{
                filter:
                    drop-shadow(0 16px 34px rgba(255,107,53,0.26))
                    drop-shadow(0 26px 56px rgba(247,147,30,0.20))
                    drop-shadow(0 36px 80px rgba(139,92,246,0.14));
            }
        }
        
        @keyframes gradientShift {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }
        
        /* Полоска - источник света */
        .hero-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 300px;
            height: 8px;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255, 107, 53, 0.4) 20%,
                rgba(247, 147, 30, 0.7) 40%,
                rgba(255, 140, 66, 0.7) 50%,
                rgba(247, 147, 30, 0.7) 60%,
                rgba(255, 107, 53, 0.4) 80%,
                transparent 100%);
            background-size: 200% 100%;
            border-radius: 4px;
            animation: fadeIn 1.5s ease-out, spotlightGlow 8s ease-in-out infinite, barShimmer 6s linear infinite;
            box-shadow: 
                0 0 20px rgba(255, 107, 53, 0.4),
                0 0 40px rgba(247, 147, 30, 0.3),
                0 0 60px rgba(255, 140, 66, 0.2);
            z-index: 1;
        }
        
        @keyframes spotlightGlow {
            0%, 100% {
                opacity: 0.85;
                transform: translateX(-50%) scaleX(1);
                box-shadow: 
                    0 0 18px rgba(255, 107, 53, 0.35),
                    0 0 35px rgba(247, 147, 30, 0.25),
                    0 0 55px rgba(255, 140, 66, 0.15);
            }
            50% {
                opacity: 1;
                transform: translateX(-50%) scaleX(1.12);
                box-shadow: 
                    0 0 25px rgba(255, 107, 53, 0.5),
                    0 0 50px rgba(247, 147, 30, 0.4),
                    0 0 75px rgba(255, 140, 66, 0.3);
            }
        }
        
        @keyframes barShimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }
        
        /* Лучи света, идущие вверх от полоски - как у памятников */
        .hero-title::before {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 500px;
            background: 
                radial-gradient(ellipse 600px 500px at center bottom,
                    rgba(255, 107, 53, 0.35) 0%,
                    rgba(247, 147, 30, 0.3) 10%,
                    rgba(255, 140, 66, 0.25) 20%,
                    rgba(255, 107, 53, 0.2) 35%,
                    rgba(247, 147, 30, 0.15) 50%,
                    rgba(255, 140, 66, 0.08) 70%,
                    transparent 100%);
            background-size: 100% 100%;
            z-index: 0;
            animation: raysUp 8s ease-in-out infinite, glowPulse 7s ease-in-out infinite, glowShift 10s ease-in-out infinite;
            pointer-events: none;
            filter: blur(35px);
            opacity: 0.82;
        }
        
        @keyframes raysUp {
            0%, 100% {
                transform: translateX(-50%) translateY(0) scale(1);
            }
            50% {
                transform: translateX(-50%) translateY(-3px) scale(1.03);
            }
        }
        
        @keyframes glowPulse {
            0%, 100% {
                opacity: 0.5;
                filter: blur(32px);
            }
            25% {
                opacity: 0.65;
                filter: blur(38px);
            }
            50% {
                opacity: 0.75;
                filter: blur(42px);
            }
            75% {
                opacity: 0.6;
                filter: blur(35px);
            }
        }
        
        @keyframes glowShift {
            0%, 100% {
                background: 
                    radial-gradient(ellipse 600px 500px at center bottom,
                        rgba(255, 107, 53, 0.35) 0%,
                        rgba(247, 147, 30, 0.3) 10%,
                        rgba(255, 140, 66, 0.25) 20%,
                        rgba(255, 107, 53, 0.2) 35%,
                        rgba(247, 147, 30, 0.15) 50%,
                        rgba(255, 140, 66, 0.08) 70%,
                        transparent 100%);
            }
            33% {
                background: 
                    radial-gradient(ellipse 650px 550px at center bottom,
                        rgba(247, 147, 30, 0.4) 0%,
                        rgba(255, 140, 66, 0.35) 10%,
                        rgba(255, 107, 53, 0.3) 20%,
                        rgba(247, 147, 30, 0.25) 35%,
                        rgba(255, 140, 66, 0.18) 50%,
                        rgba(255, 107, 53, 0.1) 70%,
                        transparent 100%);
            }
            66% {
                background: 
                    radial-gradient(ellipse 580px 480px at center bottom,
                        rgba(255, 140, 66, 0.38) 0%,
                        rgba(255, 107, 53, 0.33) 10%,
                        rgba(247, 147, 30, 0.28) 20%,
                        rgba(255, 140, 66, 0.22) 35%,
                        rgba(255, 107, 53, 0.17) 50%,
                        rgba(247, 147, 30, 0.12) 70%,
                        transparent 100%);
            }
        }
        
        
        .hero-subtitle {
            font-size: clamp(1.2rem, 4vw, 2rem);
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 4rem;
            max-width: 800px;
            font-weight: 400;
            line-height: 1.6;
        }
        
        .btn-container {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 6rem;
        }
        
        .btn {
            padding: 20px 50px;
            font-size: 1.2rem;
            font-weight: 700;
            border-radius: 14px;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
            color: white;
            box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 20px 60px rgba(239, 68, 68, 0.6);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-5px) scale(1.05);
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn:hover::before {
            width: 400px;
            height: 400px;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            width: 100%;
            padding: 0;
            box-sizing: border-box;
            margin: 0;
        }
        
        .feature-card {
            background: linear-gradient(135deg, rgba(17, 24, 39, 0.8) 0%, rgba(31, 41, 55, 0.6) 100%);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 0;
            text-align: left;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            opacity: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
            animation: cardFloat 6s ease-in-out infinite;
        }
        
        .feature-card:nth-child(1) {
            animation-delay: 0s;
        }
        
        .feature-card:nth-child(2) {
            animation-delay: 1.5s;
        }
        
        .feature-card:nth-child(3) {
            animation-delay: 3s;
        }
        
        .feature-card:nth-child(4) {
            animation-delay: 4.5s;
        }
        
        @keyframes cardFloat {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-8px);
            }
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ef4444, #f97316, #3b82f6);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.5s;
        }
        
        .feature-card:hover::before {
            transform: scaleX(1);
        }
        
        .feature-card:nth-child(1) {
            animation: scaleIn 0.8s ease-out 0.3s both;
        }
        
        .feature-card:nth-child(2) {
            animation: scaleIn 0.8s ease-out 0.4s both;
        }
        
        .feature-card:nth-child(3) {
            animation: scaleIn 0.8s ease-out 0.5s both;
        }
        
        .feature-card:nth-child(4) {
            animation: scaleIn 0.8s ease-out 0.6s both;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            border-color: rgba(239, 68, 68, 0.5);
            box-shadow: 0 30px 80px rgba(239, 68, 68, 0.15),
                        0 0 40px rgba(251, 146, 60, 0.1);
        }
        
        .feature-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(251, 146, 60, 0.2) 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .feature-image::before {
            content: '';
            position: absolute;
            inset: 0;
            background: 
                radial-gradient(circle at 30% 30%, rgba(239, 68, 68, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 70% 70%, rgba(251, 146, 60, 0.3) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.5s;
        }
        
        .feature-card:hover .feature-image::before {
            opacity: 1;
        }
        
        .feature-icon-wrapper {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(251, 146, 60, 0.2) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            transition: all 0.5s;
        }
        
        .feature-card:hover .feature-icon-wrapper {
            transform: scale(1.1) rotate(5deg);
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.4) 0%, rgba(251, 146, 60, 0.4) 100%);
        }
        
        .feature-icon {
            width: 48px;
            height: 48px;
            stroke: #ef4444;
            fill: none;
            stroke-width: 2;
            transition: all 0.5s;
        }
        
        .feature-card:hover .feature-icon {
            stroke: #f97316;
            stroke-width: 2.5;
        }
        
        .feature-content {
            padding: 2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
            line-height: 1.3;
        }
        
        .feature-desc {
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.7;
            font-size: 0.95rem;
            flex: 1;
        }
        
        .feature-link {
            margin-top: 1.5rem;
            color: #ef4444;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }
        
        .feature-link:hover {
            color: #f97316;
            gap: 1rem;
        }
        
        @media (max-width: 1400px) {
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        .overview-section {
            width: 100%;
            margin-top: 10rem;
            padding: 6rem 0;
            position: relative;
        }
        
        .overview-title {
            font-size: clamp(3rem, 8vw, 5rem);
            font-weight: 900;
            text-align: center;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 25%, #ff8c42 50%, #ff6b35 75%, #f7931e 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 4s ease infinite;
            filter: drop-shadow(0 0 30px rgba(255, 107, 53, 0.4));
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 1s ease, transform 1s ease;
        }
        
        .overview-title.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .overview-subtitle {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.3rem;
            margin-bottom: 5rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 1s ease 0.3s, transform 1s ease 0.3s;
        }
        
        .overview-subtitle.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .overview-item {
            margin-bottom: 8rem;
            opacity: 0;
            transform: translateY(70px) scale(0.985);
            filter: blur(4px);
            transition: opacity 1s ease, transform 1s ease, filter 1s ease;
            will-change: transform, opacity, filter;
        }
        
        .overview-item.visible {
            opacity: 1;
            transform: translateY(0) scale(1);
            filter: blur(0);
        }
        
        .overview-item-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            padding: 3rem;
            background: linear-gradient(135deg, rgba(17, 24, 39, 0.6) 0%, rgba(31, 41, 55, 0.4) 100%);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 32px;
            position: relative;
            overflow: hidden;
            transition: all 0.5s ease;
        }
        
        .overview-item-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 107, 53, 0.1), transparent);
            transition: left 0.8s ease;
        }
        
        .overview-item-content:hover::before {
            left: 100%;
        }
        
        .overview-item-content:hover {
            transform: translateY(-10px);
            border-color: rgba(255, 107, 53, 0.3);
            box-shadow: 0 30px 80px rgba(255, 107, 53, 0.2);
        }
        
        .overview-item:nth-child(even) .overview-item-content {
            direction: rtl;
        }
        
        .overview-item:nth-child(even) .overview-item-content > * {
            direction: ltr;
        }
        
        .overview-text {
            z-index: 1;
        }
        
        .overview-text h3 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .overview-text p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.9;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        
        .overview-features {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .overview-features li {
            color: rgba(255, 255, 255, 0.7);
            padding: 0.75rem 0;
            padding-left: 2rem;
            position: relative;
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .overview-features li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #ff6b35;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .overview-visual {
            z-index: 1;
            position: relative;
        }
        
        .overview-visual-box {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.2) 0%, rgba(247, 147, 30, 0.2) 100%);
            border-radius: 24px;
            padding: 3rem;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            transform: translateY(0);
            transition: transform 0.35s ease;
            will-change: transform;
        }

        /* Chart "build" feel on enter */
        .chart-wrap canvas {
            opacity: 0;
            transform: translateY(14px);
            transition: opacity 0.6s ease, transform 0.6s ease;
            will-change: transform, opacity;
        }

        .chart-wrap.chart-enter canvas {
            opacity: 1;
            transform: translateY(0);
        }
        
        .overview-visual-box::after {
            content: '';
            position: absolute;
            inset: 0;
            background: 
                radial-gradient(circle at 30% 30%, rgba(255, 107, 53, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 70% 70%, rgba(247, 147, 30, 0.3) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        
        .overview-item-content:hover .overview-visual-box::after {
            opacity: 1;
        }
        
        .overview-icon-large {
            width: 120px;
            height: 120px;
            stroke: #ff6b35;
            fill: none;
            stroke-width: 1.5;
            opacity: 0.6;
        }
        
        .overview-metrics-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            width: 100%;
        }
        
        .overview-metric-card {
            background: linear-gradient(135deg, rgba(17, 24, 39, 0.9) 0%, rgba(31, 41, 55, 0.7) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .overview-metric-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 107, 53, 0.5);
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.2);
        }
        
        .overview-metric-value {
            font-size: 2rem;
            font-weight: 900;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .overview-metric-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .overview-metric-change {
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
            display: inline-block;
        }
        
        .overview-metric-change.positive {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }
        
        .overview-metric-change.negative {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        /* Scroll progress indicator */
        .scroll-progress {
            position: fixed;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 220px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(10px);
            z-index: 9998;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        }

        .scroll-progress > div {
            width: 100%;
            height: 0%;
            border-radius: 999px;
            background: linear-gradient(180deg, #ef4444 0%, #f97316 60%, rgba(255,255,255,0.15) 100%);
            box-shadow: 0 0 20px rgba(249, 115, 22, 0.35);
            transition: height 0.12s linear;
        }

        @media (max-width: 768px) {
            .scroll-progress { display: none; }
        }
        
        .overview-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 4rem;
        }
        
        .overview-stat {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, rgba(17, 24, 39, 0.8) 0%, rgba(31, 41, 55, 0.6) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            transition: all 0.4s ease;
            opacity: 0;
            transform: translateY(30px);
        }
        
        .overview-stat.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .overview-stat:hover {
            transform: translateY(-10px);
            border-color: rgba(255, 107, 53, 0.5);
            box-shadow: 0 20px 60px rgba(255, 107, 53, 0.2);
        }
        
        .overview-stat-number {
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .overview-stat-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
        }
        
        @media (max-width: 1024px) {
            .overview-item-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .overview-item:nth-child(even) .overview-item-content {
                direction: ltr;
            }
            
            .overview-stats {
                grid-template-columns: 1fr;
            }
        }
        
        .testimonials-section {
            width: 100%;
            margin-top: 10rem;
            padding: 4rem 0;
        }
        
        .testimonials-title {
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 800;
            text-align: center;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 25%, #ff8c42 50%, #ff6b35 75%, #f7931e 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: fadeInUp 1s ease-out, gradientShift 4s ease infinite;
            filter: drop-shadow(0 0 20px rgba(255, 107, 53, 0.3));
        }
        
        .testimonials-subtitle {
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 1.2rem;
            margin-bottom: 4rem;
            animation: fadeInUp 1.2s ease-out 0.2s both;
        }
        
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            width: 100%;
        }
        
        .testimonial-card {
            background: linear-gradient(135deg, rgba(17, 24, 39, 0.8) 0%, rgba(31, 41, 55, 0.6) 100%);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2.5rem;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            opacity: 0;
            animation: scaleIn 0.8s ease-out both;
        }
        
        .testimonial-card:nth-child(1) {
            animation-delay: 0.1s;
        }
        
        .testimonial-card:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .testimonial-card:nth-child(3) {
            animation-delay: 0.3s;
        }
        
        .testimonial-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ef4444, #f97316, #3b82f6);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.5s;
        }
        
        .testimonial-card:hover::before {
            transform: scaleX(1);
        }
        
        .testimonial-card:hover {
            transform: translateY(-10px);
            border-color: rgba(239, 68, 68, 0.5);
            box-shadow: 0 30px 80px rgba(239, 68, 68, 0.15),
                        0 0 40px rgba(251, 146, 60, 0.1);
        }
        
        .testimonial-stars {
            display: flex;
            gap: 0.3rem;
            margin-bottom: 1.5rem;
        }
        
        .star {
            color: #fbbf24;
            font-size: 1.2rem;
        }
        
        .testimonial-text {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.8;
            font-size: 1rem;
            margin-bottom: 2rem;
            font-style: italic;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .testimonial-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(30, 41, 59, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 2px solid transparent;
            background-clip: padding-box;
        }
        
        .testimonial-avatar svg {
            stroke: url(#orangeGradient);
        }
        
        .testimonial-info {
            flex: 1;
        }
        
        .testimonial-name {
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.3rem;
        }
        
        .testimonial-role {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }
        
        @media (max-width: 1200px) {
            .testimonials-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 3.5rem;
            }
            
            .btn-container {
                flex-direction: column;
                width: 100%;
            }
            
            .btn {
                width: 100%;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .testimonials-grid {
                grid-template-columns: 1fr;
            }
            
            .testimonials-section {
                margin-top: 4rem;
                padding: 2rem 0;
            }
        }
        
        /* Registration Modal */
        .registration-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            z-index: 100000;
            isolation: isolate;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        
        .registration-modal.active {
            opacity: 1;
            pointer-events: all;
        }
        
        .registration-modal-content {
            background: linear-gradient(135deg, rgba(17, 24, 39, 0.95) 0%, rgba(31, 41, 55, 0.95) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2.5rem;
            max-width: 500px;
            width: 100%;
            position: relative;
            z-index: 100001;
            transform: scale(0.9) translateY(20px);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }
        
        .registration-modal.active .registration-modal-content {
            transform: scale(1) translateY(0);
        }
        
        .registration-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .registration-close:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }
        
        .registration-cat {
            position: absolute;
            right: -50px;
            bottom: -30px;
            width: 180px;
            height: 180px;
            z-index: 10;
            animation: catWave 2s ease-in-out infinite;
            background: transparent !important;
            overflow: hidden;
        }
        
        .registration-cat canvas {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
        }
        
        @keyframes catWave {
            0%, 100% {
                transform: rotate(-5deg) translateY(0);
            }
            50% {
                transform: rotate(5deg) translateY(-10px);
            }
        }
        
        .registration-title {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .registration-text {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.7;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }
        
        .registration-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.4);
        }
        
        .registration-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(239, 68, 68, 0.6);
        }
        
        .registration-link svg {
            width: 20px;
            height: 20px;
        }
        
        @media (max-width: 640px) {
            .registration-cat {
                display: none;
            }
            
            .registration-modal-content {
                padding: 2rem 1.5rem;
            }
        }

        /* =========================================================
           Premium Bento Section (replaces cheap rectangles)
           ========================================================= */
        .bento-section{
            width: 100%;
            margin-top: 6rem;
            padding: 3.5rem 0 1rem;
        }
        .bento-heading{
            text-align: center;
            max-width: 980px;
            margin: 0 auto 2.5rem;
        }
        .bento-title{
            font-size: clamp(2.4rem, 6vw, 4.2rem);
            font-weight: 900;
            letter-spacing: -0.04em;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 35%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 0 24px rgba(255,107,53,0.18));
            margin-bottom: 0.75rem;
        }
        .bento-sub{
            font-size: 1.1rem;
            color: rgba(255,255,255,0.70);
            line-height: 1.8;
        }

        .bento-grid{
            width: 100%;
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 1.25rem;
        }

        .bento-card{
            position: relative;
            overflow: hidden;
            border-radius: 28px;
            padding: 1.35rem 1.35rem 1.15rem;
            background:
                radial-gradient(1200px 600px at 10% 0%, rgba(255,107,53,0.14), transparent 55%),
                radial-gradient(1000px 600px at 100% 20%, rgba(139,92,246,0.14), transparent 55%),
                linear-gradient(135deg, rgba(17, 24, 39, 0.86) 0%, rgba(31, 41, 55, 0.62) 100%);
            border: 1px solid rgba(255,255,255,0.10);
            backdrop-filter: blur(26px);
            box-shadow: 0 22px 70px rgba(0,0,0,0.48);
            transform: translateZ(0);
        }
        /* animated “premium” border */
        .bento-card::before{
            content:'';
            position:absolute;
            inset:-2px;
            background: conic-gradient(from 180deg,
                rgba(255,107,53,0.0),
                rgba(255,107,53,0.55),
                rgba(247,147,30,0.45),
                rgba(59,130,246,0.35),
                rgba(139,92,246,0.45),
                rgba(255,107,53,0.0)
            );
            filter: blur(10px);
            opacity: .0;
            transition: opacity .35s ease;
            animation: bentoBorderSpin 6.5s linear infinite;
            z-index: 0;
        }
        .bento-card::after{
            content:'';
            position:absolute;
            inset: 1px;
            border-radius: 27px;
            background:
                radial-gradient(720px 420px at var(--mx, 40%) var(--my, 30%), rgba(255,255,255,0.14), transparent 55%),
                radial-gradient(900px 520px at 0% 0%, rgba(255,255,255,0.08), transparent 58%),
                radial-gradient(900px 520px at 100% 0%, rgba(255,255,255,0.05), transparent 58%),
                linear-gradient(135deg, rgba(17, 24, 39, 0.88) 0%, rgba(31, 41, 55, 0.66) 100%);
            z-index: 1;
        }
        .bento-card > *{ position: relative; z-index: 2; }
        .bento-card:hover::before{ opacity: .85; }
        @keyframes bentoBorderSpin{ to { transform: rotate(360deg); } }

        .bento-card:hover{
            transform: translateY(-8px);
            transition: transform .45s cubic-bezier(.2,.9,.2,1);
        }

        /* 3D tilt container */
        .bento-card[data-tilt]{
            transform-style: preserve-3d;
            will-change: transform;
            /* smooth return when leaving */
            transition: transform .18s cubic-bezier(.2,.9,.2,1);
        }
        /* Let JS tilt control hover transform (avoid fighting CSS hover) */
        .bento-card[data-tilt]:hover{ transform: none; }
        .bento-card[data-tilt] .tilt-depth{
            transform: translateZ(16px);
        }

        /* Tilt for usecase cards */
        .usecase-card[data-tilt]{
            transform-style: preserve-3d;
            will-change: transform;
            transition: transform .18s cubic-bezier(.2,.9,.2,1);
        }
        .usecase-card[data-tilt] .tilt-depth{ transform: translateZ(14px); }

        /* Demo hub tabs/panels */
        .demo-top{
            display:flex;
            align-items:flex-end;
            justify-content: space-between;
            gap: 18px;
            flex-wrap: wrap;
        }
        .demo-tabs{
            display:flex;
            gap: 10px;
            flex-wrap: wrap;
            padding: 8px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(2,6,23,0.22);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
        }
        .demo-tab{
            position: relative;
            appearance: none;
            border: 0;
            background: transparent;
            color: rgba(255,255,255,0.78);
            font-weight: 900;
            letter-spacing: .01em;
            padding: 10px 14px;
            border-radius: 999px;
            cursor: pointer;
            transition: background-color .25s ease, color .25s ease, transform .25s ease;
            white-space: nowrap;
        }
        .demo-tab:hover{ background: rgba(255,255,255,0.06); transform: translateY(-1px); }
        .demo-tab.is-active{
            background: linear-gradient(135deg, rgba(239,68,68,0.24) 0%, rgba(249,115,22,0.18) 100%);
            color: rgba(255,255,255,0.95);
            box-shadow: 0 14px 35px rgba(249,115,22,0.10);
        }

        .demo-panels{
            margin-top: 1.2rem;
            position: relative;
        }
        .demo-panel{
            display: none;
            border-radius: 22px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(2,6,23,0.22);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
            overflow: hidden;
        }
        .demo-panel.is-active{
            display: block;
            animation: demoIn .65s cubic-bezier(.2,.9,.2,1) both;
        }
        @keyframes demoIn{
            from{ opacity: 0; transform: translateY(14px) scale(.992); filter: blur(6px); }
            to{ opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
        }

        .demo-grid{
            display:grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 14px;
            padding: 14px;
        }
        @media (max-width: 1100px){
            .demo-grid{ grid-template-columns: 1fr; }
        }

        .demo-side{
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(255,255,255,0.04);
            padding: 14px;
            overflow: hidden;
            position: relative;
        }
        .demo-side::before{
            content:'';
            position:absolute;
            inset:-60%;
            background: radial-gradient(circle at 30% 30%, rgba(59,130,246,0.16), transparent 55%),
                        radial-gradient(circle at 70% 70%, rgba(239,68,68,0.14), transparent 55%);
            filter: blur(22px);
            opacity: .6;
            animation: demoSideGlow 8.5s ease-in-out infinite;
        }
        @keyframes demoSideGlow{
            0%,100%{ transform: translate3d(0,0,0) scale(1); }
            50%{ transform: translate3d(2%, -2%, 0) scale(1.05); }
        }
        .demo-side > *{ position: relative; z-index: 1; }

        /* Inbox animation */
        .inbox{
            display:grid;
            gap: 10px;
        }
        .msg{
            border-radius: 16px;
            padding: 12px 12px 10px;
            border: 1px solid rgba(255,255,255,0.10);
            background: linear-gradient(135deg, rgba(15,23,42,0.82) 0%, rgba(15,23,42,0.55) 100%);
            position: relative;
            overflow: hidden;
        }
        .msg::before{
            content:'';
            position:absolute;
            inset:-40%;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.12), transparent 45%);
            opacity: .0;
            transform: translateX(-20%);
            animation: msgSweep 3.2s ease-in-out infinite;
        }
        @keyframes msgSweep{
            0%{ opacity: 0; transform: translateX(-40%); }
            30%{ opacity: .55; }
            60%{ opacity: 0; transform: translateX(40%); }
            100%{ opacity: 0; }
        }
        .msg-h{
            display:flex;
            align-items:center;
            justify-content: space-between;
            gap: 10px;
            font-weight: 900;
            color: rgba(255,255,255,0.92);
            font-size: .95rem;
        }
        .msg-p{
            margin-top: 6px;
            color: rgba(203,213,225,0.86);
            font-size: .86rem;
            line-height: 1.45;
        }
        .msg-meta{
            margin-top: 8px;
            display:flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .badge{
            font-size: .72rem;
            font-weight: 900;
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.74);
        }
        .typing{
            display:flex;
            gap: 5px;
            align-items:center;
            margin-top: 10px;
        }
        .dot{
            width: 7px; height: 7px; border-radius: 999px;
            background: rgba(255,255,255,0.55);
            animation: typingDot 1.05s ease-in-out infinite;
        }
        .dot:nth-child(2){ animation-delay: .15s; }
        .dot:nth-child(3){ animation-delay: .30s; }
        @keyframes typingDot{
            0%,100%{ transform: translateY(0); opacity: .45; }
            50%{ transform: translateY(-4px); opacity: 1; }
        }

        /* Automation SVG */
        .flow{
            height: 260px;
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(2,6,23,0.20);
            position: relative;
            overflow: hidden;
        }
        .flow svg{ width: 100%; height: 100%; display:block; }
        .flow #autoPath{
            stroke-dasharray: 8 10;
            animation: dash 2.2s linear infinite;
        }
        @keyframes dash{ to { stroke-dashoffset: -36; } }

        .flow .auto-node{
            transform-box: fill-box;
            transform-origin: center;
            filter: drop-shadow(0 14px 22px rgba(249,115,22,0.16));
            animation: nodePulse 2.4s ease-in-out infinite;
        }
        .flow .auto-node:nth-of-type(2){ animation-delay: .25s; }
        .flow .auto-node:nth-of-type(3){ animation-delay: .5s; }
        .flow .auto-node:nth-of-type(4){ animation-delay: .75s; }
        .flow .auto-node:nth-of-type(5){ animation-delay: 1.0s; }
        @keyframes nodePulse{
            0%,100%{ transform: scale(1); opacity: .94; }
            50%{ transform: scale(1.04); opacity: 1; }
        }

        .flow .auto-pulse{
            opacity: .95;
        }
        .bento-eyebrow{
            display:inline-flex;
            gap: 8px;
            align-items:center;
            font-weight: 800;
            font-size: .82rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.70);
        }
        .bento-h{
            font-size: 1.5rem;
            font-weight: 900;
            margin-top: .55rem;
            color: rgba(255,255,255,0.96);
            letter-spacing: -0.02em;
        }
        .bento-p{
            margin-top: .5rem;
            color: rgba(255,255,255,0.70);
            line-height: 1.75;
        }
        .bento-list{
            margin-top: .9rem;
            display: grid;
            gap: .55rem;
        }
        .bento-li{
            display:flex;
            gap: .6rem;
            align-items:flex-start;
            color: rgba(255,255,255,0.78);
            font-size: .98rem;
        }
        .bento-li i{
            width: 12px;
            height: 3px;
            border-radius: 999px;
            margin-top: 9px;
            background: linear-gradient(90deg, rgba(239,68,68,0.95), rgba(249,115,22,0.85), rgba(139,92,246,0.75));
            box-shadow:
                0 10px 18px rgba(249,115,22,0.14),
                0 0 16px rgba(139,92,246,0.10);
            flex: 0 0 auto;
        }
        .bento-li{
            align-items: flex-start;
        }

        /* pills (variety instead of same bullet list everywhere) */
        .pill-grid{
            display:flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .pill{
            display:inline-flex;
            align-items:center;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.78);
            font-weight: 900;
            font-size: .85rem;
            letter-spacing: .01em;
        }

        /* numbered steps */
        .steps{
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 10px;
        }
        .steps li{
            display:flex;
            gap: 10px;
            align-items:flex-start;
            color: rgba(255,255,255,0.80);
        }
        .steps .n{
            width: 26px; height: 26px;
            border-radius: 999px;
            display:flex; align-items:center; justify-content:center;
            font-weight: 900;
            background: linear-gradient(135deg, rgba(239,68,68,0.35), rgba(249,115,22,0.22));
            border: 1px solid rgba(255,255,255,0.10);
            box-shadow: 0 18px 30px rgba(249,115,22,0.10);
            flex: 0 0 auto;
            color: rgba(255,255,255,0.92);
        }

        /* long-form sections */
        .section{
            width: 100%;
            margin-top: 6rem;
        }
        .section-title{
            font-size: clamp(2.2rem, 5.5vw, 3.4rem);
            font-weight: 900;
            text-align: center;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 25%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 0 24px rgba(255,107,53,0.14));
        }
        .section-subtitle{
            text-align: center;
            color: rgba(255,255,255,0.70);
            max-width: 920px;
            margin: 0.8rem auto 2.2rem;
            line-height: 1.8;
        }

        .usecases-grid{
            display:grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
        }
        .usecase-card{
            position: relative;
            overflow: hidden;
            border-radius: 24px;
            padding: 1.2rem 1.2rem 1.1rem;
            border: 1px solid rgba(255,255,255,0.10);
            background: linear-gradient(135deg, rgba(17,24,39,0.82) 0%, rgba(31,41,55,0.58) 100%);
            box-shadow: 0 18px 55px rgba(0,0,0,0.40);
        }
        .usecase-h{ font-size: 1.25rem; font-weight: 900; color: rgba(255,255,255,0.95); }
        .usecase-p{ margin-top: .5rem; color: rgba(255,255,255,0.70); line-height: 1.75; }
        @media (max-width: 1100px){ .usecases-grid{ grid-template-columns: 1fr; } }

        .timeline{
            display:grid;
            gap: 12px;
            max-width: 980px;
            margin: 0 auto;
        }
        .tstep{
            display:flex;
            gap: 12px;
            align-items:flex-start;
            border-radius: 22px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(2,6,23,0.22);
            padding: 14px 14px;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
        }
        .tn{
            width: 34px; height: 34px;
            border-radius: 999px;
            display:flex; align-items:center; justify-content:center;
            font-weight: 900;
            background: linear-gradient(135deg, rgba(239,68,68,0.35), rgba(249,115,22,0.22));
            border: 1px solid rgba(255,255,255,0.10);
            color: rgba(255,255,255,0.92);
            flex: 0 0 auto;
        }
        .th{ font-weight: 900; color: rgba(255,255,255,0.92); }
        .tp{ margin-top: 4px; color: rgba(255,255,255,0.70); line-height: 1.6; }

        .faq{
            max-width: 980px;
            margin: 0 auto;
            display: grid;
            gap: 12px;
        }
        .faq details{
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(2,6,23,0.22);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
            overflow: hidden;
        }
        .faq summary{
            cursor: pointer;
            list-style: none;
            padding: 14px 16px;
            font-weight: 900;
            color: rgba(255,255,255,0.92);
            position: relative;
        }
        .faq summary::-webkit-details-marker{ display:none; }
        .faq summary::after{
            content:'';
            position:absolute;
            right: 16px;
            top: 50%;
            width: 10px; height: 10px;
            border-right: 2px solid rgba(255,255,255,0.65);
            border-bottom: 2px solid rgba(255,255,255,0.65);
            transform: translateY(-65%) rotate(45deg);
            transition: transform .25s ease;
        }
        .faq details[open] summary::after{
            transform: translateY(-35%) rotate(225deg);
        }
        .faq-body{
            padding: 0 16px 14px;
            color: rgba(255,255,255,0.74);
            line-height: 1.75;
        }

        .cta{
            width: 100%;
            margin-top: 6rem;
            padding: 0;
        }
        .cta-inner{
            border-radius: 30px;
            padding: 2.4rem 1.6rem;
            border: 1px solid rgba(255,255,255,0.10);
            background:
                radial-gradient(900px 520px at 0% 0%, rgba(239,68,68,0.18), transparent 60%),
                radial-gradient(900px 520px at 100% 0%, rgba(249,115,22,0.16), transparent 60%),
                linear-gradient(135deg, rgba(17,24,39,0.86) 0%, rgba(31,41,55,0.62) 100%);
            box-shadow: 0 26px 80px rgba(0,0,0,0.55);
            text-align: center;
        }
        .cta-title{
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 900;
            letter-spacing: -0.03em;
            color: rgba(255,255,255,0.96);
        }
        .cta-sub{
            max-width: 860px;
            margin: .8rem auto 1.6rem;
            color: rgba(255,255,255,0.72);
            line-height: 1.8;
        }
        .cta-actions{
            display:flex;
            gap: 14px;
            justify-content:center;
            flex-wrap: wrap;
        }

        /* grid spans */
        .bento-span-6{ grid-column: span 6; }
        .bento-span-5{ grid-column: span 5; }
        .bento-span-7{ grid-column: span 7; }
        .bento-span-4{ grid-column: span 4; }
        .bento-span-8{ grid-column: span 8; }
        .bento-span-12{ grid-column: span 12; }

        @media (max-width: 1100px){
            .bento-span-7,.bento-span-5,.bento-span-6,.bento-span-4,.bento-span-8{ grid-column: span 12; }
        }

        /* reveal animation (enabled only when JS is on) */
        html.js .reveal{
            opacity: 0;
            transform: translateY(34px) perspective(900px) rotateX(8deg) scale(0.985);
            filter: blur(6px);
            transition: opacity 0.9s ease, transform 0.9s cubic-bezier(.2,.9,.2,1), filter 0.9s ease;
            transition-delay: var(--rd, 0ms);
            will-change: transform, opacity, filter;
        }
        html.js .reveal.visible{
            opacity: 1;
            transform: translateY(0) perspective(900px) rotateX(0deg) scale(1);
            filter: blur(0);
        }

        /* richer per-card content reveal (wipe + shimmer), only when visible */
        html.js .reveal .bento-h,
        html.js .reveal .bento-p,
        html.js .reveal .bento-li,
        html.js .reveal .pill,
        html.js .reveal .steps li,
        html.js .reveal .th,
        html.js .reveal .tp,
        html.js .reveal .usecase-h,
        html.js .reveal .usecase-p{
            opacity: 0;
            transform: translateY(10px);
            transition: opacity .70s ease, transform .70s cubic-bezier(.2,.9,.2,1), filter .70s ease;
            filter: blur(2px);
            position: relative;
        }
        html.js .reveal.visible .bento-h{ opacity: 1; transform: none; filter: blur(0); transition-delay: calc(var(--rd, 0ms) + 90ms); }
        html.js .reveal.visible .bento-p{ opacity: 1; transform: none; filter: blur(0); transition-delay: calc(var(--rd, 0ms) + 160ms); }
        html.js .reveal.visible .bento-li{ opacity: 1; transform: none; filter: blur(0); }
        html.js .reveal.visible .bento-li:nth-child(1){ transition-delay: calc(var(--rd, 0ms) + 220ms); }
        html.js .reveal.visible .bento-li:nth-child(2){ transition-delay: calc(var(--rd, 0ms) + 280ms); }
        html.js .reveal.visible .bento-li:nth-child(3){ transition-delay: calc(var(--rd, 0ms) + 340ms); }
        html.js .reveal.visible .bento-li:nth-child(4){ transition-delay: calc(var(--rd, 0ms) + 400ms); }
        html.js .reveal.visible .pill{ opacity: 1; transform: none; filter: blur(0); transition-delay: calc(var(--rd, 0ms) + 220ms); }
        html.js .reveal.visible .steps li{ opacity: 1; transform: none; filter: blur(0); transition-delay: calc(var(--rd, 0ms) + 220ms); }
        html.js .reveal.visible .th{ opacity: 1; transform: none; filter: blur(0); transition-delay: calc(var(--rd, 0ms) + 120ms); }
        html.js .reveal.visible .tp{ opacity: 1; transform: none; filter: blur(0); transition-delay: calc(var(--rd, 0ms) + 180ms); }
        html.js .reveal.visible .usecase-h{ opacity: 1; transform: none; filter: blur(0); transition-delay: calc(var(--rd, 0ms) + 90ms); }
        html.js .reveal.visible .usecase-p{ opacity: 1; transform: none; filter: blur(0); transition-delay: calc(var(--rd, 0ms) + 160ms); }

        /* “expensive” shimmer wipe across text when a card reveals */
        html.js .reveal .bento-h::after,
        html.js .reveal .bento-p::after,
        html.js .reveal .bento-li::after,
        html.js .reveal .pill::after,
        html.js .reveal .steps li::after,
        html.js .reveal .th::after,
        html.js .reveal .tp::after,
        html.js .reveal .usecase-h::after,
        html.js .reveal .usecase-p::after{
            content:'';
            position:absolute;
            inset:-2px -18px;
            pointer-events:none;
            opacity: 0;
            transform: translateX(-140%);
            background: linear-gradient(90deg,
                transparent 0%,
                rgba(255,255,255,0.18) 35%,
                rgba(255,255,255,0.38) 50%,
                rgba(255,255,255,0.18) 65%,
                transparent 100%);
            mix-blend-mode: overlay;
            filter: blur(0.3px);
        }
        html.js .reveal.visible .bento-h::after,
        html.js .reveal.visible .bento-p::after,
        html.js .reveal.visible .bento-li::after,
        html.js .reveal.visible .pill::after,
        html.js .reveal.visible .steps li::after,
        html.js .reveal.visible .th::after,
        html.js .reveal.visible .tp::after,
        html.js .reveal.visible .usecase-h::after,
        html.js .reveal.visible .usecase-p::after{
            opacity: 1;
            animation: textWipe .9s cubic-bezier(.2,.9,.2,1) both;
            animation-delay: calc(var(--rd, 0ms) + 160ms);
        }
        @keyframes textWipe{
            0%{ transform: translateX(-140%); opacity: 0; }
            10%{ opacity: 1; }
            100%{ transform: translateX(140%); opacity: 0; }
        }

        /* mini kanban demo (pure CSS, looks expensive) */
        .mini-kanban{
            margin-top: 1.2rem;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }
        .mini-col{
            border-radius: 18px;
            padding: 12px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(2,6,23,0.25);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
            min-height: 190px;
            position: relative;
            overflow: hidden;
        }
        .mini-col::before{
            content:'';
            position:absolute;
            inset:-40%;
            background: radial-gradient(circle at 30% 30%, rgba(255,107,53,0.18), transparent 55%),
                        radial-gradient(circle at 70% 70%, rgba(139,92,246,0.16), transparent 55%);
            opacity: .55;
            filter: blur(18px);
            animation: miniGlow 7.5s ease-in-out infinite;
        }
        @keyframes miniGlow{
            0%,100%{ transform: translate3d(0,0,0) scale(1); }
            50%{ transform: translate3d(-2%, 2%, 0) scale(1.04); }
        }
        .mini-col > *{ position: relative; z-index: 1; }
        .mini-col-title{
            font-weight: 900;
            font-size: .85rem;
            color: rgba(255,255,255,0.75);
            display:flex;
            align-items:center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .mini-pill{
            font-weight: 800;
            font-size: .72rem;
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.75);
        }
        .mini-card{
            border-radius: 14px;
            padding: 10px 10px 9px;
            border: 1px solid rgba(255,255,255,0.10);
            background: linear-gradient(135deg, rgba(15,23,42,0.78) 0%, rgba(15,23,42,0.55) 100%);
            margin-bottom: 10px;
            box-shadow: 0 14px 30px rgba(0,0,0,0.35);
            transform: translateZ(0);
            animation: miniCardFloat 4.8s ease-in-out infinite;
        }
        .mini-card:nth-child(3){ animation-delay: .7s; }
        .mini-card:nth-child(4){ animation-delay: 1.2s; }
        @keyframes miniCardFloat{
            0%,100%{ transform: translateY(0); }
            50%{ transform: translateY(-4px); }
        }
        .mini-card-h{
            font-weight: 900;
            font-size: .92rem;
            color: rgba(255,255,255,0.92);
            margin-bottom: 4px;
        }
        .mini-card-p{
            font-size: .78rem;
            color: rgba(203,213,225,0.85);
            line-height: 1.35;
        }
        .mini-tags{
            display:flex; gap: 6px; margin-top: 8px; flex-wrap: wrap;
        }
        .mini-tag{
            font-size: .7rem;
            padding: 3px 8px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.72);
        }

        /* compact chart card */
        .mini-chart-wrap{
            margin-top: 1.1rem;
            border-radius: 18px;
            padding: 14px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(2,6,23,0.25);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
            height: 220px;
        }
        .mini-chart-wrap canvas{ width: 100% !important; height: 100% !important; }

        /* NOTE: do not disable animations here — landing is designed to be animated */
    </style>
    <svg width="0" height="0" style="position: absolute;">
        <defs>
            <linearGradient id="orangeGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#ef4444;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#f97316;stop-opacity:1" />
            </linearGradient>
        </defs>
    </svg>
</head>
<body>
    <div class="animated-bg" aria-hidden="true"></div>
    <canvas id="three-canvas" aria-hidden="true"></canvas>
    <div class="scroll-progress" aria-hidden="true"><div id="scrollProgressBar"></div></div>

    <!-- Registration Modal (kept outside #siteReveal to avoid transforms/filters) -->
    <div id="registrationModal" class="registration-modal" onclick="if(event.target === this) closeRegistrationModal()">
        <div class="registration-modal-content">
            <button class="registration-close" onclick="closeRegistrationModal()">&times;</button>
            <div class="registration-cat">
                <canvas id="catCanvas"></canvas>
            </div>
            <h2 class="registration-title">🐾 Регистрация</h2>
            <p class="registration-text">
                Перед регистрацией необходимо связаться с нашей поддержкой для получения доступа к системе.
            </p>
            <a href="https://t.me/h1_h2_h3_h4_h5_h6" target="_blank" class="registration-link">
                <svg fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161c-.174 1.858-.924 6.33-1.304 8.394-.171.935-.508 1.248-.834 1.279-.712.064-1.252-.47-1.942-.923-1.078-.708-1.688-1.149-2.735-1.841-1.21-.808-.426-1.253.264-1.978.181-.189 3.246-2.977 3.307-3.23.007-.032.014-.15-.056-.212-.07-.062-.173-.041-.247-.024-.106.024-1.793 1.14-5.062 3.345-.479.329-.913.489-1.302.481-.428-.009-1.252-.242-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.895-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.559.1.014.32.06.465.277.145.216.14.5.098.691z"/>
                </svg>
                Написать в Telegram
            </a>
        </div>
    </div>

    <div id="siteReveal" class="site-reveal">
    <div class="container">
        <h1 class="hero-title">CRM</h1>
        <p class="hero-subtitle">
            Мощная система управления клиентами и продажами нового поколения. 
            Оптимизируйте бизнес-процессы и увеличивайте прибыль.
        </p>
        <div class="btn-container">
            <a href="{{ route('login') }}" class="btn btn-primary">Войти в систему</a>
            <a href="#" onclick="event.preventDefault(); showRegistrationModal();" class="btn btn-secondary">Регистрация</a>
        </div>
        <section class="bento-section">
            <div class="bento-heading reveal">
                <div class="bento-title">Ключевые возможности CRM</div>
                <div class="bento-sub">
                    Управление продажами, коммуникациями и процессами: воронка, аналитика, автоматизации и прозрачные KPI.
                </div>
            </div>

            <div class="bento-grid">
                <div class="bento-card bento-span-12 reveal demo-hub" data-tilt>
                    <div class="tilt-depth">
                        <div class="demo-top">
                            <div>
                                <div class="bento-eyebrow">
                                    <span style="width:10px;height:10px;border-radius:999px;background:linear-gradient(135deg,#ef4444,#f97316);box-shadow:0 10px 22px rgba(249,115,22,0.25)"></span>
                                    Интерактивная демонстрация
                                </div>
                                <div class="bento-h" style="font-size:1.85rem">Один экран — четыре режима: воронка / аналитика / коммуникации / автоматизация</div>
                                <div class="bento-p">Выберите раздел и посмотрите ключевые сценарии работы CRM: от воронки и аналитики до коммуникаций и автоматизаций.</div>
                            </div>
                            <div class="demo-tabs" role="tablist" aria-label="Интерактивная демонстрация CRM">
                                <button type="button" class="demo-tab is-active" data-demo="pipeline">Воронка</button>
                                <button type="button" class="demo-tab" data-demo="analytics">Аналитика</button>
                                <button type="button" class="demo-tab" data-demo="inbox">Коммуникации</button>
                                <button type="button" class="demo-tab" data-demo="automation">Автоматизация</button>
                            </div>
                        </div>

                        <div class="demo-panels">
                            <!-- Pipeline -->
                            <div class="demo-panel is-active" data-panel="pipeline">
                                <div class="demo-grid">
                                    <div>
                                        <div class="mini-kanban" aria-hidden="true">
                                            <div class="mini-col">
                                                <div class="mini-col-title"><span>Лиды</span><span class="mini-pill">12</span></div>
                                                <div class="mini-card">
                                                    <div class="mini-card-h">Промо‑кампания</div>
                                                    <div class="mini-card-p">Входящий • +2 контакта</div>
                                                    <div class="mini-tags">
                                                        <span class="mini-tag">теплый</span><span class="mini-tag">email</span>
                                                    </div>
                                                </div>
                                                <div class="mini-card">
                                                    <div class="mini-card-h">Запрос КП</div>
                                                    <div class="mini-card-p">₽ 180k • дедлайн 3д</div>
                                                    <div class="mini-tags">
                                                        <span class="mini-tag">B2B</span><span class="mini-tag">в работе</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mini-col">
                                                <div class="mini-col-title"><span>Квалификация</span><span class="mini-pill">7</span></div>
                                                <div class="mini-card">
                                                    <div class="mini-card-h">Демонстрация</div>
                                                    <div class="mini-card-p">созвон 16:00</div>
                                                    <div class="mini-tags">
                                                        <span class="mini-tag">zoom</span><span class="mini-tag">срочно</span>
                                                    </div>
                                                </div>
                                                <div class="mini-card">
                                                    <div class="mini-card-h">Подбор тарифа</div>
                                                    <div class="mini-card-p">3 пользователя</div>
                                                    <div class="mini-tags">
                                                        <span class="mini-tag">SaaS</span><span class="mini-tag">KPI</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mini-col">
                                                <div class="mini-col-title"><span>Переговоры</span><span class="mini-pill">4</span></div>
                                                <div class="mini-card">
                                                    <div class="mini-card-h">Согласование договора</div>
                                                    <div class="mini-card-p">Юристы • правки</div>
                                                    <div class="mini-tags">
                                                        <span class="mini-tag">legal</span><span class="mini-tag">priority</span>
                                                    </div>
                                                </div>
                                                <div class="mini-card">
                                                    <div class="mini-card-h">Скидка / условия</div>
                                                    <div class="mini-card-p">₽ 540k • 12м</div>
                                                    <div class="mini-tags">
                                                        <span class="mini-tag">deal</span><span class="mini-tag">финал</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mini-col">
                                                <div class="mini-col-title"><span>Выиграно</span><span class="mini-pill">2</span></div>
                                                <div class="mini-card">
                                                    <div class="mini-card-h">Оплата получена</div>
                                                    <div class="mini-card-p">Счёт закрыт</div>
                                                    <div class="mini-tags">
                                                        <span class="mini-tag">invoice</span><span class="mini-tag">done</span>
                                                    </div>
                                                </div>
                                                <div class="mini-card">
                                                    <div class="mini-card-h">Онбординг</div>
                                                    <div class="mini-card-p">план на 7 дней</div>
                                                    <div class="mini-tags">
                                                        <span class="mini-tag">success</span><span class="mini-tag">NPS</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="demo-side">
                                        <div class="bento-eyebrow">Детали</div>
                                        <div class="bento-h" style="margin-top:.35rem">Стадии + теги + SLA</div>
                                        <div class="bento-p">Системно: сделки не “теряются”, всё видно по этапам и дедлайнам.</div>
                                        <div class="bento-list" style="margin-top: 1rem;">
                                            <div class="bento-li"><i></i><span>Drag‑&‑drop стадий</span></div>
                                            <div class="bento-li"><i></i><span>План/факт + прогноз</span></div>
                                            <div class="bento-li"><i></i><span>Ответственный + история</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Analytics -->
                            <div class="demo-panel" data-panel="analytics">
                                <div class="demo-grid">
                                    <div class="demo-side">
                                        <div class="bento-eyebrow">Выручка</div>
                                        <div class="bento-h" style="margin-top:.35rem">Динамика выручки и тренд</div>
                                        <div class="bento-p">Понимайте, где вы теряете сделки и какие каналы дают рост.</div>
                                        <div class="mini-chart-wrap" style="height: 250px; margin-top: 12px;">
                                            <canvas id="demoRevenue"></canvas>
                                        </div>
                                        <div class="pill-grid" style="margin-top: 12px;">
                                            <span class="pill"><span data-count="127"></span>&nbsp;активных сделок</span>
                                            <span class="pill">SLA: <span data-count="24" data-suffix="ч"></span></span>
                                        </div>
                                    </div>
                                    <div class="demo-side">
                                        <div class="bento-eyebrow">Конверсия</div>
                                        <div class="bento-h" style="margin-top:.35rem">Конверсия по стадиям и нагрузка</div>
                                        <div class="bento-p">Сводка по воронке и эффективности команды в одном экране.</div>
                                        <div class="mini-chart-wrap" style="height: 250px; margin-top: 12px;">
                                            <canvas id="demoMix"></canvas>
                                        </div>
                                        <div class="pill-grid" style="margin-top: 12px;">
                                            <span class="pill"><span data-count="68" data-suffix="%"></span>&nbsp;конверсия</span>
                                            <span class="pill"><span data-count="18"></span>&nbsp;закрыто за неделю</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Inbox -->
                            <div class="demo-panel" data-panel="inbox">
                                <div class="demo-grid">
                                    <div class="demo-side">
                                        <div class="bento-eyebrow">Коммуникации</div>
                                        <div class="bento-h" style="margin-top:.35rem">Письма и задачи в контексте сделки</div>
                                        <div class="bento-p">История, следующий шаг и ответственность — в одном месте.</div>
                                        <div class="inbox" style="margin-top: 12px;">
                                            <div class="msg">
                                                <div class="msg-h"><span>Re: КП на 12 месяцев</span><span class="badge">сегодня</span></div>
                                                <div class="msg-p">Отправлено коммерческое предложение. Следующее действие запланировано автоматически.</div>
                                                <div class="msg-meta"><span class="badge">email</span><span class="badge">₽ 540k</span><span class="badge">переговоры</span></div>
                                            </div>
                                            <div class="msg">
                                                <div class="msg-h"><span>Задача: созвон</span><span class="badge">16:00</span></div>
                                                <div class="msg-p">Уточнить требования, согласовать сроки внедрения, показать демо.</div>
                                                <div class="msg-meta"><span class="badge">todo</span><span class="badge">SLA</span></div>
                                            </div>
                                            <div class="msg">
                                                <div class="msg-h"><span>Заметка менеджера</span><span class="badge">5 мин назад</span></div>
                                        <div class="msg-p">Клиент просит перенести срок поставки на следующую неделю и уточнить условия оплаты.</div>
                                                <div class="typing" aria-hidden="true"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="demo-side">
                                        <div class="bento-eyebrow">Единый контекст</div>
                                        <div class="bento-h" style="margin-top:.35rem">Следующее действие прозрачно для команды</div>
                                        <div class="bento-p">Согласованная работа без потерь информации между людьми и этапами.</div>
                                        <div class="bento-list" style="margin-top: 1rem;">
                                            <div class="bento-li"><i></i><span>Шаблоны писем</span></div>
                                            <div class="bento-li"><i></i><span>Авто‑напоминания</span></div>
                                            <div class="bento-li"><i></i><span>Сводка по сделке</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Automation -->
                            <div class="demo-panel" data-panel="automation">
                                <div class="demo-grid">
                                    <div class="demo-side">
                                        <div class="bento-eyebrow">Автоматизация</div>
                                        <div class="bento-h" style="margin-top:.35rem">Триггеры → действия → контроль</div>
                                        <div class="bento-p">Сценарии: смена стадии, сроки, события — и нужные действия запускаются автоматически.</div>
                                        <div class="flow" style="margin-top: 12px;">
                                            <svg class="auto-flow" viewBox="0 0 860 280" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <defs>
                                                    <linearGradient id="autoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                                        <stop offset="0%" stop-color="#ef4444" stop-opacity="0.95"/>
                                                        <stop offset="52%" stop-color="#f97316" stop-opacity="0.90"/>
                                                        <stop offset="100%" stop-color="#8b5cf6" stop-opacity="0.92"/>
                                                    </linearGradient>
                                                    <filter id="autoGlow" x="-50%" y="-50%" width="200%" height="200%">
                                                        <feGaussianBlur stdDeviation="7" result="b"/>
                                                        <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
                                                    </filter>
                                                </defs>

                                                <!-- backbone path -->
                                                <path id="autoPath" d="M70 140 C 180 60, 260 60, 330 140 S 490 220, 560 140 S 700 60, 790 140" fill="none" stroke="url(#autoGrad)" stroke-width="4.5" opacity="0.92" filter="url(#autoGlow)"/>
                                                <path d="M70 140 C 180 60, 260 60, 330 140 S 490 220, 560 140 S 700 60, 790 140" fill="none" stroke="rgba(255,255,255,0.16)" stroke-width="10" opacity="0.10"/>

                                                <!-- nodes -->
                                                <g class="auto-node">
                                                    <rect x="36" y="108" width="140" height="64" rx="16" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.16)"/>
                                                    <circle cx="58" cy="140" r="7.5" fill="url(#autoGrad)"/>
                                                    <text x="78" y="132" fill="rgba(255,255,255,0.92)" font-family="Inter, sans-serif" font-size="14" font-weight="900">Триггер</text>
                                                    <text x="78" y="152" fill="rgba(255,255,255,0.70)" font-family="Inter, sans-serif" font-size="12" font-weight="700">Стадия “КП”</text>
                                                </g>

                                                <g class="auto-node">
                                                    <rect x="250" y="108" width="170" height="64" rx="16" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.16)"/>
                                                    <circle cx="272" cy="140" r="7.5" fill="url(#autoGrad)"/>
                                                    <text x="292" y="132" fill="rgba(255,255,255,0.92)" font-family="Inter, sans-serif" font-size="14" font-weight="900">Проверка</text>
                                                    <text x="292" y="152" fill="rgba(255,255,255,0.70)" font-family="Inter, sans-serif" font-size="12" font-weight="700">Есть e‑mail?</text>
                                                </g>

                                                <g class="auto-node">
                                                    <rect x="470" y="88" width="190" height="64" rx="16" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.16)"/>
                                                    <circle cx="492" cy="120" r="7.5" fill="url(#autoGrad)"/>
                                                    <text x="512" y="112" fill="rgba(255,255,255,0.92)" font-family="Inter, sans-serif" font-size="14" font-weight="900">Действие</text>
                                                    <text x="512" y="132" fill="rgba(255,255,255,0.70)" font-family="Inter, sans-serif" font-size="12" font-weight="700">Отправить письмо</text>
                                                </g>

                                                <g class="auto-node">
                                                    <rect x="470" y="160" width="190" height="64" rx="16" fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.14)"/>
                                                    <circle cx="492" cy="192" r="7.5" fill="url(#autoGrad)"/>
                                                    <text x="512" y="184" fill="rgba(255,255,255,0.88)" font-family="Inter, sans-serif" font-size="13" font-weight="800">Действие</text>
                                                    <text x="512" y="204" fill="rgba(255,255,255,0.70)" font-family="Inter, sans-serif" font-size="12" font-weight="700">Задача менеджеру</text>
                                                </g>

                                                <g class="auto-node">
                                                    <rect x="690" y="108" width="160" height="64" rx="16" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.16)"/>
                                                    <circle cx="712" cy="140" r="7.5" fill="url(#autoGrad)"/>
                                                    <text x="732" y="132" fill="rgba(255,255,255,0.92)" font-family="Inter, sans-serif" font-size="14" font-weight="900">Контроль</text>
                                                    <text x="732" y="152" fill="rgba(255,255,255,0.70)" font-family="Inter, sans-serif" font-size="12" font-weight="700">SLA 24 часа</text>
                                                </g>

                                                <!-- pulses running along the path (positioned by JS) -->
                                                <circle class="auto-pulse" data-pulse="0" r="4.2" fill="url(#autoGrad)" filter="url(#autoGlow)"/>
                                                <circle class="auto-pulse" data-pulse="1" r="3.6" fill="rgba(255,255,255,0.95)" opacity="0.75" filter="url(#autoGlow)"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="demo-side">
                                        <div class="bento-eyebrow">Результат</div>
                                        <div class="bento-h" style="margin-top:.35rem">Стабильный процесс продаж</div>
                                        <div class="bento-p">Система помогает соблюдать регламенты, сроки и качество коммуникаций.</div>
                                        <div class="bento-list" style="margin-top: 1rem;">
                                            <div class="bento-li"><i></i><span>Триггер по дедлайну</span></div>
                                            <div class="bento-li"><i></i><span>Эскалации и контроль</span></div>
                                            <div class="bento-li"><i></i><span>Аудит действий</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bento-card bento-span-6 reveal" data-tilt>
                    <div class="tilt-depth">
                        <div class="bento-eyebrow">
                            <span style="width:10px;height:10px;border-radius:999px;background:linear-gradient(135deg,#ef4444,#f97316);box-shadow:0 10px 22px rgba(249,115,22,0.25)"></span>
                            Безопасность
                        </div>
                        <div class="bento-h">Роли, права, аудит</div>
                        <div class="bento-p">Контроль доступа и прозрачность действий — чтобы масштабироваться без хаоса.</div>
                        <div class="pill-grid" style="margin-top: 1rem;">
                            <span class="pill">Роли и права</span>
                            <span class="pill">Аудит действий</span>
                            <span class="pill">Админ‑панель</span>
                            <span class="pill">Контроль доступа</span>
                        </div>
                    </div>
                </div>

                <div class="bento-card bento-span-6 reveal" data-tilt>
                    <div class="tilt-depth">
                        <div class="bento-eyebrow">
                            <span style="width:10px;height:10px;border-radius:999px;background:linear-gradient(135deg,#3b82f6,#8b5cf6);box-shadow:0 12px 26px rgba(139,92,246,0.22)"></span>
                            Производительность
                        </div>
                        <div class="bento-h">Быстро. Стабильно. Расширяемо.</div>
                        <div class="bento-p">Очереди, планировщик, кэширование — система держит нагрузку и остаётся отзывчивой.</div>
                        <ol class="steps" style="margin-top: 1rem;">
                            <li><span class="n">1</span><span>Очереди задач и фоновые операции</span></li>
                            <li><span class="n">2</span><span>Планировщик процессов</span></li>
                            <li><span class="n">3</span><span>Кэширование и оптимизация</span></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="section reveal">
            <h2 class="section-title">Сценарии использования</h2>
            <p class="section-subtitle">
                CRM подходит для команд продаж, аккаунтинга и руководителей: от ежедневной работы менеджера до контроля показателей.
            </p>
            <div class="usecases-grid">
                <div class="usecase-card reveal" style="--rd:40ms" data-tilt>
                    <div class="tilt-depth">
                        <div class="usecase-h">Отдел продаж</div>
                        <div class="usecase-p">Воронка, задачи, дедлайны, контроль следующего шага, автоматизация follow‑up.</div>
                        <div class="pill-grid" style="margin-top: 12px;">
                            <span class="pill">Kanban</span><span class="pill">Задачи</span><span class="pill">KPI</span>
                        </div>
                    </div>
                </div>
                <div class="usecase-card reveal" style="--rd:120ms" data-tilt>
                    <div class="tilt-depth">
                        <div class="usecase-h">Руководитель</div>
                        <div class="usecase-p">Прозрачная отчётность, конверсия, источники лидов, эффективность менеджеров.</div>
                        <div class="pill-grid" style="margin-top: 12px;">
                            <span class="pill">Отчёты</span><span class="pill">Конверсия</span><span class="pill">План/факт</span>
                        </div>
                    </div>
                </div>
                <div class="usecase-card reveal" style="--rd:200ms" data-tilt>
                    <div class="tilt-depth">
                        <div class="usecase-h">Поддержка / аккаунтинг</div>
                        <div class="usecase-p">История коммуникаций, статусные обновления, чек‑листы и прозрачные договорённости.</div>
                        <div class="pill-grid" style="margin-top: 12px;">
                            <span class="pill">История</span><span class="pill">Чек‑листы</span><span class="pill">SLA</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section reveal">
            <h2 class="section-title">Внедрение</h2>
            <p class="section-subtitle">Структурированный процесс подключения команды и данных — без “переезда в хаос”.</p>
            <div class="timeline">
                <div class="tstep reveal" style="--rd:40ms"><span class="tn">1</span><div><div class="th">Структура</div><div class="tp">Стадии воронки, источники лидов, роли и доступы.</div></div></div>
                <div class="tstep reveal" style="--rd:120ms"><span class="tn">2</span><div><div class="th">Импорт</div><div class="tp">Контакты и компании: загрузка, очистка, единые поля.</div></div></div>
                <div class="tstep reveal" style="--rd:200ms"><span class="tn">3</span><div><div class="th">Автоматизации</div><div class="tp">Триггеры, напоминания, шаблоны писем, регламенты.</div></div></div>
                <div class="tstep reveal" style="--rd:280ms"><span class="tn">4</span><div><div class="th">Запуск</div><div class="tp">Отчётность, контроль качества, корректировки по данным.</div></div></div>
            </div>
        </section>

        <section class="section reveal">
            <h2 class="section-title">Ответы на вопросы</h2>
            <p class="section-subtitle">Коротко о главном — без технических деталей.</p>
            <div class="faq">
                <details>
                    <summary>Можно ли ограничить доступ сотрудников к данным?</summary>
                    <div class="faq-body">Да. Роли и права позволяют задавать доступ к разделам и действиям, а аудит фиксирует изменения.</div>
                </details>
                <details>
                    <summary>Есть ли автоматические напоминания и сценарии?</summary>
                    <div class="faq-body">Да. Можно задавать триггеры по стадиям, срокам и событиям: письма, задачи, эскалации.</div>
                </details>
                <details>
                    <summary>Можно ли контролировать эффективность менеджеров?</summary>
                    <div class="faq-body">Да. Доступны отчёты по конверсии, источникам лидов, план/факт и загрузке команды.</div>
                </details>
                <details>
                    <summary>Можно ли настроить стадии воронки под ваш процесс?</summary>
                    <div class="faq-body">Да. Стадии, названия, порядок и правила переходов настраиваются под ваш регламент.</div>
                </details>
                <details>
                    <summary>Поддерживается ли импорт компаний и контактов?</summary>
                    <div class="faq-body">Да. Можно загрузить данные из таблиц и привести поля к единому формату, чтобы избежать дублей.</div>
                </details>
                <details>
                    <summary>Есть ли API и интеграции для обмена данными?</summary>
                    <div class="faq-body">Да. Возможна интеграция через API и вебхуки: обмен компаниями, контактами, сделками и событиями воронки.</div>
                </details>
                <details>
                    <summary>Можно ли контролировать SLA и сроки по сделкам?</summary>
                    <div class="faq-body">Да. Настраиваются дедлайны, напоминания и эскалации, чтобы сделки не “зависали” на этапах.</div>
                </details>
                <details>
                    <summary>Можно ли видеть историю изменений по сделке?</summary>
                    <div class="faq-body">Да. Аудит фиксирует ключевые действия и изменения, что упрощает контроль и разбор кейсов.</div>
                </details>
                <details>
                    <summary>Подойдёт ли CRM для нескольких отделов?</summary>
                    <div class="faq-body">Да. Разделение по ролям и доступам позволяет вести продажи, сопровождение и контроль руководителя в едином контуре.</div>
                </details>
                <details>
                    <summary>Поддерживается ли поиск по базе клиентов и сделкам?</summary>
                    <div class="faq-body">Да. Быстрый поиск по компаниям, контактам и сделкам помогает находить информацию за секунды.</div>
                </details>
                <details>
                    <summary>Есть ли резервное копирование и восстановление?</summary>
                    <div class="faq-body">Да. База данных может резервироваться по регламенту, а при необходимости — восстановиться из бэкапа.</div>
                </details>
            </div>
        </section>

        <section class="cta reveal">
            <div class="cta-inner">
                <div class="cta-title">Готовы посмотреть CRM в работе?</div>
                <div class="cta-sub">Войдите в систему и оцените интерфейс, воронку и аналитику на реальных сценариях.</div>
                <div class="cta-actions">
                    <a href="{{ route('login') }}" class="btn btn-primary">Войти</a>
                    <a href="#" onclick="event.preventDefault(); showRegistrationModal();" class="btn btn-secondary">Запросить доступ</a>
                </div>
            </div>
        </section>
    </div>
    
    <script>
        // Wait for Three.js to load
        window.addEventListener('load', function() {
            // Always open at the top (CRM), no scroll restoration jump
            if ('scrollRestoration' in history) {
                history.scrollRestoration = 'manual';
            }
            window.scrollTo(0, 0);

            if (typeof THREE === 'undefined') {
                console.error('Three.js not loaded');
                return;
            }
            
            // Scene setup
            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
            const renderer = new THREE.WebGLRenderer({ 
                canvas: document.getElementById('three-canvas'),
                alpha: true,
                antialias: true
            });
            
            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            
            // Create particles
            const particleCount = 1400;
            const particles = new THREE.BufferGeometry();
            const positions = new Float32Array(particleCount * 3);
            const colors = new Float32Array(particleCount * 3);
            const scales = new Float32Array(particleCount);
            const seeds = new Float32Array(particleCount);
            
            const color1 = new THREE.Color(0xff6b35);
            const color2 = new THREE.Color(0xf7931e);
            const color3 = new THREE.Color(0x8b5cf6);
            
            for (let i = 0; i < particleCount * 3; i += 3) {
                positions[i] = (Math.random() - 0.5) * 50;
                positions[i + 1] = (Math.random() - 0.5) * 50;
                positions[i + 2] = (Math.random() - 0.5) * 50;
                
                const rand = Math.random();
                let color;
                if (rand < 0.4) {
                    color = color1;
                } else if (rand < 0.7) {
                    color = color2;
                } else {
                    color = color3;
                }
                
                colors[i] = color.r;
                colors[i + 1] = color.g;
                colors[i + 2] = color.b;

                const idx = i / 3;
                // size variation (depth + sparkle)
                scales[idx] = 0.55 + Math.random() * 1.35;
                // random phase/seed for GPU motion
                seeds[idx] = Math.random() * 1000;
            }
            
            particles.setAttribute('position', new THREE.BufferAttribute(positions, 3));
            particles.setAttribute('color', new THREE.BufferAttribute(colors, 3));
            particles.setAttribute('aScale', new THREE.BufferAttribute(scales, 1));
            particles.setAttribute('aSeed', new THREE.BufferAttribute(seeds, 1));
            
            // Create circular particle texture
            const textureCanvas = document.createElement('canvas');
            textureCanvas.width = 128;
            textureCanvas.height = 128;
            const textureContext = textureCanvas.getContext('2d');
            
            // Create radial gradient for smooth circular particle
            const gradient = textureContext.createRadialGradient(64, 64, 0, 64, 64, 64);
            gradient.addColorStop(0, 'rgba(255, 255, 255, 1)');
            gradient.addColorStop(0.3, 'rgba(255, 255, 255, 0.8)');
            gradient.addColorStop(0.6, 'rgba(255, 255, 255, 0.4)');
            gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
            
            textureContext.fillStyle = gradient;
            textureContext.fillRect(0, 0, 128, 128);
            
            const particleTexture = new THREE.CanvasTexture(textureCanvas);
            particleTexture.needsUpdate = true;

            const material = new THREE.ShaderMaterial({
                transparent: true,
                depthWrite: false,
                blending: THREE.AdditiveBlending,
                vertexColors: true,
                uniforms: {
                    uTime: { value: 0 },
                    uSize: { value: 22.0 },
                    uMap: { value: particleTexture },
                },
                vertexShader: `
                    attribute float aScale;
                    attribute float aSeed;
                    varying vec3 vColor;
                    varying float vAlpha;
                    varying float vDepth;
                    uniform float uTime;
                    uniform float uSize;

                    float hash(float n){ return fract(sin(n)*43758.5453123); }

                    void main() {
                        vColor = color.rgb;

                        vec3 p = position;
                        float t = uTime * 1.7;
                        // subtle swirl + drift (GPU)
                        float s = aSeed;
                        p.x += (sin(t + s) + sin(t*0.7 + s*1.7)) * 0.32;
                        p.y += (cos(t*0.9 + s*1.3) + sin(t*1.1 + s*0.9)) * 0.28;
                        p.z += sin(t*0.6 + s*2.1) * 0.18;

                        // extra tiny orbit per-particle (adds life even without mouse)
                        float ang = sin(t*0.55 + s) * 0.10;
                        float cx = cos(ang);
                        float sx = sin(ang);
                        p.xy = mat2(cx, -sx, sx, cx) * p.xy;

                        vec4 mvPosition = modelViewMatrix * vec4(p, 1.0);
                        gl_Position = projectionMatrix * mvPosition;

                        // size with perspective
                        float size = uSize * aScale;
                        gl_PointSize = size * (1.0 / max(0.18, -mvPosition.z * 0.06));

                        // depth fade (closer = brighter)
                        vDepth = clamp((-mvPosition.z) / 60.0, 0.0, 1.0);
                        float sparkle = 0.70 + 0.30 * sin(uTime*3.2 + s*3.0);
                        vAlpha = (0.40 + 0.60 * vDepth) * sparkle;
                    }
                `,
                fragmentShader: `
                    uniform sampler2D uMap;
                    varying vec3 vColor;
                    varying float vAlpha;
                    varying float vDepth;

                    void main() {
                        vec4 tex = texture2D(uMap, gl_PointCoord);
                        // softer edge + brighter core
                        float a = tex.a * vAlpha;
                        vec3 col = vColor;
                        col *= (0.9 + 0.5 * vDepth);
                        gl_FragColor = vec4(col, a);
                    }
                `
            });
            
            const particleSystem = new THREE.Points(particles, material);
            scene.add(particleSystem);
            
            // Camera position
            camera.position.z = 30;
            camera.position.y = 0;
            camera.position.x = 0;
            
            // Animation
            let time = 0;
            const mouse = { x: 0, y: 0 };
            
            function animate() {
                requestAnimationFrame(animate);
                
                time += 0.001;
                material.uniforms.uTime.value = time;
                
                // Rotate particles
                particleSystem.rotation.x = time * 0.35;
                particleSystem.rotation.y = time * 0.55;
                // Lightweight "breathing" (no per-particle mutation = much smoother)
                const breathe = 1 + Math.sin(time * 7.5) * 0.012;
                particleSystem.scale.set(breathe, breathe, breathe);
                particleSystem.position.x = Math.sin(time * 2.2) * 0.12;
                particleSystem.position.y = Math.cos(time * 1.9) * 0.10;
                
                // Camera movement based on mouse
                const autoX = Math.sin(time * 1.4) * 0.35;
                const autoY = Math.cos(time * 1.1) * 0.22;
                const targetX = (mouse.x * 1.2) + autoX;
                const targetY = (-mouse.y * 1.2) + autoY;
                camera.position.x += (targetX * 2 - camera.position.x) * 0.04;
                camera.position.y += (targetY * 2 - camera.position.y) * 0.04;
                camera.lookAt(scene.position);
                
                renderer.render(scene, camera);
            }
            
            // Mouse movement
            document.addEventListener('mousemove', (e) => {
                mouse.x = (e.clientX / window.innerWidth) * 2 - 1;
                mouse.y = (e.clientY / window.innerHeight) * 2 - 1;
            });
            
            // Handle resize
            window.addEventListener('resize', () => {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(window.innerWidth, window.innerHeight);
            });
            
            animate();
        });
    </script>
    
    <script>
        function showRegistrationModal() {
            const modal = document.getElementById('registrationModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeRegistrationModal() {
            const modal = document.getElementById('registrationModal');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeRegistrationModal();
            }
        });
        
        // Remove white background from cat image
        function removeWhiteBackground() {
            const canvas = document.getElementById('catCanvas');
            if (!canvas) return;
            
            const ctx = canvas.getContext('2d');
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = function() {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);
                
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const data = imageData.data;
                
                for (let i = 0; i < data.length; i += 4) {
                    const r = data[i];
                    const g = data[i + 1];
                    const b = data[i + 2];
                    
                    // Если пиксель белый или почти белый - делаем прозрачным
                    if (r > 240 && g > 240 && b > 240) {
                        data[i + 3] = 0; // alpha = 0 (прозрачный)
                    }
                }
                
                ctx.putImageData(imageData, 0, 0);
                
                // Масштабируем canvas под размер контейнера
                const container = canvas.parentElement;
                const scale = Math.min(container.offsetWidth / canvas.width, container.offsetHeight / canvas.height);
                canvas.style.width = (canvas.width * scale) + 'px';
                canvas.style.height = (canvas.height * scale) + 'px';
            };
            img.src = '/cat.png';
        }
        
        // Загружаем кошечку при загрузке страницы
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', removeWhiteBackground);
        } else {
            removeWhiteBackground();
        }
        
        // Scroll reveal animations (runs once per element)
        function initScrollAnimations() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -12% 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            const els = Array.from(document.querySelectorAll('.reveal'));
            if (!els.length) return;

            // reveal above-the-fold with a frame delay (so transition triggers)
            const vh = window.innerHeight || 1;
            const revealNow = [];
            const revealLater = [];
            els.forEach((el) => {
                const r = el.getBoundingClientRect();
                // include “just below the fold” so first section animates without scrolling
                if (r.top < vh * 1.06) revealNow.push(el);
                else revealLater.push(el);
            });

            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    revealNow.forEach((el) => el.classList.add('visible'));
                });
            });

            revealLater.forEach((el) => observer.observe(el));
        }

        // Count-up for numbers with data-count (runs once per element)
        function initCountUps() {
            const els = Array.from(document.querySelectorAll('[data-count]'));
            if (!els.length) return;
            const run = (el) => {
                if (el.dataset.counted === '1') return;
                el.dataset.counted = '1';
                const target = Number(el.dataset.count || 0);
                const suffix = el.dataset.suffix || '';
                const prefix = el.dataset.prefix || '';
                const duration = Number(el.dataset.duration || 900);
                const start = performance.now();
                const fmt = new Intl.NumberFormat('ru-RU');
                const step = (now) => {
                    const t = Math.min(1, (now - start) / duration);
                    const k = 1 - Math.pow(1 - t, 3);
                    const v = Math.round(target * k);
                    el.textContent = prefix + fmt.format(v) + suffix;
                    if (t < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            };
            const io = new IntersectionObserver((entries) => {
                entries.forEach((e) => {
                    if (e.isIntersecting) run(e.target);
                });
            }, { threshold: 0.65 });
            els.forEach((el) => io.observe(el));
        }

        function updateScrollProgress() {
            const bar = document.getElementById('scrollProgressBar');
            if (!bar) return;
            if (updateScrollProgress.__ticking) return;
            updateScrollProgress.__ticking = true;
            requestAnimationFrame(() => {
                const doc = document.documentElement;
                const scrollTop = doc.scrollTop || document.body.scrollTop;
                const scrollHeight = (doc.scrollHeight || document.body.scrollHeight) - doc.clientHeight;
                const pct = scrollHeight > 0 ? Math.min(1, Math.max(0, scrollTop / scrollHeight)) : 0;
                bar.style.height = (pct * 100).toFixed(2) + '%';
                updateScrollProgress.__ticking = false;
            });
        }

        // NOTE: old "overview" parallax/counters removed (section deleted).
        
        // Initialize on load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initScrollAnimations);
        } else {
            initScrollAnimations();
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCountUps);
        } else {
            initCountUps();
        }
        
        // Initialize overview charts (once) + allow replay on scroll
        function initOverviewCharts() {
            if (typeof Chart === 'undefined') return;

            window.__overviewCharts = window.__overviewCharts || {};

            const getOrCreate = (key, canvasEl, configFactory) => {
                if (!canvasEl) return null;
                if (window.__overviewCharts[key]) return window.__overviewCharts[key];
                const chart = new Chart(canvasEl, configFactory());
                // keep original dataset for replay
                chart.$__originalData = chart.data.datasets[0].data.slice();
                window.__overviewCharts[key] = chart;
                return chart;
            };

            // Demo charts (created only if canvases exist)
            const mkGrad = (ctx, colorA, colorB) => {
                const g = ctx.createLinearGradient(0, 0, 0, 260);
                g.addColorStop(0, colorA);
                g.addColorStop(1, colorB);
                return g;
            };

            getOrCreate('demoRevenue', document.getElementById('demoRevenue'), () => {
                const ctx = document.getElementById('demoRevenue').getContext('2d');
                return ({
                    type: 'line',
                    data: {
                        labels: ['00','01','02','03','04','05','06','07','08','09','10','11'],
                        datasets: [{
                            data: [12, 14, 13, 16, 18, 17, 20, 23, 22, 25, 28, 31],
                            borderColor: 'rgba(255, 107, 53, 0.98)',
                            backgroundColor: mkGrad(ctx, 'rgba(255, 107, 53, 0.22)', 'rgba(255, 107, 53, 0.02)'),
                            borderWidth: 3,
                            fill: true,
                            tension: 0.45,
                            pointRadius: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 900, easing: 'easeOutQuart' },
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { ticks: { color: 'rgba(255,255,255,0.55)' }, grid: { color: 'rgba(255,255,255,0.06)' } },
                            y: { ticks: { color: 'rgba(255,255,255,0.55)' }, grid: { color: 'rgba(255,255,255,0.08)' } }
                        }
                    }
                });
            });

            getOrCreate('demoMix', document.getElementById('demoMix'), () => {
                const canvas = document.getElementById('demoMix');
                const ctx = canvas.getContext('2d');

                const stages = ['Лиды','Квалификация','Предложение','Переговоры','Выиграно'];
                // kept on window so live updates can mutate consistently
                window.__demoMixCounts = window.__demoMixCounts || [120, 86, 61, 39, 24];

                const getStepConv = (arr) => arr.map((v, i) => {
                    if (i === 0) return 100;
                    const prev = Math.max(1, arr[i - 1]);
                    return Math.round((v / prev) * 100);
                });

                const labelPlugin = {
                    id: 'crmFunnelLabels',
                    afterDatasetsDraw(chart) {
                        const { ctx } = chart;
                        const meta = chart.getDatasetMeta(0);
                        if (!meta || !meta.data) return;

                        const counts = (window.__demoMixCounts || chart.data.datasets[0].data || []).slice().map((n) => Math.round(Number(n) || 0));
                        const stepConv = getStepConv(counts);

                        ctx.save();
                        ctx.font = '800 12px Inter, sans-serif';
                        ctx.textBaseline = 'middle';

                        meta.data.forEach((bar, i) => {
                            const v = counts[i];
                            const c = stepConv[i];
                            const x = bar.x;
                            const y = bar.y;
                            const right = chart.chartArea ? chart.chartArea.right : (chart.width || 0);

                            const countText = `${v}`;
                            const convText = i > 0 ? `${c}%` : '';

                            const countW = ctx.measureText(countText).width || 0;
                            const convW = convText ? (ctx.measureText(convText).width || 0) : 0;
                            const gap = convText ? 10 : 0;

                            // Prefer outside bar, but if near edge → draw inside bar end (right-aligned)
                            const outsideStart = x + 10;
                            const outsideSpace = right - outsideStart;
                            const need = countW + gap + convW + 6;

                            if (outsideSpace >= need) {
                                // outside (clean)
                                ctx.textAlign = 'left';
                                ctx.fillStyle = 'rgba(255,255,255,0.88)';
                                ctx.fillText(countText, outsideStart, y);
                                if (convText) {
                                    ctx.fillStyle = 'rgba(34,197,94,0.92)';
                                    ctx.fillText(convText, outsideStart + countW + gap, y);
                                }
                            } else {
                                // inside bar (fits even for long "Лиды")
                                const insideX = Math.max(16, x - 8);
                                ctx.textAlign = 'right';
                                if (convText) {
                                    ctx.fillStyle = 'rgba(34,197,94,0.92)';
                                    ctx.fillText(convText, insideX, y);
                                    ctx.fillStyle = 'rgba(255,255,255,0.92)';
                                    ctx.fillText(countText, Math.max(16, insideX - convW - gap), y);
                                } else {
                                    ctx.fillStyle = 'rgba(255,255,255,0.92)';
                                    ctx.fillText(countText, insideX, y);
                                }
                            }
                        });

                        ctx.restore();
                    }
                };

                return ({
                    type: 'bar',
                    data: {
                        labels: stages,
                        datasets: [{
                            label: 'Сделки по стадиям',
                            data: window.__demoMixCounts,
                            borderRadius: 14,
                            borderSkipped: false,
                            backgroundColor: mkGrad(ctx, 'rgba(139, 92, 246, 0.50)', 'rgba(59, 130, 246, 0.12)'),
                            maxBarThickness: 22
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 950, easing: 'easeOutQuart' },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                enabled: true,
                                callbacks: {
                                    label: (ctx) => {
                                        const i = ctx.dataIndex;
                                        const arr = (window.__demoMixCounts || ctx.chart.data.datasets[0].data || []).slice().map((n) => Math.round(Number(n) || 0));
                                        const v = arr[i] ?? 0;
                                        const c = getStepConv(arr)[i] ?? 0;
                                        if (i === 0) return ` ${v} (100%)`;
                                        return ` ${v} (переход ${c}%)`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grace: '25%',
                                suggestedMax: 180,
                                ticks: { color: 'rgba(255,255,255,0.45)' },
                                grid: { color: 'rgba(255,255,255,0.08)' }
                            },
                            y: {
                                ticks: { color: 'rgba(255,255,255,0.70)' },
                                grid: { display: false }
                            }
                        }
                    },
                    plugins: [labelPlugin]
                });
            });
        }
        
        function replayOverviewChart(key) {
            const charts = window.__overviewCharts || {};
            const chart = charts[key];
            if (!chart) return;
            // Cheapest way: reset animation state and re-render with animation
            if (typeof chart.reset === 'function') chart.reset();
            chart.update();
        }

        // Demo hub: tabs + tilt + live charts
        function initDemoHub() {
            const hub = document.querySelector('.demo-hub');
            if (!hub) return;

            const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            const tabs = Array.from(hub.querySelectorAll('.demo-tab'));
            const panels = Array.from(hub.querySelectorAll('.demo-panel'));
            let active = 'pipeline';
            let userTouched = false;

            const setActive = (key) => {
                active = key;
                tabs.forEach(t => t.classList.toggle('is-active', t.dataset.demo === key));
                panels.forEach(p => p.classList.toggle('is-active', p.dataset.panel === key));

                if (key === 'analytics') {
                    // create charts after panel is visible
                    requestAnimationFrame(() => {
                        initOverviewCharts();
                        const charts = window.__overviewCharts || {};
                        try { charts.demoRevenue && charts.demoRevenue.resize(); } catch (_) {}
                        try { charts.demoMix && charts.demoMix.resize(); } catch (_) {}
                    });
                }
            };

            hub.addEventListener('click', (e) => {
                const btn = e.target.closest('.demo-tab');
                if (!btn) return;
                userTouched = true;
                setActive(btn.dataset.demo);
            });

            // NOTE: no auto-cycle tabs — only manual switching (per request)

            // 3D tilt + spotlight (CSS vars --mx/--my)
            const tiltEls = Array.from(document.querySelectorAll('[data-tilt]'));
            const clamp = (v, a, b) => Math.max(a, Math.min(b, v));
            const onMove = (el, ev) => {
                const r = el.getBoundingClientRect();
                const x = (ev.clientX - r.left) / Math.max(1, r.width);
                const y = (ev.clientY - r.top) / Math.max(1, r.height);
                const rx = (0.5 - y) * 10; // deg
                const ry = (x - 0.5) * 12; // deg
                el.style.setProperty('--mx', (x * 100).toFixed(2) + '%');
                el.style.setProperty('--my', (y * 100).toFixed(2) + '%');
                el.style.transform = `perspective(1100px) rotateX(${clamp(rx, -10, 10)}deg) rotateY(${clamp(ry, -12, 12)}deg) translateY(-6px)`;
            };
            const onLeave = (el) => {
                el.style.setProperty('--mx', '40%');
                el.style.setProperty('--my', '30%');
                el.style.transform = '';
            };

            tiltEls.forEach((el) => {
                // always enable cursor tilt (requested), even if reduced-motion is on
                const move = (ev) => {
                    // make movement feel responsive (avoid “sticking”)
                    el.style.transition = 'transform .08s linear';
                    onMove(el, ev);
                };
                el.addEventListener('pointermove', move);
                el.addEventListener('mousemove', move);
                el.addEventListener('pointerleave', () => {
                    el.style.transition = '';
                    onLeave(el);
                });
                el.addEventListener('pointerdown', () => { userTouched = true; });
            });

            // Automation pulses (SVG path animation)
            (function initAutomationPulses() {
                if (prefersReduced) return;
                const svg = document.querySelector('svg.auto-flow');
                if (!svg) return;
                const path = svg.querySelector('#autoPath');
                if (!path || typeof path.getTotalLength !== 'function') return;
                const pulses = Array.from(svg.querySelectorAll('.auto-pulse'));
                if (!pulses.length) return;

                const len = path.getTotalLength();
                const speeds = [0.18, 0.12]; // cycles per second (approx)
                const offsets = [0.0, 0.42];

                function tick(t) {
                    const seconds = t / 1000;
                    pulses.forEach((c, i) => {
                        const p = (seconds * speeds[i % speeds.length] + offsets[i % offsets.length]) % 1;
                        const pt = path.getPointAtLength(p * len);
                        c.setAttribute('cx', pt.x);
                        c.setAttribute('cy', pt.y);
                    });
                    requestAnimationFrame(tick);
                }
                requestAnimationFrame(tick);
            })();

            // live chart updates (only while analytics active)
            // Keep it dynamic even if OS "reduce motion" is enabled (requested).
            window.setInterval(() => {
                if (active !== 'analytics') return;
                const charts = window.__overviewCharts || {};
                const a = charts.demoRevenue;
                const b = charts.demoMix;
                if (a) {
                    const d = a.data.datasets[0].data;
                    const last = d[d.length - 1] || 20;
                    const next = Math.max(8, Math.min(60, last + (-2 + Math.random() * 5)));
                    d.shift(); d.push(Number(next.toFixed(1)));
                    a.update();
                }
                if (b) {
                    const arr = window.__demoMixCounts || b.data.datasets[0].data;
                    // subtle variation while keeping descending funnel
                    arr[0] = Math.round(Math.max(90, Math.min(160, arr[0] + (-3 + Math.random() * 6))));
                    for (let i = 1; i < arr.length; i++) {
                        const prev = Math.max(1, arr[i - 1]);
                        const target = Math.min(prev, arr[i] + (-2 + Math.random() * 4));
                        arr[i] = Math.round(Math.max(10, target));
                    }
                    b.data.datasets[0].data = arr;
                    b.update();
                }
            }, 1800);
        }

        // Global scroll effects
        updateScrollProgress();
        window.addEventListener('scroll', updateScrollProgress, { passive: true });
        window.addEventListener('resize', updateScrollProgress);
        initDemoHub();
    </script>
</div>
</body>
</html>
