<?php include("header.php");?>

<!-- Liste des posts -->
<section class="postBody">

<h1>Forum maison des sports Thonons-les-bains</h1>
<p class="creatPost">Crée un post ? <a href="addPost.php">Clique ici</a></p>

<?php
if(isset($_GET['post'])){
    // On affiche le post en entier
    $reqPostComplet = $bdd -> prepare("SELECT * FROM posts WHERE idPost = ?");
    $reqPostComplet -> execute(array(htmlspecialchars($_GET['post'])));
    while($dataPostComplet = $reqPostComplet -> fetch()){

    ?>
    <div class="visualisationPost">
    <h2><?=$dataPostComplet["title"]?></h2>
    <p><?=$dataPostComplet["content"]?></p>
    </div>


    <?php
    }
    
    if(isset($_SESSION['id'])){ // Si l'id de l'user est stocker dans la session, alors on fait les test d'authentification

    
    $reqUser = $bdd->prepare('SELECT * FROM user WHERE idUser=?');
    $reqUser->execute(array($_SESSION['id']));

        while ($dataUser = $reqUser->fetch())
        {
            if (password_verify($_SESSION['mdp'], $dataUser['password'])) {
                if(isset($_POST['response'])){// si la réponse exsite, alors on publie la réponse
                    $reqInsertResponse = $bdd -> prepare("INSERT INTO comment(idUser, idPost, content) VALUES(?,?,?)");
                    $reqInsertResponse -> execute(array($_SESSION["id"], htmlspecialchars($_GET['post']), htmlspecialchars($_POST['response'])));
                }
        ?>
    <!-- Si connecter, alors on affiche ça -->
    <div class="commentToPost">
        <h3>Espace commentaires :</h3><?php
    $reqComments = $bdd -> prepare("SELECT * FROM comment WHERE idPost = ?"); // On affiche les commentaires
    $reqComments -> execute(array(htmlspecialchars($_GET['post'])));
    while($dataComments = $reqComments -> fetch()){
        $reqAuteurDeComment = $bdd -> prepare("SELECT * FROM user WHERE idUser = ?");
        $reqAuteurDeComment -> execute(array($dataComments['idUser']));
        while($dataAuteurDeComment = $reqAuteurDeComment -> fetch()){

    ?>

    <div class="visualisationComment">
    <label><?=$dataAuteurDeComment['Nom']?> <?=$dataAuteurDeComment['Prénom']?> :</label>
    <p><?=$dataComments["content"]?></p>
    </div>


    <?php
        }
    }
    ?>
    <form method="post" action="?post=<?=$_GET['post']?>">
        <h2 class="commentTitle">Commenter le post ?</h2>
        <textarea name="response"></textarea>

        <button class="btnPoster">Poster</button>
    </form>

    </div>
    <?php
    }else{
        ?>

        <!-- Sinon, alors : -->

        <p>Connectez vous pour commenter ce post !</p>
        <?php
        } 
   
}
}
else{
    ?>
    <p>Connectez vous pour commenter ce post !</p>
    <?php
    }
}
else{
    
?>
<div class="postList">
    <?php
        $reqListPosts = $bdd -> prepare("SELECT * FROM posts"); // On affiche la liste des posts
        $reqListPosts -> execute();

        while($dataListPosts = $reqListPosts -> fetch()){
            ?>
            <div clas="postPreview">
            <a href="?post=<?=$dataListPosts['idPost']?>"> 
                <fieldset>
                    <legend><?=$dataListPosts['title']?></legend>
                    <p><?=substr($dataListPosts['content'], 0,25)."..."?></p>
                 </fieldset>
            </a>
         </div>
         <?php
        }
    ?>
</div>
<?php 
}

?>



</section>


<?php include("footer.php");?>