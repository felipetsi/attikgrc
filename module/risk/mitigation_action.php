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
		$SQL = "SELECT c.name, c.id, c.status, r.risk_factor, r.risk_residual FROM tcontrol c, trisk r, tarisk_control ta ";
		$SQL .= "WHERE c.id = ta.id_control AND ta.id_risk = $ID_SELECTED_SOURCE AND r.id = $ID_SELECTED_SOURCE ORDER BY c.name";
		$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		$ARRAY = pg_fetch_array($RS);print_r($ARRAY);
		echo '
		<did class="row">
			<div class="col-md-2">
				<label class="control-label">RF:</u></label>
			</div>';
			if(empty($ARRAY['risk_factor'])){
				$CLASS_DIV = "box_show_neutral";
			} elseif($ARRAY['risk_factor'] <= $_SESSION['ac_risk_level']){
				$CLASS_DIV = "box_show_ok";
			} else {
				$CLASS_DIV = "box_show_out";
			}
			$RF = $ARRAY['risk_factor'];
			echo '
			<div class="col-md-3 '.$CLASS_DIV.'">
				<center>'.$RF.'</center>
			</div>

			<div class="col-md-2">
				<label class="control-label">RR:</u></label>
			</div>';
			if(empty($ARRAY['risk_residual'])){
				$CLASS_DIV = "box_show_neutral";
			} elseif($ARRAY['risk_residual'] <= $_SESSION['ac_risk_level']){
				$CLASS_DIV = "box_show_ok";
			} else {
				$CLASS_DIV = "box_show_out";
			}
			$RR = $ARRAY['risk_residual'];
			echo '
			<div class="col-md-3 '.$CLASS_DIV.'">
				<center>'.$RR.'</center>
			</div>
		</div>
																
		<table class="table table-bordered" id="dataTable3" name="dataTable3">
			<thead>
				<tr>
					<th></th>
					<th>'.$LANG_ID.'</th>
					<th>'.$LANG_NAME.'</th>
					<th>'.$LANG_STATUS.'</th>
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
					<td data-id="'.$ARRAY['id'].'"><input type="radio" name="listAssContRadio" id="listAssContRadio" value="'.$ARRAY['id'].'"></td>
					<td data-id="'.$ARRAY['id'].'"><a href="javascript:showMitigationDetail('.$ARRAY['id'].');">
						</a>'.$ARRAY['id'].'</td>
					<td data-id="'.$ARRAY['id'].'">'.$ARRAY['name'].'</td>
					<td data-id="'.$ARRAY['id'].'">'.${"C".$ARRAY['status']}.'</td>
				</tr>';
			} while($ARRAY = pg_fetch_array($RS));
		}
		echo '
		</table>';
	}

	echo '
	<script>
	$(document).ready(function() {
		$(\'#dataTable3 tr\').click(function() {
			var href = $(this).find("a").attr("href");
			if(href) {
				window.location = href;
			}
			$(this).find("input").prop("checked", true);
		});

	});
	</script>';
}
?>