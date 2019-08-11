<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/variable.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	$ID_PROC = trim(addslashes($_POST['id_proc']));
	$ID_RELATED_ITEM = trim(addslashes($_POST['id_source'])); // ID Incident, Task, etc
	$SOURCE = trim(addslashes($_POST['source'])); // Incident, Task, etc
	
	$DESTINATION_PAGE = ("associateObjRisk.php");
	
	$PERMITIONS_NAME_1 = "create_process@";
	$PERMITIONS_NAME_3 = "read_process@";

	// Load the risk related with this task
	if($SOURCE == "asse"){
		$SQL = "SELECT id_asset FROM taasset_process WHERE id_asset = $ID_RELATED_ITEM AND id_process = $ID_PROC ";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		$LIST_ITEM_CONNECTED = pg_affected_rows($RS);
	}
	
	if(!empty($ID_PROC)) {
		$SQL = "SELECT p.id, e.name AS responsible, p.name, p.detail, p.relevancy ";
		$SQL .= "FROM tprocess p, tperson e ";
		$SQL .= "WHERE p.id_risk_responsible = e.id AND p.id = $ID_PROC ";
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
						if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3)) === false &&
						  (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false){
							echo '<center>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</center>';
						} elseif(pg_affected_rows($RS) == 0){
							echo '<center>'.$LANG_PROCESS_NOT_FOUND.'</center>';
							$NOT_FOUND = 1;
						} else {
						echo '
						<form action="" method="post" name="risk_form" id="risk_form" autocomplete="off">
							<input type="hidden" name="id_asset_selected" id="id_asset_selected" value="'.$ID_RELATED_ITEM.'">';
							echo '
							<div class="row">
								<div class="col-md-5">
									<label class="control-label"><u>'.$LANG_NAME.':</u></label>
									<input class="form-control input-sm" type="text" id="name" name="name" value ="'.$ARRAY['name'].'" readonly />
								</div>
								<div class="col-md-3">
								</div>
							</div>
							<div class="row">
								<div class="col-md-5">
									<label class="control-label">'.$LANG_DETAIL.':</label>
									<textarea class="form-control input-sm" rows="5" id="detail" name="detail" 
											  placeholder="'.$LANG_DESCRIPTION.'" readonly >'.$ARRAY['detail'].'</textarea>
								</div>
								<div class="col-md-5">
									<label class="control-label"><u>'.$LANG_RELEVANCY.':</u></label>
									<input class="form-control input-sm" type="text" id="relevancy" name="relevancy" value ="'.${"L".$ARRAY['relevancy']}.'" readonly />
									<label class="control-label"><u>'.$LANG_RESPONSIBLE.':</u></label>
									<input class="form-control input-sm" type="text" id="responsible" name="responsible" value ="'.$ARRAY['responsible'].'" readonly />
								</div>
							</div>
							<div class="row">
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
				onclick="javascript:associanteProcessObjRelated('.$ID_PROC.','.$ID_RELATED_ITEM.',\''.$SOURCE.'\',\'d\');">
				<i class="fa fa-unlink"></i>'.$LANG_UNLIKN.'</button>';
			} else {
				echo '
			<button type="button" class="btn btn-primary"
				onclick="javascript:associanteProcessObjRelated('.$ID_PROC.','.$ID_RELATED_ITEM.',\''.$SOURCE.'\',\'a\');">
				<i class="fa fa-link"></i>'.$LANG_LIKN.'</button>';
			} 
		}echo '
	</div>';
}?>