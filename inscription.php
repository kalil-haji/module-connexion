<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/inscription.css" media="screen" type="text/css" />
    <title>Page d'inscription!</title>
</head>
<body>
    <header>
        <h1 class="titre_1">Formulaire d'inscription</h1>
        <div class="bloc">
            <div class="leg"><a href="index.php">Acceuil</a></div>
            <div class="repas"><a href="profil.php">Voir mon Profil</a></div>
            <div class="trav"><a href="connexion.php">Connectez vous</a></div>
        </div>
    </header>
<?php
session_start();
$bdd = mysqli_connect('localhost', 'root', '', 'moduleconnexion');
mysqli_set_charset($bdd, 'utf8');

$AfficherFormulaire=1;

if(isset($_POST['user_login'],$_POST['user_password'],$_POST['user_name'],$_POST['user_sub_name'],$_POST['user_confirm_password'])){//l'utilisateur à cliqué sur "S'inscrire", on demande donc si les champs sont défini avec "isset"
    if(empty($_POST['user_login'])){//le champ login est vide, on arrête l'exécution du script et on affiche un message d'erreur
        echo "Le champ Identifiant est vide.";
    } elseif(!preg_match("#^[a-z0-9]+$#",$_POST['user_login'])){//le champ login est renseigné mais ne convient pas au format qu'on souhaite qu'il soit, soit: que des lettres minuscule + des chiffres
        echo "Le Pseudo doit être renseigné en lettres minuscules sans accents, sans caractères spéciaux.";
    } elseif(strlen($_POST['user_login'])>25){//le login est trop long, il dépasse 25 caractères
        echo "L'identifient est trop long, il dépasse 25 caractères.";
    } elseif(empty($_POST['user_password'])){//le champ mot de passe est vide
        echo "Le champ Mot de passe est vide.";
    } elseif(mysqli_num_rows(mysqli_query($bdd,"SELECT * FROM utilisateurs WHERE login='".$_POST['user_login']."'"))==1){//on vérifie que ce pseudo n'est pas déjà utilisé par un autre membre
        echo "Cette identifient est déjà utilisé.";
    } elseif(empty($_POST['user_name'])){//le champ mot de passe est vide
        echo "Le champ Prenom est vide.";
    }elseif(empty($_POST['user_sub_name'])){//le champ mot de passe est vide
        echo "Le champ Nom est vide.";
    }elseif($_POST['user_confirm_password'] != $_POST['user_password']){//le champ mot de passe est vide
        echo "Le champ Confirmer le mot de passe n'est pas identique au mot de passe.";
    }
    else {
        //toutes les vérifications sont faites, on passe à l'enregistrement dans la base de données:
        if(!mysqli_query($bdd,"INSERT INTO utilisateurs SET login='".$_POST['user_login']."', password='".$_POST['user_password']."', nom='".$_POST['user_sub_name']."', prenom='".$_POST['user_name']."'")){//on crypte le mot de passe avec la fonction propre à PHP: md5()
            echo "Une erreur s'est produite: ".mysqli_error($bdd);//je conseille de ne pas afficher les erreurs aux visiteurs mais de l'enregistrer dans un fichier log
        } else {
            echo "Vous êtes inscrit avec succès! Page de connexion";
            //on affiche plus le formulaire
            $AfficherFormulaire=0;
            header("Refresh: 5; url=connexion.php");//redirection vers le formulaire de connexion dans 5 secondes
    echo "<br><i>Redirection en cours, vers la page de connexion...</i>";
    exit(0);//on arrête l'éxécution du reste de la page avec exit, si le membre n'est pas connecté
        }
    }
}
if($AfficherFormulaire==1){
    ?>

<form action="./inscription.php" method="post">
        <div>
            <label for="login">Identifiant :</label>
            <input type="text" id="login" name="user_login">
        </div>
        <div>
            <label for="prenom">Prenom :</label>
            <input type="text" id="prenom" name="user_name">
        </div>
        <div>
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="user_sub_name">
        </div>
        <div>
            <label for="password">Mot de passe :</label>
            <input type="text" id="password" name="user_password">
        </div>
        <label for="confirm_password">Confirmer le mot de passe :</label>
            <input type="text" id="confirm_password" name="user_confirm_password">
        </div>
        <div class="button">
            <button type="submit">Enregistrer</button>
        </div>
    
    </form>
    <?php
}

?>

</body>
</html>