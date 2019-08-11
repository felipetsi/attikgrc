<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP'].'include/function.php');

	$ID_RISK = trim(addslashes($_POST['idRisk']));
	$ID_CONTROL = trim(addslashes($_POST['idControl']));
	$ASS_DISS = trim(addslashes($_POST['ass_dis']));

	if($ASS_DISS == "d"){
		if((!empty($ID_RISK)) && (!empty($ID_CONTROL))){
			$SQL = "DELETE FROM tarisk_control_impact WHERE id_control=$ID_CONTROL AND id_risk=$ID_RISK";
			$RSINSIDE = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

			$SQL = "DELETE FROM tarisk_control WHERE id_control=$ID_CONTROL AND id_risk=$ID_RISK";
			$RSINSIDE = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

			updateResidualRisk($ID_RISK);
			
			$SQL = "SELECT id_risk FROM tarisk_control WHERE id_risk = $ID_RISK";
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			if(pg_affected_rows($RS) > 0){
				$status = "m";
			} else {
				$status = "a";
			}

			$SQL = "UPDATE trisk SET status = '$status' ";
			$SQL .= "WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
			$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_RISK";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		}
	} else {
		if((!empty($ID_RISK)) && (!empty($ID_CONTROL))){
			$SQL = "INSERT INTO tarisk_control (id_control, id_risk) VALUES ($ID_CONTROL, $ID_RISK)";
			$RSINSIDE = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

			$SQL = "SELECT id FROM timpact WHERE id_impact_type IN (SELECT id FROM timpact_type WHERE id_instance = ";
			$SQL .= $_SESSION['INSTANCE_ID']." AND id IN (SELECT id_impact_type FROM trisk WHERE id = $ID_RISK))";
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			do {
				$SQL = "INSERT INTO tarisk_control_impact (id_control, id_risk, id_impact) VALUES ($ID_CONTROL, $ID_RISK, ".$ARRAY['id'].")";
				$RSINSIDE = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
			} while($ARRAY = pg_fetch_array($RS));
			$status = "m";
			$SQL = "UPDATE trisk SET status = '$status' ";
			$SQL .= "WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
			$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_RISK";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

		}
	}
}
?>