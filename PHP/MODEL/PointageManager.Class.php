<?php
class PointageManager
{
    public static function add(Pointage $obj)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("INSERT INTO pointage (idStagiaire, idJournee, idPresence, commentaire, validation) VALUES (:idStagiaire, :idJournee, :idPresence, :commentaire, :validation)");
        $q->bindValue(":idStagiaire", $obj->getIdStagiaire());
        $q->bindValue(":idJournee", $obj->getIdJournee());
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
        $q->bindValue(":idJournee", $obj->getIdJournee());
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
        if ($results != false)
        {
            return new Pointage($results);
        }
        else
        {
            return false;
        }
    }
    public static function findByStagiairejournee($idStagiaire, $idJournee)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("SELECT * FROM pointage WHERE IdStagiaire=" . $idStagiaire . " and idJournee = " . $idJournee);
        $q->execute();
        $results = $q->fetch(PDO::FETCH_ASSOC);
        if ($results != false)
        {
            return new Pointage($results);
        }
        else
        {
            return false;
        }
    }

    public static function getList()
    {
        $db = DbConnect::getDb();
        $tableau = [];
        $q = $db->prepare("SELECT * FROM pointage");
        $q->execute();
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            if ($donnees != false)
            {
                $tableau[] = new Pointage($donnees);
            }
        }
        return $tableau;
    }
    public static function getListByStagiaire($idStagiaire, $idSemaine)
    {
        $db = DbConnect::getDb();
        $tableau = [];
        $q = $db->prepare("SELECT * FROM pointage as p , journee as j where p.idJournee=j.idJournee and idStagiaire =" . $idStagiaire . " and j.idSemaine  = " . $idSemaine . " order by p.idJournee");
        $q->execute();
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            if ($donnees != false)
            {
                $tableau[] = new Pointage($donnees);
            }
        }
        return $tableau;
    }
    public static function getListvalidesByStagiaire($idStagiaire, $idSemaine)
    {
        $db = DbConnect::getDb();
        $tableau = [];
        $q = $db->prepare("SELECT * FROM pointage as p , journee as j where p.idJournee=j.idJournee and idStagiaire =" . $idStagiaire . " and j.idSemaine  = " . $idSemaine . " and validation =1 order by p.idJournee");
        $q->execute();
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            if ($donnees != false)
            {
                $tableau[] = new Pointage($donnees);
            }
        }
        return $tableau;
    }

    /**
     * majPointage : met à jour le pointage d'un stagiaire pour une semaine donnée
     *
     * @param  mixed $idSemaine             semaine a traiter
     * @param  mixed $idStagiaire           stagiaire a traiter
     * @param  mixed $tabPointage           tableau contenant 9 objets pointage
     * @return void
     */
    public static function majPointage($idSemaine, $idStagiaire, $tabPointage)
    {
        $db = DbConnect::getDb();
        $lesJours = JourneeManager::getListBySemaine($idSemaine);
        $index = 0;
        foreach ($lesJours as $uneJournee)
        {
            //pour eviter les doublons de transmissions
            //on regarde si le pointage existe déjà
            $present = self::findByStagiairejournee($idStagiaire, $uneJournee->getIdJournee());
            if ($present == null)
            {
                $q = $db->prepare("INSERT INTO pointage ( idStagiaire, idJournee, idPresence, commentaire, validation) VALUES ( :idStagiaire, :idJournee, :idPresence, :commentaire, :validation)");
                $q->bindValue(":idStagiaire", $idStagiaire);
                $q->bindValue(":idJournee", $uneJournee->getIdJournee());
                $q->bindValue(":idPresence", $tabPointage[$index]->getidPresence());
                $q->bindValue(":commentaire", $tabPointage[$index]->getCommentaire());
                $q->bindValue(":validation", $tabPointage[$index]->getValidation());}
            else
            { //le pointage existe deja
                //on regarde si l'idPointage était présent dans le formulaire
                if ($tabPointage[$index]->getidPointage() == null)
                { //on est sans doute dans le cas qui genere les doublons
                    $tabPointage[$index]->setidPointage($present->getIdPointage());
                }
                $q = $db->prepare("UPDATE pointage  SET   idPresence=:idPresence , commentaire= :commentaire, validation= :validation WHERE idPointage = :idPointage");
                $q->bindValue(":idPointage", $tabPointage[$index]->getidPointage());
                $q->bindValue(":idPresence", $tabPointage[$index]->getidPresence());
                $q->bindValue(":commentaire", $tabPointage[$index]->getCommentaire());
                $q->bindValue(":validation", $tabPointage[$index]->getValidation());
            }
            $q->execute();
            $index++;
        }
    }
}
