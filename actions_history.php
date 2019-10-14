<?php
session_start();
// Start - individual configuration
$_SESSION['LP'] = "./"; //Level path
$CONF_DIRECTORY_NAME = "attikgrc";
$DESTINATIONPAGE_NO_LOGIN = $CONF_DIRECTORY_NAME.$_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THIS_PAGE = "actions_history.php";
$_SESSION['THIS_PAGE'] = $THIS_PAGE;
$DESTINATION_PAGE = "actions_history.php";
// END - individual configuration
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
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
	
	// Start - individual configuration	
	// if(isset($_POST['executor'])){
	// 	$EXECUTOR = substr(trim(addslashes($_POST['executor'])),0,20);
	// } else {
	// 	$EXECUTOR = '';
	// } if(isset($_POST['action'])){
	// 	$ACTION = substr(trim(addslashes($_POST['action'])),0,10);
	// } else {
	// 	$ACTION = '';
	// } if(isset($_POST['detail'])){
	// 	$DETAIL = substr(trim(addslashes($_POST['detail'])),0,50);
	// } else {
	// 	$DETAIL = '';
	// } if(isset($_POST['execution_time'])){
	// 	$EXECUTION_TIME = substr(trim(addslashes($_POST['execution_time'])),0,10);
	// } else {
	// 	$EXECUTION_TIME = '';
	// }

	$PERMITIONS_NAME_1 = "view_history@";
	
	// End - individual configuration
	?>
	<!DOCTYPE html>
	<html lang="en">
		<?php
			print_general_head($LANG);
	
		// Start - individual configuration
		echo '
		<script type="text/javascript">
			$(document).ready(function(){
				var date_input=$(\'input[name="execution_time"]\'); //our date input has the name "date"
				var container=$(\'.bootstrap-iso form\').length>0 ? $(\'.bootstrap-iso form\').parent() : "body";
				var options={
					format: \''.$LANG_DATE_FORMAT.'\',
					container: container,
					todayHighlight: true,
					autoclose: true,
				};
				date_input.datepicker(options);
			});
		</script>';
	
		// End - individual configuration
		?>
		<body class="fixed-nav sticky-footer bg-dark" id="page-top">
			<!-- Navigation -->
			<?php
			require_once($_SESSION['LP'].'include/full_menu.php');
			?>

			<div class="content-wrapper">
				<div class="container-fluid">
					<?php require_once($_SESSION['LP'].'include/sub_menu_configuration.php'); ?>
					<!-- edit item-->
					<div class="mb-0 mt-4"></div>
						<hr class="mt-2">
						<div class="card-header">
							<div class="card mb-3">
								<label class="control-label"><center><i><u><?php echo $LANG_FILTER;?></u></i></center></label>
								<div class="row">
									<div class="col-md-12">
										<!-- <button id="btn_collapse_panel" type="button" class="btn btn-default  btn-block" data-toggle="collapse" data-target="#editPanel"><i id="btn_collapse_panel_icon_up" class="fa fa-angle-double-down"></i></button> -->
									</div>
								</div>
								<div id= "editPanel" class="<?php if(isset($_SESSION['NAME'])){echo 'collapse_in';}else{echo 'collapse';}?>">
									<?php if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false){
										echo'<center> '.$LANG_YOU_NOT_HAVE_PERMISSION.'</center>';
									} else {?>
									<!-- <form action="<?php echo $DESTINATION_PAGE;?>" method="post" name="main_form" id="main_form" onkeydown="javascript:submitenter(event.keyCode);" autocomplete="off">
										<div class="row">
											<div class="col-md-3">
												<label class="control-label"><?php echo $LANG_EXECUTOR;?>:</label>
												<input class="form-control input-sm" type="text" id="executor" name="executor" 
													   placeholder="<?php echo $LANG_EXECUTOR;?>" value ="" />
											</div>
											<?php
											/*
											<div class="col-md-3">
												<label class="control-label"><?php echo $LANG_ACTION;?>:</label>
												<input class="form-control input-sm" type="text" id="action" name="action" 
													   placeholder="<?php echo $LANG_ACTION;?>" value ="<?php echo $ACTION;?>" readonly />
											</div>
											*/?>
											<div class="col-md-3">
												<label class="control-label"><?php echo $LANG_OBJECT;?>:</label>
												<input class="form-control input-sm" type="text" id="detail" name="detail" 
													   placeholder="<?php echo $LANG_OBJECT;?>" value ="" />
											</div>
											<div class="col-md-3">
												<label class="control-label"><?php echo $LANG_EXECUTION_TIME;?>:</label>
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input class="form-control input-sm" type="text" id="execution_time" name="execution_time" 
														   placeholder="<?php echo $LANG_DATE_FORMAT_UPPERCASE;?>" value ="" />
												</div>
											</div>

											<div class="panel panel-default">
												<div class="row">
													<div class="col-md-3">
														<label class="control-label"></label>
														<button type="button" class="submit" fa fa-search onclick="javascript: postForm();"><i class="fa fa-search"></i></button>
													</div>
												</div>
											</div>
										</div>
									</form> -->
									<?php }?>
								</div>
							</div>
						</div>
					<!-- Item list -->
					<div class="card mb-3">
						<div class="card-header"></div>
						<div class="card-body">
							<div class="table-responsive">
								<form action="<?php echo $THIS_PAGE;?>" method="post" name="view_form" id="view_form"> 
									<input type="hidden" name="checkeditem" id="checkeditem">
									<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
										<thead>
											<tr>
												<th></th>
												<th><?php echo $LANG_EXECUTOR;?></th>
												<th><?php echo $LANG_ACTION;?></th>
												<th><?php echo $LANG_OBJECT;?></th>
												<th><?php echo $LANG_EXECUTION_TIME;?></th>
											</tr>
										</thead>
										<?php
										// Select this page item
										$SQL = "SELECT id, code, detail, to_char(execution_time,'".$LANG_SQL_TIMESTAMP_FORMAT."') AS execution_time, ";
										$SQL .= "name_person FROM thistory WHERE id_instance=".$_SESSION['INSTANCE_ID'];
										if(!empty($EXECUTOR)){
											$SQL .= " AND name_person LIKE '%$EXECUTOR%'";
										}
										if(!empty($EXECUTOR)){
											$SQL .= " AND detail LIKE '%$DETAIL%'";
										}
										if(!empty($EXECUTION_TIME)){
											$SQL .= " AND execution_time = '$EXECUTION_TIME'";
										}
										$SQL .= " ORDER BY id DESC";
										$RS = pg_query($conn, $SQL);
										$ARRAY = pg_fetch_array($RS);
										if(pg_affected_rows($RS) == 0){?>
											<tr class="odd gradeX">
												<td></td>
												<td></td>
												<td><?php echo $LANG_NO_HAVE_DATE;?></td>
												<td></td>
												<td></td>
											</tr>
										<?php } elseif((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false){
											echo'
											<tr class="odd gradeX">
												<th></th>
												<th></th>
												<th><center>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</center></th>
												<th></th>
												<th></th>
											</tr>';
										} else {
											do{
											?>
												<tr class="odd gradeX">
													<td><a href="<?php echo 'javascript:showActHistDetail('.$ARRAY['id'].')';?>"><input type="checkbox" name="optcheckitem[]" value="<?php echo $ARRAY['id']; ?>" ></a></td>
													<td><?php echo $ARRAY['name_person'];?></td>
													<td><?php echo ${$ARRAY['code']};?></td>
													<td><?php echo $ARRAY['detail'];?></td>
													<td><?php echo $ARRAY['execution_time'];?></td>
												</tr>
													<?php
											} while($ARRAY = pg_fetch_array($RS));
										}
										?>
									</table>
								</form>

							</div>
						</div>
					</div>
					<div class="card-footer small text-muted"><?php echo ($LANG_SYSTEM_VERSION.": ".$CONF_VERSION);?></div>
				</div>	
			</div>
			<!-- /.container-fluid -->
			<div class="modal fade" id="showDetail" tabindex="-1" role="dialog" aria-labelledby="task_boxLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content" id="panel_detail">
					</div>
				</div>
			</div>
			
			<?php 
			echo '<script src="'.$_SESSION['LP'].'js/configuration.js"></script>';
			print_end_page_inside($LANG,$ID_ITEM_SELECTED);?>

		</body>

	</html>
<?php 
	unset($ID_ITEM_SELECTED);
	$_SESSION['LAST_PAGE'] = $THIS_PAGE;
} ?>