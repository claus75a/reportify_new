<?php include('../include/headscript.php'); ?>
<?php include("../class/company.php");

$conn = new mysqli($servername, $username, $password, $database);

// Query per ottenere tutti i report e i prodotti associati
$query = "
    SELECT r.*, p.products_refnumber, p.products_description
    FROM reports r
    LEFT JOIN products p ON r.idproducts = p.idproducts";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Reports</title>

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
                                    <h4 class="page-title">Reports</h4>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="reportsTable" class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Report Number</th>
                                                        <th>Report Date</th>
                                                        <th>Product Ref</th>
                                                        <th>Product Description</th>
                                                        <th>Rating</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                                        <tr data-reportid="<?php echo $row['idreports']; ?>">
                                                            <td><?php echo $row['reportsNumberLab']; ?></td>
                                                            <td><?php echo $row['reportDateIn']; ?></td>
                                                            <td><?php echo $row['products_refnumber']; ?></td>
                                                            <td><?php echo $row['products_description']; ?></td>
                                                            <td><?php echo $row['reportsRating']; ?></td>
                                                            <td>
                                                                <button class="btn btn-info btn-sm show-analysis">Analysis</button>
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
            var table = $('#reportsTable').DataTable({
                responsive: true,
                "pageLength": 50,
                "order": [
                    [0, 'asc']
                ], // Ordina per numero di report
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

            // Gestione del click su "Analysis" per visualizzare la tabella delle analisi
            $('#reportsTable').on('click', '.show-analysis', function() {
                var tr = $(this).closest('tr');
                var reportId = tr.data('reportid');
                var row = $('#reportsTable').DataTable().row(tr);
                var button = $(this);

                console.log('Loading analysis for reportId:', reportId); // Debug

                button.prop('disabled', true);
                button.html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                // Se la riga child è già visibile, non facciamo nulla
                if (row.child.isShown()) {
                    button.prop('disabled', false);
                    button.html('Analysis');
                    return;
                }

                // Carica le analisi tramite AJAX
                $.ajax({
                    url: 'get_analysis_by_report.php', // Nuovo script PHP per ottenere le analisi del report
                    type: 'POST',
                    data: {
                        reportId: reportId
                    },
                    success: function(data) {
                        console.log('Analysis data loaded:', data); // Debug
                        row.child(formatAnalysis(data)).show();
                        tr.addClass('shown');
                    },
                    complete: function() {
                        button.prop('disabled', false);
                        button.html('Analysis');
                    },
                    error: function() {
                        Swal.fire('Error!', 'There was an error loading the analysis.', 'error');
                        button.prop('disabled', false);
                        button.html('Analysis');
                    }
                });
            });

            // Funzione per formattare le analisi
            function formatAnalysis(data) {
                var parsedData = JSON.parse(data);
                var html = '<table class="table table-bordered child-table">';
                html += '<thead><tr><th>Analysis Name</th><th>Final Rating</th></tr></thead>';
                html += '<tbody>';

                // Per ogni analisi, aggiungi la riga corrispondente
                $.each(parsedData, function(index, analysis) {
                    var ratingClass = ''; // Classe CSS per la colorazione della cella
                    if (analysis.finalRating === 'FAIL') {
                        ratingClass = 'bg-danger text-white'; // Colore rosso per i fallimenti
                    } else if (analysis.finalRating === 'PASS') {
                        ratingClass = 'bg-success text-white'; // Colore verde per i successi
                    } else if (analysis.finalRating === '//' || analysis.finalRating === 'N/A') {
                        ratingClass = 'bg-warning text-dark'; // Colore giallo per risultati ambigui
                    }

                    html += '<tr>';
                    html += '<td>' + (analysis.name ? analysis.name : 'N/A') + '</td>';
                    html += '<td class="' + ratingClass + '">' + (analysis.finalRating ? analysis.finalRating : 'N/A') + '</td>';
                    html += '</tr>';
                });

                html += '</tbody></table>';
                return html;
            }

        });
    </script>

</body>

</html>