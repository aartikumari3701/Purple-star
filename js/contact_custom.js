jQuery(document).ready(function ($) {
    "use strict";

    const header = $(".header");
    const topNav = $(".top_nav");
    const hamburger = $(".hamburger_container");
    const menu = $(".hamburger_menu");
    const hamburgerClose = $(".hamburger_close");
    const fsOverlay = $(".fs_menu_overlay");
    const mapElement = $("#map");
    let menuActive = false;
    let map;

    /* Initialize features */
    setHeader();
    initMenu();
    if (mapElement.length) initGoogleMap();

    /* Event Listeners */
    $(window).on("resize", debounce(setHeader, 100));
    $(document).on("scroll", debounce(setHeader, 100));

    /* Set Header */
    function setHeader() {
        if (window.innerWidth < 992) {
            header.css({ top: $(window).scrollTop() > 100 ? "0" : "0" });
        } else {
            header.css({ top: $(window).scrollTop() > 100 ? "-50px" : "0" });
        }
        if (window.innerWidth > 991 && menuActive) {
            closeMenu();
        }
    }

    /* Initialize Menu */
    function initMenu() {
        if (hamburger.length) {
            hamburger.on("click", function () {
                if (!menuActive) openMenu();
            });
        }

        if (fsOverlay.length) {
            fsOverlay.on("click", function () {
                if (menuActive) closeMenu();
            });
        }

        if (hamburgerClose.length) {
            hamburgerClose.on("click", function () {
                if (menuActive) closeMenu();
            });
        }

        $(".menu_item.has-children").each(function () {
            $(this).on("click", function () {
                $(this).toggleClass("active");
                const panel = $(this).children(".submenu");
                panel.css("maxHeight", panel.css("maxHeight") ? null : panel.prop("scrollHeight") + "px");
            });
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

    /* Initialize Google Map */
    function initGoogleMap() {
        const myLatLng = { lat: 42.373122, lng: -71.112387 };
        const mapOptions = {
            center: myLatLng,
            zoom: 16,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            draggable: true,
            scrollwheel: false,
            zoomControl: true,
            mapTypeControl: false,
            streetViewControl: false,
            styles: [
                { elementType: "geometry", stylers: [{ color: "#f5f5f5" }] },
                // Additional styles here...
            ],
        };

        map = new google.maps.Map(mapElement[0], mapOptions);
        new google.maps.Marker({
            position: myLatLng,
            map: map,
            icon: "images/map_marker.png",
        });

        google.maps.event.addDomListener(window, "resize", function () {
            setTimeout(() => {
                google.maps.event.trigger(map, "resize");
                map.setCenter(myLatLng);
            }, 1400);
        });
    }

    /* Utility: Debounce Function */
    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            const later = () => {
                clearTimeout(timeout);
                func.apply(this, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
});
