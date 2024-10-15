<!doctype html>
<html lang="fr">
    <?php 
        $userId = intval($_GET['user_id']);

        $titre = 'ReSoC - Abonnements';
        include './Assets/includes/header.php';
        showHead($titre);
    ?>

    <body>
    <?php
        render_header(); 
        echo "SUBSCRIPTIONS"; ?>
    
<!--connexion sql_connect? -->

        <div id="wrapper">
            <?php
                $presentation = "L'utilisatrice n°".$userId." suit les personnes suivantes :";
                aside($presentation, $userImage, $userImageAlt); ?>

            <main class='contacts'>

                <?php
                    include './Assets/includes/sql_connect.php';
                    connect();

                    $laQuestionEnSql = "
                        SELECT users.* 
                        FROM followers 
                        LEFT JOIN users ON users.id=followers.followed_user_id 
                        WHERE followers.following_user_id='$userId'
                        GROUP BY users.id
                        ";
                    $lesInformations = sql_query($laQuestionEnSql);
                // Etape 4: à vous de jouer
                //@todo: faire la boucle while de parcours des abonnés et mettre les bonnes valeurs ci dessous
                if (!$lesInformations)
                {
                    echo "<article>";
                    echo("Échec de la requete : " . $mysqli->error);
                    echo("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                    exit();
                }

                include './Assets/includes/generated_url.php';

                while ($followed = $lesInformations->fetch_assoc())
                { 
                ?>
                <article>

                    <img src="./Assets/Images/user.jpg" alt="blason"/>
                    <h3> <a href="<?php echo $wallUrl ?>?user_id=<?php echo ($followed['id']) ?>">
                    <?php echo $followed['alias']?></a></h3>

                    <p>id : <?php echo $followed['id']?></p>                    
                </article>
                <?php
                }
                ?>

            </main>
        </div>
    </body>
</html>
