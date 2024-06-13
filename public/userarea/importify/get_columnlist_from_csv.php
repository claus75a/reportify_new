<?php
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
if(isset($_FILES['f_csv'])) {
    $file = $_FILES['f_csv']['tmp_name'];
    $spreadsheet = IOFactory::load($file, IReader::READ_DATA_ONLY);
    $worksheet = $spreadsheet->getActiveSheet();
    $arr_info = $worksheet->toArray();
    if(count($arr_info) > 0) {
        die(json_encode($arr_info[0]));
    } else {
        die(json_encode(array()));
    }
} else {
    die("file_empty_error");
}
