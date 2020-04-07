<?php
class StagiaireManager
{
    public static function add(Stagiaire $obj)
    {
        var_dump($obj);
        $db = DbConnect::getDb();
        $q = $db->prepare("INSERT INTO stagiaire (idStagiaire,nom,prenom,motDePasse,numBenef,idOffre,role) VALUES (:idStagiaire,:nom,:prenom,:motDePasse,:numBenef,:idOffre,:role)");
        $q->bindValue(":idStagiaire", $obj->getIdStagiaire());
        $q->bindValue(":nom", $obj->getNom());
        $q->bindValue(":prenom", $obj->getPrenom());
        $q->bindValue(":motDePasse", $obj->getMotDePasse());
        $q->bindValue(":numBenef", $obj->getNumBenef());
        $q->bindValue(":idOffre", $obj->getIdOffre());
        $q->bindValue(":role", $obj->getRole());
        $q->execute();
    }

    public static function update(Stagiaire $obj)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("UPDATE stagiaire SET  nom=:nom, prenom=:prenom, motDePasse=:motDePasse, numBenef=:numBenef, idOffre=:idOffre, role=:role WHERE idStagiaire=:idStagiaire");
        $q->bindValue(":idStagiaire", $obj->getIdStagiaire());
        $q->bindValue(":nom", $obj->getNom());
        $q->bindValue(":prenom", $obj->getPrenom());
        $q->bindValue(":motDePasse", $obj->getMotDePasse());
        $q->bindValue(":numBenef", $obj->getNumBenef());
        $q->bindValue(":idOffre", $obj->getIdOffre());
        $q->bindValue(":role", $obj->getRole());
        $q->execute();
    }

    public static function delete(Stagiaire $obj)
    {
        $db = DbConnect::getDb();
        $db->exec("DELETE FROM stagiaire WHERE idStagiaire=" . $obj->getIdStagiaire());
    }

    public static function findById($id)
    {
        $db = DbConnect::getDb();
        $id = (int) $id;
        $q = $db->query("SELECT * FROM stagiaire WHERE idStagiaire=$id");
        $results = $q->fetch(PDO::FETCH_ASSOC);
        if ($results != false)
        {
            return new Stagiaire($results);
        }
        else
        {
            return false;
        }
    }

    public static function getList()
    {
        $db = DbConnect::getDb();
        $stagiaire = [];
        $q = $db->query("SELECT * FROM stagiaire");
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            if ($donnees != false)
            {
                $stagiaire[] = new Stagiaire($donnees);
            }
        }
        return $stagiaire;
    }

    static public function getByNumBenef($pseudo) {
		$db = DbConnect::getDb (); // Instance de PDO.
		// Exécute une requête de type SELECT avec une clause WHERE, et retourne un objet Personne
		$q = $db->prepare ( 'SELECT idStagiaire, nom, prenom, motDePasse, numBenef, idOffre, role FROM stagiaire WHERE numBenef = :numBenef' );
		
		// Assignation des valeurs .
		$q->bindValue ( ':numBenef', $pseudo );
		$q->execute ();
		$donnees = $q->fetch ( PDO::FETCH_ASSOC );
		$q->CloseCursor ();
		if ($donnees == false) { // Si l'utilisateur n'existe pas, on renvoi un objet vide
			return new Stagiaire ();
		} else {
			return new Stagiaire ( $donnees );
		}
	}
/**
     * getStagiairesParOffres : Renvoi la liste des stagiaires attachés à une offre
     *
     * @param  mixed $idOffre
     * @return void : liste d'objet Stagiaires
     */
    public static function getStagiairesParOffres($idOffre)
    {
        $db = DbConnect::getDb();
        $stagiaires = [];
        $q = $db->query("SELECT * FROM stagiaires where idOffre = ".$idOffre);
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            if ($donnees != false)
            {
                $stagiaires[] = new Stagiaire($donnees);
            }
        }
        return $stagiaires;
    }
}
