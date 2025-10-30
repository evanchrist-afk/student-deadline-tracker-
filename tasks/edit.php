<?php
session_start();
include_once __DIR__ . '/../config/db.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header('Location: list.php?error=invalid');
  exit;
}

$id = intval($_GET['id']);
$msg = '';

// 🔹 Ambil data tugas yang akan diedit
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  header('Location: list.php?error=notfound');
  exit;
}

$task = $result->fetch_assoc();

// 🔹 Ambil daftar mata kuliah
$courses = $conn->query("SELECT id, name FROM courses ORDER BY name ASC");

// 🔹 Proses update tugas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $course_id = intval($_POST['course_id']);
  $title = trim($_POST['title']);
  $desc = trim($_POST['description']);
  $due = $_POST['due_date'];
  $priority = $_POST['priority'];
  $status = $_POST['status'];

  if (!$course_id || !$title || !$due) {
    $msg = '<div class="alert alert-warning">Semua field wajib diisi.</div>';
  } else {
    $stmt = $conn->prepare("
      UPDATE tasks 
      SET course_id = ?, title = ?, description = ?, due_date = ?, priority = ?, status = ?
      WHERE id = ?
    ");
    $stmt->bind_param('isssssi', $course_id, $title, $desc, $due, $priority, $status, $id);

    if ($stmt->execute()) {
      header('Location: list.php?success=updated');
      exit;
    } else {
      $msg = '<div class="alert alert-danger">❌ Gagal memperbarui tugas.</div>';
    }
  }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
  <div class="card shadow p-4">
    <h4 class="fw-bold text-primary mb-3"><i class="bi bi-pencil-square me-2"></i>Edit Tugas</h4>
    <?= $msg ?>

    <form method="POST">
      <div class="mb-3">
        <label>Mata Kuliah</label>
        <select name="course_id" class="form-select" required>
          <option value="">-- Pilih Mata Kuliah --</option>
          <?php while ($c = $courses->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>" <?= $task['course_id'] == $c['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-3">
        <label>Judul Tugas</label>
        <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($task['title']) ?>">
      </div>

      <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($task['description']) ?></textarea>
      </div>

      <div class="mb-3">
        <label>Batas Waktu</label>
        <input type="date" name="due_date" class="form-control" required value="<?= htmlspecialchars($task['due_date']) ?>">
      </div>

      <div class="mb-3">
        <label>Prioritas</label>
        <select name="priority" class="form-select">
          <option value="low" <?= $task['priority'] == 'low' ? 'selected' : '' ?>>Rendah</option>
          <option value="medium" <?= $task['priority'] == 'medium' ? 'selected' : '' ?>>Sedang</option>
          <option value="high" <?= $task['priority'] == 'high' ? 'selected' : '' ?>>Tinggi</option>
        </select>
      </div>

      <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-select">
          <option value="open" <?= $task['status'] == 'open' ? 'selected' : '' ?>>Belum Selesai</option>
          <option value="completed" <?= $task['status'] == 'completed' ? 'selected' : '' ?>>Selesai</option>
        </select>
      </div>

      <div class="d-flex justify-content-between mt-4">
        <a href="list.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
