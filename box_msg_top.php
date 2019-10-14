<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
    require_once("include/lang/".$_SESSION['lang_default']."/general.php");
    if(!empty($_SESSION['MSG_TOP'])){
        echo '
        <div class="alert info text-center" id="alert_box" name="alert_box">
            '.${$_SESSION['MSG_TOP']}.'
        </div> ';
        unset($_SESSION['MSG_TOP']);
    }
}?>