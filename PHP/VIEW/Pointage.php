<?php

$idOffre = $_SESSION['idOffre'];
$semaineEnCours = PointagesParSemainesManager::getSemaineEnCoursOffre($idOffre);
$lesJours = JourneeManager::getListBySemaine($semaineEnCours->getIdSemaine());
$listeStagiaires = StagiairesParOffresManager::getStagiairesParOffres($idOffre);

function optionComboBox($code)
{
    $select = "<select>";
    $liste = PresenceManager::getList();

    foreach ($liste as $elt) {
        if ($code == $elt->getRefPresence()) { // si le code entré en paramètre est égale à l'élément alors c'est celui qui est selectionné
            $select .= '<option value="' . $elt->getRefPresence() . '" SELECTED>' . $elt->getLibellePresence() . '</option>';
        } else {
            $select .= '<option value="' . $elt->getRefPresence() . '">' . $elt->getLibellePresence() . '</option>';
        }
    }
    $select .= "</select>";
    return $select;
}

?>

    <div class="liste-pointage">

        <div class="en-tete">
            <div class="jour">SEMAINE n°<?php $semaineEnCours->getNumSemaine() ?></div>
            <div class="jour">JOUR:</div>
            <div class="jour">LUNDI <?php $lesJours[0]->getJour() ?></div>
            <div class="jour">MARDI <?php $lesJours[2]->getJour() ?></div>
            <div class="jour">MERCREDI <?php $lesJours[4]->getJour() ?></div>
            <div class="jour">JEUDI <?php $lesJours[6]->getJour() ?></div>
            <div class="jour">VENDREDI <?php $lesJours[8]->getJour() ?></div>
        </div>
            
<?php   
        foreach($listeStagiaires as $stagiaire)
        {
            $pointage = PointageManager::getListByStagiaire($stagiaire->getIdStagiaire(), $semaineEnCours->getIdSemaine());
            echo '<div class="ligne-stagiaire">';
            echo '  <div class="case-stagiaire">'.$stagiaire->getNom().' '.$stagiaire->getPrenom().'</div>';
            echo '  <div class="lignes-moment">
                        <div class="ligne-moment">
                            <div class="contenu-stagiaire">
                                <div class="case-moment">MATIN</div>
                            </div>';
            // Pour chaque matinée
            for($i=0; $i<10; $i+=2)
            {
                if($i<count($pointage))
                {
                    $affichage = optionComboBox($pointage[$i]->getIdPresence());
                    $commente  = $pointage[$i]->getCommentaire();
                }
                else
                {
                    $affichage = optionComboBox(null);
                    $commente  = "";
                }; 

                echo '      <div class="contenu-stagiaire">
                <div class="case-check-box">'. $affichage .'</div>
                                <div class="case-commentaire">'.$commente.'</div>
                            </div> ';
            }
                echo '      <div class="ligne-moment">

                            <div class="contenu-stagiaire">
                                <div class="case-moment">AP-MIDI</div>
                            </div> ';

            // Pour chaque après-midi
            for($i=1; $i<10; $i+=2)
            {
                if($i<=count($pointage))
                {
                    $affichage = optionComboBox($pointage[$i]->getIdPresence());
                    $commente  = $pointage[$i]->getCommentaire();
                }
                else
                {
                    $affichage = optionComboBox(null);
                    $commente  = "";
                }; 
                echo '
                            <div class="contenu-stagiaire">
                                <div class="case-check-box">'. $affichage .'</div>
                                <div class="case-commentaire">'. $commente .'</div>
                
                            </div>
                        </div>';
                }

            echo '  </div>
                </div>';
        
        }

    echo   '<div class="pied-de-page">
                <div class="jour"></div>
                <div class="jour"></div>';
    $longueur = count($pointage);

    for($i=0; $i<10; $i+=2)
    {
        echo '<div class="jour">';
        if($longueur>$i && $pointage[$i]->getValidation()==1)
        {
            echo '<input type="checkbox" checked>';
        }
        else
        {
            echo '<input type="checkbox">';
        }
        echo    '</div>';
    }

    echo '</div>';


