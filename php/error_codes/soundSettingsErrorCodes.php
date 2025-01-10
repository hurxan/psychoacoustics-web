<?php
    
    function checkSSEC(){
        $sound_irreg_exp = "/^([a-zA-Z])+$/";
        //controlli su amplitude
        if (($_POST["amplitude"] == "") || ($_POST["amplitude"] == "undefined"))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=amp1");
    
        else if (!is_numeric($_POST["amplitude"]))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=amp2");
    
        else if (intval($_POST["amplitude"]) > 0)
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=amp3");
    
        //controlli su frequency
        else if (($_POST["frequency"] == "") || ($_POST["frequency"] == "undefined"))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=freq1");
    
        else if (!is_numeric($_POST["frequency"]) || intval($_POST["frequency"]) < 0)
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=freq2");
    
        //Controlli su duration
        else if (($_POST["duration"] == "") || ($_POST["duration"] == "undefined"))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=dur1");
    
        else if (!is_numeric($_POST["duration"]) || $_POST["duration"] < 0)
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=dur2");
    
        //controlli su onRamp
        else if (($_POST["onRamp"] == "") || ($_POST["onRamp"] == "undefined"))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=onRamp1");
    
        else if (!is_numeric($_POST["onRamp"]) || $_POST["onRamp"] < 0)
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=onRamp2");
    
        //controlli su offRamp
        else if (($_POST["offRamp"] == "") || ($_POST["offRamp"] == "undefined"))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=offRamp1");
    
        else if (!is_numeric($_POST["offRamp"]) || $_POST["offRamp"] < 0)
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=offRamp2");
    
        //controlli su modAmplitude
        else if ($_GET['test'] == "nmod" && (($_POST["modAmplitude"] == "") || ($_POST["modAmplitude"] == "undefined")))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=modAmp1");
    
        else if ($_GET['test'] == "nmod" && (!is_numeric($_POST["modAmplitude"])))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=modAmp2");
    
        //controlli su modFrequency
        else if ($_GET['test'] == "nmod" && (($_POST["modFrequency"] == "") || ($_POST["modFrequency"] == "undefined")))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=modFreq1");
    
        else if ($_GET['test'] == "nmod" && (!is_numeric($_POST["modFrequency"]) || $_POST["modFrequency"] < 0))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=modFreq2");
    
        //controlli su modPhase
        else if ($_GET['test'] == "nmod" && (($_POST["modPhase"] == "") || ($_POST["modPhase"] == "undefined")))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=modPhase1");
    
        else if ($_GET['test'] == "nmod" && (!is_numeric($_POST["modPhase"])))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=modPhase2");
    
        //controlli su number of blocks
        else if (($_POST["blocks"] == "") || ($_POST["blocks"] == "undefined"))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=numblock1");
    
        else if (!is_numeric($_POST["blocks"]) || $_POST["blocks"] < 0)
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=numblock2");
    
        //controlli su delta
        else if (($_POST["delta"] == "") || ($_POST["delta"] == "undefined"))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=delta1");
    
        else if (!is_numeric($_POST["delta"]) || $_POST["delta"] < 0)
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=delta2");
    
        else if ($_GET['test'] == "amp" && $_POST["amplitude"] + $_POST["delta"] > 0)
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=delta3");
    
        //controlli su ITI
        else if (($_POST["ITI"] == "") || ($_POST["ITI"] == "undefined"))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=ITI1");
    
        else if (!is_numeric($_POST["ITI"]) || $_POST["ITI"] < 1000)
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=ITI2");
    
        //controlli su ISI
        else if (($_POST["ISI"] == "") || ($_POST["ISI"] == "undefined"))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=ISI1");
    
        else if (!is_numeric($_POST["ISI"]) || $_POST["ISI"] < 0)
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=ISI2");
    
        //controlli su nAFC
        else if (($_POST["nAFC"] == "") || ($_POST["nAFC"] == "undefined"))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=nAFC1");
    
        else if (!is_numeric($_POST["nAFC"]) || $_POST["nAFC"] < 2)
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=nAFC2");
    
        else if ($_POST["nAFC"] > 9)
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=nAFC3");
    
        //controlli sul factor
        else if (($_POST["factor"] == "") || ($_POST["factor"] == "undefined"))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=factor1");
    
    
        else if (!is_numeric($_POST["factor"]) || $_POST["factor"] < $_POST["secFactor"])
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=factor2");
    
        //controlli sul factor
        else if (($_POST["secFactor"] == "") || ($_POST["secFactor"] == "undefined"))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=secFactor1");
    
    
        else if (!is_numeric($_POST["secFactor"]) || $_POST["secFactor"] < 1)
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=secFactor2");
    
        //controlli su starting rev
        else if (($_POST["reversals"] == "") || ($_POST["reversals"] == "undefined"))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=rev1");
    
        else if (!is_numeric($_POST["reversals"]) || $_POST["reversals"] < 0)
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=rev2");
    
        // controlli su secreversal
        else if (($_POST["secReversals"] == "") || ($_POST["secReversals"] == "undefined"))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=secRev1");
    
        else if (!is_numeric($_POST["secReversals"]) || $_POST["secReversals"] < 0)
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=secRev2");
    
        //controlli su revTh
        else if (($_POST["threshold"] == "") || ($_POST["threshold"] == "undefined"))
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=threshold1");
    
        else if (!is_numeric($_POST["threshold"]) || $_POST["threshold"] < 0)
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=threshold2");
    
        else if ($_POST["threshold"] > $_POST["reversals"] + $_POST["secReversals"])
            return("Location: ../soundSettings.php?test={$_GET['test']}&err=threshold3");

    

    }
    
?>