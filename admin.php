<?php
session_start();//session_start() combiné à $_SESSION (voir en fin de traitement du formulaire) nous permettra de garder le pseudo en sauvegarde pendant qu'il est connecté, si vous voulez que sur une page, le pseudo soit (ou tout autre variable sauvegardée avec $_SESSION) soit retransmis, mettez session_start() au début de votre fichier PHP, comme ici
if(!isset($_SESSION['admin'])){
    header("Refresh: 5; url=connexion.php");//redirection vers le formulaire de connexion dans 5 secondes
    echo "Vous devez vous connecter pour accéder à l'espace membre.<br><br><i>Redirection en cours, vers la page de connexion...</i>";
    exit(0);//on arrête l'éxécution du reste de la page avec exit, si le membre n'est pas connecté
}
$Pseudo=$_SESSION['admin'];//on défini la variable $Pseudo (Plus simple à écrire que $_SESSION['pseudo']) pour pouvoir l'utiliser plus bas dans la page
//on se connecte une fois pour toutes les actions possible de cette page:
$bdd=mysqli_connect('localhost','root','','moduleconnexion');//'serveur','nom d'utilisateur','pass','nom de la base'
if(!$bdd) {
    echo "Erreur connexion BDD";
    //Dans ce script, je pars du principe que les erreurs ne sont pas affichées sur le site, vous pouvez donc voir qu'elle erreur est survenue avec mysqli_error(), pour cela décommentez la ligne suivante:
    //echo "<br>Erreur retournée: ".mysqli_error($mysqli);
    exit(0);
}

?><!DOCTYPE HTML>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/admin.css" media="screen" type="text/css" />
    <title>Page admin!</title>
</head>
<body>
<header>
        <div class="bloc">
            <div class="leg"><a href="index.php">Acceuil</br></a></div>
            <div class="repas"><a href="inscription.php">Inscription</a></div>
            <div class="trav"><a href="connexion.php">Connectez vous</a></div>
        </div>
    </header>
        
<?php
$bdd = mysqli_connect('localhost', 'root', '', 'moduleconnexion');
mysqli_set_charset($bdd, 'utf8');

$requete = mysqli_query($bdd, "SELECT * FROM utilisateurs ") or die(mysqli_error($bdd));
$etudiants = mysqli_fetch_all($requete,MYSQLI_ASSOC);


?>

<div>
<table>
    <thead>
        <tr>
            <th>login</th>
            <th>prenom</th>
            <th>nom</th>
            <th>password</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($etudiants as $etudiant){
                echo '<tr><td>'.$etudiant['login'].'</td>';
                echo '<td>'.$etudiant['prenom'].'</td>';
                echo '<td>'.$etudiant['nom'].'</td>';
                echo '<td>'.$etudiant['password'].'</td></tr>';
            }
         ?>
    </tbody>
</table></div>

<h1>Informations Utilisateurs</h1>
        Pour vous déconnecter, <a href="admin.php?modifier">cliquez ici</a>
        <hr/>
        <?php
        //si "?modifier" est dans l'URL:
        if(isset($_GET['modifier'])){
            unset($_SESSION['admin']);//unset() détruit une variable, si vous enregistrez aussi l'id du membre (par exemple) vous pouvez comme avec isset(), mettre plusieurs variables séparés par une virgule:
//unset($_SESSION['pseudo'],$_SESSION['id']);
header("Refresh: 5; url=connexion.php");//redirection vers le formulaire de connexion dans 5 secondes
echo "Vous avez été correctement déconnecté du site.<br><br><i>Redirection en cours, vers la page d'accueil...</i>";
        }
            ?>

</body>
</html>