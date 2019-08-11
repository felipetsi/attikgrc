<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	$DATA_TYPE = trim(addslashes($_POST['data_type']));
	$ID_ITEM_RELATED = trim(addslashes($_POST['id_related']));

	if($DATA_TYPE == 'risk'){
		echo '
		<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
			<thead>
				<tr>
					<th><input type="checkbox" name="select_all_itens" id="select_all_itens"></th>
					<th>'.$LANG_No.'</th>
					<th>'.$LANG_NAME.'</th>
					<th>'.$LANG_RESPONSIBLE.'</th>
					<th>'.$LANG_PROCESS.'</th>
					<th>'.$LANG_RF.'</th>
					<th>'.$LANG_RR.'</th>
					<th>'.$LANG_CONTROL.'</th>
					<th>'.$LANG_STATUS.'</th>
				</tr>
			</thead>';
			if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2) === false) &&
				(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3) === false)){
				echo '
				<tr class="odd gradeX">
					<th><input type="checkbox" name="select_all_itens" id="select_all_itens"></th>
					<th></th>
					<th>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>';
			} else {
				// Select this page item
				if(!empty($ID_ITEM_RELATED)){
					$SQL_COMPL = " AND id_process = $ID_ITEM_RELATED ";
				} else {
					$SQL_COMPL = "";
				}

				// Verify permission if can read all or only own
				if(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3) !== false){
					$COMMPL_CONTROL = "";
				} else {
					$COMMPL_CONTROL = "AND r.id_process IN (SELECT id FROM tprocess WHERE ";
					$COMMPL_CONTROL .= "id_responsible = ".$_SESSION['user_id'];
					$COMMPL_CONTROL .= " OR id_risk_responsible = ".$_SESSION['user_id'].") ";
				}
				$SQL = "SELECT r.id, p.name AS process, e.name AS responsible, r.name, r.risk_factor, ";
				$SQL .= "r.risk_residual, r.status FROM trisk r, tprocess p, tperson e ";
				$SQL .= "WHERE p.id = r.id_process AND p.id_risk_responsible = e.id $SQL_COMPL $COMMPL_CONTROL AND ";
				$SQL .= "r.id_process IN(SELECT id FROM tprocess WHERE id_area IN(SELECT id FROM tarea WHERE ";
				$SQL .= "id_instance = ".$_SESSION['INSTANCE_ID'].")) ";
				$SQL .= "ORDER BY r.creation_time ASC"; 
				$RS = pg_query($conn, $SQL);
				$ARRAY = pg_fetch_array($RS);
				if(pg_affected_rows($RS) == 0){
					echo '
					<tr class="odd gradeX">
						<td><input type="checkbox" name="optcheckitem"></td>
						<td></td>
						<td>'.$LANG_NO_HAVE_DATE.'</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>';
				} else {
					do{
						$SQL = "SELECT COUNT(id_control)  AS count FROM tarisk_control WHERE id_risk = ".$ARRAY['id'];
						$RSCOUNT = pg_query($conn, $SQL);
						$ARRAYCOUNT = pg_fetch_array($RSCOUNT);
						if (!empty($ID_ITEM_SELECTED)){
							if($ARRAY['id'] == $ID_ITEM_SELECTED) {
								//$sel = 'selected="selected"';
								$sel = 'checked="checked"';
							} else {
								$sel = '';
							}
						} else {
							$sel = '';
						}
						echo '
						<tr class="odd gradeX" id="item_'.$ARRAY['id'].'">
							<td data-id="'.$ARRAY['id'].'">
								<input type="checkbox" name="optcheckitem[]" id="optcheckitem[]" value="'.$ARRAY['id'].'" '.$sel.' >
								<a href="javascript:selectTableItem('.$ARRAY['id'].')'.'"></a></td>
							<td data-id="'.$ARRAY['id'].'">'.str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'</td>
							<td data-id="'.$ARRAY['id'].'">'.substr($ARRAY['name'],0,50).'</td>
							<td data-id="'.$ARRAY['id'].'">'.substr($ARRAY['responsible'],0,30).'</td>
							<td data-id="'.$ARRAY['id'].'">'.substr($ARRAY['process'],0,30).'</td>
							<td data-id="'.$ARRAY['id'].'">'.$ARRAY['risk_factor'].'</td>
							<td data-id="'.$ARRAY['id'].'">'.$ARRAY['risk_residual'].'</td>
							<td data-id="'.$ARRAY['id'].'">'.$ARRAYCOUNT['count'].'</td>
							<td data-id="'.$ARRAY['id'].'">'.${"R".$ARRAY['status']}.'</td>
						</tr>';
					} while($ARRAY = pg_fetch_array($RS));
				}
			}
		echo '
		</table>';
	}?>