<?php

$idOffre = $_SESSION['idOffre'];
$listeStagiaires = StagiaireManager::getStagiairesParOffres($idOffre);


$idSemaineEnCours = $_POST['idSemaine'];
$indexCheckbox = 0; 

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

    foreach($listeStagiaires as $stagiaire)
    {
        $idStagiaireEnCours = $stagiaire->getIdStagiaire();

        if ($_POST["idPointage".$i."s".$stagiaire->getIdStagiaire()]=='null')
        {
            $idPoint = null;
        }
        else
        {   
            $idPoint = $_POST["idPointage".$i."s".$idStagiaireEnCours];
        }

        $p = new Pointage(["idPointage"=>$idPoint, "idStagiaire"=>$idStagiaireEnCours, "idJournee"=>$_POST["idJournee".$i],
        "idPresence"=>$_POST['combo'.$i.'s'.$idStagiaireEnCours], "idSemaine"=>$idSemaineEnCours,
        "commentaire"=>$_POST['commentaire'.$i.'s'.$idStagiaireEnCours], "validation"=>$validation ]);
        
        $tabPointage[$idStagiaireEnCours][] = $p; // tableau à 2 dimensions permettant de stocker tous les pointages de tous les stagiaires
    }

    if($i%2 != 0)
    {
        $indexCheckbox += 2;
    }
}

foreach($listeStagiaires as $stagiaire)
{
    PointageManager::majPointage($idSemaineEnCours, $stagiaire->getIdStagiaire(), $tabPointage[$stagiaire->getIdStagiaire()]);
}

header('Location: index.php?action=InterfaceFormateur');