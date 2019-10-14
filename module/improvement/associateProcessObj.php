<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP'].'include/function.php');

	$ID_PROCESS = trim(addslashes($_POST['id_process']));
	$ID_SOURCE = trim(addslashes($_POST['id_source']));
	$SOURCE = trim(addslashes($_POST['source']));
	$ASS_DISS = trim(addslashes($_POST['ass_dis']));
	
	if($SOURCE == "nonc"){
		$PERMITIONS_NAME_1 = "create_nonconformity@";
		if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) !== false){
			if($ASS_DISS == "a"){
				$SQL = "DELETE FROM tanonconformity_process WHERE id_nonconformity=$ID_SOURCE AND id_process=$ID_PROCESS";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
				
				$SQL = "INSERT INTO tanonconformity_process (id_nonconformity, id_process) VALUES ($ID_SOURCE, $ID_PROCESS)";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
			}elseif($ASS_DISS == "d"){
				$SQL = "DELETE FROM tanonconformity_process WHERE id_nonconformity=$ID_SOURCE AND id_process=$ID_PROCESS";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
			}
		}
	}elseif($SOURCE == "asse"){
		$PERMITIONS_NAME_1 = "create_asset@";
		if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) !== false){
			if($ASS_DISS == "a"){
				$SQL = "DELETE FROM taasset_process WHERE id_asset=$ID_SOURCE AND id_process=$ID_PROCESS";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
				
				$SQL = "INSERT INTO taasset_process (id_asset, id_process) VALUES ($ID_SOURCE, $ID_PROCESS)";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
			}elseif($ASS_DISS == "d"){
				$SQL = "DELETE FROM taasset_process WHERE id_asset=$ID_SOURCE AND id_process=$ID_PROCESS";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
			}
		}
	}
}
?>