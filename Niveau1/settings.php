<!doctype html>
<html lang="fr">

    <?php 
        if (isset($_GET['user_id'])) {
            $userId = intval($_GET['user_id']);
        } else {
            echo "ID d'utilisatrice inconnu";
        } 
    ?>

    <head>
        <meta charset="utf-8">
        <title>ReSoC - Paramètres</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>

    <body>
        <?php include 'header.php' ?>;
        <?php include 'sql_connect.php'; 
        connect() ?>;

            <div id="wrapper" class='profile'>
                <aside>
                    <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                    <section>
                        <h3>Présentation</h3>
                        <p>Sur cette page vous trouverez les informations de l'utilisatrice
                            n° <?php echo intval($_GET['user_id']) ?></p>
                    </section>
                </aside>
                <main>

                <?php

                //Etape 1: trouver l'id

                // Etape 2: se connecter à la base de donnée

                // Etape 3: récupérer le nom de l'utilisateur

                $laQuestionEnSql = "
                    SELECT users.*, 
                    count(DISTINCT posts.id) as totalpost, 
                    count(DISTINCT given.post_id) as totalgiven, 
                    count(DISTINCT received.user_id) as totalreceived 
                    FROM users 
                    LEFT JOIN posts ON posts.user_id=users.id 
                    LEFT JOIN likes as given ON given.user_id=users.id 
                    LEFT JOIN likes as received ON received.post_id=posts.id 
                    WHERE users.id = '$userId' 
                    GROUP BY users.id
                    ";
                
                $lesInformations = sql_query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();

                ?> 

                <!-- Etape 4  -->
                              
                <article class='parameters'>
                    <h3>Mes paramètres</h3>
                    <dl>
                        <dt>Pseudo</dt>
                        <dd><?php echo $user['alias'] ?></dd>
                        <dt>Email</dt>
                        <dd><?php echo $user['email'] ?></dd>
                        <dt>Nombre de message</dt>
                        <dd><?php echo $user['totalpost'] ?></dd>
                        <dt>Nombre de "J'aime" donnés </dt>
                        <dd><?php echo $user['totalgiven'] ?></dd>
                        <dt>Nombre de "J'aime" reçus</dt>
                        <dd><?php echo $user['totalreceived'] ?></dd>
                    </dl>
                </article>
            </main>
        </div>
    </body>
</html>
