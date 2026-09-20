<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ธีระภัทร เพียช่อ (ไวท์)</title>
</head>
<body>
    <h1>ธีระภัทร เพียช่อ (ไวท์)</h1>    
    <form method="post" action="">
        ชื่อสินค้า <input type="text" name="pname" require> <br>
        ราคาสินค้า <input type="number" name="pprice" require> <br>

        <button type="submit" name = "Submit">บันทึก</button>

    <?php 
    if(isset($_POST['Submit'])){

        require_once '../connectdb.php';

        $pname = $_POST['pname'];
        $pprice = $_POST['pprice'];
        $sql = "INSERT INTO products(product_name,category_id,price) VALUES('{$pname}','1','{$pprice}')";
        mysqli_query($conn,$sql) or die("เพิ่มข้อมูลไม่ได้");

        echo "<script>";
        echo "alert('บันทึกข้อมูลเรียบร้อย')";
        echo "</script>";

    }
    ?>






</body>
</html>