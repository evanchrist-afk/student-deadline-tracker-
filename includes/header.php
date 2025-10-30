<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<!-- 🌙 Custom Dark Mode -->
<style>
  body.dark-mode {
    background-color: #121212 !important;
    color: #f1f1f1;
  }
  .dark-mode .card, 
  .dark-mode .table {
    background-color: #1f1f1f !important;
    color: #eaeaea;
  }
  .dark-mode .btn, 
  .dark-mode .form-control, 
  .dark-mode .form-select {
    border-color: #333 !important;
  }
  .dark-mode .navbar, 
  .dark-mode .footer {
    background-color: #1a1a1a !important;
  }
</style>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Deadline Tracker</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="/student_deadline_tracker/assets/css/style.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">🎓 Student Tracker</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">
        Halo, <?= $_SESSION['username'] ?? 'Guest' ?>
      </span>
      <?php if (isset($_SESSION['user_id'])): ?>
      <a href="/student_deadline_tracker/users/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<div class="container-fluid">
  <div class="row">
