<?php

if (!isset($_POST['identifiant'])) // On est dans la page de formulaire
{
    require 'Php/View/HtmlConnexion.php'; // On affiche le formulaire

} 
else 
{ // Le formulaire a été validé
    $message = '';
    if (empty($_POST['identifiant']) || empty($_POST['motDePasse'])) // Oublie d'un champ         
    {
        $message = '<p>une erreur s\'est produite pendant votre identification.
	                   Vous devez remplir tous les champs</p>
	                   <p>Cliquez <a href="index.php?action=connect">ici</a> pour revenir</p>';
    } 
    else // On check le mot de passe
    {
        if ($_POST['choix']=="stagiaire")
        {
            $stagiaire = StagiaireManager::getByNumBenef($_POST['identifiant']);
            if ($stagiaire->getMotDePasse() == md5($_POST['motDePasse'])) // Acces OK !
            {
                $_SESSION['identifiant'] = $stagiaire->getNumBenef();
                $_SESSION['nom'] = $stagiaire->getNom();
                $_SESSION['prenom'] = $stagiaire->getPrenom();
                $message = '<p>Bienvenue ' . $stagiaire->getPrenom() ." ". $stagiaire->getNom() . ', vous êtes maintenant connecté!</p>';

                header("refresh:3,url=index.php?action=InterfaceStagiaire");
            }
            else // Acces pas OK !
            {
                $message = '<p>Une erreur s\'est produite 	    pendant votre identification.<br /> Le mot de passe ou le pseudo
                entré n\'est pas correcte.</p>';
                header("refresh:3,url=index.php?action=connect");
            }
        }
        else
        {
            $formateur = FormateurManager::getByMatricule($_POST['identifiant']);
            if ($formateur->getMotDePasse() == md5($_POST['motDePasse'])) // Acces OK !
            {
                $_SESSION['identifiant'] = $formateur->getMatricule();
                $_SESSION['nom'] = $formateur->getNom();
                $_SESSION['prenom'] = $formateur->getPrenom();
                $_SESSION['role'] = $formateur->getRole();
                $lvl = (isset($_SESSION['role'])) ? (int) $_SESSION['role'] : 1;
                $message = '<p>Bienvenue ' . $formateur->getPrenom() ." ". $formateur->getNom() . ', vous êtes maintenant connecté!</p>';

                if ($lvl==1)
                {
                    header("refresh:3,url=index.php?action=InterfaceFormateur");
                }
                else {
                    header("refresh:3,url=index.php?action=InterfaceAT");
                } 
            }
            else // Acces pas OK !
            {
                $message = '<p>Une erreur s\'est produite 	    pendant votre identification.<br /> Le mot de passe ou le pseudo
                entré n\'est pas correcte.</p>';
                header("refresh:3,url=index.php?action=connect");
            }
        }
    }echo $message;
} 


