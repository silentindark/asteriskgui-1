<?php

require_once 'header.php';   

echo '
<script src="../public/sip_channelstats.js"></script>

<header>
    <h1>Channels stat</h1>
</header>
<div id="but_excel"> 
	<a href="#"><img src="../public/css/images/csv-icon.png"></a>
</div> 
<div id="total" class="total"></div>

<div id="content"></div>
';

require 'footer.php';
?>