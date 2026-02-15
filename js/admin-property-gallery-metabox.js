/* global jQuery, wp, rectPropertyGalleryMetabox */
(function ($) {
  "use strict";

  const ROOT_SELECTOR = "[data-property-gallery-metabox]";
  const i18n = window.rectPropertyGalleryMetabox || {};

  function escapeHtml(value) {
    return String(value || "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  function buildItemMarkup(image) {
    const safeId = Number(image.id) || 0;
    const safeUrl = escapeHtml(image.thumb || image.url || "");
    const safeAlt = escapeHtml(image.alt || "");
    const removeLabel = i18n.removeLabel ? i18n.removeLabel : "Remove";

    return [
      '<li class="rect-property-gallery-metabox__item" data-image-id="',
      safeId,
      '">',
      '<span class="rect-property-gallery-metabox__drag dashicons dashicons-move" aria-hidden="true"></span>',
      '<img src="',
      safeUrl,
      '" alt="',
      safeAlt,
      '" loading="lazy">',
      '<button type="button" class="button-link-delete rect-property-gallery-metabox__remove" data-gallery-remove>',
      escapeHtml(removeLabel),
      "</button>",
      "</li>",
    ].join("");
  }

  function initGalleryMetabox(root) {
    if (!root || root.dataset.galleryMetaboxInit === "1") {
      return;
    }

    const idsField = root.querySelector("[data-gallery-ids]");
    const list = root.querySelector("[data-gallery-list]");
    const addButton = root.querySelector("[data-gallery-add]");
    const clearButton = root.querySelector("[data-gallery-clear]");

    if (!idsField || !list || !addButton || !clearButton || !wp || !wp.media) {
      return;
    }

    let mediaFrame = null;

    function hasImage(imageId) {
      return !!list.querySelector('[data-image-id="' + imageId + '"]');
    }

    function syncIdsField() {
      const ids = Array.from(list.querySelectorAll("[data-image-id]"))
        .map((item) => Number(item.getAttribute("data-image-id")) || 0)
        .filter((id) => id > 0);

      idsField.value = ids.join(",");
      root.classList.toggle("is-empty", ids.length === 0);
    }

    function appendImage(image) {
      const imageId = Number(image.id) || 0;
      if (imageId <= 0 || hasImage(imageId)) {
        return;
      }

      list.insertAdjacentHTML("beforeend", buildItemMarkup(image));
    }

    function openMediaFrame() {
      if (!mediaFrame) {
        mediaFrame = wp.media({
          title: i18n.frameTitle || "Select Property Photos",
          button: {
            text: i18n.frameButton || "Use Selected Photos",
          },
          multiple: true,
          library: {
            type: "image",
          },
        });

        mediaFrame.on("select", () => {
          const selection = mediaFrame.state().get("selection");
          if (!selection) {
            return;
          }

          selection.each((attachmentModel) => {
            const attachment = attachmentModel.toJSON();
            const thumb =
              attachment.sizes && attachment.sizes.thumbnail
                ? attachment.sizes.thumbnail.url
                : attachment.url;

            appendImage({
              id: attachment.id,
              thumb: thumb,
              url: attachment.url,
              alt: attachment.alt || attachment.title || "",
            });
          });

          syncIdsField();
        });
      }

      mediaFrame.open();
    }

    addButton.addEventListener("click", (event) => {
      event.preventDefault();
      openMediaFrame();
    });

    clearButton.addEventListener("click", (event) => {
      event.preventDefault();
      list.innerHTML = "";
      syncIdsField();
    });

    list.addEventListener("click", (event) => {
      const removeButton = event.target.closest("[data-gallery-remove]");
      if (!removeButton) {
        return;
      }

      event.preventDefault();
      const item = removeButton.closest("[data-image-id]");
      if (item) {
        item.remove();
        syncIdsField();
      }
    });

    if ($.fn.sortable) {
      $(list).sortable({
        items: "> [data-image-id]",
        handle: ".rect-property-gallery-metabox__drag",
        placeholder: "rect-property-gallery-metabox__placeholder",
        stop: syncIdsField,
      });
    }

    syncIdsField();
    root.dataset.galleryMetaboxInit = "1";
  }

  function initAll() {
    document.querySelectorAll(ROOT_SELECTOR).forEach(initGalleryMetabox);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAll);
  } else {
    initAll();
  }
})(jQuery);
