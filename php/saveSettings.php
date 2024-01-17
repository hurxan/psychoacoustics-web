<?php
	try{
		session_start(); 
		include "config.php";
		
		//sql injections handling
		$elements = ['usr', 'email', "name", "surname", "notes"];
		$characters = ['"', "\\", chr(0)];
		$specialCharacters = false;
		foreach($elements as $elem){
			$_POST[$elem] = str_replace("'","''",$_POST[$elem]);
			foreach($characters as $char)
				$specialCharacters |= is_numeric(strpos($_POST[$elem], $char));
		}
		
		if($specialCharacters)
			header("Location: ../userSettings.php?&err=0");
		else{
			$conn = new mysqli($host, $user, $password, $dbname);
			if ($conn->connect_errno)
				throw new Exception('DB connection failed');
			mysqli_set_charset($conn, "utf8");
			
			$sql = "SELECT username, date, email, name, surname, notes, gender 
				FROM account INNER JOIN guest ON account.Guest_ID = guest.ID
				WHERE username='{$_SESSION['usr']}';";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			
			$somethingChanged = false;
			$oldUsr = $_SESSION['usr'];
			$sql = "UPDATE account SET ";
			
			if($_POST['usr']!=$row['username']){
				$userControl = "SELECT username FROM account WHERE username='{$_POST['usr']}';";
				$result = $conn->query($sql);
				if($result->num_rows!=0){
					header("Location: ../userSettings.php?&err=1");
				}else{
					$sql.="username = '{$_POST['usr']}', ";
					$_SESSION['usr'] = $_POST['usr'];
					$somethingChanged = true;
				}
			}
			
			if($_POST['email']!=$row['email']){
				$sql.="email = '{$_POST['email']}', ";
				$somethingChanged = true;
			}
			
			if($_POST['date']!=$row['date']){
				$sql.="date = '{$_POST['date']}', ";
				$somethingChanged = true;
			}
			
			$sql = substr($sql, 0, -2);
			$sql .= "WHERE username='$oldUsr';";
			
			if($somethingChanged)
				$conn->query($sql);
			
			$somethingChanged = false;
			$sql = "UPDATE guest SET ";
			
			if($_POST['name']!=$row['name']){
				$sql.="name = '{$_POST['name']}', ";
				$somethingChanged = true;
			}
			
			if($_POST['surname']!=$row['surname']){
				$sql.="surname = '{$_POST['surname']}', ";
				$somethingChanged = true;
			}
			
			if($_POST['notes']!=$row['notes']){
				$sql.="notes = '{$_POST['notes']}', ";
				$somethingChanged = true;
			}
			
			if($_POST['gender']!=$row['gender']){
				$sql.="gender = '{$_POST['gender']}', ";
				$somethingChanged = true;
			}
			
			$sql = substr($sql, 0, -2);
			$sql .= "WHERE ID='{$_SESSION['idGuest']}';";
			
			if($somethingChanged)
				$conn->query($sql);
			
			header("Location: ../userSettings.php");
		}
	}catch(Exception $e){
		header("Location: ../index.php?err=db");
	}
?>
