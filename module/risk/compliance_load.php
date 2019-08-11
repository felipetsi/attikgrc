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
	//$SOURCE = trim(addslashes($_POST['source'])); // Risk or Control
	
	// Control permitions
	$PERMITIONS_NAME_1 = "create_control@";
	
	echo '
	<script  src="'.$_SESSION['LP'].'js/control.js" type="text/javascript"></script>
	
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="bp_boxLabel">'.$LANG_COMPLIANCE.'</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md">
					<div class="row">
						<div class="col-md-4">
							<label class="control-label"><u>'.$LANG_BEST_PRATICES.':</u></label>
						</div>
						<div class="col-md">
							<select class="form-control" id="best_pratices" name="best_pratices" >
								<option></option>';
								$SQL = "SELECT id,name FROM tbest_pratice WHERE id_instance = ".$_SESSION['INSTANCE_ID'];
								$SQL .= "AND status = 'a' ORDER BY name";
								$RS = pg_query($conn, $SQL);
								$ARRAY = pg_fetch_array($RS);
								do{
									echo '<option value="'.$ARRAY['id'].'" >'.$ARRAY['name'].'</option>';
								}while($ARRAY = pg_fetch_array($RS)); echo '
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="row boxInModalScroll" id="show_item_bp"> <div class="loader" id="loadItem"></div> </div>

		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">'.$LANG_CANCEL.'</button>
			<a class="btn btn-primary" href="javascript:selectedBP('.$ID_RELATED_ITEM.');">'.$LANG_UPDATE.'</a>
		</div>
	</div>
	';
}?>