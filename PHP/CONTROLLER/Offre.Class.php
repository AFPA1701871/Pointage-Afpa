<?php
class Offre
{
    /*******************************Attributs*******************************/
    private $_idOffre;
    private $_idFormation;
    private $_numOffre;

    /******************************Accesseurs*******************************/
    public function getIdOffre()
    {
        return $this->_idOffre;
    }
    public function setIdOffre($_idOffre)
    {
        return $this->_idOffre = $_idOffre;
    }

    public function getIdFormation()
    {
        return $this->_idFormation;
    }
    public function setIdFormation($_idFormation)
    {
        return $this->_idFormation = $_idFormation;
    }

    public function getNumOffre()
    {
        return $this->_numOffre;
    }
    public function setNumOffre($_numOffre)
    {
        return $this->_numOffre = $_numOffre;
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
        return $this->getIdOffre() . "\t" .  $this->getIdFormation() . "\t" . $this->getNumOffre();
    }
}
