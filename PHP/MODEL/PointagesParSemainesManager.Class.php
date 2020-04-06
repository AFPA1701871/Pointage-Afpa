<?php
class PointagesParSemainesManager
{
    public static function findById($id)
    {
        $db = DbConnect::getDb();
        $id = (int) $id;
        $q = $db->query("SELECT * FROM pointages_par_semaines WHERE idPointage=$id");
        $results = $q->fetch(PDO::FETCH_ASSOC);
        if ($results != false)
        {
            return new PointagesParSemaines($results);
        }
        else
        {
            return false;
        }
    }

    public static function getList()
    {
        $db = DbConnect::getDb();
        $pointages = [];
        $q = $db->query("SELECT * FROM pointages_par_semaines");
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            if ($donnees != false)
            {
                $pointages[] = new PointagesParSemaines($donnees);
            }
        }
        return $pointages;
    }
        
    /**
     * getSemaineEnCoursOffre : renvoi la semaine en cours en fonction du numero d'offre et de l'etat de validation des pointages
     *
     * @param  mixed $idOffre
     * @return void un objet semaine
     */
    public static function getSemaineEnCoursOffre($idOffre)
    {
        $db = DbConnect::getDb();
        //on cherche la dernière semaine de pointage
        $q = $db->prepare("SELECT max(idSemaine) as max  FROM pointages_par_semaines as p ,stagiaire as s  WHERE p.idStagiaire =s.idStagiaire and   s.IdOffre=" . $idOffre);
        $q->execute();
        $max = $q->fetch(PDO::FETCH_ASSOC)['max'];
        //on regarde si cette semaine est validée
        $q = $db->prepare("SELECT distinct(idSemaine) as idSemaine FROM pointages_par_semaines as p ,stagiaire as s  WHERE p.idStagiaire =s.idStagiaire and s.IdOffre=" . $idOffre . " and idSemaine = " . $max . " and validation is null");
        $q->execute();
        $result = $q->fetch(PDO::FETCH_ASSOC)['idSemaine'];
        if ($result == null)
        {
            return SemaineManager::findById(JourneeManager::semaineEnCours());
        }
        else
        {
            return SemaineManager::findById($result);
        }

    }
}
