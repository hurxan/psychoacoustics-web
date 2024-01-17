<!doctype html>
<html lang="en">
<head>
    <?php session_start(); ?>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="files/logo.png">

    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--    <link rel ="stylesheet" href="css/test.css-->
    <?php //if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?><!--">-->

    <title>Psychoacoustics-web - Duration test</title>

    <script type="text/javascript" src="js/fetchTexts.js"></script>

    <script>
        // pass info from php session to js
        var carAmpDb = parseFloat(<?php echo $_SESSION["amplitude"]; ?>);
        var carDur = parseFloat(<?php echo $_SESSION["duration"]; ?>);
        var onRamp = parseFloat(<?php echo $_SESSION["onRamp"]; ?>);
        var offRamp = parseFloat(<?php echo $_SESSION["offRamp"]; ?>);
        var modAmpDb = parseFloat(<?php echo $_SESSION["modAmplitude"]; ?>);
        var modFreq = parseFloat(<?php echo $_SESSION["modFrequency"]; ?>);
        var modPhase = parseFloat(<?php echo $_SESSION["modPhase"]; ?>);
        var blocks = parseInt(<?php echo $_SESSION["blocks"]; ?>);
        var delta = parseFloat(<?php echo $_SESSION["delta"]; ?>);
        var nAFC = parseInt(<?php echo $_SESSION["nAFC"]; ?>);
        var ITI = parseInt(<?php echo $_SESSION["ITI"]; ?>);
        var ISI = parseInt(<?php echo $_SESSION["ISI"]; ?>);
        var feedback = <?php echo $_SESSION["checkFb"]; ?>;
        var saveSettings = <?php echo $_SESSION["saveSettings"]; ?>;
        var factor = parseFloat(<?php echo $_SESSION["factor"]; ?>);
        var secondFactor = parseFloat(<?php echo $_SESSION["secFactor"]; ?>);
        var reversals = parseInt(<?php echo $_SESSION["reversals"]; ?>);
        var secondReversals = parseInt(<?php echo $_SESSION["secReversals"]; ?>);
        var reversalThreshold = parseInt(<?php echo $_SESSION["threshold"]; ?>);
        var algorithm = <?php echo "'{$_SESSION["algorithm"]}'"; ?>;
        var currentBlock = parseInt(<?php if (isset($_SESSION["currentBlock"])) echo $_SESSION["currentBlock"] + 1; else echo "1"?>);
    </script>
    <script type="text/javascript"
            src="js/generatorSoundAndNoise.js<?php if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?>"
            defer></script>
    <script type="text/javascript"
            src="js/noisesModulation.js<?php if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?>"
            defer></script>
</head>

<body>
<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

<div class="container">
    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-md-4 bg-light p-5 rounded-4 border mt-5" id="StartingWindow">
            <h2 class="text-center mb-5">Ready?</h2>
            <div class="d-grid">
                <button type="button" class="btn btn-lg btn-success btn-block" id="start" onclick="start()">
                    Let's start!
                </button>
            </div>
        </div>

        <div class="col-12 col-md-6 bg-light p-5 rounded-4 border mt-5" id="PlayForm" style="display: none">
            <div class="row gy-3 justify-content-between align-items-center">
                <h2 class="col-12 text-center mb-3" id="answerWhitenoiseamplitudemodulationdetection">Which is the modulated noise?</h2>
                <?php
                $colors = ["#198754", "#dc3545", "#0d6efd", "#e0b000", "#a000a0", "#ff8010", "#50a0f0", "#703000", "#606090"];
                for ($i = 1; $i <= intval($_SESSION['nAFC']); $i++) { ?>
                    <div class="col-12 col-sm-4 d-grid">
                        <?php echo "<button type='button' class='btn btn-lg btn-success' style='background-color:" . $colors[($i - 1) % count($colors)] . "; border-color: " . $colors[($i - 1) % count($colors)] . ";' id='button{$i}' onclick = 'select({$i})' disabled>{$i}° sound</button>"; ?>
                    </div>
                <?php }
                ?>
            </div>
        </div>
    </div>
    <div class="row justify-content-center align-items-center">
        <div class='col-12 col-md-6 alert alert-danger mt-5' id="wrong" style="display: none">Wrong!</div>
        <div class='col-12 col-md-6 alert alert-success mt-5' id="correct" style="display: none">Correct!</div>
    </div>
<!--    <button type="button" class="btn btn-outline-secondary btn-lg rounded-4 position-fixed bottom-0 end-0 m-5"-->
<!--            id="downloadData" onclick="downloadData('nmod')" style="display: none" disabled>-->
<!--        Download Data (only for debug!)-->
<!--    </button>-->
</div>
</body>
</html>

