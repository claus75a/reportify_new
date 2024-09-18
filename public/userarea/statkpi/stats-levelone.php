<?php include('../include/headscript.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="../assets/plugins/select2/select2.min.css">
    <script src="../assets/plugins/select2/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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

    <!-- Loader -->
    <!--<div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>

    <div id="ajax_preloader">
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
                                    <h4 class="page-title">StatKPI</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title end breadcrumb -->
                        <?php include('../include/sessionmanagement.php');
                        ?>
                        <?php include('../include/parsedatachart.php');
                        ?>
                        <!-- filter top page -->
                        <div class="row">


                            <div class="col-xl-2">
                                <div class="card">

                                    <div class="card-body">

                                        <a href="stats-details.php"><button type="button" class="btn btn-info waves-effect waves-light">Analysis Detail</button></a>
                                        <br><br>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-5">
                                <div class="card">

                                    <div class="card-body">

                                        <?php
                                        $manufacturerlist = new WA_MySQLi_RS("manufacturerlist", $repnew, 0);
                                        $manufacturerlist->setQuery("SELECT DISTINCT namesupplier FROM products WHERE products.idcompany='$idcompany'  ORDER BY products.namesupplier");
                                        $manufacturerlist->execute();
                                        ?>
                                        <form class="container" method="GET" id="form">
                                            <select class="js-example-basic-multiple" name="states[]" multiple="multiple" data-placeholder="Select Supplier">
                                                <?php
                                                while (!$manufacturerlist->atEnd()) {
                                                    echo '<option value="' . htmlspecialchars($manufacturerlist->getColumnVal("namesupplier")) . '">'
                                                        . htmlspecialchars($manufacturerlist->getColumnVal("namesupplier")) . '</option>';
                                                    $manufacturerlist->moveNext();
                                                }
                                                $manufacturerlist->moveFirst(); // Resetta il puntatore dei risultati
                                                ?>
                                            </select><br>
                                            <button type="submit" class="btn btn-primary w-xs waves-effect waves-light">Submit</button>
                                        </form>







                                    </div>
                                </div>
                            </div>



                            <div class="col-xl-5">
                                <div class="card">

                                    <div class="card-body">
                                        <form method="get">
                                            <?php if (!isset($_SESSION['datefiltermin'])) { ?> <input style="width: 100%" type="text" name="daterange" value="01/01/2000 - 01/01/2035" /><?php } else { ?>
                                                <input style="width: 100%" type="text" name="daterange" value="<?php echo $datefilter[0]; ?> - <?php echo $datefilter[1]; ?>" /><?php } ?><button type="submit" class="btn btn-primary w-xs waves-effect waves-light">Submit</button>
                                        </form>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <script>
                            $(function() {
                                $('input[name="daterange"]').daterangepicker({
                                    opens: 'left'
                                }, function(start, end, label) {
                                    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                                });
                            });
                        </script>
                        <!-- end filter top page -->

                        <!-- session filter selected -->
                        <?php include('../include/filterselected.php'); ?>
                        <!-- end session filter selected -->

                        <!-- stat number first line -->
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar">
                                                            <div class="avatar-title rounded bg-soft-primary">
                                                                <i class="bx bxs-report font-size-24 mb-0 text-primary"></i>
                                                            </div>
                                                        </div>

                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-0 font-size-15">Total Report</h6>
                                                        </div>

                                                        <div class="flex-shrink-0">
                                                            <div class="dropdown">


                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div><?php if (isset($num_rows)) { ?>
                                                            <h4 class="mt-4 pt-1 mb-0 font-size-22"><?php echo $num_rows; ?> </h4>
                                                        <?php } else { ?> <h4 class="mt-4 pt-1 mb-0 font-size-22">Data not found. Please modify your filters </h4> <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar">
                                                            <div class="avatar-title rounded bg-soft-primary">
                                                                <i class="bx bxs-no-entry font-size-24 mb-0 text-primary"></i>
                                                            </div>
                                                        </div>

                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-0 font-size-15">Failed Reports</h6>
                                                        </div>

                                                        <div class="flex-shrink-0">

                                                        </div>
                                                    </div>

                                                    <div><?php if (isset($perfail)) { ?>
                                                            <h4 class="mt-4 pt-1 mb-0 font-size-22"><?php echo $num_rows_fail; ?> <span class="text-danger fw-medium font-size-13 align-middle"> ( <?php echo $perfail; ?>% of total reports )</span> </h4>
                                                        <?php } else { ?><p class="mt-4 pt-1 mb-0 font-size-14">Data not found. Please modify your filters </p> <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="col-xl-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar">
                                                            <div class="avatar-title rounded bg-soft-primary">
                                                                <i class="bx bxs-report font-size-24 mb-0 text-primary"></i>
                                                            </div>
                                                        </div>

                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-0 font-size-15">Total Analysis</h6>
                                                        </div>

                                                        <div class="flex-shrink-0">
                                                            <div class="dropdown">


                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div><?php if (isset($num_rows_analysis)) { ?>
                                                            <h4 class="mt-4 pt-1 mb-0 font-size-22"><?php echo $num_rows_analysis; ?> </h4>
                                                        <?php } else { ?><h4 class="mt-4 pt-1 mb-0 font-size-22">Data not found. Please modify your filters </h4> <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-xl-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar">
                                                            <div class="avatar-title rounded bg-soft-primary">
                                                                <i class="bx bxs-no-entry font-size-24 mb-0 text-primary"></i>
                                                            </div>
                                                        </div>

                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-0 font-size-15">Failed Analysis</h6>
                                                        </div>

                                                        <div class="flex-shrink-0">

                                                        </div>
                                                    </div>

                                                    <div><?php if (isset($num_rows_analysis_fail)) { ?>
                                                            <h4 class="mt-4 pt-1 mb-0 font-size-22"><?php echo $num_rows_analysis_fail; ?> <span class="text-danger fw-medium font-size-13 align-middle"> ( <?php echo $percanalysisfail; ?> %of total analysis ) </span> </h4>
                                                        <?php } else { ?><h4 class="mt-4 pt-1 mb-0 font-size-22">Data not found. Please modify your filters </h4> <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end stat number first line -->

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Reports Pie</h4>
                                    </div><!-- end card header -->
                                    <?php if (!empty($num_rows_fail)) { ?> <div class="card-body">

                                            <div id="chart">
                                            </div>
                                        </div>
                                        <script>
                                            var fail = <?php echo $num_rows_fail; ?>;
                                            var pass = <?php echo $numpassres; ?>;
                                            var data = <?php echo $num_rows_data; ?>;
                                            var totrep = fail + pass + data;
                                            var percfail = <?php echo $perfail; ?>;
                                            var percdata = <?php echo $perdata; ?>;
                                            var percpass = 100 - percfail - percdata;
                                            var failtitle = 'Fail' + ' (' + percfail + ' %)';
                                            var passtitle = 'Pass' + ' (' + percpass + ' %)';
                                            var datatitle = 'Data' + ' (' + percdata + ' %)';
                                            var options = {
                                                series: [fail, pass, data],
                                                chart: {
                                                    width: 450,
                                                    type: 'pie',
                                                },

                                                colors: ['#F44336', '#63D15F', '#F7D216'],
                                                labels: [failtitle, passtitle, datatitle],
                                                responsive: [{
                                                    breakpoint: 480,
                                                    options: {
                                                        chart: {
                                                            width: 200
                                                        },
                                                        legend: {
                                                            position: 'left',
                                                            horizontalAlign: 'center'
                                                        }
                                                    }
                                                }]
                                            };

                                            var chart = new ApexCharts(document.querySelector("#chart"), options);
                                            chart.render();
                                        </script><?php } else { ?><div class="card-body">
                                            <h4 class="mt-4 pt-1 mb-0 font-size-14">Data not found. Please modify your filters </h4>
                                        </div> <?php } ?>
                                </div><!--end card-->
                            </div><!-- end col -->


                            <!-- table analysis  fail -->
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Highest Fail 10 Analysis</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">
                                        <div id="chart3">
                                        </div>
                                    </div>
                                    <script>
                                        var arraynumb = <?php echo json_encode($ncounter); ?>;
                                        var arraytest = <?php echo json_encode($testname); ?>;

                                        var options = {
                                            series: [{
                                                data: arraynumb
                                            }],
                                            chart: {
                                                type: 'bar',
                                                height: 350,
                                                toolbar: {
                                                    show: true,
                                                    offsetX: 0,
                                                    offsetY: 0,
                                                    tools: {
                                                        download: true,
                                                        selection: true,
                                                        zoom: true,
                                                        zoomin: true,
                                                        zoomout: true,
                                                        pan: true,
                                                        reset: true | '<img src="/static/icons/reset.png" width="20">',
                                                        customIcons: []
                                                    },
                                                    export: {
                                                        csv: {
                                                            filename: undefined,
                                                            columnDelimiter: ',',
                                                            headerCategory: 'category',
                                                            headerValue: 'value',
                                                            dateFormatter(timestamp) {
                                                                return new Date(timestamp).toDateString()
                                                            }
                                                        },
                                                        svg: {
                                                            filename: undefined,
                                                        },
                                                        png: {
                                                            filename: undefined,
                                                        }
                                                    },
                                                    autoSelected: 'zoom'
                                                }
                                            },
                                            plotOptions: {
                                                bar: {
                                                    borderRadius: 3,
                                                    horizontal: true,
                                                }
                                            },
                                            dataLabels: {
                                                enabled: true
                                            },
                                            yaxis: {
                                                labels: {
                                                    maxWidth: 350
                                                }
                                            },
                                            xaxis: {
                                                categories: arraytest,
                                            },
                                            colors: ['#FF5733', '#33FF57', '#3357FF', '#F7D216'], // Array di colori per le barre
                                            // ... altre impostazioni ...
                                        };

                                        var chart = new ApexCharts(document.querySelector("#chart3"), options);
                                        chart.render();
                                    </script>
                                </div><!--end card-->
                            </div>

                            <!-- table worst supplier -->
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Highest Fail 10 Analysis</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">
                                        <div id="chart3">
                                        </div>
                                    </div>
                                    <script>
                                        var arraynumb = <?php echo json_encode($ncounterworstsupplier); ?>;
                                        var arraytest = <?php echo json_encode($testnameworstsupplier); ?>;

                                        var options = {
                                            series: [{
                                                data: arraynumb
                                            }],
                                            chart: {
                                                type: 'bar',
                                                height: 350,
                                                toolbar: {
                                                    show: true,
                                                    offsetX: 0,
                                                    offsetY: 0,
                                                    tools: {
                                                        download: true,
                                                        selection: true,
                                                        zoom: true,
                                                        zoomin: true,
                                                        zoomout: true,
                                                        pan: true,
                                                        reset: true | '<img src="/static/icons/reset.png" width="20">',
                                                        customIcons: []
                                                    },
                                                    export: {
                                                        csv: {
                                                            filename: undefined,
                                                            columnDelimiter: ',',
                                                            headerCategory: 'category',
                                                            headerValue: 'value',
                                                            dateFormatter(timestamp) {
                                                                return new Date(timestamp).toDateString()
                                                            }
                                                        },
                                                        svg: {
                                                            filename: undefined,
                                                        },
                                                        png: {
                                                            filename: undefined,
                                                        }
                                                    },
                                                    autoSelected: 'zoom'
                                                }
                                            },
                                            plotOptions: {
                                                bar: {
                                                    borderRadius: 3,
                                                    horizontal: true,
                                                }
                                            },
                                            dataLabels: {
                                                enabled: true
                                            },
                                            yaxis: {
                                                labels: {
                                                    maxWidth: 350
                                                }
                                            },
                                            xaxis: {
                                                categories: arraytest,
                                            },
                                            colors: ['#FF5733', '#33FF57', '#3357FF', '#F7D216'], // Array di colori per le barre
                                            // ... altre impostazioni ...
                                        };

                                        var chart = new ApexCharts(document.querySelector("#chart3"), options);
                                        chart.render();
                                    </script>
                                </div><!--end card-->
                            </div>

                            <div class="row">
                                <!-- Prima Tavola -->



                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">Highest Fail "10 Supplier"</h4>
                                        </div><!-- end card header -->
                                        <div class="card-body">
                                            <div id="chart4">
                                            </div>
                                        </div>
                                        <script>
                                            var arraynumb = <?php echo json_encode($ncounterworstsupplier); ?>;
                                            var arraytest = <?php echo json_encode($testnameworstsupplier); ?>;
                                            console.log(arraynumb);
                                            console.log(arraytest);

                                            var options = {
                                                series: [{
                                                    data: arraynumb
                                                }],
                                                chart: {
                                                    type: 'bar',
                                                    height: 400,
                                                    toolbar: {
                                                        show: true,
                                                        offsetX: 0,
                                                        offsetY: 0,
                                                        tools: {
                                                            download: true,
                                                            selection: true,
                                                            zoom: true,
                                                            zoomin: true,
                                                            zoomout: true,
                                                            pan: true,
                                                            reset: true | '<img src="/static/icons/reset.png" width="20">',
                                                            customIcons: []
                                                        },
                                                        export: {
                                                            csv: {
                                                                filename: undefined,
                                                                columnDelimiter: ',',
                                                                headerCategory: 'category',
                                                                headerValue: 'value',
                                                                dateFormatter(timestamp) {
                                                                    return new Date(timestamp).toDateString()
                                                                }
                                                            },
                                                            svg: {
                                                                filename: undefined,
                                                            },
                                                            png: {
                                                                filename: undefined,
                                                            }
                                                        },
                                                        autoSelected: 'zoom'
                                                    }
                                                },
                                                plotOptions: {
                                                    bar: {
                                                        borderRadius: 4,
                                                        horizontal: true,
                                                    }
                                                },
                                                dataLabels: {
                                                    enabled: true
                                                },
                                                yaxis: {
                                                    labels: {
                                                        maxWidth: 300
                                                    }
                                                },
                                                xaxis: {
                                                    categories: arraytest,
                                                }
                                            };

                                            var chart = new ApexCharts(document.querySelector("#chart4"), options);
                                            chart.render();
                                        </script>
                                    </div><!--end card-->
                                </div>


                                <!-- Seconda Tavola -->

                            </div>


                        </div>


                        <div class="row">

                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Reports Results</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">
                                        <div id="tablecla"></div>
                                    </div>



                                    <script>
                                        var reportsar = <?php echo json_encode($reportsarray); ?>;
                                        $("div#tablecla").Grid({
                                            columns: [{
                                                id: 'reportsNumberLab',
                                                name: 'Report N.'
                                            }, {
                                                id: 'products_description',
                                                name: 'Description'
                                            }, {
                                                id: 'products_style',
                                                name: 'Style',
                                                width: '30%'
                                            }, {
                                                id: 'products_color',
                                                name: 'Color'
                                            }, {
                                                id: 'reportsRating',
                                                name: 'Rating'
                                            }],
                                            sort: true,
                                            search: true,
                                            resizable: true,
                                            fixedHeader: true,
                                            pagination: true,
                                            data: reportsar
                                        });
                                    </script>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->
                        </div>



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

                                            $('#f_csv').change(function() {
                                                let formdata = new FormData();
                                                if ($(this).prop('files').length > 0) {
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
                                                            if (data.indexOf("success") > -1) {
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
                                                            } else if (data.indexOf("none_define_column_error") > -1) {
                                                                $('#f_csv').val("");
                                                                showWarningPopup("The Associate Columns did not define yet!");
                                                            } else if (data.indexOf("invalid_excel_data_format_error") > -1) {
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
                                                if (tmp_analyvoc_idx < arr_total_analysisvoc.length) {
                                                    show_analysis_add_popup(arr_total_analysisvoc[tmp_analyvoc_idx], function() {
                                                        tmp_analyvoc_idx++;
                                                        show_analysis_add_pop();
                                                    })
                                                } else {
                                                    show_compunds_add_pop();
                                                }
                                            }

                                            function show_compunds_add_pop() {
                                                if (tmp_compundsvoc_idx < arr_total_compundsvoc.length) {
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
                                                for (let i = 0; i < arr_similary.length; i++) {
                                                    str_arr_option += '<option value="' + arr_similary[i]['refid'] + '">' + arr_similary[i]['nameanalysisvoc'] + '</option>';
                                                }
                                                str_arr_option += tmp_str_arr_kind_option;

                                                let swal_html = `<div class="row">
                                                    <div class="col-md-12">
                                                        <select class="form-control ipt_type">
                                                        <option value="0">Add new</option>`;
                                                swal_html += str_arr_option;

                                                swal_html += `
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
                                                        $('.swal2-popup .ipt_type').select2();
                                                        $('.swal2-popup .ipt_type').bind("change", function() {
                                                            if ($(this).val() == 0) {
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
                                                            if (data.indexOf("success") > -1) {
                                                                if (type == 0) {
                                                                    let inserted_info = JSON.parse(data)['info'];
                                                                    tmp_str_arr_kind_option += '<option value="' + inserted_info['ref_id'] + '">' + inserted_info['name'] + '</option>';
                                                                }
                                                                showSuccessAlert("Successfully added!");
                                                                if (callback) {
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
                                                for (let i = 0; i < arr_similary.length; i++) {
                                                    str_arr_option += '<option value="' + arr_similary[i]['refid'] + '">' + arr_similary[i]['namecompoundsvocabulary'] + '</option>';
                                                }
                                                str_arr_option += tmp_str_arr_compunds_kind_option;

                                                let swal_html = `<div class="row">
                                                    <div class="col-md-12">
                                                        <select class="form-control ipt_type">
                                                            <option value="0">Add new</option>`;
                                                swal_html += str_arr_option;

                                                swal_html += `
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
                                                        $('.swal2-popup .ipt_type').select2();
                                                        $('.swal2-popup .ipt_type').bind("change", function() {
                                                            if ($(this).val() == 0) {
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
                                                            if (data.indexOf("success") > -1) {
                                                                if (type == 0) {
                                                                    let inserted_info = JSON.parse(data)['info'];
                                                                    tmp_str_arr_compunds_kind_option += '<option value="' + inserted_info['ref_id'] + '">' + inserted_info['name'] + '</option>';
                                                                }
                                                                showSuccessAlert("Successfully added!");
                                                                if (callback) {
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
                                                if ($('#f_csv').prop('files').length > 0) {
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
                                                            if (data.indexOf("success") > -1) {
                                                                showSuccessPopup("Your operation requested!");
                                                            } else if (data.indexOf("none_define_column_error") > -1) {
                                                                showWarningPopup("The Associate Columns did not define yet!");
                                                            } else if (data.indexOf("invalid_excel_data_format_error") > -1) {
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