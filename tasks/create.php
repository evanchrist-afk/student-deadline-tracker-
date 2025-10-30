<?php
session_start();
include_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $course_id = intval($_POST['course_id']);
  $title = trim($_POST['title']);
  $desc = trim($_POST['description']);
  $due = $_POST['due_date'];
  $priority = $_POST['priority'];
  $created_by = $_SESSION['user_id'];

  if (!$course_id || !$title || !$due) {
    $msg = '<div class="alert alert-warning">Semua field wajib diisi.</div>';
  } else {
    $stmt = $conn->prepare("
      INSERT INTO tasks (course_id, title, description, due_date, priority, created_by, status)
      VALUES (?, ?, ?, ?, ?, ?, 'open')
    ");
    $stmt->bind_param('issssi', $course_id, $title, $desc, $due, $priority, $created_by);

    if ($stmt->execute()) {
      header('Location: list.php?success=added');
      exit;
    } else {
      $msg = '<div class="alert alert-danger">❌ Gagal menambahkan tugas.</div>';
    }
  }
}

$courses = $conn->query("SELECT id, name FROM courses ORDER BY name ASC");
include_once __DIR__ . '/../includes/header.php';
?>
<div class="container py-5">
  <div class="card shadow p-4">
    <h4 class="fw-bold text-success mb-3"><i class="bi bi-plus-circle me-2"></i>Buat Tugas Baru</h4>
    <?= $msg ?>
    <form method="POST">
      <div class="mb-3">
        <label>Mata Kuliah</label>
        <select name="course_id" class="form-select" required>
          <option value="">-- Pilih Mata Kuliah --</option>
          <?php while ($c = $courses->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-3">
        <label>Judul Tugas</label>
        <input type="text" name="title" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
      </div>
      <div class="mb-3">
        <label>Batas Waktu</label>
        <input type="date" name="due_date" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Prioritas</label>
        <select name="priority" class="form-select">
          <option value="low">Rendah</option>
          <option value="medium">Sedang</option>
          <option value="high">Tinggi</option>
        </select>
      </div>
      <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
      <a href="list.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</div>
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
