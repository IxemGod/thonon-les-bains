<?php
session_start();

// Ce try est là pour que le projet soit fonctionnel en ligne (suscorp.fr) comme en local
try
{
	$bdd = new PDO('mysql:host=suscorq454.mysql.db;dbname=suscorq454;charset=utf8', 'suscorq454', 'motdepasse');
    // $bdd = new PDO('sqlite:suscorp2');
}
catch (Exception $e)
{
	// die('Erreur : ' . $e->getMessage());
	$bdd = new PDO('sqlite:suscorp2');
	$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" sizes="16x16" href="assets/logo.png">
    
    <!-- Importation de fontawesome -->
    <link rel="stylesheet"href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">  
    <script src="https://kit.fontawesome.com/b98b4e1e1c.js" crossorigin="anonymous"></script>
    
    <!-- Importation des fichiers bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
    <!-- Importation des fichiers css -->
    <link type="text/css" rel="stylesheet" href="css/header.css">
    <link type="text/css" rel="stylesheet" href="css/index.css">
    <link type="text/css" rel="stylesheet" href="css/discipline.css">
    <link type="text/css" rel="stylesheet" href="css/conexion.css">
    <link type="text/css" rel="stylesheet" href="css/espace-membre.css">
    <link type="text/css" rel="stylesheet" href="css/forum.css">
    <link type="text/css" rel="stylesheet" href="css/footer.css">
    

    <title>Salle Omnisport Thonons-les-bains</title>
</head>
<header>
    <div class="logo">
        <a href="index.php"><img src="assets/logo.png"></a>
    </div>

    <nav>
        <a href="index.php">Accueil</a>
        <a href="forum.php">Forum</a>
        <a href="disciplines.php">Discipline</a>
        <a href="inscirption.php">Insription</a>
        <a href="espace-membre.php">Membre ?</a>
    </nav>

</header>

<body>