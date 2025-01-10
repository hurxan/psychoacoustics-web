<?php

/**
 * update demographic data of the logged user from userSettings.php
 */
session_start();
include_once "config.php";
include_once "db_connect.php";
include_once "helpers/database_functions.php";
include_once "helpers/utils.php";

//check for injection on post data
$specialCharacters = checkSpecialCharacter(['usr', 'email', "name", "surname", "notes"]);
if ($specialCharacters) {
	header("Location: ../userSettings.php?&err=0");
	exit;
}

try {
	$conn = connectdb();

	//fetch all user data
	$sql = "SELECT username, date, email, name, surname, notes, gender 
			FROM account INNER JOIN guest ON account.Guest_ID = guest.ID
			WHERE username='{$_SESSION['loggedUser']['username']}';";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

	$somethingChanged = false;
	$username = $_SESSION['loggedUser']['username'];

	$sql = "UPDATE account SET ";

	if ($_POST['email'] != $row['email']) {
		$sql .= "email = '{$_POST['email']}', ";
		$somethingChanged = true;
	}

	if ($_POST['date'] != $row['date']) {
		$sql .= "date = '{$_POST['date']}', ";
		$somethingChanged = true;
	}

	$sql = substr($sql, 0, -2);
	$sql .= "WHERE username='$username';";
	if ($somethingChanged)
		$conn->query($sql);


	$somethingChanged = false;
	$sql = "UPDATE guest SET ";

	if ($_POST['name'] != $row['name']) {
		$sql .= "name = '{$_POST['name']}', ";
		$somethingChanged = true;
	}

	if ($_POST['surname'] != $row['surname']) {
		$sql .= "surname = '{$_POST['surname']}', ";
		$somethingChanged = true;
	}

	if ($_POST['notes'] != $row['notes']) {
		$sql .= "notes = '{$_POST['notes']}', ";
		$somethingChanged = true;
	}

	if ($_POST['gender'] != $row['gender']) {
		$sql .= "Gender = '{$_POST['gender']}', ";
		$somethingChanged = true;
	}

	$sql = substr($sql, 0, -2);
	$sql .= "WHERE ID='{$_SESSION['loggedUser']['id']}';";
	if ($somethingChanged)
		$conn->query($sql);


	logEvent("User #{$_SESSION['loggedUser']['id']} changed his user data");
	header("Location: ../userSettings.php");

} catch (Exception $e) {
	header("Location: ../index.php?err=db");
}
