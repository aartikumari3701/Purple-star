<header class="header trans_300">
    <!-- Top Navigation -->
    <div class="top_nav">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="top_nav_left">Free shipping on all orders</div>
                </div>
                <div class="col-md-6 text-right">
                    <div class="top_nav_right">
                        <ul class="top_nav_menu">
                            <!-- Currency -->
                            <li class="currency">
                                <a href="#" aria-label="Select currency">
                                    USD <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="currency_selection">
                                    <li><a href="#">CAD</a></li>
                                    <li><a href="#">AUD</a></li>
                                    <li><a href="#">EUR</a></li>
                                    <li><a href="#">GBP</a></li>
                                </ul>
                            </li>
                            <!-- Language -->
                            <li class="language">
                                <a href="#" aria-label="Select language">
                                    English <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="language_selection">
                                    <li><a href="#">French</a></li>
                                    <li><a href="#">Italian</a></li>
                                    <li><a href="#">German</a></li>
                                    <li><a href="#">Spanish</a></li>
                                </ul>
                            </li>
                            <!-- Account -->
                            <li class="account">
                                <a href="#" aria-label="My account">
                                    My Account <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="account_selection">
                                    <li><a href="logout.php"><i class="fa fa-sign-in" aria-hidden="true"></i> Sign In</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <div class="main_nav_container">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-right">
                    <div class="logo_container">
                        <a href="index.php">Purple<span>Star</span></a>
                    </div>
                    <nav class="navbar">
                        <ul class="navbar_menu">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="categories.php">Shop</a></li>
                            <li><a href="text-book.php">Books</a></li>
                            <li class="menu_item">
                                <a href="#">Pages</a>
                                <ul class="dropdown">
                                    <li><a href="#">Page 1</a></li>
                                    <li><a href="#">Page 2</a></li>
                                    <li><a href="#">Page 3</a></li>
                                </ul>
                            </li>
                            <li><a href="contact.php">Contact</a></li>
                        </ul>
                        <ul class="navbar_user">
                            <li><a href="#" aria-label="Search"><i class="fa fa-search"></i></a></li>
                            <li><a href="#" aria-label="User account"><i class="fa fa-user"></i></a></li>
                            <li class="checkout">
                                <a href="#" onclick="toggleCart()" aria-label="View cart">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span id="checkout_items" class="checkout_items">0</span>
                                </a>
                            </li>
                            <div id="cart-panel" class="cart-panel">
                                <h3>Your Cart</h3>
                                <div id="cart-items-container"></div>
                                <button class="close-cart" onclick="toggleCart()">Close</button>
                            </div>
                        </ul>
                        <div class="hamburger_container">
                            <i class="fa fa-bars" aria-hidden="true" aria-label="Toggle menu"></i>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    // Dynamically update cart items count
    function updateCartCount(count) {
        document.getElementById('checkout_items').textContent = count;
    }

    // Example: Fetch cart count from localStorage or API
    document.addEventListener("DOMContentLoaded", function() {
        const cartCount = localStorage.getItem('cartCount') || 0;
        updateCartCount(cartCount);
    });

    // Toggle cart visibility
    function toggleCart() {
        const cartPanel = document.getElementById('cart-panel');
        cartPanel.classList.toggle('active');
    }
</script>
