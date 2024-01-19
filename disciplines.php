<?php include("header.php"); ?>

<?php
// Si le user à cliquer sur une disciplines, alors on affiche les donnée
if(isset($_GET['d']) and htmlspecialchars($_GET['d']) <= 4 and htmlspecialchars($_GET['d']) >= 1) 
{
    if(isset($_GET['equipe']) and isset($_SESSION['id'])){ // Si la var equipe est contenu dans l'url, ca veut dire que l'user veut s'inscrire ou se désinscrire d'une équipe
        $InfoEquipes = $bdd->prepare('SELECT * FROM équipe WHERE IdEquipe = ?');
        $InfoEquipes->execute(array(htmlspecialchars($_GET['equipe'])));
        while($InfoEquipe = $InfoEquipes->fetch()){
            // On teste si la personne est déjà inscrite dans cette équipe
            $TestEquieUsers = $bdd->prepare('SELECT * FROM equipe_user WHERE idUser = ? AND IdEquipe = ?');
            $TestEquieUsers->execute(array($_SESSION['id'], htmlspecialchars($_GET['equipe'])));
            $NbrRow = 0;
            while($TestEquieUser = $TestEquieUsers -> fetch()){
                $NbrRow = 1;
            }
            


            if($NbrRow == 1){ // Si déjà inscrite, alors on le désinscrit
                $RemoveRowEquipe = $bdd ->prepare("DELETE FROM equipe_user WHERE idUser=? AND IdEquipe=?");
		        $RemoveRowEquipe -> execute(array($_SESSION['id'],  htmlspecialchars($_GET['equipe'])));
            }
            else{ // Sinon, alors on l'inscrit
                $InsertEquipeUsers = $bdd->prepare("INSERT INTO equipe_user('IdEquipe','idUser')VALUES(?,?)");

                $InsertEquipeUsers-> execute(array(intval(htmlspecialchars($_GET['equipe'])), intval($_SESSION['id']) ));
            }
            // header("Location:disciplines.php");
        }


    }
    // On récupère toute les données correspondant à l'id de la discipline récupéré dans l'url.
    $dataRecu = $bdd->prepare('SELECT * FROM disciplines WHERE IdDiscipline = ?');
    $dataRecu->execute(array(htmlspecialchars($_GET['d'])));
    while ($data = $dataRecu->fetch())
    {
    ?>
    <section class="PresentaDiscipline">
        <div class="TitreEtText" style="background : url('assets/<?=$data['ImageClub']?>');background-size: cover;">
            <div class="Titre">
                <h1><?=$data['TitreComplet']?></h1>
                </div>

                <div class="text">
                    <p><?=$data['DesClub']?></p>
            </div>
        </div>

        <div class="tarif">
            <h2>Les Tarifs</h2>
            <table>
                <thead>
                    <tr>
                    <th></th>
                    <th>Enfant</th>
                    <th>Etudiant</th>
                    <th>Adulte</th>
                    </tr>
                </thead>
                
                <tbody>
                    <tr>
                    <td>Abonement</td>
                    <td>13€/mois</td>
                    <td>15€/mois</td>
                    <td>20€/mois</td>
                    </tr>
                </tbody>
            </table>

            <div class="Horaires">
                <div class="para">
                    <p><?=$data['horaire']?></p>
                </div>
                
            </div>
            
        </div>

        <?php 
        $DataEquipes = $bdd->prepare('SELECT * FROM équipe WHERE IdDiscipline = ?'); // On récupère les équipes liée à cette discipline
        $DataEquipes->execute(array(htmlspecialchars($_GET['d'])));
        while ($DataEquipe = $DataEquipes->fetch()){
        ?>
        <div class="Membres">
            <h2><?=$DataEquipe['Nom']?></h2>

 

            <?php
            // Pour chaque Discipline, on sélection uniquement les coachs
            $DataCoachs = $bdd->prepare('SELECT * FROM user WHERE role = ?');
            $DataCoachs->execute(array(2));
            while ($DataCoach = $DataCoachs->fetch())
            {
                $reqUserEquipeUser = $bdd->prepare('SELECT * FROM equipe_user WHERE idUser=?');
                $reqUserEquipeUser->execute(array($DataCoach["idUser"]));
                while ($DataEquipeUser = $reqUserEquipeUser->fetch()){
                    if($DataEquipeUser["IdEquipe"] == $DataEquipe["IdEquipe"]){
                        
                        ?>
                        <div class="item">
                        <img src="assets/users/<?=$DataCoach['Img']?>">
                        <p><?=$DataCoach['Prénom']?> - Coach</p>
                    </div>
                    <?php
            
                    }

                }
             }
            
                ?>

            <div class="listMembres">
    
    <?php
            // Pour chaque Discipline, on sélection uniquement les membres
            $DataMembres = $bdd->prepare('SELECT * FROM user WHERE role = ?');
            $DataMembres->execute(array(1));
        while ($DataMembre = $DataMembres->fetch())
        {
            $reqUserEquipeUser = $bdd->prepare('SELECT * FROM equipe_user WHERE idUser=?'); // On selectionne la liste des équipes où le user ($Datamembre) fait partie
            $reqUserEquipeUser->execute(array($DataMembre["idUser"]));
            while ($DataEquipeUser = $reqUserEquipeUser->fetch()){
                if($DataEquipeUser["IdEquipe"] == $DataEquipe["IdEquipe"]){ // Si l'équipe de l'user correspond à l'équipe qu'on affiche
                    
                    ?>
                    <div class="item">
                    <img src="assets/users/<?=$DataMembre['Img']?>">
                    <p><?=$DataMembre['Prénom']?>, <?=$DataMembre['age']?> ans</p>
                </div>
                <?php
        
                }

            }
         }
                ?>

             
                </div>
                <?php
                if(isset($_SESSION['id'])){  // Si l'id est socker dans une session, alors on fait des test pour pouvoir afficher le bouton s'inscrire ou se désinscrire
                    
                $reqUser = $bdd->prepare('SELECT * FROM user WHERE idUser=?');
                $reqUser->execute(array($_SESSION['id']));
                
                while ($DataUser = $reqUser->fetch()) 
                {
                    if (password_verify($_SESSION['mdp'], $DataUser['password'])) {
                        $reqDejaEquipe = $bdd->prepare('SELECT * FROM equipe_user WHERE idUser = ? AND IdEquipe = ?');
                        $reqDejaEquipe -> execute(array($_SESSION['id'], htmlspecialchars($_GET['d'])));
                        $dejavu = true;
                        while($dataDejaEquipe = $reqDejaEquipe -> fetch())
                        {
                            $dejavu = false;
                        ?>
                        <form method="POST" action="?d=<?=$_GET['d']?>&equipe=<?=$DataEquipe['IdEquipe']?>">
                            <button style="inscrire">Se désinscrire</button>
                        </form>
                <?php }
                    if($dejavu)
                    {
                        ?>
                        <form method="POST" action="?d=<?=$_GET['d']?>&equipe=<?=$DataEquipe['IdEquipe']?>">
                            <button style="inscrire">S'inscrire</button>
                        </form>

                        <?php
                    }
                    }
                }
            }

                ?>

        </div>
        <?php } ?>

    </section>
<?php }
}
// Si l'user n'a pas cliquer sur une discipline ou alors que la donnée dans l'url est incorect, on lui affiche la liste des disciplines
else{
?>
<h1 class="h1Disciplines">Les disciplines enseigné : </h1>
<section class="ListeDiscipline">
    <div class="item">
        <img src="assets/HipPopCarou.jpg">
        <h2>Hip-pop</h2>
        <p>Le hip-hop est un genre musical et culturel qui a émergé dans les quartiers urbains aux États-Unis dans les années 1970. Il englobe un large éventail d'éléments, y compris la musique, la danse, le graffiti et le style vestimentaire. La musique hip-hop se caractérise par l'utilisation de rythmes entraînants, de percussions marquées et de paroles expressives, souvent centrées sur des thèmes tels que la vie urbaine, les défis sociaux et politiques, et l'expression de soi.
            La culture hip-hop a évolué pour devenir un phénomène mondial, influençant la mode, l'art, la danse et la langue. Les quatre éléments fondamentaux du hip-hop sont le DJing, le MCing (rapping), le breakdancing et le graffiti. Ce mouvement culturel a également joué un rôle important dans la promotion de l'expression artistique, de l'activisme social et de la diversité culturelle.
        </p>
        <a href="?d=1">Voir</a>
    </div>
    
    <div class="item">
        <img src="assets/tenisTable.jpg">
        <h2>Ténis de Table</h2>
        <p>Le tennis de table, également connu sous le nom de ping-pong, est un sport dynamique et captivant qui se joue sur une table divisée par un filet. Deux joueurs ou deux équipes s'affrontent, utilisant des raquettes légères pour frapper une balle légère en caoutchouc d'un côté à l'autre de la table. La vitesse et la précision sont essentielles dans ce jeu, car les participants s'efforcent de marquer des points en faisant rebondir la balle sur la table de manière stratégique, tout en anticipant les réponses adverses.

Le tennis de table offre un mélange unique de vitesse explosive, de réflexes rapides et de finesse technique. Les échanges rapides et énergiques entre les joueurs créent une atmosphère palpitante, où la concentration et la coordination mains-yeux sont mises à l'épreuve à chaque instant. Les rebonds imprévisibles sur la table ajoutent un élément de suspense, rendant chaque point gagné une victoire bien méritée.


        </p>
        <a href="?d=2">Voir</a>
    </div> 
    
    <div class="item">
        <img src="assets/zumbaCarou.jpg">
        <h2>Zumba</h2>
        <p>
La Zumba, c'est bien plus qu'un simple cours de fitness, c'est une explosion d'énergie positive à chaque mouvement. Ce programme de danse d'inspiration latine allie l'exercice physique à la fête, créant ainsi une expérience unique et enjouée. Les participants se laissent emporter par des rythmes entraînants, allant de la salsa au merengue, en passant par la cumbia et le reggaeton.

Au cœur de la Zumba, il y a le plaisir de danser. Les chorégraphies simples et dynamiques sont conçues pour que chacun, quel que soit son niveau de danse, puisse suivre le rythme et se perdre dans la musique. Les instructeurs, véritables maîtres de la motivation, guident les participants à travers une séance où la sueur et le sourire vont de pair.
        </p>
        <a href="?d=3">Voir</a>
    </div> 
    
    <div class="item">
        <img src="assets/basketCarou.webp">
        <h2>Basket</h2>
        <p>Le hip-hop est un genre musical et culturel qui a émergé dans les quartiers urbains aux États-Unis dans les années 1970. Il englobe un large éventail d'éléments, y compris la musique, la danse, le graffiti et le style vestimentaire. La musique hip-hop se caractérise par l'utilisation de rythmes entraînants, de percussions marquées et de paroles expressives, souvent centrées sur des thèmes tels que la vie urbaine, les défis sociaux et politiques, et l'expression de soi.
            La culture hip-hop a évolué pour devenir un phénomène mondial, influençant la mode, l'art, la danse et la langue. Les quatre éléments fondamentaux du hip-hop sont le DJing, le MCing (rapping), le breakdancing et le graffiti. Ce mouvement culturel a également joué un rôle important dans la promotion de l'expression artistique, de l'activisme social et de la diversité culturelle.
        </p>
        <a href="?d=4">Voir</a>
    </div>
</section>
<?php } ?>

<?php include("footer.php"); ?>