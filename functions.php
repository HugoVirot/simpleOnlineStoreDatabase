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

// ****************** récupérer la liste des articles **********************

function getArticles()
{
    $db = getConnection();
    $query = $db->query('SELECT * FROM Articles');
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


// ****************** récupérer la liste des articles par gamme **********************

function getArticlesByRange($rangeId)
{
    $db = getConnection();
    $query = $db->prepare('SELECT * FROM Articles WHERE id_gamme = :id_gamme');
    $query->execute(array(
        'id_gamme' => $rangeId
    ));
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


// ****************** récupérer un article à partir de son id **********************

function getArticleFromId($id)
{
    $db = getConnection();
    $result = $db->prepare('SELECT * FROM Articles WHERE id = ?');
    $result->execute(array($id));
    return $result->fetch();
}


// ****************** afficher l'ensemble des articles OK**********************

function showArticles($articles)
{
    foreach ($articles as $article) {
        echo "<div class=\"card col-md-5 col-lg-3 p-3 m-3\" style=\"width: 18rem;\">
                <img class=\"card-img-top\" src=\"images/" . $article['image'] . "\" alt=\"Card image cap\">
                <div class=\"card-body\">
                    <h5 class=\"card-title font-weight-bold\">" . $article['nom'] . "</h5>
                    <p class=\"card-text font-italic\">" . $article['description'] . "</p>
                    <p class=\"card-text font-weight-light\">" . $article['prix'] . " €</p>
                    " . displayStock($article['stock']) . "
                    <form action=\"product.php\" method=\"post\">
                        <input type=\"hidden\" name=\"articleToDisplay\" value=\"" . $article['id'] . "\">
                        <input class=\"btn btn-light\" type=\"submit\" value=\"Détails produit\">
                    </form>";
        if ($article['stock'] > 0) {
            echo "<form action=\"panier.php\" method=\"post\">
                        <input type=\"hidden\" name=\"chosenArticle\" value=\"" . $article['id'] . "\">
                        <input class=\"btn btn-dark mt-2\" type=\"submit\" value=\"Ajouter au panier\">
                  </form>";
        }
        echo "</div>
              </div>";
    }
}


// ****************** afficher le détail d'un article sur la page produit **********************

function showArticleDetails($articleToDisplay)
{
    echo "<div class=\"container p-3\">
            <div class=\"row justify-content-center\">
                <img src=\"images/" . $articleToDisplay['image'] . "\">
            </div>
          </div>
          <div class=\"container w-50 border border-dark bg-light mb-3\">
            <div class=\"row pt-5 text-center font-weight-bold align-items-center bg-light p-2 justify-content-center\">
                <h2>" . $articleToDisplay['nom'] . "</h2>
            </div>
            <div class=\"row text-center font-italic align-items-center bg-light p-3 justify-content-center\">
                <h5>" . $articleToDisplay['description'] . "<h5>
            </div>
            <div class=\"row text-center align-items-center bg-light p-2 ml-5 mr-5 justify-content-center\">
                <p>" . $articleToDisplay['description_detaillee'] . "<p>
            </div>
            <div class=\"row text-center font-weight-light align-items-center bg-light p-1 justify-content-center\">    
                <h4>" . $articleToDisplay['prix'] . " €</h4>
            </div>
            <div class=\"container w-75 text-center align-items-center bg-light p-3 justify-content-center\">    
            " . displayStock($articleToDisplay['stock']) . "
            </div>
            <div class=\"row pb-5 text-center align-items-center bg-light p-2 justify-content-center\">";
    if ($articleToDisplay['stock'] > 0) {
        echo "<form action=\"panier.php\" method=\"post\">
                            <input type=\"hidden\" name=\"chosenArticle\" value=\"" . $articleToDisplay['id'] . "\">
                            <input class=\"btn btn-dark mt-2\" type=\"submit\" value=\"Ajouter au panier\">
                      </form>";
    }
    echo "</div>
          </div>";
}



// **************************************************** GAMMES ***********************************************************

// ****************** récupérer les gammes en bdd **********************

function getRanges()
{
    $db = getConnection();
    $query = $db->query('SELECT * FROM gammes');
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


// ****************** afficher les gammes **********************

function showRanges($ranges)
{
    foreach ($ranges as $range) {
        echo "<div class=\"container w-75 border border-dark bg-light\">
                <div class=\"row p-3 justify-content-center\"><h4>" . $range['nom'] . "</h4></div>
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


// ************ afficher les boutons "vider panier" et "valider la commande"  ***********

function showButtons()
{
    if ($_SESSION['cart']) {
        echo   "<form action=\"panier.php\" method=\"post\" class=\"row justify-content-center text-dark font-weight-bold p-2\">
                 <input type=\"hidden\" name=\"emptyCart\" value=\"true\">
                <button type=\"submit\" class=\"btn btn-danger\">Vider le panier</button>
            </form>";
        if (isset($_SESSION['id'])) {
            echo "
            <a href=\"validation.php\">
                <div class=\"row justify-content-center p-2\">
                    <button type=\"button\" class=\"btn btn-dark\">Valider la commande</button>
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
    $result = $query->fetch(PDO::FETCH_ASSOC);
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


// ******************************** enlever du stock le nombre d'articles achetés ********************************

function decreaseStock($articleQuantity, $id)
{
    $db = getConnection();

    $query = $db->prepare('SELECT stock FROM articles WHERE id = ?');
    $query->execute([$id]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    $stock = intval($result['stock']);

    $newStock = $stock - $articleQuantity;

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

    $query = $db->prepare('INSERT INTO commandes (id_client, numero, date_commande, prix) VALUES(:id_client, :numero, :date_commande, :prix)');

    $query->execute(array(
        'id_client' => $_SESSION['id'],
        'numero' => rand(1000000, 9999999),
        'date_commande' => date("d-m-Y h:i:s"),
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

        decreaseStock($article['quantity'], $article['id']);
    }
}



// ******************************************* UTILISATEURS (INSCRIPTION ET CONNEXION) ****************************************************

// ***************** vérifier la présence de champs vides ************************

function checkEmptyFields()
{

    $emptyFieldsFound = false;

    foreach ($_POST as $field) {
        if (empty($field)) {
            $emptyFieldsFound = true;
        }
    }

    return $emptyFieldsFound;
}


// ***************** vérifier la longueur des champs ************************

function checkInputsLenght()
{
    $inputsLenghtOk = true;

    if (strlen($_POST['firstName']) > 25 || strlen($_POST['firstName']) < 3) {
        $inputsLenghtOk = false;
    }

    if (strlen($_POST['lastName']) > 25 || strlen($_POST['lastName']) < 3) {
        $inputsLenghtOk = false;
    }

    if (strlen($_POST['email']) > 25 || strlen($_POST['email']) < 5) {
        $inputsLenghtOk = false;
    }

    if (strlen($_POST['address']) > 40 || strlen($_POST['address']) < 5) {
        $inputsLenghtOk = false;
    }

    if (strlen($_POST['zipCode']) !== 5) {
        $inputsLenghtOk = false;
    }

    if (strlen($_POST['city']) > 25 || strlen($_POST['city']) < 3) {
        $inputsLenghtOk = false;
    }

    return $inputsLenghtOk;
}


// ***************** vérifier le mot de passe ************************

function checkPassword($password)
{
    $isPasswordSecured = false;

    // minimum 8 caractères et maximum 15, minimum 1 lettre, 1 chiffre et 1 caractère spécial
    $regex = "^(?=.*[0-9])(?=.*[a-zA-Z])(?=.*[@$!%*?/&])(?=\S+$).{8,15}$^";

    if (preg_match($regex, $password)) {
        $isPasswordSecured = true;
    }

    return $isPasswordSecured;
}


// ***************** créer un utilisateur ************************

function createUser()
{
    $db = getConnection();

    if (checkEmptyFields()) {
        echo "<div class=\"container w-50 text-center p-3 mt-2 bg-danger\"> Attention : un ou plusieurs champs vides !</div>";
    } else {

        if (!checkInputsLenght()) {
            echo "<div class=\"container w-50 text-center p-3 mt-2 bg-danger\"> Attention : longueur incorrecte d'un ou plusieurs champs !</div>";
        } else {

            if (!checkPassword($_POST['password'])) {
                echo "<div class=\"container w-50 text-center p-3 mt-2 bg-danger\"> Attention : sécurité du mot de passe insuffisante !</div>";
            } else {
                echo '<script>alert(\longueur champs ok!\')</script>';
                $hashedPassword = password_hash(strip_tags($_POST['password']), PASSWORD_DEFAULT);

                $query = $db->prepare('INSERT INTO clients (nom, prenom, email, mot_de_passe) VALUES(:nom, :prenom, :email, :mot_de_passe)');
                $query->execute(array(
                    'nom' =>  strip_tags($_POST['lastName']),
                    'prenom' => strip_tags($_POST['firstName']),
                    'email' =>  strip_tags($_POST['email']),
                    'mot_de_passe' => $hashedPassword,
                ));

                $id = $db->lastInsertId();

                $query = $db->prepare('INSERT INTO adresses (id_client, adresse, code_postal, ville) VALUES(:id_client, :adresse, :code_postal, :ville)');
                $query->execute(array(
                    'id_client' => $id,
                    'adresse' => strip_tags($_POST['address']),
                    'code_postal' =>  strip_tags($_POST['zipCode']),
                    'ville' =>  strip_tags($_POST['city']),
                ));

                echo '<script>alert(\'Le compte a bien été créé !\')</script>';
            }
        }
    }
}


// ***************** se connecter  ************************

function logIn()
{
    $db = getConnection();

    $userEmail = strip_tags($_POST['email']);

    $query = $db->prepare('SELECT * FROM clients WHERE email = ?');
    $query->execute([$userEmail]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        echo '<script>alert(\'E-mail ou mot de passe incorrect !\')</script>';
    } else {

        $isPasswordCorrect = password_verify($_POST['password'], $result['mot_de_passe']);

        if ($isPasswordCorrect) {
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

    $query = $db->prepare('SELECT * FROM adresses WHERE id_client = ?');
    $query->execute([$_SESSION['id']]);
    return $query->fetch(PDO::FETCH_ASSOC);
}


// ***************** définir / mettre à jour l'adresse de la session ************************

function setSessionAddress()
{
    $_SESSION['adresse'] = getUserAdress();
}


// ***************** afficher formulaire modification adresse  ************************

function displayAddress($currentPage)
{
    $address = getUserAdress();

    echo "<div class=\"container p-5 w-50 border border-dark bg-light mb-4 p-4\">
            <form action=\"" . $currentPage . "\" method=\"post\">
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

    echo '<script>alert(\'Nouvelle adresse validée !\')</script>';
}



// **************************************************** COMPTE / INFOS CLIENT ***********************************************************

// ************************ mettre à jour les informations  *****************************

function updateUser()
{
    $db = getConnection();

    if (!checkEmptyFields()) {

        $firstName = strip_tags($_POST['firstName']);
        $lastName = strip_tags($_POST['lastName']);
        $email = strip_tags($_POST['email']);
        $id = strip_tags($_POST['clientId']);

        $query = $db->prepare('UPDATE clients SET prenom = :prenom, nom = :nom, email = :email WHERE id = :id');
        $query->execute(array(
            'prenom' =>  $firstName,
            'nom' => $lastName,
            'email' => $email,
            'id' => $id
        ));

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
                                    <div class=\"form-group col-md-6\">
                                        <label for=\"inputFirstName\">Prénom</label>
                                        <input name=\"firstName\" type=\"text\" class=\"form-control\" id=\"inputFirstName\" 
                                        value=\"" . $_SESSION['prenom'] . "\" required>
                                    </div>
                                    <div class=\"form-group col-md-6\">
                                        <label for=\"inputName\">Nom</label>
                                        <input name=\"lastName\" type=\"text\" class=\"form-control\" id=\"inputName\" value=\"" . $_SESSION['nom'] . "\" required>
                                    </div>
                                </div>
                                <div class=\"form-row justify-content-center\">
                                    <div class=\"form-group col-md-6\">
                                        <label for=\"inputEmail\">Email</label>
                                        <input name=\"email\" type=\"email\" class=\"form-control\" id=\"inputEmail\" value=\"" . $_SESSION['email'] . "\" required>
                                    </div>
                                </div>
                                <div class=\"row justify-content-center mt-2\">
                                    <button type=\"submit\" class=\"btn btn-dark\">Valider les changements</button>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>";
}


// ************************ récupérer le mot de passe en bdd*****************************

function getUserPassword()
{

    $db = getConnection();
    $query = $db->prepare('SELECT mot_de_passe FROM clients WHERE id = ?');
    $query->execute(array($_SESSION['id']));
    return $query->fetch(PDO::FETCH_ASSOC);
}


// ************************ modifier le mot de passe  *****************************

function updatePassword()
{
    if (!checkEmptyFields()) {

        if (checkPassword($_POST['newPassword'])) {

            $oldPasswordDatabase = getUserPassword();
            $oldPasswordDatabase = $oldPasswordDatabase['mot_de_passe'];

            $isPasswordCorrect = password_verify(strip_tags($_POST['oldPassword']), $oldPasswordDatabase);

            if ($isPasswordCorrect) {

                $newPassword = $_POST['newPassword'];

                $db = getConnection();
                $query = $db->prepare('UPDATE clients SET mot_de_passe = :newPassword WHERE id = :id');
                $query->execute(array(
                    'newPassword' => password_hash($newPassword, PASSWORD_DEFAULT),
                    'id' => $_SESSION['id']
                ));

                echo "<script>alert(\"Mot de passe modifié avec succès\")</script>";
            } else {
                echo "<script>alert(\"Erreur : l'ancien mot de passe saisi est incorrect\")</script>";
            };
        } else {
            echo "<script>alert(\"Attention : sécurité du mot de passe insuffisante ! \")</script>";
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
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


// ***************** récupérer les articles de chaque commande  ************************

function getOrderArticles($orderId)
{
    $db = getConnection();
    $query = $db->prepare('SELECT * FROM commande_articles ca INNER JOIN articles a ON a.id = ca.id_article WHERE id_commande = ?');
    $query->execute([$orderId]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


// ***************** afficher la liste des commandes  ************************

function displayOrders()
{
    $orders = getOrders();

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
                <td>" . $order["numero"] . "</td>
                <td>" . $order["date_commande"] . "</td>
                <td>" . $order["prix"] . " €</td>
                <td>
                    <form action=\"orderDetails.php\" method=\"post\">
                        <input type=\"hidden\" name=\"orderId\" value=\"" . $order["id"] . "\">
                        <input type=\"hidden\" name=\"orderNumber\" value=\"" . $order["numero"] . "\">
                        <input type=\"hidden\" name=\"orderTotal\" value=\"" . $order["prix"] . "\">
                        <input type=\"hidden\" name=\"orderDate\" value=\"" . $order["date_commande"] . "\">
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


// ***************** afficher la liste des commandes  ************************

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

    $articlesQuantity = 0;

    foreach ($orderArticles as $article) {

        $articlesQuantity += $article['quantite'];

        echo "<tr>
                <td>" . $article["nom"] . "</td>
                <td>" . $article["prix"] . " € </td>
                <td>" . $article["quantite"] . "</td>
                <td>" . $article["prix"] * $article["quantite"] . " €</td>
              </tr>";
    }

    echo "<tr>
    <td>Frais de port</td>
    <td>" .  number_format(3, 2, ',', 0)  . " €</td>
    <td> $articlesQuantity </td>
    <td>" .  number_format(3 * $articlesQuantity, 2, ',', 0)  . " €</td>
    </tr>
    </tbody>
    </table>";
}
