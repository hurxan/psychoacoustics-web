<?php
	try{
		include "config.php";
		session_start();

		if(isset($_GET['result']) && isset($_GET['timestamp']) && isset($_GET['type'])
			&& isset($_GET['amp']) && isset($_GET['freq']) && isset($_GET['dur']) && isset($_GET['onRamp']) && isset($_GET['offRamp']) && isset($_GET['blocks']) && isset($_GET['delta'])
			&& isset($_GET['nAFC']) && isset($_GET['ITI']) && isset($_GET['ISI']) && isset($_GET['fact']) && isset($_GET['secFact']) && isset($_GET['rev'])
			&& isset($_GET['secRev'])&& isset($_GET['threshold']) && isset($_GET['alg']) && isset($_GET['score'])
			&& isset($_GET['saveSettings']) && isset($_GET['currentBlock'])){

			//trova il tipo
			$type = "";
			if($_GET['type'] == "freq")
				$type = "PURE_TONE_FREQUENCY";
			else if($_GET['type'] == "amp")
				$type = "PURE_TONE_INTENSITY";
			else if($_GET['type'] == "dur")
				$type = "PURE_TONE_DURATION";
            else if($_GET['type'] == "gap")
                $type = "WHITE_NOISE_GAP";
            else if($_GET['type'] == "ndur")
                $type = "WHITE_NOISE_DURATION";
			else if($_GET['type'] == "nmod")
				$type = "WHITE_NOISE_MODULATION";

			if(isset($_SESSION["score"]))
				$_SESSION["score"] .= ";".$_GET['score'];
			else
				$_SESSION["score"] = $_GET['score'];

			if(isset($_SESSION["results"]))
				$_SESSION["results"] .= $_GET['result'];
			else
				$_SESSION["results"] = $_GET['result'];

			$_SESSION["time"] = $_GET['timestamp'];
			$_SESSION["type"] = $type;
			$_SESSION["amp"] = $_GET['amp'];
			$_SESSION["freq"] = $_GET['freq'];
            $_SESSION["dur"] = $_GET['dur'];
            $_SESSION["onRamp"] = $_GET['onRamp'];
            $_SESSION["offRamp"] = $_GET['offRamp'];
			if ($_GET['type'] == "nmod") {
				$_SESSION["modAmp"] = $_GET["modAmp"];
				$_SESSION["modFreq"] = $_GET["modFreq"];
				$_SESSION["modPhase"] = $_GET["modPhase"];
			}
			$_SESSION["blocks"] = $_GET['blocks'];
			//$_SESSION["delta"] = $_GET['delta'];
			$_SESSION["nAFC"] = $_GET['nAFC'];
			$_SESSION["ITI"] = $_GET['ITI'];
			$_SESSION["ISI"] = $_GET['ISI'];
			$_SESSION["fact"] = $_GET['fact'];
			$_SESSION["secFact"] = $_GET['secFact'];
			$_SESSION["rev"] = $_GET['rev'];
			$_SESSION["secRev"] = $_GET['secRev'];
			$_SESSION["thr"] = $_GET['threshold'];
			$_SESSION["alg"] = $_GET['alg'];
			$_SESSION["currentBlock"] = $_GET['currentBlock'];
            $_SESSION["sampleRate"] = $_GET['sampleRate'];

			if($_GET['currentBlock']<$_GET['blocks']){
				header("Location: ../results.php?continue=1");
			}else{
				//apro la connessione con il db
				$conn = new mysqli($host, $user, $password, $dbname);

				//controllo se è andata a buon fine
				if ($conn->connect_errno)
					throw new Exception('DB connection failed');

				//uso codifica utf8 per comunicare col db
				mysqli_set_charset($conn, "utf8");

				//save the test, if it must be saved
				if($_SESSION["checkSave"]){
					if(!isset($_SESSION['idGuestTest'])){
						header("Location: ../index.php?err=2");
					}else{
						//trovo l'id a cui associare il test
						$id = $_SESSION['idGuestTest'];

						//trova il numero di test effettuati fin'ora
						$sql = "SELECT Max(Test_count) as count FROM test WHERE Guest_ID='$id'";
						$result = $conn->query($sql);
						$row = $result->fetch_assoc();

						//il test corrente è il numero di test già effettuati + 1
						$count = $row['count'];

						//inserisci i dati del nuovo test
						$sql = "UPDATE test SET Result = '{$_SESSION['results']}', Timestamp='{$_GET['timestamp']}', SampleRate='{$_GET['sampleRate']}' WHERE Guest_ID = '$id' and Test_count = '$count'";
						/* ('$id', '$count', '{$_GET['timestamp']}', '$type', ";
						$sql .= "'{$_GET['amp']}', '{$_GET['freq']}', '{$_GET['dur']}', '{$_GET['ramp']}', '{$_GET['blocks']}', '{$_GET['delta']}', ";
						$sql .= "'{$_GET['nAFC']}', '{$_GET['ITI']}', '{$_GET['ISI']}', '{$_GET['fact']}', '{$_GET['rev']}', ";
						$sql .= "'{$_GET['secFact']}', '{$_GET['secRev']}', '{$_GET['threshold']}', '{$_GET['alg']}', '{$_GET['result']}', '{$_GET['sampleRate']}')";
						*/
						echo $sql;
						$conn->query($sql);

						// if($_GET['saveSettings']){
						// 	$sql = "UPDATE account SET fk_guestTest = '$id', fk_testCount = '$count' WHERE username = '{$_SESSION['usr']}' ";
						// 	$conn->query($sql);
						// }
					}
				}

				if(!$_SESSION["checkSave"] && $_GET['saveSettings']){
					header("Location: ../results.php?continue=0&err=1");
				}else{
					header("Location: ../results.php?continue=0");
				}
			}
		}else
			header("Location: ../index.php?err=2");
	}catch(Exception $e){
		header("Location: ../index.php?err=db");
	}
?>
