<?php include('../include/headscript.php'); ?>
<?php include("../class/company.php");
include('parsedatachart.php');
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

        .card {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .card-body {
            padding: 20px;
        }

        .card h2 {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .card h5 {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .card {
            min-height: 150px;
            /* Imposta un'altezza minima per le card */
        }

        .percentage {
            font-size: 18px;
            font-weight: bold;
            margin-top: 5px;
            margin-left: 10px;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        .border-primary {
            border: 2px solid #007bff !important;
        }

        .border-info {
            border: 2px solid #17a2b8 !important;
        }

        .border-danger {
            border: 2px solid #dc3545 !important;
        }

        .border-success {
            border: 2px solid #28a745 !important;
        }

        .border-warning {
            border: 2px solid #ffc107 !important;
        }

        .row {
            margin-top: 20px;
        }

        .col-md-2 {
            margin-bottom: 20px;
        }

        @media (max-width: 767px) {
            .col-md-2 {
                flex: 0 0 100%;
                max-width: 100%;
            }
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


                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">


                                        <!-- cards -->
                                        <div class="row justify-content-between">
                                            <div class="col-md-2 col-sm-6">
                                                <div class="card text-center bg-light border-primary">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Products</h5>
                                                        <h2 id="totalProducts">0</h2>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6">
                                                <div class="card text-center bg-light border-info">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Reports</h5>
                                                        <h2 id="totalReports">0</h2>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6">
                                                <div class="card text-center bg-light border-danger">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Failed Reports</h5>
                                                        <div class="d-flex justify-content-center align-items-baseline">
                                                            <h2 id="failedReports">0</h2>
                                                            <span class="percentage text-danger" id="failedReportsPercent" style="margin-left: 10px;">(0%)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6">
                                                <div class="card text-center bg-light border-success">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Total Tests</h5>
                                                        <h2 id="totalTests">0</h2>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6">
                                                <div class="card text-center bg-light border-warning">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Failed Tests</h5>
                                                        <div class="d-flex justify-content-center align-items-baseline">
                                                            <h2 id="failedTests">0</h2>
                                                            <span class="percentage text-danger" id="failedTestsPercent" style="margin-left: 10px;">(0%)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-md-4"> <!-- Colonna per il primo grafico (Pie Chart) -->
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Reports Overview</h5>
                                        <div id="reportPieChart"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8"> <!-- Colonna per il secondo grafico -->
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Worst Analysis</h5>
                                        <div id="worsttenanalysis"></div> <!-- Questo è lo spazio per il secondo grafico -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Worst Suppliers by Failed Report Percentage</h5>
                                        <div id="worstSuppliersChart"></div> <!-- Il grafico verrà inserito qui -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Number of Products by Supplier</h5>
                                        <div id="productBySupplierChart"></div> <!-- Lo spazio per il grafico -->
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div><!-- container -->

                </div> <!-- Page content Wrapper -->

            </div> <!-- content -->

            <?php include('../include/footer.php'); ?>

        </div>
        <!-- End Right content here -->

    </div>
    <!-- END wrapper -->

    <script>
        document.getElementById('totalProducts').innerText = <?php echo $totalProducts; ?>;
        document.getElementById('totalReports').innerText = <?php echo $totalReports; ?>;
        document.getElementById('failedReports').innerText = <?php echo $failedReports; ?>;
        document.getElementById('failedReportsPercent').innerText = "(<?php echo number_format($failedReportsPercent, 2); ?>%)";
        document.getElementById('totalTests').innerText = <?php echo $totalTests; ?>;
        document.getElementById('failedTests').innerText = <?php echo $failedTests; ?>;
        document.getElementById('failedTestsPercent').innerText = "(<?php echo number_format($failedTestsPercent, 2); ?>%)";
    </script>
    <script>
        // Data for pie chart (Reports: Fail, Pass, Others)
        var options = {
            series: [<?php echo $failReportsPie; ?>, <?php echo $passReportsPie; ?>, <?php echo $otherReportsPie; ?>],
            chart: {
                width: '100%', // Mantieni la larghezza al 100% all'interno della colonna Bootstrap
                type: 'pie',
            },
            labels: ['Fail', 'Pass', 'Others'],
            colors: ['#FF4D4D', '#28A745', '#FFA500'], // Red for Fail, Green for Pass, Orange for Others
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 250 // Riduci la larghezza sui dispositivi mobili
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            legend: {
                position: 'bottom', // Posiziona la legenda sotto il grafico
                offsetY: 0,
                height: 50, // Altezza della legenda
            }
        };

        var chart = new ApexCharts(document.querySelector("#reportPieChart"), options);
        chart.render();
    </script>
    <script>
        // Data for the bar chart
        var analysisNames = <?php echo json_encode(array_column($topFailingAnalysis, 'name')); ?>;
        var failCounts = <?php echo json_encode(array_column($topFailingAnalysis, 'failCount')); ?>;

        var options = {
            series: [{
                data: failCounts
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    dataLabels: {
                        position: 'center' // Posiziona i nomi delle analisi al centro delle barre
                    }
                }
            },
            dataLabels: {
                enabled: true,
                style: {
                    colors: ['#fff'], // Colore del testo all'interno delle barre (bianco)
                    fontSize: '12px'
                },
                formatter: function(val, opt) {
                    return analysisNames[opt.dataPointIndex]; // Mostra il nome dell'analisi dentro la barra
                }
            },
            xaxis: {
                categories: failCounts, // Visualizza solo i numeri sull'asse X
                title: {
                    text: 'Number of Failures'
                }
            },
            yaxis: {
                labels: {
                    show: false // Nascondiamo le etichette dell'asse Y
                },
                title: {
                    text: 'Analysis'
                }
            },
            colors: ['#FF4D4D'], // Rosso per i Fail
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 300
                    },
                    xaxis: {
                        labels: {
                            show: true
                        }
                    }
                }
            }],
            title: {
                text: 'Top 10 Analyses with the Most Failures',
                align: 'center'
            }
        };

        var chart = new ApexCharts(document.querySelector("#worsttenanalysis"), options);
        chart.render();
    </script>

    <script>
        // Data for the bar chart of worst suppliers
        var supplierNames = <?php echo json_encode(array_column($worstSuppliers, 'supplier')); ?>;
        var failPercentages = <?php echo json_encode(array_column($worstSuppliers, 'failPercentage')); ?>;
        var totalReportsForSupplier = <?php echo json_encode(array_column($worstSuppliers, 'totalReports')); ?>;
        var failedReportsForSupplier = <?php echo json_encode(array_column($worstSuppliers, 'failedReports')); ?>;

        var options = {
            series: [{
                data: failPercentages
            }],
            chart: {
                type: 'bar',
                height: 400
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    dataLabels: {
                        position: 'center' // Etichette al centro delle barre
                    }
                }
            },
            dataLabels: {
                enabled: true,
                style: {
                    colors: ['#fff'], // Colore del testo all'interno delle barre
                    fontSize: '12px'
                },
                formatter: function(val, opt) {
                    // Aggiungi nome del fornitore, percentuale, numero di fail e numero totale di report
                    var totalReports = totalReportsForSupplier[opt.dataPointIndex]; // Numero totale di report
                    var failedReports = failedReportsForSupplier[opt.dataPointIndex]; // Numero di report falliti
                    return supplierNames[opt.dataPointIndex] + ' (' + val.toFixed(2) + '%) (Fail: ' + failedReports + ' - Total: ' + totalReports + ')';
                }
            },
            xaxis: {
                categories: supplierNames, // Visualizza i nomi dei fornitori
                title: {
                    text: 'Failure Percentage (%)'
                }
            },
            yaxis: {
                labels: {
                    show: false // Nascondiamo le etichette dell'asse Y
                },
                title: {
                    text: 'Suppliers'
                }
            },
            colors: ['#3368FF'], // Colore blu chiaro per le barre
            title: {
                text: 'Top 10 Suppliers with the Highest Failed Reports Percentage',
                align: 'center'
            }
        };

        var chart = new ApexCharts(document.querySelector("#worstSuppliersChart"), options);
        chart.render();
    </script>

    <script>
        // Prepara i dati per il grafico
        var supplierNames = <?php echo json_encode(array_column($productBySupplier, 'supplier')); ?>;
        var totalProducts = <?php echo json_encode(array_column($productBySupplier, 'totalProducts')); ?>;

        var options = {
            series: [{
                name: 'Total Products',
                data: totalProducts
            }],
            chart: {
                type: 'bar',
                height: 400
            },
            plotOptions: {
                bar: {
                    horizontal: false, // Imposta il grafico a barre verticali
                    columnWidth: '50%',
                    dataLabels: {
                        position: 'top', // Etichette nella parte superiore delle barre
                    }
                }
            },
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ['#000']
                }
            },
            xaxis: {
                categories: supplierNames,
                title: {
                    text: 'Suppliers'
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Products'
                }
            },
            colors: ['#3368FF'],
            title: {
                text: 'Number of Products by Supplier',
                align: 'center'
            }
        };

        var chart = new ApexCharts(document.querySelector("#productBySupplierChart"), options);
        chart.render();
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