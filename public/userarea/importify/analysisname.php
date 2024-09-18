<?php include('../include/headscript.php'); ?>
<?php include("../class/company.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include('../include/seo.php'); ?>

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="../assets/images/favicon.ico">

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="../assets/js/jquery.min.js"></script>
    <link rel="stylesheet" href="../assets/plugins/select2/select2.min.css">
    <script src="../assets/plugins/select2/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <style>
        .width-100 {
            width: 100%;
        }

        .flex_center {
            display: flex;
            align-items: center;
        }

        .mg_none {
            margin: 0 !important;
        }

        .hidden {
            display: none !important;
        }

        .table-custom tr {
            height: 40px;
            line-height: 40px;
        }

        #ajax_preloader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: transparent;
            z-index: 9999999;
        }

        .table-custom td,
        .table-custom th {
            padding: 4px 8px;
        }

        .table-custom .btn {
            padding: 2px 15px;
            line-height: 1.7;
            font-size: 14px;
        }

        .form-row {
            display: flex;
            align-items: center;
            /* Questo allinea verticalmente gli elementi nella riga */
            gap: 10px;
            /* Questo crea una piccola distanza tra gli elementi nella riga */
        }

        .table-custom .form-control,
        .table-custom .form-select {
            height: 25px;
            /* Puoi modificare questo valore per adattarlo al tuo design */
            padding: 2px 6px;
            /* riduce la dimensione del padding */
            font-size: 14px;
            /* riduce la dimensione del font */
        }

        .table-custom .form-control-sm.analysis-input {
            height: 25px;
            /* Questo modifica la dimensione degli input con classe 'form-control-sm' e 'analysis-input' */
            padding: 2px 6px;
            font-size: 12px;
        }

        .padding_none {
            padding: 0 !important;
        }

        .select2-container--open {
            z-index: 9999;
        }
    </style>
</head>


<body class="fixed-left">

    <!-- Loader 
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div> -->



    <!-- Begin page -->
    <div id="wrapper">

        <?php include('../include/navigationbar.php'); ?>

        <!-- Start right Content here -->

        <div class="content-page">
            <!-- Start content -->
            <div class="content">

                <?php include('../include/topbar.php'); ?>

                <div class="page-content-wrapper ">

                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="page-title-box">
                                    <div class="btn-group float-right">
                                        <ol class="breadcrumb hide-phone p-0 m-0">
                                            <li class="breadcrumb-item"><a href="#">Reportify</a></li>
                                            <li class="breadcrumb-item active">Importify</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Importify</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title end breadcrumb -->

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="header-title pb-3 mt-0">Importify: <?php echo $dashboard; ?></h5>
                                        <br><br>
                                        <div class="table-responsive">
                                            <!-- Button for adding new analysis -->
                                            <button id="add-analysis" class="btn btn-primary mb-3">Add New Analysis</button>

                                            <!-- Form for adding new analysis (hidden by default) -->
                                            <div id="add-analysis-form" class="hidden">
                                                <form id="analysisForm">
                                                    <div class="form-row">
                                                        <input type="text" name="nameanalysisvoc" class="form-control" placeholder="Analysis Name" required>
                                                        <select name="kindanalysisvoc" class="form-control">
                                                            <option value="">Type</option>
                                                            <option value="Type1">Type1</option>
                                                            <option value="Type2">Type2</option>
                                                        </select>
                                                        <button type="submit" class="btn btn-success">Save</button>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- Table of analysis -->
                                            <!-- Table of analysis -->
                                            <table id="analysisTable" class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Analysis Name</th>
                                                        <th>Type</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="analysis-list">
                                                    <?php
                                                    $conn = new mysqli($servername, $username, $password, $database);
                                                    $query = "SELECT * FROM analysisvocabulary WHERE preferred = 'Y'";
                                                    $result = $conn->query($query);
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo '<tr class="main-analysis" data-id="' . $row['idanalysisvocabulary'] . '">';
                                                        echo '<td>' . $row['nameanalysisvoc'] . '</td>';
                                                        echo '<td>' . $row['kindanalysisvoc'] . '</td>';
                                                        echo '<td>
                <button class="btn btn-info btn-sm show-synonyms">Synonyms</button>
                <button class="btn btn-primary btn-sm add-synonym">Add Synonym</button>
                <button class="btn btn-secondary btn-sm show-components" data-id="' . $row['idanalysisvocabulary'] . '">Components</button>
                <button class="btn btn-danger btn-sm delete-analysis" data-id="' . $row['idanalysisvocabulary'] . '"><i class="bx bx-trash"></i> Delete</button>
            </td>';
                                                        echo '</tr>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>

                                        </div>


                                        <input id="f_csv" type="file" name="f_csv" style="display: none" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, text/csv">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->


                    </div><!-- container -->

                </div> <!-- Page content Wrapper -->

            </div> <!-- content -->

            <?php include('../include/footer.php'); ?>

        </div>
        <!-- End Right content here -->

    </div>
    <!-- END wrapper -->
    <script>
        $(document).ready(function() {
            // Inizializzazione di DataTables con filtraggio automatico
            $('#analysisTable').DataTable();

            // Toggle form visibility for adding a new analysis
            $('#add-analysis').on('click', function() {
                $('#add-analysis-form').toggleClass('hidden');
            });

            // Handle form submission for adding a new analysis
            $('#analysisForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: 'add_analysis.php',
                    data: formData,
                    success: function(response) {
                        Swal.fire('Success!', 'Analysis added successfully.', 'success');
                        location.reload(); // Optionally reload the page to refresh the table
                    },
                    error: function() {
                        Swal.fire('Error!', 'There was an error adding the analysis.', 'error');
                    }
                });
            });

            // Funzione per cancellare l'analisi principale con tutti i sinonimi
            $('#analysisTable').on('click', '.delete-analysis', function() {
                var analysisId = $(this).data('id');
                var row = $(this).closest('tr');
                var table = $('#analysisTable').DataTable(); // Riferimento alla DataTable

                // SweetAlert per conferma
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the analysis and all its synonyms!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete everything!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'delete_analysis.php',
                            type: 'POST',
                            data: {
                                idanalysisvocabulary: analysisId
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', 'The analysis and all its synonyms have been deleted.', 'success');

                                // Rimuovi la riga principale
                                table.row(row).remove().draw();

                                // Rimuovi eventuali righe child (sinonimi) se sono visibili
                                var childRows = row.nextUntil('.main-analysis'); // Seleziona tutte le righe successive finché non incontri un'altra analisi principale
                                childRows.each(function() {
                                    if ($(this).hasClass('child')) {
                                        table.row($(this)).remove().draw(); // Rimuove le righe dei sinonimi
                                    }
                                });
                            },
                            error: function() {
                                Swal.fire('Error!', 'There was an error deleting the analysis.', 'error');
                            }
                        });
                    }
                });
            });


            // Gestione del click su "Synonyms" per visualizzare la tabella child
            $('#analysisTable').on('click', '.show-synonyms', function() {
                var tr = $(this).closest('tr');
                var analysisId = tr.data('id');
                var row = $('#analysisTable').DataTable().row(tr);
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
                    // Chiamata AJAX per ottenere i sinonimi
                    $.ajax({
                        url: 'get_synonyms.php',
                        type: 'POST',
                        data: {
                            idanalysisvocabulary: analysisId
                        },
                        success: function(data) {
                            // Visualizza la tabella child con i sinonimi
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
                            button.prop('disabled', false);
                            button.html('Synonyms');
                        }
                    });
                }
            });

            // Funzione per formattare i sinonimi nella tabella child
            function formatSynonyms(data) {
                var html = '<table class="table table-bordered table-striped child-table" style="background-color: #f9f9f9;">';
                html += '<thead><tr><th>Synonym Name</th><th>Actions</th></tr></thead>';
                html += '<tbody>';
                $.each(JSON.parse(data), function(index, synonym) {
                    html += '<tr><td>' + synonym.nameanalysisvoc + '</td>';
                    html += '<td><button class="btn btn-danger btn-sm delete-synonym" data-id="' + synonym.idanalysisvocabulary + '"><i class="bx bx-trash"></i></button></td></tr>';
                });
                html += '</tbody></table>';
                return html;
            }

            // Funzione per cancellare il sinonimo con SweetAlert
            $('#analysisTable').on('click', '.delete-synonym', function() {
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
                            url: 'delete_synonym.php',
                            type: 'POST',
                            data: {
                                idanalysisvocabulary: synonymId
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', 'The synonym has been deleted.', 'success');
                                row.remove(); // Rimuove la riga dalla tabella
                            },
                            error: function() {
                                Swal.fire('Error!', 'There was an error deleting the synonym.', 'error');
                            }
                        });
                    }
                });
            });

            // Funzione per cancellare l'analisi principale con tutti i sinonimi
            $('#analysisTable').on('click', '.delete-analysis', function() {
                var analysisId = $(this).data('id');
                var row = $(this).closest('tr');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the analysis and all its synonyms!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete everything!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'delete_analysis.php',
                            type: 'POST',
                            data: {
                                idanalysisvocabulary: analysisId
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', 'The analysis and all its synonyms have been deleted.', 'success');
                                row.remove(); // Rimuove la riga dalla tabella
                            },
                            error: function() {
                                Swal.fire('Error!', 'There was an error deleting the analysis.', 'error');
                            }
                        });
                    }
                });
            });

            // Funzione per mostrare il form di aggiunta sinonimo sotto la riga dell'analisi
            $('#analysisTable').on('click', '.add-synonym', function() {
                var tr = $(this).closest('tr');
                var analysisId = tr.data('id');

                if (tr.next('.synonym-form').length) {
                    tr.next('.synonym-form').toggle();
                } else {
                    var formHtml = '<tr class="synonym-form"><td colspan="3">';
                    formHtml += '<form class="add-synonym-form">';
                    formHtml += '<input type="text" name="synonym_name" class="form-control" placeholder="Synonym Name" required>';
                    formHtml += '<button type="submit" class="btn btn-success btn-sm mt-2">Save</button>';
                    formHtml += '</form></td></tr>';
                    tr.after(formHtml);
                }
            });

            // Invia il form di aggiunta sinonimo
            $('#analysisTable').on('submit', '.add-synonym-form', function(e) {
                e.preventDefault();
                var form = $(this);
                var synonymName = form.find('input[name="synonym_name"]').val();
                var analysisId = form.closest('tr').prev().data('id');

                $.ajax({
                    url: 'add_synonym.php',
                    type: 'POST',
                    data: {
                        nameanalysisvoc: synonymName,
                        refid: analysisId,
                        preferred: 'N'
                    },
                    success: function(response) {
                        Swal.fire('Success!', 'Synonym added successfully.', 'success');
                        form.closest('.synonym-form').hide(); // Nasconde il form dopo il salvataggio
                    },
                    error: function() {
                        Swal.fire('Error!', 'There was an error adding the synonym.', 'error');
                    }
                });
            });

            // Gestione del pulsante "Components"
            $('#analysisTable').on('click', '.show-components', function() {
                var analysisId = $(this).data('id');
                window.location.href = 'components.php?analysisrefid=' + analysisId; // Reindirizza alla pagina dei componenti con l'ID dell'analisi
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


</body>

</html>