<?php
//Attribution des variables de session
$lvl = (isset($_SESSION['level'])) ? (int) $_SESSION['level'] : 1;
$id = (isset($_SESSION['id'])) ? (int) $_SESSION['id'] : 0;
$pseudo = (isset($_SESSION['pseudo'])) ? $_SESSION['pseudo'] : '';
?>

<body>
    <header>
        <div>
            <div class="centrer">
                <h2><?php echo $titre ?></h2>
            </div>
            <div class="colonne centrer">
                <?php if ($pseudo!=""){ 
                    echo '<div class="centrer">'.$pseudo.'</div>
                <div class="centrer"> <a href="index.php?action=deconnect">Déconnecter</a> </div>';
                }
?>            </div>
        </div>
    </header>