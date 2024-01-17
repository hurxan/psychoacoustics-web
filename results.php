<!doctype html>
<html lang="en">

<head>
    <?php
    session_start();
    ?>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="files/logo.png">

    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="css/staircaseStyle.css<?php if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?>">

    <title>Psychoacoustics-web - Test results</title>

</head>
<body>
<?php
//controllo errori
if (isset($_GET['err'])) {
    if ($_GET['err'] == "1")
        echo "<div class='alert alert-danger' style='float:left; width:95%'>'Save result' wasn't checked but 'Save settings' was, Settings can't be saved without saving the results
							<br>Result and settings weren't saved</div>";
}
?>
<div class="container mt-5">
    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-md-7 border bg-light rounded-4 p-5">
            <h2>Your threshold is
                <?php
                if (isset($_SESSION['score']))
                    if (strrpos($_SESSION['score'], ";"))
                        echo substr($_SESSION['score'], strrpos($_SESSION['score'], ";") + 1);
                    else
                        echo $_SESSION['score'];
                ?>
            </h2>
            <div class="container-fluid mt-5">
                <div class="row row-cols-1 row-cols-lg-3 align-items-center justify-content-between g-2">
                    <?php
                    if (isset($_GET['continue'])) {
                        if ($_GET['continue'] == 0) {
                            if (isset($_SESSION['usr'])) { ?>
                                <div class="col d-grid">
                                    <button type='button' class='btn btn-primary btn-lg btn-red'
                                            onclick='location.href="php/download.php?format=complete"'>
                                        Download data
                                    </button>
                                </div>
                            <?php } ?>
                            <div class="col d-grid">
                                <button type='button' class='btn btn-primary btn-lg btn-red'
                                        onclick='location.href="php/download.php?format=reduced"'>
                                    Download data (thresholds only)
                                </button>
                            </div>
                            <div class="col d-grid">
                                <button type='button' class='btn btn-primary btn-lg btn-red'
                                        onclick='location.href="index.php"'>
                                    Home
                                </button>
                            </div>
                            <?php
                        } else {
                            $page = "test.php";
                            if ($_SESSION['type'] == "PURE_TONE_FREQUENCY")
                                $page = "freq" . $page;
                            if ($_SESSION['type'] == "PURE_TONE_INTENSITY")
                                $page = "amp" . $page;
                            if ($_SESSION['type'] == "PURE_TONE_DURATION")
                                $page = "dur" . $page;
                            if ($_SESSION['type'] == "WHITE_NOISE_GAP")
                                $page = "gap" . $page;
                            if ($_SESSION['type'] == "WHITE_NOISE_DURATION")
                                $page = "ndur" . $page;
                            if ($_SESSION['type'] == "WHITE_NOISE_MODULATION")
                                $page = "nmod" . $page;
                            ?>
                            <div class='col d-grid'>
                                <button type='button' class='btn btn-primary btn-lg btn-red'
                                        onclick='location.href="<?php echo $page; ?>"'>
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
