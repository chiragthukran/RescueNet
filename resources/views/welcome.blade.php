<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="RescueNet — Centralized rescue agency coordination platform for effective disaster management">
    <title>RescueNet — Disaster Management Coordination Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #fdfbf7; color: #3b2f2f; overflow-x: hidden; }
        a { text-decoration: none; color: inherit; }

        .nav { position: fixed; top: 0; left: 0; right: 0; z-index: 50; padding: 1.25rem 2rem; display: flex; align-items: center; justify-content: space-between; background: rgba(253,251,247,0.85); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(230,218,206,0.5); }
        .nav-brand { display: flex; align-items: center; gap: 0.75rem; }
        .nav-brand-icon { width: 40px; height: 40px; background: linear-gradient(135deg, #d97706, #b45309); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
        .nav-brand-text { font-size: 1.2rem; font-weight: 800; background: linear-gradient(135deg, #d97706, #b45309); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .nav-links { display: flex; align-items: center; gap: 1rem; }
        .nav-link { padding: 0.5rem 1.25rem; border-radius: 0.75rem; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; color: #5c4d4d; }
        .nav-link:hover { background: rgba(217,119,6,0.1); color: #d97706; }
        .nav-link-primary { background: linear-gradient(135deg, #d97706, #b45309); color: white; box-shadow: 0 4px 14px rgba(217,119,6,0.3); }
        .nav-link-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(217,119,6,0.4); background: linear-gradient(135deg, #d97706, #b45309); color: white; }

        .hero { min-height: 100vh; display: flex; align-items: center; justify-content: center; text-align: center; padding: 6rem 2rem 4rem; position: relative; }
        .hero::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 50% 30%, rgba(217,119,6,0.12) 0%, transparent 60%), radial-gradient(ellipse at 80% 70%, rgba(180,83,9,0.08) 0%, transparent 50%); }
        .hero-content { position: relative; max-width: 800px; }
        .hero-badge { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.4rem 1rem; background: rgba(217,119,6,0.1); border: 1px solid rgba(217,119,6,0.2); border-radius: 9999px; font-size: 0.8rem; color: #b45309; margin-bottom: 1.5rem; }
        .hero h1 { font-size: 3.5rem; font-weight: 900; line-height: 1.1; margin-bottom: 1.5rem; background: linear-gradient(135deg, #3b2f2f 0%, #5c4d4d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .hero h1 span { background: linear-gradient(135deg, #d97706, #b45309); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .hero p { font-size: 1.15rem; color: #5c4d4d; line-height: 1.7; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto; }
        .hero-buttons { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .btn-hero { padding: 0.85rem 2rem; border-radius: 0.75rem; font-weight: 700; font-size: 1rem; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.5rem; }
        .btn-hero-primary { background: linear-gradient(135deg, #d97706, #b45309); color: white; box-shadow: 0 4px 20px rgba(217,119,6,0.3); }
        .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(217,119,6,0.4); }
        .btn-hero-secondary { background: rgba(255,255,255,0.8); color: #3b2f2f; border: 1px solid #e6dace; }
        .btn-hero-secondary:hover { background: rgba(243,237,228,0.8); }

        .features { padding: 5rem 2rem; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto; }
        .feature-card { padding: 2rem; background: rgba(255,255,255,0.8); border: 1px solid #e6dace; border-radius: 1rem; transition: transform 0.2s, box-shadow 0.2s; }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 8px 32px rgba(138,123,123,0.15); }
        .feature-icon { width: 56px; height: 56px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1rem; }
        .feature-card h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.5rem; color: #3b2f2f; }
        .feature-card p { font-size: 0.85rem; color: #5c4d4d; line-height: 1.6; }

        .stats { padding: 3rem 2rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; max-width: 900px; margin: 0 auto; text-align: center; }
        .stat-num { font-size: 2.5rem; font-weight: 900; background: linear-gradient(135deg, #d97706, #b45309); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .stat-desc { font-size: 0.8rem; color: #8a7b7b; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 0.25rem; }

        .footer { padding: 2rem; text-align: center; border-top: 1px solid #e6dace; color: #8a7b7b; font-size: 0.8rem; }

        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .float { animation: float 6s ease-in-out infinite; }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2.25rem; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    <nav class="nav">
        <div class="nav-brand">
            <div class="nav-brand-icon">🛡️</div>
            <span class="nav-brand-text">RescueNet</span>
        </div>
        <div class="nav-links">
            @auth
                <a href="{{ route('dashboard') }}" class="nav-link nav-link-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="nav-link">Sign In</a>
                <a href="{{ route('register') }}" class="nav-link nav-link-primary">Get Started</a>
            @endauth
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge float">🚨 Emergency Coordination Platform</div>
            <h1>Coordinate Rescue Operations in <span>Real-Time</span></h1>
            <p>Centralize rescue agency coordination during disasters. Share locations, track resources, send alerts, and collaborate on an interactive map — all in one platform.</p>
            <div class="hero-buttons">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-hero btn-hero-primary">🚀 Go to Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">🚀 Register Your Agency</a>
                    <a href="{{ route('login') }}" class="btn-hero btn-hero-secondary">Sign In →</a>
                @endauth
            </div>
        </div>
    </section>

    <section class="features">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="background:rgba(217,119,6,0.15); color:#d97706;">🗺️</div>
                <h3>Interactive Live Map</h3>
                <p>Track agency locations and active disaster zones on a real-time interactive map with severity indicators.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:rgba(239,68,68,0.15); color:#ef4444;">🔔</div>
                <h3>Emergency Alerts</h3>
                <p>Broadcast critical alerts to all agencies or target specific teams with priority-based notifications.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:rgba(16,185,129,0.15); color:#10b981;">📦</div>
                <h3>Resource Tracking</h3>
                <p>Track personnel, vehicles, equipment, and supplies in real-time to optimize resource allocation.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:rgba(180,83,9,0.15); color:#b45309;">💬</div>
                <h3>Secure Messaging</h3>
                <p>Direct agency-to-agency communication for coordinated disaster response and information sharing.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:rgba(245,158,11,0.15); color:#f59e0b;">⚠️</div>
                <h3>Calamity Reports</h3>
                <p>Report and track natural and man-made disasters with severity levels and real-time status updates.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:rgba(217,119,6,0.15); color:#d97706;">📍</div>
                <h3>Location Sharing</h3>
                <p>Share real-time GPS coordinates so teams can find each other and reach disaster zones faster.</p>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="stats-grid">
            <div><div class="stat-num">24/7</div><div class="stat-desc">Always On</div></div>
            <div><div class="stat-num">100%</div><div class="stat-desc">Free & Open</div></div>
            <div><div class="stat-num">Real-time</div><div class="stat-desc">Live Tracking</div></div>
            <div><div class="stat-num">Secure</div><div class="stat-desc">Communication</div></div>
        </div>
    </section>

    <footer class="footer">
        <p>© {{ date('Y') }} RescueNet — Built for saving lives. Disaster coordination made simple.</p>
    </footer>
</body>
</html>
