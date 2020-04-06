<?php
class JourneeManager
{
    public static function add(Journee $obj)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("INSERT INTO journee (jour, demiJournee, idSemaine) VALUES (:jour, :demiJournee, :idSemaine)");
        $q->bindValue(":jour", $obj->getJour());
        $q->bindValue(":demiJournee", $obj-> getDemiJournee());
        $q->bindValue(":idSemaine", $obj->getIdSemaine());
        $q->execute();
    }

    public static function update(Journee $obj)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("UPDATE journee SET jour=:jour, demiJournee=:demiJournee , idSemaine= :idSemaine  WHERE IdJournee = :IdJournee");
        $q->bindValue(":IdJournee", $obj->getIdJournee());
        $q->bindValue(":jour", $obj->getJour());
        $q->bindValue(":demiJournee", $obj-> getDemiJournee());
        $q->bindValue(":idSemaine", $obj->getIdSemaine());
        $q->execute();
    }

    public static function delete($perso)
    {
        $db = DbConnect::getDb();
        $db->exec("DELETE FROM journee WHERE IdJournee =" . $perso->getIdJournee());
    }

    public static function findById($id)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("SELECT * FROM journee WHERE IdJournee=" . $id);
        $q->execute();
        $results = $q->fetch(PDO::FETCH_ASSOC);
        if ($results != false) {
            return new Journee($results);
        } else {
            return false;
        }
    }
    

    public static function getListBySemaine($idSemaine)
    {
        $db = DbConnect::getDb();
        $tableau = [];
        $q = $db->prepare("SELECT * FROM journee where idSemaine=".$idSemaine);
        $q->execute();
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC)) {
            if ($donnees != false) {
                $tableau[] = new Journee($donnees);
            }
        }
        return $tableau;
    }

}
