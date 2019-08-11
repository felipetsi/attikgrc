<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	$PREVISION = trim(addslashes($_POST['prevision']));
	$ID_CONTROL = trim(addslashes($_POST['id_cont']));
	
	$DESTINATION_PAGE = ("task_run.php");
	
	$PERMITIONS_NAME_1 = "create_control@";
	$PERMITIONS_NAME_2 = "read_own_control@";
	$PERMITIONS_NAME_3 = "read_all_control@";
	$PERMITIONS_NAME_5 = "revision_efficacy@";
	
	if((!empty($ID_CONTROL)) && (!empty($PREVISION))) {
		$SQL = "SELECT r.id_control,r.id_responsible,r.result,r.justification, ";
		$SQL .= "TO_CHAR(r.prevision_date,'".$LANG_DATE_FORMAT_UPPERCASE."') AS prevision_date, ";
		$SQL .= "TO_CHAR(r.execution_date,'".$LANG_DATE_FORMAT_UPPERCASE."') AS execution_date ";
		$SQL .= "FROM trevision_control r WHERE r.id_control = $ID_CONTROL AND r.prevision_date = '$PREVISION'";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
	}
	echo '
	<script type="text/javascript">
		$(document).ready(function(){
			var date_input=$(\'input[name="execution_date"]\'); //our date input has the name "date"
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
			var date_input=$(\'input[name="prevision_date"]\'); //our date input has the name "date"
			var container=$(\'.bootstrap-iso form\').length>0 ? $(\'.bootstrap-iso form\').parent() : "body";
			var options={
				format: \''.$LANG_DATE_FORMAT.'\',
				container: container,
				todayHighlight: true,
				autoclose: true,
			};
			date_input.datepicker(options);
		});
		
		$("#result").keyup(function() {
			var num = $("#result").val().replace(/[^0-9.]+/g,"");
			$("#result").val(num);
		});
	</script>
	
	<div class="modal-header">
		<h5 class="modal-title" id="task_boxLabel">'.$LANG_EFFICACY_REVISION.'</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="modal-body" >
		<div id="row">
			<div class="col-md">
				<div class="card mb-3"> 	
					<div class="panel panel-default">';
						if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false &&
							 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2)) === false &&
							 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3)) === false &&
							 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_5)) === false){
						echo '<center>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</center>';
					} else {
						// Start variables
						$CRET_CONT_INP = "";
						$CRET_CONT_SEL = "";
						$CRET_CONT_INP_UPDATE = "";

						if(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_5) === false)
						{
							$CRET_CONT_INP = "readonly";
							$CRET_CONT_SEL = "disabled";
						} elseif(!empty($PREVISION)){
							$CRET_CONT_INP_UPDATE = "readonly";
						}
						echo '
						<form action="" method="post" name="efficacy_revision_form" id="efficacy_revision_form" autocomplete="off">
							<input type="hidden" name="id_control" id="id_control" value="'.$ID_CONTROL.'">
							
							<div class="row">
								<div class="col-md-5">
									<label class="control-label"><u>'.$LANG_CONTROL.':</u></label>
									<input class="form-control input-sm" type="text" id="control" name="control" 
										   readonly
										   value ="';
												$SQL = "SELECT name FROM tcontrol WHERE id = $ID_CONTROL";
												$RSINSIDE = pg_query($conn, $SQL);
												$ARRAYINSIDE = pg_fetch_array($RSINSIDE);
												echo $ARRAYINSIDE['name'];
											echo '" />
								</div>
								<div class="col-md-5">
									<label class="control-label"><u>'.$LANG_RESPONSIBLE.':</u></label>
									<input class="form-control input-sm" type="text" id="responsible" name="responsible" 
										   readonly
										   value ="';
											$SQL = "SELECT name FROM tperson WHERE id = ";
											if(!empty($ARRAY['id_responsible'])) {$SQL .= $ARRAY['id_responsible'];}
											else {$SQL .= $_SESSION['user_id'];}
							
											$RSINSIDE = pg_query($conn, $SQL);
											$ARRAYINSIDE = pg_fetch_array($RSINSIDE);
											echo $ARRAYINSIDE['name'].'" />
								</div>
							</div>
							<div class="row">
								<div class="col-md-5">
									<label class="control-label"><u>'.$LANG_PREVISION_DATE.':</u></label>
									<input class="form-control input-sm" type="text" id="prevision_date" name="prevision_date" 
										   placeholder="'.$LANG_PREVISION_DATE.'" '.$CRET_CONT_INP_UPDATE.'
										   value ="';
											if(isset($ARRAY)) {
												echo $ARRAY['prevision_date'];}
											echo '" '.$CRET_CONT_INP.' />
								</div>
								<div class="col-md-5">
									<label class="control-label">'.$LANG_EXECUTION_DATE.':</label>
									<input class="form-control input-sm" type="text" id="execution_date" name="execution_date" 
										   placeholder="'.$LANG_EXECUTION_DATE.'" 
										   value ="';
											if(isset($ARRAY)) {
												echo $ARRAY['execution_date'];}
											echo '" '.$CRET_CONT_INP.' />
								</div>
							</div>
							<div class="row">
								<div class="col-md-5">
									<label class="control-label">'.$LANG_RESULT.':</label>
									<div class="input-group">
										<input class="form-control input-sm" type="number" id="result" name="result" 
											   placeholder="'.$LANG_RESULT.'" min="0" max="100" step=".01"
											   value ="';
												if(isset($ARRAY)) {
													echo $ARRAY['result'];}
												echo '" '.$CRET_CONT_INP.' />
										<span class="input-group-addon"><i class="fa fa-percent"></i></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md">
									<label class="control-label">'.$LANG_JUSTIFY.':</label>
									<textarea class="form-control input-sm" rows="5" id="justification" name="justification" 
											  placeholder="'.$LANG_JUSTIFY.'" '.$CRET_CONT_INP.'>'; if(isset($ARRAY)) {echo $ARRAY['justification'];} echo '</textarea>
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
	<div class="modal-footer">
		<button type="button" class="btn btn-primary" id="btn_task_insert"
			onclick="javascript:submitRevisionRelated('.$ID_CONTROL.');">
			<i class="fa fa-plus-square-o"></i> ';
			echo $LANG_UPDATE.' </button>
		<button type="button" class="btn btn-primary" onclick="javascript:resetFormRevision();">
			<i class="fa fa-square-o"></i> ';
			echo $LANG_CLEAR.'</button>
	</div>';
}?>