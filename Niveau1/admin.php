<!doctype html>
<html lang="fr">
    <?php 
        $titre = 'ReSoC - Administration';
        include './Assets/includes/header.php';
        showHead($titre);
    ?>

    <body>
        <?php
        render_header();
        //echo "ADMIN"; ?>
        
        <?php include './Assets/includes/sql_connect.php';
        connect(); ?>

        <div id="wrapper" class='admin'>
            <aside>
                <h2>Mots-clés</h2>
                <?php
                /*
                 * Etape 2 : trouver tous les mots clés + vérification
                 */
                $laQuestionEnSql = "SELECT * FROM `tags` LIMIT 50";
                $lesInformations = sql_query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                    exit();
                }

                // Etape 3 : Afficher les mots clés 
                while ($tag = $lesInformations->fetch_assoc())
                {
                    
                    ?>
                    <article>
                        <h3><a href="./tags.php?tag_id=<?php echo $tag['id']?>"> <?php echo $tag['label'] ?> </a></h3>
                        <p>id:<?php echo $tag['id'] ?> </p>
                        <nav>
                            <a href="tags.php?tag_id=<?php echo $tag['id'] ?>">Messages</a>
                        </nav>
                    </article>
                <?php 
                } 
                ?>

            </aside>
                <main>
                    <h2>Utilisatrices</h2>
                    <?php
                // Etape 4 : trouver tous les mots clés + vérification
                
                $laQuestionEnSql = "SELECT * FROM `users` LIMIT 50";
                $lesInformations = sql_query($laQuestionEnSql);
                
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                    exit();
                }

                // Etape 5 : @todo : Afficher les utilisatrices 
                while ($users = $lesInformations->fetch_assoc())
                {
                    include './Assets/includes/generated_url.php';
                    
                    ?>
                    <article>
                            <h3> <a href="<?php echo $wallUrl ?>?user_id=<?php echo ($users['id']) ?>">
                            <?php echo $users['alias'] ?> </a>
                            </h3>
                            <p>id: <?php echo $users['id'] ?></p>
                            <nav>
                                <a href="wall.php?user_id=<?php echo $users['id'] ?>">Mur</a>
                                <a href="feed.php?user_id=<?php echo $users['id'] ?>">Flux</a>
                                <a href="settings.php?user_id=<?php echo $users['id'] ?>">Paramètres</a>
                                <a href="followers.php?user_id=<?php echo $users['id'] ?>">Abonné.e.s/Followers</a>
                                <a href="subscriptions.php?user_id=<?php echo $users['id'] ?>">Abonnements</a>
                            </nav>
                    </article>
                <?php 
                }
                ?>
            </main>
        </div>
    </body>
</html>
