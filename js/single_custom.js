jQuery(document).ready(function ($) {
    "use strict";

    /* Vars and Initialization */
    const header = $(".header");
    const hamburger = $(".hamburger_container");
    const menu = $(".hamburger_menu");
    const hamburgerClose = $(".hamburger_close");
    const fsOverlay = $(".fs_menu_overlay");
    const thumbnails = $(".single_product_thumbnails ul li");
    const quantityValue = $("#quantity_value");
    const stars = $(".user_star_rating li");
    const favorite = $(".product_favorite");
    const tabs = $(".tabs li");
    const tabContainers = $(".tab_container");
    let menuActive = false;

    /* Initialize Features */
    setHeader();
    initMenu();
    initThumbnail();
    initQuantity();
    initStarRating();
    initFavorite();
    initTabs();

    /* Event Listeners */
    $(window).on("resize", debounce(setHeader, 100));
    $(document).on("scroll", debounce(setHeader, 100));

    /* Set Header */
    function setHeader() {
        if (window.innerWidth < 992) {
            header.css({ top: "0" });
        } else {
            header.css({ top: $(window).scrollTop() > 100 ? "-50px" : "0" });
        }
        if (window.innerWidth > 991 && menuActive) closeMenu();
    }

    /* Initialize Menu */
    function initMenu() {
        if (hamburger.length) {
            hamburger.on("click", () => {
                if (!menuActive) openMenu();
            });
        }

        if (fsOverlay.length) {
            fsOverlay.on("click", closeMenu);
        }

        if (hamburgerClose.length) {
            hamburgerClose.on("click", closeMenu);
        }

        $(".menu_item.has-children").on("click", function () {
            $(this).toggleClass("active");
            const panel = $(this).children(".submenu");
            panel.css("maxHeight", panel.css("maxHeight") ? null : panel.prop("scrollHeight") + "px");
        });
    }

    function openMenu() {
        menu.addClass("active");
        fsOverlay.css("pointer-events", "auto");
        menuActive = true;
    }

    function closeMenu() {
        menu.removeClass("active");
        fsOverlay.css("pointer-events", "none");
        menuActive = false;
    }

    /* Initialize Thumbnail */
    function initThumbnail() {
        thumbnails.each(function () {
            const item = $(this);
            item.on("click", function () {
                thumbnails.removeClass("active");
                item.addClass("active");
                const img = item.find("img").data("image");
                $(".single_product_image_background").css("background-image", `url(${img})`);
            });
        });
    }

    /* Initialize Quantity */
    function initQuantity() {
        $(".plus").on("click", () => updateQuantity(1));
        $(".minus").on("click", () => updateQuantity(-1));

        function updateQuantity(delta) {
            let value = parseInt(quantityValue.text(), 10);
            value = isNaN(value) ? 1 : Math.max(1, value + delta);
            quantityValue.text(value);
        }
    }

    /* Initialize Star Rating */
    function initStarRating() {
        stars.each(function () {
            const star = $(this);
            star.on("click", function () {
                const index = star.index();
                stars.find("i").removeClass("fa-star").addClass("fa-star-o");
                stars.slice(0, index + 1).find("i").removeClass("fa-star-o").addClass("fa-star");
            });
        });
    }

    /* Initialize Favorite */
    function initFavorite() {
        favorite.on("click", function () {
            $(this).toggleClass("active");
        });
    }

    /* Initialize Tabs */
    function initTabs() {
        tabs.each(function () {
            const tab = $(this);
            const tabId = tab.data("active-tab");

            tab.on("click", function () {
                if (!tab.hasClass("active")) {
                    tabs.removeClass("active");
                    tabContainers.removeClass("active");
                    tab.addClass("active");
                    $(`#${tabId}`).addClass("active");
                }
            });
        });
    }

    /* Utility: Debounce */
    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
});
