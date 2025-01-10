<?php

/**
 * download test data of guests on the results page
 */

session_start();

include "config.php";
include "db_connect.php";
include "helpers/utils.php";

if (!isset($_GET['format']) || ($_GET['format'] != "complete" && $_GET['format'] != "reduced")) {
    header("Location: ../index.php");
}

$id = $_SESSION['idGuestTest'];
$testParameters = $_SESSION['testParameters'];

try {

    $conn = connectdb();

    //select demographic data of the user who performed the test
    if (!(isUserLogged()) || isset($_SESSION['referralTest'])) {
        $sql = "SELECT ID, name, surname, age, gender, notes FROM guest WHERE ID='$id'";
    } else {
        $sql = "SELECT ID, name, surname, gender, notes, date FROM guest INNER JOIN account ON account.Guest_ID = guest.ID WHERE ID='$id'";
    }

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $age = "";

    //if user not logged age is taken from guest if given
    if (isset($row['age']))
        $age = $row['age'];
       
    
    //if loggged is calculate from birthdate
    $currentDate = new DateTime();
    if (isset($row['date'])) {
        // Create DateTime objects
        $birthDate = new DateTime($row['date']);

        // Calculate the difference
        $age = $birthDate->diff($currentDate)->y;
    }

    $dateString = $currentDate->format('Y-m-d H-i'); // Example format: "2024-11-25 14-00"
    
    $testTypeExt = getExtfromCmpType($_SESSION["testTypeCmp"]);//takes full test type to insert in filename.csv
    
    
    //create and open the csv file, unique for every ID
    $path = $id . '-' . $testTypeExt . $dateString;
    if ($_GET['format'] == "complete")
        $path .= "results.csv";
    else
        $path .= "reducedResults.csv";
    $txt = fopen($path, "w") or die("Unable to open file!");
    fwrite($txt, chr(0xEF) . chr(0xBB) . chr(0xBF)); //utf8 encoding

    //define columns name
    $line = "Guest_ID;Name;Surname;Age;Gender;Notes;Test Type;Timestamp;Sample Rate;Amplitude;Frequency;Duration;Onset Ramp;Offset Ramp;";
    if ($testTypeExt == "WHITE_NOISE_MODULATION")
        $line .= "Modulator Amplitude;Modulator frequency;Modulator Phase;";
    $line .= "n. of blocks;nAFC;ISI;ITI;First factor;First reversals;Second factor;Second reversals;reversal threshold;algorithm;";

    if ($_GET['format'] == "complete")
        $line .= "block;trials;delta;variable;Variable Position;Pressed button;correct?;reversals;threshold (arithmetic mean);threshold (geometric mean)\n";
    else
        $line .= "block;threshold (arithmetic mean);threshold (geometric mean)\n";
    fwrite($txt, $line);


    //values of the firts csv segment that is constant every line
    $firstValues = $row["ID"] . ";" . $row["name"] . ";" . $row["surname"] . ";" . $age . ";" . $row["gender"] . ";" . $row["notes"] . ";" . $_SESSION["testTypeCmp"] . ";" . $dateString . ";" . $testParameters["sampleRate"] . ";" . $testParameters["amplitude"] . ";" . $testParameters["frequency"] . ";" . $testParameters["duration"] . ";" . $testParameters["onRamp"] . ";" . $testParameters["offRamp"] . ";";
    if ($testTypeExt == "WHITE_NOISE_MODULATION")
        $firstValues .= $testParameters["modAmplitude"] . ";" . $testParameters["modFrequency"] . ";" . $testParameters["modPhase"] . ";";
    $firstValues .= $testParameters["blocks"] . ";" . $testParameters["nAFC"] . ";" . $testParameters["ISI"] . ";" . $testParameters["ITI"] . ";";
    $firstValues .= $testParameters["factor"] . ";" . $testParameters["reversals"] . ";" . $testParameters["secFactor"] . ";" . $testParameters["secReversals"] . ";" . $testParameters["threshold"] . ";" . $testParameters["algorithm"];



    if ($_GET['format'] == "complete") {

        //results in form ["bl1;tr1;del1;var1;varpos1;but1;cor1;rev1", "bl2;tr2;...", ...]

        $results = explode(",", $_SESSION["results"]);
        $nItems = count($results) - 1;
        for ($i = 0; $i < $nItems; $i++) { //for every result line
            fwrite($txt, $firstValues . ";"); //fixed valies forst
            fwrite($txt, $results[$i]); //variable values

            if (($i == $nItems - 1) || ($results[$i][0] != $results[$i + 1][0])) { //end results
                $block = $results[$i][0];
                fwrite($txt, ";" . explode(";", $_SESSION["score"])[$block - 1] . ";" . explode(";", $_SESSION["geometric_score"])[$block - 1]); //scrivo il punteggio
            } else
                fwrite($txt, ";" . "NA" . ";" . "NA");

            fwrite($txt, "\n"); //next line
        }
    } else {

        $results = explode(";", $_SESSION["score"]);
        $results_geometricsore = explode(";", $_SESSION["geometric_score"]);
        for ($i = 0; $i < count($results)- 1; $i++) {
            fwrite($txt, $firstValues . ";"); //fixed values
            fwrite($txt, ($i + 1) . ";"); //block number
            fwrite($txt, $results[$i] . ";"); //block score
            fwrite($txt, $results_geometricsore[$i]); //block geometric score
            fwrite($txt, "\n"); //next line
        }
    }

    fclose($txt);
    ob_clean();

    header('Content-Disposition: attachment; filename=' . basename($path));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($path));
    header("Content-Type: text/csv");
    readfile($path);

    unlink($path); //delete file from server

} catch (Exception $e) { //in this configuration, if the dl buttons does not works, nothing happens
    echo $e;
    //header("Location: ../index.php?err=db");
}
