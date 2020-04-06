<?php
class Presence
{
 /***********************  Attributs  ***********************/
    private $_idPresence;
    private $_refPresence;
    private $_libellePresence;

/************************GETTER / SETTER ******************* */
/// 
public function getIdPresence()
{
    return $this->_idPresence;
}

public function setIdPresence($_idPresence)
{
    $this->_idPresence = $_idPresence;
}

/* ************************************ */
/// 
public function getRefPresence()
    {
        return $this->_refPresence;
    }

    public function setRefPresence($_refPresence)
    {
        $this->_refPresence = $_refPresence;
    }

/* ************************************ */
/// 
public function getLibellePresence()
{
    return $this->_libellePresence;
}

public function setLibellePresence($_libellePresence)
{
    $this->_libellePresence = $_libellePresence;
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
    return $this->getIdPresence() . ' ' . $this->getRefPresence() . ' ' . $this->getLibellePresence();
} 



}  

    

   