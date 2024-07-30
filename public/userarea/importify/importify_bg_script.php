<?php
define('BASE_PATH', realpath(__DIR__ . '/../../..'));
require_once(BASE_PATH . '\public\Connections\repnew.php');
?>
<?php require_once(BASE_PATH . '/public/webassist/mysqli/rsobj.php'); ?>
<?php require_once(BASE_PATH . '/public/webassist/mysqli/queryobj.php'); ?>
<?php require_once(BASE_PATH . "/public/webassist/form_validations/wavt_scripts_php.php"); ?>

<?php
$arr_total_products = json_decode($_POST['arr_project']);
$arr_excel_columns = json_decode($_POST['arr_excel_columns']);
$arr_associate = json_decode($_POST['arr_associate']);
$importcode = $_POST['importcode'];

$idx_sample_code_po = array_search("Sample Code (PO#)", $arr_excel_columns);

//insert to products table
$idx = 0;
foreach($arr_total_products as $product) {
    //check exist product item
    $sample_code_po = $product[0][$idx_sample_code_po];
    $product_query = new WA_MySQLi_RS("getquery", $repnew, 0);
    $product_query->setQuery("SELECT * FROM products WHERE products_refnumber='$sample_code_po'");
    $product_query->execute();

    $product_info = $product_query->Results;
    $idproducts = "";

    if(count($product_info) > 0) {
        $idproducts = $product_info[0]['idproducts'];

    } else {    // have to insert new
        $arr_product_need_idx = array();
        for($i=0; $i<count($arr_associate); $i++) {
            if($arr_associate[$i]->table_name == "products") {
                array_push($arr_product_need_idx, array($arr_associate[$i]->column_name, array_search($arr_associate[$i]->headerfile, $arr_excel_columns)));
            }
        }

        $InsertQuery = new WA_MySQLi_Query($repnew);
        $InsertQuery->Action = "insert";
        $InsertQuery->Table = "`products`";
        $InsertQuery->bindColumn("products_refnumber", "s", $product[0][$idx_sample_code_po], "WA_DEFAULT");
        $InsertQuery->bindColumn("importcode", "i", "" . $importcode. "", "WA_DEFAULT");
        for($i=0; $i<count($arr_product_need_idx); $i++) {
            $InsertQuery->bindColumn($arr_product_need_idx[$i][0], "s", $product[0][$arr_product_need_idx[$i][1]], "WA_DEFAULT");
        }
        $InsertQuery->saveInSession("");
        $InsertQuery->execute();

        $product_query = new WA_MySQLi_RS("getquery", $repnew, 0);
        $product_query->setQuery("SELECT * FROM products WHERE products_refnumber='$sample_code_po'");
        $product_query->execute();

        $product_info = $product_query->Results;

        if(count($product_info) > 0) {
            $idproducts = $product_info[0]['idproducts'];
        }
    }
    $idx++;

    if($idproducts == "") {
        die("server_error");
    }

    //-----------  report table ------------------
    //separate reports table data
    $idx_report_no_po = array_search("Report no.", $arr_excel_columns);

    $arr_total_reports = array();
    $tmp_arr_child_reports = array();
    $tmp_report_no = "";
    for($i=0; $i<count($product); $i++) {
        if($product[$i][$idx_report_no_po] == $tmp_report_no) {
            array_push($tmp_arr_child_reports, $product[$i]);
        } else {
            if($tmp_report_no != "") {
                if(count($tmp_arr_child_reports) > 0) {
                    array_push($arr_total_reports, $tmp_arr_child_reports);
                }
            }
            $tmp_report_no = $product[$i][$idx_report_no_po];
            $tmp_arr_child_reports = array();
            array_push($tmp_arr_child_reports, $product[$i]);
        }
    }
    if(count($tmp_arr_child_reports) > 0) {
        array_push($arr_total_reports, $tmp_arr_child_reports);
    }

    //insert to reports table
    foreach($arr_total_reports as $report) {
        //check exist reports item
        $report_no = $report[0][$idx_report_no_po];
        $report_query = new WA_MySQLi_RS("getquery", $repnew, 0);
        $report_query->setQuery("SELECT * FROM reports WHERE reportsNumberLab='$report_no' and idproducts='$idproducts'");
        $report_query->execute();

        $report_info = $report_query->Results;
        $idreports = "";

        if (count($report_info) > 0) {
            $idreports = $report_info[0]['idreports'];
        } else {    // have to insert new
            $arr_report_need_idx = array();
            for ($i = 0; $i < count($arr_associate); $i++) {
                if ($arr_associate[$i]->table_name == "reports") {
                    array_push($arr_report_need_idx, array($arr_associate[$i]->column_name, array_search($arr_associate[$i]->headerfile, $arr_excel_columns)));
                }
            }

            $InsertQuery = new WA_MySQLi_Query($repnew);
            $InsertQuery->Action = "insert";
            $InsertQuery->Table = "`reports`";
            $InsertQuery->bindColumn("reportsNumberLab", "s", $report[0][$idx_report_no_po]."", "WA_DEFAULT");
            $InsertQuery->bindColumn("idproducts", "i", "" . $idproducts."", "WA_DEFAULT");
            $InsertQuery->bindColumn("importcode", "i", "" . $importcode . "", "WA_DEFAULT");
            for ($i = 0; $i < count($arr_report_need_idx); $i++) {
                $InsertQuery->bindColumn($arr_report_need_idx[$i][0], "s", $report[0][$arr_report_need_idx[$i][1]], "WA_DEFAULT");
            }
            $InsertQuery->saveInSession("");
            $InsertQuery->execute();

            $report_query = new WA_MySQLi_RS("getquery", $repnew, 0);
            $report_query->setQuery("SELECT * FROM reports WHERE reportsNumberLab='$report_no' and idproducts='$idproducts'");
            $report_query->execute();

            $report_info = $report_query->Results;
            if (count($report_info) > 0) {
                $idreports = $report_info[0]['idreports'];
            }
        }
        if ($idreports == "") {
            die("server_error");
        }

        //-----------  parts table ------------------
        //separate parts table data
        $idx_part_no_po = array_search("Part No.", $arr_excel_columns);

        $arr_total_parts = array();
        $tmp_arr_child_parts = array();
        $tmp_part_no = "";
        for($i=0; $i<count($report); $i++) {
            if($report[$i][$idx_part_no_po] == $tmp_part_no) {
                array_push($tmp_arr_child_parts, $report[$i]);
            } else {
                if($tmp_part_no != "") {
                    if(count($tmp_arr_child_parts) > 0) {
                        array_push($arr_total_parts, $tmp_arr_child_parts);
                    }
                }
                $tmp_part_no = $report[$i][$idx_part_no_po];
                $tmp_arr_child_parts = array();
                array_push($tmp_arr_child_parts, $report[$i]);
            }
        }
        if(count($tmp_arr_child_parts) > 0) {
            array_push($arr_total_parts, $tmp_arr_child_parts);
        }

        //insert to parts table
        foreach($arr_total_parts as $part) {
            //check exist parts item
            $part_no = $part[0][$idx_part_no_po];
            $part_query = new WA_MySQLi_RS("getquery", $repnew, 0);
            $part_query->setQuery("SELECT * FROM parts WHERE partsCode='$part_no' and idreports='$idreports' and idproducts='$idproducts'");
            $part_query->execute();

            $part_info = $part_query->Results;
            $idparts = "";

            if (count($part_info) > 0) {
                $idparts = $part_info[0]['idParts'];
            } else {    // have to insert new
                $arr_part_need_idx = array();
                for ($i = 0; $i < count($arr_associate); $i++) {
                    if ($arr_associate[$i]->table_name == "parts") {
                        array_push($arr_part_need_idx, array($arr_associate[$i]->column_name, array_search($arr_associate[$i]->headerfile, $arr_excel_columns)));
                    }
                }

                $InsertQuery = new WA_MySQLi_Query($repnew);
                $InsertQuery->Action = "insert";
                $InsertQuery->Table = "`parts`";
                $InsertQuery->bindColumn("partsCode", "s", $part[0][$idx_part_no_po] . "", "WA_DEFAULT");
                $InsertQuery->bindColumn("idproducts", "i", "" . $idproducts . "", "WA_DEFAULT");
                $InsertQuery->bindColumn("idreports", "i", "" . $idreports . "", "WA_DEFAULT");
                $InsertQuery->bindColumn("importcode", "i", "" . $importcode . "", "WA_DEFAULT");
                for ($i = 0; $i < count($arr_part_need_idx); $i++) {
                    $InsertQuery->bindColumn($arr_part_need_idx[$i][0], "s", $part[0][$arr_part_need_idx[$i][1]], "WA_DEFAULT");
                }
                $InsertQuery->saveInSession("");
                $InsertQuery->execute();

                $part_query = new WA_MySQLi_RS("getquery", $repnew, 0);
                $part_query->setQuery("SELECT * FROM parts WHERE partsCode='$part_no' and idreports='$idreports' and idproducts='$idproducts'");
                $part_query->execute();

                $part_info = $part_query->Results;
                if (count($part_info) > 0) {
                    $idparts = $part_info[0]['idParts'];
                }
            }
            if ($idparts == "") {
                die("server_error");
            }

            //-----------  result_project table ------------------
            foreach($part as $result_project) {
                //check exist result_project item
//                                $result_project_query = new WA_MySQLi_RS("getquery", $repnew, 0);
//                                $sql_result_project_query = "SELECT * FROM result_project WHERE idPart='$idparts' and idreports='$idreports' and idproducts='$idproducts'";
//
                $arr_result_project_need_idx = array();
                for ($i = 0; $i < count($arr_associate); $i++) {
                    if ($arr_associate[$i]->table_name == "result_project") {
                        if($arr_associate[$i]->column_name == "result_TestName") {
                            $tmp_val = $result_project[array_search($arr_associate[$i]->headerfile, $arr_excel_columns)];
                            $analysis_query = new WA_MySQLi_RS("getquery", $repnew, 0);
                            $analysis_query->setQuery("SELECT * FROM analysisvocabulary WHERE nameanalysisvoc like '$tmp_val'");
                            $analysis_query->execute();

                            $analysis_data = $analysis_query->Results;
                            $ref_id = 0;
                            if(count($analysis_data) > 0) {
                                $ref_id = $analysis_data[0]['idanalysisvocabulary'];
                            }

                            array_push($arr_result_project_need_idx, array($arr_associate[$i]->column_name, $ref_id, 1));
                        } else if($arr_associate[$i]->column_name == "result_AnalytsName") {
                            $tmp_val = $result_project[array_search($arr_associate[$i]->headerfile, $arr_excel_columns)];

                            $analysis_query = new WA_MySQLi_RS("getquery", $repnew, 0);
                            $analysis_query->setQuery("SELECT * FROM compundsvocabulary WHERE namecompoundsvocabulary like '$tmp_val' or cascompoundvocabulary like '$tmp_val'");
                            $analysis_query->execute();

                            $analysis_data = $analysis_query->Results;
                            $ref_id = 0;
                            if(count($analysis_data) > 0) {
                                $ref_id = $analysis_data[0]['idcompoundsvocabulary'];
                            }

                            array_push($arr_result_project_need_idx, array($arr_associate[$i]->column_name, $ref_id, 1));
                        } else {
                            array_push($arr_result_project_need_idx, array($arr_associate[$i]->column_name, array_search($arr_associate[$i]->headerfile, $arr_excel_columns), 0));
                        }
                    }
                }
//
//                                foreach($arr_result_project_need_idx as $q) {
//                                    $sql_result_project_query .= " and ".$q[0]."='".$q[0]."'";
//                                }
//
//                                $result_project_query->setQuery($sql_result_project_query);
//                                $result_project_query->execute();
//
//                                $result_project_info = $result_project_query->Results;
//
//                                if (count($result_project_info) == 0) {
                $InsertQuery = new WA_MySQLi_Query($repnew);
                $InsertQuery->Action = "insert";
                $InsertQuery->Table = "`result_project`";
                $InsertQuery->bindColumn("idPart", "s", $idparts . "", "WA_DEFAULT");
                $InsertQuery->bindColumn("idproducts", "i", "" . $idproducts . "", "WA_DEFAULT");
                $InsertQuery->bindColumn("idreports", "i", "" . $idreports . "", "WA_DEFAULT");
                $InsertQuery->bindColumn("importcode", "i", "" . $importcode . "", "WA_DEFAULT");
                for ($i = 0; $i < count($arr_result_project_need_idx); $i++) {
                    if($arr_result_project_need_idx[$i][2] > 0) {
                        $InsertQuery->bindColumn($arr_result_project_need_idx[$i][0], "s", $arr_result_project_need_idx[$i][1], "WA_DEFAULT");
                    } else {
                        $InsertQuery->bindColumn($arr_result_project_need_idx[$i][0], "s", $result_project[$arr_result_project_need_idx[$i][1]], "WA_DEFAULT");
                    }
                }
                $InsertQuery->saveInSession("");
                $InsertQuery->execute();
//                                }
            }
        }
    }
}

$UpdateQuery = new WA_MySQLi_Query($repnew);
$UpdateQuery->Action = "update";
$UpdateQuery->Table = "`template_import_his`";
$UpdateQuery->bindColumn("f_status", "i", "1", "WA_DEFAULT");
$UpdateQuery->addFilter("importcode", "=", "s", "".$importcode . "");
$UpdateQuery->execute();
