<?php
session_start();
include_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

// --- Hapus user jika ada request ---
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  if ($id !== $_SESSION['user_id']) { // biar admin gak bisa hapus diri sendiri
    $conn->query("DELETE FROM users WHERE id = $id");
  }
  header('Location: admin.php');
  exit;
}

// --- Ambil semua data user ---
$users = $conn->query("SELECT id, username, email, role FROM users ORDER BY role, username");

include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="col-md-9 ms-sm-auto col-lg-10 p-4">
  <h3 class="fw-bold text-danger mb-4">
    <i class="bi bi-person-gear me-2"></i>Dashboard Admin
  </h3>

  <div class="card shadow border-0 p-4 rounded-4">
    <h5 class="text-primary mb-3"><i class="bi bi-people"></i> Daftar Pengguna</h5>
    <table class="table table-hover align-middle">
      <thead class="table-danger">
        <tr>
          <th>No</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if ($users->num_rows > 0):
          $no = 1;
          while ($u = $users->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td>
              <span class="badge bg-<?= 
                $u['role'] == 'admin' ? 'danger' : 
                ($u['role'] == 'teacher' ? 'warning' : 'success')
              ?>">
                <?= ucfirst($u['role']) ?>
              </span>
            </td>
            <td>
              <?php if ($u['id'] != $_SESSION['user_id']): ?>
                <a href="?delete=<?= $u['id'] ?>" 
                   class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Yakin mau hapus akun ini?')">
                  <i class="bi bi-trash"></i> Hapus
                </a>
              <?php else: ?>
                <span class="text-muted">Tidak bisa hapus diri sendiri</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile;
        else: ?>
          <tr><td colspan="5" class="text-center text-muted">Belum ada pengguna</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
