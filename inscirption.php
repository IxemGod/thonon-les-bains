<?php 
include("header.php");

if(isset($_POST["inscription"])){ // Si le bouton valider est cliquer, alors on tente de crée le compte
    if(isset($_POST['nom']) and isset($_POST['prenom']) and isset($_POST['mail']) and isset($_POST['telephone']) and isset($_POST['password'])){
			$mail = htmlspecialchars($_POST['mail']);
			$nom = htmlspecialchars($_POST['nom']);
			$prenom = htmlspecialchars($_POST['prenom']);
			$password = htmlspecialchars($_POST['password']);
			$numeroN = htmlspecialchars($_POST['telephone']);
			$telLong = strlen($numeroN);

			$qmail= $bdd->prepare("SELECT * FROM user WHERE mail = :mail"); // On cherche si le mail n'ai pas déjà présent dans la bdd
			$qmail -> execute(['mail' => $mail]);
			$resultmail = $qmail->fetch();
			if($resultmail) // Si $resultmail est à True, alors c'est que le mail est déjà utilisé
			{
				echo "<h1>Adresse Mail indisponible</h1>";   
			
			}
			else{
				if (filter_var($mail, FILTER_VALIDATE_EMAIL)){ // On test si l'email est valide
                       
                    if($telLong == 10) // On test si le numéro fait bien 10 chiffre
                    {

                        $requete = $bdd->prepare('INSERT INTO user(Nom,Prénom, Mail, Téléphone, Img, password)VALUES(?,?,?,?,?,?)');
                        $mdpCrypt = password_hash($password, PASSWORD_DEFAULT); // On hash le mot de passe
                        var_dump($requete->execute(array($nom,$prenom,$mail, $numeroN, 'user.jpg', $mdpCrypt))); // On crée le compte
                        echo "<h1>Inscirption Réussi !</h1>";
                    }
                    else // Si le numéro est différent de 10 chiffres
                        {
                            echo "<h1>Numéro incorect</h1>";   
                        
                        }
                }
                if($resultmail) // Si l'email est invalide
                {
                    echo "<h1>Adresse Mail indisponible</h1>";   
                
                }

            }
    }
}

?>

<section class="FormulaireConexion">
    <form action="inscirption.php" method="POST">
        <label>Nom</label>
        <input type="text" name="nom" placeholder="Nom">
        
        <label>Prénom</label>
        <input type="text" name="prenom" placeholder="Prénom">
        
        <label>Mail</label>
        <input type="text" name="mail" placeholder="Mail">
        
        <label>Téléphone</label>
        <input type="text" name="telephone" placeholder="Téléphone">
         
        <label>Mot de passe</label>
        <input type="password" name="password" placeholder="Mot de passe">
        
        <button name="inscription">Valider</button>
    </form>
</section>
<div class="void"></div>
<?php 
include("footer.php"); 
?>