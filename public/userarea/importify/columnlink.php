<?php include('../include/headscript.php'); ?>
<?php include("../class/company.php");?>
<?php
    $edit_id = $_GET['idimporttemplates'] != null ? $_GET['idimporttemplates'] : 0;
    if($edit_id == 0) {
        die("");
    }

    //get arr column of templatetable
    $arr_template = new WA_MySQLi_RS("rsl", $repnew, 0);
    $arr_template->setQuery("SELECT * FROM templatetable");
    $arr_template->execute();

    //get exist data from template_associate
    $arr_exist_info = new WA_MySQLi_RS("rsl", $repnew, 0);
    $arr_exist_info->setQuery("SELECT * FROM template_associate where template_importify_id=$edit_id");
    $arr_exist_info->execute();

    $arr_csv_column_name = array();
    if(count($arr_exist_info->Results) > 0) {
        $arr_csv_column_name = json_decode($arr_exist_info->Results[0]['arr_csv_columns']);
    }

    $arr_old_table_name = array();
    $arr_old_column_name = array();
    $arr_old_headerfile = array();
    $arr_old_db_headerfile = array();
    foreach($arr_exist_info->Results as $item) {
        array_push($arr_old_table_name, $item['table_name']);
        array_push($arr_old_column_name, $item['column_name']);
        array_push($arr_old_headerfile, $item['headerfile']);
        array_push($arr_old_db_headerfile, $item['db_headerfile']);
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
    <script src="../assets/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

    <style>
        .v-center {
           align-items: center;
        }

        .center {
            text-align: center;
        }

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

        #ajax_preloader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: transparent;
            z-index: 9999999;
        }

        .ipt_tbl_name {
            width: 300px;
            margin-left: 20px;
        }

        .flex {
            display: flex;
        }

        .column_div {
            height: 400px;
            overflow: auto;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            padding: 10px;
            width: 100%;
            text-align: left;
        }

        .mg_none {
            margin: 0 !important;
        }

        .mg_auto {
            margin: auto;
        }

        .hidden {
            display: none;
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
                                    <h5 class="header-title pb-3 mt-0">Importify: Associate Columns</h5>
                                    <div class="row">
                                        <div class="col-md-12 flex">
                                            <button onclick="onFileUpload()"  class="btn btn-primary" role="button"><i class="fa far fa-file-excel"></i> Import File</button>
                                            <input id="f_csv" type="file" name="f_csv" style="display: none" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, text/csv">
                                        </div>
                                    </div>
                                    <br><br>

                                    <div class="row v-center">
                                        <div class="col-md-4 center">
                                            <label>XLS Column</label>
                                            <div id="div_rdo_csv" class="column_div">
                                                <?php
                                                for($i=0; $i<count($arr_csv_column_name); $i++) {
                                                    ?>
                                                    <div class="custom-control custom-radio <?=in_array($arr_csv_column_name[$i], $arr_old_headerfile) ? 'hidden' : ''?>">
                                                        <input type="radio" id="rdo_csv<?=$i?>" value="<?=$arr_csv_column_name[$i]?>" name="rdo_csv" class="custom-control-input">
                                                        <label class="custom-control-label" for="rdo_csv<?=$i?>"><?=$arr_csv_column_name[$i]?></label>
                                                    </div>
                                                <?php
                                                }
                                                ?>

                                            </div>

                                        </div>

                                        <div class="col-md-1">
                                            <button onclick="onAddMapping()" type="button" class="btn btn-block"><i class="ion-checkmark-circled"></i> Add </button>
                                        </div>

                                        <div class="col-md-4 center">
                                            <label>Table Column</label>

                                            <div class="column_div">
                                                <?php
                                                while (!$arr_template->atEnd()) {
                                                    ?>
                                                    <div class="custom-control custom-radio <?=in_array($arr_template->getColumnVal('columntable'), $arr_old_column_name) ? 'hidden' : ''?>">
                                                        <input type="radio" data-headerfile="<?=$arr_template->getColumnVal('headerfile')?>" data-table="<?=$arr_template->getColumnVal('tablename')?>" id="rdo_tbl<?=$arr_template->getColumnVal('idtemplatetable')?>" value="<?=$arr_template->getColumnVal('columntable')?>" name="rdo_tbl" class="custom-control-input">
                                                        <label class="custom-control-label" for="rdo_tbl<?=$arr_template->getColumnVal('idtemplatetable')?>"><?php echo $arr_template->getColumnVal("headerfile")?></label>
                                                    </div>
                                                    <?php
                                                    $arr_template->moveNext();
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-9 center">
                                            <table class="mg_auto">
                                                <tbody id="tbody_result">
                                                <?php
                                                for($i=0; $i<count($arr_old_headerfile); $i++) {
                                                ?>
                                                    <tr>
                                                        <td><?=$arr_old_headerfile[$i]?></td>
                                                        <td><i class="ion-arrow-right-c" style="width: 30px"></i></td>
                                                        <td><?=$arr_old_db_headerfile[$i]?></td>
                                                        <td><input class="hidden" value="<?=$arr_old_table_name[$i]?>"><input class="hidden" value="<?=$arr_old_column_name[$i]?>"><a onclick="onRemoveResult(this)" style="color: red; padding: 5px; cursor:pointer; font-size: 16px"><i class="ion-close-circled"></i></a></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-9 center">
                                            <button type="button" onclick="goBack()" class="btn btn-dark waves-effect waves-light"><i class="fa fa-backward"></i> Back</button>&nbsp;&nbsp;
                                            <button type="button" onclick="onSave()" class="btn btn-success waves-effect waves-light"><i class="fa fa-save"></i> Save</button>
                                            <script>
                                                function goBack() {
                                                    window.history.back();
                                                }
                                            </script>
                                        </div>
                                    </div>
                                    <br>
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

    function onFileUpload() {
        $('#f_csv').trigger("click");
    }

    $('#f_csv').change(function(){
        let formdata = new FormData();
        if($(this).prop('files').length > 0)
        {
            formdata.append("f_csv", $(this).prop('files')[0]);
        }

        $.ajax({
            url: 'get_columnlist_from_csv.php',
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
                if(data == "file_empty_error") {
                    showWarningAlert("file_empty_error");
                } else {
                    let arr_title = JSON.parse(data);
                    let str_arr_title = "";
                    for(let idx=0; idx<arr_title.length; idx++) {
                        let item = arr_title[idx];
                        if(item.length > 0) {
                            str_arr_title += `<div class="custom-control custom-radio">
                                                <input type="radio" id="rdo_csv` + idx + `" value="` + item + `" name="rdo_csv" class="custom-control-input">
                                                <label class="custom-control-label" for="rdo_csv` + idx + `">` + item + `</label>
                                            </div>`;
                        }
                    }
                    $('#div_rdo_csv').html(str_arr_title);
                    $('#tbody_result').html("");
                }
            }
        })

    });

    function onAddMapping() {
        if($('input[name=rdo_csv]:checked').length == 0) {
            showWarningAlert("Please select XLS Column!");
            return;
        }

        if($('input[name=rdo_tbl]:checked').length == 0) {
            showWarningAlert("Please select Table Column!");
            return;
        }

        let str_csv_title = $('input[name=rdo_csv]:checked').val();
        let str_tbl_title = $('input[name=rdo_tbl]:checked').val();
        let str_tbl_name = $('input[name=rdo_tbl]:checked').attr("data-table");
        let str_tbl_headerfile = $('input[name=rdo_tbl]:checked').attr("data-headerfile");

        let str_result = `<tr>
                            <td>` + str_csv_title + `</td>
                            <td><i class="ion-arrow-right-c" style="width: 30px"></i></td>
                            <td>` + str_tbl_headerfile + `</td>
                            <td><input class="hidden" value="` + str_tbl_name + `"><input class="hidden" value="` + str_tbl_title + `"><a onclick="onRemoveResult(this)" style="color: red; padding: 5px; cursor:pointer; font-size: 16px"><i class="ion-close-circled"></i></a></td>
                        </tr>`;

        $('#tbody_result').append(str_result);
        $('input[name=rdo_csv]:checked').parent("div").addClass("hidden");
        $('input[name=rdo_tbl]:checked').parent("div").addClass("hidden");
        $('input[name=rdo_csv]').prop("checked", false);
        $('input[name=rdo_tbl]').prop("checked", false);
    }

    function onRemoveResult(obj) {
        let csv_title = $(obj).closest('tr').find("td:eq(0)").html();
        let tbl_title = $(obj).closest('tr').find("td:eq(3)").find("input:eq(1)").val();
        $('input[name=rdo_csv]').each(function(e) {
            if($(this).val() == csv_title) {
                $(this).parent("div").removeClass("hidden");
            }
        })
        $('input[name=rdo_tbl][value=' + tbl_title + ']').parent("div").removeClass("hidden");
        $(obj).closest("tr").remove();
    }

    //create table and insert csv data
    function onSave() {
        let arr_csv = Array();
        let arr_selected_csv = Array();
        let arr_tbl = Array();
        let arr_tbl_name = Array();
        let arr_db_headerfile = Array();
        $('input[name=rdo_csv]').each(function() {
            arr_csv.push($(this).val());
        })
        $('#tbody_result').find("tr").each(function() {
            arr_selected_csv.push($(this).find("td:eq(0)").html());
            arr_tbl.push($(this).find("td:eq(3)").find("input:eq(1)").val());
            arr_tbl_name.push($(this).find("td:eq(3)").find("input:eq(0)").val());
            arr_db_headerfile.push($(this).find("td:eq(2)").html());
        })


        if(arr_selected_csv.length > 0) {
            $.ajax({
                type: 'POST',
                url: 'save_column_link.php',
                data: {
                    template_id: '<?=$edit_id?>',
                    arr_csv: JSON.stringify(arr_csv),
                    arr_selected_csv: JSON.stringify(arr_selected_csv),
                    arr_selected_tbl_title: JSON.stringify(arr_tbl),
                    arr_selected_tbl_name: JSON.stringify(arr_tbl_name),
                    arr_db_headerfile: JSON.stringify(arr_db_headerfile)
                },
                beforeSend: function() {
                    $('#ajax_preloader').fadeIn();
                },
                error: function(e) {
                    console.log(e);
                    $('#ajax_preloader').fadeOut();
                    showWarningAlert("Server Error");
                },
                success: function(data) {
                    $('#ajax_preloader').fadeOut();
                    console.log(data);
                    showSuccessPopup("Your operation succeeded!", function() {
                        history.go(-1);
                    });
                }
            })
        } else {
            showWarningAlert("Please select columns!");
        }
    }
    //
    // function onSave() {
    //     let arr_selected_val = Array();
    //     let arr_selected_desc = Array();
    //
    //     $('#multiselect_to').find("option").each((id, item) => {
    //         arr_selected_val.push(item.value);
    //         arr_selected_desc.push($(item).attr("description"));
    //     })
    //
    //     let formdata = new FormData();
    //     formdata.append("f_csv", $('#f_csv').prop('files')[0]);
    //     formdata.append("tbl_name", $('#ipt_tbl_name').val());
    //     formdata.append("arr_selected_val", JSON.stringify(arr_selected_val));
    //     formdata.append("arr_selected_desc", JSON.stringify(arr_selected_desc));
    //
    //     if(arr_selected_val.length > 0) {
    //         $.ajax({
    //             type: 'POST',
    //             url: 'save_associate_template.php',
    //             data: formdata,
    //             processData: false,
    //             contentType: false,
    //             beforeSend: function() {
    //                 $('#ajax_preloader').fadeIn();
    //             },
    //             error: function(e) {
    //                 console.log(e);
    //                 $('#ajax_preloader').fadeOut();
    //                 showWarningAlert("Server Error");
    //             },
    //             success: function(data) {
    //                 $('#ajax_preloader').fadeOut();
    //                 console.log(data);
    //                 Swal.fire({
    //                     title: 'Success',
    //                     text: "Your operation succeeded!",
    //                     icon: 'success',
    //                     showCancelButton: false,
    //                     confirmButtonColor: '#3085d6',
    //                     confirmButtonText: 'Confirm'
    //                 }).then((result) => {
    //                     if (result.isConfirmed) {
    //                         history.go(-1);
    //                     } else {
    //                         history.go(-1);
    //                     }
    //                 });
    //             }
    //         })
    //     } else {
    //         showWarningAlert("Please add table column!");
    //     }
    // }
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

<script src="../assets/plugins/chart.js/chart.min.js"></script>
<script src="../assets/pages/dashboard.js"></script>


<script src="../assets/js/common_helper.js"></script>

<!-- App js -->
<script src="../assets/js/app.js"></script>
<script src="../assets/plugins/alertify/js/alertify.js"></script>

</body>

</html>
