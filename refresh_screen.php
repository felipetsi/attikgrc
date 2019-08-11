<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP'].'include/function.php');
	
	$SCREEN = trim(addslashes($_POST['screen']));
	
	if($SCREEN = 'dashboard'){
		destroySession(array('GRT_LABELS','GRT_AMOUNTS','GRHA_LABELS','GRHA_AMOUNTS','GRHA_KEY','AR_AMOUNTS','ANC_AMOUNTS','AC_AMOUNTS','AI_AMOUNTS','AT_AMOUNTS','PA_NAME_BP','PA_AMOUNT_BP','PA_AMOUNT_AP_BP'));
	}
}
?>