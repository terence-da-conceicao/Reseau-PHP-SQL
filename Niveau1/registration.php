<?php 
    setcookie('id_utilisateur', '152');
?>

<!doctype html>
<html lang="fr">
    <?php 
        $titre = 'ReSoC - Inscription';
        include './Assets/includes/header.php';
        showHead($titre); ?>

    <body>
        <?php
        render_header(); 
        //echo "REGISTRATION"; 
        include './Assets/includes/sql_connect.php'; ?>


        <div id="wrapper" >
        <?php bienvenue(); ?> 
                <main>
                    <article>
                        <h2>Inscription</h2>

                        <?php
    
                        $enCoursDeTraitement = isset($_POST['email']);
                        $showDispoEmail = false;
                        $showDispoAlias = false;
                        $traitement = false;
                        $insertion = false;
                       
                        if ($enCoursDeTraitement) {

                            $new_alias = $_POST['alias'];
                            $new_email = $_POST['email'];
                            $new_passwd = $_POST['motpasse'];

                            $mysqli = new mysqli("localhost", "root", "", "socialnetwork");

                            $new_email = $mysqli->real_escape_string($new_email);
                            $new_alias = $mysqli->real_escape_string($new_alias);
                            $new_passwd = $mysqli->real_escape_string($new_passwd);
                            $new_passwd = md5($new_passwd);

                            //Vérifier si le nom d'utilisateur est déjà pris ou si l'email déjà présent dans la BDD)
                            //On vérifie d'abord l'email, afin que peu importe que le pseudo soit dispo ou pas, on indique d'abord qu'un compte existe


                            //Requêter les emails
                            $RequeteCheckEmail = "
                            SELECT email
                            FROM users
                            WHERE users.email = '$new_email'";
                            $QueryCheckedEmail = $mysqli->query($RequeteCheckEmail);
                            $checkedEmail = $QueryCheckedEmail->fetch_assoc();
                            

                            //Requêter les noms d'utilisateurs
                            $RequeteCheckAlias = "
                            SELECT alias
                            FROM users
                            WHERE users.alias = '$new_alias'";
                            $QueryCheckedAlias = $mysqli->query($RequeteCheckAlias);
                            $checkedAlias = $QueryCheckedAlias->fetch_assoc();


                            //Vérifier si l'email est déjà enregistré
                            if ($checkedEmail != NULL) {
                                //le tableau des emails correspondants, renvoyé, n'est pas vide : 
                                echo "<br>";
                                echo "Un compte existe déjà sous cette adresse mail";
                                echo "<br>";
                            } else {
                                $showDispoEmail = true;
                                // L'email est disponible. Alors on continue l'inscription et on gère le pseudo

                                if ($checkedAlias != NULL && $showDispoEmail) {
                                    echo "<br>";
                                    echo "Pseudo déjà pris";
                                    echo "<br>";
                                } else {
                                    $showDispoAlias = true;
                                }
                            }

                        //On indique que les conditions d'inscriptions sont ok
                        if ($showDispoEmail === true && $showDispoAlias === true) {
                            $traitement = true;
                        } 

                        //if traitement est true/ les conditions sont ok, insérer dans la BDD
                        if ($traitement) {
                            $lInstructionSql = "
                            INSERT INTO users (id, email, password, alias)
                            VALUES (NULL, '$new_email', '$new_passwd', '$new_alias')";

                            $insertion = $mysqli->query($lInstructionSql);
                            
                        }

                        //if insertion est true/si l'insertion s'est bien passée
                        if ($insertion) {
                            echo "Votre inscription est un succès, " .$new_alias.".";
                            echo "<div id=Connexion><a href='login.php'>Connectez-vous.</a></div>";
                        } else if ($traitement && !$insertion){
                            echo "L'inscription a échouée : " . $mysqli->error;
                        }
                    }
                           

                            
                        
                        ?>                     
                        <form action="registration.php" method="post">
                        <input type='hidden'name='form_type' value='login'>
                        <dl>
                            <dt><label for='alias'>Pseudo</label></dt>
                            <dd><input required type='text'name='alias'></dd>
                            <dt><label for='email'>E-Mail</label></dt>
                            <dd><input required type='email'name='email'></dd>
                            <dt><label for='motpasse'>Mot de passe</label></dt>
                            <dd><input required type='password'name='motpasse'></dd>
                        </dl>
                        <input type='submit'>
                    </form>
                </article>
            </main>
        </div>
    </body>
</html>
