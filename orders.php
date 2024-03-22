<?php
session_start();
include('functions.php');

include('./head.php');
?>


<body>
    <header>
        <?php
        include('header.php');
        ?>
    </header>


    <main>
        <div class="container-fluid pb-3">
            <div class="row text-center">
                <img id="watchPhoto" src="images/watchturquoise.jpg" style="width: 100vw">
            </div>
        </div>

        <div class="container mt-3 text-center">
            <h3>Mes commandes</h3>
        </div>

        <div class="container-fluid p-5">
            <div class="row text-center justify-content-center">
                <?php displayOrders(getOrders()); ?>
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