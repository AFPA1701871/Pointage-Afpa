<?php

//Si l'offre ou la semaine ne sont pas selectionnés, retour à l'acceuil
if(!isset($_GET["idOffre"]) || !isset($_GET["idSemaine"])){
    header("Location: index.php");
}

//Recuperer le numero d'offre et le numero de semaine
$numOffre = OffreManager::findById($_GET["idOffre"])->getNumOffre();
$semaine = SemaineManager::findById($_GET["idSemaine"])->getNumSemaine();

 //Récupérer et afficher la liste des pointages correspondants à l'offre et à la semaine

    //Stagiaires de l'offre
    $stagiaires = StagiaireManager::getStagiairesParOffres($_GET["idOffre"]);

    //Liste des pointages
    $pointages = [];

    //Pour chaque stagiaire de l'offre
    foreach ($stagiaires as $stagiaire) {

        //Recuperer les pointages de la semaine
        $pointagesStagiaire = PointageManager::getListByStagiaire($stagiaire->getIdStagiaire(), $_GET["idSemaine"]);

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

//Paramètres du fichier csv
$cheminFichier = "CSV";
$nomFichier = "Presences Offre " . $numOffre . " - Semaine " . $semaine . ".csv";

//Créer le dossier CSV s'il n'existe pas
if (!is_dir($cheminFichier)) {
    mkdir($cheminFichier);
}

//Ouverture d'un pointeur vers le fichier csv
$csv = fopen($cheminFichier ."/". $nomFichier, 'w');

//Encodage UTF-8 du fichier
fputs($csv, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

//Mettre les lignes du tableau dans le fichier
foreach ($csvLignes as $ligne) {
    fputcsv($csv, $ligne, ";");
}

//fermeture du pointeur
fclose($csv);

echo '<p>Le fichier "' . $nomFichier . '" a bien été créé.</p>';
echo '<a class="bouton" href="' . $cheminFichier ."/". $nomFichier . '">Ouvrir fichier</a>';
echo '<a class="bouton" href="index.php?action=interfaceAT&numOffre='.$numOffre.'&semaine='.$semaine.'">Retour</a>';
