<!--<php session_start(); ?>"-->

<!doctype html>
<html lang="fr">
    <?php 
        $titre = 'ReSoC - Login';
        include './Assets/includes/header.php';
        showHead($titre); ?>

    <body>
        <?php 
            render_header(); 
            echo "LOGIN"; ?>

        <div id="wrapper" >
            <?php bienvenue(); ?>
            
            <main>
                <article>
                    <h2>Connexion</h2>

                    <?php
                    $enCoursDeTraitement = isset($_POST['email']);
                        if ($enCoursDeTraitement) {
                            $emailAVerifier = $_POST['email'];
                            $passwdAVerifier = $_POST['motpasse'];

                            $mysqli = new mysqli("localhost", "root", "", "socialnetwork");
                            
                            $emailAVerifier = $mysqli->real_escape_string($emailAVerifier);
                            $passwdAVerifier = $mysqli->real_escape_string($passwdAVerifier);
                            
                            //$passwdAVerifier = md5($passwdAVerifier);
                            //tous les mdp sont "test" ,cryptés en 098f6bcd4621d373cade4e832627b4f6
                            //le mdp de tata est "bonjour", crypté en f02368945726d5fc2a14eb576f7276c0

                            $lInstructionSql = "
                            SELECT * 
                            FROM users 
                            WHERE email 
                            LIKE '$emailAVerifier' ";
                            

                            $res = $mysqli->query($lInstructionSql);
                            $user = $res->fetch_assoc();
                            $connexionPassword = false;
                            $connexionEmail = false;
                     
                            if (empty($emailAVerifier)) {
                                echo "Entrez un e-mail. ";
                            } else if (!$user OR ($user['email']) != $emailAVerifier) {
                                echo "Mauvais email. ";
                            } else {
                                $connexionEmail = true;
                            }


                            if ($connexionEmail === true) { 
                                if (empty($passwdAVerifier)) {
                                    echo "Entrez un mot de passe. ";
                                } else if (!$user OR ($user['password'])!=$passwdAVerifier) {
                                    /*pour récupérer le mdp dans la bdd, il faut récupérer l'email.*/ 
                                    /*Si le champ email n'est pas rempli, on dit juste de rentrer un mail */
                                    echo "Mauvais mot de passe. ";
                                } else {
                                    $connexionPassword = true;
                                }
                            }

                            if ($connexionEmail === true && $connexionPassword === true) {
                                echo "Votre connexion est un succès, ".$user['alias'].".";
                                /*$_SESSION['connected_id']=$user['id'];*/
                            } else {
                                echo "<br>Connexion échouée";
                            }

                        }
                    ?>                     
                    <form action="login.php" method="post">
                        <input type='hidden'name='form_id' value='achanger'>
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
