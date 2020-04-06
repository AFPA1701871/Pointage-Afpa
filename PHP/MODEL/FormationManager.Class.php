<?php
class FormationManager
{
    public static function add(Formation $obj)
    {
        var_dump($obj);
        $db = DbConnect::getDb();
        $q = $db->prepare("INSERT INTO formation (idFormation,codeFormation,libelleFormation,idFormateur) VALUES (:idFormation,:codeFormation,:libelleFormation,:idFormateur)");
        $q->bindValue(":idFormation", $obj->getIdFormation());
        $q->bindValue(":codeFormation", $obj->getCodeFormation());
        $q->bindValue(":libelleFormation", $obj->getLibelleFormation());
        $q->bindValue(":idFormateur", $obj->getIdFormateur());
        $q->execute();
    }

    public static function update(Formation $obj)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("UPDATE formation SET  codeFormation=:codeFormation, libelleFormation=:libelleFormation, idFormateur=:idFormateur WHERE idFormation=:idFormation");
        $q->bindValue(":idFormation", $obj->getIdFormateur());
        $q->bindValue(":codeFormation", $obj->getCodeFormation());
        $q->bindValue(":libelleFormation", $obj->getLibelleFormation());
        $q->bindValue(":idFormateur", $obj->getIdFormateur());
        $q->execute();
    }

    public static function delete(Formation $obj)
    {
        $db = DbConnect::getDb();
        $db->exec("DELETE FROM formation WHERE idFormation=" . $obj->getIdFormation());
    }

    public static function findById($id)
    {
        $db = DbConnect::getDb();
        $id = (int) $id;
        $q = $db->query("SELECT * FROM formation WHERE idFormation=$id");
        $results = $q->fetch(PDO::FETCH_ASSOC);
        if ($results != false)
        {
            return new Formation($results);
        }
        else
        {
            return false;
        }
    }

    public static function getList()
    {
        $db = DbConnect::getDb();
        $formation = [];
        $q = $db->query("SELECT * FROM formation");
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            if ($donnees != false)
            {
                $formation[] = new Formation($donnees);
            }
        }
        return $formation;
    }

}
