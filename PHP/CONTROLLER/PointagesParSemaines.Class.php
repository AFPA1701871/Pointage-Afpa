<?php
class PointagesParSemaines
{
    /*******************************Attributs*******************************/
    private $_idPointage;
    private $_idStagiaire;
    private $_idPresence;
    private $_demiJournee;
    private $_jour;
    private $_idJournee;
    private $_numSemaine;
    private $_idSemaine;
    private $_mois;
    private $_commentaire;
    private $_validation;

    /******************************Accesseurs*******************************/
    public function getIdPointage()
    {
        return $this->_idPointage;
    }
    public function setIdPointage($_idPointage)
    {
        return $this->_idPointage = $_idPointage;
    }

    public function getDemiJournee()
    {
        return $this->_demiJournee;
    }
    public function setDemiJournee($_demiJournee)
    {
        return $this->_demiJournee = $_demiJournee;
    }

    public function getJour()
    {
        return $this->_jour;
    }
    public function setJour($_jour)
    {
        return $this->_jour = $_jour;
    }

    public function getIdJournee()
    {
        return $this->_idJournee;
    }
    public function setIdJournee($_idJournee)
    {
        return $this->_idJournee = $_idJournee;
    }

    public function getNumSemaine()
    {
        return $this->_numSemaine;
    }
    public function setNumSemaine($_numSemaine)
    {
        return $this->_numSemaine = $_numSemaine;
    }

    public function getIdSemaine()
    {
        return $this->_idSemaine;
    }
    public function setIdSemaine($_idSemaine)
    {
        return $this->_idSemaine = $_idSemaine;
    }

    public function getMois()
    {
        return $this->_mois;
    }
    public function setMois($_mois)
    {
        return $this->_mois = $_mois;
    }

    public function getCommentaire()
    {
        return $this->_commentaire;
    }
    public function setCommentaire($_commentaire)
    {
        return $this->_commentaire = $_commentaire;
    }

    public function getValidation()
    {
        return $this->_validation;
    }
    public function setValidation($_validation)
    {
        return $this->_validation = $_validation;
    }
    
    public function getIdStagiaire()
    {
        return $this->_idStagiaire;
    }

    public function setIdStagiaire($_idStagiaire)
    {
        $this->_idStagiaire = $_idStagiaire;
    }

    public function getIdPresence()
    {
        return $this->_idPresence;
    }

    public function setIdPresence($_idPresence)
    {
        $this->_idPresence = $_idPresence;
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
        return $this->getIdPointage() . "\t" . $this->getDemiJournee() . "\t" . $this->getJour() . "\t" . $this->getIdJournee() . "\t" . $this->getNumSemaine() . "\t" . $this->getIdSemaine() . "\t" . $this->getMois() . "\t" . $this->getCommentaire() . "\t" . $this->getValidation();
    }

}
