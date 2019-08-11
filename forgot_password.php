<?php
session_start();
require_once('include/function.php');
require_once("include/conn_db.php");
require_once("include/variable.php");

if((!isset($_GET['instance']))||(empty($_GET['instance']))){
	print_r("Error! Please contact us to more information and use");
} else {
	$LANG_NAME_INSTANCE = substr(trim(addslashes($_GET['instance'])),0,10);
	$SQL = "SELECT language_default FROM tinstance WHERE name LIKE '$LANG_NAME_INSTANCE' ";
	$RS = pg_query($conn, $SQL);
	$ARRAY = pg_fetch_array($RS);

	if(pg_affected_rows($RS) == 1){
		$LANG = $ARRAY['language_default'];
	}

	$_SESSION['lang_default'] = $LANG;

	$LANG = 'en';
	require_once("include/lang/$LANG/general.php");

	$DESTINATIONPAGE = "reset_password.php";
	$DESTINATIONPAGE_NO_LOGIN = "login.php?instance=".$_SESSION['INSTANCE_NAME'];
	?>
	<!DOCTYPE html>
	<html lang="en">

		<?php
			print_general_head($LANG);
		?>

		<body class="bg-dark">

			<div class="container">

			  <div class="card card-login mx-auto mt-5">
				<div class="card-header">
				  <?php echo $LANG_RESET_PASSWORD;?>
				</div>
				<div class="card-body">
				  <div class="text-center mt-4 mb-5">
					<h4><?php echo $LANG_FORGOT_PASSWORD;?>?</h4>
					<p><?php echo $LANG_TEXT_FORGOT_PASSWORD;?>.</p>
				  </div>
				  <form action="<?php echo $DESTINATIONPAGE;?>" method="post" name="main_form" id="main_form" onkeydown="javascript:submitenter(event.keyCode);" autocomplete="off">
					<div class="form-group">
						<input type="email" class="form-control" id="emailaddress" name="emailaddress" aria-describedby="emailHelp" placeholder="Enter email address">
					</div>

					<a class="btn btn-info btn-block" href="javascript:postForm();" ><?php echo $LANG_RESET_PASSWORD;?></a>
				  </form>
				  <div class="text-center">
					<a class="d-block small" href="<?php echo $DESTINATIONPAGE_NO_LOGIN;?>" ><?php echo $LANG_LOGIN;?></a>
				  </div>
				</div>
			  </div>
			</div>

			<?php print_end_page();?>

		</body>

	</html>
<?php
}?>