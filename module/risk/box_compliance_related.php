<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/variable.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	$ID_RELATED_ITEM = trim(addslashes($_POST['id_source'])); // ID Risk or Control
	
	// Control permitions
	$PERMITIONS_NAME_2 = "read_own_control@";
	$PERMITIONS_NAME_3 = "read_all_control@";
	
	echo '
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>'.$LANG_No.'</th>
				<th>'.$LANG_NAME.'</th>
			</tr>
		</thead>';
			if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2)) === false &&
				 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3)) === false){
				 echo '
			<tr class="odd gradeX">
				<th></th>
				<th>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</th>
			</tr>';
				} else {
					$SQL = "SELECT c.id, c.item, c.name, b.name AS best_pratice_name, b.id ";
					$SQL .= "FROM tcontrol_best_pratice c, tbest_pratice b, ";
					$SQL .= "tacontrol_best_pratice tacb WHERE ";
					$SQL .= "b.id IN (SELECT id_best_pratice FROM tsection_best_pratice WHERE ";
					$SQL .= "id IN (SELECT id_section FROM tcategory_best_pratice WHERE ";
					$SQL .= "id IN (SELECT id_category FROM tcontrol_best_pratice WHERE c.id = id))) AND ";
					$SQL .= "tacb.id_control = $ID_RELATED_ITEM ";
					$SQL .= "AND tacb.id_control_best_pratice = c.id ";
					$SQL .= "AND b.id_instance = ".$_SESSION['INSTANCE_ID'];
					$SQL .= " ORDER BY b.id,CAST(SUBSTRING(REPLACE(REPLACE(REPLACE(c.item,'A',''),'-','1'),'.',''),1,8) AS integer)";
					
					$RS = pg_query($conn, $SQL);
					$ARRAY = pg_fetch_array($RS);
					$BP_NAME = "";
					if(pg_affected_rows($RS) == 0){
					echo '
				<tr class="odd gradeX">
					<td data-id="'.$ARRAY['id'].'" data-parent="'.$ID_RELATED_ITEM.'"></td>
					<td data-id="'.$ARRAY['id'].'" data-parent="'.$ID_RELATED_ITEM.'">'.$LANG_NO_HAVE_DATE.'</td>
				</tr>';
					} else {
						do {
							
							if($BP_NAME != $ARRAY['best_pratice_name']){
								echo '
				<tr class="gradeX">
					<td style="font-size: 5px; background-color: #eeeeee; padding: 0 4px !important;"></td>
					<td style="font-size: 5px; background-color: #eeeeee; padding: 0 4px !important;"><b>'.$ARRAY['best_pratice_name'].'</b></td>
				</tr>';
							}
							echo '
				<tr class="gradeX" id="task_'.$ARRAY['id'].'">
					<td data-id="'.$ARRAY['id'].'" data-parent="'.$ID_RELATED_ITEM.'">
						'.$ARRAY['item'].'
					</td>
					<td data-id="'.$ARRAY['id'].'" data-parent="'.$ID_RELATED_ITEM.'">'.substr($ARRAY['name'],0,100).'</td>
				</tr>';
							 $BP_NAME = $ARRAY['best_pratice_name'];
						}while($ARRAY = pg_fetch_array($RS)); 
					}
			} echo '
	</table>';
}?>