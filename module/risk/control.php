<?php
session_start();
// Start - individual configuration
$_SESSION['LP'] = "../../"; //Level path
$CONF_DIRECTORY_NAME = "attikgrc";
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THIS_PAGE = "control.php";
$_SESSION['THIS_PAGE'] = $THIS_PAGE;
$DESTINATION_PAGE = "control_run.php";
$DESTINATION_PAGE_NEXT = "control.php";
// This session variable is used in box_task with 4 character
$_SESSION['PAGE_FROM'] = "cont";

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
	
	// Start - individual configuration	
	$PERMITIONS_NAME_1 = "create_control@";
	$PERMITIONS_NAME_2 = "read_own_control@";
	$PERMITIONS_NAME_3 = "read_all_control@";
	$PERMITIONS_NAME_5 = "revision_efficacy@";
	// Task permitions
	$PERMITIONS_NAME_6 = "create_task@";
	$PERMITIONS_NAME_7 = "read_own_task@";
	$PERMITIONS_NAME_8 = "read_all_task@";
	$PERMITIONS_NAME_9 = "approver_task@";
	$PERMITIONS_NAME_10 = "treatment_task@";	
	
	// Verify if multi-select is enable
	if(isset($_POST['change_multi_sel'])&&($_POST['change_multi_sel'] == 1)){if($_SESSION['STATUS_MULT_SEL'] == 0){$_SESSION['STATUS_MULT_SEL'] = 1;}
																			 else {$_SESSION['STATUS_MULT_SEL'] = 0;}}
	else {$_SESSION['STATUS_MULT_SEL'] = 0;}
	// Verify if multi-select is enable
	
	if(!empty($_POST['relateditem'])){
		$ID_ITEM_RELATED = trim(addslashes($_POST['relateditem']));
		$_SESSION['ID_ITEM_RELATED'] = $ID_ITEM_RELATED;
	} elseif ($_SESSION['LAST_PAGE'] != $THIS_PAGE){
		unset($_SESSION['ID_ITEM_RELATED']);
	} elseif(!empty($_SESSION['ID_ITEM_RELATED'])) {
		$ID_ITEM_RELATED = $_SESSION['ID_ITEM_RELATED'];
	}
	
	if(!empty($_POST['checkeditem'])){
		$ID_ITEM_SELECTED = trim(addslashes($_POST['checkeditem']));
	} elseif(isset($_SESSION['ID_SEL'])) {
		$ID_ITEM_SELECTED = $_SESSION['ID_SEL'];
	}
	
	if(!empty($ID_ITEM_SELECTED)){
		// Start - individual configuration
		$SQL = "SELECT c.id, c.name, c.detail, c.id_process, c.metric, c.metric_detail, c.goal, c.enable_revision, c.status, ";
		$SQL .= "TO_CHAR(c.implementation_date,'".$LANG_DATE_FORMAT_UPPERCASE."') AS implementation_date, ";
		$SQL .= "TO_CHAR(c.apply_revision_from,'".$LANG_DATE_FORMAT_UPPERCASE."') AS apply_revision_from, ";
		$SQL .= "c.scheduling_day, c.scheduling_month, c.scheduling_weekday, c.deadline_revision ";
		$SQL .= "FROM tcontrol c ";
		$SQL .= "WHERE c.id = $ID_ITEM_SELECTED AND ";
		$SQL .= "c.id_process IN(SELECT id FROM tprocess WHERE id_area IN ";
		$SQL .= "(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID']."))";
		$RS = pg_query($conn, $SQL);
		$ARRAYSELECTION = pg_fetch_array($RS);
		
		$SQL = "SELECT * FROM trevision_control WHERE id_control = $ID_ITEM_SELECTED  LIMIT 5";
		$RS = pg_query($conn, $SQL);
		$ARRAYLASTREVISION = pg_fetch_array($RS);
		// End - individual configuration
	} else {
		$ID_ITEM_SELECTED = '';
	}
	?>
	<!DOCTYPE html>
	<html lang="en">
		<?php echo '<script src="'.$_SESSION['LP'].'js/control.js"></script>';
		print_general_head($LANG); 
	
		// Start - individual configuration
		echo '
		<script type="text/javascript">
			$(document).ready(function(){
				var date_input=$(\'input[name="implementation_date"]\'); //our date input has the name "date"
				var container=$(\'.bootstrap-iso form\').length>0 ? $(\'.bootstrap-iso form\').parent() : "body";
				var options={
					format: \''.$LANG_DATE_FORMAT.'\',
					container: container,
					todayHighlight: true,
					autoclose: true,
				};
				date_input.datepicker(options);
			});
			$(document).ready(function(){
				var date_input=$(\'input[name="apply_revision_from"]\'); //our date input has the name "date"
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
			<!-- Start - individual configuration - Start submenu -->
			<div id="box_submenu" role="menu">
				<ul class="submenu dropdown-menu">
					<li><a id="e" href="#"><i class="fa fa-edit"></i> <?php echo $LANG_EDIT;?></a></li>
					<li><a id="d" href="#"><i class="fa fa-minus-square-o"></i> <?php echo $LANG_DELETE;?></a></li>
					<li><a id="u" href="#"><i class="fa fa-clone"></i> <?php echo $LANG_DUPLICATE;?></a></li>
						<div class="sumenu_divider"></div>
					<li><a id="m" href="#"><i class="fa fa-tasks"></i> <?php echo ${'LANG_MULTI_SELECT_'.$_SESSION['STATUS_MULT_SEL'].''};?></a>
					</li>
				</ul>
			</div>
			<div id="box_submenu2" role="menu">
				<ul class="submenu2 dropdown-menu">
					<li><a id="i2"><i class="fa fa-plus-square-o"></i> <?php echo $LANG_INSERT;?></a></li>
					<li><a id="d2"><i class="fa fa-minus-square-o"></i> <?php echo $LANG_DELETE;?></a></li>
					<li><a id="u2"><i class="fa fa-clone"></i> <?php echo $LANG_DUPLICATE;?></a></li>
				</ul>
			</div>
			<!-- End - individual configuration - End submenu -->
				
			<div class="content-wrapper">
				<div class="container-fluid">
					<?php require_once($_SESSION['LP'].'include/sub_menu_risk.php'); ?>
					<!-- edit item-->
					<div class="mb-0 mt-4"></div>
						<hr class="mt-2">
						<?php
						if(!empty($ID_ITEM_RELATED)){
							$SQL = "SELECT a.id AS id_area, a.name AS area, p.id AS id_process, p.name AS process, r.id AS id_risk, r.name AS risk ";
							$SQL .= "FROM tarea a, tprocess p, trisk r WHERE ";
							$SQL .= "r.id = $ID_ITEM_RELATED AND p.id_area = a.id AND r.id_process = p.id";
							$RS = pg_query($conn, $SQL);
							$ARRAY = pg_fetch_array($RS);
							
							echo '
							<form action="area.php" method="post" name="formBackRelatedArea" id="formBackRelatedArea">
								<input type="hidden" name="itemBackRelated" id="itemBackRelated">
							</form>

							<div class="row small">
								<div class="col-md">
									<a href="javascript:backToRelatedArea('.$ARRAY['id_area'].');">'.substr($ARRAY['area'],0,30).'</a>
								</div>
								<div class="col-md-1 small"> <i class="fa fa-caret-right"></i></div>
								<div class="col-md">
									<a href="javascript:backToRelatedArea('.$ARRAY['id_process'].');">'.substr($ARRAY['process'],0,30).'</a>
								</div>
									<div class="col-md-1 small"> <i class="fa fa-caret-left"></i></div>
								<div class="col-md">
									<a href="javascript:backToRelatedArea('.$ARRAY['id_risk'].');">'.substr($ARRAY['risk'],0,30).'</a>
								</div>
									<div class="col-md-1 small"> <i class="fa fa-caret-left"></i></div>
							</div>';
						}?>
						<div class="card-header">
							<div class="card mb-3">
								<label class="control-label"><center><i><u><?php echo $LANG_CONTROL;?></u></i></center></label>
								<div class="row">
									<div class="col-md-12">
										<button id="btn_collapse_panel" type="button" class="btn btn-default  btn-block" data-toggle="collapse" data-target="#editPanel"><i id="btn_collapse_panel_icon_up" class="fa fa-angle-double-down"></i></button>
									</div>
								</div>
							
								<div id= "editPanel" class="<?php if(isset($_SESSION['NAME_CONTROL'])){echo 'collapse_in';}else{echo 'collapse';}?>">
									<?php if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false &&
											 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2)) === false &&
											 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3)) === false){
										echo '<center>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</center>';
									} else {
										if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false) {
											$CRET_CONT_INP = "readonly";
											$CRET_CONT_SEL = "disabled";
										} else {
											// Start variables
											$CRET_CONT_INP = "";
											$CRET_CONT_SEL = "";
										}
				
										if(isset($ARRAYSELECTION)){
											if($ARRAYSELECTION['status'] == 'd')
											{
												$CRET_CONT_INP = "readonly";
												$CRET_CONT_SEL = "disabled";
											}
										}?>
										<form action="<?php echo $DESTINATION_PAGE;?>" method="post" name="main_form" id="main_form"  autocomplete="off">
											<input type="hidden" name="id_item_selected" id="id_item_selected" value="<?php echo $ID_ITEM_SELECTED;?>">
											<input type="hidden" name="mark_deleteitem" id="mark_deleteitem" value="0">
											<input type="hidden" name="mark_duplicateitem" id="mark_duplicateitem" value="0">
											<input type="hidden" name="mark_disableitem" id="mark_disableitem" value="0">
											<input type="hidden" name="bp_item_selected" id="bp_item_selected">
											<?php // Start - individual configuration	?>
											
											<div class="row">
												<div class="col-md-4">
													<div class="row">
														<div class="col-md">
															<label class="control-label"><u><?php echo $LANG_NAME;?>:</u></label>
															<input class="form-control input-sm" type="text" id="control_name" name="control_name" 
																   placeholder="<?php echo $LANG_NAME;?>" 
																   value ="<?php if(!empty($_SESSION['NAME_CONTROL'])){ echo $_SESSION['NAME_CONTROL'];} elseif(isset($ARRAYSELECTION)){
																	echo $ARRAYSELECTION['name'];} ?>" <?php echo ($CRET_CONT_INP); ?>
																   onkeydown="javascript:submitenter(event.keyCode);"/>
														</div>
													</div>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_DETAIL;?>:</label>
															<textarea class="form-control input-sm" rows="5" id="control_detail" name="control_detail" 
																	  placeholder="<?php echo $LANG_DESCRIPTION;?>" <?php echo ($CRET_CONT_INP);?>><?php if(!empty($_SESSION['DETAIL_CONTROL'])){ echo $_SESSION['DETAIL_CONTROL'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['detail'];} ?></textarea>
														</div>
													</div>
													<?php
													if(isset($ARRAYSELECTION)){
														$SQL = "SELECT p.name FROM tperson p, tprocess o WHERE o.id=".$ARRAYSELECTION['id_process']." AND ";
														$SQL .= "p.id = o.id_responsible";
														$RS = pg_query($conn, $SQL);
														$ARRAY = pg_fetch_array($RS);
														$RESPONSIBLE = $ARRAY['name'];

														$SQL = "SELECT p.name FROM tperson p, tprocess o WHERE o.id=".$ARRAYSELECTION['id_process']." AND ";
														$SQL .= "p.id = o.id_risk_responsible";
														$RS = pg_query($conn, $SQL);
														$ARRAY = pg_fetch_array($RS);
														$RISK_RESPONSIBLE = $ARRAY['name'];
													}?>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><u><?php echo $LANG_PROCESS;?>:</u></label>
															<select class="form-control" id="control_process" name="control_process" 
																	<?php echo ($CRET_CONT_SEL); ?> onclick="javascript:clear_responsibles();">
																<option></option>
																<?php
																$SQL = "SELECT id,name FROM tprocess WHERE id_area IN (SELECT id FROM tarea WHERE ";
																$SQL .= "id_instance = ".$_SESSION['INSTANCE_ID'].") ";
																$SQL .= "AND status = 'a' ORDER BY name";
																$RS = pg_query($conn, $SQL);
																$ARRAY = pg_fetch_array($RS);

																if(!empty($_SESSION['PROCESS'])){ $PARAMETER_SEL = $_SESSION['PROCESS'];}
																else{$PARAMETER_SEL = $ARRAYSELECTION['id_process'];}

																do{
																	if ($ARRAY['id'] == $PARAMETER_SEL) {
																		$sel = 'selected="selected"';
																	} else {
																		$sel = '';
																	}
																	echo '<option value="'.$ARRAY['id'].'" '.$sel.'>'.$ARRAY['name'].'</option>';
																}while($ARRAY = pg_fetch_array($RS)); ?>
															</select>
														</div>
													</div>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_RESPONSIBLE;?>:</label>
															<input class="form-control input-sm" type="text" id="control_responsible"
																   name="control_responsible" 
																   placeholder="<?php echo $LANG_AUTO_FILL;?>" 
																   value ="<?php if(isset($RESPONSIBLE)){echo $RESPONSIBLE;} ?>"
																   readonly />
														</div>
													</div>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_RISK_RESPOSIBLE;?>:</label>
															<input class="form-control input-sm" type="text" id="control_risk_responsible"
																   name="control_risk_responsible" 
																   placeholder="<?php echo $LANG_AUTO_FILL;?>" 
																   value ="<?php if(isset($RISK_RESPONSIBLE)){echo $RISK_RESPONSIBLE;} ?>"
																   readonly />
														</div>
													</div>
													<div class="row">
														<div class="col-md">
															<?php
															/*
															if(!empty($ARRAYSELECTION['status'])){
																if ($ARRAYSELECTION['status'] == 'd'){
																	$sel = 'checked="checked"';
																} else {
																	$sel = '';
																}
															} else {
																$sel = '';
															}
															echo '
															<input type="checkbox" name="control_status" id="control_status" value="d" '.$sel.' 
																   onclick="javascript:disableItemInForm('.$ID_ITEM_SELECTED.');">
															'.$LANG_DISABLE; */?>
															
														</div>
													</div>
												</div> <!-- End firt column-->
												
												
												<div class="col-md">
													<div class="row">
														<div class="col-md-6">
															<label class="control-label"><?php echo $LANG_IMPLEMENTATION;?>:</label>
														</div>
														<div class="col-md">
															<div class="input-group">
																<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																<input class="form-control input-sm" type="text" id="implementation_date"
																	   name="implementation_date" 
																	   placeholder="<?php echo $LANG_DATE_FORMAT_UPPERCASE;?>" 
																	   value ="<?php if(!empty($_SESSION['IMPLEMENTATION'])){ 
																			echo $_SESSION['IMPLEMENTATION'];}
																			   elseif(isset($ARRAYSELECTION)){
																				   echo $ARRAYSELECTION['implementation_date'];}?>"
																		<?php echo $CRET_CONT_INP;?> />
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-6">
															<label class="control-label"><?php echo $LANG_ENABLE_REVISION;?>:</label>
														</div>
														<div class="col-md">
															<?php
															if(!empty($_SESSION['ENA_REVISION'])){
																if ($_SESSION['ENA_REVISION'] == 'e'){
																	$sel = 'checked="checked"';
																} else {
																	$sel = '';
																}
															} elseif(!empty($ARRAYSELECTION['enable_revision'])){
																if ($ARRAYSELECTION['enable_revision'] == 'e'){
																	$sel = 'checked="checked"';
																} else {
																	$sel = '';
																}
															} else {
																$sel = '';
															}?>
															<input type="checkbox" name="enable_revision" id="enable_revision" value="e" <?php echo $sel;?> 
																  >
															<?php echo $LANG_ENABLE;?>
														</div>
													</div>
													<div class="hidden_field" id="box_conf_revision">
														<div class="row">
															<div class="col-md">
																<label class="control-label"><u><?php echo $LANG_GOAL;?>:</u></label>
																<div class="input-group">
																	<input class="form-control input-sm" type="number" id="goal" name="goal" 
																	   placeholder="<?php echo $LANG_GOAL;?>" min="0" max="100" step=".01"
																	   value ="<?php if(!empty($_SESSION['GOAL'])){ echo $_SESSION['GOAL'];}
																			elseif(isset($ARRAYSELECTION)){
																		echo $ARRAYSELECTION['goal'];} ?>" <?php echo ($CRET_CONT_INP); ?> />
																	<span class="input-group-addon"><i class="fa fa-percent"></i></span>
																</div>
															</div>
														</div>

														<div class="row">
															<div class="col-md">
																<label class="control-label"><?php echo $LANG_METRIC;?>:</label>
																<textarea class="form-control input-sm" rows="3" id="metric" name="metric" 
																		  placeholder="<?php echo $LANG_METRIC;?>" <?php echo ($CRET_CONT_INP);?>><?php if(!empty($_SESSION['METRIC'])){ echo $_SESSION['METRIC'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['metric'];} ?></textarea>
															</div>
														</div>
														<div class="row">
															<div class="col-md">
																<label class="control-label"><?php echo "$LANG_METRIC_DETAIL";?>:</label>
																<textarea class="form-control input-sm" rows="3" id="metric_detail" name="metric_detail" 
																		  placeholder="<?php echo $LANG_METRIC;?>" <?php echo ($CRET_CONT_INP);?>><?php if(!empty($_SESSION['METRIC_DETAIL'])){ echo $_SESSION['METRIC_DETAIL'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['metric_detail'];} ?></textarea>
															</div>
														</div>
														<div class="row">
															<div class="col-md">
																<button type="button" class="btn btn  btn-block"
																		onclick="javascript:showAnyItemHiddenBox(control_scheduling);">
																<i class="fa fa-calendar"></i> <?php echo "$LANG_SCHEDULING";?></button>
															</div>
														</div>
													</div>
													
													<!-- Scheduling-->
													<div class="col-md schedulling_control_box" id="control_scheduling">
														<div class="modal-header">
															<h5><?php echo $LANG_SCHEDULING;?>:</h5>
															<button type="button" class="close" onclick="javascript:closeJustifyItem(control_scheduling);">
																  <span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="row">
															<div class="col-md">
																<label class="control-label"><?php echo $LANG_APPLY_REVISION_FROM;?>:</label>
																<div class="input-group col-md-8">
																	<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																	<input class="form-control input-sm" type="text" id="apply_revision_from"
																	   name="apply_revision_from" 
																	   placeholder="<?php echo $LANG_APPLY_REVISION_FROM;?>" 
																	   value ="<?php if(!empty($_SESSION['APPLY_REVISION_FROM'])){ 
																			echo $_SESSION['APPLY_REVISION_FROM'];}
																			   elseif(isset($ARRAYSELECTION)){
																				   echo $ARRAYSELECTION['apply_revision_from'];}?>"
																		<?php echo $CRET_CONT_INP;?> />
																</div>
															</div>
														</div>
														<div class="row">
														<?php if(!empty($_SESSION['SCHEDULING_DAY'])){
																	$sc_day = $_SESSION['SCHEDULING_DAY'];}
																elseif(isset($ARRAYSELECTION['scheduling_day'])){
																	$sc_day = $ARRAYSELECTION['scheduling_day'];}
																else { $sc_day = "";}
														echo '
															<div class="col-md-4">
																<center><label class="control-label">'.$LANG_DAY.':</label></center>
																<select class="form-control" data-live-search="true" id="scheduling_day"
																		name="scheduling_day" '.$CRET_CONT_SEL.'>';
																foreach($CONF_DAYS as $key => $value){
																	if ($key == $sc_day) {
																		$sel = 'selected="selected"';
																	} else {
																		$sel = '';
																	}
																	if($value == 'all'){
																		$value = ${$value};
																	}
																	echo '<option value="'.$key.'" '.$sel.'>'.$value.'</option>';
																}
																echo '
																</select>

															</div>';
															if(!empty($_SESSION['SCHEDULING_MONTH'])){
																$sc_month = $_SESSION['SCHEDULING_MONTH'];}
															elseif(isset($ARRAYSELECTION['scheduling_month'])){
																$sc_month = $ARRAYSELECTION['scheduling_month'];}
															else { $sc_month = "";}
															echo '
															<div class="col-md-4">
																<center><label class="control-label">'.$LANG_MONTH.':</label></center>
																<select class="form-control" data-live-search="true" id="scheduling_month"
																		name="scheduling_month" '.$CRET_CONT_SEL.'>';
																foreach($CONF_MONTHS as $key => $value){
																	if ($key == $sc_month) {
																		$sel = 'selected="selected"';
																	} else {
																		$sel = '';
																	}
																	echo '<option value="'.$key.'" '.$sel.'>'.${$value}.'</option>';
																}
																echo '
																</select>
															</div>';
															if(!empty($_SESSION['SCHEDULING_WEEKDAY'])){
																$sc_weekday = $_SESSION['SCHEDULING_WEEKDAY'];}
															elseif(isset($ARRAYSELECTION['scheduling_weekday'])){
																$sc_weekday = $ARRAYSELECTION['scheduling_weekday'];}
															else { $sc_weekday = "";}
															echo '
															<div class="col-md-4">
																<center><label class="control-label">'.$LANG_WEEKDAY.':</label></center>
																<select class="form-control" data-live-search="true" id="scheduling_weekday"
																		name="scheduling_weekday" '.$CRET_CONT_SEL.'>';
																foreach($CONF_WEEKDAYS as $key => $value){
																	//Change key to be equals with Operation System sequence
																	$n_key = $key - 1;
																	if ($n_key == $sc_weekday) {
																		$sel = 'selected="selected"';
																	} else {
																		$sel = '';
																	}
																	echo '<option value="'.$n_key.'" '.$sel.'>'.${$value}.'</option>';
																}
																echo '
																</select>
															</div>';
															?>
														</div>
														<?php
														if(!empty($_SESSION['DEADLINE_REVISION'])){
															$sc_weekday = $_SESSION['DEADLINE_REVISION'];}
														elseif(isset($ARRAYSELECTION['deadline_revision'])){
															$sc_weekday = $ARRAYSELECTION['deadline_revision'];}
														else { $sc_weekday = "";}
														echo '
														<div class="row">
															<div class="col-md-6">
																<label class="control-label">'.$LANG_DEADLINE.':</label>
																<input class="form-control input-sm" type="text" id="deadline_revision"
																   name="deadline_revision" 
																   placeholder="'.$LANG_DEADLINE.'" 
																   value ="'; if(!empty($_SESSION['DEADLINE_REVISION'])){ 
																		echo $_SESSION['DEADLINE_REVISION'];}
																		   elseif(isset($ARRAYSELECTION['deadline_revision'])){
																			   echo $ARRAYSELECTION['deadline_revision'];} echo '"
																	'.$CRET_CONT_INP.' />
															</div>
														</div>';?>
													</div>
													
												</div> <!-- End second column-->
												
												<div class="col-md">
												<?php
													if(isset($ARRAYSELECTION)){
														echo '
													<div class="row">
														<div class="col-md-5">
															<label class="control-label"><u>'.$LANG_STATUS.':</u></label>';
															if(isset($ARRAYSELECTION)) {
																if($ARRAYSELECTION['status'] == 'a'){
																	$CLASS_DIV = "box_show_ok";
																} elseif($ARRAYSELECTION['status'] == 'n'){
																	$CLASS_DIV = "box_show_out";
																} elseif($ARRAYSELECTION['status'] == 'e'){
																	$CLASS_DIV = "box_show_out";
																} elseif($ARRAYSELECTION['status'] == 'r'){
																	$CLASS_DIV = "box_show_warning";
																} else {
																	$CLASS_DIV = "box_show_neutral";
																}
															} else {
																$CLASS_DIV = "box_show_neutral";
															}
																echo '
																<div class="'.$CLASS_DIV.'">
																	<center>'.${"C".$ARRAYSELECTION['status']}.'</center>
																</div>
														</div>

														<div class="col-md">
																<label class="control-label"><u>'.$LANG_CONTROL_NUM.':</u></label>
																	'.str_pad($ARRAYSELECTION['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'
														</div>
													</div>
													
													<!-- Start Action plan  -->
													
													<div class="row">
														<div class="col-md ">
															<div class="col-md">
																<button type="button" class="btn btn  btn-block" data-toggle="collapse" 
																	data-target="#show_action_plan">
																<i class="fa fa-fw fa-check-square-o"></i> '.$LANG_ACTION_PLAN.'</button>
															</div>
														
															<div class="collapse box_show_task_side_right" id="show_action_plan">
																<label class="control-label">'.$LANG_ACTION_PLAN.':</label>
																<div id="listRelatedTaskBox"></div>
																<div class="row">
																	<div class="col-md-4">
																		<button type="button" class="btn btn-default btn-block" data-toggle="tooltip" 
																		data-placement="top" title="'.$LANG_ADD.'"
																		onclick="javascript: addTaskRelated('.$ID_ITEM_SELECTED.',\'cont\');">
																		<i class="fa fa-plus"></i></button>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<script>
														showListTaskRelated('.$ID_ITEM_SELECTED.',\'cont\');
													</script>
													
													<!-- End Action plan 
													Start Compliance -->
													
													<div class="row">
														<div class="col-md ">
															<div class="col-md">
																<button type="button" class="btn btn  btn-block" data-toggle="collapse" 
																	data-target="#show_compliance">
																<i class="fa fa-check-square"></i> '.$LANG_COMPLIANCE.'</button>
															</div>
														
															<div class="collapse box_show_task_side_right" id="show_compliance">
																<label class="control-label">'.$LANG_COMPLIANCE.':</label>
																<div id="listRelatedComplianceBox"></div>
																<div class="row">
																	<div class="col-md-4">
																		<button type="button" class="btn btn-default btn-block" data-toggle="tooltip" 
																		data-placement="top" title="'.$LANG_ADD.'"
																		onclick="javascript: addComplianceRelated('.$ID_ITEM_SELECTED.');">
																		<i class="fa fa-edit"></i></button>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<script>
														showListComplianceRelated('.$ID_ITEM_SELECTED.');
													</script>
													
													<!-- End Compliance -->
													<!-- Start efficacy revision -->
													<div class="row">
														<div class="col-md ">
															<div class="col-md">
																<button type="button" class="btn btn  btn-block" data-toggle="collapse" 
																	data-target="#show_efficacy_revision">
																<i class="fa fa-search"></i> '.$LANG_EFFICACY_REVISION.'</button>
															</div>
														
															<div class="collapse box_show_task_side_right" id="show_efficacy_revision">
																<label class="control-label">'.$LANG_EFFICACY_REVISION.' (4 '.$LANG_LAST.'):</label>
																<div id="listEfficacyRevision"></div>
																<div class="row">
																	<div class="col-md-4">
																		<button type="button" class="btn btn-default btn-block" data-toggle="tooltip" 
																		data-placement="top" title="'.$LANG_SHOW_ALL.'"
																		onclick="javascript: showListEfficacyRev('.$ID_ITEM_SELECTED.',\'y\');">
																		<i class="fa fa-ellipsis-h"></i></button>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<script>
														showListEfficacyRev('.$ID_ITEM_SELECTED.',\'n\');
													</script>
													<!-- End efficacy revision -->
													';
													}?>
												</div>
											</div>
											
											
											<?php // End - individual configuration
											echo '
											<div class="panel panel-default">
												<div class="row">
													<div class="col-md-3">
														<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:postForm();">
														<i class="fa fa-plus-square-o"></i> '; 
														if(empty($ID_ITEM_SELECTED)){echo "$LANG_INSERT";} else {echo "$LANG_UPDATE";} echo '</button>
													</div>
													<div class="col-md-3">
														<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:clearForm();">
														<i class="fa fa-square-o"></i> '; 
														if(empty($ID_ITEM_SELECTED)){echo "$LANG_CLEAR";} else {echo "$LANG_UNSELECT";} echo '</button>
													</div>';
													if(isset($ARRAYSELECTION)) {
														echo '
													<div class="col-md-3">
														<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:deleteItem();">
														<i class="fa fa-minus-square-o"></i> '.$LANG_DELETE.'</button>
													</div>';
													} echo '
												</div>
											</div>';?>
										</form>
									<?php }?>
								</div>
							</div>
						</div>
					<!-- Item list -->
					<div class="card mb-3">
						<div class="card-header"></div>
						<div class="card-body">
							<div class="table-responsive">
								<form action="<?php echo $THIS_PAGE;?>" 
									  method="post" name="view_form_aux" id="view_form_aux"> 
									<input type="hidden" name="change_multi_sel" id="change_multi_sel" value="">
								</form>
								<form action="<?php echo $DESTINATION_PAGE_NEXT;?>" method="post" name="submit_to_net" id="submit_to_net"> 
									<input type="hidden" name="relateditem" id="relateditem" value="">
								</form>
								<form action="<?php if($_SESSION['STATUS_MULT_SEL'] == 0){ echo $THIS_PAGE;} else {echo $DESTINATION_PAGE;}?>" 
									  method="post" name="view_form" id="view_form">
									<input type="hidden" name="checkeditem" id="checkeditem" value="">
									<input type="hidden" name="mark_deleteitem_view_form" id="mark_deleteitem_view_form" value="0">
									<input type="hidden" name="mark_duplicateitem_view_form" id="mark_duplicateitem_view_form" value="0">
									<input type="hidden" name="mark_disableitem_view_form" id="mark_disableitem_view_form" value="0">
									<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
										<thead>
											<tr>
												<th><input type="checkbox" name="select_all_itens" id="select_all_itens"></th>
												<th><?php echo $LANG_No;?></th>
												<th><?php echo $LANG_NAME;?></th>
												<th><?php echo $LANG_RESPONSIBLE;?></th>
												<th><?php echo $LANG_PROCESS;?></th>
												<th><?php echo $LANG_RISK;?></th>
												<th><?php echo $LANG_STATUS;?></th>
											</tr>
										</thead>
										<?php
										// Start - individual configuration
										if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2) === false) &&
											(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3) === false)){
											echo '
											<tr class="odd gradeX">
												<th><input type="checkbox" name="select_all_itens" id="select_all_itens"></th>
												<th></th>
												<th>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
											</tr>';
										} else {
											// Select this page item
											if(!empty($ID_ITEM_RELATED)){
												$SQL_COMPL = " AND id_process = $ID_ITEM_RELATED ";
											} else {
												$SQL_COMPL = "";
											}
											
											// Verify permission if can read all or only own
											if(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3) !== false){
												$COMMPL_CONTROL = "";
											} else {
												$COMMPL_CONTROL = "AND r.id_process IN ";
												$COMMPL_CONTROL .= "(SELECT id FROM tprocess WHERE id_responsible = ".$_SESSION['user_id'];
												$COMMPL_CONTROL .= " OR id_risk_responsible = ".$_SESSION['user_id'].") ";
											}
											
											$SQL = "SELECT c.id, c.name, c.detail, o.name AS process, c.metric, c.goal, c.enable_revision, c.status, ";
											$SQL .= "c.implementation_date, p.name AS responsible FROM tcontrol c, tperson p, tprocess o ";
											$SQL .= "WHERE o.id = c.id_process AND c.id_process IN ";
											$SQL .= "(SELECT id FROM tprocess WHERE id_responsible = p.id) $SQL_COMPL $COMMPL_CONTROL AND ";
											$SQL .= "o.id_area IN(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID'].") ";
											$SQL .= "ORDER BY c.name ASC";
											$RS = pg_query($conn, $SQL);
											$ARRAY = pg_fetch_array($RS);
											if(pg_affected_rows($RS) == 0){?>
												<tr class="odd gradeX">
													<td><input type="checkbox" name="optcheckitem"></td>
													<td></td>
													<td><?php echo $LANG_NO_HAVE_DATE;?></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>
											<?php } else {
													do{
														$SQL = "SELECT id_risk FROM tarisk_control WHERE id_control = ".$ARRAY['id']; 
														$RSINSIDE = pg_query($conn, $SQL);
														$AMOUNT_RISK = pg_affected_rows($RSINSIDE);
														if(empty($AMOUNT_RISK)){
															$AMOUNT_RISK = 0;
														}
														
														if (!empty($ID_ITEM_SELECTED)){
															if($ARRAY['id'] == $ID_ITEM_SELECTED) {
																$sel = 'checked="checked"';
															} else {
																$sel = '';
															}
														} else {
															$sel = '';
														}
												?>
													<tr class="odd gradeX" id="item_<?php echo $ARRAY['id'];?>">
														<td data-id="<?php echo $ARRAY['id'];?>"><input type="checkbox" name="optcheckitem[]" id="optcheckitem[]" value="<?php echo $ARRAY['id']; ?>" <?php echo $sel; ?> ><a href="<?php echo 'javascript:selectTableItem('.$ARRAY['id'].')';?>"></a></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT);?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo substr($ARRAY['name'],0,50);?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo substr($ARRAY['responsible'],0,30);?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo substr($ARRAY['process'],0,30);?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo $AMOUNT_RISK;?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo ${"C".$ARRAY['status']};?></td>
													</tr>
														<?php
												} while($ARRAY = pg_fetch_array($RS));
											}
											// END - individual configuration
										}
										?>
									</table>
								</form>
								<?php printDeleteBox($LANG);?>
							</div>
						</div>
					</div>
					<div class="card-footer small text-muted"><?php echo ($LANG_SYSTEM_VERSION.": ".$CONF_VERSION);?></div>
				</div>	
			</div>
			
			<div class="modal fade" id="taskBoxRelated" tabindex="-1" role="dialog" aria-labelledby="task_boxLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content" id="panel_task"></div>
				</div>
			</div>
			
			<div class="modal fade" id="bp_box" tabindex="-1" role="dialog" aria-labelledby="bp_boxLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content" id="panel_related"></div>
				</div>
			</div>
			
			<!-- /.container-fluid -->
			<?php print_end_page_inside($LANG,$ID_ITEM_SELECTED);
			printDeleteTaskBox($LANG);
			if ((isset($_SESSION['ENA_REVISION']) && ($_SESSION['ENA_REVISION'] == 'e')) || 
				(isset($ARRAYSELECTION['enable_revision']) && ($ARRAYSELECTION['enable_revision'] == 'e'))){
				echo '<script>showConfRevision();</script>';
			}
			?>
		</body>
	</html>
<?php 
	destroySession(array('ID_SEL','NAME_CONTROL','DETAIL_CONTROL','NAME','DETAIL','PROBABILITY','JUSTIFY_PROB','PROCESS','IMPLEMENTATION', 'GENERAL_IMPACT','ACTION','PREVISION','CONNECTED_ITEM','ID_ITEM_FROM_TASK','GOAL','METRIC','METRIC_DETAIL','ENA_REVISION', 'APPLY_REVISION_FROM','SCHEDULING_DAY', 'SCHEDULING_MONTH','SCHEDULING_WEEKDAY','DEADLINE_REVISION'));
	$_SESSION['LAST_PAGE'] = ("/$CONF_DIRECTORY_NAME/module/risk/$THIS_PAGE");
}?>