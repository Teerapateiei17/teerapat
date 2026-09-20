<?php
$host = "localhost" ;
$usr = "root" ; 
$pwd = "" ;
$db = "8032db" ;
$conn = mysqli_connect($host,$usr,$pwd,$db) ; 

$sql = "SELECT * FROM products ";
$rs = mysqli_query($conn,$sql) ;

echo "<h1>ธีระภัทร เพียช่อ(ไวท์)</h1>";

while ($data = mysqli_fetch_array($rs)) {
    echo $data['product_name']." ------ ".$data['price']. "<br>" ;
}

?>