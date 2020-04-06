<?php
class PresenceManager
{
    public static function add(Presence $obj)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("INSERT INTO presence (refPresence, libellePresence) VALUES (:refPresence, :libellePresence)");
        $q->bindValue(":refPresence", $obj->getRefPresence());
        $q->bindValue(":libellePresence", $obj-> getLibellePresence());
        $q->execute();
    }

    public static function update(Presence $obj)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("UPDATE presence SET refPresence=:refPresence, libellePresence=:libellePresence  WHERE IdPresence = :IdPresence");
        $q->bindValue(":IdPresence", $obj->getIdPresence());
        $q->bindValue(":refPresence", $obj->getRefPresence());
        $q->bindValue(":libellePresence", $obj-> getLibellePresence());
        $q->execute();
    }

    public static function delete($perso)
    {
        $db = DbConnect::getDb();
        $db->exec("DELETE FROM presence WHERE IdPresence =" . $perso->getIdPresence());
    }

    public static function findById($id)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("SELECT * FROM presence WHERE IdPresence=" . $id);
        $q->execute();
        $results = $q->fetch(PDO::FETCH_ASSOC);
        if ($results != false) {
            return new Presence($results);
        } else {
            return false;
        }
    }

    public static function getList()
    {
        $db = DbConnect::getDb();
        $tableau = [];
        $q = $db->prepare("SELECT * FROM presence");
        $q->execute();
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC)) {
            if ($donnees != false) {
                $tableau[] = new Presence($donnees);
            }
        }
        return $tableau;
    }

}
