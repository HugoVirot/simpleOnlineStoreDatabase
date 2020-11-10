<?php
session_start();

include('functions.php');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (isset($_POST['orderValidated'])) {
    saveOrder(calculateTotalPrice());
    emptyCart(false);
}

if (isset($_POST['userCreated'])) {
    createUser();
}

if (isset($_POST['userConnection'])) {
    if (!isset($_SESSION['id'])) {
        logIn();
    } else {
        echo "<script>alert(\"Vous êtes déjà connecté !\")</script>";
    }
}

if(isset($_POST['logout'])){
    logOut();
}

?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arinfo, montres intemporelles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body>

    <?php
    include('./header.php');
    ?>

    <main>

        <div class="container-fluid pb-3">
            <div class="row text-center">
                <img id="watchPhoto" src="images/watch.jpg" style="width: 100vw">
            </div>
        </div>

        <div class="container p-5">
            <div class="row text-center justify-content-center">
                <?php
                showArticles();
                ?>
            </div>
        </div>

    </main>

    <?php
    include('./footer.php');
    ?>

</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

</html>