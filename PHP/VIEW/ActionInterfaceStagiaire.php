<?php
$idSemaine = $_POST['idSemaine'];
$idStagiaire=$_SESSION['idStagiaire'];
var_dump($_POST);
for ($i=0;$i<9;$i++)
{
    if ($_POST["idPointage".$i]=='null')
    $idpoint = null;
    else 
    $idpoint = $_POST["idPointage".$i];
    $p= new Pointage(["idPointage"=>$idpoint,"idStagiaire"=>$idStagiaire,
    "idJournee"=>$_POST["idJournee".$i],"idPresence"=>$_POST['combo'.$i],
    "commentaire"=>$_POST['commentaire'.$i],"validation"=>0
    ]);
    $tabPointage[]=$p;
}
var_dump($tabPointage);
PointageManager::majPointage($idSemaine, $idStagiaire, $tabPointage);