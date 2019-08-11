<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/variable.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	$ID_RELATED_ITEM = trim(addslashes($_POST['id_source'])); // ID Risk or Control
	$SOURCE = trim(addslashes($_POST['source'])); // Risk or Control
	if(isset($_POST['response_type'])){$RESP_TYPE = trim(addslashes($_POST['response_type']));} else {$RESP_TYPE = "";}// Incident and Nonconformity
	
	// Risk permitions
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
	
	echo '
	<script  src="'.$_SESSION['LP'].'js/custom.js" type="text/javascript"></script>
	
	<table class="table table-bordered" id="dataTable2" name="dataTable2">
		<thead>
			<tr>
				<th>'.$LANG_No.'</th>
				<th>'.$LANG_NAME.'</th>
			</tr>
		</thead>';
			if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_6)) === false &&
				 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_7)) === false &&
				 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_8)) === false &&
				 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_9)) === false &&
				 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_10)) === false){
				 echo '
			<tr class="odd gradeX">
				<th></th>
				<th>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</th>
			</tr>';
				} else {
					$SQL = "SELECT t.id,t.name, t.status FROM ttask_workflow t ";
					if($SOURCE == "risk"){	
						$SQL .= "WHERE t.id IN (SELECT id_task FROM tarisk_task WHERE ";
						$SQL .= "id_risk = $ID_RELATED_ITEM) ";
					} elseif($SOURCE == "cont"){
						$SQL .= "WHERE t.id IN (SELECT id_task FROM tacontrol_task WHERE ";
						$SQL .= "id_control = $ID_RELATED_ITEM) ";
					} elseif($SOURCE == "inci"){
						$SQL .= "WHERE t.id IN (SELECT id_task FROM tainicident_response_task WHERE ";
						$SQL .= "id_incident = $ID_RELATED_ITEM AND response_type = '$RESP_TYPE') ";
					} elseif($SOURCE == "nonc"){
						$SQL .= "WHERE t.id IN (SELECT id_task FROM tanonconformity_response_task WHERE ";
						$SQL .= "id_nonconformity = $ID_RELATED_ITEM AND response_type = '$RESP_TYPE') ";
					} elseif($SOURCE == "proj"){
						$SQL .= "WHERE t.id IN (SELECT id_task FROM taproject_control_best_pratice_task WHERE ";
						$SQL .= "id_project = $ID_RELATED_ITEM AND id_control_best_pratice = $RESP_TYPE) ";
					}
				
					$SQL .= "ORDER BY t.id DESC";
					$RS = pg_query($conn, $SQL);
					$ARRAY = pg_fetch_array($RS);
					if(pg_affected_rows($RS) == 0){
					echo '
				<tr class="odd gradeX">
					<td data-id="'.$ARRAY['id'].'" data-parent="'.$ID_RELATED_ITEM.','.$SOURCE.','.$RESP_TYPE.'"></td>
					<td data-id="'.$ARRAY['id'].'" data-parent="'.$ID_RELATED_ITEM.','.$SOURCE.','.$RESP_TYPE.'">'.$LANG_NO_HAVE_DATE.'</td>
				</tr>';
					} else {
						do {																					
						echo '
				<tr class="gradeX" id="task_'.$ARRAY['id'].'">
					<td data-id="'.$ARRAY['id'].'" data-parent="'.$ID_RELATED_ITEM.','.$SOURCE.','.$RESP_TYPE.'">';
						if($SOURCE != "proj"){
							echo '
						<a href="javascript:showTaskRelatedAj('.$ARRAY['id'].','.$ID_RELATED_ITEM.',\''.$SOURCE.'\',\''.$RESP_TYPE.'\');"></a>';
						} echo '
						'.str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'

					</td>
					<td data-id="'.$ARRAY['id'].'" data-parent="'.$ID_RELATED_ITEM.','.$SOURCE.','.$RESP_TYPE.'">'.substr($ARRAY['name'],0,50).'</td>
				</tr>';
						}while($ARRAY = pg_fetch_array($RS)); 
					}
			} echo '
	</table>
	
	<script src="'.$_SESSION['LP'].'js/custom_submenu2.js"></script>
	
	<script>
	$(document).ready(function() {

		$("#dataTable2 tr").click(function() {
			var href = $(this).find("a").attr("href");
			if(href) {
				window.location = href;
			}
		});

	});
	</script>';
	
	unset($_SESSION['NAME']);
	unset($_SESSION['DETAIL']);
	unset($_SESSION['ACTION']);
	unset($_SESSION['RESPONSIBLE']);
	unset($_SESSION['PREVISION']);
	unset($_SESSION['CONNECTED_ITEM']);	
}?>