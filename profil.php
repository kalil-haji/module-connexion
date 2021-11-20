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
            <div class="repas"><a href="inscription.php">Inscritpion</a></div>
            <div class="trav"><a href="connexion.php">Connectez vous</a></div>
        </div>
    </header>
        <h1>Espace membre</h1>
        Pour modifier vos informations, <a href="profil.php?modifier">cliquez ici</a>
        <br>
        Pour vous déconnecter, <a href="profil.php?deco">cliquez ici</a>
        <hr/>
        <?php
        if(isset($_GET['deco'])){
            unset($_SESSION['login']);//unset() détruit une variable, si vous enregistrez aussi l'id du membre (par exemple) vous pouvez comme avec isset(), mettre plusieurs variables séparés par une virgule:
//unset($_SESSION['pseudo'],$_SESSION['id']);
header("Refresh: 5; url=connexion.php");//redirection vers le formulaire de connexion dans 5 secondes
echo "Vous avez été correctement déconnecté du site.<br><br><i>Redirection en cours, vers la page d'accueil...</i>";
        }
        //si "?modifier" est dans l'URL:
        if(isset($_GET['modifier'])){
            ?>
            <h1>Modification du compte</h1>
            Choisissez une option: 
            <p>
                <a href="profil.php?modifier=login">Modifier l'identifient'</a>
                <br>
                <a href="profil.php?modifier=mdp">Modifier le mot de passe</a>
                <br>
                <a href="profil.php?modifier=prenom">Changer le prenom</a>
                <br>
                <a href="profil.php?modifier=nom">Changer le nom</a>
            </p>
            <hr/>
            <?php
            if($_GET['modifier']=="login"){
                echo "<p>Renseignez le formulaire ci-dessous pour modifier vos informations:</p>";
                if(isset($_POST['valider'])){
                    if(!isset($_POST['login'])){
                        echo "Le champ mail n'est pas reconnu.";
                    } else {
                        if(isset($_POST['login'])) {
                            //tout est OK, on met à jours son compte dans la base de données:
                            if(mysqli_query($bdd,"UPDATE utilisateurs SET login='".htmlentities($_POST['login'],ENT_QUOTES,"UTF-8")."' WHERE login='$Pseudo'")){
                                echo "Identifient {$_POST['login']} modifiée avec succès!";
                                $TraitementFini=true;//pour cacher le formulaire
                                header("Refresh: 5; url=connexion.php");//redirection vers le formulaire de connexion dans 5 secondes
    echo "<br>Vous allez être redirigez vers la page de connexion.<br><br><i>Redirection en cours, vers la page de connexion...</i>";
    exit(0);//on arrête l'éxécution du reste de la page avec exit, si le membre n'est pas connecté
                            } else {
                                echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
                                //echo "<br>Erreur retournée: ".mysqli_error($mysqli);
                            }
                        }
                    }
                }
                if(!isset($TraitementFini)){
                    ?>
                    <br>
                    <form method="post" action="profil.php?modifier=login">
                        <input type="text" name="login" value="<?php echo $info['login']; ?>" required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
                        <input type="submit" name="valider" value="Valider la modification">
                    </form>
                    <?php
                }
            } elseif($_GET['modifier']=="mdp"){
                echo "<p>Renseignez le formulaire ci-dessous pour modifier vos informations:</p>";
                //si le formulaire est envoyé ("envoyé" signifie que le bouton submit est cliqué)
                if(isset($_POST['valider'])){
                    //vérifie si tous les champs sont bien pris en compte:
                    if(!isset($_POST['nouveau_mdp'],$_POST['confirmer_mdp'],$_POST['mdp'])){
                        echo "Un des champs n'est pas reconnu.";
                    } else {
                        if($_POST['nouveau_mdp']!=$_POST['confirmer_mdp']){
                            echo "Les mots de passe ne correspondent pas.";
                        } else {
                            $Mdp=$_POST['mdp'];
                            $NouveauMdp=$_POST['nouveau_mdp'];
                            $req=mysqli_query($bdd,"SELECT * FROM utilisateurs WHERE login='$Pseudo' AND password='$Mdp'");
                            //on regarde si le mot de passe correspond à son compte:
                            if(mysqli_num_rows($req)!=1){
                                echo "Mot de passe actuel incorrect.";
                            } else {
                                //tout est OK, on met à jours son compte dans la base de données:
                                if(mysqli_query($bdd,"UPDATE utilisateurs SET password='$NouveauMdp' WHERE login='$Pseudo'")){
                                    echo "Mot de passe modifié avec succès!";
                                    $TraitementFini=true;//pour cacher le formulaire
                                } else {
                                    echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
                                    //echo "<br>Erreur retournée: ".mysqli_error($mysqli);
                                }
                            }
                        }
                    }
                }
                if(!isset($TraitementFini)){
                    ?>
                    <br>
                    <form method="post" action="profil.php?modifier=mdp">
                        <input type="password" name="nouveau_mdp" placeholder="Nouveau mot de passe..." required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
                        <input type="password" name="confirmer_mdp" placeholder="Confirmer nouveau passe..." required>
                        <input type="password" name="mdp" placeholder="Votre mot de passe actuel..." required>
                        <input type="submit" name="valider" value="Valider la modification">
                    </form>
                    <?php
                }
            }
            if($_GET['modifier']=="prenom"){
                echo "<p>Renseignez le formulaire ci-dessous pour modifier vos informations:</p>";
                if(isset($_POST['valider'])){
                    if(!isset($_POST['prenom'])){
                        echo "Le champ mail n'est pas reconnu.";
                    } else {
                        if(isset($_POST['prenom'])) {
                            //tout est OK, on met à jours son compte dans la base de données:
                            if(mysqli_query($bdd,"UPDATE utilisateurs SET prenom='".htmlentities($_POST['prenom'],ENT_QUOTES,"UTF-8")."' WHERE login='$Pseudo'")){
                                echo "Prenom {$_POST['prenom']} modifiée avec succès!";
                                $TraitementFini=true;//pour cacher le formulaire
                                
                            } else {
                                echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
                                //echo "<br>Erreur retournée: ".mysqli_error($mysqli);
                            }
                        }
                    }
                }
                if(!isset($TraitementFini)){$newreqq = mysqli_query($bdd, "SELECT prenom FROM utilisateurs WHERE login='$Pseudo'") or die(mysqli_error($bdd));
                    $usernamee = mysqli_fetch_all($newreqq,MYSQLI_ASSOC);
                    ?>
                    <br>
                    <form method="post" action="profil.php?modifier=prenom">
                        <input type="text" name="prenom" value="<?php echo $usernamee[0]['prenom']; ?>" required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
                        <input type="submit" name="valider" value="Valider la modification">
                    </form>
                    <?php
                }
            }
            if($_GET['modifier']=="nom"){
                echo "<p>Renseignez le formulaire ci-dessous pour modifier vos informations:</p>";
                if(isset($_POST['valider'])){
                    if(!isset($_POST['nom'])){
                        echo "Le champ mail n'est pas reconnu.";
                    } else {
                        if(isset($_POST['nom'])) {
                            //tout est OK, on met à jours son compte dans la base de données:
                            if(mysqli_query($bdd,"UPDATE utilisateurs SET nom='".htmlentities($_POST['nom'],ENT_QUOTES,"UTF-8")."' WHERE login='$Pseudo'")){
                                echo "Nom {$_POST['nom']} modifiée avec succès!";
                                $TraitementFini=true;//pour cacher le formulaire
                                
                            } else {
                                echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
                                //echo "<br>Erreur retournée: ".mysqli_error($mysqli);
                            }
                        }
                    }
                }
                if(!isset($TraitementFini)){$newreq = mysqli_query($bdd, "SELECT nom FROM utilisateurs WHERE login='$Pseudo'") or die(mysqli_error($bdd));
                    $usersubname = mysqli_fetch_all($newreq,MYSQLI_ASSOC);
                    ?>
                    <br>
                    <form method="post" action="profil.php?modifier=nom">
                        <input type="text" name="nom" value="<?php echo $usersubname[0]['nom']; ?>" required><!-- required permet d'empêcher l'envoi du formulaire si le champ est vide -->
                        <input type="submit" name="valider" value="Valider la modification">
                    </form>
                    <?php
                }
            }
        }
        
        ?>
    </body>
</html>