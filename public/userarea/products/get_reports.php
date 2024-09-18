<?php
include('../include/headscript.php');
include("../class/company.php");

$conn = new mysqli($servername, $username, $password, $database);
$productId = $_POST['productId'];

$query = "SELECT * FROM reports WHERE idproducts = $productId";
$result = $conn->query($query);

$reports = [];
while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}
echo json_encode($reports);
