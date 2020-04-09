<div id="interfaceAT">

<?php

//Formulaire de selection d'offre et de semaine
echo

    '<form action="" method="POST">

    <input type="hidden" name="action" value="' . $_GET['action'] . '">

    <p><label for="numOffre">Numero d\'Offre: </label>';

echo '<p id="selectAllOffres" class="bouton">Tout selectionner</p>';

echo '<fieldset id="listeOffres">';
$offres = OffreManager::getList();
foreach ($offres as $offre) {

    $checked = "";

    //Mettre la checkbox en checked si l'offre correspond et recupérer son id
    if (isset($_POST['numOffre'])) {
        if (in_array($offre->getNumOffre(), $_POST['numOffre']) !== false) {
            $idOffres[$offre->getNumOffre()] = $offre->getIdOffre();
            $checked = "checked";
        }
    }

    //Affichage de l'option avec le numero d'offre
    echo '<span class="offre '.$checked.'"><input class="checkboxOffre" type="checkbox" name="numOffre[]" ' . $checked . ' value="' . $offre->getNumOffre() . '">' . $offre->getNumOffre() . '</span>';
}

echo '</fieldset>';

echo '</p>

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
    $selected = "";
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

echo '</select></p>

    <p><input type="submit" value="Afficher"></p>

</form>';

//Si une offre et une semaine sont selectionnés

if (isset($_POST["numOffre"])) {

    $offres = $_POST["numOffre"];

    if (isset($_POST["semaine"])) {
        $semaine = $_POST["semaine"];
    }

    //Bouton vers l'export au format csv
    echo '<form action="index.php?action=exporterCSV&mode=multiple&idSemaine='.$idSemaine.'" method="POST">';
    
        echo '<input type="submit" value="Tout Exporter CSV">';
    

    foreach ($offres as $offre) {

        echo '<h3>Pointages de l\'offre n°' . $offre . ' pour la semaine n° ' . $semaine . '</h3>';

        //Récupérer et afficher la liste des pointages correspondants à l'offre et à la semaine

        $pointages = [];

        //Stagiaires de l'offre

        $stagiaires = StagiaireManager::getStagiairesParOffres($idOffres[$offre]);

        //Pour chaque stagiaire de l'offre
        foreach ($stagiaires as $stagiaire) {

            //Recuperer les pointages de la semaine
            $pointagesStagiaire = PointageManager::getListByStagiaire($stagiaire->getIdStagiaire(), $idSemaine);

            $pointages = array_merge($pointages, $pointagesStagiaire);
        }

        if (count($pointages) > 0) {

            //Parcours et affichage du tableau des pointages

            echo '<div class="pointagesOffre">

            <div class="listePointages">

            <div class="entete ligne">
                <div>Jour</div>
                <div>Demi-Journée</div>
                <div>N° Bénéficiaire</div>
                <div>Code Présence</div>
            </div>';

            //Pour chaque pointage
            foreach ($pointages as $pointage) {

                //Récupération du stagiaire grace à son id dans pointage
                $stagiaire = StagiaireManager::findById($pointage->getIdStagiaire());

                $nomStagiaire = $stagiaire->getNom();
                $prenomStagiaire = $stagiaire->getPrenom();
                $numBenefStagiaire = $stagiaire->getNumBenef();

                //Récupération du jour grace à son id dans pointage
                $journee = JourneeManager::findById($pointage->getIdJournee());

                $date = $journee->getJour();
                $demiJournee = $journee->getDemiJournee();

                //Récupération de l'indicateur de présence grace à son id dans pointage
                $presence = PresenceManager::findById($pointage->getIdPresence());

                $refPresence = $presence->getRefPresence();
                $libellePresence = $presence->getLibellePresence();

                //Affichage du pointage

                echo '<div class="ligne">
                    <div>' . $date . '</div>
                    <div>' . $demiJournee . '</div>
                    <div>' . $numBenefStagiaire . '</div>
                    <div>' . $refPresence . '</div>
                </div>';

            }

            echo '</div>';

            //Bouton vers l'export au format csv
            echo '<a class="bouton" href="index.php?action=exporterCSV&mode=unique&idOffre=' . $idOffres[$offre] . '&idSemaine=' . $idSemaine . '">Exporter CSV</a>

            </div>';

            echo '<input type="hidden" name="idOffres[]" value='.$idOffres[$offre].'>';

        } else {

            //Message si aucun pointage
            echo "<p>Aucun pointage pour l'offre " . $offre . " dans la semaine " . $semaine . "</p>";
        }
    }

    echo '</form>';

} else {

    //Message si aucune offre/semaine selectionnées
    echo "<p>Veuillez sélectionner un numéro d'offre et une semaine.</p>";
}

?>

</div>
