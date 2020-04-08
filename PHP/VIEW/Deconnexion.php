<?php

session_destroy();
header("refresh:0,url=index.php?action=connect");
?>