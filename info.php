<?php
session_start();
include "php/config.php";
include_once "php/db_connect.php";
if (!isset($_GET["test"]) && !isset($_SESSION['referralTest']))
    header("Location: index.php");


try {
    $conn = connectdb();

    $sql = "SELECT Type, Amplitude as amp, Frequency as freq, Duration as dur, OnRamp as onRamp, OffRamp as offRamp, ISI, blocks, Delta, nAFC, 
						Factor as fact, Reversal as rev, SecFactor as secfact, SecReversal as secrev, Feedback as feedback,
						Threshold as thr, Algorithm as alg, ModAmplitude as modAmp, ModFrequency as modFreq, ModPhase as modPhase
						
				FROM test
						
				WHERE Guest_ID='{$_SESSION['referralTest']['guest']}' AND Test_count='{$_SESSION['referralTest']['count']}'";

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
} catch (Exception $e) {
    header("Location: index.php?err=db");
}


if ($row['Type'] == 'PURE_TONE_INTENSITY') {
    $type = "amplitude";
    $testMsg_1 =  "amplitude of a sound";
    $testMsg_2 = "loudest";
    $testMsg_3 = "Which is the loudest tone?";
} else if ($row['Type'] == 'PURE_TONE_FREQUENCY') {
    $type = "frequency";
    $testMsg_1 =  "frequency of a sound";
    $testMsg_2 = "highest pitch"; 
    $testMsg_3 = "Which is the highest pitch tone?";
} else if ($row['Type'] == 'PURE_TONE_DURATION') {
    $type = "duration"; 
    $testMsg_1 =  "duration of a sound";
    $testMsg_2 = "longest";
    $testMsg_3 = "Which is the longest tone?";
} else if ($row['Type'] == 'WHITE_NOISE_GAP') {
    $type = "gap";
    $testMsg_1 =  "detect a gap of a noise";
    $testMsg_2 = "one with a gap in the middle";
    $testMsg_3 = "Which is the noise with the gap?";
} else if ($row['Type'] == 'WHITE_NOISE_DURATION') {
    $type = "nduration";
    $testMsg_1 =  "duration of a noise";
    $testMsg_2 = "longest";
    $testMsg_3 = "Which is the longest noise?";
} else if ($row['Type'] == 'WHITE_NOISE_MODULATION') {
    $type = "nmodulation";
    $testMsg_1 =  "amplitude modulation of a noise";
    $testMsg_2 = "modulated";
    $testMsg_3 = "Which is the modulated noise?";
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
    <script type="text/javascript" src="js/fetchTexts.js"></script>
    <title>Psychoacoustics-web - Test settings</title>


    <script>
        var type = "<?php echo $type; ?>";
        var amp = parseFloat(<?php echo $row["amp"]; ?>);
        var freq = parseFloat(<?php echo $row["freq"]; ?>);
        var dur = parseFloat(<?php echo $row["dur"]; ?>);
        if (type == "nmodulation") {
            var modAmp = parseFloat(<?php echo $row["modAmp"]; ?>);
            var modFreq = parseFloat(<?php echo $row["modFreq"]; ?>);
            var modPhase = parseFloat(<?php echo $row["modPhase"]; ?>);
        }
        var onRamp = parseFloat(<?php echo $row["onRamp"]; ?>);
        var offRamp = parseFloat(<?php echo $row["offRamp"]; ?>);
        var delta = parseFloat(<?php echo $row["Delta"]; ?>);
        var ISI = parseInt(<?php echo $row["ISI"]; ?>);
        var nAFC = parseInt(<?php echo $row["nAFC"]; ?>);
        var feedback = <?php echo $row["feedback"]; ?>;

    </script>
    <script type="text/javascript"
        src="js/test_common/generatorSoundAndNoise.js<?php if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?>"
        defer></script>
    <script type="text/javascript"
        src="js/testPreview.js<?php if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?>"
        defer></script>
</head>

<body>
    <img src="files/wallpaper1.jpg" class="wallpaper">

    <div class="container my-5 p-5 rounded rounded-4 bg-white-transparent">
        <h2 class="">Hi <?php echo $_SESSION['name']; ?></h2>
        <p id="infoDescription" class="">

            You will now do a test that will measure your sensibility to the <?php echo $testMsg_1 ?>.
            <br><br>
            The test unfolds along several trials. In each trial you will hear <?php echo $row['nAFC']; ?>
            sound-intervals and will have to choose which of them was the <?php echo $testMsg_2 ?>.
            <br><br>

            <?php if ($row["feedback"]) echo " Click \"Start test preview\" to try some familiarization trials."; ?>

        </p>

        <div class="d-flex align-items-center">
            <h5 class="m-0">Test preview</h5>
            <button id="playTest" class="btn btn-dark ms-4" onclick="createRandomizedOutput()">Start test preview</button>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="bg-white border-2 rounded-4 p-5 mt-4 mx-auto">
                        <form action="" class="" id="PlayForm">

                            <h1 class="text-center mb-4">
                            <?php echo $testMsg_3 ?></h1>
                            <div class="row gy-3 justify-content-between align-items-center">
                                <?php
                                //a different color for every sound button
                                $colors = ["#198754", "#dc3545", "#0d6efd", "#e0b000", "#a000a0", "#ff8010", "#50a0f0", "#703000", "#606090"];
                                for ($i = 1; $i <= intval($row['nAFC']); $i++) { ?>
                                    <div class="col-12 col-sm-4 d-grid">
                                        <?php echo "<button type='button' class='btn btn-lg btn-success' style='background-color:" . $colors[($i - 1) % count($colors)] . "; border-color:" . $colors[($i - 1) % count($colors)] . "' id='button{$i}' onclick = 'computeResponse({$i})' disabled>{$i}° sound</button>"; ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                    <div class='alert w-50 mx-auto' id="alert"></div>
                </div>
            </div>
        </div>

        <form action="php/sound_settings_validation.php" name="Settings" method="post">
            <div class="container">
                <div class="row gy-2">

                    <div class="col d-grid">
                        <button type="submit" class="btn btn-primary btn-lg btn-red">
                            START
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</body>

</html>