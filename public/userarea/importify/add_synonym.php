<?php
include('../include/headscript.php');
include("../class/company.php");

$conn = new mysqli($servername, $username, $password, $database);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['nameanalysisvoc'];
    $refid = $_POST['refid'];
    $preferred = $_POST['preferred'];

    $query = "INSERT INTO analysisvocabulary (nameanalysisvoc, refid, preferred) VALUES ('$name', $refid, '$preferred')";

    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => $conn->error]);
    }
}
