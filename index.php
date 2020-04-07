<?php
function ChargerClasse($classe)
{
    if (file_exists("php/Controller/" . $classe . ".Class.php"))
    {
        require "php/Controller/" . $classe . ".Class.php";
    }
    if (file_exists("php/Model/" . $classe . ".Class.php"))
    {
        require "php/Model/" . $classe . ".Class.php";
    }
}
spl_autoload_register("ChargerClasse");
function AfficherPage($page)
{
    $chemin = $page[0];
    $nom = $page[1];
    $titre = $page[2];

    include 'php/view/Head.php';
    include 'php/view/header.php';
    include $chemin . $nom . '.php'; //Chargement de la page en fonction du chemin et du nom
    include 'php/view/Footer.php';
}
// on initialise les paramètres du fichier parametre.ini
Parametre::init();
//on active la connexion à la base de données
DbConnect::init();
session_start();
require "PHP/CONTROLLER/Outils.php";
$routes = [
    "default" => ["php/view/", "Connexion", "Connexion"],

    "connexion" => ["php/view/", "Connexion", "Connexion"],
    "deconnexion" => ["php/view/", "deconnexionAction", "Deconnexion"],
    
    "InterfaceStagiaire" => ["php/view/", "InterfaceStagiaire", "Stagiaire"],
    "InterfaceFormateur" => ["php/view/", "InterfaceFormateur", "Formateur"],
    "ChoixFormateur" => ["php/view/", "ChoixFormateur", "Formateur"],
    "ActionFormateur" => ["php/view/", "ActionFormateur", "Formateur"],
    "InterfaceAT" => ["php/view/", "InterfaceAT", "Assistante Technique"],
    
];

if (isset($_GET["action"]))
{

    $action = $_GET["action"];

    //Si cette route existe dans le tableau des routes
    if (isset($routes[$action]))
    {
        //Afficher la page correspondante
        AfficherPage($routes[$action]);
    }
    else
    {
        //Sinon afficher la page par defaut
        AfficherPage($routes["default"]);
    }

}
else
{
    //Sinon afficher la page par defaut
    AfficherPage($routes["default"]);
    //Decommenter la ligne ci-dessous pour faire le testmanager
    // $_SESSION['idOffre']=4;
    //  AfficherPage(["php/view/", "pointage", "Deconnexion"]);
}
