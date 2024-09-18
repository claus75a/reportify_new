<?php
include('../include/headscript.php');
include("../class/company.php");

$conn = new mysqli($servername, $username, $password, $database);
$refid = $_POST['refid'];

$query = "SELECT * FROM compundsvocabulary WHERE refid = $refid AND preferred = 'N'";
$result = $conn->query($query);

$synonyms = [];
while ($row = $result->fetch_assoc()) {
    $synonyms[] = $row;
}

echo json_encode($synonyms);
