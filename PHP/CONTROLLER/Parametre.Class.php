<?php
Class Parametre
{
    static private $_host;
    static private $_port;
    static private $_dbName;
    static private $_login;
    static private $_pwd;
    
    static public function getHost()
    {
        return self::$_host;
    }

    static public function getPort()
    {
        return self::$_port;
    }

    static public function getDbName()
    {
        return self::$_dbName;
    }

    static public function getLogin()
    {
        return self::$_login;
    }

    static public function getPwd()
    {
        return self::$_pwd;
    }
    static public function init()
    {
        // si le fichier existe
        if (file_exists("parametre.ini")) $fic = "parametre.ini";
        else echo "Pas de fichier de paramètres";
        $flux = fopen($fic,"r"); //on ouvre le fichier en lecture
        //tant qu'il y a des lignes
        while (!feof($flux))
        {
            $ligne = fgets($flux,4096);
            if ($ligne) // si la ligne n'est pas vide
            {
                $info = explode(":",$ligne); // on sépare la ligne selon le ;
                $param[$info[0]]=rtrim($info[1]); //on remplit un tableau associatif avec la 1ere partie en clé, la 2nde en valeur
            }
        }
        // on remplie les attributs de la classe
        self::$_host = $param["Host"]; 
        self::$_port = $param["Port"];
        self::$_dbName = $param["DbName"];
        self::$_login= $param["Login"];
        self::$_pwd = $param["Pwd"];

    }

    
}