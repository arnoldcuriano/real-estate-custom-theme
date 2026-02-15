/**
 * Single-property pricing accordion controller.
 *
 * Behavior:
 * - One card open at a time.
 * - First card open by default.
 * - Clicking the open card keeps it open (always one open).
 */
(function () {
  function getAccordionItems(root) {
    return Array.from(root.querySelectorAll("[data-pricing-item]"));
  }

  function getToggleButton(item) {
    return item.querySelector("[data-pricing-toggle]");
  }

  function getPanel(item) {
    return item.querySelector("[data-pricing-panel]");
  }

  function setItemOpen(item, isOpen) {
    const button = getToggleButton(item);
    const panel = getPanel(item);

    if (!button || !panel) {
      return;
    }

    item.classList.toggle("is-open", isOpen);
    button.setAttribute("aria-expanded", isOpen ? "true" : "false");
    panel.hidden = !isOpen;
  }

  function openItem(root, activeItem) {
    getAccordionItems(root).forEach((item) => {
      setItemOpen(item, item === activeItem);
    });
  }

  function initAccordion(root) {
    if (!root || root.dataset.pricingAccordionInit === "1") {
      return;
    }

    const items = getAccordionItems(root);
    if (!items.length) {
      return;
    }

    let initiallyOpen = items.find((item) => item.classList.contains("is-open"));
    if (!initiallyOpen) {
      initiallyOpen = items[0];
    }

    openItem(root, initiallyOpen);

    items.forEach((item) => {
      const button = getToggleButton(item);
      if (!button) {
        return;
      }

      button.addEventListener("click", () => {
        if (item.classList.contains("is-open")) {
          return;
        }
        openItem(root, item);
      });
    });

    root.dataset.pricingAccordionInit = "1";
  }

  function initAll() {
    document
      .querySelectorAll("[data-pricing-accordion]")
      .forEach(initAccordion);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAll);
  } else {
    initAll();
  }
})();
