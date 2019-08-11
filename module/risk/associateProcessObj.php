<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP'].'include/function.php');

	$ID_PROC = trim(addslashes($_POST['id_proc']));
	$ID_SOURCE = trim(addslashes($_POST['id_source']));
	$SOURCE = trim(addslashes($_POST['source']));
	$ASS_DISS = trim(addslashes($_POST['ass_dis']));
	
	if($SOURCE == "asse"){
		if($ASS_DISS == "a"){
			$SQL = "DELETE FROM taasset_process WHERE id_asset=$ID_SOURCE AND id_process=$ID_PROC";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
			
			$SQL = "INSERT INTO taasset_process (id_asset, id_process) VALUES ($ID_SOURCE, $ID_PROC)";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		}elseif($ASS_DISS == "d"){
			$SQL = "DELETE FROM taasset_process WHERE id_asset=$ID_SOURCE AND id_process=$ID_PROC";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		}
	}
}
?>