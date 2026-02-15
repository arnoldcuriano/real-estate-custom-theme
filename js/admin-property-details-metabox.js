/* global jQuery */
(function ($) {
  "use strict";

  const ROOT_SELECTOR = "[data-property-details-metabox]";

  function syncGroupRows(group) {
    const groupKey = group.getAttribute("data-detail-group") || "";
    const rows = Array.from(group.querySelectorAll("[data-detail-row]"));

    rows.forEach((row, index) => {
      row.querySelectorAll("[data-field]").forEach((field) => {
        const fieldKey = field.getAttribute("data-field");
        if (!fieldKey) {
          return;
        }

        field.setAttribute(
          "name",
          "rect_property_details[" +
            groupKey +
            "][" +
            index +
            "][" +
            fieldKey +
            "]",
        );
      });
    });

    group.classList.toggle("is-empty", rows.length === 0);
  }

  function addRow(group) {
    const rowsContainer = group.querySelector("[data-detail-rows]");
    const template = group.querySelector("template[data-detail-template]");

    if (!rowsContainer || !template) {
      return;
    }

    const templateHtml = String(template.innerHTML || "").trim();
    if (!templateHtml) {
      return;
    }

    rowsContainer.insertAdjacentHTML("beforeend", templateHtml);
    syncGroupRows(group);
  }

  function initGroup(group) {
    if (!group || group.dataset.detailsGroupInit === "1") {
      return;
    }

    const rowsContainer = group.querySelector("[data-detail-rows]");
    const addButton = group.querySelector("[data-detail-add]");

    if (!rowsContainer || !addButton) {
      return;
    }

    addButton.addEventListener("click", (event) => {
      event.preventDefault();
      addRow(group);
    });

    group.addEventListener("click", (event) => {
      const removeButton = event.target.closest("[data-detail-remove]");
      if (removeButton) {
        event.preventDefault();
        const row = removeButton.closest("[data-detail-row]");
        if (row) {
          row.remove();
          syncGroupRows(group);
        }
      }
    });

    if ($.fn.sortable) {
      $(rowsContainer).sortable({
        items: "> [data-detail-row]",
        handle: ".rect-property-details-metabox__drag",
        placeholder: "rect-property-details-metabox__placeholder",
        stop: function () {
          syncGroupRows(group);
        },
      });
    }

    syncGroupRows(group);
    group.dataset.detailsGroupInit = "1";
  }

  function initDetailsMetabox(root) {
    if (!root || root.dataset.detailsMetaboxInit === "1") {
      return;
    }

    root.querySelectorAll("[data-detail-group]").forEach(initGroup);
    root.dataset.detailsMetaboxInit = "1";
  }

  function initAll() {
    document.querySelectorAll(ROOT_SELECTOR).forEach(initDetailsMetabox);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAll);
  } else {
    initAll();
  }
})(jQuery);
