<?php

/**
 * validate the usr and psw given to log User in
 */

session_start();

include_once "helpers/database_functions.php";
include "config.php";
include_once "db_connect.php";
include_once "helpers/utils.php";


//verify any injection on POST data
$specialCharacters = checkSpecialCharacter(['usr', 'psw']);
if ($specialCharacters) {
	header("Location: ../login.php?&err=0");
	exit;
}

$usr = $_POST['usr'];
$psw = $_POST['psw'];

try {
	$conn = connectdb();

	//check if the user actually exists
	$sql = "SELECT Guest_ID 
			FROM account 
			WHERE Username='$usr' AND Password=SHA2('$psw', 256)";

	$result = $conn->query($sql);

	if ($result->num_rows <= 0) { //error if no user is found
		header('Location: ../login.php?err=1');
		$conn->close();
		exit;
	}

	$row = $result->fetch_assoc();

	$_SESSION['loggedUser']['username'] = $usr;
	$_SESSION['loggedUser']['id'] = $row['Guest_ID'];
} catch (Exception $e) {
	error_log($e, 3, "errors_log.txt");
	header("Location: ../index.php?err=db");
}

logEvent("User #{$_SESSION['loggedUser']['id']} logged in");
header('Location: ../index.php');
