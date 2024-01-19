<?php 
include("header.php");
// On détruit la session en cours et on en récrée une autre
session_destroy();
session_start();

// Si le post pour le mdp et le mail éxiste, alors on lance la tentative de connexion
if(isset($_POST['password']) and isset($_POST['mail']))
{
    $mail = htmlspecialchars($_POST['mail']);
    $password = htmlspecialchars($_POST['password']);
    if (filter_var($mail, FILTER_VALIDATE_EMAIL)){ // Si le mail fournis par l'utilisateur est valide
        $reqUser = $bdd->prepare('SELECT * FROM user WHERE Mail=?'); // On récupère les informations de l'utilisateur grâce au mail
        $reqUser->execute(array($mail));

        while($DataUser = $reqUser->fetch()) // On parcours les éléments de la liste (en théorie il n'y a seulement qu'une seule occurence)
        {
            $rien = false; // Cette varriable sert à savoir si il éxiste une occurence avec l'email contenu dans $mail
            if (password_verify($password, $DataUser['password'])){ // On compare si le mot de passe fournie correspond bien au mot de passe correspondant au user qui à le mail contenu dans $mail
                $_SESSION["mdp"] = $password; // On sauvegarde le mot de passe en clair dans une varriable session
                $_SESSION["id"] = $DataUser['idUser']; // On sauvegarde l'ID dans une varriable de session
                header("Location:espace-membre.php"); // On redirige l'utilisateur sur la page espace-membre.php
            }
            else{ // Si le mot de passe n'est pas bon, alors on renvoi une érreur
                $erreur = "test";
            }
        } 
        if (!isset($rien)){ // Si la var $rien n'éxiste pas, alors il n'y a personne dans la bdd qui à l'email contenu dans $mail
            $erreur = "test";
        }
    }
    else{ // L'email est incrorect
        $erreur = "test";
    }
}



?>
<section class="FormulaireConexion">
    <form action="conexion.php" method="POST">
        <label>Mail</label>
        <input type="text" name="mail" placeholder="Mail">
        
        <label>Mot de passe</label>
        <input type="password" name="password" placeholder="Mot de passe">
        
        <?php
        if(isset($erreur)){ // Si la var $erreur existe, alors on renvoi une érreur
            echo "<h2 class='erreur'>Identifiant incorect</h2>";
        }
            
        ?>
        <button>Valider</button>
    </form>
</section>
<div class="void"></div>
<?php 
include("footer.php"); 
?>