<?php
session_start();
$_SESSION['LP'] = "./";
$DESTINATIONPAGE = "login_change_password_run.php";
$DESTINATIONPAGE_NO_LOGIN = "login.php?instance=".$_SESSION['INSTANCE_NAME'];

if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once('include/function.php');
	require_once("include/lang/".$_SESSION['lang_default']."/general.php");
	echo '
	<!DOCTYPE html>
	<html lang="en">';
		print_general_head($_SESSION['lang_default']);
	echo '
		<body class="bg-dark">

			<div class="container">';
				if(!empty($_SESSION['MSG_TOP'])){
					echo '
					<div class="alert info text-center" id="alert_box" name="alert_box">
					  '.${$_SESSION['MSG_TOP']}.'
					</div> ';
					unset($_SESSION['MSG_TOP']);
				}
				echo '
			  <div class="card card-login mx-auto mt-5">
				  <h5>'.$LANG_MANDATORY_CHANGE_PASSW.'</h5>
				<div class="card-body">
				<form action="'.$DESTINATIONPAGE.'" name="main_form" id="main_form" method="post">
					<input type="hidden" name="login_myself" id="login_myself" value="1">
					<div class="row">
						<div class="col-md">
							<label class="control-label"><u>'.($LANG_NEW.' '.$LANG_PASSWORD).':</u></label>
							<input class="form-control input-sm" type="password" id="passwd" name="passwd" 
								   placeholder="'.$LANG_PASSWORD.'" />
						</div>
					</div>
					<div class="row">
						<div class="col-md">
							<label class="control-label"><u>'.($LANG_REPEAT.' '.$LANG_NEW.' '.$LANG_PASSWORD).':</u></label>
							<input class="form-control input-sm" type="password" id="re_passwd" name="re_passwd" 
								   placeholder="'.$LANG_REPEAT.'" />
						</div>
					</div>
					<div class="row">
						<div class="col-md">
							<input type="submit" class="btn btn-info btn-block" value="'.$LANG_UPDATE.'">
						</div>
					</div>
				  </form>
				</div>
			  </div>
			</div>
			<?php print_end_page();?>
		</body>
	</html>
	<script>
	function login_proc_sel_user(){
		document.getElementById("login_myself").value = "0";
		document.getElementById("main_form").submit();
	}
	</script>
	';
}?>