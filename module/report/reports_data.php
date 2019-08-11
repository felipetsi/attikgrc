<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");
	require_once($_SESSION['LP']."include/variable.php");
	
	$DATATYPE = trim(addslashes($_POST['datatype']));
	
	switch ($DATATYPE){
		case 'riskbyarea':
			$_SESSION['show_report'] = 'riskbyarea';
			$SQL = "SELECT r.id, a.name AS area, r.name AS risk, r.risk_factor, r.risk_residual, r.status, p.name AS process FROM trisk r, tarea a, ";
			$SQL .= "tprocess p  WHERE r.id_process = p.id AND p.id_area = a.id AND r.status != 'd' AND a.id_instance = ".$_SESSION['INSTANCE_ID']." ";
			$SQL .= "ORDER BY a.id";
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			echo '
			<div class="card mb-3">
				<label class="control-label"><center><i><u>'.$LANG_RISK_BY_AREA.'</u></i></center></label>
			</div>
			<div class="panel panel-default">
				<div class="row">
					<div class="col-md">
						<div class="col-xl">
							<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
								<thead>
									<tr>
										<th>'.$LANG_AREA.'</th>
										<th>'.$LANG_PROCESS.'</th>
										<th>'.$LANG_No.'</th>
										<th>'.$LANG_RISK.'</th>
										<th>'.$LANG_RF.'</th>
										<th>'.$LANG_RR.'</th>
										<th>'.$LANG_STATUS.'</th>
									</tr>
								</thead>';
							do{
							echo '
								<tr class="odd gradeX">
									<td>'.$ARRAY['area'].'</td>
									<td>'.$ARRAY['process'].'</td>
									<td>'.str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'</td>
									<td>'.$ARRAY['risk'].'</td>
									<td>'.$ARRAY['risk_factor'].'</td>
									<td>'.$ARRAY['risk_residual'].'</td>
									<td>'.${"R".$ARRAY['status']}.'</td>
								</tr>
							';
							} while($ARRAY = pg_fetch_array($RS));
							echo '
							</table>
						</div>
					</div>
				</div>
			</div>
			';
			break;
		case 'riskbyprocess':
			$_SESSION['show_report'] = 'riskbyprocess';
			$SQL = "SELECT r.id, r.name AS risk, r.risk_factor, r.risk_residual, r.status, p.name AS process FROM trisk r, tprocess p, tarea a ";
			$SQL .= "WHERE r.id_process = p.id AND r.status != 'd' AND a.id = p.id_area AND a.id_instance = ".$_SESSION['INSTANCE_ID']." ORDER BY p.id";
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			echo '
			<div class="card mb-3">
				<label class="control-label"><center><i><u>'.$LANG_RISK_BY_PROCESS.'</u></i></center></label>
			</div>
			<div class="panel panel-default">
				<div class="row">
					<div class="col-md">
						<div class="col-xl">
							<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
								<thead>
									<tr>
										<th>'.$LANG_PROCESS.'</th>
										<th>'.$LANG_No.'</th>
										<th>'.$LANG_RISK.'</th>
										<th>'.$LANG_RF.'</th>
										<th>'.$LANG_RR.'</th>
										<th>'.$LANG_STATUS.'</th>
									</tr>
								</thead>';
							do{
							echo '
								<tr class="odd gradeX">
									<td>'.$ARRAY['process'].'</td>
									<td>'.str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'</td>
									<td>'.$ARRAY['risk'].'</td>
									<td>'.$ARRAY['risk_factor'].'</td>
									<td>'.$ARRAY['risk_residual'].'</td>
									<td>'.${"R".$ARRAY['status']}.'</td>
								</tr>
							';
							} while($ARRAY = pg_fetch_array($RS));
							echo '
							</table>
						</div>
					</div>
				</div>
			</div>
			';
			break;
		case 'riskbyrf':
			$_SESSION['show_report'] = 'riskbyrf';
			$SQL = "SELECT r.id, a.name AS area, r.name AS risk, r.risk_factor, r.risk_residual, r.status, p.name AS process FROM trisk r, tarea a, ";
			$SQL .= "tprocess p  WHERE r.id_process = p.id AND p.id_area = a.id AND r.status != 'd' AND a.id_instance = ".$_SESSION['INSTANCE_ID']." ";
			$SQL .= "ORDER BY r.risk_factor DESC";
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			echo '
			<div class="card mb-3">
				<label class="control-label"><center><i><u>'.$LANG_RISK_BY_FACTOR.'</u></i></center></label>
			</div>
			<div class="panel panel-default">
				<div class="row">
					<div class="col-md">
						<div class="col-xl">
							<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
								<thead>
									<tr>
										<th>'.$LANG_RF.'</th>
										<th>'.$LANG_AREA.'</th>
										<th>'.$LANG_PROCESS.'</th>
										<th>'.$LANG_No.'</th>
										<th>'.$LANG_RISK.'</th>
										<th>'.$LANG_RR.'</th>
										<th>'.$LANG_STATUS.'</th>
									</tr>
								</thead>';
							do{
							echo '
								<tr class="odd gradeX">
									<td>'.$ARRAY['risk_factor'].'</td>
									<td>'.$ARRAY['area'].'</td>
									<td>'.$ARRAY['process'].'</td>
									<td>'.str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'</td>
									<td>'.$ARRAY['risk'].'</td>
									<td>'.$ARRAY['risk_residual'].'</td>
									<td>'.${"R".$ARRAY['status']}.'</td>
								</tr>
							';
							} while($ARRAY = pg_fetch_array($RS));
							echo '
							</table>
						</div>
					</div>
				</div>
			</div>
			';
			break;
			
		case 'riskbylabel':
			$_SESSION['show_report'] = 'riskbylabel';
			$SQL = "SELECT r.id, a.name AS area, r.name AS risk, r.risk_factor, r.risk_residual, r.status, r.rlabel, p.name AS process FROM trisk r, tarea a, ";
			$SQL .= "tprocess p  WHERE r.id_process = p.id AND p.id_area = a.id AND r.status != 'd' AND a.id_instance = ".$_SESSION['INSTANCE_ID']." ";
			$SQL .= "ORDER BY r.rlabel";
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			echo '
			<div class="card mb-3">
				<label class="control-label"><center><i><u>'.$LANG_RISK_BY_LABEL.'</u></i></center></label>
			</div>
			<div class="panel panel-default">
				<div class="row">
					<div class="col-md">
						<div class="col-xl">
							<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
								<thead>
									<tr>
										<th>'.$LANG_No.'</th>
										<th>'.$LANG_LABEL.'</th>
										<th>'.$LANG_RISK.'</th>
										<th>'.$LANG_RF.'</th>
										<th>'.$LANG_RR.'</th>
										<th>'.$LANG_PROCESS.'</th>
										<th>'.$LANG_STATUS.'</th>
									</tr>
								</thead>';
							do{
							echo '
								<tr class="odd gradeX">
									<td>'.str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'</td>
									<td>'.${"LB".$ARRAY['rlabel']}.'</td>
									<td>'.$ARRAY['risk'].'</td>
									<td>'.$ARRAY['risk_factor'].'</td>
									<td>'.$ARRAY['risk_residual'].'</td>
									<td>'.$ARRAY['process'].'</td>
									<td>'.${"R".$ARRAY['status']}.'</td>
								</tr>
							';
							} while($ARRAY = pg_fetch_array($RS));
							echo '
							</table>
						</div>
					</div>
				</div>
			</div>
			';
			break;
		case 'controlNotEvaluated':
			$_SESSION['show_report'] = 'controlNotEvaluated';
			$SQL = "SELECT c.id, c.name AS control, c.implementation_date, p.name AS responsible ";
			$SQL .= "FROM tcontrol c, tperson p WHERE c.id NOT IN(SELECT id_control FROM trevision_control) AND ";
			$SQL .= "c.id_process IN (SELECT id FROM tprocess WHERE id_risk_responsible=p.id AND 
											id_area IN(SELECT id FROM tarea WHERE status != 'd' AND
											id_instance = ".$_SESSION['INSTANCE_ID']."))
					ORDER BY c.id";
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			echo '
			<div class="card mb-3">
				<label class="control-label"><center><i><u>'.$LANG_CONTROL.' - '.$LANG_NOT_EVALUATED.'</u></i></center></label>
			</div>
			<div class="panel panel-default">
				<div class="row">
					<div class="col-md">
						<div class="col-xl">
							<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
								<thead>
									<tr>
										<th>'.$LANG_No.'</th>
										<th>'.$LANG_CONTROL.'</th>
										<th>'.$LANG_IMPLEMENTATION.'</th>
										<th>'.$LANG_RESPONSIBLE.'</th>
									</tr>
								</thead>';
							do{
							echo '
								<tr class="odd gradeX">
									<td>'.str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'</td>
									<td>'.$ARRAY['control'].'</td>
									<td>'.$ARRAY['implementation_date'].'</td>
									<td>'.$ARRAY['responsible'].'</td>
								</tr>
							';
							} while($ARRAY = pg_fetch_array($RS));
							echo '
							</table>
						</div>
					</div>
				</div>
			</div>
			';
			break;
		case 'controlDelayedRev':
			$_SESSION['show_report'] = 'controlDelayedRev';
			$SQL = "SELECT c.id, c.name AS control, r.prevision_date, p.name AS responsible ";
			$SQL .= "FROM tcontrol c, tperson p, trevision_control r WHERE c.id = r.id_control ";
			$SQL .= "AND ((r.prevision_date + c.deadline_revision) < current_date) AND r.result IS NULL ";
			$SQL .= "AND c.id_process IN (SELECT id FROM tprocess WHERE id_risk_responsible=p.id AND ";
			$SQL .= "id_area IN(SELECT id FROM tarea WHERE status != 'd' AND ";
			$SQL .= "id_instance = ".$_SESSION['INSTANCE_ID'].")) ORDER BY c.id";
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			echo '
			<div class="card mb-3">
				<label class="control-label"><center><i><u>'.$LANG_CONTROL.' - '.$Ce.'</u></i></center></label>
			</div>
			<div class="panel panel-default">
				<div class="row">
					<div class="col-md">
						<div class="col-xl">
							<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
								<thead>
									<tr>
										<th>'.$LANG_No.'</th>
										<th>'.$LANG_CONTROL.'</th>
										<th>'.$LANG_PREVISION_DATE.'</th>
										<th>'.$LANG_RESPONSIBLE.'</th>
									</tr>
								</thead>';
							do{
							echo '
								<tr class="odd gradeX">
									<td>'.str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'</td>
									<td>'.$ARRAY['control'].'</td>
									<td>'.$ARRAY['prevision_date'].'</td>
									<td>'.$ARRAY['responsible'].'</td>
								</tr>
							';
							} while($ARRAY = pg_fetch_array($RS));
							echo '
							</table>
						</div>
					</div>
				</div>
			</div>
			';
			break;
		case 'controlNotEffective':
			$_SESSION['show_report'] = 'controlNotEffective';
			$SQL = "SELECT c.id, c.name AS control, c.implementation_date, p.name AS responsible ";
			$SQL .= "FROM tcontrol c, tperson p WHERE c.status = 'n' AND ";
			$SQL .= "c.id_process IN (SELECT id FROM tprocess WHERE id_risk_responsible=p.id AND 
											id_area IN(SELECT id FROM tarea WHERE status != 'd' AND
											id_instance = ".$_SESSION['INSTANCE_ID']."))
					ORDER BY c.id";
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			echo '
			<div class="card mb-3">
				<label class="control-label"><center><i><u>'.$LANG_CONTROL.' - '.$Cn.'</u></i></center></label>
			</div>
			<div class="panel panel-default">
				<div class="row">
					<div class="col-md">
						<div class="col-xl">
							<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
								<thead>
									<tr>
										<th>'.$LANG_No.'</th>
										<th>'.$LANG_CONTROL.'</th>
										<th>'.$LANG_IMPLEMENTATION.'</th>
										<th>'.$LANG_RESPONSIBLE.'</th>
									</tr>
								</thead>';
							do{
							echo '
								<tr class="odd gradeX">
									<td>'.str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'</td>
									<td>'.$ARRAY['control'].'</td>
									<td>'.$ARRAY['implementation_date'].'</td>
									<td>'.$ARRAY['responsible'].'</td>
								</tr>
							';
							} while($ARRAY = pg_fetch_array($RS));
							echo '
							</table>
						</div>
					</div>
				</div>
			</div>
			';
			break;
		case 'pedingtask':
			$_SESSION['show_report'] = 'pedingtask';
			$SQL = "SELECT t.id, t.name AS task, t.action, t.status,p.name AS responsible, t.prevision_date, t.execution_date FROM ttask_workflow t, ";
			$SQL .= "tperson p WHERE p.id = t.id_responsible AND t.status != 'c' AND t.id_instance = ".$_SESSION['INSTANCE_ID']." ORDER BY t.id";
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			echo '
			<div class="card mb-3">
				<label class="control-label"><center><i><u>'.$LANG_PENDING_TASKS.'</u></i></center></label>
			</div>
			<div class="panel panel-default">
				<div class="row">
					<div class="col-md">
						<div class="col-xl">
							<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
								<thead>
									<tr>
										<th>'.$LANG_No.'</th>
										<th>'.$LANG_TASK.'</th>
										<th>'.$LANG_ACTION.'</th>
										<th>'.$LANG_RESPONSIBLE.'</th>
										<th>'.$LANG_PREVISION_DATE.'</th>
										<th>'.$LANG_EXECUTION_DATE.'</th>
										<th>'.$LANG_STATUS.'</th>
									</tr>
								</thead>';
							do{
							echo '<a href="#">
								<tr class="odd gradeX">
									<td>'.str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'</td>
									<td>'.$ARRAY['task'].'</td>
									<td>'.$ARRAY['action'].'</td>
									<td>'.$ARRAY['responsible'].'</td>
									<td>'.$ARRAY['prevision_date'].'</td>
									<td>'.$ARRAY['execution_date'].'</td>
									<td>'.${$ARRAY['status']}.'</td>
								</tr>
							';
							} while($ARRAY = pg_fetch_array($RS));
							echo '
							</table>
						</div>
					</div>
				</div>
			</div>
			';
			break;
		case 'taskbyperson':
			$_SESSION['show_report'] = 'taskbyperson';
			$SQL = "SELECT t.id, t.name AS task, t.action, t.status,p.name AS responsible, t.prevision_date, t.execution_date FROM ttask_workflow t, ";
			$SQL .= "tperson p WHERE p.id = t.id_responsible AND t.id_instance = ".$_SESSION['INSTANCE_ID']." ORDER BY p.id";
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			echo '
			<div class="card mb-3">
				<label class="control-label"><center><i><u>'.$LANG_TASK_BY_PERSON.'</u></i></center></label>
			</div>
			<div class="panel panel-default">
				<div class="row">
					<div class="col-md">
						<div class="col-xl">
							<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
								<thead>
									<tr>
										<th>'.$LANG_No.'</th>
										<th>'.$LANG_TASK.'</th>
										<th>'.$LANG_ACTION.'</th>
										<th>'.$LANG_RESPONSIBLE.'</th>
										<th>'.$LANG_PREVISION_DATE.'</th>
										<th>'.$LANG_EXECUTION_DATE.'</th>
										<th>'.$LANG_STATUS.'</th>
									</tr>
								</thead>';
							do{
							echo '<a href="#">
								<tr class="odd gradeX">
									<td>'.str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'</td>
									<td>'.$ARRAY['task'].'</td>
									<td>'.$ARRAY['action'].'</td>
									<td>'.$ARRAY['responsible'].'</td>
									<td>'.$ARRAY['prevision_date'].'</td>
									<td>'.$ARRAY['execution_date'].'</td>
									<td>'.${$ARRAY['status']}.'</td>
								</tr>
							';
							} while($ARRAY = pg_fetch_array($RS));
							echo '
							</table>
						</div>
					</div>
				</div>
			</div>
			';
			break;
		case 'taskdelayed':
			$_SESSION['show_report'] = 'pedingtask';
			$SQL = "SELECT t.id, t.name AS task, t.status,p.name AS responsible, t.prevision_date, t.execution_date FROM ttask_workflow t, ";
			$SQL .= "tperson p WHERE p.id = t.id_responsible AND t.status != 'c' AND ";
			$SQL .= "(t.prevision_date < CURRENT_DATE AND t.execution_date IS NULL) AND t.id_instance = ".$_SESSION['INSTANCE_ID']." ORDER BY responsible";
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			echo '
			<div class="card mb-3">
				<label class="control-label"><center><i><u>'.$LANG_PENDING_TASKS.'</u></i></center></label>
			</div>
			<div class="panel panel-default">
				<div class="row">
					<div class="col-md">
						<div class="col-xl">
							<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
								<thead>
									<tr>
										<th>'.$LANG_No.'</th>
										<th>'.$LANG_TASK.'</th>
										<th>'.$LANG_RESPONSIBLE.'</th>
										<th>'.$LANG_PREVISION_DATE.'</th>
										<th>'.$LANG_EXECUTION_DATE.'</th>
										<th>'.$LANG_STATUS.'</th>
									</tr>
								</thead>';
							do{
							echo '<a href="#">
								<tr class="odd gradeX">
									<td>'.str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'</td>
									<td>'.$ARRAY['task'].'</td>
									<td>'.$ARRAY['responsible'].'</td>
									<td>'.$ARRAY['prevision_date'].'</td>
									<td>'.$ARRAY['execution_date'].'</td>
									<td>'.${$ARRAY['status']}.'</td>
								</tr>
							';
							} while($ARRAY = pg_fetch_array($RS));
							echo '
							</table>
						</div>
					</div>
				</div>
			</div>
			';
			break;
	}
}
?>