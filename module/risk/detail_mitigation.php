<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");
	require_once($_SESSION['LP']."include/variable.php");

	/*$CRET_CONT_INP = "";
	$CRET_CONT_SEL = "";*/
	$CRET_CONT_INP = (isset($_SESSION['CRET_CONT_INP'])) ? $_SESSION['CRET_CONT_INP'] : '';
	$CRET_CONT_SEL = (isset($_SESSION['CRET_CONT_SEL'])) ? $_SESSION['CRET_CONT_SEL'] : '';

	$ID_RISK = trim(addslashes($_POST['idRisk']));
	$ID_CONTROL = trim(addslashes($_POST['idControl']));

	if((!empty($ID_RISK)) && (!empty($ID_CONTROL))){
		$SQL = "SELECT c.name, c.id, r.name AS risk, ta.probability, ta.probability_justification FROM tcontrol c, trisk r, tarisk_control ta ";
		$SQL .= "WHERE c.id = ta.id_control AND ta.id_risk = r.id AND r.id = $ID_RISK AND ta.id_control = $ID_CONTROL";
		$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		$ARRAY = pg_fetch_array($RS);
		echo '
		<form action="" method="post" name="formDetailMitigation" id="formDetailMitigation">
			<input type="hidden" name="id_risk_mitigation" id="id_risk_mitigation" value = "'.$ID_RISK.'">
			<input type="hidden" name="id_control_mitigation" id="id_control_mitigation" value = "'.$ID_CONTROL.'">
			<div class="row">
				<div class="col-md-6">  <!-- Firt column -->
					<div class="row">
						<div class="col-md">
							<label class="control-label"><u>'.$LANG_RISK.':</u></label>
							<input class="form-control input-sm" type="text" id="risk_name_mit" name="risk_name_mit" 
								   placeholder="'.($LANG_CONTROL." ".$LANG_NAME).'" value="'.$ARRAY['risk'].'" readonly />
						</div>
					</div>
					<div class="row">
						<div class="col-md">
							<label class="control-label"><u>'.$LANG_CONTROL.':</u></label>
							<input class="form-control input-sm" type="text" id="control_name" name="control_name" 
								   placeholder="'.($LANG_CONTROL." ".$LANG_NAME).'" value="'.$ARRAY['name'].'" readonly />
						</div>
					</div>
					<div class="row">
						<div class="col-md">
							<div id="justify_mitigation" >
							</div>
						</div>
					</div>
				</div>  <!-- Firt column -->
				<div class="col-md">  <!-- Second column -->
					<div class="row">
						<div class="col-md">';
							$SQL = "SELECT * FROM timpact WHERE id_impact_type IN (SELECT id FROM timpact_type WHERE id_instance = ";
							$SQL .= $_SESSION['INSTANCE_ID']." AND id IN (SELECT id_impact_type FROM trisk WHERE id = $ID_RISK))";
							$RS = pg_query($conn, $SQL);
							$ARRAYIMPACT = pg_fetch_array($RS);
							$IDENTIFY_ID_IMPACT = "";
							$i = 0;
							do{
								$i++;
								$IDENTIFY_ID_IMPACT .= (substr($ARRAYIMPACT['name'],0,1).$ARRAYIMPACT['id']."@");
								$SQL = "SELECT id_impact, value, justification FROM tarisk_control_impact WHERE id_risk = $ID_RISK AND id_control = $ID_CONTROL AND ";
								$SQL .= "id_impact = ".$ARRAYIMPACT['id'];
								$RSINSIDE = pg_query($conn, $SQL);
								$ARRAYINSIDE = pg_fetch_array($RSINSIDE);

								echo '
								<div class="col-md" onclick="javascript:showJustifyMit(justify_mitigate'.$i.');">
									<label class="control-label">'.${$ARRAYIMPACT['name']}.':</label>
								';
								if($ARRAYIMPACT['name'] != 'financial'){
									echo '
									<select class="form-control" id="imp_mit'.$ARRAYIMPACT['id'].'" name="imp_mit'.$ARRAYIMPACT['id'].'" 
									'.$CRET_CONT_SEL.'>';
										foreach ($CONF_IMPACT_MITIGATE_LEVELS as $item_op) {
										$temp_array = explode("@",$item_op);
											if ($temp_array[0] == $ARRAYINSIDE['value']) {
												$sel = 'selected="selected"';
											} else {
												$sel = '';
											}
											echo '
											<option value="'.$temp_array[0].'" '.$sel.'>'.${$temp_array[1]}.'</option>';
										}
										echo '
									</select>';
								} else {
									echo '
										<div class="input-group">
											<span class="input-group-addon">'.$LANG_MONEY.'</span>
											<input class="form-control input-sm" type="text" id="imp_mit'.$ARRAYIMPACT['id'].'"
												name="imp_mit'.$ARRAYIMPACT['id'].'"  
											   placeholder = "'.$LANG_FINANCIAL_IMPACT.' '.$LANG_REDUCTION.'" 
											   value ="'.$ARRAYINSIDE['value'].'" '.$CRET_CONT_INP.' />
										</div>';
								}
									echo '

										<div class="justify_mitigation_box" id="justify_mitigate'.$i.'">
											<div class="modal-header">
											<h5>'.$LANG_JUSTIFY.' - '.${$ARRAYIMPACT['name']}.':</h5>
											<button type="button" class="close" 
											onclick="javascript:closeJustifyMit(justify_mitigate'.$i.');">
											  <span aria-hidden="true">&times;</span>
											</button>
											</div>
											<textarea class="form-control input-sm" rows="5" id="justify_impact_text'.$ARRAYIMPACT['id'].'"
											name="justify_impact_text'.$ARRAYIMPACT['id'].'"  placeholder="'.$LANG_JUSTIFY.'"
											'.$CRET_CONT_INP.'>'.$ARRAYINSIDE['justification'].'</textarea>
										</div>
								</div>';
							}while($ARRAYIMPACT = pg_fetch_array($RS));
							echo '
							<input type="hidden" name="identify_impact" id="identify_impact" value = "'.$IDENTIFY_ID_IMPACT.'">
								<div class="col-md" onclick="javascript:showJustifyMit(justify_prob_mit);">
									<label class="control-label">
									'.$LANG_PROBABILITY.':
									</label>

									<select class="form-control" id="probability" name="probability" 
									'.$CRET_CONT_SEL.'>';
										foreach ($CONF_IMPACT_MITIGATE_LEVELS as $item_op) {
										$temp_array = explode("@",$item_op);
											if ($temp_array[0] == $ARRAY['probability']) {
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

								<div class="justify_mitigation_box" id="justify_prob_mit">
									<div class="modal-header">
									<h5>'.$LANG_JUSTIFY.' - '.$LANG_PROBABILITY.':</h5>
									<button type="button" class="close" 
									onclick="javascript:closeJustifyMit(justify_prob_mit);">
									  <span aria-hidden="true">&times;</span>
									</button>
									</div>
									<textarea class="form-control input-sm" rows="5" id="justify_prob_mit_text"
									name="justify_prob_mit_text"  placeholder="'.$LANG_JUSTIFY.'"
									'.$CRET_CONT_INP.'>'.$ARRAY['probability_justification'].'</textarea>
								</div>
						</div>
					</div>
				</div>  <!-- Second column -->
			</div>
			<div class="col-md modal-footer">
				<button type="button" class="btn btn-primary btn-block" onclick="javascript: updateMitigationDetail('.$ID_CONTROL.','.$ID_RISK.');">'.$LANG_UPDATE.'</button>
			</div>
		</form>';
	}
}
?>