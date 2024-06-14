<?php include('../include/headscript.php'); ?>
<?php
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReader;

if(isset($_FILES['f_csv'])) {
    $file = $_FILES['f_csv']['tmp_name'];
    $template_id = $_POST['template_id'];

    //get template associate data
    $arr_associate_data = new WA_MySQLi_RS("rsl", $repnew, 0);
    $arr_associate_data->setQuery("SELECT * FROM template_associate where template_importify_id=$template_id");
    $arr_associate_data->execute();
    $arr_associate = $arr_associate_data->Results;

    if(count($arr_associate) > 0) { //check define columns
        $spreadsheet = IOFactory::load($file, IReader::READ_DATA_ONLY);
        $worksheet = $spreadsheet->getActiveSheet();
        $arr_info = $worksheet->toArray();
        if(count($arr_info) > 1) {  //check excel rows
            $arr_excel_columns = $arr_info[0];
            $arr_need_columns = array();
            array_push($arr_need_columns, "Sample Code (PO#)");
            array_push($arr_need_columns, "Report no.");
            array_push($arr_need_columns, "Part No.");

            foreach($arr_associate as $item) {
                array_push($arr_need_columns, $item['headerfile']);
            }

            //check excel data column with template associate data
            $verify_flag = true;
            for($i=0; $i<count($arr_need_columns); $i++) {
                if(!in_array($arr_need_columns[$i], $arr_excel_columns)) {
                    $verify_flag = false;
                    break;
                }
            }

            if($verify_flag) {
                //separate by Sample Code (PO#) - product
                $idx_sample_code_po = array_search("Sample Code (PO#)", $arr_excel_columns);

                $arr_total_products = array();
                $tmp_arr_child_products = array();
                $tmp_sample_code_po = "";
                for($i=1; $i<count($arr_info); $i++) {
                    if($arr_info[$i][$idx_sample_code_po] == $tmp_sample_code_po) {
                        array_push($tmp_arr_child_products, $arr_info[$i]);
                    } else {
                        if($tmp_sample_code_po != "") {
                            if(count($tmp_arr_child_products) > 0) {
                                array_push($arr_total_products, $tmp_arr_child_products);
                            }
                        }
                        $tmp_sample_code_po = $arr_info[$i][$idx_sample_code_po];
                        $tmp_arr_child_products = array();
                        array_push($tmp_arr_child_products, $arr_info[$i]);
                    }
                }
                if(count($tmp_arr_child_products) > 0) {
                    array_push($arr_total_products, $tmp_arr_child_products);
                }


                //define importcode (timestamp)
                $importcode = time();

                //insert to products table
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
                            if($arr_associate[$i]['table_name'] == "products") {
                                array_push($arr_product_need_idx, array($arr_associate[$i]['column_name'], $i));
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
                                if ($arr_associate[$i]['table_name'] == "reports") {
                                    array_push($arr_report_need_idx, array($arr_associate[$i]['column_name'], $i));
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
                                    if ($arr_associate[$i]['table_name'] == "parts") {
                                        array_push($arr_part_need_idx, array($arr_associate[$i]['column_name'], $i));
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
                                $result_project_query = new WA_MySQLi_RS("getquery", $repnew, 0);
                                $sql_result_project_query = "SELECT * FROM result_project WHERE idPart='$idparts' and idreports='$idreports' and idproducts='$idproducts'";

                                $arr_result_project_need_idx = array();
                                for ($i = 0; $i < count($arr_associate); $i++) {
                                    if ($arr_associate[$i]['table_name'] == "result_project") {
                                        array_push($arr_result_project_need_idx, array($arr_associate[$i]['column_name'], $i));
                                    }
                                }

                                foreach($arr_result_project_need_idx as $q) {
                                    $sql_result_project_query .= " and ".$q[0]."='".$q[1]."'";
                                }

                                $result_project_query->setQuery($sql_result_project_query);
                                $result_project_query->execute();

                                $result_project_info = $result_project_query->Results;

                                if (count($result_project_info) == 0) {
                                    $InsertQuery = new WA_MySQLi_Query($repnew);
                                    $InsertQuery->Action = "insert";
                                    $InsertQuery->Table = "`result_project`";
                                    $InsertQuery->bindColumn("idPart", "s", $idparts . "", "WA_DEFAULT");
                                    $InsertQuery->bindColumn("idproducts", "i", "" . $idproducts . "", "WA_DEFAULT");
                                    $InsertQuery->bindColumn("idreports", "i", "" . $idreports . "", "WA_DEFAULT");
                                    $InsertQuery->bindColumn("importcode", "i", "" . $importcode . "", "WA_DEFAULT");
                                    for ($i = 0; $i < count($arr_result_project_need_idx); $i++) {
                                        $InsertQuery->bindColumn($arr_result_project_need_idx[$i][0], "s", $result_project[$arr_result_project_need_idx[$i][1]], "WA_DEFAULT");
                                    }
                                    $InsertQuery->saveInSession("");
                                    $InsertQuery->execute();
                                }
                            }
                        }
                    }
                }

                die('success');
            } else {
                die("invalid_excel_data_format_error");
            }
        } else {
            die("invalid_excel_data_format_error");
        }
    } else {
        die("none_define_column_error");
    }
} else {
    die("file_empty_error");
}




