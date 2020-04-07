<?php
if (isset($_SESSION['matricule'])) {
    $liste = OffreManager::getListByFormateur($_SESSION['idFormateur']); // on va chercher les offres du formateur
    if (count($liste) == 1) { // si il n'y a qu'une offre
        header('Location: index.php?action=InterfaceFormateur'); // redirection vers la page de poointage
        // sinon on laisse charger cette page
    }
} else {
    header('Location: index.php?action=connexion'); // redirection direct vers la page de connexion
}
?>
    <div class="content"> 
        <form class="connect-form" action="index.php?action=ActionFormateur" method="post">
            <fieldset>
                <legend>Liste des offres :</legend>
                <div class="contenu">
                    <label for="choix">Choix offres :</label>
                    <div class="ligne">
                        <select name="offre" id="user-select">
                            <?php
                            foreach ($liste as $elt) {
                                $formation = FormationManager::findById($elt->getIdFormation());
                                echo '<option value="'. $elt->getIdOffre() .'">'. $elt->getNumOffre() . ' - ' . $formation->getLibelleFormation() . ' (' . $formation->getCodeFormation() . ')</option>';
                            }
                            ?>
                        </select>  
                        </div>
                        <div class="centrer">
                            <input class="bouton centrer" type="submit" value="Valider" />
                        </div>
            </fieldset>
        </form>
    </div>