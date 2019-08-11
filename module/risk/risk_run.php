<?php
session_start();
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THISPAGE = "risk_run.php";
$DESTINATION_PAGE = "risk.php";

$CODE_SUCCESSFUL_DE = 'SDRISK0001';
$CODE_SUCCESSFUL_IN = 'SIRISK0001';
$CODE_SUCCESSFUL_DUPLIC = 'SURISK0002';
$CODE_SUCCESSFUL_UP = 'SURISK0001';
$CODE_SUCCESSFUL_ENABLE = 'SURISK0003';
$CODE_SUCCESSFUL_DISABLE = 'SURISK0004';
$CODE_SUCCESSFUL_DE_2 = 'SDRISK0002';
$CODE_SUCCESSFUL_DE_3 = 'SDRISK0003';

if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP'].'include/function.php');
	
	$PERMITIONS_NAME_1 = "create_risk@";
	$PERMITIONS_NAME_2 = "read_own_risk@";
	$PERMITIONS_NAME_3 = "read_all_risk@";
	$PERMITIONS_NAME_5 = "treatment_risk@";
	
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
			$NAME = str_replace("\'","''",substr(trim(addslashes($_POST['risk_name'])),0,255)); $_SESSION['NAME_RISK'] = $NAME;
			$DETAIL = str_replace("\'","''",substr(trim(addslashes($_POST['risk_detail'])),0,500)); $_SESSION['DETAIL_RISK'] = $DETAIL;
			$PROCESS = trim(addslashes($_POST['risk_process'])); $_SESSION['PROCESS'] = $PROCESS;
			$LABEL = trim(addslashes($_POST['label'])); $_SESSION['LABEL'] = $LABEL;
			$IMPACT = substr(trim(addslashes($_POST['desc_general_impact'])),0,500); $_SESSION['GENERAL_IMPACT'] = $IMPACT;
			$IMPACT_TYPE = trim(addslashes($_POST['impact_type'])); $_SESSION['IMPACT_TYPE'] = $IMPACT_TYPE;
			// Load impact to get impact sent
			$SQL = "SELECT id,name FROM timpact WHERE id_impact_type =".$IMPACT_TYPE;
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			do{
				$IMPACT_VALUE[$ARRAY['id']] = preg_replace("/[^0-9.]/", "",(trim(addslashes($_POST['impact_'.$ARRAY['id'].'']))));
					$_SESSION['IMPACT'.$ARRAY['id'].''] = $IMPACT_VALUE[$ARRAY['id']];
				$JUSTIFY_IMPACT[$ARRAY['id']] = trim(addslashes($_POST['justify_impact_text'.$ARRAY['id'].'']));
					$_SESSION['JUSTIFY_IMPACT'.$ARRAY['id'].''] = $JUSTIFY_IMPACT[$ARRAY['id']];
				
				if($ARRAY['name'] != 'financial'){
					$IMPACT_VAL[$ARRAY['id']] = $IMPACT_VALUE[$ARRAY['id']];
				} else {
					if(empty($IMPACT_VALUE[$ARRAY['id']])){
						$IMPACT_VALUE[$ARRAY['id']] = 0;
					}
					$financial = $IMPACT_VALUE[$ARRAY['id']];
					$idFinc = $ARRAY['id'];
				}
			}while($ARRAY = pg_fetch_array($RS));
			$PROBABILITY = trim(addslashes($_POST['probability'])); $_SESSION['PROBABILITY'] = $PROBABILITY;
			$JUSTIFY_PROBABILITY = trim(addslashes($_POST['justify_probability_text'])); $_SESSION['JUSTIFY_PROB'] = $JUSTIFY_PROBABILITY;
			
			$STATUS = 'a';
			
			if(isset($_POST['mark_deleteitem'])){$DELETE_ITEM = substr(trim(addslashes($_POST['mark_deleteitem'])),0,1);} else {$DELETE_ITEM = 0;}
			if(isset($_POST['mark_duplicateitem'])){$DUPLICATE_ITEM = substr(trim(addslashes($_POST['mark_duplicateitem'])),0,1);} else {$DUPLICATE_ITEM = 0;}
			if(isset($_POST['mark_finishitem'])){$CONTROL_FINISH = substr(trim(addslashes($_POST['mark_finishitem'])),0,1);} else {$CONTROL_FINISH = 0;}
			if(isset($_POST['mark_disableitem'])){$DISABLE_ITEM = substr(trim(addslashes($_POST['mark_disableitem'])),0,1);} else {$DISABLE_ITEM = 0;}
		}

		if($DELETE_ITEM == "1"){
			for($f=0; $f < sizeof($ID_ITEM); $f++)
			{
				$SQL = "SELECT p.id FROM tprocess p, trisk r WHERE r.id_process = p.id AND r.id = $ID_ITEM[$f] AND ";
				$SQL .= "(p.id_responsible = ".$_SESSION['user_id']." OR p.id_risk_responsible = ".$_SESSION['user_id'].")";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
				$AFFECTED = pg_affected_rows($RS);

				if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2) === false) && ($AFFECTED == 0)){
					$_SESSION['MSG_TOP'] = 'LANG_YOU_NOT_HAVE_PERMISSION_DEL_SOME';
				} else {
					if(verifyDeleteCascade() == 'n'){
						$SQL = "SELECT id_risk FROM tarisk_task WHERE id_risk = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$HAVE_DEPENDENCE = pg_affected_rows($RS);

						$SQL = "SELECT id_risk FROM taincident_risk WHERE id_risk = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$HAVE_DEPENDENCE += pg_affected_rows($RS);
						
						$SQL = "SELECT id_risk FROM tarisk_control WHERE id_risk = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$HAVE_DEPENDENCE += pg_affected_rows($RS);

					} else {
						// Delete ssociate table between Incident and Risk
						$SQL = "DELETE FROM taincident_risk WHERE id_risk = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						// Delete ssociate table between Task and Risk
						$SQL = "DELETE FROM tarisk_task WHERE id_risk = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						// Delete task
						$SQL = "DELETE FROM ttask_workflow ";
						$SQL .= "WHERE id IN (SELECT id_task FROM tarisk_task WHERE id_risk = $ID_ITEM[$f]) ";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						// Delete relation with control
						$SQL = "DELETE FROM tarisk_control ";
						$SQL .= "WHERE id_risk = $ID_ITEM[$f] ";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						// Delete relation with impact control
						$SQL = "DELETE FROM tarisk_control_impact ";
						$SQL .= "WHERE id_risk = $ID_ITEM[$f] ";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DE_3,$_SESSION['user_name'],$ARRAY['name']);

						$HAVE_DEPENDENCE = 0;
					}
					if($HAVE_DEPENDENCE == 0) {
						$SQL = "SELECT name FROM trisk WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN (SELECT id FROM tarea WHERE ";
						$SQL .= "id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_ITEM[$f] ";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);

						$SQL = "DELETE FROM tarisk_impact ";
						$SQL .= "WHERE id_risk = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

						$SQL = "DELETE FROM trisk ";
						$SQL .= "WHERE id = $ID_ITEM[$f] AND id_process IN (SELECT id FROM tprocess WHERE id_area IN (SELECT id FROM tarea WHERE ";
						$SQL .= "id_instance=".$_SESSION['INSTANCE_ID'].")) ";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','NAME_RISK','DETAIL_RISK', 'PROBABILITY','JUSTIFY_PROB','PROCESS','GENERAL_IMPACT','CRET_CONT_INP', 'CRET_CONT_SEL','ID_ITEM_FROM_TASK','LABEL'));
							foreach($IMPACT_VALUE as $key => $value){
								destroySession(array("JUSTIFY_IMPACT$key","IMPACT$key"));
							}
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
			if((empty($NAME) || empty($PROCESS) || empty($IMPACT_TYPE)) && ($DUPLICATE_ITEM == 0) && ($DISABLE_ITEM == 0)){
				$_SESSION['MSG_TOP'] = 'LANG_MSG_NEED_FILL_UNDERLINED';
			} else {
				if(empty($ID_ITEM[0]) && ($DUPLICATE_ITEM == 0)){
					// Load relevancy of area and process
					$SQL = "SELECT a.relevancy AS area, p.relevancy AS process FROM tarea a, tprocess p WHERE ";
					$SQL .= "p.id_area = a.id AND p.id = $PROCESS AND a.id_instance=".$_SESSION['INSTANCE_ID'];
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$ARRAY = pg_fetch_array($RS);
					
					$FR = calcRiskFactor($IMPACT_VAL,$PROBABILITY,$ARRAY['area'],$ARRAY['process'],$financial);
					$RR = $FR;
					$SQL = "INSERT INTO trisk(id_process, name, detail, id_impact_type, impact, risk_factor, risk_residual, probability, probability_justification, ";
					$SQL .= "status, creation_time, rlabel) ";
					$SQL .= "VALUES ($PROCESS, '$NAME', '$DETAIL', $IMPACT_TYPE, '$IMPACT', $FR, $RR, $PROBABILITY, '$JUSTIFY_PROBABILITY', '$STATUS', CURRENT_TIMESTAMP, '$LABEL')";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$LAST_ID_ARRAY = pg_fetch_array(pg_query("SELECT CURRVAL('trisk_id_seq')"));
					$LAST_ID_RISK = $LAST_ID_ARRAY[0];
					
					foreach($IMPACT_VALUE as $key => $value){
						$SQL = "INSERT INTO tarisk_impact(id_risk, id_impact, value, justification) ";
						$SQL .= "VALUES ($LAST_ID_RISK, $key, $value, '$JUSTIFY_IMPACT[$key]')";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					}
					
					if(pg_affected_rows($RS) > 0){
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
						destroySession(array('ID_SEL','NAME_RISK','DETAIL_RISK', 'PROBABILITY','JUSTIFY_PROB','PROCESS','GENERAL_IMPACT','CRET_CONT_INP', 'CRET_CONT_SEL','ID_ITEM_FROM_TASK','LABEL'));
						foreach($IMPACT_VALUE as $key => $value){
							destroySession(array("JUSTIFY_IMPACT$key","IMPACT$key"));
						}
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
					}
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_IN,$_SESSION['user_name'],$NAME);
				} elseif($DUPLICATE_ITEM == 1){
					for($f=0; $f < sizeof($ID_ITEM); $f++){
						$SQL = "SELECT name FROM trisk WHERE id = $ID_ITEM[$f] AND id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
						$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID']."))";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);

						$SQL = "SELECT id FROM trisk WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
						$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND ";
						$SQL .= "name LIKE '%".$ARRAY['name']."%'";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$NUM_COPY = pg_affected_rows($RS);

						$SQL = "INSERT INTO trisk(id_process, name, detail, id_impact_type, impact, risk_factor, risk_residual, probability, probability_justification, status, ";
						$SQL .= "creation_time, rlabel) ";
						$SQL .= "SELECT id_process, '"."copy($NUM_COPY) - ".$ARRAY['name']."', detail, id_impact_type, impact, risk_factor, risk_factor, probability, ";
						$SQL .= "probability_justification, 'a', CURRENT_TIMESTAMP, rlabel ";
						$SQL .= "FROM trisk WHERE id = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$LAST_ID_ARRAY = pg_fetch_array(pg_query("SELECT CURRVAL('trisk_id_seq')"));
						$LAST_ID_RISK = $LAST_ID_ARRAY[0];
						
						$SQL = "INSERT INTO tarisk_impact(id_risk, id_impact, value, justification) ";
						$SQL .= "SELECT $LAST_ID_RISK, id_impact, value, justification FROM tarisk_impact WHERE id_risk = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						
						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','NAME_RISK','DETAIL_RISK', 'PROBABILITY','JUSTIFY_PROB','PROCESS','GENERAL_IMPACT','CRET_CONT_INP', 'CRET_CONT_SEL','ID_ITEM_FROM_TASK','LABEL'));
							foreach($IMPACT_VALUE as $key => $value){
								destroySession(array("JUSTIFY_IMPACT$key","IMPACT$key"));
							}
							insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DUPLIC,$_SESSION['user_name'],$ARRAY['name']);
						} else {
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
						}
					}
				} /*elseif($DISABLE_ITEM == 1){
					for($f=0; $f < sizeof($ID_ITEM); $f++){
						$SQL = "SELECT name,status FROM trisk WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
						$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);
						
						if($ARRAY['status'] != 'd'){
							$SQL = "UPDATE trisk SET status = 'd' ";
							$SQL .= "WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
							$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_ITEM[$f]";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							$CODE_SUCESSFUL_ED = $CODE_SUCCESSFUL_DISABLE;
						} else {
							$SQL = "SELECT id_control FROM tarisk_control ";
							$SQL .= "WHERE id_risk = $ID_ITEM[$f]";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							if(pg_affected_rows($RS) > 0){
								$status = 'm';
							} else {
								$status = 'a';
							}
							
							$SQL = "UPDATE trisk SET status = '$status' ";
							$SQL .= "WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
							$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_ITEM[$f]";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							$CODE_SUCESSFUL_ED = $CODE_SUCCESSFUL_ENABLE;
						}

						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','NAME_RISK','DETAIL_RISK', 'PROBABILITY','JUSTIFY_PROB','PROCESS','GENERAL_IMPACT','CRET_CONT_INP', 'CRET_CONT_SEL','ID_ITEM_FROM_TASK'));
							insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCESSFUL_ED,$_SESSION['user_name'],$ARRAY['name']);
						} else {
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
						}
					}
				}*/ else {
					$SQL = "SELECT p.id FROM tprocess p, trisk r WHERE r.id_process = p.id AND r.id = $ID_ITEM[0] AND ";
					$SQL .= "(p.id_responsible = ".$_SESSION['user_id']." OR p.id_risk_responsible = ".$_SESSION['user_id'].")";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$AFFECTED = pg_affected_rows($RS);

					if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2) === false) && ($AFFECTED == 0)){
						$_SESSION['MSG_TOP'] = 'LANG_YOU_NOT_HAVE_PERMISSION_DEL_SOME';
					} else {
					
						// Load relevancy of area and process
						$SQL = "SELECT a.relevancy AS area, p.relevancy AS process FROM tarea a, tprocess p WHERE ";
						$SQL .= "p.id_area = a.id AND p.id = $PROCESS AND a.id_instance=".$_SESSION['INSTANCE_ID'];
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);

						// Delete and insert again the impact to update
						$SQL = "DELETE FROM tarisk_impact ";
						$SQL .= "WHERE id_risk = $ID_ITEM[0]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

						foreach($IMPACT_VALUE as $key => $value){
							$SQL = "INSERT INTO tarisk_impact(id_risk, id_impact, value, justification) ";
							$SQL .= "VALUES ($ID_ITEM[0], $key, $value, '$JUSTIFY_IMPACT[$key]')";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						}
						
						// Select to load exist control to calculate residual risk
						$SQL ="SELECT i.id_impact, SUM(i.value) AS value, SUM(c.probability) AS probability, ";
						$SQL .= "COUNT(i.id_control) AS qtd_control ";
						$SQL .= "FROM tarisk_control c, tarisk_control_impact i ";
						$SQL .= "WHERE c.id_control = i.id_control AND c.id_control IN (SELECT id FROM tcontrol WHERE status = 'a' OR status = 'r') AND ";
						$SQL .= "c.id_risk = $ID_ITEM[0] AND i.id_risk = $ID_ITEM[0] ";
						$SQL .= "GROUP BY i.id_impact";
						
						$RS = pg_query($conn, $SQL);
						$ARRAY_MIT = pg_fetch_array($RS);
						do{
							if($ARRAY_MIT['id_impact'] != $idFinc)
							{
								if($ARRAY_MIT['value'] == "null"){
									$IMPACT_MIT_VAL[$ARRAY_MIT['id_impact']] = 0;
								} else {
									$IMPACT_MIT_VAL[$ARRAY_MIT['id_impact']] = $ARRAY_MIT['value'];
								}
							} else {
								$mitFinc = $ARRAY_MIT['value'];
							}
							$mitProb = $ARRAY_MIT['probability'];
							$qtd_control = $ARRAY_MIT['qtd_control'];
						}while($ARRAY_MIT = pg_fetch_array($RS));
						
						

						$FR = calcRiskFactor($IMPACT_VAL,$PROBABILITY,$ARRAY['area'],$ARRAY['process'],$financial);
						$RR = calcResidualRisk($IMPACT_VAL,$PROBABILITY,$ARRAY['area'],$ARRAY['process'],$financial, $IMPACT_MIT_VAL,$mitProb,$mitFinc,$qtd_control);
							
						$SQL = "UPDATE trisk SET id_process=$PROCESS, name='$NAME', detail='$DETAIL', impact='$IMPACT', risk_factor=$FR,risk_residual= $RR, ";
						$SQL .= "probability=$PROBABILITY, probability_justification='$JUSTIFY_PROBABILITY', rlabel='$LABEL' ";
						$SQL .= "WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
						$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_ITEM[0]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','NAME_RISK','DETAIL_RISK', 'PROBABILITY','JUSTIFY_PROB','PROCESS','GENERAL_IMPACT','CRET_CONT_INP', 'CRET_CONT_SEL','ID_ITEM_FROM_TASK','LABEL'));
							foreach($IMPACT_VALUE as $key => $value){
								destroySession(array("JUSTIFY_IMPACT$key","IMPACT$key"));
							}
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