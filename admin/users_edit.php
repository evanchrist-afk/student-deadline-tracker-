<?php
session_start();
include_once __DIR__ . '/../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

$id = intval($_GET['id']);
$user = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();

if (!$user) {
  die('User tidak ditemukan');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $conn->real_escape_string($_POST['username']);
  $email = $conn->real_escape_string($_POST['email']);
  $role = $_POST['role'];
  $pw = $_POST['password'];

  if (!empty($pw)) {
    $pw_hash = password_hash($pw, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET username='$username', email='$email', role='$role', password='$pw_hash' WHERE id=$id";
  } else {
    $sql = "UPDATE users SET username='$username', email='$email', role='$role' WHERE id=$id";
  }

  if ($conn->query($sql)) {
    header('Location: users_list.php');
    exit;
  } else {
    $msg = '<div class="alert alert-danger">Gagal mengupdate user</div>';
  }
}

include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="col-md-9 ms-sm-auto col-lg-10 p-4">
  <h3 class="fw-bold text-warning mb-4"><i class="bi bi-pencil-square me-2"></i>Edit User</h3>
  <?= $msg ?? '' ?>
  <div class="card shadow border-0 p-4">
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password (Kosongkan jika tidak diganti)</label>
        <input type="password" class="form-control" name="password">
      </div>
      <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-select">
          <option value="student" <?= $user['role']=='student'?'selected':'' ?>>Student</option>
          <option value="teacher" <?= $user['role']=='teacher'?'selected':'' ?>>Teacher</option>
          <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
        </select>
      </div>
      <button class="btn btn-warning w-100" type="submit">Update</button>
    </form>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
