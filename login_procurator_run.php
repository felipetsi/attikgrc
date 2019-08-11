<?php
session_start();
$DESTINATIONPAGE_NO_LOGIN = "login.php?instance=".$_SESSION['INSTANCE_NAME'];
$DESTINATIONPAGE = "dashboard.php";
$CODE_SUCCESSFUL_PROC = 'SUUSPE0003';
$CODE_SUCCESSFUL = 'SSLOGI0001'; // S-U-CONF-0001: 1º= Success or Fail, 2º opration type (update, insert, etc), 3º short name of page with four character, 4º sequencial number by page

if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once('include/function.php');
	require_once("include/lang/".$_SESSION['lang_default']."/general.php");
	$LOGIN_MYSELF = trim(addslashes($_POST['login_myself']));
	$PROCURATOR_AS = trim(addslashes($_POST['procurator_as']));
	
	if(($LOGIN_MYSELF == "0") && (!empty($PROCURATOR_AS))){
		$SQL = "SELECT p.name FROM tperson p WHERE p.id = $PROCURATOR_AS";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
	
		$_SESSION['user_id'] = $PROCURATOR_AS;
		insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_PROC,$_SESSION['user_name'],$ARRAY['name']);
		$_SESSION['user_name'] = $_SESSION['user_name']." - [".$ARRAY['name']."]";
	} else {
		insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL,$LANG_LOGIN);
	}
	
	login_procedures($_SESSION['user_id']);
	header("Location:$DESTINATIONPAGE");
}
?>