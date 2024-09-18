<?php
include('../include/headscript.php');
include("../class/company.php");

$conn = new mysqli($servername, $username, $password, $database);
$idcompoundsvocabulary = $_POST['idcompoundsvocabulary'];

// Cancella il componente principale
$query1 = "DELETE FROM compundsvocabulary WHERE idcompoundsvocabulary = $idcompoundsvocabulary";

// Cancella i sinonimi del componente
$query2 = "DELETE FROM compundsvocabulary WHERE refid = $idcompoundsvocabulary";

if ($conn->query($query1) && $conn->query($query2)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => true]);
}
