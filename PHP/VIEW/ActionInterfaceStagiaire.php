<?php
//le formulaire interface Stagiaire vient d'être soumis (appuie sur le bouton enregistrer)

//on recupere les données du stagiaires et de la semaine
$idSemaine = $_POST['idSemaine'];
$idStagiaire = $_SESSION['idStagiaire'];
//pour chaque pointage
for ($i = 0; $i < 9; $i++)
{
    //on modifie l'idpointage (il faut passer de la chaine "null" à la valeur null pour la base de données)
    if ($_POST["idPointage" . $i] == 'null')
    {
        $idpoint = null;}
    else
    {
        $idpoint = $_POST["idPointage" . $i];
    }
    //on crée un objet pointage avec toutes les informations recueillies
    $p = new Pointage(["idPointage" => $idpoint, "idStagiaire" => $idStagiaire,
        "idJournee" => $_POST["idJournee" . $i], "idPresence" => $_POST['combo' . $i],
        "commentaire" => $_POST['commentaire' . $i], "validation" => 0,
    ]);
    $tabPointage[] = $p;
}
//on enregistre le pointage en base de données
PointageManager::majPointage($idSemaine, $idStagiaire, $tabPointage);
header('location:index.php?action=InterfaceStagiaire');