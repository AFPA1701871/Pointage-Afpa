
<div id="interfaceAT">

<?php
// le formulaire présente la partie de sélection tout le temps et la partie de résultats seulement si le 1er formulaire a été rempli.
// la 2eme partie contient des boutons qui redirige vers ExportCSV


##########################################################################################################################################
#                                                   SELECTION OFFRES ET SEMAINE                                                          #
##########################################################################################################################################

//Formulaire de selection d'offre et de semaine
echo

    '<form id="selectionForm" action="" method="POST">
        <input type="hidden" name="action" value="' . $_GET['action'] . '">
        <div class="elementForm">
            <div class = "centrer row">
                <div class="centrer row">
                    <fieldset id="listeOffres">
                        <legend>Numero d\'Offre:</legend>
                        <div class="numOffres">';

$offres = OffreManager::getList();
foreach ($offres as $offre)
{
    $offreFormation = FormationManager::findById($offre->getIdFormation())->getCodeFormation();
    $checked = "";

    //Mettre la checkbox en checked si l'offre correspond et recupérer son id (Cas du 2eme passage)
    if (isset($_POST['numOffre']))
    {
        if (in_array($offre->getNumOffre(), $_POST['numOffre']) !== false)
        {
            $idOffres[$offre->getNumOffre()] = $offre->getIdOffre();
            $checked = "checked";
        }
    }

    //Affichage de l'option avec le numero d'offre
    echo '<span class="offre ' . $checked . '"><input class="checkboxOffre" type="checkbox" name="numOffre[]" ' . $checked . ' value="' . $offre->getNumOffre() . '">' . $offre->getNumOffre() . ' : ' . $offreFormation . '</span>';
}

echo '                  </div>
                    </fieldset>
                </div>
                <div class="centrer colonne">
                    <div class="centrer">
                        <p id="selectAllOffres" class="btna">Tout selectionner</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="elementForm">
            <p><label for="semaine">Numero de semaine: </label>
            <select id="selectSemaine" name="semaine">';

//Affichage des options de selection des semaines
$semaines = SemaineManager::getList();

foreach ($semaines as $semaine)
{
    //Récuperation du premier et dernier jour de la semaine pour afficher dans le select
    $jours = JourneeManager::getListBySemaine($semaine->getIdSemaine());

    if (count($jours) > 0)
    {
        $premierJour = date('d-m', strtotime($jours[0]->getJour()));
        $dernierJour = date('d-m', strtotime(end($jours)->getJour()));
    }
    else
    {
        $premierJour = "####-##-##";
        $dernierJour = "####-##-##";
    }

    //Mettre l'option en selected si la semaine correspond et recupérer son id (Cas du 2eme passage)
    if (isset($_POST['semaine']))
    {
        if ($semaine->getNumSemaine() == $_POST['semaine'])
        {
            $idSemaine = $semaine->getIdSemaine();
            $selected = "selected";
        }
        else
        {
            $selected = "";
        }
    }

    //Affichage de l'option avec le numero de semaine, le premier jour et le dernier jour
    echo '<option ' . $selected . ' value="' . $semaine->getNumSemaine() . '"> N°' . $semaine->getNumSemaine() . ' du ' . $premierJour . ' au ' . $dernierJour . '</option>';
}

echo '      </select>
        </div>
        <div class="centrer"> 
            <input type="submit" value="Afficher">
        </div>
    </form>';

##########################################################################################################################################
#                                         RECUPERATION ET AFFICHAGE DES DONNÉES DE POINTAGE                                              #
##########################################################################################################################################
//Cas du 2eme passage
//Si une offre et une semaine sont selectionnés

if (isset($_POST["numOffre"]))
{
    $offres = $_POST["numOffre"];
    if (isset($_POST["semaine"]))
    {
        $semaine = $_POST["semaine"];
    }

    //Bouton vers l'export au format csv
    echo '<form action="index.php?action=exporterCSV&mode=multiple&idSemaine=' . $idSemaine . '" method="POST">';

    echo '  <div class="centrer">
                <input type="submit" value="Exporter toutes les données en un seul fichier">
            </div>';

    #############################################################
    #                RECUPERATION DES DONNEES                   #
    #############################################################
    $listeOffres=""; //pour avoir la liste sous forme texte (nom du fichier)
    //Pour chaque offre selectionnée
    foreach ($offres as $offre)
    {
        $listeOffres .= $offre."-"; 
        //Stagiaires de l'offre
        $stagiaires = StagiaireManager::getStagiairesParOffres($idOffres[$offre]);

        //on vérifie qu'il y a du pointage validé
        $trouvePointage = false;
        $indexStagiaire = 0;
        while ($indexStagiaire < count($stagiaires) && !$trouvePointage)
        {
            echo "trouvePointage : ".$trouvePointage . "  index : ".$indexStagiaire;
            //Recuperer les pointages de la semaine
            $pointagesStagiaire = PointageManager::getListValidesByStagiaire($stagiaires[$indexStagiaire]->getIdStagiaire(), $idSemaine);
            if (count($pointagesStagiaire) > 0)
            {
                $trouvePointage = true;
            }
            $indexStagiaire++;
        }

        #############################################################
        #                  AFFICHAGE DE L'EN-TÊTE                   #
        #############################################################
        //Récuperation du premier et dernier jour de la semaine selectionee
        $jours = JourneeManager::getListBySemaine($idSemaine);

        if (count($jours) > 0)
        {
            $premierJour = date('d-m', strtotime($jours[0]->getJour()));
            $dernierJour = date('d-m', strtotime(end($jours)->getJour()));
        }
        else
        {
            $premierJour = "####-##-##";
            $dernierJour = "####-##-##";
        }

        echo '<div class="pointagesOffre">';
        echo '<h3>Pointages de l\'offre n°' . $offre . '  pour la semaine n° ' . $semaine . ' du ' . $premierJour . ' au ' . $dernierJour . '</h3>';

        //S'il y a des pointages pour cette offfre
        if ($trouvePointage)
        {
            //Bouton vers l'export au format csv
            echo '<a class="btna" href="index.php?action=exporterCSV&mode=unique&idOffre=' . $idOffres[$offre] . '&idSemaine=' . $idSemaine . '">Exporter cette offre dans un fichier séparé</a>';
            //Afficher entete avec jours de la semaine
            echo '<div class="listePointages">
                    <div class="entete ligne">
                        <div class="bloc">N°_Bénéficiaire</div>
                        <div class="bloc">LUNDI</div>
                        <div class="bloc">MARDI</div>
                        <div class="bloc">MERCREDI</div>
                        <div class="bloc">JEUDI</div>
                        <div class="bloc">VENDREDI</div>
                    </div>
                    <div class=corpsTable>';

            #############################################################
            #    Affichage des pointages stagiaires                     #
            #############################################################

            //Pour chaque stagiaire
            foreach ($stagiaires as $stagiaire)
            {
                //Récupérer la liste de ses pointages dans la semaine
                $pointagesStagiaire = PointageManager::getListValidesByStagiaire($stagiaire->getIdStagiaire(), $idSemaine);
                //recuperaton des informations stagiaires
                $identifStagiaire = StagiaireManager::findById($pointagesStagiaire[0]->getIdStagiaire());
                $beneficiaire = $identifStagiaire->getNumBenef();
                $colonne = 0;
                echo '<div class="ligne">';
                echo '<div class="bloc">' . $beneficiaire . '</div>';
                foreach ($pointagesStagiaire as $pointage)
                {
                    if ($pointage->getIdJournee() == $jours[$colonne]->getIdJournee())
                    {
                        //Récupération de l'indicateur de présence grace à son id dans pointage
                        $presence = PresenceManager::findById($pointage->getIdPresence());
                        $refPresence = $presence->getRefPresence();
                        //Afficher le code de présence
                        echo '<div class="bloc" >' . $refPresence . '</div>';
                    }
                    $colonne++;
                }
                echo '</div>';
            }
            echo '</div></div></div>';
            //Ajouter l'offre au formulaire afin de la passer à la page d'exportation csv
            echo '<input type="hidden" name="idOffres[]" value=' . $idOffres[$offre] . '>';
            echo '<input type="hidden" name="listeOffres" value=' . $listeOffres . '>';
        }
        else
        {
            //Message si aucun pointage
            echo "<p>Aucun pointage pour l'offre " . $offre . " dans la semaine " . $semaine . "</p></div>";
        }
    }
    echo '</form>';
}
else
{
    //Message si aucune offre/semaine selectionnées
    echo "<p>Veuillez sélectionner un numéro d'offre et une semaine.</p>";
}
?>
</div>
