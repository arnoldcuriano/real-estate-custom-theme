/**
 * About page sliders.
 */
(function () {
  const CAROUSEL_SELECTOR = "[data-about-carousel]";
  const SECTION_REVEAL_SELECTOR = ".about-team, .about-clients-section";

  function formatCounter(value) {
    const safeValue = Math.max(0, Number(value) || 0);
    return String(safeValue).padStart(2, "0");
  }

  function createPagedCarousel(root) {
    const viewport = root.querySelector("[data-about-carousel-viewport]");
    const track = root.querySelector("[data-about-carousel-track]");
    const slides = Array.from(
      root.querySelectorAll("[data-about-carousel-slide]"),
    );
    const prevButton = root.querySelector("[data-about-carousel-prev]");
    const nextButton = root.querySelector("[data-about-carousel-next]");
    const currentEl = root.querySelector("[data-about-carousel-current]");
    const totalEl = root.querySelector("[data-about-carousel-total]");

    if (!viewport || !track || slides.length === 0) {
      return null;
    }

    const state = {
      currentPage: 0,
      totalPages: 1,
      slidesPerPage: 1,
      pageOffset: 0,
      totalSlides: slides.length,
      resizeRaf: null,
    };

    function getSlidesPerPage() {
      if (window.matchMedia("(max-width: 680px)").matches) {
        return 1;
      }

      return 2;
    }

    function recalculatePageOffset() {
      const firstSlide = slides[0];
      if (!firstSlide) {
        state.pageOffset = 0;
        return;
      }

      const trackStyle = window.getComputedStyle(track);
      const gapSize =
        parseFloat(trackStyle.columnGap || "") ||
        parseFloat(trackStyle.gap || "") ||
        0;
      const slideWidth = firstSlide.getBoundingClientRect().width;
      state.pageOffset = (slideWidth + gapSize) * state.slidesPerPage;
    }

    function canNavigate() {
      return state.totalPages > 1;
    }

    function render() {
      const offset = -(state.currentPage * state.pageOffset);
      track.style.transform = `translate3d(${offset}px, 0, 0)`;

      if (currentEl) {
        currentEl.textContent = formatCounter(state.currentPage + 1);
      }

      if (totalEl) {
        totalEl.textContent = formatCounter(state.totalPages);
      }

      const isStatic = !canNavigate();
      root.classList.toggle("is-static", isStatic);
      root.classList.toggle("is-interactive", !isStatic);

      if (prevButton) {
        prevButton.disabled = isStatic;
        prevButton.classList.toggle("is-muted", isStatic);
      }

      if (nextButton) {
        nextButton.disabled = isStatic;
        nextButton.classList.toggle("is-muted", isStatic);
      }
    }

    function recalculate() {
      state.slidesPerPage = getSlidesPerPage();
      state.totalPages = Math.max(
        1,
        Math.ceil(state.totalSlides / Math.max(1, state.slidesPerPage)),
      );
      state.currentPage = Math.min(
        Math.max(0, state.currentPage),
        state.totalPages - 1,
      );

      recalculatePageOffset();
      render();
    }

    function next() {
      if (!canNavigate()) {
        return;
      }

      state.currentPage = (state.currentPage + 1) % state.totalPages;
      render();
    }

    function prev() {
      if (!canNavigate()) {
        return;
      }

      state.currentPage =
        (state.currentPage - 1 + state.totalPages) % state.totalPages;
      render();
    }

    function handleResize() {
      if (state.resizeRaf) {
        window.cancelAnimationFrame(state.resizeRaf);
      }
      state.resizeRaf = window.requestAnimationFrame(() => {
        recalculate();
      });
    }

    if (prevButton) {
      prevButton.addEventListener("click", prev);
    }
    if (nextButton) {
      nextButton.addEventListener("click", next);
    }

    window.addEventListener("resize", handleResize);
    window.addEventListener("orientationchange", handleResize);

    recalculate();

    return {
      destroy() {
        window.removeEventListener("resize", handleResize);
        window.removeEventListener("orientationchange", handleResize);
        if (prevButton) {
          prevButton.removeEventListener("click", prev);
        }
        if (nextButton) {
          nextButton.removeEventListener("click", next);
        }
        if (state.resizeRaf) {
          window.cancelAnimationFrame(state.resizeRaf);
        }
      },
    };
  }

  function initAboutCarousels() {
    const roots = document.querySelectorAll(CAROUSEL_SELECTOR);
    roots.forEach((root) => {
      if ("1" === root.dataset.aboutCarouselInit) {
        return;
      }

      const controller = createPagedCarousel(root);
      if (!controller) {
        return;
      }

      root.dataset.aboutCarouselInit = "1";
      root.__aboutCarouselController = controller;
    });
  }

  function initAboutSectionReveal() {
    const targets = Array.from(
      document.querySelectorAll(SECTION_REVEAL_SELECTOR),
    ).filter((target) => "1" !== target.dataset.aboutRevealInit);

    if (targets.length === 0) {
      return;
    }

    targets.forEach((target) => {
      target.dataset.aboutRevealInit = "1";
      target.classList.add("scroll-animate--about");
    });

    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    if (prefersReducedMotion || !("IntersectionObserver" in window)) {
      targets.forEach((target) => {
        target.classList.add("is-visible");
      });
      return;
    }

    const observer = new IntersectionObserver(
      (entries, io) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) {
            return;
          }

          entry.target.classList.add("is-visible");
          io.unobserve(entry.target);
        });
      },
      {
        threshold: 0.08,
        rootMargin: "0px 0px 14% 0px",
      },
    );

    targets.forEach((target) => {
      const rect = target.getBoundingClientRect();
      if (rect.top < window.innerHeight * 0.94) {
        target.classList.add("is-visible");
        return;
      }

      observer.observe(target);
    });
  }

  function initAboutPageModules() {
    initAboutSectionReveal();
    initAboutCarousels();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAboutPageModules);
  } else {
    initAboutPageModules();
  }

  window.addEventListener("load", initAboutPageModules);
})();
