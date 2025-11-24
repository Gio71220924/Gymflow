{{-- resources/views/layouts/main.blade.php --}}
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title')</title>

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- DataTables v2 CSS -->
    <link rel="stylesheet"
          href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css">

    <style>
      :root{
        /* Sidebar Dimensions */
        --sidebar-w: 260px;
        --sidebar-w-collapsed: 72px;
        
        /* Brand Colors */
        --brand-primary: #FC7753;
        --brand-dark: #28231C;
        --brand-ink: #1f130c;
        --brand-soft: #fff2ea;
        
        /* Neutral Colors */
        --neutral-bg: #FAFAFA;
        --neutral-card: #FFFFFF;
        --neutral-border: #E5E5E5;
        --neutral-text: #6B6B6B;
        --neutral-light: #F5F5F5;
        
        /* Spacing System (8px grid) */
        --space-1: 0.25rem;  /* 4px */
        --space-2: 0.5rem;   /* 8px */
        --space-3: 0.75rem;  /* 12px */
        --space-4: 1rem;     /* 16px */
        --space-5: 1.5rem;   /* 24px */
        --space-6: 2rem;     /* 32px */
        --space-8: 3rem;     /* 48px */
        
        /* Typography */
        --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        --text-xs: 0.75rem;   /* 12px */
        --text-sm: 0.875rem;  /* 14px */
        --text-base: 1rem;    /* 16px */
        --text-lg: 1.125rem;  /* 18px */
        --text-xl: 1.25rem;   /* 20px */
        --text-2xl: 1.5rem;   /* 24px */
        
        /* Shadows */
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
        --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
        
        /* Border Radius */
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
      }

      /* Global Styles */
      * { box-sizing: border-box; }
      html, body { 
        height: 100%; 
        font-family: var(--font-primary);
        color: var(--brand-ink);
      }
      .layout { min-height: 100vh; }

      /* Sidebar */
      .sidebar {
        width: var(--sidebar-w);
        min-height: 100vh;
        background: linear-gradient(180deg, var(--brand-dark) 0%, #1f1a15 100%);
        color: #fff;
        transition: width 0.25s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: var(--shadow-lg);
      }
      .sidebar.collapsed {
        width: var(--sidebar-w-collapsed) !important;
      }

      .sidebar .brand {
        font-weight: 700;
        font-size: var(--text-xl);
        letter-spacing: -0.02em;
      }

      /* Navigation Links */
      .sidebar .nav-link {
        font-size: var(--text-base);
        color: rgba(255,255,255,0.75);
        border-radius: var(--radius-md);
        padding: var(--space-3) var(--space-4);
        margin-bottom: var(--space-2);
        display: flex;
        align-items: center;
        gap: var(--space-3);
        line-height: 1.2;
        white-space: nowrap;
        transition: all 0.2s ease;
        font-weight: 500;
      }
      .sidebar .nav-link i {
        font-size: 1.1rem;
        margin-right: 0;
        opacity: 0.9;
      }
      .sidebar .nav-link:hover {
        color: #fff;
        background: rgba(255,255,255,0.08);
        transform: translateX(2px);
      }
      .sidebar .nav-link.active {
        color: #fff;
        background: rgba(252,119,83,0.15);
        border-left: 3px solid var(--brand-primary);
        padding-left: calc(var(--space-4) - 3px);
      }

      /* Collapsed State */
      .sidebar .label { white-space: nowrap; }
      .sidebar.collapsed .label { display: none; }
      .sidebar.collapsed .nav-link { 
        justify-content: center; 
        gap: 0;
        padding-left: var(--space-4);
      }
      .sidebar.collapsed .nav-link.active {
        border-left: none;
        border-left: 3px solid var(--brand-primary);
        padding-left: calc(var(--space-4) - 3px);
      }

      /* Resize Handle */
      .sidebar-resizer{
        position: absolute;
        top: 0; right: 0; bottom: 0;
        width: 6px;
        cursor: col-resize;
        background: transparent;
        transition: background 0.2s ease;
      }
      .sidebar-resizer:hover { 
        background: rgba(252,119,83,0.2);
      }
      body.resizing {
        cursor: col-resize !important;
        user-select: none;
      }

      /* Main Content Area */
      .content {
        background: var(--neutral-bg);
        min-width: 0;
        padding: var(--space-6);
      }

      /* Page Header */
      .page-header-title{
        margin: 0;
        font-weight: 700;
        font-size: var(--text-2xl);
        letter-spacing: -0.02em;
        color: var(--brand-dark);
      }
      
      /* Cards */
      .card.shadow-sm {
        border: 1px solid var(--neutral-border);
        border-radius: var(--radius-lg);
        background: var(--neutral-card);
        box-shadow: var(--shadow-md);
      }
      .card-header {
        background: transparent;
        border-bottom: 1px solid var(--neutral-border);
        padding: var(--space-5);
        font-weight: 600;
        color: var(--brand-dark);
      }
      .card-body {
        padding: var(--space-6);
      }
    </style>

    @yield('styles')
  </head>
  <body>
    <div class="d-flex layout">
      <!-- Sidebar kiri -->
      <aside class="sidebar p-3">
        <div>
          <a href="{{ route('home') }}" class="d-flex align-items-center mb-4 px-2 text-white text-decoration-none">
            @if(!empty($appSettings['branding_logo']))
              <img src="{{ asset('storage/branding/'.$appSettings['branding_logo']) }}" alt="Logo" style="height:34px;" class="mr-2">
              <span class="brand label">{{ $appSettings['branding_name'] ?? 'GymFlow' }}</span>
            @else
              <i class="bi bi-barbell mr-2 text-warning" style="font-size:1.4rem;"></i>
              <span class="brand label">{{ $appSettings['branding_name'] ?? 'GymFlow' }}</span>
            @endif
          </a>

          <nav class="nav flex-column">
            <a class="nav-link {{ ($key ?? '') === 'home'    ? 'active' : '' }}" href="{{ route('home') }}">
              <i class="bi bi-speedometer2"></i> <span class="label">Dashboard</span>
            </a>
            <a class="nav-link {{ ($key ?? '') === 'member'  ? 'active' : '' }}" href="/member">
              <i class="bi bi-people"></i> <span class="label">Member Management</span>
            </a>
            <a class="nav-link {{ ($key ?? '') === 'users'  ? 'active' : '' }}" href="/users">
              <i class="bi bi-person-gear"></i> <span class="label">User Management</span>
            </a>
            <a class="nav-link {{ ($key ?? '') === 'class' ? 'active' : '' }}" href="/class">
              <i class="bi bi-calendar-event"></i> <span class="label">Classes</span>
            </a>
            <a class="nav-link {{ ($key ?? '') === 'billing' ? 'active' : '' }}" href="/billing">
              <i class="bi bi-receipt"></i> <span class="label">Billing</span>
            </a>
            <a class="nav-link {{ ($key ?? '') === 'settings'? 'active' : '' }}" href="/settings">
              <i class="bi bi-gear"></i> <span class="label">Settings</span>
            </a>
          </nav>
        </div>

        <div>
          <div class="border-top border-light pt-3 px-2">
            <div class="media align-items-center">
              <img src="https://preview.redd.it/u7lozj3xywo91.jpg?width=1080&crop=smart&auto=webp&s=3031b3dd9263f0cc01ccf4c567d5fb73373da915"
                   class="mr-2 rounded-circle" width="36" height="36" alt="pp">
              <div class="media-body">
                <small class="d-block label">Admin Name</small>
                <small class="text-muted label">Gym Administrator</small>
              </div>
            </div>
          </div>
          <a class="nav-link mt-2" href="#"><i class="bi bi-box-arrow-right mr-1"></i> <span class="label">Log Out</span></a>
        </div>

        <!-- Handle untuk drag-resize -->
        <div class="sidebar-resizer" title="Drag to resize"></div>
      </aside>

      <!-- Konten kanan -->
      <main class="content flex-fill">
        <!-- Heading + tombol toggle sidebar -->
        <header class="mb-3 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <button id="sidebarToggle"
                    class="btn btn-sm btn-outline-secondary mr-2"
                    type="button"
                    aria-label="Toggle sidebar"
                    aria-expanded="true">
              <i id="sidebarToggleIcon" class="bi bi-layout-sidebar-inset"></i>
            </button>
            <h1 class="page-header-title">
              @yield('page_heading', 'Member Management')
            </h1>
          </div>
          <div></div>
        </header>

        <!-- Toolbar opsional (search/filter) -->
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
          <div class="d-flex flex-wrap">
            @yield('toolbar')
          </div>
          <div></div>
        </div>

        <!-- Card utama berisi judul + aksi + konten -->
        <div class="card shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center">
            <div>@yield('card_title')</div>
            <div>@yield('card_actions')</div>
          </div>
          <div class="card-body">
            @yield('content')
          </div>
        </div>
      </main>
    </div>

    <!-- jQuery, Popper, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>

    <!-- DataTables v2 (vanilla) -->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>

    <!-- Init + Collapse + Drag-Resize -->
    <script>
      (function () {
        const sidebar = document.querySelector('.sidebar');
        const resizer = document.querySelector('.sidebar-resizer');
        const btn = document.getElementById('sidebarToggle');
        const btnIcon = document.getElementById('sidebarToggleIcon');

        // ===== DataTables auto-init + simpan instance untuk penyesuaian lebar
        window._dtInstances = [];
        function initTables() {
          document.querySelectorAll('table[data-datatable]').forEach(function (el) {
            if (!el.dataset.dtInited) {
              const dt = new DataTable(el);
              el.dataset.dtInited = '1';
              window._dtInstances.push(dt);
            }
          });
        }
        function adjustTables() {
          // Coba panggil API adjust jika ada, kalau tidak, paksa reflow via resize event
          let adjusted = false;
          window._dtInstances.forEach(function (dt) {
            if (dt && typeof dt.columns?.adjust === 'function') {
              try { dt.columns.adjust(); adjusted = true; } catch(e){}
            }
          });
          if (!adjusted) {
            window.dispatchEvent(new Event('resize'));
          }
        }

        // ===== Persist state
        const savedCollapsed = localStorage.getItem('sidebarCollapsed') === '1';
        const savedWidth = parseInt(localStorage.getItem('sidebarWidth') || '0', 10);
        const savedExpandedWidth = parseInt(localStorage.getItem('sidebarWidthExpanded') || '0', 10);

        if (savedCollapsed) {
          sidebar.classList.add('collapsed');
          btn?.setAttribute('aria-expanded', 'false');
          btnIcon?.classList.remove('bi-layout-sidebar-inset');
          btnIcon?.classList.add('bi-layout-sidebar-reverse');
        } else if (savedWidth) {
          sidebar.style.width = savedWidth + 'px';
        }

        // ===== Toggle collapse
        btn && btn.addEventListener('click', function () {
          const willCollapse = !sidebar.classList.contains('collapsed');
          if (willCollapse) {
            // simpan lebar terakhir saat expanded
            const w = parseInt(getComputedStyle(sidebar).width, 10);
            localStorage.setItem('sidebarWidthExpanded', String(w));
            // lalu collapse
            sidebar.classList.add('collapsed');
            btn.setAttribute('aria-expanded', 'false');
            btnIcon.classList.remove('bi-layout-sidebar-inset');
            btnIcon.classList.add('bi-layout-sidebar-reverse');
            localStorage.setItem('sidebarCollapsed', '1');
          } else {
            // expand: pakai lebar terakhir yang disimpan atau default
            sidebar.classList.remove('collapsed');
            btn.setAttribute('aria-expanded', 'true');
            btnIcon.classList.remove('bi-layout-sidebar-reverse');
            btnIcon.classList.add('bi-layout-sidebar-inset');
            localStorage.setItem('sidebarCollapsed', '0');

            const expandedW = savedExpandedWidth || 260;
            sidebar.style.width = expandedW + 'px';
            localStorage.setItem('sidebarWidth', String(expandedW));
          }
          // beri ruang ke tabel
          setTimeout(adjustTables, 220); // setelah animasi .2s
        });

        // ===== Drag-resize
        if (resizer) {
          const MIN = 180, MAX = 420;
          let startX = 0, startW = 0, dragging = false;

          const onMove = (e) => {
            if (!dragging) return;
            const dx = e.clientX - startX;
            let w = Math.min(MAX, Math.max(MIN, startW + dx));
            sidebar.style.width = w + 'px';
          };
          const onUp = () => {
            if (!dragging) return;
            dragging = false;
            document.body.classList.remove('resizing');
            document.removeEventListener('mousemove', onMove);
            document.removeEventListener('mouseup', onUp);

            const w = parseInt(getComputedStyle(sidebar).width, 10);
            localStorage.setItem('sidebarWidth', String(w));
            localStorage.setItem('sidebarWidthExpanded', String(w));
            localStorage.setItem('sidebarCollapsed', '0'); // pastikan status expanded
            // penyesuaian tabel
            adjustTables();
          };
          resizer.addEventListener('mousedown', (e) => {
            // kalau collapsed, buka dulu biar bisa resize
            if (sidebar.classList.contains('collapsed')) {
              sidebar.classList.remove('collapsed');
              btn?.setAttribute('aria-expanded', 'true');
              btnIcon?.classList.remove('bi-layout-sidebar-reverse');
              btnIcon?.classList.add('bi-layout-sidebar-inset');
              localStorage.setItem('sidebarCollapsed', '0');
              // pakai lebar tersimpan atau default
              const expandedW = parseInt(localStorage.getItem('sidebarWidthExpanded') || '260', 10);
              sidebar.style.width = expandedW + 'px';
            }

            dragging = true;
            startX = e.clientX;
            startW = parseInt(getComputedStyle(sidebar).width, 10);
            document.body.classList.add('resizing');
            document.addEventListener('mousemove', onMove);
            document.addEventListener('mouseup', onUp);
          });
        }

        // ===== Init on load
        document.addEventListener('DOMContentLoaded', function () {
          initTables();
          // kecil kemungkinan, tapi jaga-jaga: adjust setelah render
          setTimeout(adjustTables, 50);
        });
      })();
    </script>

    @yield('scripts')
  </body>
</html>
