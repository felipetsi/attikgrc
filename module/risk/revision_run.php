<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP'].'include/function.php');

	$ID_CONTROL = trim(addslashes($_POST['id_control']));
	$PREV_DATE = substr(trim(addslashes($_POST['prevision_date'])),0,10);
	$EXEC_DATE = substr(trim(addslashes($_POST['execution_date'])),0,10);
	$RESULT = preg_replace("/[^0-9.]/", "", (trim(addslashes($_POST['result']))));
	$JUSTIFY = substr(trim(addslashes($_POST['justification'])),0,500);
	$RESPONSIBLE = $_SESSION['user_id'];
	
	if(empty($EXEC_DATE)){
		if(empty($RESULT)){
			$EXEC_DATE = 'NULL';
		} else {
			$EXEC_DATE = date('Y-m-d');
			$EXEC_DATE = "'".$EXEC_DATE."'";
		}
	} else {
		$EXEC_DATE = "'".$EXEC_DATE."'";
	}
	
	if(empty($RESULT)){
		$RESULT = 'NULL';
		$EXEC_DATE = 'NULL';
	} else {
		$RESULT = "'".$RESULT."'";
	}
	
	$SQL = "UPDATE trevision_control SET execution_date=$EXEC_DATE, result=$RESULT, justification='$JUSTIFY' ";
	$SQL .= "WHERE id_control = $ID_CONTROL AND  prevision_date = '$PREV_DATE'";
	$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
	
	// Verify if have modification sice last efficacy revision to change residual risk associated
	$SQL = "SELECT result FROM trevision_control WHERE id_control = $ID_CONTROL ORDER BY prevision_date DESC LIMIT 2";
	$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
	$ARRAY = pg_fetch_array($RS);
	$i = 1;
	do{
		if($i == 1){
			$LAST_RESULT_1 = $ARRAY['result'];
		} elseif($i == 2){
			$LAST_RESULT_2 = $ARRAY['result'];
		}
		$i++;
	}while($ARRAY = pg_fetch_array($RS));

	// Update status's Control
	$SQL = "SELECT goal FROM tcontrol WHERE id = $ID_CONTROL";
	$RS = pg_query($conn, $SQL);
	$ARRAY = pg_fetch_array($RS);
	if($LAST_RESULT_1 < $ARRAY['goal']){
		$status = "n";
	} else {
		$status = "a";
	}
	$SQL = "UPDATE tcontrol SET status = '$status' WHERE id = $ID_CONTROL";
	$RS = pg_query($conn, $SQL);
	
	// Continue vrification if have modification sice last efficacy revision to change residual risk associated
	if((($LAST_RESULT_2 >= $ARRAY['goal']) && ($LAST_RESULT_1 < $ARRAY['goal'])) ||
	   (($LAST_RESULT_2 < $ARRAY['goal']) && ($LAST_RESULT_1 >= $ARRAY['goal'])) ||
	   (empty($PREVISION_RESULT))) {
		$SQL = "SELECT id FROM trisk WHERE id IN(SELECT id_risk FROM tarisk_control WHERE id_control = $ID_CONTROL)";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		do{
			if(!empty($ARRAY['id'])){
				updateResidualRisk($ARRAY['id']);
			}
		}while($ARRAY = pg_fetch_array($RS));
	}
}
?>