// script.js
document.addEventListener("DOMContentLoaded", () => {
  console.log("✅ Student Deadline Tracker loaded");

  // Navbar scroll shadow effect
  const navbar = document.querySelector(".navbar");
  if (navbar) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 10) {
        navbar.classList.add("navbar-shadow");
      } else {
        navbar.classList.remove("navbar-shadow");
      }
    });
  }

  // Form focus effect
  const inputs = document.querySelectorAll("input, select");
  inputs.forEach((input) => {
    input.addEventListener("focus", () => {
      input.classList.add("input-focus");
    });
    input.addEventListener("blur", () => {
      input.classList.remove("input-focus");
    });
  });

  // Auto hide alerts
  const alerts = document.querySelectorAll(".alert");
  alerts.forEach((alert) => {
    setTimeout(() => {
      alert.style.opacity = "0";
      setTimeout(() => alert.remove(), 500);
    }, 3500);
  });
});
