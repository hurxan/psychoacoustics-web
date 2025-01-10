<?php
session_start();
include_once "php/config.php";
include_once "php/helpers/utils.php";


$_SESSION['version'] = 1; //change this number to force cache update

//session gets destroyed to clear cached data but logged user data is kept
if (isUserLogged()) {
    $logged = $_SESSION['loggedUser'];
}

$_SESSION = array(); //session get resetted
session_destroy();
session_start();

if (isset($logged)) { //account data are preserved
    $_SESSION['loggedUser'] = $logged;
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet"
        href="css/staircaseStyle.css<?php if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?>">

    <script type="text/javascript" src="js/fetchTexts.js"></script>

    <script type="text/javascript"
        src="js/funzioni.js<?php if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?>"></script>

    <title>Psychoacoustics-web</title>
</head>

<body>
    <img src="view_modules/serve_image.php" class="wallpaper">

    <!-- Navigation Bar -->
    <?php include_once 'view_modules/navbar.php'; ?>


    <?php
    //this section read the error code returned by other php pages and display the proper messages
    
    if (isset($_GET['err'])) {
        $errorCode = $_GET['err'] ;

        if ($errorCode == 1)
            echo "<div class='alert alert-danger'>Access denied, this function is exclusive to Administrators</div>";
        if ($errorCode == 2)
            echo "<div class='alert alert-danger'>Something went wrong</div>";
        if ($errorCode == "db")
            echo "<div class='alert alert-danger'>Something went wrong while trying to connect to the database, please contact an administator</div>";
    }
    ?>

    <!-- Description and Presentation -->
    <div class="container">

        <!-- welcome title -->
        <h1 class="text-center my-5" id="indexTitle">
            Welcome to PSYCHOACOUSTICS-WEB 
        </h1>

        <!-- Site Description -->
        <p class="p-5 bg-white-transparent rounded-5 fs-5">
            PSYCHOACOUSTICS-WEB is a web developed tool to measure auditory sensory thresholds for a
            variety of classic tasks. You can run each test as a guest or you can create your personal
            account and costumize the toolbox for your own research. Please refer to the
            <a href="files/PSYCHOACOUSTICS-WEB_manual.pdf">instruction manual</a> 
            for further info on how to use the toolbox.
            <br><br>
            In the tasks you can set up the characteristics of the sounds as well as
            the characteristics of the adaptive staircase. The tests implement the 2-down 1-up,
            and 3-down 1-up algorithms suggested by Levitt (JASA, 1971).
        </p>
    </div>

    <!-- cards -->
    <div class="container my-5">
        <div class="row row-cols-1 row-cols-lg-3 gx-4 gy-3">
            
        
            <!-- Pure tone intensity discrimination card --> 
            <div class="col">
                <a href="demographicData.php?test=amp" class="text-decoration-none text-white">
                    <div class="bg-light rounded-3 p-4 text-center shadow-lg card bg-dark">
                        <h5 class="bg-light-title" id="indexButtonPuretoneintensitydiscrimination">Pure tone intensity discrimination</h5>
                        <p id="indexButtonsDescription">Click here to run the test</p>
                    </div>
                </a>
            </div>

            <!-- Pure tone frequency discrimination card --> 
            <div class="col">
                <a href="demographicData.php?test=freq" class="text-decoration-none text-white">
                    <div class="bg-light rounded-3 p-4 text-center shadow-lg card bg-dark">
                        <h5 class="bg-light-title" id="indexButtonPuretonefrequencydiscrimination">Pure tone frequency discrimination</h5>
                        <p id="indexButtonsDescription">Click here to run the test</p>
                    </div>
                </a>
            </div>

            <!-- Pure tone duration discrimination card --> 
            <div class="col">
                <a href="demographicData.php?test=dur" class="text-decoration-none text-white">
                    <div class="bg-light rounded-3 p-4 text-center shadow-lg card bg-dark">
                        <h5 class="bg-light-title" id="indexButtonPuretonedurationdiscrimination">Pure tone duration discrimination</h5>
                        <p id="indexButtonsDescription">Click here to run the test</p>
                    </div>
                </a>
            </div>

            <!-- White noise amplitude modulation detection card --> 
            <div class="col">
                <a href="demographicData.php?test=nmod" class="text-decoration-none text-white">
                    <div class="bg-light rounded-3 p-4 text-center shadow-lg card bg-dark">
                        <h5 class="bg-light-title" id="indexButtonWhitenoiseamplitudemodulationdetection">White noise amplitude modulation detection</h5>
                        <p id="indexButtonsDescription">Click here to run the test</p>
                    </div>
                </a>
            </div>

            <!-- White gap detection card --> 
            <div class="col">
                <a href="demographicData.php?test=gap" class="text-decoration-none text-white">
                    <div class="bg-light rounded-3 p-4 text-center shadow-lg card bg-dark">
                        <h5 class="bg-light-title" id="indexButtonWhitenoisegapdetection">White noise gap detection</h5>
                        <p id="indexButtonsDescription">Click here to run the test</p>
                    </div>
                </a>
            </div>

            <!-- White noise duration discrimination card --> 
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


    <?php 
    //comment this function to stop collecting traffic data
    trackCountryTraffic() ?>
</body>

</html>