<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");
	
	$DATATYPE = trim(addslashes($_POST['datatype']));
	
	switch ($DATATYPE){
		case 'amountrisk':
			if(empty($_SESSION['AR_AMOUNTS'])){
				$SQL = "SELECT COUNT(id) AS amountrisk FROM trisk WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
				$SQL .= "(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID']."))";
				$RS = pg_query($conn, $SQL);
				$ARRAY = pg_fetch_array($RS);
				$AR_AMOUNTS = $ARRAY['amountrisk'];
				$_SESSION['AR_AMOUNTS'] = $AR_AMOUNTS;
			} else {
				$AR_AMOUNTS = $_SESSION['AR_AMOUNTS'];
			}
			echo '
			<div class="card text-white bg-primary o-hidden h-100">
				<div class="card-body">
					<div class="card-body-icon">
						<i class="fa fa-exclamation-triangle"></i>
					</div>
					<div class="mr-5">
						'.$AR_AMOUNTS.' '.$LANG_RISKS.'
					</div>
				</div>
				<a href="/module/risk/risk.php" class="card-footer text-white clearfix small z-1">
					<span class="float-left">'.$LANG_VIEW.'</span>
					<span class="float-right"><i class="fa fa-angle-right"></i></span>
				</a>
			</div>';
			break;
			
		case 'amountnonconformity':
			if(empty($_SESSION['ANC_AMOUNTS'])){
				$SQL = "SELECT COUNT(id) AS amountnonconformity FROM tnonconformity WHERE id_instance = ".$_SESSION['INSTANCE_ID'];
				$RS = pg_query($conn, $SQL);
				$ARRAY = pg_fetch_array($RS);
				$ANC_AMOUNTS = $ARRAY['amountnonconformity'];
				$_SESSION['ANC_AMOUNTS'] = $ANC_AMOUNTS;
			} else {
				$ANC_AMOUNTS = $_SESSION['ANC_AMOUNTS'];
			}
			echo '
			<div class="card text-white bg-warning o-hidden h-100">
				<div class="card-body">
					<div class="card-body-icon">
						<i class="fa fa-fw fa-list"></i>
					</div>
					<div class="mr-5">
						'.$ANC_AMOUNTS.' '.$LANG_NONCONFORMITY.'
					</div>
				</div>
				<a href="/module/improvement/nonconformity.php" class="card-footer text-white clearfix small z-1">
					<span class="float-left">'.$LANG_VIEW.'</span>
					<span class="float-right"><i class="fa fa-angle-right"></i></span>
				</a>
			</div>
			';
			break;
			
		case 'amountcontrol':
			if(empty($_SESSION['AC_AMOUNTS'])){
				$SQL = "SELECT COUNT(id) AS amountcontrol FROM tcontrol WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
				$SQL .= "(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID']."))";
				$RS = pg_query($conn, $SQL);
				$ARRAY = pg_fetch_array($RS);
				$AC_AMOUNTS = $ARRAY['amountcontrol'];
				$_SESSION['AC_AMOUNTS'] = $AC_AMOUNTS;
			} else {
				$AC_AMOUNTS = $_SESSION['AC_AMOUNTS'];
			}
			echo '
			<div class="card text-white bg-success o-hidden h-100">
				<div class="card-body">
					<div class="card-body-icon">
						<i class="fa fa-lock"></i>
					</div>
					<div class="mr-5">
						'.$AC_AMOUNTS.' '.$LANG_CONTROLS.'
					</div>
				</div>
				<a href="/module/risk/control.php" class="card-footer text-white clearfix small z-1">
					<span class="float-left">'.$LANG_VIEW.'</span>
					<span class="float-right"><i class="fa fa-angle-right"></i></span>
				</a>
			</div>';
			break;
			
		case 'amountincident':
			if(empty($_SESSION['AI_AMOUNTS'])){
				$SQL = "SELECT COUNT(id) AS amountincident FROM tincident WHERE id_instance = ".$_SESSION['INSTANCE_ID'];
				$RS = pg_query($conn, $SQL);
				$ARRAY = pg_fetch_array($RS);
				$AI_AMOUNTS = $ARRAY['amountincident'];
				$_SESSION['AI_AMOUNTS'] = $AI_AMOUNTS;
			} else {
				$AI_AMOUNTS = $_SESSION['AI_AMOUNTS'];
			}
			echo '
			<div class="card text-white bg-danger o-hidden h-100">
				<div class="card-body">
					<div class="card-body-icon">
						<i class="fa fa-question-circle"></i>
					</div>
					<div class="mr-5">
						'.$AI_AMOUNTS.' '.$LANG_SECURITY_INCIDENTS.'
					</div>
				</div>
				<a href="/module/improvement/incident.php" class="card-footer text-white clearfix small z-1">
					<span class="float-left">'.$LANG_VIEW.'</span>
					<span class="float-right"><i class="fa fa-angle-right"></i></span>
				</a>
			</div>';
			break;
		case 'amounttask':
			if(empty($_SESSION['AT_AMOUNTS'])){
				$SQL = "SELECT COUNT(id) AS amounttask FROM ttask_workflow WHERE (status = 'o' OR status = 't') AND id_instance = ".$_SESSION['INSTANCE_ID'];
				$RS = pg_query($conn, $SQL);
				$ARRAY = pg_fetch_array($RS);
				$AT_AMOUNTS = $ARRAY['amounttask'];
				$_SESSION['AT_AMOUNTS'] = $AT_AMOUNTS;
			} else {
				$AT_AMOUNTS = $_SESSION['AT_AMOUNTS'];
			}
			echo '
			<div class="card text-white bg-warning o-hidden h-100">
				<div class="card-body">
					<div class="card-body-icon">
						<i class="fa fa-fw fa-list"></i>
					</div>
					<div class="mr-5">
						'.$AT_AMOUNTS.' '.$LANG_TASKS.'
					</div>
				</div>
				<a href="/module/improvement/task.php" class="card-footer text-white clearfix small z-1">
					<span class="float-left">'.$LANG_VIEW.'</span>
					<span class="float-right"><i class="fa fa-angle-right"></i></span>
				</a>
			</div>
			';
			break;
		case 'percentapplied':
			if((empty($_SESSION['PA_NAME_BP']))&&(empty($_SESSION['PA_AMOUNT_BP']))&&(empty($_SESSION['PA_AMOUNT_AP_BP']))){
				$SQL = "SELECT bp.id,bp.name,COUNT(bp.id) AS amountitem FROM tbest_pratice bp, tcontrol_best_pratice cbp ";
				$SQL .= "WHERE bp.id_instance = ".$_SESSION['INSTANCE_ID']." AND bp.status = 'a' AND cbp.id_category IN(SELECT id FROM tcategory_best_pratice ";
				$SQL .= "WHERE id_section IN(SELECT id FROM tsection_best_pratice WHERE id_best_pratice IN(SELECT id FROM tbest_pratice WHERE id = bp.id))) ";
				$SQL .= "GROUP BY bp.name, bp.id";
				$RS = pg_query($conn, $SQL);
				$ARRAY = pg_fetch_array($RS);
				$i = 0;
				do{
					$SQL = "SELECT COUNT(cbp.id) AS amountapply FROM tcontrol_best_pratice cbp ";
					$SQL .= "WHERE cbp.id IN (SELECT tacb.id_control_best_pratice FROM tacontrol_best_pratice tacb WHERE tacb.id_control IN ";
					$SQL .= "(SELECT id FROM tcontrol WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
					$SQL .= "(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")) AND ";
					$SQL .= "tacb.id_control IN (SELECT id_control FROM tarisk_control) AND status = 'a')) AND ";
					$SQL .= "cbp.id_category IN(SELECT id FROM tcategory_best_pratice WHERE ";
					$SQL .= "id_section IN(SELECT id FROM tsection_best_pratice WHERE id_best_pratice IN(SELECT id FROM tbest_pratice WHERE ";
					$SQL .= "id = ".$ARRAY['id'].")))";
					$RSINSIDE = pg_query($conn, $SQL);
					$ARRAYINSIDE = pg_fetch_array($RSINSIDE);

					$i++;
					$AMOUNITEMBP[$i] = $ARRAY['amountitem'];
					$NAMEBP[$i] = $ARRAY['name'];
					$AMOUNTAPPLY[$i] = $ARRAYINSIDE['amountapply'];
				}while($ARRAY = pg_fetch_array($RS));
				
				$_SESSION['PA_NAME_BP'] = $NAMEBP;
				$_SESSION['PA_AMOUNT_BP'] = $AMOUNITEMBP;
				$_SESSION['PA_AMOUNT_AP_BP'] = $AMOUNTAPPLY;
			} else {
				$NAMEBP = $_SESSION['PA_NAME_BP'];
				$AMOUNITEMBP = $_SESSION['PA_AMOUNT_BP'];
				$AMOUNTAPPLY = $_SESSION['PA_AMOUNT_AP_BP'];
			}
			echo '
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-check-square"></i>
					'.$LANG_IMPLEMENTATION_LEVEL.'
				</div>
				<div class="list-group list-group-flush">';
					foreach($NAMEBP as $key => $value){
						echo '
						<a href="#" class="list-group-item list-group-item-action">
						<div class="media">
							<div class="media-body">
								<div class="list-group-item">
									<strong>'.$value.'</strong>
								</div>
								<div class="row">
									<div class="col-md-8">'.$LANG_ITENS.':</div>
									<div class="col-md">'.$AMOUNITEMBP[$key].'</div>
								</div>
								<div class="row">
									<div class="col-md-8">'.$LANG_IMPLEMENTED.': </div>
									<div class="col-md">'.$AMOUNTAPPLY[$key].'</div>
								</div>
								<div class="row">
									<div class="col-md-8">'.$LANG_PERCENT.': </div>
									<div class="col-md">'.number_format(($AMOUNTAPPLY[$key]/$AMOUNITEMBP[$key]*100), 2, ',', '.').' % </div>
								</div>
							</div>
						</div>
						</a>';
					}
			echo '
				</div>
			</div>';
			break;
		case 'riskcomplied':
			echo '
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-exclamation-triangle"></i>
					'.$LANG_RISKS.'
				</div>
				<div class="list-group list-group-flush small">
						<a href="#" class="list-group-item list-group-item-action">
						<div class="media">
							<div class="media-body">';
									$SQL = "SELECT COUNT(CASE WHEN (status LIKE 'a') THEN id END) AS amount_a, ";
									$SQL .= "COUNT(CASE WHEN (status LIKE 'm') THEN id END) AS amount_m, ";
									$SQL .= "COUNT(CASE WHEN (status LIKE 'c') THEN id END) AS amount_c, ";
									$SQL .= "COUNT(CASE WHEN (status LIKE 't') THEN id END) AS amount_t, ";
									$SQL .= "COUNT(CASE WHEN (status LIKE 'e') THEN id END) AS amount_e, ";
									$SQL .= "COUNT(CASE WHEN (status LIKE 'v') THEN id END) AS amount_v ";
									$SQL .= "FROM trisk WHERE id_process IN ";
									$SQL .= "(SELECT id FROM tprocess WHERE id_area IN ";
									$SQL .= "(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID']."))";
									$RS = pg_query($conn, $SQL);
									$ARRAY = pg_fetch_array($RS);echo '
								<div class="row">
									<div class="col-md-8">'.$Ra.':</div>
									<div class="col-md">'.$ARRAY['amount_a'].'</div>
								</div>
								<div class="dropdown-divider"></div>
								<div class="row">
									<div class="col-md-8">'.$Rm.':</div>
									<div class="col-md">'.$ARRAY['amount_m'].'</div>
								</div>
								<div class="dropdown-divider"></div>
								<div class="row">
									<div class="col-md-8">'.$Rc.':</div>
									<div class="col-md">'.$ARRAY['amount_c'].'</div>
								</div>
								<div class="dropdown-divider"></div>
								<div class="row">
									<div class="col-md-8">'.$Rt.':</div>
									<div class="col-md">'.$ARRAY['amount_t'].'</div>
								</div>
								<div class="dropdown-divider"></div>
								<div class="row">
									<div class="col-md-8">'.$Rv.':</div>
									<div class="col-md">'.$ARRAY['amount_v'].'</div>
								</div>
								<div class="dropdown-divider"></div>
								<div class="row">
									<div class="col-md-8">'.$Re.':</div>
									<div class="col-md">'.$ARRAY['amount_e'].'</div>
								</div>
							</div>
						</div>
						</a>
				</div>
			</div>';
			break;
		case 'aboutcontrol':
			$SQL = "SELECT COUNT(CASE WHEN (status LIKE 'a') THEN id END) AS amount_ok, ";
			$SQL .= "COUNT(CASE WHEN (status LIKE 'n') THEN id END) AS amount_noteffetive, ";
			$SQL .= "COUNT(CASE WHEN (status LIKE 'e') THEN id END) AS amount_revidelayed, ";
			$SQL .= "COUNT(CASE WHEN (status LIKE 'r') THEN id END) AS amount_revisionnear ";
			$SQL .= "FROM tcontrol WHERE id_process IN ";
			$SQL .= "(SELECT id FROM tprocess WHERE id_area IN(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID']."))";
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			echo '
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-lock"></i>
					'.$LANG_CONTROL.'
				</div>
				<div class="list-group list-group-flush small">
						<a href="#" class="list-group-item list-group-item-action">
						<div class="media">
							<div class="media-body">
								<div class="row">
									<div class="col-md-8">'.$Ca.':</div>
									<div class="col-md">'.$ARRAY['amount_ok'].'</div>
								</div>
								<div class="dropdown-divider"></div>
								<div class="row">
									<div class="col-md-8">'.$Cn.':</div>
									<div class="col-md">'.$ARRAY['amount_noteffetive'].'</div>
								</div>
								<div class="dropdown-divider"></div>
								<div class="row">
									<div class="col-md-8">'.$Ce.':</div>
									<div class="col-md">'.$ARRAY['amount_revidelayed'].'</div>
								</div>
								<div class="dropdown-divider"></div>
								<div class="row">
									<div class="col-md-8">'.$Cr.':</div>
									<div class="col-md">'.$ARRAY['amount_revisionnear'].'</div>
								</div>
							</div>
						</div>
						</a>
				</div>
			</div>';
			break;
		case 'aboutarea':
			$SQL = "SELECT COUNT(a.id) AS amountarea ";
			$SQL .= "FROM tarea a WHERE a.status = 'a' AND a.id_instance = ".$_SESSION['INSTANCE_ID'];
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			$AMOUTAREA = $ARRAY['amountarea'];
			$SQL = "SELECT COUNT(p.id) AS amountproc ";
			$SQL .= "FROM tprocess p WHERE p.status = 'a' AND p.id_area IN ";
			$SQL .= "(SELECT id FROM tarea WHERE status = 'a' AND id_instance = ".$_SESSION['INSTANCE_ID'].")";
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			echo '
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-reorder"></i>
					'.$LANG_AREA_AND_PROCESS.'
				</div>
				<div class="list-group list-group-flush small">
						<a href="#" class="list-group-item list-group-item-action">
						<div class="media">
							<div class="media-body">
								<div class="row">
									<div class="col-md-8">'.$LANG_AREA.':</div>
									<div class="col-md">'.$AMOUTAREA.'</div>
								</div>
								<div class="dropdown-divider"></div>
								<div class="row">
									<div class="col-md-8">'.$LANG_PROCESS.':</div>
									<div class="col-md">'.$ARRAY['amountproc'].'</div>
								</div>
							</div>
						</div>
						</a>
				</div>
			</div>';
			break;
			
		case 'graph_risk_treatment':
			if((empty($_SESSION['GRT_LABELS'])) && (empty($_SESSION['GRT_AMOUNTS']))){
				$SQL = "SELECT status,COUNT(status) AS amount FROM trisk WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
				$SQL .= "(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")) ";
				$SQL .= "GROUP BY status";
				$RS = pg_query($conn, $SQL);
				$ARRAY = pg_fetch_array($RS);
				$AMOUNTS = "";
				$LABELS = "";
			//if(!empty($ARRAY['amount'])){
				do{
					$LABELS .= "'".$ARRAY['amount'].' - '.${"R".$ARRAY['status']}."',";
					//$KEY .= "'".$ARRAY['amount']."',";
					$AMOUNTS .= $ARRAY['amount'].",";
				}while($ARRAY = pg_fetch_array($RS));
			
				$LABELS = substr($LABELS,0,(strlen($LABELS)-1));
				$AMOUNTS = substr($AMOUNTS,0,(strlen($AMOUNTS)-1));
				$_SESSION['GRT_LABELS'] = $LABELS;
				$_SESSION['GRT_AMOUNTS'] = $AMOUNTS;
			} else {
				$LABELS = $_SESSION['GRT_LABELS'];
				$AMOUNTS = $_SESSION['GRT_AMOUNTS'];
			}
			if(!empty($AMOUNTS)){//
				echo '
				<div class="col-md">
					<i class="fa fa-pie-chart"></i>
					'.$LANG_RISK_TREATMENT.'

					<canvas id="graph_risk_treatment" width="250" height="250"></canvas>
					<script>
						new RGraph.SVG.Pie({
							id: \'graph_risk_treatment\',
							data: ['.$AMOUNTS.'],
							options: {
								tooltipsEvent: \'mousemove\',
								highlightStyle: \'outline\',
								labelsSticksHlength: 50,
								colors: [\'#132A40\',\'#1B3B59\',\'#224C73\',\'#4F6F8F\',\'#7B93AB\',\'#005B77\'],
								tooltips: ['.$LABELS.'],
								key: [\'\']
							}
						}).draw();
					</script>	
				</div>';
			}
			break;
			
		case 'graph_risk_history_amount':
			if((empty($_SESSION['GRHA_LABELS'])) && (empty($_SESSION['GRHA_AMOUNTS']))){
				$MON_N = date('Y-m');
				for($i = 12; $i > 0; $i--){
					$MON = date('Y-m', strtotime("-$i month"));
					$PERIOD = explode("-",$MON);

					$SQL = "SELECT COUNT(id) AS amount FROM trisk WHERE TO_CHAR(creation_time,'YYYY-MM') <= '".$MON."' AND ";
					$SQL .= "id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
					$SQL .= "(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")) ";
					$RS = pg_query($conn, $SQL);
					$ARRAY = pg_fetch_array($RS);
					$AMOUNT[$MON] = $ARRAY['amount'];
				}
					$LABELS = "";
					$KEY = "";
					$AMOUNTS = "";
				foreach($AMOUNT as $key => $value){	
					$LABELS .= $value.",";
					$KEY .= "'".$key."',";
					$AMOUNTS .= $value.",";
				}

				$LABELS = substr($LABELS,0,(strlen($LABELS)-1));
				$AMOUNTS = substr($AMOUNTS,0,(strlen($AMOUNTS)-1));
				$_SESSION['GRHA_LABELS']  = $LABELS;
				$_SESSION['GRHA_AMOUNTS'] = $AMOUNTS;
				$_SESSION['GRHA_KEY'] = $KEY;
			} else {
				$LABELS = $_SESSION['GRHA_LABELS'];
				$AMOUNTS = $_SESSION['GRHA_AMOUNTS'];
				$KEY = $_SESSION['GRHA_KEY'];
			}
			echo '
			<div class="col-md">
				
				<canvas id="graph_risk_history_amount" ></canvas>
				<script>
					new RGraph.SVG.Line({
						id: \'graph_risk_history_amount\',
						data: ['.$LABELS.'],
						options: {
							tooltips: \'onmousemove\',
							tooltips: ['.$LABELS.'],
							
							backgroundGridVlines: false,
							backgroundGridBorder: false,

							filled: true,
							filledOpacity: 0.5,
							colors: [\'#022B49\'],

							gutterTop: 55,

							linewidth: 1,
							hmargin: 0,

							title: \''.$LANG_AMOUNT_RISK_HIST.'\',

							gutterLeft: 60,
							gutterRight: 60,
							gutterBottom: 75,
							yaxisDecimals: 0,

							tickmarksFill: \'white\',
							tickmarksLinewidth: 3,
							tickmarksSize: 12,

							spline: true,
							xaxis: false,
							yaxis: false,
							xaxisLabels: [
								'.$KEY.'
							],
							shadow: true
						}
					}).trace();
				</script>
			</div>';
			break;		
	}
}
?>