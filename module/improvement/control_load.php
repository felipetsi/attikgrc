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
	$PERMITIONS_NAME_3 = "read_all_control@";
	
	echo '
	<script  src="'.$_SESSION['LP'].'js/custom.js" type="text/javascript"></script>
	
	<div class="modal-header">
		<h5 class="modal-title" id="task_boxLabel">'.$LANG_CONTROLS.'</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="modal-body" >
		<div class="row">
			<div class="col-md">
				<label class="control-label"><u>'.$LANG_CONTROL_No.':</u></label>
				<input class="form-control input-sm" type="text" id="especificControlNo" 
					onkeydown="javascript:openEspecificControl(event.keyCode,'.$ID_RELATED_ITEM.',\''.$SOURCE.'\');"
					placeholder="'.$LANG_TEXT_CONT_NUM_ENTER.'" />
			</div>
		</div>
			
		<form action="" method="post" name="control_form" id="control_form" autocomplete="off">
			<strong><center>'.$LANG_LAST.' (5) '.$LANG_CONTROLS.'</center></strong>
			<table class="table table-bordered" id="dataTable2" name="dataTable2">
				<thead>
					<tr>
						<th>'.$LANG_No.'</th>
						<th>'.$LANG_NAME.'</th>
						<th>'.$LANG_DETAIL.'</th>
					</tr>
				</thead>';
					if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3)) === false){
						 echo '
					<tr class="odd gradeX">
						<th></th>
						<th>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</th>
						<th></th>
					</tr>';
						} else {
							$SQL = "SELECT c.id, c.name, c.detail FROM tcontrol c WHERE c.status != 'd' ";
							if($SOURCE == "task"){	
								$SQL .= "AND c.id NOT IN (SELECT id_control FROM tacontrol_task WHERE ";
								$SQL .= "id_task = $ID_RELATED_ITEM) ";
							} elseif($SOURCE == "nonc"){
								$SQL .= "AND c.id NOT IN (SELECT id_control FROM tanonconformity_control WHERE ";
								$SQL .= "id_nonconformity = $ID_RELATED_ITEM) ";
							}
							$SQL .= "AND c.id_process IN(SELECT id FROM tprocess WHERE id_area IN ";
							$SQL .= "(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")) ";
							$SQL .= "ORDER BY c.id DESC LIMIT 5";
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
								<a href="javascript:showControlRelatedAj('.$ARRAY['id'].','.$ID_RELATED_ITEM.',\''.$SOURCE.'\');"></a>
								'.str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'

							</td>
							<td data-id="'.$ARRAY['id'].'" data-parent="'.$ID_RELATED_ITEM.','.$SOURCE.'">'.substr($ARRAY['name'],0,50).'</td>
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