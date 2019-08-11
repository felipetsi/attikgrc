<?php
session_start();
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THISPAGE = "control_run.php";
$DESTINATION_PAGE = "control.php";

$CODE_SUCCESSFUL_DE = 'SDCONT0001';
$CODE_SUCCESSFUL_IN = 'SICONT0001';
$CODE_SUCCESSFUL_DUPLIC = 'SUCONT0002';
$CODE_SUCCESSFUL_UP = 'SUCONT0001';
$CODE_SUCCESSFUL_ENABLE = 'SUCONT0003';
$CODE_SUCCESSFUL_DISABLE = 'SUCONT0004';
$CODE_SUCCESSFUL_DE_2 = 'SDCONT0002';
$CODE_SUCCESSFUL_DE_3 = 'SDCONT0003';

if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP'].'include/function.php');
	
	$PERMITIONS_NAME_1 = "create_control@";
	$PERMITIONS_NAME_2 = "read_own_control@";
	$PERMITIONS_NAME_3 = "read_all_control@";
	$PERMITIONS_NAME_5 = "revision_efficacy@";
	
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
			$NAME = str_replace("\'","''",substr(trim(addslashes($_POST['control_name'])),0,255)); $_SESSION['NAME_CONTROL'] = $NAME;
			$DETAIL = str_replace("\'","''",substr(trim(addslashes($_POST['control_detail'])),0,500)); $_SESSION['DETAIL_CONTROL'] = $DETAIL;	
			$PROCESS = trim(addslashes($_POST['control_process'])); $_SESSION['PROCESS'] = $PROCESS;
			$IMPLEMENTATION = preg_replace("/[^0-9\/-]/", "",(substr(trim(addslashes($_POST['implementation_date'])),0,10))); $_SESSION['IMPLEMENTATION'] = $IMPLEMENTATION;
			if(empty($IMPLEMENTATION)){ $IMPLEMENTATION = 'NULL';} else {$IMPLEMENTATION = "'".$IMPLEMENTATION."'";}
			$ENABLE_REVISION = substr(trim(addslashes($_POST['enable_revision'])),0,1); $_SESSION['ENA_REVISION'] = $ENABLE_REVISION;
			$GOAL = preg_replace("/[^0-9.]/", "",(trim(addslashes($_POST['goal'])))); $_SESSION['GOAL'] = $GOAL;
			if(empty($GOAL)){ $GOAL = 'NULL';}
			$METRIC = substr(trim(addslashes($_POST['metric'])),0,300); $_SESSION['METRIC'] = $METRIC;
			$METRIC_DETAIL = str_replace("\'","''",substr(trim(addslashes($_POST['metric_detail'])),0,500)); $_SESSION['METRIC_DETAIL'] = $METRIC_DETAIL;
			$APPLY_REVISION_FROM = preg_replace("/[^0-9\/-]/", "",(substr(trim(addslashes($_POST['apply_revision_from'])),0,10))); $_SESSION['APPLY_REVISION_FROM'] = $APPLY_REVISION_FROM;
			if(empty($APPLY_REVISION_FROM)){$APPLY_REVISION_FROM = date('Y-m-d');}
			$APPLY_REVISION_FROM = "'".$APPLY_REVISION_FROM."'";
			$SC_DAY = preg_replace("/[^0-9]/", "",(substr(trim(addslashes($_POST['scheduling_day'])),0,10))); $_SESSION['SCHEDULING_DAY'] = $SC_DAY;
			$SC_MONTH = preg_replace("/[^0-9]/", "",(substr(trim(addslashes($_POST['scheduling_month'])),0,10))); $_SESSION['SCHEDULING_MONTH'] = $SC_MONTH;
			$SC_WEEKDAY = preg_replace("/[^0-9]/", "",(substr(trim(addslashes($_POST['scheduling_weekday'])),0,10))); $_SESSION['SCHEDULING_WEEKDAY'] = $SC_WEEKDAY;
			$DEADLINE_REV = preg_replace("/[^0-9]/", "",(substr(trim(addslashes($_POST['deadline_revision'])),0,10))); $_SESSION['DEADLINE_REVISION'] = $DEADLINE_REV;
			if(empty($DEADLINE_REV)){ $DEADLINE_REV = 'NULL';}
			
			if(isset($_POST['control_status'])){$STATUS = 'd';} else {$STATUS = 'a';}
			
			if(isset($_POST['mark_deleteitem'])){$DELETE_ITEM = substr(trim(addslashes($_POST['mark_deleteitem'])),0,1);} else {$DELETE_ITEM = 0;}
			if(isset($_POST['mark_duplicateitem'])){$DUPLICATE_ITEM = substr(trim(addslashes($_POST['mark_duplicateitem'])),0,1);} else {$DUPLICATE_ITEM = 0;}
			if(isset($_POST['mark_finishitem'])){$CONTROL_FINISH = substr(trim(addslashes($_POST['mark_finishitem'])),0,1);} else {$CONTROL_FINISH = 0;}
			if(isset($_POST['mark_disableitem'])){$DISABLE_ITEM = substr(trim(addslashes($_POST['mark_disableitem'])),0,1);} else {$DISABLE_ITEM = 0;}
		}
		
		if(empty($ENABLE_REVISION)){
			$METRIC = "";
			$METRIC_DETAIL = "";
			$GOAL = 'NULL';
			$APPLY_REVISION_FROM = 'NULL';
			$SC_DAY = 'NULL';
			$SC_MONTH = 'NULL';
			$SC_WEEKDAY = 'NULL';
			$DEADLINE_REV = 'NULL';
		}

		if($DELETE_ITEM == "1"){
			for($f=0; $f < sizeof($ID_ITEM); $f++)
			{
				$SQL = "SELECT p.id FROM tprocess p, tcontrol c WHERE c.id_process = p.id AND c.id = $ID_ITEM[$f] AND ";
				$SQL .= "(p.id_responsible = ".$_SESSION['user_id']." OR p.id_risk_responsible = ".$_SESSION['user_id'].")";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
				$AFFECTED = pg_affected_rows($RS);

				if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2) === false) && ($AFFECTED == 0)){
					$_SESSION['MSG_TOP'] = 'LANG_YOU_NOT_HAVE_PERMISSION_DEL_SOME';
				} else {
					if(verifyDeleteCascade() == 'n'){
						$SQL = "SELECT id_control FROM tacontrol_task WHERE id_control = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$HAVE_DEPENDENCE = pg_affected_rows($RS);

						$SQL = "SELECT id_control FROM tarisk_control WHERE id_control = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$HAVE_DEPENDENCE += pg_affected_rows($RS);

					} else {
						// Delete ssociate table between Incident and Control
						$SQL = "DELETE FROM tarisk_control WHERE id_control = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

						// Delete ssociate table between Task and Control
						$SQL = "DELETE FROM tacontrol_task WHERE id_control = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						// Delete task
						$SQL = "DELETE FROM ttask_workflow ";
						$SQL .= "WHERE id IN (SELECT id_task FROM tacontrol_task WHERE id_control = $ID_ITEM[$f]) ";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DE_3,$_SESSION['user_name'],$ARRAY['name']);

						$HAVE_DEPENDENCE = 0;
					}
					if($HAVE_DEPENDENCE == 0) {
						$SQL = "SELECT name FROM tcontrol WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN (SELECT id FROM tarea WHERE ";
						$SQL .= "id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_ITEM[$f] ";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);

						$SQL = "DELETE FROM tacontrol_best_pratice ";
						$SQL .= "WHERE id_control = $ID_ITEM[$f] AND id_control IN (SELECT id FROM tcontrol WHERE id_process IN ";
						$SQL .= "(SELECT id FROM tprocess WHERE id_area IN ";
						$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID']."))) ";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						
						$SQL = "DELETE FROM trevision_control ";
						$SQL .= "WHERE id_control = $ID_ITEM[$f]  AND id_control IN (SELECT id FROM tcontrol WHERE id_process IN ";
						$SQL .= "(SELECT id FROM tprocess WHERE id_area IN ";
						$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID']."))) ";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

						$SQL = "DELETE FROM tcontrol ";
						$SQL .= "WHERE id = $ID_ITEM[$f]  AND id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
						$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) ";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','NAME_CONTROL','DETAIL_CONTROL','NAME','DETAIL','PROBABILITY', 'JUSTIFY_PROB','PROCESS','IMPLEMENTATION','GENERAL_IMPACT','ACTION','PREVISION','CONNECTED_ITEM','ID_ITEM_FROM_TASK', 'GOAL','METRIC','METRIC_DETAIL','ENA_REVISION', 'APPLY_REVISION_FROM','SCHEDULING_DAY', 'SCHEDULING_MONTH','SCHEDULING_WEEKDAY', 'DEADLINE_REVISION'));
						} else {
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
						}

						insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DE,$_SESSION['user_name'],$ARRAY['name']);
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_HAVE_DEPEDENCE';
					}
				}
			}
		} else {
			if((empty($NAME) || empty($PROCESS)) && ($DUPLICATE_ITEM == 0) && ($DISABLE_ITEM == 0)){
				$_SESSION['MSG_TOP'] = 'LANG_MSG_NEED_FILL_UNDERLINED';
			} else {
				if(empty($ID_ITEM[0]) && ($DUPLICATE_ITEM == 0)){
					$SQL = "INSERT INTO tcontrol(name, detail, id_process, metric, metric_detail, goal, implementation_date, ";
					$SQL .= "enable_revision,status, apply_revision_from, scheduling_day, scheduling_month, scheduling_weekday, deadline_revision) ";
					$SQL .= "VALUES ('$NAME', '$DETAIL', $PROCESS, '$METRIC', '$METRIC_DETAIL', $GOAL, $IMPLEMENTATION, ";
					$SQL .= "'$ENABLE_REVISION', '$STATUS', $APPLY_REVISION_FROM,$SC_DAY, $SC_MONTH, $SC_WEEKDAY,$DEADLINE_REV)";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$LAST_ID_ARRAY = pg_fetch_array(pg_query("SELECT CURRVAL('tcontrol_id_seq')"));
					$LAST_ID_CONTROL = $LAST_ID_ARRAY[0];
					
					if(pg_affected_rows($RS) > 0){
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
						destroySession(array('ID_SEL','NAME_CONTROL','DETAIL_CONTROL','NAME','DETAIL','PROBABILITY', 'JUSTIFY_PROB','PROCESS','IMPLEMENTATION','GENERAL_IMPACT','ACTION','PREVISION','CONNECTED_ITEM','ID_ITEM_FROM_TASK', 'GOAL','METRIC','METRIC_DETAIL','ENA_REVISION', 'APPLY_REVISION_FROM','SCHEDULING_DAY', 'SCHEDULING_MONTH','SCHEDULING_WEEKDAY', 'DEADLINE_REVISION'));
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
					}
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_IN,$_SESSION['user_name'],$NAME);
				} elseif($DUPLICATE_ITEM == 1){
					for($f=0; $f < sizeof($ID_ITEM); $f++){
						$SQL = "SELECT name FROM tcontrol WHERE id = $ID_ITEM[$f] AND id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
						$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID']."))";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);

						$SQL = "SELECT id FROM tcontrol WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
						$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND ";
						$SQL .= "name LIKE '%".$ARRAY['name']."%'";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$NUM_COPY = pg_affected_rows($RS);

						$SQL = "INSERT INTO tcontrol(id_process, name, detail, metric, metric_detail, goal, implementation_date, ";
						$SQL .= "enable_revision,status, apply_revision_from, scheduling_day, scheduling_month, scheduling_weekday,deadline_revision) ";
						$SQL .= "SELECT id_process, '"."copy($NUM_COPY) - ".$ARRAY['name']."', detail, metric, metric_detail, goal, ";
						$SQL .= "implementation_date, enable_revision,status, apply_revision_from, scheduling_day, scheduling_month, scheduling_weekday, ";
						$SQL .= "deadline_revision FROM tcontrol WHERE id = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$LAST_ID_ARRAY = pg_fetch_array(pg_query("SELECT CURRVAL('tcontrol_id_seq')"));
						$LAST_ID_CONTROL = $LAST_ID_ARRAY[0];
						
						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','NAME_CONTROL','DETAIL_CONTROL','NAME','DETAIL','PROBABILITY', 'JUSTIFY_PROB','PROCESS','IMPLEMENTATION','GENERAL_IMPACT','ACTION','PREVISION','CONNECTED_ITEM','ID_ITEM_FROM_TASK', 'GOAL','METRIC','METRIC_DETAIL','ENA_REVISION', 'APPLY_REVISION_FROM','SCHEDULING_DAY', 'SCHEDULING_MONTH','SCHEDULING_WEEKDAY', 'DEADLINE_REVISION'));
							insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DUPLIC,$_SESSION['user_name'],$ARRAY['name']);
						} else {
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
						}
						
						$SQL = "INSERT INTO tacontrol_best_pratice(id_control, id_control_best_pratice) ";
						$SQL .= "SELECT $LAST_ID_CONTROL, id_control_best_pratice FROM tacontrol_best_pratice WHERE id_control = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						
					}
				} /*elseif($DISABLE_ITEM == 1){
					for($f=0; $f < sizeof($ID_ITEM); $f++){
						$SQL = "SELECT name,status FROM tcontrol WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
						$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);
						
						if($ARRAY['status'] == 'a'){
							$SQL = "UPDATE tcontrol SET status = 'd' ";
							$SQL .= "WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
							$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_ITEM[$f]";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							$CODE_SUCESSFUL_ED = $CODE_SUCCESSFUL_DISABLE;
						} else {
							$SQL = "UPDATE tcontrol SET status = 'a' ";
							$SQL .= "WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
							$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_ITEM[$f]";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							$CODE_SUCESSFUL_ED = $CODE_SUCCESSFUL_ENABLE;
						}

						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','NAME_CONTROL','DETAIL_CONTROL','NAME','DETAIL','PROBABILITY', 'JUSTIFY_PROB','PROCESS','IMPLEMENTATION','GENERAL_IMPACT','ACTION','PREVISION','CONNECTED_ITEM','ID_ITEM_FROM_TASK', 'GOAL','METRIC','METRIC_DETAIL','ENA_REVISION', 'APPLY_REVISION_FROM','SCHEDULING_DAY', 'SCHEDULING_MONTH','SCHEDULING_WEEKDAY', 'DEADLINE_REVISION'));
							insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCESSFUL_ED,$_SESSION['user_name'],$ARRAY['name']);
						} else {
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
						}
						// Update Residual Risk value in risk that user this control
						$SQL = "SELECT r.id FROM trisk r WHERE id IN(SELECT id_risk FROM tarisk_control WHERE id_control = $ID_ITEM[$f]) AND r.status <> 'd'";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);
						if(pg_affected_rows($RS) > 0){
							do{
								updateResidualRisk($ARRAY['id']);
							}while($ARRAY = pg_fetch_array($RS));
						}
					}
				}*/ else {
					$SQL = "SELECT p.id FROM tprocess p, tcontrol c WHERE c.id_process = p.id AND c.id = $ID_ITEM[0] AND ";
					$SQL .= "(p.id_responsible = ".$_SESSION['user_id']." OR p.id_risk_responsible = ".$_SESSION['user_id'].")";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$AFFECTED = pg_affected_rows($RS);

					if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2) === false) && ($AFFECTED == 0)){
						$_SESSION['MSG_TOP'] = 'LANG_YOU_NOT_HAVE_PERMISSION';
					} else {
						if(empty($ENABLE_REVISION)){
							$METRIC = "";
							$METRIC_DETAIL = "";
							$GOAL = 'null';
						}
						
						$SQL = "UPDATE tcontrol SET id_process=$PROCESS, name='$NAME', detail='$DETAIL', metric='$METRIC', metric_detail='$METRIC_DETAIL', ";
						$SQL .= "goal=$GOAL, implementation_date=$IMPLEMENTATION, enable_revision='$ENABLE_REVISION', ";
						$SQL .= "apply_revision_from=$APPLY_REVISION_FROM, scheduling_day=$SC_DAY, scheduling_month=$SC_MONTH, ";
						$SQL .= "scheduling_weekday=$SC_WEEKDAY, deadline_revision=$DEADLINE_REV ";
						$SQL .= "WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
						$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_ITEM[0]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						
						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','NAME_CONTROL','DETAIL_CONTROL','NAME','DETAIL','PROBABILITY', 'JUSTIFY_PROB','PROCESS','IMPLEMENTATION','GENERAL_IMPACT','ACTION','PREVISION','CONNECTED_ITEM','ID_ITEM_FROM_TASK', 'GOAL','METRIC','METRIC_DETAIL','ENA_REVISION', 'APPLY_REVISION_FROM','SCHEDULING_DAY', 'SCHEDULING_MONTH','SCHEDULING_WEEKDAY', 'DEADLINE_REVISION'));
						} else {
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
						}
						
						insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_UP,$_SESSION['user_name'],$NAME);
					}
				}
			}
		}
	}
	header("Location:$DESTINATION_PAGE");
}
?>