<?php
	session_start();
	$_SESSION['updatingSavedSettings']=true;
	header("Location: ../soundSettings.php?test=".$_GET['test']);
?>
