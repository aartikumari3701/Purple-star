/* JS Document */

/******************************

[Table of Contents]

1. Vars and Inits
2. Set Header
3. Init Menu
4. Init Favorite
5. Init Fix Product Border
6. Init Isotope Filtering
7. Init Price Slider
8. Init Checkboxes



******************************/

jQuery(document).ready(function ($) {
    "use strict";

    /* Initialization of essential variables */
    let cartItems = JSON.parse(localStorage.getItem("cartItems")) || [];

    const header = $(".header");
    const topNav = $(".top_nav");
    const hamburger = $(".hamburger_container");
    const menu = $(".hamburger_menu");
    const hamburgerClose = $(".hamburger_close");
    const fsOverlay = $(".fs_menu_overlay");

    let menuActive = false;

    setHeader();
    updateCartPanel(); // Update cart on page load

    $(window).on("resize", function () {
        setHeader();
    });

    $(document).on("scroll", function () {
        setHeader();
    });

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

    /* Init Menu */
    hamburger.on("click", function () {
        if (!menuActive) openMenu();
    });

    fsOverlay.on("click", function () {
        if (menuActive) closeMenu();
    });

    hamburgerClose.on("click", function () {
        if (menuActive) closeMenu();
    });

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

    /* Add to Cart */
    function addToCart(itemName, price, image = "images/fallback.png", description = "") {
        cartItems.push({ name: itemName, price: price, image: image, description: description });
        localStorage.setItem("cartItems", JSON.stringify(cartItems));
        updateCartPanel();
        const checkoutItems = document.getElementById("checkout_items");
        if (checkoutItems) checkoutItems.textContent = cartItems.length;
        toggleCart();
    }

    /* Remove from Cart */
    function removeItemFromCart(itemName) {
        cartItems = cartItems.filter((item) => item.name !== itemName);
        localStorage.setItem("cartItems", JSON.stringify(cartItems));
        updateCartPanel();
        const checkoutItems = document.getElementById("checkout_items");
        if (checkoutItems) checkoutItems.textContent = cartItems.length;
    }

    /* Toggle Cart Panel */
    function toggleCart() {
        const cartPanel = document.getElementById("cart-panel");
        if (cartPanel) cartPanel.classList.toggle("active");
    }

    /* Update Cart Panel */
    function updateCartPanel() {
        const cartItemsContainer = document.getElementById("cart-items-container");
        if (!cartItemsContainer) return;

        cartItemsContainer.innerHTML = ""; // Clear existing items

        cartItems.forEach((item) => {
            const cartItem = document.createElement("div");
            cartItem.classList.add("cart-item");

            cartItem.innerHTML = `
                <div class="cart-item-content">
                    <img src="${item.image}" alt="${item.name}" class="cart-item-image" 
                         onerror="this.onerror=null; this.src='images/fallback.png';">
                    <div class="cart-item-details">
                        <h4 class="product-name">${item.name}</h4>
                        <p class="product-description">${item.description}</p>
                        <p class="product-price">$${item.price.toFixed(2)}</p>
                        <button class="remove-item" onclick="removeItemFromCart('${item.name}')">Remove</button>
                        <button class="book-now" onclick="bookNow('${item.name}')">Book Now</button>
                    </div>
                </div>
            `;

            cartItemsContainer.appendChild(cartItem);
        });
    }

    /* Load Cart Items (for book.php) */
    function loadCartItems() {
        const savedCartItems = JSON.parse(localStorage.getItem("cartItems")) || [];
        const cartItemsContainer = document.getElementById("cart-items-container");
        if (!cartItemsContainer) return;

        savedCartItems.forEach((item) => {
            const cartItem = document.createElement("div");
            cartItem.classList.add("cart-item");

            const imageSrc = item.image || "images/fallback.png";

            cartItem.innerHTML = `
                <div class="cart-item-content">
                    <img src="${imageSrc}" alt="${item.name}" class="cart-item-image"
                         onerror="this.onerror=null; this.src='images/fallback.png';">
                    <p class="product-name">${item.name}</p>
                    <p class="product-price">$${item.price.toFixed(2)}</p>
                </div>
            `;

            cartItemsContainer.appendChild(cartItem);
        });
    }

    /* Book Now */
    function bookNow(itemName) {
        localStorage.setItem("selectedItem", itemName);
        window.location.href = "book.php";
    }

    /* Category Filtering */
    $(".sidebar_categories a").click(function (e) {
        e.preventDefault();
        const filter = $(this).data("filter");

        if (filter === "*") {
            $(".product-item").show();
        } else {
            $(".product-item").each(function () {
                $(this).toggle($(this).hasClass(filter.substr(1)));
            });
        }
    });
});


// 

