<!doctype html>
<html lang="fr">
    <?php 
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
            <aside>
                <img src="./Assets/Images/user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>L'utilisatrice n° <?php echo intval($_GET['user_id']) ?>
                        suit les personnes suivantes : 
                    </p>

                </section>
            </aside>
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

                while ($followed = $lesInformations->fetch_assoc())
                { 
                ?>
                <article>
                    <img src="./Assets/Images/user.jpg" alt="blason"/>
                    <h3><?php echo $followed['alias']?></h3>
                    <p>id : <?php echo $followed['id']?></p>                    
                </article>
                <?php
                }
                ?>

            </main>
        </div>
    </body>
</html>
