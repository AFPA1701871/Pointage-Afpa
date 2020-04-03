<?php

// Ce fichier sera inclus � chaque fois que l'on aura besoin d'acceder � la base de donn�es.
// Il permet d'ouvrir la connection � la base de donn�es
class DbConnect {
	private static $db;
	
	public static function getDb() {
		return DbConnect::$db;
	}

	public static function init() {
		try {
			// On se connecte � MySQL
			self::$db= new PDO ( 'mysql:host='.Parametre::getHost().';port='.Parametre::getPort().';dbname='.Parametre::getDbName().';charset=utf8', Parametre::getLogin(),Parametre::getPwd() );
		} catch ( Exception $e ) {
			// En cas d'erreur, on affiche un message et on arr�te tout
			die ( 'Erreur : ' . $e->getMessage () );
		}
		
	}
}