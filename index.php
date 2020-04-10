<?PHP

function ChargerClasse($classe)
{
    if (file_exists("PHP/CONTROLLER/" . $classe . ".Class.php"))
    {
        require "PHP/CONTROLLER/" . $classe . ".Class.php";
    }
    if (file_exists("PHP/MODEL/" . $classe . ".Class.php"))
    {	
        require "PHP/MODEL/" . $classe . ".Class.php";
    }
}
spl_autoload_register("ChargerClasse");
function AfficherPage($page)
{
    $chemin = $page[0];
    $nom = $page[1];
    $titre = $page[2];

    include 'PHP/VIEW/Head.php';
    include 'PHP/VIEW/Header.php';
    include $chemin . $nom . '.php'; //Chargement de la page en fonction du chemin et du nom
    include 'PHP/VIEW/Footer.php';
}

// on initialise les paramètres du fichier parametre.ini
Parametre::init();
//on active la connexion à la base de données
DbConnect::init();
session_start();
require "PHP/CONTROLLER/Outils.php";
$routes = [
    "default" => ["PHP/VIEW/", "Connexion", "Connexion"],

    "connexion" => ["PHP/VIEW/", "Connexion", "Connexion"],
    "deconnexion" => ["PHP/VIEW/", "Deconnexion", "Deconnexion"],
    
    "InterfaceStagiaire" => ["PHP/VIEW/", "InterfaceStagiaire", "Stagiaire"],
    "InterfaceFormateur" => ["PHP/VIEW/", "InterfaceFormateur", "Formateur"],
    "ChoixFormateur" => ["PHP/VIEW/", "ChoixFormateur", "Formateur"],
    "ActionFormateur" => ["PHP/VIEW/", "ActionFormateur", "Formateur"],
    "InterfaceAT" => ["PHP/VIEW/", "InterfaceAT", "Interface AT"],
    "exporterCSV" => ["PHP/VIEW/", "ExporterCSV", "Exporter CSV"],
    "ActionInterfaceStagiaire" =>["PHP/VIEW/","ActionInterfaceStagiaire","ActionInterfaceStagiaire"],
    "ActionInterfaceFormateur" =>["PHP/VIEW/","ActionInterfaceFormateur","ActionInterfaceFormateur"]
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

}
