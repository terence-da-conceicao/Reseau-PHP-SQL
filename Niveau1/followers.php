<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mes abonnés </title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
    <?php include 'header.php' ?>;
        <div id="wrapper">          
            <aside>
                <img src = "user.jpg" alt = "Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez la liste des personnes qui
                        suivent les messages de l'utilisatrice
                        n° <?php echo intval($_GET['user_id']) ?></p>
                </section>
            </aside>

            <main class='contacts'>
                <?php
                    // Etape 1: récupérer l'id de l'utilisateur
                    $userId = intval($_GET['user_id']);

                    // Etape 2: se connecter à la base de donnée
                    include 'sql_connect.php'; connect();

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

                    while ($follower = $lesInformations->fetch_assoc())
                    { 
                        ?>
                        <article>
                            <img src="user.jpg" alt="blason"/>
                            <h3><?php echo $follower['alias']?></h3>
                            <p>id : <?php echo $follower['id']?></p>
                        </article>
                        <?php
                    }
                ?>
            </main>
        </div>
    </body>
</html>
