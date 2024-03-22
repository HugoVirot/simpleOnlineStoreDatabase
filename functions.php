<?php

// ****************** connexion à la base de données **********************

function getConnection()
{
    // try : je tente une connexion
    try {
        $db = new PDO(
            'mysql:host=localhost;dbname=online_store;charset=utf8', // infos : sgbd, nom base, adresse (host) + encodage
            'root', // pseudo utilisateur (root en local)
            '', // mot de passe (aucun en local)
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC)
        ); // options PDO : 1) affichage des erreurs / 2) récupération des données simplifiée

        // si ça ne marche pas : je mets fin au code php en affichant l'erreur
    } catch (Exception $erreur) { // je récupère l'erreur en paramètre
        die('Erreur : ' . $erreur->getMessage());  // je l'affiche et je mets fin au script
    }

    // je retourne la connexion stockée dans une variable
    return $db;
}



// **************************************************** ARTICLES ***********************************************************

// ****************** récupérer la liste des articles **********************

function getArticles()
{
    // je me connecte à la base de données
    $db = getConnection();

    // j'exécute une requête qui va récupérer tous les articles
    $results = $db->query('SELECT * FROM articles');

    // je récupère les résultats et je les renvoie grâce à return
    return $results->fetchAll();
}


// ****************** récupérer la liste des articles par gamme **********************

function getArticlesByRange($rangeId)

{
    $db = getConnection(); // connexion à la base

    // je prépare ma requête => SANS variable php dedans mais avec une variable SQL
    $query = $db->prepare('SELECT * FROM Articles WHERE id_gamme = ?');

    // je lance ma requête en indiquant à quoi correspond ma variable SQL
    $query->execute([$rangeId]);

    // je récupère les resultats 
    $result = $query->fetchAll();

    // je retourne les résultats
    return $result;
}


// ****************** récupérer un article à partir de son id **********************

function getArticleFromId($id)
{
    // je me connecte à la bdd
    $db = getConnection();

    // /!\ JAMAIS DE VARIABLE PHP DIRECTEMENT DANS UNE REQUETE /!\ (risque d'injection SQL)

    // je mets en place ma requête préparée
    $query = $db->prepare('SELECT * FROM Articles WHERE id = ?');

    // je l'exécute avec le bon paramètre
    $query->execute([$id]);

    // je retourne l'article sous forme de tableau associatif
    return $query->fetch();
}



// ****************** afficher l'ensemble des articles **********************

function showArticles($articles)
{
    foreach ($articles as $article) {
        echo "<div class=\"card col-md-5 col-lg-3 p-3 m-3\">
                <img class=\"card-img-top\" src=\"images/" . htmlspecialchars($article['image']) . "\" alt=\"Card image cap\">
                <div class=\"card-body\">
                    <h5 class=\"card-title fs-4 fw-bold\">" . htmlspecialchars($article['nom']) . "</h5>
                    <p class=\"card-text font-italic\">" . strip_tags($article['description']) . "</p>
                    <p class=\"card-text font-weight-light\">" . htmlspecialchars($article['prix']) . " €</p>
                    " . displayStock(htmlspecialchars($article['stock'])) . "
                    <form action=\"product.php\" method=\"post\">
                        <input type=\"hidden\" name=\"articleToDisplay\" value=\"" . htmlspecialchars($article['id']) . "\">
                        <input class=\"btn btn-lg btn-outline-dark mt-2\" type=\"submit\" value=\"Détails produit\">
                    </form>";
        if ($article['stock'] > 0) {
            echo "<form action=\"panier.php\" method=\"post\">
                        <input type=\"hidden\" name=\"chosenArticle\" value=\"" . htmlspecialchars($article['id']) . "\">
                        <input class=\"btn btn-lg btn-dark mt-2\" type=\"submit\" value=\"Ajouter au panier\">
                  </form>";
        }
        echo "</div>
              </div>";
    }
}


// ****************** afficher le détail d'un article sur la page produit **********************

function showArticleDetails($articleToDisplay)
{
  echo "<div class=\"container p-3 mt-md-5\">

            <div class=\"row\">

                <img src=\"images/" . htmlspecialchars($articleToDisplay['image']) . "\" class=\"col-md-6\">

                <div class=\"col-md-6 border border-dark bg-light mb-3\">
                
                    <div class=\"row pt-5 text-center align-items-center bg-light p-2 justify-content-center\">
                        <h2 class=\"fs-3 fw-bold\">" . htmlspecialchars($articleToDisplay['nom']) . "</h2>
                    </div>
                    <div class=\"row text-center font-italic align-items-center bg-light p-3 justify-content-center\">
                        <p class=\"fs-5\">" . htmlspecialchars($articleToDisplay['description']) . "<p>
                    </div>
                    <div class=\"row text-center align-items-center bg-light p-2 ml-5 mr-5 justify-content-center\">
                        <p>" . htmlspecialchars($articleToDisplay['description_detaillee']) . "<p>
                    </div>
                    <div class=\"row text-center font-weight-light align-items-center bg-light p-1 justify-content-center\">    
                        <h4>" . htmlspecialchars($articleToDisplay['prix']) . " €</h4>
                    </div>
                    <div class=\"container w-75 text-center align-items-center bg-light p-3 justify-content-center\">    
                    " . displayStock(htmlspecialchars($articleToDisplay['stock'])) . "
                    </div>
                    <div class=\"row pb-5 text-center align-items-center bg-light p-2 justify-content-center\">";
                 if ($articleToDisplay['stock'] > 0) {
                 echo "<form action=\"panier.php\" method=\"post\">
                            <input type=\"hidden\" name=\"chosenArticle\" value=\"" . htmlspecialchars($articleToDisplay['id']) . "\">
                            <input class=\"btn btn-lg btn-dark mt-2\" type=\"submit\" value=\"Ajouter au panier\">
                      </form>";
    }
    echo            "</div>
                 </div>
             </div>
        </div>";
}



// **************************************************** GAMMES ***********************************************************

// ****************** récupérer les gammes en bdd **********************

function getRanges()
{
    $db = getConnection();
    $query = $db->query('SELECT * FROM gammes');
    return $query->fetchAll();
}


// ****************** afficher les gammes **********************

function showRanges($ranges)
{
    foreach ($ranges as $range) {
        echo "<div class=\"container w-75 bg-dark text-white\">
                <div class=\"row p-3 justify-content-center\"><h4>" . htmlspecialchars($range['nom']) . "</h4></div>
              </div>";

        $rangeArticles = getArticlesByRange(intval($range['id']));

        echo "<div class=\"container p-5\">
                <div class=\"row text-center justify-content-center\">";

        showArticles($rangeArticles);

        echo "</div>
            </div>";
    }
}



// **************************************************** PANIER ***********************************************************

// ****************** ajouter un article au panier **********************

function addToCart($article)
{
    $isArticleAlreadyAdded = false;

    for ($i = 0; $i < count($_SESSION['cart']); $i++) {

        if ($_SESSION['cart'][$i]['id'] == $article['id']) {
            echo "<script> alert(\"Article déjà présent dans le panier !\");</script>";
            $isArticleAlreadyAdded = true;
        }
    }

    if (!$isArticleAlreadyAdded) {
        $article['quantity'] = 1;
        array_push($_SESSION['cart'], $article);
    }
}


// ****************** enlever un article au panier **********************

function removeToCart($articleId)
{

    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['id'] == $articleId) {
            array_splice($_SESSION['cart'], $i, 1);
        }
    }
    echo "<script> alert(\"Article retiré du panier\");</script>";
}


// ************ modifier la quantité d'un article dans le panier ***********

function updateQuantity()
{

    $modifiedArticleId = $_POST['modifiedArticleId'];

    $newQuantity = checkTypedQuantity($modifiedArticleId);

    if (is_numeric($newQuantity)) {

        for ($i = 0; $i < count($_SESSION['cart']); $i++) {

            if ($_SESSION['cart'][$i]['id'] == $modifiedArticleId) {
                $_SESSION['cart'][$i]['quantity'] = $newQuantity;
            }
        }
    }
}


// ************ afficher le contenu du panier ***********

function showCartContent($pageName)
{
    foreach ($_SESSION['cart'] as $chosenArticle) {
        echo "<div class=\"row text-center text-light align-items-center bg-dark p-3 justify-content-around mb-1\">
                        <img class=\"col-md-2\" style=\"width: 150px\" src=\"images/" . htmlspecialchars($chosenArticle['image']) . "\">
                        <p class=\"font-weight-bold col-md-2\">" . htmlspecialchars($chosenArticle['nom']) . "</p>
                        <p class=\"col-md-2\">" . htmlspecialchars($chosenArticle['description']) . "</p>
                        <p class=\"col-md-2\">" . htmlspecialchars($chosenArticle['prix']) . " €</p>

                        <form class=\"col-lg-3\" action=\"" . $pageName . "\" method=\"post\">
                            <div class=\"row pt-2\">
                            <input type=\"hidden\" name=\"modifiedArticleId\" value=\"" . htmlspecialchars($chosenArticle['id']) . "\">
                            <input class=\"col-2 offset-2\" type=\"text\" name=\"newQuantity\" value=\"" . htmlspecialchars($chosenArticle['quantity']) . "\">
                            <button type=\"submit\" class=\"col-5 offset-1 btn btn-light\">
                                Modifier quantité
                            </button>
                            </div>
                        </form>

                        <form class=\"col-lg-1\" action=\"" . $pageName . "\" method=\"post\">
                            <input type=\"hidden\" name=\"deletedArticle\" value=\"" . htmlspecialchars($chosenArticle['id']) . "\">
                            <button type=\"submit\" class=\"btn btn-dark\">
                                <i class=\"fas fa-ban\"></i>
                            </button>
                        </form>
                      </div>";
    }
}


// ************ afficher les boutons "vider panier" et "valider la commande"  ***********

function showButtons()
{
    // si le panier est défini et contient des articles 
    if ($_SESSION['cart']) {
        echo   "<form action=\"panier.php\" method=\"post\" class=\"row w-50 mx-auto justify-content-center text-dark font-weight-bold p-2\">
                 <input type=\"hidden\" name=\"emptyCart\" value=\"true\">
                <button type=\"submit\" class=\"btn btn-danger\">Vider le panier</button>
            </form>";
        if (isset($_SESSION['id'])) {
            echo "
            <a href=\"validation.php\">
                <div class=\"row w-50 justify-content-center mx-auto p-2\">
                    <button type=\"button\" class=\"btn btn-dark btn-lg\">Valider la commande</button>
                </div>
            </a>";
        }
    }
}


// ************vérifie que la quantité entrée est un nombre entre 1 et 10  ***********

function checkTypedQuantity($articleId)
{

    if (isset($_POST['newQuantity'])) {
        $typedQuantity = strip_tags($_POST['newQuantity']);
    } else {
        $typedQuantity = null;
    }

    $db = getConnection();
    $query = $db->prepare('SELECT stock FROM articles where id = ?');
    $query->execute([$articleId]);
    $result = $query->fetch();
    $quantityInStock = $result['stock'];

    if (is_numeric($typedQuantity) && $typedQuantity <= $quantityInStock && $typedQuantity >= 1 && $typedQuantity <= 10) {
        return $typedQuantity;
    } else {
        echo "<script> alert(\"Quantité saisie incorrecte !\");</script>";
    }
}


// ****************** calculer le total du panier **********************

function getCartTotal()
{
    $cartTotal = 0;

    if (isset($_SESSION['cart']) && count($_SESSION['cart']) !== 0) {

        foreach ($_SESSION['cart'] as $article) {
            $cartTotal += $article['prix'] * $article['quantity'];
        }
        return $cartTotal;
    } else {
        echo "Votre panier est vide !";
    }
}


// ****************** calculer le montant des frais de port (3€ / montre) **********************


function calculateShippingFees()
{
    $totalArticlesQuantity = 0;

    $cart = $_SESSION['cart'];

    for ($i = 0; $i < count($cart); $i++) {

        $totalArticlesQuantity += $cart[$i]['quantity'];
    }

    return  3 * $totalArticlesQuantity;
}


// ****************** calculer le montant total de la commande **********************

function calculateTotalPrice()
{
    // version avec frais de port "fixes" par articles
    // return getCartTotal() + calculateShippingFees();

    // version avec choix entre domicile et point-relais
    if ($_SESSION['delivery'] == "domicile") {
        return getCartTotal() + 10;
    } else {
        return getCartTotal() + 5;
    }
}


// ****************** vider le panier **********************


function emptyCart($showConfirmation)
{
    $_SESSION['cart'] = [];
    if ($showConfirmation) {
        echo "<script> alert(\"Le panier a bien été vidé\");</script>";
    }
}



// **************************************************** STOCKS ***********************************************************

// *************************************** afficher le stock d'un article ***************************************

function displayStock($stock)
{
    if ($stock >= 10) {
        return "<p class=\"w-75 card-text m-auto rounded p-1 text-white bg-success\">En stock</p>";
    } else if ($stock > 0) {
        return "<p class=\"w-75 card-text m-auto rounded p-1 bg-warning\">Plus que <b>" . $stock . "</b> en stock</p>";
    } else {
        return "<p class=\"w-75 card-text m-auto rounded p-1 text-light bg-danger\">Article épuisé</p>";
    }
}


// ******************************** déduire des stocks le nombre d'articles achetés ********************************

function decreaseStock($stock, $orderedQuantity, $id)
{
    $db = getConnection();

    $stock = intval($stock);
    $newStock = $stock - $orderedQuantity;

    if ($newStock < 0) {
        $newStock = 0;
    }

    $query = $db->prepare('UPDATE articles SET stock = :newStock WHERE id = :id');
    $query->execute(array(
        'newStock' => $newStock,
        'id' => $id
    ));
}



// ********************************** SAUVEGARDE COMMANDE *********************************************

function saveOrder($totalPrice)
{
    $db = getConnection();

    $query = $db->prepare('INSERT INTO commandes (id_client, numero, date_commande, prix, livraison, id_adresse) VALUES(:id_client, :numero, :date_commande, :prix, :livraison, :id_adresse)');

    $query->execute(array(
        'id_client' => $_SESSION['id'],
        'numero' => rand(1000000, 9999999),
        'date_commande' => date("d-m-Y h:i:s"),
        'prix' => $totalPrice,
        'livraison' => $_SESSION['delivery'],
        'id_adresse' => $_SESSION['deliveryAddress']['id']
    ));

    $id = $db->lastInsertId();

    $query = $db->prepare('INSERT INTO commande_articles (id_commande, id_article, quantite) VALUES(:id_commande, :id_article, :quantity)');

    foreach ($_SESSION['cart'] as $article) {

        $query->execute(array(
            'id_commande' => $id,
            'id_article' => $article['id'],
            'quantity' => $article['quantity']
        ));

        decreaseStock($article['stock'], $article['quantity'], $article['id']);
    }
}



// ******************************************* UTILISATEURS (INSCRIPTION ET CONNEXION) ****************************************************

// ***************** vérifier la présence de champs vides ************************

function checkEmptyFields()
{
    foreach ($_POST as $field) {
        if (empty($field)) {
            return true;
        }
    }
    return false;
}


// ***************** vérifier la longueur des champs ************************

function checkInputsLenght()
{

    if (strlen($_POST['prenom']) > 25 || strlen($_POST['prenom']) < 3) {
        return false;
    }

    if (strlen($_POST['nom']) > 25 || strlen($_POST['nom']) < 3) {
        return false;
    }

    if (strlen($_POST['email']) > 50 || strlen($_POST['email']) < 5) {
        return false;
    }

    if (strlen($_POST['addresse']) > 40 || strlen($_POST['addresse']) < 5) {
        return false;
    }

    if (strlen($_POST['code_postal']) !== 5) {
        return false;
    }

    if (strlen($_POST['ville']) > 25 || strlen($_POST['ville']) < 3) {
        return false;
    }

    return true;
}


// ***************** vérifier que le mot de passe réunit tous les critères demandés ************************

function checkPassword($password)
{
    // minimum 8 caractères et maximum 15, minimum 1 lettre, 1 chiffre et 1 caractère spécial
    $regex = "^(?=.*[0-9])(?=.*[a-zA-Z])(?=.*[@$!%*?/&])(?=\S+$).{8,15}$^";
    return preg_match($regex, $password);
}


// ***************** vérifier que l'e-mail est déjà utilisé ************************

function checkEmail()
{
    $db = getConnection();

    $query = $db->prepare("SELECT * FROM clients WHERE email = ?");
    $query->execute([$_POST['email']]);

    return $query->fetch();
}

// ***************** créer un utilisateur ************************

function createUser()
{
    $db = getConnection();  // on se connecte à la bdd

    if (checkEmptyFields()) {  // vérif si champs vides => message d'erreur si c'est le cas
        echo "<div class=\"container w-50 text-center p-3 mt-2 bg-danger text-white\"> Attention : un ou plusieurs champs vides !</div>";
    } else {

        if (!checkInputsLenght()) {  // vérif si longeur des champs correcte
            echo "<div class=\"container w-50 text-center p-3 mt-2 bg-danger text-white\"> Attention : longueur incorrecte d'un ou plusieurs champs !</div>";
        } else {

            if (checkEmail()) { // vérif si email déjà utilisé
                echo "<div class=\"container w-50 text-center p-3 mt-2 bg-danger text-white\"> Attention : e-mail déjà utilisé !</div>";
            } else {

                if (!checkPassword(strip_tags($_POST['password']))) { // vérif si mdp réunit les critères requis
                    echo "<div class=\"container w-50 text-center p-3 mt-2 bg-danger text-white\"> Attention : sécurité du mot de passe insuffisante !</div>";
                } else {

                    // hâchage du mot de passe => on le stocke dans une variable
                    $hashedPassword = password_hash(strip_tags($_POST['password']), PASSWORD_DEFAULT);

                    // insertion de l'utilisateur en base de données
                    $query = $db->prepare('INSERT INTO clients (nom, prenom, email, mot_de_passe) VALUES(:nom, :prenom, :email, :mot_de_passe)');
                    $query->execute([
                        'nom' =>  strip_tags($_POST['nom']),
                        'prenom' => strip_tags($_POST['prenom']),
                        'email' =>  strip_tags($_POST['email']),
                        'mot_de_passe' => $hashedPassword,
                    ]);

                    // récupération de l'id de l'utilisateur créé
                    $id = $db->lastInsertId();

                    // insertion de son adresse dans la table adresses
                    createAddress($id);

                    // on renvoie un message de succès 
                    echo '<script>alert(\'Le compte a bien été créé !\')</script>';
                }
            }
        }
    }
}


// ******************** créer une nouvelle adresse ****************

function createAddress($user_id)
{
    $db = getConnection();

    $query = $db->prepare('INSERT INTO adresses (id_client, adresse, code_postal, ville) VALUES(:id_client, :adresse, :code_postal, :ville)');
    $query->execute([
        'id_client' => $user_id,
        'adresse' => strip_tags($_POST['addresse']),
        'code_postal' =>  strip_tags($_POST['code_postal']),
        'ville' =>  strip_tags($_POST['ville']),
    ]);
}

// ***************** se connecter  ************************

function logIn()
{
    // connexion à la base de données
    $db = getConnection();

    // on nettoie l'email saisi avec strip tags, et on le stocke dans la variable $userEmail
    // pour le manipuler plus facilement
    $userEmail = strip_tags($_POST['email']);

    // on fait une requête SQL pour vérifier si le client existe, grâce à son email
    $user = checkEmail();

    // si la requête n'a rien récupéré => l'utilisateur n'existe pas
    if (!$user) {
        // on renvoie un message d'erreur en JS via la fonction alert() (volontairement imprécis pour ne pas aider les hackers)
        echo '<script>alert(\'E-mail ou mot de passe incorrect !\')</script>';
        // sinon => l'utilisateur existe
    } else {
        // on vérifie que son mot de passe saisi (en clair) correspond à son mot de passe en base de données (hashé)
        // pour cela, on utilise la fonction password_verify, qui compare un mdp en clair (1er paramètre) et un mdp hashé (2è p.)
        // elle renvoie true si les deux correspondent (le mpd hashé contient des informations qui permettent de faire ça)
        $isPasswordCorrect = password_verify($_POST['password'], $user['mot_de_passe']);

        // si les deux correspondent => mot de passe ok => on stocke les infos de l'utilisateur dans la session
        // on stocke aussi son adresse g^râce à la fonction setSessionAdress()
        // et on renvoie un message de succès
        if ($isPasswordCorrect) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['email'] = $userEmail;
            setSessionAddresses();
            header('Location: ./index.php');
            // sinon, on renvoie un message d'erreur (volontairement imprécis pour ne pas aider les hackers)
        } else {

            echo '<script>alert(\'E-mail ou mot de passe incorrect !\')</script>';
        }
    }
}


// ***************** se déconnecter  ************************

function logOut()
{
    $_SESSION = array();
    echo '<script>alert(\'Vous avez bien été déconnecté !\')</script>';
}




// **************************************************** ADRESSES ***********************************************************


// ***************** récupérer l'adresse du client en bdd ************************

function getUserAdresses()
{
    $db = getConnection();

    $query = $db->prepare('SELECT * FROM adresses WHERE id_client = ?');
    $query->execute([$_SESSION['id']]);
    return $query->fetchAll();
}


// ***************** définir / mettre à jour l'adresse de la session ************************

function setSessionAddresses()
{
    $_SESSION['adresses'] = getUserAdresses();
}


// ***************** afficher formulaire modification adresse  ************************

function displayAddresses($currentPage)
{
    $addresses = getUserAdresses();

    foreach ($addresses as $address) {
        echo "<div class=\"container p-5 w-50 border border-dark bg-light mb-4 p-4\">
            <form action=\"" . $currentPage . "\" method=\"post\">
                <input type=\"hidden\" name=\"addressChanged\">
                <input type=\"hidden\" name=\"addressId\" value=\"" . htmlspecialchars($address['id']) . "\">
                <div class=\"form-group mb-3\">
                    <label for=\"inputAddress\">Adresse</label>
                    <input name=\"address\" type=\"text\" class=\"form-control\" id=\"inputAddress\" value=\"" . htmlspecialchars($address['adresse']) . "\" required>
                </div>
                <div class=\"form-row\">
                    <div class=\"form-group col-md-6 mb-3\">
                        <label for=\"inputZip\">Code Postal</label>
                        <input name=\"zipCode\" type=\"text\" class=\"form-control\" id=\"inputZip\"  value=\"" . htmlspecialchars($address['code_postal']) . "\" required>
                    </div>
                    <div class=\"form-group col-md-6 mb-3\">
                        <label for=\"inputCity\">Ville</label>
                        <input name=\"city\" type=\"text\" class=\"form-control\" id=\"inputCity\" value=\"" . htmlspecialchars($address['ville']) . "\" required>
                    </div>
                </div>
                <div class=\"row justify-content-center mt-2\">
                <button type=\"submit\" class=\"btn btn-dark\">Valider</button>
                </div>
            </form>
        </div>";
    }
}


// ***************** mettre à jour l'adresse sauvegardée  ************************

function updateAddress()
{
    $db = getConnection();

    $query = $db->prepare('UPDATE adresses SET adresse = :adresse, code_postal = :code_postal, ville = :ville WHERE id = :id');
    $query->execute(array(
        'adresse' =>  strip_tags($_POST['address']),
        'code_postal' => strip_tags($_POST['zipCode']),
        'ville' =>  strip_tags($_POST['city']),
        'id' => strip_tags($_POST['addressId'])
    ));

    echo '<script>alert(\'Nouvelle adresse validée !\')</script>';
}



// **************************************************** COMPTE / INFOS CLIENT ***********************************************************

// ************************ mettre à jour les informations  *****************************

function updateUser()
{
    if (!checkEmptyFields()) {

        $db = getConnection();

        $firstName = strip_tags($_POST['firstName']);
        $lastName = strip_tags($_POST['lastName']);
        $email = strip_tags($_POST['email']);
        $id = strip_tags($_POST['clientId']);

        $query = $db->prepare('UPDATE clients SET prenom = :prenom, nom = :nom, email = :email WHERE id = :id');
        $query->execute([
            'prenom' =>  $firstName,
            'nom' => $lastName,
            'email' => $email,
            'id' => $id
        ]);

        $_SESSION['prenom'] = $firstName;
        $_SESSION['nom'] = $lastName;
        $_SESSION['email'] = $email;

        echo '<script>alert(\'Changements validés !\')</script>';
    } else {
        echo '<script>alert(\'Attention : un ou plusieurs champs vides !\')</script>';
    }
}


// ************************ afficher formulaire infos client *****************************

function displayInformations($currentPage)
{
    echo "<div class=\"container p-5\">
            <div class=\"row text-center justify-content-center\">
                <div class=\"col-md-6\">
                        <div class=\"container border border-dark bg-light mb-4 p-5\">
                            <form action=\"" . $currentPage . "\" method=\"post\">
                                <input type=\"hidden\" name=\"userModified\" value=\"true\">
                                <input type=\"hidden\" name=\"clientId\" value=\"" . $_SESSION['id'] . "\">
                                <div class=\"form-row\">
                                    <div class=\"form-group\">
                                        <label for=\"inputFirstName\">Prénom</label>
                                        <input name=\"firstName\" type=\"text\" class=\"form-control\" id=\"inputFirstName\" 
                                        value=\"" . htmlspecialchars($_SESSION['prenom']) . "\" required>
                                    </div>
                                    <div class=\"form-group\">
                                        <label for=\"inputName\">Nom</label>
                                        <input name=\"lastName\" type=\"text\" class=\"form-control\" id=\"inputName\" 
                                        value=\"" . htmlspecialchars($_SESSION['nom']) . "\" required>
                                    </div>
                                </div>
                                <div class=\"form-row justify-content-center\">
                                    <div class=\"form-group\">
                                        <label for=\"inputEmail\">Email</label>
                                        <input name=\"email\" type=\"email\" class=\"form-control\" id=\"inputEmail\" 
                                        value=\"" . htmlspecialchars($_SESSION['email']) . "\" required>
                                    </div>
                                </div>
                                <div class=\"row justify-content-center mt-5\">
                                    <button type=\"submit\" class=\"btn btn-dark\">Valider les changements</button>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>";
}


// ************************ récupérer le mot de passe en bdd*****************************

function  getUserPassword()
{
    $db = getConnection();
    $query = $db->prepare('SELECT mot_de_passe FROM clients WHERE id = ?');
    $query->execute(array($_SESSION['id']));
    $infos = $query->fetch();
    return $infos['mot_de_passe'];
}


// ************************ modifier le mot de passe  *****************************

function updatePassword()
{
    if (!checkEmptyFields()) {  // on vérifie d'abord si il n'y a pas de champs vides. Si oui, message d'erreur et fin de la fonction.

        $oldPasswordDatabase = getUserPassword();   // on récupère le mdp actuel en base (hashé)

        // on vérifie le mdp actuel saisi par rapport à l'actuel en base
        // si mdp actuel saisi = mdp actuel en base, on passe à la suite. Sinon fin de la fonction et message d'erreur
        if (password_verify(strip_tags($_POST['oldPassword']), $oldPasswordDatabase)) {

            // on nettoie le nouveau mdp choisi
            $newPassword = strip_tags($_POST['newPassword']);

            // on vérifie que le nouveau mdp choisi réunit les critères de sécurité. Si pas bon => sortie et message d'erreur
            if (checkPassword($newPassword)) {

                //si nouveau mdp ok => on le sauvegarde en le hâchant avec password_hash()
                $db = getConnection();

                // hâchage du mot de passe
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                $query = $db->prepare('UPDATE clients SET mot_de_passe = :newPassword WHERE id = :id');
                $query->execute(array(
                    'newPassword' => $hashedPassword,
                    'id' => $_SESSION['id']
                ));

                echo "<script>alert(\"Mot de passe modifié avec succès\")</script>";
            } else {
                echo "<script>alert(\"Attention : sécurité du mot de passe insuffisante ! \")</script>";
            };
        } else {
            echo "<script>alert(\"Erreur : l'ancien mot de passe saisi est incorrect\")</script>";
        }
    } else {
        echo "<script>alert(\"Attention : un ou plusieurs champs vides ! \")</script>";
    }
}



// **************************************************** COMMANDES ***********************************************************

// ***************** récupérer la liste des commandes  ************************

function getOrders()
{
    $db = getConnection();
    $query = $db->prepare('SELECT * FROM commandes WHERE id_client = ? ORDER BY date_commande DESC');
    $query->execute([$_SESSION['id']]);
    return $query->fetchAll();
}


// ***************** récupérer les articles de chaque commande  ************************

function getOrderArticles($orderId)
{
    $db = getConnection();
    $query = $db->prepare('SELECT * FROM commande_articles AS ca 
                            INNER JOIN articles AS a 
                            ON a.id = ca.id_article 
                            WHERE id_commande = ?');
    $query->execute([$orderId]);
    return $query->fetchAll();
}


// ***************** afficher la liste des commandes  ************************

function displayOrders()
{
    $orders = getOrders();

    if (count($orders) == 0) {
        echo "<p>Vous n'avez pas encore passé de commande !</p>";
    } else {
        echo "<table class=\"table  table-striped\">
    <thead class=\"thead-dark\">
      <tr>
        <th scope=\"col\">Numéro</th>
        <th scope=\"col\">Date</th>
        <th scope=\"col\">Montant</th>
        <th scope=\"col\">Détails</th>
    </tr>
    </thead>
    <tbody>";

        foreach ($orders as $order) {

            echo "<tr>
                <td>" . htmlspecialchars($order["numero"]) . "</td>
                <td>" . htmlspecialchars($order["date_commande"]) . "</td>
                <td>" . htmlspecialchars($order["prix"]) . " €</td>
                <td>
                    <form action=\"orderDetails.php\" method=\"post\">
                        <input type=\"hidden\" name=\"orderId\" value=\"" . htmlspecialchars($order["id"]) . "\">
                        <input type=\"hidden\" name=\"orderNumber\" value=\"" . htmlspecialchars($order["numero"]) . "\">
                        <input type=\"hidden\" name=\"orderTotal\" value=\"" . htmlspecialchars($order["prix"]) . "\">
                        <input type=\"hidden\" name=\"orderDate\" value=\"" . htmlspecialchars($order["date_commande"]) . "\">
                        <input type=\"hidden\" name=\"livraison\" value=\"" . htmlspecialchars($order["livraison"]) . "\">
                        <button type=\"submit\"  class=\"btn btn-dark\">Détails</button>
                    </form>
                </td>
              </tr>";
        }
        echo "</tr>
        </td>
        </tr>";

        echo "</tbody>
    </table>";
    }
}


// ***************** afficher le détail d'une commande  ************************

function displayOrderArticles($orderArticles)
{
    echo "<table class=\"table\">
    <thead>
      <tr>
        <th scope=\"col\">Article</th>
        <th scope=\"col\">Prix</th>
        <th scope=\"col\">Quantité</th>
        <th scope=\"col\">Montant</th>
      </tr>
    </thead>
    <tbody>";

    // pour calculer les frais de port fixes (3€ * nombre de montres)
    //$articlesQuantity = 0;

    foreach ($orderArticles as $article) {

        //$articlesQuantity += $article['quantite'];

        echo "<tr>
                <td>" . htmlspecialchars($article["nom"]) . "</td>
                <td>" . htmlspecialchars($article["prix"]) . " € </td>
                <td>" . htmlspecialchars($article["quantite"]) . "</td>
                <td>" . htmlspecialchars($article["prix"]) * htmlspecialchars($article["quantite"]) . " €</td>
              </tr>";
    }

    // affichage des frais de livraison
    // on les détermine à partir du choix fait lors de cette commande
    // il a été stocké dans la session en haut de la page orderdetails
    $deliveryCost = 0;

    if ($_SESSION['displayedOrderDelivery'] == 'domicile') {
        $deliveryCost = 10;
    } else {
        $deliveryCost = 5;
    }

    $deliveryCost = number_format($deliveryCost, 2, ',', 0);

    echo "<tr>
    <td>mode de livraison choisi : " . str_replace("_", " ", $_SESSION['displayedOrderDelivery']) . "</td>
    <td>" .  $deliveryCost  . " €</td>
    <td>1</td>
    <td>" .  $deliveryCost  . " €</td>
    </tr>
    </tbody>
    </table>";
}

// affichage des frais de port (version avec frais de port fixes) 
//<td>Frais de port</td>
//<td>" .  number_format(3, 2, ',', 0)  . " €</td>
// <td> $articlesQuantity </td>
// <td>" .  number_format(3 * $articlesQuantity, 2, ',', 0)  . " €</td>
