<?php

session_start();
include "config.php";
$deviceInfo = str_replace(";", " ", $_SERVER['HTTP_USER_AGENT']);
if (isset($_SESSION['test'])) {
    try {
        $conn = new mysqli($host, $user, $password, $dbname);
        if ($conn->connect_errno)
            throw new Exception('DB connection failed');
        mysqli_set_charset($conn, "utf8");

        $sql = "SELECT Type, Amplitude as amp, Frequency as freq, Duration as dur, OnRamp as onRamp, OffRamp as offRamp, blocks, Delta, nAFC, 
					ISI, ITI, Factor as fact, Reversal as rev, SecFactor as secfact, SecReversal as secrev, Feedback as feedback,
					Threshold as thr, Algorithm as alg, ModAmplitude as modAmp, ModFrequency as modFreq, ModPhase as modPhase
					
					FROM test
					
					WHERE Guest_ID='{$_SESSION['test']['guest']}' AND Test_count='{$_SESSION['test']['count']}'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        if ($row['Type'] == 'PURE_TONE_INTENSITY')
            $type = "amp";
        else if ($row['Type'] == 'PURE_TONE_FREQUENCY')
            $type = "freq";
        else if ($row['Type'] == 'PURE_TONE_DURATION')
            $type = "dur";
        else if ($row['Type'] == 'WHITE_NOISE_GAP')
            $type = "gap";
        else if ($row['Type'] == 'WHITE_NOISE_DURATION')
            $type = "ndur";
        else if ($row['Type'] == 'WHITE_NOISE_MODULATION')
            $type = "nmod";

        $checkFb = 0;
        if (isset($_POST["checkFb"]))
            $checkFb = 1;

        $_SESSION["amplitude"] = $row["amp"];
        $_SESSION["frequency"] = $row["freq"];
        $_SESSION["duration"] = $row["dur"];
        $_SESSION["onRamp"] = $row["onRamp"];
        $_SESSION["offRamp"] = $row["offRamp"];
        $_SESSION["modAmplitude"] = $row["modAmp"];
        $_SESSION["modFrequency"] = $row["modFreq"];
        $_SESSION["modPhase"] = $row["modPhase"];
        $_SESSION["blocks"] = $row["blocks"];
        $_SESSION["nAFC"] = $row["nAFC"];
        $_SESSION["ITI"] = $row["ITI"];
        $_SESSION["ISI"] = $row["ISI"];
        $_SESSION["delta"] = $row["Delta"];
        $_SESSION["checkFb"] = $row["feedback"];
        $_SESSION["saveSettings"] = 0;
        $_SESSION["factor"] = $row["fact"];
        $_SESSION["secFactor"] = $row["secfact"];
        $_SESSION["reversals"] = $row["rev"];
        $_SESSION["secReversals"] = $row["secrev"];
        $_SESSION["threshold"] = $row["thr"];
        $_SESSION["algorithm"] = $row["alg"];
        unset($_SESSION['score']);
        unset($_SESSION['currentBlock']);
        unset($_SESSION['results']);

        echo $_SESSION['idGuestTest'];
        try {
            $conn = new mysqli($host, $user, $password, $dbname);
            if ($conn->connect_errno)
                throw new Exception('DB connection failed');
            mysqli_set_charset($conn, "utf8");

            $id = $_SESSION['idGuestTest'];
            if ($type == "freq")
                $t_type = "PURE_TONE_FREQUENCY";
            else if ($type == "amp")
                $t_type = "PURE_TONE_INTENSITY";
            else if ($type == "dur")
                $t_type = "PURE_TONE_DURATION";
            else if ($type == "gap")
                $t_type = "WHITE_NOISE_GAP";
            else if ($type == "ndur")
                $t_type = "WHITE_NOISE_DURATION";
            else if ($type == "nmod")
                $t_type = "WHITE_NOISE_MODULATION";

            $sql = "SELECT Max(Test_count) as count FROM test WHERE Guest_ID='$id'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            //il test corrente è il numero di test già effettuati + 1
            $count = $row['count'] + 1;

            if ($type == "gap" || $type == "ndur") {
                $sql = "INSERT INTO test VALUES ('$id', '$count', current_timestamp(), '$t_type', ";
                $sql .= "'{$_SESSION['amplitude']}', NULL, '{$_SESSION['duration']}', '{$_SESSION['onRamp']}', '{$_SESSION['offRamp']}', '{$_SESSION['blocks']}', '{$_SESSION['delta']}', ";
                $sql .= "'{$_SESSION['nAFC']}', '{$_SESSION['ITI']}', '{$_SESSION['ISI']}', '{$_SESSION['factor']}', '{$_SESSION['reversals']}', ";
                $sql .= "'{$_SESSION['secFactor']}', '{$_SESSION['secReversals']}', '{$_SESSION['threshold']}', '{$_SESSION['algorithm']}', '', '0','$checkFb', NULL, NULL, NULL, '$deviceInfo')";
            } else if ($type == "nmod") {
                $sql = "INSERT INTO test VALUES ('$id', '$count', current_timestamp(), '$t_type', ";
                $sql .= "'{$_SESSION['amplitude']}', NULL, '{$_SESSION['duration']}', '{$_SESSION['onRamp']}', '{$_SESSION['offRamp']}', '{$_SESSION['blocks']}', '{$_SESSION['delta']}', ";
                $sql .= "'{$_SESSION['nAFC']}', '{$_SESSION['ITI']}', '{$_SESSION['ISI']}', '{$_SESSION['factor']}', '{$_SESSION['reversals']}', ";
                $sql .= "'{$_SESSION['secFactor']}', '{$_SESSION['secReversals']}', '{$_SESSION['threshold']}', '{$_SESSION['algorithm']}', '', '0','$checkFb', '" . floatval($_SESSION["modAmplitude"]) . "', '{$_SESSION["modFrequency"]}', '{$_SESSION["modPhase"]}', '$deviceInfo')";
            } else {
                $sql = "INSERT INTO test VALUES ('$id', '$count', current_timestamp(), '$t_type', ";
                $sql .= "'{$_SESSION['amplitude']}', '{$_SESSION['frequency']}', '{$_SESSION['duration']}', '{$_SESSION['onRamp']}', '{$_SESSION['offRamp']}', '{$_SESSION['blocks']}', '{$_SESSION['delta']}', ";
                $sql .= "'{$_SESSION['nAFC']}', '{$_SESSION['ITI']}', '{$_SESSION['ISI']}', '{$_SESSION['factor']}', '{$_SESSION['reversals']}', ";
                $sql .= "'{$_SESSION['secFactor']}', '{$_SESSION['secReversals']}', '{$_SESSION['threshold']}', '{$_SESSION['algorithm']}', '', '0','$checkFb', NULL, NULL, NULL, '$deviceInfo')";
            }

            $conn->query($sql);

            if (!$conn->query($sql)) {
                echo "SQLSTATE error: " . $conn->sqlstate;
                echo $sql;
            }
            header("Location: ../{$type}test.php");
        } catch (Exception $e) {
            echo 'sono nel catch';
            header("Location: ../index.php?err=db");
        }
    } catch (Exception $e) {
        header("Location: ../index.php?err=db");
    }
} else {

    $sound_irreg_exp = "/^([a-zA-Z])+$/";
    //controlli su amplitude
    if (($_POST["amplitude"] == "") || ($_POST["amplitude"] == "undefined"))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=amp1");

    else if (!is_numeric($_POST["amplitude"]))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=amp2");

    else if (intval($_POST["amplitude"]) > 0)
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=amp3");

    //controlli su frequency
    else if (($_POST["frequency"] == "") || ($_POST["frequency"] == "undefined"))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=freq1");

    else if (!is_numeric($_POST["frequency"]) || intval($_POST["frequency"]) < 0)
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=freq2");

    //Controlli su duration
    else if (($_POST["duration"] == "") || ($_POST["duration"] == "undefined"))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=dur1");

    else if (!is_numeric($_POST["duration"]) || $_POST["duration"] < 0)
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=dur2");

    //controlli su onRamp
    else if (($_POST["onRamp"] == "") || ($_POST["onRamp"] == "undefined"))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=onRamp1");

    else if (!is_numeric($_POST["onRamp"]) || $_POST["onRamp"] < 0)
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=onRamp2");

    //controlli su offRamp
    else if (($_POST["offRamp"] == "") || ($_POST["offRamp"] == "undefined"))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=offRamp1");

    else if (!is_numeric($_POST["offRamp"]) || $_POST["offRamp"] < 0)
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=offRamp2");

    //controlli su modAmplitude
    else if ($_GET['test'] == "nmod" && (($_POST["modAmplitude"] == "") || ($_POST["modAmplitude"] == "undefined")))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=modAmp1");

    else if ($_GET['test'] == "nmod" && (!is_numeric($_POST["modAmplitude"]) || $_POST["modAmplitude"] > 0))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=modAmp2");

    //controlli su modFrequency
    else if ($_GET['test'] == "nmod" && (($_POST["modFrequency"] == "") || ($_POST["modFrequency"] == "undefined")))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=modFreq1");

    else if ($_GET['test'] == "nmod" && (!is_numeric($_POST["modFrequency"]) || $_POST["modFrequency"] < 0))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=modFreq2");

    //controlli su modPhase
    else if ($_GET['test'] == "nmod" && (($_POST["modPhase"] == "") || ($_POST["modPhase"] == "undefined")))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=modPhase1");

    else if ($_GET['test'] == "nmod" && (!is_numeric($_POST["modPhase"])))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=modPhase2");

    //controlli su number of blocks
    else if (($_POST["blocks"] == "") || ($_POST["blocks"] == "undefined"))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=numblock1");

    else if (!is_numeric($_POST["blocks"]) || $_POST["blocks"] < 0)
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=numblock2");

    //controlli su delta
    else if (($_POST["delta"] == "") || ($_POST["delta"] == "undefined"))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=delta1");

    else if (!is_numeric($_POST["delta"]) || $_POST["delta"] < 0)
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=delta2");

    else if ($_GET['test'] == "amp" && $_POST["amplitude"] + $_POST["delta"] > 0)
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=delta3");

    //controlli su ITI
    else if (($_POST["ITI"] == "") || ($_POST["ITI"] == "undefined"))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=ITI1");

    else if (!is_numeric($_POST["ITI"]) || $_POST["ITI"] < 1000)
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=ITI2");

    //controlli su ISI
    else if (($_POST["ISI"] == "") || ($_POST["ISI"] == "undefined"))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=ISI1");

    else if (!is_numeric($_POST["ISI"]) || $_POST["ISI"] < 0)
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=ISI2");

    //controlli su nAFC
    else if (($_POST["nAFC"] == "") || ($_POST["nAFC"] == "undefined"))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=nAFC1");

    else if (!is_numeric($_POST["nAFC"]) || $_POST["nAFC"] < 2)
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=nAFC2");

    else if ($_POST["nAFC"] > 9)
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=nAFC3");

    //controlli sul factor
    else if (($_POST["factor"] == "") || ($_POST["factor"] == "undefined"))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=factor1");


    else if (!is_numeric($_POST["factor"]) || $_POST["factor"] < $_POST["secFactor"])
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=factor2");

    //controlli sul factor
    else if (($_POST["secFactor"] == "") || ($_POST["secFactor"] == "undefined"))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=secFactor1");


    else if (!is_numeric($_POST["secFactor"]) || $_POST["secFactor"] < 1)
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=secFactor2");

    //controlli su starting rev
    else if (($_POST["reversals"] == "") || ($_POST["reversals"] == "undefined"))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=rev1");

    else if (!is_numeric($_POST["reversals"]) || $_POST["reversals"] < 0)
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=rev2");

    // controlli su secreversal
    else if (($_POST["secReversals"] == "") || ($_POST["secReversals"] == "undefined"))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=secRev1");

    else if (!is_numeric($_POST["secReversals"]) || $_POST["secReversals"] < 0)
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=secRev2");

    //controlli su revTh
    else if (($_POST["threshold"] == "") || ($_POST["threshold"] == "undefined"))
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=threshold1");

    else if (!is_numeric($_POST["threshold"]) || $_POST["threshold"] < 0)
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=threshold2");

    else if ($_POST["threshold"] > $_POST["reversals"] + $_POST["secReversals"])
        header("Location: ../soundSettings.php?test={$_GET['test']}&err=threshold3");

    else {
        $checkFb = 0;
        if (isset($_POST["checkFb"]))
            $checkFb = 1;

        $checkSave = 0;
        if (isset($_POST["saveSettings"]))
            $checkSave = 1;

        $_SESSION["amplitude"] = $_POST["amplitude"];
        $_SESSION["frequency"] = $_POST["frequency"];
        $_SESSION["duration"] = $_POST["duration"];
        $_SESSION["onRamp"] = $_POST["onRamp"];
        $_SESSION["offRamp"] = $_POST["offRamp"];
        if ($_GET['test'] == "nmod") {
            $_SESSION["modAmplitude"] = $_POST["modAmplitude"];
            $_SESSION["modFrequency"] = $_POST["modFrequency"];
            $_SESSION["modPhase"] = $_POST["modPhase"];
        }
        $_SESSION["blocks"] = $_POST["blocks"];
        $_SESSION["nAFC"] = $_POST["nAFC"];
        $_SESSION["ITI"] = $_POST["ITI"];
        $_SESSION["ISI"] = $_POST["ISI"];
        $_SESSION["delta"] = $_POST["delta"];
        $_SESSION["checkFb"] = $checkFb;
        $_SESSION["saveSettings"] = $checkSave;
        $_SESSION["factor"] = $_POST["factor"];
        $_SESSION["secFactor"] = $_POST["secFactor"];
        $_SESSION["reversals"] = $_POST["reversals"];
        $_SESSION["secReversals"] = $_POST["secReversals"];
        $_SESSION["threshold"] = $_POST["threshold"];
        $_SESSION["algorithm"] = $_POST["algorithm"];
        unset($_SESSION['score']);
        unset($_SESSION['currentBlock']);
        unset($_SESSION['results']);

        try {
            $conn = new mysqli($host, $user, $password, $dbname);
            if ($conn->connect_errno)
                throw new Exception('DB connection failed');
            mysqli_set_charset($conn, "utf8");

            $id = $_SESSION['idGuestTest'];
            $type = "";
            if ($_GET['test'] == "freq")
                $type = "PURE_TONE_FREQUENCY";
            else if ($_GET['test'] == "amp")
                $type = "PURE_TONE_INTENSITY";
            else if ($_GET['test'] == "dur")
                $type = "PURE_TONE_DURATION";
            else if ($_GET['test'] == "gap")
                $type = "WHITE_NOISE_GAP";
            else if ($_GET['test'] == "ndur")
                $type = "WHITE_NOISE_DURATION";
            else if ($_GET['test'] == "nmod")
                $type = "WHITE_NOISE_MODULATION";

            $sql = "SELECT Max(Test_count) as count FROM test WHERE Guest_ID='$id'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            //il test corrente è il numero di test già effettuati + 1
            $count = $row['count'] + 1;

            if ($_GET['test'] == "gap" || $_GET['test'] == "ndur") {
                $sql = "INSERT INTO test VALUES ('$id', '$count', current_timestamp(), '$type', ";
                $sql .= "'{$_POST['amplitude']}', NULL, '{$_POST['duration']}', '{$_POST['onRamp']}', '{$_POST['offRamp']}', '{$_POST['blocks']}', '{$_POST['delta']}', ";
                $sql .= "'{$_POST['nAFC']}', '{$_POST['ITI']}', '{$_POST['ISI']}', '{$_POST['factor']}', '{$_POST['reversals']}', ";
                $sql .= "'{$_POST['secFactor']}', '{$_POST['secReversals']}', '{$_POST['threshold']}', '{$_POST['algorithm']}', '', '0','$checkFb', NULL, NULL, NULL, '$deviceInfo')";
            } else if ($_GET['test'] == "nmod") {
                $sql = "INSERT INTO test VALUES ('$id', '$count', current_timestamp(), '$type', ";
                $sql .= "'{$_POST['amplitude']}', NULL, '{$_POST['duration']}', '{$_POST['onRamp']}', '{$_POST['offRamp']}', '{$_POST['blocks']}', '{$_POST['delta']}', ";
                $sql .= "'{$_POST['nAFC']}', '{$_POST['ITI']}', '{$_POST['ISI']}', '{$_POST['factor']}', '{$_POST['reversals']}', ";
                $sql .= "'{$_POST['secFactor']}', '{$_POST['secReversals']}', '{$_POST['threshold']}', '{$_POST['algorithm']}', '', '0','$checkFb', '" . floatval($_POST["modAmplitude"]) . "', '{$_POST["modFrequency"]}', '{$_POST["modPhase"]}', '$deviceInfo')";
            } else {
                $sql = "INSERT INTO test VALUES ('$id', '$count', current_timestamp(), '$type', ";
                $sql .= "'{$_POST['amplitude']}', '{$_POST['frequency']}', '{$_POST['duration']}', '{$_POST['onRamp']}', '{$_POST['offRamp']}', '{$_POST['blocks']}', '{$_POST['delta']}', ";
                $sql .= "'{$_POST['nAFC']}', '{$_POST['ITI']}', '{$_POST['ISI']}', '{$_POST['factor']}', '{$_POST['reversals']}', ";
                $sql .= "'{$_POST['secFactor']}', '{$_POST['secReversals']}', '{$_POST['threshold']}', '{$_POST['algorithm']}', '', '0','$checkFb', NULL, NULL, NULL, '$deviceInfo')";
            }

            $conn->query($sql);

            if (!$conn->query($sql)) {
                echo "SQLSTATE error: " . $conn->sqlstate;
                echo $sql;
            }

            if ($checkSave) {
                $sql = "UPDATE account SET fk_guestTest = '$id', fk_testCount = '$count' WHERE username = '{$_SESSION['usr']}' ";
                $conn->query($sql);
            }
            header("Location: ../{$_GET['test']}test.php");
        } catch (Exception $e) {
            echo 'sono nel catch';
            header("Location: ../index.php?err=db");
        }
    }
}

?>
