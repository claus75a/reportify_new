<?php include('../include/headscript.php'); ?>
<?php include("../class/company.php"); ?>
<?php
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if(isset($_FILES['f_csv'])) {
    $tbl_name = $_POST['tbl_name'];
    $arr_val = json_decode($_POST['arr_selected_val']);
    $arr_description = json_decode($_POST['arr_selected_desc']);

    $file = $_FILES['f_csv']['tmp_name'];
    $spreadsheet = IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();
    $arr_info = $worksheet->toArray();

    if(count($arr_info) > 1) {

        //insert template header and description
        for($i=0; $i<count($arr_val); $i++) {
            $InsertQuery = new WA_MySQLi_Query($repnew);
            $InsertQuery->Action = "insert";
            $InsertQuery->Table = "`templatetable`";
            $InsertQuery->bindColumn("tablename", "s", "". $tbl_name, "WA_DEFAULT");
            $InsertQuery->bindColumn("columntable", "s", "".$tbl_name."_". $arr_val[$i], "WA_DEFAULT");
            $InsertQuery->bindColumn("headerfile", "s", "".$arr_description[$i], "WA_DEFAULT");
            $InsertQuery->saveInSession("");
            $InsertQuery->execute();
        }

        //create table
        $sql_create_tbl = "CREATE TABLE ".$tbl_name."( id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,";
        for($i=0; $i<count($arr_val); $i++) {
            if($i == (count($arr_val) - 1)) {
                $sql_create_tbl .= $arr_val[$i]." TEXT NOT NULL";
            } else {
                $sql_create_tbl .= $arr_val[$i]." TEXT NOT NULL,";
            }
        }
        $sql_create_tbl .= ")";

        if ($repnew->query($sql_create_tbl) === TRUE) {
            $arr_pos = array();
            for($i=0; $i<count($arr_info[0]); $i++) {
                for($j=0; $j<count($arr_description); $j++) {
                    if($arr_info[0][$i] == $arr_description[$j]) {
                        array_push($arr_pos, $i);
                    }
                }
            }

            $arr_result_data = array(); //insert data list
            for($i=1; $i<count($arr_info); $i++) {
                $item_info = array();
                for($pos=0; $pos<count($arr_pos); $pos++) {
                    array_push($item_info, $arr_info[$i][$arr_pos[$pos]]);
                }
                array_push($arr_result_data, $item_info);
            }

            //insert item to target table
            for($i=0; $i<count($arr_result_data); $i++) {
                $InsertQuery = new WA_MySQLi_Query($repnew);
                $InsertQuery->Action = "insert";
                $InsertQuery->Table = "`".$tbl_name."`";
                for($k=0; $k<count($arr_val); $k++) {
                    $InsertQuery->bindColumn("".$arr_val[$k], "s", "". $arr_result_data[$i][$k], "WA_DEFAULT");
                }
                $InsertQuery->saveInSession("");
                $InsertQuery->execute();
            }

        } else {
            die("error");
        }
        die("success");
    } else {
        die("success1");
    }
} else {
    die("file_empty_error");
}
