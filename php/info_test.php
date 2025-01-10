<?php
/**
 * fetch info (parameters) of a given test
 */
session_start();

require_once "db_connect.php";
require_once "helpers/database_functions.php";

$testId = $_SESSION['loggedUser']['id'];
$testCount = $_POST['testCount'];

try {
    $conn = connectdb();

    $row = getTestParameters($testId, $testCount, $conn);

    //put everything in a session array
    $_SESSION['testInfoParameters'] = $row;
} catch (Exception $e) {
    error_log($e, 3, "errors_log.txt");
    header("Location: ../index.php?err=db");
}

header("Location: ../userSettings.php");
