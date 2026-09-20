<?php

$host = "localhost";
$usr  = "root";
$pwd  = "";
$db   = "8032db";

$conn = mysqli_connect($host, $usr, $pwd, $db);

if (!$conn) {
    die("เชื่อมต่อฐานข้อมูลไม่สำเร็จ");
}

mysqli_set_charset($conn, "utf8mb4");

// ดึงประเภทสินค้า
$category_sql = "SELECT * FROM categories ORDER BY category_id";
$category_rs = mysqli_query($conn, $category_sql);

// ดึงสินค้าทั้งหมดพร้อมประเภท
$product_sql = "
    SELECT 
        products.*,
        categories.category_name
    FROM products
    LEFT JOIN categories
        ON products.category_id = categories.category_id
    WHERE products.status = 'active'
    ORDER BY products.product_id
";

$product_rs = mysqli_query($conn, $product_sql);

?>

<!DOCTYPE html>
<html lang="th">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Liverpool FC Store</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        /* Navbar */
        .navbar-liverpool {
            background-color: #c8102e;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 24px;
        }

        /* Hero */
        .hero {
            background: linear-gradient(
                rgba(200, 16, 46, 0.95),
                rgba(120, 0, 20, 0.95)
            );

            color: white;
            padding: 60px 20px;
            text-align: center;
        }

        .hero h1 {
            font-weight: bold;
        }

        /* Filter */
        .filter-box {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .filter-title {
            font-weight: bold;
            color: #c8102e;
        }

        .filter-btn {
            border: 2px solid #c8102e;
            color: #c8102e;
            background-color: white;
            margin: 5px;
            border-radius: 25px;
            padding: 8px 20px;
            transition: 0.3s;
        }

        .filter-btn:hover {
            background-color: #c8102e;
            color: white;
        }

        .filter-btn.active {
            background-color: #c8102e;
            color: white;
        }

        /* Card */
        .product-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: 0.3s;
            height: 100%;
            background-color: white;
        }

        .product-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .product-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .card-title {
            font-weight: bold;
            min-height: 48px;
        }

        .price {
            color: #c8102e;
            font-size: 20px;
            font-weight: bold;
        }

        .stock {
            color: #666;
            font-size: 14px;
        }

        .btn-liverpool {
            background-color: #c8102e;
            color: white;
            border: none;
        }

        .btn-liverpool:hover {
            background-color: #a50d26;
            color: white;
        }

        /* Footer */
        footer {
            background-color: #171717;
            color: white;
            margin-top: 50px;
            padding: 30px;
            text-align: center;
        }

    </style>

</head>

<body>


<!-- ================= NAVBAR ================= -->

<nav class="navbar navbar-expand-lg navbar-dark navbar-liverpool">

    <div class="container">

        <a class="navbar-brand" href="#">
            🔴 LIVERPOOL FC STORE
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
        >

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        หน้าแรก
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#products">
                        สินค้า
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>


<!-- ================= HERO ================= -->

<section class="hero">

    <div class="container">

        <h1>
            Liverpool FC Official Store
        </h1>

        <p class="lead">
            รวมสินค้าและของที่ระลึกสำหรับแฟนหงส์แดง
        </p>

        <a href="#products" class="btn btn-light btn-lg">
            ดูสินค้าทั้งหมด
        </a>

    </div>

</section>


<!-- ================= PRODUCTS ================= -->

<div class="container py-5" id="products">

    <h2 class="text-center mb-4 fw-bold">
        🛒 สินค้าของเรา
    </h2>


    <!-- ================= FILTER ================= -->

    <div class="filter-box text-center mb-5">

        <h5 class="filter-title mb-3">
            เลือกหมวดหมู่สินค้า
        </h5>


        <!-- ทั้งหมด -->

        <button
            class="btn filter-btn active"
            onclick="filterProducts('all', this)"
        >
            ทั้งหมด
        </button>


        <?php while ($category = mysqli_fetch_assoc($category_rs)) { ?>

            <button
                class="btn filter-btn"
                onclick="filterProducts(
                    'category-<?php echo $category['category_id']; ?>',
                    this
                )"
            >

                <?php echo htmlspecialchars($category['category_name']); ?>

            </button>

        <?php } ?>

    </div>


    <!-- ================= PRODUCT GRID ================= -->

    <div class="row g-4" id="product-container">


        <?php while ($data = mysqli_fetch_assoc($product_rs)) { ?>


            <div
                class="col-12 col-sm-6 col-lg-3 product-item category-<?php echo $data['category_id']; ?>"
            >

                <div class="card product-card shadow-sm">


                    <!-- รูปสินค้า -->

                    <img src="img/<?php echo $data['image_url']; ?>" class="card-img-top" alt="">


                    <div class="card-body d-flex flex-column">


                        <!-- ชื่อ -->

                        <h5 class="card-title">

                            <?php
                            echo htmlspecialchars($data['product_name']);
                            ?>

                        </h5>


                        <!-- รายละเอียด -->

                        <p class="card-text text-muted">

                            <?php
                            echo htmlspecialchars($data['description']);
                            ?>

                        </p>


                        <!-- ราคา -->

                        <div class="price mb-2">

                            ฿<?php
                            echo number_format($data['price'], 2);
                            ?>

                        </div>


                        <!-- จำนวนสินค้า -->

                        <div class="stock mb-3">

                            เหลือ <?php echo $data['stock']; ?> ชิ้น

                        </div>


                        <!-- ปุ่ม -->

                        <a
                            href="#"
                            class="btn btn-liverpool mt-auto"
                        >

                            🛒 ซื้อสินค้า

                        </a>


                    </div>

                </div>

            </div>


        <?php } ?>


    </div>

</div>


<!-- ================= FOOTER ================= -->

<footer>

    <h5>
        Liverpool FC Store
    </h5>

    <p class="mb-0">
        © 2026 Liverpool FC Store |
        ธีระภัทร เพียช่อ (ไวท์)
    </p>

</footer>


<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<!-- ================= FILTER SCRIPT ================= -->

<script>

function filterProducts(category, button) {

    // เปลี่ยนปุ่ม Active

    let buttons = document.querySelectorAll(".filter-btn");

    buttons.forEach(function(btn) {
        btn.classList.remove("active");
    });

    button.classList.add("active");


    // ดึง Card สินค้าทั้งหมด

    let products = document.querySelectorAll(".product-item");


    products.forEach(function(product) {

        if (category === "all") {

            product.style.display = "";

        } else {

            if (product.classList.contains(category)) {

                product.style.display = "";

            } else {

                product.style.display = "none";

            }

        }

    });

}

</script>


</body>

</html>

<?php

mysqli_close($conn);

?>