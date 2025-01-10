<?php

/**
 * verify new referral info given before redirecting to test setting form
 */

session_start();
include_once "db_connect.php";
include_once "helpers/utils.php";
include_once "helpers/database_functions.php";


$specialCharacter = checkSpecialCharacter(['referralName']);
if ($specialCharacter) {
	header("Location: ../userSettings.php?&err=0");
	exit;
}

$referralName = $_POST['referralName'];
if (!isset($referralName) || $referralName == "") { //IF NO TEST NAME GIVEN
	header('Location: ../userSettings.php?err=8');
	exit;
}

$testType = $_POST['testType'];
if (!isset($testType) || $testType == "") { //IF NO TEST TYPE HAS BEEN SELECTED
	header('Location: ../userSettings.php?err=5');
	exit;
}

$id = $_SESSION['loggedUser']['id'];

try {
	$conn = connectdb();

	//check if the referral test already exist
	$sql = "SELECT COUNT(*) 
			FROM test 
			WHERE Ref_name='$referralName' AND Guest_ID = '$id'";

	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

	if ($row['COUNT(*)'] > 0) {
		header('Location: ../userSettings.php?err=7'); //this test name already exist for this user
		exit;
	}
} catch (Exception $e) {
	header('Location: ../index.php?err=db');
	exit;
}

$_SESSION['creatingNewReferral'] = true; //used to redirect to php/save_new_referral.php from soundSettings.php
header("Location: ../soundSettings.php?test=" . $testType . "&refn=" . $referralName);
