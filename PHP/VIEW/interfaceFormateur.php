<?php

$idOffre = $_SESSION['idOffre'];
$semaineEnCours = PointagesParSemainesManager::getSemaineEnCoursOffre($idOffre);
$lesJours = JourneeManager::getListBySemaine($semaineEnCours->getIdSemaine());
$listeStagiaires = StagiaireManager::getStagiairesParOffres($idOffre);
$nombreOffre = count(OffreManager::getListByFormateur($_SESSION['idFormateur'])); // on compte le nombre d'offres du.de la formateur.rice connecté.e
?>
    <div id="tableau">
        <div class="colonne"><?php echo $semaineEnCours->getNumSemaine().'('.$semaineEnCours->getIdSemaine().')'; ?></div>
            
        <div class="enTete">
            <div class="colonne">Nom/prenom</div>
            <div class="colonne-jour"></div>
            <div class="colonne">Lundi <?php echo $lesJours[0]->getJour()?></div>
            <div class="colonne">Mardi <?php echo $lesJours[2]->getJour()?></div>
            <div class="colonne">Mercredi <?php echo $lesJours[4]->getJour()?></div>
            <div class="colonne">Jeudi <?php echo $lesJours[6]->getJour()?></div>
            <div class="colonne">Vendredi <?php echo $lesJours[8]->getJour()?></div>
        </div>
<?php
echo  '<form action="index.php?action="ActionInterfaceFormateur" method="POST">';

foreach ($listeStagiaires as $stagiaire)
{
    $pointage = PointageManager::getListByStagiaire($stagiaire->getIdStagiaire(), $semaineEnCours->getIdSemaine());
    $longueur = count($pointage);
    echo '<div class="bloc">

            <div class="colonne">' . $stagiaire->getNom() . ' ' . $stagiaire->getPrenom() . '<br>N°' . $stagiaire->getNumBenef() . '</div>

            <div class="colonne-jour">
                <div class="case rotate">Matin</div>
                <div class="espv"></div>
                <div class="case rotate">Après-Midi</div>
            </div>
            <div class="colonne">';
    $compteur = 0;
    $indexPointage=0;
    for ($i = 0; $i < 10; $i++)
    {
        
        if ($indexPointage < $longueur)
        {
            if ($pointage[$indexPointage]->getIdJournee() == $lesJours[$i]->getIdJournee())
            {
                    $presence = PresenceManager::findById($pointage[$indexPointage]->getIdPresence());
                    if ($pointage[$indexPointage]->getValidation() == 1)
                    {
                        $affichage = '<input disabled="disabled "type="text" value="'.$presence->getRefPresence().'">';
                        $commente = '<input disabled="disabled" type="text" value="'.$pointage[$indexPointage]->getCommentaire().'">';
                    }
                    else
                    {
                        $affichage = optionComboBox($pointage[$indexPointage]->getIdPresence(), 2,$i);
                        $commente = $pointage[$indexPointage]->getCommentaire();
                    }
            }
                
            else
            {
                $indexPointage--;
                $affichage = optionComboBox(null, 2,$i);
                $commente = "";
            }
        }
        else
        {
            $affichage = optionComboBox(null, 2,$i);
            $commente = "";
        }
        $compteur++;
        if ($i < 9)
        {
            echo '<!--Lundi-->
                <div class="case">
                    <div>' . $affichage . '</div>
                </div>
                <div class="case">
                    <div class="commente">' . $commente . '</div>
                </div>';
        }
        else
        {
            echo '<div class="case">
                    <div></div>
                </div>
                <div class="case">
                    <div ></div>
                </div>';

        }

        if ($compteur == 2 && $i<9)
        {
            $compteur = 0;
            echo '</div><div class="colonne">';
        }  

        $indexPointage++;
    }

    echo '</div></div>';
    //         <div class="colonne">
    //         <div class="case">
    //         <div >'.optionComboBox($pointage[8]->getIdPresence(),2).'</div>
    //     </div>
    //     <div class="case">
    //         <div >'.$pointage[8]->getCommentaire().'</div>
    //     </div>';

}
//var_dump($pointage);
for ($i = 0; $i < 10; $i += 2)
{
    echo '<div class="blocCheck">';

    if ($longueur > $i && $pointage[$i]->getValidation() == 1)
    {
        echo '<input type="checkbox" checked>';
    }
    else
    {
        echo '<input type="checkbox">';
    }
    echo '</div>';
}

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