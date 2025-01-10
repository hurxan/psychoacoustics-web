<?php


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


?>