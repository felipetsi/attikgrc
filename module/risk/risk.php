<?php
session_start();
// Start - individual configuration
$_SESSION['LP'] = "../../"; //Level path
$CONF_DIRECTORY_NAME = "attikgrc";
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THIS_PAGE = "risk.php";
$_SESSION['THIS_PAGE'] = $THIS_PAGE;
$DESTINATION_PAGE = "risk_run.php";
$DESTINATION_PAGE_NEXT = "control.php";
// This session variable is used in box_task with 4 character
$_SESSION['PAGE_FROM'] = "risk";

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
	$PERMITIONS_NAME_1 = "create_risk@";
	$PERMITIONS_NAME_2 = "read_own_risk@";
	$PERMITIONS_NAME_3 = "read_all_risk@";
	$PERMITIONS_NAME_5 = "treatment_risk@";
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
		$SQL = "SELECT r.id, r.id_process, e.name AS responsible, r.name, r.detail, r.creation_time, r.risk_factor, r.risk_residual, r.status, ";
		$SQL .= "r.probability, r.probability_justification, r.impact, r.id_impact_type, r.rlabel ";
		$SQL .= "FROM trisk r, tprocess p, tperson e ";
		$SQL .= "WHERE p.id = r.id_process AND p.id_risk_responsible = e.id AND r.id = $ID_ITEM_SELECTED AND ";
		$SQL .= "id_process IN(SELECT id FROM tprocess WHERE id_area IN(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID']."))";
		$RS = pg_query($conn, $SQL);
		$ARRAYSELECTION = pg_fetch_array($RS);
		// End - individual configuration
	} else {
		$ID_ITEM_SELECTED = '';
	}
	?>
	<!DOCTYPE html>
	<html lang="en">
		<?php echo '<script src="'.$_SESSION['LP'].'js/risk.js"></script>';
		print_general_head($LANG); 
	
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
						<div class="sumenu_divider"></div>
					<h6 class="dropdown-header"><?php echo $LANG_RISK_TREATMENT;?></h6>
					<li><a id="i" href="#"><i class="fa fa-level-down"></i> <?php echo $LANG_MITIGATE;?></a></li>
					<!--
					<li><a id="c" href="#"><i class="fa fa-check"></i> <?php echo $LANG_ACCEPT;?></a></li>
					<li><a id="v" href="#"><i class="fa fa-ban"></i> <?php echo $LANG_AVOID;?></a></li>
					<li><a id="t" href="#"><i class="fa fa-mail-forward"></i> <?php echo $LANG_TRANSFER;?></a></li>
					-->
				</ul>
			</div>
			<div id="box_submenu2" role="menu">
				<ul class="submenu2 dropdown-menu">
					<li><a id="e2"><i class="fa fa-edit"></i> <?php echo $LANG_EDIT;?></a></li>
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
							$SQL = "SELECT a.id AS id_area, a.name AS area, p.id AS id_process, p.name AS process FROM tarea a, tprocess p WHERE ";
							$SQL .= "p.id = $ID_ITEM_RELATED AND p.id_area = a.id";
							$RS = pg_query($conn, $SQL);
							$ARRAY = pg_fetch_array($RS);
							
							echo '
							<div class="row small">
								<div class="col-md">
									<a href="javascript:backToRelatedObj('.$ARRAY['id_area'].',\'area\');">'.substr($ARRAY['area'],0,30).'</a>
								</div>
								<div class="col-md-1 small"> <i class="fa fa-caret-right"></i></div>
								<div class="col-md">
									<a href="javascript:backToRelatedObj('.$ARRAY['id_process'].',\'proc\');">'.substr($ARRAY['process'],0,30).'</a>
								</div>
									<div class="col-md-1 small"> <i class="fa fa-caret-left"></i></div>
							</div>';
						}?>
						<div class="card-header">
							<div class="card mb-3">
								<label class="control-label"><center><i><u><?php echo $LANG_RISKS;?></u></i></center></label>
								<div class="row">
									<div class="col-md-12">
										<button id="btn_collapse_panel" type="button" class="btn btn-default  btn-block" data-toggle="collapse" data-target="#editPanel"><i id="btn_collapse_panel_icon_up" class="fa fa-angle-double-down"></i></button>
									</div>
								</div>
								
								<div id= "editPanel" class="<?php if(isset($_SESSION['NAME_RISK'])){echo 'collapse_in';}else{echo 'collapse';}?>">
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
										}
									
									$_SESSION['CRET_CONT_INP'] = $CRET_CONT_INP;
									$_SESSION['CRET_CONT_SEL'] = $CRET_CONT_SEL;
									?>
										<form action="<?php echo $DESTINATION_PAGE;?>" method="post" name="main_form" id="main_form"  autocomplete="off">
											<input type="hidden" name="id_item_selected" id="id_item_selected" value="<?php echo $ID_ITEM_SELECTED;?>">
											<input type="hidden" name="mark_deleteitem" id="mark_deleteitem" value="0">
											<input type="hidden" name="mark_duplicateitem" id="mark_duplicateitem" value="0">
											<input type="hidden" name="mark_disableitem" id="mark_disableitem" value="0">
											<?php // Start - individual configuration	?>
											
											<div class="row">
												<div class="col-md-4">
													<div class="row">
														<div class="col-md">
															<label class="control-label"><u><?php echo $LANG_NAME;?>:</u></label>
															<input class="form-control input-sm" type="text" id="risk_name" name="risk_name" 
																   placeholder="<?php echo $LANG_NAME;?>" 
																   value ="<?php if(!empty($_SESSION['NAME_RISK'])){ echo $_SESSION['NAME_RISK'];} elseif(isset($ARRAYSELECTION)){
																	echo $ARRAYSELECTION['name'];} ?>" <?php echo ($CRET_CONT_INP); ?>
																    onkeydown="javascript:submitenter(event.keyCode);" />
														</div>
													</div>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_DETAIL;?>:</label>
															<textarea class="form-control input-sm" rows="5" id="risk_detail" name="risk_detail" 
																	  placeholder="<?php echo $LANG_DESCRIPTION;?>" <?php echo ($CRET_CONT_INP);?>><?php if(!empty($_SESSION['DETAIL_RISK'])){ echo $_SESSION['DETAIL_RISK'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['detail'];} ?></textarea>
														</div>
													</div>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_RESPONSIBLE;?>:</label>
															<input class="form-control input-sm" type="text" id="risk_responsible" name="risk_responsible" 
																   placeholder="<?php echo $LANG_RESPONSIBLE;?>" 
																   value ="<?php if(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['responsible'];} ?>"
																   readonly />
														</div>
													</div>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><u><?php echo $LANG_PROCESS;?>:</u></label>
															<select class="form-control" id="risk_process" name="risk_process" 
																	<?php if(!empty($_SESSION['ID_ITEM_RELATED'])){echo 'readonly';} else { echo ($CRET_CONT_SEL); }?>>
																<option></option>
																<?php
																$SQL = "SELECT id,name FROM tprocess WHERE id_area IN (SELECT id FROM tarea WHERE ";
																$SQL .= "id_instance = ".$_SESSION['INSTANCE_ID'].") ";
																$SQL .= "AND status = 'a' ORDER BY name";
																$RS = pg_query($conn, $SQL);
																$ARRAY = pg_fetch_array($RS);

																if(!empty($_SESSION['ID_ITEM_RELATED'])){ $PARAMETER_SEL = $_SESSION['ID_ITEM_RELATED'];}
																elseif(!empty($_SESSION['PROCESS'])){ $PARAMETER_SEL = $_SESSION['PROCESS'];}
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
															<label class="control-label"><?php echo $LANG_LABEL;?>:</label>
															<select class="form-control" id="label" name="label" <?php echo $CRET_CONT_SEL?>>
																<option></option>
																<?php
																if(!empty($_SESSION['LABEL'])){ $PARAMETER_SEL = $_SESSION['LABEL'];}
																else{$PARAMETER_SEL = $ARRAYSELECTION['rlabel'];}

																foreach ($CONF_RISK_LABEL as $item_op) {
																	if ($item_op == $PARAMETER_SEL) {
																		$sel = 'selected="selected"';
																	} else {
																		$sel = '';
																	}
																	echo '<option value="'.$item_op.'" '.$sel.'>'.${"LB".$item_op}.'</option>';
																}
																?>
															</select>
														</div>
													</div>
													
												</div> <!-- End firt column-->
												
												
												<div class="col-md">
													<div class="row">
														<div class="col-md-5">
															<label class="control-label"><u><?php echo $LANG_RF;?>:</u></label>
															<?php 
															if(isset($ARRAYSELECTION)) {
																if(empty($ARRAYSELECTION['risk_factor'])){
																	$CLASS_DIV = "box_show_neutral";
																} elseif($ARRAYSELECTION['risk_factor'] <= $_SESSION['ac_risk_level']){
																	$CLASS_DIV = "box_show_ok";
																} else {
																	$CLASS_DIV = "box_show_out";
																}
																$RF = $ARRAYSELECTION['risk_factor'];
															} else {
																$CLASS_DIV = "box_show_neutral";
																$RF = "0.00";
															}
																echo '
																<div class="'.$CLASS_DIV.'">
																	<center>'.$RF.'</center>
																</div>'; ?>
														</div>
														<div class="col-md-5">
															<label class="control-label"><u><?php echo $LANG_RR;?>:</u></label>
															<?php 
															if(isset($ARRAYSELECTION)) {
																if(empty($ARRAYSELECTION['risk_residual'])){
																	$CLASS_DIV = "box_show_neutral";
																} elseif($ARRAYSELECTION['risk_residual'] <= $_SESSION['ac_risk_level']){
																	$CLASS_DIV = "box_show_ok";
																} else {
																	$CLASS_DIV = "box_show_out";
																}
																$RF = $ARRAYSELECTION['risk_residual'];
															} else {
																$CLASS_DIV = "box_show_neutral";
																$RF = "0.00";
															}
																echo '
																<div class="'.$CLASS_DIV.'">
																	'.$RF.'
																</div>'; ?>
														</div>
													</div>
													<?php
														echo '
													<div class="row">	
														<div class="col-md-5">
															<label class="control-label">'.$LANG_IMPACT_TYPE.':</label>
														</div>
														<div class="col-md">
															<select class="form-control" id="impact_type" name="impact_type" 		
																	onchange="javascript: showImpactRelatedAj('.$CRET_CONT_SEL.');" 
																	'.$CRET_CONT_SEL.'>';
																
															if(empty($ID_ITEM_SELECTED)){
																$SQL = "SELECT id,name FROM timpact_type WHERE status != 'd' AND ";
																$SQL .= "id_instance = ".$_SESSION['INSTANCE_ID'];
																$RS = pg_query($conn, $SQL);
																$ARRAY = pg_fetch_array($RS);
																do{
																	if($_SESSION['impact_default'] == $ARRAY['id']){ 
																		$sel = 'selected="selected"';
																	} else {
																		$sel = '';
																	}
																	echo '
																	<option value="'.$ARRAY['id'].'" 
																	'.$sel.'>'.${$ARRAY['name']}.'</option>';
																}while($ARRAY = pg_fetch_array($RS));
															} else {
																$SQL = "SELECT id,name FROM timpact_type WHERE id = ".$ARRAYSELECTION['id_impact_type'];
																$SQL .= " AND id_instance = ".$_SESSION['INSTANCE_ID'];
																$RS = pg_query($conn, $SQL);
																$ARRAY = pg_fetch_array($RS);
																$sel = 'selected="selected"';
																echo '
																<option value="'.$ARRAY['id'].'" 
																'.$sel.'>'.${$ARRAY['name']}.'</option>';
															}
																echo '
															</select>
															<script>
																javascript:
																showImpactRelatedAj('.$CRET_CONT_SEL.');
															</script>
														</div>
													</div>
													
													<div id="impact_related"></div>
													
													<div class="row" onclick="javascript:showJustifyItem(justify_probability);">
														<div class="col-md-5 divisor_div">
															<label class="control-label">
															'.$LANG_PROBABILITY.':
															</label>
														</div>
														<div class="col-md divisor_div">
															<select class="form-control" id="probability" name="probability" 
															'.$CRET_CONT_SEL.'>';
																if(!empty($_SESSION['PROBABILITY'])){ $PARAMETER_SEL = $_SESSION['PROBABILITY'];}
																else{$PARAMETER_SEL = $ARRAYSELECTION['probability'];}
																foreach ($CONF_IMPACT_LEVELS as $item_op) {
																$temp_array = explode("@",$item_op);
																	if ($temp_array[0] == $PARAMETER_SEL) {
																		$sel = 'selected="selected"';
																	} else {
																		$sel = '';
																	}
																	echo '
																	<option value="'.$temp_array[0].'" '.$sel.'>'.${$temp_array[1]}.'</option>';
																}
															echo '
															</select>
														</div>
														
															<div class="col-md justify_box" id="justify_probability">
																<div class="modal-header">
																<h5>'.$LANG_JUSTIFY.' - '.$LANG_PROBABILITY.':</h5>
																<button type="button" class="close" 
																onclick="javascript:closeJustifyItem(justify_probability);">
																  <span aria-hidden="true">&times;</span>
																</button>
																</div>
																<textarea class="form-control input-sm" rows="4" id="justify_probability_text"
																name="justify_probability_text'.$ARRAY['id'].'"  placeholder="'.$LANG_JUSTIFY.'"
																'.$CRET_CONT_INP.'>';if(!empty($_SESSION['JUSTIFY_PROB'])){ echo $_SESSION['JUSTIFY_PROB'];}
																elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['probability_justification'];}echo '</textarea>
															</div>
														
													</div>';?>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_IMPACT_GENERAL;?>:</label>
															<textarea class="form-control input-sm" rows="2" id="desc_general_impact"
															name="desc_general_impact"  placeholder="<?php echo $LANG_IMPACT;?>"
															<?php echo $CRET_CONT_INP;?>><?php if(!empty($_SESSION['GENERAL_IMPACT'])){ echo $_SESSION['GENERAL_IMPACT'];}
															elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['impact'];}?></textarea>
														</div>
													</div>
												</div>
												<div class="col-md">
												<?php
													if(isset($ARRAYSELECTION)){
														echo '
													<div class="row">
														<div class="col-md">
															<label class="control-label"><u>'.$LANG_RISK_NUM.':</u></label>
																	'.str_pad($ARRAYSELECTION['id'], 8, "0", STR_PAD_LEFT).'
														</div>
													</div>
													<div class="row">
														<div class="col-md ">
															<div class="col-md">
																<button type="button" class="btn btn  btn-block" data-toggle="collapse" 
																	data-target="#show_risk_treatment">
																<i class="fa fa-certificate"></i> '.$LANG_RISK_TREATMENT.'</button>
															</div>
															<div class="collapse" id="show_risk_treatment">
																<center>
																	<button type="button" class="btn-secondary btn" data-toggle="tooltip" 
																	data-placement="top" title="'.$LANG_MITIGATE.'"
																	onclick="javascript:mitigateRisk('.$ID_ITEM_SELECTED.');">
																		<i class="fa fa-level-down"></i>
																	</button>
																	<button type="button" class="btn-secondary btn" data-toggle="tooltip" 
																	data-placement="top" title="'.$LANG_ACCEPT.'"
																	onclick="javascript:confirmRiskTreatment('.$ID_ITEM_SELECTED.',\'c\');">
																		<i class="fa fa-check"></i>
																	</button>
																	<button type="button" class="btn-secondary btn" data-toggle="tooltip" 
																	data-placement="top" title="'.$LANG_AVOID.'"
																	onclick="javascript:confirmRiskTreatment('.$ID_ITEM_SELECTED.',\'v\');">
																		<i class="fa fa-ban"></i>
																	</button>
																	<button type="button" class="btn-secondary btn" data-toggle="tooltip" 
																	data-placement="top" title="'.$LANG_TRANSFER.'"
																	onclick="javascript:confirmRiskTreatment('.$ID_ITEM_SELECTED.',\'t\');">
																		<i class="fa fa-mail-forward"></i>
																	</button>
																	<button type="button" class="btn-secondary btn" data-toggle="tooltip" 
																	data-placement="top" title="'.$LANG_ELIMINATE_SOURCE.'"
																	onclick="javascript:confirmRiskTreatment('.$ID_ITEM_SELECTED.',\'e\');">
																		<i class="fa fa-remove"></i>
																	</button>
																</center>
															</div>
														</div>
													</div>
													
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
																		onclick="javascript: addTaskRelated('.$ID_ITEM_SELECTED.',\'risk\');">
																		<i class="fa fa-plus"></i></button>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<script>
														showListTaskRelated('.$ID_ITEM_SELECTED.',\'risk\');
													</script>';
													}
													echo '
													<div id="show_justify_box"></div>';
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
					<div class="card mb-3" id="show_risk_list">
						<script>
							showListRisk();
						</script>
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
			
			<div class="modal fade" id="addMitControl" data-backdrop="static">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="bp_boxLabel"><?php echo $LANG_CONTROL;?></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="row boxInModalScroll" id="show_controls"></div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $LANG_CANCEL;?></button>
							<a class="btn btn-primary" href="javascript:associateContRisk();"><?php echo $LANG_INSERT;?></a>
						</div>
					</div>
				</div>
			</div>
			
			<div class="modal fade" id="mitigate_box" tabindex="-1" role="dialog" aria-labelledby="mitigate_boxLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="mitigate_boxLabel"><?php echo ($LANG_RISK_TREATMENT." - ".$LANG_MITIGATE);?></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="col-md">
								<button id="btn_collapse_panel_detail" type="button" class="btn btn-default  btn-block" data-toggle="collapse" 
										data-target="#panel_modal_detail">
									<i id="btn_collapse_panel_icon_down" class="fa fa-toggle-down"></i> 
									<i id="btn_collapse_panel_icon_up" class="fa fa-toggle-up"></i>
								</button>
							</div>
							<div class="row">
								<div id="panel_modal_detail" class="col-md-7 collapse in"></div>
								<div class="col-md boxSmallInModalScroll" id="showListMitControl"></div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" id="btn_remove_control" onclick="javascript:dissociateContRisk();"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-secondary" id="btn_add_control" onclick="javascript:controlLoad();"><i class="fa fa-plus"></i></button>
							<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $LANG_CANCEL;?></button>
						</div>
					</div>
				</div>
			</div>

			<div id="confirmTreatBox" class="modal fade" role="dialog" aria-labelledby="confirmTreatBox" aria-hidden="true">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title" id="confirmTreatBox"><?php echo $LANG_CONFIRM_TREAT;?></h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<b><?php echo $LANG_TEXT_CONFIRM_TREAT?>:</b>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $LANG_NO;?></button>
							<button type="button" class="btn btn-danger"  id="confirm" onclick="javascript:riskTreatment();"><?php echo $LANG_YES;?></button>
						</div>
					</div>

				</div>
			</div>
			<!-- /.container-fluid -->
			<?php print_end_page_inside($LANG,$ID_ITEM_SELECTED);
			printDeleteTaskBox($LANG);
			printDeleteBox($LANG);?>
			
		</body>
	</html>
<?php 
	destroySession(array('ID_SEL','NAME_RISK','DETAIL_RISK','PROBABILITY','JUSTIFY_PROB','PROCESS','GENERAL_IMPACT','CRET_CONT_INP', 'CRET_CONT_SEL','ID_ITEM_FROM_TASK','LABEL'));
	$_SESSION['LAST_PAGE'] = ("/$CONF_DIRECTORY_NAME/module/risk/$THIS_PAGE");
}?>