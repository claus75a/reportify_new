<?php include('../include/headscript.php'); ?>
<?php include("../class/company.php");
?>
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $InsertQuery = new WA_MySQLi_Query($repnew);
    $InsertQuery->Action = "insert";
    $InsertQuery->Table = "`family_analysis`";

    $InsertQuery->bindColumn("namefamily", "s", "" . ((isset($_POST["namefamily"])) ? $_POST["namefamily"] : "") . "", "WA_DEFAULT");

    $InsertQuery->saveInSession("");
    $InsertQuery->execute();


    $InsertGoTo = "analysis-category.php";
    if (function_exists("rel2abs")) $InsertGoTo = $InsertGoTo ? rel2abs($InsertGoTo, dirname(__FILE__)) : "";
    $InsertQuery->redirect($InsertGoTo);
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
    <style>
        /* select2 css */
        .select2-container {
            width: 100% !important;
        }

        .select2-selection__choice,
        .select2-selection__choice__remove {
            background-color: blue !important;
            color: white !important;
            border: 1px solid blue !important;
        }

        /* select2 css end */

        input:invalid {
            border-color: #ff0000;
            background-color: #fff7e6;
        }

        input:focus {
            background: yellow;
        }

        input:valid {
            border-color: #66ff33;
            background-color: #eeffe6;
        }

        select:invalid {
            border-color: #ff0000;
            background-color: #fff7e6;
        }

        select:focus {
            background-color: yellow;
        }

        select:valid {
            border-color: #66ff33;
            background-color: #eeffe6;
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

                                        <div>
                                            <form method="post" class="form-horizontal p-t-20" id="updatebeach">


                                                <div class="mb-3 row">
                                                    <label for="name" class="col-md-2 col-form-label"><?php echo $name_lang; ?></label>
                                                    <div class="col-md-10">
                                                        <div class="input-group">

                                                            <input name="namefamily" type="text" class="form-control" id="namefamily">
                                                        </div>
                                                    </div>
                                                </div>












                                                <div class="offset-sm-3 col-sm-9">
                                                    <button type="submit" class="btn btn-success waves-effect waves-light">Insert</button>
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
            // File upload via Ajax
            $("#uploadForm").on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'uploadlogorsl.php',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        $('#uploadStatus').html('<img src="images/uploading.gif"/>');
                    },
                    error: function() {
                        $('#uploadStatus').html('<span style="color:#EA4335;">Images upload failed, please try again.<span>');
                    },
                    success: function(data) {
                        $('#uploadForm')[0].reset();
                        $('#uploadStatus').html('<span style="color:#28A74B;">Images uploaded successfully.<span>');
                        $('.gallery').html(data);
                    }
                });
            });

            // File type validation
            $("#fileInput").change(function() {
                var fileLength = this.files.length;
                var match = ["image/jpeg", "image/png", "image/jpg", "image/gif"];
                var i;
                for (i = 0; i < fileLength; i++) {
                    var file = this.files[i];
                    var imagefile = file.type;
                    if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]) || (imagefile == match[3]))) {
                        alert('Please select a valid image file (JPEG/JPG/PNG/GIF).');
                        $("#fileInput").val('');
                        return false;
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".upload-image").click(function() {
                $(".form-horizontal").ajaxForm({
                    target: '.preview'
                }).submit();
            });
            $('#form').parsley();
        });
    </script>
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