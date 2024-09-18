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

// Query per ottenere le parti e i risultati del report con il nome dell'analista e delle parti
$queryPartsAndResults = "
    SELECT rp.*, 
           a.nameanalysisvoc AS testName, 
           cv.namecompoundsvocabulary AS analytsName, 
           pr.partsDescription 
    FROM result_project rp
    LEFT JOIN analysisvocabulary a ON rp.result_TestName = a.idanalysisvocabulary
    LEFT JOIN compundsvocabulary cv ON rp.result_AnalytsName = cv.idcompoundsvocabulary
    LEFT JOIN parts pr ON rp.idPart = pr.idParts
    WHERE rp.idreports = ?
    ORDER BY rp.result_TestName, rp.idPart";
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
    <style>
        .section-separator {
            background-color: #f1f3f5;
            font-weight: bold;
            padding: 10px;
            margin-top: 20px;
            border-top: 3px solid #6c757d;
        }

        .table-part {
            margin-top: 10px;
            border: 2px solid #dee2e6;
        }

        .rating-pass {
            background-color: #28a745;
            color: white;
        }

        .rating-fail {
            background-color: #dc3545;
            color: white;
        }

        .rating-ambiguous {
            background-color: #ffc107;
            color: black;
        }

        .report-header {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
        }

        .report-header h5 {
            margin-bottom: 10px;
        }

        .report-details-table {
            width: 100%;
        }

        .report-details-table td {
            padding: 5px;
        }

        .table-filter {
            margin-bottom: 20px;
        }

        .fixed-width-analyts {
            width: 40%;
            /* Modifica questo valore per adattare la larghezza */
        }

        .fixed-width-value {
            width: 30%;
            /* Modifica questo valore per adattare la larghezza */
        }

        .fixed-width-rating {
            width: 30%;
            /* Modifica questo valore per adattare la larghezza */
        }
    </style>
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
                                        <div class="report-header">
                                            <h5>Report: <?php echo $reportDetails['reportsNumberLab']; ?></h5>
                                            <table class="report-details-table">
                                                <tr>
                                                    <td><strong>Report Date:</strong></td>
                                                    <td><?php echo $reportDetails['reportDateIn']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Product Ref:</strong></td>
                                                    <td><?php echo $reportDetails['products_refnumber']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Product Description:</strong></td>
                                                    <td><?php echo $reportDetails['products_description']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Style:</strong></td>
                                                    <td><?php echo $reportDetails['products_style']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Color:</strong></td>
                                                    <td><?php echo $reportDetails['products_color']; ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtri -->
                        <!-- Aggiungi questo filtro sopra la tabella -->
                        <div class="row table-filter">
                            <div class="col-md-4">
                                <input type="text" id="searchTestName" class="form-control" placeholder="Search by Test Name">
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="searchPart" class="form-control" placeholder="Search by Part">
                            </div>
                            <div class="col-md-4">
                                <select id="searchRating" class="form-control">
                                    <option value="">All Ratings</option>
                                    <option value="pass" class="rating-pass">Pass</option>
                                    <option value="fail" class="rating-fail">Fail</option>
                                    <option value="ambiguous" class="rating-ambiguous">N/A or Ambiguous</option>
                                </select>
                            </div>
                        </div>


                        <!-- Tabella con Parti e Risultati -->
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="header-title pb-3 mt-0">Parts and Results</h5>
                                        <div class="table-responsive">
                                            <?php

                                            $currentTestName = '';
                                            $currentPart = '';

                                            while ($row = $partsAndResults->fetch_assoc()) {
                                                // Debug: Stampa i dati di ogni riga
                                                // Questo serve per stampare i risultati in modo più leggibile e debuggare

                                                $previousPart = '';


                                                // Se il nome dell'analisi cambia, crea una nuova sezione
                                                if ($currentTestName != $row['testName']) {
                                                    if ($currentTestName != '') {
                                                        echo '</tbody></table>'; // Chiude la tabella precedente
                                                    }
                                                    $currentTestName = $row['testName'];
                                                    echo '<div class="section-separator">Analysis: ' . $currentTestName . '</div>';
                                                }

                                                // Verifica se la parte corrente è diversa dalla precedente per evitare duplicazioni
                                                $currentPart = $row['partsDescription'] ?? 'Unknown Part';
                                                if ($currentPart != $previousPart) {
                                                    // Stampa il titolo della parte solo se cambia
                                                    echo '<h6>Part: ' . (!empty($row['partsDescription']) ? $currentPart : 'Part Not Specified') . '</h6>';

                                                    // Apri una nuova tabella per la parte
                                                    echo '<table class="table table-bordered table-part"><thead>';
                                                    echo '<tr><th class="fixed-width-analyts">Analyts Name</th><th class="fixed-width-value">Result Value</th><th class="fixed-width-rating">Rating</th></tr>';
                                                    echo '</thead><tbody>';


                                                    // Aggiorna la variabile di controllo per la parte
                                                    $previousPart = $currentPart;
                                                }

                                                // Classificazione del rating
                                                $ratingClass = '';
                                                if (strtoupper($row['test_Rating']) == 'FAIL' || strtoupper($row['test_Rating']) == "DOESN'T COMPLY") {
                                                    $ratingClass = 'rating-fail';
                                                } elseif (strtoupper($row['test_Rating']) == 'PASS' || strtoupper($row['test_Rating']) == 'COMPLIES') {
                                                    $ratingClass = 'rating-pass';
                                                } else {
                                                    $ratingClass = 'rating-ambiguous';
                                                }

                                                // Stampa i dettagli della riga
                                                echo '<tr>';
                                                echo '<td>' . (!empty($row['analytsName']) ? $row['analytsName'] . ' (ID: ' . $row['result_AnalytsName'] . ')' : '&nbsp;') . '</td>';
                                                echo '<td>' . (!empty($row['result_Value']) ? htmlspecialchars($row['result_Value'], ENT_QUOTES, 'UTF-8') : '&nbsp;') . '</td>';
                                                echo '<td class="' . $ratingClass . '">' . (!empty($row['test_Rating']) ? htmlspecialchars($row['test_Rating'], ENT_QUOTES, 'UTF-8') : '&nbsp;') . '</td>';
                                                echo '</tr>';
                                            }
                                            if ($currentTestName != '') {
                                                echo '</tbody></table>'; // Chiude l'ultima tabella
                                            }
                                            ?>
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
    <script>
        $(document).ready(function() {
            // Filtro per Test Name
            $('#searchTestName').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('.section-separator').each(function() {
                    var section = $(this);
                    if (section.text().toLowerCase().includes(value)) {
                        section.show();
                        section.nextUntil('.section-separator').show();
                    } else {
                        section.hide();
                        section.nextUntil('.section-separator').hide();
                    }
                });
            });

            // Filtro per Part Name
            $('#searchPart').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('h6').each(function() {
                    var part = $(this);
                    if (part.text().toLowerCase().includes(value)) {
                        part.show();
                        part.next('table').show();
                    } else {
                        part.hide();
                        part.next('table').hide();
                    }
                });
            });

            // Filtro per Rating
            $('#searchRating').on('change', function() {
                var selectedRating = $(this).val().toLowerCase(); // Prende il rating selezionato

                $('table.table-part').each(function() {
                    var table = $(this);
                    var visibleRows = 0;

                    // Controlla ogni riga della tabella
                    table.find('tbody tr').each(function() {
                        var ratingCell = $(this).find('td:last-child'); // L'ultima cella è quella del rating
                        var ratingText = ratingCell.text().trim().toLowerCase();

                        // Mostra/nasconde la riga in base al filtro di rating
                        if (selectedRating === '' ||
                            (selectedRating === 'pass' && (ratingText === 'pass' || ratingText === 'complies')) ||
                            (selectedRating === 'fail' && (ratingText === 'fail' || ratingText === "doesn't comply")) ||
                            (selectedRating === 'ambiguous' && (ratingText === 'n/a' || ratingText === 'ambiguous' || ratingText === '//'))) {
                            $(this).show();
                            visibleRows++;
                        } else {
                            $(this).hide();
                        }
                    });

                    // Nasconde l'intera tabella se non ci sono righe visibili
                    if (visibleRows === 0) {
                        table.hide();
                        table.prev('h6').hide(); // Nasconde anche il titolo della parte
                    } else {
                        table.show();
                        table.prev('h6').show(); // Mostra il titolo della parte
                    }
                });
            });
        });
    </script>
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

    <script>
        $(document).ready(function() {
            // Filtro per Test Name
            $('#searchTestName').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('.section-separator').each(function() {
                    var section = $(this);
                    if (section.text().toLowerCase().includes(value)) {
                        section.show();
                        section.nextUntil('.section-separator').show();
                    } else {
                        section.hide();
                        section.nextUntil('.section-separator').hide();
                    }
                });
            });

            // Filtro per Part Name
            $('#searchPart').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('h6').each(function() {
                    var part = $(this);
                    if (part.text().toLowerCase().includes(value)) {
                        part.show();
                        part.next('table').show();
                    } else {
                        part.hide();
                        part.next('table').hide();
                    }
                });
            });

            // Filtro per Rating
            $('#searchRating').on('change', function() {
                var selectedRating = $(this).val().toLowerCase(); // Prende il rating selezionato
                $('table.table-part tbody tr').each(function() {
                    var ratingCell = $(this).find('td:last-child'); // L'ultima cella dovrebbe essere quella del rating
                    var ratingText = ratingCell.text().trim().toLowerCase();

                    // Se l'utente non seleziona alcun valore (tutti i rating) o il rating corrisponde
                    if (selectedRating === '' ||
                        (selectedRating === 'pass' && (ratingText === 'pass' || ratingText === 'complies')) ||
                        (selectedRating === 'fail' && (ratingText === 'fail' || ratingText === "doesn't comply")) ||
                        (selectedRating === 'ambiguous' && (ratingText === 'n/a' || ratingText === 'ambiguous' || ratingText === '//'))) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>

</body>

</html>