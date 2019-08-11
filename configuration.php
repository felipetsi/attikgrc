<?php
session_start();
// Start - individual configuration
$_SESSION['LP'] = "./"; //Level path
$CONF_DIRECTORY_NAME = "attikgrc";
$DESTINATIONPAGE_NO_LOGIN = "login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THIS_PAGE = "configuration.php";
$_SESSION['THIS_PAGE'] = $THIS_PAGE;
$DESTINATION_PAGE = "configuration_run.php";
// End - individual configuration

if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once('include/function.php');

	if(isset($_GET['lang'])){
		$CHECK_CHANGE_LANG = substr(trim(addslashes($_GET['lang'])),0,2);
		$_SESSION['lang_default'] = $CHECK_CHANGE_LANG;
		$LANG = $_SESSION['lang_default'];
	} elseif(!empty($_SESSION['lang_default'])){
		$LANG = $_SESSION['lang_default'];
	} else {
		$LANG = $CONF_DEFAULT_SYSTEM_LANG;
	}
	require_once("include/lang/$LANG/general.php");
	
	// Start - individual configuration	
	$PERMITIONS_NAME_1 = "instance_conf@";
		
	$SQL = "SELECT name,limit_user,detail,language_default,acceptance_risk_level,limit_error_login,max_password_lifetime, ";
	$SQL .= "min_password_lifetime, time_change_temp_password, close_system, last_update, enable_delete_cascade ";
	$SQL .= "FROM tinstance WHERE id = ".$_SESSION['INSTANCE_ID'];
	$RS = pg_query($conn, $SQL);
	$ARRAY = pg_fetch_array($RS);
	// End - individual configuration
	?>
	<!DOCTYPE html>
	<html lang="en">
		<?php
			print_general_head($LANG);
		?>

		<body class="fixed-nav sticky-footer bg-dark" id="page-top">
			<!-- Navigation -->
			<?php
			require_once('include/full_menu.php');
			?>

			<div class="content-wrapper">

			<div class="container-fluid">

			<?php require_once('include/sub_menu_configuration.php'); ?>
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="panel-body"> <!-- edit asset-->
								<?php if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false){
									echo'<center> '.$LANG_YOU_NOT_HAVE_PERMISSION.'</center>';
								} else {?>
								<div class="panel panel-default">
									<form action="<?php echo $DESTINATION_PAGE;?>" method="post" name="main_form" id="main_form" onkeydown="javascript:submitenter(event.keyCode);" autocomplete="off"> 
										<div class="row">
											<div class="col-md-4">
												<h4><?php echo $LANG_GENERAL_CONFIGURATION;?></h4>
												<div class="col-md">
													<label class="control-label"><?php echo $LANG_INSTANCE_NAME;?>:</label>
													<input class="form-control input-sm" type="text" id="name" name="name" 
														   value ="<?php echo $ARRAY['name'];?>" readonly />
												</div>
												<div class="col-md">
													<label class="control-label"><?php echo $LANG_LIMIT_AMOUNT_USER;?>:</label>
													<input class="form-control input-sm" type="text" id="limit_user" name="limit_user" 
														   value ="<?php echo $ARRAY['limit_user'];?>" readonly />
												</div>
												<div class="col-md">
													<label class="control-label"><?php echo $LANG_DEFAULT_LANGUAGE;?>:</label>
													<select class="form-control" id="language_default" name="language_default">
														<?php
														foreach ($CONF_AVAILABLE_LANGUAGE as $lang_op) {
															$temp_array = explode("@",$lang_op);
															if ($temp_array[1] == $ARRAY['language_default']) {
																$sel = 'selected="selected"';
															} else {
																$sel = '';
															}
															echo '<option value="'.$temp_array[1].'" '.$sel.'>'.$temp_array[0].'</option>';
														}
														?>
													</select>
												</div>
												<div class="col-md">
													<label class="control-label"><?php echo $LANG_DEFAULT_APPROVER;?>:</label>
													<select class="form-control" id="approver_default" name="approver_default">
														<?php
														$SQL = "SELECT id_person FROM tespecial_person WHERE name LIKE 'defau_appr' AND ";
														$SQL .= "id_instance = ".$_SESSION['INSTANCE_ID'];
														$RS = pg_query($conn, $SQL);
														$ARRAY_ESP_USER = pg_fetch_array($RS);
										
														$SQL = "SELECT id,name FROM tperson WHERE id_instance = ".$_SESSION['INSTANCE_ID']." ";
														$SQL .= "AND status = 'a' ORDER BY name";
														$RSPERSON = pg_query($conn, $SQL);
														$ARRAYPERSON = pg_fetch_array($RSPERSON);

														do{
															if ($ARRAYPERSON['id'] == $ARRAY_ESP_USER['id_person']) {
																$sel = 'selected="selected"';
															} else {
																$sel = '';
															}
															echo '<option value="'.$ARRAYPERSON['id'].'" '.$sel.'>'.$ARRAYPERSON['name'].'</option>';
														}while($ARRAYPERSON = pg_fetch_array($RSPERSON)); ?>
													</select>
												</div>
												<div class="col-md">
													<label class="control-label"><?php echo $LANG_BEST_PRATICES;?>:</label>
													<div class="col-md">
														<table class="table table-bordered" width="100%" id="bp" name="bp" cellspacing="0">
															<thead>
																<tr>
																	<th></th>
																	<th><?php echo $LANG_NAME;?></th>
																</tr>
															</thead>
														<?php
														$SQL = "SELECT name,id,status FROM tbest_pratice WHERE id_instance = ".$_SESSION['INSTANCE_ID'];
														$RSIN = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
														$ARRAYIN = pg_fetch_array($RSIN);
														do {
															if ($ARRAYIN['status'] == 'a'){
																$sel = 'checked="checked"';
															} else {
																$sel = "";
															}
															echo '
																<tr class="gradeX">
																	<td><input type="checkbox" class="form-check-input" id="bp-'.$ARRAYIN['id'].'" name="bp-'.$ARRAYIN['id'].'" value="1" '.$sel.'></td>
																	<td for="bp-'.$ARRAYIN['id'].'">'.$ARRAYIN['name'].'</td>
																</tr>';
														}while($ARRAYIN = pg_fetch_array($RSIN));?>
														</table>
													</div>
												</div>
												<?php
												/*
												<div class="col-md">
													<label class="control-label"><?php echo $LANG_CLOSE_SYSTEM;?>:</label>
													<select class="form-control" id="close_sytem" name="close_sytem">
														<?php
														foreach ($CONF_BOOLEAN_OP as $item_op) {
															$sub_item_op = explode("@",$item_op);
															if ($sub_item_op[1] == $ARRAY['close_system']) {
																$sel = 'selected="selected"';
															} else {
																$sel = '';
															}
															echo '<option value="'.$sub_item_op[1].'" '.$sel.'>'.${$sub_item_op[0]}.'</option>';
														}
														?>
													</select>
												</div>
												*/?>
											</div>
											
											<div class="col-md-4">
												<h4><?php echo $LANG_PASSWORD_CONFIGURATION;?></h4>
												<div class="col-md">
													<label class="control-label"><?php echo $LANG_LIMIT_ERROR_LOGIN;?>:</label>
													<select class="form-control" id="limit_error_login" name="limit_error_login">
														<?php
														foreach ($CONF_ERROR_LOGIN_SCALE as $item_op) {
															if ($item_op == $ARRAY['limit_error_login']) {
																$sel = 'selected="selected"';
															} else {
																$sel = '';
															}
															echo '<option value="'.$item_op.'" '.$sel.'>'.$item_op.'</option>';
														}
														?>
													</select>
													<div class="dropdown-message small"><?php echo $LANG_MSG_KEEP_ZERO_TO_NOT_USE;?></div>
												</div>
												<div class="col-md">
													<label class="control-label"><?php echo $LANG_MAX_PASSWORD_LIFETIME;?>:</label>
													<select class="form-control" id="maximum_password_lifetime" name="maximum_password_lifetime">
														<?php
														foreach ($CONF_PASSWORD_LIFETIME as $item_op) {
															if ($item_op == $ARRAY['max_password_lifetime']) {
																$sel = 'selected="selected"';
															} else {
																$sel = '';
															}
															echo '<option value="'.$item_op.'" '.$sel.'>'.$item_op.'</option>';
														}
														?>
													</select>
													<div class="dropdown-message small"><?php echo $LANG_MSG_KEEP_ZERO_TO_NOT_USE;?></div>
												</div>
												<div class="col-md">
													<label class="control-label"><?php echo $LANG_MIN_PASSWORD_LIFETIME;?>:</label>
													<select class="form-control" id="minimum_password_lifetime" name="minimum_password_lifetime">
														<?php
														foreach ($CONF_PASSWORD_LIFETIME as $item_op) {
															if ($item_op == $ARRAY['min_password_lifetime']) {
																$sel = 'selected="selected"';
															} else {
																$sel = '';
															}
															echo '<option value="'.$item_op.'" '.$sel.'>'.$item_op.'</option>';
														}
														?>
													</select>
													<div class="dropdown-message small"><?php echo $LANG_MSG_KEEP_ZERO_TO_NOT_USE;?></div>
												</div>
												<div class="col-md">
													<label class="control-label"><?php echo $LANG_TIME_TO_CHANGE_TEMP_PASSWORD;?>:</label>
													<select class="form-control" id="time_change_temp_password" name="time_change_temp_password">
														<?php
														foreach ($CONF_PASSWORD_LIFETIME as $item_op) {
															if ($item_op == $ARRAY['time_change_temp_password']) {
																$sel = 'selected="selected"';
															} else {
																$sel = '';
															}
															echo '<option value="'.$item_op.'" '.$sel.'>'.$item_op.'</option>';
														}
														?>
													</select>
													<div class="dropdown-message small"><?php echo $LANG_MSG_KEEP_ZERO_TO_NOT_USE;?></div>
												</div>
											</div>
											<div class="col-md-4">
												<h4><?php echo $LANG_RISK_CONFIGURATION;?></h4>
												<div class="col-md">
													<label class="control-label"><?php echo $LANG_ACCEPTANCE_RISK_LEVEL;?>:</label>
													<input class="form-control input-sm" type="text" id="acceptance_level" name="acceptance_level" 
														   placeholder="<?php echo $LANG_ACCEPTANCE_RISK_LEVEL;?>:" value ="<?php echo $ARRAY['acceptance_risk_level'];?>" required />
												</div>
												<div class="col-md">
													<label class="control-label"><?php echo $LANG_ENABLE_DELETE_CASCADE;?>:</label>
													<select class="form-control" id="delete_cascade" name="delete_cascade">
														<?php
														foreach ($CONF_BOOLEAN_OP as $item_op) {
															$sub_item_op = explode("@",$item_op);
															if ($sub_item_op[1] == $ARRAY['enable_delete_cascade']) {
																$sel = 'selected="selected"';
															} else {
																$sel = '';
															}
															echo '<option value="'.$sub_item_op[1].'" '.$sel.'>'.${$sub_item_op[0]}.'</option>';
														}
														?>
													</select>
												</div>
												<?php
												$SQL = "SELECT * FROM tinstance_impact_money WHERE id_instance = ".$_SESSION['INSTANCE_ID'];
												$RSINSIDE = pg_query($conn, $SQL);
												$ARRAYINSIDE = pg_fetch_array($RSINSIDE);
												do{
													echo '
												<div class="col-md">
													<div class="row">
														<div class="col-md">
															<label class="control-label">'.$LANG_IMPACT_FINANCIAL_VALUE.' - '.${'L'.$ARRAYINSIDE['impact_level']}.':</label>
															<div class="row">
																<div class="col-md-5">
																	<input class="form-control input-sm" type="text"
																	id="impact_money_'.$ARRAYINSIDE['impact_level'].'_s" 
																	name="impact_money_'.$ARRAYINSIDE['impact_level'].'_s" 
																		   value ="'.$ARRAYINSIDE['value_start'].'" required />
																</div> <b> < </b>
																<div class="col-md-5">
																	<input class="form-control input-sm" type="text"
																	id="impact_money_'.${'L'.$ARRAYINSIDE['impact_level']}.'_e" 
																	name="impact_money_'.$ARRAYINSIDE['impact_level'].'_e" 
																		   value ="'.$ARRAYINSIDE['value_end'].'" required />
																</div>
																<div class="dropdown-message small">'.$LANG_PUT_ZERO_TO_INFINITY.'</div>
															</div>
														</div>
													</div>
												</div>';
												
												}while($ARRAYINSIDE = pg_fetch_array($RSINSIDE));
												?>
												<div class="col-md">
													<label class="control-label"><?php echo $LANG_IMPACT_TYPE;?>:</label>
													<div class="col-md">
														<table class="table table-bordered" width="100%" id="impact_type" name="impact_type" cellspacing="0">
															<thead>
																<tr>
																	<th></th>
																	<th><?php echo $LANG_NAME;?></th>
																	<th><?php echo $LANG_DEFAULT;?></th>
																</tr>
															</thead>
														<?php
														$SQL = "SELECT name,id,status,default_type FROM timpact_type WHERE id_instance = ".$_SESSION['INSTANCE_ID'];
														$RSIN = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
														$ARRAYIN = pg_fetch_array($RSIN);
														do {
															if ($ARRAYIN['status'] == 'a'){
																$sel = 'checked="checked"';
															} else {
																$sel = "";
															}
															if ($ARRAYIN['default_type'] == 'y'){
																$sel_def = 'checked="checked"';
															} else {
																$sel_def = "";
															}
															echo '
																<tr class="gradeX">
																	<td><input type="checkbox" class="form-check-input" id="impact-'.$ARRAYIN['id'].'" name="impact-'.$ARRAYIN['id'].'" value="1" '.$sel.'></td>
																	<td for="impact-'.$ARRAYIN['id'].'">'.${$ARRAYIN['name']}.'</td>
																	<td><input type="radio" name="impact_type_default" value="'.$ARRAYIN['id'].'" '.$sel_def.'></td>
																</tr>';
														}while($ARRAYIN = pg_fetch_array($RSIN));?>
														</table>
													</div>
												</div>
												<?php /*
												<div class="col-md">
													<label class="control-label"><?php echo $LANG_LOGO_FILE;?>:</label>
													<input id="logo_file" name="logo_file" type="file" class="file">
												</div>
												*/?>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="row">
												<div class="col-md">
													<button type="button" class="btn btn-secondary btn-block" onclick="javascript: postForm();">
														<?php echo $LANG_UPDATE;?>
													</button>
												</div>
											</div>
										</div>
									</form>
								</div>
								<?php 
								 }?>
							</div> <!-- edit asset-->
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer small text-muted"><?php echo ($LANG_SYSTEM_VERSION.": ".$CONF_VERSION);?></div>
			</div>

			</div>
			<!-- /.container-fluid -->

			<?php print_end_page_inside($LANG,0);?>

		</body>

	</html>
<?php 
	$_SESSION['LAST_PAGE'] = $THIS_PAGE;
} ?>