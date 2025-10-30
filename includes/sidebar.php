<div class="col-md-3 col-lg-2 bg-dark text-white p-3 vh-100 sidebar">
  <h5 class="text-uppercase text-muted">Menu</h5>
  <ul class="nav flex-column">
    <?php if ($_SESSION['role'] === 'admin'): ?>
      <li class="nav-item"><a href="/student_deadline_tracker/dashboard/admin.php" class="nav-link text-white"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
      <li class="nav-item"><a href="/student_deadline_tracker/admin/users_list.php" class="nav-link text-white"><i class="bi bi-people me-2"></i>Kelola User</a></li>
    <?php elseif ($_SESSION['role'] === 'teacher'): ?>
      <li class="nav-item"><a href="/student_deadline_tracker/dashboard/teacher.php" class="nav-link text-white"><i class="bi bi-book me-2"></i>Dashboard</a></li>
      <li class="nav-item"><a href="/student_deadline_tracker/courses/list.php" class="nav-link text-white"><i class="bi bi-journal-text me-2"></i>Courses</a></li>
      <li class="nav-item"><a href="/student_deadline_tracker/tasks/list.php" class="nav-link text-white"><i class="bi bi-list-task me-2"></i>Tasks</a></li>
    <?php else: ?>
      <li class="nav-item"><a href="/student_deadline_tracker/dashboard/student.php" class="nav-link text-white"><i class="bi bi-house me-2"></i>Dashboard</a></li>
      <li class="nav-item"><a href="/student_deadline_tracker/submissions/list.php" class="nav-link text-white"><i class="bi bi-upload me-2"></i>Submissions</a></li>
    <?php endif; ?>
  </ul>
</div>
