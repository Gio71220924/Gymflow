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
      /* Layout dasar */
      html, body { height: 100%; }
      .layout { min-height: 100vh; }

      /* Sidebar gelap ala Tailwind sample */
      .sidebar {
        width: 260px;
        min-height: 100vh;
        background-color: #28231C;
        color: #fff;
      }
      .sidebar .brand {
        font-weight: 700;
        font-size: 2.5rem;
      }
      .sidebar .nav-link {
        font-size: 1.3rem;
        color: rgba(255,255,255,0.7);
        border-radius: .75rem;
        padding: .5rem .75rem;
        margin-bottom: 0.6rem;
        display: flex;
        align-items: center;
        letter-spacing: 0.4px;
        gap: 0.75rem;
        margin-right: 0 !important;
        padding-top:0.9 rem;
        padding-bottom:0.9 rem;
        line-height: 2;
      }
      .sidebar .nav-link i {
        font-size: 1rem;
        margin-right: .5rem;
      }
      .sidebar .nav-link.active,
      .sidebar .nav-link:hover {
        color: #fff;
        background: rgba(255,255,255,0.1);
      }

      /* Konten kanan */
      .content {
        background: #f8f9fa;
      }
    </style>

    @yield('styles')
  </head>
  <body>
    <div class="d-flex layout">
      <!-- Sidebar kiri -->
      <aside class="sidebar d-flex flex-column justify-content-between p-3">
        <div>
          <div class="d-flex align-items-center mb-4 px-2">
            <i class="bi bi-barbell mr-2 text-warning" style="font-size:1.4rem;"></i>
            <span class="brand">GymFlow</span>
          </div>

          <nav class="nav flex-column">
            <a class="nav-link {{ ($key ?? '') === 'home'    ? 'active' : '' }}" href="/home">
              <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a class="nav-link {{ ($key ?? '') === 'member'  ? 'active' : '' }}" href="/member">
              <i class="bi bi-people"></i> Member Management
            </a>
            <a class="nav-link {{ ($key ?? '') === 'class' ? 'active' : '' }}" href="/class">
              <i class="bi bi-calendar-event"></i> Classes
            </a>
            <a class="nav-link {{ ($key ?? '') === 'billing' ? 'active' : '' }}" href="/billing">
              <i class="bi bi-receipt"></i> Billing
            </a>
            <a class="nav-link {{ ($key ?? '') === 'settings'? 'active' : '' }}" href="/settings">
              <i class="bi bi-gear"></i> Settings
            </a>
          </nav>
        </div>

        <div>
          <div class="border-top border-light pt-3 px-2">
            <div class="media align-items-center">
              <img src="https://preview.redd.it/u7lozj3xywo91.jpg?width=1080&crop=smart&auto=webp&s=3031b3dd9263f0cc01ccf4c567d5fb73373da915"
                   class="mr-2 rounded-circle" width="36" height="36" alt="pp">
              <div class="media-body">
                <small class="d-block">Admin Name</small>
                <small class="text-muted">Gym Administrator</small>
              </div>
            </div>
          </div>
          <a class="nav-link mt-2" href="#"><i class="bi bi-box-arrow-right mr-1"></i> Log Out</a>
        </div>
      </aside>

      <!-- Konten kanan -->
      <main class="content flex-fill p-4">
        <!-- Heading halaman -->
        <header class="mb-3">
          <h1 class="h3 font-weight-bold mb-0">
            @yield('page_heading', 'Member Management')
          </h1>
        </header>

        <!-- Toolbar opsional (search/filter) -->
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
          <div class="d-flex flex-wrap">
            @yield('toolbar')
          </div>
          <!-- Dikosongkan karena tombol add dipindah ke header card di bawah -->
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

    <!-- Auto-init generik: semua table yang punya atribut data-datatable -->
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('table[data-datatable]').forEach(function (el) {
          if (!el.dataset.dtInited) {
            new DataTable(el);
            el.dataset.dtInited = '1';
          }
        });
      });
    </script>

    @yield('scripts')
  </body>
</html>
