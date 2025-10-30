<?php
session_start();
include_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

if (!isset($_GET['task_id'])) {
  die('Tugas tidak ditemukan.');
}

$task_id = intval($_GET['task_id']);

$sql = "SELECT t.*, c.name AS course_name 
        FROM tasks t 
        JOIN courses c ON t.course_id = c.id 
        WHERE t.id = $task_id";
$result = $conn->query($sql);

if (!$result->num_rows) {
  die('Tugas tidak ditemukan.');
}

$task = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Tugas | Student Deadline Tracker</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
  <div class="container bg-white p-4 rounded shadow">
    <h3 class="text-primary mb-3"><?= htmlspecialchars($task['title']) ?></h3>
    <p><strong>Mata Kuliah:</strong> <?= htmlspecialchars($task['course_name']) ?></p>
    <p><strong>Deskripsi:</strong></p>
    <p><?= nl2br(htmlspecialchars($task['description'])) ?></p>
    <p><strong>Deadline:</strong> <?= date('d M Y', strtotime($task['due_date'])) ?></p>

    <a href="/student_deadline_tracker/student/dashboard.php" class="btn btn-secondary mt-3">
      <i class="bi bi-arrow-left"></i> Kembali
    </a>
  </div>
</body>
</html>
