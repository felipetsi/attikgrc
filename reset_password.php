<?php
session_start();
require_once('include/function.php');
require_once("include/conn_db.php");
$DESTINATIONPAGE = "login.php?instance=".$_SESSION['INSTANCE_NAME'];
$DESTINATIONPAGE_NO_LOGIN = "https://grc.attik.com.br/login.php?instance=".$_SESSION['INSTANCE_NAME'];

$EMAIL = substr(trim(addslashes($_POST['emailaddress'])),0,100);
$LANG_NAME_INSTANCE = $_SESSION['INSTANCE_NAME'];

if((!empty($EMAIL)) && (!empty($LANG_NAME_INSTANCE))) {
	$SQL = "SELECT id,min_password_lifetime,limit_error_login FROM tinstance WHERE name LIKE '$LANG_NAME_INSTANCE' ";
	$RS = pg_query($conn, $SQL);
	$ARRAY = pg_fetch_array($RS);
	$LANG_LIMIT_ERROR_LOGIN = $ARRAY['limit_error_login'];
	$MIN_TIMELIFE_PASS = $ARRAY['min_password_lifetime'];

	if(pg_affected_rows($RS) == 1){
		$ID_INSTANCE = $ARRAY['id'];
		$SQL = "SELECT name,email,erro_access_login,date_last_change_password,language_default FROM tperson WHERE email = '$EMAIL' AND id_instance = $ID_INSTANCE";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		if(pg_affected_rows($RS) == 1){
			
			if(($ARRAY['erro_access_login'] > $LANG_LIMIT_ERROR_LOGIN)||((date("Y-m-d") - $ARRAY['date_last_change_password']) < $MIN_TIMELIFE_PASS)){
				//Show error 
				$CODE = "FSLOGI0002";
				insertHistory($_SESSION['INSTANCE_ID'],$CODE,$ARRAY['name'],$EMAIL);
			} else{
				$LANG_PASSWORD_VALUE = generate_password();
				$HASH_PASSWORD = sha1($LANG_PASSWORD_VALUE);

				$SQL = "UPDATE tperson SET password = '$HASH_PASSWORD' ";
				$SQL .= "WHERE email = '".$ARRAY['email']."'";
				$RS = pg_query($conn, $SQL) or (die(pg_last_error($conn)));

				require_once("include/lang/".$ARRAY['language_default']."/general.php");
				//Change password
				$to = $ARRAY['email'];
				$subject = $LANG_TITLE_EMAIL_RESET_PASSWORD;

				$message = '
					<html>
					<head>
					<title>'.$LANG_TITLE_EMAIL_RESET_PASSWORD.'</title>
					</head>
					<body>
					<p>'.$LANG_TEXT_BODY_RESET_PASSWORD.'.</p>
					<h3>'.$LANG_TEMPORARY_PASSWORD.': '.$LANG_PASSWORD_VALUE.'</h3>
					<a href="'.$DESTINATIONPAGE_NO_LOGIN.'">'.$LANG_LOGIN.'</a>
					</body>
					</html>';

				// Always set content-type when sending HTML email
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				// More headers
				$headers .= 'From: <attik@attik.com.br>' . "\r\n";

				mail($to,$subject,$message,$headers);

				$CODE = "SSLOGI0002";
				insertHistory($_SESSION['INSTANCE_ID'],$CODE,$ARRAY['name'],$EMAIL);
			}
		}
	}
}

header("Location:$DESTINATIONPAGE");
?>