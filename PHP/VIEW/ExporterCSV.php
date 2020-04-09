<div id="interfaceAT">

<?php


//Si l'offre ou la semaine ne sont pas selectionnés, retour à l'acceuil
if (!isset($_GET["mode"])) {
    header("Location: index.php");
}

//Si exportation du CSV d'une offre
if ($_GET["mode"] == "unique") {

    if (!isset($_GET["idOffre"]) || !isset($_GET["idSemaine"])) {
        header("Location: index.php?action=InterfaceAT");
    }

    //Recuperer le numero d'offre et le numero de semaine
    $numOffre = OffreManager::findById($_GET["idOffre"])->getNumOffre();
    $semaine = SemaineManager::findById($_GET["idSemaine"])->getNumSemaine();
    $pointages = [];

    //Générer les données csv
    $csvLignes = creationDonneesOffreCSV($_GET["idOffre"], $_GET["idSemaine"], $numOffre, $semaine, $pointages);

//Si exportation du CSV de plusieurs offres
} else if ($_GET["mode"] == "multiple") {
    if (!isset($_POST["idOffres"]) || !isset($_GET["idSemaine"])) {
        header("Location: index.php?action=InterfaceAT");
    }

    $semaine = SemaineManager::findById($_GET["idSemaine"])->getNumSemaine();

    $csvLignes = [];

    //Pour chaque offre
    foreach ($_POST["idOffres"] as $offre) {

        $numOffre = OffreManager::findById($offre)->getNumOffre();
        $pointages = [];

        //Générer les données csv
        $csvLignes = array_merge($csvLignes, creationDonneesOffreCSV($offre, $_GET["idSemaine"], $numOffre, $semaine, $pointages));

    }

}

//Paramètres du fichier csv
$cheminFichier = "CSV";

//Création du nom de fichier

//Si offre unique
if ($_GET["mode"] == "unique") {

    //Nom de fichier avec n° d'offre et de semaine
    $nomFichier = "Presences Offre " . $numOffre . " - Semaine " . $semaine . ".csv";

//Si offre multiples
} else if ($_GET["mode"] == "multiple") {

    //Nom de fichier avec tous les n° d'offres et le n° de semaine

    $nomFichier = "Presences Offres ";

    foreach ($_POST["idOffres"] as $offre) {
        $numOffre = OffreManager::findById($offre)->getNumOffre();
        $nomFichier .= $numOffre . ",";
    }

    $nomFichier = rtrim($nomFichier, ",");
    $nomFichier .= " - Semaine " . $semaine . ".csv";
}

//Créer le dossier CSV s'il n'existe pas
if (!is_dir($cheminFichier)) {
    mkdir($cheminFichier);
}

//Ouverture d'un pointeur vers le fichier csv
$csv = fopen($cheminFichier . "/" . $nomFichier, 'w');

//Encodage UTF-8 du fichier
fputs($csv, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

//Mettre les lignes du tableau dans le fichier
foreach ($csvLignes as $ligne) {
    fputcsv($csv, $ligne, ";");
}

//fermeture du pointeur
fclose($csv);

echo '<p>Le fichier "' . $nomFichier . '" a bien été créé.</p>';
echo '<a class="bouton" href="' . $cheminFichier . "/" . $nomFichier . '">Ouvrir fichier</a>';
echo '<a class="bouton" href="index.php?action=InterfaceAT">Retour</a>';

##########################################################################################################################################
#                                                            FONCTIONS                                                                   #
##########################################################################################################################################

//Fonction qui génère les lignes du fichier csv pour une offre et une semaine
function creationDonneesOffreCSV($idOffre, $idSemaine, $numOffre, $semaine, $pointages)
{
    //Récupérer et afficher la liste des pointages correspondants à l'offre et à la semaine

    //Stagiaires de l'offre
    $stagiaires = StagiaireManager::getStagiairesParOffres($idOffre);

    //Pour chaque stagiaire de l'offre
    foreach ($stagiaires as $stagiaire) {

        //Recuperer les pointages de la semaine
        $pointagesStagiaire = PointageManager::getListByStagiaire($stagiaire->getIdStagiaire(), $idSemaine);

        $pointages = array_merge($pointages, $pointagesStagiaire);
    }

    //Création d'un tableau contenant les pointages avec titre et première ligne d'entête

    $csvTitre = ["Présences des bénéficiaires de l'offre " . $numOffre . " dans la semaine n°" . $semaine . "."]; //Titre

    //Entetes de colonnes
    $csvEntetes = [
        "Jour",
        "Demi-Journée",
        "Nom Stagiaire",
        "Prénom Stagiaire",
        "Numero Bénéficiaire",
        "Code Présence",
    ];

    //Ajout du titre et des entetes au tableau
    $csvLignes[] = $csvTitre;
    $csvLignes[] = [];
    $csvLignes[] = $csvEntetes;

    //Ajouter chaque pointage au tableau
    foreach ($pointages as $pointage) {

        //Récupération du stagiaire grace à son id dans pointage
        $stagiaire = StagiaireManager::findById($pointage->getIdStagiaire());
        $nomStagiaire = $stagiaire->getNom();
        $prenomStagiaire = $stagiaire->getPrenom();
        $numBenefStagiaire = $stagiaire->getNumBenef();

        //Récupération du jour grace à son id dans pointage
        $journee = JourneeManager::findById($pointage->getIdJournee());
        $date = $journee->getJour();
        $demiJournee = $journee->getDemiJournee();

        //Récupération de l'indicateur de présence grace à son id dans pointage
        $presence = PresenceManager::findById($pointage->getIdPresence());
        $refPresence = $presence->getRefPresence();
        $libellePresence = $presence->getLibellePresence();

        //Ajout des données du pointage dans une nouvelle ligne du tableau
        $csvLignes[] = [
            $date,
            $demiJournee,
            $nomStagiaire,
            $prenomStagiaire,
            $numBenefStagiaire,
            $refPresence,
        ];
    }

    $csvLignes[] = [];
    $csvLignes[] = [];
    $csvLignes[] = [];

    return $csvLignes;

}

?>

</div>
