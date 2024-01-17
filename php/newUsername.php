<?php
	try{
		include "config.php";
		session_start();
		
		//sql injections handling
		$characters = ['"', "\\", chr(0)];
		$specialCharacters = false;
		$_POST['username'] = str_replace("'","''",$_POST['username']);
		foreach($characters as $char)
			$specialCharacters |= is_numeric(strpos($_POST['username'], $char));
		
		if($specialCharacters)
			header("Location: ../userSettings.php?&err=0");
		else{
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

				$sql = "UPDATE account SET Type='1' WHERE Username='".$_POST['username']."'";
				$conn->query($sql);
			}
			
			header("Location: ../userSettings.php");
		}
	}catch(Exception $e){
		header("Location: ../index.php?err=db");
	}
?>
