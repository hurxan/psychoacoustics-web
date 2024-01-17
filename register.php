<!DOCTYPE html>
<html>
<head>
    <?php
    include "php/config.php";
    session_start()
    ?>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/x-icon" href="files/logo.png">
    <title>Psychoacoustics-web - Register</title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/staircaseStyle.css" rel="stylesheet">
    <!--		<link rel="stylesheet" href="css/formStyle.css-->
    <?php //if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?><!--"> -->
</head>
<body>
<?php
//se si sceglie un username già esistente verrà messo "?err=1" nell'url
if (isset($_GET['err'])) {
    if ($_GET['err'] == 0)
        echo "<div class='alert alert-danger'>Some inserted characters aren't allowed</div>";
    if ($_GET['err'] == 1)
        echo "<div class='alert alert-danger'>Username already taken</div>";
}
?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <div class="p-5 border rounded rounded-4 bg-light">
                <h2>Sign Up</h2>
                <form method="post" action="php/registering.php" onsubmit="validation()">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Username*</span>
                        <input type="text" class="form-control" placeholder="Username" required name="usr">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Password*</span>
                        <input type="password" class="form-control" placeholder="Password" required name="psw">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Email*</span>
                        <input type="text" class="form-control" placeholder="Email" required name="email">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Name*</span>
                        <input type="text" class="form-control" placeholder="Name" required name="name">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Surname</span>
                        <input type="text" class="form-control" placeholder="Surname" name="surname">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Birth date</span>
                        <input type="date" class="form-control" name="date">
                    </div>
                    <select name='gender' class="form-select mb-3">
                        <option disabled="disabled" selected value="null" id="NullGender">Select your gender</option>
                        <?php
                        try {
                            $conn = new mysqli($host, $user, $password, $dbname);
                            if ($conn->connect_errno)
                                throw new Exception('DB connection failed');
                            mysqli_set_charset($conn, "utf8");

                            $sql = "SELECT COLUMN_TYPE AS ct FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'psychoacoustics_db' AND TABLE_NAME = 'guest' AND COLUMN_NAME = 'gender';";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();//questa query da un risultato di tipo enum('Male','Female','Non-Binary')

                            //metto i valori in un array
                            $values = substr($row['ct'], 5, -1);//tolgo "enum(" e ")"
                            $values = str_replace("'", "", $values);//tolgo gli apici
                            $list = explode(",", $values);//divido in una lista in base alle virgole

                            //creo un'opzione per ogni possibile valore
                            foreach ($list as $elem)
                                echo "<option value='" . strtoupper($elem) . "'>" . strtoupper($elem) . "</option>";
                        } catch (Exception $e) {
                            header("Location: index.php?err=db");
                        }
                        ?>
                    </select>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Notes</span>
                        <input type="text" class="form-control" placeholder="Notes" name="notes">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg btn-red">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

<script>
    function validation() {
        document.getElementById("NullGender").disabled = false;
    }
</script>
</html>
