<?php include('../include/headscript.php'); ?>
<?php include("../class/company.php"); ?>

<?php
$conn = new mysqli($servername, $username, $password, $database);
$analysisrefid = $_GET['analysisrefid']; // Ottieni l'ID dell'analisi dalla query string

// Recupera i componenti associati all'analisi selezionata
$query = "SELECT * FROM compundsvocabulary WHERE analysisrefid = $analysisrefid AND preferred = 'Y'";
$result = $conn->query($query);

// Recupera il nome dell'analisi
$analysisQuery = "SELECT nameanalysisvoc FROM analysisvocabulary WHERE idanalysisvocabulary = $analysisrefid AND preferred = 'Y'";
$analysisResult = $conn->query($analysisQuery);
$analysisn = $analysisResult->fetch_assoc();
$analysisName = $analysisn['nameanalysisvoc']; // Nome dell'analisi
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Components</title>

    <link rel="shortcut icon" href="../assets/images/favicon.ico">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>
    <script src="../assets/js/jquery.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

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
                                    <h4 class="page-title">Components of Analysis: <?php echo $analysisName; ?></h4>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <!-- Tabella dei componenti -->
                                            <table id="componentsTable" class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Component Name</th>
                                                        <th>CAS Number</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo '<tr data-id="' . $row['idcompoundsvocabulary'] . '">';
                                                        echo '<td>' . $row['namecompoundsvocabulary'] . '</td>';
                                                        echo '<td>' . $row['cascompoundvocabulary'] . '</td>';
                                                        echo '<td>
                <button class="btn btn-info btn-sm show-synonyms">Synonyms</button>
                <button class="btn btn-danger btn-sm delete-component"><i class="bx bx-trash"></i> Delete</button>
            </td>';
                                                        echo '</tr>';
                                                    }
                                                    ?>
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
            // Inizializza DataTables sulla tabella dei componenti
            $('#componentsTable').DataTable();

            // Gestione del click su "Synonyms" per visualizzare la tabella child
            $('#componentsTable').on('click', '.show-synonyms', function() {
                var tr = $(this).closest('tr');
                var componentId = tr.data('id');
                var row = $('#componentsTable').DataTable().row(tr);
                var button = $(this); // Riferimento al pulsante

                // Disabilita il pulsante e aggiunge un indicatore di caricamento
                button.prop('disabled', true);
                button.html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                // Se la riga child è già aperta, la chiude
                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                    // Riabilita il pulsante
                    button.prop('disabled', false);
                    button.html('Synonyms');
                } else {
                    // Chiamata AJAX per ottenere i sinonimi del componente
                    $.ajax({
                        url: 'get_component_synonyms.php',
                        type: 'POST',
                        data: {
                            refid: componentId
                        },
                        success: function(data) {
                            // Visualizza i sinonimi come tabella child
                            row.child(formatSynonyms(data)).show();
                            tr.addClass('shown');
                        },
                        complete: function() {
                            // Riabilita il pulsante e ripristina il testo originale
                            button.prop('disabled', false);
                            button.html('Synonyms');
                        },
                        error: function() {
                            Swal.fire('Error!', 'There was an error loading the synonyms.', 'error');
                            // Riabilita il pulsante in caso di errore
                            button.prop('disabled', false);
                            button.html('Synonyms');
                        }
                    });
                }
            });

            // Funzione per formattare i sinonimi nella tabella child
            function formatSynonyms(data) {
                var html = '<table class="table table-bordered child-table">';
                html += '<thead><tr><th>Synonym Name</th><th>Actions</th></tr></thead>';
                html += '<tbody>';
                $.each(JSON.parse(data), function(index, synonym) {
                    html += '<tr><td>' + synonym.namecompoundsvocabulary + '</td>';
                    html += '<td><button class="btn btn-danger btn-sm delete-synonym" data-id="' + synonym.idcompoundsvocabulary + '"><i class="bx bx-trash"></i></button></td></tr>';
                });
                html += '</tbody></table>';
                return html;
            }

            // Funzione per cancellare un singolo sinonimo
            $('#componentsTable').on('click', '.delete-synonym', function() {
                var synonymId = $(this).data('id');
                var row = $(this).closest('tr');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the synonym!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'delete_synonymcomp.php',
                            type: 'POST',
                            data: {
                                idcompoundsvocabulary: synonymId
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', 'The synonym has been deleted.', 'success');
                                $('#componentsTable').DataTable().row(row).remove().draw(); // Rimuovi la riga del sinonimo dalla tabella
                            },
                            error: function() {
                                Swal.fire('Error!', 'There was an error deleting the synonym.', 'error');
                            }
                        });
                    }
                });
            });

            // Funzione per cancellare il componente principale con i suoi sinonimi
            $('#componentsTable').on('click', '.delete-component', function() {
                var componentId = $(this).closest('tr').data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the component and all its synonyms!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete everything!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'delete_component.php',
                            type: 'POST',
                            data: {
                                idcompoundsvocabulary: componentId
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', 'The component and all its synonyms have been deleted.', 'success');
                                $('#componentsTable').DataTable().row($(this).closest('tr')).remove().draw(); // Rimuovi la riga dalla tabella
                            },
                            error: function() {
                                Swal.fire('Error!', 'There was an error deleting the component.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>