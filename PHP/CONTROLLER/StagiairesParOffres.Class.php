<?php
class StagiairesParOffres
{
    /*******************************Attributs*******************************/
    private $_idStagiaire;
    private $_nom;
    private $_prenom;
    private $_numBenef;
    private $_idOffre;
    private $_numOffre;
    private $_idFormation;
    private $_codeFormation;
    private $_libelleFormation;

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

    public function getNumOffre()
    {
        return $this->_numOffre;
    }
    public function setNumOffre($_numOffre)
    {
        return $this->_numOffre = $_numOffre;
    }

    public function getIdFormation()
    {
        return $this->_idFormation;
    }
    public function setIdFormation($_idFormation)
    {
        return $this->_idFormation = $_idFormation;
    }

    public function getCodeFormation()
    {
        return $this->_codeFormation;
    }
    public function setCodeFormation($_codeFormation)
    {
        return $this->_codeFormation = $_codeFormation;
    }

    public function getLibelleFormation()
    {
        return $this->_libelleFormation;
    }
    public function setLibelleFormation($_libelleFormation)
    {
        return $this->_libelleFormation = $_libelleFormation;
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
        return $this->getIdStagiaire() . "\t" . $this->getNom() . "\t" . $this->getPrenom() . "\t" . $this->getNumBenef() . "\t" . $this->getIdOffre() . "\t" . $this->getNumOffre() . "\t" . $this->getIdFormation() . "\t" . $this->getCodeFormation() . "\t" . $this->getLibelleFormation();
    }
}
