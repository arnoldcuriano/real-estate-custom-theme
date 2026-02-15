/* global jQuery */
(function ($) {
  "use strict";

  const ROOT_SELECTOR = "[data-property-pricing-metabox]";

  function syncPanelRows(panel) {
    const panelKey = panel.getAttribute("data-pricing-group") || "";
    const rows = Array.from(panel.querySelectorAll("[data-pricing-row]"));

    rows.forEach((row, index) => {
      row.querySelectorAll("[data-pricing-field]").forEach((field) => {
        const fieldKey = field.getAttribute("data-pricing-field");
        if (!fieldKey) {
          return;
        }

        field.setAttribute(
          "name",
          "rect_property_pricing[" +
            panelKey +
            "][" +
            index +
            "][" +
            fieldKey +
            "]",
        );
      });
    });

    panel.classList.toggle("is-empty", rows.length === 0);
  }

  function addRow(panel) {
    const rowsContainer = panel.querySelector("[data-pricing-rows]");
    const template = panel.querySelector("template[data-pricing-template]");

    if (!rowsContainer || !template) {
      return;
    }

    const templateHtml = String(template.innerHTML || "").trim();
    if (!templateHtml) {
      return;
    }

    rowsContainer.insertAdjacentHTML("beforeend", templateHtml);
    syncPanelRows(panel);
  }

  function initPanel(panel) {
    if (!panel || panel.dataset.pricingPanelInit === "1") {
      return;
    }

    const rowsContainer = panel.querySelector("[data-pricing-rows]");
    const addButton = panel.querySelector("[data-pricing-add]");

    if (!rowsContainer || !addButton) {
      return;
    }

    addButton.addEventListener("click", (event) => {
      event.preventDefault();
      addRow(panel);
    });

    panel.addEventListener("click", (event) => {
      const removeButton = event.target.closest("[data-pricing-remove]");
      if (removeButton) {
        event.preventDefault();
        const row = removeButton.closest("[data-pricing-row]");
        if (row) {
          row.remove();
          syncPanelRows(panel);
        }
      }
    });

    if ($.fn.sortable) {
      $(rowsContainer).sortable({
        items: "> [data-pricing-row]",
        handle: ".rect-property-pricing-metabox__drag",
        placeholder: "rect-property-pricing-metabox__placeholder",
        stop: function () {
          syncPanelRows(panel);
        },
      });
    }

    syncPanelRows(panel);
    panel.dataset.pricingPanelInit = "1";
  }

  function initPricingMetabox(root) {
    if (!root || root.dataset.pricingMetaboxInit === "1") {
      return;
    }

    root.querySelectorAll("[data-pricing-group]").forEach(initPanel);
    root.dataset.pricingMetaboxInit = "1";
  }

  function initAll() {
    document.querySelectorAll(ROOT_SELECTOR).forEach(initPricingMetabox);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAll);
  } else {
    initAll();
  }
})(jQuery);
