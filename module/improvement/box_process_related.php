<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/variable.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	$ID_RELATED_ITEM = trim(addslashes($_POST['id_source'])); // ID Incident, Task, etc
	$SOURCE = trim(addslashes($_POST['source'])); // Incident, Task, etc
	
	$PERMITIONS_NAME_1 = "create_incident@";
	$PERMITIONS_NAME_3 = "read_all_incident@";


	// Load the process related with this task
	if($SOURCE == "inci"){
		$SQL = "SELECT * FROM tincident_file WHERE id_incident = $ID_RELATED_ITEM ";
		$RS = pg_query($conn, $SQL);
		//$raw = pg_fetch_result($RS, 'file');
		$ARRAYSELECTION = pg_fetch_array($RS);
		// pg_unescape_bytea($ARRAYSELECTION['uploaded_file'])
		
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
	}
	echo '
	<script  src="'.$_SESSION['LP'].'js/custom.js" type="text/javascript"></script>
	<script  src="'.$_SESSION['LP'].'js/nonconformity.js" type="text/javascript"></script>
	
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
					$SQL = "SELECT p.id, p.name, p.status FROM tprocess p ";
					if($SOURCE == "nonc"){
						$SQL .= "WHERE p.id IN (SELECT id_process FROM tanonconformity_process WHERE ";
						$SQL .= "id_nonconformity = $ID_RELATED_ITEM) ";
					}
					$SQL .= "ORDER BY p.name DESC";
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
				<tr class="gradeX" id="control_'.$ARRAY['id'].'">
					<td data-id="'.$ARRAY['id'].'" data-parent="'.$ID_RELATED_ITEM.','.$SOURCE.'">
						<a href="javascript:showProcessRelatedAj('.$ARRAY['id'].','.$ID_RELATED_ITEM.',\''.$SOURCE.'\');"></a>
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