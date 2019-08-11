<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	$DESTINATION_PAGE = "soa.php";
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");
	
	$SQL = "SELECT r.id,r.version,r.creation_date,r.history,r.status,p.name AS creator FROM treport r, tperson p WHERE r.created_by = p.id AND ";
	$SQL .= "r.id_instance = ".$_SESSION['INSTANCE_ID']." ORDER BY r.version ASC";
	$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
	$ARRAY = pg_fetch_array($RS);
	echo '
	<form action="'.$DESTINATION_PAGE.'" method="post" name="view_form" id="view_form"> 
		<input type="hidden" name="checkeditem" id="checkeditem">
	</form>
	<form action="update_history_report.php" method="post" name="history_form" id="history_form">
		<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
			<thead>
				<tr>
					<th>'.$LANG_VERSION.'</th>
					<th>'.$LANG_CREATION_DATE.'</th>
					<th>'.$LANG_CREATOR.'</th>
					<th>'.$LANG_STATUS.'</th>
					<th>'.$LANG_HISTORY.'</th>
				</tr>
			</thead>';
		do{
			if($ARRAY['status'] != 'a'){
				$CRET_CONT_INP = "readonly";
				$NAME_HIST = 'history'.$ARRAY['id'];
			} else {
				$CRET_CONT_INP = "";
				$NAME_HIST = 'history';
			}
			echo '
			<tr class="odd gradeX">			
				<td><a href="javascript:selectTableItem('.$ARRAY['id'].');"><i class="fa fa-arrow-left"></i></a>'.$ARRAY['version'].'</td>
				<td>'.$ARRAY['creation_date'].'</td>
				<td>'.$ARRAY['creator'].'</td>
				<td>'.$ARRAY['status'].'</td>
				<td>
					<textarea class="form-control input-sm" rows="2" id="'.$NAME_HIST.'" name="'.$NAME_HIST.'" 
				  '.$CRET_CONT_INP.'>'.$ARRAY['history'].'</textarea>
				</td>
			</tr>';
		}while($ARRAY = pg_fetch_array($RS));
		echo '
		</table>
	</form>';
}
?>