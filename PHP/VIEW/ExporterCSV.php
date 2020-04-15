<?php
//cette partie permet de mettre les données de pointage sous forme de fichier Excel.
// D'abord on duplique un fichier existant comportant les mises en forme.
// si le choix est une selection multiple, on prepare un onglet par offre


#############################################################
#                IMPORT DE PHP SPREADSHEET                  #
#############################################################

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

##########################################################################################################################################
#                                                            PROGRAMME                                                                   #
##########################################################################################################################################

echo '<div id="interfaceAT">';

//Si l'offre ou la semaine ne sont pas selectionnés, retour à la sélection
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
    //on recupere les informations sur l'offre, la semaine et la formation
    $numOffre = OffreManager::findById($idOffre)->getNumOffre();
    $numSemaine = SemaineManager::findById($_GET["idSemaine"])->getNumSemaine();
    $codeFormation = FormationManager::findById(OffreManager::findById($idOffre)->getIdFormation())->getCodeFormation();

    // on recupere le fichier modele
    $gabarit = \PhpOffice\PhpSpreadsheet\IOFactory::load("CSV/Gabarit.xlsx");
    $sheet = $gabarit->getActiveSheet();
    $nomFichier = "Pointages Offre " . $numOffre . " - Semaine n°" . $numSemaine;
    //on le sauvegarde sous le nom definitif
    saveCSV($nomFichier, $gabarit);
    //on reprend le fichier dans une variable
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("CSV/" . $nomFichier . ".xlsx");
    $onglet = $spreadsheet->getActiveSheet();
    //on renomme l'onglet
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
    //on recupere les informations sur la semaine
    $numSemaine = SemaineManager::findById($_GET["idSemaine"])->getNumSemaine();
    // on recupere le fichier modele
    $gabarit = \PhpOffice\PhpSpreadsheet\IOFactory::load("CSV/Gabarit.xlsx");
    $sheet = $gabarit->getActiveSheet();
    //on le sauvegarde sous le nom definitif
    $nomFichier = "Pointages Offres ".$_POST['listeOffres']." Semaine n°" . $numSemaine;
    saveCSV($nomFichier, $gabarit);
    //on reprend le fichier dans une variable
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("CSV/" . $nomFichier . ".xlsx");
    $onglet = $spreadsheet->getActiveSheet();

    $numOffres = [];
    //pour chaque offre
    foreach ($_POST["idOffres"] as $key => $idOffre)
    {
        //on recupere les informations de l'offre et de la formation
        $numOffre = OffreManager::findById($idOffre)->getNumOffre();
        $codeFormation = FormationManager::findById(OffreManager::findById($idOffre)->getIdFormation())->getCodeFormation();
        //on vérifie que l'onglet avec ce numero d'offre n'existe pas deja.
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
    //on enleve le 1er onglet du gabarit
    $spreadsheet->removeSheetByIndex(0);
    //on replace le fichier sur le 1er onglet
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


/**
 * remplirOnglet : permet d'inscrire les numéros de beneficiaires et les pointages pour une offre et une semaine
 *
 * @param  mixed $idOffre
 * @param  mixed $idSemaine
 * @param  mixed $onglet
 * @return void
 */
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

    //Données de pointage
     $i = 0;
    foreach ($stagiaires as $stagiaire)
    {
        $colonne = 0;
        //on recupere les informations du stagiaire
        $pointagesStagiaire = PointageManager::getListValidesByStagiaire($stagiaire->getIdStagiaire(), $idSemaine);
        $identifStagiaire = StagiaireManager::findById($pointagesStagiaire[0]->getIdStagiaire());
        $beneficiaire = $identifStagiaire->getNumBenef() . ' - ' . $identifStagiaire->getNom() . ' ' . $identifStagiaire->getPrenom();
        //on inscrit le numero de beneficiaire, le nom et prenom dans la colonne 1 (A) à partir de  la 4eme ligne
        $onglet->setCellValueByColumnAndRow(1, 4 + $i, $beneficiaire);
        foreach ($pointagesStagiaire as $pointage)
        {
            //pour chaque pointage, on vérifie qu'il correspond à la journée que l'on traite
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
}

?>

</div>
