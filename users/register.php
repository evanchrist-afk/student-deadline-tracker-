<?php
session_start();
include_once __DIR__ . '/../config/db.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $conn->real_escape_string($_POST['username']);
  $email = $conn->real_escape_string($_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role = $_POST['role'];

  $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
  if ($conn->query($sql)) {
    header('Location: login.php');
    exit;
  } else {
    $msg = '<div class="alert alert-danger text-center">❌ Gagal membuat akun. Mungkin email sudah terdaftar.</div>';
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register | Student Deadline Tracker</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #E3F2FD, #BBDEFB);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
    }

    .register-card {
      display: flex;
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 35px rgba(0,0,0,0.15);
      max-width: 900px;
      width: 100%;
      animation: fadeIn 1.2s ease;
    }

    .register-img {
      width: 50%;
      background: url('https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=900&q=80') center/cover no-repeat;
    }

    .register-content {
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

    .form-label {
      font-weight: 500;
    }

    @media (max-width: 768px) {
      .register-img { display: none; }
      .register-content { width: 100%; }
    }
  </style>
</head>
<body>
  <div class="register-card">
    <div class="register-img"></div>

    <div class="register-content">
      <h3 class="text-center mb-3">Buat Akun Baru 🎓</h3>
      <p class="text-muted text-center mb-4">Daftar sebagai Student, Teacher, atau Admin</p>
      <?= $msg ?>

      <form method="post">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input class="form-control" type="text" name="username" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input class="form-control" type="email" name="email" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input class="form-control" type="password" name="password" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Pilih Role</label>
          <select class="form-select" name="role" required>
            <option value="">-- Pilih Role --</option>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        <button class="btn btn-primary w-100 mt-2" type="submit">Daftar</button>
      </form>

      <p class="text-center mt-3">Sudah punya akun? 
        <a href="login.php" class="text-primary fw-semibold">Login</a>
      </p>
    </div>
  </div>
</body>
</html>
