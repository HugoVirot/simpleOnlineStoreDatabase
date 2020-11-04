<?php

// ****************** connexion à la base de données **********************


function getConnection()
{
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=online_store;charset=utf8', 'hugo', 'H30flm645@', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    return $bdd;
}



// function getArticlesSelected($selected)
// {
//     $chaine = join(',', $selected);
//     $bdd = getConnection();
//     $query = $bdd->prepare('SELECT * FROM Articles WHERE idArticles = ? ');
//     $query->execute(array($chaine));
//     $resultat = $query->fetchAll();
//     var_dump($resultat);
//     return $resultat;
// }


// function getClients()
// {
//     $bdd = getConnection();
//     $query = $bdd->query('SELECT * FROM Clients');
//     $resultat = $query->fetchAll();
//     return $resultat;
// }


// function getAdresses()
// {
//     $bdd = getConnection();
//     $query = $bdd->query('SELECT * FROM Adresses');
//     $resultat = $query->fetchAll();
//     return $resultat;
// }



// ****************** récupérer la liste des articles **********************

function getArticles()
{

     $bdd = getConnection();
     $query = $bdd->query('SELECT * FROM Articles');
     return $query->fetchAll();
}

// function getArticle($id)
// {
//     $bdd = getConnection();
//     $resultat = $bdd->prepare('SELECT * FROM Articles WHERE idArticles = ?');
//     $resultat->execute(array($id));
//     $result = $resultat->fetch();
//     return $result;
// }



// ****************** afficher l'ensemble des articles **********************

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



// ****************** récupérer un article à partir de son id **********************

function getArticleFromId($id)
{

    $articles = getArticles();

    foreach ($articles as $article) {
        if ($article['id'] == $id) {
            $searchedArticle = $article;
            break;
        }
    }
    return $searchedArticle;
}



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
            $cartTotal += $article['price'] * $article['quantity'];
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


// function ajoutCommandeBDD ($_SESSION)
// {
//     $bdd = getConnection();
//     $req = $bdd-> query('INSERT INTO Commandes (`id`,`Numéro`, `Date`, `Clients_ID`, `Adresses_idAdresses`, `Adresses_idAdresses1`)
//         VALUES (21, rand(1000000000,9999999999),date("Y", "m", "d", "H", "i"), 1, 1, 1)');
//     //public PDO::lastInsertId ([ string $name = NULL ] ) : string;
//     foreach ($_SESSION['panier'] as $article)
//     {
//         $req2 = $bdd->query('INSERT INTO Commandes_has_articles (`Commandes_ID`, `Articles_idArticles`, `Prix`, `Quantité`) 
//         VALUES (21, '$article['id']','$article['prix']','$article['quantite']')');
//     }
// }