<?php
$idSemaine = $_POST['idSemaine'];
$idStagiaire = $_SESSION['idStagiaire'];
for ($i = 0; $i < 9; $i++)
{
    if ($_POST["idPointage" . $i] == 'null')
    {
        $idpoint = null;}
    else
    {
        $idpoint = $_POST["idPointage" . $i];
    }
    $p = new Pointage(["idPointage" => $idpoint, "idStagiaire" => $idStagiaire,
        "idJournee" => $_POST["idJournee" . $i], "idPresence" => $_POST['combo' . $i],
        "commentaire" => $_POST['commentaire' . $i], "validation" => 0,
    ]);
    $tabPointage[] = $p;
}

PointageManager::majPointage($idSemaine, $idStagiaire, $tabPointage);
header('location:index.php?action=InterfaceStagiaire');