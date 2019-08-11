<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	$ID_SELECTED_SOURCE = trim(addslashes($_POST['id_selected']));

	if(!empty($ID_SELECTED_SOURCE)){
		$SQL = "SELECT c.name, c.id FROM tcontrol c WHERE c.id NOT IN (SELECT id_control FROM tarisk_control WHERE id_risk = $ID_SELECTED_SOURCE) ";
		$SQL .= "AND c.id_process IN(SELECT id FROM tprocess WHERE id_area IN ";
		$SQL .= "(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")) ";
		$SQL .= "ORDER BY c.name"; 
		$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		$ARRAY = pg_fetch_array($RS);
		echo '
		<table class="table table-bordered" id="dataTable4" name="dataTable4">
			<thead>
				<tr>
					<th></th>
					<th>'.$LANG_ID.'</th>
					<th>'.$LANG_NAME.'</th>
				</tr>
			</thead>';
		if(pg_affected_rows($RS) == 0){
				echo'
				<tr>
					<td></td>
					<td></td>
					<td>'.$LANG_NO_HAVE_DATE.'</td>
				</tr>';
		} else {
			do{
				echo'
				<tr>
					<td data-id="'.$ARRAY['id'].'"><input type="radio" name="controlCkdRadio" id="controlCkdRadio" value="'.$ARRAY['id'].'"></td>
					<td data-id="'.$ARRAY['id'].'">'.$ARRAY['id'].'</td>
					<td data-id="'.$ARRAY['id'].'">'.$ARRAY['name'].'</td>
				</tr>';
			} while($ARRAY = pg_fetch_array($RS));
		}
		echo '
		</table>';
	}
	echo '
	<script>
	$(document).ready(function() {
		$(\'#dataTable4 tr\').click(function() {
			$(this).find("input").prop("checked", true);
		});

	});
	</script>';
}
?>