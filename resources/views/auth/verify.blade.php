{{-- resources/views/auth/verify.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Verifikasi Email | GymFlow</title>

  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
  <style>
    body{
      background:#f8f9fa;
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:16px;
      font-family:"Segoe UI", Arial, sans-serif;
    }
    .auth-box{
      width:480px;
      background:#fff;
      border-radius:12px;
      box-shadow:0 16px 48px rgba(0,0,0,0.08);
      padding:28px;
    }
    .accent{
      background:#FC7753;
      color:#fff;
    }
    .btn-primary{
      background:#FC7753;
      border-color:#FC7753;
    }
    .btn-primary:hover{
      background:#e96a49;
      border-color:#e05f3f;
    }
    .btn-primary:focus,.btn-primary:active{
      background:#e05f3f!important;
      border-color:#d85738!important;
      box-shadow:0 0 0 .2rem rgba(252,119,83,.35);
    }
  </style>
</head>
<body>
  <div class="auth-box">
    <div class="mb-4 text-center">
      <div class="rounded-circle d-inline-flex align-items-center justify-content-center accent" style="width:54px;height:54px;">
        <span style="font-size:20px;">GF</span>
      </div>
      <h5 class="mt-3 mb-0">Verifikasi email Anda</h5>
      <small class="text-muted">Kami sudah mengirimkan tautan verifikasi ke email.</small>
    </div>

    @if(session('status'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <p class="text-muted">Jika belum menerima email, klik tombol di bawah untuk mengirim ulang tautan verifikasi.</p>

    <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
      @csrf
      <button type="submit" class="btn btn-primary btn-block">Kirim ulang link verifikasi</button>
    </form>

    <div class="text-center mt-3">
      <a href="{{ route('logout') }}"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-qQSIqC8b1DxJtYhPib7+PBkO5RAwOB1p5MNDoAuCEVs0aKBslrHong/QwZ0p6fM9"
          crossorigin="anonymous"></script>
</body>
</html>
