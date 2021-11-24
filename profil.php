<?php
session_start();//session_start() combiné à $_SESSION (voir en fin de traitement du formulaire) nous permettra de garder le pseudo en sauvegarde pendant qu'il est connecté, si vous voulez que sur une page, le pseudo soit (ou tout autre variable sauvegardée avec $_SESSION) soit retransmis, mettez session_start() au début de votre fichier PHP, comme ici
if(!isset($_SESSION['login'])){
    header("Refresh: 5; url=connexion.php");//redirection vers le formulaire de connexion dans 5 secondes
    echo "Vous devez vous connecter pour accéder à l'espace membre.<br><br><i>Redirection en cours, vers la page de connexion...</i>";
    exit(0);//on arrête l'éxécution du reste de la page avec exit, si le membre n'est pas connecté
}

$Pseudo=$_SESSION['login'];//on défini la variable $Pseudo (Plus simple à écrire que $_SESSION['pseudo']) pour pouvoir l'utiliser plus bas dans la page
//on se connecte une fois pour toutes les actions possible de cette page:
$bdd=mysqli_connect('localhost','root','','moduleconnexion');//'serveur','nom d'utilisateur','pass','nom de la base'
if(!$bdd) {
    echo "Erreur connexion BDD";
    //Dans ce script, je pars du principe que les erreurs ne sont pas affichées sur le site, vous pouvez donc voir qu'elle erreur est survenue avec mysqli_error(), pour cela décommentez la ligne suivante:
    //echo "<br>Erreur retournée: ".mysqli_error($mysqli);
    exit(0);
}
//on récupère les infos du membre si on souhaite les afficher dans la page:
$req=mysqli_query($bdd,"SELECT * FROM utilisateurs WHERE login='$Pseudo'");
$info=mysqli_fetch_assoc($req);
?><!DOCTYPE HTML>
<html>
    <head>
    <link rel="stylesheet" href="./css/profil.css" media="screen" type="text/css" />
        <title>Script espace membre</title>
    </head>
    <body>
    <header>
        <h1 class="titre_1">Mon Profil</h1>
        <div class="bloc">
            <div class="leg"><a href="index.php">Acceuil</a></div>
            
			<p>Identifient : <?php if(isset($_POST['login'])){echo $_POST['login'];}
				else {$newreq4 = mysqli_query($bdd, "SELECT login FROM utilisateurs WHERE login='$Pseudo'");
			$usersubname4 = mysqli_fetch_all($newreq4,MYSQLI_ASSOC);
			echo $usersubname4[0]['login'];}?></br>Nom : <?php if(isset($_POST['nom'])){echo $_POST['nom'];}
				else {$newreq = mysqli_query($bdd, "SELECT nom FROM utilisateurs WHERE login='$Pseudo'");
			$usersubname = mysqli_fetch_all($newreq,MYSQLI_ASSOC);
				echo $usersubname[0]['nom'];}?></br>Prenom : <?php if(isset($_POST['prenom'])){echo $_POST['prenom'];}
				else {$newreq2 = mysqli_query($bdd, "SELECT prenom FROM utilisateurs WHERE login='$Pseudo'");
			$usersubname2 = mysqli_fetch_all($newreq2,MYSQLI_ASSOC);
			echo $usersubname2[0]['prenom'];}?></br>Mot de passe : <?php if(isset($_POST['password'])){echo $_POST['password'];}
			else {$newreq3 = mysqli_query($bdd, "SELECT password FROM utilisateurs WHERE login='$Pseudo'");
			$usersubname3 = mysqli_fetch_all($newreq3,MYSQLI_ASSOC);
			echo $usersubname3[0]['password'];}?></p>
        </div>
    </header>
        <h1>Modification des Informations</h1>

        <?php $newreqq = mysqli_query($bdd, "SELECT prenom FROM utilisateurs WHERE login='$Pseudo'") or die(mysqli_error($bdd));
                    $usernamee = mysqli_fetch_all($newreqq,MYSQLI_ASSOC);
                    $newreq = mysqli_query($bdd, "SELECT nom FROM utilisateurs WHERE login='$Pseudo'") or die(mysqli_error($bdd));
                    $usersubname = mysqli_fetch_all($newreq,MYSQLI_ASSOC);
                    $newreq3 = mysqli_query($bdd, "SELECT password FROM utilisateurs WHERE login='$Pseudo'") or die(mysqli_error($bdd));
                    $pass = mysqli_fetch_all($newreq3,MYSQLI_ASSOC); 
                    
                    if(isset($_POST['valider'])){
                        if(!isset($_POST['prenom'])){
                            echo "Le champ mail n'est pas reconnu.";
                        } else {
                            if(isset($_POST['prenom'])) {
                                //tout est OK, on met à jours son compte dans la base de données:
                                if(mysqli_query($bdd,"UPDATE utilisateurs SET prenom='".htmlentities($_POST['prenom'],ENT_QUOTES,"UTF-8")."' WHERE login='$Pseudo'")){
                                    echo "Informations modifié avec succés !";
                                    
                                    
                                }
                            }
                        }
                        if(!isset($_POST['nom'])){
                            echo "Le champ mail n'est pas reconnu.";
                        } else {
                            if(isset($_POST['nom'])) {
                                //tout est OK, on met à jours son compte dans la base de données:
                                if(mysqli_query($bdd,"UPDATE utilisateurs SET nom='".htmlentities($_POST['nom'],ENT_QUOTES,"UTF-8")."' WHERE login='$Pseudo'")){
                                    
                                    
                                    
                                }
                            }
                        }
                        if(!isset($_POST['password'])){
                            echo "Le champ mail n'est pas reconnu.";
                        } else {
                            if(isset($_POST['password'])) {
                                //tout est OK, on met à jours son compte dans la base de données:
                                if(mysqli_query($bdd,"UPDATE utilisateurs SET password='".htmlentities($_POST['password'],ENT_QUOTES,"UTF-8")."' WHERE login='$Pseudo'")){
                                    
                                    
                                    
                                }
                            }
                        }
                        if(!isset($_POST['login'])){
                            echo "Le champ mail n'est pas reconnu.";
                        } else {
                            if(isset($_POST['login'])) {
                                //tout est OK, on met à jours son compte dans la base de données:
                                if(mysqli_query($bdd,"UPDATE utilisateurs SET login='".htmlentities($_POST['login'],ENT_QUOTES,"UTF-8")."' WHERE login='$Pseudo'")){
                                    
                                    $Pseudo = $_POST['login'];
                                    
                                    if($_POST['login'] !== $_SESSION['login']){
                                        unset($_SESSION['login']);//unset() détruit une variable, si vous enregistrez aussi l'id du membre (par exemple) vous pouvez comme avec isset(), mettre plusieurs variables séparés par une virgule:
                //unset($_SESSION['pseudo'],$_SESSION['id']);
                header("Refresh: 5; url=connexion.php");//redirection vers le formulaire de connexion dans 5 secondes
                echo "Identifient modifié.<br><br><i>Redirection en cours, vers la page de connexion...</i>";
                                    }
                                    
                                }
                            }
                        }
                    }
                    
                    ?>

        <form method="post" action="profil.php">
                        <label for="login">Identifient :</label>
                        <input type="text" name="login" value="<?php if(isset($_POST['login'])){echo $_POST['login'];} else {echo $info['login'];} ?>" required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
                        <label for="password">Prenom :</label>
                        <input type="text" name="prenom" value="<?php if(isset($_POST['prenom'])){echo $_POST['prenom'];} else {echo $usernamee[0]['prenom'];} ?>" required>
                        <label for="password">Nom :</label>
                        <input type="text" name="nom" value="<?php if(isset($_POST['nom'])){echo $_POST['nom'];} else {echo $usersubname[0]['nom'];} ?>" required>
                        <label for="password">Mot de passe :</label>
                        <input type="text" name="password" value="<?php if(isset($_POST['password'])){echo $_POST['password'];} else {echo $pass[0]['password'];} ?>" required>
                        <input type="submit" name="valider" value="Valider la modification">
                    </form>
         
    </body>
</html>
