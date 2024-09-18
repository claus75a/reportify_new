<?php include('../include/headscript.php'); ?>
<?php include("../class/company.php");

$conn = new mysqli($servername, $username, $password, $database);

// Recupera il valore di idreports dalla query string
$idreports = isset($_GET['idreports']) ? $_GET['idreports'] : 0;

// Query per ottenere i dettagli del report e del prodotto associato
$queryReportDetails = "
    SELECT r.*, p.products_refnumber, p.products_description, p.products_style, p.products_color
    FROM reports r
    LEFT JOIN products p ON r.idproducts = p.idproducts
    WHERE r.idreports = ?";
$stmt = $conn->prepare($queryReportDetails);
$stmt->bind_param("i", $idreports);
$stmt->execute();
$reportDetails = $stmt->get_result()->fetch_assoc();

// Query per ottenere le parti e i risultati del report
$queryPartsAndResults = "
    SELECT rp.*, a.nameanalysisvoc AS testName, av.nameanalysisvoc AS analytsName
    FROM result_project rp
    LEFT JOIN analysisvocabulary a ON rp.result_TestName = a.idanalysisvocabulary
    LEFT JOIN analysisvocabulary av ON rp.result_AnalytsName = av.idanalysisvocabulary
    WHERE rp.idreports = ?";
$stmtParts = $conn->prepare($queryPartsAndResults);
$stmtParts->bind_param("i", $idreports);
$stmtParts->execute();
$partsAndResults = $stmtParts->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Report Details</title>

    <!-- Altri riferimenti al CSS e JS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="../assets/js/jquery.min.js"></script>
    <link rel="stylesheet" href="../assets/plugins/select2/select2.min.css">
    <script src="../assets/plugins/select2/select2.min.js"></script>
</head>


<body class="fixed-left">

    <div id="wrapper">
        <?php include('../include/navigationbar.php'); ?>
        <div class="content-page">
            <div class="content">
                <?php include('../include/topbar.php'); ?>
                <div class="page-content-wrapper">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">Report Details</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Dati del Report e del Prodotto -->
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="header-title pb-3 mt-0">Report: <?php echo $reportDetails['reportsNumberLab']; ?></h5>
                                        <p><strong>Report Date:</strong> <?php echo $reportDetails['reportDateIn']; ?></p>
                                        <p><strong>Product Ref:</strong> <?php echo $reportDetails['products_refnumber']; ?></p>
                                        <p><strong>Product Description:</strong> <?php echo $reportDetails['products_description']; ?></p>
                                        <p><strong>Style:</strong> <?php echo $reportDetails['products_style']; ?></p>
                                        <p><strong>Color:</strong> <?php echo $reportDetails['products_color']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabella con Parti e Risultati -->
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="header-title pb-3 mt-0">Parts and Results</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Part Number</th>
                                                        <th>Test Name</th>
                                                        <th>Analyts Name</th>
                                                        <th>Result Value</th>
                                                        <th>Rating</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($row = $partsAndResults->fetch_assoc()) { ?>
                                                        <tr>
                                                            <td><?php echo $row['partNumber']; ?></td>
                                                            <td><?php echo $row['testName']; ?></td>
                                                            <td><?php echo $row['analytsName']; ?></td>
                                                            <td><?php echo $row['result_Value']; ?></td>
                                                            <td><?php echo $row['test_Rating']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div><!-- container -->
                </div><!-- Page content Wrapper -->
            </div><!-- content -->
            <?php include('../include/footer.php'); ?>
        </div>
    </div>

    <!-- plugin JS  -->
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/modernizr.min.js"></script>
    <script src="../assets/js/detect.js"></script>
    <script src="../assets/js/fastclick.js"></script>
    <script src="../assets/js/jquery.slimscroll.js"></script>
    <script src="../assets/js/jquery.blockUI.js"></script>
    <script src="../assets/js/waves.js"></script>
    <script src="../assets/js/jquery.nicescroll.js"></script>
    <script src="../assets/js/jquery.scrollTo.min.js"></script>
    <script src="../assets/js/common_helper.js"></script>

    <script src="../assets/plugins/chart.js/chart.min.js"></script>
    <script src="../assets/pages/dashboard.js"></script>

    <!-- App js -->
    <script src="../assets/js/app.js"></script>
    <script src="../assets/plugins/alertify/js/alertify.js"></script>

</body>

</html>