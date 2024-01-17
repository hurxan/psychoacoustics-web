<?php
	session_start();

	unset($_SESSION['usr']);
	unset($_SESSION['idGuest']);
	
	header("Location: ../index.php");
?>
