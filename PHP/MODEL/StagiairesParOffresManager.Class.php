<?php
class StagiairesParOffresManager
{
    public static function findById($id)
    {
        $db = DbConnect::getDb();
        $id = (int) $id;
        $q = $db->query("SELECT * FROM stagiaires_par_offres WHERE idStagiaire=$id");
        $results = $q->fetch(PDO::FETCH_ASSOC);
        if ($results != false)
        {
            return new StagiairesParOffres($results);
        }
        else
        {
            return false;
        }
    }

    public static function getList()
    {
        $db = DbConnect::getDb();
        $stagiaires = [];
        $q = $db->query("SELECT * FROM stagiaires_par_offres");
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            if ($donnees != false)
            {
                $stagiaires[] = new StagiairesParOffres($donnees);
            }
        }
        return $stagiaires;
    }
        
    
}
