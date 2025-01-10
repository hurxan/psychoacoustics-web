<?php

/**
 * this page handle the elimination of a record given an ID and a COUNT
 */
session_start();

include_once "config.php";
include_once "db_connect.php";
include_once "helpers/utils.php";


$testId = $_POST['testId'];
$testCount = $_POST['testCount'];

//this automatically get the returning page
$returnPage = $_SERVER['HTTP_REFERER'];

try {
    $conn = connectdb();
    $sql = "DELETE 
            FROM test 
            WHERE Guest_ID = '{$testId}' AND Test_count = '{$testCount}'";

    $conn->query($sql);
} catch (Exception $e) {
    error_log($e, 3, "errors_log.txt");
    header("Location: ../index.php?err=db");
}

logEvent("User #$testId deleted test $testCount of user #$testId");
header("Location: $returnPage");

