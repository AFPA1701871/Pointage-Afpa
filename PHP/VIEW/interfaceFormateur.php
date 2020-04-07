<?php

$idOffre = $_SESSION['idOffre'];
$semaineEnCours = PointagesParSemainesManager::getSemaineEnCoursOffre($idOffre);
$lesJours = JourneeManager::getListBySemaine($semaineEnCours->getIdSemaine());
$listeStagiaires = StagiaireManager::getStagiairesParOffres($idOffre);

?>

    <div id="tableau">
        <div class="enTete">
            <div class="colonne">Nom/prenom</div>
            <div class="colonne-jour"></div>
            <div class="colonne">Lundi <?php $lesJours[0]->getJour() ?></div>
            <div class="colonne">Mardi <?php $lesJours[2]->getJour() ?></div>
            <div class="colonne">Mercredi <?php $lesJours[4]->getJour() ?></div>
            <div class="colonne">Jeudi <?php $lesJours[6]->getJour() ?></div>
            <div class="colonne">Vendredi <?php $lesJours[8]->getJour() ?></div>
        </div>

<?php 
        foreach($listeStagiaires as $stagiaire)
        {
            $pointage = PointageManager::getListByStagiaire($stagiaire->getIdStagiaire(), $semaineEnCours->getIdSemaine());
            $longueur = count($pointage);
            echo '<div class="bloc">

            <div class="colonne">'.$stagiaire->getNom().' '.$stagiaire->getPrenom().'<br>N°'.$stagiaire->getNumBenef().'</div>

            <div class="colonne-jour">
                <div class="case rotate">Matin</div>
                <div class="espv"></div>
                <div class="case rotate">Après-Midi</div>
            </div>
            <div class="colonne">';
             $compteur = 0;
            for($i=0; $i<10; $i++)
            {
               
                if($i<$longueur)
                {
                    $affichage = optionComboBox($pointage[$i]->getIdPresence(),2);
                    $commente  = $pointage[$i]->getCommentaire();
                  
                }
                else
                {
                    $affichage = optionComboBox(null,2);
                    $commente  = "";
                }
                $compteur++;
                if ($i<9)
                echo '<!--Lundi-->
                <div class="case">
                    <div id="code-presence">'.$affichage.'</div>
                </div>
                <div class="case">
                    <div id="motif">'.$commente.'</div>
                </div>';
                else{
                    echo '<div class="case">
                    <div id="code-presence"></div>
                </div>
                <div class="case">
                    <div id="motif"></div>
                </div>';

                }

                if($compteur == 2){
                    $compteur = 0;
                    echo '</div> <div class="colonne">';
                }
            }
        
              echo '</div></div>';
            //         <div class="colonne">
            //         <div class="case">
            //         <div id="code-presence">'.optionComboBox($pointage[8]->getIdPresence(),2).'</div>
            //     </div>
            //     <div class="case">
            //         <div id="motif">'.$pointage[8]->getCommentaire().'</div>
            //     </div>';
        }
?>              
        
    </div>
        <div class="boutonsTableau">
            <div class="colonne "></div>
            <div class="colonne-jour"></div>
            <div class="colonne centre"><button type="button">OK</button></div>
            <div class="colonne centre"><button type="button">OK</button></div>
            <div class="colonne centre"><button type="button">OK</button></div>
            <div class="colonne centre"><button type="button">OK</button></div>
            <div class="colonne centre"><button type="button">OK</button></div>
        </div>

    </div>

