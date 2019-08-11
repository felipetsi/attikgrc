<?php
session_start();
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THISPAGE = "asset_run.php";
$DESTINATION_PAGE = "asset.php";

$CODE_SUCCESSFUL_DE = 'SDASSE0001';
$CODE_SUCCESSFUL_IN = 'SIASSE0001';
$CODE_SUCCESSFUL_DUPLIC = 'SUASSE0002';
$CODE_SUCCESSFUL_UP = 'SUASSE0001';
$CODE_SUCCESSFUL_ENABLE = 'SUASSE0003';
$CODE_SUCCESSFUL_DISABLE = 'SUASSE0004';
$CODE_SUCCESSFUL_DE_2 = 'SDASSE0002';
$CODE_SUCCESSFUL_DE_3 = 'SDASSE0003';

if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP'].'include/function.php');
	
	$PERMITIONS_NAME_1 = "create_asset@";
	$PERMITIONS_NAME_2 = "read_own_asset@";
	
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
			$NAME = str_replace("\'","''",substr(trim(addslashes($_POST['asset_name'])),0,255)); $_SESSION['NAME_ASSET'] = $NAME;
			$DETAIL = str_replace("\'","''",substr(trim(addslashes($_POST['asset_detail'])),0,1000)); $_SESSION['DETAIL_ASSET'] = $DETAIL;
			$PROCESS = trim(addslashes($_POST['asset_process'])); $_SESSION['PROCESS'] = $PROCESS;
			// Load impact to get impact sent
			$SQL = "SELECT id,name FROM timpact WHERE id_impact_type IN (SELECT id FROM timpact_type WHERE ";
			$SQL .= "id_instance =".$_SESSION['INSTANCE_ID']." AND name LIKE 'security')";
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			do{
				$IMPACT_VALUE[$ARRAY['id']] = trim(addslashes($_POST['impact_'.$ARRAY['id'].''])); 
				$_SESSION['IMPACT'.$ARRAY['id'].''] = $IMPACT_VALUE[$ARRAY['id']];
				if((empty($IMPACT_VALUE[$ARRAY['id']])) && ($ARRAY['name'] != 'financial')){
					$IMPACT_VALUE[$ARRAY['id']] = 1;
				}
			}while($ARRAY = pg_fetch_array($RS));
			$STATUS = 'a';
			
			if(isset($_POST['mark_deleteitem'])){$DELETE_ITEM = substr(trim(addslashes($_POST['mark_deleteitem'])),0,1);} else {$DELETE_ITEM = 0;}
			if(isset($_POST['mark_duplicateitem'])){$DUPLICATE_ITEM = substr(trim(addslashes($_POST['mark_duplicateitem'])),0,1);} else {$DUPLICATE_ITEM = 0;}
			if(isset($_POST['mark_finishitem'])){$CONTROL_FINISH = substr(trim(addslashes($_POST['mark_finishitem'])),0,1);} else {$CONTROL_FINISH = 0;}
			if(isset($_POST['mark_disableitem'])){$DISABLE_ITEM = substr(trim(addslashes($_POST['mark_disableitem'])),0,1);} else {$DISABLE_ITEM = 0;}
		}

		if($DELETE_ITEM == "1"){
			for($f=0; $f < sizeof($ID_ITEM); $f++)
			{
				$SQL = "SELECT p.id FROM tprocess p, tasset a WHERE a.id_process = p.id AND a.id = $ID_ITEM[$f] AND ";
				$SQL .= "(p.id_responsible = ".$_SESSION['user_id']." OR p.id_risk_responsible = ".$_SESSION['user_id'].")";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
				$AFFECTED = pg_affected_rows($RS);

				if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2) === false) && ($AFFECTED == 0)){
					$_SESSION['MSG_TOP'] = 'LANG_YOU_NOT_HAVE_PERMISSION_DEL_SOME';
				} else {
					if(verifyDeleteCascade() == 'n'){
						$HAVE_DEPENDENCE = 0;
					} else {
						// Delete relation with impact control
						$SQL = "DELETE FROM taasset_process ";
						$SQL .= "WHERE id_asset = $ID_ITEM[$f] ";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DE_3,$_SESSION['user_name'],$ARRAY['name']);

						$HAVE_DEPENDENCE = 0;
					}
					if($HAVE_DEPENDENCE == 0) {
						$SQL = "SELECT name FROM tasset WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN (SELECT id FROM tarea WHERE ";
						$SQL .= "id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);
						
						$SQL = "DELETE FROM taasset_impact WHERE id_asset = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

						$SQL = "DELETE FROM tasset ";
						$SQL .= "WHERE id = $ID_ITEM[$f] AND id_process IN (SELECT id FROM tprocess WHERE id_area IN (SELECT id FROM tarea WHERE ";
						$SQL .= "id_instance=".$_SESSION['INSTANCE_ID'].")) ";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','CRET_CONT_INP','CRET_CONT_SEL','PROCESS','DETAIL_ASSET','NAME_ASSET'));
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
					$SQL = "INSERT INTO tasset(id_process, name, detail, status) ";
					$SQL .= "VALUES ($PROCESS, '$NAME', '$DETAIL', '$STATUS')";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$LAST_ID_ARRAY = pg_fetch_array(pg_query("SELECT CURRVAL('tasset_id_seq')"));
					$LAST_ID_ASSET = $LAST_ID_ARRAY[0];
					
					foreach($IMPACT_VALUE as $key => $value){
						if(!empty($value)){
							$SQL = "INSERT INTO taasset_impact(id_asset, id_impact, value) ";
							$SQL .= "VALUES ($LAST_ID_ASSET, $key, $value)";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						}
					}
					
					if(pg_affected_rows($RS) > 0){
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
						destroySession(array('ID_SEL','CRET_CONT_INP','CRET_CONT_SEL','PROCESS','DETAIL_ASSET','NAME_ASSET'));
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
					}
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_IN,$_SESSION['user_name'],$NAME);
				} elseif($DUPLICATE_ITEM == 1){
					for($f=0; $f < sizeof($ID_ITEM); $f++){
						$SQL = "SELECT name FROM tasset WHERE id = $ID_ITEM[$f] AND id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
						$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID']."))";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);

						$SQL = "SELECT id FROM tasset WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
						$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND ";
						$SQL .= "name LIKE '%".$ARRAY['name']."%'";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$NUM_COPY = pg_affected_rows($RS);

						$SQL = "INSERT INTO tasset(id_process, name, detail, status) ";
						$SQL .= "SELECT id_process, '"."copy($NUM_COPY) - ".$ARRAY['name']."', detail, status ";
						$SQL .= "FROM tasset WHERE id = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$LAST_ID_ARRAY = pg_fetch_array(pg_query("SELECT CURRVAL('tasset_id_seq')"));
						$LAST_ID_ASSET = $LAST_ID_ARRAY[0];
						
						$SQL = "INSERT INTO taasset_impact(id_asset, id_impact, value) ";
						$SQL .= "SELECT $LAST_ID_ASSET, id_impact, value FROM taasset_impact WHERE id_asset = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						
						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','CRET_CONT_INP','CRET_CONT_SEL','PROCESS','DETAIL_ASSET','NAME_ASSET'));
							insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DUPLIC,$_SESSION['user_name'],$ARRAY['name']);
						} else {
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
						}
					}
				} elseif($DISABLE_ITEM == 1){
					for($f=0; $f < sizeof($ID_ITEM); $f++){
						$SQL = "SELECT name,status FROM tasset WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
						$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);
						
						if($ARRAY['status'] != 'd'){
							$SQL = "UPDATE tasset SET status = 'd' ";
							$SQL .= "WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
							$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_ITEM[$f]";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							$CODE_SUCESSFUL_ED = $CODE_SUCCESSFUL_DISABLE;
						} else {
							$SQL = "UPDATE tasset SET status = '$STATUS' ";
							$SQL .= "WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
							$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_ITEM[$f]";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							$CODE_SUCESSFUL_ED = $CODE_SUCCESSFUL_ENABLE;
						}

						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','CRET_CONT_INP','CRET_CONT_SEL','PROCESS','DETAIL_ASSET','NAME_ASSET'));
							insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCESSFUL_ED,$_SESSION['user_name'],$ARRAY['name']);
						} else {
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
						}
					}
				} else {
					$SQL = "SELECT p.id FROM tprocess p, tasset a WHERE a.id_process = p.id AND a.id = $ID_ITEM[0] AND ";
					$SQL .= "(p.id_responsible = ".$_SESSION['user_id']." OR p.id_risk_responsible = ".$_SESSION['user_id'].")";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$AFFECTED = pg_affected_rows($RS);

					if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2) === false) && ($AFFECTED == 0)){
						$_SESSION['MSG_TOP'] = 'LANG_YOU_NOT_HAVE_PERMISSION_DEL_SOME';
					} else {
						// Delete and insert again the impact to update
						$SQL = "DELETE FROM taasset_impact ";
						$SQL .= "WHERE id_asset = $ID_ITEM[0]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

						foreach($IMPACT_VALUE as $key => $value){
							if(!empty($value)){
								$SQL = "INSERT INTO taasset_impact(id_asset, id_impact, value) ";
								$SQL .= "VALUES ($ID_ITEM[0], $key, $value)";
								$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							}
						}
						$SQL = "UPDATE tasset SET id_process=$PROCESS, name='$NAME', detail='$DETAIL' ";
						$SQL .= "WHERE id_process IN (SELECT id FROM tprocess WHERE id_area IN ";
						$SQL .= "(SELECT id FROM tarea WHERE id_instance=".$_SESSION['INSTANCE_ID'].")) AND id = $ID_ITEM[0]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','CRET_CONT_INP','CRET_CONT_SEL','PROCESS','DETAIL_ASSET','NAME_ASSET'));
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