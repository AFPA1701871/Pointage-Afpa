<?php
class Semaine
{
 /***********************  Attributs  ***********************/
    private $_idSemaine;
    private $_mois;
    private $_numSemaine;

/************************GETTER / SETTER ******************* */
/// 
public function getIdSemaine()
{
    return $this->_idSemaine;
}

public function setIdSemaine($_idSemaine)
{
    $this->_idSemaine = $_idSemaine;
}

/* ************************************ */
/// 
public function getMois()
    {
        return $this->_mois;
    }

    public function setMois($_mois)
    {
        $this->_mois = $_mois;
    }

/* ************************************ */
///
public function getNumSemaine()
{
    return $this->_numSemaine;
}

public function setNumSemaine($_numSemaine)
{
    $this->_numSemaine = $_numSemaine;
}


/************************CONSTRUCTEUR ******************* */

 public function __construct(array $options = [])
 {
        if (!empty($options)) // empty : renvoi vrai si le tableau est vide
        {
            $this->hydrate($options);
        }
    }
    public function hydrate($data)
    {
        foreach ($data as $key => $value)
        {
            $methode = "set" . ucfirst($key); //ucfirst met la 1ere lettre en majuscule
            if (is_callable(([$this, $methode]))) // is_callable verifie que la methode existe
            {
                $this->$methode($value);
            }
        }
    }

/************************AUTRES METHODES ******************* */
 
public function toString()
{
    return $this->getIdSemaine() . ' ' . $this->getMois() . ' ' . $this->getNumSemaine() ;
}
  
}  

    

   