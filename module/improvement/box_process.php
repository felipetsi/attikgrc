<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/variable.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	$ID_PROCESS = trim(addslashes($_POST['id_process']));
	$ID_RELATED_ITEM = trim(addslashes($_POST['id_source'])); // ID Incident, Task, etc
	$SOURCE = trim(addslashes($_POST['source'])); // Incident, Task, etc
	
	$DESTINATION_PAGE = ("associateControlObj.php");
	
	// Load the process related with this Obj
	if($SOURCE == "nonc"){
		$SQL = "SELECT id_nonconformity FROM tanonconformity_process WHERE id_process = $ID_PROCESS ";
		$SQL .= "AND id_nonconformity = $ID_RELATED_ITEM";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		$LIST_ITEM_CONNECTED = pg_affected_rows($RS);
		$PERMITIONS_NAME_1 = "create_nonconformity@";
	} elseif ($SOURCE == "asse"){
		$SQL = "SELECT id_asset FROM taasset_process WHERE id_process = $ID_PROCESS ";
		$SQL .= "AND id_asset = $ID_RELATED_ITEM";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		$LIST_ITEM_CONNECTED = pg_affected_rows($RS);
		$PERMITIONS_NAME_1 = "create_asset@";
	}
	
	
	if((!empty($ID_PROCESS)) && (!empty($ID_RELATED_ITEM))) {
		$SQL = "SELECT p.id, p.name, p.detail, a.name AS area, e.name AS responsible ";
		$SQL .= "FROM tprocess p, tarea a, tperson e ";
		$SQL .= "WHERE p.id_area = a.id AND e.id = p.id_responsible AND p.id = $ID_PROCESS ";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
	}
	echo '
	<div class="modal-header">
		<h5 class="modal-title" id="task_boxLabel">'.$LANG_PROCESS.'</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="modal-body" >
		<div id="row">
			<div class="col-md">
				<div class="card mb-3"> 	
					<div class="panel panel-default">';
						if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false){
							echo '<center>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</center>';
						} elseif(pg_affected_rows($RS) == 0){
							echo '<center>'.$LANG_CONTROL_NOT_FOUND.'</center>';
							$NOT_FOUND = 1;
						} else {
						echo '
						<form action="" method="post" name="control_form" id="control_form" autocomplete="off">
							<input type="hidden" name="id_control_selected" id="id_control_selected" value="'.$ID_PROCESS.'">
							<div class="row">
								<div class="col-md">
									<label class="control-label"><u>'.$LANG_NAME.':</u></label>
									<input class="form-control input-sm" type="text" id="name" name="name" value ="'.$ARRAY['name'].'" readonly />
								</div>
							</div>
							<div class="row">
								<div class="col-md">
									<label class="control-label">'.$LANG_DETAIL.':</label>
									<textarea class="form-control input-sm" rows="7" id="detail" name="detail" 
											  placeholder="'.$LANG_DESCRIPTION.'" readonly >'.$ARRAY['detail'].'</textarea>
								</div>
							</div>
							<div class="row">
								<div class="col-md">
									<label class="control-label"><u>'.$LANG_AREA.':</u></label>
									<input class="form-control input-sm" type="text" id="area" name="area" value ="'.$ARRAY['area'].'" readonly />
								</div>
							</div>
							<div class="row">
								<div class="col-md"> 
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
				onclick="javascript:associanteProcessObjRelated('.$ID_PROCESS.','.$ID_RELATED_ITEM.',\''.$SOURCE.'\',\'d\');">
				<i class="fa fa-unlink"></i>'.$LANG_UNLIKN.'</button>';
			} else {
				echo '
			<button type="button" class="btn btn-primary"
				onclick="javascript:associanteProcessObjRelated('.$ID_PROCESS.','.$ID_RELATED_ITEM.',\''.$SOURCE.'\',\'a\');">
				<i class="fa fa-link"></i>'.$LANG_LIKN.'</button>';
			} 
		}echo '
	</div>';
}?>