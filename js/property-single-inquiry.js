/**
 * Single-property inquiry enhancements.
 *
 * Prefills and locks the selected property field so submissions always
 * carry the current property context.
 */
(function () {
  function buildSelectedPropertyValue(root) {
    const title = (root.getAttribute("data-selected-property-title") || "").trim();
    const location = (root.getAttribute("data-selected-property-location") || "").trim();

    if (!title) {
      return "";
    }

    return location ? `${title}, ${location}` : title;
  }

  function initSinglePropertyInquiry() {
    const root = document.querySelector(".property-inquiry--single");
    if (!root) {
      return;
    }

    const selectedProperty = buildSelectedPropertyValue(root);
    if (!selectedProperty) {
      return;
    }

    const selectedInput = root.querySelector('input[name="selected_property"]');
    if (!selectedInput) {
      return;
    }

    selectedInput.value = selectedProperty;
    selectedInput.readOnly = true;
    selectedInput.setAttribute("readonly", "readonly");
    selectedInput.setAttribute("aria-readonly", "true");
    selectedInput.classList.add("property-inquiry__selected-input");

    const wrap = selectedInput.closest(".wpcf7-form-control-wrap");
    if (wrap) {
      wrap.classList.add("property-inquiry__selected-wrap");
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initSinglePropertyInquiry);
  } else {
    initSinglePropertyInquiry();
  }

  document.addEventListener("wpcf7submit", initSinglePropertyInquiry);
})();
