<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/variable.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	$ID_RELATED_ITEM = trim(addslashes($_POST['id_cont']));
	$SHOW_ALL = trim(addslashes($_POST['show_all']));

	// Risk permitions
	$PERMITIONS_NAME_1 = "create_control@";
	$PERMITIONS_NAME_2 = "read_own_control@";
	$PERMITIONS_NAME_3 = "read_all_control@";
	$PERMITIONS_NAME_5 = "revision_efficacy@";
	
	echo '
	<table class="table table-bordered" id="dataTable4" name="dataTable4">
		<thead>
			<tr>
				<th>'.$LANG_DATE.'</th>
				<th>'.$LANG_RESULT.'</th>
			</tr>
		</thead>';
			if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false &&
				 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2)) === false &&
				 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3)) === false &&
				 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_5)) === false){
				 echo '
			<tr class="odd gradeX">
				<th></th>
				<th>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</th>
			</tr>';
				} else {
					if($SHOW_ALL == "n"){
						$SQL_COMP = "LIMIT 4";
					} else {
						$SQL_COMP = "";
					}
					$SQL = "SELECT result, prevision_date, TO_CHAR(prevision_date,'".$LANG_DATE_FORMAT_UPPERCASE."') AS prevision_date_formated ";
					$SQL .= "FROM trevision_control WHERE id_control = $ID_RELATED_ITEM ORDER BY prevision_date DESC $SQL_COMP";
					$RS = pg_query($conn, $SQL);
					$ARRAY = pg_fetch_array($RS);
					if(pg_affected_rows($RS) == 0){
					echo '
				<tr class="odd gradeX">
					<th></th>
					<th>'.$LANG_NO_HAVE_DATE.'</th>
				</tr>';
					} else {
						do {																					
						echo '
				<tr class="gradeX">
					<td data-id="'.$ARRAY['prevision_date'].'" >
					<a href="javascript:showRevisionRelatedAj(\''.$ARRAY['prevision_date'].'\','.$ID_RELATED_ITEM.');"></a>
						'.$ARRAY['prevision_date_formated'].'
					</td>
					<td data-id="'.$ARRAY['prevision_date'].'" >'.substr($ARRAY['result'],0,50).'</td>
				</tr>';
						}while($ARRAY = pg_fetch_array($RS)); 
					}
			} echo '
	</table>
	<script>
	$(document).ready(function() {

		$("#dataTable4 tr").click(function() {
			var href = $(this).find("a").attr("href");
			if(href) {
				window.location = href;
			}
		});

	});
	</script>
	';
}?>