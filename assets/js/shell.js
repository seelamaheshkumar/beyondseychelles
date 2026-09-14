/* ==========================================================================
   BEYOND design — shared app shell (sidebar + topbar)
   Injects the same nav on every page and highlights the active item so the
   whole UI stays in sync from one place while these stay static HTML pages.
   ========================================================================== */
(function () {
  const NAV = [
    { group: "Overview", items: [
      { key: "dashboard", label: "Dashboard", icon: "bi-grid-1x2", href: "../dashboard/index.php" },
    ]},
    { group: "Workflow", items: [
      { key: "quotations", label: "Quotations", icon: "bi-file-earmark-text", href: "../quotations/index.php" },
      { key: "jobs", label: "Jobs", icon: "bi-briefcase", href: "../jobs/index.php" },
      { key: "pending", label: "Pending", icon: "bi-hourglass-split", href: "../pjobs/index.php" },
      { key: "delivered", label: "Delivered", icon: "bi-check2-circle", href: "../djobs/index.php" },
    ]},
    { group: "Accounts", items: [
      { key: "credit-bills", label: "Credit Bills", icon: "bi-receipt", href: "../cjobs/index.php" },
    ]},
    { group: "Reports", items: [
      { key: "report-quotations", label: "Quotations Report", icon: "bi-file-text", href: "../rpt_quotations/index.php" },
      { key: "report-jobs", label: "Jobs Report", icon: "bi-briefcase", href: "../rpt_jobs/index.php" },
      { key: "report-cashflow", label: "Day Wise Cash Flow", icon: "bi-cash-stack", href: "../rpt_cashflow/index.php" },
    ]},
    { group: "Admin", items: [
      { key: "users", label: "Users", icon: "bi-people", href: "../users/index.php" },
    ]},
  ];

  function initials(name) {
    return name.split(" ").map(w => w[0]).slice(0, 2).join("").toUpperCase();
  }

  window.BD = {
    mount(active, opts) {
      opts = opts || {};
      const user = opts.user || { name: "Arjun Rao", role: "Admin" };

      const navHtml = NAV.map(section => `
        <div class="nav-section-label">${section.group}</div>
        ${section.items.map(item => `
          <a href="${item.href}" class="nav-link ${item.key === active ? "active" : ""}">
            <i class="bi ${item.icon}"></i><span>${item.label}</span>
          </a>
        `).join("")}
      `).join("");

      const sidebar = `
        <aside class="bd-sidebar" id="bdSidebar">
          <div class="brand">
            <img src="../assets/img/logo.png" alt="BEYOND design">
          </div>
          <nav class="bd-nav">${navHtml}</nav>
          <div class="sidebar-foot d-flex align-items-center justify-content-between">
            <span>Beyond Design &copy; 2026</span>
            <span class="cmyk-dots"><span></span><span></span><span></span><span></span></span>
          </div>
        </aside>`;

      const topbar = `
        <header class="bd-topbar">
          <div class="d-flex align-items-center gap-3">
            <button class="btn btn-bd-outline btn-sm d-lg-none" id="bdSidebarToggle" type="button" aria-label="Toggle menu">
              <i class="bi bi-list"></i>
            </button>
            <div class="search-box">
              <i class="bi bi-search"></i>
              <input type="search" class="form-control" placeholder="Search jobs, clients, invoices…">
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <button class="icon-btn" type="button" aria-label="Notifications">
              <i class="bi bi-bell"></i><span class="dot"></span>
            </button>
            <div class="dropdown">
              <button class="d-flex align-items-center gap-2 btn p-0 border-0 bg-transparent" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar">${initials(user.name)}</div>
                <div class="d-none d-md-block text-start">
                  <div class="fw-semibold" style="font-size:13px; line-height:1.1;">${user.name}</div>
                  <div class="text-muted" style="font-size:11.5px;">${user.role}</div>
                </div>
                <i class="bi bi-chevron-down text-muted small"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li><a class="dropdown-item" href="../profile/index.php"><i class="bi bi-person me-2"></i>My profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="../logout/index.php"><i class="bi bi-box-arrow-right me-2"></i>Sign out</a></li>
              </ul>
            </div>
          </div>
        </header>`;

      document.body.insertAdjacentHTML("afterbegin", sidebar);
      const main = document.getElementById("bdMain");
      if (main) main.insertAdjacentHTML("afterbegin", topbar);

      const toggle = document.getElementById("bdSidebarToggle");
      const sidebarEl = document.getElementById("bdSidebar");
      if (toggle && sidebarEl) {
        toggle.addEventListener("click", () => sidebarEl.classList.toggle("show"));
      }
    }
  };
})();
