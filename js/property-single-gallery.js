/**
 * Property single gallery interactions.
 */
(function () {
  function formatCounter(value) {
    const safeValue = Math.max(0, Number(value) || 0);
    return String(safeValue).padStart(2, "0");
  }

  function initPropertyGallery(root) {
    if (!root || "1" === root.dataset.propertyGalleryInit) {
      return;
    }

    const thumbButtons = Array.from(
      root.querySelectorAll("[data-gallery-thumb]"),
    );
    const primaryFrame = root.querySelector('[data-gallery-frame="0"]');
    const secondaryFrame = root.querySelector('[data-gallery-frame="1"]');
    const prevButton = root.querySelector("[data-gallery-prev]");
    const nextButton = root.querySelector("[data-gallery-next]");
    const currentEl = root.querySelector("[data-gallery-current]");
    const totalEl = root.querySelector("[data-gallery-total]");

    if (!thumbButtons.length || !primaryFrame || !secondaryFrame) {
      return;
    }

    const images = thumbButtons
      .map((button) => ({
        full: button.dataset.galleryFull || "",
        alt: button.dataset.galleryAlt || "",
        srcset: button.dataset.gallerySrcset || "",
        sizes: button.dataset.gallerySizes || "",
      }))
      .filter((item) => item.full);

    if (!images.length) {
      return;
    }

    const state = {
      current: 0,
      total: images.length,
    };

    function nextIndex(value) {
      if (state.total <= 1) {
        return 0;
      }
      return (value + 1) % state.total;
    }

    function render() {
      const currentImage = images[state.current];
      const secondaryImage = images[nextIndex(state.current)] || currentImage;
      const canNavigate = state.total > 1;

      function applyImage(frame, image) {
        frame.src = image.full;
        frame.alt = image.alt;

        if (image.srcset) {
          frame.setAttribute("srcset", image.srcset);
          frame.setAttribute("sizes", image.sizes || "100vw");
        } else {
          frame.removeAttribute("srcset");
          frame.removeAttribute("sizes");
        }
      }

      applyImage(primaryFrame, currentImage);
      applyImage(secondaryFrame, secondaryImage);

      root.classList.toggle("is-single", !canNavigate);

      if (prevButton) {
        prevButton.disabled = !canNavigate;
      }
      if (nextButton) {
        nextButton.disabled = !canNavigate;
      }

      if (currentEl) {
        currentEl.textContent = formatCounter(state.current + 1);
      }
      if (totalEl) {
        totalEl.textContent = formatCounter(state.total);
      }

      thumbButtons.forEach((button, index) => {
        const isActive = index === state.current;
        button.classList.toggle("is-active", isActive);
        button.setAttribute("aria-pressed", isActive ? "true" : "false");
      });
    }

    function setCurrent(index) {
      const safeIndex = Math.max(0, Math.min(index, state.total - 1));
      state.current = safeIndex;
      render();
    }

    thumbButtons.forEach((button, index) => {
      button.addEventListener("click", () => {
        setCurrent(index);
      });
    });

    if (prevButton) {
      prevButton.addEventListener("click", () => {
        if (state.total <= 1) {
          return;
        }
        setCurrent((state.current - 1 + state.total) % state.total);
      });
    }

    if (nextButton) {
      nextButton.addEventListener("click", () => {
        if (state.total <= 1) {
          return;
        }
        setCurrent((state.current + 1) % state.total);
      });
    }

    root.dataset.propertyGalleryInit = "1";
    render();
  }

  function initPropertyGalleries() {
    const roots = document.querySelectorAll("[data-property-gallery]");
    roots.forEach(initPropertyGallery);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initPropertyGalleries);
  } else {
    initPropertyGalleries();
  }
})();
