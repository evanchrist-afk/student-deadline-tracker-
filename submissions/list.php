<?php
session_start();
include_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Gunakan prepared statement agar aman dari SQL Injection
if ($role === 'student') {
  $sql = "SELECT s.*, t.title, c.name AS course_name
          FROM submissions s
          JOIN tasks t ON s.task_id = t.id
          JOIN courses c ON t.course_id = c.id
          WHERE s.user_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $user_id);
} else {
  $sql = "SELECT s.*, u.username, t.title, c.name AS course_name
          FROM submissions s
          JOIN users u ON s.user_id = u.id
          JOIN tasks t ON s.task_id = t.id
          JOIN courses c ON t.course_id = c.id";
  $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

include_once __DIR__ . '/../includes/header.php';
?>

<div class="container mt-5">
  <div class="card p-4 shadow">
    <h4 class="mb-3 text-primary">📄 Daftar Pengumpulan Tugas</h4>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Mata Kuliah</th>
          <th>Judul Tugas</th>
          <?php if ($role !== 'student'): ?><th>Mahasiswa</th><?php endif; ?>
          <th>File</th>
          <th>Dikumpulkan Pada</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0):
          $no = 1;
          while ($r = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($r['course_name']) ?></td>
              <td><?= htmlspecialchars($r['title']) ?></td>
              <?php if ($role !== 'student'): ?><td><?= htmlspecialchars($r['username']) ?></td><?php endif; ?>
              <td><a href="<?= htmlspecialchars($r['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a></td>
              <td><?= htmlspecialchars($r['submitted_at']) ?></td>
            </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="6" class="text-center text-muted">Belum ada pengumpulan tugas</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
