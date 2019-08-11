<?php
session_start();
// Start - individual configuration
$_SESSION['LP'] = "../../"; //Level path
$CONF_DIRECTORY_NAME = "attikgrc";
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THIS_PAGE = "reports.php";
$_SESSION['THIS_PAGE'] = $THIS_PAGE;
// This session variable is used in box_task with 4 character
$_SESSION['PAGE_FROM'] = "reports";
$DATATYPE = trim(addslashes($_GET['datatype']));
$_SESSION['STATUS_MULT_SEL'] = 0;

// END - individual configuration
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP'].'include/function.php');

	if(isset($_GET['lang'])){
		$CHECK_CHANGE_LANG = substr(trim(addslashes($_GET['lang'])),0,2);
		$_SESSION['lang_default'] = $CHECK_CHANGE_LANG;
		$LANG = $_SESSION['lang_default'];
	} elseif(!empty($_SESSION['lang_default'])){
		$LANG = $_SESSION['lang_default'];
	} else {
		$LANG = $CONF_DEFAULT_SYSTEM_LANG;
	}
	require_once($_SESSION['LP']."include/lang/$LANG/general.php");

	$PERMITIONS_NAME_2 = "read_report@";
	?>
	<!DOCTYPE html>
	<html lang="en">
		<?php
		echo '<script src="'.$_SESSION['LP'].'js/reports.js"></script>';
		print_general_head($LANG);
		?>
		<body class="fixed-nav sticky-footer bg-dark" id="page-top">
			<!-- Navigation -->
			<?php
			require_once($_SESSION['LP'].'include/full_menu.php');
			?>
			<div class="content-wrapper">
				<div class="container-fluid">
					<!-- edit item-->
					<hr class="mt-2">
					<div class="card-header">
						<div id="boxReport" ></div>
					</div>
				</div><!-- /.container-fluid -->
			</div><!-- /.content-wrapper -->
			<?php print_end_page_inside($LANG,0);?>
		</body>
	</html>
<?php 
	if ((($_SESSION['PAGE_FROM'] == "reports") && (!empty($_SESSION['show_report'])))||(!empty($DATATYPE))) {
	echo '
	<script>
		showReport(\''.$DATATYPE.'\');
	</script>';
	}
}?>