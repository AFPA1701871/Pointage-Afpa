<?php

$idOffre = $_SESSION['idOffre'];
$semaineEnCours = JourneeManager::getSemaineEnCours();

$lesJours = JourneeManager::getListBySemaine($semaineEnCours->getIdSemaine());
$idStagiaire = $_SESSION['idStagiaire'];
$stagiaire = StagiaireManager::findById($idStagiaire);
?>

<div id="tableau">

        <div class="en-tete">
            <div class="colonne">SEMAINE n°<?php echo $semaineEnCours->getNumSemaine() ?></div>
            </div>

<?php
echo '<form action="index.php?action=ActionInterfaceStagiaire" method="POST">';
echo '<input id="idSemaine" name="idSemaine" value = "'.$semaineEnCours->getIdSemaine().'" type="hidden">';
$pointage = PointageManager::getListByStagiaire($idStagiaire, $semaineEnCours->getIdSemaine());
$longueur = count($pointage);
echo '<div class="bloc colonne">';
//<div class="row">';

$compteur = 0;
$indexPointage = 0;
for ($i = 0; $i < 10; $i++)
{
    if ($indexPointage < $longueur)
    {
        if ($pointage[$indexPointage]->getIdJournee() == $lesJours[$i]->getIdJournee())
        {
            $inputIdpointage = '<input id="idPointage'.$i.'" name="idPointage'.$i.'" value = "'.$pointage[$indexPointage]->getIdPointage().'" type="hidden">';
            $presence = PresenceManager::findById($pointage[$indexPointage]->getIdPresence());
            if ($pointage[$indexPointage]->getValidation() == 1)
            {
                $affichage = '<input disabled="disabled name="combo'.$i.'" type="text" value="' . $presence->getRefPresence() . '">';
                $commente = '<input disabled="disabled" name="commentaire'.$i.'" type="text" value="' . $pointage[$indexPointage]->getCommentaire() . '">';
            }
            else
            {
                $affichage = optionComboBox($pointage[$indexPointage]->getIdPresence(), 1,$i);
                $commente = '<input type="text" name="commentaire'.$i.'" value="'.$pointage[$indexPointage]->getCommentaire().'">';
            }
        }
        else
        {
            $inputIdpointage = '<input id="idPointage'.$i.'" name="idPointage'.$i.'" value = "null" type="hidden">';
            $indexPointage--;
            $affichage = optionComboBox(null, 1,$i);
            $commente = '<input type="text" name="commentaire'.$i.'" value="" placeholder="commentaire éventuel">';
        }
    }
    else
    {
        $inputIdpointage = '<input id="idPointage'.$i.'" name="idPointage'.$i.'" value = "null" type="hidden">';
        $affichage = optionComboBox(null, 1,$i);
        $commente = '<input type="text" name="commentaire'.$i.'" value="" placeholder="commentaire éventuel">';
    }
    $compteur++;

    switch ($i)
    {
        case 0:
            echo '    <div class="days">LUNDI ' . $lesJours[0]->getJour() . '</div>';
            break;
        case 2:
            echo '    </div><div class="days">MARDI ' . $lesJours[2]->getJour() . '</div>';
            break;
        case 4:
            echo '    </div><div class="days">MERCREDI ' . $lesJours[4]->getJour() . '</div>';
            break;
        case 6:
            echo '    </div><div class="days">JEUDI ' . $lesJours[6]->getJour() . '</div>';
            break;
        case 8:
            echo '    </div><div class="days">VENDREDI ' . $lesJours[8]->getJour() . '</div>';
            break;
    }
    if ($i % 2 == 0)
    {
        echo ' <div class="colonne"> <div class="case ">Matin</div>';
    }
    else
    {
        echo '  <div class="case ">Ap Midi</div>';
    }
    if ($i < 9)
    { //Tous les jours 

        echo '<div class="colonne">'.$inputIdpointage.'
            <input type ="hidden" id="idJournee'.$i.'" name="idJournee'.$i.'" value="'.$lesJours[$i]->getIdJournee().'">
            <div class="case">
                <div>' . $affichage . '</div>
            </div>
            <div class="case">
                <div>' . $commente . '</div>
                </div></div>';
    }
    else
    { //vendredi apres midi
        echo '<div class="colonne"><div class="case">
                <div></div>
            </div>
            <div class="case">
                <div ></div>
                </div></div>';

    }
    // if ($compteur == 2 && $i < 9)
    // {
    //     $compteur = 0;
    //     echo '</div><div class="row">';
    // }

    $indexPointage++;

}
echo '</div></div>';
echo '</div>
    <div class="colonne centre">
        <input class="btna" type="submit" value="Enregistrer">';

echo '</div>
        </form>
            </div>';
