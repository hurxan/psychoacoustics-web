<?php
	function writeResults($txt, $firstValues, $results){
		//results sarà nella forma ["bl1;tr1;del1;var1;varpos1;but1;cor1;rev1", "bl2;tr2;...", ...]
		for($i = 0;$i<count($results)-1;$i++){
			fwrite($txt, $firstValues.";");
			fwrite($txt, $results[$i]);
			fwrite($txt, "\n");//vado all'altra linea
		}
	}

	try{
		//apro la connessione con la sessione e col db
		include "config.php";
		session_start();
		
		$conn = new mysqli($host, $user, $password, $dbname);
		if ($conn->connect_errno)
			throw new Exception('DB connection failed');
		mysqli_set_charset($conn, "utf8");
		
		//prendo i dati del guest
		$usr = $_SESSION['usr'];
		$id = $_SESSION['idGuest'];
		
		//controllo di sicurezza
		$sql = "SELECT Type FROM account WHERE Guest_ID='$id' AND Username='$usr'";
		$result=$conn->query($sql);
		$row=$result->fetch_assoc();
		if($row['Type'] == 1){
			
			//creo e apro il file csv
			$path = "allResults.csv";
			$txt = fopen($path, "w") or die("Unable to open file!");
			fwrite($txt, chr(0xEF).chr(0xBB).chr(0xBF)); //utf8 encoding
			
			//scrivo il nome delle colonne
			$line = "Name;Surname;Age;Gender;Test Count;Test Type;Timestamp;Sample Rate;Device Info;Amplitude;Frequency;Duration;Onset Ramp;Offset Ramp;Modulator Amplitude;ModulatorFrequency;Modulator Phase;n. of blocks;";
			$line .= "nAFC;ISI;ITI;First factor;First reversals;Second factor;Second reversals;reversal threshold;algorithm;";
			$line .= "block;trials;delta;variable;Variable Position;Pressed button;correct?;reversals\n";
			
			fwrite($txt, $line);
			
			//metto i dati dei guest collegati
			$sql = "SELECT guest.Name as name, guest.Surname as surname, guest.Age as age, guest.Gender as gender, 
					test.Test_count as count, test.Type as type, test.Timestamp as time, test.Amplitude as amp, 
					test.Frequency as freq, test.Duration as dur, test.OnRamp as onRamp, test.OffRamp as offRamp,
					test.ModAmplitude as modAmp, test.ModFrequency as modFreq, test.ModPhase as modPhase, test.SampleRate as sampleRate, test.blocks as blocks, test.nAFC as nafc, 
					test.ISI as isi, test.ITI as iti, test.Factor as fact, test.Reversal as rev, test.SecFactor as secfact, 
					test.SecReversal as secrev, test.Threshold as thr, test.Algorithm as alg, test.Result as results, test.DeviceInfo as deviceInfo,
					account.Date as date
					
					FROM guest
					INNER JOIN test ON guest.ID=test.Guest_ID
					LEFT JOIN account ON guest.ID=account.Guest_ID;";
			$result = $conn->query($sql);

			while($row = $result->fetch_assoc()){
				if($row['date']!="")
					$age = strval(date_diff(date_create($row['date']), date_create('now'))->y);
				else if(strval($row['age'])!='0')
					$age = strval($row['age']);
				else
					$age = "";
				
				//valore della prima parte (quella fissa che va ripetuta)
				$firstValues = $row["name"].";".$row["surname"].";".$age.";".$row["gender"].";".$row["count"].";".$row["type"].";".$row["time"].";".$row["sampleRate"].";".$row["deviceInfo"].";";
				$firstValues .= $row["amp"].";".$row["freq"].";".$row["dur"].";".$row["onRamp"].";".$row["offRamp"].";".$row["modAmp"].";".$row["modFreq"].";".$row["modPhase"].";".$row["blocks"].";".$row["nafc"].";".$row["isi"].";".$row["iti"].";";
				$firstValues .= $row["fact"].";".$row["rev"].";".$row["secfact"].";".$row["secrev"].";".$row["thr"].";".$row["alg"];
					
				//parte variabile e scrittura su file
				$results = explode(",", $row["results"]);
				writeResults($txt, $firstValues, $results);
			}
			
			fclose($txt);
			//*scrittura su file (per disattivare togliere uno slash da questo commento)
			header('Content-Description: File Transfer');
			header('Content-Disposition: attachment; filename='.basename($path));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($path));
			header("Content-Type: text/plain");
			readfile($path);
			//*/
			unlink($path);//elimino il file dal server
		}
		else{//tentativo di accesso senza permessi: scrivo nel file log
			date_default_timezone_set('Europe/Rome');
			$date = date('Y/m/d h:i:s a', time());
			
			$txt = fopen("files/log.txt", "a") or die("Unable to open file!");
			
			fwrite($txt, "Attempt to access downloadAll.php without permission - timestamp: ".$date);
			if(isset($_SESSION['usr']))
				fwrite($txt, " - username: ".$_SESSION['usr']);
			fwrite($txt, "\n");
			
			fclose($txt);
			header("Location: ../index.php?err=1");
		}
	}catch(Exception $e){
		header("Location: ../index.php?err=db");
	}
?>
