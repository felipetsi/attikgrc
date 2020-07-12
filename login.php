<?php
session_start();
session_destroy();
session_start();
$_SESSION['LP'] = "./"; //Level path
$THIS_PAGE = "login.php";
$_SESSION['LAST_PAGE'] = $THIS_PAGE;

if((!isset($_GET['instance']))||(empty($_GET['instance']))){
	print_r("Error! Please contact us to more information and use");

} else {
	require_once($_SESSION['LP'].'include/function.php');
	
	$_SESSION['INSTANCE_NAME'] = substr(trim(addslashes($_GET['instance'])),0,10);
	
	$SQL = "SELECT id, language_default FROM tinstance WHERE name LIKE '".$_SESSION['INSTANCE_NAME']."'";
	$RS = pg_query($conn, $SQL);
	$ARRAY = pg_fetch_array($RS);

	if(pg_affected_rows($RS) == 1){
		$LANG = $ARRAY['language_default'];
		$_SESSION['INSTANCE_ID'] = $ARRAY['id'];
		$_SESSION['lang_default'] = substr($LANG,0,2);

		$LANG = $CONF_DEFAULT_SYSTEM_LANG;
		require_once($_SESSION['LP']."include/lang/$LANG/general.php");

		$DESTINATIONPAGE = "login_run.php";
	} else {
		print_r("Instance not found! Please, check the URL typed");	
	}

}
	?>
	<!DOCTYPE html>
	<html lang="en">
		<?php
		print_general_head($LANG);
		echo '<script src="js/login.js"></script>';
		?>
		<body class="bg-dark">

			<div class="container">
			<?php
			if(!empty($_SESSION['MSG_TOP'])){
				echo '
				<div class="alert info text-center" id="alert_box" name="alert_box">
				  '.${$_SESSION['MSG_TOP']}.'
				</div> ';
				unset($_SESSION['MSG_TOP']);
			}
			?>
			  <div class="card card-login mx-auto mt-5">
			  	<img src="../img/logoattik_GRC.png" class="img-fluid" alt="Responsive image">
				<div class="card-header">
				  <?php echo $LANG_LOGIN;?>
				</div>
				<div class="card-body">
				  <form action="<?php echo $DESTINATIONPAGE;?>" name="main_form" id="main_form" method="post" onkeydown="javascript:submitenter(event.keyCode);">
					<div class="form-group">
					  <label for="login"><?php echo $LANG_LOGIN;?>:</label>
					  <input type="text" class="form-control" id="login" name="login" aria-describedby="emailHelp" placeholder="<?php echo $LANG_LOGIN;?>">
					</div>
					<div class="form-group">
					  <label for="password"><?php echo $LANG_PASSWORD;?>:</label>
					  <input type="password" class="form-control" id="password" name="password" placeholder="Password">
					</div>
					<div class="form-group">
					  <div class="form-check">
						<label class="form-check-label">
						  <input type="checkbox" class="form-check-input" value="remember-me" id="remember_me">
						  <?php echo $LANG_REMEMBER_PASSWORD;?>
						</label>
					  </div>
					</div>
					<a class="btn btn-primary btn-block" href="javascript:postForm();"><?php echo $LANG_LOGIN;?></a>
				  </form>
				  <div class="text-center">
					<a class="d-block small" href="forgot_password.php?instance=<?php echo $_SESSION['INSTANCE_NAME'];?>"><?php echo $LANG_FORGOT_PASSWORD;?>?</a>
				  </div>
				</div>
			  </div>
			</div>

			<?php print_end_page();?>

		</body>

	</html>
