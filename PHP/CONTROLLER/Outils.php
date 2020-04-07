<?php
    /**
     * optionComboBox : crée une combobox pour choisir le type de présence
     *
     * @param  mixed $code           code de présence existante (ou non)
     * @param  bool  $ref            type d'utilisateur : 1/stagaire (affiche le libellé), 2/formateur (affiche le code)
     * @return void
     */

    function optionComboBox($code, $ref)
    {
        $select = "<select>";
        $liste = PresenceManager::getList();
    
        foreach ($liste as $elt) {
            if ($code == null) { // si le code est null, on ne mets pas de choix par défaut avec valeur
                $select .= '<option value="" SELECTED>Choisir situation</option>';
            }
            else if ($code == $elt->getIdPresence()) { // si le code entré en paramètre est égale à l'élément alors c'est celui qui est selectionné
                if ($ref == 1) // si c'est un stagiaire
                    $select .= '<option value="' . $elt->getRefPresence() . '" SELECTED>' . $elt->getLibellePresence() . '</option>';
                if ($ref == 2) // si c'est un formateur
                    $select .= '<option value="' . $elt->getRefPresence() . '" SELECTED>' . $elt->getRefPresence() . '</option>';    
            } else {
                if ($ref == 1) 
                    $select .= '<option value="' . $elt->getRefPresence() . '">' . $elt->getLibellePresence() . '</option>';
                if ($ref == 2)
                    $select .= '<option value="' . $elt->getRefPresence() . '">' . $elt->getRefPresence() . '</option>';    
            }
        }
        $select .= "</select>";
        return $select;
    }