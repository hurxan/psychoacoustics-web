<!DOCTYPE html>
<html>
<head>
    <?php session_start(); ?>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Psychoacoustics-web - Login</title>
    <link rel="icon" type="image/x-icon" href="files/logo.png">

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/staircaseStyle.css" rel="stylesheet">
    <!--    <link rel="stylesheet"-->
    <!--          href="css/formStyle.css-->
    <?php //if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?><!--">-->
</head>
<body>
<?php
//se si sceglie un username già esistente verrà messo "?err=1" nell'url
if (isset($_GET['err'])) {
    if ($_GET['err'] == 0)
        echo "<div class='alert alert-danger'>Some inserted characters aren't allowed</div>";
    if ($_GET['err'] == 1)
        echo "<div class='alert alert-danger'>Incorrect username or password</div>";
}
?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-4">
            <div class="p-5 border rounded rounded-4 bg-light">
                <h2>Login</h2>
                <form method="post" action="php/log.php">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Username</span>
                        <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                               aria-describedby="basic-addon1" required name="usr">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Password</span>
                        <input type="password" class="form-control" placeholder="Password" aria-label="Username"
                               aria-describedby="basic-addon1" required name="psw">
                    </div>
                    <div class="d-grid">
                        <!--input type="password" id="password" placeholder="Password" name="password"-->
                        <button type="submit" class="btn btn-primary btn-lg btn-red">Log in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>