<?php
//on recupere l'offre ,la semaine et les informations stagiaire
$idOffre = $_SESSION['idOffre'];
$semaineEnCours = JourneeManager::getSemaineEnCours();
$lesJours = JourneeManager::getListBySemaine($semaineEnCours->getIdSemaine());
$idStagiaire = $_SESSION['idStagiaire'];
$stagiaire = StagiaireManager::findById($idStagiaire);
//on crée la page
?>

<div id="tableau">

        <div class="enTete">
            <div class="colonne">SEMAINE n°<?php echo $semaineEnCours->getNumSemaine() ?></div>
            </div>

<?php
echo '<form action="index.php?action=ActionInterfaceStagiaire" method="POST">';
echo '<input id="idSemaine" name="idSemaine" value = "'.$semaineEnCours->getIdSemaine().'" type="hidden">';
$pointage = PointageManager::getListByStagiaire($idStagiaire, $semaineEnCours->getIdSemaine());
$longueur = count($pointage);
echo '<div class="bloc colonne">';
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
            $inputIdpointage = '<input id="idPointage'.$i.'" name="idPointage'.$i.'" value = "'.$pointage[$indexPointage]->getIdPointage().'" type="hidden">';
            $presence = PresenceManager::findById($pointage[$indexPointage]->getIdPresence());
            //si le pointage est validé
            if ($pointage[$indexPointage]->getValidation() == 1)
            {
                //on crée une combo
                $affichage = '<input readonly id="combo'.$i.'" name="combo'.$i.'" type="text" value="' . $presence->getRefPresence() . '">';
                $commente = '<input readonly id="commentaire'.$i.'" name="commentaire'.$i.'" type="text" >' . $pointage[$indexPointage]->getCommentaire() . '</textarea>';
            }
            else
            {
                //on crée un input
                $affichage = optionComboBox($pointage[$indexPointage]->getIdPresence(), 1,"combo".$i,"","");
                $commente = '<textarea class="commente" id="commentaire'.$i.'" name="commentaire'.$i.'" >'.$pointage[$indexPointage]->getCommentaire().'</textarea>';
            }
        }
        else
        {
            //pas de pointage en base, on met un idpointage null et une combo sans selection
            $inputIdpointage = '<input id="idPointage'.$i.'" name="idPointage'.$i.'" value = "null" type="hidden">';
            $indexPointage--;
            $affichage = optionComboBox(null, 1,"combo".$i,"","");
            $commente = '<textarea class="commente" id="commentaire'.$i.'" name="commentaire'.$i.'"  placeholder="commentaire éventuel"></textarea>';
        }
    }
    else
    {
        //pas de pointage en base, on met un idpointage null et une combo sans selection
        $inputIdpointage = '<input id="idPointage'.$i.'" name="idPointage'.$i.'" value = "null" type="hidden">';
        $affichage = optionComboBox(null, 1,"combo".$i,"","");
        $commente = '<textarea class="commente" id="commentaire'.$i.'" name="commentaire'.$i.'"  placeholder="commentaire éventuel"></textarea>';
    }
    $compteur++;
    //en fonction de la case pointage, on prepare le titre
    switch ($i)
    {
        case 0:
            echo '    <div class="days">LUNDI ' . date('d-m',strtotime($lesJours[0]->getJour())) . '</div>';
            break;
        case 2:
            echo '    </div><div class="days">MARDI ' . date('d-m',strtotime($lesJours[2]->getJour())) . '</div>';
            break;
        case 4:
            echo '    </div><div class="days">MERCREDI ' . date('d-m',strtotime($lesJours[4]->getJour())) . '</div>';
            break;
        case 6:
            echo '    </div><div class="days">JEUDI ' . date('d-m',strtotime($lesJours[6]->getJour())) . '</div>';
            break;
        case 8:
            echo '    </div><div class="days">VENDREDI ' .date('d-m',strtotime( $lesJours[8]->getJour())) . '</div>';
            break;
    }
    if ($i % 2 == 0)
    {
        echo ' <div class="colonne"> <div class="case ">Matin</div>';
    }
    else
    {
        if ($i < 9)
        echo '  <div class="case ">Après Midi</div>';
    }
    if ($i < 9)
    { //Tous les jours 
        //on injecte les inputs créés dans la dom
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
    $indexPointage++;
}
echo '              </div>
                </div>
            </div>
            <div class="colonne centre">
                <input class="btna" type="submit" value="Enregistrer">';

echo '      </div>
        </form>
    </div>';