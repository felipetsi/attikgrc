<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	// Verify if multi-select is enable
	if(isset($_POST['change_multi_sel'])&&($_POST['change_multi_sel'] == 1)){if($_SESSION['STATUS_MULT_SEL'] == 0)
		{$_SESSION['STATUS_MULT_SEL'] = 1;}
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
	
	$DESTINATION_PAGE = ("risk_run.php");
	
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
	
	// Load the risk
	if(!empty($ID_ITEM_SELECTED)){
		// Start - individual configuration
		$SQL = "SELECT r.id, r.id_process, e.name AS responsible, r.name, r.detail, r.creation_time, r.risk_factor, r.risk_residual, r.status, ";
		$SQL .= "r.probability, r.probability_justification, r.impact ";
		$SQL .= "FROM trisk r, tprocess p, tperson e ";
		$SQL .= "WHERE p.id = r.id_process AND p.id_risk_responsible = e.id AND r.id = $ID_ITEM_SELECTED AND ";
		$SQL .= "id_process IN(SELECT id FROM tprocess WHERE id_area IN(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID']."))";
		$RS = pg_query($conn, $SQL);
		$ARRAYSELECTION = pg_fetch_array($RS);
		
		$SQL = "SELECT * FROM tarisk_impact WHERE id_risk = $ID_ITEM_SELECTED ";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		do{
			$IMPACT_SEL_VALUE[$ARRAY['id_impact']] = $ARRAY['value'];
			$JUSTIFICATION_IMPACT[$ARRAY['id_impact']] = $ARRAY['justification'];
		}while($ARRAY = pg_fetch_array($RS));
		
		// End - individual configuration
	} else {
		$ID_ITEM_SELECTED = '';
	}
	
	echo '
	<div class="modal-header">
		<h5 class="modal-title" id="task_boxLabel">'.$LANG_RISK.'</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="modal-body" >
	<div id= "editPanel" class="'; if(isset($_SESSION['NAME_RISK'])){echo 'collapse_in';}else{echo 'collapse';} echo '">';
		if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false &&
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
		
			echo '
			<form action="<?php echo $DESTINATION_PAGE;?>" method="post" name="main_form" id="main_form"  autocomplete="off">
				<input type="hidden" name="id_item_selected" id="id_item_selected" value="'.$ID_ITEM_SELECTED.'">
				<input type="hidden" name="mark_deleteitem" id="mark_deleteitem" value="0">
				<input type="hidden" name="mark_duplicateitem" id="mark_duplicateitem" value="0">
				<input type="hidden" name="mark_disableitem" id="mark_disableitem" value="0">

				<div class="row">
					<div class="col-md-4">
						<div class="row">
							<div class="col-md">
								<label class="control-label"><u><?php echo $LANG_NAME;?>:</u></label>
								<input class="form-control input-sm" type="text" id="risk_name" name="risk_name" 
									   placeholder="<?php echo $LANG_NAME;?>" 
									   value ="'; if(!empty($_SESSION['NAME_RISK'])){ echo $_SESSION['NAME_RISK'];} elseif(isset($ARRAYSELECTION)){
										echo $ARRAYSELECTION['name'];} echo '" '.$CRET_CONT_INP.'
										onkeydown="javascript:submitenter(event.keyCode);" />
							</div>
						</div>
						<div class="row">
							<div class="col-md">
								<label class="control-label">'.$LANG_DETAIL.':</label>
								<textarea class="form-control input-sm" rows="5" id="risk_detail" name="risk_detail" 
										  placeholder="'.$LANG_DESCRIPTION.'" '.$CRET_CONT_INP.'>';
										  if(!empty($_SESSION['DETAIL_RISK'])){ echo $_SESSION['DETAIL_RISK'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['detail'];} echo '</textarea>
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
					$SQL = "SELECT * FROM timpact WHERE id_instance =".$_SESSION['INSTANCE_ID'];
					$RS = pg_query($conn, $SQL);
					$ARRAY = pg_fetch_array($RS);
					$i=0;

					do{
						$i++;
						echo '
						<div class="row" onclick="javascript:showJustifyItem(justify_impact'.$i.');">
						<div class="col-md-5">
							<label class="control-label">'.${$ARRAY['name']}.':</label>
						</div>
						';
						if($ARRAY['name'] != 'financial'){
							echo '
							<div class="col-md">
								<select class="form-control" id="impact_'.$ARRAY['id'].'" name="impact_'.$ARRAY['id'].'" 
								'.$CRET_CONT_SEL.'>';
									if(!empty($_SESSION['IMPACT'.$ARRAY['id'].''])){ 
										$PARAMETER_SEL = $_SESSION['IMPACT'.$ARRAY['id'].''];}
									elseif(isset($IMPACT_SEL_VALUE[$ARRAY['id']])){
										$PARAMETER_SEL = $IMPACT_SEL_VALUE[$ARRAY['id']];
									}
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
							</div>';
						} else {
							echo '
							<div class="col-md">
								<div class="input-group">
								<span class="input-group-addon">'.$LANG_MONEY.'</span>
								<input class="form-control input-sm" type="text" id="impact_'.$ARRAY['id'].'" name="impact_'.$ARRAY['id'].'"  
								   placeholder = "'.$LANG_FINANCIAL_IMPACT.'" 
								   value ="';
										if(!empty($_SESSION['IMPACT'.$ARRAY['id'].''])){ echo $_SESSION['IMPACT'.$ARRAY['id'].''];}
										elseif(isset($IMPACT_SEL_VALUE[$ARRAY['id']])){ echo $IMPACT_SEL_VALUE[$ARRAY['id']];}
									echo '" '.$CRET_CONT_INP.' />
								</div>
							</div>';
						}
							echo '

								<div class="col-md justify_box" id="justify_impact'.$i.'">
									<div class="modal-header">
									<h5>'.$LANG_JUSTIFY.' - '.${$ARRAY['name']}.':</h5>
									<button type="button" class="close" onclick="javascript:closeJustifyItem(justify_impact'.$i.');">
									  <span aria-hidden="true">&times;</span>
									</button>
									</div>
									<textarea class="form-control input-sm" rows="4" id="justify_impact_text'.$ARRAY['id'].'"
									name="justify_impact_text'.$ARRAY['id'].'"  placeholder="'.$LANG_JUSTIFY.'"
									'.$CRET_CONT_INP.'>';if(!empty($_SESSION['JUSTIFY_IMPACT'.$ARRAY['id'].''])){ echo $_SESSION['JUSTIFY_IMPACT'.$ARRAY['id'].''];}elseif(isset($JUSTIFICATION_IMPACT[$ARRAY['id']])){echo $JUSTIFICATION_IMPACT[$ARRAY['id']];}echo '</textarea>
								</div>


						</div>';
						unset($_SESSION['IMPACT'.$ARRAY['id'].'']);
						unset($_SESSION['JUSTIFY_IMPACT'.$ARRAY['id'].'']);
					}while($ARRAY = pg_fetch_array($RS));

						echo '
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
								<label class="control-label"><?php echo $LANG_IMPACT;?>:</label>
								<textarea class="form-control input-sm" rows="2" id="desc_general_impact"
								name="desc_general_impact"  placeholder="<?php echo $LANG_IMPACT_GENERAL;?>"
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
				<div class="modal-footer">
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
					</div>
				</div>';?>
			</form>
		<?php
		 }?>
	</div>
	
	</div>
	<?php
	
}?>