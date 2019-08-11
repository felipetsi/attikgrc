<?php
session_start();
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THISPAGE = "incident_run.php";
$DESTINATION_PAGE = "incident.php";

$CODE_SUCCESSFUL_DE = 'SDINCI0001';
$CODE_SUCCESSFUL_IN = 'SIINCI0001';
$CODE_SUCCESSFUL_DUPLIC = 'SUINCI0002';
$CODE_SUCCESSFUL_UP = 'SUINCI0001';
$CODE_SUCCESSFUL_ENABLE = 'SUINCI0003';
$CODE_SUCCESSFUL_DISABLE = 'SUINCI0004';
$CODE_SUCCESSFUL_DE_2 = 'SDINCI0002';
$CODE_SUCCESSFUL_DE_3 = 'SDINCI0003';


if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP'].'include/function.php');
	
	$PERMITIONS_NAME_1 = "create_incident@";
	
	if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1) === false)){
		$_SESSION['MSG_TOP'] = 'LANG_YOU_NOT_HAVE_PERMISSION';
	} else {
		if($_SESSION['STATUS_MULT_SEL'] == 1){
			$f = 0;
			foreach ($_POST['optcheckitem'] as $key => $value) {
				$ID_ITEM[$f] = trim(addslashes($value));
				$f++;
			}
			if(isset($_POST['mark_deleteitem_view_form'])){$DELETE_ITEM = substr(trim(addslashes($_POST['mark_deleteitem_view_form'])),0,1);} else {$DELETE_ITEM = 0;}
			if(isset($_POST['mark_duplicateitem_view_form'])){$DUPLICATE_ITEM = substr(trim(addslashes($_POST['mark_duplicateitem_view_form'])),0,1);} else
				{$DUPLICATE_ITEM = 0;}
			if(isset($_POST['mark_disableitem_view_form'])){$DISABLE_ITEM = substr(trim(addslashes($_POST['mark_disableitem_view_form'])),0,1);} else {$DISABLE_ITEM = 0;}
		} else {
			$ID_ITEM[0] = trim(addslashes($_POST['id_item_selected']));$_SESSION['ID_SEL'] = $ID_ITEM[0];
			$NAME = str_replace("\'","''",substr(trim(addslashes($_POST['incident_name'])),0,255)); $_SESSION['INCIDENT_NAME'] = $NAME;
			$DETAIL = str_replace("\'","''",substr(trim(addslashes($_POST['incident_detail'])),0,1000)); $_SESSION['INCIDENT_DETAIL'] = $DETAIL;	
			$RESPONSIBLE = trim(addslashes($_POST['incident_responsible'])); $_SESSION['INCIDENT_RESPONSIBLE'] = $RESPONSIBLE;
			$ROOT_CAUSE = str_replace("\'","''",substr(trim(addslashes($_POST['root_cause'])),0,500)); $_SESSION['ROOT_CAUSE'] = $ROOT_CAUSE;
			$EVIDENCE = str_replace("\'","''",substr(trim(addslashes($_POST['evidence'])),0,500)); $_SESSION['EVIDENCE'] = $EVIDENCE;
			
			if(isset($_POST['mark_deleteitem'])){$DELETE_ITEM = substr(trim(addslashes($_POST['mark_deleteitem'])),0,1);} else {$DELETE_ITEM = 0;}
			if(isset($_POST['mark_duplicateitem'])){$DUPLICATE_ITEM = substr(trim(addslashes($_POST['mark_duplicateitem'])),0,1);} else {$DUPLICATE_ITEM = 0;}
			if(isset($_POST['mark_finishitem'])){$CONTROL_FINISH = substr(trim(addslashes($_POST['mark_finishitem'])),0,1);} else {$CONTROL_FINISH = 0;}
			if(isset($_POST['mark_disableitem'])){$DISABLE_ITEM = substr(trim(addslashes($_POST['mark_disableitem'])),0,1);} else {$DISABLE_ITEM = 0;}
			if(empty($RESPONSIBLE)){ $RESPONSIBLE = 'NULL';}
		}

		if($DELETE_ITEM == "1"){
			for($f=0; $f < sizeof($ID_ITEM); $f++)
			{
				if(verifyDeleteCascade() == 'n'){
					$SQL = "SELECT id_incident FROM tainicident_response_task WHERE id_incident = $ID_ITEM[$f]";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$HAVE_DEPENDENCE = pg_affected_rows($RS);
				} else {
					// Delete risks
					$SQL = "DELETE FROM taincident_risk ";
					$SQL .= "WHERE id_incident = $ID_ITEM[$f]";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DE_2,$_SESSION['user_name'],$ARRAY['name']);
					
					// Delete record with association with this icident
					$SQL = "DELETE FROM tainicident_response_task ";
					$SQL .= "WHERE id_incident = $ID_ITEM[$f]";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

					// Delete task
					$SQL = "DELETE FROM ttask_workflow ";
					$SQL .= "WHERE id IN (SELECT id_task FROM tainicident_response_task WHERE id_incident = $ID_ITEM[$f]) AND id_instance = ".$_SESSION['INSTANCE_ID'];
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DE_3,$_SESSION['user_name'],$ARRAY['name']);
					
					$HAVE_DEPENDENCE = 0;
				}
				if($HAVE_DEPENDENCE == 0) {
					$SQL = "SELECT name FROM tincident WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND id = $ID_ITEM[$f]";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$ARRAY = pg_fetch_array($RS);

					$SQL = "DELETE FROM tincident ";
					$SQL .= "WHERE id = $ID_ITEM[$f] AND id_instance = ".$_SESSION['INSTANCE_ID'];
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

					if(pg_affected_rows($RS) > 0){
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
						destroySession(array('ID_SEL','INCIDENT_NAME','INCIDENT_DETAIL','INCIDENT_RESPONSIBLE','ROOT_CAUSE','EVIDENCE', 'CRET_CONT_INP','CRET_CONT_SEL','ID_ITEM_FROM_TASK'));
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
					}

					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DE,$_SESSION['user_name'],$ARRAY['name']);
				} else {
					$_SESSION['MSG_TOP'] = 'LANG_MSG_HAVE_DEPEDENCE';
				}
			}
		} else {
			if(empty($NAME) && ($DUPLICATE_ITEM == 0) && ($DISABLE_ITEM == 0)){
				$_SESSION['MSG_TOP'] = 'LANG_MSG_NEED_FILL_UNDERLINED';
			} else {
				if(empty($ID_ITEM[0]) && ($DUPLICATE_ITEM == 0)){
					$SQL = "INSERT INTO tincident(name, detail, id_responsible, id_person_register, id_instance, root_cause, evidence, status) ";
					$SQL .= "VALUES ('$NAME', '$DETAIL', $RESPONSIBLE, ".$_SESSION['user_id'].", ".$_SESSION['INSTANCE_ID'].", '$ROOT_CAUSE', ";
					$SQL .= "'$EVIDENCE', 'o')";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					if(pg_affected_rows($RS) > 0){
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
						destroySession(array('ID_SEL','INCIDENT_NAME','INCIDENT_DETAIL','INCIDENT_RESPONSIBLE','ROOT_CAUSE','EVIDENCE', 'CRET_CONT_INP','CRET_CONT_SEL','ID_ITEM_FROM_TASK'));
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
					}
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_IN,$_SESSION['user_name'],$NAME);
				} elseif($DUPLICATE_ITEM == 1){
					for($f=0; $f < sizeof($ID_ITEM); $f++){
						$SQL = "SELECT name FROM tincident WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND id = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);

						$SQL = "SELECT id FROM tincident WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND name LIKE '%".$ARRAY['name']."%'";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$NUM_COPY = pg_affected_rows($RS);

						$SQL = "INSERT INTO tincident(name, detail, id_responsible, id_person_register, id_instance, root_cause, evidence, status) ";
						$SQL .= "SELECT '"."copy($NUM_COPY) - ".$ARRAY['name']."', detail, id_responsible, ".$_SESSION['user_id'].", id_instance, ";
						$SQL .= "root_cause, evidence, 'o' ";
						$SQL .= "FROM tincident WHERE id = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','INCIDENT_NAME','INCIDENT_DETAIL','INCIDENT_RESPONSIBLE','ROOT_CAUSE','EVIDENCE', 'CRET_CONT_INP','CRET_CONT_SEL','ID_ITEM_FROM_TASK'));
							insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DUPLIC,$_SESSION['user_name'],$ARRAY['name']);
						} else {
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
						}
					}
				} else {
					$SQL = "UPDATE tincident SET name='$NAME', detail='$DETAIL', id_responsible=$RESPONSIBLE, root_cause='$ROOT_CAUSE', evidence='$EVIDENCE' ";
					$SQL .= "WHERE id = $ID_ITEM[0] ";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

					if(pg_affected_rows($RS) > 0){
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
						destroySession(array('ID_SEL','INCIDENT_NAME','INCIDENT_DETAIL','INCIDENT_RESPONSIBLE','ROOT_CAUSE','EVIDENCE', 'CRET_CONT_INP','CRET_CONT_SEL','ID_ITEM_FROM_TASK'));
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
					}
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_UP,$_SESSION['user_name'],$NAME);
				}
			}
		}
	}
	header("Location:$DESTINATION_PAGE");
}
?>