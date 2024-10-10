
<?php 
function render_header() {
    echo
    '<!doctype html>
        <html lang="fr">
            <head>
                <meta charset="utf-8">
                <title>ReSoC - Administration</title> 
                <meta name="author" content="Julien Falconnet">
                <link rel="stylesheet" href="style.css"/>
            </head>
            <body>
                <header>
                    <img src="resoc.jpg" alt="Logo de notre réseau social"/>
                    <nav id="menu">
                        <a href="news.php">Actualités</a>
                        <a href="wall.php?user_id=5">Mur</a>
                        <a href="feed.php?user_id=5">Flux</a>
                        <a href="tags.php?tag_id=1">Mots-clés</a>
                    </nav>
                    <nav id="user">
                        <a href="#">Profil</a>
                        <ul>
                            <li><a href="settings.php?user_id=5">Paramètres</a></li>
                            <li><a href="followers.php?user_id=5">Mes Abonné.e.s/Followers</a></li>
                            <li><a href="subscriptions.php?user_id=5">Mes abonnements</a></li>
                        </ul>
                    </nav>
                </header>
            </body>
        </html>';
}
?>


<?php 
function bienvenue() {
    echo
        '<aside>
            <h2>Présentation</h2>
            <p>Bienvenue sur notre réseau social.</p>
        </aside>';
}
?>