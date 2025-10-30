<?php
session_start();
include_once __DIR__ . '/../config/db.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $conn->real_escape_string($_POST['email']);
  $pw = $_POST['password'];
  $role = $_POST['role'];

  $sql = "SELECT * FROM users WHERE email='$email' AND role='$role' LIMIT 1";
  $res = $conn->query($sql);
  if ($res && $res->num_rows) {
    $u = $res->fetch_assoc();
    if (password_verify($pw, $u['password'])) {
      $_SESSION['user_id'] = $u['id'];
      $_SESSION['username'] = $u['username'];
      $_SESSION['role'] = $u['role'];
      header('Location: /student_deadline_tracker/dashboard/' . $u['role'] . '.php');
      exit;
    } else {
      $msg = '<div class="alert alert-danger text-center">❌ Password salah</div>';
    }
  } else {
    $msg = '<div class="alert alert-danger text-center">⚠️ Email atau role tidak cocok</div>';
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login | Student Deadline Tracker</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(120deg, #E3F2FD, #BBDEFB);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
    }

    .login-card {
      display: flex;
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 35px rgba(0,0,0,0.15);
      max-width: 900px;
      width: 100%;
      animation: fadeIn 1.2s ease;
    }

    /* 🔥 Gambar login */
    .login-img {
      width: 50%;
      background: url('https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=900&q=80') center/cover no-repeat;
    }

    .login-content {
      width: 50%;
      padding: 40px;
    }

    h3 {
      font-weight: 600;
      color: #1976d2;
    }

    .btn-primary {
      background-color: #1976d2;
      border: none;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #0d47a1;
      box-shadow: 0 0 12px rgba(25, 118, 210, 0.6);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="login-img"></div>
    <div class="login-content">
      <h3 class="text-center mb-3">Student Deadline Tracker 🧭</h3>
      <?= $msg ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Masuk Sebagai</label>
          <select name="role" class="form-select" required>
            <option value="">-- Pilih Role --</option>
            <option value="student">Mahasiswa</option>
            <option value="teacher">Dosen</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-3">Masuk</button>
      </form>
      <p class="text-center mt-3">Belum punya akun? <a href="register.php">Daftar Sekarang</a></p>
    </div>
  </div>
</body>
</html>
