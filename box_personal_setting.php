<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once("./include/conn_db.php");
	require_once("./include/variable.php");
	require_once("./include/lang/".$_SESSION['lang_default']."/general.php");

	$SQL = "SELECT p.id, p.name, p.detail, p.email, p.language_default FROM tperson p ";
	$SQL .= "WHERE id = ".$_SESSION['user_id']." AND p.id_instance = ".$_SESSION['INSTANCE_ID'];
	$RS = pg_query($conn, $SQL);
	$ARRAY = pg_fetch_array($RS);
	if(pg_affected_rows($RS) > 0){
		echo '
		<script type="text/javascript">
			$(document).ready(function(){
				var date_input=$(\'input[name="start_date"]\'); //our date input has the name "date"
				var container=$(\'.bootstrap-iso form\').length>0 ? $(\'.bootstrap-iso form\').parent() : "body";
				var options={
					format: \''.$LANG_DATE_FORMAT.'\',
					container: container,
					todayHighlight: true,
					autoclose: true,
				};
				date_input.datepicker(options);
			});
					$(document).ready(function(){
				var date_input=$(\'input[name="end_date"]\'); //our date input has the name "date"
				var container=$(\'.bootstrap-iso form\').length>0 ? $(\'.bootstrap-iso form\').parent() : "body";
				var options={
					format: \''.$LANG_DATE_FORMAT.'\',
					container: container,
					todayHighlight: true,
					autoclose: true,
				};
				date_input.datepicker(options);
			});
		</script>
		<form action="" method="post" id="personal_setting_form" autocomplete="off">
			<div class="modal-header">
				<h5 class="modal-title" id="personal_setting_boxLabel">'.$LANG_PERSONAL_SETTINGS.'</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md">
						<label class="control-label"><u>'.$LANG_NAME.':</u></label>
						<input class="form-control input-sm" type="text" id="name" name="name" 
							   placeholder="'.$LANG_NAME.'" 
							   value ="'.$ARRAY['name'].'" />
					</div>
				</div>
				<div class="row">
					<div class="col-md">
						<label class="control-label"><u>'.$LANG_DETAIL.':</u></label>
						<input class="form-control input-sm" type="text" id="detail" name="detail" 
							   placeholder="'.$LANG_DETAIL.'" 
							   value ="'.$ARRAY['detail'].'" />
					</div>
				</div>
				<div class="row">
					<div class="col-md">
						<label class="control-label"><u>'.$LANG_EMAILADDRESS.':</u></label>
						<input class="form-control input-sm" type="email" id="email" name="email" 
							   placeholder="'.$LANG_EMAILADDRESS.'" 
							   value ="'.$ARRAY['email'].'" />
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
						<label class="control-label">'.$LANG_PASSWORD.':</label>
						<input class="form-control input-sm" type="password" id="passwd" name="passwd" 
							   placeholder="'.$LANG_PASSWORD.'" />
					</div>
					<div class="col-md-5">
						<label class="control-label">'.($LANG_REPEAT." ".$LANG_PASSWORD).':</label>
						<input class="form-control input-sm" type="password" id="re_passwd" name="re_passwd" 
							   placeholder="'.$LANG_PASSWORD.'" />
					</div>
				</div>
				<div class="small">'.$LANG_MSG_FILL_ONLY_CHANGE.'</div>
				<div class="row">
					<div class="col-md-5">
						<label class="control-label">'.($LANG_DEFAULT." ".$LANG_LANGUAGE).':</label>
						<select class="form-control" id="language_default" name="language_default">';
							foreach ($CONF_AVAILABLE_LANGUAGE as $lang_op) {
								$temp_array = explode("@",$lang_op);
								if ($temp_array[1] == $ARRAY['language_default']) {
									$sel = 'selected="selected"';
								} else {
									$sel = '';
								}
								echo '<option value="'.$temp_array[1].'" '.$sel.'>'.$temp_array[0].'</option>';
							}echo '
						</select>
					</div>
				</div>
				<div class="dropdown-divider"></div>
				';
				$SQL = "SELECT * FROM tprocurator WHERE id_person = ".$ARRAY['id'];
				$RS = pg_query($conn, $SQL);
				$ARRAY = pg_fetch_array($RS); 

				if($ARRAY['status'] == 'a'){
					$sel = 'checked="checked"';
				} else {
					$sel = '';
				}
				echo'
				<div class="row">
					<div class="col-md">
						<input type="checkbox" name="procurator_status" id="procurator_status" value="a" '.$sel.' >'.$LANG_ENABLE_PROCURATOR.'
					</div>
				</div>
				<div class="row" id="procurator">
					<div class="col-md-5">
						<label class="control-label">'.$LANG_PROCURATOR.':</label>
						<select class="form-control" id="procurator" name="procurator">
							<option></option>';
							$SQL = "SELECT id,login FROM tperson WHERE id_instance = ".$_SESSION['INSTANCE_ID']." ";
							$SQL .= "AND status = 'a' ORDER BY login";
							$RSINSIDE = pg_query($conn, $SQL);
							$ARRAYINSIDE = pg_fetch_array($RSINSIDE);

							do{
								if ($ARRAYINSIDE['id'] == $ARRAY['id_procurator']) {
									$sel = 'selected="selected"';
								} else {
									$sel = '';
								}
								echo '<option value="'.$ARRAYINSIDE['id'].'" '.$sel.'>'.$ARRAYINSIDE['login'].'</option>';
							}while($ARRAYINSIDE = pg_fetch_array($RSINSIDE)); echo '
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
						<label class="control-label">'.$LANG_START.':</label>
						<input class="form-control input-sm" type="text" id="start_date" name="start_date"
							   placeholder="'.$LANG_START.'" value ="'.$ARRAY['date_start'].'" />
					</div>
					<div class="col-md-5">
						<label class="control-label">'.$LANG_END.':</label>
						<input class="form-control input-sm" type="text" id="end_date" name="end_date" 
							   placeholder="'.$LANG_END.'" value ="'.$ARRAY['date_end'].'" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">'.$LANG_CANCEL.'</button>
				<a class="btn btn-primary" href="javascript:updatePersonalSettings();">'.$LANG_UPDATE.'</a>
			</div>
		</form>';
	}
}
?>