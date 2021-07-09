<?php

require_once 'header.php';

echo '<script src="../public/report_cdr.js"></script>

    <div class="aheader">
    Report CDR
    </div>

    <div class="config_panel">
    <form class="form-inline" onSubmit="return myRefresh();">
        <label class="checkbox-inline">За период<input type="text" class="form-control" id="daterange" value=""></label>
        <label class="checkbox-inline">последние<input type="text" class="form-control" id="calls_limit" value="20" >звонков</label>
        <label class="checkbox-inline">искать номер<input type="text" class="form-control" id="search_number" value="" ></label>
    </form>
    </div>

<div id="but_excel">
    <a href="#"><img src="../public/css/images/csv-icon.png"></a>
</div> 
<div id="total" class="total"></div>


<div id="content"></div>
';


require 'footer.php';
