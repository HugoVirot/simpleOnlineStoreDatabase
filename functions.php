<?php

// ****************** récupérer la liste des articles **********************

function getArticles()
{

    $article1 = [
        'name' => 'Dark Watch',
        'id' => '1',
        'price' => 149.99,
        'description' => 'Moderne et élégante',
        'picture' => 'watch1.jpg'
    ];

    $article2 = [
        'name' => 'Classic Leather',
        'id' => '2',
        'price' => 229.49,
        'description' => 'Affiche l\'heure de 250 pays',
        'picture' => 'watch2.jpg'
    ];

    $article3 = [
        'name' => 'Silver Star',
        'id' => '3',
        'price' => 345.99,
        'description' => 'Bracelet acier inoxydable',
        'picture' => 'watch3.jpg'
    ];

    $articles = array();

    array_push($articles, $article1);
    array_push($articles, $article2);
    array_push($articles, $article3);

    return $articles;
}



// ****************** afficher l'ensemble des articles **********************

function showArticles()
{
    $articles = getArticles();

    foreach ($articles as $article) {
        echo "<div class=\"card col-md-3 p-3 m-3\" style=\"width: 18rem;\">
                <img class=\"card-img-top\" src=\"images/" . $article['picture'] . "\" alt=\"Card image cap\">
                <div class=\"card-body\">
                    <h5 class=\"card-title font-weight-bold\">" . $article['name'] . "</h5>
                    <p class=\"card-text font-italic\">" . $article['description'] . "</p>
                    <p class=\"card-text font-weight-light\">" . $article['price'] . " €</p>
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
        $cartTotal = number_format($cartTotal, 2, ',', ' ');
        echo "Total des achats : " . $cartTotal . "€";
    } else {
        echo "Votre panier est vide !";
    }
}


// ****************** calculer le total du panier **********************


function calculateShippingFees()
{
    $totalArticlesQuantity = 0;

    $cart = $_SESSION['cart'];
    
    for ($i = 0; $i < count($cart); $i++) {

        $totalArticlesQuantity += $cart[$i]['quantity'];
    }
    
    return 3 * $totalArticlesQuantity;
}


// ****************** vider le panier **********************


function emptyCart()
{
    $_SESSION['cart'] = [];
    echo "<script> alert(\"Le panier a bien été vidé\");</script>";
}
