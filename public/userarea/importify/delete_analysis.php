<?php
include('../include/headscript.php');
include("../class/company.php");

$conn = new mysqli($servername, $username, $password, $database);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idanalysisvocabulary = $_POST['idanalysisvocabulary'];

    // Cancella l'analisi principale
    $query1 = "DELETE FROM analysisvocabulary WHERE idanalysisvocabulary = $idanalysisvocabulary";

    // Cancella tutti i sinonimi collegati
    $query2 = "DELETE FROM analysisvocabulary WHERE refid = $idanalysisvocabulary";

    if ($conn->query($query1) && $conn->query($query2)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => true]);
    }
}
