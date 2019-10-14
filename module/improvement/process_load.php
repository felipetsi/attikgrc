<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/variable.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	$ID_RELATED_ITEM = trim(addslashes($_POST['id_source'])); // ID Nonconformity or Asset
	$SOURCE = trim(addslashes($_POST['source'])); // Source type
	
	// Risk permitions
	$PERMITIONS_NAME_3 = "read_all_nonconformity@";
	if($SOURCE == "nonc"){
		$SQL_C = "AND p.id NOT IN (SELECT id_process FROM tanonconformity_process WHERE ";
		$SQL_C .= "id_nonconformity = $ID_RELATED_ITEM) ";
		$PERMITIONS_NAME_1 = "create_nonconformity@";
	}elseif($SOURCE == "asse"){
		$SQL_C = "AND p.id NOT IN (SELECT id_process FROM taasset_process WHERE ";
		$SQL_C .= "id_asset = $ID_RELATED_ITEM) ";
		$PERMITIONS_NAME_1 = "create_asset@";
	}
	
	echo '
	<script  src="'.$_SESSION['LP'].'js/custom.js" type="text/javascript"></script>
	<script  src="'.$_SESSION['LP'].'js/nonconformity.js" type="text/javascript"></script>
	
	<div class="modal-header">
		<h5 class="modal-title" id="task_boxLabel">'.$LANG_PROCESS.'</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="modal-body" >
			
		<form action="" method="post" name="control_form" id="control_form" autocomplete="off">
			<table class="table table-bordered" id="dataTable2" name="dataTable2">
				<thead>
					<tr>
						<th>'.$LANG_NAME.'</th>
						<th>'.$LANG_DETAIL.'</th>
					</tr>
				</thead>';
					if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false){
						 echo '
					<tr class="odd gradeX">
						<th>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</th>
						<th></th>
					</tr>';
						} else {
							$SQL = "SELECT p.id, p.name, p.detail FROM tprocess p WHERE p.status != 'd' ";
							$SQL .= $SQL_C;
							$SQL .= " AND p.id_area IN ";
							$SQL .= "(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID'].") ";
							$SQL .= "ORDER BY p.name";
							$RS = pg_query($conn, $SQL);
							$ARRAY = pg_fetch_array($RS);
							if(pg_affected_rows($RS) == 0){
							echo '
						<tr class="odd gradeX">
							<th>'.$LANG_NO_HAVE_DATE.'</th>
							<th></th>
						</tr>';
							} else {
								do {																					
								echo '
						<tr class="gradeX" id="control_'.$ARRAY['id'].'">
							<td data-id="'.$ARRAY['id'].'" data-parent="'.$ID_RELATED_ITEM.','.$SOURCE.'">
							<a href="javascript:showProcessRelatedAj('.$ARRAY['id'].','.$ID_RELATED_ITEM.',\''.$SOURCE.'\');"></a>
							'.substr($ARRAY['name'],0,50).'</td>
							<td data-id="'.$ARRAY['id'].'" data-parent="'.$ID_RELATED_ITEM.','.$SOURCE.'">'.substr($ARRAY['detail'],0,100).'</td>
						</tr>';
								}while($ARRAY = pg_fetch_array($RS)); 
							}
					} echo '
			</table>
		</form>
	</div>
	<div class="modal-footer">
	</div>
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
}?>