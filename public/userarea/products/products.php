<?php include('../include/headscript.php'); ?>
<?php include("../class/company.php"); ?>

<?php
$conn = new mysqli($servername, $username, $password, $database);

// Query per ottenere tutti i prodotti
$query = "SELECT * FROM products";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Products</title>

    <!-- Includi prima jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Includi DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- Includi DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- Altri riferimenti al CSS e JS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
</head>

<style>
    .bg-danger {
        background-color: #ff4d4d !important;
        /* Rosso per fallimenti */
    }

    .bg-success {
        background-color: #28a745 !important;
        /* Verde per successi */
    }

    .bg-warning {
        background-color: #ffc107 !important;
        /* Giallo per N/A o // */
    }

    .text-white {
        color: white !important;
    }

    .text-dark {
        color: black !important;
    }
</style>

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
                                    <h4 class="page-title">Products</h4>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="productsTable" class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Product Ref</th>
                                                        <th>Description</th>
                                                        <th>Style</th>
                                                        <th>Color</th>
                                                        <th>Season</th>
                                                        <th>Market</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                                        <tr data-productid="<?php echo $row['idproducts']; ?>">
                                                            <td><?php echo $row['products_refnumber']; ?></td>
                                                            <td><?php echo $row['products_description']; ?></td>
                                                            <td><?php echo $row['products_style']; ?></td>
                                                            <td><?php echo $row['products_color']; ?></td>
                                                            <td><?php echo $row['products_season']; ?></td>
                                                            <td><?php echo $row['products_market']; ?></td>
                                                            <td>
                                                                <button class="btn btn-info btn-sm show-reports">Reports</button>
                                                            </td>
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

    <script>
        $(document).ready(function() {
            // Inizializza DataTables con filtri per le colonne
            var table = $('#productsTable').DataTable({
                responsive: true,
                "pageLength": 50,
                "order": [
                    [1, 'asc']
                ], // Ordina per descrizione
                initComplete: function() {
                    // Aggiungi i filtri per ogni colonna
                    this.api().columns().every(function() {
                        var column = this;
                        var input = $('<input class="form-control form-control-sm" type="text" placeholder="Search"/>')
                            .appendTo($(column.header()))
                            .on('keyup change clear', function() {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });
                    });
                }
            });

            // Gestione del click su "Reports" per visualizzare la tabella report e analisi
            $('#productsTable').on('click', '.show-reports', function() {
                var tr = $(this).closest('tr');
                var productId = tr.data('productid');
                var row = $('#productsTable').DataTable().row(tr);
                var button = $(this);

                console.log('Loading reports and analysis for productId:', productId); // Debug

                button.prop('disabled', true);
                button.html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                // Se la riga child è già visibile, non facciamo nulla
                if (row.child.isShown()) {
                    button.prop('disabled', false);
                    button.html('Reports');
                    return;
                }

                // Carica i report e le analisi insieme tramite AJAX
                $.ajax({
                    url: 'get_reports_and_analysis.php', // Nuovo script PHP per ottenere sia i report che le analisi
                    type: 'POST',
                    data: {
                        productId: productId
                    },
                    success: function(data) {
                        console.log('Reports and Analysis data loaded:', data); // Debug
                        row.child(formatReportsAndAnalysis(data)).show();
                        tr.addClass('shown');
                    },
                    complete: function() {
                        button.prop('disabled', false);
                        button.html('Reports');
                    },
                    error: function() {
                        Swal.fire('Error!', 'There was an error loading the reports and analysis.', 'error');
                        button.prop('disabled', false);
                        button.html('Reports');
                    }
                });
            });

            // Funzione per formattare i report e le analisi nella stessa tabella
            function formatReportsAndAnalysis(data) {
                var parsedData = JSON.parse(data);
                var reports = parsedData.reports;
                var html = '<table class="table table-bordered child-table">';
                html += '<thead><tr><th>Report Number</th><th>Report Date</th><th>Rating</th><th>Analysis (Name)</th><th>Final Rating</th><th>Action</th></tr></thead>';
                html += '<tbody>';

                // Per ogni report, aggiungi il report e le analisi associate
                $.each(reports, function(index, report) {
                    // Riga del report principale
                    html += '<tr>';
                    html += '<td>' + report.reportsNumberLab + '</td>';
                    html += '<td>' + report.reportDateIn + '</td>';
                    html += '<td>' + report.reportsRating + '</td>';
                    html += '<td colspan="2"></td>'; // Righe vuote per mantenere l'allineamento
                    html += '<td><button class="btn btn-primary btn-sm report-details" data-reportid="' + report.idreports + '">Details</button></td>'; // Aggiungi il bottone Details

                    // Se ci sono analisi associate al report, le aggiungi sotto lo stesso report
                    if (report.analysis.length > 0) {
                        $.each(report.analysis, function(i, analysis) {
                            var ratingClass = ''; // Classe CSS per la colorazione della cella
                            if (analysis.finalRating === 'FAIL') {
                                ratingClass = 'bg-danger text-white'; // Colore rosso per i fallimenti
                            } else if (analysis.finalRating === 'PASS') {
                                ratingClass = 'bg-success text-white'; // Colore verde per i successi
                            } else if (analysis.finalRating === '//' || analysis.finalRating === 'N/A') {
                                ratingClass = 'bg-warning text-dark'; // Colore giallo per risultati ambigui
                            }

                            html += '<tr>';
                            html += '<td></td>'; // Lascia vuoto per mantenere l'allineamento del report
                            html += '<td></td>'; // Lascia vuoto per mantenere l'allineamento del report
                            html += '<td></td>'; // Lascia vuoto per mantenere l'allineamento del report
                            html += '<td>' + (analysis.name ? analysis.name : 'N/A') + '</td>'; // Nome dell'analisi
                            html += '<td class="' + ratingClass + '">' + (analysis.finalRating ? analysis.finalRating : 'N/A') + '</td>';
                            html += '<td></td>'; // Lascia vuoto per non aggiungere il bottone "Details" nella riga dell'analisi
                            html += '</tr>';
                        });
                    } else {
                        html += '<tr><td colspan="5">No analysis available for this report.</td></tr>';
                    }
                    html += '</tr>';
                });

                html += '</tbody></table>';
                return html;
            }



        });

        $(document).on('click', '.report-details', function() {
            var reportId = $(this).data('reportid'); // Ottieni l'id del report
            window.location.href = 'reportdetails.php?idreports=' + reportId; // Reindirizza alla pagina reportdetails.php con idreports
        });
    </script>

</body>

</html>