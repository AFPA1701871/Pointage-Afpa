<?php
class Journee
{
 /***********************  Attributs  ***********************/
    private $_idJournee;
    private $_jour;
    private $_demiJournee;
    private $_idSemaine;


/************************GETTER / SETTER ******************* */
/// 
public function getIdJournee()
{
    return $this->_idJournee;
}

public function setIdJournee($_idJournee)
{
    $this->_idJournee = $_idJournee;
}


/* ************************************ */
public function getJour()
{
    return $this->_jour;
}

public function setJour($_jour)
{
    $this->_jour = $_jour;
}
/// 


/* ************************************ */
public function getDemiJournee()
{
    return $this->_demiJournee;
}

public function setDemiJournee($_demiJournee)
{
    $this->_demiJournee = $_demiJournee;
}


/* ************************************ */
public function getIdSemaine()
{
    return $this->_idSemaine;
}

public function setIdSemaine($_idSemaine)
{
    $this->_idSemaine = $_idSemaine;
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
    return $this->getIdJournee() . ' ' . $this->getJour() . ' ' . $this->getDemiJournee() . ' ' . $this->getIdSemaine();
}
 
   
}  

    

   