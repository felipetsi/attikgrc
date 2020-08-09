<?php
require_once($_SESSION['LP']."include/conn_db.php");
require_once($_SESSION['LP']."include/variable.php");

function print_general_head($lang){
require($_SESSION['LP']."include/lang/$lang/general.php");
echo '
	<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<meta name="description" content="">
		<meta name="author" content="">
		<title>'.$LANG_TITLE_PAGE.'</title>
  
		<!-- Bootstrap core CSS -->
		<link href="'.$_SESSION['LP'].'vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom fonts for this template -->
		<link href="'.$_SESSION['LP'].'vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

		<!-- Plugin CSS -->
		<link href="'.$_SESSION['LP'].'vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="'.$_SESSION['LP'].'css/sb-admin.css" rel="stylesheet">
		
		<!-- Custom styles -->
		<link href="'.$_SESSION['LP'].'css/custom.css" rel="stylesheet">
		
        <!-- jQuery -->
		
		<!-- jQuery Table-->
		<link rel="stylesheet" href="'.$_SESSION['LP'].'vendor/jquery-table/custom.css" media="screen" />
		
		<!-- Was be in and of page -->
		<script  src="'.$_SESSION['LP'].'js/custom.js" type="text/javascript"></script>

		<!--<script src="'.$_SESSION['LP'].'vendor/jquery/jquery.min.js"></script>--!>
		<script src="'.$_SESSION['LP'].'vendor/jquery/jquery.js"></script>
		<script src="'.$_SESSION['LP'].'vendor/popper/popper.min.js"></script>
		<script src="'.$_SESSION['LP'].'vendor/bootstrap/js/bootstrap.min.js"></script>

		<script src="'.$_SESSION['LP'].'vendor/jquery-easing/jquery.easing.min.js"></script>
		<script src="'.$_SESSION['LP'].'vendor/datatables/jquery.dataTables.js"></script>
		<script src="'.$_SESSION['LP'].'vendor/datatables/dataTables.bootstrap4.js"></script>
		
        <!-- Date Plugin -->
        <script type="text/javascript" src="'.$_SESSION['LP'].'vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
		<link rel="stylesheet" href="'.$_SESSION['LP'].'vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css"/>

	</head>';
}

function print_end_page(){
	echo '
	<script src="'.$_SESSION['LP'].'js/sb-admin.min.js"></script>
	<script src="'.$_SESSION['LP'].'js/custom_end.js"></script>';
}

function print_end_page_inside($lang,$ID_ITEM_SELECTED){
	require($_SESSION['LP']."include/lang/$lang/general.php");
	echo ' 	
	<!-- /.content-wrapper -->

	<footer class="sticky-footer">
	  <div class="container">
		<div class="text-center">
		  <small>Copyright &copy; @ttik</small>
		</div>
	  </div>
	</footer>

	<!-- Scroll to Top Button -->
	<a class="scroll-to-top rounded" href="#page-top">
	  <i class="fa fa-angle-up"></i>
	</a>
		
	<!-- Logout Modal -->
	<div class="modal fade" id="logout_box" tabindex="-1" role="dialog" aria-labelledby="logout_boxLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="logout_boxLabel">'.$LANG_READY_TO_LEAVE.'?</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			'.$LANG_TEXT_LOGOUT.'.
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">'.$LANG_CANCEL.'</button>
			<a class="btn btn-primary" href="'.$_SESSION['LP'].'login.php?instance='.$_SESSION['INSTANCE_NAME'].'">'.$LANG_LOGOUT.'</a>
		  </div>
		</div>
	  </div>
	</div>
	<!-- User settings Modal -->
	<div class="modal fade" id="personal_setting_box" tabindex="-1" role="dialog" aria-labelledby="personal_setting_boxLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content" id="panel_personal_settings">
		  
		</div>
	  </div>
	</div>';
	if(!empty($ID_ITEM_SELECTED)){
		echo '
		<script type="text/javascript">	
			$(\'button#btn_collapse_panel\').click();
		</script>';
	}
	
	if(isset($_SESSION['STATUS_MULT_SEL'])){
		if(($_SESSION['STATUS_MULT_SEL']) == 0){
			echo '<script src="'.$_SESSION['LP'].'js/custom_click_table.js"></script>';
		} else {
			echo '<script src="'.$_SESSION['LP'].'js/custom_click_disable_table.js"></script>';
		}
	}
	print_end_page();
}

function print_end_page_inside_ajax_list(){
	if(isset($_SESSION['STATUS_MULT_SEL'])){
		if(($_SESSION['STATUS_MULT_SEL']) == 0){
			echo '<script src="'.$_SESSION['LP'].'js/custom_click_table.js"></script>';
		} else {
			echo '<script src="'.$_SESSION['LP'].'js/custom_click_disable_table.js"></script>';
		}
	}
	print_end_page();
}

function printDeleteBox($lang){
	require($_SESSION['LP']."include/lang/$lang/general.php");
	echo '
		<div id="deleteBox" class="modal fade" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">'.$LANG_DELETE.'</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<b>'.$LANG_TEXT_DELETE.':</b>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal" onclick="javascript:deleteItemCancel();">'.$LANG_CANCEL.'</button>
						<button type="button" class="btn btn-danger" id="confirm" onclick="javascript:deleteItemConfirmed();">'.$LANG_DELETE.'</button>
					</div>
				</div>

			</div>
		</div>';
}

function printDeleteTaskBox($lang){
	require($_SESSION['LP']."include/lang/$lang/general.php");
	echo '
	<div id="deleteBoxTask" class="modal fade" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">'.$LANG_DELETE.'</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<b>'.$LANG_TEXT_DELETE.':</b>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" onclick="javascript:deletetTaskCancel();">'.$LANG_CANCEL.'</button>
					<button type="button" class="btn btn-danger" onclick="javascript:deleteConfirmedTask();">'.$LANG_DELETE.'</button>
				</div>
			</div>

		</div>
	</div>
	';
}

function generate_password(){
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz+-/()@\#$!%*&_{}[]?;:.,';
	$LANG_PASSWORD = substr(str_shuffle($characters),0,10);
	return $LANG_PASSWORD;
}

function insertHistory($instance,$code,$LANG_NAME_person,$detail){
	require($_SESSION['LP']."include/conn_db.php");
	$detail = substr(trim(addslashes($detail)),0,500);
	$SQL = "INSERT INTO thistory (id_instance,code,name_person,detail) ";
	$SQL .= "VALUES ($instance,'$code','$LANG_NAME_person','$detail')";
	$RS = pg_query($conn, $SQL);
}

function verifyDeleteCascade(){
	require($_SESSION['LP']."include/conn_db.php");
	$SQL = "SELECT enable_delete_cascade FROM tinstance WHERE id = ".$_SESSION['INSTANCE_ID'];
	$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
	$ARRAY = pg_fetch_array($RS);
	
	return $ARRAY['enable_delete_cascade']; 
}

function calcRiskFactor($IMPACT_VAL,$probability,$relevancyArea,$relevancyProcess,$financial){
	$impact = 0;
	if($financial > 0){
		
		foreach($_SESSION['financial_impact'] as $key => $value){
			$ARRAY_TEMP = explode("@",$value);
			$VALUE_S = $ARRAY_TEMP[0];
			$VALUE_E = $ARRAY_TEMP[1];
			
			if(($financial >= $VALUE_S) && (($financial <= $VALUE_E) || ($VALUE_E == 0))){
				$impact = $key;
			}
		}
	}
	
	$impact_qual = array_sum($IMPACT_VAL);

	if((($impact_qual)/sizeof($IMPACT_VAL)) > $impact) {
		$impact = (($impact_qual)/sizeof($IMPACT_VAL));
	}
	
	return (((($impact) * $probability) * ((($relevancyArea*3)+$relevancyProcess) / 4)) / 3);
}

function calcResidualRisk($IMPACT_VAL,$probability,$relevancyArea,$relevancyProcess,$financial,$IMPACT_MIT_VAL,$mitProb,$mitFinc,$qtd_control)
{
	/* Divisor factor is to verify how much of mitigation is in each attribute. If number divisor of 100, then do minus 0.01 in end of the formula to never give 100% of mitigation.*/
	$LEN_MATRIX = sizeof($IMPACT_VAL);
	$DIV_FACTOR = ((1/$LEN_MATRIX) - 0.01); //  - 0.01
	$AVG_ARRAY_MIT = (array_sum($IMPACT_MIT_VAL)/($LEN_MATRIX*$qtd_control));
	$impact_qual = 0;
	$prob_qual = 0;
	foreach($IMPACT_VAL as $key => $value){
		if($IMPACT_MIT_VAL[$key] > 0){
			$IMPACT_MIT_VAL[$key] = ($value - ((($IMPACT_MIT_VAL[$key]/$qtd_control) * $DIV_FACTOR) * $value));
			$impact_qual = 1;
		} else {
			$IMPACT_MIT_VAL[$key] = $IMPACT_VAL[$key];
		}
	}
	
	if($mitProb > 0){ 
		$mitProb = ($probability - ((($mitProb/$qtd_control) * $DIV_FACTOR) * $probability));
		$prob_qual = 1;
	} else {
		$mitProb = $probability;
	}
		
	$impact = 0;
	
	if(($financial > 0) && (($mitFinc > 0))){
		foreach($_SESSION['financial_impact'] as $key => $value){
			$ARRAY_TEMP = explode("@",$value);
			$VALUE_S = $ARRAY_TEMP[0];
			$VALUE_E = $ARRAY_TEMP[1];
			
			if(($mitFinc >= $VALUE_S) && (($mitFinc <= $VALUE_E) || ($VALUE_E == 0))){
				$impact = ($LEN_MATRIX - (($key * $DIV_FACTOR) * $LEN_MATRIX));
				$impact_quat = $key;
				$impact_qual = 1;
			}
		}
	}
	
	if($AVG_ARRAY_MIT >= $impact_quat) {
		$AVG_ARRAY_MIT = (array_sum($IMPACT_MIT_VAL)/$LEN_MATRIX);
		$impact = $AVG_ARRAY_MIT;
	}

	if(($impact_qual == 0) && ($prob_qual == 0)){
		return calcRiskFactor($IMPACT_VAL,$probability,$relevancyArea,$relevancyProcess,$financial);
	} else {
		return (((($impact) * $mitProb) * ((($relevancyArea*$LEN_MATRIX)+$relevancyProcess) / 4)) / $LEN_MATRIX);
	}
}

function updateResidualRisk($ID_RISK){
	require($_SESSION['LP']."include/conn_db.php");
	// Load risk impact parameters
	$SQL = "SELECT i.id AS id_impact, i.name AS impact, ta.value, a.relevancy AS rarea, p.relevancy AS rprocess, r.probability ";
	$SQL .= "FROM tarisk_impact ta, timpact i, tarea a, tprocess p, trisk r ";
	$SQL .= "WHERE ta.id_risk = r.id AND r.id_process = p.id AND p.id_area = a.id AND ";
	$SQL .= "ta.id_impact = i.id AND ta.id_risk = $ID_RISK";
	$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
	$ARRAYIN = pg_fetch_array($RS);
	do{
		if($ARRAYIN['impact'] != 'financial'){
			$IMPACT_VAL[$ARRAYIN['id_impact']] = $ARRAYIN['value'];
		} else {
			$financial = $ARRAYIN['value'];
			$idFinc = $ARRAYIN['id_impact'];
		}
		
		$PROBRISK = $ARRAYIN['probability'];
		$RAREA = $ARRAYIN['rarea'];
		$RPROC = $ARRAYIN['rprocess'];
	}while($ARRAYIN = pg_fetch_array($RS));
	// Load mitigation impact parameters
	$SQL ="SELECT i.id_impact, SUM(i.value) AS value, SUM(c.probability) AS probability, ";
	$SQL .= "COUNT(i.id_control) AS qtd_control ";
	$SQL .= "FROM tarisk_control c, tarisk_control_impact i ";
	$SQL .= "WHERE c.id_control = i.id_control AND c.id_control IN (SELECT id FROM tcontrol WHERE status = 'a' OR status = 'r') AND ";
	$SQL .= "c.id_risk = $ID_RISK AND i.id_risk = $ID_RISK ";
	$SQL .= "GROUP BY i.id_impact";
	
	$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
	$ARRAYIN = pg_fetch_array($RS);
	do{
		if($ARRAYIN['id_impact'] != $idFinc)
		{
			if($ARRAYIN['value'] == "null"){
				$IMPACT_MIT_VAL[$ARRAYIN['id_impact']] = 0;
			} else {
				$IMPACT_MIT_VAL[$ARRAYIN['id_impact']] = $ARRAYIN['value'];
			}
		} else {
			if($ARRAYIN['value'] > 0){
				$mitFinc = $ARRAYIN['value'];
			} else {
				$mitFinc = 0;
			}
		}
		$mitProb = $ARRAYIN['probability'];
		$qtd_control = $ARRAYIN['qtd_control'];
	} while($ARRAYIN = pg_fetch_array($RS));
	$RR = calcResidualRisk($IMPACT_VAL,$PROBRISK,$RAREA,$RPROC,$financial,$IMPACT_MIT_VAL,$mitProb,$mitFinc,$qtd_control);
	$SQL = "UPDATE trisk SET risk_residual= $RR WHERE id = $ID_RISK";
	$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
}

function load_user_task($ID_LOGIN){
	require($_SESSION['LP']."include/conn_db.php");
	// Load user tasks
	$SQL = "SELECT t.id, t.name AS task_name,p.name AS creator, t.prevision_date FROM ttask_workflow t, tperson p ";
	$SQL .= "WHERE t.id_creator = p.id AND t.status != 'c' AND t.id_instance = ".$_SESSION['INSTANCE_ID']." AND ";
	$SQL .= "(t.id_responsible = ".$_SESSION['user_id']." OR t.id_approver = ".$_SESSION['user_id'].") ";
	$SQL .= " ORDER BY t.creation_date";
	$RS = pg_query($conn, $SQL);
	$ARRAY = pg_fetch_array($RS);
	$TEMP_ARRAY_TASK[0] = pg_affected_rows($RS);
	if ($TEMP_ARRAY_TASK[0] > 0){

		$f = 1;
		do {
			$TASK_NAME = substr($ARRAY['task_name'],0,30)."...";
			$TASK_RESP = substr($ARRAY['creator'],0,30);
			$TEMP_ARRAY_TASK[$f] = $ARRAY['id']."@&".$TASK_NAME."@&".$TASK_RESP."@&".$ARRAY['prevision_date'];
			$f++;
		}while($ARRAY = pg_fetch_array($RS));	
	}
	$_SESSION['task_logged'] = $TEMP_ARRAY_TASK;
}

function login_procedures($ID_LOGIN){
	require($_SESSION['LP']."include/conn_db.php");
	// Load user logged permitions
	$SQL = "SELECT ip.name AS permission FROM tperson p, tprofile pr, titemprofile ip, taprofile_itemprofile tapi ";
	$SQL .= "WHERE p.id_profile = pr.id AND tapi.id_profile = pr.id AND tapi.id_itemprofile = ip.id AND p.id = ".$ID_LOGIN;
	$RS = pg_query($conn, $SQL);
	$ARRAY = pg_fetch_array($RS);
	unset($_SESSION['user_permission']);
	do{
		$_SESSION['user_permission'] .= $ARRAY['permission']."@";
	}while($ARRAY = pg_fetch_array($RS));
	
	// Load user tasks
	load_user_task($ID_LOGIN);

	// Load financial impact values to formula of calculate risk factor
	$SQL = "SELECT * FROM tinstance_impact_money WHERE id_instance = ".$_SESSION['INSTANCE_ID'];
	$RS = pg_query($conn, $SQL);
	$ARRAY = pg_fetch_array($RS);
	do {
		$ARRAY_TEMP[$ARRAY['impact_level']] = $ARRAY['value_start']."@".$ARRAY['value_end'];

	} while($ARRAY = pg_fetch_array($RS));
	$_SESSION['financial_impact'] = $ARRAY_TEMP;
}

function destroySession(array $SESSIONS){
	foreach($SESSIONS as $value){
		unset($_SESSION[$value]);
	}
}
?>