
<div id="interfaceAT">

<?php

##########################################################################################################################################
#                                                   SELECTION OFFRES ET SEMAINE                                                          #
##########################################################################################################################################

//Formulaire de selection d'offre et de semaine
echo

    '<form id="selectionForm" action="" method="POST">

    <input type="hidden" name="action" value="' . $_GET['action'] . '">

    <div class="elementForm">';

echo '<div class="centrer"><fieldset id="listeOffres"><legend>Numero d\'Offre:</legend><div class="numOffres">';

$offres = OffreManager::getList();
foreach ($offres as $offre) {

    $offreFormation = FormationManager::findById($offre->getIdFormation())->getCodeFormation();

    $checked = "";

    //Mettre la checkbox en checked si l'offre correspond et recupérer son id
    if (isset($_POST['numOffre'])) {
        if (in_array($offre->getNumOffre(), $_POST['numOffre']) !== false) {
            $idOffres[$offre->getNumOffre()] = $offre->getIdOffre();
            $checked = "checked";
        }
    }

    //Affichage de l'option avec le numero d'offre
    echo '<span class="offre ' . $checked . '"><input class="checkboxOffre" type="checkbox" name="numOffre[]" ' . $checked . ' value="' . $offre->getNumOffre() . '">' . $offre->getNumOffre() . ' : ' . $offreFormation . '</span>';
}

echo '</div></fieldset></div>';
echo '<div class=centrer><p id="selectAllOffres" class="btna">Tout selectionner</p></div>';

echo '</div><div class="elementForm">

    <p><label for="semaine">Numero de semaine: </label>

    <select name="semaine">';

//Affichage des options de selection des semaines
$semaines = SemaineManager::getList();

foreach ($semaines as $semaine) {

    //Récuperation du premier et dernier jour de la semaine pour afficher dans le select
    $jours = JourneeManager::getListBySemaine($semaine->getIdSemaine());

    if (count($jours) > 0) {
        $premierJour = $jours[0]->getJour();
        $dernierJour = end($jours)->getJour();
    } else {
        $premierJour = "####-##-##";
        $dernierJour = "####-##-##";
    }

    //Mettre l'option en selected si la semaine correspond et recupérer son id
    if (isset($_POST['semaine'])) {
        if ($semaine->getNumSemaine() == $_POST['semaine']) {
            $idSemaine = $semaine->getIdSemaine();
            $selected = "selected";
        } else {
            $selected = "";
        }
    }

    //Affichage de l'option avec le numero de semaine, le premier jour et le dernier jour
    echo '<option ' . $selected . ' value="' . $semaine->getNumSemaine() . '"> N°' . $semaine->getNumSemaine() . ' du ' . $premierJour . ' au ' . $dernierJour . '</option>';
}

echo '</select></div>

<div class="centrer"> <input type="submit" value="Afficher"></div>

</form>';

##########################################################################################################################################
#                                         RECUPERATION ET AFFICHAGE DES DONNÉES DE POINTAGE                                              #
##########################################################################################################################################

//Si une offre et une semaine sont selectionnés

if (isset($_POST["numOffre"])) {

    $offres = $_POST["numOffre"];

    if (isset($_POST["semaine"])) {
        $semaine = $_POST["semaine"];
    }

    //Bouton vers l'export au format csv
    echo '<form action="index.php?action=exporterCSV&mode=multiple&idSemaine=' . $idSemaine . '" method="POST">';

    echo '<input type="submit" value="Tout Exporter CSV">';

    #############################################################
    #                RECUPERATION DES DONNEES                   #
    #############################################################

    //Pour chaque offre selectionnée
    foreach ($offres as $offre) {

        // $formation =  FormationManager::findById($idOffres[$offre]);

        // if($formation != null){
        //     $offreFormation = $formation->getCodeFormation();
        // }

        //Récupérer et afficher la liste des pointages correspondants à l'offre et à la semaine

        $pointages = [];

        //Stagiaires de l'offre
        $stagiaires = StagiaireManager::getStagiairesParOffres($idOffres[$offre]);

        $pointagesStagiaire = []; //Pointages d'un stagiaire dans la semaine
        $pointagesStagiaires = []; //Listes des pointages de tout les stagiaires dans la semaine

        //Pour chaque stagiaire de l'offre
        foreach ($stagiaires as $stagiaire) {

            //Recuperer les pointages de la semaine
            $pointagesStagiaire = PointageManager::getListByStagiaire($stagiaire->getIdStagiaire(), $idSemaine);

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
        #                  AFFICHAGE DE L'EN-TÊTE                   #
        #############################################################

        //Récuperation du premier et dernier jour de la semaine
        $jours = JourneeManager::getListBySemaine($idSemaine);

        if (count($jours) > 0) {
            $premierJour = $jours[0]->getJour();
            $dernierJour = end($jours)->getJour();
        } else {
            $premierJour = "####-##-##";
            $dernierJour = "####-##-##";
        }

        echo '<div class="pointagesOffre">';

        echo '<h3>Pointages de l\'offre n°' . $offre . '  pour la semaine n° ' . $semaine . ' du ' . $premierJour . ' au ' . $dernierJour . '</h3>';

        //S'il y a des pointages pour cette offfre
        if ($nbPointages > 0) {

            //Bouton vers l'export au format csv
            echo '<a class="bouton" href="index.php?action=exporterCSV&mode=unique&idOffre=' . $idOffres[$offre] . '&idSemaine=' . $idSemaine . '">Exporter CSV</a>';

            //Afficher entete avec jours de la semaine
            echo '<div class="listePointages">

                    <div class="entete ligne">
                        <div class="bloc">N° Bénéficiaire</div>
                        <div class="bloc">LUNDI</div>
                        <div class="bloc">MARDI</div>
                        <div class="bloc">MERCREDI</div>
                        <div class="bloc">JEUDI</div>
                        <div class="bloc">VENDREDI</div>
                    </div>


                    <div class=corpsTable>';

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

                //Contient les pointages organisés par jour et demi journée
                // Ex:  ["LUNDI"=>["matin"=>$pointage1,"apres-midi"=>$pointage2],"MARDI"=>["matin"=>$pointage3,"apres-midi"=>$pointage4] ....... ]
                $pointagesJours = [];

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

                //Récupération des informations du pointage

                //informations du stagiaire
                $numBenefStagiaire = $stagiaire->getNumBenef();

                #############################################################
                #     AFFICHAGE D'UNE LIGNE DE POINTAGES PAR STAGIAIRE      #
                #############################################################

                //Affichage d'une ligne avec tous les pointages de la semaine

                echo '<div class="ligne">';
                echo '<div class="bloc">' . $numBenefStagiaire . '</div>';

                //Pour chaque jour de la semaine
                foreach ($libelleJours as $libelle) {

                    //Si un pointage correspond à ce jour
                    if (isset($pointagesJours[$libelle])) {

                        //Créer un bloc contenant les informations de pointage
                        echo '<div class="bloc" data-jour="' . $libelle . '">';

                        //Si une information de pointage existe pour le matin
                        if (isset($pointagesJours[$libelle]["matin"])) {

                            //Récupération de l'indicateur de présence grace à son id dans pointage
                            $presence = PresenceManager::findById($pointagesJours[$libelle]["matin"]->getIdPresence());

                            $refPresence = $presence->getRefPresence();
                            $libellePresence = $presence->getLibellePresence();

                            //Afficher le code de présence
                            echo '<div class="demiJournee" data-demiJournee="matin">' . $refPresence . '</div>';
                        } else {
                            echo '<div class="demiJournee" data-demiJournee="matin"></div>';
                        }

                        //Si une information de pointage existe pour l'après-midi
                        if (isset($pointagesJours[$libelle]["après-midi"])) {

                            //Récupération de l'indicateur de présence grace à son id dans pointage
                            $presence = PresenceManager::findById($pointagesJours[$libelle]["après-midi"]->getIdPresence());

                            $refPresence = $presence->getRefPresence();
                            $libellePresence = $presence->getLibellePresence();

                            //Afficher le code de présence
                            echo '<div class="demiJournee" data-demiJournee="après-midi">' . $refPresence . '</div>';
                        } else {
                            echo '<div class="demiJournee" data-demiJournee="après-midi"></div>';
                        }

                        echo '</div>';

                        //S'il n ya pas de pointage pour ce jour, afficher un bloc vide
                    } else {
                        echo '<div class="bloc" data-jour="' . $libelle . '">
                                <div  class="demiJournee" data-demiJournee="matin"></div>
                                <div class="demiJournee" data-demiJournee="après-midi"></div>
                            </div>';
                    }
                }

                echo '</div>';

            }

            echo '</div></div></div>';

            //Ajouter l'offre au formulaire afin de la passer à la page d'exportation csv
            echo '<input type="hidden" name="idOffres[]" value=' . $idOffres[$offre] . '>';

        } else {

            //Message si aucun pointage
            echo "<p>Aucun pointage pour l'offre " . $offre . " dans la semaine " . $semaine . "</p></div>";
        }
    }

    echo '</form>';

} else {

    //Message si aucune offre/semaine selectionnées
    echo "<p>Veuillez sélectionner un numéro d'offre et une semaine.</p>";
}

?>

</div>
