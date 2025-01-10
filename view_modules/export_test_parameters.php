<?php
$testParam = $_SESSION['testParameters'];
?>

<script>
    // pass info from php session to js
    //this function is universal, parameters not set will be passed as null
    <?php
    // Define the variables with conditional checks
    $variables = [
        "amp" => isset($testParam["amplitude"]) ? $testParam["amplitude"] : "null",
        "carAmpDb" => isset($testParam["amplitude"]) ? $testParam["amplitude"] : "null",
        "freq" => isset($testParam["frequency"]) ? $testParam["frequency"] : "null",
        "dur" => isset($testParam["duration"]) ? $testParam["duration"] : "null",
        "carDur" => isset($testParam["duration"]) ? $testParam["duration"] : "null",
        "onRamp" => isset($testParam["onRamp"]) ? $testParam["onRamp"] : "null",
        "offRamp" => isset($testParam["offRamp"]) ? $testParam["offRamp"] : "null",
        "modAmpDb" => isset($testParam["modAmplitude"]) ? $testParam["modAmplitude"] : "null",
        "modFreq" => isset($testParam["modFrequency"]) ? $testParam["modFrequency"] : "null",
        "modPhase" => isset($testParam["modPhase"]) ? $testParam["modPhase"] : "null",
        "blocks" => isset($testParam["blocks"]) ? $testParam["blocks"] : "null",
        "delta" => isset($testParam["delta"]) ? $testParam["delta"] : "null",
        "nAFC" => isset($testParam["nAFC"]) ? $testParam["nAFC"] : "null",
        "ITI" => isset($testParam["ITI"]) ? $testParam["ITI"] : "null",
        "ISI" => isset($testParam["ISI"]) ? $testParam["ISI"] : "null",
        "feedback" => isset($testParam["checkFb"]) ? $testParam["checkFb"] : "null",
        "factor" => isset($testParam["factor"]) ? $testParam["factor"] : "null",
        "secondFactor" => isset($testParam["secFactor"]) ? $testParam["secFactor"] : "null",
        "reversals" => isset($testParam["reversals"]) ? $testParam["reversals"] : "null",
        "secondReversals" => isset($testParam["secReversals"]) ? $testParam["secReversals"] : "null",
        "reversalThreshold" => isset($testParam["threshold"]) ? $testParam["threshold"] : "null",
        "algorithm" => isset($testParam["algorithm"]) ? "'{$testParam["algorithm"]}'" : "null"
    ];

    //loop through the variables and output the JavaScript assignment
    foreach ($variables as $key => $value) {
        echo "var {$key} = {$value};\n";
    }

    ?>
    var currentBlock = parseInt(<?php if (isset($_SESSION["currentBlock"]))
                                    echo $_SESSION["currentBlock"] + 1;
                                else
                                    echo "1" ?>);
</script>