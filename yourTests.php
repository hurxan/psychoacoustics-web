<!doctype html>
<html lang="en">

<head>
    <?php
    session_start();
    if (!isset($_SESSION['usr']) || !isset($_SESSION['idGuest']))
        header("Location: index.php");
    include "php/config.php";
    ?>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="files/logo.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/staircaseStyle.css">
    <title>Psychoacoustics-web - Test results</title>
</head>
<body>

<nav class="navbar navbar-dark bg-dark shadow-lg text-white">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="files/logo.png" alt="" width="25" height="25" class="d-inline-block align-text-top">
            PSYCHOACOUSTICS-WEB
        </a>
        <form class="d-flex align-items-center">
            <label class='text-white navbar-text me-3'>Welcome <?php echo $_SESSION['usr'] ?></label>
            <button class="btn btn-outline-light me-3" type="button" onclick="location.href='yourTests.php'">
                Your tests
            </button>
            <button class="btn btn-outline-light me-3" type="button" onclick="location.href='php/logout.php'">
                Log Out
            </button>
            <a class='settings navbar-text' href='userSettings.php'>
                <i class='material-icons rotate text-white'>settings</i>
            </a>
        </form>
    </div>
</nav>

<div class="container">
    <h1 class="mt-5">Welcome <?php echo $_SESSION['usr']; ?></h1>
    <div class="row g-3">
        <div class="col d-grid">
            <button type='button' class='btn btn-primary btn-lg btn-red'
                    onclick='location.href="php/downloadYours.php?all=1"'>
                Download all your data
            </button>
        </div>
        <div class="col d-grid">
            <button type='button' class='btn btn-primary btn-lg btn-red'
                    onclick='location.href="php/downloadYours.php?all=0"'>
                Download all your guest's data
            </button>
        </div>
        <?php
        try {
            $conn = new mysqli($host, $user, $password, $dbname);

            if ($conn->connect_errno)
                throw new Exception('DB connection failed');

            mysqli_set_charset($conn, "utf8");

            $usr = $_SESSION['usr'];
            $id = $_SESSION['idGuest'];

            $sql = "SELECT Type FROM account WHERE Guest_ID='$id' AND Username='$usr'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            if ($row['Type'] == 1) { ?>
                <div class="col d-grid">
                    <button type='button' class='btn btn-primary btn-lg btn-red'
                            onclick='location.href="php/downloadAll.php"'>
                        Download all the data in the database
                    </button>
                </div>
            <?php }
        } catch (Exception $e) {
            header("Location: index.php?err=db");
        }
        ?>
    </div>

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
            $sql = "SELECT Test_count, Timestamp, Type FROM test WHERE Guest_ID='$id'";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row["Test_count"]; ?></td>
                    <td><?php echo $row["Timestamp"]; ?></td>
                    <td><?php echo $row["Type"]; ?></td>
                </tr>
            <?php }
        } catch (Exception $e) {
            header("Location: index.php?err=db");
        }
        ?>
        </tbody>
    </table>

    <h3 class="mt-5">Your guest's results</h3>
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
            $sql = "SELECT Name, Test_count, Timestamp, Type FROM test INNER JOIN guest ON Guest_ID=ID WHERE fk_guest='{$_SESSION['usr']}'";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row["Name"]; ?></td>
                    <td><?php echo $row["Test_count"]; ?></td>
                    <td><?php echo $row["Timestamp"]; ?></td>
                    <td><?php echo $row["Type"]; ?></td>
                </tr>
            <?php }
        } catch (Exception $e) {
            header("Location: index.php?err=db");
        }
        ?>
    </table>
    <div class="d-grid my-5">
        <button type="button" class="btn btn-primary btn-lg btn-red" id="home" onclick="location.href='index.php'">Home</button>
    </div>
</div>

</body>
</html>


