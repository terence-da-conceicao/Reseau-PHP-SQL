<!doctype html>
<html lang="fr">
    <?php 
        $titre = 'Messages par mots-clés';
        include './Assets/includes/header.php';
        showHead($titre);
    ?>

    <body>
    <?php
        render_header();
        echo "TAGS"; ?>
    
        <div id="wrapper">
            <?php
            /**
             * Cette page est similaire à wall.php ou feed.php 
             * mais elle porte sur les mots-clés (tags)
             */
            /**
             * Etape 1: Le mur concerne un mot-clé en particulier
             */
            $tagId = intval($_GET['tag_id']);
            ?>
            <?php
            /**
             * Etape 2: se connecter à la base de donnée
             */
            include './Assets/includes/sql_connect.php';
            connect();
            ?>

            <aside>
                <?php
                // Etape 3: récupérer le nom du mot-clé
                $laQuestionEnSql = "SELECT * FROM tags WHERE id= '$tagId' ";
                $lesInformations = sql_query($laQuestionEnSql);
                $tag = $lesInformations->fetch_assoc();
                ?>

                <img src="./Assets/Images/user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                        <p>Sur cette page vous trouverez les derniers messages comportant
                        le mot-clé <?php echo $tag['label'] ?>
                        (n° <?php echo $tagId ?>)
                        </p>

                </section>
            </aside>
            <main>
                <?php
                // Etape 3: récupérer tous les messages avec un mot clé donné
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    users.alias as author_name,
                    users.id,  
                    count(likes.id) as like_number,  
                    tags.label AS onetag,
                    GROUP_CONCAT(DISTINCT tags.label ORDER BY tags.id) AS taglist,
                    GROUP_CONCAT(DISTINCT tags.id) AS idlist 

                    FROM posts_tags as filter

                    JOIN posts ON posts.id=filter.post_id
                    JOIN users ON users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE filter.tag_id = '$tagId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                $lesInformations = sql_query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                

                // Etape 4: Parcourir les messsages et remplir le HTML
                while ($post = $lesInformations->fetch_assoc())
                {
                include './Assets/includes/generated_url.php';
                $tag_list = explode(",", $post['taglist']);
                $id_list = explode(",", $post['idlist']);
                $tag_id_list = array_combine ($id_list, $tag_list);
                ?>

                    <article>
                        <h3>
                            <time datetime='2020-02-01 11:12:13' ><?php echo $post['created'] ?></time>
                        </h3>
                            <address> <a href="<?php echo $wallUrl ?>?user_id=<?php echo $post['id'] ?>">
                            par <?php echo $post['author_name'] ?> </a>
                            </address>
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