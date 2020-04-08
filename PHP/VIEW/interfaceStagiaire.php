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
            <div class="case-stagiaire"><?php echo $stagiaire->getNom().' '.$stagiaire->getPrenom().'<br> '.$stagiaire->getNumBenef()?></div>
            <div class="jour">JOUR:</div>

        </div>

<?php
echo '<form action="index.php?action="ActionInterfaceStagiaire" method="POST">';

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
            // Ajouter un test sur la validation
            // si validé -> input
            // sinon combo
            // POUR NABIL

            //if(:

            $affichage = optionComboBox($pointage[$indexPointage]->getIdPresence(), 1);
            $commente = $pointage[$indexPointage]->getCommentaire();

        }
        else
        {
            $indexPointage--;
            $affichage = optionComboBox(null, 1);
            $commente = "";
        }
    }
    else
    {
        $affichage = optionComboBox(null, 1);
        $commente = "";
    }
    $compteur++;
    

    switch ($i)
    {
        case 0:
            echo '    <div>LUNDI '.$lesJours[0]->getJour() .'</div>';
            break;
        case 2:
            echo '    </div><div>MARDI '. $lesJours[2]->getJour() .'</div>';
            break;
        case 4:
            echo '    </div><div>MERCREDI '.$lesJours[4]->getJour() .'</div>';
            break;
        case 6:
            echo '    </div><div>JEUDI '.$lesJours[6]->getJour() .'</div>';
            break;
        case 8:
            echo '    </div><div>VENDREDI '. $lesJours[8]->getJour() .'</div>';
            break;
    }
    if ($i % 2 == 0)
    {
        echo ' <div class="jour"> <div class="case ">Matin</div>';
    }
    else
    {
        echo '  <div class="case ">Ap Midi</div>';
    }
    if ($i < 9)
    {

        echo '<div class="colonne">
            <div class="case">
                <div>' . $affichage . '</div>
            </div>
            <div class="case">
                <div>' . $commente . '</div>
                </div></div>';
    }
    else
    {
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
