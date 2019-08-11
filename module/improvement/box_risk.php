<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/variable.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	$ID_RISK = trim(addslashes($_POST['id_risk']));
	$ID_RELATED_ITEM = trim(addslashes($_POST['id_source'])); // ID Incident, Task, etc
	$SOURCE = trim(addslashes($_POST['source'])); // Incident, Task, etc
	
	$DESTINATION_PAGE = ("associateObjRisk.php");
	
	$PERMITIONS_NAME_1 = "create_risk@";
	$PERMITIONS_NAME_3 = "read_all_risk@";

	// Load the risk related with this task
	if($SOURCE == "task"){
		$SQL = "SELECT id_risk FROM tarisk_task WHERE id_risk = $ID_RISK AND id_task = $ID_RELATED_ITEM";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		$LIST_ITEM_CONNECTED = pg_affected_rows($RS);
	} elseif($SOURCE == "inci"){
		$SQL = "SELECT id_incident FROM taincident_risk WHERE id_risk = $ID_RISK AND id_incident = $ID_RELATED_ITEM";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		$LIST_ITEM_CONNECTED = pg_affected_rows($RS);
	}
	
	if((!empty($ID_RISK)) && (!empty($ID_RELATED_ITEM))) {
		$SQL = "SELECT r.id, r.id_process, e.name AS responsible, r.name, r.detail, r.creation_time, r.risk_factor, r.risk_residual, r.status, r.probability, ";
		$SQL .= "r.probability_justification, r.impact ";
		$SQL .= "FROM trisk r, tprocess p, tperson e ";
		$SQL .= "WHERE p.id = r.id_process AND p.id_risk_responsible = e.id AND r.id = $ID_RISK ";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
	}
	echo '
	<div class="modal-header">
		<h5 class="modal-title" id="task_boxLabel">'.$LANG_RISK.'</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="modal-body" >
		<div id="row">
			<div class="col-md">
				<div class="card mb-3"> 	
					<div class="panel panel-default">';
						if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3)) === false &&
						  (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false){
							echo '<center>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</center>';
						} elseif(pg_affected_rows($RS) == 0){
							echo '<center>'.$LANG_RISK_NOT_FOUND.'</center>';
							$NOT_FOUND = 1;
						} else {
						echo '
						<form action="" method="post" name="risk_form" id="risk_form" autocomplete="off">
							<input type="hidden" name="id_risk_selected" id="id_risk_selected" value="'.$ID_RISK.'">';
							if($SOURCE == "task"){
								echo '
							<input type="hidden" name="id_task_selected" id="id_task_selected" value="'.$ID_RELATED_ITEM.'">';
							}elseif($SOURCE == "inci"){
								echo '
							<input type="hidden" name="id_incident_selected" id="id_incident_selected" value="'.$ID_RELATED_ITEM.'">';
							}
							echo '
							<div class="row">
								<div class="col-md-5">
									<label class="control-label"><u>'.$LANG_NAME.':</u></label>
									<input class="form-control input-sm" type="text" id="name" name="name" value ="'.$ARRAY['name'].'" readonly />
								</div>
								<div class="col-md-3" id="box_show_number">
									<label class="control-label"><u>'.$LANG_No.':</u></label>
									<div id="place_task_number">';	if(isset($ARRAY)) {echo str_pad($ARRAY['id'], 8, "0", STR_PAD_LEFT);} echo '</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-5">
									<label class="control-label"><u>'.$LANG_RF.':</u></label>';
									if(isset($ARRAY)) {
										if(empty($ARRAY['risk_factor'])){
											$CLASS_DIV = "box_show_neutral";
										} elseif($ARRAY['risk_factor'] <= $_SESSION['ac_risk_level']){
											$CLASS_DIV = "box_show_ok";
										} else {
											$CLASS_DIV = "box_show_out";
										}
										$RF = $ARRAY['risk_factor'];
									} else {
										$CLASS_DIV = "box_show_neutral";
										$RF = "0.00";
									}
										echo '
										<div class="'.$CLASS_DIV.'">
											<center>'.$RF.'</center>
										</div>
								</div>
								<div class="col-md-5">
									<label class="control-label"><u>'.$LANG_RR.':</u></label>';
									if(isset($ARRAY)) {
										if(empty($ARRAY['risk_residual'])){
											$CLASS_DIV = "box_show_neutral";
										} elseif($ARRAY['risk_residual'] <= $_SESSION['ac_risk_level']){
											$CLASS_DIV = "box_show_ok";
										} else {
											$CLASS_DIV = "box_show_out";
										}
										$RF = $ARRAY['risk_residual'];
									} else {
										$CLASS_DIV = "box_show_neutral";
										$RF = "0.00";
									}
										echo '
										<div class="'.$CLASS_DIV.'">
											'.$RF.'
										</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-5">
									<label class="control-label">'.$LANG_DETAIL.':</label>
									<textarea class="form-control input-sm" rows="7" id="detail" name="detail" 
											  placeholder="'.$LANG_DESCRIPTION.'" readonly >'.$ARRAY['detail'].'</textarea>
								</div>
								<div class="col-md-5">';
								$SQL = "SELECT i.name, i.id, ta.value FROM timpact i, tarisk_impact ta WHERE i.id = ta.id_impact AND ta.id_risk = $ID_RISK ";
								$RSIMPACT = pg_query($conn, $SQL);
								$ARRAYIMPACT = pg_fetch_array($RSIMPACT);
								$i=0;
								do{
									$i++;
									echo '
									<div class="row">
										<label class="control-label">'.${$ARRAYIMPACT['name']}.':</label>';
									if($ARRAYIMPACT['name'] != 'financial'){
										$name_impact = "";
										foreach ($CONF_IMPACT_LEVELS as $item_op) {
											$temp_array = explode("@",$item_op);
											if($temp_array[0] == $ARRAYIMPACT['value']){
												$name_impact = $temp_array[1];
											}
										}
										echo '
											<input class="form-control input-sm" type="text" id="impact'.$ARRAYIMPACT['id'].'"
											name="impact'.$ARRAYIMPACT['id'].'" value ="'.${$name_impact}.'" readonly />

									</div>';
									} else {
										echo '
									<div class="row">
										<div class="input-group">
											<span class="input-group-addon">'.$LANG_MONEY.'</span>
											<input class="form-control input-sm" type="text" id="impact'.$ARRAYIMPACT['id'].'"
											name="impact'.$ARRAYIMPACT['id'].'" value ="'.$ARRAYIMPACT['value'].'" readonly />
										</div>
									</div>';
									}
								}while($ARRAYIMPACT = pg_fetch_array($RSIMPACT));
							$prob_value = "";
							foreach ($CONF_IMPACT_LEVELS as $item_op) {
								$temp_array = explode("@",$item_op);
								if($temp_array[0] == $ARRAY['probability']){
									$prob_value = $temp_array[1];
								}
							}
							echo '
								</div>
							</div>
							<div class="row">
								<div class="col-md-5"> 
									<label class="control-label"><u>'.$LANG_RESPONSIBLE.':</u></label>
									<input class="form-control input-sm" type="text" id="responsible" name="responsible" value ="'.$ARRAY['responsible'].'" readonly />
								</div>
								<div class="col-md-5">
									<label class="control-label">
									'.$LANG_PROBABILITY.':
									</label>
									<input class="form-control input-sm" type="text" id="responsible" name="responsible" value ="'.${$prob_value}.'" readonly />
								</div>
							</div>
						</form>';
						}
						echo '
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">';
		if(!isset($NOT_FOUND)){
			if($LIST_ITEM_CONNECTED > 0){
				echo '
			<button type="button" class="btn btn-primary"
				onclick="javascript:associanteRiskObjRelated('.$ID_RISK.','.$ID_RELATED_ITEM.',\''.$SOURCE.'\',\'d\');">
				<i class="fa fa-unlink"></i>'.$LANG_UNLIKN.'</button>';
			} else {
				echo '
			<button type="button" class="btn btn-primary"
				onclick="javascript:associanteRiskObjRelated('.$ID_RISK.','.$ID_RELATED_ITEM.',\''.$SOURCE.'\',\'a\');">
				<i class="fa fa-link"></i>'.$LANG_LIKN.'</button>';
			} 
		}echo '
	</div>';
}?>