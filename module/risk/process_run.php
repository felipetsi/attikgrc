<?php
session_start();
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THISPAGE = "process_run.php";
$DESTINATION_PAGE = "process.php";

$CODE_SUCCESSFUL_DE = 'SDPROC0001';
$CODE_SUCCESSFUL_IN = 'SIPROC0001';
$CODE_SUCCESSFUL_DUPLIC = 'SUPROC0002';
$CODE_SUCCESSFUL_UP = 'SUPROC0001';
$CODE_SUCCESSFUL_ENABLE = 'SUPROC0003';
$CODE_SUCCESSFUL_DISABLE = 'SUPROC0004';
$CODE_SUCCESSFUL_DE_2 = 'SDPROC0002';
$CODE_SUCCESSFUL_DE_3 = 'SDPROC0003';
$CODE_SUCCESSFUL_DE_4 = 'SDPROC0004';

if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP'].'include/function.php');
	
	$PERMITIONS_NAME_1 = "create_process@";
	
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
			$NAME = str_replace("\'","''",substr(trim(addslashes($_POST['name'])),0,255)); $_SESSION['NAME'] = $NAME;
			$DETAIL = str_replace("\'","''",substr(trim(addslashes($_POST['detail'])),0,1000)); $_SESSION['DETAIL'] = $DETAIL;
			$RESPONSIBLE = trim(addslashes($_POST['responsible'])); $_SESSION['RESPONSIBLE'] = $RESPONSIBLE;
			$RISK_RESPONSIBLE = trim(addslashes($_POST['responsible_risk'])); $_SESSION['RESPONSIBLE_RISK'] = $RISK_RESPONSIBLE;
			$AREA = trim(addslashes($_POST['area'])); $_SESSION['AREA'] = $AREA;
			$RELEVANCY = substr(trim(addslashes($_POST['relevancy'])),0,2); $_SESSION['RELEVANCY'] = $RELEVANCY;
			if(isset($_POST['status'])){$STATUS = 'd';} else {$STATUS = 'a';}
			
			if(isset($_POST['mark_deleteitem'])){$DELETE_ITEM = substr(trim(addslashes($_POST['mark_deleteitem'])),0,1);} else {$DELETE_ITEM = 0;}
			if(isset($_POST['mark_duplicateitem'])){$DUPLICATE_ITEM = substr(trim(addslashes($_POST['mark_duplicateitem'])),0,1);} else {$DUPLICATE_ITEM = 0;}
			if(isset($_POST['mark_finishitem'])){$CONTROL_FINISH = substr(trim(addslashes($_POST['mark_finishitem'])),0,1);} else {$CONTROL_FINISH = 0;}
			if(isset($_POST['mark_disableitem'])){$DISABLE_ITEM = substr(trim(addslashes($_POST['mark_disableitem'])),0,1);} else {$DISABLE_ITEM = 0;}
		}

		if($DELETE_ITEM == "1"){
			for($f=0; $f < sizeof($ID_ITEM); $f++)
			{
				if(verifyDeleteCascade() == 'n'){
					$SQL = "SELECT id_process FROM trisk WHERE id_process = $ID_ITEM[$f]";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$HAVE_DEPENDENCE = pg_affected_rows($RS);
				} else {
					// Delete incident
					/*$SQL = "DELETE FROM tincident ";
					$SQL .= "WHERE id IN (SELECT id_incident FROM taincident_risk WHERE id_risk IN ";
					$SQL .= "(SELECT id FROM trisk WHERE id_process = $ID_ITEM[$f]))";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DE_2,$_SESSION['user_name'],$ARRAY['name']);*/
					// Delete ssociate table between Incident and Risk
					$SQL = "DELETE FROM taincident_risk WHERE id_risk IN ";
					$SQL .= "(SELECT id FROM trisk WHERE id_process = $ID_ITEM[$f])";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					
					// Delete ssociate table between Task and Risk
					$SQL = "DELETE FROM tarisk_task WHERE id_risk IN ";
					$SQL .= "(SELECT id FROM trisk WHERE id_process = $ID_ITEM[$f])";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					// Delete task
					$SQL = "DELETE FROM ttask_workflow ";
					$SQL .= "WHERE id IN (SELECT id_task FROM tarisk_task WHERE id_risk IN ";
					$SQL .= "(SELECT id FROM trisk WHERE id_process = $ID_ITEM[$f]))";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DE_3,$_SESSION['user_name'],$ARRAY['name']);
					
					// Delete risks
					$SQL = "DELETE FROM trisk ";
					$SQL .= "WHERE id_process = $ID_ITEM[$f]";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DE_4,$_SESSION['user_name'],$ARRAY['name']);
					
					$HAVE_DEPENDENCE = 0;
				}
				if($HAVE_DEPENDENCE == 0) {
					$SQL = "SELECT name FROM tprocess WHERE id_area IN (SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].") ";
					$SQL .= "AND id = $ID_ITEM[$f]";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$ARRAY = pg_fetch_array($RS);

					$SQL = "DELETE FROM tprocess ";
					$SQL .= "WHERE id = $ID_ITEM[$f] AND id_area IN (SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].") ";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

					if(pg_affected_rows($RS) > 0){
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
						destroySession(array('ID_SEL','RELEVANCY','RESPONSIBLE','DETAIL','NAME','RESPONSIBLE_RISK','AREA'));
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
					}

					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DE,$_SESSION['user_name'],$ARRAY['name']);
				} else {
					$_SESSION['MSG_TOP'] = 'LANG_MSG_HAVE_DEPEDENCE';
				}
			}
		} else {
			if((empty($NAME) ||empty($NAME) || empty($RESPONSIBLE) || empty($RISK_RESPONSIBLE) || empty($AREA) || empty($RELEVANCY)) 
			   && ($DUPLICATE_ITEM == 0) && ($DISABLE_ITEM == 0)){
				$_SESSION['MSG_TOP'] = 'LANG_MSG_NEED_FILL_UNDERLINED';
			} else {
				unset($_SESSION['NAME']);
				unset($_SESSION['DETAIL']);
				unset($_SESSION['RESPONSIBLE']);
				unset($_SESSION['RELEVANCY']);
				unset($_SESSION['RESPONSIBLE_RISK']);
				unset($_SESSION['AREA']);
				if(empty($ID_ITEM[0]) && ($DUPLICATE_ITEM == 0)){
					$SQL = "INSERT INTO tprocess(name, detail, id_responsible,id_risk_responsible, id_area, relevancy, status) ";
					$SQL .= "VALUES ('$NAME', '$DETAIL', $RESPONSIBLE, $RISK_RESPONSIBLE, $AREA, $RELEVANCY, '$STATUS')";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					if(pg_affected_rows($RS) > 0){
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
						destroySession(array('ID_SEL','RELEVANCY','RESPONSIBLE','DETAIL','NAME','RESPONSIBLE_RISK','AREA'));
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
					}
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_IN,$_SESSION['user_name'],$NAME);
				} elseif($DUPLICATE_ITEM == 1){
					for($f=0; $f < sizeof($ID_ITEM); $f++){
						$SQL = "SELECT name FROM tprocess WHERE id = $ID_ITEM[$f] AND id_area IN (SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);

						$SQL = "SELECT id FROM tprocess WHERE id_area IN (SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].") AND ";
						$SQL .= "name LIKE '%".$ARRAY['name']."%'";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$NUM_COPY = pg_affected_rows($RS);

						$SQL = "INSERT INTO tprocess(name, detail, id_responsible,id_risk_responsible, id_area, relevancy, status) ";
						$SQL .= "SELECT '"."copy($NUM_COPY) - ".$ARRAY['name']."', detail, id_responsible, id_risk_responsible, id_area, relevancy, status ";
						$SQL .= "FROM tprocess WHERE id = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','RELEVANCY','RESPONSIBLE','DETAIL','NAME','RESPONSIBLE_RISK','AREA'));
							insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DUPLIC,$_SESSION['user_name'],$ARRAY['name']);
						} else {
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
						}
					}
				} elseif($DISABLE_ITEM == 1){
					for($f=0; $f < sizeof($ID_ITEM); $f++){
						$SQL = "SELECT name,status FROM tprocess WHERE id_area IN (SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")  AND ";
						$SQL .= "id = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);
						
						if($ARRAY['status'] == 'a'){
							$SQL = "UPDATE tprocess SET status = 'd' ";
							$SQL .= "WHERE id = $ID_ITEM[$f] AND id_area IN (SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							$CODE_SUCESSFUL_ED = $CODE_SUCCESSFUL_DISABLE;
						} else {
							$SQL = "UPDATE tprocess SET status = 'a' ";
							$SQL .= "WHERE id = $ID_ITEM[$f] AND id_area IN (SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							$CODE_SUCESSFUL_ED = $CODE_SUCCESSFUL_ENABLE;
						}

						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','RELEVANCY','RESPONSIBLE','DETAIL','NAME','RESPONSIBLE_RISK','AREA'));
							insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCESSFUL_ED,$_SESSION['user_name'],$ARRAY['name']);
						} else {
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
						}
					}
				} else {
					$SQL = "UPDATE tprocess SET name='$NAME', detail='$DETAIL', id_responsible=$RESPONSIBLE, id_risk_responsible=$RISK_RESPONSIBLE, ";
					$SQL .= "id_area=$AREA, relevancy=$RELEVANCY, status='$STATUS' ";
					$SQL .= "WHERE id = $ID_ITEM[0] AND id_area IN (SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					
					if(pg_affected_rows($RS) > 0){
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
						destroySession(array('ID_SEL','RELEVANCY','RESPONSIBLE','DETAIL','NAME','RESPONSIBLE_RISK','AREA'));
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
					}
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_UP,$_SESSION['user_name'],$NAME);

					// Update the risk factor
					$SQL = "SELECT r.id, r.probability, a.relevancy AS relevancy_area FROM trisk r, tarea a  WHERE a.id IN (SELECT id_area FROM tprocess ";
					$SQL .= "WHERE id = r.id_process AND r.id_process = $ID_ITEM[0]) ";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$ARRAY = pg_fetch_array($RS);
					do{
						if(!empty($ARRAY['id'])){
							$SQL = "SELECT i.name, ri.value FROM tarisk_impact ri, timpact i WHERE i.id = ri.id_impact AND id_risk = ".$ARRAY['id'];
							$RS_INSIDE = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							$ARRAY_INSIDE = pg_fetch_array($RS_INSIDE);
							do{
								switch ($ARRAY_INSIDE['name']){
									case 'confidentiality':
										$confidentiality = $ARRAY_INSIDE['value'];
										break;
									case 'integrity':
										$integrity = $ARRAY_INSIDE['value'];
										break;
									case 'availability':
										$availability = $ARRAY_INSIDE['value'];
										break;
									case 'financial':
										$financial = $ARRAY_INSIDE['value'];
										break;
								}
							}while($ARRAY_INSIDE = pg_fetch_array($RS_INSIDE));
							$FR = calcRiskFactor($confidentiality,$integrity,$availability,$ARRAY['probability'],$ARRAY['relevancy_area'],$RELEVANCY,$financial);
							$SQL = "UPDATE trisk SET risk_factor=$FR ";
							$SQL .= "WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
							$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = ".$ARRAY['id'];
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							updateResidualRisk($ARRAY['id']);
						}
					}while($ARRAY = pg_fetch_array($RS));
				}
			}
		}
	}
	header("Location:$DESTINATION_PAGE");
}
?>