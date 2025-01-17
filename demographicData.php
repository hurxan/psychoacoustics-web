<?php
session_start();
include_once "php/db_connect.php";
include "php/config.php";
include_once "php/helpers/utils.php";
//if no  test has been selected or no referral link is present in the url, return
//to the index page
if (!isset($_GET['ref']) && !isset($_GET["test"]))
    header("Location: index.php");
?>

<!doctype html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="files/logo.png">

    <!-- Bootstrap CSS -->

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/staircaseStyle.css<?php if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?>">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/funzioni.js<?php if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?>"></script>
    
    <title>Psychoacoustics-web - Personal info</title>

    <script>
        /**
         * this function is needed to hide div marked with "conditional Display" on specific conditons 
         */
        function verifyRef() {
            var display = true;
            var logged = <?php if (isUserLogged()) echo "true";
                            else echo "false"; ?>;

            if (document.getElementById("ref").value != "" && logged) { //if the user is logged
                if (document.getElementById("name").innerHTML.slice(-1) != "*")
                    document.getElementById("name").innerHTML += "*";
                display = true;
            } else
            if (document.getElementById("ref").value == "" && logged) {
                display = false;
            }

            if (!document.getElementById("checkSave").checked) {
                display = false;
                console.log("The checkbox is not checked.");
            }

            updatePage(display);
        }


        function insertRef() {
            <?php
            if (isUserLogged()) {
                try {

                    $conn = connectdb();

                    $sql = "SELECT Referral as ref FROM account WHERE Username='{$_SESSION['loggedUser']['username']}'";

                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                } catch (Exception $e) {
                    header("Location: index.php?err=db");
                }
            }
            ?>
            document.getElementById("ref").value = <?php //get the user referral to add with "ADD MINE"-button only if the user is logged
                                                    if (isUserLogged()) {
                                                        echo "'" . $row['ref'] . "'";
                                                    } else
                                                        echo "''";
                                                    ?>;
            verifyRef();
        }
    </script>


    <style>
        /* Add this style to increase the width of the range input */
        #volume {
            width: 500px;
            /* Adjust the width as needed */
        }
    </style>
</head>



<body onload="verifyRef()">
    <?php
    //if an already existing name is selected "?err=1" will be returned in the url
    if (isset($_GET['err'])) {
        if ($_GET['err'] == 0)
            echo "<div class='alert alert-danger'>Some inserted characters aren't allowed</div>";
        if ($_GET['err'] == 1)
            echo "<div class='alert alert-danger'>The name field is required</div>";
        if ($_GET['err'] == 2)
            echo "<div class='alert alert-danger'>The name field is required when using a referral code</div>";
        else if ($_GET['err'] == 3)
            echo "<div class='alert alert-danger'>Invalid referral code</div>";
    }
    ?>


    <div class="container my-5 p-4 p-sm-5 border rounded rounded-4 bg-light">
        <h2>Personal Informations</h2>

        <form name="staircase" method="post" action="php/personal_info_validation.php<?php
                                                                                        if (isset($_GET["test"]))
                                                                                            echo "?test=" . $_GET["test"];
                                                                                        ?>">
            <!-- this box contains all the page main buttons and content -->
            <div class="row align-items-center g-4">

                <!--name form -->
                <div class="col-12 col-lg-3">
                    <div class="input-group flex-nowrap conditionalDisplay">
                        <span class="input-group-text" id="name">Name<?php if (!isset($_SESSION['loggedUser']['username'])) echo "*"; ?></span>
                        <input type="text" class="form-control" id="inputName" placeholder="Name" name="name">
                    </div>
                </div>

                <!--surname form -->
                <div class="col-12 col-lg-3">
                    <div class="input-group flex-nowrap conditionalDisplay">
                        <span class="input-group-text" id="surname">Surname</span>
                        <input type="text" class="form-control" id="inputSurname" placeholder="Surname" name="surname">
                    </div>
                </div>

                <!--age form -->
                <div class="col-12 col-lg-3">
                    <div class="input-group flex-nowrap conditionalDisplay">
                        <span class="input-group-text" id="age">Age</span>
                        <input type="text" class="form-control" id="inputAge" placeholder="Age" name="age">
                    </div>
                </div>

                <!--gender form -->
                <div class="col-12 col-lg-3">
                    <div class="input-group flex-nowrap conditionalDisplay">
                        <span class="input-group-text" id="gender">Gender</span>
                        <select class="form-select" id="gender" name="gender">
                            <option value="" selected disabled>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <!--notes form -->
                <div class="col-12">
                    <div class="input-group flex-nowrap conditionalDisplay" id="notesDiv">
                        <span class="input-group-text" id="notes">Notes</span>
                        <input type="text" class="form-control" id="inputNotes" placeholder="Notes" name="notes">
                    </div>
                </div>


                <!--Warning + Slider box -->
                <div class="row mt-3">
                    <div class="col-12 col-lg-6">
                        <p style="color: black;">
                            <strong>Warning:</strong> High intensity sounds can damage your hearing. Please use the slider to adjust the intensity to a comfortable level before starting the test.<br>
                            <strong>Notice:</strong> The maximum intensity also depends on your system volume setting.
                        </p>
                    </div>

                    <div class="col-12 col-lg-6">
                        <form method="post" action="">
                            <label for="volume">Intensity:</label>
                            <input type="range" id="volume" name="volume" min="0" max="100" step="1">
                        </form>
                    </div>
                </div>


                <!--save result check-->
                <div class="col-12 col-lg-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="checkSave" name="checkSave" onclick="verifyRef()" checked>
                        <label class="form-check-label" for="flexCheckDefault">Save results</label>
                    </div>
                </div>


                <!--referral code box -->
                <div class="col-12 col-lg-6">
                    <div class="row align-items-center justify-content-between g-4">
                        <div class="col-12 col-lg-6">
                            <div class="input-group flex-nowrap referral">
                                <span class="input-group-text" id="notes">Invite code</span>
                                <input type="text" class="form-control" id="ref" name="ref" onkeyup="verifyRef()" value="<?php if (isset($_GET['ref'])) {
                                                                                                                                echo $_GET['ref'];
                                                                                                                            } ?>">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 d-grid">
                            <?php if (isset($_SESSION['loggedUser']['username'])) { ?>
                                <button type="button" class="btn btn-primary btn-red refButton" onclick="insertRef()">
                                    USE MINE
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>


                <!-- navigation buttons -->
                <div class="container mt-3">
                    <div class="row align-items-center g-4">
                        <div class="col-12">
                            <div class="col-lg-6">
                                <?php if (!isUserLogged()) { ?>
                                    <div class="col-12 ">
                                        <p style="color: black;"><strong>Warning:</strong> if you press “NEXT” you accept the “Terms and conditions" written below.</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row row-cols-2 gy-2">
                            <div class="col d-grid">
                                <button type="button" class="btn btn-primary btn-lg btn-red" onclick="location.href='index.php'">
                                    BACK
                                </button>
                            </div>
                            <div class="col d-grid">
                                <button type="submit" class="btn btn-primary btn-lg btn-red">
                                    NEXT
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </form>
    </div>

    <?php if (!isset($_SESSION['loggedUser']['username'])) { ?>
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
    <?php } ?>

    <script>
        // Ensure the setup is called when the DOM is fully loaded
        document.addEventListener("DOMContentLoaded", () => {
            setupVolumeControl(); // Initialize volume control function
        });
    </script>


    </body>

</html>