<?php
//le formulaire interface Formateur vient d'être soumis (appuie sur le bouton enregistrer ou coche d'une checkbox)
$idOffre = $_SESSION['idOffre'];
//on recupere les informations sur la semaine et les stagiaires associés à l'offre
$listeStagiaires = StagiaireManager::getStagiairesParOffres($idOffre);
$idSemaineEnCours = $_POST['idSemaine'];
$indexCheckbox = 0; 
//pour chacun des pointages
for ($i=0; $i<9; $i++)
{
    if(isset($_POST['checkbox'.$indexCheckbox])) // si la checkbox est présente dans le $_POST
    {
        $validation = 1; // 
    }
    else
    {
        $validation = 0;
    }
    //pour chaque stagiaire
    foreach($listeStagiaires as $stagiaire)
    {
        $idStagiaireEnCours = $stagiaire->getIdStagiaire();
        //on modifie l'idpointage (il faut passer de la chaine "null" à la valeur null pour la base de données)
        if ($_POST["idPointage".$i."s".$stagiaire->getIdStagiaire()]=='null')
        {
            $idPoint = null;
        }
        else
        {   
            $idPoint = $_POST["idPointage".$i."s".$idStagiaireEnCours];
        }
        // la combo et les inputs peuvent exister en meme temps (cf JS), on choisit la source
        if (isset($_POST["incombo".$i.'s'.$idStagiaireEnCours]))
        {   //si un input existe, je prend la presence de l'input
            $idPresence = $_POST["incombo".$i.'s'.$idStagiaireEnCours];
        }
        else{
            //je prend la presence de la combo
            $idPresence = $_POST["combo".$i.'s'.$idStagiaireEnCours];
        }
        //on crée un objet pointage avec toutes les informations recueillies
        $p = new Pointage(["idPointage"=>$idPoint, "idStagiaire"=>$idStagiaireEnCours, "idJournee"=>$_POST["idJournee".$i],
        "idPresence"=>$idPresence, "idSemaine"=>$idSemaineEnCours,
        "commentaire"=>$_POST['commentaire'.$i.'s'.$idStagiaireEnCours], "validation"=>$validation ]);
        //je stocke cet objet pour permettre plus tard,l'enregistrement en base
        $tabPointage[$idStagiaireEnCours][] = $p; // tableau à 2 dimensions permettant de stocker tous les pointages de tous les stagiaires
    }

    if($i%2 != 0)
    {   //on augmente l'index des checkbox un pointage sur 2 (une checkbox pour 2 demi-journée)
        $indexCheckbox += 2;
    }
}

foreach($listeStagiaires as $stagiaire)
{
    // pour chaque stagiaire, on enregistre le pointage en base de données
    PointageManager::majPointage($idSemaineEnCours, $stagiaire->getIdStagiaire(), $tabPointage[$stagiaire->getIdStagiaire()]);
}

header('Location: index.php?action=InterfaceFormateur');