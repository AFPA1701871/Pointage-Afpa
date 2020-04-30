<?php
//Attribution des variables de session
$lvl = (isset($_SESSION['level'])) ? (int) $_SESSION['level'] : 1;
$id = (isset($_SESSION['id'])) ? (int) $_SESSION['id'] : 0;
$identifiant = (isset($_SESSION['identifiant'])) ? $_SESSION['identifiant'] : '';
$matricule = (isset($_SESSION['matricule'])) ? $_SESSION['matricule'] : '';
$nom2 = (isset($_SESSION['nom'])) ? $_SESSION['nom'] : '';
$prenom = (isset($_SESSION['prenom'])) ? $_SESSION['prenom'] : '';
$action = (isset($_GET['action'])) ? $_GET['action'] : '';

?>

<body>
    <header>

        <div class="logo">
            <img src="Images/Logo_Afpa.jpg" alt="Logo Afpa">
        </div>

        <div id="title"><?php echo $titre ?></div>

        <div class="colonne centrer" id="bienvenue">

            <?php
if ($action != "connexion" && $action != "")
{
    if ($identifiant != "" || $matricule != "")
    {
        echo '<div class="centrer">' . $nom2 . ' ' . $prenom . '</div>
                <div class="centrer"> <a href="index.php?action=deconnexion">Se déconnecter</a> </div>';
    }
}?>
        </div>

    </header>