
<?php

#############################################################
#                IMPORT DE PHP SPREADSHEET                  #
#############################################################

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

##########################################################################################################################################
#                                                            PROGRAMME                                                                   #
##########################################################################################################################################

echo '<div id="interfaceAT">';

//Si l'offre ou la semaine ne sont pas selectionnés, retour à l'acceuil
if (!isset($_GET["mode"]))
{
    header("Location: index.php?action=InterfaceAT");
}

//Si exportation du CSV d'une offre
if ($_GET["mode"] == "unique")
{

    if (!isset($_GET["idOffre"]) || !isset($_GET["idSemaine"]))
    {
        header("Location: index.php?action=InterfaceAT");
    }
    $idOffre = $_GET["idOffre"];
    $numOffre = OffreManager::findById($idOffre)->getNumOffre();
    $numSemaine = SemaineManager::findById($_GET["idSemaine"])->getNumSemaine();
    $codeFormation = FormationManager::findById(OffreManager::findById($idOffre)->getIdFormation())->getCodeFormation();

    $gabarit = \PhpOffice\PhpSpreadsheet\IOFactory::load("CSV/Gabarit.xlsx");
    $sheet = $gabarit->getActiveSheet();
    $nomFichier = "Pointages Offre " . $numOffre . " - Semaine n°" . $numSemaine;
    saveCSV($nomFichier, $gabarit);
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("CSV/" . $nomFichier . ".xlsx");
    $onglet = $spreadsheet->getActiveSheet();
    $onglet->setTitle($numOffre . ' - ' . $codeFormation);

    remplirOnglet($_GET["idOffre"], $_GET["idSemaine"], $onglet);
    saveCSV($nomFichier, $spreadsheet);

//Si exportation du CSV de plusieurs offres
}
else if ($_GET["mode"] == "multiple")
{
    if (!isset($_POST["idOffres"]) || !isset($_GET["idSemaine"]))
    {
        header("Location: index.php?action=InterfaceAT");
    }

    $numSemaine = SemaineManager::findById($_GET["idSemaine"])->getNumSemaine();

    $gabarit = \PhpOffice\PhpSpreadsheet\IOFactory::load("CSV/Gabarit.xlsx");
    $sheet = $gabarit->getActiveSheet();
    // $nomFichier = "Pointages Offre " . implode(',', $numOffres) . " - Semaine n°" . $numSemaine;
    $nomFichier = "Pointages Offres ".$_POST['listeOffres']." Semaine n°" . $numSemaine;
    saveCSV($nomFichier, $gabarit);
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("CSV/" . $nomFichier . ".xlsx");
    $onglet = $spreadsheet->getActiveSheet();

    $numOffres = [];

    foreach ($_POST["idOffres"] as $key => $idOffre)
    {

        $numOffre = OffreManager::findById($idOffre)->getNumOffre();
        $codeFormation = FormationManager::findById(OffreManager::findById($idOffre)->getIdFormation())->getCodeFormation();

        if ($spreadsheet->getSheetByName($numOffre . ' - ' . $codeFormation) == null)
        {
            $numOffres[] = $numOffre;
            //on duplique l'onglet
            $clonedWorksheet = clone $spreadsheet->getSheetByName('1');
            $clonedWorksheet->setTitle($numOffre . ' - ' . $codeFormation);
            $spreadsheet->addSheet($clonedWorksheet);

            remplirOnglet($idOffre, $_GET["idSemaine"], $clonedWorksheet);

        }
    }
    //on enleve le gabarit
    $spreadsheet->removeSheetByIndex(0);
    $spreadsheet->setActiveSheetIndex(0);
}

saveCSV($nomFichier, $spreadsheet);

echo '<p>Le fichier "' . $nomFichier . '" a bien été créé.</p>';
echo '<div class="centrer"><a class="btna " href="CSV/' . $nomFichier . '">Ouvrir fichier</a></div>';
echo '<div class="centrer"><a class="btna " href="index.php?action=InterfaceAT">Retour</a></div>';

##########################################################################################################################################
#                                                            FONCTIONS                                                                   #
##########################################################################################################################################

//Fonction qui sauvegarde le fichier
function saveCSV($nomFichier, $spreadsheet)
{

    //Créer le dossier CSV s'il n'existe pas
    if (!is_dir("CSV"))
    {
        mkdir("CSV");
    }

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
    $writer->save("CSV/" . $nomFichier . ".xlsx");

}


function remplirOnglet($idOffre, $idSemaine, $onglet)
{

    //Pointages de tous les stagiaires dans la semaine
    $stagiaires = StagiaireManager::getStagiairesParOffres($idOffre);

    $numOffre = OffreManager::findById($idOffre)->getNumOffre();
    $numSemaine = SemaineManager::findById($idSemaine)->getNumSemaine();
    $codeFormation = FormationManager::findById(OffreManager::findById($idOffre)->getIdFormation())->getCodeFormation();
    $lesJours = JourneeManager::getListBySemaine($idSemaine);

    //Cellule de titre
    $onglet->setCellValue('A1', 'Pointages des bénéficiaires de l\'offre ' . $numOffre . ' - ' . $codeFormation . ' pendant la semaine n°' . $numSemaine);

    //En-tête du tableau
     $i = 0;
    foreach ($stagiaires as $stagiaire)
    {
   
        $colonne = 0;
        $pointagesStagiaire = PointageManager::getListValidesByStagiaire($stagiaire->getIdStagiaire(), $idSemaine);
        $identifStagiaire = StagiaireManager::findById($pointagesStagiaire[0]->getIdStagiaire());
        $beneficiaire = $identifStagiaire->getNumBenef() . ' - ' . $identifStagiaire->getNom() . ' ' . $identifStagiaire->getPrenom();
        $onglet->setCellValueByColumnAndRow(1, 4 + $i, $beneficiaire);
        foreach ($pointagesStagiaire as $pointage)
        {
            if ($pointage->getIdJournee() == $lesJours[$colonne]->getIdJournee())
            {
                //Récupération de l'indicateur de présence grace à son id dans pointage
                $presence = PresenceManager::findById($pointage->getIdPresence());

                $refPresence = $presence->getRefPresence();

                //Afficher le code de présence
                $onglet->setCellValueByColumnAndRow($colonne + 2,$i+4,  $refPresence);

            }
            $colonne++;
        }
        
        $i++;
    }

    return $onglet;

}

?>

</div>
