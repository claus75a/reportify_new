<?php include('../include/headscript.php'); ?>
<?php include("../class/company.php"); ?>
<?php
$template_id = $_POST['template_id'];
$arr_csv = $_POST['arr_csv'];
$arr_selected_csv = json_decode($_POST['arr_selected_csv']);
$arr_selected_tbl_title = json_decode($_POST['arr_selected_tbl_title']);
$arr_selected_tbl_name = json_decode($_POST['arr_selected_tbl_name']);
$arr_db_headerfile = json_decode($_POST['arr_db_headerfile']);

$deleteQuery = new WA_MySQLi_Query($repnew);
$deleteQuery->Action = "delete";
$deleteQuery->Table = "`template_associate`";

$deleteQuery->addFilter("template_importify_id", "=", "i", "" . $template_id . "");
$deleteQuery->execute();

for($i=0; $i<count($arr_selected_csv); $i++) {
    $InsertQuery = new WA_MySQLi_Query($repnew);
    $InsertQuery->Action = "insert";
    $InsertQuery->Table = "`template_associate`";
    $InsertQuery->bindColumn("template_importify_id", "i", $template_id, "WA_DEFAULT");
    $InsertQuery->bindColumn("arr_csv_columns", "s", "".$arr_csv, "WA_DEFAULT");
    $InsertQuery->bindColumn("table_name", "s", "".$arr_selected_tbl_name[$i], "WA_DEFAULT");
    $InsertQuery->bindColumn("column_name", "s", "".$arr_selected_tbl_title[$i], "WA_DEFAULT");
    $InsertQuery->bindColumn("headerfile", "s", "".$arr_selected_csv[$i], "WA_DEFAULT");
    $InsertQuery->bindColumn("db_headerfile", "s", "".$arr_db_headerfile[$i], "WA_DEFAULT");
    $InsertQuery->saveInSession("");
    $InsertQuery->execute();
}
die("success");

