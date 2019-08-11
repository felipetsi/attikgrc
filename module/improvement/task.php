<?php
session_start();
// Start - individual configuration
$_SESSION['LP'] = "../../"; //Level path
$CONF_DIRECTORY_NAME = "attikgrc";
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THIS_PAGE = "task.php";
$_SESSION['THIS_PAGE'] = $THIS_PAGE;
$DESTINATION_PAGE = "task_run.php";
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
	$PERMITIONS_NAME_1 = "create_task@";
	$PERMITIONS_NAME_2 = "read_own_task@";
	$PERMITIONS_NAME_3 = "read_all_task@";
	$PERMITIONS_NAME_4 = "approver_task@";
	$PERMITIONS_NAME_5 = "treatment_task@";
	
	// Verify if multi-select is enable
	if(isset($_POST['change_multi_sel'])&&($_POST['change_multi_sel'] == 1)){if($_SESSION['STATUS_MULT_SEL'] == 0){$_SESSION['STATUS_MULT_SEL'] = 1;}
																			 else {$_SESSION['STATUS_MULT_SEL'] = 0;}}
	else {$_SESSION['STATUS_MULT_SEL'] = 0;}
	// Verify if multi-select is enable
	
	if(!empty($_POST['checkeditem'])){
		$ID_ITEM_SELECTED = substr(trim(addslashes($_POST['checkeditem'])),0,50);
	} elseif(!empty($_POST['checkeditemtask'])){
		$ID_ITEM_SELECTED = substr(trim(addslashes($_POST['checkeditemtask'])),0,50);
	} elseif(isset($_SESSION['ID_SEL'])) {
		$ID_ITEM_SELECTED = $_SESSION['ID_SEL'];
	}
	
	if(!empty($ID_ITEM_SELECTED)){
		// Start - individual configuration
		$SQL = "SELECT t.id, t.name, t.detail, t.action, t.id_responsible, t.id_approver, id_creator, t.source, t.status, ";
		$SQL .= "TO_CHAR(t.prevision_date,'".$LANG_DATE_FORMAT_UPPERCASE."') AS prevision_date, ";
		$SQL .= "TO_CHAR(t.creation_date,'".$LANG_DATE_FORMAT_UPPERCASE."') AS creation_date, ";
		$SQL .= "TO_CHAR(t.execution_date,'".$LANG_DATE_FORMAT_UPPERCASE."') AS execution_date ";
		$SQL .= "FROM ttask_workflow t ";
		$SQL .= "WHERE t.id = $ID_ITEM_SELECTED ";
		$SQL .= "AND t.id_instance=".$_SESSION['INSTANCE_ID'];
		$RS = pg_query($conn, $SQL);
		$ARRAYSELECTION = pg_fetch_array($RS);
		// End - individual configuration
	} else {
		$ID_ITEM_SELECTED = '';
	}
	?>
	<!DOCTYPE html>
	<html lang="en">
		<?php print_general_head($LANG); 
	
		// Start - individual configuration
		echo '
		<script type="text/javascript">
			$(document).ready(function(){
				var date_input=$(\'input[name="prevision_time"]\'); //our date input has the name "date"
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
					<li><a id="m" href="#"><i class="fa fa-tasks"></i> <?php echo ${'LANG_MULTI_SELECT_'.$_SESSION['STATUS_MULT_SEL'].''};?></a></li>
				</ul>
			</div>
			<!-- Start - individual configuration - End submenu -->
			<div class="content-wrapper">
				<div class="container-fluid">
					<?php require_once($_SESSION['LP'].'include/sub_menu_improvement.php'); ?>
					<!-- edit item-->
					<div class="mb-0 mt-4"></div>
						<hr class="mt-2">
						<div class="card-header">
							<div class="card mb-3">
								<label class="control-label"><center><i><u><?php echo $LANG_TASKS;?></u></i></center></label>
								<div class="row">
									<div class="col-md-12">
										<button id="btn_collapse_panel" type="button" class="btn btn-default  btn-block" data-toggle="collapse" data-target="#editPanel"><i id="btn_collapse_panel_icon_up" class="fa fa-angle-double-down"></i></button>
									</div>
								</div>
								 	
								<div id= "editPanel" class="<?php if(isset($_SESSION['NAME'])){echo 'collapse_in';}else{echo 'collapse';}?>">
									<?php if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false &&
											 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2)) === false &&
											 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3)) === false &&
											 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_4)) === false){
										echo '<center>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</center>';
									} else {
										// Start variables
										$CRET_CONT_INP = "";
										$CRET_CONT_SEL = "";
										$CRET_CONT_INP_CRET = "";
										$CRET_CONT_SEL_CRET = "";
										$CRET_CONT_INP_RESP = "";
										$CRET_CONT_SEL_RESP = "";
										$CRET_CONT_SEL_APPR = "";
				
										if(isset($ARRAYSELECTION)){
											if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false ||
												($ARRAYSELECTION['status'] == 'f') || ($ARRAYSELECTION['status'] == 'c'))
											{
												$CRET_CONT_INP = "readonly";
												$CRET_CONT_SEL = "disabled";
												$CRET_CONT_SEL_APPR = "disabled";
											} else{
												if (($_SESSION['user_id'] != $ARRAYSELECTION['id_creator']) &&
														  ($_SESSION['user_id'] != $ARRAYSELECTION['id_approver'])){
													$CRET_CONT_INP_CRET = "readonly";
													$CRET_CONT_SEL_CRET = "disabled";
												} 
												if (($_SESSION['user_id'] != $ARRAYSELECTION['id_responsible']) ||
														  ((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_5)) === false ) &&
														  ($_SESSION['user_id'] != $ARRAYSELECTION['id_approver'])){
													$CRET_CONT_INP_RESP = "readonly";
													$CRET_CONT_SEL_RESP = "disabled";
												}
											if(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_4) === false){
													$CRET_CONT_SEL_APPR = "disabled";
												}
											}
										} else {
											if(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1) === false)
											{
												$CRET_CONT_INP = "readonly";
												$CRET_CONT_SEL = "disabled";
											}
											if(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_4) === false)
											{
												$CRET_CONT_SEL_APPR = "disabled";
											}
										}?>
										<form action="<?php echo $DESTINATION_PAGE;?>" method="post" name="main_form" id="main_form">
											<input type="hidden" name="id_item_selected" id="id_item_selected" value="<?php echo $ID_ITEM_SELECTED;?>">
											<input type="hidden" name="mark_deleteitem" id="mark_deleteitem" value="0">
											<input type="hidden" name="mark_duplicateitem" id="mark_duplicateitem" value="0">
											<input type="hidden" name="mark_disableitem" id="mark_disableitem" value="0">
											<input type="hidden" name="mark_finishitem" id="mark_finishitem" value="0">
											<?php // Start - individual configuration	?>
											
											<div class="row">
												<div class="col-md-4">
													<div class="row">
														<div class="col-md">
															<label class="control-label"><u><?php echo $LANG_NAME;?>:</u></label>
															<input class="form-control input-sm" type="text" id="name" name="name" 
																   placeholder="<?php echo $LANG_NAME;?>" 
																   value ="<?php if(!empty($_SESSION['NAME'])){ echo $_SESSION['NAME'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['name'];} ?>"
																   <?php echo ($CRET_CONT_INP.$CRET_CONT_INP_CRET); ?>
																   onkeydown="javascript:submitenter(event.keyCode);" />
														</div>
													</div>
													
													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_DETAIL;?>:</label>
															<textarea class="form-control input-sm" rows="5" id="detail" name="detail" 
																	  placeholder="<?php echo $LANG_DESCRIPTION;?>" <?php echo ($CRET_CONT_INP.$CRET_CONT_INP_CRET);?>><?php if(!empty($_SESSION['DETAIL'])){ echo $_SESSION['DETAIL'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['detail'];} ?></textarea>
														</div>
													</div>
													
													<div class="row">
														<div class="col-md">
															<label class="control-label"><u><?php echo $LANG_RESPONSIBLE;?>:</u></label>
															<select class="form-control" id="responsible" name="responsible" <?php echo $CRET_CONT_SEL.$CRET_CONT_SEL_CRET?>>
																<option></option>
																<?php
																$SQL = "SELECT id,name FROM tperson WHERE id_instance = ".$_SESSION['INSTANCE_ID']."  ";
																$SQL .= "AND status <> 'e' ORDER BY name";
																$RSPERSON = pg_query($conn, $SQL);
																$ARRAYPERSON = pg_fetch_array($RSPERSON);

																if(!empty($_SESSION['RESPONSIBLE'])){ $PARAMETER_SEL = $_SESSION['RESPONSIBLE'];}
																else{$PARAMETER_SEL = $ARRAYSELECTION['id_responsible'];}

																do{
																	if ($ARRAYPERSON['id'] == $PARAMETER_SEL) {
																		$sel = 'selected="selected"';
																	} else {
																		$sel = '';
																	}
																	echo '<option value="'.$ARRAYPERSON['id'].'" '.$sel.'>'.$ARRAYPERSON['name'].'</option>';
																}while($ARRAYPERSON = pg_fetch_array($RSPERSON)); ?>
															</select>
														</div>
													</div>
													
													<div class="row">
														<div class="col-md">
															<label class="control-label"><u><?php echo $LANG_APPROVER;?>:</u></label>
															<select class="form-control" id="approver" name="approver" <?php echo $CRET_CONT_SEL_APPR?> >
																<?php
																if(!empty($_SESSION['APPROVER'])){ $PARAMETER_SEL = $_SESSION['APPROVER'];}
																else {$PARAMETER_SEL = $ARRAYSELECTION['id_approver'];}

																pg_result_seek($RSPERSON, 0);
																reset($ARRAYPERSON);

																do{
																	if ($ARRAYPERSON['id'] == $PARAMETER_SEL) {
																		$sel = 'selected="selected"';
																	} else {
																		$sel = '';
																	}
																	echo '<option value="'.$ARRAYPERSON['id'].'" '.$sel.'>'.$ARRAYPERSON['name'].'</option>';

																}while($ARRAYPERSON = pg_fetch_array($RSPERSON)); ?>
															</select>
														</div>
													</div>
												</div>
												<div class="col-md-4">
													<div class="row">
														<div class="col-md">
															<?php
															if(isset($ARRAYSELECTION)){
																echo '
																<label class="control-label"><u>'.$LANG_TASKS_NUM.':</u></label> 
																		'.str_pad($ARRAYSELECTION['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT);
															}?>
														</div>
														<div class="col-md-5 alert-info text-center">
															<strong>
																<?php if(isset($ARRAYSELECTION)){echo ${$ARRAYSELECTION['status']};} ?>
															</strong>
														</div>
													</div>

													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_ACTION;?>:</label>
															<textarea class="form-control input-sm" rows="5" id="action" name="action" 
																	  placeholder="<?php echo $LANG_ACTION;?>" <?php echo $CRET_CONT_INP.$CRET_CONT_INP_RESP?>><?php if(!empty($_SESSION['ACTION'])){ echo $_SESSION['ACTION'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['action'];} ?></textarea>
														</div>
													</div>
													
													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_PREVISION_DATE;?>:</label>
															<div class="input-group">
																<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																<input class="form-control input-sm" type="text" id="prevision_time" name="prevision_time" 
																	   placeholder="<?php echo $LANG_DATE_FORMAT_UPPERCASE;?>" 
																	   value ="<?php if(!empty($_SESSION['PREVISION'])){ echo $_SESSION['PREVISION'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['prevision_date'];} ?>" <?php echo $CRET_CONT_INP.$CRET_CONT_INP_RESP?>/>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><u><?php echo $LANG_SOURCE;?>:</u></label>
															<select class="form-control" id="source" name="source" <?php echo $CRET_CONT_SEL.$CRET_CONT_SEL_CRET?>>
																<option></option>
																<?php
																if(!empty($_SESSION['SOURCE'])){ $PARAMETER_SEL = $_SESSION['SOURCE'];}
																else {$PARAMETER_SEL = $ARRAYSELECTION['source'];}
																foreach ($CONF_SOURCE_TASK as $item_op) {

																	if ($item_op == $PARAMETER_SEL) {
																		$sel = 'selected="selected"';
																	} else {
																		$sel = '';
																	}
																	echo '<option value="'.$item_op.'" '.$sel.'>'.${$item_op}.'</option>';
																}
																?>
															</select>
														</div>
													</div>
											
													<div class="col-md box_information">
														<div class="row">
															<label class="control-label"><u><?php echo $LANG_CREATOR;?></u>:</label> 
															<?php
																if(isset($ARRAYSELECTION)){
																	if(!empty($ARRAYSELECTION['id_creator'])){
																		$SQL = "SELECT name FROM tperson WHERE id = ".$ARRAYSELECTION['id_creator'];
																		$RSINSIDE = pg_query($conn, $SQL);
																		$ARRAYINSIDE = pg_fetch_array($RSINSIDE);
																		$CREATOR = $ARRAYINSIDE['name'];
																		echo $CREATOR; 
																	} else {echo $LANG_SYSTEM;}
																} else {echo $_SESSION['user_name'];}?>
														</div>
														<div class="row">
															<label class="control-label"><u><?php echo $LANG_CREATION_DATE;?></u>:</label> 
															<?php if(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['creation_date'];} ?>
														</div>
														<div class="row">
															<label class="control-label"><?php echo $LANG_EXECUTION_DATE;?>:</label>
															<?php if(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['execution_date'];} ?>
														</div>
													</div>
												</div>
												<div class="col-md">
													<?php
													if(isset($ARRAYSELECTION)){
														echo '
													<div class="row">
														<div class="col-md ">
															<div class="col-md">
																<button type="button" class="btn btn  btn-block" data-toggle="collapse" 
																	data-target="#show_risks">
																<i class="fa fa-exclamation-triangle"></i> '.$LANG_RISK.'</button>
															</div>
														
															<div class="collapse box_show_task_side_right" id="show_risks">
																<label class="control-label">'.$LANG_RISK.':</label>
																<div id="listRelationshipRiskBox"></div>
																<div class="row">
																	<div class="col-md-4">';
																		if(($ARRAYSELECTION['status'] != 'c') && ($ARRAYSELECTION['status'] != 'f')){
																				echo '
																		<button type="button" class="btn btn-default btn-block" data-toggle="tooltip" 
																		data-placement="top" title="'.$LANG_ADD.'"
																		onclick="javascript: addRiskRelated('.$ID_ITEM_SELECTED.',\'task\');">
																		<i class="fa fa-plus"></i></button>';} echo '
																	</div>
																</div>
															</div>
														</div>
													</div>
													<script>
														showListRisksRelationship('.$ID_ITEM_SELECTED.',\'task\');
													</script>
														
													<div class="row">
														<div class="col-md">
															<div class="col-md">
																<button type="button" class="btn btn  btn-block" data-toggle="collapse" 
																	data-target="#show_controls">
																<i class="fa fa-lock"></i> '.$LANG_CONTROL.'</button>
															</div>
														
															<div class="collapse box_show_task_side_right" id="show_controls">
																<label class="control-label">'.$LANG_CONTROL.':</label>
																<div id="listRelationshipControlBox"></div>
																<div class="row">
																	<div class="col-md-4">
																		<button type="button" class="btn btn-default btn-block" data-toggle="tooltip" 
																		data-placement="top" title="'.$LANG_ADD.'"
																		onclick="javascript: addControlRelated('.$ID_ITEM_SELECTED.',\'task\');">
																		<i class="fa fa-plus"></i></button>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<script>
														showListControlRelationship('.$ID_ITEM_SELECTED.',\'task\');
													</script>
													
													<div class="dropdown-divider"></div>
													<div class="row">
														<div class="col-md">
															<button type="button" class="btn btn  btn-block" data-toggle="collapse" 
																	data-target="#show_files">
																<i class="fa fa-archive"></i> '.$LANG_FILE.' </button>
															<div class="collapse box_show_task_side_right dropzone" id="show_files">
																<div id="fileZone"></div>
															</div>
														</div>
													</div>
													<script>
														showFileRelationship('.$ID_ITEM_SELECTED.',\'task\');
													</script>
													';
													}
													?>
												</div>
											</div>
											<?php // End - individual configuration	?>
											<div class="panel panel-default">
												<div class="row">
													<div class="col-md-3">
														<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:postForm();"><i class="fa fa-plus-square-o"></i> <?php if(empty($ID_ITEM_SELECTED)){echo "$LANG_INSERT";} else {echo "$LANG_UPDATE";}?></button>
													</div>
													<div class="col-md-3">
														<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:clearForm();"><i class="fa fa-square-o"></i> <?php if(empty($ID_ITEM_SELECTED)){echo "$LANG_CLEAR";} else {echo "$LANG_UNSELECT";}?></button>
													</div>
													<?php
													if(isset($ARRAYSELECTION)){
														echo '
														<div class="col-md-3">
															<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:deleteItem();">
															<i class="fa fa-minus-square-o"></i> '.$LANG_DELETE.'</button>
														</div>';
														
														if((empty($CRET_CONT_INP))||((($ARRAYSELECTION['status'] == 'c') || ($ARRAYSELECTION['status'] == 'f')) &&
																					  (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_4) !== false))){
														echo '
														<div class="col-md-3">
															<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:finishItemControl();">
															<i class="fa fa-check-square-o"></i>';
																if(isset($ARRAYSELECTION)) {
																	if(($ARRAYSELECTION['status'] == 'c') && 
																	   (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_4) !== false)){echo $LANG_REOPEN;}
																	elseif(($ARRAYSELECTION['status'] == 'f') && 
																		  (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_4) !== false)){echo $LANG_CLOSE;} 
																	else{echo $LANG_FINISH;}
																} else {
																	echo $LANG_FINISH;
																}
															echo '
															</button>
														</div>';
														}
													}?>
												</div>
											</div>
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
												<th><?php echo $LANG_APPROVER;?></th>
												<th><?php echo $LANG_CREATION_DATE;?></th>
												<th><?php echo $LANG_PREVISION_DATE;?></th>
												<th><?php echo $LANG_EXECUTION_DATE;?></th>
												<th><?php echo $LANG_STATUS;?></th>
											</tr>
										</thead>
										<?php
										// Start - individual configuration
										if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2) === false) &&
											(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3) === false) &&
										  	(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_4) === false)){
											echo '
											<tr class="odd gradeX">
												<th><input type="checkbox" name="select_all_itens" id="select_all_itens"></th>
												<th></th>
												<th>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
											</tr>';
										} else {
											if(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3) !== false){
												$COMMPL_CONTROL = "";
											} else {
												$COMMPL_CONTROL = "AND (t.id_responsible = ".$_SESSION['user_id']." OR ";
												$COMMPL_CONTROL .= "t.id_approver = ".$_SESSION['user_id']." OR ";
												$COMMPL_CONTROL .= "t.id_creator = ".$_SESSION['user_id'].")";
											}
											// Select this page item
											$SQL = "SELECT t.id, t.name, t.id_approver, t.source, t.status, t.id_responsible,  ";
											$SQL .= "TO_CHAR(t.prevision_date,'".$LANG_DATE_FORMAT_UPPERCASE."') AS prevision_date, ";
											$SQL .= "TO_CHAR(t.creation_date,'".$LANG_DATE_FORMAT_UPPERCASE."') AS creation_date, ";
											$SQL .= "TO_CHAR(t.execution_date,'".$LANG_DATE_FORMAT_UPPERCASE."') AS execution_date, ";
											$SQL .= "status ";
											$SQL .= "FROM ttask_workflow t ";
											$SQL .= "WHERE t.id_instance=".$_SESSION['INSTANCE_ID']." $COMMPL_CONTROL ";
											$SQL .= "ORDER BY t.id ASC";
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
													<td></td>
												</tr>
											<?php } else {
													do{
														if (!empty($ID_ITEM_SELECTED)){
															if($ARRAY['id'] == $ID_ITEM_SELECTED) {
																//$sel = 'selected="selected"';
																$sel = 'checked="checked"';
															} else {
																$sel = '';
															}
														} else {
															$sel = '';
														}

														$SQL = "SELECT name FROM tperson WHERE id = ".$ARRAY['id_approver'];
														$RSINSIDE = pg_query($conn, $SQL);
														$ARRAYINSIDE = pg_fetch_array($RSINSIDE);
														$APROVER = $ARRAYINSIDE['name'];
														$SQL = "SELECT name FROM tperson WHERE id = ".$ARRAY['id_responsible'];
														$RSINSIDE = pg_query($conn, $SQL);
														$ARRAYINSIDE = pg_fetch_array($RSINSIDE);
														$RESPONSIBLE = $ARRAYINSIDE['name'];
												?>
													<tr class="odd gradeX" id="item_<?php echo $ARRAY['id'];?>">
														<td data-id="<?php echo $ARRAY['id'];?>"><input type="checkbox" name="optcheckitem[]" id="optcheckitem[]" value="<?php echo $ARRAY['id']; ?>" <?php echo $sel; ?> ><a href="<?php echo 'javascript:selectTableItem('.$ARRAY['id'].')';?>"></a></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT);?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo substr($ARRAY['name'],0,50);?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo $RESPONSIBLE;?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo $APROVER;?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo $ARRAY['creation_date'];?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo $ARRAY['prevision_date'];?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo $ARRAY['execution_date'];?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo ${$ARRAY['status']};?></td>
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
			
			<div class="modal fade" id="showFrontModalRelated" data-backdrop="static">
				<div class="modal-dialog" role="document">
					<div class="modal-content" id="front_modal_panel">
					</div>
				</div>
			</div>
			
			<div class="modal fade" id="modalRelated" tabindex="-1" role="dialog" aria-labelledby="risk_boxLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content" id="panel_related">
					</div>
				</div>
			</div>
			
			<div class="modal fade" id="taskBoxRelated" tabindex="-1" role="dialog" aria-labelledby="task_boxLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content" id="panel_task">
					</div>
				</div>
			</div>
			
			<!-- /.container-fluid -->
			<?php print_end_page_inside($LANG,$ID_ITEM_SELECTED);?>
		</body>

	</html>
<?php 
	destroySession(array('ID_SEL','SOURCE','PREVISION','APPROVER','RESPONSIBLE','ACTION','DETAIL','NAME'));
	$_SESSION['LAST_PAGE'] = $THIS_PAGE;
} ?>