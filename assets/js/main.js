document.addEventListener("DOMContentLoaded", function () {
  const alerts = document.querySelectorAll(".alert");
  alerts.forEach((alert) => {
    setTimeout(() => {
      alert.style.opacity = "0";
      alert.style.transition = "opacity 0.5s";
      setTimeout(() => alert.remove(), 500);
    }, 5000);
  });

  const deleteButtons = document.querySelectorAll(".btn-delete");
  deleteButtons.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      if (!confirm("Are you sure you want to delete this item?")) {
        e.preventDefault();
      }
    });
  });

  const forms = document.querySelectorAll("form");
  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      const requiredFields = form.querySelectorAll("[required]");
      let isValid = true;

      requiredFields.forEach((field) => {
        if (!field.value.trim()) {
          field.style.borderColor = "var(--danger)";
          isValid = false;
        } else {
          field.style.borderColor = "var(--border)";
        }
      });

      if (!isValid) {
        e.preventDefault();
        alert("Please fill in all required fields.");
      }
    });
  });

  const tables = document.querySelectorAll(".table");
  tables.forEach((table) => {
    const wrapper = document.createElement("div");
    wrapper.style.overflowX = "auto";
    table.parentNode.insertBefore(wrapper, table);
    wrapper.appendChild(table);
  });

  console.log("School Complaint System initialized");
});
