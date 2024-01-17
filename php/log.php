<?php
	try{
		include "config.php";
		//apro la sessione per comunicare con le altre pagine del sito
		session_start();
		
		//sql injections handling
		$elements = ['usr', 'psw'];
		$characters = ['"', "\\", chr(0)];
		$specialCharacters = false;
		foreach($elements as $elem){
			$_POST[$elem] = str_replace("'","''",$_POST[$elem]);
			foreach($characters as $char)
				$specialCharacters |= is_numeric(strpos($_POST[$elem], $char));
		}
		
		if($specialCharacters)
			header("Location: ../login.php?&err=0");
		else{
			//apro la connessione con il db
			$conn = new mysqli($host, $user, $password, $dbname);
			
			//controllo se è andata a buon fine
			if ($conn->connect_errno)
				throw new Exception('DB connection failed');
			
			//uso codifica utf8 per comunicare col db
			mysqli_set_charset($conn, "utf8");
			
			//recupero username e password dal form di registrazione
			$usr = $_POST['usr'];
			$psw = $_POST['psw'];
			
			//controllo se esiste
			$sql = "SELECT Guest_ID FROM account WHERE Username='$usr' AND Password=SHA2('$psw', 256)";
			
			$result=$conn->query($sql);
			
			if($result->num_rows>0){
				$row=$result->fetch_assoc();
				
				//faccio sapere alle altre pagine quale utente è loggato
				$_SESSION['usr'] = $usr;
				$_SESSION['idGuest'] = $row['Guest_ID'];
				
				$conn->close();
				header('Location: ../index.php');
			}else{
				$conn->close();
				header('Location: ../login.php?err=1');
			}
		}    
	}catch(Exception $e){
		header("Location: ../index.php?err=db");
	}
?>
