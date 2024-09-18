<?php
// queries creation
$mainquery = "select * from reports left join products ON reports.idreports=products.idreports";
$failqueryvar = " AND LOWER(reports.reportsRating) = 'fail'";
$passqueryvar = " AND reports.reportsRating='PASS'";
$dataqueryvar = " AND reports.reportsRating='//'";

// var on off
if (isset($_SESSION['datefiltermin'])) {
    $datequeryvar = " AND reports.reportsDateOut BETWEEN '$datefiltermin' AND '$datefiltermax'";
} else {
    $datequeryvar = "";
};
if (isset($_SESSION['supplierfilter'])) {
    $supplierqueryvar = " AND products.namesupplier IN ('$supplierf')";
} else {
    $supplierqueryvar = "";
};
//print_r($_SESSION['supplierfilter']);
?>
<?php

// fetch failed reports
$sqlfail = "select * from reports WHERE LOWER(reports.reportsRating) = 'fail'";
$reportfail = mysqli_query($repnew, $sqlfail) or die("Error in Selecting " . mysqli_error($repnew));
$num_rows_fail = mysqli_num_rows($reportfail);

// fetch data reports
$sqldata = $mainquery . $dataqueryvar . $supplierqueryvar . $datequeryvar;
$reportdata = mysqli_query($repnew, $sqldata) or die("Error in Selecting " . mysqli_error($repnew));
$num_rows_data = mysqli_num_rows($reportdata);


//fetch table rows from mysql db
//echo $mainquery.$supplierqueryvar.$datequeryvar;
$sql = $mainquery . $supplierqueryvar . $datequeryvar;

$result = mysqli_query($repnew, $sql) or die("Error in Selecting " . mysqli_error($repnew));
$num_rows = mysqli_num_rows($result);

//fetch table rows from analysis
$sqlanalysis = "select * from result_project LEFT JOIN reports ON result_project.idreports=reports.idreports GROUP BY result_project.idPart, reports.reportsNumberLab  ";
$resultanalysis = mysqli_query($repnew, $sqlanalysis) or die("Error in Selecting " . mysqli_error($repnew));
$num_rows_analysis = mysqli_num_rows($resultanalysis);

//fetch table rows from analysis fail
$sqlanalysisfail = "
    SELECT COUNT(DISTINCT result_project.idPart, reports.reportsNumberLab) AS total_rows
    FROM result_project
    LEFT JOIN reports ON result_project.idreports = reports.idreports
    WHERE result_project.result_Rating = 'FAIL'
";

$resultanalysisfail = mysqli_query($repnew, $sqlanalysisfail) or die("Error in Selecting " . mysqli_error($repnew));

// Fetch the result
$row = mysqli_fetch_assoc($resultanalysisfail);
$num_rows_analysis_fail = $row['total_rows'];
if ($num_rows_analysis > 0) {
    $percanalysisfail = round(($num_rows_analysis_fail / $num_rows_analysis) * 100, 1);
} else {
    $percanalysisfail = 0; // Oppure un altro valore di default
}

//create an array

if (!function_exists('replaceSmartQuotes')) {
    function replaceSmartQuotes($string)
    {
        $search = array(chr(145), chr(146), chr(147), chr(148), chr(151));
        $replace = array("'", "'", '"', '"', '-');
        return str_replace($search, $replace, $string);
    }
}

if (!function_exists('replaceSmartQuotesInArray')) {
    function replaceSmartQuotesInArray($array)
    {
        foreach ($array as $key => $value) {
            if (is_string($value)) {
                $array[$key] = replaceSmartQuotes($value);
            } elseif (is_array($value)) {
                $array[$key] = replaceSmartQuotesInArray($value);
            }
        }
        return $array;
    }
}

$reportsarray = array();
while ($row = mysqli_fetch_assoc($result)) {
    $reportsarray[] = replaceSmartQuotesInArray($row);
}
//$totalreport=
if ((!empty($num_rows)) && (!empty($num_rows_fail))) {
    $perfail = round(($num_rows_fail / $num_rows) * 100, 1);
}
if ((!empty($num_rows)) && (!empty($num_rows_data))) {
    $perdata = round(($num_rows_data / $num_rows) * 100, 1);
}
if ((!empty($num_rows)) && (isset($num_rows_fail)) && (!empty($num_rows_data))) {
    $numpassres = ($num_rows - $num_rows_data - $num_rows_fail);
}
if ((!empty($perfail)) && (!empty($perdata))) {
    $perpass = round(100 - $perfail - $perdata, 1);
}

?>
<?php
// fecth data of top 10 worst analysis

// $worstanalysis = "select result_project.idAnalysis AS analysisid, result_TestName, name_analysis , COUNT(*) as counter from result_project LEFT JOIN reports ON result_project.idreports=reports.idreports LEFT JOIN analysis ON result_project.idAnalysis=analysis.idanalysis WHERE reports.idcompany='$idcompany' AND result_project.result_Rating='F' GROUP BY result_TestName ORDER by counter DESC LIMIT 10";


$worstanalysis = "SELECT result_TestName, COUNT(*) AS counter 
FROM result_project 
LEFT JOIN reports ON result_project.idreports = reports.idreports 
WHERE result_project.result_Rating = 'FAIL' 
GROUP BY result_TestName 
ORDER BY counter DESC 
LIMIT 10;
";
$resultworstanalysis = mysqli_query($repnew, $worstanalysis) or die("Error in Selecting " . mysqli_error($repnew));




// Print out result
$ncounter = array();
$testname = array();
while ($row = mysqli_fetch_array($resultworstanalysis)) {
    $ncounter[] = $row['counter'];
    $testname[] = replaceSmartQuotes($row['result_TestName']);
}


?>
<?php
// fecth data of failed analysis

// $worstanalysis = "select result_project.idAnalysis AS analysisid, result_TestName, name_analysis , COUNT(*) as counter from result_project LEFT JOIN reports ON result_project.idreports=reports.idreports LEFT JOIN analysis ON result_project.idAnalysis=analysis.idanalysis WHERE reports.idcompany='$idcompany' AND result_project.result_Rating='F' GROUP BY result_TestName ORDER by counter DESC LIMIT 10";


$failedanalysis = "SELECT result_TestName, COUNT(*) AS counter 
FROM result_project 
LEFT JOIN reports ON result_project.idreports = reports.idreports 
WHERE result_project.result_Rating = 'FAIL' 
GROUP BY result_TestName 
ORDER BY counter DESC 
LIMIT 50;
";
$resultfailedanalysis = mysqli_query($repnew, $failedanalysis) or die("Error in Selecting " . mysqli_error($repnew));


// Print out result
$ncounterfailed = array();
$testnamefailed = array();
while ($row = mysqli_fetch_array($resultfailedanalysis)) {
    $ncounterfailed[] = $row['counter'];
    $testnamefailed[] = replaceSmartQuotes($row['result_TestName']);
}


?>
<?php
// fecth failed components

// $worstanalysis = "select result_project.idAnalysis AS analysisid, result_TestName, name_analysis , COUNT(*) as counter from result_project LEFT JOIN reports ON result_project.idreports=reports.idreports LEFT JOIN analysis ON result_project.idAnalysis=analysis.idanalysis WHERE reports.idcompany='$idcompany' AND result_project.result_Rating='F' GROUP BY result_TestName ORDER by counter DESC LIMIT 10";


$failedanalyts = "SELECT result_AnalytsName, COUNT(*) AS counter 
FROM result_project 
LEFT JOIN reports ON result_project.idreports = reports.idreports 
WHERE result_project.result_AnalytsRating = 'F' 
GROUP BY result_AnalytsName 
ORDER BY counter DESC 
LIMIT 100;
";
$resultfailedanalyts = mysqli_query($repnew, $failedanalyts) or die("Error in Selecting " . mysqli_error($repnew));


// Print out result
$ncounterfailedanalyts = array();
$testnamefailedanalyts = array();
while ($row = mysqli_fetch_array($resultfailedanalyts)) {
    $ncounterfailedanalyts[] = $row['counter'];
    $testnamefailedanalyts[] = $row['result_AnalytsName'];
}


?>
<?php
// fecth worst supplier

// $worstanalysis = "select result_project.idAnalysis AS analysisid, result_TestName, name_analysis , COUNT(*) as counter from result_project LEFT JOIN reports ON result_project.idreports=reports.idreports LEFT JOIN analysis ON result_project.idAnalysis=analysis.idanalysis WHERE reports.idcompany='$idcompany' AND result_project.result_Rating='F' GROUP BY result_TestName ORDER by counter DESC LIMIT 10";


$worstsupplier = "SELECT namesupplier, COUNT(*) AS counter 
FROM result_project 
LEFT JOIN reports ON result_project.idreports = reports.idreports 
LEFT JOIN products ON result_project.idproducts = products.idproducts 
WHERE LOWER(result_project.result_Rating) = 'fail' 
GROUP BY namesupplier 
ORDER BY counter DESC 
LIMIT 10;
";

$resultworstsupplier = mysqli_query($repnew, $worstsupplier) or die("Error in Selecting " . mysqli_error($repnew));


// Print out result
$ncounterworstsupplier = array();
$testnameworstsupplier = array();
while ($row = mysqli_fetch_array($resultworstsupplier)) {
    $ncounterworstsupplier[] = $row['counter'];
    $testnameworstsupplier[] = $row['namesupplier'];
}


?>
<?php
// fecth region area

// $worstanalysis = "select result_project.idAnalysis AS analysisid, result_TestName, name_analysis , COUNT(*) as counter from result_project LEFT JOIN reports ON result_project.idreports=reports.idreports LEFT JOIN analysis ON result_project.idAnalysis=analysis.idanalysis WHERE reports.idcompany='$idcompany' AND result_project.result_Rating='F' GROUP BY result_TestName ORDER by counter DESC LIMIT 10";


$worstregion = "SELECT products_region, COUNT(*) AS counter 
FROM result_project 
LEFT JOIN reports ON result_project.idreports = reports.idreports 
LEFT JOIN products ON result_project.idreports = products.idreports 
WHERE result_project.result_Rating = 'FAIL' 
GROUP BY namesupplier 
ORDER BY counter DESC 
LIMIT 10;
";
$resultworstregion = mysqli_query($repnew, $worstregion) or die("Error in Selecting " . mysqli_error($repnew));


// Print out result
$ncounterregion = array();
$nameregion = array();
while ($row = mysqli_fetch_array($resultworstregion)) {
    $ncounterregion[] = $row['counter'];
    $nameregion[] = $row['products_region'];
}


?>