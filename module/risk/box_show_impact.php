<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/variable.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	$ID_ITEM_SELECTED = trim(addslashes($_POST['id_risk'])); // ID Risk or Control
	$ID_IMPACT_TYPE = trim(addslashes($_POST['id_impact_type']));
	if(!empty($_POST['cret_cont_sel'])){$CRET_CONT_SEL = trim(addslashes($_POST['cret_cont_sel']));} else {$CRET_CONT_SEL = "";}
	if(!empty($CRET_CONT_SEL)){$CRET_CONT_INP = "readonly";} else {$CRET_CONT_INP = "";}
	
	// Risk permitions
	$PERMITIONS_NAME_1 = "create_risk@";
	$PERMITIONS_NAME_2 = "read_own_risk@";
	$PERMITIONS_NAME_3 = "read_all_risk@";
	$PERMITIONS_NAME_5 = "treatment_risk@";

	if(!empty($ID_ITEM_SELECTED)){
		$SQL = "SELECT * FROM tarisk_impact WHERE id_risk = $ID_ITEM_SELECTED ";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		do{
			$IMPACT_SEL_VALUE[$ARRAY['id_impact']] = $ARRAY['value'];
			$JUSTIFICATION_IMPACT[$ARRAY['id_impact']] = $ARRAY['justification'];
		}while($ARRAY = pg_fetch_array($RS));
	}
	
	$SQL = "SELECT * FROM timpact WHERE id_impact_type IN (SELECT id FROM ";
	$SQL .= "timpact_type WHERE id = $ID_IMPACT_TYPE AND id_instance = ".$_SESSION['INSTANCE_ID'].")";
	$RS = pg_query($conn, $SQL);
	$ARRAY = pg_fetch_array($RS);
	
	$i=0;

	do{
		$i++;
		echo '
		<div class="row" onclick="javascript:showJustifyItem(justify_impact'.$i.');">
			<div class="col-md-5">
				<label class="control-label">'.${$ARRAY['name']}.':</label>
			</div>';
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
}