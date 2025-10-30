<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Dosen | Student Deadline Tracker</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f7f9fc;
      display: flex;
      height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background: #1e293b;
      color: #fff;
      display: flex;
      flex-direction: column;
      padding: 30px 20px;
    }

    .sidebar h4 {
      font-weight: 600;
      margin-bottom: 30px;
      text-align: center;
      color: #fff;
      letter-spacing: 0.5px;
    }

    .sidebar a {
      color: #cbd5e1;
      text-decoration: none;
      padding: 12px 15px;
      border-radius: 10px;
      margin-bottom: 8px;
      transition: 0.3s;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .sidebar a:hover, .sidebar a.active {
      background-color: #2563eb;
      color: #fff;
    }

    /* Main content */
    .main {
      flex: 1;
      padding: 40px;
    }

    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
      background-color: #fff;
    }

    .header-title {
      font-weight: 600;
      color: #1e3a8a;
    }

    .btn-primary {
      background-color: #2563eb;
      border: none;
    }

    .btn-primary:hover {
      background-color: #1e40af;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h4>👨‍🏫 Dosen Panel</h4>
    <a href="#" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="/student_deadline_tracker/courses/list.php"><i class="bi bi-journal-text"></i> Kelola Mata Kuliah</a>
    <a href="/student_deadline_tracker/tasks/list.php"><i class="bi bi-clipboard-data"></i> Kelola Tugas</a>
    <a href="/student_deadline_tracker/users/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>

  <!-- Main -->
  <div class="main">
    <div class="card p-5">
      <h3 class="header-title mb-3">Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?></h3>
      <p class="text-muted">Anda dapat mengelola mata kuliah dan tugas mahasiswa melalui menu di sisi kiri.</p>

      <hr>

      <div class="row g-4 mt-3">
        <div class="col-md-6">
          <div class="card p-4 border-0 shadow-sm">
            <h5><i class="bi bi-journal-text text-primary me-2"></i>Kelola Mata Kuliah</h5>
            <p class="text-muted small mb-3">Tambahkan, ubah, atau hapus mata kuliah yang Anda ampu.</p>
            <a href="/student_deadline_tracker/courses/list.php" class="btn btn-primary btn-sm">Masuk</a>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card p-4 border-0 shadow-sm">
            <h5><i class="bi bi-clipboard-data text-success me-2"></i>Kelola Tugas</h5>
            <p class="text-muted small mb-3">Buat dan atur tenggat tugas untuk setiap mata kuliah.</p>
            <a href="/student_deadline_tracker/tasks/list.php" class="btn btn-primary btn-sm">Masuk</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
