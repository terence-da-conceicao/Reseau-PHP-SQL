<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mes abonnements</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
    <?php include 'header.php' ?>;
    
        <div id="wrapper">
            <aside>
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
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
                include 'sql_connect.php';
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

                include 'generated_url.php';

                while ($followed = $lesInformations->fetch_assoc())
                { 
                ?>
                <article>
                    <img src="user.jpg" alt="blason"/>
                    <h3> <a href="<?php echo $wallUrl ?>?user_id=<?php echo ($followed['id']) ?>">
                    <?php echo $followed['alias']?> </a>
                    </h3>
                    <p>id : <?php echo $followed['id']?></p>                    
                </article>
                <?php
                }
                ?>

            </main>
        </div>
    </body>
</html>
