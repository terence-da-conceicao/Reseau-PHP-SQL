<!doctype html>
<html lang="fr">
    <?php 
        $titre = 'ReSoC - Mes abonné.e.s';
        include './Assets/includes/header.php';
        showHead($titre);
    ?>

    <body>
        <?php
        render_header();
        echo "FOLLOWERS"; ?>
    
        <div id="wrapper">          
            <?php 
                
                $presentation = "Sur cette page vous trouverez la liste des personnes qui suivent les messages de l'utilisatrice n°".intval($_GET['user_id']);
                aside($presentation, $userImage, $userImageAlt);
            ?>
            <main class='contacts'>
                <?php
                    // Etape 1: récupérer l'id de l'utilisateur
                    $userId = intval($_GET['user_id']);

                    // Etape 2: se connecter à la base de donnée
                    include './Assets/includes/sql_connect.php'; 
                    connect();

                    // Etape 3: récupérer le nom de l'utilisateur
                    $laQuestionEnSql = "
                        SELECT users.*
                        FROM followers
                        LEFT JOIN users ON users.id=followers.following_user_id
                        WHERE followers.followed_user_id='$userId'
                        GROUP BY users.id
                        ";
                        $lesInformations = sql_query($laQuestionEnSql);

                    // Etape 4
                    if (!$lesInformations)
                    {
                        echo "<article>";
                        echo("Échec de la requete : " . $mysqli->error);
                        echo("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                        exit();
                    }

                    include './Assets/includes/generated_url.php';

                    while ($follower = $lesInformations->fetch_assoc())
                    { 
                        ?>
                        <article>

                            <img src="./Assets/Images/user.jpg" alt="blason"/>
                            <h3><a href="<?php echo $wallUrl ?>?user_id=<?php echo ($follower['id']) ?>">
                            <?php echo $follower['alias']?></a></h3>

                            <p>id : <?php echo $follower['id']?></p>
                        </article>
                        <?php
                    }
                ?>
            </main>
        </div>
    </body>
</html>
