<?php
session_start();
include('functions.php');

if (isset($_POST['chosenArticle'])) {

    $chosenArticleId = $_POST['chosenArticle'];
    $article = getArticleFromId($chosenArticleId);
    addToCart($article);
}

if (isset($_POST['deletedArticle'])) {
    $deletedArticleId = $_POST['deletedArticle'];
    removeToCart($deletedArticleId);
}

if (isset($_POST['modifiedArticleId'])) {
    updateQuantity();
}

if (isset($_POST['emptyCart']) && $_POST['emptyCart'] == true) {
    emptyCart($showConfirmation = true);
}

if (isset($_POST['addressChanged'])) {
    updateAddress();
}

if (isset($_POST['userModified'])) {
    updateUser();
}

if (isset($_POST['adresseLivraisonId'])) {
    
    foreach ($_SESSION['adresses'] as $adresse){
        if ($adresse['id'] == $_POST['adresseLivraisonId']){
            $_SESSION['deliveryAddress'] = $adresse;
        }
    }
}

// pour sauvegarder dans la session le type de livraison choisi

if (isset($_POST['delivery'])) {
    $_SESSION['delivery'] = $_POST['delivery'];
}

var_dump($_SESSION);
var_dump($_POST);

?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valider la commande - Arinfo, montres intemporelles</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body>
    <header>
        <?php
        include('header.php');
        ?>
    </header>

    <main>
        <div class="container-fluid pb-5">
            <div class="row text-center">
                <img id="watchPhoto" src="images/watchdesktop.jpg" style="width: 100vw">
            </div>
        </div>

        <div class="container text-center mb-3">
            <h3 class="mb-5">Récapitulatif de votre commande</h3>
            <?php
            showCartContent("validation.php");
            ?>

            <div class="row text-dark justify-content-center font-weight-bold bg-light p-4">
                <?php
                $cartTotal = getCartTotal();
                if ($_SESSION['cart']) {
                    $cartTotal = number_format($cartTotal, 2, ',', ' ');
                    echo "Total des achats : " . $cartTotal . "€";
                }
                ?>

            </div>

            <!-- ********************** frais de port : version facile : 3€ par montre (idem boutique v1) ********************** -->

            <!-- <div class="row text-dark justify-content-center font-weight-bold bg-light p-4">
                <?php
                // if ($_SESSION['cart']) {
                //     $shippingFees = calculateShippingFees();
                //     $shippingFees = number_format($shippingFees, 2, ',', ' ');
                //     echo "Frais de port (3,00 € par montre) : " . $shippingFees . "€";
                // }
                ?>
            </div> -->

            <!-- ********************** frais de port : version difficile: domicile ou point-relais ********************** -->

            <div>
                <h5 class="p-3">Type de livraison</h5>
                <form method="post" action="validation.php">
                    <div class="form-group">
                        <input type="radio" name="delivery" id="domicile" value="domicile" <?php if (isset($_SESSION['delivery']) && $_SESSION['delivery'] === "domicile") { ?> checked <?php } ?>>
                        <label for="classique">à domicile: 10 €</label>
                    </div>
                    <div class="form-group">
                        <input type="radio" name="delivery" id="point_relais" value="point_relais" <?php if (isset($_SESSION['delivery']) && $_SESSION['delivery'] === "point_relais") { ?> checked <?php } ?>>
                        <label for="classique">en point-relais : 5 €</label>
                    </div>
                    <button type="submit" class="btn btn-info mb-3">Valider</button>
                </form>
            </div>

            <!-- **************************** affichage du total *************************** -->

            <div class="row text-dark justify-content-center font-weight-bold bg-light p-4">
                <?php
                // si le panier est défini et contient des articles et si le type de livraison a été choisi
                if ($_SESSION['cart'] && isset($_SESSION['delivery'])) {
                    $totalPrice = calculateTotalPrice();
                    $totalPrice = number_format($totalPrice, 2, ',', ' ');
                    echo "<h5>TOTAL A PAYER : " . $totalPrice . "€</h5>";
                } else {
                    echo "Choisissez un type de livraison pour connaître le total.";
                }
                ?>
            </div>

            <!-- **************************** Coordonnées *************************** -->

            <h5 class="pt-5">Coordonnées</h5>
            <?php displayInformations("validation.php"); ?>


            <!-- **************************** Choix des adresses *************************** -->

            <h5 class="pb-5">Adresse de livraison</h5>

            <div class="row pb-3">
                <div class="col-6 offset-3 text-center border border-info pb-3">

                    <!-- affichage de l'adresse choisie -->

                    <?php if (isset($_SESSION['deliveryAddress'])) {
                        $adresseLivraison = $_SESSION['deliveryAddress'];
                    ?>

                        <div class="font-weight-bold pt-3">
                            <p><?php echo $_SESSION['prenom'] . " " . $_SESSION['nom']; ?></p>
                            <p><?php echo $adresseLivraison['adresse'] ?></p>
                            <p><?php echo $adresseLivraison['code_postal'] . ' ' . $adresseLivraison['ville'] ?></p>
                        </div>

                    <?php } else { ?>
                        <p class="mt-4">Aucune adresse choisie.</p>
                    <?php } ?>

                    <!-- si le user a enregistré des adresses, je lui propose le choix -->

                    <form action="validation.php" class="p-3" method="post">
                        <div class="form-group">
                            <label for="adresseLivraisonId">Choisisez une adresse</label>
                            <select name="adresseLivraisonId" id="adresseLivraisonId">
                                <option value=""></option>
                                <?php foreach ($_SESSION['adresses'] as $adresse) { ?>
                                    <option value="<?php echo $adresse['id'] ?>">
                                        <p><?= $adresse['adresse'] ?></p>
                                        <p><?= $adresse['code_postal'] ?></p>
                                        <p><?= $adresse['ville'] ?></p>
                                    </option>
                                <?php } ?>
                            </select>
                            <button type="submit" class="btn btn-warning">Sélectionner</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ******** bouton de confirmation : affiché seulement si panier pas vide, mode de livraison et adresse choisis ******** -->

            <?php if (count($_SESSION['cart']) > 0 && isset($_SESSION['delivery']) && isset ($_SESSION['deliveryAddress'])) {
                echo "<div class=\"row justify-content-center p-4\">
                    <button type=\"button\" class=\"btn btn-dark\" data-toggle=\"modal\" data-target=\"#confirmation\">Confirmer l'achat</button>
                </div>";
            } else {
                echo "<div class=\"w-75 mx-auto bg-danger text-white m-4 p-3 rounded\">Choisissez un mode de livraison et une adresse de livraison pour valider votre commande</div>";
            } 
            ?>

            <!-- Modal -->
            <div class="modal fade" id="confirmation" tabindex="-1" role="dialog" aria-labelledby="confirmation" aria-hidden="true">
                <div class="modal-dialog" role="document" pointer-events="all">
                    <div class="modal-content">
                        <div class="modal-header text-light bg-dark text-center">
                            <h5 class="modal-title text-center" id="exampleModalLabel">Félicitations !</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h4 class="text-success mt-3">Votre commande a été validée.</h4><br>
                            <br>
                            <h5>Montant total : <?php echo $totalPrice ?> €</h5><br>
                            <br>
                            Elle sera expédiée le <span class="font-weight-bold">
                                <?php echo date('d-m-Y', strtotime(date('d-m-Y') . ' + 3 days')); ?></span><br>
                            <br>
                            Merci pour votre confiance.
                        </div>
                        <div class="modal-footer">
                            <form action="index.php" method="post">
                                <input type="hidden" name="orderValidated" value="true">
                                <input type="submit" class="btn btn-secondary" value="Retour à l'accueil">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <?php
    include('./footer.php');
    ?>

</body>
<script>

</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

</html>