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
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

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

          @php $role = Auth::user()->role ?? null; @endphp
          <nav class="nav flex-column">
            @if($role === \App\User::ROLE_SUPER_ADMIN)
            <a class="nav-link {{ ($key ?? '') === 'home' ? 'active' : '' }}" href="{{ route('home') }}">
              <i class="bi bi-speedometer2"></i>
              <span class="label">Dashboard</span>
            </a>
            <a class="nav-link {{ ($key ?? '') === 'member' ? 'active' : '' }}" href="/member">
              <i class="bi bi-people"></i>
              <span class="label">Members</span>
            </a>
            <a class="nav-link {{ ($key ?? '') === 'trainers' ? 'active' : '' }}" href="{{ route('trainers.index') }}">
              <i class="bi bi-person-badge"></i>
              <span class="label">Instruktur</span>
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
            @else
            <a class="nav-link {{ ($key ?? '') === 'user-home' ? 'active' : '' }}" href="{{ route('home') }}">
              <i class="bi bi-house"></i>
              <span class="label">Beranda</span>
            </a>
            <a class="nav-link {{ ($key ?? '') === 'class' ? 'active' : '' }}" href="/class">
              <i class="bi bi-calendar-event"></i>
              <span class="label">Jadwal</span>
            </a>
            <a class="nav-link {{ ($key ?? '') === 'billing' ? 'active' : '' }}" href="/billing">
              <i class="bi bi-receipt"></i>
              <span class="label">Tagihan</span>
            </a>
            <a class="nav-link {{ ($key ?? '') === 'change-password' ? 'active' : '' }}" href="{{ route('change-password') }}">
              <i class="bi bi-person"></i>
              <span class="label">Profil</span>
            </a>
            @endif
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
          <button id="themeToggle" class="btn-toggle mr-2" type="button" aria-label="Toggle theme">
            <i id="themeToggleIcon" class="bi bi-moon-stars"></i>
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
        const themeToggle = document.getElementById('themeToggle');
        const themeToggleIcon = document.getElementById('themeToggleIcon');
        const root = document.documentElement;
        const THEME_KEY = 'uiTheme';

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

        // ===== Theme toggle
        function setThemeIcon(theme) {
          if (!themeToggleIcon) return;
          themeToggleIcon.classList.remove('bi-brightness-high', 'bi-moon-stars');
          if (theme === 'dark') {
            themeToggleIcon.classList.add('bi-brightness-high');
            themeToggle?.setAttribute('aria-label', 'Switch to light mode');
          } else {
            themeToggleIcon.classList.add('bi-moon-stars');
            themeToggle?.setAttribute('aria-label', 'Switch to dark mode');
          }
        }
        function applyTheme(theme) {
          const normalized = theme === 'dark' ? 'dark' : 'light';
          root.setAttribute('data-theme', normalized);
          localStorage.setItem(THEME_KEY, normalized);
          setThemeIcon(normalized);
          // sesuaikan tabel setelah repaint
          setTimeout(adjustTables, 50);
        }
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const savedTheme = localStorage.getItem(THEME_KEY);
        applyTheme(savedTheme || (prefersDark ? 'dark' : 'light'));
        themeToggle?.addEventListener('click', function () {
          const current = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
          applyTheme(current === 'dark' ? 'light' : 'dark');
        });

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
