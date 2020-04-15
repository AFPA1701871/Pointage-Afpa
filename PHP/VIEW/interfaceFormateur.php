<?php
// On recupere l'offre choisie, on calcule la semaine a afficher, on affiche les pointages pour les stagiaires concernés par l'offre
$idOffre = $_SESSION['idOffre'];
//on recupere la semaine en cours (semaine courante sauf si le pointage de la semaine précédente n'a pas été validé)
$semaineEnCours = PointagesParSemainesManager::getSemaineEnCoursOffre($idOffre);
$lesJours = JourneeManager::getListBySemaine($semaineEnCours->getIdSemaine());
$listeStagiaires = StagiaireManager::getStagiairesParOffres($idOffre);
// on compte le nombre d'offres du.de la formateur.rice connecté.e, pour affichage du bouton retour à ChoixFormateur ou pas
$nombreOffre = count(OffreManager::getListByFormateur($_SESSION['idFormateur'])); 
//on prepare des tags pour ajouter sur les inputs et permettre une sélection plus facile en JS
$journee = ["lundi", "lundi", "mardi", "mardi", "mercredi", "mercredi", "jeudi", "jeudi", "vendredi", ""];
?>
    <div id="tableau">
        <div class="enTeteSemaine">Semaine N°<?php echo $semaineEnCours->getNumSemaine() ; ?></div>

        <div class="enTete">
            <div class="colonne">Nom/Prénom</div>
            <div class="colonne-jour"></div>
            <div class="colonne">Lundi <br><?php echo date('d-m', strtotime($lesJours[0]->getJour())) ?></div>
             <div class="colonne">Mardi <br><?php echo date('d-m', strtotime($lesJours[2]->getJour())) ?></div>
            <div class="colonne">Mercredi<br> <?php echo date('d-m', strtotime($lesJours[4]->getJour())) ?></div>
            <div class="colonne">Jeudi<br> <?php echo date('d-m', strtotime($lesJours[6]->getJour())) ?></div>
            <div class="colonne">Vendredi<br> <?php echo date('d-m', strtotime($lesJours[8]->getJour())) ?></div>
        </div>
<?php
echo '<form id="Formateur" name="Formateur" action="index.php?action=ActionInterfaceFormateur" method="POST">';
echo '<input id="idSemaine" name="idSemaine" value = "' . $semaineEnCours->getIdSemaine() . '" type="hidden">';
//on crée des inputs cachés pour récupérer les journées
for ($i = 0; $i < 9; $i++)
{
    echo '<input type ="hidden" id="idJournee' . $i . '"  name="idJournee' . $i . '" value="' . $lesJours[$i]->getIdJournee() . '">';
}
$indexStagiaire = 0;
//pour chaque stagiaire
foreach ($listeStagiaires as $stagiaire)
{
    //on recupere les infos du stagiaire et son pointage
    $idStagiaire = $stagiaire->getIdStagiaire();
    echo '<input id="idStagiaire' . $indexStagiaire . '" name="idStagiaire' . $indexStagiaire . '" value = "' . $idStagiaire . '" type="hidden">';
    $pointage = PointageManager::getListByStagiaire($stagiaire->getIdStagiaire(), $semaineEnCours->getIdSemaine());
    $longueur = count($pointage);
    echo '<div class="bloc bloc2">
            <div class="colonne stagiaire">' . $stagiaire->getNom() . ' ' . $stagiaire->getPrenom() . '<br>N°' . $stagiaire->getNumBenef() . '</div>
            <div class="colonne-jour">
                <div class="case rotate">Matin</div>
                <div class="espv"></div>
                <div class="case rotate">Après-Midi</div>
            </div>
            <div class="colonne">';
    $compteur = 0;
    $indexPointage = 0;
    //pour chaque case de pointage
    for ($i = 0; $i < 10; $i++)
    {
        //s'il reste du pointage (en base de données)
        if ($indexPointage < $longueur)
        {
            //si le pointage correspond à la demi-journée traitée
            if ($pointage[$indexPointage]->getIdJournee() == $lesJours[$i]->getIdJournee())
            {
                //on cache l'id pointage, qui permettra de faire la difference entre add et update
                $inputIdpointage = '<input id="idPointage' . $i . 's' . $idStagiaire . '" name="idPointage' . $i . 's' . $idStagiaire . '" value = "' . $pointage[$indexPointage]->getIdPointage() . '" type="hidden">';
                $presence = PresenceManager::findById($pointage[$indexPointage]->getIdPresence());
                //si le pointage est validé
                if ($pointage[$indexPointage]->getValidation() == 1)
                {
                    //Rq : le name et l'id des objets ci dessous permettent de retrouvé le stagiaire, la journée, ...
                    //on cache une combo (utile en JS en cas de dévalidation)
                    $affichage = optionComboBox($pointage[$indexPointage]->getIdPresence(), 2, "combo" . $i . 's' . $idStagiaire, $journee[$i], 'class="invisible"');
                    // on crée un input visible avec la ref pointage (lisible) et un autre caché avec l'id
                    $affichage .= '<input readonly id="inputcombo' . $i . 's' . $idStagiaire . '" ' . $journee[$i] . ' name="inputcombo' . $i . 's' . $idStagiaire . '"type="text" value="' . $presence->getRefPresence() . '">';
                    $affichage .= '<input type="hidden" id="incombo' . $i . 's' . $idStagiaire . '" ' . $journee[$i] . ' name="incombo' . $i . 's' . $idStagiaire . '"type="text" value="' . $presence->getIdPresence() . '">';
                    $commente = '<textarea class="commente" readonly ' . $journee[$i] . ' id="commentaire' . $i . 's' . $idStagiaire . '" name="commentaire' . $i . 's' . $idStagiaire . '" >' . $pointage[$indexPointage]->getCommentaire() . '</textarea>';
                }
                else
                {   //le pointage n'est pas validé, on crée la combo avec le pointage correspondant selectionné
                    $affichage = optionComboBox($pointage[$indexPointage]->getIdPresence(), 2, "combo" . $i . 's' . $idStagiaire, $journee[$i], "");
                    $commente = '<textarea class="commente"  ' . $journee[$i] . ' id="commentaire' . $i . 's' . $idStagiaire . '" name="commentaire' . $i . 's' . $idStagiaire . '">' . $pointage[$indexPointage]->getCommentaire() . '</textarea>';
                }
            }
            else
            {
                //pas de pointage en base pour la demi-journee, on crée une combo sans valeur preselectionné
                $inputIdpointage = '<input  ' . $journee[$i] . ' id="idPointage' . $i . 's' . $idStagiaire . '" name="idPointage' . $i . 's' . $idStagiaire . '" value = "null" type="hidden">';
                $indexPointage--; //on revient en arriere sur la liste des pointage en base de données, puisqu'il n'a pas été utilisé (ne correspond pas à la demi-journée traitée)
                $affichage = optionComboBox(null, 2, "combo" . $i . 's' . $idStagiaire, $journee[$i], "");
                $commente = '<textarea  ' . $journee[$i] . ' class="commente" id="commentaire' . $i . 's' . $idStagiaire . '" name="commentaire' . $i . 's' . $idStagiaire . '"></textarea>';
            }
        }
        else
        {
            //pas de pointage en base, on crée une combo sans valeur preselectionné    
            $inputIdpointage = '<input  ' . $journee[$i] . ' id="idPointage' . $i . 's' . $idStagiaire . '" name="idPointage' . $i . 's' . $idStagiaire . '" value = "null" type="hidden">';
            $affichage = optionComboBox(null, 2, "combo" . $i . 's' . $idStagiaire, $journee[$i], "");
            $commente = '<textarea  ' . $journee[$i] . ' class="commente" id="commentaire' . $i . 's' . $idStagiaire . '" name="commentaire' . $i . 's' . $idStagiaire . '"></textarea>';
        }
        $compteur++;
        if ($i < 9)
        {
            //on met les différents inputs créés dans la dom
            echo '  <div class="case">' . $inputIdpointage . '
                        <div>' . $affichage . '</div>
                    </div>
                    <div class="case">
                        <div>' . $commente . '</div>
                    </div>';
        }
        else
        {   //on crée l'espace pour le vendredi après midi
            echo '  <div class="case">
                        <div></div>
                    </div>
                    <div class="case">
                        <div ></div>
                    </div>';

        }
        //les pointages matin, apres midi sont entourés par une div colonne
        //si compteur =2, on vient de terminer l'apres midi, on ferme la colonne et on ouvre une autre
        //si vendredi, on fermera apres la boucle (pour ne pas en reouvrir)
        if ($compteur == 2 && $i < 9)
        {
            $compteur = 0;
            echo '</div><div class="colonne">';
        }
        $indexPointage++;
    }
    echo '</div></div>';
    $indexStagiaire++;
}
// Bloc avec les checks box pour valider
echo '<div class="blocCheck">
<div class="colonne valide">Cocher pour valider la journée</div>';

$indexPointage = 0;
//on regarde les pointage du dernier stagiaire (si le dernier stagiaire est validé, tous le sont)
//1 pointage sur 2 seulement. Si le matin est validé, l'apres midi aussi
for ($i = 0; $i < 10; $i += 2)
{
    echo '<div class="colonneCheck">';
    //s'il y a du pointage et qu'il est validé
    if ($longueur > $indexPointage && $pointage[$indexPointage]->getValidation() == 1)
    {
        //si le pointage correspond à la journée traitée
        if ($pointage[$indexPointage]->getIdJournee() == $lesJours[$i]->getIdJournee())
        {
            //on coche
            echo '<input type="checkbox" id="checkbox' . $i . '" name="checkbox' . $i . '"  checked>';
            $indexPointage+=2;
        }
        else
        {
            //on coche pas
            echo '<input type="checkbox" id="checkbox' . $i . '" name="checkbox' . $i . '">';
        }
    }
    else
    {
        // pas de pointage, on coche pas
        echo '<input type="checkbox" id="checkbox' . $i . '" name="checkbox' . $i . '">';

    }
    echo '<span class="messageCheck"></span></div>';
}

echo '</div>';
echo '</div>
    <div class="colonne centre">
        <input class="btna" type="submit" value="Enregistrer">';

if ($nombreOffre > 1) // si il y a plus d'une offre
{
    echo '<a class="btna" href="index.php?action=ChoixFormateur">Retour</a>'; // on affiche un bouton 'retour' qui amène aux choix de formations
}

echo '</div>
</form>
    </div>';
