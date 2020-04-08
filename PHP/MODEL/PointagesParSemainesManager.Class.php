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
        $semaineActuelle = SemaineManager::findById(JourneeManager::getSemaineEnCours()->getIdSemaine());
        $db = DbConnect::getDb();
        //on regarde si la semaine précédente est validée
        $idSemainePrecedente = $semaineActuelle->getIdSemaine() - 1;
        $q = $db->prepare("SELECT validation FROM pointages_par_semaines AS p, stagiaire AS s WHERE p.idStagiaire = s.idStagiaire AND s.IdOffre = " . $idOffre . " AND idSemaine = " . $idSemainePrecedente . " GROUP BY idJournee");
        $q->execute();
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $result[] = $donnees['validation'];
        }

        if (count($result) == 9 && array_search("0", $result)==false)
        { //on renvoi la semaine en cours
            return $semaineActuelle;
        }
        else
        { //si un pointage n'est pas validé, on renvoi la semaine precedente
            return SemaineManager::findById($semaineActuelle->getIdSemaine() - 1);

        }
    }
}
