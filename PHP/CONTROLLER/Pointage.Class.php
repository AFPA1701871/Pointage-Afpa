<?php
class Pointage
{
 /***********************  Attributs  ***********************/
    private $_idPointage;
    private $_idStagiaire;
    private $_idJournee;
    private $_idPresence;
    private $_commentaire;
    private $_validation;
   

/************************GETTER / SETTER ******************* */
/// 
public function getIdPointage()
{
    return $this->_idPointage;
}

public function setIdPointage($_idPointage)
{
    $this->_idPointage = $_idPointage;
}

/* ************************************ */
/// 
public function getIdStagiaire()
{
    return $this->_idStagiaire;
}

public function setIdStagiaire($_idStagiaire)
{
    $this->_idStagiaire = $_idStagiaire;
}

/* ************************************ */
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
public function getCommentaire()
    {
        return $this->_commentaire;
    }

    public function setCommentaire($_commentaire)
    {
        $this->_commentaire = $_commentaire;
    }
/* ************************************ */
/// 
public function getValidation()
{
    return $this->_validation;
}

public function setValidation($_validation)
{
    $this->_validation = $_validation;
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
    return $this->getIdPointage() . ' ' . $this->getIdStagiaire() . ' ' . $this->getIdJournee() . ' ' . $this->getIdPresence(). ' '. $this->getCommentaire(). ' ' . $this->getValidation();
}
  



  
}  