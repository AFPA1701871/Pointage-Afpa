<?php

if (!isset($_POST['nom'])) // On est dans la page de formulaire
{
    require 'Php/View/HtmlConnexion.php'; // On affiche le formulaire

} else { // Le formulaire a été validé
    $message = '';
    if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['motDePasse'])) // Oublie d'un champ        (empty($_POST['role']) ||) comme le stagiaire n'a pas de role 
    {
        $message = '<p>une erreur s\'est produite pendant votre identification.
	                   Vous devez remplir tous les champs</p>
	                   <p>Cliquez <a href="index.php?action=connect">ici</a> pour revenir</p>';
    } else // On check le mot de passe
    {

        $stagiaire = StagiaireManager::findById($_POST['NumBenef']);
        $formateur = FormateurManager::findById($_POST['Matricule']);

        if ($stagiaire->getMotDePasse() == md5($_POST['motDePasse'])) // Acces OK !
        {
            $_SESSION['NumBenef'] = $stagiaire->getNumBenef();
            $_SESSION['nom'] = $stagiaire->getNom();
            $_SESSION['prenom'] = $stagiaire->getPrenom();
            $_SESSION['id'] = $stagiaire->getNumBenef();
            $message = '<p>Bienvenue ' . $stagiaire->getPrenom() ." ". $stagiaire->getNom() . ', vous êtes maintenant connecté!</p>';

            header("refresh:3,url=index.php?action=InterfaceStagiaire");

        } else if ($formateur->getMotDePasse() == md5($_POST['motDePasse'])) // Acces OK !
        {
            $_SESSION['Matricule'] = $formateur->getMatricule();
            $_SESSION['nom'] = $formateur->getNom();
            $_SESSION['prenom'] = $formateur->getPrenom();
            $_SESSION['role'] = $formateur->getRole();
            $lvl = (isset($_SESSION['role'])) ? (int) $_SESSION['role'] : 1;
            $_SESSION['id'] = $formateur->getMatricule();
            $message = '<p>Bienvenue ' . $formateur->getPrenom() ." ". $formateur->getNom() . ', vous êtes maintenant connecté!</p>';

            if ($lvl==1)
            {
                header("refresh:3,url=index.php?action=InterfaceFormateur");}
             else {
                 header("refresh:3,url=index.php?action=InterfaceAT");
             } 

        } else // Acces pas OK !
        {
            $message = '<p>Une erreur s\'est produite 	    pendant votre identification.<br /> Le mot de passe ou le pseudo
            entré n\'est pas correcte.</p>';
            header("refresh:3,url=index.php?action=connect");
        }
    }
    echo $message;
}
