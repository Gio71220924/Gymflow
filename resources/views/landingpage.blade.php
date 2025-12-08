@php
  use Carbon\Carbon;
  $brandName = $appSettings['branding_name'] ?? 'GymFlow';
  $brandColor = $appSettings['branding_color'] ?? '#FC7753';
  $tagline = $appSettings['branding_tagline'] ?? 'Kelola gym, jadwal, dan pembayaran dengan satu dashboard.';
  $basicPrice = isset($appSettings['billing_basic_price']) ? number_format($appSettings['billing_basic_price'], 0, ',', '.') : '150.000';
  $premiumPrice = isset($appSettings['billing_premium_price']) ? number_format($appSettings['billing_premium_price'], 0, ',', '.') : '300.000';
  $liveClasses = collect($todayClasses ?? []);
@endphp
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $brandName }} | Landing Member</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Sora:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    :root{
      --brand: {{ $brandColor }};
      --ink: #0f172a;
      --muted: #4c5567;
      --soft: #f6f7fb;
      --card: #ffffff;
      --line: #e4e7ef;
      --shadow-lg: 0 30px 80px rgba(15,23,42,0.12);
      --shadow-md: 0 16px 50px rgba(15,23,42,0.08);
      --shadow-sm: 0 10px 28px rgba(15,23,42,0.07);
      --radius: 14px;
    }
    * { box-sizing: border-box; }
    html {
      scroll-behavior: smooth;
      overflow-x: hidden;
    }
    body {
      margin: 0;
      background: linear-gradient(180deg, #f8f9fb 0%, #f3f4f6 100%);
      color: var(--ink);
      font-family: 'Sora', sans-serif;
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
      width: 100%;
    }
    a { color: inherit; text-decoration: none; }
    h1, h2, h3, h4 { font-family: 'Space Grotesk', 'Sora', sans-serif; letter-spacing: -0.02em; margin: 0; }
    p { margin: 0; color: var(--muted); }
    .page { position: relative; min-height: 100vh; overflow-x: hidden; }
    .backdrop {
      position: fixed;
      z-index: -1;
      filter: blur(110px);
      opacity: 0.75;
      border-radius: 50%;
    }
    .gradient-1 { width: 360px; height: 360px; top: -120px; left: -60px; background: radial-gradient(circle, rgba(252,119,83,0.35) 0%, rgba(255,255,255,0) 65%); }
    .gradient-2 { width: 320px; height: 320px; bottom: 10%; right: 5%; background: radial-gradient(circle, rgba(15,23,42,0.18) 0%, rgba(255,255,255,0) 60%); }
    .gradient-3 { width: 280px; height: 280px; bottom: 30%; left: 10%; background: radial-gradient(circle, rgba(255,188,143,0.25) 0%, rgba(255,255,255,0) 65%); }
    .container { 
      width: min(1160px, 92vw); 
      margin: 0 auto; 
      max-width: 100%;
      padding-left: max(20px, env(safe-area-inset-left));
      padding-right: max(20px, env(safe-area-inset-right));
    }
    .topbar {
      position: sticky;
      top: 0;
      z-index: 20;
      padding: 18px 4vw;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      background: rgba(255,255,255,0.78);
      border-bottom: 1px solid rgba(228,231,239,0.6);
      backdrop-filter: blur(14px);
      transition: box-shadow 0.2s ease, background 0.2s ease;
    }
    .topbar.scrolled { box-shadow: var(--shadow-sm); background: rgba(255,255,255,0.92); }
    .brand { display: inline-flex; align-items: center; gap: 12px; }
    .brand .mark {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      background: linear-gradient(135deg, var(--brand), #ffb686);
      display: grid;
      place-items: center;
      color: #0f172a;
      font-weight: 700;
      font-size: 18px;
      box-shadow: 0 12px 25px rgba(252,119,83,0.35);
    }
    .brand-name { font-weight: 700; font-size: 1.05rem; }
    .brand-tagline { font-size: 0.85rem; color: var(--muted); margin-top: -2px; }
    .nav-links { display: flex; align-items: center; gap: 16px; font-size: 0.95rem; flex-wrap: wrap; }
    .nav-links a { padding: 10px 12px; border-radius: 12px; transition: color 0.2s ease, background 0.2s ease; }
    .nav-links a:hover { color: var(--brand); background: rgba(252,119,83,0.08); }
    .pill {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 12px;
      border-radius: 999px;
      background: rgba(252,119,83,0.12);
      color: var(--ink);
      font-weight: 600;
      font-size: 0.9rem;
    }
    .pill::before {
      content: '';
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: var(--brand);
      box-shadow: 0 0 0 6px rgba(252,119,83,0.16);
    }
    .pill.soft { background: rgba(15,23,42,0.05); color: var(--ink); }
    .pill.mini { padding: 6px 10px; font-size: 0.8rem; box-shadow: none; }
    .pill.mini::before { width: 8px; height: 8px; box-shadow: none; }
    .pill.success { background: rgba(40,199,111,0.16); color: #126f3f; }
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 12px 16px;
      border-radius: 14px;
      font-weight: 600;
      font-size: 0.95rem;
      border: 1px solid var(--line);
      background: #fff;
      transition: all 0.2s ease;
      cursor: pointer;
      text-decoration: none;
    }
    .btn.primary {
      background: linear-gradient(120deg, var(--brand), #ffb686);
      color: #0f172a;
      border-color: transparent;
      box-shadow: 0 15px 30px rgba(252,119,83,0.35);
    }
    .btn.primary:hover { transform: translateY(-1px); box-shadow: 0 18px 35px rgba(252,119,83,0.4); }
    .btn.soft { background: rgba(15,23,42,0.05); }
    .btn.soft:hover { border-color: rgba(15,23,42,0.12); }
    .btn.ghost {
      background: rgba(15,23,42,0.05);
      border-color: rgba(15,23,42,0.07);
    }
    .btn.join-btn {
      padding: 10px 14px;
      background: #0f172a;
      color: #fff;
      border-color: #0f172a;
    }
    .btn.join-btn:hover { background: var(--brand); color: #0f172a; box-shadow: 0 12px 28px rgba(252,119,83,0.28); }
    .btn.join-btn.full { width: 100%; justify-content: center; }
    .hero { padding: 70px 0 40px; }
    .hero-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 32px;
      align-items: center;
    }
    .hero-copy h1 { font-size: clamp(2.4rem, 4vw, 3.2rem); line-height: 1.1; margin: 18px 0 12px; }
    .hero-copy .lead { font-size: 1.05rem; max-width: 600px; }
    .cta { display: flex; gap: 12px; align-items: center; margin: 22px 0; flex-wrap: wrap; }
    .hero-points { display: grid; gap: 10px; margin-top: 12px; }
    .hero-points .point { display: inline-flex; gap: 10px; align-items: center; color: var(--muted); font-weight: 500; }
    .hero-points i { color: var(--brand); }
    .hero-visual { position: relative; }
    .panel {
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: var(--radius);
      box-shadow: var(--shadow-lg);
      padding: 20px;
      position: relative;
      overflow: hidden;
    }
    .panel + .panel { margin-top: 16px; }
    .panel-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 14px;
    }
    .panel-title { font-weight: 700; color: var(--ink); }
    .eyebrow { text-transform: uppercase; letter-spacing: 0.08em; font-weight: 700; font-size: 0.75rem; color: #6b7285; margin-bottom: 6px; }
    .schedule { display: grid; gap: 12px; }
    .schedule-row {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 12px;
      padding: 12px 14px;
      border: 1px solid var(--line);
      border-radius: 12px;
      background: #fff;
      box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
      flex-wrap: wrap;
    }
    .schedule-row .time { font-weight: 700; color: var(--ink); min-width: 50px; }
    .schedule-row .title { font-weight: 600; word-break: break-word; }
    .schedule-row .meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; justify-content: flex-end; text-align: right; }
    .badge {
      display: inline-flex;
      align-items: center;
      padding: 6px 9px;
      border-radius: 10px;
      font-weight: 600;
      font-size: 0.85rem;
      background: rgba(252,119,83,0.12);
      color: #c14f2d;
    }
    .badge.success { background: rgba(40,199,111,0.16); color: #126f3f; }
    .badge.info { background: rgba(15,23,42,0.08); color: #0f172a; }
    .badge.danger { background: rgba(239,68,68,0.14); color: #b42318; }
    .badge.warning { background: rgba(252,201,52,0.18); color: #9a6400; }
    .muted { color: #7b8495; font-weight: 600; }
    .panel-stats { background: linear-gradient(145deg, #0f172a 0%, #111827 35%, #121c2d 100%); color: #e5ecf5; border: none; box-shadow: 0 25px 60px rgba(12,18,32,0.4); }
    .panel-stats .panel-title { color: #fff; }
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 14px; }
    .stat { padding: 12px 14px; background: rgba(255,255,255,0.06); border-radius: 12px; }
    .stat .label { color: #cbd5e1; font-size: 0.9rem; }
    .stat .value { font-weight: 700; font-size: 1.4rem; color: #fff; }
    .progress { height: 10px; background: rgba(255,255,255,0.08); border-radius: 999px; margin: 12px 0; overflow: hidden; }
    .progress .bar { height: 100%; background: linear-gradient(90deg, var(--brand), #ffb686); }
    .panel-foot { display: flex; align-items: center; justify-content: space-between; font-size: 0.9rem; color: #dfe6f0; }
    .floating-note {
      position: absolute;
      top: -12px;
      right: 18px;
      background: #fff;
      border: 1px solid var(--line);
      border-radius: 12px;
      padding: 10px 12px;
      box-shadow: var(--shadow-md);
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-weight: 600;
      color: var(--ink);
      animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
      0% { transform: translateY(0); }
      50% { transform: translateY(6px); }
      100% { transform: translateY(0); }
    }
    .stat-strip {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: 16px;
      padding: 18px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 14px;
      align-items: center;
      box-shadow: var(--shadow-sm);
      margin: 20px auto 10px;
    }
    .stat-chip {
      padding: 12px 14px;
      border-radius: 12px;
      background: var(--soft);
      border: 1px dashed rgba(15,23,42,0.08);
    }
    .stat-chip .label { color: #6b7285; font-weight: 600; font-size: 0.9rem; }
    .stat-chip .value { font-weight: 700; font-size: 1.2rem; color: var(--ink); }
    .section { padding: 60px 0; }
    .section-head { margin-bottom: 24px; }
    .section-head h2 { font-size: clamp(1.8rem, 3vw, 2.4rem); margin-top: 6px; }
    .feature-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 16px;
    }
    .feature-card {
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: var(--radius);
      padding: 18px;
      box-shadow: var(--shadow-sm);
      position: relative;
      overflow: hidden;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .feature-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
    .feature-icon {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      background: rgba(252,119,83,0.12);
      color: var(--brand);
      display: grid;
      place-items: center;
      font-size: 1.2rem;
      margin-bottom: 12px;
    }
    .feature-card h3 { margin: 6px 0 8px; font-size: 1.1rem; }
    .feature-card p { font-size: 0.96rem; }
    .feature-highlight {
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at 20% 20%, rgba(252,119,83,0.1), transparent 35%);
      pointer-events: none;
    }
    .flow-steps {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 16px;
      margin-top: 18px;
    }
    .flow-step {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: var(--radius);
      padding: 16px;
      box-shadow: var(--shadow-sm);
      position: relative;
    }
    .step-num {
      width: 34px;
      height: 34px;
      border-radius: 10px;
      background: rgba(15,23,42,0.06);
      display: grid;
      place-items: center;
      font-weight: 700;
      color: var(--ink);
      margin-bottom: 10px;
    }
    .class-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 16px;
      margin-top: 12px;
    }
    .class-card {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: var(--radius);
      padding: 16px;
      box-shadow: var(--shadow-sm);
      display: grid;
      gap: 8px;
    }
    .class-meta { display: flex; justify-content: space-between; align-items: center; color: var(--muted); font-size: 0.95rem; flex-wrap: wrap; gap: 8px; }
    .class-cap { display: inline-flex; gap: 6px; align-items: center; white-space: nowrap; }
    .class-cap i { color: var(--brand); }
    .facility-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 14px;
      margin-top: 14px;
    }
    .facility-card {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: var(--radius);
      padding: 16px;
      box-shadow: var(--shadow-sm);
      display: grid;
      gap: 8px;
    }
    .pricing-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 18px;
      margin-top: 14px;
    }
    .pricing-card {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: var(--radius);
      padding: 20px;
      box-shadow: var(--shadow-sm);
      display: grid;
      gap: 10px;
    }
    .pricing-card.highlight {
      background: linear-gradient(140deg, #0f172a, #111827 50%, #131f34 100%);
      color: #fff;
      border: none;
      box-shadow: 0 24px 60px rgba(12,18,32,0.5);
    }
    .pricing-card .pill { width: fit-content; }
    .pricing-card.highlight .pill { background: rgba(255,255,255,0.12); color: #fff; }
    .price { font-size: 2rem; font-weight: 700; display: flex; gap: 6px; align-items: baseline; }
    .price span { font-size: 0.95rem; color: var(--muted); }
    .pricing-card.highlight .price span { color: #d4d9e4; }
    .pricing-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 8px; }
    .pricing-list li { display: flex; gap: 8px; align-items: center; color: var(--muted); }
    .pricing-card.highlight .pricing-list li { color: #d4d9e4; }
    .pricing-list i { color: var(--brand); }
    .pricing-card.highlight i { color: #86efac; }
    .cta-card {
      background: linear-gradient(120deg, var(--brand), #0f172a);
      color: #fff;
      padding: 32px;
      border-radius: 18px;
      border: 1px solid rgba(255,255,255,0.18);
      box-shadow: var(--shadow-lg);
      display: grid;
      grid-template-columns: 1fr auto;
      gap: 16px;
      align-items: center;
    }
    .cta-card p { color: rgba(255,255,255,0.82); }
    .cta-actions { display: flex; gap: 12px; flex-wrap: wrap; justify-content: flex-end; }
    .cta-card .btn { border-color: rgba(255,255,255,0.22); color: #0f172a; }
    .cta-card .btn.primary { color: #0f172a; }
    .footer {
      padding: 26px 0 40px;
      color: var(--muted);
      border-top: 1px solid var(--line);
      margin-top: 40px;
    }
    .footer-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 16px;
      align-items: center;
    }
    .footer-links { display: flex; gap: 16px; flex-wrap: wrap; justify-content: flex-end; }
    .footer-links a { padding: 6px 0; color: var(--muted); }
    @media (max-width: 1024px) and (min-width: 781px) {
      .hero-copy h1 { font-size: 2.6rem; }
      .section-head h2 { font-size: 2rem; }
      .pricing-grid { grid-template-columns: repeat(2, 1fr); }
    }
    
    @media (max-width: 780px) {
      .topbar { 
        position: static; 
        border-radius: 0 0 18px 18px; 
        flex-direction: column;
        align-items: flex-start;
        padding: 16px 20px;
      }
      .brand { margin-bottom: 12px; }
      .nav-links { 
        width: 100%;
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
      }
      .nav-links a { 
        width: 100%;
        text-align: center;
      }
      .hero { padding-top: 30px; }
      .hero-grid { grid-template-columns: 1fr; }
      .hero-copy h1 { font-size: 2rem; }
      .hero-copy .lead { font-size: 1rem; }
      .cta { flex-direction: column; }
      .cta .btn { width: 100%; justify-content: center; }
      .cta-card { 
        grid-template-columns: 1fr; 
        text-align: left;
        padding: 24px 20px;
      }
      .cta-actions { 
        justify-content: stretch; 
        flex-direction: column;
      }
      .cta-actions .btn { 
        width: 100%;
        justify-content: center;
      }
      .floating-note { 
        position: relative; 
        top: auto; 
        right: auto; 
        margin-top: 10px; 
      }
      .feature-grid { grid-template-columns: 1fr; }
      .class-grid { grid-template-columns: 1fr; }
      .facility-grid { grid-template-columns: 1fr; }
      .pricing-grid { grid-template-columns: 1fr; }
      .stat-strip { 
        grid-template-columns: repeat(2, 1fr);
        padding: 16px;
      }
      .footer-grid { 
        grid-template-columns: 1fr;
        text-align: center;
      }
      .footer-links { 
        justify-content: center;
        flex-direction: column;
        gap: 8px;
      }
    }
    
    @media (max-width: 480px) {
      .container { width: 95vw; }
      .hero-copy h1 { font-size: 1.75rem; }
      .section-head h2 { font-size: 1.5rem; }
      .stat-strip { grid-template-columns: 1fr; }
      .brand-name { font-size: 0.95rem; }
      .brand .mark { width: 38px; height: 38px; font-size: 16px; }
      .price { font-size: 1.75rem; }
    }
  </style>
</head>
<body>
  <div class="backdrop gradient-1"></div>
  <div class="backdrop gradient-2"></div>
  <div class="backdrop gradient-3"></div>

  <div class="page">
    <header class="topbar" id="topbar">
      <div class="brand">
        <div class="mark">{{ strtoupper(substr($brandName, 0, 1)) }}</div>
        <div>
          <div class="brand-name">{{ $brandName }}</div>
          <div class="brand-tagline">Latihan nyaman, progres nyata</div>
        </div>
      </div>
      <nav class="nav-links">
        <a href="#classes">Kelas</a>
        <a href="#features">Keunggulan</a>
        <a href="#facilities">Fasilitas</a>
        <a href="#pricing">Membership</a>
        <a class="btn ghost" href="{{ route('login') }}">Masuk / Daftar</a>
      </nav>
    </header>

    <main>
      <section class="hero" id="home">
        <div class="container hero-grid">
          <div class="hero-copy">
            <span class="pill">Rasakan gym yang rapi, kelas terjadwal, pembayaran cashless</span>
            <h1>Latihan lebih terarah di {{ $brandName }}.</h1>
            <p class="lead">{{ $tagline }} Member tinggal booking kelas, check-in digital, dan bayar tanpa antre.</p>
            <div class="cta">
              <a class="btn primary" href="{{ route('login') }}">Masuk / Daftar</a>
              <a class="btn soft" href="#classes">Lihat jadwal kelas</a>
            </div>
            <div class="hero-points">
              <div class="point"><i class="bi bi-check2-circle"></i>Check-in cepat lewat ponsel atau barcode.</div>
              <div class="point"><i class="bi bi-check2-circle"></i>Kelas group & private dengan slot real-time.</div>
              <div class="point"><i class="bi bi-check2-circle"></i>Pembayaran aman, bukti otomatis terkirim.</div>
            </div>
          </div>

          <div class="hero-visual">
            <div class="panel">
              <div class="panel-head">
                <div>
                  <div class="eyebrow">Jadwal hari ini</div>
                  <div class="panel-title">{{ $liveClasses->isEmpty() ? 'Jadwal terbaru' : 'Tinggal pilih slot' }}</div>
                </div>
                <span class="pill mini success">Live</span>
              </div>
              <div class="schedule">
                @forelse($liveClasses->take(4) as $class)
                  @php
                    $start = Carbon::parse($class->start_at);
                    $end   = $class->end_at ? Carbon::parse($class->end_at) : null;
                    $booked = (int) ($class->booked_count ?? 0);
                    $capacity = max(0, (int) ($class->capacity ?? 0));
                    $slotsLeft = max($capacity - $booked, 0);
                    $statusKey = strtolower($class->status ?? '');
                    $isCancelled = $statusKey === 'cancelled';
                    $isDone = $statusKey === 'done';
                    $isLive = !$isCancelled && !$isDone && $end && $start->isPast() && $end->isFuture();
                    $isFull = $capacity > 0 && $booked >= $capacity;
                    $badgeClass = $isCancelled ? 'danger' : ($isLive ? 'info' : ($isFull ? 'warning' : 'success'));
                    $badgeLabel = $isCancelled ? 'Dibatalkan' : ($isLive ? 'Berlangsung' : ($isDone ? 'Selesai' : ($isFull ? 'Penuh' : 'Tersedia')));
                  @endphp
                  <div class="schedule-row">
                    <div>
                      <div class="time">{{ $start->format('H:i') }}</div>
                      <div class="title">{{ $class->title }}</div>
                      <div class="muted">{{ $class->location ?? 'Studio utama' }}</div>
                    </div>
                    <div class="meta">
                      <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                      @if($capacity > 0)
                        <span class="muted">{{ $booked }} / {{ $capacity }} slot</span>
                        @if(!$isFull && !$isCancelled && !$isDone)
                          <span class="pill mini soft">Sisa {{ $slotsLeft }}</span>
                        @endif
                      @else
                        <span class="muted">{{ $booked }} peserta</span>
                      @endif
                      <a class="btn join-btn" href="{{ route('login') }}">Bergabung</a>
                    </div>
                  </div>
                @empty
                  <div class="schedule-row">
                    <div>
                      <div class="title">Belum ada jadwal hari ini.</div>
                      <div class="muted">Jadwal akan diperbarui otomatis begitu kelas baru dibuat.</div>
                    </div>
                    <a class="btn join-btn" href="{{ route('login') }}">Masuk</a>
                  </div>
                @endforelse
              </div>
            </div>

            <div class="panel panel-stats">
              <div class="panel-head">
                <div>
                  <div class="eyebrow" style="color:#9ba8bd;">Progres kamu</div>
                  <div class="panel-title">Bulan ini</div>
                </div>
                <i class="bi bi-fire" aria-hidden="true"></i>
              </div>
              <div class="stat-grid">
                <div class="stat">
                  <div class="label">Check-in</div>
                  <div class="value">12x</div>
                </div>
                <div class="stat">
                  <div class="label">Kelas selesai</div>
                  <div class="value">7 sesi</div>
                </div>
                <div class="stat">
                  <div class="label">Kalori terbakar</div>
                  <div class="value">9.2k</div>
                </div>
              </div>
              <div class="progress"><div class="bar" style="width:72%;"></div></div>
              <div class="panel-foot">
                <span>72% target mingguan</span>
                <span>Pengingat aktif</span>
              </div>
            </div>

            <div class="floating-note">
              <i class="bi bi-broadcast-pin"></i>
              Notifikasi sebelum kelas dimulai.
            </div>
          </div>
        </div>
      </section>

      <section class="section">
        <div class="container stat-strip">
          <div class="stat-chip">
            <div class="label">Member aktif</div>
            <div class="value">120+</div>
          </div>
          <div class="stat-chip">
            <div class="label">Tingkat hadir</div>
            <div class="value">86%</div>
          </div>
          <div class="stat-chip">
            <div class="label">Kelas per minggu</div>
            <div class="value">30+</div>
          </div>
          <div class="stat-chip">
            <div class="label">Durasi check-in</div>
            <div class="value">&lt; 20 dtk</div>
          </div>
        </div>
      </section>

      <section class="section" id="features">
        <div class="container">
          <div class="section-head">
            <p class="eyebrow">Keunggulan</p>
            <h2>Semua yang member butuhkan ada di satu app</h2>
            <p>Booking kelas, cek progres, dan bayar tagihan tanpa antre atau chat panjang.</p>
          </div>
          <div class="feature-grid">
            <article class="feature-card">
              <div class="feature-highlight"></div>
              <div class="feature-icon"><i class="bi bi-qr-code-scan"></i></div>
              <h3>Check-in digital</h3>
              <p>Pakai barcode atau QR, masuk lebih cepat, catatan kehadiran otomatis tersimpan.</p>
            </article>
            <article class="feature-card">
              <div class="feature-highlight"></div>
              <div class="feature-icon"><i class="bi bi-calendar2-week"></i></div>
              <h3>Slot kelas real-time</h3>
              <p>Lihat kursi tersisa, pilih coach, dan dapatkan pengingat sebelum kelas mulai.</p>
            </article>
            <article class="feature-card">
              <div class="feature-highlight"></div>
              <div class="feature-icon"><i class="bi bi-heart-pulse"></i></div>
              <h3>Pantau progres</h3>
              <p>Track frekuensi latihan, sesi selesai, dan target pribadi tiap minggu.</p>
            </article>
            <article class="feature-card">
              <div class="feature-highlight"></div>
              <div class="feature-icon"><i class="bi bi-credit-card"></i></div>
              <h3>Pembayaran cashless</h3>
              <p>Invoice digital, bukti bayar otomatis, dukung transfer atau e-wallet.</p>
            </article>
          </div>
        </div>
      </section>

      <section class="section" id="classes">
        <div class="container">
          <div class="section-head">
            <p class="eyebrow">Pilihan kelas</p>
            <h2>Kelas favorit untuk semua level</h2>
            <p>Jadwal berganti setiap minggu. Pilih sesi pagi, siang, atau malam sesuai waktu kamu.</p>
          </div>
          <div class="class-grid">
            @forelse($liveClasses as $class)
              @php
                $start = Carbon::parse($class->start_at);
                $end   = $class->end_at ? Carbon::parse($class->end_at) : null;
                $booked = (int) ($class->booked_count ?? 0);
                $capacity = max(0, (int) ($class->capacity ?? 0));
                $slotsLeft = max($capacity - $booked, 0);
                $statusKey = strtolower($class->status ?? '');
                $isFull = $capacity > 0 && $booked >= $capacity;
                $statusLabel = $statusKey === 'cancelled' ? 'Dibatalkan' : ($statusKey === 'done' ? 'Selesai' : 'Terjadwal');
              @endphp
              <div class="class-card">
                <div class="class-meta">
                  <span class="pill mini soft">{{ $start->format('d M') }}</span>
                  <span class="class-cap"><i class="bi bi-people"></i>{{ $capacity > 0 ? $capacity . ' kursi' : 'Tanpa batas' }}</span>
                </div>
                <h3>{{ $class->title }}</h3>
                <p>{{ $class->location ?? 'Studio utama' }}</p>
                <div class="class-meta">
                  <span><i class="bi bi-clock"></i> {{ $start->format('H:i') }}{{ $end ? ' - ' . $end->format('H:i') : '' }}</span>
                  <span><i class="bi bi-geo-alt"></i> {{ $class->location ?? 'Lokasi menyusul' }}</span>
                </div>
                <div class="class-meta">
                  @if($capacity > 0)
                    <span class="badge {{ $isFull ? 'danger' : 'success' }}">{{ $isFull ? 'Penuh' : 'Sisa ' . $slotsLeft . ' slot' }}</span>
                  @else
                    <span class="badge info">Slot fleksibel</span>
                  @endif
                  <span class="muted">{{ $booked }} terdaftar - {{ $statusLabel }}</span>
                </div>
                <a class="btn join-btn full" href="{{ route('login') }}">Bergabung</a>
              </div>
            @empty
              <div class="class-card">
                <h3>Belum ada jadwal aktif</h3>
                <p>Jadwal akan muncul otomatis saat admin membuat kelas baru.</p>
                <a class="btn join-btn full" href="{{ route('login') }}">Masuk untuk update</a>
              </div>
            @endforelse
          </div>
        </div>
      </section>

      <section class="section" id="facilities">
        <div class="container">
          <div class="section-head">
            <p class="eyebrow">Fasilitas</p>
            <h2>Ruang latihan yang siap dipakai</h2>
            <p>Peralatan diperbarui rutin, area bersih, dan staf siap membantu.</p>
          </div>
          <div class="facility-grid">
            <div class="facility-card">
              <strong>Free weights lengkap</strong>
              <p>Dumbbell, barbell, rack, dan platform untuk semua program.</p>
            </div>
            <div class="facility-card">
              <strong>Studio kelas nyaman</strong>
              <p>AC, pencahayaan hangat, dan sound system untuk sesi group.</p>
            </div>
            <div class="facility-card">
              <strong>Locker & shower</strong>
              <p>Ruangan bersih dengan loker aman serta air panas.</p>
            </div>
            <div class="facility-card">
              <strong>Area recovery</strong>
              <p>Foam roller, mat, dan alat mobilitas untuk pendinginan.</p>
            </div>
          </div>
        </div>
      </section>

      <section class="section" id="pricing">
        <div class="container">
          <div class="section-head">
            <p class="eyebrow">Membership</p>
            <h2>Pilih ritme yang pas buatmu</h2>
            <p>Mulai dari akses dasar sampai premium dengan pengingat otomatis.</p>
          </div>
          <div class="pricing-grid">
            <div class="pricing-card">
              <span class="pill mini soft">Basic</span>
              <div class="price">Rp {{ $basicPrice }} <span>/bulan</span></div>
              <p>Akses penuh gym dan kelas populer untuk menjaga konsistensi latihan.</p>
              <ul class="pricing-list">
                <li><i class="bi bi-check2-circle"></i>Akses gym & kelas reguler</li>
                <li><i class="bi bi-check2-circle"></i>Check-in digital</li>
                <li><i class="bi bi-check2-circle"></i>Invoice & bukti bayar</li>
                <li><i class="bi bi-check2-circle"></i>Branding {{ $brandName }}</li>
              </ul>
              <a class="btn soft" href="{{ route('login') }}">Pilih Basic</a>
            </div>
            <div class="pricing-card highlight">
              <span class="pill mini">Premium</span>
              <div class="price">Rp {{ $premiumPrice }} <span>/bulan</span></div>
              <p>Semua fasilitas, kelas premium, reminder otomatis, dan prioritas booking.</p>
              <ul class="pricing-list">
                <li><i class="bi bi-check2-circle"></i>Kelas lanjutan & kapasitas prioritas</li>
                <li><i class="bi bi-check2-circle"></i>Reminder pembayaran otomatis</li>
                <li><i class="bi bi-check2-circle"></i>Pelacakan progres detail</li>
                <li><i class="bi bi-check2-circle"></i>Dukungan cepat dari tim</li>
              </ul>
              <a class="btn primary" href="{{ route('login') }}">Pilih Premium</a>
            </div>
          </div>
        </div>
      </section>

      <section class="section">
        <div class="container">
          <div class="cta-card">
            <div>
              <p class="eyebrow" style="color: rgba(255,255,255,0.78);">Mulai hari ini</p>
              <h2>Booking kelas pertama kamu di {{ $brandName }}.</h2>
              <p>Masuk atau daftar, pilih jadwal, dan terima pengingat otomatis sebelum kelas.</p>
            </div>
            <div class="cta-actions">
              <a class="btn primary" href="{{ route('login') }}">Masuk / Daftar</a>
              <a class="btn" href="#classes">Lihat kelas</a>
            </div>
          </div>
        </div>
      </section>
    </main>

    <footer class="footer">
      <div class="container footer-grid">
        <div>
          <div class="brand-name">{{ $brandName }}</div>
          <p>Tempat latihan yang rapi, modern, dan siap menemani progresmu.</p>
        </div>
        <div class="footer-links">
          <a href="#classes">Kelas</a>
          <a href="#pricing">Membership</a>
          <a href="{{ route('login') }}">Masuk / Daftar</a>
        </div>
      </div>
    </footer>
  </div>

  <script>
    (function () {
      const topbar = document.getElementById('topbar');
      function handleScroll() {
        if (!topbar) return;
        if (window.scrollY > 20) topbar.classList.add('scrolled');
        else topbar.classList.remove('scrolled');
      }
      handleScroll();
      window.addEventListener('scroll', handleScroll);

      document.querySelectorAll('a[href^="#"]').forEach(function (link) {
        link.addEventListener('click', function (e) {
          const targetId = this.getAttribute('href');
          const target = document.querySelector(targetId);
          if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        });
      });
    })();
  </script>
</body>
</html>
