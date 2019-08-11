login_change_password_run<?php
session_start();
$DESTINATIONPAGE_NO_LOGIN = "login.php?instance=".$_SESSION['INSTANCE_NAME'];
$PAGEBACK = "login_change_password.php";
$DESTINATIONPAGE = "dashboard.php";

if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once('include/function.php');
	require_once("include/lang/".$_SESSION['lang_default']."/general.php");
	$PASSWD = sha1($_POST['passwd']);
	$PASSWD_RE = sha1($_POST['re_passwd']);
	$PASSWD_RAW = substr(trim(addslashes($_POST['passwd'])),0,1);
	
	if((empty($PASSWD)) || (empty($PASSWD_RE))){
		$_SESSION['MSG_TOP'] = 'LANG_MSG_NEED_FILL_UNDERLINED';
		header("Location:$PAGEBACK");
	} elseif($PASSWD != $PASSWD_RE){
		$_SESSION['MSG_TOP'] = 'LANG_MSG_PASSWORDS_DIFFERENT';
		header("Location:$PAGEBACK");
	} else {
		$SQL = "UPDATE tperson SET password = '$PASSWD', date_last_change_password = CURRENT_DATE, change_password_next_login = 'n' ";
		$SQL .= "WHERE id = ".$_SESSION['user_id']." AND id_instance = ".$_SESSION['INSTANCE_ID'];
		$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		if(pg_affected_rows($RS) > 0){
			$_SESSION['MSG_TOP'] = 'LANG_MSG_SUCESS_LOGIN_PASSWORD';
			header("Location:$DESTINATIONPAGE");
		} else {
			$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
			header("Location:$PAGEBACK");
		}
	}
}
?>