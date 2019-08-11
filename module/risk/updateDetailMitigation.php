<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP'].'include/function.php');

	$ID_RISK = trim(addslashes($_POST['id_risk_mitigation']));
	$ID_CONTROL = trim(addslashes($_POST['id_control_mitigation']));
	$PROB = trim(addslashes($_POST['probability']));
	$PROB_JUST = trim(addslashes($_POST['justify_prob_mit_text']));
	$IDS_IMPACT = trim(addslashes($_POST['identify_impact'])); 
	$IDS_IMPACT = substr($IDS_IMPACT,0,(strlen($IDS_IMPACT)-1));
	$IDS_IMPACT = explode("@",$IDS_IMPACT);

	foreach($IDS_IMPACT as $pseudoValue){
		$value = substr($pseudoValue,1,(strlen($pseudoValue)-1));
		//$IMPACT[$value] = trim(addslashes($_POST['imp_mit'.$value]));
		$IMPACT[$value] = trim(addslashes($_POST['imp_mit'.$value])); if(empty($IMPACT[$value])){$IMPACT[$value] = "null";}
		$IMPACT_JUST[$value] = trim(addslashes($_POST["justify_impact_text$value"]));
	}

	$SQL = "UPDATE tarisk_control ";
	$SQL .= "SET probability=$PROB, probability_justification='$PROB_JUST' ";
	$SQL .= "WHERE id_risk = $ID_RISK AND id_control = $ID_CONTROL";
	$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

	foreach($IMPACT as $key => $value){
		if($value >= 0 ){
			$SQL = "UPDATE tarisk_control_impact ";
			$SQL .= "SET value=$value, justification='$IMPACT_JUST[$key]'";
			$SQL .= "WHERE id_risk = $ID_RISK AND id_control = $ID_CONTROL AND id_impact = $key";
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		}
	}
	updateResidualRisk($ID_RISK);
}
?>