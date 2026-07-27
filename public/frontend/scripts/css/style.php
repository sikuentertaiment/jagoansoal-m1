<style>
  :root {
    --primary: #2563EB;
    --primary-hover: #1D4ED8;
    --primary-light: rgba(37, 99, 235, 0.1);
    --secondary: #10B981;
    --secondary-hover: #059669;
    --accent: #F59E0B;
    --accent-hover: #D97706;
    --dark: #0F172A;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06);
    --shadow-md: 0 4px 12px rgba(0,0,0,0.06), 0 2px 4px rgba(0,0,0,0.04);
    --shadow-lg: 0 12px 40px rgba(0,0,0,0.08), 0 4px 12px rgba(0,0,0,0.04);
    --shadow-xl: 0 20px 60px rgba(0,0,0,0.1);
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 20px;
    --radius-xl: 24px;
    --transition: all 0.2s ease;
  }

  html {
    scroll-behavior: smooth;
    scroll-padding-top: 80px;
  }

  * {
    font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
    font-weight: 400;
    font-style: normal;
  }

  h1, h2, h3, h4, h5, h6 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
  }

  body {
    margin: 0;
    background: linear-gradient(180deg, #ffffff 0%, #eff6ff 50%, #f8fafc 100%);
    color: var(--dark);
    min-height: 100vh;
  }

  /* Navbar */
  .navbar {
    position: sticky;
    top: 0;
    z-index: 50;
    padding: 12px 0;
    max-height: 55px;
    height: 55px;
    min-height: 55px;
  }

  .navbar-container {
    margin: 0 auto;
    padding: 0 16px;
    display: flex;
    align-items: center;
    justify-content: start;
    height: 100%;
  }

  .navbar-brand {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: var(--dark);
    font-weight: 700;
    font-size: 20px;
  }

  /* (lang-toggle removed - Indonesian only) */

  .lang-flag {
    line-height: 1;
  }

  .sidebar-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    padding: 8px 0;
    margin-right: 16px;
    color: var(--dark);
  }

  @media (max-width: 768px) {
    .sidebar-toggle {
      display: block;
    }
  }

  /* Page Layout */
  .page {
    display: none !important;
  }

  .page.active {
    display: block !important;
  }

  .page.entering {
    animation: pageEnter 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
  }

  .page.exiting {
    animation: pageExit 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
  }

  @keyframes pageEnter {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @keyframes pageExit {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(-16px); }
  }

  .page.entering-scale {
    animation: pageEnterScale 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
  }

  .page.exiting-scale {
    animation: pageExitScale 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
  }

  @keyframes pageEnterScale {
    from { opacity: 0; transform: scale(0.96); }
    to { opacity: 1; transform: scale(1); }
  }

  @keyframes pageExitScale {
    from { opacity: 1; transform: scale(1); }
    to { opacity: 0; transform: scale(1.02); }
  }

  /* Landing Page */
  .landing-page {
    min-height: 100vh;
    overflow-x: hidden;
  }

  /* Landing Navbar */
  .landing-navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 100;
    padding: 20px 0;
    background: transparent;
    backdrop-filter: blur(16px) saturate(180%);
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    border-bottom: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    display: none;
  }

  .landing-navbar.visible {
    display: block;
  }

  .landing-navbar.scrolled {
    background: rgba(255, 255, 255, 0.85);
    box-shadow: 0 1px 20px rgba(0,0,0,0.06);
    padding: 12px 0;
  }

  .landing-navbar-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
  }

  .landing-navbar-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: var(--dark);
    font-size: 22px;
    font-weight: 700;
  }

  .landing-navbar-menu {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .landing-navbar-link {
    padding: 10px 18px;
    text-decoration: none;
    color: var(--gray-600);
    font-size: 15px;
    font-weight: 500;
    border-radius: 50px;
    transition: all 0.2s ease;
  }

  .landing-navbar-link:hover {
    color: var(--primary);
  }

  .landing-navbar-link.active {
    color: var(--primary);
    font-weight: 600;
  }

  .landing-navbar-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, var(--primary), var(--primary-hover));
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s ease;
    margin-left: 12px;
  }

  .landing-navbar-cta:hover {
    background: linear-gradient(135deg, var(--primary-hover), var(--primary));
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
  }

  .landing-navbar-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--dark);
    padding: 4px;
  }

  @media (max-width: 768px) {
    .landing-navbar-menu {
      display: none;
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: white;
      flex-direction: column;
      padding: 16px;
      border-bottom: 1px solid var(--gray-200);
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .landing-navbar-menu.open {
      display: flex;
    }

    .landing-navbar-link {
      width: 100%;
      text-align: center;
    }

    .landing-navbar-toggle {
      display: block;
    }

    .landing-navbar-cta {
      margin: 12px 0 0;
      justify-content: center;
    }
  }

  /* Hero Section */
  .hero-section {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 100px 24px 0px;
    position: relative;
    background: linear-gradient(180deg, #eff6ff 0%, #f0f9ff 50%, #f8fafc 100%);
    overflow: hidden;
  }

  .hero-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
      linear-gradient(rgba(37, 99, 235, 0.04) 1px, transparent 1px),
      linear-gradient(90deg, rgba(37, 99, 235, 0.04) 1px, transparent 1px),
      radial-gradient(circle at 20% 20%, rgba(37, 99, 235, 0.06) 0%, transparent 50%),
      radial-gradient(circle at 80% 80%, rgba(16, 185, 129, 0.04) 0%, transparent 40%);
    background-size: 64px 64px, 64px 64px, 100% 100%, 100% 100%;
    pointer-events: none;
  }

  .hero-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    max-width: 1100px;
    width: 100%;
    align-items: flex-start;
    position: relative;
    z-index: 1;
  }

  .hero-text-col {
    text-align: left;
  }

  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: rgba(37, 99, 235, 0.08);
    border: 1px solid rgba(37, 99, 235, 0.15);
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    color: var(--primary);
    margin-bottom: 20px;
  }

  .hero-badge i {
    font-size: 12px;
  }

  .hero-title {
    font-size: clamp(2rem, 4.5vw, 3.2rem);
    line-height: 1.15;
    margin-bottom: 16px;
  }

  .hero-subtitle {
    font-size: 16px;
    line-height: 1.7;
    color: var(--gray-500);
    max-width: 520px;
    margin-bottom: 28px;
  }

  .hero-cta {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 32px;
    background: linear-gradient(135deg, var(--primary), #1d4ed8);
    color: white;
    font-size: 16px;
    font-weight: 600;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 4px 20px rgba(37, 99, 235, 0.25);
  }

  .hero-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(37, 99, 235, 0.35);
  }

  .hero-stats {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0;
    margin-top: 24px;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 16px;
    padding: 16px 20px;
    width: 100%;
  }

  .hero-stat-item {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
    justify-content: center;
  }

  .hero-stat-icon {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.08), rgba(37, 99, 235, 0.03));
    border-radius: 12px;
    color: var(--primary);
    font-size: 18px;
    flex-shrink: 0;
  }

  .hero-stat-info {
    display: flex;
    flex-direction: column;
    gap: 1px;
  }

  .hero-stat-number {
    font-size: 18px;
    font-weight: 800;
    color: var(--dark);
    line-height: 1.2;
  }

  .hero-stat-label {
    font-size: 12px;
    color: var(--gray-400);
    font-weight: 500;
    white-space: nowrap;
  }

  .hero-stat-divider {
    width: 1px;
    height: 36px;
    background: #e2e8f0;
    margin: 0 4px;
    flex-shrink: 0;
  }

  /* Hero Visual / Mockup */
  .hero-visual-col {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding-top: 10px;
    flex-direction: column;
  }

  .hero-mockup-stack {
    position: relative;
    width: 100%;
    height: 300px;
    overflow: visible;
  }

  .hero-card-mockup {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08), 0 8px 24px rgba(0, 0, 0, 0.04);
    overflow: hidden;
    transition: all 0.65s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform, opacity, top;
  }

  .hero-card-mockup.stack-front {
    top: 40px;
    z-index: 3;
    opacity: 1;
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.12), 0 10px 30px rgba(0, 0, 0, 0.06);
  }

  .hero-card-mockup.stack-middle {
    top: 8px;
    z-index: 2;
    opacity: 0.8;
    transform: translateX(-50%) scale(0.96);
  }

  .hero-card-mockup.stack-back {
    top: -24px;
    z-index: 1;
    opacity: 0.55;
    transform: translateX(-50%) scale(0.92);
  }

  .mockup-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
  }

  .mockup-dots {
    display: flex;
    gap: 6px;
  }

  .mockup-dots span {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #e2e8f0;
  }

  .mockup-dots span:nth-child(1) { background: #ef4444; }
  .mockup-dots span:nth-child(2) { background: #f59e0b; }
  .mockup-dots span:nth-child(3) { background: #10b981; }

  .mockup-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--gray-500);
  }

  .mockup-body {
    padding: 20px;
  }

  .mockup-question {
    margin-bottom: 16px;
  }

  .mockup-q-badge {
    display: inline-block;
    padding: 2px 8px;
    font-size: 11px;
    font-weight: 700;
    color: white;
    background: var(--primary);
    border-radius: 4px;
    margin-bottom: 10px;
  }

  .mockup-q-text {
    font-size: 14px;
    line-height: 1.6;
    color: var(--gray-700);
    margin: 0;
  }

  .mockup-options {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 16px;
  }

  .mockup-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    background: #f8fafc;
    border-radius: 8px;
    font-size: 13px;
    color: var(--gray-600);
  }

  .mockup-opt-letter {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
    color: var(--gray-500);
  }

  .mockup-footer {
    display: flex;
    align-items: center;
    gap: 8px;
    padding-top: 14px;
    border-top: 1px dashed #e2e8f0;
  }

  .mockup-answer-label {
    font-size: 12px;
    color: var(--gray-400);
    font-weight: 500;
  }

  .mockup-answer-value {
    font-size: 13px;
    font-weight: 700;
    color: var(--secondary);
    background: rgba(16, 185, 129, 0.1);
    padding: 2px 10px;
    border-radius: 4px;
  }

  /* Floating icons */
  .hero-float-icon {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    width: auto;
    padding: 10px;
    height: 44px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
    color: var(--primary);
    font-size: 18px;
    animation: float 6s ease-in-out infinite;
    pointer-events: none;
    gap: 5px;
  }

  .hero-float-1 {
    top: 5%;
    right: -10px;
    color: #10b981;
    animation-delay: 0s;
  }

  .hero-float-2 {
    bottom: 15%;
    left: -20px;
    animation-delay: 2s;
  }

  .hero-float-3 {
    top: 40%;
    right: -30px;
    color: #f59e0b;
    animation-delay: 4s;
  }

  .hero-float-4 {
    top: 15%;
    left: -15px;
    color: #ef4444;
    animation-delay: 1s;
  }

  .hero-float-5 {
    top: 25%;
    left: -20px;
    color: #2563eb;
    animation-delay: 3s;
  }

  .hero-float-6 {
    top: 8%;
    left: 70px;
    color: #8b5cf6;
    animation-delay: 5s;
  }

  @media (max-width: 868px) {
    .hero-grid {
      grid-template-columns: 1fr;
      gap: 40px;
      text-align: center;
    }

    .hero-text-col {
      text-align: center;
    }

    .hero-subtitle {
      max-width: 100%;
      margin-left: auto;
      margin-right: auto;
    }

    .hero-stats {
      justify-content: center;
    }

    .hero-visual-col {
      display: block;
      margin-top: 20px;
    }

    .hero-mockup-stack {
      max-width: 380px;
      margin: 0 auto;
    }

    .hero-section {
      padding: 200px 20px 60px;
    }
  }

  @media (max-width: 480px) {
    .hero-stats {
      flex-direction: column;
      gap: 12px;
      padding: 14px 16px;
    }

    .hero-stat-divider {
      width: 60%;
      height: 1px;
      margin: 0 auto;
    }

    .hero-stat-number {
      font-size: 17px;
    }
  }

  .hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
      linear-gradient(rgba(37, 99, 235, 0.04) 1px, transparent 1px),
      linear-gradient(90deg, rgba(37, 99, 235, 0.04) 1px, transparent 1px),
      radial-gradient(circle at 20% 20%, rgba(37, 99, 235, 0.06) 0%, transparent 50%),
      radial-gradient(circle at 80% 80%, rgba(16, 185, 129, 0.04) 0%, transparent 40%),
      radial-gradient(circle at 50% 50%, rgba(15, 23, 42, 0.02) 0%, transparent 60%);
    background-size: 64px 64px, 64px 64px, 100% 100%, 100% 100%, 100% 100%;
    pointer-events: none;
  }

  .hero-decor {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    opacity: 0.6;
  }

  .hero-decor-1 {
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.08) 0%, transparent 70%);
    top: -100px;
    left: -100px;
    animation: float 20s ease-in-out infinite;
  }

  .hero-decor-2 {
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.04) 0%, transparent 70%);
    top: 20%;
    right: -50px;
    animation: float 15s ease-in-out infinite reverse;
  }

  .hero-decor-3 {
    width: 150px;
    height: 150px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.06) 0%, transparent 70%);
    bottom: 10%;
    left: 5%;
    animation: float 18s ease-in-out infinite;
  }

  .hero-decor-4 {
    width: 80px;
    height: 80px;
    background: rgba(37, 99, 235, 0.1);
    top: 30%;
    left: 15%;
    animation: pulse 4s ease-in-out infinite;
  }

  .hero-decor-5 {
    width: 120px;
    height: 120px;
    border: 1px solid rgba(37, 99, 235, 0.15);
    background: transparent;
    bottom: 20%;
    right: 10%;
    animation: float 22s ease-in-out infinite;
  }

  .hero-decor-6 {
    width: 40px;
    height: 40px;
    background: rgba(15, 23, 42, 0.05);
    top: 40%;
    right: 20%;
    animation: float 12s ease-in-out infinite;
  }

  .hero-decor-7 {
    width: 60px;
    height: 60px;
    border: 1px solid rgba(15, 23, 42, 0.05);
    background: transparent;
    top: 60%;
    left: 25%;
    animation: float 16s ease-in-out infinite reverse;
  }

  .hero-decor-8 {
    width: 20px;
    height: 20px;
    background: rgba(37, 99, 235, 0.2);
    top: 15%;
    right: 30%;
    animation: pulse 3s ease-in-out infinite;
  }

  .hero-decor-9 {
    width: 12px;
    height: 12px;
    background: rgba(15, 23, 42, 0.08);
    bottom: 35%;
    left: 40%;
    animation: pulse 5s ease-in-out infinite;
  }

  @keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
  }

  @keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.6; }
    50% { transform: scale(1.2); opacity: 1; }
  }

  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 100px;
    font-size: 13px;
    color: var(--gray-600);
    margin-bottom: 32px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  }

  .hero-badge-dot {
    width: 8px;
    height: 8px;
    background: var(--secondary);
    border-radius: 50%;
    animation: pulse-dot 2s ease-in-out infinite;
  }

  @keyframes pulse-dot {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
  }

  .hero-title {
    font-size: clamp(36px, 6.5vw, 72px);
    font-weight: 700;
    letter-spacing: -0.02em;
    line-height: 1.15;
    margin: 0 0 16px;
    max-width: 800px;
    background: black;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .hero-title-highlight {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .hero-subtitle {
    font-size: clamp(15px, 1.8vw, 18px);
    color: var(--gray-500);
    margin: 0 0 36px;
    max-width: 540px;
    line-height: 1.7;
    padding: 0 16px;
  }

  .hero-cta {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 16px 36px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
    color: white;
    text-decoration: none;
    border-radius: 14px;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(37, 99, 235, 0.25);
    margin-bottom: 48px;
  }

  .hero-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(37, 99, 235, 0.35);
  }

  .hero-cta i {
    transition: transform 0.3s ease;
  }

  .hero-cta:hover i {
    transform: translateX(4px);
  }

  .hero-stats {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0;
    flex-wrap: wrap;
    width: 100%;
    max-width: 680px;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 20px;
    padding: 24px 0;
  }

  .hero-stat-item {
    flex: 1;
    min-width: 120px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    padding: 8px 16px;
    border-right: 1px solid var(--gray-200);
  }

  .hero-stat-item:last-child {
    border-right: none;
  }

  .hero-stat-number {
    font-size: clamp(24px, 3vw, 32px);
    font-weight: 700;
    color: var(--primary);
    line-height: 1.2;
  }

  .hero-stat-label {
    font-size: 12px;
    color: var(--gray-500);
    font-weight: 500;
    text-align: center;
  }

  @media (max-width: 640px) {
    .hero-section {
      padding: 200px 16px 60px;
      min-height: 90vh;
      justify-content: center;
    }

    .hero-stats {
      flex-direction: column;
      gap: 0;
      padding: 20px 0;
    }

    .hero-stat-item {
      border-right: none;
      border-bottom: 1px solid var(--gray-200);
      padding: 12px 16px;
      width: 100%;
    }

    .hero-stat-item:last-child {
      border-bottom: none;
    }

    .hero-decor-1 { width: 180px; height: 180px; top: -60px; left: -60px; }
    .hero-decor-2 { width: 120px; height: 120px; }
    .hero-decor-3 { width: 100px; height: 100px; }
    .hero-decor-4, .hero-decor-5, .hero-decor-6, .hero-decor-7, .hero-decor-8, .hero-decor-9 { display: none; }
  }

  @media (max-width: 480px) {
    .hero-section {
      padding: 200px 12px 40px;
    }

    .hero-cta {
      padding: 14px 28px;
      font-size: 14px;
      width: 100%;
      max-width: 300px;
      justify-content: center;
    }
  }

  /* How It Works / Tutorial Section */
  .howitworks-section,
  .tutorial-section {
    padding: 100px 24px;
    background: white;
  }

  .features-section {
    padding: 100px 24px;
    background: #f8fafc;
  }

  .tutorial-container {
    max-width: 1000px;
    margin: 0 auto;
  }

  .tutorial-header {
    text-align: center;
    margin-bottom: 64px;
  }

  .tutorial-title {
    font-size: 36px;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 12px;
  }

  .tutorial-subtitle {
    font-size: 16px;
    color: var(--gray-500);
    margin: 0;
  }

  .tutorial-title-underline {
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    border-radius: 2px;
    margin: 16px auto 0;
  }

  .tutorial-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 32px;
  }

  .tutorial-card {
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(8px);
    border-radius: 20px;
    border: 1px solid rgba(0,0,0,0.04);
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .tutorial-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--primary), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .tutorial-card:hover {
    border-color: rgba(37, 99, 235, 0.1);
    box-shadow: 0 12px 40px rgba(0,0,0,0.08);
    transform: translateY(-4px);
  }

  .tutorial-card:hover::before {
    opacity: 1;
  }

  .tutorial-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border-radius: 14px;
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 20px;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
  }

  .tutorial-card-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--dark);
    margin: 0 0 8px;
  }

  .tutorial-card-desc {
    font-size: 14px;
    color: var(--gray-500);
    line-height: 1.6;
    margin: 0;
  }

  /* Tutorial Video Grid (Landing Page) */
  .tutorial-video-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 28px;
  }
  .tutorial-video-card {
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
  }
  .tutorial-video-card:hover {
    border-color: rgba(37, 99, 235, 0.15);
    box-shadow: var(--shadow-md);
    transform: translateY(-3px);
  }
  .tutorial-video-wrapper {
    position: relative;
    width: 100%;
    padding-top: 56.25%;
    background: #000;
  }
  .tutorial-video-wrapper iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
  }
  .tutorial-video-body {
    padding: 18px 20px 22px;
  }
  .tutorial-video-title {
    font-family: var(--font-heading, 'Plus Jakarta Sans'), system-ui, sans-serif;
    font-size: 15px;
    font-weight: 600;
    color: var(--dark);
    margin: 0 0 6px;
    line-height: 1.4;
  }
  .tutorial-video-desc {
    font-size: 13px;
    color: var(--gray-500);
    line-height: 1.6;
    margin: 0;
  }
  .tutorial-empty {
    text-align: center;
    padding: 48px 24px;
    color: var(--gray-400);
  }
  .tutorial-empty i {
    font-size: 48px;
    margin-bottom: 16px;
    display: block;
  }
  .tutorial-empty p {
    font-size: 14px;
    margin: 0;
  }

  @media (max-width: 768px) {
    .tutorial-video-grid {
      grid-template-columns: 1fr;
      gap: 20px;
    }
    .tutorial-video-body { padding: 14px 16px 18px; }
  }

  /* Tutorial Tabs */
  .tutorial-tabs {
    display: flex;
    justify-content: center;
    margin-bottom: 48px;
    position: relative;
  }

  .tutorial-tabs-track {
    display: flex;
    background: var(--gray-100);
    border-radius: 100px;
    padding: 4px;
    position: relative;
    width: fit-content;
  }

  .tutorial-tab {
    padding: 14px 32px;
    border: none;
    background: transparent;
    border-radius: 100px;
    font-size: 14px;
    font-weight: 500;
    color: var(--gray-600);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
  }

  .tutorial-tab:hover {
    color: var(--dark);
  }

  .tutorial-tab.active {
    color: var(--primary);
  }

  .tutorial-tab i {
    font-size: 16px;
    transition: transform 0.3s ease;
  }

  .tutorial-tab.active i {
    transform: scale(1.1);
  }

  .tutorial-tabs-indicator {
    position: absolute;
    top: 4px;
    bottom: 4px;
    left: 4px;
    background: white;
    border-radius: 100px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1;
    pointer-events: none;
  }

  @media (max-width: 768px) {
    .tutorial-tabs-track {
      width: 100%;
      max-width: 320px;
    }

    .tutorial-tab {
      flex: 1;
      justify-content: center;
      padding: 12px 16px;
      font-size: 13px;
    }

    .tutorial-tab i {
      font-size: 14px;
    }

    .howitworks-section,
    .tutorial-section {
      padding: 60px 16px;
    }

    .features-section {
      padding: 60px 16px;
    }

    .tutorial-header {
      margin-bottom: 40px;
    }

    .tutorial-title {
      font-size: 28px;
    }

    .tutorial-grid {
      gap: 20px;
    }

    .tutorial-card {
      padding: 24px;
    }
  }

  @media (max-width: 480px) {
    .tutorial-grid {
      grid-template-columns: 1fr;
    }

    .tutorial-card {
      padding: 20px;
    }

    .tutorial-number {
      width: 36px;
      height: 36px;
      font-size: 15px;
      margin-bottom: 16px;
    }

    .tutorial-card-title {
      font-size: 16px;
    }
  }

  /* Features Grid */
  .features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-top: 64px;
    margin-left: auto;
    margin-right: auto;
  }

  .feature-card {
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 16px;
    padding: 28px 24px;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
  }

  .feature-card:hover {
    border-color: rgba(37, 99, 235, 0.15);
    box-shadow: var(--shadow-md);
    transform: translateY(-3px);
  }

  .feature-icon {
    width: 52px;
    height: 52px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    font-size: 22px;
    margin-bottom: 16px;
  }

  .feature-title {
    font-family: var(--font-heading, 'Plus Jakarta Sans'), system-ui, sans-serif;
    font-size: 16px;
    font-weight: 600;
    color: var(--dark);
    margin: 0 0 8px;
  }

  .feature-desc {
    font-size: 13px;
    color: var(--gray-500);
    line-height: 1.6;
    margin: 0;
  }

  @media (max-width: 768px) {
    .features-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 16px;
      margin-top: 40px;
    }

    .feature-card {
      padding: 20px 16px;
    }

    .feature-icon {
      width: 44px;
      height: 44px;
      font-size: 18px;
      margin-bottom: 12px;
    }
  }

  @media (max-width: 480px) {
    .features-grid {
      grid-template-columns: 1fr;
      max-width: 360px;
    }
  }

  /* Export Features Sub-section */
  .export-features-section {
    margin-top: 64px;
    text-align: center;
  }
  .export-features-title {
    font-family: var(--font-heading, 'Plus Jakarta Sans'), system-ui, sans-serif;
    font-size: 20px;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 8px;
  }
  .export-features-title i {
    color: var(--primary);
    margin-right: 8px;
  }
  .export-features-subtitle {
    font-size: 14px;
    color: var(--gray-500);
    margin: 0 0 32px;
  }
  .export-features-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin: 0 auto 24px;
  }
  .export-format-card {
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 14px;
    padding: 24px 16px;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
  }
  .export-format-card:hover {
    border-color: rgba(37, 99, 235, 0.15);
    box-shadow: var(--shadow-md);
    transform: translateY(-3px);
  }
  .export-format-icon {
    width: 48px;
    height: 48px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 20px;
    margin-bottom: 14px;
  }
  .export-format-title {
    font-family: var(--font-heading, 'Plus Jakarta Sans'), system-ui, sans-serif;
    font-size: 15px;
    font-weight: 600;
    color: var(--dark);
    margin: 0 0 6px;
  }
  .export-format-desc {
    font-size: 12px;
    color: var(--gray-500);
    line-height: 1.6;
    margin: 0;
  }
  .export-custom-msg {
    font-size: 13px;
    color: var(--gray-500);
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: 10px;
    padding: 14px 20px;
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
    line-height: 1.6;
  }
  .export-custom-msg i {
    color: var(--primary);
    margin-right: 6px;
  }

  @media (max-width: 768px) {
    .export-features-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 14px;
    }
    .export-features-title { font-size: 18px; }
    .export-custom-msg { font-size: 12px; padding: 12px 14px; }
  }

  @media (max-width: 480px) {
    .export-features-grid {
      grid-template-columns: 1fr;
      max-width: 360px;
    }
  }

  /* CEO Section */
  .ceo-section {
    margin-top: 64px;
  }

  .ceo-card {
    display: flex;
    align-items: center;
    gap: 32px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.04), rgba(16, 185, 129, 0.04));
    border: 1px solid var(--gray-200);
    border-radius: 20px;
    padding: 36px 40px;
    margin: 0 auto;
    box-shadow: var(--shadow-sm);
  }

  .ceo-photo {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 3px solid white;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
  }

  .ceo-content {
    flex: 1;
  }

  .ceo-quote {
    font-size: 14px;
    color: var(--gray-600);
    line-height: 1.7;
    margin: 0 0 12px;
    font-style: italic;
  }

  .ceo-name {
    font-size: 15px;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
  }

  .ceo-title {
    font-size: 13px;
    color: var(--gray-500);
    margin: 2px 0 0;
  }

  @media (max-width: 640px) {
    .ceo-card {
      flex-direction: column;
      text-align: center;
      padding: 28px 24px;
      gap: 16px;
    }

    .ceo-photo {
      width: 72px;
      height: 72px;
    }
  }

  /* Pricing Section */
  .pricing-section {
    padding: 100px 24px;
    background: #ffffff;
  }

  .pricing-container {
    max-width: 1000px;
    margin: 0 auto;
  }

  .pricing-header {
    text-align: center;
    margin-bottom: 64px;
  }

  .pricing-title {
    font-size: 36px;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 12px;
  }

  .pricing-subtitle {
    font-size: 16px;
    color: var(--gray-500);
    margin: 0;
  }

  .pricing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
  }

  .pricing-grid-2 {
    grid-template-columns: repeat(2, 1fr);
    max-width: 800px;
    margin: 0 auto;
  }

  @media (max-width: 640px) {
    .pricing-section {
      padding: 60px 16px;
    }

    .pricing-header {
      margin-bottom: 40px;
    }

    .pricing-title {
      font-size: 28px;
    }
  }

  @media (max-width: 600px) {
    .pricing-grid-2 {
      grid-template-columns: 1fr;
      max-width: 400px;
    }

    .pricing-card.featured {
      transform: none;
    }
  }

  @media (max-width: 480px) {
    .pricing-card {
      padding: 28px 20px;
    }

    .pricing-price {
      font-size: 36px;
    }
  }

  .pricing-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    font-size: 11px;
    font-weight: 700;
    padding: 6px 16px;
    border-radius: 100px;
    margin-bottom: 16px;
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    letter-spacing: 0.02em;
    text-transform: uppercase;
  }

  .pricing-card.featured {
    position: relative;
  }

  .pricing-currency {
    font-size: 18px;
    font-weight: 500;
    margin-left: 4px;
  }

  .pricing-card {
    border-radius: 24px;
    padding: 40px 32px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
  }

  .pricing-card.featured {
    background: linear-gradient(135deg, var(--dark) 0%, #1e3a5f 50%, var(--dark) 100%);
    color: white;
    transform: scale(1.05);
    box-shadow: 0 20px 60px rgba(15, 23, 42, 0.2);
  }

  .pricing-card:not(.featured) {
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(0,0,0,0.06);
    box-shadow: var(--shadow-sm);
  }

  .pricing-card:not(.featured):hover {
    border-color: rgba(37, 99, 235, 0.2);
    box-shadow: var(--shadow-lg);
    transform: translateY(-4px);
  }

  .pricing-name {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 8px;
  }

  .pricing-price {
    font-size: 48px;
    font-weight: 700;
    margin: 0 0 8px;
  }

  .pricing-card.featured .pricing-price {
    color: white;
  }

  .pricing-period {
    font-size: 14px;
    color: var(--gray-400);
    margin: 0 0 24px;
  }

  .pricing-card.featured .pricing-period {
    color: rgba(255,255,255,0.7);
  }

  .pricing-features {
    list-style: none;
    padding: 0;
    margin: 0 0 32px;
    text-align: left;
  }

  .pricing-features li {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    font-size: 14px;
    border-bottom: 1px solid var(--gray-100);
  }

  .pricing-card.featured .pricing-features li {
    border-color: rgba(255,255,255,0.1);
  }

  .pricing-features li:last-child {
    border-bottom: none;
  }

  .pricing-features i {
    color: var(--primary);
    width: 16px;
  }

  .pricing-card.featured .pricing-features i {
    color: var(--secondary);
  }

  .pricing-btn {
    display: block;
    width: 100%;
    padding: 14px 24px;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s;
  }

  .pricing-card.featured .pricing-btn {
    background: white;
    color: var(--dark);
    border: none;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  .pricing-card.featured .pricing-btn:hover {
    background: var(--gray-50);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
  }

  .pricing-card:not(.featured) .pricing-btn {
    background: transparent;
    color: var(--dark);
    border: 1px solid var(--gray-200);
    font-weight: 600;
  }

  .pricing-card:not(.featured) .pricing-btn:hover {
    border-color: var(--primary);
    background: var(--primary);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(37, 99, 235, 0.15);
  }

  /* FAQ Section */
  .faq-section {
    padding: 100px 24px;
    background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
  }

  .faq-container {
    max-width: 700px;
    margin: 0 auto;
  }

  .faq-header {
    text-align: center;
    margin-bottom: 64px;
  }

  .faq-title {
    font-size: 36px;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 12px;
  }

  .faq-subtitle {
    font-size: 16px;
    color: var(--gray-500);
    margin: 0;
  }

  .faq-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  .faq-item {
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(8px);
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.04);
    overflow: hidden;
    transition: all 0.2s ease;
    box-shadow: var(--shadow-sm);
  }

  .faq-item:hover {
    border-color: rgba(37, 99, 235, 0.1);
    box-shadow: var(--shadow-md);
  }

  .faq-question {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    color: var(--dark);
    text-align: left;
    gap: 16px;
    transition: all 0.2s ease;
  }

  .faq-question:hover {
    background: rgba(37, 99, 235, 0.04);
  }

  .faq-question i {
    font-size: 14px;
    color: var(--gray-400);
    transition: all 0.3s ease;
    flex-shrink: 0;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: var(--gray-100);
  }

  .faq-item.open .faq-question i {
    transform: rotate(180deg);
    color: white;
    background: var(--primary);
  }

  .faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
  }

  .faq-item.open .faq-answer {
    max-height: 300px;
  }

  .faq-answer-content {
    font-size: 14px;
    color: var(--gray-600);
    padding: 0 24px 20px;
    line-height: 1.7;
  }

  @media (max-width: 640px) {
    .faq-section {
      padding: 60px 16px;
    }

    .faq-header {
      margin-bottom: 40px;
    }

    .faq-title {
      font-size: 28px;
    }

    .faq-question {
      padding: 16px;
      font-size: 14px;
    }

    .faq-answer-content {
      padding: 0 16px 16px;
    }
  }

  /* Footer */
  .landing-footer {
    background: linear-gradient(135deg, var(--dark) 0%, #1e293b 100%);
    color: white;
    padding: 80px 24px 32px;
    position: relative;
    overflow: hidden;
  }

  .landing-footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(37, 99, 235, 0.3), transparent);
  }

  .landing-footer-container {
    max-width: 1200px;
    margin: 0 auto;
  }

  .landing-footer-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 48px;
    margin-bottom: 48px;
  }

  @media (max-width: 768px) {
    .landing-footer-grid {
      grid-template-columns: 1fr;
      gap: 32px;
    }
  }

  .landing-footer-brand {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .landing-footer-desc {
    font-size: 14px;
    color: var(--gray-400);
    line-height: 1.7;
    margin-bottom: 24px;
    max-width: 320px;
  }

  .landing-footer-social {
    display: flex;
    gap: 12px;
  }

  .landing-footer-social a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.08);
    border-radius: 10px;
    color: white;
    text-decoration: none;
    font-size: 16px;
    transition: all 0.2s ease;
  }

  .landing-footer-social a:hover {
    background: var(--primary);
    transform: translateY(-2px);
  }

  .landing-footer-title {
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 20px;
    color: var(--gray-400);
  }

  .landing-footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .landing-footer-links li {
    margin-bottom: 12px;
  }

  .landing-footer-links a {
    color: var(--gray-300);
    text-decoration: none;
    font-size: 14px;
    transition: color 0.2s ease;
  }

  .landing-footer-links a:hover {
    color: var(--primary);
  }

  .landing-footer-newsletter {
    margin-top: 32px;
  }

  .landing-footer-newsletter-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 16px;
    color: var(--gray-300);
  }

  .landing-footer-newsletter-form {
    display: flex;
    gap: 8px;
  }

  .landing-footer-newsletter-input {
    flex: 1;
    padding: 12px 16px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 8px;
    color: white;
    font-size: 14px;
  }

  .landing-footer-newsletter-input::placeholder {
    color: var(--gray-500);
  }

  .landing-footer-newsletter-input:focus {
    outline: none;
    border-color: var(--primary);
  }

  .landing-footer-newsletter-btn {
    padding: 12px 20px;
    background: var(--primary);
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s ease;
  }

  .landing-footer-newsletter-btn:hover {
    background: var(--primary-hover);
  }

  .landing-footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.08);
    padding-top: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
  }

  .landing-footer-copyright {
    font-size: 13px;
    color: var(--gray-500);
  }

  .landing-footer-legal {
    display: flex;
    gap: 24px;
  }

  .landing-footer-legal a {
    font-size: 13px;
    color: var(--gray-500);
    text-decoration: none;
    transition: color 0.2s ease;
  }

  .landing-footer-legal a:hover {
    color: white;
  }

  /* Tools Layout (minimal — most styles are inline in tools.php) */
  .admin-link {
    color: var(--accent) !important;
  }

  .admin-link i {
    color: var(--accent);
  }

  .admin-link:hover {
    background: rgba(245, 158, 11, 0.1) !important;
  }

  .navbar-credit {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 6px 12px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(16, 185, 129, 0.08));
    border: 1px solid rgba(37, 99, 235, 0.15);
    border-radius: 100px;
    font-size: 13px;
    font-weight: 600;
    color: var(--primary);
    white-space: nowrap;
    cursor: default;
    transition: all 0.2s;
    margin: 0 4px;
  }

  .navbar-credit i {
    font-size: 14px;
    color: var(--accent);
  }

  @media (max-width: 768px) {
    .navbar-credit {
      padding: 4px 10px;
      font-size: 12px;
    }
    .navbar-credit i {
      font-size: 12px;
    }
  }

  /* Form Elements */
  .form-group {
    margin-bottom: 14px;
  }

  .form-label {
    display: block;
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 5px;
    color: var(--dark);
  }

  .form-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    font-size: 13px;
    transition: all 0.2s;
    background: white;
    box-shadow: var(--shadow-sm);
  }

  .form-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  }

  .form-select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    font-size: 13px;
    background: white;
    cursor: pointer;
    box-shadow: var(--shadow-sm);
    transition: all 0.2s;
  }

  .form-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  }

  .form-textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    font-size: 13px;
    transition: all 0.2s;
    background: white;
    box-shadow: var(--shadow-sm);
    resize: vertical;
    min-height: 80px;
  }

  .form-textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  }

  .form-help {
    font-size: 11px;
    color: var(--gray-400);
    margin-top: 3px;
  }

  /* Input Method Tabs */
  .input-method-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
  }

  .input-method-tab {
    flex: 1;
    padding: 10px 16px;
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    color: var(--gray-600);
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
  }

  .input-method-tab:hover {
    background: var(--gray-100);
  }

  .input-method-tab.active {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
  }

  .input-method-content {
    display: none;
  }

  .input-method-content.active {
    display: block;
  }

  /* Upload Area */
  .upload-area {
    border: 2px dashed var(--gray-200);
    border-radius: 16px;
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    background: linear-gradient(135deg, var(--gray-50) 0%, #ffffff 100%);
    box-shadow: var(--shadow-sm);
  }

  .upload-area:hover {
    border-color: var(--primary);
    background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
    box-shadow: 0 4px 20px rgba(37, 99, 235, 0.08);
  }

  .upload-area.dragover {
    border-color: var(--primary);
    background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
    box-shadow: 0 4px 20px rgba(37, 99, 235, 0.12);
  }

  .upload-area.has-image {
    padding: 12px;
    border-style: solid;
    border-color: var(--gray-200);
  }

  .upload-icon {
    font-size: 40px;
    color: var(--gray-400);
    margin-bottom: 12px;
  }

  .upload-text {
    font-size: 14px;
    color: var(--gray-600);
    margin-bottom: 4px;
  }

  .upload-hint {
    font-size: 12px;
    color: var(--gray-400);
  }

  .upload-preview {
    position: relative;
    margin: 0 auto;
  }

  .upload-preview img {
    max-width: 100%;
    max-height: 300px;
    border-radius: 8px;
  }

  .upload-remove {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 28px;
    height: 28px;
    background: #dc2626;
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
  }

  .upload-remove:hover {
    background: #b91c1c;
  }

  .upload-input {
    display: none;
  }

  /* Style Cards */
  .style-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 12px;
  }

  .style-card {
    padding: 20px 12px;
    background: white;
    border: 2px solid var(--gray-100);
    border-radius: 14px;
    text-align: center;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--shadow-sm);
  }

  .style-card:hover {
    border-color: var(--gray-300);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
  }

  .style-card.selected {
    border-color: var(--primary);
    background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
    box-shadow: 0 4px 20px rgba(37, 99, 235, 0.15);
    transform: translateY(-2px);
  }

  .style-card.selected .style-card-icon {
    color: var(--primary);
  }

  .style-card-icon {
    font-size: 32px;
    color: var(--gray-400);
    margin-bottom: 10px;
    transition: all 0.2s;
  }

  .style-card-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--gray-600);
    transition: color 0.2s;
  }

  .style-card.selected .style-card-name {
    color: var(--primary);
  }

  /* Buttons */
  .btn-generate {
    width: 100%;
    padding: 14px 20px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s;
    box-shadow: 0 4px 16px rgba(37, 99, 235, 0.2);
  }

  .btn-generate:hover {
    background: linear-gradient(135deg, var(--primary-hover) 0%, var(--primary) 100%);
    box-shadow: 0 6px 24px rgba(37, 99, 235, 0.3);
    transform: translateY(-1px);
  }

  .btn-generate:disabled {
    background: var(--gray-300);
    box-shadow: none;
    cursor: not-allowed;
    transform: none;
  }

  .btn-secondary {
    padding: 10px 20px;
    background: var(--secondary);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
  }

  .btn-secondary:hover {
    background: var(--secondary-hover);
    transform: translateY(-1px);
  }

  .btn-outline {
    padding: 10px 20px;
    background: transparent;
    border: 1px solid var(--gray-200);
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    color: var(--gray-600);
    cursor: pointer;
    transition: all 0.2s;
  }

  .btn-outline:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: var(--primary-light);
  }

  /* Loading States */
  .loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 2px solid var(--gray-300);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
  }

  .loading-spinner-lg {
    width: 40px;
    height: 40px;
    border-width: 3px;
  }

  @keyframes spin {
    to { transform: rotate(360deg); }
  }

  .generation-loading {
    display: none;
    text-align: center;
    margin-top: 20px;
    padding: 32px;
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(8px);
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.04);
  }

  .generation-loading.active {
    display: block;
    animation: fadeInUp 0.3s ease;
  }

  .generation-loading-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 16px;
    position: relative;
  }

  .generation-loading-icon::before,
  .generation-loading-icon::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 50%;
    border: 3px solid transparent;
    border-top-color: var(--primary);
  }

  .generation-loading-icon::before {
    animation: spin 1.2s linear infinite;
  }

  .generation-loading-icon::after {
    inset: 8px;
    border-top-color: rgba(37, 99, 235, 0.3);
    animation: spin 1s linear infinite reverse;
  }

  .generation-loading-text {
    font-size: 14px;
    color: var(--gray-600);
  }

  .generation-loading-hint {
    font-size: 12px;
    color: var(--gray-400);
    margin-top: 8px;
  }

  /* Modal Overlay */
  .modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
  }

  .modal-overlay.active {
    display: flex;
    animation: fadeIn 0.2s ease;
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  .modal-box {
    background: white;
    border-radius: 20px;
    padding: 32px;
    max-width: 400px;
    width: 100%;
    animation: modalEnter 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    border: 1px solid rgba(0,0,0,0.04);
  }

  @keyframes modalEnter {
    from {
      opacity: 0;
      transform: scale(0.95) translateY(10px);
    }
    to {
      opacity: 1;
      transform: scale(1) translateY(0);
    }
  }

  .modal-icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    font-size: 24px;
  }

  .modal-icon.warning {
    background: #fef2f2;
    color: #dc2626;
  }

  .modal-icon.success {
    background: #f0fdf4;
    color: #16a34a;
  }

  .modal-icon.info {
    background: #eff6ff;
    color: var(--primary);
  }

  .modal-title {
    font-size: 20px;
    font-weight: 600;
    text-align: center;
    margin-bottom: 8px;
    color: var(--dark);
  }

  .modal-message {
    font-size: 14px;
    text-align: center;
    color: var(--gray-600);
    margin-bottom: 24px;
    line-height: 1.6;
  }

  .modal-actions {
    display: flex;
    gap: 12px;
  }

  .modal-btn {
    flex: 1;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
  }

  .modal-btn-cancel {
    background: var(--gray-100);
    color: var(--gray-700);
  }

  .modal-btn-cancel:hover {
    background: var(--gray-200);
  }

  .modal-btn-confirm {
    background: var(--primary);
    color: white;
  }

  .modal-btn-confirm:hover {
    background: var(--primary-hover);
  }

  .modal-btn-danger {
    background: #dc2626;
    color: white;
  }

  .modal-btn-danger:hover {
    background: #b91c1c;
  }

  .modal-btn-success {
    background: var(--secondary);
    color: white;
  }

  .modal-btn-success:hover {
    background: var(--secondary-hover);
  }

  .modal-btn-loading {
    opacity: 0.7;
    cursor: not-allowed;
  }

  /* Admin Panel */
  .admin-layout {
    display: flex;
    min-height: calc(100vh - 55px);
    height: calc(100vh - 55px);
    max-height: calc(100vh - 55px);
    gap: 20px;
  }

  .admin-sidebar {
    width: 200px;
    min-width: 200px;
    padding: 12px 10px;
    display: flex;
    flex-direction: column;
    gap: 3px;
    border-radius: 10px;
    border: 1px solid rgba(0,0,0,0.05);
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(16px) saturate(180%);
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    position: sticky;
    top: 63px;
    align-self: flex-start;
    height: calc(100vh - 79px);
    overflow-y: auto;
    box-shadow: 0 1px 4px rgba(0,0,0,0.02);
  }

  .admin-sidebar-header {
    font-size: 10px;
    font-weight: 700;
    color: var(--primary);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 12px;
    padding: 0 10px;
  }

  .admin-nav-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 7px 10px;
    border-radius: 8px;
    text-decoration: none;
    color: var(--gray-500);
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s;
  }

  .admin-nav-link:hover {
    background: var(--gray-100);
    color: var(--dark);
  }

  .admin-nav-link.active {
    background: var(--primary);
    color: white;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.15);
  }

  .admin-nav-link i {
    width: 16px;
    text-align: center;
    font-size: 14px;
  }

  .admin-content {
    flex: 1;
    min-width: 0;
    overflow-y: auto;
    padding: 20px;
  }

  .admin-panel {
    display: none;
  }

  .admin-panel.active {
    display: block;
    animation: fadeInUp 0.3s ease;
  }

  .admin-page-title {
    font-size: 18px;
    font-weight: 500;
    margin-bottom: 16px;
  }

  .admin-page-title i {
    margin-right: 8px;
    color: var(--primary);
  }

  .admin-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 12px;
  }

  .admin-stat-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px;
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(0,0,0,0.04);
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    transition: all 0.2s;
  }

  .admin-stat-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
  }

  .admin-stat-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
  }

  .admin-stat-icon.blue {
    background: #eff6ff;
    color: var(--primary);
  }

  .admin-stat-icon.green {
    background: #f0fdf4;
    color: var(--secondary);
  }

  .admin-stat-icon.amber {
    background: #fffbeb;
    color: var(--accent);
  }

  .admin-stat-info {
    display: flex;
    flex-direction: column;
  }

  .admin-stat-value {
    font-size: 20px;
    font-weight: 700;
    line-height: 1.2;
  }

  .admin-stat-label {
    font-size: 11px;
    color: var(--gray-500);
    margin-top: 1px;
  }

  .admin-chart-section {
    margin-top: 16px;
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    padding: 16px;
  }

  .admin-chart-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .admin-chart-title i {
    color: var(--primary);
  }

  .admin-chart-container {
    height: 200px;
    position: relative;
  }

  .admin-search {
    padding: 7px 12px;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    font-size: 13px;
    width: 220px;
    background: white;
    box-shadow: var(--shadow-sm);
    transition: all 0.2s;
  }

  .admin-search:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  }

  .admin-table-wrapper {
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(0,0,0,0.04);
    border-radius: 10px;
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    overflow-x: auto;
  }

  .admin-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
  }

  .admin-table th {
    text-align: left;
    padding: 10px 12px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--gray-500);
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-100);
    white-space: nowrap;
  }

  .admin-table td {
    padding: 8px 12px;
    border-bottom: 1px solid var(--gray-50);
    color: var(--gray-600);
  }

  .admin-table tr:hover td {
    background: rgba(37, 99, 235, 0.02);
  }

  .admin-table tr:last-child td {
    border-bottom: none;
  }

  .admin-user-cell {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .admin-user-avatar {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    object-fit: cover;
  }

  .admin-user-avatar-placeholder {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    color: var(--gray-400);
    flex-shrink: 0;
  }

  .admin-action-btn {
    padding: 5px 10px;
    border: 1px solid var(--gray-200);
    border-radius: 6px;
    background: white;
    cursor: pointer;
    font-size: 11px;
    font-weight: 500;
    color: var(--primary);
    transition: all 0.15s;
  }

  .admin-action-btn:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
  }

  .admin-mono {
    font-family: 'DM Mono', monospace;
    font-size: 11px;
  }

  .admin-date {
    font-size: 11px;
    color: var(--gray-400);
    white-space: nowrap;
  }

  .admin-loading {
    text-align: center;
    padding: 24px;
    color: var(--gray-400);
  }

  .admin-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 11px;
    font-weight: 500;
    padding: 3px 8px;
    border-radius: 4px;
  }

  .admin-status-success {
    background: rgba(16, 185, 129, 0.1);
    color: #15803d;
  }

  .admin-status-pending {
    background: rgba(245, 158, 11, 0.1);
    color: #a16207;
  }

  .admin-status-failed {
    background: rgba(239, 68, 68, 0.1);
    color: #b91c1c;
  }

  .admin-sidebar-overlay {
    display: none;
  }

  @media (max-width: 1024px) {
    .admin-sidebar {
      width: 180px;
      min-width: 180px;
    }
  }

  @media (max-width: 768px) {
    .admin-sidebar {
      position: fixed;
      left: -280px;
      top: 55px;
      bottom: 0;
      z-index: 40;
      transition: left 0.3s ease;
      width: 260px;
      min-width: 260px;
      height: calc(100vh - 55px);
      border-radius: 0;
      border: none;
      border-right: 1px solid rgba(0,0,0,0.06);
      background: rgba(255,255,255,0.92);
      backdrop-filter: blur(16px) saturate(180%);
      -webkit-backdrop-filter: blur(16px) saturate(180%);
      padding: 16px 10px;
      display: flex;
      flex-direction: column;
      gap: 3px;
    }

    .admin-sidebar.active {
      left: 0;
    }

    .admin-sidebar-overlay {
      display: none;
      position: fixed;
      inset: 0;
      top: 55px;
      background: rgba(0,0,0,0.5);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      z-index: 30;
    }

    .admin-sidebar-overlay.active {
      display: block;
    }

    .admin-content {
      padding: 16px;
    }
    .admin-stats-grid {
      grid-template-columns: 1fr 1fr;
      gap: 8px;
    }
    .admin-search {
      width: 140px;
    }
    .admin-page-title {
      font-size: 16px;
      margin-bottom: 12px;
    }
    .admin-table-wrapper {
      border-radius: 8px;
    }
    .admin-table th {
      padding: 8px 10px;
    }
    .admin-table td {
      padding: 6px 10px;
    }
  }

  @media (max-width: 480px) {
    .admin-content {
      padding: 12px;
    }
    .admin-stats-grid {
      grid-template-columns: 1fr;
      gap: 6px;
    }
    .admin-stat-card {
      padding: 12px;
    }
    .admin-chart-section {
      padding: 12px;
    }
    .admin-chart-container {
      height: 160px;
    }
    .admin-table th {
      padding: 6px 8px;
      font-size: 9px;
    }
    .admin-table td {
      padding: 5px 8px;
      font-size: 11px;
    }
  }

  /* Auth Pages */
  .auth-container {
    min-height: calc(100vh - 55px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    flex-direction: column;
  }

  .auth-card {
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    padding: 32px;
    width: 100%;
    max-width: 400px;
    text-align: center;
  }

  .auth-title {
    font-size: 20px;
    font-weight: 600;
    margin: 0 0 6px;
  }

  .auth-subtitle {
    color: var(--gray-500);
    font-size: 14px;
    margin: 0 0 24px;
  }

  .btn-google {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 12px 16px;
    background: white;
    border: 1px solid var(--gray-300);
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
  }

  .btn-google:hover {
    background: var(--gray-50);
    border-color: var(--gray-400);
  }

  .btn-google:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  .auth-footer {
    margin-top: 24px;
    font-size: 14px;
    color: var(--gray-500);
  }

  .auth-link {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
  }

  .auth-link:hover {
    text-decoration: underline;
  }

  /* Generated Items Grid */
  .generated-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
  }

  .generated-item {
    background: white;
    border: 1px solid var(--gray-100);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--shadow-sm);
  }

  .generated-item:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(37, 99, 235, 0.1);
  }

  .generated-item img {
    width: 100%;
    height: 220px;
    object-fit: cover;
  }

  .generated-info {
    padding: 14px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
  }

  .generated-type {
    display: inline-block;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 6px;
    background: var(--gray-100);
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.03em;
  }

  .generated-type.exam {
    background: rgba(37, 99, 235, 0.1);
    color: var(--primary-hover);
  }

  .generated-type.quiz {
    background: rgba(16, 185, 129, 0.1);
    color: var(--secondary-hover);
  }

  .generated-date {
    display: block;
    font-size: 11px;
    color: var(--gray-400);
    margin-left: auto;
  }

  .generated-empty {
    text-align: center;
    padding: 60px 20px;
    color: var(--gray-500);
  }

  .generated-empty i {
    font-size: 48px;
    margin-bottom: 16px;
    color: var(--gray-300);
  }

  /* Pagination */
  .pagination {
    display: flex;
    justify-content: center;
    margin-top: 32px;
  }

  .pagination-inner {
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .pagination-btn {
    min-width: 40px;
    height: 40px;
    padding: 0 12px;
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    font-size: 14px;
    color: var(--gray-600);
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .pagination-btn:hover:not(:disabled) {
    background: var(--gray-50);
    border-color: var(--gray-300);
  }

  .pagination-btn.active {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
  }

  .pagination-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
  }

  .pagination-ellipsis {
    padding: 0 8px;
    color: var(--gray-400);
  }

  /* Account Page */
  .account-header {
    display: flex;
    align-items: center;
    gap: 24px;
    padding: 28px;
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(0,0,0,0.04);
    border-radius: 16px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
  }

  .account-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
  }

  .account-avatar-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--gray-100), var(--gray-200));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: var(--gray-400);
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
  }

  .account-name {
    font-size: 24px;
    font-weight: 400;
    margin: 0 0 4px;
  }

  .account-email {
    color: var(--gray-500);
    font-size: 14px;
    margin: 0;
  }

  .account-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
  }

  .stat-card {
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(0,0,0,0.04);
    border-radius: 16px;
    padding: 24px 20px;
    text-align: center;
    box-shadow: var(--shadow-sm);
    transition: all 0.2s;
  }

  .stat-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
  }

  .stat-value {
    font-size: 36px;
    font-weight: 400;
    color: var(--dark);
    margin-bottom: 4px;
  }

  .stat-label {
    font-size: 11px;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .stat-card.credit .stat-value {
    color: var(--primary);
  }

  /* Credit Card */
  .credit-card {
    background: linear-gradient(135deg, var(--primary), var(--primary-hover));
    border-radius: 16px;
    padding: 28px;
    margin-bottom: 28px;
    color: white;
  }

  .credit-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .credit-card-left {
    display: flex;
    align-items: center;
    gap: 16px;
  }

  .credit-card-icon {
    font-size: 36px;
    line-height: 1;
  }

  .credit-card-title {
    font-size: 14px;
    opacity: 0.85;
    margin-bottom: 4px;
  }

  .credit-card-balance {
    font-size: 36px;
    font-weight: 700;
    line-height: 1;
  }

  .credit-topup-btn {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    padding: 10px 20px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
  }

  .credit-topup-btn:hover {
    background: rgba(255,255,255,0.3);
  }

  /* Lightbox */
  .lightbox {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.95);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    flex-direction: column;
  }

  .lightbox.active {
    display: flex;
  }

  .lightbox-close {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: white;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 20px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .lightbox-close:hover {
    background: rgba(255, 255, 255, 0.2);
  }

  .lightbox-content {
    max-width: 90vw;
    max-height: 75vh;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: default;
  }

  .lightbox-content img {
    max-width: 100%;
    max-height: 75vh;
    object-fit: contain;
    transition: transform 0.2s;
    user-select: none;
    -webkit-user-drag: none;
  }

  .lightbox-toolbar {
    display: flex;
    gap: 12px;
    margin-top: 20px;
  }

  .lightbox-btn {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: white;
    width: 44px;
    height: 44px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 18px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
  }

  .lightbox-btn:hover {
    background: rgba(255, 255, 255, 0.2);
  }

  /* Animations */
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @keyframes shimmer {
    0% { background-position: -200px 0; }
    100% { background-position: calc(200px + 100%) 0; }
  }

  /* Utility */
  .hidden {
    display: none !important;
  }

  .gradient-text {
    background: linear-gradient(135deg, var(--dark) 20%, var(--primary) 93%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  /* 404 Page */
  .notfound-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 70vh;
    text-align: center;
    padding: 40px 20px;
  }

  .notfound-code {
    font-size: 120px;
    font-weight: 800;
    color: var(--primary);
    line-height: 1;
    margin-bottom: 8px;
    opacity: 0.15;
    user-select: none;
  }

  .notfound-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 12px;
  }

  .notfound-desc {
    font-size: 16px;
    color: var(--gray-500);
    max-width: 400px;
    margin: 0 0 28px;
    line-height: 1.6;
  }

  .notfound-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background: var(--primary);
    color: #fff;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s;
  }

  .notfound-btn:hover {
    background: var(--primary-hover);
  }

  .notfound-links {
    margin-top: 32px;
    font-size: 14px;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    justify-content: center;
  }

  .notfound-links a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
  }

  .notfound-links a:hover {
    text-decoration: underline;
  }

  @media (max-width: 480px) {
    .notfound-code { font-size: 80px; }
    .notfound-title { font-size: 22px; }
  }
</style>
