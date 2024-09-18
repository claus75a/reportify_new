<?php
include('../include/headscript.php');
include("../class/company.php");

$conn = new mysqli($servername, $username, $password, $database);

// Recupera il valore di reportId dal POST
$reportId = isset($_POST['reportId']) ? $_POST['reportId'] : 0;

// Query per ottenere le analisi associate al report, comprese le parti
$query = "
    SELECT a.nameanalysisvoc AS name, rp.result_Rating AS finalRating
    FROM result_project rp
    LEFT JOIN analysisvocabulary a ON rp.result_TestName = a.idanalysisvocabulary
    WHERE rp.idreports = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $reportId);
$stmt->execute();
$result = $stmt->get_result();

// Array per raccogliere le analisi e le relative valutazioni (rating)
$analysis = [];

// Raggruppa le analisi e seleziona il rating più restrittivo
while ($row = $result->fetch_assoc()) {
    $name = $row['name'];
    $rating = strtoupper($row['finalRating']); // Normalizziamo tutto in maiuscolo per uniformità

    if (!isset($analysis[$name])) {
        // Se l'analisi non è ancora presente, la aggiungiamo
        $analysis[$name] = $rating;
    } else {
        // Applica il rating più restrittivo: Fail > N/A > Pass
        if ($analysis[$name] != 'FAIL' && ($rating == 'FAIL' || $rating == "DOESN'T COMPLY")) {
            $analysis[$name] = 'FAIL';
        } elseif ($analysis[$name] != 'FAIL' && $analysis[$name] != 'N/A' && ($rating == 'N/A' || $rating == '//')) {
            $analysis[$name] = 'N/A';
        } elseif ($analysis[$name] == 'PASS' && ($rating == 'PASS' || $rating == 'COMPLIES')) {
            $analysis[$name] = 'PASS';
        }
    }
}

// Trasforma i dati in un array
$finalAnalysis = [];
foreach ($analysis as $name => $finalRating) {
    $finalAnalysis[] = [
        'name' => $name,
        'finalRating' => $finalRating
    ];
}

// Restituisci i dati come JSON
echo json_encode($finalAnalysis);
