<?php
session_start();
// Start - individual configuration
$_SESSION['LP'] = "../../"; //Level path
$CONF_DIRECTORY_NAME = "attikgrc";
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THIS_PAGE = "incident.php";
$_SESSION['THIS_PAGE'] = $THIS_PAGE;
$DESTINATION_PAGE = "incident_run.php";
// This session variable is used in box_task with 4 character
$_SESSION['PAGE_FROM'] = "icident";

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
	$PERMITIONS_NAME_1 = "create_incident@";
	$PERMITIONS_NAME_2 = "read_own_incident@";
	$PERMITIONS_NAME_3 = "read_all_incident@";
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
		$SQL = "SELECT i.id, i.id_person_register, i.id_responsible, i.name, i.detail, i.root_cause, ";
		$SQL .= "i.creation_date, i.execution_date, i.status, i.evidence ";
		$SQL .= "FROM tincident i ";
		$SQL .= "WHERE i.id = $ID_ITEM_SELECTED AND i.id_instance = ".$_SESSION['INSTANCE_ID'];
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
		echo '<script src="'.$_SESSION['LP'].'vendor/dropzone/dropzone.js"></script>
		<link rel="stylesheet" href="'.$_SESSION['LP'].'vendor/dropzone/dropzone.css">';
		// Start - individual configuration

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
			<?php 
			if(isset($ARRAYSELECTION)){
				if(($ARRAYSELECTION['status'] != 'c') && ($ARRAYSELECTION['status'] != 'f')){
					echo '
				<div id="box_submenu2" role="menu">
					<ul class="submenu2 dropdown-menu">
						<li><a id="e2"><i class="fa fa-edit"></i>'.$LANG_EDIT.'</a></li>
						<li><a id="i2"><i class="fa fa-plus-square-o"></i>'.$LANG_INSERT.'</a></li>
						<li><a id="d2"><i class="fa fa-minus-square-o"></i>'.$LANG_DELETE.'</a></li>
						<li><a id="u2"><i class="fa fa-clone"></i>'.$LANG_DUPLICATE.'</a></li>
					</ul>
				</div>';}
			}?>
			<!-- End - individual configuration - End submenu -->
				
			<div class="content-wrapper">
				<div class="container-fluid">
					<?php require_once($_SESSION['LP'].'include/sub_menu_improvement.php'); ?>
					<!-- edit item-->
					<div class="mb-0 mt-4"></div>
						<hr class="mt-2">
						<div class="card-header">
							<div class="card mb-3">
								<label class="control-label"><center><i><u><?php echo $LANG_SECURITY_INCIDENT;?></u></i></center></label>
								<div class="row">
									<div class="col-md-12">
										<button id="btn_collapse_panel" type="button" class="btn btn-default  btn-block" data-toggle="collapse" data-target="#editPanel"><i id="btn_collapse_panel_icon_up" class="fa fa-angle-double-down"></i></button>
									</div>
								</div>
								 	
								<div id= "editPanel" class="<?php if(isset($_SESSION['INCIDENT_NAME'])){echo 'collapse_in';}else{echo 'collapse';}?>">
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
											if(($ARRAYSELECTION['status'] == 'c') || ($ARRAYSELECTION['status'] == 'f'))
											{
												$CRET_CONT_INP = "readonly";
												$CRET_CONT_SEL = "disabled";
											}
										}
									
									$_SESSION['CRET_CONT_INP'] = $CRET_CONT_INP;
									$_SESSION['CRET_CONT_SEL'] = $CRET_CONT_SEL;
									?>
										<form action="<?php echo $DESTINATION_PAGE;?>" method="post" name="main_form" id="main_form"  autocomplete="off">
											<input type="hidden" name="id_item_selected" id="id_item_selected" value="<?php echo $ID_ITEM_SELECTED;?>">
											<input type="hidden" name="mark_deleteitem" id="mark_deleteitem" value="0">
											<input type="hidden" name="mark_duplicateitem" id="mark_duplicateitem" value="0">
											<?php // Start - individual configuration	?>
											
											<div class="row">
												<div class="col-md-4">
													<div class="row">
														<div class="col-md">
															<label class="control-label"><u><?php echo $LANG_NAME;?>:</u></label>
															<input class="form-control input-sm" type="text" id="incident_name" name="incident_name" 
																   placeholder="<?php echo $LANG_NAME;?>" 
																   value ="<?php if(!empty($_SESSION['INCIDENT_NAME'])){ echo $_SESSION['INCIDENT_NAME'];}
																	elseif(isset($ARRAYSELECTION)){ echo $ARRAYSELECTION['name'];} ?>" <?php echo ($CRET_CONT_INP); ?> 
																   onkeydown="javascript:submitenter(event.keyCode);" />
														</div>
													</div>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_DETAIL;?>:</label>
															<textarea class="form-control input-sm" rows="5" id="incident_detail" name="incident_detail" 
																	  placeholder="<?php echo $LANG_DESCRIPTION;?>" <?php echo ($CRET_CONT_INP);?>><?php if(!empty($_SESSION['INCIDENT_DETAIL'])){ echo $_SESSION['INCIDENT_DETAIL'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['detail'];} ?></textarea>
														</div>
													</div>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_RESPONSIBLE;?>:</label>
															<select class="form-control" id="incident_responsible" name="incident_responsible" 
																	<?php echo ($CRET_CONT_SEL);?>>
																<option></option>
																<?php
																$SQL = "SELECT id,name FROM tperson WHERE id_instance = ".$_SESSION['INSTANCE_ID']." ";
																$SQL .= "AND status = 'a' ORDER BY name";
																$RSPRATICES = pg_query($conn, $SQL);
																$ARRAYPRATICES = pg_fetch_array($RSPRATICES);

																if(!empty($_SESSION['INCIDENT_RESPONSIBLE'])){ $PARAMETER_SEL = $_SESSION['INCIDENT_RESPONSIBLE'];}
																else{$PARAMETER_SEL = $ARRAYSELECTION['id_responsible'];}

																do{
																	if ($ARRAYPRATICES['id'] == $PARAMETER_SEL) {
																		$sel = 'selected="selected"';
																	} else {
																		$sel = '';
																	}
																	echo '<option value="'.$ARRAYPRATICES['id'].'" '.$sel.'>'.$ARRAYPRATICES['name'].'</option>';
																}while($ARRAYPRATICES = pg_fetch_array($RSPRATICES)); ?>
															</select>
														</div>
													</div>
													
												</div> <!-- End firt column-->
												
												
												<div class="col-md">
													<div class="row">
														
													</div>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_EVIDENCE;?>:</label>
															<textarea class="form-control input-sm" rows="3" id="evidence" name="evidence" 
																	  placeholder="<?php echo $LANG_EVIDENCE;?>" <?php echo ($CRET_CONT_INP);?>><?php if(!empty($_SESSION['EVIDENCE'])){ echo $_SESSION['EVIDENCE'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['evidence'];} ?></textarea>
														</div>
													</div>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_ROOT_CAUSE;?>:</label>
															<textarea class="form-control input-sm" rows="5" id="root_cause" name="root_cause" 
																	  placeholder="<?php echo $LANG_ROOT_CAUSE;?>" <?php echo ($CRET_CONT_INP);?>><?php if(!empty($_SESSION['ROOT_CAUSE'])){ echo $_SESSION['ROOT_CAUSE'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['root_cause'];} ?></textarea>
														</div>
													</div>
													<div class="row">
														<div class="col-md box_information">
															<div class="row">
																<label class="control-label"><u> <?php echo $LANG_CREATOR;?></u>: </label> 
																<?php
																	if(isset($ARRAYSELECTION)){
																		if(!empty($ARRAYSELECTION['id_person_register'])){
																			$SQL = "SELECT name FROM tperson WHERE id = ".$ARRAYSELECTION['id_person_register'];
																			$RSINSIDE = pg_query($conn, $SQL);
																			$ARRAYINSIDE = pg_fetch_array($RSINSIDE);
																			$CREATOR = $ARRAYINSIDE['name'];
																			echo $CREATOR; 
																		} else {echo $LANG_SYSTEM;}
																	} else {echo $_SESSION['user_name'];}?>
															</div>
															<div class="row">
																<label class="control-label"><u> <?php echo $LANG_CREATION_DATE;?></u>: </label> 
																<?php if(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['creation_date'];} ?>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md">
												<?php
													if(isset($ARRAYSELECTION)){
														echo '
													<div class="row">
														<div class="col-md">
															<label class="control-label"><u>'.$LANG_SECURITY_INCIDENT_NUM.':</u></label>
																	'.str_pad($ARRAYSELECTION['id'], 8, "0", STR_PAD_LEFT).'
														</div>
													</div>
													
													<div class="row">
														<div class="col-md ">
															<div class="col-md alert-info text-center" id="box_show_status">';
																if(!empty($ARRAYSELECTION['status'])){echo ${$ARRAYSELECTION['status']};}  echo '
															</div>
														</div>
													</div>
													
													<div class="row">
														<div class="col-md ">
															<div class="col-md">
																<button type="button" class="btn btn  btn-block" data-toggle="collapse" 
																	data-target="#show_intial_response">
																<i class="fa fa-fire-extinguisher"></i> '.$LANG_IMMEDIATE_RESPONSE.'</button>
															</div>
														
															<div class="collapse box_show_task_side_right" id="show_intial_response">
																<label class="control-label">'.$LANG_IMMEDIATE_RESPONSE.':</label>
																<div id="listRelatedTaskBox"></div>
																<div class="row">
																	<div class="col-md-4">';
																		if(($ARRAYSELECTION['status'] != 'c') && ($ARRAYSELECTION['status'] != 'f')){
																			echo '
																		<button type="button" class="btn btn-default btn-block" data-toggle="tooltip" 
																		data-placement="top" title="'.$LANG_ADD.'"
																		onclick="javascript: addTaskRelated('.$ID_ITEM_SELECTED.',\'inci\',\'i\');">
																		<i class="fa fa-plus"></i></button>';} echo '
																	</div>
																</div>
															</div>
														</div>
													</div>
													<script>
														showListTaskRelated('.$ID_ITEM_SELECTED.',\'inci\',\'i\');
													</script>
													<div class="row">
														<div class="col-md ">
															<div class="col-md">
																<button type="button" class="btn btn  btn-block" data-toggle="collapse" 
																	data-target="#show_solution">
																<i class="fa fa-circle-o"></i> '.$LANG_SOLUTION.'</button>
															</div>
														
															<div class="collapse box_show_task_side_right" id="show_solution">
																<label class="control-label">'.$LANG_SOLUTION.':</label>
																<div id="listRelatedTaskBox2"></div>
																<div class="row">
																	<div class="col-md-4">';
																		if(($ARRAYSELECTION['status'] != 'c') && ($ARRAYSELECTION['status'] != 'f')){
																			echo '
																		<button type="button" class="btn btn-default btn-block" data-toggle="tooltip" 
																		data-placement="top" title="'.$LANG_ADD.'"
																		onclick="javascript: addTaskRelated('.$ID_ITEM_SELECTED.',\'inci\',\'s\');">
																		<i class="fa fa-plus"></i></button>';} echo '
																	</div>
																</div>
															</div>
														</div>
													</div>
													<script>
														showListTaskRelated('.$ID_ITEM_SELECTED.',\'inci\',\'s\');
													</script>
													<div class="dropdown-divider"></div>
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
																	<div class="col-md-4">
																		<button type="button" class="btn btn-default btn-block" data-toggle="tooltip" 
																		data-placement="top" title="'.$LANG_ADD.'"
																		onclick="javascript: addRiskRelated('.$ID_ITEM_SELECTED.',\'inci\');">
																		<i class="fa fa-plus"></i></button>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<script>
														showListRisksRelationship('.$ID_ITEM_SELECTED.',\'inci\');
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
														showFileRelationship('.$ID_ITEM_SELECTED.',\'inci\');
													</script>
													';
													}
													?>
													
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
												<th><?php echo $LANG_CREATION_DATE;?></th>
												<th><?php echo $LANG_EXECUTION_DATE;?></th>
												<th><?php echo $LANG_ACTION;?></th>
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
												$COMMPL_CONTROL = "AND i.id_responsible = ".$_SESSION['user_id'];
											}
											$SQL = "SELECT i.id, i.name, i.creation_date, i.execution_date, i.status ";
											$SQL .= "FROM tincident i WHERE i.id_instance = ".$_SESSION['INSTANCE_ID']." $COMMPL_CONTROL";
											$SQL .= " ORDER BY i.creation_date ASC"; 
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
														$SQL = "SELECT COUNT(id_task)  AS count FROM tainicident_response_task WHERE id_incident = ".$ARRAY['id'];
														$RSCOUNT = pg_query($conn, $SQL);
														$ARRAYCOUNT = pg_fetch_array($RSCOUNT);
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
												?>
													<tr class="odd gradeX" id="item_<?php echo $ARRAY['id'];?>">
														<td data-id="<?php echo $ARRAY['id'];?>"><input type="checkbox" name="optcheckitem[]" id="optcheckitem[]" value="<?php echo $ARRAY['id']; ?>" <?php echo $sel; ?> ><a href="<?php echo 'javascript:selectTableItem('.$ARRAY['id'].')';?>"></a></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT);?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo substr($ARRAY['name'],0,50);?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo substr($ARRAY['creation_date'],0,30);?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo substr($ARRAY['execution_date'],0,30);?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo $ARRAYCOUNT['count'];?></td>
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
			
			<div class="modal fade" id="taskBoxRelated" tabindex="-1" role="dialog" aria-labelledby="task_boxLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content" id="panel_task">
					</div>
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
			
			<!-- /.container-fluid -->
			<?php print_end_page_inside($LANG,$ID_ITEM_SELECTED);
			printDeleteTaskBox($LANG);?>
			
		</body>
	</html>
<?php 
	destroySession(array('ID_SEL','INCIDENT_NAME','INCIDENT_DETAIL','INCIDENT_RESPONSIBLE','ROOT_CAUSE','EVIDENCE', 'CRET_CONT_INP','CRET_CONT_SEL','ID_ITEM_FROM_TASK'));
	$_SESSION['LAST_PAGE'] = ("/$CONF_DIRECTORY_NAME/module/improvement/$THIS_PAGE");
}?>