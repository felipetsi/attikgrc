<?php
error_reporting(E_ALL);

session_destroy();
session_start();
$_SESSION['LP'] = "./"; //Level path
$THIS_PAGE = "login.php";
$_SESSION['LAST_PAGE'] = $THIS_PAGE;

if((!isset($_GET['instance']))||(empty($_GET['instance']))){
	print_r("Lab mode! Type it: http://IP_INSTANCE?instance=TRY-HACKING");

} else {
	require_once($_SESSION['LP'].'include/function.php');
	
	$_SESSION['INSTANCE_NAME'] = $_GET['instance'];
	
	$SQL = "SELECT id, language_default FROM tinstance WHERE name LIKE '".$_SESSION['INSTANCE_NAME']."'";
	
	$RS = pg_query($conn, $SQL);
	$ARRAY = pg_fetch_array($RS);
	$ARRAY2 = $ARRAY;
	
	do{
		print_r($ARRAY2);
	}while($ARRAY2 = pg_fetch_array($RS));

	if(pg_affected_rows($RS) != 0){
		$LANG = $ARRAY['language_default'];
		$_SESSION['INSTANCE_ID'] = $ARRAY['id'];
		$_SESSION['lang_default'] = $LANG;

		$LANG = $CONF_DEFAULT_SYSTEM_LANG;
		require_once($_SESSION['LP']."include/lang/$LANG/general.php");

		$DESTINATIONPAGE = "login_run.php";
	} else {
		print_r("URL typed wrong. Type it: http://IP_INSTANCE?instance=TRY-HACKING");	
	}

}
	?>
	<!DOCTYPE html>
	<html lang="en">
		<?php
		print_general_head($LANG);
		echo '<script src="js/login.js"></script>';
		?>
		<body >

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
			  	<img src="../img/logo_labsec.png" class="img-fluid" alt="Responsive image">
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
				</div>
			  </div>
			</div>

			<?php print_end_page();?>

		</body>

	</html>
