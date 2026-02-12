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
 * Homepage featured properties carousel interactions.
 */
(function () {
  const CAROUSEL_SELECTOR = "[data-featured-carousel]";
  const TRACK_SELECTOR = ".featured-properties__track";
  const SLIDE_SELECTOR = ".featured-properties__slide:not(.featured-properties__slide--clone)";
  const PREV_SELECTOR = "[data-featured-prev]";
  const NEXT_SELECTOR = "[data-featured-next]";
  let isAlpineRegistered = false;

  function formatCounter(value) {
    const safeValue = Math.max(0, Number(value) || 0);
    return String(safeValue).padStart(2, "0");
  }

  function makeFeaturedCarouselController(rootEl, trackEl, onStateChange) {
    return {
      rootEl,
      trackEl,
      onStateChange,
      total: 0,
      currentIndex: 0,
      stepSize: 0,
      autoIntervalMs: 4000,
      autoTimerId: null,
      paused: false,
      transitionHandler: null,
      resizeHandler: null,
      visibilityHandler: null,
      cleanupFns: [],

      getOriginalSlides() {
        return Array.from(this.trackEl.querySelectorAll(SLIDE_SELECTOR));
      },

      getAllSlides() {
        return Array.from(this.trackEl.querySelectorAll(".featured-properties__slide"));
      },

      shouldSlide() {
        return this.total > 1;
      },

      getLogicalIndex() {
        if (!this.shouldSlide()) {
          return this.total > 0 ? 1 : 0;
        }

        if (this.currentIndex <= 0) {
          return this.total;
        }

        if (this.currentIndex >= this.total + 1) {
          return 1;
        }

        return this.currentIndex;
      },

      emitState() {
        const state = {
          current: this.getLogicalIndex(),
          total: this.total,
          canSlide: this.shouldSlide(),
        };

        if ("function" === typeof this.onStateChange) {
          this.onStateChange(state);
        }

        const currentTextEl = this.rootEl.querySelector("[data-featured-current]");
        const totalTextEl = this.rootEl.querySelector("[data-featured-total]");
        const prevButton = this.rootEl.querySelector(PREV_SELECTOR);
        const nextButton = this.rootEl.querySelector(NEXT_SELECTOR);

        if (currentTextEl) {
          currentTextEl.textContent = formatCounter(state.current);
        }

        if (totalTextEl) {
          totalTextEl.textContent = formatCounter(state.total);
        }

        if (prevButton) {
          prevButton.disabled = !state.canSlide;
        }

        if (nextButton) {
          nextButton.disabled = !state.canSlide;
        }
      },

      prepareTrack() {
        if (!this.shouldSlide() || "1" === this.trackEl.dataset.carouselPrepared) {
          return;
        }

        const slides = this.getOriginalSlides();
        if (slides.length < 2) {
          return;
        }

        const firstClone = slides[0].cloneNode(true);
        const lastClone = slides[slides.length - 1].cloneNode(true);

        firstClone.classList.add("featured-properties__slide--clone");
        lastClone.classList.add("featured-properties__slide--clone");
        firstClone.setAttribute("aria-hidden", "true");
        lastClone.setAttribute("aria-hidden", "true");

        this.trackEl.insertBefore(lastClone, slides[0]);
        this.trackEl.appendChild(firstClone);
        this.trackEl.dataset.carouselPrepared = "1";
        this.currentIndex = 1;
      },

      recalculate() {
        const firstSlide = this.trackEl.querySelector(".featured-properties__slide");
        if (!firstSlide) {
          this.stepSize = 0;
          return;
        }

        const trackStyle = window.getComputedStyle(this.trackEl);
        const gap =
          parseFloat(trackStyle.columnGap || "") ||
          parseFloat(trackStyle.gap || "") ||
          0;
        this.stepSize = firstSlide.getBoundingClientRect().width + gap;

        if (this.stepSize <= 0) {
          return;
        }

        this.trackEl.style.transition = "none";
        this.applyTransform();
        window.requestAnimationFrame(() => {
          this.trackEl.style.transition = "transform 420ms cubic-bezier(0.22, 1, 0.36, 1)";
        });
      },

      applyTransform() {
        if (this.stepSize <= 0) {
          return;
        }
        const offset = -this.currentIndex * this.stepSize;
        this.trackEl.style.transform = `translate3d(${offset}px, 0, 0)`;
      },

      normalizeAfterTransition() {
        if (!this.shouldSlide()) {
          this.emitState();
          return;
        }

        if (this.currentIndex === 0) {
          this.trackEl.style.transition = "none";
          this.currentIndex = this.total;
          this.applyTransform();
          this.trackEl.getBoundingClientRect();
          this.trackEl.style.transition = "transform 420ms cubic-bezier(0.22, 1, 0.36, 1)";
        } else if (this.currentIndex === this.total + 1) {
          this.trackEl.style.transition = "none";
          this.currentIndex = 1;
          this.applyTransform();
          this.trackEl.getBoundingClientRect();
          this.trackEl.style.transition = "transform 420ms cubic-bezier(0.22, 1, 0.36, 1)";
        }

        this.emitState();
      },

      next() {
        if (!this.shouldSlide()) {
          return;
        }

        this.currentIndex += 1;
        this.applyTransform();
      },

      prev() {
        if (!this.shouldSlide()) {
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
        if (!this.shouldSlide()) {
          return;
        }

        this.stopAuto();
        this.autoTimerId = window.setInterval(() => {
          if (this.paused || document.hidden) {
            return;
          }
          this.next();
        }, this.autoIntervalMs);
      },

      stopAuto() {
        if (this.autoTimerId) {
          window.clearInterval(this.autoTimerId);
          this.autoTimerId = null;
        }
      },

      bindEvents() {
        this.transitionHandler = () => {
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

        if (this.total === 0) {
          this.emitState();
          return false;
        }

        this.currentIndex = this.shouldSlide() ? 1 : 0;
        this.prepareTrack();
        this.recalculate();
        this.emitState();

        if (this.shouldSlide()) {
          this.bindEvents();
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

  function featuredPropertiesCarousel() {
    return {
      controller: null,
      formattedCurrent: "01",
      formattedTotal: "00",
      canSlide: false,

      applyState(state) {
        this.formattedCurrent = formatCounter(state.current);
        this.formattedTotal = formatCounter(state.total);
        this.canSlide = !!state.canSlide;
      },

      init() {
        if (this.$root && this.$root.__featuredCarouselFallbackController) {
          this.controller = this.$root.__featuredCarouselFallbackController;
          this.$root.__featuredCarouselFallbackController = null;
          this.applyState({
            current: this.controller.getLogicalIndex(),
            total: this.controller.total,
            canSlide: this.controller.shouldSlide(),
          });
          return;
        }

        if (!this.$refs.track) {
          return;
        }

        this.controller = makeFeaturedCarouselController(
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

    window.Alpine.data("featuredPropertiesCarousel", featuredPropertiesCarousel);
    isAlpineRegistered = true;
  }

  function initFallbackCarousels() {
    const carousels = document.querySelectorAll(CAROUSEL_SELECTOR);

    carousels.forEach((carousel) => {
      if (
        carousel.__featuredCarouselFallbackController ||
        "1" === carousel.dataset.carouselInit ||
        carousel._x_dataStack
      ) {
        return;
      }

      const track = carousel.querySelector(TRACK_SELECTOR);
      if (!track) {
        return;
      }

      const controller = makeFeaturedCarouselController(carousel, track);
      const initialized = controller.init();
      if (!initialized) {
        return;
      }

      const prevButton = carousel.querySelector(PREV_SELECTOR);
      const nextButton = carousel.querySelector(NEXT_SELECTOR);

      if (prevButton) {
        prevButton.addEventListener("click", () => controller.prev());
      }

      if (nextButton) {
        nextButton.addEventListener("click", () => controller.next());
      }

      carousel.__featuredCarouselFallbackController = controller;
      carousel.dataset.carouselInit = "1";
    });
  }

  window.featuredPropertiesCarousel = featuredPropertiesCarousel;
  document.addEventListener("alpine:init", registerAlpineComponent);
  registerAlpineComponent();

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initFallbackCarousels);
  } else {
    initFallbackCarousels();
  }

  window.addEventListener("load", initFallbackCarousels);
})();
// End of homepage featured properties carousel interactions.
