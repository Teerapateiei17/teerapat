<?php

// ==========================================
// DATABASE CONNECTION
// ==========================================

$host = "localhost";
$usr  = "root";
$pwd  = "";
$db   = "8032db";

$conn = mysqli_connect($host, $usr, $pwd, $db);

if (!$conn) {
    die("เชื่อมต่อฐานข้อมูลไม่สำเร็จ : " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");


// ==========================================
// FOLDER UPLOAD
// ==========================================

// ตำแหน่งเก็บรูปจริง
$upload_dir = "C:/xampp/htdocs/f/img/";

// Path ที่จะเก็บลง Database
$db_image_path = "img/";


// ==========================================
// MESSAGE
// ==========================================

$message = "";
$message_type = "";


// ==========================================
// INSERT PRODUCT
// ==========================================

if (isset($_POST['Submit'])) {

    // รับค่าจากฟอร์ม
    $product_name = trim($_POST['product_name']);
    $category_id  = intval($_POST['category_id']);
    $description  = trim($_POST['description']);
    $price        = floatval($_POST['price']);
    $stock        = intval($_POST['stock']);
    $status       = $_POST['status'];


    // ======================================
    // ตรวจสอบข้อมูล
    // ======================================

    if ($product_name == "") {

        $message = "กรุณากรอกชื่อสินค้า";
        $message_type = "danger";

    } elseif ($category_id <= 0) {

        $message = "กรุณาเลือกประเภทสินค้า";
        $message_type = "danger";

    } elseif ($price < 0) {

        $message = "ราคาสินค้าไม่ถูกต้อง";
        $message_type = "danger";

    } elseif ($stock < 0) {

        $message = "จำนวนสินค้าไม่ถูกต้อง";
        $message_type = "danger";

    } else {


        // ======================================
        // IMAGE UPLOAD
        // ======================================

        $image_path = "";


        if (isset($_FILES['image']) &&
            $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {


            // ตรวจสอบ Error
            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {

                $message = "เกิดข้อผิดพลาดในการอัปโหลดรูป";
                $message_type = "danger";

            } else {

                $file_tmp  = $_FILES['image']['tmp_name'];
                $file_name = $_FILES['image']['name'];
                $file_size = $_FILES['image']['size'];


                // ==================================
                // ตรวจสอบนามสกุล
                // ==================================

                $allowed_types = [
                    "jpg",
                    "jpeg",
                    "png",
                    "webp"
                ];

                $extension = strtolower(
                    pathinfo($file_name, PATHINFO_EXTENSION)
                );


                if (!in_array($extension, $allowed_types)) {

                    $message = "รองรับเฉพาะ JPG, JPEG, PNG และ WEBP";
                    $message_type = "danger";

                }


                // ==================================
                // ตรวจสอบขนาด
                // สูงสุด 5 MB
                // ==================================

                elseif ($file_size > 5 * 1024 * 1024) {

                    $message = "ขนาดรูปภาพต้องไม่เกิน 5 MB";
                    $message_type = "danger";

                }


                // ==================================
                // ตรวจสอบว่าเป็นรูปจริง
                // ==================================

                elseif (getimagesize($file_tmp) === false) {

                    $message = "ไฟล์ที่อัปโหลดไม่ใช่รูปภาพ";
                    $message_type = "danger";

                }


                else {


                    // ==================================
                    // สร้างชื่อไฟล์ใหม่
                    // ==================================

                    $new_file_name =
                        uniqid("product_", true)
                        . "."
                        . $extension;


                    $destination =
                        $upload_dir
                        . $new_file_name;


                    // ==================================
                    // ย้ายรูปไปโฟลเดอร์ img
                    // ==================================

                    if (move_uploaded_file(
                        $file_tmp,
                        $destination
                    )) {

                        // path สำหรับเก็บใน database
                        $image_path =
                            $db_image_path
                            . $new_file_name;

                    } else {

                        $message = "ไม่สามารถบันทึกรูปภาพได้";
                        $message_type = "danger";

                    }

                }

            }

        }


        // ======================================
        // INSERT DATABASE
        // ======================================

        if ($message == "") {


            $sql = "
                INSERT INTO products
                (
                    category_id,
                    product_name,
                    description,
                    price,
                    stock,
                    image_url,
                    status
                )

                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                )
            ";


            $stmt = mysqli_prepare($conn, $sql);


            if ($stmt) {


                mysqli_stmt_bind_param(
                    $stmt,
                    "issdiss",
                    $category_id,
                    $product_name,
                    $description,
                    $price,
                    $stock,
                    $image_path,
                    $status
                );


                if (mysqli_stmt_execute($stmt)) {

                    $message =
                        "เพิ่มสินค้าเรียบร้อยแล้ว";

                    $message_type = "success";


                    // ล้างค่าฟอร์ม
                    $product_name = "";
                    $description = "";
                    $price = "";
                    $stock = "";


                } else {

                    // ถ้า INSERT ไม่สำเร็จ
                    // ลบรูปที่เพิ่งอัปโหลด
                    if (
                        $image_path != "" &&
                        file_exists(
                            $upload_dir .
                            basename($image_path)
                        )
                    ) {

                        unlink(
                            $upload_dir .
                            basename($image_path)
                        );

                    }


                    $message =
                        "เกิดข้อผิดพลาด : "
                        . mysqli_stmt_error($stmt);

                    $message_type = "danger";

                }


                mysqli_stmt_close($stmt);


            } else {

                $message =
                    "ไม่สามารถเตรียมคำสั่ง SQL ได้ : "
                    . mysqli_error($conn);

                $message_type = "danger";

            }

        }

    }

}


// ==========================================
// GET CATEGORIES
// ==========================================

$category_sql = "
    SELECT *
    FROM categories
    ORDER BY category_id
";

$category_rs =
    mysqli_query(
        $conn,
        $category_sql
    );

?>

<!DOCTYPE html>

<html lang="th">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        เพิ่มสินค้า | Liverpool FC Store
    </title>


    <!-- =====================================
         BOOTSTRAP 5 CDN
    ====================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <style>

        body {

            background-color: #f3f3f3;

            font-family:
                Arial,
                "Noto Sans Thai",
                sans-serif;

        }


        /* Navbar */

        .navbar-liverpool {

            background-color: #c8102e;

        }


        .navbar-brand {

            font-weight: bold;

        }


        /* Card */

        .form-card {

            border: none;

            border-radius: 15px;

            box-shadow:
                0 8px 25px
                rgba(0, 0, 0, 0.10);

        }


        /* Header */

        .form-header {

            background-color: #c8102e;

            color: white;

            padding: 25px;

            border-radius:
                15px 15px 0 0;

        }


        .form-header h2 {

            margin: 0;

            font-weight: bold;

        }


        /* Label */

        .form-label {

            font-weight: bold;

        }


        /* Input */

        .form-control,
        .form-select {

            border-radius: 8px;

        }


        .form-control:focus,
        .form-select:focus {

            border-color: #c8102e;

            box-shadow:
                0 0 0 0.2rem
                rgba(200, 16, 46, 0.15);

        }


        /* Button */

        .btn-liverpool {

            background-color: #c8102e;

            color: white;

            border: none;

            padding: 10px 25px;

            border-radius: 8px;

            font-weight: bold;

        }


        .btn-liverpool:hover {

            background-color: #a50d26;

            color: white;

        }


        .btn-reset {

            border-radius: 8px;

            padding: 10px 25px;

        }


        /* Image preview */

        #preview {

            width: 100%;

            max-width: 300px;

            height: 220px;

            object-fit: contain;

            border-radius: 10px;

            border: 1px solid #ddd;

            background-color: #f8f8f8;

            display: none;

            margin-top: 15px;

        }


        /* Footer */

        footer {

            background-color: #171717;

            color: white;

            text-align: center;

            padding: 25px;

            margin-top: 50px;

        }

    </style>

</head>


<body>


<!-- =========================================
     NAVBAR
========================================== -->

<nav class="navbar navbar-expand-lg navbar-dark navbar-liverpool">

    <div class="container">


        <a
            class="navbar-brand"
            href="#"
        >

            🔴 Liverpool FC Store

        </a>


        <div>

            <a
                href="../index.php"
                class="btn btn-light btn-sm"
            >

                หน้าร้าน

            </a>

        </div>


    </div>

</nav>



<!-- =========================================
     MAIN
========================================== -->

<div class="container py-5">


    <div class="row justify-content-center">

        <div class="col-lg-8">


            <!-- CARD -->

            <div class="card form-card">


                <!-- HEADER -->

                <div class="form-header">

                    <h2>

                        ➕ เพิ่มสินค้า

                    </h2>

                    <p class="mb-0 mt-2">

                        เพิ่มข้อมูลสินค้าเข้าสู่ระบบ

                    </p>

                </div>



                <div class="card-body p-4">


                    <!-- =================================
                         MESSAGE
                    ================================== -->

                    <?php if ($message != "") { ?>

                        <div
                            class="alert alert-<?php
                                echo $message_type;
                            ?> alert-dismissible fade show"
                        >

                            <?php
                            echo htmlspecialchars(
                                $message
                            );
                            ?>


                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                            ></button>

                        </div>

                    <?php } ?>



                    <!-- =================================
                         FORM
                    ================================== -->

                    <form
                        method="POST"
                        enctype="multipart/form-data"
                    >


                        <!-- =============================
                             PRODUCT NAME
                        ============================== -->

                        <div class="mb-3">

                            <label
                                class="form-label"
                            >

                                ชื่อสินค้า
                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <input
                                type="text"
                                name="product_name"
                                class="form-control"
                                placeholder="เช่น Liverpool FC Home Jersey 2025/26"
                                value="<?php
                                    echo isset($product_name)
                                        ? htmlspecialchars(
                                            $product_name
                                        )
                                        : '';
                                ?>"
                                required
                            >

                        </div>



                        <!-- =============================
                             CATEGORY
                        ============================== -->

                        <div class="mb-3">

                            <label
                                class="form-label"
                            >

                                ประเภทสินค้า
                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <select
                                name="category_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    -- เลือกประเภทสินค้า --
                                </option>


                                <?php

                                while (
                                    $category =
                                    mysqli_fetch_assoc(
                                        $category_rs
                                    )
                                ) {

                                ?>

                                    <option
                                        value="<?php
                                            echo $category[
                                                'category_id'
                                            ];
                                        ?>"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $category[
                                                'category_name'
                                            ]
                                        );

                                        ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>



                        <!-- =============================
                             DESCRIPTION
                        ============================== -->

                        <div class="mb-3">

                            <label
                                class="form-label"
                            >

                                รายละเอียดสินค้า

                            </label>


                            <textarea
                                name="description"
                                class="form-control"
                                rows="4"
                                placeholder="รายละเอียดสินค้า เช่น สี ขนาด หรือรายละเอียดเพิ่มเติม"
                            ><?php

                            echo isset($description)
                                ? htmlspecialchars(
                                    $description
                                )
                                : '';

                            ?></textarea>

                        </div>



                        <!-- =============================
                             PRICE + STOCK
                        ============================== -->

                        <div class="row">


                            <!-- PRICE -->

                            <div class="col-md-6 mb-3">

                                <label
                                    class="form-label"
                                >

                                    ราคา
                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <div class="input-group">

                                    <span class="input-group-text">
                                        ฿
                                    </span>


                                    <input
                                        type="number"
                                        name="price"
                                        class="form-control"
                                        placeholder="0.00"
                                        step="0.01"
                                        min="0"
                                        value="<?php
                                            echo isset($price)
                                                ? htmlspecialchars(
                                                    $price
                                                )
                                                : '';
                                        ?>"
                                        required
                                    >

                                </div>

                            </div>



                            <!-- STOCK -->

                            <div class="col-md-6 mb-3">

                                <label
                                    class="form-label"
                                >

                                    จำนวนสินค้า
                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <div class="input-group">

                                    <span class="input-group-text">
                                        ชิ้น
                                    </span>


                                    <input
                                        type="number"
                                        name="stock"
                                        class="form-control"
                                        placeholder="0"
                                        min="0"
                                        value="<?php
                                            echo isset($stock)
                                                ? htmlspecialchars(
                                                    $stock
                                                )
                                                : '';
                                        ?>"
                                        required
                                    >

                                </div>

                            </div>

                        </div>



                        <!-- =============================
                             IMAGE
                        ============================== -->

                        <div class="mb-3">

                            <label
                                class="form-label"
                            >

                                รูปสินค้า
                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <input
                                type="file"
                                name="image"
                                id="image"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.webp"
                                onchange="previewImage(event)"
                                required
                            >


                            <div
                                class="form-text"
                            >

                                รองรับ JPG, JPEG, PNG, WEBP
                                ขนาดไม่เกิน 5 MB

                            </div>


                            <!-- Preview -->

                            <img
                                id="preview"
                                alt="Preview"
                            >

                        </div>



                        <!-- =============================
                             STATUS
                        ============================== -->

                        <div class="mb-4">

                            <label
                                class="form-label"
                            >

                                สถานะสินค้า

                            </label>


                            <select
                                name="status"
                                class="form-select"
                            >

                                <option
                                    value="active"
                                    selected
                                >

                                    เปิดขาย

                                </option>


                                <option
                                    value="inactive"
                                >

                                    ปิดขาย

                                </option>

                            </select>

                        </div>



                        <!-- =============================
                             BUTTON
                        ============================== -->

                        <div class="d-flex gap-2">


                            <button
                                type="submit"
                                name="Submit"
                                class="btn btn-liverpool"
                            >

                                💾 บันทึกสินค้า

                            </button>


                            <button
                                type="reset"
                                class="btn btn-secondary btn-reset"
                                onclick="clearPreview()"
                            >

                                ↻ ล้างข้อมูล

                            </button>


                            <a
                                href="../index.php"
                                class="btn btn-outline-dark btn-reset"
                            >

                                ยกเลิก

                            </a>


                        </div>


                    </form>


                </div>

            </div>

        </div>

    </div>

</div>



<!-- =========================================
     FOOTER
========================================== -->

<footer>

    <h5>

        🔴 Liverpool FC Store

    </h5>

    <p class="mb-0">

        ธีระภัทร เพียช่อ (ไวท์)

    </p>

</footer>



<!-- =========================================
     BOOTSTRAP JS
========================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>



<!-- =========================================
     IMAGE PREVIEW
========================================== -->

<script>

function previewImage(event) {

    const file =
        event.target.files[0];

    const preview =
        document.getElementById("preview");


    if (file) {

        preview.src =
            URL.createObjectURL(file);

        preview.style.display =
            "block";

    }

}


function clearPreview() {

    const preview =
        document.getElementById("preview");

    preview.src = "";

    preview.style.display =
        "none";

}

</script>


</body>

</html>


<?php

mysqli_close($conn);

?>