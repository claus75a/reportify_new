<?php include('../include/headscript.php'); ?>
<?php
$his_id = $_POST['his_id'];
$his_query = new WA_MySQLi_RS("getquery", $repnew, 0);
$his_query->setQuery("SELECT * FROM template_import_his WHERE idImportHis='$his_id'");
$his_query->execute();

$his_info = $his_query->Results;
$importcode = "";
if (count($his_info) > 0) {
    $importcode = $his_info[0]['importcode'];

    $deleteQuery = new WA_MySQLi_Query($repnew);
    $deleteQuery->Action = "delete";
    $deleteQuery->Table = "`result_project`";

    $deleteQuery->addFilter("importcode", "=", "s", "" . $importcode . "");
    $deleteQuery->execute();


    $deleteQuery = new WA_MySQLi_Query($repnew);
    $deleteQuery->Action = "delete";
    $deleteQuery->Table = "`parts`";

    $deleteQuery->addFilter("importcode", "=", "s", "" . $importcode . "");
    $deleteQuery->execute();


    $deleteQuery = new WA_MySQLi_Query($repnew);
    $deleteQuery->Action = "delete";
    $deleteQuery->Table = "`reports`";

    $deleteQuery->addFilter("importcode", "=", "s", "" . $importcode . "");
    $deleteQuery->execute();


    $deleteQuery = new WA_MySQLi_Query($repnew);
    $deleteQuery->Action = "delete";
    $deleteQuery->Table = "`products`";

    $deleteQuery->addFilter("importcode", "=", "s", "" . $importcode . "");
    $deleteQuery->execute();

    $deleteQuery = new WA_MySQLi_Query($repnew);
    $deleteQuery->Action = "delete";
    $deleteQuery->Table = "`template_import_his`";

    $deleteQuery->addFilter("idImportHis", "=", "i", "" . $his_id . "");
    $deleteQuery->execute();
}
die("success");
