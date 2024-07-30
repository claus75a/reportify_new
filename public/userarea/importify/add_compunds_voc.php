<?php include('../include/headscript.php'); ?>
<?php
    $type = $_POST['type'];
    $analysis_name = $_POST['analysis_name'];
    $str_name = $_POST['str_name'];
    $str_kind = $_POST['str_kind'];

    $analysis_refid = 0;
    if($analysis_name != "") {
        $arr_analysis_refdata = new WA_MySQLi_RS("rsl", $repnew, 0);
        $arr_analysis_refdata->setQuery("SELECT * FROM analysisvocabulary where nameanalysisvoc like '$analysis_name'");
        $arr_analysis_refdata->execute();
        $arr_analysis_ref = $arr_analysis_refdata->Results;
        if(count($arr_analysis_ref) == 0) {
            $analysis_refid = $arr_analysis_ref[0]['refid'];
        }
    }

    if($type == 0) {
        $InsertQuery = new WA_MySQLi_Query($repnew);
        $InsertQuery->Action = "insert";
        $InsertQuery->Table = "`compundsvocabulary`";
        $InsertQuery->bindColumn("namecompoundsvocabulary", "s", "" . $str_name, "WA_DEFAULT");
        $InsertQuery->bindColumn("cascompoundvocabulary", "s", "" .$str_kind, "WA_DEFAULT");
        $InsertQuery->bindColumn("analysisrefid", "i", "".$analysis_refid, "WA_DEFAULT");
        $InsertQuery->bindColumn("refid", "i", "0", "WA_DEFAULT");
        $InsertQuery->bindColumn("preferred", "s", "Y", "WA_DEFAULT");
        $InsertQuery->saveInSession("");
        $InsertQuery->execute();

        $inserted_query = new WA_MySQLi_RS("getquery", $repnew, 0);
        $inserted_query->setQuery("SELECT * FROM compundsvocabulary WHERE namecompoundsvocabulary like '$str_name' and preferred like 'Y' and refid=0");
        $inserted_query->execute();

        $inserted_data = $inserted_query->Results;
        $ref_id = 0;
        if(count($inserted_data) > 0) {
            $ref_id = $inserted_data[0]['idcompoundsvocabulary'];
        }

        if($ref_id > 0) {
            $UpdateQuery = new WA_MySQLi_Query($repnew);
            $UpdateQuery->Action = "update";
            $UpdateQuery->Table = "`compundsvocabulary`";
            $UpdateQuery->bindColumn("refid", "i", "" . $ref_id . "", "WA_DEFAULT");
            $UpdateQuery->addFilter("idcompoundsvocabulary", "=", "i", "" . ($ref_id) . "");
            $UpdateQuery->execute();

            die(json_encode(array(
                'code' => "success",
                'info' => array(
                    'name' => $str_name,
                    'kind' => $str_kind,
                    'ref_id' => $ref_id,
                )
            )));
        } else {
            die("error");
        }
    } else {
        $InsertQuery = new WA_MySQLi_Query($repnew);
        $InsertQuery->Action = "insert";
        $InsertQuery->Table = "`compundsvocabulary`";
        $InsertQuery->bindColumn("namecompoundsvocabulary", "s", "" . $str_name, "WA_DEFAULT");
        $InsertQuery->bindColumn("analysisrefid", "i", "".$analysis_refid, "WA_DEFAULT");
        $InsertQuery->bindColumn("refid", "i", $type, "WA_DEFAULT");
        $InsertQuery->bindColumn("preferred", "s", "N", "WA_DEFAULT");
        $InsertQuery->saveInSession("");
        $InsertQuery->execute();

        die(json_encode(array(
            'code' => "success",
            'info' => array(
                'name' => $str_name,
                'kind' => $str_kind,
                'ref_id' => $type,
            )
        )));
    }