<?php
session_start();
include_once "php/helpers/database_functions.php";
include_once "php/db_connect.php";
include_once "php/helpers/utils.php";

if (!isUserLogged()) {
    header("Location: index.php?err=2");
    exit;
}

//fetch all user demographic data
try {
    $conn = connectdb();

    $sql = "SELECT referral, name, surname, date, gender, notes, email 
                FROM account INNER JOIN guest ON account.Guest_ID = guest.ID 
                WHERE username='" . $_SESSION['loggedUser']['username'] . "'";

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
} catch (Exception $e) {
    header("Location: index.php?err=db");
    exit;
}

//data to display in Change user setting section
$name = $row['name'];
$sur = $row['surname'];
$date = $row['date'];
$gender = $row['gender'];
$notes = $row['notes'];
$email = $row['email'];

$inviteCode = $row['referral'];
$activeTestTypeExt = 'No test created yet';

//fetch data of user's active referral test
try {

    $refrow = getReferralKeyFromInviteCode($inviteCode, $conn); //return an array with referral data

    $activeReferralKey = array( //gather referral data
        "guest" => $refrow['fk_GuestTest'],
        "count" => $refrow['fk_TestCount']
    );
} catch (Exception $e) { //if invalid
    header("Location: ../demographicData.php?" . $type . $inviteCode . "&ref=&err=3");
    exit;
}


//fetch all the user's created referral
try {

    $sql = "SELECT * 
            FROM test 
            WHERE Guest_ID = '{$_SESSION['loggedUser']['id']}' AND Ref_name != ''
            ORDER BY Timestamp DESC";
    $allRefTest = $conn->query($sql);
} catch (Exception $e) {
    header("Location: index.php?err=db");
    exit;
}

//param array for info button, comes from php/info_test.php
$param = null;
if (isset($_SESSION['testInfoParameters'])) {
    $param = $_SESSION['testInfoParameters'];
    unset($_SESSION['testInfoParameters']);
}

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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <link href="css/staircaseStyle.css" rel="stylesheet">
    <script type="text/javascript" src="js/funzioni.js<?php if (isset($_SESSION['version'])) echo "?{$_SESSION['version']}"; ?>"></script>
    <title>Psychoacoustics-web - User settings</title>
</head>

<body>

    <!-- Navigation Bar -->
    <?php include_once 'view_modules/navbar.php'; ?>

    <?php
    //se si sceglie un username già esistente verrà messo "?err=1" nell'url
    if (isset($_GET['err'])) {

        if ($_GET['err'] == 0)
            echo "<div class='alert alert-danger'>Some inserted characters aren't allowed</div>";
        if ($_GET['err'] == 1)
            echo "<div class='alert alert-danger'>Username already taken</div>";
        if ($_GET['err'] == 2)
            echo "<div class='alert alert-danger'>Wrong password</div>";
        if ($_GET['err'] == 3)
            echo "<div class='alert alert-success'>Password changed</div>";
        if ($_GET['err'] == 4)
            echo "<div class='alert alert-success'>Test created Successfully</div>";
        if ($_GET['err'] == 5)
            echo "<div class='alert alert-danger'>Select a test type from the menu</div>";
        if ($_GET['err'] == 6)
            echo "<div class='alert alert-danger'>The created test already exist, it is now your active referral</div>";
        if ($_GET['err'] == 7)
            echo "<div class='alert alert-danger'>There already is a test with the same name</div>";
        if ($_GET['err'] == 8)
            echo "<div class='alert alert-danger'>Insert a name for the test</div>";
    }
    ?>

    <div class="container my-5">

        <!-- Create New Referral -->
        <div class="container-fluid p-4 border rounded-4 bg-light">
            <h4 class="mb-3">Create New Experiment</h4>
            <form action="php/create_new_referral.php" method="POST" class="settingForm ref">
                <div class="row row-cols-1 row-cols-lg-2 g-3 justify-content-center align-items-center">

                    <!-- invite code box -->
                    <div class="col">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text title" title="click to copy">Invite code</span>
                            <span class="input-group-text form-control link" id="ref" title="click to copy"><?php echo $inviteCode; ?></span>
                        </div>
                    </div>

                    <!-- full referral link box -->
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text title" title="click to copy">Link</span>
                            <span class="input-group-text form-control overflow-scroll link" id="link" title="click to copy">https://psychoacoustics.dpg.psy.unipd.it/sito/demographicData.php?ref=<?php echo $inviteCode; ?></span>
                        </div>
                    </div>


                    <!-- Test type selection -->
                    <div class="col">
                        <div class="row g-3">

                            <!-- Ref name and test type selection in one row -->
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">Ref. Name</span>
                                    <input type="text" class="form-control" placeholder="Insert a test name" name="referralName">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <select name="testType" class="form-select" id="testType">
                                    <option selected disabled value=''>Select a Test Type</option>
                                    <option value='amp'>Pure tone intensity discrimination</option>
                                    <option value='freq'>Pure tone frequency discrimination</option>
                                    <option value='dur'>Pure tone duration discrimination</option>
                                    <option value='gap'>White noise gap detection</option>
                                    <option value='ndur'>White noise duration discriminaiton</option>
                                    <option value='nmod'>White noise AM detection</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <!-- change test settings button -->
                    <div class="col">
                        <div class="row row-cols-2 g-3 justify-content-end">

                            <div class="col d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-red w-100">
                                    Create Experiment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>




        <!-- Your Referral Section -->
        <div class="container-fluid p-4 border rounded-4 bg-light mt-5 ">
            <h4 class="mb-3">Your Experiments</h4>

            <?php while ($row = $allRefTest->fetch_assoc()) {
                $borderStyle = ''; //to give blue border on selected test
                $color = 'style="background-color: #ffffff;"';
                $selected = false;

                if ($row['Test_count'] == $activeReferralKey['count']) { //if the test visualized is the active one
                    $borderStyle = 'border border-2 border-primary';
                    //$color = 'style="background-color: #e8f4fa;"';
                    $selected = true;
                } ?>

                <!-- test card -->
                <div class="container-fluid p-3 border rounded-4 mt-2 d-flex justify-content-between align-items-center shadow-sm bg-body rounded <?php echo $borderStyle ?>" <?php echo $color; ?>>

                    
                     <!-- Info + test name div -->
                    <div class="d-flex align-items-center">

                        <!-- Info button -->

                        <form action="php/info_test.php" method="POST">
                            <div class="me-5">
                                <!-- Hidden input fields -->
                                <input type="hidden" name="testId" value="<?php echo $row['Guest_ID']; ?>">
                                <input type="hidden" name="testCount" value="<?php echo $row['Test_count']; ?>">

                                <div class="text-center">
                                    <!-- Submit button for the form -->
                                    <button type="submit" class="btn btn-light btn-sm rounded-circle p-2" style="width: 40px; height: 40px; border: 1px solid #ced4da;">
                                        <i class="fa-solid fa-list"></i>
                                    </button>
                                    <small class="d-block text-muted mt-2">Info</small>
                                </div>
                            </div>
                        </form>

                        <!-- Paragraph with name and selected status -->
                        <p class="fw-bold fs-5 mb-0 px-2">
                            <?php echo $row['Ref_name']; ?>
                            <?php if ($selected) echo '<span class="text-primary ms-1 fw-normal d-inline d-sm-none"><i class="fas fa-star"></i></span><span class="text-primary ms-1 fw-normal d-none d-sm-inline">Active <i class="fas fa-star"></i></span>'; ?>
                        </p>

                    </div>

                    <!-- test type -->
                    <p class="fs-6 mb-0 d-none d-sm-block"><?php echo $row['Type']; ?></p>



                    <!-- Div for delete and load button -->
                    <div class="d-flex justify-content-center">

                        <!-- Form for Delete Button -->
                        <div class="me-2"> <!-- Adds margin to the right -->
                            <form method="post" action="php/delete_record.php">
                                <input type="hidden" name="testId" value="<?php echo $row['Guest_ID']; ?>">
                                <input type="hidden" name="testCount" value="<?php echo $row['Test_count']; ?>">

                                <div class="text-center">
                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle p-2" style="width: 40px; height: 40px;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <small class="d-block text-muted mt-2">Delete</small>
                                </div>
                            </form>
                        </div>

                        <!-- Form for Load Button -->
                        <div>
                            <form method="post" action="php/change_referral.php">
                                <input type="hidden" name="testId" value="<?php echo $row['Guest_ID']; ?>">
                                <input type="hidden" name="testCount" value="<?php echo $row['Test_count']; ?>">

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-sm rounded-circle p-2" style="width: 40px; height: 40px;">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                    <small class="d-block text-muted mt-2">Activate</small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>


        <?php
        //this section is active only is a Superuser is logged --ignore
        try {
            $sql = "SELECT Type FROM account WHERE Guest_ID='{$_SESSION['loggedUser']['id']}' AND Username='{$_SESSION['loggedUser']['username']}'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            if (isset($row['Type']) && $row['Type'] == 1) { ?>
                <div class="container-fluid p-4 border rounded-4 bg-light mt-5">
                    <h4 class="mb-3">Create new superuser</h4>
                    <form action="php/newUsername.php" method="POST" class="settingForm ref">
                        <div class="row row-cols-1 row-cols-lg-2 g-3 justify-content-center align-items-center">
                            <div class="col">
                                <div class="input-group">
                                    <span class="input-group-text title" onclick="copy('ref')" title="Username">Username</span>
                                    <input type="text" class="form-control" placeholder="Username" name="username">
                                </div>
                            </div>
                            <div class="col d-grid">
                                <button type="submit" class="btn btn-primary btn-red">Create new Superuser</button>
                            </div>
                        </div>
                    </form>
                </div>
        <?php }
        } catch (Exception $e) {
            header("Location: index.php?err=db");
        }
        ?>

        <!-- change password section -->
        <div class="container-fluid p-4 border rounded-4 bg-light mt-5">
            <h4 class="mb-3">Change password</h4>
            <form action="php/change_password.php" method="post" class="settingForm">
                <div class="row row-cols-1 row-cols-lg-3 g-3 justify-content-center align-items-center">

                    <!-- old psw form -->
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text">Old password</span>
                            <input type="password" class="form-control" placeholder="Old password" name="oldPsw">
                        </div>
                    </div>

                    <!-- new psw form -->
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text">New password</span>
                            <input type="password" class="form-control" placeholder="New password" name="newPsw">
                        </div>
                    </div>

                    <!-- change psw button -->
                    <div class="col d-grid">
                        <button type="submit" class="btn btn-primary btn-red">Change Password</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- change user settings section -->
        <div class="container-fluid p-4 border rounded-4 bg-light mt-5">
            <h4 class="mb-3">Change user settings</h4>
            <form method="post" action="php/change_user_data.php" class="settingForm">
                <div class="row row-cols-1 row-cols-lg-2 g-3 justify-content-center align-items-center">
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text">Username</span>
                            <input type="text" class="form-control" name="usr" value="<?php echo $_SESSION['loggedUser']['username']; ?>" readonly>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text">Email</span>
                            <input type="text" class="form-control" name="email" value="<?php echo $email; ?>">
                        </div>
                    </div>

                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text">Name</span>
                            <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text">Surname</span>
                            <input type="text" class="form-control" name="surname" value="<?php echo $sur; ?>">
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text">Birth date</span>
                            <input type="date" class="form-control" name="date" value="<?php echo $date; ?>">
                        </div>
                    </div>
                    <div class="col">
                        <select class="form-select" id="gender" name="gender">
                            <option value="" selected disabled>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col">
                        <div class="input-group notes">
                            <span class="input-group-text">Notes</span>
                            <input type="text" class="form-control" placeholder="Notes" name="notes" value="<?php echo $notes; ?>">
                        </div>
                    </div>
                    <div class="col d-grid">
                        <button type="submit" class="btn btn-primary btn-red">Save</button>
                    </div>
                </div>
            </form>
        </div>




        <!-- Info modal Banner, this only shows when the info button is pressed-->    
        <?php if (isset($param)) { ?>

            <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="infoModalLabel">Test Information</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                <?php echo isset($param['Type']) && $param['Type'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Type: <strong>" . htmlspecialchars($param['Type'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['creationDate']) && $param['creationDate'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Creation Date: <strong>" . htmlspecialchars($param['creationDate'] ?? '') . "</strong></li>" : '';  ?> 
                                <?php echo isset($param['amplitude']) && $param['amplitude'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Amplitude: <strong>" . htmlspecialchars($param['amplitude'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['frequency']) && $param['frequency'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Frequency: <strong>" . htmlspecialchars($param['frequency'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['duration']) && $param['duration'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Duration: <strong>" . htmlspecialchars($param['duration'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['onRamp']) && $param['onRamp'] !== null ? "<li style='display:block; margin-bottom: 2px;'>On Ramp: <strong>" . htmlspecialchars($param['onRamp'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['offRamp']) && $param['offRamp'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Off Ramp: <strong>" . htmlspecialchars($param['offRamp'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['modAmplitude']) && $param['modAmplitude'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Modulation Amplitude: <strong>" . htmlspecialchars($param['modAmplitude'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['modFrequency']) && $param['modFrequency'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Modulation Frequency: <strong>" . htmlspecialchars($param['modFrequency'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['modPhase']) && $param['modPhase'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Modulation Phase: <strong>" . htmlspecialchars($param['modPhase'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['blocks']) && $param['blocks'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Blocks: <strong>" . htmlspecialchars($param['blocks'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['nAFC']) && $param['nAFC'] !== null ? "<li style='display:block; margin-bottom: 2px;'>nAFC: <strong>" . htmlspecialchars($param['nAFC'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['ITI']) && $param['ITI'] !== null ? "<li style='display:block; margin-bottom: 2px;'>ITI: <strong>" . htmlspecialchars($param['ITI'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['ISI']) && $param['ISI'] !== null ? "<li style='display:block; margin-bottom: 2px;'>ISI: <strong>" . htmlspecialchars($param['ISI'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['delta']) && $param['delta'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Delta: <strong>" . htmlspecialchars($param['delta'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['checkFb']) && $param['checkFb'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Check Feedback: <strong>" . htmlspecialchars($param['checkFb'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['factor']) && $param['factor'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Factor: <strong>" . htmlspecialchars($param['factor'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['secFactor']) && $param['secFactor'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Sec Factor: <strong>" . htmlspecialchars($param['secFactor'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['reversals']) && $param['reversals'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Reversals: <strong>" . htmlspecialchars($param['reversals'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['secReversals']) && $param['secReversals'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Sec Reversals: <strong>" . htmlspecialchars($param['secReversals'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['threshold']) && $param['threshold'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Threshold: <strong>" . htmlspecialchars($param['threshold'] ?? '') . "</strong></li>" : ''; ?>
                                <?php echo isset($param['algorithm']) && $param['algorithm'] !== null ? "<li style='display:block; margin-bottom: 2px;'>Algorithm: <strong>" . htmlspecialchars($param['algorithm'] ?? '') . "</strong></li>" : ''; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Automatically show the modal when the page loads -->
            <script>
                var infoModal = new bootstrap.Modal(document.getElementById('infoModal'));
                infoModal.show();
            </script>
        <?php } ?>



</body>

</html>
