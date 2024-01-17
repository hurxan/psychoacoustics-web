<!doctype html>
<html lang="en">
<head>
    <?php
    session_start();
    $_SESSION['version'] = 4; //change this number to force cache update
    ?>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="files/logo.png">

    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet"
          href="css/staircaseStyle.css<?php if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?>">

    <script type="text/javascript" src="js/fetchTexts.js"></script>

    <script type="text/javascript"
            src="js/funzioni.js<?php if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?>"></script>

    <title>Psychoacoustics-web</title>

</head>

<body>
<img src="files/wallpaper1.jpg" class="wallpaper">
<!-- Barra navigazione -->
<nav class="navbar navbar-dark bg-dark shadow-lg text-white">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="files/logo.png" alt="" width="25" height="25" class="d-inline-block align-text-top">
            <span id="menuTitle">PSYCHOACOUSTICS-WEB</span>
        </a>
        <form class="d-flex align-items-center">
            <?php
            if (!isset($_SESSION["usr"])) {
                if (isset($_SESSION["idGuest"]))
                    unset($_SESSION["idGuest"]);
                ?>
                <button id="menuSignUp" class="btn btn-outline-light me-3" type="button" onclick="location.href='register.php'">
                    Sign Up
                </button>
                <button id="menuLogIn" class="btn btn-outline-light me-3" type="button" onclick="location.href='login.php'">
                    Log In
                </button>
            <?php } else { ?>
                <label id="menuWelcome" class='text-white navbar-text me-3'>Welcome <?php echo $_SESSION['usr'] ?></label>
                <button id="menuYourTests" class="btn btn-outline-light me-3" type="button" onclick="location.href='yourTests.php'">
                    Your tests
                </button>
                <button id="menuLogOut" class="btn btn-outline-light me-3" type="button" onclick="location.href='php/logout.php'">
                    Log Out
                </button>
                <a class='settings navbar-text' href='userSettings.php'>
                    <i class='material-icons rotate text-white'>settings</i>
                </a>
            <?php } ?>
        </form>
    </div>
</nav>

<?php
if (isset($_GET['err'])) {
    if ($_GET['err'] == 1)
        echo "<div class='alert alert-danger'>Access denied, attempt logged</div>";
    if ($_GET['err'] == 2)
        echo "<div class='alert alert-danger'>Something went wrong</div>";
    if ($_GET['err'] == "db")
        echo "<div class='alert alert-danger'>Something went wrong while trying to connect to the database, please contact an administator</div>";
}
?>
<!-- Descrizione e presentazione -->

<div class="container">
    <h1 class="text-center my-5" id="indexTitle">
        Welcome to PSYCHOACOUSTICS-WEB
    </h1>
    <p class="p-5 bg-white-transparent rounded-5 fs-5" id="indexDescription">
        PSYCHOACOUSTICS-WEB is a web developed tool to measure auditory sensory thresholds for a
        variety of classic tasks. You can run each test as a guest or you can create your personal
        account and costumize the toolbox for your own research. Please refer to the <a
                href="files/PSYCHOACOUSTICS-WEB_manual.pdf">instruction
            manual</a> for further info on how to use the toolbox.
        <br><br>
        The tasks estimate the intensity, frequency and duration discrimination threshold for a pure tone.
        In the tasks you can set up the characteristics of the tone as well as
        the characteristics of the adaptive staircase. The tests implement the
        following adaptive staircase algorithms: simple up-down, 2-down 1-up,
        and 3-down 1-up. Please refer to Levitt (JASA, 1971) for more info on
        these adaptive staircases.
    </p>
</div>

<!-- cards -->
<div class="container my-5">
    <div class="row row-cols-1 row-cols-lg-3 gx-4 gy-3">
        <div class="col">
            <a href="demographicData.php?test=amp" class="text-decoration-none text-white">
                <div class="bg-light rounded-3 p-4 text-center shadow-lg card bg-dark">
                    <h5 class="bg-light-title" id="indexButtonPuretoneintensitydiscrimination">Pure tone intensity discrimination</h5>
                    <p id="indexButtonsDescription">Click here to run the test</p>
                </div>
            </a>
        </div>


        <div class="col">
            <a href="demographicData.php?test=freq" class="text-decoration-none text-white">
                <div class="bg-light rounded-3 p-4 text-center shadow-lg card bg-dark">
                    <h5 class="bg-light-title" id="indexButtonPuretonefrequencydiscrimination">Pure tone frequency discrimination</h5>
                    <p id="indexButtonsDescription">Click here to run the test</p>
                </div>
            </a>
        </div>


        <div class="col">
            <a href="demographicData.php?test=dur" class="text-decoration-none text-white">
                <div class="bg-light rounded-3 p-4 text-center shadow-lg card bg-dark">
                    <h5 class="bg-light-title" id="indexButtonPuretonedurationdiscrimination">Pure tone duration discrimination</h5>
                    <p id="indexButtonsDescription">Click here to run the test</p>
                </div>
            </a>
        </div>

        <div class="col">
            <a href="demographicData.php?test=nmod" class="text-decoration-none text-white">
                <div class="bg-light rounded-3 p-4 text-center shadow-lg card bg-dark">
                    <h5 class="bg-light-title" id="indexButtonWhitenoiseamplitudemodulationdetection">White noise amplitude modulation detection</h5>
                    <p id="indexButtonsDescription">Click here to run the test</p>
                </div>
            </a>
        </div>

        <div class="col">
            <a href="demographicData.php?test=gap" class="text-decoration-none text-white">
                <div class="bg-light rounded-3 p-4 text-center shadow-lg card bg-dark">
                    <h5 class="bg-light-title" id="indexButtonWhitenoisegapdetection">White noise gap detection</h5>
                    <p id="indexButtonsDescription">Click here to run the test</p>
                </div>
            </a>
        </div>

        <div class="col">
            <a href="demographicData.php?test=ndur" class="text-decoration-none text-white">
                <div class="bg-light rounded-3 p-4 text-center shadow-lg card bg-dark">
                    <h5 class="bg-light-title" id="indexButtonWhitenoisedurationdiscrimination">White noise duration discrimination</h5>
                    <p id="indexButtonsDescription">Click here to run the test</p>
                </div>
            </a>
        </div>
    </div>
</div>
</body>
</html>
