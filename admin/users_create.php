<?php
session_start();
include_once __DIR__ . '/../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $conn->real_escape_string($_POST['username']);
  $email = $conn->real_escape_string($_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role = $_POST['role'];

  $sql = "INSERT INTO users (username, email, password, role)
          VALUES ('$username', '$email', '$password', '$role')";
  if ($conn->query($sql)) {
    header('Location: users_list.php');
    exit;
  } else {
    $msg = '<div class="alert alert-danger">Gagal menambah user</div>';
  }
}

include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="col-md-9 ms-sm-auto col-lg-10 p-4">
  <h3 class="fw-bold text-success mb-4"><i class="bi bi-person-plus-fill me-2"></i>Tambah User</h3>
  <?= $msg ?>
  <div class="card shadow border-0 p-4">
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" class="form-control" name="username" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-select">
          <option value="student">Student</option>
          <option value="teacher">Teacher</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <button class="btn btn-success w-100" type="submit">Simpan</button>
    </form>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
