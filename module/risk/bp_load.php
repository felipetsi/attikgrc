<?php
	session_start();
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");
	$BP = trim(addslashes($_POST['bp']));
	$ID_SELECTED_SOURCE = trim(addslashes($_POST['id_selected']));
	$STR_BP_EXISTING = "";

	if(!empty($ID_SELECTED_SOURCE)){
		$SQL = "SELECT id_control_best_pratice FROM tacontrol_best_pratice WHERE id_control = $ID_SELECTED_SOURCE ";
		$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
		$ARRAY = pg_fetch_array($RS);		
		do{
			$STR_BP_EXISTING .= "@".$ARRAY['id_control_best_pratice']."@";
		}while($ARRAY = pg_fetch_array($RS));
	}

	$SQL = "SELECT id, item, name FROM tcontrol_best_pratice WHERE id_category IN ";
	$SQL .= "(SELECT id FROM tcategory_best_pratice WHERE id_section IN ";
	$SQL .= "(SELECT id FROM tsection_best_pratice WHERE id_best_pratice = $BP)) ";
	$SQL .= "ORDER BY CAST(SUBSTRING(REPLACE(REPLACE(REPLACE(item,'A',''),'-','1'),'.',''),1,8) AS integer)";
//$SQL .= "ORDER BY CAST (replace(replace(substring(replace(item,'.',''),1,6),'-','1'),'A','') AS integer)";

	$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
	$ARRAY = pg_fetch_array($RS);
	echo '
	<div class="small">'.$LANG_TEXT_EXPLAIN_DASH.'</div>
	<input type="hidden" name="bp" id="bp" value="'.$BP.'">
	<table class="table table-bordered" id="dataTable3" name="dataTable3">
		<thead>
			<tr>
				<th></th>
				<th>'.$LANG_ITEM.'</th>
				<th>'.$LANG_NAME.'</th>
			</tr>
		</thead>';
	do{
		$ID_LOOP = "@".$ARRAY['id']."@";
		if (!empty($STR_BP_EXISTING)){
			if((strpos($STR_BP_EXISTING,$ID_LOOP)) !== false){
				$sel = 'checked="checked"';
			} else {
				$sel = '';
			}
		} else {
			$sel = '';
		}
		
		echo'
		<tr>
			<td data-id="'.$ARRAY['id'].'"><input type="checkbox" name="bpchecked" id="bpchecked" value="'.$ARRAY['id'].'" '.$sel.'></td>
			<td data-id="'.$ARRAY['id'].'">'.$ARRAY['item'].'</td>
			<td data-id="'.$ARRAY['id'].'">'.$ARRAY['name'].'</td>
		</tr>';
	} while($ARRAY = pg_fetch_array($RS));
	echo '</table>
	<div class="small">'.$LANG_TEXT_EXPLAIN_DASH.'</div>
<script>
$(document).ready(function() {
	$(\'#dataTable3 tr\').click(function() {
		if($(this).find("input").attr("checked") == "checked"){
			$(this).find("input").attr("checked", false);
		} else {
			$(this).find("input").attr("checked", true);
		}
	});

});
</script>';
?>