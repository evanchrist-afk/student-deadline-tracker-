<?php
session_start();
include_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

if (!isset($_GET['task_id'])) {
  echo "ID tugas tidak ditemukan.";
  exit;
}

$task_id = intval($_GET['task_id']);
$user_id = $_SESSION['user_id'];

// Ambil detail tugas
$sql = "SELECT t.*, c.name AS course_name, s.file_path, s.submitted_at
        FROM tasks t
        JOIN courses c ON t.course_id = c.id
        LEFT JOIN submissions s ON s.task_id = t.id AND s.user_id = $user_id
        WHERE t.id = $task_id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
  echo "Tugas tidak ditemukan.";
  exit;
}

$task = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Tugas | Student Deadline Tracker</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8fafc;
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
    }
    .container {
      max-width: 800px;
      margin-top: 50px;
      background: white;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .btn-back {
      background: #1e3a8a;
      color: white;
    }
    .btn-back:hover {
      background: #172554;
      color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <h3 class="text-primary mb-4">📘 Detail Tugas</h3>
    
    <p><strong>Mata Kuliah:</strong> <?= htmlspecialchars($task['course_name']) ?></p>
    <p><strong>Judul Tugas:</strong> <?= htmlspecialchars($task['title']) ?></p>
    <p><strong>Deskripsi:</strong> <?= nl2br(htmlspecialchars($task['description'])) ?></p>
    <p><strong>Deadline:</strong> <?= date('d M Y', strtotime($task['due_date'])) ?></p>

    <hr>

    <?php if (!empty($task['file_path'])): ?>
      <p><strong>File yang dikumpulkan:</strong></p>
      <a href="<?= htmlspecialchars($task['file_path']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
        Lihat File
      </a>
      <p class="mt-2"><strong>Waktu Pengumpulan:</strong> <?= date('d M Y H:i', strtotime($task['submitted_at'])) ?></p>
    <?php else: ?>
      <div class="alert alert-warning">Belum ada file dikumpulkan.</div>
    <?php endif; ?>

    <a href="/student_deadline_tracker/dashboard/student.php" class="btn btn-back mt-3">⬅ Kembali ke Dashboard</a>
  </div>
</body>
</html>
