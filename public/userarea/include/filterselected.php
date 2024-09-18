<div class="row">
     
<div style='margin-right:5px; width: 190px;' class="col-xl-2 alert alert-info alert-dismissible fade show" role="alert">
                                            Filter Selected:
                                            
                                        </div>
                                     <?php if (isset($_SESSION['datefiltermin'])) { ?> <div style='margin-right:5px; width: 160px;' class="col-xl-2 alert alert-warning alert-dismissible fade show" role="alert">
                                            Date
                                            <a href="stats-levelone.php?deldatefilter=null"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></a>
                                        </div> <?php } ?> 
    <?php if (isset($_SESSION['supplierfilter'])) { ?><div style='margin-right:5px;  width: 160px;' class="col-xl-2 alert alert-warning alert-dismissible fade show" role="alert">
                                            Supplier
                                            <a href="stats-levelone.php?delsupplierfilter=null"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></a>
                                        </div><?php } ?>

                        </div>