<?php
include('../include/headscript.php');
include("../class/company.php");

$conn = new mysqli($servername, $username, $password, $database);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idcompoundsvocabulary = $_POST['idcompoundsvocabulary'];

    $query = "DELETE FROM compundsvocabulary WHERE idcompoundsvocabulary = $idcompoundsvocabulary";

    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => true]);
    }
}
