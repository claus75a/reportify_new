<?php include('../include/headscript.php'); ?>
<?php include("../class/company.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idanalysisvocabulary = $_POST['idanalysisvocabulary'];
    $conn = new mysqli($servername, $username, $password, $database);
    $query = "SELECT idanalysisvocabulary, nameanalysisvoc FROM analysisvocabulary WHERE refid = $idanalysisvocabulary AND preferred = 'N'";
    $result = $conn->query($query);

    $synonyms = [];
    while ($row = $result->fetch_assoc()) {
        $synonyms[] = $row;
    }

    echo json_encode($synonyms);
}
