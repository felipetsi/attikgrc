<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	
	$HISTORY = trim(addslashes($_POST['history']));
	
	$SQL = "SELECT id FROM treport WHERE id_instance = ".$_SESSION['INSTANCE_ID']." ORDER BY version DESC LIMIT 1";
	$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
	$ARRAY = pg_fetch_array($RS);
	
	$SQL = "UPDATE treport SET history = '$HISTORY' WHERE status = 'a' AND id = ".$ARRAY['id']." AND id_instance = ".$_SESSION['INSTANCE_ID'];
	$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
}
?>