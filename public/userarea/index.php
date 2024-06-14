<?php include('include/headscript.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include('include/seo.php'); ?>

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">

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

        <?php include('include/navigationbar.php'); ?>

        <!-- Start right Content here -->

        <div class="content-page">
            <!-- Start content -->
            <div class="content">

                <?php include('include/topbar.php'); ?>

                <div class="page-content-wrapper ">

                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="page-title-box">
                                    <div class="btn-group float-right">
                                        <ol class="breadcrumb hide-phone p-0 m-0">
                                            <li class="breadcrumb-item"><a href="#">Reportify</a></li>
                                            <li class="breadcrumb-item active">Dashboard</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Dashboard</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title end breadcrumb -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="icon-contain">
                                                    <div class="row">
                                                        <div class="col-2 align-self-center">
                                                            <i class="fas fa-tasks text-gradient-success"></i>
                                                        </div>
                                                        <div class="col-10 text-right">
                                                            <h5 class="mt-0 mb-1">190</h5>
                                                            <p class="mb-0 font-12 text-muted">Active Tasks</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="card">
                                            <div class="card-body justify-content-center">
                                                <div class="icon-contain">
                                                    <div class="row">
                                                        <div class="col-2 align-self-center">
                                                            <i class="far fa-gem text-gradient-danger"></i>
                                                        </div>
                                                        <div class="col-10 text-right">
                                                            <h5 class="mt-0 mb-1">62</h5>
                                                            <p class="mb-0 font-12 text-muted">Project</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="icon-contain">
                                                    <div class="row">
                                                        <div class="col-2 align-self-center">
                                                            <i class="fas fa-users text-gradient-warning"></i>
                                                        </div>
                                                        <div class="col-10 text-right">
                                                            <h5 class="mt-0 mb-1">14</h5>
                                                            <p class="mb-0 font-12 text-muted">Teams</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="card ">
                                            <div class="card-body">
                                                <div class="icon-contain">
                                                    <div class="row">
                                                        <div class="col-2 align-self-center">
                                                            <i class="fas fa-database text-gradient-primary"></i>
                                                        </div>
                                                        <div class="col-10 text-right">
                                                            <h5 class="mt-0 mb-1">$15562</h5>
                                                            <p class="mb-0 font-12 text-muted">Budget</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>


                        <div class="row">

                            <div class="col-lg-3">
                                <div class="card card-body">
                                    <a href="<?php echo USERAREA_PATH; ?>products/products.php">
                                        <div class="alert alert-warning alert-dismissible fade show px-4 mb-0 text-center" role="alert">
                                            <i class="mdi mdi-database-import d-block display-4 mt-2 mb-3 text-warning"></i>
                                            <h5 class="text-primary"><?php echo $products; ?></h5>
                                            <p class="text-primary"><?php echo $productsslogan; ?></p>

                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card card-body">
                                    <a href="<?php echo USERAREA_PATH; ?>reports/reports.php">
                                        <div class="alert alert-primary alert-dismissible fade show px-4 mb-0 text-center" role="alert">
                                            <i class="mdi mdi-cloud-sync d-block display-4 mt-2 mb-3 text-primary"></i>
                                            <h5 class="text-primary">Reports</h5>
                                            <p class="text-primary"><?php echo $reportsslogan; ?></p>

                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card card-body">
                                    <a href="<?php echo USERAREA_PATH; ?>statkpi/statkpi.php">
                                        <div class="alert alert-info alert-dismissible fade show px-4 mb-0 text-center" role="alert">
                                            <i class="mdi mdi-signal d-block display-4 mt-2 mb-3 text-info"></i>
                                            <h5 class="text-primary"><?php echo $statkpi; ?></h5>
                                            <p class="text-primary"><?php echo $statkpislogan; ?></p>

                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card card-body">
                                    <a href="<?php echo USERAREA_PATH; ?>importify/importifydashboard.php">
                                        <div class="alert alert-success alert-dismissible fade show px-4 mb-0 text-center" role="alert">
                                            <i class="mdi mdi-database-import d-block display-4 mt-2 mb-3 text-success"></i>
                                            <h5 class="text-primary"><?php echo $importify; ?></h5>
                                            <p class="text-primary"><?php echo $importifyslogan; ?></p>

                                        </div>
                                    </a>
                                </div>
                            </div>

                        </div>


                        <div class="row">

                            <div class="col-lg-3">
                                <div class="card card-body">
                                    <a href="<?php echo USERAREA_PATH; ?>easyspec/rsl.php">
                                        <div class="alert alert-danger alert-dismissible fade show px-4 mb-0 text-center" role="alert">
                                            <i class="mdi mdi-view-list d-block display-4 mt-2 mb-3 text-danger"></i>
                                            <h5 class="text-danger"><?php echo $rsl; ?></h5>
                                            <p><?php echo $easyspecslogan; ?></p>

                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card card-body">
                                    <a href="importify.php">
                                        <div class="alert alert-primary alert-dismissible fade show px-4 mb-0 text-center" role="alert">
                                            <i class="mdi mdi-file-document-box d-block display-4 mt-2 mb-3 text-primary"></i>
                                            <h5 class="text-primary"><?php echo $reports; ?></h5>
                                            <p class="text-primary"><?php echo $reportsslogan; ?></p>

                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card card-body">
                                    <a href="importify.php">
                                        <div class="alert alert-info alert-dismissible fade show px-4 mb-0 text-center" role="alert">
                                            <i class="mdi mdi-signal d-block display-4 mt-2 mb-3 text-info"></i>
                                            <h5 class="text-primary"><?php echo $statkpi; ?></h5>
                                            <p class="text-primary"><?php echo $statkpislogan; ?></p>

                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card card-body">
                                    <a href="importify.php">
                                        <div class="alert alert-success alert-dismissible fade show px-4 mb-0 text-center" role="alert">
                                            <i class="mdi mdi-database-import d-block display-4 mt-2 mb-3 text-success"></i>
                                            <h5 class="text-primary"><?php echo $importify; ?></h5>
                                            <p class="text-primary"><?php echo $importifyslogan; ?></p>

                                        </div>
                                    </a>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="header-title pb-3 mt-0"><?php echo $dashboard; ?></h5>
                                        <div class="table-responsive">

                                        </div><!--end table-responsive-->

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                    </div><!-- container -->

                </div> <!-- Page content Wrapper -->

            </div> <!-- content -->

            <?php include('include/footer.php'); ?>

        </div>
        <!-- End Right content here -->

    </div>
    <!-- END wrapper -->


    <!-- jQuery  -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/modernizr.min.js"></script>
    <script src="assets/js/detect.js"></script>
    <script src="assets/js/fastclick.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/js/jquery.blockUI.js"></script>
    <script src="assets/js/waves.js"></script>
    <script src="assets/js/jquery.nicescroll.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>

    <script src="assets/plugins/chart.js/chart.min.js"></script>
    <script src="assets/pages/dashboard.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>


</body>

</html>