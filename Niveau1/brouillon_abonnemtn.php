<!--

Etape 0 : L'utilisateur est connecté, il s'appelle Jean, son
id est n°2, $_SESSION['user_id'] contient son id (2)

On déclare $abonne_id = $_SESSION['user_id']
On déclare $userId = $_GET['user_id]

On est sur le mur de Valérie, son id est n°5, donc 
url wall.php?user_id=5  et son id est dans $_GET['user_id']



Etape 1 : faire un formulaire qui comporte
un id (pour l'associer à une action.une fonction etc),
un name et une value (c'est ce qui va aller dans $_POST)
et une method 'post'

Exemple : 
<form id='abonnement' name='???' value='???' method='post'/>

Etape 2 : 
Quand l'utilisateur connecté (Jean n°2) clique sur s'abonner, 
ça fait une requête SQL qui vérifie s'il est abonné à la personne (Valérie n°5).
Il faut selectionner tous les abonnements de Jean.

$InstructionSQL =
"SELECT followers_user_id 
FROM followers
WHERE following_user_id = '$abonne_id';";

Récupérer avec fetch query machin chouette

ETape 3 :
Faire la vérification.
Faire une condition : 

if (!$InstructionSQL) {
    echo error 
} else if {
    Vérification
VOUS VOYEZ LE PRINCIPE
Si dejà abo : 
echo "vous ete deja abo"
} else {
        INSERT INTO followers (id, followed_users_id, following_users_id)
        VALUES (NULL, $userID, $abonne_id)

        $creationnouvelabonne = query etc
}

if ($creationnouvelabonne) {
    echo "youpii t abo!!!!!"
} else {
    echo "abonnement échoué";
}




















-->