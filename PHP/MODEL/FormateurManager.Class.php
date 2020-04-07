<?php
class FormateurManager
{
    public static function add(Formateur $obj)
    {
        var_dump($obj);
        $db = DbConnect::getDb();
        $q = $db->prepare("INSERT INTO formateur (idFormateur,matricule,nom,prenom,motDePasse,role) VALUES (:idFormateur,:matricule,:nom,:prenom,:motDePasse,:role)");
        $q->bindValue(":idFormateur", $obj->getIdFormateur());
        $q->bindValue(":matricule", $obj->getMatricule());
        $q->bindValue(":nom", $obj->getNom());
        $q->bindValue(":prenom", $obj->getPrenom());
        $q->bindValue(":motDePasse", $obj->getMotDePasse());
        $q->bindValue(":role", $obj->getRole());
        $q->execute();
    }

    public static function update(Formateur $obj)
    {
        $db = DbConnect::getDb();
        $q = $db->prepare("UPDATE formateur SET  matricule=:matricule, nom=:nom, prenom=:prenom, motDePasse=:motDePasse, role=:role WHERE idFormateur=:idFormateur");
        $q->bindValue(":idFormateur", $obj->getIdFormateur());
        $q->bindValue(":matricule", $obj->getMatricule());
        $q->bindValue(":nom", $obj->getNom());
        $q->bindValue(":prenom", $obj->getPrenom());
        $q->bindValue(":motDePasse", $obj->getMotDePasse());
        $q->bindValue(":role", $obj->getRole());
        $q->execute();
    }

    public static function delete(Formateur $obj)
    {
        $db = DbConnect::getDb();
        $db->exec("DELETE FROM formateur WHERE idFormateur=" . $obj->getIdFormateur());
    }

    public static function findById($id)
    {
        $db = DbConnect::getDb();
        $id = (int) $id;
        $q = $db->query("SELECT * FROM formateur WHERE idFormateur=$id");
        $results = $q->fetch(PDO::FETCH_ASSOC);
        if ($results != false)
        {
            return new Formateur($results);
        }
        else
        {
            return false;
        }
    }

    public static function getList()
    {
        $db = DbConnect::getDb();
        $formateur = [];
        $q = $db->query("SELECT * FROM formateur");
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            if ($donnees != false)
            {
                $formateur[] = new Formateur($donnees);
            }
        }
        return $formateur;
    }

    static public function getByMatricule($pseudo) {
		$db = DbConnect::getDb (); // Instance de PDO.
		// Exécute une requête de type SELECT avec une clause WHERE, et retourne un objet Personne
		$q = $db->prepare ( 'SELECT idFormateur, matricule, nom, prenom, motDePasse, role  FROM formateur WHERE matricule = :matricule' );
		
		// Assignation des valeurs .
		$q->bindValue ( ':matricule', $pseudo );
		$q->execute ();
		$donnees = $q->fetch ( PDO::FETCH_ASSOC );
		$q->CloseCursor ();
		if ($donnees == false) { // Si l'utilisateur n'existe pas, on renvoi un objet vide
			return new Formateur ();
		} else {
			return new Formateur ( $donnees );
		}
	}

}
