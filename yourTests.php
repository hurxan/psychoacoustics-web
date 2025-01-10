<?php
session_start();
include "php/config.php";
include_once "php/db_connect.php";
include_once "php/helpers/utils.php";

//check if there is a user logged
if (!isUserLogged())
    header("Location: index.php");

$conn = connectdb();

$usr = $_SESSION['loggedUser']['username'];
$id = $_SESSION['loggedUser']['id'];

try {

    // Get all your tests
    $sql = "SELECT Guest_ID, Test_count, Timestamp, Type FROM test WHERE Guest_ID='$id' AND ref_name = ''";
    $yourResult = $conn->query($sql);

    // Get all guest tests
    $sql = "SELECT ID, Name, Test_count, Timestamp, Type FROM test INNER JOIN guest ON Guest_ID=ID WHERE fk_guest='{$_SESSION['loggedUser']['username']}'";
    $guestResult = $conn->query($sql);

    // Get user Type
    $sql = "SELECT Type FROM account WHERE Guest_ID='$id' AND Username='$usr'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $checkSuperuser = $row['Type'];


} catch (Exception $e) {
    header("Location: index.php?err=db");
}


?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="files/logo.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/staircaseStyle.css">
    <script src="js/libraries/maintainscroll.js"></script>

    <title>Psychoacoustics-web - Test results</title>
</head>


<body>

    <!-- Navigation Bar -->
    <?php include_once 'view_modules/navbar.php'; ?>


    <div class="container">
        <h1 class="mt-5 pt-5">Welcome <?php echo $_SESSION['loggedUser']['username']; ?> <?php echo '  #' . $_SESSION['loggedUser']['id']; ?> </h1>
        <div class="row g-3">

            <!-- download all your data Button -->
            <div class="col d-grid">
                <button type='button' class='btn btn-primary btn-lg btn-red'
                    onclick='location.href="php/download_tests.php?type=0"'>
                    Download all your personal data
                </button>
            </div>

            <!-- Download all your guest's data Button -->
            <div class="col d-grid">
                <button type='button' class='btn btn-primary btn-lg btn-red'
                    onclick='location.href="php/download_tests.php?type=1"'>
                    Download all your partecipants' data
                </button>
            </div>


            <?php

            //function deicated to Superuser
            if ($checkSuperuser == 1) { ?>
                <div class="col d-grid">
                    <button type='button' class='btn btn-primary btn-lg btn-red'
                        onclick='location.href="php/download_tests.php?type=2"'>
                        Download all the data in the database
                    </button>
                </div>
            <?php }
            ?>

        </div>

        <!-- your results Table -->
        <h3 class="mt-5">Your results</h3>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Test</th>
                    <th scope="col">Time</th>
                    <th scope="col">Type</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {

                    while ($row = $yourResult->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row["Test_count"]; ?></td>
                            <td><?php echo $row["Timestamp"]; ?></td>
                            <td><?php echo $row["Type"]; ?></td>

                            <?php $TestId = (int)$row['Guest_ID'] ?>
                            <?php $TestCount = (int)$row['Test_count'] ?>

                            <td class="text-end">
                                <form method="post" action="php/delete_record.php">
                                    <input type="hidden" name="testId" value="<?php echo $TestId; ?>">
                                    <input type="hidden" name="testCount" value="<?php echo $TestCount; ?>">
                                    <button type="submit" class="btn btn-link text-danger p-0"
                                        id="<?php echo $TestId; ?>"
                                        name="delete_id"
                                        title="ID: <?php echo $TestId; ?> COUNT: <?php echo $TestCount; ?>">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                <?php }
                } catch (Exception $e) {
                    header("Location: index.php?err=db");
                }
                ?>
            </tbody>
        </table>


        <!-- Guest's results Table -->
        <h3 class="mt-5">Partecipants' results (<?php echo $guestResult->num_rows; ?>)</h3>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Time</th>
                    <th scope="col">Type</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {


                    while ($row = $guestResult->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row["Name"]; ?></td>
                            <td><?php echo $row["Timestamp"]; ?></td>
                            <td><?php echo $row["Type"]; ?></td>

                            <?php $TestId = (int)$row['ID'] ?>
                            <?php $TestCount = (int)$row['Test_count'] ?>

                            <td class="text-end">
                                <form method="post" action="php/delete_record.php">
                                    <input type="hidden" name="testId" value="<?php echo $TestId; ?>">
                                    <input type="hidden" name="testCount" value="<?php echo $TestCount; ?>">
                                    <button type="submit" class="btn btn-link text-danger p-0"
                                        id="<?php echo $TestId; ?>"
                                        name="delete_id"
                                        title="ID: <?php echo $TestId; ?> COUNT: <?php echo $TestCount; ?>">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                <?php }
                } catch (Exception $e) {
                    header("Location: index.php?err=db");
                }
                ?>
        </table>

    </div>


</body>

</html>