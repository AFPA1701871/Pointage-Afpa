<?php
class Formation
{
    /*******************************Attributs*******************************/
    private $_idFormation;
    private $_codeFormation;
    private $_libelleFormation;
    private $_idFormateur;

    /******************************Accesseurs*******************************/
   
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

    public function getIdFormateur()
    {
        return $this->_idFormateur;
    }
    public function setIdFormateur($_idFormateur)
    {
        return $this->_idFormateur = $_idFormateur;
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
        return $this->getIdFormation() . "\t" . $this->getCodeFormation() . "\t" . $this->getLibelleFormation() . "\t" . $this->getIdFormateur();
    }
}
