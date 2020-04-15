<?php
//le formulaire ChoixFormateur vient d'être soumis
//on met l'offre dans la session, et on redirige vers InterfaceFormateur
$_SESSION['idOffre'] = $_POST["offre"];
header('Location: index.php?action=InterfaceFormateur');