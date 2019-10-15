<?php
session_start();

$CODE_SUCCESSFUL_UP = 'SUUSPE0001';
$CODE_SUCCESSFUL_UP_2 = 'SUUSPE0002';
$DESTINATIONPAGE_NO_LOGIN = "login.php?instance=".$_SESSION['INSTANCE_NAME'];

if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	$LAST_LP = $_SESSION['LP'];
	$_SESSION['LP'] = "./";
	require_once($_SESSION['LP'].'include/function.php');
	
	$NAME = substr(trim(addslashes($_POST['name'])),0,255);
	$DETAIL = substr(trim(addslashes($_POST['detail'])),0,500);
	$EMAIL = substr(trim(addslashes($_POST['email'])),0,100);
	$PASSWD = sha1($_POST['passwd']);
	$PASSWD_RE = sha1($_POST['re_passwd']);
	$LANG_DEFAULT = substr(trim(addslashes($_POST['language_default'])),0,2);
	if(isset($_POST['procurator_status'])){$PROCURATOR_STATUS = substr(trim(addslashes($_POST['procurator_status'])),0,1);} else {$PROCURATOR_STATUS = 'd';}
	$PROCURATOR = trim(addslashes($_POST['procurator']));
	$START_DATE = substr(trim(addslashes($_POST['start_date'])),0,10);
	$END_DATE = substr(trim(addslashes($_POST['end_date'])),0,10);
	
	if((!empty($_POST['passwd'])) && ($PASSWD == $PASSWD_RE)){
		$SQL = "SELECT min_password_lifetime FROM tinstance WHERE id = ".$_SESSION['INSTANCE_ID'];
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		$MIN_TIMELIFE_PASS = $ARRAY['min_password_lifetime'];
		
		$SQL = "SELECT date_last_change_password FROM tperson WHERE id = ".$_SESSION['user_id']." AND id_instance = ".$_SESSION['INSTANCE_ID'];
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		
		$now = date('Y-m-d');
		
		if(($now - $ARRAY['date_last_change_password']) >= $MIN_TIMELIFE_PASS){
			$SQL_COMPL = ", password = '$PASSWD', date_last_change_password = '$now' ";
		}
	}
	$SQL = "UPDATE tperson SET language_default = '$LANG_DEFAULT', name = '$NAME', detail = '$DETAIL', ";
	$SQL .= "email = '$EMAIL' $SQL_COMPL";
	$SQL .= "WHERE id = ".$_SESSION['user_id']." AND id_instance = ".$_SESSION['INSTANCE_ID'];
	$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
	insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_UP,$_SESSION['user_name'],$NAME);
	
	$SQL = "DELETE FROM tprocurator WHERE id_person = ".$_SESSION['user_id'];
	$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
	
	if((!empty($PROCURATOR)) && (!empty($START_DATE)) && (!empty($END_DATE))){	
		$SQL = "INSERT INTO tprocurator (id_person,id_procurator,date_start,date_end,status) VALUES ";
		$SQL .= "(".$_SESSION['user_id'].", $PROCURATOR, '$START_DATE', '$END_DATE', '$PROCURATOR_STATUS')";
		$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_UP_2,$_SESSION['user_name'],$NAME);
	}
	$_SESSION['LP'] = $LAST_LP;
}
?>