<!doctype html>
<html lang="fr">
    <?php 
        $titre = 'ReSoC - Flux';
        include 'header.php';
        showHead($titre);
    ?>
    <body>
        <?php
        render_header();
        echo "FEED"; ?>
        
        
        <div id="wrapper">
            <?php $userId = intval($_GET['user_id']); ?>

            <?php include 'sql_connect.php';
            connect(); ?>
            
            <aside>
                <?php
                // Etape 3: récupérer le nom de l'utilisateur
                    $laQuestionEnSql = "SELECT * FROM `users` WHERE id= '$userId' ";
                    $lesInformations = sql_query($laQuestionEnSql);
                    $user = $lesInformations->fetch_assoc();             
                ?>

                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                        <p>Sur cette page vous trouverez tous les message des utilisatrices
                        auxquel est abonnée l'utilisatrice <a href="./wall.php?user_id=<?php echo ($user['id']) ?>"> <?php echo $user ['alias'] ?> </a>
                        (n° <?php echo $userId ?>)</p>
                </section>
            </aside>

            <main>
                <?php
                // Etape 3: récupérer tous les messages des abonnements
                
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    users.alias as author_name,
                    users.id,
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM followers 
                    JOIN users ON users.id=followers.followed_user_id
                    JOIN posts ON posts.user_id=users.id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE followers.following_user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                $lesInformations = sql_query($laQuestionEnSql);;
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                // Etape 4: Parcourir les messsages et remplir le HTML
                 
                while ($user = $lesInformations->fetch_assoc())
                {
                ?>
                                
                <article>
                    <h3>
                        <time datetime='2020-02-01 11:12:13' ><?php echo $user['created'] ?></time>
                    </h3>
                        <address>
                        <a href="./wall.php?user_id=<?php echo ($user['id']) ?>">    
                        <?php echo $user['author_name'] ?></address>
                    <div>
                        <p><?php echo $user['content'] ?></p>
                    </div>                                            
                    <footer>
                        <small>♥ <?php echo $user['like_number'] ?></small>
                        <a href=""><?php echo $user['taglist'] ?></a>
                    </footer>
                </article>
                <?php
                }
                ?>

            </main>
        </div>
    </body>
</html>
