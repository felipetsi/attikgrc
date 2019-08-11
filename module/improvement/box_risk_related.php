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
	
	// Risk permitions
	$PERMITIONS_NAME_3 = "read_all_risk@";
	
	echo '
	<script  src="'.$_SESSION['LP'].'js/custom.js" type="text/javascript"></script>
	
	<table class="table table-bordered" id="dataTable3" name="dataTable3">
		<thead>
			<tr>
				<th>'.$LANG_No.'</th>
				<th>'.$LANG_NAME.'</th>
			</tr>
		</thead>';
			if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3)) === false){
				 echo '
			<tr class="odd gradeX">
				<th></th>
				<th>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</th>
			</tr>';
				} else {
					$SQL = "SELECT r.id, r.name, r.status FROM trisk r ";
					if($SOURCE == "task"){	
						$SQL .= "WHERE r.id IN (SELECT id_risk FROM tarisk_task WHERE ";
						$SQL .= "id_task = $ID_RELATED_ITEM) ";
					} elseif($SOURCE == "inci"){
						$SQL .= "WHERE r.id IN (SELECT id_risk FROM taincident_risk WHERE ";
						$SQL .= "id_incident = $ID_RELATED_ITEM) ";
					}
				
					$SQL .= "ORDER BY r.id DESC";
					$RS = pg_query($conn, $SQL);
					$ARRAY = pg_fetch_array($RS);
					if(pg_affected_rows($RS) == 0){
					echo '
				<tr class="odd gradeX">
					<th></th>
					<th>'.$LANG_NO_HAVE_DATE.'</th>
				</tr>';
					} else {
						do {																					
						echo '
				<tr class="gradeX" id="task_'.$ARRAY['id'].'">
					<td data-id="'.$ARRAY['id'].'" data-parent="'.$ID_RELATED_ITEM.','.$SOURCE.'">
						<a href="javascript:showRiskRelatedAj('.$ARRAY['id'].','.$ID_RELATED_ITEM.',\''.$SOURCE.'\');"></a>
						'.str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'

					</td>
					<td data-id="'.$ARRAY['id'].'" data-parent="'.$ID_RELATED_ITEM.','.$SOURCE.'">'.substr($ARRAY['name'],0,50).'</td>
				</tr>';
						}while($ARRAY = pg_fetch_array($RS)); 
					}
			} echo '
	</table>
	<script>
	$(document).ready(function() {

		$("#dataTable3 tr").click(function() {
			var href = $(this).find("a").attr("href");
			if(href) {
				window.location = href;
			}
		});

	});
	</script>
	';
}?>