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
<div class="container my-5 p-4 p-sm-5 border rounded rounded-4 bg-light">
    <p style="color: black;"> TERMS AND CONDITION
        PSYCHOACOUSTICS-WEB is a research tool designed by Andrei Senyuva, Giulio Contemori, Andrea Felline, Gnana Prakash Goli, Mauro Migliardi, Niccolò Orlandi, Mattia Toffanin under the supervision and responsibility of Massimo Grassi. The responsible person and referent person of PSYCHOACOUSTICS-WEB (hereafter referred to as “referent person”) is Massimo Grassi, Department of General Psychology, University of Padua, via Venezia 8, 35134, Padua, Italy, email: massimo.grassi@unipd.it, phone (office): +39 0498277494. PSYCHOACOUSTICS-WEB complies with the current pertinent regulations related to research ethics and professional deontology, such as: The General Data Protection Regulation (EU) 2016/679 ("GDPR"), the decree “Regole deontologiche per trattamenti a fini statistici o di ricerca scientifica pubblicate ai sensi dell’art. 20, comma 4, del d.lgs. 10 agosto 2018, n. 101 - 19 dec 2018”.

        Brief description and aim.
        PSYCHOACOUSTICS-WEB is a tool that enables to estimate the human auditory sensitivity for a set of acoustical parameters such as acoustics frequency, acoustic intensity, sound duration and other characteristics related to sound. PSYCHOACOUSTICS-WEB is not a clinical tool nor provides clinical measures related to hearing. It is a purely research tool that provides measures of human auditory sensitivity that are relevant for research purposes only. PSYCHOACOUSTICS-WEB cannot be used for commercial purposes. PSYCHOACOUSTICS-WEB is designed for three different types of users and can be used in three ways: (i) by an occasional user that accesses the tool from the tool’s website; (ii) by a researcher (hereafter referred to as researcher) that creates a personal account and invites participants to participate to experiments s/he created with the tool via direct links; (iii) by a participant of an experiment that receives a direct link to one experiment sent by a researcher.

        Accessibility and support.
        If you need assistance or further information about the tool please contact the referent person. If you are the participant of an experiment, please refer to the researcher that invited you via invite link.

        Data treatment.
        The data collected through PSYCHOACOUSTICS-WEB can be used for research, teaching or “third mission” (e.g., scientific dissemination to the general population) purposes either by the referent person or by the researcher that invited you to use the tool. The referent person and/or the researcher commit to treat the data collected with confidentiality. Your privacy will be protected to the maximum extent allowable by law. Data will be stored in the web-server for the Department of General Psychology of the University of Padua that is accessible by the referent person or by the technical personnel of the department. In the case data are downloaded from the server, they will be stored in the referent person’s personal computer, and/or in the researcher’s personal computer and/or in the personal computer of the referent person’s collaborators and/or researcher’s collaborators. Data can also be stored into open access research archives and made available to the research community. If your data will be used for any of the aims described above, your data will be presented either aggregated or -if presented individually- anonymized so that your identity will remain confidential to the referent person and/or the researcher. In the case data will be stored in an open access research archive, data will be anonymized. The person responsible for your data is either the referent person or the researcher.

        Deletion of your data.
        When you use PSYCHOACOUSTICS-WEB, the tool assigns you a Guest_ID, this ID is a unique identifier of your data. In the case you want to withdraw the right to use your data, you can ask for the deletion of the data from the server by contacting the referent person and providing your Guest_ID. The Guest_ID can also be provided to the referent person, the researcher and their collaborators to delete the copy of your data that are stored in the personal computers of the research team conducting the research. The deletion request must arrive within five years from the moment the data were collected. After this time, the referent person, the researcher or the collaborators reserve the right to keep your data.

        Additional terms and conditions for “researchers”.
        In the case you create a personal account in PSYCHOACOUSTICS-WEB, the personal data of your account will be stored in the server of the Department of General Psychology of the University of Padua. Your personal data will be accessible only by the referent person or by the technical personnel of the department. The referent person reserves the right to block the account of the researcher in the case the researcher does not respect the terms and conditions written above or uses the tool inappropriately.
    </p>
</div>
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
                    <div>
                        <p style="color: black;">If you click “register” you accept the conditions reported below.</p> 
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
