<?php
/**
 * create a new account based on info given.
 * every account is associated with a Gest where all the main demographic is saved
 * the is of the Account and Guest is the same, created with an increasing integer
 */
session_start();

include_once "config.php";
include_once "db_connect.php";
include_once "helpers/database_functions.php";
include_once "helpers/utils.php";

/*
 * @var string contains the name of the logged user
 */
$_SESSION['loggedUser']['username'] = "";
/*
 * @var int contains the id of the logged user
 */
$_SESSION['loggedUser']['id'] = 0;


//sql injections handling
$specialCharacters = checkSpecialCharacter(['usr', 'psw', 'name', 'surname', 'email', 'notes']);
if ($specialCharacters) {
	header("Location: ../register.php?&err=0");
	exit;
}

//get username from form
$usr = $_POST['usr'];

try {
	
	$conn = connectdb();

	//test for selectFromTable Function, ignore
	$result = selectFromTable(['*'], 'account', ["Username='$usr'"], $conn);

	//chech if it already exist
	if ($result->num_rows > 0) {
		header('Location: ../register.php?err=1');
		exit;
	}

	//takes all form data
	$psw = $_POST['psw'];
	$name = $_POST['name'];
	$surname = $_POST['surname'];
	$date = $_POST['date'];
	$gender = $_POST['gender'];
	$notes = $_POST['notes'];
	$email = $_POST['email'];   

	//crate insertion query
	
	//create guest
	$sql = "INSERT INTO guest (Name";
	$sqlVal = " VALUES ('$name'";

	if ($surname != "") {
		$sql .= ",Surname";
		$sqlVal .= ",'$surname'";
	}

	if ($gender != "NULL") {
		$sql .= ",Gender";
		$sqlVal .= ",'$gender'";
	}

	if ($notes != "") {
		$sql .= ",Notes";
		$sqlVal .= ",'$notes'";
	}

	$sql .= ")";
	$sqlVal .= ");SELECT LAST_INSERT_ID() as id;";
	$sql .= $sqlVal;

	$conn->multi_query($sql);
	$conn->next_result();
	$result = $conn->store_result();
	$row = $result->fetch_assoc();
	$id = $row['id'];

	//create a new account
	$sql = "INSERT INTO account VALUES ('$usr', SHA2('$psw', 256) ";

	if ($date != "")
		$sql .= ",'$date' ";
	else
		$sql .= ",NULL ";

	$sql .= ",'$id', '0', '" . base64_encode($usr) . "', NULL, NULL, '$email');";
	$conn->query($sql);

	$_SESSION['loggedUser']['username'] = $usr;
	$_SESSION['loggedUser']['id'] = $id;

	$conn->close();
} catch (Exception $e) {
	header("Location: ../index.php?err=db");
}

logEvent("New account created #$id");
header('Location: ../index.php');
	