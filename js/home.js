/**
 * Homepage quick-links loop interactions.
 */
(function () {
  const LOOP_SELECTOR = "[data-quick-links-loop]";
  const ITEM_SELECTOR = ".quick-links__item:not(.quick-links__item--clone)";
  let isAlpineRegistered = false;

  function makeLoopController(rootEl, trackEl) {
    return {
      rootEl,
      trackEl,
      viewportEl: rootEl ? rootEl.querySelector(".quick-links__viewport") : null,
      activeIndex: 0,
      frameId: null,
      lastTimestamp: null,
      offset: 0,
      speed: 62,
      loopWidth: 0,
      isPaused: false,
      resizeHandler: null,
      cleanupFns: [],

      getItems() {
        return Array.from(this.trackEl.querySelectorAll(ITEM_SELECTOR));
      },

      markActive(index) {
        this.activeIndex = index;
        this.getItems().forEach((item, itemIndex) => {
          item.classList.toggle("is-active", itemIndex === index);
        });
      },

      prepareTrack() {
        if ("1" === this.trackEl.dataset.loopPrepared) {
          return;
        }

        const sourceItems = this.getItems();
        sourceItems.forEach((item) => {
          const clone = item.cloneNode(true);
          clone.classList.add("quick-links__item--clone");
          clone.setAttribute("aria-hidden", "true");
          clone.setAttribute("tabindex", "-1");

          Array.from(clone.attributes).forEach((attribute) => {
            if (
              attribute.name.startsWith("@") ||
              attribute.name.startsWith(":") ||
              attribute.name.startsWith("x-")
            ) {
              clone.removeAttribute(attribute.name);
            }
          });

          clone
            .querySelectorAll("a, button, input, select, textarea")
            .forEach((focusable) => {
              focusable.setAttribute("tabindex", "-1");
            });

          this.trackEl.appendChild(clone);
        });

        this.trackEl.dataset.loopPrepared = "1";
      },

      recalculate() {
        const width = this.trackEl.scrollWidth / 2;

        this.loopWidth = width > 0 ? width : 0;
        this.offset = 0;
        this.lastTimestamp = null;
        this.trackEl.style.transform = "translate3d(0, 0, 0)";
      },

      start() {
        if (this.frameId) {
          window.cancelAnimationFrame(this.frameId);
        }

        this.frameId = window.requestAnimationFrame((timestamp) =>
          this.animate(timestamp),
        );
      },

      animate(timestamp) {
        if ("number" !== typeof timestamp) {
          timestamp = window.performance.now();
        }

        if (this.loopWidth <= 0) {
          this.recalculate();
          if (this.loopWidth <= 0) {
            this.frameId = window.requestAnimationFrame((nextTimestamp) =>
              this.animate(nextTimestamp),
            );
            return;
          }
        }

        if (null === this.lastTimestamp) {
          this.lastTimestamp = timestamp;
        }

        const deltaSeconds = Math.min(
          (timestamp - this.lastTimestamp) / 1000,
          0.05,
        );
        this.lastTimestamp = timestamp;

        if (!this.isPaused) {
          this.offset -= this.speed * deltaSeconds;
          if (this.offset <= -this.loopWidth) {
            this.offset += this.loopWidth;
          }
          this.trackEl.style.transform = `translate3d(${this.offset}px, 0, 0)`;
        }

        this.frameId = window.requestAnimationFrame((nextTimestamp) =>
          this.animate(nextTimestamp),
        );
      },

      pause() {
        this.isPaused = true;
      },

      resume() {
        this.isPaused = false;
      },

      bindViewportPause() {
        if (!this.viewportEl) {
          return;
        }

        const onEnter = () => {
          this.pause();
        };
        const onLeave = () => {
          this.resume();
        };

        this.viewportEl.addEventListener("mouseenter", onEnter);
        this.viewportEl.addEventListener("mouseleave", onLeave);
        this.viewportEl.addEventListener("focusin", onEnter);
        this.viewportEl.addEventListener("focusout", onLeave);

        this.cleanupFns.push(() => {
          this.viewportEl.removeEventListener("mouseenter", onEnter);
          this.viewportEl.removeEventListener("mouseleave", onLeave);
          this.viewportEl.removeEventListener("focusin", onEnter);
          this.viewportEl.removeEventListener("focusout", onLeave);
        });
      },

      bindManualHover() {
        this.getItems().forEach((item, index) => {
          const onEnter = () => {
            this.markActive(index);
          };

          item.addEventListener("mouseenter", onEnter);
          item.addEventListener("focusin", onEnter);

          this.cleanupFns.push(() => {
            item.removeEventListener("mouseenter", onEnter);
            item.removeEventListener("focusin", onEnter);
          });
        });
      },

      init(options = {}) {
        if (!this.rootEl || !this.trackEl) {
          return false;
        }

        this.prepareTrack();
        this.rootEl.dataset.loopInit = "1";
        this.markActive(this.activeIndex);
        this.bindViewportPause();

        window.requestAnimationFrame(() => {
          this.recalculate();
          this.start();
        });

        if (options.manualHover) {
          this.bindManualHover();
        }

        this.resizeHandler = () => {
          this.recalculate();
        };
        window.addEventListener("resize", this.resizeHandler);

        return true;
      },

      destroy() {
        if (this.frameId) {
          window.cancelAnimationFrame(this.frameId);
          this.frameId = null;
        }

        if (this.resizeHandler) {
          window.removeEventListener("resize", this.resizeHandler);
          this.resizeHandler = null;
        }

        this.cleanupFns.forEach((cleanup) => cleanup());
        this.cleanupFns = [];
      },
    };
  }

  function quickLinksLoop() {
    return {
      activeIndex: 0,
      controller: null,

      init() {
        if (this.$root && this.$root.__quickLinksFallbackController) {
          this.controller = this.$root.__quickLinksFallbackController;
          this.$root.__quickLinksFallbackController = null;
          this.controller.markActive(this.activeIndex);
          return;
        }

        if (!this.$refs.track) {
          return;
        }

        this.controller = makeLoopController(this.$root, this.$refs.track);
        this.controller.activeIndex = this.activeIndex;
        this.controller.init();
      },

      setActive(index) {
        this.activeIndex = index;
        if (this.controller) {
          this.controller.markActive(index);
        }
      },

      pause() {
        if (this.controller) {
          this.controller.pause();
        }
      },

      resume() {
        if (this.controller) {
          this.controller.resume();
        }
      },

      destroy() {
        if (this.controller) {
          this.controller.destroy();
        }
      },
    };
  }

  function registerAlpineComponent() {
    if (
      isAlpineRegistered ||
      !window.Alpine ||
      "function" !== typeof window.Alpine.data
    ) {
      return;
    }

    window.Alpine.data("quickLinksLoop", quickLinksLoop);
    isAlpineRegistered = true;
  }

  function initFallbackLoops() {
    const sections = document.querySelectorAll(LOOP_SELECTOR);

    sections.forEach((section) => {
      if (
        "1" === section.dataset.loopInit ||
        section.__quickLinksFallbackController ||
        section._x_dataStack
      ) {
        return;
      }

      const track = section.querySelector(".quick-links__track");
      if (!track) {
        return;
      }

      const controller = makeLoopController(section, track);
      const isInitialized = controller.init({ manualHover: true });

      if (isInitialized) {
        section.__quickLinksFallbackController = controller;
      }
    });
  }

  window.quickLinksLoop = quickLinksLoop;

  document.addEventListener("alpine:init", registerAlpineComponent);
  registerAlpineComponent();

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initFallbackLoops);
  } else {
    initFallbackLoops();
  }

  window.addEventListener("load", initFallbackLoops);
})();
// End of homepage quick-links loop interactions.

/**
 * Homepage card carousel interactions (featured properties + testimonials).
 */
(function () {
  const CAROUSEL_CONFIGS = [
    {
      id: "featured",
      rootSelector: "[data-featured-carousel]",
      viewportSelector: ".featured-properties__viewport",
      trackSelector: ".featured-properties__track",
      slideSelector:
        ".featured-properties__slide:not(.featured-properties__slide--clone)",
      anySlideSelector: ".featured-properties__slide",
      cloneClass: "featured-properties__slide--clone",
      prevSelector: "[data-featured-prev]",
      nextSelector: "[data-featured-next]",
      currentSelector: "[data-featured-current]",
      totalSelector: "[data-featured-total]",
      alpineName: "featuredPropertiesCarousel",
      globalName: "featuredPropertiesCarousel",
      fallbackKey: "__featuredCarouselFallbackController",
      initFlag: "featuredCarouselInit",
    },
    {
      id: "testimonials",
      rootSelector: "[data-testimonials-carousel]",
      viewportSelector: ".testimonials__viewport",
      trackSelector: ".testimonials__track",
      slideSelector:
        ".testimonials__slide:not(.testimonials__slide--clone)",
      anySlideSelector: ".testimonials__slide",
      cloneClass: "testimonials__slide--clone",
      prevSelector: "[data-testimonials-prev]",
      nextSelector: "[data-testimonials-next]",
      currentSelector: "[data-testimonials-current]",
      totalSelector: "[data-testimonials-total]",
      alpineName: "testimonialsCarousel",
      globalName: "testimonialsCarousel",
      fallbackKey: "__testimonialsCarouselFallbackController",
      initFlag: "testimonialsCarouselInit",
    },
    {
      id: "faqs",
      rootSelector: "[data-faq-carousel]",
      viewportSelector: ".faqs__viewport",
      trackSelector: ".faqs__track",
      slideSelector: ".faqs__slide:not(.faqs__slide--clone)",
      anySlideSelector: ".faqs__slide",
      cloneClass: "faqs__slide--clone",
      prevSelector: "[data-faq-prev]",
      nextSelector: "[data-faq-next]",
      currentSelector: "[data-faq-current]",
      totalSelector: "[data-faq-total]",
      alpineName: "faqCarousel",
      globalName: "faqCarousel",
      fallbackKey: "__faqCarouselFallbackController",
      initFlag: "faqCarouselInit",
    },
  ];
  let isAlpineRegistered = false;

  function formatCounter(value) {
    const safeValue = Math.max(0, Number(value) || 0);
    return String(safeValue).padStart(2, "0");
  }

  function makeCardCarouselController(config, rootEl, trackEl, onStateChange) {
    return {
      rootEl,
      trackEl,
      viewportEl: rootEl ? rootEl.querySelector(config.viewportSelector) : null,
      onStateChange,
      total: 0,
      currentIndex: 0,
      visibleCount: 1,
      stepSize: 0,
      gapSize: 0,
      autoIntervalMs: 4000,
      autoTimerId: null,
      paused: false,
      canAuto: false,
      canManual: false,
      isStatic: true,
      isInteractive: false,
      transitionHandler: null,
      resizeHandler: null,
      visibilityHandler: null,
      cleanupFns: [],

      getOriginalSlides() {
        return Array.from(this.trackEl.querySelectorAll(config.slideSelector));
      },

      computeCapabilities() {
        this.canAuto = this.total > 9;
        this.canManual = this.total >= 5;
        this.isStatic = this.total <= 4;
        this.isInteractive = this.canAuto || this.canManual;
      },

      getLogicalIndex() {
        if (this.total <= 0) {
          return 0;
        }

        if (!this.isInteractive) {
          return this.total > 0 ? 1 : 0;
        }

        const baseIndex = this.currentIndex - this.visibleCount;
        const normalizedIndex = ((baseIndex % this.total) + this.total) % this.total;
        return normalizedIndex + 1;
      },

      emitState() {
        const state = {
          current: this.getLogicalIndex(),
          total: this.total,
          canManual: this.canManual,
          canAuto: this.canAuto,
          isStatic: this.isStatic,
          isInteractive: this.isInteractive,
        };

        if ("function" === typeof this.onStateChange) {
          this.onStateChange(state);
        }

        const currentTextEl = this.rootEl.querySelector(config.currentSelector);
        const totalTextEl = this.rootEl.querySelector(config.totalSelector);
        const prevButton = this.rootEl.querySelector(config.prevSelector);
        const nextButton = this.rootEl.querySelector(config.nextSelector);

        if (currentTextEl) {
          currentTextEl.textContent = formatCounter(state.current || 0);
        }

        if (totalTextEl) {
          totalTextEl.textContent = formatCounter(state.total);
        }

        if (prevButton) {
          prevButton.disabled = !state.canManual;
          prevButton.classList.toggle("is-muted", !state.canManual);
        }

        if (nextButton) {
          nextButton.disabled = !state.canManual;
          nextButton.classList.toggle("is-muted", !state.canManual);
        }

        this.rootEl.classList.toggle("is-static", state.isStatic);
        this.rootEl.classList.toggle("is-interactive", state.isInteractive);
      },

      removeClones() {
        this.trackEl
          .querySelectorAll(`.${config.cloneClass}`)
          .forEach((clone) => clone.remove());
        delete this.trackEl.dataset.carouselPrepared;
        delete this.trackEl.dataset.visibleCount;
      },

      cloneSlide(slide) {
        const clone = slide.cloneNode(true);
        clone.classList.add(config.cloneClass);
        clone.setAttribute("aria-hidden", "true");
        clone.setAttribute("tabindex", "-1");

        clone
          .querySelectorAll("a, button, input, select, textarea")
          .forEach((focusable) => {
            focusable.setAttribute("tabindex", "-1");
            focusable.setAttribute("aria-hidden", "true");
          });

        return clone;
      },

      setTransition(isEnabled) {
        this.trackEl.style.transition = isEnabled
          ? "transform 420ms cubic-bezier(0.22, 1, 0.36, 1)"
          : "none";
      },

      calculateDimensions() {
        const sampleSlide =
          this.getOriginalSlides()[0] ||
          this.trackEl.querySelector(config.anySlideSelector);

        if (!sampleSlide) {
          this.stepSize = 0;
          this.gapSize = 0;
          return;
        }

        const trackStyle = window.getComputedStyle(this.trackEl);
        this.gapSize =
          parseFloat(trackStyle.columnGap || "") ||
          parseFloat(trackStyle.gap || "") ||
          0;
        this.stepSize = sampleSlide.getBoundingClientRect().width + this.gapSize;
      },

      calculateVisibleCount() {
        if (!this.viewportEl || this.stepSize <= 0) {
          return 1;
        }

        const viewportWidth = this.viewportEl.getBoundingClientRect().width;
        const estimatedVisible = Math.round(
          (viewportWidth + this.gapSize) / this.stepSize,
        );

        return Math.max(1, Math.min(this.total, estimatedVisible || 1));
      },

      prepareTrack() {
        if (!this.isInteractive) {
          this.removeClones();
          return;
        }

        const preparedForVisible = Number(this.trackEl.dataset.visibleCount || "0");
        if ("1" === this.trackEl.dataset.carouselPrepared && preparedForVisible === this.visibleCount) {
          return;
        }

        this.removeClones();

        const originals = this.getOriginalSlides();
        if (originals.length < 2) {
          return;
        }

        const prependSlides = originals.slice(-this.visibleCount);
        const appendSlides = originals.slice(0, this.visibleCount);

        prependSlides.forEach((slide) => {
          const clone = this.cloneSlide(slide);
          this.trackEl.insertBefore(clone, this.trackEl.firstChild);
        });

        appendSlides.forEach((slide) => {
          this.trackEl.appendChild(this.cloneSlide(slide));
        });

        this.trackEl.dataset.carouselPrepared = "1";
        this.trackEl.dataset.visibleCount = String(this.visibleCount);
      },

      applyTransform() {
        if (this.stepSize <= 0 || !this.isInteractive) {
          this.trackEl.style.transform = "translate3d(0, 0, 0)";
          return;
        }

        const offset = -this.currentIndex * this.stepSize;
        this.trackEl.style.transform = `translate3d(${offset}px, 0, 0)`;
      },

      normalizeAfterTransition() {
        if (!this.isInteractive) {
          this.emitState();
          return;
        }

        let requiresJump = false;

        if (this.currentIndex < this.visibleCount) {
          this.currentIndex += this.total;
          requiresJump = true;
        } else if (this.currentIndex >= this.total + this.visibleCount) {
          this.currentIndex -= this.total;
          requiresJump = true;
        }

        if (requiresJump) {
          this.setTransition(false);
          this.applyTransform();
          this.trackEl.getBoundingClientRect();
          this.setTransition(true);
        }

        this.emitState();
      },

      next() {
        if (!this.canManual) {
          return;
        }

        this.currentIndex += 1;
        this.applyTransform();
      },

      prev() {
        if (!this.canManual) {
          return;
        }

        this.currentIndex -= 1;
        this.applyTransform();
      },

      pause() {
        this.paused = true;
      },

      resume() {
        this.paused = false;
      },

      startAuto() {
        if (!this.canAuto) {
          return;
        }

        this.stopAuto();
        this.autoTimerId = window.setInterval(() => {
          if (this.paused || document.hidden || !this.canAuto) {
            return;
          }
          this.currentIndex += 1;
          this.applyTransform();
        }, this.autoIntervalMs);
      },

      stopAuto() {
        if (this.autoTimerId) {
          window.clearInterval(this.autoTimerId);
          this.autoTimerId = null;
        }
      },

      recalculate() {
        if (this.total <= 0) {
          return;
        }

        const previousLogical = this.currentIndex > 0 ? this.getLogicalIndex() || 1 : 1;
        this.calculateDimensions();

        if (this.stepSize <= 0) {
          this.emitState();
          return;
        }

        this.visibleCount = this.isInteractive ? this.calculateVisibleCount() : 1;
        this.prepareTrack();

        if (this.isInteractive) {
          this.currentIndex = this.visibleCount + Math.max(0, Math.min(this.total - 1, previousLogical - 1));
          this.setTransition(false);
          this.applyTransform();
          this.trackEl.getBoundingClientRect();
          this.setTransition(true);
        } else {
          this.currentIndex = 0;
          this.setTransition(false);
          this.applyTransform();
        }

        this.emitState();
      },

      bindEvents() {
        this.transitionHandler = (event) => {
          if (event.target !== this.trackEl) {
            return;
          }
          this.normalizeAfterTransition();
        };
        this.trackEl.addEventListener("transitionend", this.transitionHandler);

        const onEnter = () => this.pause();
        const onLeave = () => this.resume();
        this.rootEl.addEventListener("mouseenter", onEnter);
        this.rootEl.addEventListener("mouseleave", onLeave);
        this.rootEl.addEventListener("focusin", onEnter);
        this.rootEl.addEventListener("focusout", onLeave);

        this.resizeHandler = () => {
          this.recalculate();
        };
        window.addEventListener("resize", this.resizeHandler);

        this.visibilityHandler = () => {
          if (document.hidden) {
            this.pause();
            return;
          }
          this.resume();
        };
        document.addEventListener("visibilitychange", this.visibilityHandler);

        this.cleanupFns.push(() => {
          this.rootEl.removeEventListener("mouseenter", onEnter);
          this.rootEl.removeEventListener("mouseleave", onLeave);
          this.rootEl.removeEventListener("focusin", onEnter);
          this.rootEl.removeEventListener("focusout", onLeave);
        });
      },

      init() {
        if (!this.rootEl || !this.trackEl) {
          return false;
        }

        const originalSlides = this.getOriginalSlides();
        this.total = originalSlides.length;
        this.computeCapabilities();

        if (this.total === 0) {
          this.emitState();
          return false;
        }

        this.currentIndex = this.isInteractive ? 1 : 0;
        this.recalculate();

        if (this.isInteractive) {
          this.bindEvents();
        }

        if (this.canAuto) {
          this.startAuto();
        }

        return true;
      },

      destroy() {
        this.stopAuto();

        if (this.transitionHandler) {
          this.trackEl.removeEventListener("transitionend", this.transitionHandler);
          this.transitionHandler = null;
        }

        if (this.resizeHandler) {
          window.removeEventListener("resize", this.resizeHandler);
          this.resizeHandler = null;
        }

        if (this.visibilityHandler) {
          document.removeEventListener("visibilitychange", this.visibilityHandler);
          this.visibilityHandler = null;
        }

        this.cleanupFns.forEach((cleanup) => cleanup());
        this.cleanupFns = [];
      },
    };
  }

  function makeCarouselComponent(config) {
    return {
      controller: null,
      formattedCurrent: "01",
      formattedTotal: "00",
      canManual: false,
      canAuto: false,
      isStatic: true,
      isInteractive: false,

      applyState(state) {
        this.formattedCurrent = formatCounter(state.current);
        this.formattedTotal = formatCounter(state.total);
        this.canManual = !!state.canManual;
        this.canAuto = !!state.canAuto;
        this.isStatic = !!state.isStatic;
        this.isInteractive = !!state.isInteractive;
      },

      init() {
        if (this.$root && this.$root[config.fallbackKey]) {
          this.controller = this.$root[config.fallbackKey];
          this.$root[config.fallbackKey] = null;
          this.applyState({
            current: this.controller.getLogicalIndex(),
            total: this.controller.total,
            canManual: this.controller.canManual,
            canAuto: this.controller.canAuto,
            isStatic: this.controller.isStatic,
            isInteractive: this.controller.isInteractive,
          });
          return;
        }

        if (!this.$refs.track) {
          return;
        }

        this.controller = makeCardCarouselController(
          config,
          this.$root,
          this.$refs.track,
          (state) => this.applyState(state),
        );
        this.controller.init();
      },

      next() {
        if (this.controller) {
          this.controller.next();
        }
      },

      prev() {
        if (this.controller) {
          this.controller.prev();
        }
      },

      pause() {
        if (this.controller) {
          this.controller.pause();
        }
      },

      resume() {
        if (this.controller) {
          this.controller.resume();
        }
      },

      destroy() {
        if (this.controller) {
          this.controller.destroy();
        }
      },
    };
  }

  function registerAlpineComponent() {
    if (
      isAlpineRegistered ||
      !window.Alpine ||
      "function" !== typeof window.Alpine.data
    ) {
      return;
    }

    CAROUSEL_CONFIGS.forEach((config) => {
      window.Alpine.data(config.alpineName, () => makeCarouselComponent(config));
    });
    isAlpineRegistered = true;
  }

  function initFallbackCarousels() {
    CAROUSEL_CONFIGS.forEach((config) => {
      const carousels = document.querySelectorAll(config.rootSelector);

      carousels.forEach((carousel) => {
        if (
          carousel[config.fallbackKey] ||
          "1" === carousel.dataset[config.initFlag] ||
          carousel._x_dataStack
        ) {
          return;
        }

        const track = carousel.querySelector(config.trackSelector);
        if (!track) {
          return;
        }

        const controller = makeCardCarouselController(config, carousel, track);
        const initialized = controller.init();
        if (!initialized) {
          return;
        }

        const prevButton = carousel.querySelector(config.prevSelector);
        const nextButton = carousel.querySelector(config.nextSelector);

        if (prevButton) {
          prevButton.addEventListener("click", () => controller.prev());
        }

        if (nextButton) {
          nextButton.addEventListener("click", () => controller.next());
        }

        carousel[config.fallbackKey] = controller;
        carousel.dataset[config.initFlag] = "1";
      });
    });
  }

  CAROUSEL_CONFIGS.forEach((config) => {
    window[config.globalName] = () => makeCarouselComponent(config);
  });

  document.addEventListener("alpine:init", registerAlpineComponent);
  registerAlpineComponent();

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initFallbackCarousels);
  } else {
    initFallbackCarousels();
  }

  window.addEventListener("load", initFallbackCarousels);
})();
// End of homepage card carousel interactions.
