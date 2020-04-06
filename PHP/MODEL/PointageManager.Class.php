<?php
class PointageManager
{
    public static function add(Pointage $obj)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("INSERT INTO pointage (idStagiaire, idJournee, idPresence, commentaire, validation) VALUES (:idStagiaire, :idJournee, :idPresence, :commentaire, :validation)");
        $q->bindValue(":idStagiaire", $obj->getIdStagiaire());
        $q->bindValue(":idJournee", $obj-> getIdJournee());
        $q->bindValue(":idPresence", $obj->getIdPresence());
        $q->bindValue(":commentaire", $obj->getCommentaire());
        $q->bindValue(":validation", $obj->getValidation()); 
        $q->execute();
    }

    public static function update(Pointage $obj)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("UPDATE pointage SET idStagiaire=:idStagiaire, idJournee=:idJournee , idPresence= :idPresence , commentaire = :commentaire, validation = :validation  WHERE IdPointage = :IdPointage");
        $q->bindValue(":IdPointage", $obj->getIdPointage());
        $q->bindValue(":idStagiaire", $obj->getIdStagiaire());
        $q->bindValue(":idJournee", $obj-> getIdJournee());
        $q->bindValue(":idPresence", $obj->getIdPresence());
        $q->bindValue(":commentaire", $obj->getCommentaire());
        $q->bindValue(":validation", $obj->getValidation()); 
        $q->execute();
    }

    public static function delete($perso)
    {
        $db = DbConnect::getDb();
        $db->exec("DELETE FROM pointage WHERE IdPointage =" . $perso->getIdPointage());
    }

    public static function findById($id)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("SELECT * FROM pointage WHERE IdPointage=" . $id);
        $q->execute();
        $results = $q->fetch(PDO::FETCH_ASSOC);
        if ($results != false) {
            return new Pointage($results);
        } else {
            return false;
        }
    }

    public static function getList()
    {
        $db = DbConnect::getDb();
        $caisse = [];
        $q = $db->prepare("SELECT * FROM pointage");
        $q->execute();
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC)) {
            if ($donnees != false) {
                $caisse[] = new Pointage($donnees);
            }
        }
        return $caisse;
    }
        
    /**
     * majPointage : met à jour le pointage d'un stagiaire pour une semaine donnée
     *
     * @param  mixed $idSemaine             semaine a traiter
     * @param  mixed $idStagiaire           stagiaire a traiter
     * @param  mixed $tabPointage           tableau à 2 dimensions et 9 cases, la premiere contient le code pointage, la 2de le commentaire
     * @return void
     */
    public static function majPointage($idSemaine, $idStagiaire,$tabPointage)
    {
        $db = DbConnect::getDb();
        $lesJours = JourneeManager::findById($idSemaine);
        $q = $db->prepare("INSERT INTO pointage (idStagiaire, idJournee, idPresence, commentaire, validation) VALUES (:idStagiaire, :idJournee, :idPresence, :commentaire, :validation)");
        $q->bindValue(":idStagiaire", $obj->getIdStagiaire());
        $q->bindValue(":idJournee", $obj-> getIdJournee());
        $q->bindValue(":idPresence", $obj->getIdPresence());
        $q->bindValue(":commentaire", $obj->getCommentaire());
        $q->bindValue(":validation", $obj->getValidation()); 
        $q->execute();
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC)) {
            if ($donnees != false) {
                $caisse[] = new Pointage($donnees);
            }
        }
        return $caisse;
    }

}
