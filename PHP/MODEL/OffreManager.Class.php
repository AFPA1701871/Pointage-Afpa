<?php
class OffreManager
{
    public static function add(Offre $obj)
    {
        var_dump($obj);
        $db = DbConnect::getDb();
        $q = $db->prepare("INSERT INTO offre (idOffre,idFormation,numOffre) VALUES (:idOffre,:idFormation,:numOffre)");
        $q->bindValue(":idOffre", $obj->getIdOffre());
        $q->bindValue(":idFormation", $obj->getIdFormation());
        $q->bindValue(":numOffre", $obj->getNumOffre());
        $q->execute();
    }

    public static function update(Offre $obj)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("UPDATE offre SET  idFormation=:idFormation, numOffre=:numOffre WHERE idOffre=:idOffre");
        $q->bindValue(":idOffre", $obj->getIdOffre());
        $q->bindValue(":idFormation", $obj->getIdFormation());
        $q->bindValue(":numOffre", $obj->getNumOffre());
        $q->execute();
    }

    public static function delete(Offre $obj)
    {
        $db = DbConnect::getDb();
        $db->exec("DELETE FROM offre WHERE idOffre=" . $obj->getIdOffre());
    }

    public static function findById($id)
    {
        $db = DbConnect::getDb();
        $id = (int) $id;
        $q = $db->query("SELECT * FROM offre WHERE idOffre=$id");
        $results = $q->fetch(PDO::FETCH_ASSOC);
        if ($results != false)
        {
            return new Offre($results);
        }
        else
        {
            return false;
        }
    }

    public static function getList()
    {
        $db = DbConnect::getDb();
        $offre = [];
        $q = $db->query("SELECT * FROM offre");
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            if ($donnees != false)
            {
                $offre[] = new Offre($donnees);
            }
        }
        return $offre;
    }

}
