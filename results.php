<?php
session_start();
include_once "php/helpers/utils.php";

if (!isset($_SESSION['idGuestTest']))
    header("Location: index.php");


$currentBlock = $_SESSION['currentBlock'];

if (isset($_SESSION['geometric_score'])) {
    $score = explode(";", $_SESSION['geometric_score']);
    $geometricScore = $score[$currentBlock - 1];
}

if (isset($_SESSION['score'])) {
    $score = explode(";", $_SESSION['score']);
    $score = $score[$currentBlock - 1];
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="files/logo.png">

    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/staircaseStyle.css<?php if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?>">

    <title>Psychoacoustics-web - Test results</title>
</head>


<body>

    <?php
    if (isset($_GET['err'])) {
        if ($_GET['err'] == "1")
            echo "<div class='alert alert-danger' style='float:left; width:95%'>'Save result' wasn't checked but 'Save settings' was, Settings can't be saved without saving the results
							<br>Result and settings weren't saved</div>";
    }
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-12 col-md-7 border bg-light rounded-4 p-5">

                <h1>Your user ID is: #<?php if (isset($_SESSION["saveData"]))
                                                echo $_SESSION['idGuestTest'];
                                            else echo "anonymous";
                                        ?></h1>

                <h2>Your threshold is:</h2>
                <p></p>

                <h2>
                    <?php
                    echo $geometricScore
                    ?> (Geometric Mean)
                </h2>

                <p></p>
                <h2>
                    <?php
                    echo $score;
                    ?> (Arithmetic Mean)
                </h2>

                <div class="container-fluid mt-5">
                    <div class="row row-cols-1 row-cols-lg-3 align-items-center justify-content-between g-2">
                        <?php
                        if (isset($_GET['continue'])) {
                            //if continue = 1 this is not the last block of tests
                            if ($_GET['continue'] == 0) {

                                //only logged user can download the complete csv
                                if (isUserLogged()) { ?>
                                    <div class="col d-grid">
                                        <button type='button' class='btn btn-primary btn-lg btn-red' onclick='location.href="php/quick_download.php?format=complete"'>
                                            Download data
                                        </button>
                                    </div>
                                <?php } ?>

                                <div class="col d-grid">
                                    <button type='button' class='btn btn-primary btn-lg btn-red' onclick='location.href="php/quick_download.php?format=reduced"'>
                                        Download data (thresholds only)
                                    </button>
                                </div>

                                <div class="col d-grid">
                                    <button type='button' class='btn btn-primary btn-lg btn-red' onclick='location.href="index.php"'>
                                        Home
                                    </button>
                                </div>

                            <?php
                            } else {
                                //this display the CONTINUE button to procede with the next block
                                $page = "takeTest.php?type={$_SESSION['testTypeCmp']}";

                            ?>
                                <div class='col d-grid'>
                                    <button type='button' class='btn btn-primary btn-lg btn-red' onclick='location.href="<?php echo $page; ?>"'>
                                        Continue
                                    </button>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>