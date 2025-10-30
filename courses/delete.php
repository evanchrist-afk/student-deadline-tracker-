<?php
session_start();
include_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

$id = intval($_GET['id']);
$teacher_id = $_SESSION['user_id'];

$conn->query("DELETE FROM courses WHERE id = $id AND created_by = $teacher_id");
header('Location: list.php');
exit;
