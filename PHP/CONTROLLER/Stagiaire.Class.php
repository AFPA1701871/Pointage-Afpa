<?php
class Stagiaire
{
    /*******************************Attributs*******************************/
    private $_idStagiaire;
    private $_nom;
    private $_prenom;
    private $_motDePasse;
    private $_numBenef;
    private $_idOffre;
    private $_role;

    /******************************Accesseurs*******************************/
    public function getIdStagiaire()
    {
        return $this->_idStagiaire;
    }
    public function setIdStagiaire($_idStagiaire)
    {
        return $this->_idStagiaire = $_idStagiaire;
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

    public function getNumBenef()
    {
        return $this->_numBenef;
    }
    public function setNumBenef($_numBenef)
    {
        return $this->_numBenef = $_numBenef;
    }

    public function getIdOffre()
    {
        return $this->_idOffre;
    }
    public function setIdOffre($_idOffre)
    {
        return $this->_idOffre = $_idOffre;
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
        return $this->getIdStagiaire() . "\t" . $this->getNom() . "\t" . $this->getPrenom() . "\t" . $this->getMotDePasse() . "\t" . $this->getNumBenef() . "\t" . $this->getIdOffre() . "\t" . $this->getRole();
    }
}
