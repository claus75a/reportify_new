<?php include('../include/headscript.php'); ?>
<?php include("../class/company.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['nameanalysisvoc'];
    $kind = $_POST['kindanalysisvoc'];
    $conn = new mysqli($servername, $username, $password, $database);
    $query = "INSERT INTO analysisvocabulary (nameanalysisvoc, kindanalysisvoc, preferred) VALUES ('$name', '$kind', 'Y')";
    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => true]);
    }
}
?>
