<?php

$idOffre = $_SESSION['idOffre'];
$semaineEnCours = PointagesParSemainesManager::getSemaineEnCoursOffre($idOffre);
$lesJours = JourneeManager::getListBySemaine($semaineEnCours->getIdSemaine());
$listeStagiaires = StagiaireManager::getStagiairesParOffres($idOffre);
$nombreOffre = count(OffreManager::getListByFormateur($_SESSION['idFormateur'])); // on compte le nombre d'offres du.de la formateur.rice connecté.e
?>
    <div id="tableau">
        <div class="enTeteSemaine">Semaine N°<?php echo $semaineEnCours->getNumSemaine().'('.$semaineEnCours->getIdSemaine().')'; ?></div>
            
        <div class="enTete">
            <div class="colonne">Nom/Prénom</div>
            <div class="colonne-jour"></div>
            <div class="colonne">Lundi <br><?php echo date('d-m',strtotime($lesJours[0]->getJour()))?></div>
             <div class="colonne">Mardi <br><?php echo date('d-m',strtotime($lesJours[2]->getJour()))?></div>
            <div class="colonne">Mercredi<br> <?php echo date('d-m',strtotime($lesJours[4]->getJour()))?></div>
            <div class="colonne">Jeudi<br> <?php echo date('d-m',strtotime($lesJours[6]->getJour()))?></div>
            <div class="colonne">Vendredi<br> <?php echo date('d-m',strtotime($lesJours[8]->getJour()))?></div>
        </div>
<?php
echo  '<form action="index.php?action=ActionInterfaceFormateur" method="POST">';
echo '<input id="idSemaine" name="idSemaine" value = "'.$semaineEnCours->getIdSemaine().'" type="hidden">';
for ($i=0;$i<9;$i++)
{
    echo '<input type ="hidden" id="idJournee'.$i.'" name="idJournee'.$i.'" value="'.$lesJours[$i]->getIdJournee().'">';
}
$indexStagiaire=0;
foreach ($listeStagiaires as $stagiaire)
{
    $idStagiaire = $stagiaire->getIdStagiaire();
    echo '<input id="idStagiaire'.$indexStagiaire.'" name="idStagiaire'.$indexStagiaire.'" value = "'.$idStagiaire.'" type="hidden">';
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
    $indexPointage=0;
    for ($i = 0; $i < 10; $i++)
    {
        
        if ($indexPointage < $longueur)
        {
            if ($pointage[$indexPointage]->getIdJournee() == $lesJours[$i]->getIdJournee())
            {
                $inputIdpointage = '<input id="idPointage'.$i.'s'.$idStagiaire.'" name="idPointage'.$i.'s'.$idStagiaire.'" value = "'.$pointage[$indexPointage]->getIdPointage().'" type="hidden">';
                    $presence = PresenceManager::findById($pointage[$indexPointage]->getIdPresence());
                    if ($pointage[$indexPointage]->getValidation() == 1)
                    {
                        $affichage = '<input readonly id="combo'.$i.'s'.$idStagiaire.'" name="combo'.$i .'s'.$idStagiaire.'"type="text" value="'.$presence->getRefPresence().'">';
                        $commente = '<textarea class="commente" readonly id="commentaire'.$i.'s'.$idStagiaire.'" name="commentaire'.$i .'s'.$idStagiaire.'" >'.$pointage[$indexPointage]->getCommentaire().'</textarea>';
                    }
                    else
                    {
                        $affichage = optionComboBox($pointage[$indexPointage]->getIdPresence(), 2,$i.'s'.$idStagiaire);
                        $commente = '<textarea class="commente" id="commentaire'.$i.'s'.$idStagiaire.'" name="commentaire'.$i .'s'.$idStagiaire.'">'.$pointage[$indexPointage]->getCommentaire().'</textarea>';
                    }
            }
                
            else
            {
                $inputIdpointage = '<input id="idPointage'.$i.'s'.$idStagiaire.'" name="idPointage'.$i.'s'.$idStagiaire.'" value = "null" type="hidden">';
                $indexPointage--;
                $affichage = optionComboBox(null, 2,$i.'s'.$idStagiaire);
                $commente = '<textarea class="commente" id="commentaire'.$i.'s'.$idStagiaire.'" name="commentaire'.$i .'s'.$idStagiaire.'"></textarea>';
            }
        }
        else
        {
            $inputIdpointage = '<input id="idPointage'.$i.'s'.$idStagiaire.'" name="idPointage'.$i.'s'.$idStagiaire.'" value = "null" type="hidden">';
            $affichage = optionComboBox(null, 2,$i.'s'.$idStagiaire);
            $commente = '<textarea class="commente" id="commentaire'.$i.'s'.$idStagiaire.'" name="commentaire'.$i .'s'.$idStagiaire.'"></textarea>';
        }
        $compteur++;
        if ($i < 9)
        {
            echo '<!--Lundi-->
                <div class="case">'.$inputIdpointage.'
                
                    <div>' . $affichage . '</div>
                </div>
                <div class="case">
                   <div>' . $commente . '</div>
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

    $indexStagiaire++;
}
//var_dump($pointage);

echo '<div class="blocCheck">
<div class="colonne valide">Cocher pour valider la journée</div>
<div class="colonne-jour"></div>';


for ($i = 0; $i < 10; $i += 2)
{
    echo'<div class="colonneCheck">';

    if ($longueur > $i && $pointage[$i]->getValidation() == 1)
    {
        echo '<input type="checkbox" id="checkbox'.$i.'" name="checkbox'.$i.'"  checked>';
    }
    else
    {
        echo '<input type="checkbox" id="checkbox'.$i.'" name="checkbox'.$i.'">';
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