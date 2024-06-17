<?php include('../include/headscript.php'); ?>
<?php include("../class/company.php");
$importify_his = new WA_MySQLi_RS("rsl", $repnew, 0);
$user_id = $_SESSION['iduserlogin'];
$importify_his->setQuery("SELECT * FROM template_import_his where user_id=$user_id");
$importify_his->execute();

$arr_his = $importify_his->Results;
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
    <!-- DataTables -->
    <link href="../assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <script src="../assets/js/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <style>
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

        table.table-bordered.dataTable tbody th, table.table-bordered.dataTable thead td, table.table-bordered.dataTable tbody td, table.table-bordered.dataTable th, table.table-bordered.dataTable td {
            text-align: center !important;
        }

        .no_padding {
            padding: 0 !important;
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

<div id="ajax_preloader">
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
                                    <h5 class="header-title pb-3 mt-0">Importify: History Import</h5>


                                    <br><br>
                                    <div class="table-responsive">
                                        <table id="tbl_his" class="table table-bordered dt-responsive nowrap">
                                            <thead>
                                            <tr>
                                                <th width="60px">No</th>
                                                <th width="120px">Date/Time</th>
                                                <th>ImportCode</th>
                                                <th width="80px">Status</th>
                                                <th width="80px">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            for($i=0; $i< count($arr_his); $i++) {
                                                $item = $arr_his[$i];
                                                ?>
                                                <tr>
                                                    <td><?=$i + 1?></td>
                                                    <td><?=date("Y-m-d H:i:s", strtotime($item['created_at']))?></td>
                                                    <td><?=$item['importcode']?></td>
                                                    <td class="no_padding">
                                                        <?php
                                                        if($item['f_status'] == 0) {
                                                            ?>
                                                            <button class="btn btn-sm btn-round btn-secondary">Progressing</button>
                                                        <?php
                                                        } else if($item['f_status'] == 1) {
                                                        ?>
                                                            <button class="btn btn-sm btn-round btn-success">Complete</button>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="no_padding">
                                                        <?php
                                                        if($item['f_status'] == 1) {
                                                            ?>
                                                            <button onclick="onCancelHis(<?=$item['idImportHis']?>)" type="button" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
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

<!-- Required datatable js -->
<script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>


<!-- END wrapper -->
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        $('#ajax_preloader').fadeOut();
    });

    $('#tbl_his').DataTable({

        "processing": true,
        "serverSide": false,
        "lengthChange": true,
        "lengthMenu": [10, 20, 50, 100],
        "pageLength": 10,
        "columns": [
            {"orderable": true},
            {"orderable": true},
            {"orderable": true},
            {"orderable": true},
            {"orderable": false},
        ],
        oLanguage: {
            oAria: {
                sSortAscending: ": activate to sort column ascending",
                sSortDescending: ": activate to sort column descending"
            },
            oPaginate: {sFirst: "First", sLast: "Last", sNext: "Next", sPrevious: "Previous"},
            sEmptyTable: "No data available in table",
            sInfo: "Showing _START_ to _END_ of _TOTAL_ entries",
            sInfoEmpty: "Showing 0 to 0 of 0 entries",
            sInfoFiltered: "(filtered from _MAX_ total entries)",
            sInfoPostFix: "",
            sDecimal: "",
            sThousands: ",",
            sLengthMenu: "_MENU_",
            sLoadingRecords: "Loading...",
            sProcessing: "Processing...",
            sSearch: "Search:",
            sSearchPlaceholder: "",
            sUrl: "",
            sZeroRecords: "No matching records found"
        },
        // "ajax": {
        //     "url": "/loadGoodsDestroyed",
        //     "type": "POST",
        //     "dataType": "json",
        //     "dataSrc": "data",
        //     "data": {start_date: start_date, end_date: end_date}
        // },
        // "columns": [
        //     {"data": "name"},
        //     {"data": "status"},
        //     {"data": "count"},
        //     {"data": "price"},
        //     {"data": "consumer"},
        //     {"data": "date"}
        // ]
    });

    function onCancelHis(id) {
        Swal.fire({
            title: 'Cancel Confirm!',
            text: "Do you want to cancel import his?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'cancel_import_his.php',
                    type: 'POST',
                    data: {
                        his_id: id
                    },
                    beforeSend: function() {
                        $('#ajax_preloader').fadeIn();
                    },
                    error: function() {
                        $('#ajax_preloader').fadeOut();
                        showWarningAlert("Server Error");
                    },
                    success: function(data) {
                        $('#ajax_preloader').fadeOut();

                        if(data.indexOf("success") > -1) {
                            showSuccessPopup("Your operation successed!", function() {
                                location.reload();
                            });
                        } else {
                            showWarningAlert("Server Error.")
                        }
                    }
                })
            }
        });
    }
</script>

</body>
</html>
