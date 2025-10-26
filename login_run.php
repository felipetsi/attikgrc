<?php
 error_reporting(E_ALL);
 
session_start();
require_once('include/function.php');
$DESTINATIONPAGE = "dashboard.php";
$DESTINATIONPAGE_NO_LOGIN = "login.php?instance=".$_SESSION['INSTANCE_NAME'];
$CODE_SUCCESSFUL = 'SSLOGI0001';
$CODE_FAILED = 'FSLOGI0001';


$LANG_LOGIN = $_POST['login'];
$LANG_PASSWORD = sha1($_POST['password']);
$_SESSION['INSTANCE_NAME'] = 'labsec';

$SQL = "SELECT p.id, p.name, i.status AS instance_status, p.status AS person_status, p.login, p.password, p.language_default, i.acceptance_risk_level, change_password_next_login ";
$SQL .= "FROM tinstance i, tperson p ";
$SQL .= "WHERE p.id_instance = i.id AND p.login = '$LANG_LOGIN' AND p.password = '$LANG_PASSWORD' AND i.name LIKE '".$_SESSION['INSTANCE_NAME']."'";
$RS = pg_query($conn, $SQL);
$ARRAY = pg_fetch_array($RS);
if((pg_affected_rows($RS) > 0)){
	$_SESSION['MSG_TOP'] = 'LANG_MSG_SUCESS_LOGIN_PASSWORD';
	$_SESSION['lang_default'] = $ARRAY['language_default'];
	$_SESSION['user_id'] = $ARRAY['id'];
	$_SESSION['user_name'] = $ARRAY['name'];
	
	header("Location:$DESTINATIONPAGE");
	
} else {
	print_r(pg_result_error($RS));
	$LANG = $_SESSION['lang_default'];
	require("include/lang/$LANG/general.php");
	if($ARRAY['instance_status'] == 'd'){
		$_SESSION['MSG_TOP'] = 'LANG_MSG_INSTANCE_DISABLE';
	} elseif ($ARRAY['person_status'] == 'd'){
		$_SESSION['MSG_TOP'] = 'LANG_MSG_PERSON_DISABLE';
	} else{
		$_SESSION['MSG_TOP'] = 'LANG_MSG_ERROR_LOGIN_PASSWORD';
	}
	insertHistory($_SESSION['INSTANCE_ID'],$CODE_FAILED,$LANG_LOGIN,$_SESSION['user_name']);
	header("Location:$DESTINATIONPAGE_NO_LOGIN");
}

?>
