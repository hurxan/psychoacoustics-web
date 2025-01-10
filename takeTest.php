<?php
session_start();
$type = $_GET['type'];

switch ($type) {
    case 'amp':
        $testMsg = "Which is the loudest tone?";
        $title = "Amplitude test";
        $script = "js/soundsAmplitude.js";
        break;
    case 'dur':
        $testMsg = "Which is the longest tone?";
        $title = "Duration test";
        $script = "js/soundsDuration.js";
        break;
    case 'freq':
        $testMsg = "Which is the highest pitch tone?";
        $title = "Frequency test";
        $script = "js/soundsFrequency.js";
        break;
    case 'gap':
        $testMsg = "Which is the noise with the gap?";
        $title = "Gap test";
        $script = "js/noisesGap.js";
        break;
    case 'ndur':
        $testMsg = "Which is the longest noise?";
        $title = "Duration test";
        $script = "js/noisesDuration.js";
        break;
    case 'nmod':
        $testMsg = "Which is the modulated noise?";
        $title = "Modulation test";
        $script = "js/noisesModulation.js";
        break;
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="files/logo.png">

    <title>Psychoacoustics-web - <?php echo $title ?> test</title>

    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <?php include 'view_modules/export_test_parameters.php'; ?>
    <script type="text/javascript"
        src="js/test_common/generatorSoundAndNoise.js"
        defer>
    </script>
    <script src="js/test_common/test_shared.js"></script>
    <script type="text/javascript"
        src="<?php echo $script; ?>"
        defer>
    </script>
    <?php include 'view_modules/test_dashboard.php'; ?>
</body>

</html>