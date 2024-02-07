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

    <title>Psychoacoustics-web - Test settings</title>

</head>
<body>
<?php
//controllo errori
if (isset($_GET['err'])) {
    if ($_GET['err'] == "amp1")
        echo "<div class='alert alert-danger'>The amplitude field is required</div>";
    else if ($_GET['err'] == "amp2")
        echo "<div class='alert alert-danger'>The amplitude value must be a number</div>";
    else if ($_GET['err'] == "amp3")
        echo "<div class='alert alert-danger'>The amplitude value can't be a positive value (maximum is 0dB)</div>";
    else if ($_GET['err'] == "freq1")
        echo "<div class='alert alert-danger'>The frequency field is required</div>";
    else if ($_GET['err'] == "freq2")
        echo "<div class='alert alert-danger'>The frequency value must be a positive number</div>";
    else if ($_GET['err'] == "dur1")
        echo "<div class='alert alert-danger'>The duration field is required</div>";
    else if ($_GET['err'] == "dur2")
        echo "<div class='alert alert-danger'>The duration value must be a positive number</div>";
    else if ($_GET['err'] == "onRamp1")
        echo "<div class='alert alert-danger'>The onset ramp field is required</div>";
    else if ($_GET['err'] == "onRamp2")
        echo "<div class='alert alert-danger'>The onset ramp value must be a positive number</div>";
    else if ($_GET['err'] == "offRamp1")
        echo "<div class='alert alert-danger'>The offset ramp field is required</div>";
    else if ($_GET['err'] == "offRamp2")
        echo "<div class='alert alert-danger'>The offset ramp value must be a positive number</div>";
    else if ($_GET['err'] == "modAmp1")
        echo "<div class='alert alert-danger'>The modulator amplitude field is required</div>";
    else if ($_GET['err'] == "modAmp2")
        echo "<div class='alert alert-danger'>The modulator amplitude must be a negative number</div>";
    else if ($_GET['err'] == "modFreq1")
        echo "<div class='alert alert-danger'>The modulator frequency field is required</div>";
    else if ($_GET['err'] == "modFreq2")
        echo "<div class='alert alert-danger'>The modulator frecuency must be a positive number</div>";
    else if ($_GET['err'] == "modPhase1")
        echo "<div class='alert alert-danger'>The modulator phase field is required</div>";
    else if ($_GET['err'] == "modPhase2")
        echo "<div class='alert alert-danger'>The modulator phase must be a number</div>";
    else if ($_GET['err'] == "numblock1")
        echo "<div class='alert alert-danger'>The n. of blocks field is required</div>";
    else if ($_GET['err'] == "numblock2")
        echo "<div class='alert alert-danger'>The n. of blocks value must be a positive number</div>";
    else if ($_GET['err'] == "delta1")
        echo "<div class='alert alert-danger'>The delta field is required</div>";
    else if ($_GET['err'] == "delta2")
        echo "<div class='alert alert-danger'>The delta value must be a positive number</div>";
    else if ($_GET['err'] == "delta3")
        echo "<div class='alert alert-danger'>The delta value is too high</div>";
    else if ($_GET['err'] == "ITI1")
        echo "<div class='alert alert-danger'>The ITI field is required</div>";
    else if ($_GET['err'] == "ITI2")
        echo "<div class='alert alert-danger'>The ITI value must be a number greater than or equal to 1000</div>";
    else if ($_GET['err'] == "ISI1")
        echo "<div class='alert alert-danger'>The ISI field is required</div>";
    else if ($_GET['err'] == "ISI2")
        echo "<div class='alert alert-danger'>The ISI value must be a positive number</div>";
    else if ($_GET['err'] == "nAFC1")
        echo "<div class='alert alert-danger'>The nAFC field is required</div>";
    else if ($_GET['err'] == "nAFC2")
        echo "<div class='alert alert-danger'>The nAFC value must be a number greater than or equal to 2</div>";
    else if ($_GET['err'] == "nAFC3")
        echo "<div class='alert alert-danger'>The nAFC value can't be greater than 9</div>";
    else if ($_GET['err'] == "factor1")
        echo "<div class='alert alert-danger'>The factor field is required</div>";
    else if ($_GET['err'] == "factor2")
        echo "<div class='alert alert-danger'>The factor value must be a number grater than the second factor</div>";
    else if ($_GET['err'] == "secFactor1")
        echo "<div class='alert alert-danger'>The second factor field is required</div>";
    else if ($_GET['err'] == "secFactor2")
        echo "<div class='alert alert-danger'>The second factor value must be a number lower than the factor</div>";
    else if ($_GET['err'] == "rev1")
        echo "<div class='alert alert-danger'>The reversals field is required</div>";
    else if ($_GET['err'] == "rev2")
        echo "<div class='alert alert-danger'>The reversals value must be a positive number</div>";
    else if ($_GET['err'] == "secRev1")
        echo "<div class='alert alert-danger'>The second reversals field is required</div>";
    else if ($_GET['err'] == "secRev2")
        echo "<div class='alert alert-danger'>The second reversals value must be a positive number</div>";
    else if ($_GET['err'] == "threshold1")
        echo "<div class='alert alert-danger'>The reversal threshold field is required</div>";
    else if ($_GET['err'] == "threshold2")
        echo "<div class='alert alert-danger'>The reversal threshold value must be a positive number</div>";
    else if ($_GET['err'] == "threshold3")
        echo "<div class='alert alert-danger'>The reversal threshold value can't be more than the sum of 'Reversals' value and 'Second reversal' value</div>";
}

if (isset($_GET['test']))
    $type = $_GET['test'];

if (isset($_SESSION['usr'])) {
    try {
        $conn = new mysqli($host, $user, $password, $dbname);
        if ($conn->connect_errno)
            throw new Exception('DB connection failed');
        mysqli_set_charset($conn, "utf8");

        $sql = "SELECT test.Type as type, test.Amplitude as amp, test.Frequency as freq, test.Duration as dur, test.Delta as delta, test.OnRamp as onRamp, test.OffRamp as offRamp, test.blocks as blocks, 
								test.nAFC, test.ITI, test.ISI, test.Factor as fact, test.Reversal as rev, 
								test.SecFactor as secfact, test.SecReversal as secrev, test.Algorithm as alg, test.Feedback as fb,
                                test.ModAmplitude as modAmp, test.ModFrequency as modFreq, test.ModPhase as modPhase
												
								FROM test
								INNER JOIN account ON account.fk_GuestTest=test.Guest_ID AND account.fk_TestCount=test.Test_count
								
								WHERE account.Username='{$_SESSION['usr']}'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    } catch (Exception $e) {
        header("Location: index.php?err=dB");
    }
} else
    $row = false;
?>
<div class="container my-5 p-4 p-sm-5 border rounded rounded-4 bg-light">
    <h2>Set the characteristics of the experiment</h2>
    <form action="<?php
    if (isset($_SESSION['updatingSavedSettings']) && $_SESSION['updatingSavedSettings'] == true)
        echo "php/updatingSavedSettings.php?test=" . $type;
    else
        echo "php/soundSettingsValidation.php?test=" . $type;
    ?>" name="Settings" method="post">

        <!-- Primo slot di setting -->
        <div class="container mt-3 p-3 border rounded-4 bg-light">
            <h5>Set the characteristics of the standard tone</h5>
            <?php if ($type != "nmod") { ?>
                <div class="row row-cols-1 row-cols-lg-3 gy-3">
                    <div class="col">
                        <!-- Contenuto dello slot, qui vanno inseriti tutti i bottoni e i check box del primo slot -->
                        <div class="input-group flex-nowrap"
                             title="dB of the standard tone, 0dB = 1 is the maximum value">
                            <span class="input-group-text">Amplitude</span>
                            <input type="text" class="form-control" name="amplitude" id="amplitude"
                                   value="<?php
                                   if ($row)
                                       echo $row['amp'];
                                   else
                                       echo "-20";
                                   ?>">
                            <span class="input-group-text">dB</span>
                        </div>
                    </div>
                    <div class="col"
                        <?php if ($type == "gap" || $type == "ndur") echo 'style = "display: none"' ?>>
                        <div class="input-group flex-nowrap"
                             title="Hz of the standard tone, a higher frequency makes the sound sharper">
                            <span class="input-group-text">Frequency</span>
                            <input type="text" class="form-control" name="frequency" id="frequency"
                                   value="<?php
                                   if ($row && $row['freq'] != "")
                                       echo $row['freq'];
                                   else
                                       echo "1000";
                                   ?>">
                            <span class="input-group-text">Hz</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group flex-nowrap"
                             title="ms of the standard tone, a higher value makes the sound last longer">
                            <span class="input-group-text">Duration</span>
                            <input type="text" class="form-control" name="duration" id="duration"
                                   value="<?php
                                   if ($row)
                                       echo $row['dur'];
                                   else
                                       echo "500";
                                   ?>">
                            <span class="input-group-text">ms</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group flex-nowrap"
                             title="ms of the onset ramp of the standard tone, a higher value makes the initial transition slower">
                            <span class="input-group-text">Duration onset ramp</span>
                            <input type="text" class="form-control" name="onRamp" id="onRamp"
                                   value="<?php
                                   if ($row)
                                       echo $row['onRamp'];
                                   else
                                       echo "10";
                                   ?>">
                            <span class="input-group-text">ms</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group flex-nowrap"
                             title="ms of the offset ramp of the standard tone, a higher value makes the final transition slower">
                            <span class="input-group-text">Duration offset ramp</span>
                            <input type="text" class="form-control" name="offRamp" id="offRamp"
                                   value="<?php
                                   if ($row)
                                       echo $row['offRamp'];
                                   else
                                       echo "10";
                                   ?>">
                            <span class="input-group-text">ms</span>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <h6 class="mt-2">Master settings</h6>
                <div class="row row-cols-1 row-cols-lg-3 gy-3">
                    <div class="col">
                        <div class="input-group flex-nowrap"
                             title="ms of the onset ramp of the standard tone, a higher value makes the initial transition slower">
                            <span class="input-group-text">Duration onset ramp</span>
                            <input type="text" class="form-control" name="onRamp" id="onRamp"
                                   value="<?php
                                   if ($row)
                                       echo $row['onRamp'];
                                   else
                                       echo "10";
                                   ?>">
                            <span class="input-group-text">ms</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group flex-nowrap"
                             title="ms of the offset ramp of the standard tone, a higher value makes the final transition slower">
                            <span class="input-group-text">Duration offset ramp</span>
                            <input type="text" class="form-control" name="offRamp" id="offRamp"
                                   value="<?php
                                   if ($row)
                                       echo $row['offRamp'];
                                   else
                                       echo "10";
                                   ?>">
                            <span class="input-group-text">ms</span>
                        </div>
                    </div>
                </div>
                <h6 class="mt-2">Carrier settings</h6>
                <div class="row row-cols-1 row-cols-lg-3 gy-3">
                    <div class="col">
                        <div class="input-group flex-nowrap"
                             title="dB of the carrier, 0dB = 1 is the maximum value">
                            <span class="input-group-text">Amplitude</span>
                            <input type="text" class="form-control" name="amplitude" id="amplitude"
                                   value="<?php
                                   if ($row)
                                       echo $row['amp'];
                                   else
                                       echo "-20";
                                   ?>">
                            <span class="input-group-text">dB</span>
                        </div>
                    </div>
                    <div class="col" style="display: none">
                        <div class="input-group flex-nowrap"
                             title="">
                            <span class="input-group-text">Frequency</span>
                            <input type="text" class="form-control" name="frequency" id="frequency"
                                   value="0">
                            <span class="input-group-text">Hz</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group flex-nowrap"
                             title="ms of the carrier tone, a higher value makes the sound last longer">
                            <span class="input-group-text">Duration</span>
                            <input type="text" class="form-control" name="duration" id="duration"
                                   value="<?php
                                   if ($row)
                                       echo $row['dur'];
                                   else
                                       echo "500";
                                   ?>">
                            <span class="input-group-text">ms</span>
                        </div>
                    </div>
                </div>
                <h6 class="mt-2">Modulator settings</h6>
                <div class="row row-cols-1 row-cols-lg-3 gy-3">
                    <div class="col">
                        <div class="input-group flex-nowrap"
                             title="dB of the modulator, 0dB = 1 is the maximum value">
                            <span class="input-group-text">Amplitude</span>
                            <input type="text" class="form-control" name="modAmplitude" id="modAmplitude"
                                   value="<?php
                                   if ($row && $row["modAmp"] != "")
                                       echo $row['modAmp'];
                                   else
                                       echo "-7.95";
                                   ?>">
                            <span class="input-group-text">dB</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group flex-nowrap"
                             title="Hz of the modulator">
                            <span class="input-group-text">Frequency</span>
                            <input type="text" class="form-control" name="modFrequency" id="modFrequency"
                                   value="<?php
                                   if ($row && $row["modFreq"] != "")
                                       echo $row['modFreq'];
                                   else
                                       echo "10";
                                   ?>">
                            <span class="input-group-text">Hz</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group flex-nowrap"
                             title="phase of the modulator">
                            <span class="input-group-text">Phase</span>
                            <input type="text" class="form-control" name="modPhase" id="modPhase"
                                   value="<?php
                                   if ($row && $row["modPhase"] != "")
                                       echo $row['modPhase'];
                                   else
                                       echo "0";
                                   ?>">
                            <span class="input-group-text">rad</span>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Secondo slot di setting -->
        <div class="container mt-3 p-3 border rounded-4 bg-light">
            <h5>Set the characteristics of the experiment</h5>
            <div class="row row-cols-1 row-cols-lg-3 gy-3">
                <!-- Contenuto dello slot, qui vanno inseriti tutti i bottoni e i check box del secondo slot -->
                <div class="col">
                    <div class="input-group flex-nowrap"
                         title="how many times the test will be repeated">
                        <span class="input-group-text">
                            n. of blocks
                        </span>
                        <input type="text" class="form-control" name="blocks" id="blocks"
                               value="<?php
                               if ($row)
                                   echo $row['blocks'];
                               else
                                   echo "3";
                               ?>">
                    </div>
                </div>
                <div class="col">
                    <div class="input-group flex-nowrap" title="how many sounds will be played">
                        <span class="input-group-text">nAFC</span>
                        <input type="text" class="form-control" name="nAFC" id="nAFC"
                               value="<?php
                               if ($row)
                                   echo $row['nAFC'];
                               else
                                   echo "2";
                               ?>">
                    </div>
                </div>

                <div class="col">
                    <div class="input-group flex-nowrap"
                         title="the time before the new sounds are played (ms)">
                        <span class="input-group-text">ITI</span>
                        <input type="text" class="form-control" name="ITI" id="ITI"
                               value="<?php
                               if ($row)
                                   echo $row['ITI'];
                               else
                                   echo "1000";
                               ?>">
                        <span class="input-group-text">ms</span>
                    </div>
                </div>

                <div class="col">
                    <div class="input-group flex-nowrap" title="the time between two sounds (ms)">
                        <span class="input-group-text">ISI</span>
                        <input type="text" class="form-control" name="ISI" id="ISI"
                               value="<?php
                               if ($row)
                                   echo $row['ISI'];
                               else
                                   echo "500";
                               ?>">
                        <span class="input-group-text">ms</span>
                    </div>
                </div>
                <div class="col"
                    <?php if ($type == "nmod") echo 'style = "display: none"' ?>>
                    <div class="input-group flex-nowrap"
                         title="the starting difference between the sounds">
                        <span class="input-group-text">
                            <?php
                            if ($type == "gap")
                                echo "Gap duration";
                            else
                                echo "Delta";
                            ?>
                        </span>
                        <input type="text" class="form-control" name="delta" id="level"
                               value="<?php
                               if ($type == "amp") {
                                   if ($row && $row['type'] == 'PURE_TONE_INTENSITY')
                                       echo $row['delta'];
                                   else
                                       echo "12";
                               } else if ($type == "freq") {
                                   if ($row && $row['type'] == 'PURE_TONE_FREQUENCY')
                                       echo $row['delta'];
                                   else
                                       echo "200";
                               } else if ($type == "dur") {
                                   if ($row && $row['type'] == 'PURE_TONE_DURATION')
                                       echo $row['delta'];
                                   else
                                       echo "300";
                               } else if ($type == "ndur") {
                                   if ($row && $row['type'] == 'WHITE_NOISE_DURATION')
                                       echo $row['delta'];
                                   else
                                       echo "375";
                               } else if ($type == "gap") {
                                   if ($row && $row['type'] == 'WHITE_NOISE_GAP')
                                       echo $row['delta'];
                                   else
                                       echo "50";
                               } else if ($type == "nmod") {
                                   if ($row && $row['type'] == 'WHITE_NOISE_MODULATION')
                                       echo $row['delta'];
                                   else
                                       echo "12";
                               }
                               ?>">
                        <span class="input-group-text">
                            <?php
                            if ($type == "amp" || $type == "nmod")
                                echo "dB";
                            else if ($type == "freq")
                                echo "Hz";
                            else if ($type == "dur" || $type == "ndur")
                                echo "ms";
                            else if ($type == "gap")
                                echo "ms";
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terzo slot di setting -->
        <div class="container mt-3 p-3 border rounded-4 bg-light">
            <h5>Set the characteristics of the staircase</h5>
            <div class="row row-cols-1 row-cols-lg-2 gy-3">
                <!-- Contenuto dello slot, qui vanno inseriti tutti i componenti del terzo slot -->
                <!-- Radios, sono raggruppati in un div che sta sulla sinistra-->
                <div class="col">
                    <div class="row gy-3">
                        <div class="col-12">
                            <div class="form-check"
                                 title="every correct answer increases the difficulty of the test, every wrong answer makes it easier">
                                <input class="form-check-input" type="radio" name="algorithm"
                                       value="SimpleUpDown" id="alg"
                                    <?php if ($row && $row['alg'] == "SimpleUpDown")
                                        echo "checked";
                                    ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    SimpleUpDown
                                </label>
                            </div>
                            <div class="form-check"
                                 title="two consecutive correct answers increase the difficulty of the test, every wrong answer makes it easier">
                                <input class="form-check-input" type="radio" name="algorithm"
                                       value="TwoDownOneUp" id="alg"
                                    <?php if (($row && $row['alg'] == "TwoDownOneUp") || !$row)
                                        echo "checked";
                                    ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    TwoDownOneUp
                                </label>
                            </div>
                            <div class="form-check"
                                 title="three consecutive correct answers increase the difficulty of the test, every wrong answer makes it easier">
                                <input class="form-check-input" type="radio" name="algorithm"
                                       value="ThreeDownOneUp" id="alg"
                                    <?php if ($row && $row['alg'] == "ThreeDownOneUp")
                                        echo "checked";
                                    ?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    ThreeDownOneUp
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <!-- Checkbox -->
                            <div class="form-check"
                                 title="if checked a message will tell if you choose the correct sound">
                                <input class="form-check-input" type="checkbox" id="cb" name="checkFb"
                                    <?php
                                    if (($row && $row['fb']) || !$row)
                                        echo "checked";
                                    ?>
                                >
                                <label class="form-check-label" for="cb">
                                    Feedback after response
                                </label>
                            </div>
                            <?php if (isset($_SESSION['usr']) && !(isset($_SESSION['updatingSavedSettings']) && $_SESSION['updatingSavedSettings'] == true)) { ?>
                                <div class="form-check"
                                     title="if checked the settings will be saved and used as default for the next tests">
                                    <input class="form-check-input" type="checkbox" id="saveSettings"
                                           name="saveSettings">
                                    <label class="form-check-label" for="saveSettings">
                                        Save settings
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- input boxes, sono raggruppati in un div che sta sulla destra-->
                <div class="col">
                    <div class="row gy-3">
                        <div class="col-12 col-lg-6">
                            <div class="input-group flex-nowrap"
                                 title="the changing factor for the first raversals">
                                <span class="input-group-text">First factor</span>
                                <input type="text" class="form-control" name="factor" id="factor"
                                       value="<?php
                                       if ($row)
                                           echo $row['fact'];
                                       else
                                           echo "2";
                                       ?>">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group flex-nowrap"
                                 title="the changing factor for the second raversals">
                                <span class="input-group-text">Second factor</span>
                                <input type="text" class="form-control" name="secFactor" id="secondFactor"
                                       value="<?php
                                       if ($row)
                                           echo $row['secfact'];
                                       else
                                           echo "1.414";
                                       ?>">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group flex-nowrap"
                                 title="for how many reversals the algorithm will use the first factor">
                                <span class="input-group-text">First reversals</span>
                                <input type="text" class="form-control" name="reversals" id="reversals"
                                       value="<?php
                                       if ($row)
                                           echo $row['rev'];
                                       else
                                           echo "4";
                                       ?>">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="input-group flex-nowrap"
                                 title="for how many reversals the algorithm will use the second factor">
                                <span class="input-group-text">Second reversals</span>
                                <input type="text" class="form-control" name="secReversals" id="reversals"
                                       value="<?php
                                       if ($row)
                                           echo $row['secrev'];
                                       else
                                           echo "8";
                                       ?>">
                            </div>
                        </div>
                        <div class="col 12">
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text">Reversal threshold</span>
                                <input type="text" class="form-control" name="threshold" id="reversalsTh"
                                       value="<?php
                                       if ($row)
                                           echo $row['secrev'];
                                       else
                                           echo "8";
                                       ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- i bottoni sono fuori dal terzo slot -->
        <div class="row row-cols-2 gy-2 mt-3">
            <div class="col d-grid">
                <button type="button" class="btn btn-primary btn-lg btn-red"
                        onclick="location.href='demographicData.php'">BACK
                </button>
            </div>
            <div class="col d-grid">
                <button type="submit" class="btn btn-primary btn-lg btn-red">
                    <?php
                    if (isset($_SESSION['updatingSavedSettings']) && $_SESSION['updatingSavedSettings'] == true)
                        echo "SAVE SETTINGS";
                    else
                        echo "START";
                    ?>
                </button>
            </div>
        </div>
    </form>
</div>
<!-- Bootstrap Bundle with Popper -->
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
