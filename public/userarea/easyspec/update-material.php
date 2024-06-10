<?php include('../include/headscript.php'); ?>
<?php include("../class/company.php");
?>
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $UpdateQuery = new WA_MySQLi_Query($repnew);
    $UpdateQuery->Action = "update";
    $UpdateQuery->Table = "`material_type`";
    $UpdateQuery->bindColumn("idmaterial_type", "s", "" . ((isset($_POST["idmaterial_type"])) ? $_POST["idmaterial_type"] : "") . "", "WA_DEFAULT");
    $UpdateQuery->bindColumn("name_material", "s", "" . ((isset($_POST["name_material"])) ? $_POST["name_material"] : "") . "", "WA_DEFAULT");
    $UpdateQuery->bindColumn("desc_material", "s", "" . ((isset($_POST["desc_material"])) ? $_POST["desc_material"] : "") . "", "WA_DEFAULT");
    $UpdateQuery->bindColumn("company_id", "s", "" . ((isset($_POST["company_id"])) ? $_POST["company_id"] : "") . "", "WA_DEFAULT");
    $UpdateQuery->addFilter("idmaterial_type", "=", "i", "" . ($_GET['idmaterial_type']) . "");
    $UpdateQuery->execute();
    $UpdateGoTo = "material.php";
    if (function_exists("rel2abs")) $UpdateGoTo = $UpdateGoTo ? rel2abs($UpdateGoTo, dirname(__FILE__)) : "";
    $UpdateQuery->redirect($UpdateGoTo);
}
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
                        <?php $idmaterial_type = $_GET['idmaterial_type'];
                        $updatequery = new WA_MySQLi_RS("updatequery", $repnew, 0);
                        $updatequery->setQuery("SELECT * FROM material_type WHERE material_type.idmaterial_type=$idmaterial_type");
                        $updatequery->execute();

                        ?>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="header-title pb-3 mt-0">EasySpec: Edit Material/End Use</h5>


                                        <div>
                                            <form method="post" class="form-horizontal p-t-20" id="updatebeach">
                                                <td><input name="idmaterial_type" type="hidden" id="idmaterial_type" value="<?php echo ($updatequery->getColumnVal("idmaterial_type")); ?>"></td>
                                                <div class="form-group row">
                                                    <label for="exampleInputuname3" class="col-sm-3 control-label"><?php echo $name_material_lang; ?></label>
                                                    <div class="col-sm-9">
                                                        <div class="mb-3 row">

                                                            <input name="name_material" type="text" class="form-control" id="name_material" value="<?php echo ($updatequery->getColumnVal("name_material")); ?>">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="exampleInputuname3" class="col-sm-3 control-label"><?php echo $desc_material_lang; ?></label>
                                                    <div class="col-sm-9">
                                                        <div class="mb-3 row">

                                                            <input name="desc_material" type="text" class="form-control" id="desc_material" value="<?php echo ($updatequery->getColumnVal("desc_material")); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <td><input name="company_id" type="hidden" id="company_id" value="<?php echo ($updatequery->getColumnVal("company_id")); ?>"></td>
                                                <div class="form-group row m-b-0">
                                                    <div class="offset-sm-3 col-sm-9">
                                                        <button type="submit" class="btn btn-success waves-effect waves-light">Update</button>
                                                    </div>
                                                </div>
                                                <div class="card-body collapse show">
                                                    <button type="button" onclick="goBack()" class="btn btn-dark waves-effect waves-light"><i class="fa fa-backward"></i> Back</button>
                                                    <script>
                                                        function goBack() {
                                                            window.history.back();
                                                        }
                                                    </script>
                                                </div>
                                        </div>
                                        </form>
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