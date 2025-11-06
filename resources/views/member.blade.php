<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Gym Membership Management (Bootstrap)</title>

  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Symbols (icons) -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <!-- Lexend font (optional) -->
  <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet" />

  <!-- Custom CSS (Laravel Blade) -->
  <!-- If using Laravel Blade, keep this line: -->
  <link rel="stylesheet" href="{{ asset('css/member.css') }}">
  <!-- If using a plain HTML file in /public, use: <link rel="stylesheet" href="/css/member.css"> -->

  <style>
    /* Minimal inline fallback in case member.css not loaded */
    body { font-family: "Lexend", system-ui, -apple-system, Segoe UI, Roboto, sans-serif; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; font-size: 20px; line-height:1; }
  </style>
</head>
<body>
  <header class="border-bottom py-3">
    <div class="container d-flex align-items-center justify-content-between gap-3">
      <div class="d-flex align-items-center gap-2">
        <div class="text-primary" style="width:24px;height:24px" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M24 .757 47.243 24 24 47.243.757 24 24 .757ZM21 35.757V12.243L9.243 24 21 35.757Z"/></svg>
        </div>
        <strong class="fs-5">GymLogo</strong>
      </div>

      <div class="d-flex align-items-center gap-2">
        <!-- Toggle Admin/User -->
        <div class="btn-group" role="group" aria-label="View switch">
          <input type="radio" class="btn-check" name="viewToggle" id="viewAdmin" autocomplete="off" checked>
          <label class="btn btn-outline-secondary" for="viewAdmin">Admin</label>

          <input type="radio" class="btn-check" name="viewToggle" id="viewUser" autocomplete="off">
          <label class="btn btn-outline-secondary" for="viewUser">User</label>
        </div>

        <!-- Theme toggle -->
        <button id="themeToggle" type="button" class="btn btn-outline-secondary" aria-label="Toggle theme">
          <span class="material-symbols-outlined align-text-top">dark_mode</span>
        </button>

        <!-- Add member -->
        <button id="openModal" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#memberModal">
          Add Member
        </button>

        <!-- Avatar -->
        <div class="rounded-circle" style="width:40px;height:40px;background:url('https://lh3.googleusercontent.com/aida-public/AB6AXuCdYOZUIUEHupKNhRGA1Iv-8tHZYhPUEH34IG6UrBrO91u0ijxdKl39ZDmlG7X86yEI5hTnnyw_XSX_UlDxGlU3WI514DS0NEMjNYPF9XiG7crm60hf7eQGqaBpWxOCz_TqR9KY7_1z7RWR7SJJyl4orN2Nd3w13XpLYyR62QQsvzTjAxoGySYVuM8I8ccZOxRf4hEKdkpYLyKnrHSI8Suz6q0A9hBb8GtjN8yzYj_aL3lV9o4O5OSbD_ya4tsqxNXC_2S0uYX29vg') center/cover no-repeat"></div>
      </div>
    </div>
  </header>

  <main class="container py-4">
    <!-- Admin View -->
    <section id="adminView" class="d-flex flex-column gap-3">
      <div class="d-flex flex-wrap justify-content-between align-items-end">
        <div>
          <h1 class="display-6 fw-bold mb-0">Member Management</h1>
          <p class="text-secondary-emphasis mb-0">Create, read, update, and delete member information.</p>
        </div>
      </div>

      <!-- Search -->
      <div class="input-group mt-3">
        <span class="input-group-text"><span class="material-symbols-outlined">search</span></span>
        <input id="searchInput" type="text" class="form-control" placeholder="Search members by name or email" autocomplete="off" />
      </div>

      <!-- Table -->
      <div class="card mt-3">
        <div class="table-responsive">
          <table id="memberTable" class="table table-hover table-borderless align-middle mb-0">
            <thead class="table-secondary-subtle">
              <tr>
                <th class="sortable">Member Name</th>
                <th class="sortable">Email</th>
                <th class="sortable">Membership</th>
                <th class="sortable">Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="memberBody">
              <tr>
                <td>Alex Johnson</td>
                <td class="text-secondary">alex.j@email.com</td>
                <td class="text-secondary">Premium</td>
                <td><span class="badge rounded-pill text-bg-success">Active</span></td>
                <td>
                  <div class="d-flex gap-1">
                    <button type="button" class="btn btn-sm btn-outline-secondary action-edit" aria-label="Edit Alex Johnson"><span class="material-symbols-outlined">edit</span></button>
                    <button type="button" class="btn btn-sm btn-outline-danger action-delete" aria-label="Delete Alex Johnson"><span class="material-symbols-outlined">delete</span></button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Maria Garcia</td>
                <td class="text-secondary">maria.g@email.com</td>
                <td class="text-secondary">Basic</td>
                <td><span class="badge rounded-pill text-bg-success">Active</span></td>
                <td>
                  <div class="d-flex gap-1">
                    <button type="button" class="btn btn-sm btn-outline-secondary action-edit" aria-label="Edit Maria Garcia"><span class="material-symbols-outlined">edit</span></button>
                    <button type="button" class="btn btn-sm btn-outline-danger action-delete" aria-label="Delete Maria Garcia"><span class="material-symbols-outlined">delete</span></button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Sam Chen</td>
                <td class="text-secondary">sam.c@email.com</td>
                <td class="text-secondary">Premium</td>
                <td><span class="badge rounded-pill text-bg-warning">Inactive</span></td>
                <td>
                  <div class="d-flex gap-1">
                    <button type="button" class="btn btn-sm btn-outline-secondary action-edit" aria-label="Edit Sam Chen"><span class="material-symbols-outlined">edit</span></button>
                    <button type="button" class="btn btn-sm btn-outline-danger action-delete" aria-label="Delete Sam Chen"><span class="material-symbols-outlined">delete</span></button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- User View -->
    <section id="userView" class="d-none">
      <div class="text-center my-4">
        <h2 class="fw-bold">Choose Your Plan</h2>
        <p class="text-secondary-emphasis">Select the perfect membership plan to start your fitness journey with us today.</p>
      </div>

      <div class="row g-4">
        <div class="col-12 col-md-6">
          <div class="card h-100">
            <div class="card-body">
              <h3 class="h4 fw-bold">Basic Fit</h3>
              <p class="text-secondary">Core features for a great start.</p>
              <div class="my-3"><span class="display-5 fw-black">$29</span><span class="ms-2">/month</span></div>
              <ul class="list-unstyled vstack gap-2 text-body-secondary">
                <li class="d-flex align-items-center gap-2"><span class="material-symbols-outlined text-success">check_circle</span>Full Gym Access</li>
                <li class="d-flex align-items-center gap-2"><span class="material-symbols-outlined text-success">check_circle</span>Standard Group Classes</li>
                <li class="d-flex align-items-center gap-2"><span class="material-symbols-outlined text-success">check_circle</span>Locker Room Access</li>
              </ul>
              <button type="button" id="selectBasic" class="btn btn-outline-success w-100 mt-3">Select Plan</button>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="card border-primary h-100">
            <div class="card-body position-relative">
              <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill text-bg-primary">Most Popular</span>
              <h3 class="h4 fw-bold">Premium Pro</h3>
              <p class="text-secondary">All features for the ultimate experience.</p>
              <div class="my-3"><span class="display-5 fw-black">$59</span><span class="ms-2">/month</span></div>
              <ul class="list-unstyled vstack gap-2 text-body-secondary">
                <li class="d-flex align-items-center gap-2"><span class="material-symbols-outlined text-success">check_circle</span>Everything in Basic</li>
                <li class="d-flex align-items-center gap-2"><span class="material-symbols-outlined text-success">check_circle</span>Personal Trainer Sessions (2/mo)</li>
                <li class="d-flex align-items-center gap-2"><span class="material-symbols-outlined text-success">check_circle</span>Sauna and Spa Access</li>
                <li class="d-flex align-items-center gap-2"><span class="material-symbols-outlined text-success">check_circle</span>Guest Passes</li>
              </ul>
              <button type="button" id="selectPremium" class="btn btn-primary w-100 mt-3">Book Now</button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Member Modal (Create / Edit) -->
  <div class="modal fade" id="memberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="memberModalTitle">Add Member</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="memberForm" autocomplete="off">
          <div class="modal-body">
            <input type="hidden" name="rowIndex" />
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input name="nama_member" class="form-control" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email_member" class="form-control" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Membership</label>
              <select name="membership" class="form-select">
                <option>Basic</option>
                <option>Premium</option>
              </select>
            </div>
            <div class="mb-2">
              <label class="form-label">Status</label>
              <select name="status" class="form-select">
                <option>Active</option>
                <option>Inactive</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap Bundle (with Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // ===== Theme (Bootstrap 5.3 data-bs-theme)
    (function(){
      const KEY = 'bs-theme';
      const html = document.documentElement;
      const saved = localStorage.getItem(KEY);
      if(saved){ html.setAttribute('data-bs-theme', saved); }
      document.getElementById('themeToggle')?.addEventListener('click', ()=>{
        const next = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', next);
        localStorage.setItem(KEY, next);
      });
    })();

    // ===== View toggle
    (function(){
      const admin = document.getElementById('adminView');
      const user  = document.getElementById('userView');
      const viewAdmin = document.getElementById('viewAdmin');
      const viewUser  = document.getElementById('viewUser');
      function apply(){
        admin.classList.toggle('d-none', !viewAdmin.checked);
        user.classList.toggle('d-none', !viewUser.checked);
      }
      [viewAdmin, viewUser].forEach(r=> r.addEventListener('change', apply));
      apply();
    })();

    // ===== Search filter
    (function(){
      const input = document.getElementById('searchInput');
      const tbody = document.getElementById('memberBody');
      input?.addEventListener('input', (e)=>{
        const q = e.target.value.trim().toLowerCase();
        tbody.querySelectorAll('tr').forEach(tr => {
          const hay = tr.innerText.toLowerCase();
          tr.classList.toggle('d-none', !hay.includes(q));
        });
      });
    })();

    // ===== Sortable headers
    (function(){
      const table = document.getElementById('memberTable');
      const tbody = document.getElementById('memberBody');
      table.querySelectorAll('th.sortable').forEach((th, index) => {
        let asc = true;
        th.style.cursor = 'pointer';
        th.addEventListener('click', ()=>{
          const rows = Array.from(tbody.querySelectorAll('tr'));
          rows.sort((a,b)=>{
            const A = a.children[index].innerText.trim().toLowerCase();
            const B = b.children[index].innerText.trim().toLowerCase();
            return asc ? A.localeCompare(B) : B.localeCompare(A);
          }).forEach(tr => tbody.appendChild(tr));
          asc = !asc;
        });
      });
    })();

    // ===== Modal + CRUD (client-only demo)
    (function(){
      const tbody = document.getElementById('memberBody');
      const form = document.getElementById('memberForm');
      const modalEl = document.getElementById('memberModal');
      const modal = new bootstrap.Modal(modalEl);
      const title = document.getElementById('memberModalTitle');

      // Open empty (Add)
      document.getElementById('openModal')?.addEventListener('click', ()=>{
        form.reset();
        if(form.rowIndex) form.rowIndex.value = '';
        title.textContent = 'Add Member';
      });

      function statusBadge(status){
        const active = status === 'Active';
        return `<span class="badge rounded-pill ${active ? 'text-bg-success' : 'text-bg-warning'}">${status}</span>`;
      }

      form.addEventListener('submit', (e)=>{
        e.preventDefault();
        const fd = new FormData(form);
        const data = Object.fromEntries(fd.entries());
        const isEdit = !!data.rowIndex;

        if(isEdit){
          const tr = tbody.querySelectorAll('tr')[Number(data.rowIndex)];
          if(tr){
            tr.children[0].textContent = data.nama_member;
            tr.children[1].textContent = data.email_member;
            tr.children[2].textContent = data.membership;
            tr.children[3].innerHTML = statusBadge(data.status);
          }
        } else {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${data.nama_member}</td>
            <td class="text-secondary">${data.email_member}</td>
            <td class="text-secondary">${data.membership}</td>
            <td>${statusBadge(data.status)}</td>
            <td>
              <div class="d-flex gap-1">
                <button type="button" class="btn btn-sm btn-outline-secondary action-edit"><span class="material-symbols-outlined">edit</span></button>
                <button type="button" class="btn btn-sm btn-outline-danger action-delete"><span class="material-symbols-outlined">delete</span></button>
              </div>
            </td>`;
          tbody.appendChild(tr);
        }
        modal.hide();
        form.reset();
      });

      // Delegated actions
      tbody.addEventListener('click', (e)=>{
        const btn = e.target.closest('button');
        if(!btn) return;
        const tr = btn.closest('tr');
        if(btn.classList.contains('action-delete')){
          const name = tr.children[0].textContent.trim();
          if(confirm(`Delete ${name}?`)) tr.remove();
          return;
        }
        if(btn.classList.contains('action-edit')){
          const cells = tr.children;
          form.nama_member.value = cells[0].textContent.trim();
          form.email_member.value = cells[1].textContent.trim();
          form.membership.value = cells[2].textContent.trim();
          form.status.value = cells[3].innerText.trim();
          const index = Array.from(tbody.children).indexOf(tr);
          form.rowIndex.value = String(index);
          title.textContent = 'Edit Member';
          modal.show();
          return;
        }
      });
    })();

    // ===== User view demo buttons
    document.getElementById('selectBasic')?.addEventListener('click', ()=> alert('Basic Fit selected!'));
    document.getElementById('selectPremium')?.addEventListener('click', ()=> alert('Premium Pro booked!'));
  </script>
</body>
</html>
