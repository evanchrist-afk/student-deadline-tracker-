<?php
session_start();
include_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
  header('Location: /student_deadline_tracker/users/login.php');
  exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT t.*, c.name AS course_name,
        (SELECT COUNT(*) FROM submissions s WHERE s.task_id = t.id AND s.user_id = $user_id) AS submitted
        FROM tasks t
        JOIN courses c ON t.course_id = c.id
        ORDER BY t.due_date ASC";
$tasks = $conn->query($sql);

// Statistik singkat
$total_tasks = $tasks->num_rows;
$done = 0;
while ($row = $tasks->fetch_assoc()) {
  if ($row['submitted']) $done++;
}
$tasks->data_seek(0);
$pending = $total_tasks - $done;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Mahasiswa | Student Deadline Tracker</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #eef2ff, #e0e7ff);
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
    }

    header {
      background: #1e3a8a;
      color: white;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    header h2 {
      font-weight: 600;
      margin: 0;
    }

    .main {
      flex: 1;
      padding: 40px;
    }

    .stat-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: white;
      border-radius: 20px;
      padding: 25px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .stat-icon {
      background: #1e3a8a;
      color: white;
      font-size: 26px;
      border-radius: 12px;
      padding: 10px;
    }

    .stat-text h5 {
      font-size: 15px;
      color: #64748b;
      margin-bottom: 5px;
    }

    .stat-text h3 {
      margin: 0;
      font-weight: 700;
      color: #1e3a8a;
    }

    .table-card {
      background: white;
      border-radius: 20px;
      padding: 25px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }

    .btn-primary {
      background-color: #1e3a8a;
      border: none;
    }

    .btn-primary:hover {
      background-color: #172554;
    }

    table thead {
      background-color: #e0e7ff;
    }

    footer {
      background: #1e3a8a;
      color: white;
      text-align: center;
      padding: 10px;
      font-size: 14px;
    }
  </style>
</head>
<body>

  <header>
    <h2>🎓 Halo, <?= htmlspecialchars($_SESSION['username']) ?></h2>
    <a href="/student_deadline_tracker/users/logout.php" class="btn btn-light btn-sm">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
  </header>

  <div class="main">
    <div class="stat-cards">
      <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-journal-text"></i></div>
        <div class="stat-text">
          <h5>Total Tugas</h5>
          <h3><?= $total_tasks ?></h3>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-text">
          <h5>Sudah Dikumpul</h5>
          <h3><?= $done ?></h3>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-clock-history"></i></div>
        <div class="stat-text">
          <h5>Belum Dikumpul</h5>
          <h3><?= $pending ?></h3>
        </div>
      </div>
    </div>

    <div class="table-card">
      <h4 class="mb-3 fw-bold text-primary"><i class="bi bi-list-task me-2"></i>Daftar Tugas</h4>
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>Mata Kuliah</th>
            <th>Judul Tugas</th>
            <th>Deadline</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($tasks->num_rows): while ($t = $tasks->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($t['course_name']) ?></td>
            <td><?= htmlspecialchars($t['title']) ?></td>
            <td><?= date('d M Y', strtotime($t['due_date'])) ?></td>
            <td>
              <?= $t['submitted']
                ? '<span class="badge bg-success">Sudah Dikumpul</span>'
                : '<span class="badge bg-danger">Belum</span>'; ?>
            </td>
            <td>
              <?php if (!$t['submitted']): ?>
                <a href="/student_deadline_tracker/submissions/submit.php?task_id=<?= $t['id'] ?>" class="btn btn-primary btn-sm">Kumpulkan</a>
              <?php else: ?>
                <a href="/student_deadline_tracker/submissions/view.php?task_id=<?= $t['id'] ?>" class="btn btn-outline-secondary btn-sm">Lihat</a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="5" class="text-center text-muted">Belum ada tugas tersedia.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <footer>
    © <?= date('Y') ?> Student Deadline Tracker. Semua hak dilindungi.
  </footer>
</body>
</html>
