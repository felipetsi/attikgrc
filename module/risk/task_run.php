<?php
session_start();
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THISPAGE = "task_run.php";
$DESTINATION_PAGE = $_SESSION['LAST_PAGE'];
$CODE_SUCCESSFUL_DE = 'SDTASK0001';
$CODE_SUCCESSFUL_IN = 'SITASK0001';
$CODE_SUCCESSFUL_DUPLIC = 'SUTASK0002';
$CODE_SUCCESSFUL_UP = 'SUTASK0001';

$CODE_SUCCESSFUL_DISABLE = 'SUTASK0003';
$CODE_FAILED_NAME_EXISTS = 'FUTASK0002';
$CODE_FAILED_USER_CURRENT = 'FUTASK0003';

if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP'].'include/function.php');
	
	$PERMITIONS_NAME_1 = "create_task@";
	$PERMITIONS_NAME_4 = "approver_task@";
	$PERMITIONS_NAME_5 = "treatment_task@";
	
	if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1) === false) && 
	   (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_4) === false) &&
	   ((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_5) === false))){
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
		} else {	
			$ID_ITEM[0] = trim(addslashes($_POST['id_item_selected']));$_SESSION['ID_SEL'] = $ID_ITEM[0];
			// verify if have POST of variable from other page	
			if(empty($ID_ITEM[0])){
					$ID_ITEM[0] = trim(addslashes($_POST['id_task_selected']));	
			}
			
			$NAME = str_replace("\'","''",substr(trim(addslashes($_POST['name'])),0,255)); $_SESSION['NAME'] = $NAME;
			$DETAIL = str_replace("\'","''",substr(trim(addslashes($_POST['detail'])),0,1000)); $_SESSION['DETAIL'] = $DETAIL;
			$ACTION = str_replace("\'","''",substr(trim(addslashes($_POST['action'])),0,1000));$_SESSION['ACTION'] = $ACTION;
			$RESPONSIBLE = trim(addslashes($_POST['responsible'])); $_SESSION['RESPONSIBLE'] = $RESPONSIBLE;
			$APPROVER = trim(addslashes($_POST['approver'])); $_SESSION['APPROVER'] = $APPROVER;
			$PREVISION_TIME = preg_replace("/[^0-9\/-]/", "",(substr(trim(addslashes($_POST['prevision_time'])),0,10))); $_SESSION['PREVISION'] = $PREVISION_TIME;
			$CONNECTED_ITEM = trim(addslashes($_POST['connect_others'])); $_SESSION['CONNECTED_ITEM'] = $CONNECTED_ITEM;
			if(isset($_POST['mark_deleteitem'])){$DELETE_ITEM = substr(trim(addslashes($_POST['mark_deleteitem'])),0,1);}  else {$DELETE_ITEM = 0;}
			if(isset($_POST['mark_deletetask'])){$DELETE_TASK_RELATED = substr(trim(addslashes($_POST['mark_deletetask'])),0,1); 
												if($DELETE_TASK_RELATED == 1){$DELETE_ITEM = 1;}}
			if(isset($_POST['mark_duplicateitem'])){$DUPLICATE_ITEM = substr(trim(addslashes($_POST['mark_duplicateitem'])),0,1);} else {$DUPLICATE_ITEM = 0;}
			if(isset($_POST['mark_duplicatetask'])){$DUPLICATE_TASK_RELATED = substr(trim(addslashes($_POST['mark_duplicatetask'])),0,1); 
												if($DUPLICATE_TASK_RELATED == 1){ $DUPLICATE_ITEM = 1;} else {$DUPLICATE_TASK_RELATED = 0;}}
				else {$DUPLICATE_TASK_RELATED = 0;}
			if(isset($_POST['mark_finishitem'])){$CONTROL_FINISH = substr(trim(addslashes($_POST['mark_finishitem'])),0,1);} else {$CONTROL_FINISH = 0;}
			if(isset($_POST['id_project_selected'])){$ID_PROJECT = trim(addslashes($_POST['id_project_selected']));} else {$ID_PROJECT = 0;}
			if(isset($_POST['id_control_selected'])){$ID_CONTROL_BP = trim(addslashes($_POST['id_control_selected']));} else {$ID_CONTROL_BP = 0;}
			if(isset($_POST['id_risk_selected'])){$ID_RISK = trim(addslashes($_POST['id_risk_selected'])); $_SESSION['ID_ITEM_FROM_TASK'] = $ID_RISK;} 
				else {$ID_RISK = 0;}
			if(isset($_POST['id_control_mech_selected'])){$ID_CONTROL = trim(addslashes($_POST['id_control_mech_selected'])); $_SESSION['ID_ITEM_FROM_TASK'] = $ID_CONTROL;} 
				else {$ID_CONTROL = 0;}
			if(isset($_POST['id_incident_selected'])){
				$ID_INCIDENT = trim(addslashes($_POST['id_incident_selected'])); $_SESSION['ID_ITEM_FROM_TASK'] = $ID_INCIDENT;
				$RESP_TYPE = trim(addslashes($_POST['response']));
				} else {$ID_INCIDENT = 0;}
			if(isset($_POST['id_nonconformity_selected'])){
				$ID_NONC = trim(addslashes($_POST['id_nonconformity_selected'])); $_SESSION['ID_ITEM_FROM_TASK'] = $ID_NONC;
				$RESP_TYPE = trim(addslashes($_POST['response']));
				} else {$ID_NONC = 0;}
			
			if($ID_PROJECT != 0){
				$SOURCE = 'project';
			} elseif ($ID_RISK != 0){
				$SOURCE = 'riskmanager';
			} elseif ($ID_CONTROL != 0){
				$SOURCE = 'control';
			} elseif ($ID_INCIDENT != 0){
				$SOURCE = 'incident';
			} elseif ($ID_NONC != 0){
				$SOURCE = 'nonconformity';
			} else {
				$SOURCE = substr(trim(addslashes($_POST['source'])),0,1000); $_SESSION['SOURCE'] = $SOURCE;
			}
		}
		// Load creator to insert action
		$CREATOR = $_SESSION['user_id'];

		if($DELETE_ITEM == "1"){
			for($f=0; $f < sizeof($ID_ITEM); $f++)
			{
				$SQL = "SELECT name, id_creator FROM ttask_workflow WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND id = $ID_ITEM[$f]";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
				$ARRAY = pg_fetch_array($RS);

				if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1) === false) || 
				   (($ARRAY['id_creator'] != $_SESSION['user_id']) && (!empty($ARRAY['id_creator'])))) {
					$_SESSION['MSG_TOP'] = 'LANG_YOU_NOT_HAVE_PERMISSION_DEL_SOME';
				} else {
					// delete relashionship between task and project
					$SQL = "DELETE FROM taproject_control_best_pratice_task ";
					$SQL .= "WHERE id_task = $ID_ITEM[$f] AND id_instance = ".$_SESSION['INSTANCE_ID'];
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					
					// delete relashionship between task and risk
					$SQL = "DELETE FROM tarisk_task ";
					$SQL .= "WHERE id_task = $ID_ITEM[$f] AND id_task IN (SELECT id FROM ttask_workflow WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					
					// delete relashionship between task and control
					$SQL = "DELETE FROM tacontrol_task ";
					$SQL .= "WHERE id_task = $ID_ITEM[$f] AND id_task IN (SELECT id FROM ttask_workflow WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")";
					$RSDELTA = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					
					// delete relashionship between task and incident
					$SQL = "DELETE FROM tainicident_response_task ";
					$SQL .= "WHERE id_task = $ID_ITEM[$f]  AND id_task IN (SELECT id FROM ttask_workflow WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")";
					$RSDELTA = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					
					// delete relashionship between task and nonconformity
					$SQL = "DELETE FROM tanonconformity_response_task ";
					$SQL .= "WHERE id_task = $ID_ITEM[$f]  AND id_task IN (SELECT id FROM ttask_workflow WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")";
					$RSDELTA = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					
					$SQL = "DELETE FROM ttask_workflow ";
					$SQL .= "WHERE id = $ID_ITEM[$f] AND id_instance = ".$_SESSION['INSTANCE_ID'];
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

					if(pg_affected_rows($RS) > 0){
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
						destroySession(array('ID_SEL','SOURCE','PREVISION','APPROVER','RESPONSIBLE','ACTION','DETAIL','NAME'));
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
					}

					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DE,$_SESSION['user_name'],$ARRAY['name']);
				}
			}
		} else {
			if(((empty($NAME)) || (empty($RESPONSIBLE))) && (($DUPLICATE_ITEM == 0) && ($CONTROL_FINISH == 0))){
				$_SESSION['MSG_TOP'] = 'LANG_MSG_NEED_FILL_UNDERLINED';
			} else {
				if (!empty($PREVISION_TIME)){
					$SQL_COMP_PREV = "'$PREVISION_TIME'";
				} else {
					$SQL_COMP_PREV = "NULL";
				}
				
				if(empty($ID_ITEM[0]) && ($DUPLICATE_ITEM == 0)){
					if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1) === false)) {
						$_SESSION['MSG_TOP'] = 'LANG_YOU_NOT_HAVE_PERMISSION';
					} else {
						if(empty($ACTION)) {
							$STATUS = 'o';
						} else {
							$STATUS = 't';
						}
						$SQL = "SELECT p.id FROM tperson p WHERE p.status = 'a' AND ";
						$SQL .= "p.id_instance = ".$_SESSION['INSTANCE_ID'];
						$SQL .= " AND p.id_profile IN ";
						$SQL .= "	(SELECT id FROM tprofile WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND id IN ";
						$SQL .= "	(SELECT id_profile FROM taprofile_itemprofile WHERE id_itemprofile IN ";
						$SQL .= "	(SELECT id FROM titemprofile WHERE name = 'approver_task'))) ";
						$SQL .= "	ORDER BY random() LIMIT 1";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);
						if((pg_affected_rows($RS) == 0) || (empty($ARRAY['id']))){
							$SQL = "SELECT id_person AS id FROM tespecial_person WHERE id_instance = ".$_SESSION['INSTANCE_ID'];
							$SQL .= "AND name LIKE 'defau_appr'";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							$ARRAY = pg_fetch_array($RS);
							if((pg_affected_rows($RS) == 0) || (empty($ARRAY['id']))){
								$SQL = "SELECT p.id FROM tperson p WHERE p.status != 'e' AND ";
								$SQL .= "p.id_instance = ".$_SESSION['INSTANCE_ID'];
								$SQL .= "ORDER BY random() LIMIT 1";
								$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								$ARRAY = pg_fetch_array($RS);
							}
							
							$_SESSION['MSG_TOP'] = 'LANG_MSG_DONT_HAVE_APPROVER';
						} 
						$APPROVER = $ARRAY['id'];
						$SQL = "INSERT INTO ttask_workflow(name, detail, id_instance, id_creator, id_responsible, id_approver, source, status, ";
						$SQL .= "prevision_date, creation_date, action) ";
						$SQL .= "VALUES ('$NAME', '$DETAIL', ".$_SESSION['INSTANCE_ID'].", $CREATOR, $RESPONSIBLE, $APPROVER, '$SOURCE', '$STATUS', ";
						$SQL .= "$SQL_COMP_PREV, CURRENT_DATE, '$ACTION')";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$LAST_ID_ARRAY = pg_fetch_array(pg_query("SELECT CURRVAL('ttask_workflow_id_seq')"));
						$LAST_ID_TASK = $LAST_ID_ARRAY[0];

						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','SOURCE','PREVISION','APPROVER','RESPONSIBLE','ACTION','DETAIL','NAME'));
						} else {
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
						}
						insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_IN,$_SESSION['user_name'],$NAME);

						// Insert task vinculated with Project
						if(($ID_CONTROL_BP != 0) && ($ID_PROJECT != 0)){
							$SQL = "INSERT INTO taproject_control_best_pratice_task(id_project, id_control_best_pratice, id_task, id_instance) ";
							$SQL .= "VALUES ($ID_PROJECT, $ID_CONTROL_BP, $LAST_ID_TASK, ".$_SESSION['INSTANCE_ID'].")";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							if(!empty($CONNECTED_ITEM)){
								$ARRAY_CONTROL_CONN = explode(",",$CONNECTED_ITEM);
								for($i = 0; $i < sizeof($ARRAY_CONTROL_CONN);$i++){
									$SQL = "SELECT id FROM tcontrol_best_pratice WHERE item = '".$ARRAY_CONTROL_CONN[$i]."' ";
									$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
									$ARRAY = pg_fetch_array($RS);

									if($ARRAY['id'] != $ID_CONTROL_BP){
										$SQL = "INSERT INTO taproject_control_best_pratice_task(id_project, id_control_best_pratice, id_task, id_instance) ";
										$SQL .= "VALUES ($ID_PROJECT, ".$ARRAY['id'].", $LAST_ID_TASK, ".$_SESSION['INSTANCE_ID'].")";
										$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
									}
								}
							}
						}

						// Insert task vinculated with Risk
						if($ID_RISK != 0){
							$SQL = "INSERT INTO tarisk_task(id_risk, id_task) ";
							$SQL .= "VALUES ($ID_RISK,$LAST_ID_TASK)";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							if(!empty($CONNECTED_ITEM)){
								$ARRAY_CONTROL_CONN = explode(",",$CONNECTED_ITEM);
								for($i = 0; $i < sizeof($ARRAY_CONTROL_CONN);$i++){
									$SQL = "SELECT id FROM trisk WHERE id = '".(INT)$ARRAY_CONTROL_CONN[$i]."' ";
									$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
									$ARRAY = pg_fetch_array($RS);

									if($ARRAY['id'] != $ID_RISK){
										$SQL = "INSERT INTO tarisk_task(id_risk, id_task) ";
										$SQL .= "VALUES (".$ARRAY['id'].", $LAST_ID_TASK)";
										$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
									}
								}
							}
						}
						// Insert task vinculated with Control
						if($ID_CONTROL != 0){
							$SQL = "INSERT INTO tacontrol_task(id_control, id_task) ";
							$SQL .= "VALUES ($ID_CONTROL,$LAST_ID_TASK)";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							if(!empty($CONNECTED_ITEM)){
								$ARRAY_CONTROL_CONN = explode(",",$CONNECTED_ITEM);
								for($i = 0; $i < sizeof($ARRAY_CONTROL_CONN);$i++){
									$SQL = "SELECT id FROM tcontrol WHERE id = '".(INT)$ARRAY_CONTROL_CONN[$i]."' ";
									$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
									$ARRAY = pg_fetch_array($RS);

									if($ARRAY['id'] != $ID_CONTROL){
										$SQL = "INSERT INTO tacontrol_task(id_control, id_task) ";
										$SQL .= "VALUES (".$ARRAY['id'].", $LAST_ID_TASK)";
										$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
									}
								}
							}
						}

						// Insert task vinculated with Incident
						if($ID_INCIDENT != 0){
							$SQL = "INSERT INTO tainicident_response_task(id_incident, id_task, response_type) ";
							$SQL .= "VALUES ($ID_INCIDENT,$LAST_ID_TASK,'$RESP_TYPE')";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							if(!empty($CONNECTED_ITEM)){
								$ARRAY_CONTROL_CONN = explode(",",$CONNECTED_ITEM);
								for($i = 0; $i < sizeof($ARRAY_CONTROL_CONN);$i++){
									$SQL = "SELECT id FROM tincident WHERE id = '".(INT)$ARRAY_CONTROL_CONN[$i]."' ";
									$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
									$ARRAY = pg_fetch_array($RS);

									if($ARRAY['id'] != $ID_INCIDENT){
										$SQL = "INSERT INTO tainicident_response_task(id_incident, id_task, response_type) ";
										$SQL .= "VALUES (".$ARRAY['id'].", $LAST_ID_TASK, '$RESP_TYPE')";
										$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
									}
								}
							}
						}

						// Insert task vinculated with Incident
						if($ID_NONC != 0){
							$SQL = "INSERT INTO tanonconformity_response_task(id_nonconformity, id_task, response_type) ";
							$SQL .= "VALUES ($ID_NONC,$LAST_ID_TASK,'$RESP_TYPE')";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							if(!empty($CONNECTED_ITEM)){
								$ARRAY_CONTROL_CONN = explode(",",$CONNECTED_ITEM);
								for($i = 0; $i < sizeof($ARRAY_CONTROL_CONN);$i++){
									$SQL = "SELECT id FROM tnonconformity WHERE id = '".(INT)$ARRAY_CONTROL_CONN[$i]."' ";
									$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
									$ARRAY = pg_fetch_array($RS);

									if($ARRAY['id'] != $ID_NONC){
										$SQL = "INSERT INTO tanonconformity_response_task(id_nonconformity, id_task, response_type) ";
										$SQL .= "VALUES (".$ARRAY['id'].", $LAST_ID_TASK, '$RESP_TYPE')";
										$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
									}
								}
							}
						}
						
					}
				} elseif($DUPLICATE_ITEM == 1){
					if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1) === false)) {
						$_SESSION['MSG_TOP'] = 'LANG_YOU_NOT_HAVE_PERMISSION';
					} else {
						for($f=0; $f < sizeof($ID_ITEM); $f++){
							$SQL = "SELECT name FROM ttask_workflow WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND id = $ID_ITEM[$f]";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							$ARRAY = pg_fetch_array($RS);

							$SQL = "SELECT id FROM ttask_workflow WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND name LIKE '%".$ARRAY['name']."%'";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							$NUM_COPY = pg_affected_rows($RS);

							$SQL = "INSERT INTO ttask_workflow(name, detail, id_instance, id_creator, id_responsible, id_approver, source, status, prevision_date, ";
							$SQL .= "creation_date, action) ";
							$SQL .= "SELECT '".'copy('.$NUM_COPY.') - '.$ARRAY['name']."', detail, id_instance, $CREATOR, id_responsible, id_approver, source, ";
							$SQL .= "'o', prevision_date, CURRENT_DATE, action ";
							$SQL .= "FROM ttask_workflow WHERE id = $ID_ITEM[$f]";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							$LAST_ID_ARRAY = pg_fetch_array(pg_query("SELECT CURRVAL('ttask_workflow_id_seq')"));
							$LAST_ID_TASK = $LAST_ID_ARRAY[0];
						
							if($DUPLICATE_TASK_RELATED == 1){
								if( $ID_RISK > 0){
									$SQL = "INSERT INTO tarisk_task(id_risk, id_task) ";
									$SQL .= "SELECT id_risk, $LAST_ID_TASK FROM tarisk_task ";
									$SQL .= "WHERE id_task = $ID_ITEM[$f] AND id_task IN ";
									$SQL .= "(SELECT id FROM ttask_workflow WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")";
									$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								} elseif($ID_CONTROL > 0){
									$SQL = "INSERT INTO tacontrol_task(id_control, id_task) ";
									$SQL .= "SELECT id_control, $LAST_ID_TASK FROM tacontrol_task ";
									$SQL .= "WHERE id_task = $ID_ITEM[$f] AND id_task IN ";
									$SQL .= "(SELECT id FROM ttask_workflow WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")";
									$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								} elseif($ID_INCIDENT > 0){
									$SQL = "INSERT INTO tainicident_response_task(id_incident, id_task, response_type) ";
									$SQL .= "SELECT id_incident, $LAST_ID_TASK, response_type FROM tainicident_response_task ";
									$SQL .= "WHERE id_task = $ID_ITEM[$f] AND id_task IN ";
									$SQL .= "(SELECT id FROM ttask_workflow WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")";
									$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								} elseif($ID_NONC > 0){
									$SQL = "INSERT INTO tanonconformity_response_task(id_nonconformity, id_task, response_type) ";
									$SQL .= "SELECT id_nonconformity, $LAST_ID_TASK, response_type FROM tanonconformity_response_task ";
									$SQL .= "WHERE id_task = $ID_ITEM[$f] AND id_task IN ";
									$SQL .= "(SELECT id FROM ttask_workflow WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")";
									$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								} elseif ($ID_PROJECT > 0){
									$SQL = "INSERT INTO taproject_control_best_pratice_task(id_project, id_control_best_pratice, id_task, id_instance) ";
									$SQL .= "SELECT id_project, id_control_best_pratice, $LAST_ID_TASK, id_instance FROM  ";
									$SQL .= "taproject_control_best_pratice_task WHERE id_task = $ID_ITEM[$f] AND id_instance = ".$_SESSION['INSTANCE_ID'];
									$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								}
							}
							
							if(pg_affected_rows($RS) > 0){
								$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
								destroySession(array('ID_SEL','SOURCE','PREVISION','APPROVER','RESPONSIBLE','ACTION','DETAIL','NAME'));
								insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DUPLIC,$_SESSION['user_name'],$ARRAY['name']);
							} else {
								$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
							}
						}
					}
				} else {
					
					if ($ID_ITEM[0] > 0){
						$SQL = "SELECT status, id_approver, id_creator, id_responsible FROM ttask_workflow WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND ";
						$SQL .= "id = $ID_ITEM[0]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);
						$STATUS = $ARRAY['status'];
						
						/*if (((($_SESSION['user_id'] != $ARRAY['id_responsible']) || 
							((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_5)) !== false )) &&
							(($ARRAY['status'] == 'o') || ($ARRAY['status'] == 't'))) && ($CONTROL_FINISH == 1)) {*/
						if ((($_SESSION['user_id'] != $ARRAY['id_responsible']) || 
							((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_5)) === false )) &&
							(($ARRAY['status'] == 'o') || ($ARRAY['status'] == 't')) && 
							($CONTROL_FINISH == 1)) {
							$_SESSION['MSG_TOP'] = 'LANG_MSG_ONLY_RESPONSIBLE_CAN';
						} else {
							
							
							if ((($_SESSION['user_id'] == $ARRAY['id_creator']) || ($_SESSION['user_id'] == $ARRAY['id_approver'])) && 
								(($ARRAY['status'] == 'o') || ($ARRAY['status'] == 't')) &&
								((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) !== false )){
								$SQL_COMPL_CRET =  ", name = '$NAME', detail = '$DETAIL', id_responsible = $RESPONSIBLE, source = '$SOURCE' ";

								// Verify if have multi-item related
								if(!empty($CONNECTED_ITEM)){
									//Risk
									if($ID_RISK != 0){
										// Delete all except the main item control of task.
										$SQL = "DELETE FROM tarisk_task ";
										$SQL .= "WHERE id_task = $ID_ITEM[0] AND id_risk != $ID_RISK AND ";
										$SQL .= "id_task IN (SELECT id FROM ttask_workflow WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")";
										$RSDELTA = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

										$ARRAY_CONTROL_CONN = explode(",",$CONNECTED_ITEM);

										for($i = 0; $i < sizeof($ARRAY_CONTROL_CONN);$i++){
											$SQL = "SELECT id FROM trisk WHERE id = '".(INT)$ARRAY_CONTROL_CONN[$i]."' ";
											$RSDELTA = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
											$ARRAY_DELTA = pg_fetch_array($RSDELTA);

											if(($ARRAY_DELTA['id'] != $ID_RISK) && (!empty($ARRAY_DELTA['id']))){
												$SQL = "INSERT INTO tarisk_task(id_risk, id_task) ";
												$SQL .= "VALUES (".$ARRAY_DELTA['id'].", $ID_ITEM[0])";
												$RSDELTA = pg_query($conn, $SQL);
											}
										}
									}
									//Control
									if($ID_CONTROL != 0){
										// Delete all except the main item control of task.tacontrol_task
										$SQL = "DELETE FROM tacontrol_task ";
										$SQL .= "WHERE id_task = $ID_ITEM[0] AND id_control != $ID_CONTROL AND ";
										$SQL .= "id_task IN (SELECT id FROM ttask_workflow WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")";
										$RSDELTA = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

										$ARRAY_CONTROL_CONN = explode(",",$CONNECTED_ITEM);

										for($i = 0; $i < sizeof($ARRAY_CONTROL_CONN);$i++){
											$SQL = "SELECT id FROM tcontrol WHERE id = '".(INT)$ARRAY_CONTROL_CONN[$i]."' ";
											$RSDELTA = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
											$ARRAY_DELTA = pg_fetch_array($RSDELTA);

											if(($ARRAY_DELTA['id'] != $ID_CONTROL) && (!empty($ARRAY_DELTA['id']))){
												$SQL = "INSERT INTO tacontrol_task(id_control, id_task) ";
												$SQL .= "VALUES (".$ARRAY_DELTA['id'].", $ID_ITEM[0])";
												$RSDELTA = pg_query($conn, $SQL);
											}
										}
									}

									//Incident
									if($ID_INCIDENT != 0){
										// Delete all except the main item control of task.tacontrol_task
										$SQL = "DELETE FROM tainicident_response_task ";
										$SQL .= "WHERE id_task = $ID_ITEM[0] AND id_incident != $ID_INCIDENT AND ";
										$SQL .= "id_task IN (SELECT id FROM ttask_workflow WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")";
										$RSDELTA = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

										$ARRAY_CONTROL_CONN = explode(",",$CONNECTED_ITEM);

										for($i = 0; $i < sizeof($ARRAY_CONTROL_CONN);$i++){
											$SQL = "SELECT id FROM tincident WHERE id = '".(INT)$ARRAY_CONTROL_CONN[$i]."' ";
											$RSDELTA = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
											$ARRAY_DELTA = pg_fetch_array($RSDELTA);

											if(($ARRAY_DELTA['id'] != $ID_INCIDENT) && (!empty($ARRAY_DELTA['id']))){
												$SQL = "INSERT INTO tainicident_response_task(id_incident, id_task, response_type) ";
												$SQL .= "VALUES (".$ARRAY_DELTA['id'].", $ID_ITEM[0], '$RESP_TYPE')";
												$RSDELTA = pg_query($conn, $SQL);
											}
										}
									}

									//Nonconformity
									if($ID_NONC != 0){
										// Delete all except the main item control of task.tacontrol_task
										$SQL = "DELETE FROM tanonconformity_response_task ";
										$SQL .= "WHERE id_task = $ID_ITEM[0] AND id_nonconformity != $ID_NONC AND ";
										$SQL .= "id_task IN (SELECT id FROM ttask_workflow WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")";
										$RSDELTA = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

										$ARRAY_CONTROL_CONN = explode(",",$CONNECTED_ITEM);

										for($i = 0; $i < sizeof($ARRAY_CONTROL_CONN);$i++){
											$SQL = "SELECT id FROM tnonconformity WHERE id = '".(INT)$ARRAY_CONTROL_CONN[$i]."' ";
											$RSDELTA = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
											$ARRAY_DELTA = pg_fetch_array($RSDELTA);

											if(($ARRAY_DELTA['id'] != $ID_NONC) && (!empty($ARRAY_DELTA['id']))){
												$SQL = "INSERT INTO tanonconformity_response_task(id_nonconformity, id_task, response_type) ";
												$SQL .= "VALUES (".$ARRAY_DELTA['id'].", $ID_ITEM[0], '$RESP_TYPE')";
												$RSDELTA = pg_query($conn, $SQL);
											}
										}
									}

									// Project
									if($ID_PROJECT != 0){
										// Delete all except the main item control of task.
										$SQL = "DELETE FROM taproject_control_best_pratice_task WHERE id_control_best_pratice != $ID_CONTROL_BP AND ";
										$SQL .= "id_task = $ID_ITEM[0] AND id_project = $ID_PROJECT AND id_instance = ".$_SESSION['INSTANCE_ID'];
										$RSDELTA = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

										$ARRAY_CONTROL_CONN = explode(",",$CONNECTED_ITEM);
										for($i = 0; $i < sizeof($ARRAY_CONTROL_CONN);$i++){
											$SQL = "SELECT id FROM tcontrol_best_pratice WHERE item = '".$ARRAY_CONTROL_CONN[$i]."' ";
											$RSDELTA = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
											$ARRAY_DELTA = pg_fetch_array($RSDELTA);

											if($ARRAY['id'] != $ID_CONTROL_BP){
												$SQL = "INSERT INTO taproject_control_best_pratice_task(id_project, id_control_best_pratice, id_task, id_instance) ";
												$SQL .= "VALUES ($ID_PROJECT, ".$ARRAY_DELTA['id'].", $ID_ITEM[0], ".$_SESSION['INSTANCE_ID'].")";
												$RSDELTA = pg_query($conn, $SQL);
											}
										}
									}
								}
							}
							if (($_SESSION['user_id'] == $ARRAY['id_responsible']) && 
								((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_5)) !== false )){
								if((($ARRAY['status'] == 'o') || (($ARRAY['status'] == 't'))) && ($CONTROL_FINISH == 1)) {
									$STATUS = 'f';
									$SQL_COMPL_RESP = ", action = '$ACTION', prevision_date = $SQL_COMP_PREV, execution_date = CURRENT_DATE ";
										// Update status of incident and noncoformity, if have
										//Incident
										$SQL = "SELECT id_incident,i.status FROM tainicident_response_task, tincident i WHERE i.id = id_incident AND id_task = $ID_ITEM[0]";
										$RSIMPROV = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
										$ARRAYIMPROV = pg_fetch_array($RSIMPROV);
										if(pg_affected_rows($RSIMPROV) > 0){
											do{
												$SQL = "SELECT id FROM ttask_workflow WHERE id IN (SELECT id_task FROM tainicident_response_task ";
												$SQL .= "WHERE id_incident = ".$ARRAYIMPROV['id_incident'].") AND status != '$STATUS' AND id !=  $ID_ITEM[0]";
												$RSIMPROVIN = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
												if(pg_affected_rows($RSIMPROVIN) == 0){
													if($ARRAYIMPROV['status'] != $STATUS){
														$SQL = "UPDATE tincident SET status='$STATUS' ";
														$SQL .= "WHERE id = ".$ARRAYIMPROV['id_incident'];
														$RSIMPROVIN = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
													}
												}
											}while($ARRAYIMPROV = pg_fetch_array($RSIMPROV));
										}
										//Nonconformity
										$SQL = "SELECT id_nonconformity,n.status FROM tanonconformity_response_task, tnonconformity n WHERE n.id = id_nonconformity ";
										$SQL .= "AND id_task = $ID_ITEM[0]";
										$RSIMPROV = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
										$ARRAYIMPROV = pg_fetch_array($RSIMPROV);
										if(pg_affected_rows($RSIMPROV) > 0){
											do{
												$SQL = "SELECT id FROM ttask_workflow WHERE id IN (SELECT id_task FROM tanonconformity_response_task ";
												$SQL .= "WHERE id_nonconformity = ".$ARRAYIMPROV['id_nonconformity'].") AND status != '$STATUS' AND id !=  $ID_ITEM[0]";
												$RSIMPROVIN = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
												if(pg_affected_rows($RSIMPROVIN) == 0){
													if($ARRAYIMPROV['status'] != $STATUS){
														$SQL = "UPDATE tnonconformity SET status='$STATUS' ";
														$SQL .= "WHERE id = ".$ARRAYIMPROV['id_nonconformity'];
														$RSIMPROVIN = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
													}
												}
											}while($ARRAYIMPROV = pg_fetch_array($RSIMPROV));
										}
										// END Update status of incident and noncoformity, if have
								} elseif (!empty($ACTION)){
									$STATUS = 't';
									$SQL_COMPL_RESP = ", action = '$ACTION', prevision_date = $SQL_COMP_PREV ";
									// Update status of incident and noncoformity, if have
									$SQL = "SELECT id_incident,i.status FROM tainicident_response_task, tincident i WHERE i.id = id_incident AND id_task = $ID_ITEM[0]";
									$RSIMPROV = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
									$ARRAYIMPROV = pg_fetch_array($RSIMPROV);
									if(pg_affected_rows($RSIMPROV) > 0){
										do{
											if($ARRAYIMPROV['status'] != $STATUS){
												$SQL = "UPDATE tincident SET status='$STATUS' ";
												$SQL .= "WHERE id = ".$ARRAYIMPROV['id_incident'];
												$RSIMPROVIN = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
											}
										}while($ARRAYIMPROV = pg_fetch_array($RSIMPROV));
									}
									$SQL = "SELECT id_nonconformity,n.status FROM tanonconformity_response_task, tnonconformity n WHERE n.id = id_nonconformity ";
									$SQL .= "AND id_task = $ID_ITEM[0]";
									$RSIMPROV = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
									$ARRAYIMPROV = pg_fetch_array($RSIMPROV);
									if(pg_affected_rows($RSIMPROV) > 0){
										do{
											if($ARRAYIMPROV['status'] != $STATUS){
												$SQL = "UPDATE tnonconformity SET status='$STATUS' ";
												$SQL .= "WHERE id = ".$ARRAYIMPROV['id_nonconformity'];
												$RSIMPROVIN = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
											}
										}while($ARRAYIMPROV = pg_fetch_array($RSIMPROV));
									}
									// END Update status of incident and noncoformity, if have
								} elseif (!empty($SQL_COMP_PREV)){
									$SQL_COMPL_RESP = ", prevision_date = $SQL_COMP_PREV ";
								}
							}
							if (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_4) !== false){
								if(($ARRAY['status'] == 'f') && ($CONTROL_FINISH == 1)) {
									$STATUS = 'c';
									$SQL_COMPL_APPR = ", id_approver = ".$_SESSION['user_id']." ";
										// Update status of incident and noncoformity, if have
										//Incident
										$SQL = "SELECT id_incident,i.status FROM tainicident_response_task, tincident i WHERE i.id = id_incident ";
										$SQL .= "AND id_task = $ID_ITEM[0]";
										$RSIMPROV = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
										$ARRAYIMPROV = pg_fetch_array($RSIMPROV);
										if(pg_affected_rows($RSIMPROV) > 0){
											do{
												$SQL = "SELECT id FROM ttask_workflow WHERE id IN (SELECT id_task FROM tainicident_response_task ";
												$SQL .= "WHERE id_incident = ".$ARRAYIMPROV['id_incident'].") AND status != '$STATUS' AND id !=  $ID_ITEM[0]";
												$RSIMPROVIN = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
												if(pg_affected_rows($RSIMPROVIN) == 0){
													if($ARRAYIMPROV['status'] != $STATUS){
														$SQL = "UPDATE tincident SET status='$STATUS' ";
														$SQL .= "WHERE id = ".$ARRAYIMPROV['id_incident'];
														$RSIMPROVIN = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
													}
												}
											}while($ARRAYIMPROV = pg_fetch_array($RSIMPROV));
										}
										//Nonconformity
										$SQL = "SELECT id_nonconformity,n.status FROM tanonconformity_response_task, tnonconformity n WHERE ";
										$SQL .= "n.id = id_nonconformity AND id_task = $ID_ITEM[0]";
										$RSIMPROV = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
										$ARRAYIMPROV = pg_fetch_array($RSIMPROV);
										if(pg_affected_rows($RSIMPROV) > 0){
											do{
												$SQL = "SELECT id FROM ttask_workflow WHERE id IN (SELECT id_task FROM tanonconformity_response_task WHERE ";
												$SQL .= "id_nonconformity = ".$ARRAYIMPROV['id_nonconformity'].") AND status != '$STATUS' AND id !=  $ID_ITEM[0]";
												$RSIMPROVIN = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
												if(pg_affected_rows($RSIMPROVIN) == 0){
													if($ARRAYIMPROV['status'] != $STATUS){
														$SQL = "UPDATE tnonconformity SET status='$STATUS' ";
														$SQL .= "WHERE id = ".$ARRAYIMPROV['id_nonconformity'];
														$RSIMPROVIN = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
													}
												}
											}while($ARRAYIMPROV = pg_fetch_array($RSIMPROV));
										}
										// END Update status of incident and noncoformity, if have
								} elseif (($ARRAY['status'] == 'c') && ($CONTROL_FINISH == 1)) {
									$STATUS = 'o';
									$SQL_COMPL_APPR = ", id_approver = ".$_SESSION['user_id'].", execution_date = NULL ";
										// Update status of incident and noncoformity, if have
										//Incident
										$SQL = "SELECT id_incident,i.status FROM tainicident_response_task, tincident i WHERE i.id = id_incident ";
										$SQL .= "AND id_task = $ID_ITEM[0]";
										$RSIMPROV = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
										$ARRAYIMPROV = pg_fetch_array($RSIMPROV);
										if(pg_affected_rows($RSIMPROV) > 0){
											do{
												$SQL = "SELECT id FROM ttask_workflow WHERE id IN (SELECT id_task FROM tainicident_response_task ";
												$SQL .= "WHERE id_incident = ".$ARRAYIMPROV['id_incident'].") AND status != '$STATUS' AND id !=  $ID_ITEM[0]";
												$RSIMPROVIN = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
												if(pg_affected_rows($RSIMPROVIN) == 0){
													if($ARRAYIMPROV['status'] != $STATUS){
														$SQL = "UPDATE tincident SET status='$STATUS' ";
														$SQL .= "WHERE id = ".$ARRAYIMPROV['id_incident'];
														$RSIMPROVIN = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
													}
												}
											}while($ARRAYIMPROV = pg_fetch_array($RSIMPROV));
										}
										//Nonconformity
										$SQL = "SELECT id_nonconformity,n.status FROM tanonconformity_response_task, tnonconformity n WHERE ";
										$SQL .= "n.id = id_nonconformity AND id_task = $ID_ITEM[0]";
										$RSIMPROV = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
										$ARRAYIMPROV = pg_fetch_array($RSIMPROV);
										if(pg_affected_rows($RSIMPROV) > 0){
											do{
												$SQL = "SELECT id FROM ttask_workflow WHERE id IN (SELECT id_task FROM tanonconformity_response_task WHERE ";
												$SQL .= "id_nonconformity = ".$ARRAYIMPROV['id_nonconformity'].") AND status != '$STATUS' AND id !=  $ID_ITEM[0]";
												$RSIMPROVIN = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
												if(pg_affected_rows($RSIMPROVIN) == 0){
													if($ARRAYIMPROV['status'] != $STATUS){
														$SQL = "UPDATE tnonconformity SET status='$STATUS' ";
														$SQL .= "WHERE id = ".$ARRAYIMPROV['id_nonconformity'];
														$RSIMPROVIN = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
													}
												}
											}while($ARRAYIMPROV = pg_fetch_array($RSIMPROV));
										}
										// END Update status of incident and noncoformity, if have
								} elseif(!empty($APPROVER)) {
									$SQL_COMPL_APPR = ", id_approver = $APPROVER ";
								}
							}


							$SQL = "UPDATE ttask_workflow SET status = '$STATUS' $SQL_COMPL_CRET ";
							$SQL .= "$SQL_COMPL_RESP $SQL_COMPL_APPR ";
							$SQL .= "WHERE id = $ID_ITEM[0] AND id_instance = ".$_SESSION['INSTANCE_ID'];
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

							if(pg_affected_rows($RS) > 0){
								$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
								destroySession(array('ID_SEL','SOURCE','PREVISION','APPROVER','RESPONSIBLE','ACTION','DETAIL','NAME'));
							} else {
								$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
							}
							insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_UP,$_SESSION['user_name'],$NAME);
						}
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_NO_SELECTED';
					}
				}
			}
		}
	}
	header("Location:$DESTINATION_PAGE");
}
?>