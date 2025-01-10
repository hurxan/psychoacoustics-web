<?php

/**
 * updates user password from setting page
 */

session_start();

include_once "config.php";
include "db_connect.php";
include "helpers/database_functions.php";
include_once "helpers/utils.php";

//verify any injection on POST data
$specialCharacters = checkSpecialCharacter(['oldPsw', 'newPsw']);
if ($specialCharacters) {
    header("Location: ../userSettings.php?&err=0");
    exit;
}

$oldPsw = $_POST['oldPsw'];
$newPsw = $_POST['newPsw'];

try {

    $conn = connectdb();

    //look for user with credentials given, takes email
    $sql = "SELECT email 
            FROM account 
            WHERE Username ='" . $_SESSION['loggedUser']['username'] . "' 
                  AND 
                  password = SHA2('$oldPsw', 256)";
    $result = $conn->query($sql);

    if ($result->num_rows <= 0) { //error if usr could not be found (no rows in results)
        header('Location: ../userSettings.php?err=2');
        $conn->close();
        exit;
    }

    //update password
    $sql = "UPDATE account 
            SET password = SHA2('$newPsw', 256)  
            WHERE username= '" . $_SESSION['loggedUser']['username'] . "'";
    $conn->query($sql);

    $row = $result->fetch_assoc();
    //send confirmation email to fetched email
    $email = $row['email'];
    mail($email, 'Password changing', 'you have correctly changed the password');

} catch (Exception $e) {
    header("Location: ../index.php?err=db");
    error_log($e, 3, "errors_log.txt");
}

logEvent("User #{$_SESSION['loggedUser']['id']} changed his password");
header('Location: ../userSettings.php?err=3'); //this is not an error

