<?php

/**
 * retrieve the test parameters inserted in soundsettings.php or fetch new referral parameters
 */
session_start();

require_once "db_connect.php";
require 'error_codes/soundSettingsErrorCodes.php';
include_once "helpers/utils.php";
include_once "helpers/database_functions.php";


unset($_SESSION['currentBlock']);
/**
 * initialize score and results
 */
$_SESSION['score'] = '';
$_SESSION['geometric_score'] = '';
$_SESSION['results'] = '';
unset($_SESSION['testParameters']);
/**
 *contains the test type in compact form of the test it's going to be taken
 */
unset($_SESSION['testTypeCmp']);

if (isset($_SESSION['referralTest'])) { //referral present

    $refId = $_SESSION['referralTest']['guest'];
    $refCount = $_SESSION['referralTest']['count'];

    try {

        $conn = connectdb();
        $row = getTestParameters($refId, $refCount, $conn);
    } catch (Exception $e) {
        error_log($e, 3, "errors_log.txt");
        header("Location: ../index.php?err=db");
        exit;
    }

    //select the test type to perform
    if ($row['Type'] == 'PURE_TONE_INTENSITY')
        $type = "amp";
    elseif ($row['Type'] == 'PURE_TONE_FREQUENCY')
        $type = "freq";
    elseif ($row['Type'] == 'PURE_TONE_DURATION')
        $type = "dur";
    elseif ($row['Type'] == 'WHITE_NOISE_GAP')
        $type = "gap";
    elseif ($row['Type'] == 'WHITE_NOISE_DURATION')
        $type = "ndur";
    elseif ($row['Type'] == 'WHITE_NOISE_MODULATION')
        $type = "nmod";


    $_SESSION['testTypeCmp'] = $type;
    $testParameters = initializeTestParameter($row);
    $_SESSION['testParameters'] = $testParameters;

    header("Location: ../takeTest.php?type={$type}");
    exit;
}

//this section calls a function to check all the forms inserted
//stored in soundSettinsValidation.php, if no redirect string is returned, it goes on
$redirect = "";
$redirect = checkSSEC();
if ($redirect != "") {
    header($redirect);
    exit;
}

$_SESSION['testTypeCmp'] = $_GET['test'];
$testParameters = initializeTestParameter($_POST);
$_SESSION['testParameters'] = $testParameters;

header("Location: ../takeTest.php?type={$_GET['test']}");
