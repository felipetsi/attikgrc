<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP'].'include/function.php');

	$ID_RISK = trim(addslashes($_POST['idRisk']));
	$TREATMENT = trim(addslashes($_POST['treatment']));
	
	if(!empty($ID_RISK)){
		switch ($TREATMENT){
			case 'c':
				$SQL = "UPDATE trisk SET status = 'c' ";
				$SQL .= "WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
				$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_RISK";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
				break;
			case 'v':
				$SQL = "UPDATE trisk SET status = 'v' ";
				$SQL .= "WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
				$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_RISK";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
				break;
			case 't':
				$SQL = "UPDATE trisk SET status = 't' ";
				$SQL .= "WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
				$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_RISK";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
				break;
			case 'e':
				$SQL = "UPDATE trisk SET status = 'e' ";
				$SQL .= "WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
				$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_RISK";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
				break;
		}
		if(pg_affected_rows($RS) > 0){
			$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
		} else {
			$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
		}
	}
}
?>