<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/variable.php");
	require_once($_SESSION['LP']."include/function.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");
	$PERMITIONS_NAME_2 = "read_own_risk@";
	$PERMITIONS_NAME_3 = "read_all_risk@";
	$THIS_PAGE = "risk.php";
	$DESTINATION_PAGE = "risk_run.php";
	$DESTINATION_PAGE_NEXT = "control.php";

	echo '
	<div class="card-header"></div>
	<div class="card-body">
		<div class="table-responsive">
			<form action="'.$THIS_PAGE.'" 
				  method="post" name="view_form_aux" id="view_form_aux"> 
				<input type="hidden" name="change_multi_sel" id="change_multi_sel" value="">
			</form>
			<form action="'.$DESTINATION_PAGE_NEXT.'" method="post" name="submit_to_net" id="submit_to_net"> 
				<input type="hidden" name="relateditem" id="relateditem" value="">
			</form>
			<form action="'; if($_SESSION['STATUS_MULT_SEL'] == 0){ echo $THIS_PAGE;} else {echo $DESTINATION_PAGE;} echo '" 
				  method="post" name="view_form" id="view_form">
				<input type="hidden" name="checkeditem" id="checkeditem" value="">
				<input type="hidden" name="mark_deleteitem_view_form" id="mark_deleteitem_view_form" value="0">
				<input type="hidden" name="mark_duplicateitem_view_form" id="mark_duplicateitem_view_form" value="0">
				<input type="hidden" name="mark_disableitem_view_form" id="mark_disableitem_view_form" value="0">

				<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
					<thead>
						<tr>
							<th><input type="checkbox" name="select_all_itens" id="select_all_itens"></th>
							<th>'.$LANG_No.'</th>
							<th>'.$LANG_NAME.'</th>
							<th>'.$LANG_LABEL.'</th>
							<th>'.$LANG_RESPONSIBLE.'</th>
							<th>'.$LANG_PROCESS.'</th>
							<th>'.$LANG_RF.'</th>
							<th>'.$LANG_RR.'</th>
							<th>'.$LANG_CONTROL.'</th>
							<th>'.$LANG_STATUS.'</th>
						</tr>
					</thead>';
					
					// Start - individual configuration
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
						if(!empty($_SESSION['ID_ITEM_RELATED'])){
							$SQL_COMPL = " AND id_process = ".$_SESSION['ID_ITEM_RELATED'] ;
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
						$SQL .= "r.risk_residual, r.status, r.rlabel FROM trisk r, tprocess p, tperson e ";
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
									<td data-id="'.$ARRAY['id'].'"><input type="checkbox" name="optcheckitem[]" id="optcheckitem[]" value="'.$ARRAY['id'].'" '.$sel.' ><a href="javascript:selectTableItem('.$ARRAY['id'].')"></a></td>
									<td data-id="'.$ARRAY['id'].'">'.str_pad($ARRAY['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'</td>
									<td data-id="'.$ARRAY['id'].'">'.substr($ARRAY['name'],0,50).'</td>
									<td data-id="'.$ARRAY['id'].'">'.substr(${"LB".$ARRAY['rlabel']},0,3).'</td>
									<td data-id="'.$ARRAY['id'].'">'.substr($ARRAY['responsible'],0,20).'</td>
									<td data-id="'.$ARRAY['id'].'">'.substr($ARRAY['process'],0,30).'</td>
									<td data-id="'.$ARRAY['id'].'">'.$ARRAY['risk_factor'].'</td>
									<td data-id="'.$ARRAY['id'].'">'.$ARRAY['risk_residual'].'</td>
									<td data-id="'.$ARRAY['id'].'">'.$ARRAYCOUNT['count'].'</td>
									<td data-id="'.$ARRAY['id'].'">'.${"R".$ARRAY['status']}.'</td>
								</tr>';
							} while($ARRAY = pg_fetch_array($RS));
						}
						// END - individual configuration
					}
					echo '
				</table>
			</form>
		</div>
	</div>';
	print_end_page_inside_ajax_list();
	
}?>