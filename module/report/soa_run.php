<?php
session_start();
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THISPAGE = "soa_run.php";
$DESTINATION_PAGE = "soa.php";

$CODE_SUCCESSFUL_IN = 'SIRSOA0001';
$CODE_SUCCESSFUL_DE = 'SDRSOA0001';

if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP'].'include/function.php');
	
	$PERMITIONS_NAME_1 = "create_report@";
	
	if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1) === false)){
		$_SESSION['MSG_TOP'] = 'LANG_YOU_NOT_HAVE_PERMISSION';
	} else {
		if($_SESSION['PAGE_FROM'] == 'soa'){
			$ID_ITEM = trim(addslashes($_POST['id_item_selected']));
			if(isset($_POST['mark_deleteitem'])){$DELETE_ITEM = substr(trim(addslashes($_POST['mark_deleteitem'])),0,1);} else {$DELETE_ITEM = 0;}
			if(empty($ID_ITEM)){
				$SQL = "SELECT id FROM tbest_pratice WHERE name like '%27001%' AND id_instance = ".$_SESSION['INSTANCE_ID']; 
				$SQL .= " LIMIT 1";
				$RS_BP = pg_query($conn, $SQL);
				$ARRAY_BP = pg_fetch_array($RS_BP);
				if(pg_affected_rows($RS_BP) == 1){
					$SQL = "SELECT id, version FROM treport WHERE name = 'soa' AND ";
					$SQL .= "id_instance = ".$_SESSION['INSTANCE_ID']." ORDER BY version DESC LIMIT 1";
					$RS_VS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$ARRAY_VS = pg_fetch_array($RS_VS);

					$SQL = "UPDATE treport SET status='o' ";
					$SQL .= "WHERE name = 'soa' AND id_instance = ".$_SESSION['INSTANCE_ID'];
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

					$SQL = "INSERT INTO treport(name, id_instance, version, created_by, creation_date, history, status) VALUES ";
					$SQL .= "('soa',".$_SESSION['INSTANCE_ID'].",".($ARRAY_VS['version']+1).", ".$_SESSION['user_id'].", CURRENT_DATE, ";
					$SQL .= "'','a')";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$LAST_ID_ARRAY = pg_fetch_array(pg_query("SELECT CURRVAL('treport_id_seq')"));
					$LAST_ID = $LAST_ID_ARRAY[0];

					$SQL = "SELECT id,item,name FROM tcontrol_best_pratice WHERE id_category IN ";
					$SQL .= "(SELECT id FROM tcategory_best_pratice WHERE id_section IN ";
					$SQL .= "(SELECT id FROM tsection_best_pratice WHERE id_best_pratice = ".$ARRAY_BP['id'].")) ";
					$SQL .= "ORDER BY CAST(substring(replace(item,'.',''),2,6) AS integer)";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$ARRAY = pg_fetch_array($RS);
					do{
						$SQL = "SELECT name FROM tcontrol WHERE id IN (SELECT id_control FROM tacontrol_best_pratice WHERE ";
						$SQL .= "id_control_best_pratice = ".$ARRAY['id'].") AND status != 'd' AND ";
						$SQL .= "id_process IN(SELECT id FROM tprocess WHERE id_area IN ";
						$SQL .= "(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID']."))";
						$RS_INSIDE = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY_INSIDE = pg_fetch_array($RS_INSIDE);
						$CONTROL_NAME = "";
						do{
							$CONTROL_NAME .= $ARRAY_INSIDE['name']."@r";
						}while($ARRAY_INSIDE = pg_fetch_array($RS_INSIDE));
						$CONTROL_NAME = substr($CONTROL_NAME,0,(strlen($CONTROL_NAME)-2));
						
						if(!empty($CONTROL_NAME)){
							$STATUS = 'y';
						} else {
							$STATUS = 'n';
						}
						
						if($ARRAY_VS['id'] > 0){
							$SQL = "SELECT id FROM titem_report WHERE content LIKE '".$ARRAY['item']."%' AND id_report = ".$ARRAY_VS['id'];
							$RS_INSIDE = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							$ARRAY_INSIDE = pg_fetch_array($RS_INSIDE);
							
							$SQL = "INSERT INTO titem_report(id_report, content, justification,status) ";
							$SQL .= "SELECT $LAST_ID,'".$ARRAY['item']."@c".$ARRAY['name']."@c$CONTROL_NAME', justification , '$STATUS' ";
							$SQL .= "FROM titem_report WHERE id_report = ".$ARRAY_VS['id']." AND id = ".$ARRAY_INSIDE['id'];
						} else {
							$SQL = "INSERT INTO titem_report(id_report, content, justification,status) ";
							$SQL .= "VALUES ($LAST_ID, '".$ARRAY['item']."@c".$ARRAY['name']."@c$CONTROL_NAME', '', '$STATUS')";
						}
						$RS_INSIDE = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					}while($ARRAY = pg_fetch_array($RS));
					if(pg_affected_rows($RS_INSIDE) > 0){
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
					}

					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_IN,$_SESSION['user_name'],"");
				}
			} else {
				if($DELETE_ITEM == "1"){
					$SQL = "SELECT version FROM treport WHERE id = $ID_ITEM AND id_instance = ".$_SESSION['INSTANCE_ID'];
					$RS_VS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$ARRAY_VS = pg_fetch_array($RS_VS);
					
					$SQL = "SELECT id FROM treport WHERE version = (".$ARRAY_VS['version']."-1) AND id_instance = ".$_SESSION['INSTANCE_ID'];
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$ARRAY = pg_fetch_array($RS);
					if(!empty($ARRAY['id'])){
						$SQL = "UPDATE treport SET status='a' ";
						$SQL .= "WHERE name = 'soa' AND id = ".$ARRAY['id']." AND id_instance = ".$_SESSION['INSTANCE_ID'];
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					}
					
					$SQL = "DELETE FROM titem_report WHERE id_report = $ID_ITEM";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$SQL = "DELETE FROM treport WHERE id = $ID_ITEM ";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					
					if(pg_affected_rows($RS) > 0){
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
					}
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DE,$_SESSION['user_name'],$ARRAY_VS['version']);
				} else {
					$SQL = "SELECT id FROM titem_report WHERE id_report = $ID_ITEM";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$ARRAY = pg_fetch_array($RS);
					do{
						$APPL = trim(addslashes($_POST['applicable'.$ARRAY['id']]));
						$JUST = trim(addslashes($_POST['justify'.$ARRAY['id']]));

						$SQL = "UPDATE titem_report SET justification='$JUST', status='$APPL' ";
						$SQL .= "WHERE id = ".$ARRAY['id']." AND id_report IN ";
						$SQL .= "(SELECT id FROM treport WHERE id = $ID_ITEM AND id_instance = ".$_SESSION['INSTANCE_ID'].")";
						$RS_INSIDE = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					}while($ARRAY = pg_fetch_array($RS));
					if(pg_affected_rows($RS) > 0){
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
					}
				}
			}
		}
	}
}
header("Location:$DESTINATION_PAGE");
?>