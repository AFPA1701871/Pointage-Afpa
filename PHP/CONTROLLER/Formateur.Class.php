<?php
class Formateur
{
    /*******************************Attributs*******************************/
    private $_idFormateur;
    private $_matricule;
    private $_nom;
    private $_prenom;
    private $_motDePasse;
    private $_role;

    /******************************Accesseurs*******************************/
    public function getIdFormateur()
    {
        return $this->_idFormateur;
    }
    public function setIdFormateur($_idFormateur)
    {
        return $this->_idFormateur = $_idFormateur;
    }

    public function getMatricule()
    {
        return $this->_matricule;
    }
    public function setMatricule($_matricule)
    {
        return $this->_matricule = $_matricule;
    }

    public function getNom()
    {
        return $this->_nom;
    }
    public function setNom($_nom)
    {
        return $this->_nom = $_nom;
    }

    public function getPrenom()
    {
        return $this->_prenom;
    }
    public function setPrenom($_prenom)
    {
        return $this->_prenom = $_prenom;
    }

    public function getMotDePasse()
    {
        return $this->_motDePasse;
    }
    public function setMotDePasse($_motDePasse)
    {
        return $this->_motDePasse = $_motDePasse;
    }

    public function getRole()
    {
        return $this->_role;
    }
    public function setRole($_role)
    {
        return $this->_role = $_role;
    }

    /*******************************Construct*******************************/
    public function __construct(array $options = [])
    {
        if (!empty($options)) {
            $this->hydrate($options);
        }
    }

    public function hydrate($data)
    {
        foreach ($data as $key => $value) {
            $methode = "set" . ucfirst($key);
            if (is_callable(([$this, $methode]))) {
                $this->$methode($value);
            }
        }
    }

    /****************************Autres méthodes****************************/
    public function toString()
    {
        return $this->getIdFormateur() . "\t" . $this->getMatricule() . "\t" . $this->getNom() . "\t" . $this->getPrenom() . "\t" . $this->getMotDePasse() . "\t" . $this->getRole();
    }
}
