<?php 
       $mysqli = new mysqli("localhost", "root", "", "socialnetwork");

    function connect() {
        global $mysqli;
        //var_dump($mysqli);
        if ($mysqli->connect_errno)
        {
            echo("Échec de la connexion : " . $mysqli->connect_error);
            exit();
        }
    }
?>


<?php function sql_query($question) {
    global $mysqli;
    $lesInformations = $mysqli->query($question);
        if ( ! $lesInformations)
        {
            echo("Échec de la requete : " . $mysqli->error);
        } else {
            return $lesInformations;
        }
    }
?>