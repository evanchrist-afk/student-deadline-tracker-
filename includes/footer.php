  </div> <!-- end row -->
</div> <!-- end container-fluid -->

<footer class="text-center mt-4 mb-2 text-muted">
  <small>&copy; <?= date('Y') ?> Student Deadline Tracker — All rights reserved.</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/student_deadline_tracker/assets/js/script.js"></script>

</body>
<script src="/student_deadline_tracker/assets/js/animations.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- 🌙 Dark Mode + Toast Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  // === DARK MODE TOGGLE ===
  const toggle = document.createElement('button');
  toggle.className = 'btn btn-outline-light position-fixed bottom-0 end-0 m-4 rounded-circle shadow';
  toggle.innerHTML = '<i class="bi bi-moon-stars-fill fs-4"></i>';
  toggle.style.zIndex = 1000;
  document.body.appendChild(toggle);

  // Load saved preference
  if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-mode');
  }

  toggle.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('theme', 
      document.body.classList.contains('dark-mode') ? 'dark' : 'light'
    );
  });

  // === TOAST HANDLER ===
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('success') === 'done') {
    const toastHTML = `
      <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
        <div id="taskToast" class="toast align-items-center text-bg-success border-0 shadow" role="alert">
          <div class="d-flex">
            <div class="toast-body fw-semibold">
              ✅ Tugas berhasil ditandai selesai!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
          </div>
        </div>
      </div>`;
    document.body.insertAdjacentHTML('beforeend', toastHTML);
    const toast = new bootstrap.Toast(document.getElementById('taskToast'));
    toast.show();
  }
});
</script>

</html>
