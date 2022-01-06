<?php

$title = 'Show queues';
require_once 'header.php';
?>
    <script src="../public/queue.js"></script>

    <div class="config_panel" id="config_panel">
        <label><input id="na" type="checkbox">Not active</label>
        <label><input id="empty" type="checkbox">Empty</label>
    </div>

    <div id="content"></div>
<?php
require 'footer.php';
