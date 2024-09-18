<?php include('../include/headscript.php'); ?>
<?php include("../class/company.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idanalysisvocabulary = $_POST['idanalysisvocabulary'];
    $conn = new mysqli($servername, $username, $password, $database);
    $query = "DELETE FROM analysisvocabulary WHERE idanalysisvocabulary = $idanalysisvocabulary";
    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => true]);
    }
}
?>
