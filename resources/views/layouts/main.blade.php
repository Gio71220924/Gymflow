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
        --sidebar-w: 260px;
        --sidebar-w-collapsed: 72px;
        
        --brand-primary: #FC7753;
        --brand-dark: #28231C;
        --brand-ink: #1f130c;
        --brand-soft: #fff2ea;
        
        --neutral-bg: #F8F9FA;
        --neutral-card: #FFFFFF;
        --neutral-border: #E5E7EB;
        --neutral-text: #6B7280;
        --neutral-light: #F3F4F6;
        
        --space-2: 0.5rem;
        --space-3: 0.75rem;
        --space-4: 1rem;
        --space-5: 1.5rem;
        --space-6: 2rem;
        
        --font-primary: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        --text-sm: 0.875rem;
        --text-base: 0.9375rem;
        --text-lg: 1.125rem;
        --text-xl: 1.25rem;
        
        --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md: 0 2px 8px rgba(0,0,0,0.06);
        --shadow-lg: 2px 0 12px rgba(0,0,0,0.08);
        
        --radius-md: 10px;
        --radius-lg: 12px;
      }

      * { box-sizing: border-box; }
      html, body { 
        height: 100%; 
        font-family: var(--font-primary);
        color: var(--brand-ink);
        font-size: 15px;
        line-height: 1.6;
      }
      .layout { min-height: 100vh; height: 100vh; overflow: hidden; }

      .sidebar {
        width: var(--sidebar-w);
        min-height: 100vh;
        height: 100vh;
        position: sticky;
        top: 0;
        background: linear-gradient(180deg, var(--brand-dark) 0%, #1f1a15 100%);
        color: #fff;
        transition: width 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow-y: auto;
        box-shadow: var(--shadow-lg);
      }
      .sidebar.collapsed { width: var(--sidebar-w-collapsed) !important; }

      .sidebar .brand {
        font-weight: 700;
        font-size: var(--text-lg);
        letter-spacing: -0.01em;
      }

      .sidebar .nav-link {
        font-size: var(--text-sm);
        color: rgba(255,255,255,0.7);
        border-radius: var(--radius-md);
        padding: 0.65rem var(--space-4);
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: var(--space-3);
        white-space: nowrap;
        transition: all 0.2s ease;
        font-weight: 500;
      }
      .sidebar .nav-link i {
        font-size: 1rem;
        opacity: 0.85;
        flex-shrink: 0;
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

      .sidebar .label { white-space: nowrap; }
      .sidebar.collapsed .label { display: none; }
      .sidebar.collapsed .nav-link { 
        justify-content: center; 
        gap: 0;
      }
      .sidebar.collapsed .nav-link.active {
        border-left: 3px solid var(--brand-primary);
        padding-left: calc(var(--space-4) - 3px);
      }

      .sidebar-resizer{
        position: absolute;
        top: 0; right: 0; bottom: 0;
        width: 6px;
        cursor: col-resize;
        background: transparent;
        transition: background 0.2s ease;
      }
      .sidebar-resizer:hover { background: rgba(252,119,83,0.2); }
      body.resizing { cursor: col-resize !important; user-select: none; }

      .content {
        background: var(--neutral-bg);
        min-width: 0;
        height: 100vh;
        padding: var(--space-5) var(--space-6);
        overflow-y: auto;
      }

      .page-header {
        margin-bottom: var(--space-5);
      }

      .page-header-title{
        margin: 0;
        font-weight: 700;
        font-size: var(--text-xl);
        letter-spacing: -0.01em;
        color: var(--brand-dark);
      }
      
      .btn-toggle {
        padding: 0.4rem 0.65rem;
        border: 1px solid var(--neutral-border);
        background: white;
        border-radius: var(--radius-md);
        color: var(--neutral-text);
        transition: all 0.2s ease;
      }
      .btn-toggle:hover {
        background: var(--neutral-light);
        border-color: var(--brand-primary);
        color: var(--brand-dark);
      }

      .card.shadow-sm {
        border: 1px solid var(--neutral-border);
        border-radius: var(--radius-lg);
        background: var(--neutral-card);
        box-shadow: var(--shadow-sm);
      }
      .card-header {
        background: var(--neutral-light);
        border-bottom: 1px solid var(--neutral-border);
        padding: var(--space-4) var(--space-5);
        font-weight: 600;
        font-size: var(--text-base);
        color: var(--brand-dark);
      }
      .card-body {
        padding: var(--space-5);
      }

      .user-profile {
        border-top: 1px solid rgba(255,255,255,0.1);
        padding-top: var(--space-4);
      }
      .user-profile img {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        flex-shrink: 0;
        object-fit: cover;
        aspect-ratio: 1 / 1;
      }
      .user-info {
        font-size: var(--text-sm);
        color: rgba(255,255,255,0.9);
      }
      .user-role {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.5);
      }

      .btn-logout, .btn-profile-action {
        width: 100%;
        color: rgba(255,255,255,0.7);
        text-align: left;
        padding: 0.5rem var(--space-3);
        border-radius: var(--radius-md);
        transition: all 0.2s ease;
        font-size: var(--text-sm);
      }
      .btn-logout:hover, .btn-profile-action:hover {
        color: #fff;
        background: rgba(255,255,255,0.08);
        text-decoration: none;
      }
      .btn-logout i, .btn-profile-action i { font-size: 1rem; }
      
      .profile-actions {
        margin-top: var(--space-2);
      }
    </style>

    @yield('styles')
  </head>
  <body>
    <div class="d-flex layout">
      <aside class="sidebar p-3">
        <div>
          <a href="{{ route('home') }}" class="d-flex align-items-center mb-4 px-2 text-white text-decoration-none">
            @if(!empty($appSettings['branding_logo']))
              <img src="{{ asset('storage/branding/'.$appSettings['branding_logo']) }}" alt="Logo" style="height:32px;" class="mr-2">
              <span class="brand label">{{ $appSettings['branding_name'] ?? 'GymFlow' }}</span>
            @else
              <i class="bi bi-activity mr-2 text-warning" style="font-size:1.3rem;"></i>
              <span class="brand label">{{ $appSettings['branding_name'] ?? 'GymFlow' }}</span>
            @endif
          </a>

          <nav class="nav flex-column">
            <a class="nav-link {{ ($key ?? '') === 'home' ? 'active' : '' }}" href="{{ route('home') }}">
              <i class="bi bi-speedometer2"></i>
              <span class="label">Dashboard</span>
            </a>
            <a class="nav-link {{ ($key ?? '') === 'member' ? 'active' : '' }}" href="/member">
              <i class="bi bi-people"></i>
              <span class="label">Members</span>
            </a>
            <a class="nav-link {{ ($key ?? '') === 'users' ? 'active' : '' }}" href="/users">
              <i class="bi bi-person-gear"></i>
              <span class="label">Users</span>
            </a>
            <a class="nav-link {{ ($key ?? '') === 'class' ? 'active' : '' }}" href="/class">
              <i class="bi bi-calendar-event"></i>
              <span class="label">Classes</span>
            </a>
            <a class="nav-link {{ ($key ?? '') === 'billing' ? 'active' : '' }}" href="/billing">
              <i class="bi bi-receipt"></i>
              <span class="label">Billing</span>
            </a>
            <a class="nav-link {{ ($key ?? '') === 'settings' ? 'active' : '' }}" href="/settings">
              <i class="bi bi-gear"></i>
              <span class="label">Settings</span>
            </a>
          </nav>
        </div>

        <div>
          <div class="user-profile">
            <div class="d-flex align-items-center mb-2">
              @php
                $userPhoto = optional(Auth::user()->memberGym)->foto_profil;
              @endphp
              @if($userPhoto)
                <img src="{{ asset('storage/foto_profil/' . $userPhoto) }}" alt="{{ Auth::user()->name }}">
              @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FC7753&color=fff&size=128" alt="{{ Auth::user()->name }}">
              @endif
              <div class="ml-2 label">
                <div class="user-info">{{Auth::user()->name}}</div>
                <div class="user-role">{{Auth::user()->role}}</div>
              </div>
            </div>
          </div>
          <div class="profile-actions">
            <a href="{{ route('change-password') }}" class="btn-profile-action btn btn-link d-flex align-items-center">
              <i class="bi bi-key mr-2"></i>
              <span class="label">Ubah Password</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="btn-logout btn btn-link d-flex align-items-center">
                <i class="bi bi-box-arrow-right mr-2"></i>
                <span class="label">Log Out</span>
              </button>
            </form>
          </div>
        </div>

        <div class="sidebar-resizer" title="Drag to resize"></div>
      </aside>

      <main class="content flex-fill">
        <header class="page-header d-flex align-items-center">
          <button id="sidebarToggle" class="btn-toggle mr-3" type="button" aria-label="Toggle sidebar" aria-expanded="true">
            <i id="sidebarToggleIcon" class="bi bi-layout-sidebar-inset"></i>
          </button>
          <h1 class="page-header-title">@yield('page_heading', 'Dashboard')</h1>
        </header>

        @hasSection('toolbar')
        <div class="d-flex align-items-center justify-content-between mb-4">
          @yield('toolbar')
        </div>
        @endif

        <div class="card shadow-sm">
          @if(View::hasSection('card_title') || View::hasSection('card_actions'))
          <div class="card-header d-flex justify-content-between align-items-center">
            <div>@yield('card_title')</div>
            <div>@yield('card_actions')</div>
          </div>
          @endif
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
