<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP'].'include/function.php');

	$ID_RISK = trim(addslashes($_POST['id_risk']));
	$ID_SOURCE = trim(addslashes($_POST['id_source']));
	$SOURCE = trim(addslashes($_POST['source']));
	$ASS_DISS = trim(addslashes($_POST['ass_dis']));
	
	if($SOURCE == "task"){	
		if($ASS_DISS == "a"){
			$SQL = "DELETE FROM tarisk_task WHERE id_task=$ID_SOURCE AND id_risk=$ID_RISK";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
			
			$SQL = "INSERT INTO tarisk_task (id_task, id_risk) VALUES ($ID_SOURCE, $ID_RISK)";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		}elseif($ASS_DISS == "d"){
			$SQL = "DELETE FROM tarisk_task WHERE id_task=$ID_SOURCE AND id_risk=$ID_RISK";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		}
	}elseif($SOURCE == "inci"){
		if($ASS_DISS == "a"){
			$SQL = "DELETE FROM taincident_risk WHERE id_incident=$ID_SOURCE AND id_risk=$ID_RISK";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
			
			$SQL = "INSERT INTO taincident_risk (id_incident, id_risk) VALUES ($ID_SOURCE, $ID_RISK)";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		}elseif($ASS_DISS == "d"){
			$SQL = "DELETE FROM taincident_risk WHERE id_incident=$ID_SOURCE AND id_risk=$ID_RISK";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		}
	}
}
?>