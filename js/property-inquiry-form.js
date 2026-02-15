(function () {
  const ROOT_SELECTOR = ".property-inquiry";
  const SELECT_SELECTOR = ".property-inquiry .js-property-inquiry-select, .property-inquiry select";
  let initialized = false;

  function closeAll(dropdowns, except) {
    dropdowns.forEach((dropdown) => {
      if (dropdown !== except) {
        dropdown.close(false);
      }
    });
  }

  function createDropdown(select, index, dropdowns) {
    const controlWrap = select.closest(".wpcf7-form-control-wrap") || select.parentElement;
    if (!controlWrap) {
      return null;
    }

    if (select.classList.contains("property-inquiry__select-native")) {
      return null;
    }

    const options = Array.from(select.options || []);
    if (!options.length) {
      return null;
    }

    const menuId = "property-inquiry-menu-" + String(index + 1);

    controlWrap.classList.add("property-inquiry__select-wrap", "is-enhanced");
    select.classList.add("property-inquiry__select-native");
    select.setAttribute("aria-hidden", "true");
    select.setAttribute("tabindex", "-1");

    const trigger = document.createElement("button");
    trigger.type = "button";
    trigger.className = "property-inquiry__select-trigger";
    trigger.setAttribute("aria-haspopup", "listbox");
    trigger.setAttribute("aria-expanded", "false");
    trigger.setAttribute("aria-controls", menuId);

    const triggerText = document.createElement("span");
    triggerText.className = "property-inquiry__select-trigger-text";
    trigger.appendChild(triggerText);

    const menu = document.createElement("div");
    menu.className = "property-inquiry__select-menu";
    menu.id = menuId;
    menu.setAttribute("role", "listbox");
    menu.setAttribute("tabindex", "-1");

    const optionButtons = options.map((option, optionIndex) => {
      const button = document.createElement("button");
      button.type = "button";
      button.className = "property-inquiry__select-option";
      button.dataset.value = option.value;
      button.dataset.index = String(optionIndex);
      button.setAttribute("role", "option");
      button.textContent = option.textContent || option.label || "";

      if (option.disabled) {
        button.disabled = true;
        button.classList.add("is-disabled");
      }

      menu.appendChild(button);
      return button;
    });

    const dropdown = {
      controlWrap,
      select,
      trigger,
      triggerText,
      menu,
      optionButtons,
      getSelectedIndex() {
        const selectedIndex = this.select.selectedIndex;
        return selectedIndex >= 0 ? selectedIndex : 0;
      },
      focusOptionByIndex(targetIndex) {
        const maxIndex = this.optionButtons.length - 1;
        const safeIndex = Math.max(0, Math.min(targetIndex, maxIndex));
        const targetOption = this.optionButtons[safeIndex];
        if (targetOption && !targetOption.disabled) {
          targetOption.focus();
          return;
        }

        const fallback = this.optionButtons.find((optionButton) => !optionButton.disabled);
        if (fallback) {
          fallback.focus();
        }
      },
      updateFromSelect() {
        const selectedOption = this.select.options[this.getSelectedIndex()];
        const selectedValue = selectedOption ? selectedOption.value : "";
        const selectedLabel = selectedOption ? selectedOption.textContent || selectedOption.label || "" : "";

        this.triggerText.textContent = selectedLabel;

        this.optionButtons.forEach((optionButton) => {
          const isSelected = optionButton.dataset.value === selectedValue;
          optionButton.classList.toggle("is-selected", isSelected);
          optionButton.setAttribute("aria-selected", isSelected ? "true" : "false");
        });
      },
      open(focusMode) {
        closeAll(dropdowns, this);
        this.controlWrap.classList.add("is-open");
        this.trigger.setAttribute("aria-expanded", "true");

        if ("first" === focusMode) {
          this.focusOptionByIndex(0);
          return;
        }

        if ("last" === focusMode) {
          this.focusOptionByIndex(this.optionButtons.length - 1);
          return;
        }

        this.focusOptionByIndex(this.getSelectedIndex());
      },
      close(restoreFocus) {
        this.controlWrap.classList.remove("is-open");
        this.trigger.setAttribute("aria-expanded", "false");

        if (restoreFocus) {
          this.trigger.focus();
        }
      },
      toggle() {
        if (this.controlWrap.classList.contains("is-open")) {
          this.close(true);
          return;
        }

        this.open("selected");
      },
    };

    trigger.addEventListener("click", (event) => {
      event.preventDefault();
      dropdown.toggle();
    });

    trigger.addEventListener("keydown", (event) => {
      if ("ArrowDown" === event.key) {
        event.preventDefault();
        dropdown.open("first");
        return;
      }

      if ("ArrowUp" === event.key) {
        event.preventDefault();
        dropdown.open("last");
        return;
      }

      if ("Enter" === event.key || " " === event.key) {
        event.preventDefault();
        dropdown.toggle();
      }
    });

    menu.addEventListener("keydown", (event) => {
      const activeOption = document.activeElement;
      const activeIndex = Number(activeOption && activeOption.dataset ? activeOption.dataset.index : 0);

      if ("Escape" === event.key) {
        event.preventDefault();
        dropdown.close(true);
        return;
      }

      if ("ArrowDown" === event.key) {
        event.preventDefault();
        dropdown.focusOptionByIndex(activeIndex + 1);
        return;
      }

      if ("ArrowUp" === event.key) {
        event.preventDefault();
        dropdown.focusOptionByIndex(activeIndex - 1);
        return;
      }

      if ("Home" === event.key) {
        event.preventDefault();
        dropdown.focusOptionByIndex(0);
        return;
      }

      if ("End" === event.key) {
        event.preventDefault();
        dropdown.focusOptionByIndex(dropdown.optionButtons.length - 1);
        return;
      }

      if (("Enter" === event.key || " " === event.key) && activeOption && activeOption.classList.contains("property-inquiry__select-option")) {
        event.preventDefault();
        activeOption.click();
      }
    });

    optionButtons.forEach((optionButton) => {
      optionButton.addEventListener("click", (event) => {
        event.preventDefault();
        if (optionButton.disabled) {
          return;
        }

        select.value = optionButton.dataset.value || "";
        select.dispatchEvent(new Event("change", { bubbles: true }));
        dropdown.close(true);
      });
    });

    select.addEventListener("change", () => {
      dropdown.updateFromSelect();
    });

    controlWrap.appendChild(trigger);
    controlWrap.appendChild(menu);
    dropdown.updateFromSelect();

    return dropdown;
  }

  function initInquirySelects() {
    if (initialized) {
      return;
    }

    const root = document.querySelector(ROOT_SELECTOR);
    if (!root) {
      return;
    }

    const selects = Array.from(root.querySelectorAll(SELECT_SELECTOR));
    if (!selects.length) {
      return;
    }

    const dropdowns = [];

    selects.forEach((select, index) => {
      const dropdown = createDropdown(select, index, dropdowns);
      if (dropdown) {
        dropdowns.push(dropdown);
      }
    });

    if (!dropdowns.length) {
      return;
    }

    root.classList.add("property-inquiry--selects-enhanced");

    document.addEventListener("click", (event) => {
      const clickedInside = dropdowns.some((dropdown) => dropdown.controlWrap.contains(event.target));
      if (!clickedInside) {
        closeAll(dropdowns, null);
      }
    });

    document.addEventListener("keydown", (event) => {
      if ("Escape" === event.key) {
        closeAll(dropdowns, null);
      }
    });

    window.addEventListener("resize", () => {
      closeAll(dropdowns, null);
    });

    initialized = true;
  }

  if ("loading" === document.readyState) {
    document.addEventListener("DOMContentLoaded", initInquirySelects);
  } else {
    initInquirySelects();
  }
})();
