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
                                        <h5 class="header-title pb-3 mt-0">Importify: <?php echo $dashboard; ?></h5>
                                        <a class="btn btn-primary" href="insert-importifytemplate.php" role="button">Insert new template</a>
                                        <a class="btn btn-success" href="rsl-category.php" role="button">Import File</a>
                                        <a href="history_importify.php"><button type="button" class="btn btn-info w-md waves-effect waves-light">Hystory Import</button></a>
                                        <a href="importifydashboard.php"><button type="button" class="btn btn-pink w-md waves-effect waves-light">Importify Dasboard</button></a>
                                        <a href="dashboard.php"><button type="button" class="btn btn-danger w-md waves-effect waves-light">Reportify Dasboard</button></a>


                                        <br><br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-sm sm-0">
                                                <thead>
                                                    <tr>

                                                        <th><strong>Template Name</strong></th>
                                                        <th><strong>Description</strong></th>
                                                        <th><strong>File Source</strong></th>
                                                        <th><strong>Lab Name</strong></th>
                                                        <th><strong>Action</strong></th>





                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $templateimportify = new WA_MySQLi_RS("rsl", $repnew, 0);
                                                    $templateimportify->setQuery("SELECT * FROM template_importify");
                                                    $templateimportify->execute();

                                                    $wa_startindex = 0;
                                                    while (!$templateimportify->atEnd()) {
                                                        $wa_startindex = $templateimportify->Index;
                                                    ?> <tr>
                                                            <td><?php echo ($templateimportify->getColumnVal("templatename")); ?></td>
                                                            <td><?php echo ($templateimportify->getColumnVal("templatedescription")); ?></td>
                                                            <td><?php echo ($templateimportify->getColumnVal("fileextension")); ?></td>
                                                            <td><?php echo ($templateimportify->getColumnVal("labname")); ?></td>




                                                            <td>
                                                                <a onclick="onRunImport(<?php echo ($templateimportify->getColumnVal("idimporttemplates")); ?>)">
                                                                    <button type="button" class="btn btn-danger waves-effect waves-light" data-toggle="tooltip" title="Run Import">
                                                                        <i class="fas fa-play font-size-16 align-middle"></i>
                                                                    </button>
                                                                </a>

                                                                <a href="columnlink.php?idimporttemplates=<?php echo ($templateimportify->getColumnVal("idimporttemplates")); ?>">
                                                                    <button type="button" class="btn btn-info waves-effect waves-light" data-toggle="tooltip" title="Associate Columns">
                                                                        <i class="fas fa-project-diagram font-size-16 align-middle"></i>
                                                                    </button>
                                                                </a>
                                                                <a class="btn btn-warning" href="update-importifytemplate.php?idimporttemplates=<?php echo ($templateimportify->getColumnVal("idimporttemplates")); ?>" role="button" data-toggle="tooltip" title="Go">
                                                                    <i class="fas fa-pencil-alt font-size-16 align-middle"></i>
                                                                </a>

                                                                <a class="btn btn-danger canc-btn" href="cancel-importifytemplate.php?idimporttemplates=<?php echo ($templateimportify->getColumnVal("idimporttemplates")); ?>" role="button" data-toggle="tooltip" title="Delete">
                                                                    <i class="fas fa-trash font-size-16 align-middle"></i>
                                                                </a>

                                                            </td>

                                                        </tr>
                                                    <?php $templateimportify->moveNext();
                                                    }
                                                    $templateimportify->moveFirst(); //return RS to first record
                                                    unset($wa_startindex);
                                                    unset($wa_repeatcount);

                                                    ?></tbody>
                                            </table>
                                        </div><!--end table-responsive-->
                                        <input id="f_csv" type="file" name="f_csv" style="display: none" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, text/csv">
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const deleteButtons = document.querySelectorAll('.canc-btn');

                                                deleteButtons.forEach(button => {
                                                    button.addEventListener('click', function(event) {
                                                        event.preventDefault(); // Previene il comportamento predefinito del link
                                                        const href = this.getAttribute('href');

                                                        Swal.fire({
                                                            title: 'Are you sure?',
                                                            text: 'Do you want to cancel the import template?',
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#3085d6',
                                                            cancelButtonColor: '#d33',
                                                            confirmButtonText: 'Yes, cancel it!',
                                                            cancelButtonText: 'No, keep it'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                window.location.href = href;
                                                            }
                                                        });
                                                    });
                                                });
                                            });

                                            let tmp_id_template_importify = 0;

                                            function onRunImport(id_template_importify) {
                                                tmp_id_template_importify = id_template_importify;
                                                $('#f_csv').trigger("click");                                                
                                            }

                                            $('#f_csv').change(function(){
                                                let formdata = new FormData();
                                                if($(this).prop('files').length > 0) {
                                                    formdata.append("f_csv", $(this).prop('files')[0]);
                                                    formdata.append("template_id", tmp_id_template_importify);

                                                    $.ajax({
                                                        url: 'check_vocabulary.php',
                                                        type: 'POST',
                                                        data: formdata,
                                                        processData: false,
                                                        contentType: false,
                                                        beforeSend: function() {
                                                            $('#ajax_preloader').fadeIn();
                                                        },
                                                        error: function() {
                                                            $('#f_csv').val("");
                                                            $('#ajax_preloader').fadeOut();
                                                            showWarningAlert("Server Error");
                                                        },
                                                        success: function(data) {
                                                            $('#ajax_preloader').fadeOut();
                                                            if(data.indexOf("success") > -1) {
                                                                let arr_data = JSON.parse(data);
                                                                let arr_analysisvoc = arr_data['arr_analysis_data'];
                                                                console.log(arr_analysisvoc);
                                                                let arr_compundsvoc = arr_data['arr_compunds_data'];
                                                                console.log(arr_compundsvoc);

                                                                tmp_analyvoc_idx = 0;
                                                                tmp_compundsvoc_idx = 0;
                                                                arr_total_analysisvoc = arr_analysisvoc;
                                                                arr_total_compundsvoc = arr_compundsvoc;
                                                                tmp_str_arr_compunds_kind_option = '';
                                                                tmp_str_arr_kind_option = '';

                                                                show_analysis_add_pop();
                                                            }else if(data.indexOf("none_define_column_error") > -1) {
                                                                $('#f_csv').val("");
                                                                showWarningPopup("The Associate Columns did not define yet!");
                                                            } else if(data.indexOf("invalid_excel_data_format_error") > -1) {
                                                                $('#f_csv').val("");
                                                                showWarningPopup("Excel data format is not valid!")
                                                            } else {
                                                                $('#f_csv').val("");
                                                                showWarningAlert("Server Error.")
                                                            }
                                                        }
                                                    })
                                                }
                                            });
                                            
                                            let arr_total_analysisvoc = Array();
                                            let tmp_analyvoc_idx = 0;
                                            let arr_total_compundsvoc = Array();
                                            let tmp_compundsvoc_idx = 0;
                                            function show_analysis_add_pop() {
                                                if(tmp_analyvoc_idx < arr_total_analysisvoc.length) {
                                                    show_analysis_add_popup(arr_total_analysisvoc[tmp_analyvoc_idx], function() {
                                                        tmp_analyvoc_idx++;
                                                        show_analysis_add_pop();
                                                    })
                                                } else {
                                                    show_compunds_add_pop();
                                                }
                                            }

                                            function show_compunds_add_pop() {
                                                if(tmp_compundsvoc_idx < arr_total_compundsvoc.length) {
                                                    show_compunds_add_popup(arr_total_compundsvoc[tmp_compundsvoc_idx], function() {
                                                        tmp_compundsvoc_idx++;
                                                        show_compunds_add_pop();
                                                    })
                                                } else {
                                                    import_auto_script();
                                                }
                                            }

                                            let tmp_str_arr_kind_option = '';
                                            function show_analysis_add_popup(voc_info, callback) {
                                                let str_word = voc_info['word'];
                                                let arr_similary = voc_info['arr_similary'];

                                                let str_arr_option = '';
                                                for(let i=0; i<arr_similary.length; i++) {
                                                    str_arr_option += '<option value="' + arr_similary[i]['refid'] + '">' + arr_similary[i]['nameanalysisvoc'] + '</option>';
                                                }
                                                str_arr_option += tmp_str_arr_kind_option;

                                                let swal_html = `<div class="row">
                                                    <div class="col-md-12">
                                                        <select class="form-control ipt_type">`;
                                                swal_html += str_arr_option;

                                                swal_html += `<option value="0">Add new</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 flex_center div_input ` + (str_arr_option != "" ? "hidden" : "") + `" style="margin-top: 5px">
                                                        <label class="mg_none" style="min-width: 70px">Name</label>
                                                        <input class="form-control ipt_name ipt_val" ` + (str_arr_option != "" ? "" : "readonly") + ` placeholder="Please input name." value="` + str_word + `">
                                                    </div>
                                                    <div class="col-md-12 flex_center div_input ` + (str_arr_option != "" ? "hidden" : "") + `" style="margin-top: 5px">
                                                        <label class="mg_none" style="min-width: 70px">Kind</label>
                                                        <input class="form-control ipt_kind ipt_val" placeholder="Please input kind.">
                                                    </div>
                                                </div>`;
                                                Swal.fire({
                                                    html: swal_html,
                                                    title: 'Which analysis wants you to associate?<br><span>"' + str_word + '"</span>',
                                                    showCancelButton: false,
                                                    confirmButtonColor: '#3085d6',
                                                    confirmButtonText: 'Confirm',
                                                    allowOutsideClick: false,
                                                    didOpen: () => {
                                                        $('.swal2-popup .ipt_type').bind("click", function() {
                                                            if($(this).val() == 0) {
                                                                $('.div_input').removeClass("hidden");
                                                                $('.ipt_val').val("");
                                                                $('.swal2-popup .ipt_name').val(str_word);
                                                                $('.swal2-popup .ipt_name').attr("readonly", "readonly");
                                                            } else {
                                                                $('.div_input').addClass("hidden");
                                                            }
                                                        });

                                                        $('.swal2-popup .ipt_type').trigger("change");
                                                    },
                                                }).then((result) => {
                                                    let type = $('.swal2-popup .ipt_type').val();
                                                    let str_name = $('.swal2-popup .ipt_name').val();
                                                    let str_kind = $('.swal2-popup .ipt_kind').val();

                                                    $.ajax({
                                                        url: 'add_analysis_voc.php',
                                                        type: 'POST',
                                                        data: {
                                                            type: type,
                                                            str_name: str_name,
                                                            str_kind: str_kind,
                                                        },
                                                        beforeSend: function() {
                                                            $('#ajax_preloader').fadeIn();
                                                        },
                                                        error: function() {
                                                            $('#ajax_preloader').fadeOut();
                                                            showSuccessAlert("Successfully added!");
                                                        },
                                                        success: function(data) {
                                                            $('#ajax_preloader').fadeOut();
                                                            if(data.indexOf("success") > -1) {
                                                                if(type == 0) {
                                                                    let inserted_info = JSON.parse(data)['info'];
                                                                    tmp_str_arr_kind_option += '<option value="' + inserted_info['ref_id'] + '">' + inserted_info['name'] + '</option>';
                                                                }
                                                                showSuccessAlert("Successfully added!");
                                                                if(callback) {
                                                                    callback();
                                                                }
                                                            } else {
                                                                showWarningAlert("Server Error.")
                                                            }
                                                        }
                                                    })
                                                });
                                            }

                                            let tmp_str_arr_compunds_kind_option = '';
                                            function show_compunds_add_popup(voc_info, callback) {
                                                let str_word = voc_info['word'];
                                                let str_analysis_word = voc_info['anaysis_word'];
                                                let arr_similary = voc_info['arr_similary'];

                                                let str_arr_option = '';
                                                for(let i=0; i<arr_similary.length; i++) {
                                                    str_arr_option += '<option value="' + arr_similary[i]['refid'] + '">' + arr_similary[i]['namecompoundsvocabulary'] + '</option>';
                                                }
                                                str_arr_option += tmp_str_arr_compunds_kind_option;

                                                let swal_html = `<div class="row">
                                                    <div class="col-md-12">
                                                        <select class="form-control ipt_type">`;
                                                swal_html += str_arr_option;

                                                swal_html += `<option value="0">Add new</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 flex_center div_input ` + (str_arr_option != "" ? "hidden" : "") + `" style="margin-top: 5px">
                                                        <label class="mg_none" style="min-width: 70px">Name</label>
                                                        <input class="form-control ipt_name ipt_val" ` + (str_arr_option != "" ? "" : "readonly") + ` placeholder="Please input component name." value="` + str_word + `">
                                                    </div>
                                                    <div class="col-md-12 flex_center div_input ` + (str_arr_option != "" ? "hidden" : "") + `" style="margin-top: 5px">
                                                        <label class="mg_none" style="min-width: 70px">Cascompound</label>
                                                        <input class="form-control ipt_kind ipt_val" placeholder="Please input cascompound.">
                                                    </div>
                                                </div>`;
                                                Swal.fire({
                                                    html: swal_html,
                                                    title: 'Which component wants you to associate?<br><span>"' + str_word + '"</span>',
                                                    showCancelButton: false,
                                                    confirmButtonColor: '#3085d6',
                                                    confirmButtonText: 'Confirm',
                                                    allowOutsideClick: false,
                                                    didOpen: () => {
                                                        $('.swal2-popup .ipt_type').bind("click", function() {
                                                            if($(this).val() == 0) {
                                                                $('.div_input').removeClass("hidden");
                                                                $('.ipt_val').val("");
                                                                $('.swal2-popup .ipt_name').val(str_word);
                                                                $('.swal2-popup .ipt_name').attr("readonly", "readonly");
                                                            } else {
                                                                $('.div_input').addClass("hidden");
                                                            }
                                                        });

                                                        $('.swal2-popup .ipt_type').trigger("change");
                                                    },
                                                }).then((result) => {
                                                    let type = $('.swal2-popup .ipt_type').val();
                                                    let str_name = $('.swal2-popup .ipt_name').val();
                                                    let str_kind = $('.swal2-popup .ipt_kind').val();

                                                    $.ajax({
                                                        url: 'add_compunds_voc.php',
                                                        type: 'POST',
                                                        data: {
                                                            type: type,
                                                            analysis_name: str_analysis_word,
                                                            str_name: str_name,
                                                            str_kind: str_kind,
                                                        },
                                                        beforeSend: function() {
                                                            $('#ajax_preloader').fadeIn();
                                                        },
                                                        error: function() {
                                                            $('#ajax_preloader').fadeOut();
                                                            showSuccessAlert("Successfully added!");
                                                        },
                                                        success: function(data) {
                                                            $('#ajax_preloader').fadeOut();
                                                            if(data.indexOf("success") > -1) {
                                                                if(type == 0) {
                                                                    let inserted_info = JSON.parse(data)['info'];
                                                                    tmp_str_arr_compunds_kind_option += '<option value="' + inserted_info['ref_id'] + '">' + inserted_info['name'] + '</option>';
                                                                }
                                                                showSuccessAlert("Successfully added!");
                                                                if(callback) {
                                                                    callback();
                                                                }
                                                            } else {
                                                                showWarningAlert("Server Error.")
                                                            }
                                                        }
                                                    })
                                                });
                                            }

                                            function import_auto_script() {
                                                let formdata = new FormData();
                                                if($('#f_csv').prop('files').length > 0) {
                                                    formdata.append("f_csv", $('#f_csv').prop('files')[0]);
                                                    formdata.append("template_id", tmp_id_template_importify);

                                                    $.ajax({
                                                        url: 'import_auto_script.php',
                                                        type: 'POST',
                                                        data: formdata,
                                                        processData: false,
                                                        contentType: false,
                                                        beforeSend: function() {
                                                            $('#ajax_preloader').fadeIn();
                                                            $('#f_csv').val("");
                                                        },
                                                        error: function() {
                                                            $('#ajax_preloader').fadeOut();
                                                            showWarningAlert("Server Error");
                                                        },
                                                        success: function(data) {
                                                            $('#ajax_preloader').fadeOut();
                                                            if(data.indexOf("success") > -1) {
                                                                showSuccessPopup("Your operation requested!");
                                                            }else if(data.indexOf("none_define_column_error") > -1) {
                                                                showWarningPopup("The Associate Columns did not define yet!");
                                                            } else if(data.indexOf("invalid_excel_data_format_error") > -1) {
                                                                showWarningPopup("Excel data format is not valid!")
                                                            } else {
                                                                showWarningAlert("Server Error.")
                                                            }
                                                            console.log(data);
                                                        }
                                                    })
                                                }
                                            }
                                        </script>
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
            $('#ajax_preloader').fadeOut();
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
