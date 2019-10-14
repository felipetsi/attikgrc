<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	$ID_RELATED_ITEM = trim(addslashes($_POST['id_source'])); // ID Control
	$ID_BP = trim(addslashes($_POST['id_bp'])); // ID BP
	$ID_BP_SELECTEC = trim(addslashes($_POST['bpchecked'])); 
	$ID_BP_SELECTEC = substr($ID_BP_SELECTEC,0,(strlen($ID_BP_SELECTEC)-1));
	$ID_BP_SELECTEC = explode("@",$ID_BP_SELECTEC);
	
	if(!empty($ID_RELATED_ITEM)){
		// Control permitions
		$PERMITIONS_NAME_1 = "create_control@";

		// Delete and insert again the best practices to update
		$SQL = "DELETE FROM tacontrol_best_pratice ";
		$SQL .= "WHERE id_control = $ID_RELATED_ITEM AND id_control IN (SELECT id FROM tcontrol WHERE id_process IN ";
		$SQL .= "(SELECT id FROM tprocess WHERE id_area IN(SELECT id FROM tarea WHERE ";
		$SQL .= "id_instance = ".$_SESSION['INSTANCE_ID']."))) AND id_control_best_pratice IN (SELECT id FROM ";
		$SQL .= "tcontrol_best_pratice WHERE id_category IN (SELECT id FROM tcategory_best_pratice WHERE id_section ";
		$SQL .= "IN (SELECT id FROM tsection_best_pratice WHERE id_best_pratice = $ID_BP)))";
			
		$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

		if($ID_BP_SELECTEC[0] > 0) {
			foreach($ID_BP_SELECTEC as $value){
				if(!empty($value)){
					$SQL = "INSERT INTO tacontrol_best_pratice(id_control, id_control_best_pratice) ";
					$SQL .= "VALUES ($ID_RELATED_ITEM, $value)";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
				}
			}
		}
		if(pg_affected_rows($RS) > 0){
			$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
		} else {
			$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
		}
	}
}