<?php

session_destroy();
echo '<p>Vous êtes à présent déconnecté <br />';
header("refresh:3,url=index.php?action=connect");
?>