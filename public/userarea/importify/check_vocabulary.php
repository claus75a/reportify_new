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

            $result_testNameHeaderFile = "";    //for analysisvocabulary
            $result_AnalytsNameHeaderFile = "";    //for compundsvocabulary

            foreach($arr_associate as $item) {
                array_push($arr_need_columns, $item['headerfile']);

                if($item['column_name'] == 'result_TestName') {
                    $result_testNameHeaderFile = $item['headerfile'];
                }

                if($item['column_name'] == 'result_AnalytsName') {
                    $result_AnalytsNameHeaderFile = $item['headerfile'];
                }
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
                //check result_TestName field
                $arr_anaysisvoc_words = array();
                if($result_testNameHeaderFile != "") {
                    $arr_diff_anaysisvoc_words = array();
                    $idx_resultTestName_po = array_search($result_testNameHeaderFile, $arr_excel_columns);

                    for($i=1; $i<count($arr_info); $i++) {
                        if(!in_array($arr_info[$i][$idx_resultTestName_po], $arr_diff_anaysisvoc_words)) {
                            array_push($arr_diff_anaysisvoc_words, $arr_info[$i][$idx_resultTestName_po]);                        
                        }
                    }

                    foreach($arr_diff_anaysisvoc_words as $item) {
                        $arr_analysis_refdata = new WA_MySQLi_RS("rsl", $repnew, 0);
                        $arr_analysis_refdata->setQuery("SELECT * FROM analysisvocabulary where nameanalysisvoc like '$item'");
                        $arr_analysis_refdata->execute();
                        $arr_analysis_ref = $arr_analysis_refdata->Results;
                        if(count($arr_analysis_ref) == 0) {
                            //check kind
                            $arr_analysiskind_refdata = new WA_MySQLi_RS("rsl", $repnew, 0);
                            $arr_analysiskind_refdata->setQuery("SELECT * FROM analysisvocabulary where preferred like 'Y'");
                            $arr_analysiskind_refdata->execute();
                            $arr_analysiskind_ref = $arr_analysiskind_refdata->Results;
                            array_push($arr_anaysisvoc_words, array(
                                'word' => $item,
                                'arr_similary' => $arr_analysiskind_ref
                            ));
                        }
                    }
                }

                //check result_AnalytsName field
                $arr_compundsvoc_words = array();
                if($result_AnalytsNameHeaderFile != "") {
                    $arr_diff_compundsvoc_words = array();
                    $arr_tmp_diff_compundsvoc_words = array();
                    $idx_resultAnalytsName_po = array_search($result_AnalytsNameHeaderFile, $arr_excel_columns);
                    $idx_resultTestName_po = $result_testNameHeaderFile != "" ? array_search($result_testNameHeaderFile, $arr_excel_columns) : 0;
                    
                    for($i=1; $i<count($arr_info); $i++) {
                        if(!in_array($arr_info[$i][$idx_resultAnalytsName_po], $arr_tmp_diff_compundsvoc_words)) {
                            array_push($arr_tmp_diff_compundsvoc_words, $arr_info[$i][$idx_resultAnalytsName_po]);
                            array_push($arr_diff_compundsvoc_words, array(
                                'word' => $arr_info[$i][$idx_resultAnalytsName_po],
                                'analysis_word' => $result_testNameHeaderFile != "" ? $arr_info[$i][$idx_resultTestName_po] : ""
                            ));                        
                        }
                    }

                    foreach($arr_diff_compundsvoc_words as $item) {
                        $arr_compunds_refdata = new WA_MySQLi_RS("rsl", $repnew, 0);
                        $compund_word = $item['word'];
                        $arr_compunds_refdata->setQuery("SELECT * FROM compundsvocabulary where namecompoundsvocabulary like '$compund_word' or cascompoundvocabulary like '$compund_word'");
                        $arr_compunds_refdata->execute();
                        $arr_compunds_ref = $arr_compunds_refdata->Results;
                        if(count($arr_compunds_ref) == 0) {
                            //check kind
                            $arr_compundskind_refdata = new WA_MySQLi_RS("rsl", $repnew, 0);
                            $arr_compundskind_refdata->setQuery("SELECT * FROM compundsvocabulary where preferred like 'Y'");
                            $arr_compundskind_refdata->execute();
                            $arr_compundskind_ref = $arr_compundskind_refdata->Results;
                            array_push($arr_compundsvoc_words, array(
                                'word' => $compund_word,
                                'anaysis_word' => $item['analysis_word'],
                                'arr_similary' => $arr_compundskind_ref
                            ));
                        }
                    }
                }

                die(json_encode(array(
                    'code' => "success",
                    'arr_analysis_data' => $arr_anaysisvoc_words,
                    'arr_compunds_data' => $arr_compundsvoc_words,
                )));
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




