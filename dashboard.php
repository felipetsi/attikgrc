<?php
session_start();
// Start - individual configuration
$_SESSION['LP'] = "./"; //Level path
$CONF_DIRECTORY_NAME = "attikgrc";
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THIS_PAGE = "dashboard.php";
$_SESSION['THIS_PAGE'] = $THIS_PAGE;
// This session variable is used in box_task with 4 character
$_SESSION['PAGE_FROM'] = "dashboard";

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

	?>
	<!DOCTYPE html>
	<html lang="en">
		<?php
		print_general_head($LANG);
		?>
		<script src="vendor/RGraph/libraries/RGraph.svg.common.core.js"></script>
		<script src="vendor/RGraph/libraries/RGraph.svg.common.key.js"></script>
		<script src="vendor/RGraph/libraries/RGraph.svg.common.tooltips.js"></script>
		<script src="vendor/RGraph/libraries/RGraph.svg.pie.js"></script>
		<script src="vendor/RGraph/libraries/RGraph.svg.line.js"></script>
		<script src="vendor/RGraph/libraries/RGraph.svg.bar.js"></script>

		
		<body class="fixed-nav sticky-footer bg-dark" id="page-top">
			<!-- Navigation -->
			<?php
			require_once('include/full_menu.php');
			?>
			<div class="content-wrapper">
				<div class="container-fluid">
					<!-- Breadcrumbs -->
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="#">Dashboard</a>
						</li>
						<li class="breadcrumb-item active"><?php echo $LANG_MY_DASHBOARD;?></li>
						<li align="right"><a href="javascript:refreshScreen('dashboard');"><i class="fa fa-refresh"></i></a></li>
					</ol>
					<!-- Icon Cards -->
					<div class="row">
						<div class="col-xl-3 col-sm-6 mb-3" id="amountrisk"><script>showDataDashboard("amountrisk");</script></div>
						<div class="col-xl-3 col-sm-6 mb-3" id="amountcontrol"><script>showDataDashboard("amountcontrol");</script></div>
						<div class="col-xl-3 col-sm-6 mb-3" id="amountnonconformity"><script>showDataDashboard("amountnonconformity");</script></div>
						<div class="col-xl-3 col-sm-6 mb-3" id="amountincident"><script>showDataDashboard("amountincident");</script></div>
					</div>
					<div class="row">
						<div class="col-xl-3" id="riskcomplied"><script>showDataDashboard("riskcomplied");</script></div>
						<div class="col-xl-9" id="graph_risk_history_amount"><script>showDataDashboard("graph_risk_history_amount");</script></div>
					</div>
					<div class="row">
						<div class="col-xl-3" id="graph_risk_treatment"><script>showDataDashboard("graph_risk_treatment");</script></div>
						<div class="col-xl-3" id="aboutcontrol"><script>showDataDashboard("aboutcontrol");</script></div>
						<div class="col-xl-3" id="aboutarea"><script>showDataDashboard("aboutarea");</script></div>
						<div class="col-xl-3" id="percentapplied"><script>showDataDashboard("percentapplied");</script></div>
					</div>
				</div><!-- /.container-fluid -->
			</div><!-- /.content-wrapper -->
			<?php print_end_page_inside($LANG,0);?>
		</body>
	</html>
<?php 
}?>