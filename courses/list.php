<?php
session_start();
include_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

$teacher_id = $_SESSION['user_id'];
$courses = $conn->query("SELECT * FROM courses WHERE created_by = $teacher_id ORDER BY id DESC");

include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="col-md-9 ms-sm-auto col-lg-10 p-4">
  <h3 class="fw-bold text-primary mb-4"><i class="bi bi-journal-bookmark-fill me-2"></i>Daftar Mata Kuliah</h3>

  <div class="mb-3 text-end">
    <a href="create.php" class="btn btn-success"><i class="bi bi-plus-circle-fill me-1"></i>Tambah Mata Kuliah</a>
  </div>

  <div class="card shadow border-0">
    <div class="card-body p-0">
      <table class="table table-striped align-middle mb-0">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama Mata Kuliah</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($courses->num_rows): $no=1; while($c = $courses->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><span class="badge bg-secondary"><?= htmlspecialchars($c['code']) ?></span></td>
            <td><?= htmlspecialchars($c['name']) ?></td>
            <td><?= htmlspecialchars($c['description']) ?></td>
            <td>
              <a href="edit.php?id=<?= $c['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
              <a href="delete.php?id=<?= $c['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus course ini?')"><i class="bi bi-trash3-fill"></i></a>
            </td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="5" class="text-center text-muted py-4">Belum ada mata kuliah</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
