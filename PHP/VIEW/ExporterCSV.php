
<?php

#############################################################
#                IMPORT DE PHP SPREADSHEET                  #
#############################################################

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

##########################################################################################################################################
#                                                            PROGRAMME                                                                   #
##########################################################################################################################################

echo '<div id="interfaceAT">';

//Si l'offre ou la semaine ne sont pas selectionnés, retour à l'acceuil
if (!isset($_GET["mode"])) {
    header("Location: index.php?action=InterfaceAT");
}

//Si exportation du CSV d'une offre
if ($_GET["mode"] == "unique") {

    if (!isset($_GET["idOffre"]) || !isset($_GET["idSemaine"])) {
        header("Location: index.php?action=InterfaceAT");
    }

    $numOffre = OffreManager::findById($_GET["idOffre"])->getNumOffre();
    $numSemaine = SemaineManager::findById($_GET["idSemaine"])->getNumSemaine();

    $spreadsheet = new Spreadsheet();
    $spreadsheet->removeSheetByIndex(0);

    $onglet = getOngletOffre($_GET["idOffre"], $_GET["idSemaine"]);

    $spreadsheet->addSheet($onglet, 0);
    $spreadsheet->setActiveSheetIndex(0);

    $spreadsheet->getActiveSheet()->getStyle('A1:Z200')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $colonnes = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
    foreach ($colonnes as $colonne) {
        $spreadsheet->getActiveSheet()->getColumnDimension($colonne)->setWidth(20);
    }

    $nomFichier = "Pointages Offre " . $numOffre . " - Semaine n°" . $numSemaine;

//Si exportation du CSV de plusieurs offres
} else if ($_GET["mode"] == "multiple") {
    if (!isset($_POST["idOffres"]) || !isset($_GET["idSemaine"])) {
        header("Location: index.php");
    }

    $numSemaine = SemaineManager::findById($_GET["idSemaine"])->getNumSemaine();

    $spreadsheet = new Spreadsheet();
    $spreadsheet->removeSheetByIndex(0);

    $numOffres = [];

    foreach ($_POST["idOffres"] as $key => $idOffre) {

        $numOffre = OffreManager::findById($idOffre)->getNumOffre();

        if ($spreadsheet->getSheetByName($numOffre . ' - libelle formation') == null) {

            $numOffres[] = $numOffre;

            $onglet = getOngletOffre($idOffre, $_GET["idSemaine"]);
            $spreadsheet->addSheet($onglet, $key);

        }
    }

    foreach($spreadsheet->getAllSheets() as $sheet){
        $sheet->getStyle('A1:Z200')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $colonnes = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
        foreach ($colonnes as $colonne) {
            $sheet->getColumnDimension($colonne)->setWidth(20);
        }
    }

    
    $spreadsheet->setActiveSheetIndex(0);

    $nomFichier = "Pointages Offre " . implode(',', $numOffres) . " - Semaine n°" . $numSemaine;

}

saveCSV($nomFichier,$spreadsheet);

echo '<p>Le fichier "' . $nomFichier . '" a bien été créé.</p>';
echo '<a class="bouton" href="CSV/' . $nomFichier . '">Ouvrir fichier</a>';
echo '<a class="bouton" href="index.php?action=InterfaceAT">Retour</a>';

##########################################################################################################################################
#                                                            FONCTIONS                                                                   #
##########################################################################################################################################

//Fonction qui sauvegarde le fichier
function saveCSV($nomFichier,$spreadsheet)
{

    //Créer le dossier CSV s'il n'existe pas
    if (!is_dir("CSV")) {
        mkdir("CSV");
    }

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
    $writer->save("CSV/" . $nomFichier . ".xlsx");

}

//Fonction qui retourne une liste triée des pointages d'un offre
function getPointagesOffreSemaine($idOffre, $idSemaine)
{

    #############################################################
    #                RECUPERATION DES DONNEES                   #
    #############################################################

    //Récupérer et afficher la liste des pointages correspondants à l'offre et à la semaine
    $pointages = [];

    //Stagiaires de l'offre
    $stagiaires = StagiaireManager::getStagiairesParOffres($idOffre);

    $pointagesStagiaire = []; //Pointages d'un stagiaire dans la semaine
    $pointagesStagiaires = []; //Listes des pointages de tout les stagiaires dans la semaine

    //Pour chaque stagiaire de l'offre
    foreach ($stagiaires as $stagiaire) {

        //Recuperer les pointages de la semaine
        $pointagesStagiaire = PointageManager::getListValidesByStagiaire($stagiaire->getIdStagiaire(), $idSemaine);

        //Ajouter les pointages du stagiaire à la liste de pointage tout les stagiaires
        $pointagesStagiaires[$stagiaire->getIdStagiaire()] = $pointagesStagiaire;
    }

    //Nb total de pointages
    $nbPointages = 0;

    //Compte le nb total de pointages en ajoutant le nombre de pointages de chaque stagiaire
    foreach ($pointagesStagiaires as $pointagesStagiaire) {
        $nbPointages += count($pointagesStagiaire);
    }

    #############################################################
    #    TRI DES DONNEES PAR STAGIAIRE, JOUR, DEMI-JOURNÉE      #
    #############################################################

    //Pour chaque stagiaire
    foreach ($stagiaires as $stagiaire) {
        //Récupérer la liste de ses pointages dans la semaine
        $pointagesStagiaire = $pointagesStagiaires[$stagiaire->getIdStagiaire()];

        //Liste des jours de la semaine
        $jours = JourneeManager::getListBySemaine($idSemaine);

        //Libellé des jours
        $libelleJours = ["LUNDI", "MARDI", "MERCREDI", "JEUDI", "VENDREDI"];

        //Jours associés à leur libellé
        // Ex: ["LUNDI" => 2020-04-07,"MARDI" => 2020-04-08, ......... ]
        $joursSemaine = [];

        $indexSemaine = 0;

        foreach ($jours as $key => $jour) {

            if ($key > 0) {
                if ($jour->getJour() != $jours[$key - 1]->getJour()) {
                    $indexSemaine++;
                }
            }

            $joursSemaine[$libelleJours[$indexSemaine]] = $jour->getJour();

        }

        $pointagesJours = [];

        //Remplissage de $pointagesJours
        // Ex:  ["LUNDI"=>["matin"=>$pointage1,"apres-midi"=>$pointage2],"MARDI"=>["matin"=>$pointage3,"apres-midi"=>$pointage4] ....... ]

        foreach ($pointagesStagiaire as $pointage) {

            //Jour du pointage
            $jour = JourneeManager::findById($pointage->getIdJournee());

            //Remplissage de $pointagesJours
            foreach ($libelleJours as $libelle) {
                if ($joursSemaine[$libelle] == $jour->getJour()) {
                    if ($jour->getDemiJournee() == "matin") {
                        $pointagesJours[$libelle]["matin"] = $pointage;
                    } else if ($jour->getDemiJournee() == "après-midi") {
                        $pointagesJours[$libelle]["après-midi"] = $pointage;
                    }
                }
            }
        }

        $pointages[] = [
            "numBenef" => $stagiaire->getNumBenef(),
            "pointages" => $pointagesJours,
        ];
    }

    return $pointages;
}

//Fonction qui retourne un onglet pour les pointages d'une offre
function getOngletOffre($idOffre, $idSemaine)
{

    //Pointages de tous les stagiaires dans la semaine
    $pointagesStagiaires = getPointagesOffreSemaine($idOffre, $idSemaine);

    $numOffre = OffreManager::findById($idOffre)->getNumOffre();
    $numSemaine = SemaineManager::findById($idSemaine)->getNumSemaine();

    //Créer un nouvel onglet
    $onglet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet(null, $numOffre . ' - libelle formation');

    //Cellule de titre
    $onglet->setCellValue('A1', 'Pointages des bénéficiaires de l\'offre ' . $numOffre . ' pendant la semaine n°' . $numSemaine);
    $onglet->mergeCells('A1:G1');

    //En-tête du tableau

    $jours = ['LUNDI', 'MARDI', 'MERCREDI', 'JEUDI', 'VENDREDI'];

    $entetes = array_merge(['N°Bénéficiaire'], $jours);

    foreach ($entetes as $key => $entete) {
        $onglet->setCellValueByColumnAndRow($key + 1, 3, $entete);
    }

    foreach ($pointagesStagiaires as $key => $pointagesStagiaire) {

        $onglet->setCellValueByColumnAndRow(1, 4 + ($key * 2), $pointagesStagiaire["numBenef"]);
        $onglet->mergeCellsByColumnAndRow(1, 4 + ($key * 2), 1, 4 + ($key * 2) + 1);

        $pointagesJours = $pointagesStagiaire["pointages"];

        foreach ($jours as $keyJour => $libelle) {

            //Si un pointage correspond à ce jour
            if (isset($pointagesJours[$libelle])) {

                //Si une information de pointage existe pour le matin
                if (isset($pointagesJours[$libelle]["matin"])) {

                    //Récupération de l'indicateur de présence grace à son id dans pointage
                    $presence = PresenceManager::findById($pointagesJours[$libelle]["matin"]->getIdPresence());

                    $refPresence = $presence->getRefPresence();
                    $libellePresence = $presence->getLibellePresence();

                    //Afficher le code de présence
                    $onglet->setCellValueByColumnAndRow($keyJour + 2, 4 + ($key * 2), $refPresence);
                }

                //Si une information de pointage existe pour l'après-midi
                if (isset($pointagesJours[$libelle]["après-midi"])) {

                    //Récupération de l'indicateur de présence grace à son id dans pointage
                    $presence = PresenceManager::findById($pointagesJours[$libelle]["après-midi"]->getIdPresence());

                    $refPresence = $presence->getRefPresence();
                    $libellePresence = $presence->getLibellePresence();

                    //Afficher le code de présence
                    $onglet->setCellValueByColumnAndRow($keyJour + 2, 4 + ($key * 2) + 1, $refPresence);
                }
            }
        }

    }

    return $onglet;

}

?>

</div>
