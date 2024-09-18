<?php
include('../include/headscript.php');
include("../class/company.php");

$conn = new mysqli($servername, $username, $password, $database);

if (isset($_POST['reportId'])) {
    $reportId = $_POST['reportId'];

    // Query per ottenere i dati delle analisi
    $query = "SELECT * FROM result_project WHERE idreports = $reportId";
    $result = $conn->query($query);

    $analysis = [];
    while ($row = $result->fetch_assoc()) {
        $analysis[] = $row;
    }

    echo json_encode($analysis);
} else {
    echo json_encode([]);
}
