<?php
session_start();
include_once __DIR__ . '/../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

$users = $conn->query("SELECT * FROM users ORDER BY id DESC");

include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="col-md-9 ms-sm-auto col-lg-10 p-4">
  <h3 class="fw-bold text-primary mb-4"><i class="bi bi-people-fill me-2"></i>Kelola Pengguna</h3>

  <div class="mb-3 text-end">
    <a href="user_add.php" class="btn btn-success"><i class="bi bi-person-plus-fill me-1"></i>Tambah User</a>
  </div>

  <div class="card shadow border-0">
    <div class="card-body p-0">
      <table class="table table-striped align-middle mb-0">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($users->num_rows): $no=1; while($u = $users->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><span class="badge bg-info text-dark"><?= ucfirst($u['role']) ?></span></td>
            <td>
              <a href="user_edit.php?id=<?= $u['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
              <a href="user_delete.php?id=<?= $u['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus user ini?')"><i class="bi bi-trash3-fill"></i></a>
            </td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="5" class="text-center text-muted">Belum ada pengguna</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
