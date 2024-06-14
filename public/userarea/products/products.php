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
                                            <li class="breadcrumb-item active">Products</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Reports</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title end breadcrumb -->

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="header-title pb-3 mt-0">Products</h5>
                                        <a class="btn btn-primary" href="insert-importifytemplate.php" role="button">Products</a>
                                        <a class="btn btn-danger" href="../index.php" role="button">Dahboard</a>

                                        <br><br>
                                        <div class="col-sm-12 mb-3">
                                            <input type="text" class="form-control" id="searchInput" placeholder="Search by Component or CAS">

                                        </div>

                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-sm sm-0">
                                                <thead>
                                                    <tr>

                                                        <th><strong>Ref. Number</strong></th>
                                                        <th><strong>Description</strong></th>
                                                        <th><strong>Ref. Number</strong></th>
                                                        <th><strong>Description</strong></th>
                                                        <th><strong>Test Out</strong></th>
                                                        <th><strong>Rating</strong></th>
                                                        <th><strong>Action</strong></th>





                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $productslist = new WA_MySQLi_RS("rsl", $repnew, 0);
                                                    $productslist->setQuery("SELECT * FROM products");
                                                    $productslist->execute();

                                                    $wa_startindex = 0;
                                                    while (!$productslist->atEnd()) {
                                                        $wa_startindex = $productslist->Index;
                                                    ?> <tr>
                                                            <td><?php echo ($productslist->getColumnVal("products_refnumber")); ?></td>
                                                            <td><?php echo ($productslist->getColumnVal("products_description")); ?></td>
                                                            <td><?php echo ($productslist->getColumnVal("products_refnumber")); ?></td>
                                                            <td><?php echo ($productslist->getColumnVal("products_description")); ?></td>
                                                            <td><?php echo ($productslist->getColumnVal("reportsDateOut")); ?></td>
                                                            <td><?php echo ($productslist->getColumnVal("reportsRating")); ?></td>




                                                            <td>
                                                                <a class="btn btn-success" href="material-rsl.php?id=<?php echo ($productslist->getColumnVal("idimporttemplates")); ?>" role="button" data-toggle="tooltip" title="Go"><i class="fas fa-angle-double-right font-size-16 align-middle"></i></a>
                                                                <a class="btn btn-primary" href="material-rsl.php?id=<?php echo ($productslist->getColumnVal("idimporttemplates")); ?>" role="button" data-toggle="tooltip" title="Expand"><i class="fas fa-angle-double-down font-size-16 align-middle"></i></a>

                                                            </td>

                                                        </tr>
                                                    <?php $productslist->moveNext();
                                                    }
                                                    $productslist->moveFirst(); //return RS to first record
                                                    unset($wa_startindex);
                                                    unset($wa_repeatcount);

                                                    ?></tbody>
                                            </table>
                                        </div><!--end table-responsive-->

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