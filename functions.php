<?php

// ****************** connexion à la base de données OK**********************

function getConnection()
{
    try {
        $db = new PDO('mysql:host=localhost;dbname=online_store;charset=utf8', 'hugo', 'H30flm645@', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    return $db;
}



// **************************************************** ARTICLES ***********************************************************

// ****************** récupérer la liste des articles OK**********************

function getArticles()
{
    $db = getConnection();
    $query = $db->query('SELECT * FROM Articles');
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


// ****************** récupérer un article à partir de son id OK**********************

function getArticleFromId($id)
{
    $db = getConnection();
    $result = $db->prepare('SELECT * FROM Articles WHERE id = ?');
    $result->execute(array($id));
    $article = $result->fetch();
    return $article;
}


// ****************** afficher l'ensemble des articles OK**********************

function showArticles()
{
    $articles = getArticles();
    foreach ($articles as $article) {
        echo "<div class=\"card col-md-5 col-lg-3 p-3 m-3\" style=\"width: 18rem;\">
                <img class=\"card-img-top\" src=\"images/" . $article['image'] . "\" alt=\"Card image cap\">
                <div class=\"card-body\">
                    <h5 class=\"card-title font-weight-bold\">" . $article['nom'] . "</h5>
                    <p class=\"card-text font-italic\">" . $article['description'] . "</p>
                    <p class=\"card-text font-weight-light\">" . $article['prix'] . " €</p>
                    <form action=\"product.php\" method=\"post\">
                        <input type=\"hidden\" name=\"articleToDisplay\" value=\"" . $article['id'] . "\">
                        <input class=\"btn btn-light\" type=\"submit\" value=\"Détails produit\">
                    </form>
                    <form action=\"panier.php\" method=\"post\">
                        <input type=\"hidden\" name=\"chosenArticle\" value=\"" . $article['id'] . "\">
                        <input class=\"btn btn-dark mt-2\" type=\"submit\" value=\"Ajouter au panier\">
                    </form>
                </div>
            </div>";
    }
}


// function getClients()
// {
//     $db = getConnection();
//     $query = $db->query('SELECT * FROM Clients');
//     $resultat = $query->fetchAll();
//     return $resultat;
// }


// function getAdresses()
// {
//     $db = getConnection();
//     $query = $db->query('SELECT * FROM Adresses');
//     $resultat = $query->fetchAll();
//     return $resultat;
// }


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

    $newQuantity = checkTypedQuantity();

    if (is_numeric($newQuantity)) {

        $modifiedArticleId = $_POST['modifiedArticleId'];

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
                        <img class=\"col-md-2\" style=\"width: 150px\" src=\"images/" . $chosenArticle['image'] . "\">
                        <p class=\"font-weight-bold col-md-2\">" . $chosenArticle['nom'] . "</p>
                        <p class=\"col-md-2\">" . $chosenArticle['description'] . "</p>
                        <p class=\"col-md-2\">" . $chosenArticle['prix'] . " €</p>

                        <form class=\"col-lg-3\" action=\"" . $pageName . "\" method=\"post\">
                            <div class=\"row pt-2\">
                            <input type=\"hidden\" name=\"modifiedArticleId\" value=\"" . $chosenArticle['id'] . "\">
                            <input class=\"col-2 offset-2\" type=\"text\" name=\"newQuantity\" value=\"" . $chosenArticle['quantity'] . "\">
                            <button type=\"submit\" class=\"col-5 offset-1 btn btn-light\">
                                Modifier quantité
                            </button>
                            </div>
                        </form>

                        <form class=\"col-lg-1\" action=\"" . $pageName . "\" method=\"post\">
                            <input type=\"hidden\" name=\"deletedArticle\" value=\"" . $chosenArticle['id'] . "\">
                            <button type=\"submit\" class=\"btn btn-dark\">
                                <i class=\"fas fa-ban\"></i>
                            </button>
                        </form>
                      </div>";
    }
}


// ************vérifie que la quantité entrée est un nombre entre 1 et 10  ***********

function checkTypedQuantity()
{

    if (isset($_POST['newQuantity'])) {
        $typedQuantity = $_POST['newQuantity'];
    } else {
        $typedQuantity = null;
    }

    if (is_numeric($typedQuantity) && $typedQuantity >= 1 && $typedQuantity <= 9) {
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
    $cartTotal = getCartTotal();
    $shippingFees = calculateShippingFees();
    return $cartTotal + $shippingFees;
}


// ****************** vider le panier **********************


function emptyCart($showConfirmation)
{
    $_SESSION['cart'] = [];
    if ($showConfirmation) {
        echo "<script> alert(\"Le panier a bien été vidé\");</script>";
    }
}



// ********************************** SAUVEGARDE COMMANDE *********************************************


function saveOrder($totalPrice)
{
    $db = getConnection();

    $query = $db->prepare('INSERT INTO commandes (id_client, id_adresse, numero, date_commande, prix) VALUES(:id_client, :id_adresse, :numero, :date_commande, :prix)');

    $query->execute(array(
        'id_client' => $_SESSION['id'],
        'id_adresse' => $_SESSION['adresse']['id'],
        'numero' => rand(1000000, 9999999),
        'date_commande' => date("d-m-Y"),
        'prix' => $totalPrice,
    ));

    $id = $db->lastInsertId();

    foreach ($_SESSION['cart'] as $article) {
        $query = $db->prepare('INSERT INTO commande_articles (id_commande, id_article, quantite) VALUES(:id_commande, :id_article, :quantity)');
        $query->execute(array(
            'id_commande' => $id,
            'id_article' => $article['id'],
            'quantity' => $article['quantity']
        ));
    }
}



// **************************************************** UTILISATEURS ***********************************************************

// ***************** créer un utilisateur ************************

function createUser()
{
    $db = getConnection();

    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = $db->prepare('INSERT INTO clients (nom, prenom, email, mot_de_passe) VALUES(:nom, :prenom, :email, :mot_de_passe)');
    $query->execute(array(
        'nom' =>  $_POST['lastName'],
        'prenom' => $_POST['firstName'],
        'email' =>  $_POST['email'],
        'mot_de_passe' => $hashedPassword,
    ));

    $id = $db->lastInsertId();

    $query = $db->prepare('INSERT INTO adresses (id_client, adresse, code_postal, ville) VALUES(:id_client, :adresse, :code_postal, :ville)');
    $query->execute(array(
        'id_client' => $id,
        'adresse' => $_POST['address'],
        'code_postal' =>  $_POST['zipCode'],
        'ville' =>  $_POST['city'],
    ));

    echo '<script>alert(\'Le compte a bien été créé !\')</script>';
}


// ***************** se connecter  ************************

function logIn()
{
    $db = getConnection();

    $userEmail = $_POST['email'];

    $query = $db->prepare('SELECT * FROM clients WHERE email = :email');
    $query->execute(array(
        'email' => $userEmail
    ));
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        echo '<script>alert(\'E-mail ou mot de passe incorrect !\')</script>';
    } else {

        $isPasswordCorrect = password_verify($_POST['password'], $result['mot_de_passe']);

        if ($isPasswordCorrect) {
            // session_start();
            $_SESSION['id'] = $result['id'];
            $_SESSION['nom'] = $result['nom'];
            $_SESSION['prenom'] = $result['prenom'];
            $_SESSION['email'] = $userEmail;
            setSessionAddress();
            echo '<script>alert(\'Vous êtes connecté !\')</script>';
        } else {
            echo '<script>alert(\'E-mail ou mot de passe incorrect !\')</script>';
        }
    }
}


// ***************** se déconnecter  ************************

function logOut()
{
    $_SESSION = array();
    session_destroy();
    echo '<script>alert(\'Vous avez bien été déconnecté !\')</script>';
}




// **************************************************** ADRESSES ***********************************************************


// ***************** récupérer l'adresse du client en bdd ************************

function getUserAdress()
{
    $db = getConnection();

    $query = $db->prepare('SELECT * FROM adresses WHERE id_client = :id_client');
    $query->execute(array(
        'id_client' => $_SESSION['id']
    ));
    return $query->fetch(PDO::FETCH_ASSOC);
}


// ***************** définir / mettre à jour l'adresse de la session ************************

function setSessionAddress()
{
    $_SESSION['adresse'] = getUserAdress();
}


// ***************** afficher l'adresse par défaut lors de la commande  ************************

function displayAddress()
{
    $address = getUserAdress();

    echo "<div class=\"container w-50 border border-dark bg-light mb-4 p-4\">
            <form action=\"validation.php\" method=\"post\">
                <input type=\"hidden\" name=\"addressChanged\">
                <input type=\"hidden\" name=\"addressId\" value=\"" . $address['id'] . "\">
                <div class=\"form-group\">
                    <label for=\"inputAddress\">Adresse</label>
                    <input name=\"address\" type=\"text\" class=\"form-control\" id=\"inputAddress\" value=\"" . $address['adresse'] . "\" required>
                </div>
                <div class=\"form-row\">
                    <div class=\"form-group col-md-6\">
                        <label for=\"inputZip\">Code Postal</label>
                        <input name=\"zipCode\" type=\"text\" class=\"form-control\" id=\"inputZip\"  value=\"" . $address['code_postal'] . "\" required>
                    </div>
                    <div class=\"form-group col-md-6\">
                        <label for=\"inputCity\">Ville</label>
                        <input name=\"city\" type=\"text\" class=\"form-control\" id=\"inputCity\" value=\"" . $address['ville'] . "\" required>
                    </div>
                </div>
                <div class=\"row justify-content-center mt-2\">
                <button type=\"submit\" class=\"btn btn-dark\">Valider</button>
                </div>
            </form>
        </div>";
}


// ***************** mettre à jour l'adresse sauvegardée  ************************

function updateAddress()
{
    $db = getConnection();

    $query = $db->prepare('UPDATE adresses SET adresse = :adresse, code_postal = :code_postal, ville = :ville WHERE id = :id');
    $query->execute(array(
        'adresse' =>  $_POST['address'],
        'code_postal' => $_POST['zipCode'],
        'ville' =>  $_POST['city'],
        'id' => $_POST['addressId']
    ));

    echo '<script>alert(\'Adresse validée !\')</script>';
}
