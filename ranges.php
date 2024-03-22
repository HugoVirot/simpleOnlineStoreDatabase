<?php
session_start();

include('functions.php');

if (isset($_POST['articleToDisplay'])) {

    $articleToDisplayId = $_POST['articleToDisplay'];
    $articleToDisplay = getArticleFromId($articleToDisplayId);
}
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
                <img id="watchPhoto" src="images/watchesrange.jpg" style="width: 100vw">
            </div>
        </div>

        <div class="container p-4 text-center">
            <h3 class="">Nos Gammes</h3>
        </div>

        <?php 
        showRanges(getRanges());
        ?>

    </main>

    <?php
    include('./footer.php');
    ?>

</body>

</html>