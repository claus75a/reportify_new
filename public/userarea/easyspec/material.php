<?php include('../include/headscript.php'); ?>
<?php //include("class/company.php"); 
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
    <style>
        .table-custom tr {
            height: 40px;
            line-height: 40px;
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
    </style>
</head>


<body class="fixed-left">

    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>

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
                                            <li class="breadcrumb-item active">EasySpec</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">EasySpec</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title end breadcrumb -->

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="header-title pb-3 mt-0">EasySpec: <?php echo $dashboard; ?></h5>
                                        <a class="btn btn-danger" href="insert-material.php" role="button">Insert Material</a> <a class="btn btn-danger" href="rsl.php" role="button">RSL</a> <a class="btn btn-danger" href="analysis.php" role="button">Analysis</a> <?php if ($infobox == "wizard") { ?> <a class="btn btn-dark" href="rslwizard1.php" role="button">Back to Wizard</a><?php     } ?><br><br>

                                        <div class="table-responsive">

                                            <table class="table table-striped table-sm sm-0">
                                                <thead>
                                                    <tr>

                                                        <th><strong><?php echo $name_material_lang; ?></strong></th>
                                                        <th><strong><?php echo $desc_material_lang; ?></strong></th>

                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $tablequery = new WA_MySQLi_RS("tablequery", $repnew, 0);
                                                    $tablequery->setQuery("SELECT * FROM material_type WHERE material_type.company_id='$idcompany'");
                                                    $tablequery->execute();
                                                    ?>
                                                    <?php

                                                    $wa_startindex = 0;
                                                    while (!$tablequery->atEnd()) {
                                                        $wa_startindex = $tablequery->Index;
                                                    ?>
                                                        <tr>

                                                            <td><?php echo ($tablequery->getColumnVal("name_material")); ?></td>
                                                            <td><?php echo ($tablequery->getColumnVal("desc_material")); ?></td>


                                                            <td><a class="btn btn-danger" href="update-material.php?idmaterial_type=<?php echo ($tablequery->getColumnVal("idmaterial_type")); ?>" role="button">E</a></td>
                                                            <td><a class="btn btn-danger" href="cancel-material.php?idmaterial_type=<?php echo ($tablequery->getColumnVal("idmaterial_type")); ?>" role="button">C</a></td>
                                                        </tr>
                                                    <?php $tablequery->moveNext();
                                                    }
                                                    $tablequery->moveFirst(); //return RS to first record
                                                    unset($wa_startindex);
                                                    unset($wa_repeatcount);

                                                    ?>
                                                </tbody>
                                            </table>
                                        </div><!--end table-responsive-->

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Selezioniamo tutti i bottoni con la classe 'clone-btn'
                                let cloneBtns = document.querySelectorAll('.clone-btn');

                                // Aggiungiamo un ascoltatore d'evento a ciascun bottone
                                cloneBtns.forEach(cloneBtn => {
                                    cloneBtn.addEventListener('click', function(e) {
                                        // Preveniamo il comportamento predefinito del link
                                        e.preventDefault();

                                        Swal.fire({
                                            title: 'Clone TRL',
                                            text: "Do you want to clone the TRL?",
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Proceed',
                                            cancelButtonText: 'Cancel',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Se confermato, andiamo al link originale
                                                window.location.href = cloneBtn.getAttribute('href');
                                            }
                                        });
                                    });
                                });
                            });
                        </script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Selezioniamo tutti i bottoni con la classe 'clone-btn'
                                let cloneBtns = document.querySelectorAll('.rev-btn');

                                // Aggiungiamo un ascoltatore d'evento a ciascun bottone
                                cloneBtns.forEach(cloneBtn => {
                                    cloneBtn.addEventListener('click', function(e) {
                                        // Preveniamo il comportamento predefinito del link
                                        e.preventDefault();

                                        Swal.fire({
                                            title: 'Revise TRL',
                                            text: "Do you want to Revise the TRL?",
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Proceed',
                                            cancelButtonText: 'Cancel',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Se confermato, andiamo al link originale
                                                window.location.href = cloneBtn.getAttribute('href');
                                            }
                                        });
                                    });
                                });
                            });
                        </script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Selezioniamo tutti i bottoni con la classe 'clone-btn'
                                let cloneBtns = document.querySelectorAll('.canc-btn');

                                // Aggiungiamo un ascoltatore d'evento a ciascun bottone
                                cloneBtns.forEach(cloneBtn => {
                                    cloneBtn.addEventListener('click', function(e) {
                                        // Preveniamo il comportamento predefinito del link
                                        e.preventDefault();

                                        Swal.fire({
                                            title: 'Cancel TRL',
                                            text: "Do you want to cancel the TRL?",
                                            icon: 'danger',
                                            showCancelButton: true,
                                            confirmButtonText: 'Proceed',
                                            cancelButtonText: 'Cancel',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Se confermato, andiamo al link originale
                                                window.location.href = cloneBtn.getAttribute('href');
                                            }
                                        });
                                    });
                                });
                            });
                        </script>
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
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <!-- plugin JS  -->
    <script src="../assets/js/jquery.min.js"></script>
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

    <script src="../assets/plugins/chart.js/chart.min.js"></script>
    <script src="../assets/pages/dashboard.js"></script>

    <!-- App js -->
    <script src="../assets/js/app.js"></script>


</body>

</html>