<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/variable.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	$ID_CONTROL = trim(addslashes($_POST['id_control']));
	$ID_RELATED_ITEM = trim(addslashes($_POST['id_source'])); // ID Incident, Task, etc
	$SOURCE = trim(addslashes($_POST['source'])); // Incident, Task, etc
	
	$DESTINATION_PAGE = ("associateControlObj.php");
	
	$PERMITIONS_NAME_1 = "create_control@";
	$PERMITIONS_NAME_3 = "read_all_control@";

	// Load the risk related with this task
	if($SOURCE == "task"){
		$SQL = "SELECT id_control FROM tacontrol_task WHERE id_control = $ID_CONTROL AND id_task = $ID_RELATED_ITEM";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		$LIST_ITEM_CONNECTED = pg_affected_rows($RS);
	}elseif($SOURCE == "nonc"){
		$SQL = "SELECT id_nonconformity FROM tanonconformity_control WHERE id_control = $ID_CONTROL AND id_nonconformity = $ID_RELATED_ITEM";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		$LIST_ITEM_CONNECTED = pg_affected_rows($RS);
	}
	
	
	if((!empty($ID_CONTROL)) && (!empty($ID_RELATED_ITEM))) {
		$SQL = "SELECT c.id, c.id_process, e.name AS responsible, c.name, c.detail, c.status ";
		$SQL .= "FROM tcontrol c, tprocess p, tperson e ";
		$SQL .= "WHERE p.id = c.id_process AND p.id_risk_responsible = e.id AND c.id = $ID_CONTROL ";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
	}
	echo '
	<div class="modal-header">
		<h5 class="modal-title" id="task_boxLabel">'.$LANG_CONTROL.'</h5>
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
							echo '<center>'.$LANG_CONTROL_NOT_FOUND.'</center>';
							$NOT_FOUND = 1;
						} else {
						echo '
						<form action="" method="post" name="control_form" id="control_form" autocomplete="off">
							<input type="hidden" name="id_control_selected" id="id_control_selected" value="'.$ID_CONTROL.'">';
							if($SOURCE == "task"){
								echo '
							<input type="hidden" name="id_task_selected" id="id_task_selected" value="'.$ID_RELATED_ITEM.'">';
							}elseif($SOURCE == "nonc"){
								echo '
							<input type="hidden" name="id_nonconformity_selected" id="id_nonconformity_selected" value="'.$ID_RELATED_ITEM.'">';
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
								<div class="col-md-5"></div>
								<div class="col-md-5">
									<label class="control-label"><u>'.$LANG_STATUS.':</u></label>';
										echo '
										<div class="box_show_neutral">
											<center>'.${"C".$ARRAY['status']}.'</center>
										</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-5">
									<label class="control-label">'.$LANG_DETAIL.':</label>
									<textarea class="form-control input-sm" rows="7" id="detail" name="detail" 
											  placeholder="'.$LANG_DESCRIPTION.'" readonly >'.$ARRAY['detail'].'</textarea>
								</div>
							</div>
							<div class="row">
								<div class="col-md-5"> 
									<label class="control-label"><u>'.$LANG_RESPONSIBLE.':</u></label>
									<input class="form-control input-sm" type="text" id="responsible" name="responsible" value ="'.$ARRAY['responsible'].'" readonly />
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
				onclick="javascript:associanteControlObjRelated('.$ID_CONTROL.','.$ID_RELATED_ITEM.',\''.$SOURCE.'\',\'d\');">
				<i class="fa fa-unlink"></i>'.$LANG_UNLIKN.'</button>';
			} else {
				echo '
			<button type="button" class="btn btn-primary"
				onclick="javascript:associanteControlObjRelated('.$ID_CONTROL.','.$ID_RELATED_ITEM.',\''.$SOURCE.'\',\'a\');">
				<i class="fa fa-link"></i>'.$LANG_LIKN.'</button>';
			} 
		}echo '
	</div>';
}?>