<?php
session_start();
include_once __DIR__ . '/../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

$id = intval($_GET['id']);
$conn->query("DELETE FROM users WHERE id = $id");
header('Location: users_list.php');
exit;
