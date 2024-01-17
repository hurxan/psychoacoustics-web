<?php
	try{
		include "config.php";
		session_start();

		//apro la connessione con il db
		$conn = new mysqli($host, $user, $password, $dbname);

		//controllo se è andata a buon fine
		if ($conn->connect_errno)
			throw new Exception('DB connection failed');

		//uso codifica utf8 per comunicare col db
		mysqli_set_charset($conn, "utf8");

		$id = $_SESSION['idGuest'];

		//trova il numero di test effettuati fin'ora
		$sql = "SELECT Max(Test_count) as count FROM test WHERE Guest_ID='$id'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();

		//il test corrente è il numero di test già effettuati + 1
		$count = $row['count']+1;

		$checkFb = 0;
		if(isset($_POST["checkFb"]))
			$checkFb = 1;
		
		//trova il tipo
		$type = "";
		if($_GET['test'] == "freq")
			$type = "PURE_TONE_FREQUENCY";
		else if($_GET['test'] == "amp")
			$type = "PURE_TONE_INTENSITY";
		else if($_GET['test'] == "dur")
			$type = "PURE_TONE_DURATION";
		else if($_GET['test'] == "gap")
			$type = "WHITE_NOISE_GAP";
		else if($_GET['test'] == "ndur")
			$type = "WHITE_NOISE_DURATION";
		else if($_GET['test'] == "nmod")
			$type = "WHITE_NOISE_MODULATION";

		//inserisci i dati del nuovo test
		if ($_GET['test'] == "gap" || $_GET['test'] == "ndur") {
			$sql = "INSERT INTO test VALUES ('$id', '$count', current_timestamp(), '$type', ";
			$sql .= "'{$_POST['amplitude']}', NULL, '{$_POST['duration']}', '{$_POST['onRamp']}', '{$_POST['offRamp']}', '{$_POST['blocks']}', '{$_POST['delta']}', ";
			$sql .= "'{$_POST['nAFC']}', '{$_POST['ITI']}', '{$_POST['ISI']}', '{$_POST['factor']}', '{$_POST['reversals']}', ";
			$sql .= "'{$_POST['secFactor']}', '{$_POST['secReversals']}', '{$_POST['threshold']}', '{$_POST['algorithm']}', '', '0','$checkFb', NULL, NULL, NULL, NULL)";
		} else if ($_GET['test'] == "nmod") {
			$sql = "INSERT INTO test VALUES ('$id', '$count', current_timestamp(), '$type', ";
			$sql .= "'{$_POST['amplitude']}', NULL, '{$_POST['duration']}', '{$_POST['onRamp']}', '{$_POST['offRamp']}', '{$_POST['blocks']}', '{$_POST['delta']}', ";
			$sql .= "'{$_POST['nAFC']}', '{$_POST['ITI']}', '{$_POST['ISI']}', '{$_POST['factor']}', '{$_POST['reversals']}', ";
			$sql .= "'{$_POST['secFactor']}', '{$_POST['secReversals']}', '{$_POST['threshold']}', '{$_POST['algorithm']}', '', '0','$checkFb', '" . floatval($_POST["modAmplitude"]) . "', '{$_POST["modFrequency"]}', '{$_POST["modPhase"]}', NULL)";
		} else {
			$sql = "INSERT INTO test VALUES ('$id', '$count', current_timestamp(), '$type', ";
			$sql .= "'{$_POST['amplitude']}', '{$_POST['frequency']}', '{$_POST['duration']}', '{$_POST['onRamp']}', '{$_POST['offRamp']}', '{$_POST['blocks']}', '{$_POST['delta']}', ";
			$sql .= "'{$_POST['nAFC']}', '{$_POST['ITI']}', '{$_POST['ISI']}', '{$_POST['factor']}', '{$_POST['reversals']}', ";
			$sql .= "'{$_POST['secFactor']}', '{$_POST['secReversals']}', '{$_POST['threshold']}', '{$_POST['algorithm']}', '', '0','$checkFb', NULL, NULL, NULL, NULL)";
		}
		$conn->query($sql);
		
		$sql = "UPDATE account SET fk_GuestTest = '$id', fk_TestCount = '$count' WHERE Username = '{$_SESSION['usr']}' ";
		$conn->query($sql);
		
		unset($_SESSION['updatingSavedSettings']);
		
		header("Location: ../userSettings.php?err=4");
	}catch(Exception $e){
		header("Location: ../index.php?err=db");
	}
?>
