@php
  $brandName = $appSettings['branding_name'] ?? 'GymFlow';
  $brandColor = $appSettings['branding_color'] ?? '#FC7753';
  $tagline = $appSettings['branding_tagline'] ?? 'Kelola gym, jadwal, dan pembayaran dengan satu dashboard.';
  $basicPrice = isset($appSettings['billing_basic_price']) ? number_format($appSettings['billing_basic_price'], 0, ',', '.') : '150.000';
  $premiumPrice = isset($appSettings['billing_premium_price']) ? number_format($appSettings['billing_premium_price'], 0, ',', '.') : '300.000';
@endphp
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $brandName }} | Platform Gym Minimalis</title>
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
    body {
      margin: 0;
      background: linear-gradient(180deg, #f8f9fb 0%, #f3f4f6 100%);
      color: var(--ink);
      font-family: 'Sora', sans-serif;
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
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
    .container { width: min(1160px, 92vw); margin: 0 auto; }
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
      gap: 8px;
      padding: 12px 16px;
      border-radius: 14px;
      font-weight: 600;
      font-size: 0.95rem;
      border: 1px solid var(--line);
      background: #fff;
      transition: all 0.2s ease;
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
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 12px 14px;
      border: 1px solid var(--line);
      border-radius: 12px;
      background: #fff;
      box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
    }
    .schedule-row .time { font-weight: 700; color: var(--ink); }
    .schedule-row .title { font-weight: 600; }
    .schedule-row .meta { display: flex; align-items: center; gap: 8px; }
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
    @media (max-width: 780px) {
      .topbar { position: static; border-radius: 0 0 18px 18px; }
      .hero { padding-top: 40px; }
      .hero-grid { grid-template-columns: 1fr; }
      .cta-card { grid-template-columns: 1fr; text-align: left; }
      .floating-note { position: relative; top: auto; right: auto; margin-top: 10px; }
      .nav-links { justify-content: flex-end; }
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
          <div class="brand-tagline">Fitness operating system</div>
        </div>
      </div>
      <nav class="nav-links">
        <a href="#features">Fitur</a>
        <a href="#workflow">Alur</a>
        <a href="#pricing">Paket</a>
        <a class="btn ghost" href="{{ route('login') }}">Masuk</a>
      </nav>
    </header>

    <main>
      <section class="hero" id="home">
        <div class="container hero-grid">
          <div class="hero-copy">
            <span class="pill">Dibuat untuk tim studio & gym yang sibuk</span>
            <h1>Operasional {{ $brandName }} jadi ringkas, terukur, dan siap berkembang.</h1>
            <p class="lead">{{ $tagline }}</p>
            <div class="cta">
              <a class="btn primary" href="{{ route('login') }}">Masuk Dashboard</a>
              <a class="btn soft" href="#features">Lihat fitur</a>
            </div>
            <div class="hero-points">
              <div class="point"><i class="bi bi-check2-circle"></i>Status member, jatuh tempo, dan kehadiran terekam otomatis.</div>
              <div class="point"><i class="bi bi-check2-circle"></i>Tagihan rapi, pembayaran tercatat, invoice siap cetak.</div>
              <div class="point"><i class="bi bi-check2-circle"></i>Branding, kelas, dan jadwal terasa konsisten untuk member.</div>
            </div>
          </div>

          <div class="hero-visual">
            <div class="panel">
              <div class="panel-head">
                <div>
                  <div class="eyebrow">Jadwal hari ini</div>
                  <div class="panel-title">Semua sesi terstruktur</div>
                </div>
                <span class="pill mini success">Realtime</span>
              </div>
              <div class="schedule">
                <div class="schedule-row">
                  <div>
                    <div class="time">07.00</div>
                    <div class="title">HIIT Burn</div>
                  </div>
                  <div class="meta">
                    <span class="badge success">Penuh</span>
                    <span class="muted">12/12</span>
                  </div>
                </div>
                <div class="schedule-row">
                  <div>
                    <div class="time">10.00</div>
                    <div class="title">Strength Circuit</div>
                  </div>
                  <div class="meta">
                    <span class="badge info">Check-in</span>
                    <span class="muted">8/14</span>
                  </div>
                </div>
                <div class="schedule-row">
                  <div>
                    <div class="time">18.30</div>
                    <div class="title">Mobility Flow</div>
                  </div>
                  <div class="meta">
                    <span class="badge">Tersedia</span>
                    <span class="muted">5 kursi lagi</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="panel panel-stats">
              <div class="panel-head">
                <div>
                  <div class="eyebrow" style="color:#9ba8bd;">Arus kas</div>
                  <div class="panel-title">Tagihan bulan ini</div>
                </div>
                <i class="bi bi-graph-up" aria-hidden="true"></i>
              </div>
              <div class="stat-grid">
                <div class="stat">
                  <div class="label">Lunas</div>
                  <div class="value">Rp 42,5jt</div>
                </div>
                <div class="stat">
                  <div class="label">Menunggu</div>
                  <div class="value">Rp 8,1jt</div>
                </div>
                <div class="stat">
                  <div class="label">Jatuh tempo</div>
                  <div class="value">5 invoice</div>
                </div>
              </div>
              <div class="progress"><div class="bar" style="width:78%;"></div></div>
              <div class="panel-foot">
                <span>78% target tercapai</span>
                <span>Terakhir sinkron 2m lalu</span>
              </div>
            </div>

            <div class="floating-note">
              <i class="bi bi-broadcast-pin"></i>
              Pengingat otomatis sebelum jatuh tempo.
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
            <div class="label">Rata-rata check-in</div>
            <div class="value">86%</div>
          </div>
          <div class="stat-chip">
            <div class="label">Rata-rata konversi tagihan</div>
            <div class="value">91%</div>
          </div>
          <div class="stat-chip">
            <div class="label">Waktu admin</div>
            <div class="value">-4 jam/minggu</div>
          </div>
        </div>
      </section>

      <section class="section" id="features">
        <div class="container">
          <div class="section-head">
            <p class="eyebrow">Kenapa {{ $brandName }}</p>
            <h2>Kontrol penuh operasional di satu layar</h2>
            <p>Monitor membership, jadwal, billing, sampai identitas brand tanpa tab yang berantakan.</p>
          </div>
          <div class="feature-grid">
            <article class="feature-card">
              <div class="feature-highlight"></div>
              <div class="feature-icon"><i class="bi bi-people"></i></div>
              <h3>Kelola member tanpa ribet</h3>
              <p>Atur plan, status, catatan, dan foto profil. Semua data tersinkron dengan billing dan jadwal.</p>
            </article>
            <article class="feature-card">
              <div class="feature-highlight"></div>
              <div class="feature-icon"><i class="bi bi-receipt-cutoff"></i></div>
              <h3>Tagihan jelas, bukti tersimpan</h3>
              <p>Invoice otomatis, metode pembayaran fleksibel, dan riwayat pembayaran yang aman untuk audit.</p>
            </article>
            <article class="feature-card">
              <div class="feature-highlight"></div>
              <div class="feature-icon"><i class="bi bi-calendar2-week"></i></div>
              <h3>Jadwal & kehadiran real-time</h3>
              <p>Buka kelas, pantau kursi tersedia, dan catat check-in. Semua terhubung dengan member aktif.</p>
            </article>
            <article class="feature-card">
              <div class="feature-highlight"></div>
              <div class="feature-icon"><i class="bi bi-magic"></i></div>
              <h3>Branding konsisten</h3>
              <p>Gunakan logo, warna, dan alamat resmi agar pengalaman member terasa premium dan seragam.</p>
            </article>
          </div>
        </div>
      </section>

      <section class="section" id="workflow">
        <div class="container">
          <div class="section-head">
            <p class="eyebrow">Alur kerja</p>
            <h2>Mulai dalam tiga langkah sederhana</h2>
            <p>Pilih plan, cek dashboard, dan biarkan pengingat otomatis berjalan di belakang layar.</p>
          </div>
          <div class="flow-steps">
            <div class="flow-step">
              <div class="step-num">1</div>
              <h3>Masuk dashboard</h3>
              <p>Login, cek status member, dan lihat gambaran umum performa gym.</p>
            </div>
            <div class="flow-step">
              <div class="step-num">2</div>
              <h3>Rapikan jadwal & tagihan</h3>
              <p>Buka kelas, atur kapasitas, buat invoice, dan aktifkan pengingat otomatis.</p>
            </div>
            <div class="flow-step">
              <div class="step-num">3</div>
              <h3>Monitor & tumbuh</h3>
              <p>Pantau check-in, konversi pembayaran, dan optimalkan kapasitas kelas harian.</p>
            </div>
          </div>
        </div>
      </section>

      <section class="section" id="pricing">
        <div class="container">
          <div class="section-head">
            <p class="eyebrow">Paket</p>
            <h2>Pilih ritme bisnis yang pas</h2>
            <p>Harga transparan sesuai kebutuhan, siap digunakan langsung.</p>
          </div>
          <div class="pricing-grid">
            <div class="pricing-card">
              <span class="pill mini soft">Basic</span>
              <div class="price">Rp {{ $basicPrice }} <span>/bulan</span></div>
              <p>Untuk studio yang baru menata sistem dan ingin alur kerja rapi.</p>
              <ul class="pricing-list">
                <li><i class="bi bi-check2-circle"></i>Manajemen member & plan</li>
                <li><i class="bi bi-check2-circle"></i>Invoice digital & cetak</li>
                <li><i class="bi bi-check2-circle"></i>Jadwal kelas dasar</li>
                <li><i class="bi bi-check2-circle"></i>Branding warna & logo</li>
              </ul>
              <a class="btn soft" href="{{ route('login') }}">Gunakan Basic</a>
            </div>
            <div class="pricing-card highlight">
              <span class="pill mini">Premium</span>
              <div class="price">Rp {{ $premiumPrice }} <span>/bulan</span></div>
              <p>Tingkatkan skala, minimalkan pekerjaan manual, dan percepat pembayaran.</p>
              <ul class="pricing-list">
                <li><i class="bi bi-check2-circle"></i>Reminder pembayaran otomatis</li>
                <li><i class="bi bi-check2-circle"></i>Kelas & kapasitas lanjutan</li>
                <li><i class="bi bi-check2-circle"></i>Pelacakan kehadiran detail</li>
                <li><i class="bi bi-check2-circle"></i>Dukungan branding lengkap</li>
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
              <p class="eyebrow" style="color: rgba(255,255,255,0.78);">Siap mulai</p>
              <h2>Terima member baru dengan pengalaman terbaik.</h2>
              <p>Masuk ke dashboard {{ $brandName }}, atur jadwal pertama, dan biarkan pengingat berjalan otomatis.</p>
            </div>
            <div class="cta-actions">
              <a class="btn primary" href="{{ route('login') }}">Masuk sekarang</a>
              <a class="btn" href="#features">Lihat alur</a>
            </div>
          </div>
        </div>
      </section>
    </main>

    <footer class="footer">
      <div class="container footer-grid">
        <div>
          <div class="brand-name">{{ $brandName }}</div>
          <p>Platform minimalis untuk mengelola member, kelas, dan pembayaran gym Anda.</p>
        </div>
        <div class="footer-links">
          <a href="#features">Fitur</a>
          <a href="#pricing">Paket</a>
          <a href="{{ route('login') }}">Masuk</a>
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
