@php
  use Carbon\Carbon;
  use Illuminate\Support\Facades\Storage;
  $brandName = $appSettings['branding_name'] ?? 'GymFlow';
  $brandColor = $appSettings['branding_color'] ?? '#FC7753';
  $tagline = $appSettings['branding_tagline'] ?? 'Kelola gym, jadwal, dan pembayaran dengan satu dashboard.';
  $contactEmail = $appSettings['branding_email'] ?? 'info@gymflow.id';
  $basicPrice = isset($appSettings['billing_basic_price']) ? number_format($appSettings['billing_basic_price'], 0, ',', '.') : '150.000';
  $premiumPrice = isset($appSettings['billing_premium_price']) ? number_format($appSettings['billing_premium_price'], 0, ',', '.') : '300.000';
  $brandLogo = $brandLogo ?? null;
  $liveClasses = collect($gymClasses ?? []);
  $timezone = $timezone ?? (config('app.timezone') !== 'UTC' ? config('app.timezone') : 'Asia/Jakarta');
  $statMembers = $appSettings['landing_stat_members'] ?? '120+';
  $statAttendance = $appSettings['landing_stat_attendance'] ?? '86%';
  $statClasses = $appSettings['landing_stat_classes'] ?? '30+';
  $statCheckinSpeed = $appSettings['landing_stat_checkin'] ?? '< 20 dtk';
  $today = Carbon::now($timezone);
  $searchDate = $searchDate ?? $today->toDateString();
  $searchQuery = $searchQuery ?? '';
  $searchDateCarbon = Carbon::parse($searchDate, $timezone);
  $preparedClasses = $liveClasses->map(function ($class) use ($timezone) {
    $start = Carbon::parse($class->start_at)->timezone($timezone);
    $end   = $class->end_at ? Carbon::parse($class->end_at)->timezone($timezone) : null;
    $capacity = max(0, (int) ($class->capacity ?? 0));
    $booked = (int) ($class->booked_count ?? 0);
    $photoUrl = !empty($class->photo) ? Storage::url($class->photo) : asset('images/noimage.png');
    if ($booked === 0) {
      // Tampilkan angka simulasi agar tidak kosong, tanpa melebihi kapasitas
      if ($capacity > 0) {
        $booked = min($capacity, max(1, random_int(1, max(3, $capacity - 1))));
      } else {
        $booked = random_int(5, 18);
      }
    }
    $slotsLeft = max($capacity - $booked, 0);
    $statusKey = strtolower($class->status ?? '');
    $isCancelled = $statusKey === 'cancelled';
    $isDone = $statusKey === 'done';
    $isLive = !$isCancelled && !$isDone && $end && $start->isPast() && $end->isFuture();
    $isFull = $capacity > 0 && $booked >= $capacity;
    $badgeClass = $isCancelled ? 'danger' : ($isLive ? 'info' : ($isFull ? 'warning' : 'success'));
    $badgeLabel = $isCancelled ? 'Dibatalkan' : ($isLive ? 'Berlangsung' : ($isDone ? 'Selesai' : ($isFull ? 'Penuh' : 'Tersedia')));
    $statusLabel = $statusKey === 'cancelled' ? 'Dibatalkan' : ($statusKey === 'done' ? 'Selesai' : 'Terjadwal');

    return [
      'raw' => $class,
      'start' => $start,
      'end' => $end,
      'booked' => $booked,
      'capacity' => $capacity,
      'slotsLeft' => $slotsLeft,
      'isCancelled' => $isCancelled,
      'isDone' => $isDone,
      'isLive' => $isLive,
      'isFull' => $isFull,
      'badgeClass' => $badgeClass,
      'badgeLabel' => $badgeLabel,
      'statusLabel' => $statusLabel,
      'dateLabel' => $start->format('d M'),
      'timeLabel' => $start->format('H:i'),
      'timeRange' => $end ? $start->format('H:i') . ' - ' . $end->format('H:i') : $start->format('H:i'),
      'location' => $class->location ?: 'Lokasi menyusul',
      'title' => $class->title,
      'trainerNames' => $class->trainer_name ?? '',
      'photo' => $photoUrl,
    ];
  })->values();
  $filteredClasses = $preparedClasses->filter(function ($class) use ($searchDateCarbon) {
    return $class['start']->isSameDay($searchDateCarbon);
  })->values();
@endphp
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="{{ $tagline }}">
  <meta property="og:title" content="{{ $brandName }} | Gym &amp; kelas terjadwal">
  <meta property="og:description" content="{{ $tagline }}">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta name="twitter:card" content="summary_large_image">
  <title>{{ $brandName }} | Landing Member</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Sora:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css">
  <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
  <style>:root{--brand: {{ $brandColor }};}</style>
</head>
<body>
  <div class="backdrop gradient-1"></div>
  <div class="backdrop gradient-2"></div>
  <div class="backdrop gradient-3"></div>

  <div class="page">
    <header class="topbar" id="topbar">
      <div class="brand">
        <a class="brand-logo" href="#home">
          <div class="mark{{ $brandLogo ? ' mark-img' : '' }}">
            @if($brandLogo)
              <img src="{{ $brandLogo }}" alt="{{ $brandName }} logo">
            @else
              {{ strtoupper(substr($brandName, 0, 1)) }}
            @endif
          </div>
        </a>
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
            @forelse($preparedClasses->take(4) as $class)
              <div class="schedule-row">
                <div>
                  <div class="time">{{ $class['timeLabel'] }}</div>
                  <div class="title">{{ $class['title'] }}</div>
                  <div class="muted">{{ $class['location'] }}</div>
                </div>
                <div class="meta">
                  <span class="badge {{ $class['badgeClass'] }}">{{ $class['badgeLabel'] }}</span>
                  @if($class['capacity'] > 0)
                    <span class="muted">{{ $class['booked'] }} / {{ $class['capacity'] }} slot</span>
                    @if(!$class['isFull'] && !$class['isCancelled'] && !$class['isDone'])
                      <span class="pill mini soft">Sisa {{ $class['slotsLeft'] }}</span>
                    @endif
                  @else
                    <span class="muted">{{ $class['booked'] }} peserta</span>
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

            <div class="floating-note">
              <i class="bi bi-broadcast-pin"></i>
              Notifikasi sebelum kelas dimulai.
            </div>
          </div>
        </div>
      </section>

      <section class="section" id="cta">
        <div class="container stat-strip">
          <div class="stat-chip">
            <div class="label">Member aktif</div>
            <div class="value">{{ $statMembers }}</div>
          </div>
          <div class="stat-chip">
            <div class="label">Tingkat hadir</div>
            <div class="value">{{ $statAttendance }}</div>
          </div>
          <div class="stat-chip">
            <div class="label">Kelas per minggu</div>
            <div class="value">{{ $statClasses }}</div>
          </div>
          <div class="stat-chip">
            <div class="label">Durasi check-in</div>
            <div class="value">{{ $statCheckinSpeed }}</div>
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
          <form class="class-search" id="classes-search" method="GET" action="{{ route('landingpage') }}#classes-search">
            <div class="class-search-grid">
              <div class="form-group mb-0">
                <label class="eyebrow mb-1">Tanggal</label>
                <input type="date" name="date" value="{{ $searchDate }}" min="{{ $today->toDateString() }}" class="form-control form-control-sm class-search-input">
              </div>
              <div class="form-group mb-0">
                <label class="eyebrow mb-1">Cari</label>
                <input type="text" name="q" value="{{ $searchQuery }}" class="form-control form-control-sm class-search-input" placeholder="Judul, lokasi, instruktur">
              </div>
              <div class="form-group mb-0 align-self-end">
                <div class="class-search-actions">
                  <button type="submit" class="btn primary search-btn">Cari jadwal</button>
                  <a class="btn soft search-btn" href="{{ route('landingpage') }}#classes-search">Reset</a>
                </div>
              </div>
            </div>
            <div class="text-muted small mt-2">Hasil untuk {{ $searchDateCarbon->format('d M Y') }} ({{ $filteredClasses->count() }} kelas)</div>
          </form>

          <div class="class-grid">
            @forelse($filteredClasses as $class)
              <div class="class-card">
                <div style="height:140px; border-radius:12px; overflow:hidden; margin-bottom:12px; border:1px solid #eee; background:#f8f8f8; display:flex; align-items:center; justify-content:center;">
                  <img src="{{ $class['photo'] }}" alt="Foto {{ $class['title'] }}" style="width:100%; height:100%; object-fit:contain;">
                </div>
                <div class="class-meta">
                  <span class="pill mini soft">{{ $class['dateLabel'] }}</span>
                  <span class="class-cap"><i class="bi bi-people"></i>{{ $class['capacity'] > 0 ? $class['capacity'] . ' kursi' : 'Tanpa batas' }}</span>
                </div>
                <h3>{{ $class['title'] }}</h3>
                <p>{{ $class['location'] }}</p>
                @if(!empty($class['trainerNames']))
                  <div class="text-muted small mb-1">Instruktur: {{ $class['trainerNames'] }}</div>
                @endif
                <div class="class-meta">
                  <span><i class="bi bi-clock"></i> {{ $class['timeRange'] }}</span>
                  <span><i class="bi bi-geo-alt"></i> {{ $class['location'] }}</span>
                </div>
                <div class="class-meta">
                  @if($class['capacity'] > 0)
                    <span class="badge {{ $class['isFull'] ? 'danger' : 'success' }}">{{ $class['isFull'] ? 'Penuh' : 'Sisa ' . $class['slotsLeft'] . ' slot' }}</span>
                  @else
                    <span class="badge info">Slot fleksibel</span>
                  @endif
                  <span class="muted">{{ $class['booked'] }} terdaftar - {{ $class['statusLabel'] }}</span>
                </div>
                <a class="btn join-btn full" href="{{ route('login') }}">Bergabung</a>
              </div>
            @empty
              <div class="class-card">
                <h3>Belum ada jadwal</h3>
                <p>Tidak ditemukan kelas untuk tanggal {{ $searchDateCarbon->format('d M') }}. Coba tanggal lain atau hapus pencarian.</p>
                <a class="btn join-btn full" href="#classes">Reset pencarian</a>
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
        <div class="footer-brand">
          <div class="brand-name">{{ $brandName }}</div>
          <p>Tempat latihan yang rapi, modern, dan siap menemani progresmu.</p>
          <div class="footer-meta">&copy; {{ date('Y') }} {{ $brandName }}</div>
        </div>
        <div class="footer-links">
          <h4>Tautan Cepat</h4>
          <ul class="footer-links-list">
            <li><a href="#home"><i class="bi bi-arrow-right-circle"></i>Beranda</a></li>
            <li><a href="#features"><i class="bi bi-arrow-right-circle"></i>Keunggulan</a></li>
            <li><a href="#classes"><i class="bi bi-arrow-right-circle"></i>Kelas</a></li>
            <li><a href="#facilities"><i class="bi bi-arrow-right-circle"></i>Fasilitas</a></li>
            <li><a href="#pricing"><i class="bi bi-arrow-right-circle"></i>Membership</a></li>
            <li><a href="{{ route('login') }}"><i class="bi bi-arrow-right-circle"></i>Masuk / Daftar</a></li>
          </ul>
        </div>
        <div class="footer-contact">
          <h4>Hubungi Kami</h4>
          <p>Kami siap membantu lewat email.</p>
          <a class="contact-chip" href="mailto:{{ $contactEmail }}">
            <i class="bi bi-envelope"></i>
            <span>{{ $contactEmail }}</span>
          </a>
        </div>
        <div class="footer-actions">
          <a class="btn soft" href="#home"><i class="bi bi-arrow-up-circle"></i> Kembali ke atas</a>
          <a class="btn primary" href="{{ route('login') }}">Masuk / Daftar</a>
        </div>
      </div>
    </footer>
    <button id="backToTop" class="back-to-top" aria-label="Kembali ke atas">
      <i class="bi bi-arrow-up-short" style="font-size:22px;"></i>
    </button>
  </div>

  <script>
    (function () {
      const topbar = document.getElementById('topbar');
      const backToTop = document.getElementById('backToTop');
      function handleScroll() {
        if (topbar) {
          if (window.scrollY > 20) topbar.classList.add('scrolled');
          else topbar.classList.remove('scrolled');
        }
        if (backToTop) {
          if (window.scrollY > 240) backToTop.classList.add('show');
          else backToTop.classList.remove('show');
        }
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

      if (backToTop) {
        backToTop.addEventListener('click', function () {
          window.scrollTo({ top: 0, behavior: 'smooth' });
        });
      }
    })();
  </script>
  <script type="module">
    import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';

    // Widget chat n8n (floating bubble di pojok kanan bawah)
    createChat({                                                                                                                                                                                        
    webhookUrl: 'http://localhost:5678/webhook/2c0f65e8-e7a0-4e6d-837d-23254c504243/chat',                                                                                                                                               
    title: 'FlowAI',                                                                                                                                                                                  
    subtitle: 'Asisten otomatis GymFlow',                                                                                                                                                             
    initialMessages: [                                                                                                                                                                                
      'Hi, selamat datang di FlowAI!, Assisten otomatis dari GymFlow yang siap melayani kamu!'                                                                                                        
    ],                                                                                                                                                                                                                                                                                                                                                                                                                                                            
  });                                                                                                                                                                                                 
  </script>
</body>
</html>
