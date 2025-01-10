<?php

/**
 *this page make a given test the active one for the logged user
 */
session_start();

include "config.php";
require_once "db_connect.php";
include_once "helpers/utils.php";

$testCount = $_POST['testCount'];
$id = $_SESSION['loggedUser']['username'];

try {
    $conn = connectdb();
    
    $sql = "UPDATE account 
            SET fk_TestCount = '$testCount'  
            WHERE Username = '$id'";
    $conn->query($sql);
} catch (Exception $e) {
    error_log($e, 3, "errors_log.txt");
    header("Location: ../index.php?err=db");
}

logEvent("User #{$_SESSION['loggedUser']['id']} changed his referral test");
header("Location: ../userSettings.php");

