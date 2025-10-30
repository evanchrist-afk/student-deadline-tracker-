<?php
session_start();
include_once __DIR__ . '/../config/db.php';

// Pastikan user login dan role student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

$msg = '';
$student_id = $_SESSION['user_id'];

// Ambil ID tugas dari parameter URL
$task_id = isset($_GET['task_id']) ? (int)$_GET['task_id'] : 0;

// Cek apakah task_id valid dan ada di database
if ($task_id > 0) {
  $check_task = $conn->prepare("SELECT id FROM tasks WHERE id = ?");
  $check_task->bind_param("i", $task_id);
  $check_task->execute();
  $check_task->store_result();
  $task_exists = $check_task->num_rows > 0;
  $check_task->close();

  if (!$task_exists) {
    $msg = '<div class="alert alert-danger">❌ Tugas tidak ditemukan.</div>';
  }
} else {
  $msg = '<div class="alert alert-danger">⚠️ Tidak ada ID tugas yang dipilih.</div>';
}

// Proses upload file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $task_id > 0) {
  $target_dir = __DIR__ . "/../uploads/";
  if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
  }

  // Validasi file
  if (isset($_FILES["file"]) && $_FILES["file"]["error"] === UPLOAD_ERR_OK) {
    $file_name = time() . "_" . basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $file_name;
    $file_path = "/student_deadline_tracker/uploads/" . $file_name;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
      // Simpan ke database
      $stmt = $conn->prepare("
        INSERT INTO submissions (task_id, user_id, file_path, submitted_at)
        VALUES (?, ?, ?, NOW())
      ");
      $stmt->bind_param('iis', $task_id, $student_id, $file_path);

      if ($stmt->execute()) {
        $msg = '<div class="alert alert-success">✅ Tugas berhasil dikumpulkan!</div>';
      } else {
        $msg = '<div class="alert alert-danger">❌ Gagal menyimpan ke database.<br>' . $conn->error . '</div>';
      }

      $stmt->close();
    } else {
      $msg = '<div class="alert alert-danger">❌ Upload file gagal.</div>';
    }
  } else {
    $msg = '<div class="alert alert-warning">⚠️ Pilih file terlebih dahulu.</div>';
  }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="container mt-5">
  <div class="card p-4 shadow">
    <h4 class="mb-3 text-primary fw-bold">📤 Kumpulkan Tugas</h4>
    <?= $msg ?>

    <?php if ($task_id > 0): ?>
      <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Upload File Tugas</label>
          <input type="file" name="file" class="form-control" required>
        </div>
        <button class="btn btn-success w-100" type="submit">
          <i class="bi bi-upload me-2"></i>Kumpulkan
        </button>
      </form>
    <?php endif; ?>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
