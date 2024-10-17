<!doctype html>
<html lang="fr">
    <?php 
        $titre = 'ReSoC - Actualités';
        include './Assets/includes/header.php';
        showHead($titre);
    ?>

    <body>
    <?php
        render_header(); 
        //echo "NEWS";?>

        <div id="wrapper">
            <?php 
                $presentation = "Sur cette page vous trouverez les derniers messages de toutes les utilisatrices du site.";
                aside($presentation, $userImage, $userImageAlt); ?>
            
            <main>
                <?php
                    include './Assets/includes/sql_connect.php';
                    connect();

                    // Poser une question à la base de donnée et récupérer ses informations + vérification
                    $laQuestionEnSql = "
                        SELECT posts.content,
                        posts.created,
                        users.alias as author_name,  
                        users.id,
                        count(likes.id) as like_number,  
                        GROUP_CONCAT(DISTINCT tags.label) AS taglist,
                        GROUP_CONCAT(DISTINCT tags.id  ORDER BY tags.label) AS idlist 
                        FROM posts
                        JOIN users ON  users.id=posts.user_id
                        LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                        LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                        LEFT JOIN likes      ON likes.post_id  = posts.id 
                        GROUP BY posts.id
                        ORDER BY posts.created DESC  
                        LIMIT 5
                        ";
                    $lesInformations = sql_query($laQuestionEnSql);
                
                if (!$lesInformations) // =! présence de $lesInformations (si la requête (query) ne réussit pas et que $lesInformations ne se forme pas)
                {
                    echo "<article>";
                    echo("Échec de la requete : " . $mysqli->error);
                    echo("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                    exit();
                }

                

                // Parcourir ces données et les ranger bien comme il faut dans du html
                while ($post = $lesInformations->fetch_assoc())
                {
                    include './Assets/includes/generated_url.php';
                    $tag_list = explode(",", $post['taglist']);
                    $id_list = explode(",", $post['idlist']);
                    $tag_id_list = array_combine ($id_list, $tag_list);

                    
                    ?>
                    <article>
                        <h3>
                            <time><?php echo $post['created'] ?></time>
                        </h3>
                            <address> <a href="<?php echo $wallUrl ?>?user_id=<?php echo ($post['id']) ?>">
                            <?php echo $post['author_name'] ?> </a> </address>
                        <div>
                            <p><?php echo $post['content'] ?></p>
                        </div>
                        <footer>
                            <small>♥ <?php echo $post['like_number'] ?></small>
                            <?php 
                            foreach ($tag_id_list as $key => $data)
                            { ?>
                            <a href="./tags.php?tag_id=<?php echo $key?>">#<?php echo $data?></a>
                            <?php } ?>
                        </footer>
                    </article>
                <?php
                }
                ?>

            </main>
        </div>
    </body>
</html>
