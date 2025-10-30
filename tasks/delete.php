<?php
session_start();
include_once __DIR__ . '/../config/db.php';

// Cek login & role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

$teacher_id = $_SESSION['user_id'];

// Pastikan ada ID course
if (!isset($_GET['id'])) {
  header('Location: index.php');
  exit;
}

$course_id = (int) $_GET['id'];

// Cek apakah mata kuliah milik guru ini
$check = $conn->prepare("SELECT * FROM courses WHERE id = ? AND created_by = ?");
$check->bind_param("ii", $course_id, $teacher_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
  $_SESSION['error'] = "Mata kuliah tidak ditemukan atau bukan milik Anda.";
  header('Location: index.php');
  exit;
}

// Hapus pengumpulan (submissions) yang terkait dengan tugas dalam course ini
$conn->query("
  DELETE submissions 
  FROM submissions 
  JOIN tasks ON submissions.task_id = tasks.id 
  WHERE tasks.course_id = $course_id
");

// Hapus semua tugas (tasks) yang terkait dengan course
$conn->query("DELETE FROM tasks WHERE course_id = $course_id");

// Hapus mata kuliah
$stmt = $conn->prepare("DELETE FROM courses WHERE id = ? AND created_by = ?");
$stmt->bind_param("ii", $course_id, $teacher_id);
$stmt->execute();

$_SESSION['success'] = "Mata kuliah beserta semua tugas & pengumpulan berhasil dihapus.";
header('Location: index.php');
exit;
?>
