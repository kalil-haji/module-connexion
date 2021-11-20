<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/connexion.css" media="screen" type="text/css" />
    <title>Page de connexion!</title>
</head>
<body>
<header>
        
        <div class="bloc">
            <div class="leg"><a href="index.php">Acceuil</a></div>
            <div class="repas"><a href="profil.php">Voir mon Profil</a></div>
            <div class="trav"><a href="inscription.php">Inscription</a></div>
        </div>
    </header>
        
<?php
$bdd = mysqli_connect('localhost', 'root', '', 'moduleconnexion');
mysqli_set_charset($bdd, 'utf8');
session_start();

$AfficherFormulaire=1;
if(isset($_POST['user_login'])) { // si le bouton "Connexion" est appuyé
    // on vérifie que le champ "login" n'est pas vide
    if(empty($_POST['user_login'])) {
        echo "Le champ login est vide.";
    } if($_POST['user_login']=='admin' && $_POST['user_password']=='admin'){
        $_SESSION['admin'] = 'test'; // la session peut être appelée différemment et son contenu aussi
                    echo "Vous êtes à présent connecté en tant qu'administrateur !";
                    $AfficherFormulaire=0;
                    header("Refresh: 5; url=admin.php");//redirection vers le formulaire de connexion dans 5 secondes
    echo "<br><br><i>Redirection en cours, vers la page admin...</i>";
    exit(0);//on arrête l'éxécution du reste de la page avec exit, si le membre n'est pas connecté
    }
    else {
        // on vérifie maintenant si le champ "Mot de passe" n'est pas vide"
        if(empty($_POST['user_password'])) {
            echo "Le champ Mot de passe est vide.";
        } else {
            // les champs sont bien posté et pas vide, on sécurise les données entrées par le membre:
            $Pseudo = htmlentities($_POST['user_login'], ENT_QUOTES, "ISO-8859-1");
            $MotDePasse = htmlentities($_POST['user_password'], ENT_QUOTES, "ISO-8859-1");
            //on se connecte à la base de données:
        
            //on vérifie que la connexion s'effectue correctement:
            if(!$bdd){
                echo "Erreur de connexion à la base de données.";
            } else {
                // on fait maintenant la requête dans la base de données pour rechercher si ces données existe et correspondent:
                $Requete = mysqli_query($bdd,"SELECT * FROM utilisateurs WHERE login = '".$Pseudo."' AND password = '".$MotDePasse."'");
                // si il y a un résultat, mysqli_num_rows() nous donnera alors 1
                // si mysqli_num_rows() retourne 0 c'est qu'il a trouvé aucun résultat
                if(mysqli_num_rows($Requete) == 0) {
                    echo "Le pseudo ou le mot de passe est incorrect, le compte n'a pas été trouvé.";
                } else {
                    // on ouvre la session avec $_SESSION:
                    $_SESSION['login'] = $Pseudo; // la session peut être appelée différemment et son contenu aussi
                    echo "Vous êtes à présent connecté !";
                    $AfficherFormulaire=0;
                    header("Refresh: 5; url=profil.php");//redirection vers le formulaire de connexion dans 5 secondes
    echo "<br><br><i>Redirection en cours, vers la page de profil...</i>";
    exit(0);//on arrête l'éxécution du reste de la page avec exit, si le membre n'est pas connecté
                }
            }
        }
    }
}


if($AfficherFormulaire==1){
?>

<form action="./connexion.php" method="post">
        <div>
            <label for="login">Identifiant :</label>
            <input type="text" id="login" name="user_login">
        </div>
        <div>
            <label for="password">Mot de passe :</label>
            <input type="text" id="password" name="user_password">
        <div class="button">
            <button type="submit">Enregistrer</button>
        </div>
    
    </form>
    <?php
}

?>

</body>
</html>