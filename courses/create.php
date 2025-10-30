<?php
session_start();
include_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $code = $conn->real_escape_string($_POST['code']);
  $name = $conn->real_escape_string($_POST['name']);
  $desc = $conn->real_escape_string($_POST['description']);
  $teacher_id = $_SESSION['user_id'];

  $sql = "INSERT INTO courses (code, name, description, created_by)
          VALUES ('$code', '$name', '$desc', '$teacher_id')";
  if ($conn->query($sql)) {
    header('Location: list.php');
    exit;
  } else {
    $msg = '<div class="alert alert-danger">Gagal menambah mata kuliah</div>';
  }
}

include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="col-md-9 ms-sm-auto col-lg-10 p-4">
  <h3 class="fw-bold text-success mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Mata Kuliah</h3>
  <?= $msg ?>
  <div class="card shadow border-0 p-4">
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Kode</label>
        <input type="text" class="form-control" name="code" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Nama Mata Kuliah</label>
        <input type="text" class="form-control" name="name" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description" rows="3"></textarea>
      </div>
      <button class="btn btn-success w-100" type="submit">Simpan</button>
    </form>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
