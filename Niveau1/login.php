<?php
session_start();
?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Flux</title>         
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
    <?php include 'header.php';
    render_header(); 
    echo "LOGIN"; ?>

        <div id="wrapper" >
    <?php bienvenue(); ?>
            <main>
                <article>
                    <h2>Connexion</h2>
                    <?php
                    
                    

                    $enCoursDeTraitement = isset($_POST['email']);
                    if ($enCoursDeTraitement)
                    {
                        $emailAVerifier = $_POST['email'];
                        $passwdAVerifier = $_POST['motpasse'];

                        $mysqli = new mysqli("localhost", "root", "", "socialnetwork");
                        
                        $emailAVerifier = $mysqli->real_escape_string($emailAVerifier);
                        $passwdAVerifier = $mysqli->real_escape_string($passwdAVerifier);
                        
                        $passwdAVerifier = md5($passwdAVerifier);
                        //tous les mdp sont "test" ,cryptés en 098f6bcd4621d373cade4e832627b4f6
                        //le mdp de tata est "bonjour", crypté en f02368945726d5fc2a14eb576f7276c0

                        $lInstructionSql = "
                        SELECT * 
                        FROM users 
                        WHERE email 
                        LIKE '$emailAVerifier' ";
                        

                        $res = $mysqli->query($lInstructionSql);
                        $user = $res->fetch_assoc();
                        
                        if (!$user OR ($user['password']) != $passwdAVerifier)
                        {
                            echo "La connexion a échoué. ";
                            echo "     bdd: ";
                            echo
                            print_r($user['password']);
                            echo "    post: ";
                            print_r($passwdAVerifier);
                            

                            
                        } else
                        {
                            echo "Votre connexion est un succès : " . $user['alias'] . ".";
                            // Etape 7 : Se souvenir que l'utilisateur s'est connecté pour la suite
                            // documentation: https://www.php.net/manual/fr/session.examples.basic.php
                            $_SESSION['connected_id']=$user['id'];
                        }
                    }
                    ?>                     
                    <form action="login.php" method="post">
                        <!--<input type='hidden'name='form_id' value='achanger'>-->
                        <dl>
                            <dt><label for='email'>E-Mail</label></dt>
                            <dd><input type='email'name='email'></dd>
                            <dt><label for='motpasse'>Mot de passe</label></dt>
                            <dd><input type='password'name='motpasse'></dd>
                        </dl>
                        <input type='submit'>
                    </form>
                    <p>
                        Pas de compte?
                        <a href='registration.php'>Inscrivez-vous.</a>
                    </p>

                </article>
            </main>
        </div>
    </body>
</html>
