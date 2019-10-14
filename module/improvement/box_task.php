<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	$ID_TASK = trim(addslashes($_POST['id_task']));
	$ID_RELATED_ITEM = trim(addslashes($_POST['id_source'])); // ID Risk or Control
	$SOURCE = trim(addslashes($_POST['source'])); // Risk or Control
	if(isset($_POST['response_type'])){$RESP_TYPE = trim(addslashes($_POST['response_type']));} else {$RESP_TYPE = "";}// Incident and Nonconformity
	
	$DESTINATION_PAGE = ("task_run.php");
	
	$PERMITIONS_NAME_1 = "create_task@";
	$PERMITIONS_NAME_2 = "read_own_task@";
	$PERMITIONS_NAME_3 = "read_all_task@";
	$PERMITIONS_NAME_4 = "approver_task@";
	$PERMITIONS_NAME_5 = "treatment_task@";
	// Risk permition
	$PERMITIONS_NAME_6 = "create_risk@";
	$PERMITIONS_NAME_7 = "read_all_risk@";
	$PERMITIONS_NAME_8 = "treatment_risk@";

	// Load the risk related with this task
	$LIST_ITEM_CONNECTED = "";
	if($SOURCE == "risk"){
		$SQL = "SELECT id_risk FROM tarisk_task WHERE id_task = $ID_TASK";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		do{
			$LIST_ITEM_CONNECTED .= $ARRAY['id_risk'].",";
		} while($ARRAY = pg_fetch_array($RS));
	} elseif($SOURCE == "cont"){
		$SQL = "SELECT id_control FROM tacontrol_task WHERE id_task = $ID_TASK";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		do{
			$LIST_ITEM_CONNECTED .= $ARRAY['id_control'].",";
		} while($ARRAY = pg_fetch_array($RS));
	} elseif($SOURCE == "inci"){
		$SQL = "SELECT id_incident FROM tainicident_response_task WHERE id_task = $ID_TASK";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		do{
			$LIST_ITEM_CONNECTED .= $ARRAY['id_incident'].",";
		} while($ARRAY = pg_fetch_array($RS));
	} elseif($SOURCE == "nonc"){
		$SQL = "SELECT id_nonconformity FROM tanonconformity_response_task WHERE id_task = $ID_TASK";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		do{
			$LIST_ITEM_CONNECTED .= $ARRAY['id_nonconformity'].",";
		} while($ARRAY = pg_fetch_array($RS));
	} elseif($SOURCE == "proj"){
		$SQL = "SELECT item FROM tcontrol_best_pratice WHERE id IN ";
		$SQL .= "(SELECT id_control_best_pratice FROM taproject_control_best_pratice_task WHERE id_task = $ID_TASK)";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		do{
			$LIST_ITEM_CONNECTED .= $ARRAY['item'].",";
		} while($ARRAY = pg_fetch_array($RS));
	}
	
	$LIST_ITEM_CONNECTED = substr($LIST_ITEM_CONNECTED,0,(strlen($LIST_ITEM_CONNECTED)-1));
	
	if((!empty($ID_TASK)) && (!empty($ID_RELATED_ITEM))) {
		$SQL = "SELECT t.id, t.name, t.detail, t.action, t.id_responsible, t.id_creator, t.status, ";
		$SQL .= "TO_CHAR(t.prevision_date,'".$LANG_DATE_FORMAT_UPPERCASE."') AS prevision_date ";
		$SQL .= "FROM ttask_workflow t ";
		$SQL .= "WHERE t.id = $ID_TASK ";
		$SQL .= "AND t.id_instance=".$_SESSION['INSTANCE_ID'];
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
	}
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
	</script>
	
	<div class="modal-header">
		<h5 class="modal-title" id="task_boxLabel">'.$LANG_TASKS.'</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="modal-body">
		<div id="row">
			<div class="col-md">
				<div class="card mb-3"> 	
					<div class="panel panel-default">';
						if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false &&
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

						if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1) === false) ||
								(($ARRAY['status'] == 'f') || ($ARRAY['status'] == 'c')))
						{
							$CRET_CONT_INP = "readonly";
							$CRET_CONT_SEL = "disabled";
							$CRET_CONT_SEL_APPR = "disabled";
						}
						if(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_4) === false)
						{
							$CRET_CONT_SEL_APPR = "disabled";
						}
						echo '
						<form action="" method="post" name="task_form" id="task_form" autocomplete="off">
							<input type="hidden" name="id_task_selected" id="id_task_selected" value="'.$ID_TASK.'">';
							if($SOURCE == "risk"){
								echo '
							<input type="hidden" name="id_risk_selected" id="id_risk_selected" value="'.$ID_RELATED_ITEM.'">';
							}elseif($SOURCE == "cont"){
								echo '
							<input type="hidden" name="id_control_mech_selected" id="id_control_mech_selected" value="'.$ID_RELATED_ITEM.'">';
							}elseif($SOURCE == "inci"){
								echo '
							<input type="hidden" name="id_incident_selected" id="id_incident_selected" value="'.$ID_RELATED_ITEM.'">
							<input type="hidden" name="response" id="response" value="'.$RESP_TYPE.'">';
							}elseif($SOURCE == "nonc"){
								echo '
							<input type="hidden" name="id_nonconformity_selected" id="id_nonconformity_selected" value="'.$ID_RELATED_ITEM.'">
							<input type="hidden" name="response" id="response" value="'.$RESP_TYPE.'">';
							}elseif($SOURCE == "proj"){
								echo '
							<input type="hidden" name="id_project_selected" id="id_project_selected" value="'.$ID_RELATED_ITEM.'">
							<input type="hidden" name="id_control_selected" id="id_control_selected" value="'.$RESP_TYPE.'">';
							}
							echo '
							<input type="hidden" name="mark_deletetask" id="mark_deletetask" value="0">
							<input type="hidden" name="mark_duplicatetask" id="mark_duplicatetask" value="0">
							<input type="hidden" name="mark_finishitem" id="mark_finishitem" value="0">

							<div class="row">
								<div class="col-md-5">
									<label class="control-label" id="la_name"><u>'.$LANG_NAME.':</u></label>
									<input class="form-control input-sm" type="text" id="name" name="name" 
										   placeholder="'.$LANG_NAME.'" 
										   value ="';
											if(!empty($_SESSION['NAME'])){ 
												echo $_SESSION['NAME'];} 
											elseif(isset($ARRAY)) {
												echo $ARRAY['name'];}
											echo '" '.$CRET_CONT_INP.' />
								</div>
								<div class="col-md-3" id="box_show_number">
									<label class="control-label"><u>'.$LANG_No.':</u></label>
									<div id="place_task_number">';	if(isset($ARRAY)) {echo str_pad($ARRAY['id'], 8, "0", STR_PAD_LEFT);} echo '</div>
								</div>
								<div class="col-md-3 alert-info text-center" id="box_show_status">';
									if(!empty($ARRAY['status'])){echo ${$ARRAY['status']};}  echo '
								</div>
							</div>
							<div class="row">
								<div class="col-md-5">
									<label class="control-label">'.$LANG_DETAIL.':</label>
									<textarea class="form-control input-sm" rows="5" id="detail" name="detail" 
											  placeholder="'.$LANG_DESCRIPTION.'" '.$CRET_CONT_INP.'>'; if(!empty($_SESSION['DETAIL'])){ echo $_SESSION['DETAIL'];} elseif(isset($ARRAY)) {echo $ARRAY['detail'];} echo '</textarea>
								</div>
								<div class="col-md-5">
									<label class="control-label">'.$LANG_ACTION.':</label>
									<textarea class="form-control input-sm" rows="5" id="action" name="action" 
											  placeholder="'.$LANG_ACTION.'" '.$CRET_CONT_INP.'>'; if(!empty($_SESSION['ACTION'])){ echo $_SESSION['ACTION'];} elseif(isset($ARRAY)) {echo $ARRAY['action'];} echo '</textarea>
								</div>
							</div>
							<div class="row">
								<div class="col-md-5">
									<label class="control-label" id="la_responsible"><u>'.$LANG_RESPONSIBLE.':</u></label>
									<select class="form-control" id="responsible" name="responsible" '.$CRET_CONT_SEL.'>
										<option></option>';
										$SQL = "SELECT id,name FROM tperson WHERE id_instance = ".$_SESSION['INSTANCE_ID']."  ";
										$SQL .= "AND status <> 'e' ORDER BY name";
										$RSPERSON = pg_query($conn, $SQL);
										$ARRAYPERSON = pg_fetch_array($RSPERSON);

										if(!empty($_SESSION['RESPONSIBLE'])){ $PARAMETER_SEL = $_SESSION['RESPONSIBLE'];} 
										elseif(isset($ARRAY)) {$PARAMETER_SEL = $ARRAY['id_responsible'];}

										do{
											if ($ARRAYPERSON['id'] == $PARAMETER_SEL) {
												$sel = 'selected="selected"';
											} else {
												$sel = '';
											}
											echo '<option value="'.$ARRAYPERSON['id'].'" '.$sel.'>'.$ARRAYPERSON['name'].'</option>';
										} while($ARRAYPERSON = pg_fetch_array($RSPERSON));
									echo '
									</select>
								</div>
								<div class="col-md-5">
									<label class="control-label">'.$LANG_PREVISION_DATE.':</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input class="form-control input-sm" type="text" id="prevision_time" name="prevision_time" 
											   placeholder="'.$LANG_DATE_FORMAT_UPPERCASE.'" 
											   value ="'; if(!empty($_SESSION['PREVISION'])){ echo $_SESSION['PREVISION'];} elseif(isset($ARRAY)) { echo $ARRAY['prevision_date'];}
												echo '" '.$CRET_CONT_INP.' />
									</div>
								</div>
							</div>
							<div class="row">';
						if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_6)) !== false &&
							 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_7)) !== false &&
							 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_8)) !== false){
							echo '
								<div class="col-md">
									<label class="control-label">'.$LANG_CONNECT_ACTION_OTHER_ITEM.':</label>
									<input class="form-control input-sm" type="text" id="connect_others" name="connect_others" 
											   placeholder="'.$LANG_EXPLAIN_CONN_ACTION_OTHER_ITEM.'" 
											   value ="'; if(!empty($_SESSION['CONNECTED_ITEM'])){ echo $_SESSION['CONNECTED_ITEM'];}
															elseif(isset($ARRAY)){ echo $LIST_ITEM_CONNECTED;} echo '" '.$CRET_CONT_INP.' />
									<span class="small text-muted">'.$LANG_EXPLAIN_CONN_ACTION_OTHER_ITEM.'</span>
								</div>';
							} echo '
							</div>
						</form>';
						}
						echo '
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-primary" id="btn_task_insert"
			onclick="javascript:submitTaskRelated('.$ID_RELATED_ITEM.',\''.$SOURCE.'\',\''.$RESP_TYPE.'\');">
			<i class="fa fa-plus-square-o"></i> ';
			if(empty($ID_TASK)){echo "$LANG_INSERT";} else {echo "$LANG_UPDATE";}  echo '</button>
		<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="javascript:resetFormTask();">
			<i class="fa fa-square-o"></i> ';
			if(empty($ID_TASK)){echo "$LANG_CLEAR";} else {echo "$LANG_UNSELECT";} echo '</button>';
	if(!empty($ID_TASK)){
		if((((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) !== false && ($_SESSION['user_id'] == $ARRAY['id_creator'])) ||
		   (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_4)) !== false) && (($ARRAY['status'] != 'c') && ($ARRAY['status'] != 'f'))){
			echo '
		<button type="button" class="btn btn-primary" data-dismiss="modal" id="btn_task_delete"
			onclick="javascript:deletetTask('.$ID_TASK.','.$ID_RELATED_ITEM.',\''.$SOURCE.'\',\''.$RESP_TYPE.'\');">
			<i class="fa fa-minus-square-o"></i> '.$LANG_DELETE.'</button>
			';} 
		if(((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_5)) !== false && ($_SESSION['user_id'] == $ARRAY['id_responsible'])) ||
		   (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_4)) !== false){
			echo '
		<button type="button" class="btn btn-primary" data-dismiss="modal" id="btn_task_finish"
			onclick="javascript:finishTaskControl('.$ID_RELATED_ITEM.',\''.$SOURCE.'\',\''.$RESP_TYPE.'\');">
			<i class="fa fa-check-square-o"></i>';
				if(($ARRAY['status'] == 'c') && 
				   (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_4) !== false)){echo $LANG_REOPEN;}
				elseif(($ARRAY['status'] == 'f') && 
					  (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_4) !== false)){echo $LANG_CLOSE;} 
				else{echo $LANG_FINISH;}
			echo '
		</button>
			';}
	} echo '
	</div>
	';
	
	unset($_SESSION['NAME']);
	unset($_SESSION['DETAIL']);
	unset($_SESSION['ACTION']);
	unset($_SESSION['RESPONSIBLE']);
	unset($_SESSION['PREVISION']);
	unset($_SESSION['CONNECTED_ITEM']);
}?>