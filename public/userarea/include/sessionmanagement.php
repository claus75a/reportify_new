<?php
// get variable
if (isset($_GET['supplierfilter'])) {
    $supplierfArray=array();
$supplierArray=$_GET['supplierfilter'];
    foreach ($supplierArray as $suppliername) {
        $supplierfArray[]=$suppliername;
    }
    $_SESSION['supplierfilter']=implode("','",$supplierfArray);
}
if (isset($_GET['daterange'])) {
    $vardate=$_GET['daterange'];
  $datefilter=explode(" - ",$vardate);
    $datefilterminone=str_replace("/","-",$datefilter[0]);
    $datefiltermintwo=explode("-",$datefilterminone);
    $_SESSION['datefiltermin']=$datefiltermintwo[2].'-'.$datefiltermintwo[0].'-'.$datefiltermintwo[1];
    $datefiltermaxone=str_replace("/","-",$datefilter[1]);
    $datefiltermaxtwo=explode("-",$datefiltermaxone);
    $_SESSION['datefiltermax']=$datefiltermaxtwo[2].'-'.$datefiltermaxtwo[0].'-'.$datefiltermaxtwo[1];

}

// unset variable
if (isset($_GET['delsupplierfilter'])) { unset ($_SESSION["supplierfilter"]); }
if (isset($_GET['deldatefilter'])) { unset ($_SESSION["datefiltermin"]); 
                                   unset ($_SESSION["datefiltermax"]); 
                                   }
?>

<?php
if (isset($_SESSION['datefiltermin'])) {
    $datefiltermin=$_SESSION['datefiltermin']; }
if (isset($_SESSION['datefiltermax'])) {
    $datefiltermax=$_SESSION['datefiltermax'];; }
if (isset($_SESSION['supplierfilter'])) {
    $supplierf=$_SESSION['supplierfilter']; }
if (!isset($_SESSION['labfilter'])) {
    $_SESSION['labfilter']='*'; }
if (!isset($_SESSION['reportnumberfilter'])) {
    $_SESSION['reportnumberfilter']='*'; }
if (!isset($_SESSION['prdorefnumberfilter'])) {
    $_SESSION['prdorefnumberfilter']='*'; }
if (!isset($_SESSION['proddescriptionfilter'])) {
    $_SESSION['proddescriptionfilter']='*'; }
if (!isset($_SESSION['prodskufilter'])) {
    $_SESSION['prodskufilter']='*'; }
if (!isset($_SESSION['prodseasonfilter'])) {
    $_SESSION['prodseasonfilter']='*'; }
if (!isset($_SESSION['prodbuyerfilter'])) {
    $_SESSION['prodbuyerfilter']='*'; }

//print_r($_SESSION['supplierfilter']);
?>