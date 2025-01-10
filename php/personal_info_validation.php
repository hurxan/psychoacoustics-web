<?php   
/**
 * insert the demographics of the user in the database
 * if the user is logged no insertion is needed.
 * if a referral is present, it is MANDATORY to create a new Guest
 * 
 */
session_start();

include "config.php";
include_once('db_connect.php');
include_once('helpers/database_functions.php');
include_once "helpers/utils.php";

/*
 * @var int contains the id of the test taker whether it is a guest or a logged user
 */
unset($_SESSION['idGuestTest']); 
/*
 * @var string contains the name of the guest, if it is setted after this page, a new user 
 * has been created
 */
unset($_SESSION['name']); 
/*
 * @var array contains the key of the referral test if present in the link
 */
unset($_SESSION['referralTest']); 

//creates concatenation string to quick pass test type later
$type = "";
if (isset($_GET["test"]))
    $type = "test=" . $_GET["test"];

//takes referral Code if present
$ref = "";
if (isset($_POST["ref"]) &&  (($_POST["ref"]) != "")){
    $referralCode = $_POST["ref"];
    $ref = "&ref=" . $referralCode;
}
   
//verify injection on POST data
$specialCharacters = checkSpecialCharacter(['name', 'surname', 'notes', 'ref']);
$specialCharacters |= (!is_numeric($_POST["age"]) && $_POST["age"] != "");
//if sql injection test fail, return to previous page
if ($specialCharacters) {
    header("Location: ../demographicData.php?" . $type . $ref . "&err=0");
    exit;
}

$redirection = "Location: ../soundSettings.php?" . $type; //redirection string based on the presence of referal code

if (isset($referralCode) && ($referralCode != "")) { //check if a referral code in the link is present and valid
    
    try {
        $conn = connectdb();
        $refrow = getReferralKeyFromInviteCode($_POST['ref'], $conn); //return an array with referral key
    } catch (Exception $e) { //if invalid
        header("Location: ../demographicData.php?" . $type . $ref . "&ref=&err=3");
        exit;
    }
    $_SESSION['referralTest'] = array( //gather referral key
        "guest" => $refrow['Guest_ID'],
        "count" => $refrow['fk_TestCount']
    );

    $redirection = "Location: ../info.php?".$type; //all referral tests get redirected to info.php page
}

if (!isset($_POST["checkSave"])) { //no data to save, no user to create, skip ahead
    $_SESSION["saveData"] = false;
    header($redirection);
    exit;
}
$_SESSION["saveData"] = true;


if (isUserLogged()) { 
    $_SESSION['idGuestTest'] = $_SESSION['loggedUser']['id']; 

    if (!isset($referralCode)) { //no referral is present, go ahead
        header($redirection);
        exit;
    } else 
        if ($_POST["name"] == "") { //referral is present but no name given (mandatory) return an error
        header("Location: ../demographicData.php?" . $type . $ref . "&err=2");
        exit;
    }
} else {
    if ($_POST["name"] == "") { //name is mandatory if not logged
        header("Location: ../demographicData.php?" . $type . $ref . "&err=1");
        exit;
    }
}


//if none of the condition above validate : i must create a new Guest

// beginning of the insertion query
// create a new guest
$sql = "INSERT INTO guest VALUES (NULL, '" . $_POST["name"] . "',";

$sql .= ($_POST["surname"] == "") ? "NULL, " : "'" . $_POST["surname"] . "', ";
$sql .= ($_POST["age"] == "") ? "NULL, " : "'" . $_POST["age"] . "', ";
$sql .= (!isset($_POST["gender"])) ? "NULL, " : "'" . $_POST["gender"] . "', ";
$sql .= ($_POST["notes"] == "") ? "NULL, " : "'" . $_POST["notes"] . "', ";

if (!isset($referralCode)) {
    $sql .= "NULL);SELECT LAST_INSERT_ID() as id;"; //no referral
} else {
    $sql .= "'" . $refrow['Username'] . "');SELECT LAST_INSERT_ID() as id;"; //if referral present i must insert the referrer Username
}

try {
    $conn = connectdb();
    $conn->multi_query($sql);
    $conn->next_result();
    $result = $conn->store_result();
    $row = $result->fetch_assoc();

    $_SESSION['idGuestTest'] = $row['id']; //the test taker's ID is the new user created
    $_SESSION['name'] = $_POST["name"];

} catch (Exception $e) {
    error_log($e, 3, "errors_log.txt");
    header("Location: ../index.php?err=db");
}

logEvent("Guest #{$_SESSION['idGuestTest']} created");
header($redirection);
