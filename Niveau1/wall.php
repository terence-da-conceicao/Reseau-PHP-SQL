<!doctype html>
<html lang="fr">
    <?php 
        $titre = 'Mur';
        include './Assets/includes/header.php';
        showHead($titre);
    ?>
    <body>
    <?php
        render_header();
        echo "WALL"; ?>

        <div id="wrapper">
            <?php
            //Etape 1: Récupérer l'id de l'utilisateur
             
            $userId = intval($_GET['user_id']);
            ?>
            
            <?php
            // Etape 2: se connecter à la base de donnée
            include './Assets/includes/sql_connect.php';
            connect();
            ?>

            <aside>
                <?php
                // Etape 3: récupérer le nom de l'utilisateur              
                $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                ?>

                <img src="./Assets/Images/user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                        <p>Sur cette page vous trouverez tous les message de l'utilisatrice : <?php echo $user['alias'] ?>
                        (n° <?php echo $userId ?>)
                        </p>
                </section>
            </aside>
            <main> 
                <form method=POST>
                    <label for="new_post">Nouveau message</label><br>
                    <textarea name="new_post" rows=7 columns=90 label="new_message">Rédigez un nouveau message</textarea><br>
                    <label for="choose_tags">Ajoutez des mots-clés</label><br>
                    <select name="choose_tags" multiple>
                        <option value="">--Choisissez un ou plusieurs tags--</option>
                        <?php 
                        $query_tags = "
                        SELECT * FROM tags
                        ";
                        $result = $mysqli -> query ($query_tags);
                        while ($options = mysqli_fetch_array($result)) {
                            echo "<option value='" . $options['id'] . "'>" . $options['label'] . "</option>";
                        }
                        ?>
                    </select><br>
                    <input type="submit" value="Publiez" /><br>
                </form>
                <?php
                // Etape 4: récupérer tous les messages de l'utilisatrice
                $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias as author_name, 
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label ORDER BY tags.id) AS taglist,
                    GROUP_CONCAT(DISTINCT tags.id) AS idlist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                $lesInformations = sql_query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                // Etape 5: Parcourir les messsages et remplir le HTML
                while ($post = $lesInformations->fetch_assoc())

                {
                include './Assets/includes/generated_url.php';
                $tag_list = explode(",", $post['taglist']);
                $id_list = explode(",", $post['idlist']);
                $tag_id_list = array_combine ($id_list, $tag_list);
                //var_dump ($tag_id_list);

                ?>

                    <article>
                        <h3>
                            <time><?php echo $post['created'] ?></time>
                        </h3>
                            <address> <a href="<?php echo $wallUrl ?>?user_id=<?php echo $userId ?>"> 
                            par <?php echo $user['alias'] ?></a>
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
                            <?php
                            }
                            ?>
                        </footer>
                    
                    </article>
                <?php
                }
                var_dump ($post);
                die();
                ?>

            </main>
        </div>
    </body>
</html>
