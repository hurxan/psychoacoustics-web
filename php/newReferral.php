<?php
	try{
		session_start(); 
		include "config.php";
		
		$conn = new mysqli($host, $user, $password, $dbname);
		if ($conn->connect_errno)
			throw new Exception('DB connection failed');
		mysqli_set_charset($conn, "utf8");
		
		$sql = "SELECT Referral FROM account WHERE Username='".$_SESSION['usr']."'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$ref = $row['referral'];
		
		$newRef = base64_encode($_SESSION['usr'].rand(-9999, 9999));
		while($newRef == $ref)
			$newRef = base64_encode($_SESSION['usr'].rand(-9999, 9999));
		
		$sql = "UPDATE account SET Referral='$newRef' WHERE Username='".$_SESSION['usr']."'";
		$conn->query($sql);
		
		header("Location: ../userSettings.php");
	}catch(Exception $e){
		header("Location: ../index.php?err=db");
	}
?>
