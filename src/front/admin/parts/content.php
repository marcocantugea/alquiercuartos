<?php

if(empty($_GET['p']) || !isset($_GET['p'])) {
    require("./pages/page_home.php");
    return;
}

$actions= explode("/",$_GET['p']);
require("./pages/page_".$actions[0].".php");
return;
