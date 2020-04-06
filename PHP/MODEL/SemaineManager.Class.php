<?php
class SemaineManager
{
    public static function add(Semaine $obj)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("INSERT INTO semaine (mois, numSemaine) VALUES (:mois, :numSemaine)");
        $q->bindValue(":mois", $obj->getMois());
        $q->bindValue(":numSemaine", $obj-> getNumSemaine());
        $q->execute();
    }

    public static function update(Semaine $obj)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("UPDATE semaine SET mois=:mois, numSemaine=:numSemaine  WHERE IdSemaine = :IdSemaine");
        $q->bindValue(":IdSemaine", $obj->getIdSemaine());
        $q->bindValue(":mois", $obj->getMois());
        $q->bindValue(":numSemaine", $obj-> getNumSemaine());
        $q->execute();
    }

    public static function delete($perso)
    {
        $db = DbConnect::getDb();
        $db->exec("DELETE FROM semaine WHERE IdSemaine =" . $perso->getIdSemaine());
    }

    public static function findById($id)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("SELECT * FROM semaine WHERE IdSemaine=" . $id);
        $q->execute();
        $results = $q->fetch(PDO::FETCH_ASSOC);
        if ($results != false) {
            return new Semaine($results);
        } else {
            return false;
        }
    }

    public static function getList()
    {
        $db = DbConnect::getDb();
        $tableau = [];
        $q = $db->prepare("SELECT * FROM semaine");
        $q->execute();
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC)) {
            if ($donnees != false) {
                $tableau[] = new Semaine($donnees);
            }
        }
        return $tableau;
    }

}
