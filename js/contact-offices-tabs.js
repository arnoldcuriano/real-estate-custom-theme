(function () {
  const ROOT_SELECTOR = "[data-contact-offices-tabs]";
  const TAB_SELECTOR = "[data-office-tab]";
  const CARD_SELECTOR = "[data-office-card]";

  function setActiveTab(root, tabValue) {
    const tabs = Array.from(root.querySelectorAll(TAB_SELECTOR));
    const cards = Array.from(root.querySelectorAll(CARD_SELECTOR));

    tabs.forEach((tab) => {
      const isActive = (tab.dataset.officeTab || "") === tabValue;
      tab.classList.toggle("is-active", isActive);
      tab.setAttribute("aria-selected", isActive ? "true" : "false");
      tab.setAttribute("tabindex", isActive ? "0" : "-1");
    });

    cards.forEach((card) => {
      const category = (card.dataset.officeCategory || "").toLowerCase();
      const shouldShow = "all" === tabValue || tabValue === category;
      card.hidden = !shouldShow;
    });
  }

  function initOfficeTabs(root) {
    if (!root || root.dataset.officeTabsInit === "1") {
      return;
    }

    const tabs = Array.from(root.querySelectorAll(TAB_SELECTOR));
    if (!tabs.length) {
      return;
    }

    tabs.forEach((tab, index) => {
      tab.setAttribute("tabindex", 0 === index ? "0" : "-1");

      tab.addEventListener("click", () => {
        const value = (tab.dataset.officeTab || "all").toLowerCase();
        setActiveTab(root, value);
      });

      tab.addEventListener("keydown", (event) => {
        const currentIndex = tabs.indexOf(tab);
        let nextIndex = currentIndex;

        if ("ArrowRight" === event.key) {
          event.preventDefault();
          nextIndex = (currentIndex + 1) % tabs.length;
        } else if ("ArrowLeft" === event.key) {
          event.preventDefault();
          nextIndex = (currentIndex - 1 + tabs.length) % tabs.length;
        } else if ("Home" === event.key) {
          event.preventDefault();
          nextIndex = 0;
        } else if ("End" === event.key) {
          event.preventDefault();
          nextIndex = tabs.length - 1;
        } else {
          return;
        }

        tabs[nextIndex].focus();
        tabs[nextIndex].click();
      });
    });

    setActiveTab(root, "all");
    root.dataset.officeTabsInit = "1";
  }

  function init() {
    document.querySelectorAll(ROOT_SELECTOR).forEach(initOfficeTabs);
  }

  if ("loading" === document.readyState) {
    document.addEventListener("DOMContentLoaded", init);
    return;
  }

  init();
})();
