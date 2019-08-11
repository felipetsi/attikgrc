<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP'].'include/function.php');

	$ID_CONTROL = trim(addslashes($_POST['id_control']));
	$ID_SOURCE = trim(addslashes($_POST['id_source']));
	$SOURCE = trim(addslashes($_POST['source']));
	$ASS_DISS = trim(addslashes($_POST['ass_dis']));
	
	if($SOURCE == "task"){	
		if($ASS_DISS == "a"){
			$SQL = "DELETE FROM tacontrol_task WHERE id_task=$ID_SOURCE AND id_control=$ID_CONTROL";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
			
			$SQL = "INSERT INTO tacontrol_task (id_task, id_control) VALUES ($ID_SOURCE, $ID_CONTROL)";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		}elseif($ASS_DISS == "d"){
			$SQL = "DELETE FROM tacontrol_task WHERE id_task=$ID_SOURCE AND id_control=$ID_CONTROL";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		}
	}elseif($SOURCE == "nonc"){	
		if($ASS_DISS == "a"){
			$SQL = "DELETE FROM tanonconformity_control WHERE id_nonconformity=$ID_SOURCE AND id_control=$ID_CONTROL";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
			
			$SQL = "INSERT INTO tanonconformity_control (id_nonconformity, id_control) VALUES ($ID_SOURCE, $ID_CONTROL)";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		}elseif($ASS_DISS == "d"){
			$SQL = "DELETE FROM tanonconformity_control WHERE id_nonconformity=$ID_SOURCE AND id_control=$ID_CONTROL";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		}
	}
}
?>