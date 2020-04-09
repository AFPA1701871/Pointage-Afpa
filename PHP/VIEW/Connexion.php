<?php

if (!isset($_POST['identifiant'])) // On est dans la page de formulaire
{
    require 'Php/View/HtmlConnexion.php'; // On affiche le formulaire

} 
else 
{ // Le formulaire a été validé
    $message = '';
    if (empty(trim($_POST['identifiant'])) || empty(trim($_POST['motDePasse']))) // Oublie d'un champ         
    {
        $message = '<p>une erreur s\'est produite pendant votre identification.
                       Vous devez remplir tous les champs</p>
                       <p>Cliquez <a href="index.php?action=connexion">ici</a> pour revenir</p>';
    } 
    else // On check le mot de passe
    {
        if ($_POST['choix']=="stagiaire")
        {
            $stagiaire = StagiaireManager::getByNumBenef($_POST['identifiant']);
            if ($stagiaire->getMotDePasse() == md5(trim($_POST['motDePasse']))) // Acces OK !
            {
                $_SESSION['identifiant'] = $stagiaire->getNumBenef();
                $_SESSION['idStagiaire'] = $stagiaire->getIdStagiaire();
                $_SESSION['idOffre'] = $stagiaire->getIdOffre();
                $_SESSION['nom'] = $stagiaire->getNom();
                $_SESSION['prenom'] = $stagiaire->getPrenom();
                header("refresh:0,url=index.php?action=InterfaceStagiaire");
            }
            else // Acces pas OK !
            {
                $message = '<p>Une erreur s\'est produite pendant votre identification.<br /> Le mot de passe ou l\'identifiant
                entré n\'est pas correcte.</p>';
                header("refresh:3,url=index.php?action=connexion");
            }
        }
        else
        {
            $formateur = FormateurManager::getByMatricule($_POST['identifiant']);
            if ($formateur->getMotDePasse() == md5(trim($_POST['motDePasse']))) // Acces OK !
            {
                $_SESSION['idFormateur'] = $formateur->getIdFormateur();
                $_SESSION['matricule'] = $formateur->getMatricule();
                $_SESSION['nom'] = $formateur->getNom();
                $_SESSION['prenom'] = $formateur->getPrenom();
                $_SESSION['role'] = $formateur->getRole();
                $lvl = (isset($_SESSION['role'])) ? (int) $_SESSION['role'] : 1;
                if ($lvl==1)
                {
                    header("refresh:0,url=index.php?action=ChoixFormateur");
                }
                else {
                    header("refresh:0,url=index.php?action=InterfaceAT");
                } 
            }
            else // Acces pas OK !
            {
                $message = '<p>Une erreur s\'est produite pendant votre identification.<br /> Le mot de passe ou l\'identifiant
                entré n\'est pas correcte.</p>';
                header("refresh:3,url=index.php?action=connexion");
            }
        }
    }echo $message;
} 