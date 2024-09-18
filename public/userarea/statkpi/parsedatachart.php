<?php include('../../Connections/repnew.php'); ?>
<?php
$conn = new mysqli($servername, $username, $password, $database);

// Ottieni i filtri dal POST
$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : '';
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : '';
$supplierFilter = isset($_POST['supplier']) ? $_POST['supplier'] : '';

// Creazione della condizione dei filtri di data e supplier
$filters = "WHERE 1=1";
if (!empty($startDate) && !empty($endDate)) {
    $filters .= " AND r.reportsDateOut BETWEEN '$startDate' AND '$endDate'";
}
if (!empty($supplierFilter)) {
    $filters .= " AND p.namesupplier = '$supplierFilter'";
}

// Statistic 1: Total number of products (filtered by supplier if necessary)
$totalProductsQuery = "SELECT COUNT(DISTINCT p.idproducts) AS totalProducts FROM products p";
if (!empty($supplierFilter)) {
    $totalProductsQuery .= " WHERE p.namesupplier = '$supplierFilter'";
}
$totalProductsResult = $conn->query($totalProductsQuery);
$totalProducts = $totalProductsResult->fetch_assoc()['totalProducts'];

// Statistic 2: Total number of reports
$totalReportsQuery = "
    SELECT COUNT(DISTINCT r.idreports) AS totalReports 
    FROM reports r 
    LEFT JOIN products p ON r.idproducts = p.idproducts 
    $filters
";
$totalReportsResult = $conn->query($totalReportsQuery);
$totalReports = $totalReportsResult->fetch_assoc()['totalReports'];

// Statistic 3: Number of 'fail' reports and percentage compared to total
$failedReportsQuery = "
    SELECT COUNT(DISTINCT r.idreports) AS failedReports 
    FROM reports r 
    LEFT JOIN products p ON r.idproducts = p.idproducts 
    $filters AND UPPER(r.reportsRating) IN ('FAIL', 'F', 'DOESN\'T COMPLY')
";
$failedReportsResult = $conn->query($failedReportsQuery);
$failedReports = $failedReportsResult->fetch_assoc()['failedReports'];
$failedReportsPercent = ($totalReports > 0) ? ($failedReports / $totalReports) * 100 : 0;

// Statistic 4: Total number of tests performed (distinct tests)
$totalTestsQuery = "
    SELECT COUNT(DISTINCT rp.idreports, rp.idPart, rp.result_TestName) AS totalTests 
    FROM result_project rp 
    LEFT JOIN reports r ON rp.idreports = r.idreports 
    LEFT JOIN products p ON r.idproducts = p.idproducts 
    $filters
";
$totalTestsResult = $conn->query($totalTestsQuery);
$totalTests = $totalTestsResult->fetch_assoc()['totalTests'];

// Statistic 5: Number of 'fail' tests and percentage (case-insensitive for rating fail)
$failedTestsQuery = "
    SELECT COUNT(DISTINCT rp.idreports, rp.idPart, rp.result_TestName) AS failedTests 
    FROM result_project rp 
    LEFT JOIN reports r ON rp.idreports = r.idreports 
    LEFT JOIN products p ON r.idproducts = p.idproducts 
    $filters AND UPPER(rp.result_Rating) IN ('FAIL', 'F', 'DOESN\'T COMPLY')
";
$failedTestsResult = $conn->query($failedTestsQuery);
$failedTests = $failedTestsResult->fetch_assoc()['failedTests'];
$failedTestsPercent = ($totalTests > 0) ? ($failedTests / $totalTests) * 100 : 0;

// Pie Chart Data for Reports (Fail, Pass, Others)
$failReportsPieQuery = "
    SELECT COUNT(*) AS failReports 
    FROM reports r 
    LEFT JOIN products p ON r.idproducts = p.idproducts 
    $filters AND UPPER(r.reportsRating) IN ('FAIL', 'F', 'DOESN\'T COMPLY')
";
$failReportsPieResult = $conn->query($failReportsPieQuery);
$failReportsPie = $failReportsPieResult->fetch_assoc()['failReports'];

$passReportsPieQuery = "
    SELECT COUNT(*) AS passReports 
    FROM reports r 
    LEFT JOIN products p ON r.idproducts = p.idproducts 
    $filters AND UPPER(r.reportsRating) IN ('PASS', 'P', 'COMPLIES')
";
$passReportsPieResult = $conn->query($passReportsPieQuery);
$passReportsPie = $passReportsPieResult->fetch_assoc()['passReports'];

$otherReportsPieQuery = "
    SELECT COUNT(*) AS otherReports 
    FROM reports r 
    LEFT JOIN products p ON r.idproducts = p.idproducts 
    $filters AND UPPER(r.reportsRating) NOT IN ('FAIL', 'F', 'DOESN\'T COMPLY', 'PASS', 'P', 'COMPLIES')
";
$otherReportsPieResult = $conn->query($otherReportsPieQuery);
$otherReportsPie = $otherReportsPieResult->fetch_assoc()['otherReports'];

// Query to get the top 10 analyses with the most 'Fail' results
$topFailingAnalysisQuery = "
    SELECT a.nameanalysisvoc AS analysisName, COUNT(DISTINCT rp.idreports, rp.idPart, rp.result_TestName) AS failCount 
    FROM result_project rp 
    LEFT JOIN reports r ON rp.idreports = r.idreports 
    LEFT JOIN products p ON r.idproducts = p.idproducts 
    LEFT JOIN analysisvocabulary a ON rp.result_TestName = a.idanalysisvocabulary 
    $filters AND UPPER(rp.result_Rating) IN ('FAIL', 'F', 'DOESN\'T COMPLY') 
    GROUP BY rp.result_TestName 
    ORDER BY failCount DESC 
    LIMIT 10
";
$topFailingAnalysisResult = $conn->query($topFailingAnalysisQuery);
$topFailingAnalysis = [];
while ($row = $topFailingAnalysisResult->fetch_assoc()) {
    $analysisName = (strlen($row['analysisName']) > 80) ? substr($row['analysisName'], 0, 80) . '...' : $row['analysisName'];
    $topFailingAnalysis[] = ['name' => $analysisName, 'failCount' => $row['failCount']];
}

// Statistic for worst suppliers based on % of failed reports
$worstSuppliersQuery = "
    SELECT p.namesupplier AS supplier, COUNT(r.idreports) AS totalReports, 
    SUM(CASE WHEN UPPER(r.reportsRating) IN ('FAIL', 'F', 'DOESN\'T COMPLY') THEN 1 ELSE 0 END) AS failedReports, 
    (SUM(CASE WHEN UPPER(r.reportsRating) IN ('FAIL', 'F', 'DOESN\'T COMPLY') THEN 1 ELSE 0 END) / COUNT(r.idreports)) * 100 AS failPercentage 
    FROM reports r 
    LEFT JOIN products p ON r.idproducts = p.idproducts 
    $filters 
    GROUP BY p.namesupplier 
    ORDER BY failPercentage DESC 
    LIMIT 10
";
$worstSuppliersResult = $conn->query($worstSuppliersQuery);
$worstSuppliers = [];
while ($row = $worstSuppliersResult->fetch_assoc()) {
    $worstSuppliers[] = [
        'supplier' => $row['supplier'],
        'failPercentage' => round($row['failPercentage'], 2),
        'totalReports' => $row['totalReports'],
        'failedReports' => $row['failedReports']
    ];
}

// Statistic for products by suppliers
$productBySupplierQuery = "
    SELECT p.namesupplier AS supplier, COUNT(p.idproducts) AS totalProducts 
    FROM products p 
    WHERE p.namesupplier IS NOT NULL 
    GROUP BY p.namesupplier 
    ORDER BY totalProducts DESC";
$productBySupplierResult = $conn->query($productBySupplierQuery);
$productBySupplier = [];
while ($row = $productBySupplierResult->fetch_assoc()) {
    $productBySupplier[] = [
        'supplier' => $row['supplier'],
        'totalProducts' => $row['totalProducts']
    ];
}

// Ora controlliamo se è una richiesta AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Rispondi ai dati aggiornati tramite AJAX
    echo json_encode([
        'totalProducts' => $totalProducts,
        'totalReports' => $totalReports,
        'failedReports' => $failedReports,
        'failedReportsPercent' => $failedReportsPercent,
        'totalTests' => $totalTests,
        'failedTests' => $failedTests,
        'failedTestsPercent' => $failedTestsPercent,
        'failReportsPie' => $failReportsPie,
        'passReportsPie' => $passReportsPie,
        'otherReportsPie' => $otherReportsPie,
        'topFailingAnalysis' => $topFailingAnalysis,
        'worstSuppliers' => $worstSuppliers,
        'productBySupplier' => $productBySupplier
    ]);
    exit; // Ferma l'esecuzione del resto dello script dopo aver risposto all'AJAX
}
