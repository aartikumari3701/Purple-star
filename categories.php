<?php
session_start();
// Database connection
$conn = new mysqli('localhost', 'root', '', 'login_register',3308);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$price_min = isset($_GET['price_min']) ? $_GET['price_min'] : 0;
$price_max = isset($_GET['price_max']) ? $_GET['price_max'] : 10000;

$sql = "SELECT * FROM products WHERE price BETWEEN $price_min AND $price_max";
if ($category_filter) {
    $sql .= " AND category = '$category_filter'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Categories</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Colo Shop Template">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/animate.css">
<link rel="stylesheet" type="text/css" href="plugins/jquery-ui-1.12.1.custom/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="styles/categories_styles.css">
<link rel="stylesheet" type="text/css" href="styles/categories_responsive.css">
</head>

<body>

<div class="super_container">

    <!-- Header -->

    <!-- Navbar Start -->
    <?php include 'include/navbar.php';
 include 'dbconnection.php';  ?>


    <div class="fs_menu_overlay"></div>

    <!-- Hamburger Menu -->

    <div class="hamburger_menu">
        <div class="hamburger_close"><i class="fa fa-times" aria-hidden="true"></i></div>
        <div class="hamburger_menu_content text-right">
            <ul class="menu_top_nav">
                <li class="menu_item has-children">
                    <a href="#">
                        usd
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="menu_selection">
                        <li><a href="#">cad</a></li>
                        <li><a href="#">aud</a></li>
                        <li><a href="#">eur</a></li>
                        <li><a href="#">gbp</a></li>
                    </ul>
                </li>
                <li class="menu_item has-children">
                    <a href="#">
                        English
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="menu_selection">
                        <li><a href="#">French</a></li>
                        <li><a href="#">Italian</a></li>
                        <li><a href="#">German</a></li>
                        <li><a href="#">Spanish</a></li>
                    </ul>
                </li>
                <li class="menu_item has-children">
                    <a href="#">
                        My Account
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="menu_selection">
                        <li><a href="logout.php"><i class="fa fa-sign-in" aria-hidden="true"></i>Sign In</a></li>
                    </ul>
                </li>
                <li class="menu_item"><a href="#">home</a></li>
                <li class="menu_item"><a href="categories.php">shop</a></li>
                <li class="menu_item"><a href="#">promotion</a></li>
                <li class="menu_item">
                    <a href="#">Pages</a>
                    <ul class="dropdown">
                        <li><a href="#">Page 1</a></li>
                        <li><a href="#">Page 2</a></li>
                        <li><a href="#">Page 3</a></li>
                    </ul>
                </li>
                <li class="menu_item"><a href="single.php">blog</a></li>
                <li class="menu_item"><a href="contact.php">contact</a></li>
            </ul>
        </div>
    </div>

    <div class="container product_section_container">
        <div class="row">
            <div class="col product_section clearfix">

                <!-- Breadcrumbs -->

                <div class="breadcrumbs d-flex flex-row align-items-center">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li class="active"><a href="index.php"><i class="fa fa-angle-right" aria-hidden="true"></i>Items</a></li>
                    </ul>
                </div>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Product Category -->
                    <div class="sidebar_section">
                        <div class="sidebar_title">
                            <h5>Product Category</h5>
                        </div>
                        <ul class="sidebar_categories">
                            <li><a href="categories.php">All</a></li>
                            <li><a href="categories.php?category=pens">Pens</a></li>
                            <li><a href="categories.php?category=papers">Papers</a></li>
                            <li><a href="categories.php?category=notebooks">Notebooks</a></li>
                            <li><a href="categories.php?category=office-supplies">Office Supplies</a></li>
                            <li><a href="categories.php?category=writing-tools">Writing Tools</a></li>
                            <li><a href="categories.php?category=other-stationery">Other Stationery</a></li>
                        </ul>
                    </div>

                    <!-- Price Range Filtering -->
                    <div class="sidebar_section">
                        <div class="sidebar_title">
                            <h5>Filter by Price</h5>
                        </div>
                        <p>
                            <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
                        </p>
                        <div id="slider-range"></div>
                        <div class="filter_button">
                            <span>Filter</span>
                        </div>
                    </div>
                </div>
                                

                <!-- Main Content -->

                <div class="main_content">

                    <!-- Products -->
                    <div class="products_iso">
                        <div class="row">
                            <div class="col">

                                <!-- Product Sorting -->
                                <div class="product_sorting_container product_sorting_container_top">
                                    <ul class="product_sorting">
                                        <li>
                                            <span class="type_sorting_text">Default Sorting</span>
                                            <i class="fa fa-angle-down"></i>
                                            <ul class="sorting_type">
                                                <li class="type_sorting_btn" data-isotope-option='{ "sortBy": "original-order" }'><span>Default Sorting</span></li>
                                                <li class="type_sorting_btn" data-isotope-option='{ "sortBy": "price" }'><span>Price</span></li>
                                                <li class="type_sorting_btn" data-isotope-option='{ "sortBy": "name" }'><span>Product Name</span></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Product Grid -->
                                <div class="product-grid">
                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                    <!-- Product Item -->
                                    <div class="product-item <?php echo $row['category']; ?>">
                                        <div class="product discount product_filter">
                                            <div class="product_image">
                                                <img src="images/<?php echo $row['image']; ?>" alt="">
                                            </div>
                                            <div class="favorite favorite_left"></div>
                                            <div class="product_bubble product_bubble_right product_bubble_red d-flex flex-column align-items-center"><span>Sale</span></div>
                                            <div class="product_info">
                                                <h6 class="product_name"><a href="single.php?id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></h6>
                                                <div class="product_price">$<?php echo number_format($row['price'], 2); ?></div>
                                            </div>
                                        </div>
                                        <div class="red_button add_to_cart_button"><a href="#" onclick="addToCart('<?php echo $row['name']; ?>', <?php echo $row['price']; ?>)">add to cart</a></div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script src="plugins/Isotope/isotope.pkgd.min.js"></script>
<script src="plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="plugins/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="js/categories_custom.js"></script>

</body>
</html>
