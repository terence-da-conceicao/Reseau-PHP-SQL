<?= session_start(); 
print_r($_SESSION['user_id']); 
$session_user_id = $_SESSION['user_id'];
?>

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
        //echo "WALL"; ?>

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
                        <form method="POST">
                            <button type="submit" name="follow">S'abonner</button>
                        </form>

                        <?php
                    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['follow'])) {
                    //         // Vérifiez si l'utilisateur suit déjà
                    //         $stmt = $mysqli->prepare("SELECT * FROM followers WHERE followed_user_id = ? AND following_user_id = ?");
                    //         $stmt->bind_param("ii", $userId, $session_user_id);
                    //         $stmt->execute();
                    //         $result_follow = $stmt->get_result();

                    //         if ($result_follow->num_rows > 0) {
                    //         echo "Vous suivez déjà cette personne.";
                    //     } else {
                    //         // Ajoutez l'abonnement
                    //         $stmt = $mysqli->prepare("INSERT INTO followers (followed_user_id, following_user_id) VALUES (?, ?)");
                    //         $stmt->bind_param("ii", $userId, $session_user_id);
                    //         if ($stmt->execute()) {
                    //             echo "Vous suivez maintenant cet utilisateur.";
                    //         } else {
                    //             echo "Échec de l'abonnement : " . $stmt->error;
                    //         }
                    //     }
                    // }
                    ?>

                </section>
            </aside>
            <main> 
                <!-- formulaire pour nouveau message + tableau tags -->
                <form method=POST style="line-height: 1.5">
                    <label for="new_post">Nouveau message</label><br>
                    <textarea name="new_post" rows=8 style="width: 55%; font-family: Arial" label="new_message">Rédigez un nouveau message</textarea><br>
                    <label for="choose_tags">Ajoutez des mots-clés</label><br>
                    <select name="choose_tags[]" multiple>
                        <option value="">--choisissez un ou plusieurs tags--</option>
                        <?php 
                        $query_tags = "
                        SELECT * FROM tags
                        ";
                        $result_post = $mysqli -> query ($query_tags);
                        while ($options = mysqli_fetch_array($result_post)) {
                            echo "<option value='" . $options['id'] . "'>" . $options['label'] . "</option>";
                        }
                        ?>
                    </select><br>
                    <input type="submit" value="Publiez" /><br>
                </form>
                <?php
                    //ajout du nouveau message à la BDD
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $new_post = $mysqli->real_escape_string($_POST['new_post']);
                        $add_tags = $_POST['choose_tags'];

                        $new_post_sql = "
                        INSERT INTO posts (id, user_id, content, created) 
                        VALUES (NULL, '$userId', '$new_post', NOW());
                        ";
                    //ajout des tags si message créé
                        if ($mysqli->query($new_post_sql)) {
                            $post_id = $mysqli->insert_id;
                            if (!empty($add_tags)) {
                                foreach ($add_tags as $tag_id) {
                                    $tag_id = intval($tag_id); // Sécurise l'ID du tag
                                    $insert_tag_sql = "
                                    INSERT INTO posts_tags (post_id, tag_id)
                                    VALUES ('$post_id', '$tag_id');
                                    ";
                                    $mysqli->query($insert_tag_sql);
                                }
                            }
                            echo "Le message a été publié avec succès !";
                            } else {
                            echo "Échec de l'insertion du message : " . $mysqli->error;
                        }
                    }

                // Etape 4: récupérer tous les messages de l'utilisatrice
                $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias as author_name, 
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label ) AS taglist,
                    GROUP_CONCAT(DISTINCT tags.id ORDER BY tags.label) AS idlist 
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
                
                ?>

                    <article>
                        <h3>
                            <time><?php echo $post['created'] ?></time>
                        </h3>
                            
                            <address><a href="<?php echo $wallUrl ?>?user_id=<?php echo $userId ?>"> par <?php echo $user['alias'] ?></a></address>
                        <div>
                            <p><?php echo $post['content'] ?></p>
                        </div>                                            
                        <footer>
                            <small>♥ <?php echo $post['like_number'] ?></small>

                            <?php foreach ($tag_id_list as $key => $data): ?>
                                <a href="./tags.php?tag_id=<?= $key ?>">#<?= $data ?></a>
                            <?php endforeach  ?>
                        </footer>
                    
                    </article>
                <?php
                }
                ?>

            </main>
        </div>
    </body>
</html>
