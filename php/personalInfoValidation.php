<?php
try {
    include "config.php";
    //accesso alla sessione
    session_start();
    unset($_SESSION['idGuestTest']); //se c'erano stati altri guest temporanei, li elimino per evitare collisioni
    unset($_SESSION['name']); //se è settato dopo questa pagina, allora è stato creato un nuovo guest
    unset($_SESSION['test']); //se è settato dopo questa pagina, allora è stato usato un referral

    if (isset($_POST["ref"]))
        $ref = "&ref=" . $_POST["ref"];
    else
        $ref = "";

    if (isset($_GET["test"]))
        $type = "test=" . $_GET["test"];
    else
        $type = "";

    //sql injections handling
    $elements = ['name', 'surname', 'notes', 'ref'];
    $characters = ['"', "\\", chr(0)];
    $specialCharacters = false;
    foreach ($elements as $elem) {
        $_POST[$elem] = str_replace("'", "''", $_POST[$elem]);
        foreach ($characters as $char)
            $specialCharacters |= is_numeric(strpos($_POST[$elem], $char));
    }
    $specialCharacters |= (!is_numeric($_POST["age"]) && $_POST["age"] != "");

    if ($specialCharacters) {
        header("Location: ../demographicData.php?" . $type . $ref . "&err=0");
    } else {
        //connessione al db
        $conn = new mysqli($host, $user, $password, $dbname);

        if ($conn->connect_errno)
            throw new Exception('DB connection failed');

        mysqli_set_charset($conn, "utf8");

        // vedo se andranno salvati i dati del test
        $checkSave = 0;
        if (isset($_POST["checkSave"]))
            $checkSave = 1;
        $_SESSION["checkSave"] = $checkSave;

        if ($checkSave == 0) {
            header("Location: ../soundSettings.php?" . $type);
        } else {
            //scrivo la query di creazione del guest
            $sql = "INSERT INTO guest VALUES (NULL, '" . $_POST["name"] . "',";
            $_SESSION['name'] = $_POST["name"];

            if ($_POST["surname"] == "") {
                $_SESSION["surname"] = null;
                $sql .= "NULL, ";
            } else {
                $_SESSION["surname"] = $_POST["surname"];
                $sql .= "'" . $_SESSION["surname"] . "', ";
            }

            if ($_POST["age"] == "") {
                $_SESSION["age"] = null;
                $sql .= "NULL, ";
            } else {
                $_SESSION["age"] = $_POST["age"];
                $sql .= "'" . $_SESSION["age"] . "', ";
            }

            if (!isset($_POST["gender"])) {
                $_SESSION["gender"] = null;
                $sql .= "NULL, ";
            } else {
                $_SESSION["gender"] = $_POST["gender"];
                $sql .= "'" . $_SESSION["gender"] . "', ";
            }

            if ($_POST["notes"] == "") {
                $_SESSION["notes"] = null;
                $sql .= "NULL, ";
            } else {
                $_SESSION["notes"] = $_POST["notes"];
                $sql .= "'" . $_SESSION["notes"] . "', ";
            }

            if ($_POST["name"] == "" && !isset($_SESSION["idGuest"])) { //niente log in e nome mancante (errore)
                header("Location: ../demographicData.php?" . $type . $ref . "&err=1");

            } else if (!isset($_SESSION["idGuest"])) { //niente log in ma c'è il nome (creo il guest)
                $_SESSION["name"] = $_POST["name"];

                if ($_POST["ref"] == "") { //niente referral
                    $_SESSION["ref"] = null;
                    $sql .= "NULL);SELECT LAST_INSERT_ID() as id;";
                    $conn->multi_query($sql);
                    $conn->next_result();
                    $result = $conn->store_result();
                    $row = $result->fetch_assoc();

                    $id = $row['id'];
                    $_SESSION['idGuest'] = $id;
                    $_SESSION['idGuestTest'] = $id;
                    header("Location: ../soundSettings.php?" . $type);
                } else { //c'è il referral
                    $_SESSION["ref"] = $_POST["ref"];
                    $refSQL = "SELECT Username, fk_GuestTest, fk_TestCount FROM account WHERE Referral='{$_POST["ref"]}';";
                    $result = $conn->query($refSQL);
                    $row = $result->fetch_assoc();
                    if (!isset($row['Username'])) {
                        header("Location: ../demographicData.php?&ref=&err=3");
                    } else {
                        $_SESSION['test'] = array(
                            "guest" => $row['fk_GuestTest'],
                            "count" => $row['fk_TestCount']
                        );
                        $sql .= "'" . $row['Username'] . "');SELECT LAST_INSERT_ID() as id;";
                        $conn->multi_query($sql);
                        $conn->next_result();
                        $result = $conn->store_result();
                        $row = $result->fetch_assoc();

                        $id = $row['id'];
                        $_SESSION['idGuestTest'] = $id;
                        if (isset($_SESSION['test']))
                            header("Location: ../info.php");
                    }
                }
            } else { //è stato fatto il log in
                if ($_POST["name"] == "" && $_POST['ref'] == "") {//log in ma niente nome e niente referral, il test va collegato all'account che ha fatto il log in
                    $_SESSION['idGuestTest'] = $_SESSION['idGuest'];
                    header("Location: ../soundSettings.php?" . $type);
                } else if ($_POST["name"] != "" && $_POST['ref'] == "") {//log in e nome ma niente referral, va creato un nuovo guest e va collegato all'account che ha fatto il log in
//                    $_SESSION["name"] = $_POST["name"];
//
//                    $sql .= "'" . $_SESSION['usr'] . "');SELECT LAST_INSERT_ID() as id;";
//
//                    $conn->multi_query($sql);
//                    $conn->next_result();
//                    $result = $conn->store_result();
//                    $row = $result->fetch_assoc();
//
//                    $id = $row['id'];
//                    $_SESSION['idGuestTest'] = $id;
//
//                    $refSQL = "SELECT fk_GuestTest, fk_TestCount FROM account WHERE Username='{$_SESSION['usr']}';";
//                    $result = $conn->query($refSQL);
//                    $row = $result->fetch_assoc();
//
//                    $_SESSION['test'] = array(
//                        "guest" => $row['fk_GuestTest'],
//                        "count" => $row['fk_TestCount']
//                    );
//                    if (isset($_SESSION['test']))
//                        header("Location: ../info.php");
//                    else
//                        header("Location: ../soundSettings.php?" . $type);

                    header("Location: ../demographicData.php?" . $type);
                } else if ($_POST["name"] == "" && $_POST['ref'] != "") {//log in e referral ma niente nome, va lanciato un errore (nome obbligatorio col referral)
                    header("Location: ../demographicData.php?" . $type . $ref . "&err=2");
                } else if ($_POST["name"] != "" && $_POST['ref'] != "") {//log in, referral e nome, va creato un nuovo guest e va collegato all'account del referral
                    $_SESSION["name"] = $_POST["name"];

                    $_SESSION["ref"] = $_POST["ref"];

                    $refSQL = "SELECT Username FROM account WHERE Referral='{$_SESSION["ref"]}';";
                    $result = $conn->query($refSQL);
                    $row = $result->fetch_assoc();    // dopo aver fatto la query controllo se il risultato é nullo, se lo é, il referral non é valido
                    if (!isset($row['Username'])) {
                        header("Location: ../demographicData.php?" . $type . $ref . "&err=3");
                    } else {
                        $sql .= "'" . $row['Username'] . "');SELECT LAST_INSERT_ID() as id;";

                        $conn->multi_query($sql);
                        $conn->next_result();
                        $result = $conn->store_result();
                        $row = $result->fetch_assoc();

                        $id = $row['id'];
                        $_SESSION['idGuestTest'] = $id;

                        $refSQL = "SELECT fk_GuestTest, fk_TestCount FROM account WHERE Referral='{$_SESSION["ref"]}';";
                        $result = $conn->query($refSQL);
                        $row = $result->fetch_assoc();

                        $_SESSION['test'] = array(
                            "guest" => $row['fk_GuestTest'],
                            "count" => $row['fk_TestCount']
                        );
                        if (isset($_SESSION['test']))
                            header("Location: ../info.php");
                        else
                            header("Location: ../soundSettings.php?" . $type);
                    }
                }

            }
        }
    }
} catch (Exception $e) {
    header("Location: ../index.php?err=db");
}
?>
