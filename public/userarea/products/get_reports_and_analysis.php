<?php
include('../include/headscript.php');
include("../class/company.php");

$conn = new mysqli($servername, $username, $password, $database);

if (isset($_POST['productId'])) {
    $productId = $_POST['productId'];

    // Query per ottenere i report associati al prodotto
    $queryReports = "SELECT * FROM reports WHERE idproducts = $productId";
    $resultReports = $conn->query($queryReports);

    $reports = [];
    while ($report = $resultReports->fetch_assoc()) {
        // Query per ottenere le analisi associate a ogni report con LEFT JOIN su analysisvocabulary
        $reportId = $report['idreports'];
        $queryAnalysis = "
            SELECT rp.*, av.nameanalysisvoc
            FROM result_project rp
            LEFT JOIN analysisvocabulary av ON rp.result_TestName = av.idanalysisvocabulary
            WHERE rp.idreports = $reportId";
        $resultAnalysis = $conn->query($queryAnalysis);

        // Mappa per aggregare i risultati delle analisi in base a 'result_TestName' e calcolare il peggior rating
        $analysisGrouped = [];
        while ($analysis = $resultAnalysis->fetch_assoc()) {
            $testName = $analysis['nameanalysisvoc'];

            // Normalizza il rating per confrontare varianti diverse
            $normalizedRating = strtoupper(trim($analysis['test_Rating']));

            // Gestiamo anche varianti come "Complies", "Doesn't Comply", "//" e "N/A"
            if ($normalizedRating === 'P' || $normalizedRating === 'PASS' || $normalizedRating === 'COMPLIES') {
                $normalizedRating = 'PASS';
            } elseif ($normalizedRating === 'F' || $normalizedRating === 'FAIL' || $normalizedRating === 'DOESN\'T COMPLY') {
                $normalizedRating = 'FAIL';
            } elseif ($normalizedRating === '//' || $normalizedRating === 'N/A') {
                $normalizedRating = '//';
            } else {
                $normalizedRating = 'N/A';  // Per valori non riconosciuti
            }

            // Se l'analisi per questo test è già stata trovata, confronta i rating e prendi il peggiore
            if (isset($analysisGrouped[$testName])) {
                $currentRating = $analysisGrouped[$testName]['finalRating'];
                // Logica di priorità: Fail > // > Pass
                if ($currentRating === 'PASS' && ($normalizedRating === 'FAIL' || $normalizedRating === '//')) {
                    $analysisGrouped[$testName]['finalRating'] = $normalizedRating;
                } elseif ($currentRating === '//' && $normalizedRating === 'FAIL') {
                    $analysisGrouped[$testName]['finalRating'] = 'FAIL';  // Peggiora il rating
                }
            } else {
                // Prima occorrenza dell'analisi, aggiungila alla mappa
                $analysisGrouped[$testName] = [
                    'name' => $testName,
                    'finalRating' => $normalizedRating
                ];
            }
        }

        // Aggiungi le analisi raggruppate al report
        $report['analysis'] = array_values($analysisGrouped);  // Converte la mappa in un array
        $reports[] = $report;
    }

    // Restituisci i report e le analisi in formato JSON
    echo json_encode(['reports' => $reports]);
} else {
    echo json_encode(['error' => 'Product ID not provided']);
}
