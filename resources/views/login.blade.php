<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login | GymFlow</title>

  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
  <style>
    body{
      background: #f8f9fa;
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:16px;
      font-family: "Segoe UI", Arial, sans-serif;
    }
    .login-box{
      width: 380px;
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
    .btn-primary:focus, .btn-primary:active{
      background:#e05f3f !important;
      border-color:#d85738 !important;
      box-shadow:0 0 0 .2rem rgba(252,119,83,.35);
    }
  </style>
</head>
<body>
  <div class="login-box">
    <div class="mb-4 text-center">
      <div class="rounded-circle d-inline-flex align-items-center justify-content-center accent" style="width:54px;height:54px;">
        <span style="font-size:20px;">GF</span>
      </div>
      <h5 class="mt-3 mb-0">Masuk ke GymFlow</h5>
      <small class="text-muted">Gunakan email dan password akun Anda</small>
    </div>
    <form>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" class="form-control" placeholder="nama@email.com" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" class="form-control" placeholder="••••••••" required>
      </div>
      <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="remember">
          <label class="custom-control-label" for="remember">Ingat saya</label>
        </div>
        <a href="#" class="small">Lupa password?</a>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-qQSIqC8b1DxJtYhPib7+PBkO5RAwOB1p5MNDoAuCEVs0aKBslrHong/QwZ0p6fM9"
          crossorigin="anonymous"></script>
</body>
</html>
