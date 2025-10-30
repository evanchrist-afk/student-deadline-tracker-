<?php
session_start();
include_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

// ✅ Tandai tugas selesai
if (isset($_GET['done']) && is_numeric($_GET['done'])) {
  $id = intval($_GET['done']);
  $stmt = $conn->prepare("UPDATE tasks SET status='completed' WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  header('Location: list.php?success=done');
  exit;
}

// ✅ Filter & Search
$search = trim($_GET['search'] ?? '');
$course_filter = $_GET['course_id'] ?? '';

$where = "1=1";
$params = [];
$types = '';

if ($search) {
  $where .= " AND (t.title LIKE CONCAT('%', ?, '%') OR t.description LIKE CONCAT('%', ?, '%'))";
  $params[] = $search;
  $params[] = $search;
  $types .= 'ss';
}
if ($course_filter) {
  $where .= " AND t.course_id = ?";
  $params[] = $course_filter;
  $types .= 'i';
}

$sql = "SELECT t.*, c.name AS course_name 
        FROM tasks t
        JOIN courses c ON t.course_id = c.id
        WHERE $where
        ORDER BY t.due_date ASC";

$stmt = $conn->prepare($sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

$courses = $conn->query("SELECT id, name FROM courses ORDER BY name ASC");

// Hitung progress
$total = $conn->query("SELECT COUNT(*) AS total FROM tasks")->fetch_assoc()['total'];
$done = $conn->query("SELECT COUNT(*) AS done FROM tasks WHERE status='completed'")->fetch_assoc()['done'];
$progress = $total ? round(($done / $total) * 100, 1) : 0;

include_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold text-primary"><i class="bi bi-list-check me-2"></i>Daftar Tugas</h3>
    <a href="create.php" class="btn btn-success shadow-sm"><i class="bi bi-plus-circle me-2"></i>Tambah Tugas</a>
  </div>

  <!-- 📊 Progress Bar -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h6 class="fw-semibold text-muted mb-2">Progress Penyelesaian Tugas:</h6>
      <div class="progress" style="height: 25px;">
        <div class="progress-bar <?= $progress == 100 ? 'bg-success' : 'bg-info' ?> progress-bar-striped progress-bar-animated"
             role="progressbar" style="width: <?= $progress ?>%;" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100">
          <?= $progress ?>%
        </div>
      </div>
    </div>
  </div>

  <!-- 🔍 Filter & Search -->
  <form method="get" class="row mb-4">
    <div class="col-md-4">
      <input type="text" name="search" class="form-control shadow-sm" placeholder="Cari tugas..." value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-md-4">
      <select name="course_id" class="form-select shadow-sm">
        <option value="">Semua Mata Kuliah</option>
        <?php while ($c = $courses->fetch_assoc()): ?>
          <option value="<?= $c['id'] ?>" <?= $course_filter == $c['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['name']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-4 text-end">
      <button class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
      <a href="list.php" class="btn btn-secondary"><i class="bi bi-arrow-repeat"></i> Reset</a>
    </div>
  </form>

  <!-- 📋 Tabel Tugas -->
  <div class="table-responsive">
    <table class="table table-bordered align-middle shadow-sm">
      <thead class="table-primary text-center">
        <tr>
          <th>#</th>
          <th>Judul</th>
          <th>Mata Kuliah</th>
          <th>Deadline</th>
          <th>Prioritas</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($res->num_rows === 0): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada tugas ditemukan.</td></tr>
        <?php else:
          $no = 1;
          while ($row = $res->fetch_assoc()):
            $due = new DateTime($row['due_date']);
            $today = new DateTime();
            $diff = $today->diff($due)->days;
            $isNear = ($due > $today && $diff <= 2);
            $isOver = ($due < $today);

            $rowClass = $isNear ? 'table-warning' : ($isOver ? 'table-danger' : '');
        ?>
          <tr class="<?= $rowClass ?>">
            <td class="text-center fw-semibold"><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['course_name']) ?></td>
            <td class="text-center"><?= htmlspecialchars($row['due_date']) ?></td>
            <td class="text-center">
              <span class="badge bg-<?= 
                $row['priority'] == 'high' ? 'danger' :
                ($row['priority'] == 'medium' ? 'warning text-dark' : 'secondary')
              ?>"><?= htmlspecialchars($row['priority']) ?></span>
            </td>
            <td class="text-center">
              <span class="badge bg-<?= $row['status'] == 'completed' ? 'success' : 'secondary' ?>">
                <?= htmlspecialchars($row['status']) ?>
              </span>
            </td>
            <td class="text-center">
              <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
              <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger me-1"
                 onclick="return confirm('Yakin hapus tugas ini?')"><i class="bi bi-trash"></i></a>
              <?php if ($row['status'] !== 'completed'): ?>
                <a href="list.php?done=<?= $row['id'] ?>" class="btn btn-sm btn-outline-success">
                  <i class="bi bi-check2-circle"></i> Selesai
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
