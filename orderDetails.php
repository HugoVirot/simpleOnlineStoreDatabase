<?php
session_start();
include('functions.php');

include('./head.php');
?>


<body>
    <header>
        <?php
        include('header.php');

        if(isset($_POST['livraison'])){
            $_SESSION['displayedOrderDelivery'] = $_POST['livraison'];
        }
        ?>
    </header>


    <main>
        <div class="container-fluid pb-3">
            <div class="row text-center">
                <img id="watchPhoto" src="images/watchturquoise.jpg" style="width: 100vw">
            </div>
        </div>

        <div class="container mt-3 text-center">
            <h3>Détails commande <?php echo $_POST['orderNumber'] ?></h3>
        </div>

        <div class="container-fluid p-5">

            <div class="row text-center mb-5 justify-content-center">
                <h5>Date et heure : <b><?= $_POST['orderDate']?>
                    </b> - montant total : <b><?php echo $_POST['orderTotal'] ?> €</b></h4>
            </div>
            <div class="row text-center justify-content-center">
                <?php displayOrderArticles(getOrderArticles($_POST['orderId'])); ?>
            </div>
        </div>

        <div class="container text-center">
            <a href="account.php">
                <button class="btn btn-dark">Retour au compte</button>
            </a>
        </div>
    </main>


    <?php
    include('./footer.php');
    ?>

</body>

</html>