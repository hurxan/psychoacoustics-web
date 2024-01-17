<!doctype html>
<html lang="en">
<head>
    <?php
    session_start();
    include "php/config.php";
    if (!isset($_GET["test"]) && !isset($_SESSION['test']))
        header("Location: index.php");
    ?>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="files/logo.png">

    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="css/staircaseStyle.css<?php if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?>">

    <script type="text/javascript" src="js/fetchTexts.js"></script>

    <title>Psychoacoustics-web - Test settings</title>

    <?php
    try {
        $conn = new mysqli($host, $user, $password, $dbname);
        if ($conn->connect_errno)
            throw new Exception('DB connection failed');
        mysqli_set_charset($conn, "utf8");

        $sql = "SELECT Type, Amplitude as amp, Frequency as freq, Duration as dur, OnRamp as onRamp, OffRamp as offRamp, ISI, blocks, Delta, nAFC, 
						Factor as fact, Reversal as rev, SecFactor as secfact, SecReversal as secrev, Feedback as feedback,
						Threshold as thr, Algorithm as alg, ModAmplitude as modAmp, ModFrequency as modFreq, ModPhase as modPhase
						
						FROM test
						
						WHERE Guest_ID='{$_SESSION['test']['guest']}' AND Test_count='{$_SESSION['test']['count']}'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        if ($row['Type'] == 'PURE_TONE_INTENSITY')
            $type = "amplitude";
        else if ($row['Type'] == 'PURE_TONE_FREQUENCY')
            $type = "frequency";
        else if ($row['Type'] == 'PURE_TONE_DURATION')
            $type = "duration";
        else if ($row['Type'] == 'WHITE_NOISE_GAP')
            $type = "gap";
        else if ($row['Type'] == 'WHITE_NOISE_DURATION')
            $type = "nduration";
        else if ($row['Type'] == 'WHITE_NOISE_MODULATION')
            $type = "nmodulation";
    } catch (Exception $e) {
        header("Location: index.php?err=db");
    }
    ?>
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
            src="js/generatorSoundAndNoise.js<?php if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?>"
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
        You will now do an acoustic test that will measure your sensibility to the <?php
        if ($type == 'amplitude')
            echo "amplitude of a sound";
        else if ($type == 'frequency')
            echo "frequency of a sound";
        else if ($type == 'duration')
            echo "duration of a sound";
        else if ($type == 'gap')
            echo "detect a gap of a noise";
        else if ($type == 'nduration')
            echo "duration of a noise";
        else if ($type == 'nmodulation')
            echo "amplitude modulation of a noise";
        ?>.
        <br><br>
        During the test you will be asked a series of questions. In each question you will
        hear <?php echo $row['nAFC']; ?> sounds and will have to choose which of them was the <?php
        if ($type == 'amplitude')
            echo "loudest";
        else if ($type == 'frequency')
            echo "highest pitch";
        else if ($type == 'duration')
            echo "longest";
        else if ($type == 'gap')
            echo "one with a gap in the middle";
        else if ($type == 'nduration')
            echo "longest";
        else if ($type == 'nmodulation')
            echo "modulated";
        ?>.
        <br><br><?php
        if ($row['nAFC'] > 2)
            echo "Only o";
        else
            echo "O";
        ?>ne of the sounds will be <?php
        if ($type == 'amplitude')
            echo "louder";
        else if ($type == 'frequency')
            echo "higher pitch";
        else if ($type == 'duration')
            echo "longer";
        else if ($type == 'gap')
            echo "with a gap in the middle";
        else if ($type == 'nduration')
            echo "longer";
        else if ($type == 'nmodulation')
            echo "modulated";
        ?> than the other<?php
        if ($row['nAFC'] > 2)
            echo "s";
        ?>. It will have a <?php
        if ($type == 'amplitude')
            echo "n intensity of " . (floatval($row['amp']) + floatval($row['Delta'])) . " dB";
        else if ($type == 'frequency')
            echo "frequency of " . (floatval($row['freq']) + floatval($row['Delta'])) . " Hz";
        else if ($type == 'duration')
            echo "duration of " . (floatval($row['dur']) + floatval($row['Delta'])) . " ms";
        else if ($type == 'gap')
            echo "gap of " . floatval($row['Delta']) . " ms";
        else if ($type == 'nduration')
            echo "duration of " . (floatval($row['dur']) + floatval($row['Delta'])) . " ms";
        else if ($type == 'nmodulation')
            echo "modulation of " . floatval($row["modAmp"]) . " dB";
        ?>, while the other<?php
        if ($row['nAFC'] > 2)
            echo "s";
        ?> will have a <?php
        if ($type == 'amplitude')
            echo "n intensity of " . floatval($row['amp']) . " dB (the maximum amplitude is 0dB, which corresponds to 
							'1' in decimal, while a negative number in decibel corresponds to a number near '0' in decimal 
							(the higher is the absolute value, the nearest is to 0))";
        else if ($type == 'frequency')
            echo "frequency of " . floatval($row['freq']) . " Hz";
        else if ($type == 'duration')
            echo "duration of " . floatval($row['dur']) . " ms";
        else if ($type == 'gap')
            echo "no gap";
        else if ($type == 'nduration')
            echo "duration of " . floatval($row['dur']) . " ms";
        else if ($type == 'nmodulation')
            echo "no modulation";
        ?>.
        <br><br>
        The number of questions that will be asked depends on the number of reversals that was set.
        Every time you give a correct answer and then a wrong answer or vice versa, there will be a reversal (the
        correctness direction changes).
        In this case there will be <?php echo $row['rev']; ?> reversal<?php if ($row['rev'] > 1) echo "s"; ?> using a
        factor of
        <?php echo $row['fact']; ?> and then <?php echo $row['secrev']; ?>
        reversal<?php if ($row['secrev'] > 1) echo "s"; ?> using a factor of
        <?php echo $row['secfact']; ?>.
        <br>
        But what are these factors?
        <br>
        Every time you give <?php
        if ($row['alg'] == 'SimpleUpDown')
            echo "1";
        else if ($row['alg'] == 'TwoDownOneUp')
            echo "2";
        else if ($row['alg'] == 'ThreeDownOneUp')
            echo "3";
        ?> right answer<?php
        if ($row['alg'] != 'SimpleUpDown')
            echo "s";
        ?>
        or a wrong answer, the difficulty changes. The right answers make the test harder, reducing the difference
        between the
        correct sound and the wrong one, the wrong answers make it easier, increasing the difference.
        <br><br>
        The test will be repeated <?php echo $row['blocks']; ?> time<?php if ($row['blocks'] > 1) echo "s"; ?>. Each
        time you will see your threshold, a number that summarizes your level of sensibility. It is calculated only
        using the last <?php echo $row['thr']; ?> reversal<?php if ($row['rev'] > 1) echo "s"; ?>.
        <br>
        At the end you can download a csv file that summarizes all the tests done (and also a file with the details of
        each test, if you are logged in with an account).
        <br><br>

        <?php if ($row["feedback"]) echo " You will receive a feedback to advice whether the response is correct or not."; ?>

    </p>

    <div class="d-flex align-items-center">
        <h5 class="m-0">Test preview</h5>
        <button id="playTest" class="btn btn-dark ms-4" onclick="random()">Start test preview</button>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="bg-white border-2 rounded-4 p-5 mt-4 mx-auto">
                    <form action="" class="" id="PlayForm">
                        <h1 class="text-center mb-4"><?php
                            if ($type == 'amplitude')
                                echo "Which is the loudest tone?";
                            else if ($type == "frequency")
                                echo "Which is the highest pitch tone?";
                            else if ($type == "duration")
                                echo "Which is the longest tone?";
                            else if ($type == "gap")
                                echo "Which is the noise with the gap?";
                            else if ($type == "nduration")
                                echo "Which is the longest noise?";
                            else if ($type == "nmodulation")
                                echo "Which is the modulated noise?";
                            ?></h1>
                        <div class="row gy-3 justify-content-between align-items-center">
                            <?php
                            $colors = ["#198754", "#dc3545", "#0d6efd", "#e0b000", "#a000a0", "#ff8010", "#50a0f0", "#703000", "#606090"];
                            for ($i = 1; $i <= intval($row['nAFC']); $i++) { ?>
                                <div class="col-12 col-sm-4 d-grid">
                                    <?php echo "<button type='button' class='btn btn-lg btn-success' style='background-color:" . $colors[($i - 1) % count($colors)] . "; border-color:" . $colors[($i - 1) % count($colors)] . "' id='button{$i}' onclick = 'select({$i})' disabled>{$i}° sound</button>"; ?>
                                </div>
                            <?php } ?>
                        </div>
                    </form>
                </div>
                <div class='alert w-50 mx-auto' id="alert"></div>
            </div>
        </div>
    </div>

    <form action="php/soundSettingsValidation.php" name="Settings" method="post">
        <div class="container">
            <div class="row row-cols-2 gy-2">
                <div class="col d-grid">
                    <button type="button" class="btn btn-primary btn-lg btn-red"
                            onclick="location.href='demographicData.php'">
                        BACK
                    </button>
                </div>
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
