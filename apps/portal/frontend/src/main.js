// Import style.css to be processed by Vite
import "./index.css";

// Import Lucide icons locally for tree-shaking
import { 
  createIcons, 
  ShieldCheck, 
  Sparkles, 
  ArrowUpRight, 
  Building2, 
  Users, 
  Scale, 
  Eye, 
  Wallet, 
  Megaphone, 
  Handshake, 
  Activity, 
  TrendingUp, 
  CheckCircle2, 
  Send, 
  ChevronDown, 
  Loader2, 
  X, 
  Menu, 
  MapPin, 
  Mail, 
  Instagram, 
  Linkedin, 
  CheckCircle, 
  AlertCircle 
} from 'lucide';

document.addEventListener("DOMContentLoaded", () => {
  // Icon rendering wrapper
  const renderIcons = () => {
    createIcons({
      icons: {
        ShieldCheck, 
        Sparkles, 
        ArrowUpRight, 
        Building2, 
        Users, 
        Scale, 
        Eye, 
        Wallet, 
        Megaphone, 
        Handshake, 
        Activity, 
        TrendingUp, 
        CheckCircle2, 
        Send, 
        ChevronDown, 
        Loader2, 
        X, 
        Menu, 
        MapPin, 
        Mail, 
        Instagram, 
        Linkedin, 
        CheckCircle, 
        AlertCircle
      }
    });
  };

  // Initial render of icons
  renderIcons();

  // Set current year in footer
  const yearEl = document.getElementById("current-year");
  if (yearEl) {
    yearEl.textContent = new Date().getFullYear();
  }

  /* ---------------- HEADER SCROLL EFFECTS ---------------- */
  const header = document.getElementById("site-header");
  const container = document.getElementById("header-container");
  const brand = document.getElementById("header-brand");
  const nav = document.getElementById("header-nav");
  const desktopCta = document.querySelector("#header-container .btn-aspirasi-trigger");

  const checkScroll = () => {
    if (window.scrollY > 50) {
      header.classList.remove("bg-transparent", "border-transparent");
      header.classList.add("bg-[#02040A]/40", "backdrop-blur-lg", "border-white/[0.06]");
      
      container.classList.remove("h-[92px]");
      container.classList.add("h-[76px]");
    } else {
      header.classList.add("bg-transparent", "border-transparent");
      header.classList.remove("bg-[#02040A]/40", "backdrop-blur-lg", "border-white/[0.06]");
      
      container.classList.add("h-[92px]");
      container.classList.remove("h-[76px]");
    }
  };

  window.addEventListener("scroll", checkScroll, { passive: true });
  checkScroll(); // Initial run

  /* ---------------- MOBILE MENU TOGGLE ---------------- */
  const mobileMenuToggle = document.getElementById("mobile-menu-toggle");
  const mobileMenu = document.getElementById("mobile-menu");

  const closedClasses = ["max-h-0", "opacity-0", "pointer-events-none", "-translate-y-4"];
  const openClasses = ["max-h-[400px]", "opacity-100", "pointer-events-auto", "translate-y-0"];

  const toggleMenu = (forceClose = false) => {
    if (!mobileMenu || !mobileMenuToggle) return;
    const isClosed = mobileMenu.classList.contains("max-h-0");
    if (isClosed && !forceClose) {
      mobileMenu.classList.remove(...closedClasses);
      mobileMenu.classList.add(...openClasses);
      mobileMenuToggle.innerHTML = `<i data-lucide="x" class="h-5 w-5"></i>`;
    } else {
      mobileMenu.classList.remove(...openClasses);
      mobileMenu.classList.add(...closedClasses);
      mobileMenuToggle.innerHTML = `<i data-lucide="menu" class="h-5 w-5"></i>`;
    }
    renderIcons();
  };

  if (mobileMenuToggle && mobileMenu) {
    mobileMenuToggle.addEventListener("click", () => toggleMenu());

    // Auto-close menu when clicking mobile links
    const mobileLinks = mobileMenu.querySelectorAll("a");
    mobileLinks.forEach(link => {
      link.addEventListener("click", () => toggleMenu(true));
    });
  }

  /* ---------------- SCROLL SPY ACTIVE NAV HIGHLIGHT ---------------- */
  const navLinks = document.querySelectorAll("#header-nav a[href^='#']");
  const spySections = document.querySelectorAll("#home, #about, #cta");

  if (navLinks.length > 0 && spySections.length > 0) {
    const activeClasses = ["text-white", "bg-gradient-to-b", "from-amber-500/25", "to-amber-700/10", "shadow-[inset_0_0_0_1px_rgba(229,184,105,0.4)]"];
    const inactiveClasses = ["text-slate-300", "hover:text-white", "hover:bg-white/[0.05]"];

    const spyObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const id = entry.target.getAttribute("id");
          navLinks.forEach(link => {
            if (link.getAttribute("href") === `#${id}`) {
              link.classList.remove(...inactiveClasses);
              link.classList.add(...activeClasses);
            } else {
              link.classList.add(...inactiveClasses);
              link.classList.remove(...activeClasses);
            }
          });
        }
      });
    }, {
      root: null,
      rootMargin: "-30% 0px -50% 0px", // triggers when section dominates the viewport
      threshold: 0
    });

    spySections.forEach(sec => spyObserver.observe(sec));
  }

  /* ---------------- ASPIRASI MODAL DIALOG ---------------- */
  const modal = document.getElementById("aspirasi-modal");
  const modalInner = modal ? modal.querySelector(".relative") : null;
  const closeBtn = document.getElementById("modal-close");
  const triggers = document.querySelectorAll(".btn-aspirasi-trigger");

  const openModal = () => {
    if (!modal) return;
    modal.classList.remove("hidden");
    // Force reflow
    modal.offsetHeight;
    modal.classList.remove("opacity-0");
    modal.classList.add("opacity-100");
    if (modalInner) {
      modalInner.classList.remove("scale-95");
      modalInner.classList.add("scale-100");
    }
    
    // Close mobile menu if open
    toggleMenu(true);
  };

  const closeModal = () => {
    if (!modal) return;
    modal.classList.add("opacity-0");
    modal.classList.remove("opacity-100");
    if (modalInner) {
      modalInner.classList.add("scale-95");
      modalInner.classList.remove("scale-100");
    }
    setTimeout(() => {
      modal.classList.add("hidden");
    }, 300);
  };

  triggers.forEach(btn => btn.addEventListener("click", openModal));
  if (closeBtn) closeBtn.addEventListener("click", closeModal);
  
  if (modal) {
    modal.addEventListener("click", (e) => {
      if (e.target === modal) closeModal();
    });
  }

  /* ---------------- SCROLL ANIMATIONS (INTERSECTION OBSERVER) ---------------- */
  const animatedSections = document.querySelectorAll(".section-animate");
  const progressBars = document.querySelectorAll(".progress-bar");

  const observerOptions = {
    root: null,
    threshold: 0.12
  };

  const sectionObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.remove("opacity-0", "translate-y-6");
        entry.target.classList.add("opacity-100", "translate-y-0");
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  animatedSections.forEach(sec => sectionObserver.observe(sec));

  // Dashboard / Progress bars observer
  const dashboardSec = document.querySelector("[data-testid='pengawasan-preview']");
  if (dashboardSec) {
    const dashboardObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          progressBars.forEach(bar => {
            const w = bar.getAttribute("data-width");
            bar.style.transition = "width 1.2s cubic-bezier(0.4, 0, 0.2, 1)";
            bar.style.width = w;
          });
          observer.unobserve(entry.target);
        }
      });
    }, observerOptions);
    dashboardObserver.observe(dashboardSec);
  }

  /* ---------------- CUSTOM TOAST SYSTEM ---------------- */
  const toastContainer = document.getElementById("toast-container");

  const showToast = (title, message = "", type = "success") => {
    if (!toastContainer) return;

    const toast = document.createElement("div");
    toast.className = "flex gap-3 items-start w-full max-w-sm rounded-xl p-4 bg-[#0A101D]/92 border border-white/10 shadow-[0_8px_32px_rgba(0,0,0,0.6)] backdrop-blur-md pointer-events-auto transform translate-y-[-10px] opacity-0 transition-all duration-300";
    if (type === "success") {
      toast.style.borderColor = "rgba(198, 140, 72, 0.4)";
    } else {
      toast.style.borderColor = "rgba(239, 68, 68, 0.4)";
    }

    const iconColor = type === "success" ? "text-amber-400" : "text-red-500";
    const iconName = type === "success" ? "CheckCircle" : "AlertCircle";

    toast.innerHTML = `
      <div class="mt-0.5 ${iconColor}">
        <i data-lucide="${iconName}" class="h-5 w-5"></i>
      </div>
      <div class="flex-1 min-w-0">
        <h4 class="text-sm font-semibold text-white leading-tight">${title}</h4>
        ${message ? `<p class="text-xs text-slate-400 mt-1 leading-relaxed">${message}</p>` : ""}
      </div>
    `;

    toastContainer.appendChild(toast);
    renderIcons();

    // Trigger animation
    toast.offsetHeight; // force reflow
    toast.classList.remove("translate-y-[-10px]", "opacity-0");
    toast.classList.add("translate-y-0", "opacity-100");

    // Auto dismiss
    setTimeout(() => {
      toast.classList.remove("translate-y-0", "opacity-100");
      toast.classList.add("translate-y-[-10px]", "opacity-0");
      setTimeout(() => {
        toast.remove();
      }, 300);
    }, 4500);
  };

  /* ---------------- ASPIRASI FORM SUBMISSION ---------------- */
  const form = document.getElementById("aspirasi-form");
  const submitBtn = document.getElementById("aspirasi-submit");
  const submitSpinner = document.getElementById("submit-spinner");
  const submitIcon = document.getElementById("submit-icon");
  const submitText = document.getElementById("submit-text");

  if (form) {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      // Get values
      const nama = document.getElementById("aspirasi-nama").value.trim();
      const nim = document.getElementById("aspirasi-nim").value.trim();
      const fakultas = document.getElementById("aspirasi-fakultas").value.trim();
      const kategori = document.getElementById("aspirasi-kategori").value;
      const judul = document.getElementById("aspirasi-judul").value.trim();
      const isi = document.getElementById("aspirasi-isi").value.trim();

      // Frontend Validation
      if (!nama || !nim || !fakultas || !kategori || !judul || !isi) {
        showToast("Error", "Mohon lengkapi seluruh kolom aspirasi.", "error");
        return;
      }

      if (nama.length < 2 || nama.length > 120) {
        showToast("Error", "Nama harus di antara 2 dan 120 karakter.", "error");
        return;
      }

      if (nim.length < 3 || nim.length > 30) {
        showToast("Error", "NIM harus di antara 3 dan 30 karakter.", "error");
        return;
      }

      if (fakultas.length < 2 || fakultas.length > 120) {
        showToast("Error", "Fakultas harus di antara 2 dan 120 karakter.", "error");
        return;
      }

      if (judul.length < 3 || judul.length > 160) {
        showToast("Error", "Judul harus di antara 3 dan 160 karakter.", "error");
        return;
      }

      if (isi.length < 10 || isi.length > 4000) {
        showToast("Error", "Isi aspirasi minimal 10 karakter.", "error");
        return;
      }

      // Start Loading State
      submitBtn.disabled = true;
      if (submitSpinner) submitSpinner.classList.remove("hidden");
      if (submitIcon) submitIcon.classList.add("hidden");
      if (submitText) submitText.textContent = "Mengirim...";

      try {
        const response = await fetch("/api/aspirasi", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ nama, nim, fakultas, kategori, judul, isi })
        });

        const data = await response.json();

        if (response.status === 201) {
          // Success
          showToast(
            "Aspirasi berhasil dikirim!",
            "Terima kasih. Suara Anda akan ditindaklanjuti oleh DPM UB.",
            "success"
          );
          form.reset();
          closeModal();
        } else {
          // Error response
          const msg = data?.detail || "Gagal mengirim aspirasi. Silakan coba lagi.";
          showToast("Gagal mengirim aspirasi", msg, "error");
        }
      } catch (err) {
        console.error(err);
        showToast("Error", "Koneksi ke server gagal. Pastikan backend aktif.", "error");
      } finally {
        // Stop Loading State
        submitBtn.disabled = false;
        if (submitSpinner) submitSpinner.classList.add("hidden");
        if (submitIcon) submitIcon.classList.remove("hidden");
        if (submitText) submitText.textContent = "Kirim Aspirasi";
      }
    });
  }
});
