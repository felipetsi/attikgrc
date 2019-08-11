<?php
session_start();
require_once('include/function.php');
$DESTINATIONPAGE = "dashboard.php";
$DESTINATIONPAGE_NO_LOGIN = "login.php?instance=".$_SESSION['INSTANCE_NAME'];
$CODE_SUCCESSFUL = 'SSLOGI0001'; // S-U-CONF-0001: 1º= Success or Fail, 2º opration type (update, insert, etc), 3º short name of page with four character, 4º sequencial number by page
$CODE_FAILED = 'FSLOGI0001';


$LANG_LOGIN = substr(trim(addslashes($_POST['login'])),0,30);
$LANG_PASSWORD = sha1($_POST['password']);

$SQL = "SELECT p.id, p.name, i.status AS instance_status, p.status AS person_status, p.login, p.password, p.language_default, i.acceptance_risk_level, change_password_next_login ";
$SQL .= "FROM tinstance i, tperson p ";
$SQL .= "WHERE p.id_instance = i.id AND p.login = '$LANG_LOGIN' AND p.password = '$LANG_PASSWORD' AND i.name LIKE '".$_SESSION['INSTANCE_NAME']."'";
$RS = pg_query($conn, $SQL);
$ARRAY = pg_fetch_array($RS);
	
if((pg_affected_rows($RS) == 1) && ($ARRAY['instance_status'] == 'a') && ($ARRAY['person_status'] == 'a') && ($ARRAY['login'] == $LANG_LOGIN) && ($ARRAY['password'] == $LANG_PASSWORD)){
	$_SESSION['MSG_TOP'] = 'LANG_MSG_SUCESS_LOGIN_PASSWORD';
	$_SESSION['lang_default'] = $ARRAY['language_default'];
	$_SESSION['user_id'] = $ARRAY['id'];
	$_SESSION['user_name'] = $ARRAY['name'];
	$_SESSION['ac_risk_level'] = $ARRAY['acceptance_risk_level'];
	
	$SQL = "SELECT id FROM timpact_type WHERE default_type = 'y' AND id_instance = ".$_SESSION['INSTANCE_ID'];
	$RS_IMP = pg_query($conn, $SQL);
	$ARRAY_IMP = pg_fetch_array($RS_IMP);
	$_SESSION['impact_default'] = $ARRAY_IMP['id'];
	
	if($ARRAY['change_password_next_login'] == 'y')
	{
		require('login_change_password.php');
	} else {
		$now = date('Y-m-d');
		$SQL = "SELECT p.id FROM tprocurator r, tperson p WHERE p.id = r.id_person AND r.status = 'a' AND '$now' >= r.date_start AND '$now' <= r.date_end AND ";
		$SQL .= "r.id_procurator = ".$ARRAY['id'];
		$RSPROC = pg_query($conn, $SQL);

		if(pg_affected_rows($RSPROC) > 0){
			require('login_procurator.php');
		} else {
			login_procedures($ARRAY['id']);
			insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL,$LANG_LOGIN,$_SESSION['user_name']);
			header("Location:$DESTINATIONPAGE");
		}
	}
} else {
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