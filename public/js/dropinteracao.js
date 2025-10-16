document.addEventListener("DOMContentLoaded", function () {
  const dropdown = document.querySelectorAll(".nav-item.dropdown");

  dropdown.forEach((item) => {
    item.addEventListener("mouseover", () => {
      const toggle = item.querySelector('[data-bs-toggle="dropdown"]');
      const dropdownMenu = item.querySelector(".dropdown-menu");
      if (toggle && dropdownMenu) {
        const bsDropdown = bootstrap.Dropdown.getOrCreateInstance(toggle);
        bsDropdown.show();
      }
    });

    item.addEventListener("mouseleave", () => {
      const toggle = item.querySelector('[data-bs-toggle="dropdown"]');
      if (toggle) {
        const bsDropdown = bootstrap.Dropdown.getInstance(toggle);
        if (bsDropdown) bsDropdown.hide();
      }
    });
  });
});
