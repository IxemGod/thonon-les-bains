<?php
include("header.php"); 
if(isset($_SESSION['id'])) // On test si l'id est stocker dans une var session
{

$reqUser = $bdd->prepare('SELECT * FROM user WHERE idUser=?'); // On récupère les information de l'user
$reqUser->execute(array($_SESSION['id']));

while ($dataUser = $reqUser->fetch()) //Si on passe par ici, alors on ajoute 1 à la varriable "i". Si on passe pas, alors "i" restera à 0.
{
    if (password_verify($_SESSION['mdp'], $dataUser['password'])) {  // On test si les mots de passes correspondent
?>
        <section class="containerEspaceMembre">
            <nav class="menuVertical">
                <img class="avatarPageMembre" src="assets/users/<?= $dataUser['Img'] ?>">
                <h2><?= $dataUser["Nom"] ?> <?= $dataUser["Prénom"] ?></h2>

                <section class="ListOnglet">
                    <div><a href="?page=1"><i class="far fa-address-card"></i> Vos informations</a>
                        <div class="trait"></div>
                    </div>
                    <div><a href="?page=2"><i class="fas fa-hammer"></i> Paramêtre</a>
                        <div class="trait"></div>
                    </div>
                        
                    <div><a href="?page=3"><i class="fa-solid fa-lock"></i></i> Securité</a>
                        <div class="trait"></div>
                    </div>
                    <div><a href="conexion.php"><i class="fas fa-sign-out-alt"></i> Se déconecter</a>
                        <div class="trait"></div>
                    </div>
                </section>
            </nav>


            <?php

            if (isset($_GET['page'])) { // Si il éxite une var page dans l'urel
                if (htmlspecialchars($_GET['page']) == 1) { // Si le contenu de la var page est 1, alors c'est l'onglet des infos personnel
            ?>
                    <div class="InfoPerso">
                        <h2 class="titreEspaceMembre">Vos informations personnels</h2>

                        <div class="ListInfoPerso">

                            <div class="ItemInfoPerso">
                                <label>Nom :</label>
                                <input type="text" value="<?= $dataUser["Nom"] ?>">
                            </div>

                            <div class="ItemInfoPerso">

                                <label>Prénom :</label>
                                <input type="text" value="<?= $dataUser["Prénom"] ?>">

                            </div>

                            <div class="ItemInfoPerso">

                                <label>Mail :</label>
                                <input type="text" value="<?= $dataUser["Mail"] ?>">

                            </div>

                            <div class="ItemInfoPerso">

                                <label>Téléphone :</label>
                                <input type="text" value="<?= $dataUser["Téléphone"] ?>">

                            </div>

                            <div class="ItemInfoPerso">

                                <label>Âge :</label>
                                <input type="text" value="<?= $dataUser["age"] ?>">

                            </div>


                            <div class="ItemInfoPerso">

                                <label>Vos équipes :</label>
                                <?php 
                                // Ici on affiche toute les informations relatives à l'user 
                                $reqEquipeDeUser = $bdd -> prepare("SELECT * FROM equipe_user WHERE idUser = ?");
                                $reqEquipeDeUser -> execute(array($_SESSION['id']));

                                while($dataEquipeDeUser = $reqEquipeDeUser -> fetch()){
                                    $reqDataEquipeDeUser = $bdd -> prepare("SELECT * FROM équipe WHERE IdEquipe = ?");
                                    $reqDataEquipeDeUser -> execute(array($dataEquipeDeUser['IdEquipe']));
                                    while($dataCompletEquipeDeUser = $reqDataEquipeDeUser -> fetch()){
                                        ?>
                                        <p><?=$dataCompletEquipeDeUser['Nom']?></p>
                                        <?php
                                    }
                                }                                
                                ?>
                
                            </div>
                        </div>



                    </div>
                <?php
                }

                if (htmlspecialchars($_GET['page']) == 2) {  // Si la var page contenu dans l'url est 2, alors on affiche l'onglet de modification des information personnel

                ?>
                    <div class="InfoPerso">
                        <h2 class="titreEspaceMembre">Modification de vos informations personnels</h2>
                        <?php
                        if (isset($_POST["name"]) and isset($_POST["familyname"]) and isset($_POST["mail"]) and isset($_POST["phone"]) and isset($_POST["old"])
                            and strlen($_POST["name"]) > 0 and strlen($_POST["familyname"]) > 0 and strlen($_POST["mail"]) > 0 and strlen($_POST["phone"]) > 0 and strlen($_POST["old"]) > 0) {
                            $name = htmlspecialchars($_POST['familyname']);
                            $familyname = htmlspecialchars($_POST['name']);
                            $mail = htmlspecialchars($_POST['mail']);
                            $phone = htmlspecialchars($_POST['phone']);
                            $old = intval(htmlspecialchars($_POST['old']));
                            settype($old, "integer");


                            if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                                $phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);

                                // If you want to clean it up manually you can:
                                $phone = preg_replace('/[^0-9+-]/', '', $phone);
                 


                                // If you want to check the length of the phone number and that it's valid you can:
                                if (strlen($phone) == 10) {
                      

                                    if ($old > 90 || $old < 2) { // Si l'âge est > 90 ou < 2, alors l'âge est incorect
                                        echo "<p style='color: red;'>J'ai juré tu mens sur ton âge..</p>";
                                    } else {
                                            $reqUpdateInfoUser = $bdd->prepare('UPDATE user SET age = :old, Nom = :familyname, Prénom = :name, Mail = :mail, Téléphone = :phone WHERE idUser = :IdUser');
                                            $reqUpdateInfoUser->execute(array(
                                                   'name' => $name,
                                                   "familyname" => $familyname,
                                                   'mail' => $mail,
                                                   'phone' => $phone,
                                                   'old' => $old,
                                                   "IdUser" => intval($_SESSION["id"])
                                                    ));

                                        echo "<p style='color : green;'>Les informations ont bien été pris en compte</p>";
                                    }
                                } else {
                                    echo "<p style='color :red;'>Le numéro saisi est incorect</p>";
                                }
                            }
                        } else {
                            echo "<p style='color:red;'>Merci de remplire tout les champs</p>";
                        }

                        $reqUser = $bdd->prepare('SELECT * FROM user WHERE idUser=?'); // On récupère toute les informations pour que l'utilisateur puisse par la suite les modifié
                        $reqUser->execute(array($_SESSION['id']));

                        while ($dataUser = $reqUser->fetch())
                        {

                        ?>

                        <div class="ListInfoPerso">
                            <form action="?page=2" method="post">
                                <div class="ItemInfoPerso">
                                    <label>Nom :</label>
                                    <input type="text" name="name" value="<?= $dataUser["Nom"] ?>">
                                </div>

                                <div class="ItemInfoPerso">

                                    <label>Prénom :</label>
                                    <input type="text" name="familyname" value="<?= $dataUser["Prénom"] ?>">

                                </div>

                                <div class="ItemInfoPerso">

                                    <label>Mail :</label>
                                    <input type="text" name="mail" value="<?= $dataUser["Mail"] ?>">

                                </div>

                                <div class="ItemInfoPerso">

                                    <label>Téléphone :</label>
                                    <input type="text" name="phone" value="<?= $dataUser["Téléphone"] ?>">

                                </div>

                                <div class="ItemInfoPerso">

                                    <label>Âge :</label>
                                    <input type="text" name="old" value="<?= $dataUser["age"] ?>">

                                </div>

                                <button>Modifier</button>

                        </div>

                        <?php } ?>



                        </div>
                <?php               
                }  
                if (htmlspecialchars($_GET['page']) == 3) { // Si la var page contenu dans l'url est égale à 3, alors c'est l'onglet de modfication du mot de passe
                    ?>
                    <div class="InfoPerso">
                    <h2 class="titreEspaceMembre">Modification de votre mot de passe</h2>
                    <?php
                    if (isset($_POST["newPassword"]) and isset($_POST["confirmPassword"]) and strlen($_POST["newPassword"]) >= 4 and strlen($_POST["confirmPassword"]) >= 4) {
                        $newPassword = htmlspecialchars($_POST['newPassword']);
                        $confirmPassword = htmlspecialchars($_POST['confirmPassword']);

                        if ($newPassword == $confirmPassword) {
                            
                            $password = password_hash($newPassword, PASSWORD_DEFAULT);
                            $reqUpdateInfoUser = $bdd->prepare('UPDATE user SET password = :password WHERE idUser = :IdUser');
                            $reqUpdateInfoUser->execute(array(
                                    'password' => $password,
                                    "IdUser" => intval($_SESSION["id"])
                                    ));

                                    echo "<p style='color : green;'>Les informations ont bien été pris en compte</p>";
                            }
                            else{
                                echo "<p style='color :red;'Vos mots de passe sont différent</p>";
                            }

                            } else {
                                echo "<p style='color :red;'>Votre mot de passe doit faire plus de 3 caractères</p>";
                            }
                        
           
                    ?>

                    <div class="ListInfoPerso">
                        <form action="?page=3" method="post">
                            <div class="ItemInfoPerso">
                                <label>Nouveau Mot de passe :</label>
                                <input type="text" name="newPassword">
                            </div>

                            <div class="ItemInfoPerso">

                                <label>Confirmation du mot de passe :</label>
                                <input type="text" name="confirmPassword">

                            </div>

                            <button>Modifier</button>

                    </div>

                    
                </div>
                <?php } ?>
        </section>
            <?php
                
                
            } else {
                header("Location:?page=1"); // Si il n'éxiste pas de var page dans l'url, alors on en crée une
            }
        } else {
            header("Location:conexion.php");
        }
    }
}
else{
    header("Location:conexion.php"); // Si il n'éxiste par de var SESSION alors on redirige ver s connexion
}
    include("footer.php");
     ?>