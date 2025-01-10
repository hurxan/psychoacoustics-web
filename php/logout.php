<?php
	session_start();
	Include_once "helpers/utils.php";
	
	logEvent("User #{$_SESSION['loggedUser']['id']} logged out");

    unset($_SESSION['loggedUser']);

	header("Location: ../index.php");

