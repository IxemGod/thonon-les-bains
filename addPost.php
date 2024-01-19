<?php include("header.php"); ?>
<?php
if(isset($_SESSION['id'])){


if(isset($_POST["title"]) and isset($_POST["content"])){ // Si les var en post title et content existe, alors on les ajoutes à la ddb

    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $reqUser = $bdd->prepare('SELECT * FROM user WHERE idUser=?');
    $reqUser->execute(array($_SESSION['id']));

while ($dataUser = $reqUser->fetch()) //Si on passe par ici, alors on ajoute 1 à la varriable "i". Si on passe pas, alors "i" restera à 0.
{
    if (password_verify($_SESSION['mdp'], $dataUser['password'])) {
        $insertPost = $bdd -> prepare("INSERT INTO posts(idUser, title, content) VALUES (?,?,?)");
        $insertPost-> execute(array($_SESSION['id'], $title ,$content));
        
        $getIdPost = $bdd ->prepare("SELECT idPost FROM posts WHERE title = ?");
        $getIdPost-> execute(array($title));

        while($dataPostRecent = $getIdPost -> fetch()){
            header("Location:forum.php?post=".$dataPostRecent['idPost']);
        }
    }

    }
}
}
else{
    echo "<h1 style='text-align:center; color: red;'>Connectez vous</h1>";
}


?>
<section class="formNewPost">
<h1>Nouveau post</h1>
<form method="POST" action="">
    <label>Titre :</label>
    <input type="text" name="title" placeholder="Titre du post"required>
    <label>Contenu du post : </label>
    <textarea name="content" required></textarea>

    <button class="btnPoster">Poster</button>
</form>

</section>


<?php include("footer.php")?>