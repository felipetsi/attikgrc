<?php
session_start();
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THISPAGE = "project_run.php";
$DESTINATION_PAGE = "project.php";

$CODE_SUCCESSFUL_DE = 'SDPROJ0001';
$CODE_SUCCESSFUL_IN = 'SIPROJ0001';
$CODE_SUCCESSFUL_DUPLIC = 'SUPROJ0002';
$CODE_SUCCESSFUL_UP = 'SUPROJ0001';

if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP'].'include/function.php');
	
	$PERMITIONS_NAME_1 = "create_project@";
	$PERMITIONS_NAME_2 = "read_project@";
	
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
		} else {
			$ID_ITEM[0] = trim(addslashes($_POST['id_item_selected']));$_SESSION['ID_SEL'] = $ID_ITEM[0];
			$NAME = str_replace("\'","''",substr(trim(addslashes($_POST['name'])),0,255)); $_SESSION['NAME'] = $NAME;
			$DETAIL = str_replace("\'","''",substr(trim(addslashes($_POST['detail'])),0,1000)); $_SESSION['DETAIL'] = $DETAIL;
			$BESTPRATICES = trim(addslashes($_POST['bestpratices'])); $_SESSION['BESTPRATICES'] = $BESTPRATICES;
			$SPONSOR = trim(addslashes($_POST['sponsor'])); $_SESSION['SPONSOR'] = $SPONSOR;
			$MANAGER = trim(addslashes($_POST['manager'])); $_SESSION['MANAGER'] = $MANAGER;
			$BUDGET = preg_replace("/[^0-9.]/", "", (trim(addslashes($_POST['budget'])))); $_SESSION['BUDGET'] = $BUDGET;
			$DEADLINE = trim(addslashes($_POST['deadline'])); $_SESSION['DEADLINE'] = $DEADLINE;
			
			if(isset($_POST['mark_deleteitem'])){$DELETE_ITEM = substr(trim(addslashes($_POST['mark_deleteitem'])),0,1);} else {$DELETE_ITEM = 0;}
			if(isset($_POST['mark_duplicateitem'])){$DUPLICATE_ITEM = substr(trim(addslashes($_POST['mark_duplicateitem'])),0,1);} else {$DUPLICATE_ITEM = 0;}
			if(isset($_POST['mark_finishitem'])){$CONTROL_FINISH = substr(trim(addslashes($_POST['mark_finishitem'])),0,1);} else {$CONTROL_FINISH = 0;}
			
			if(empty($SPONSOR)){ $SPONSOR = 'NULL';}
			if(empty($MANAGER)){ $MANAGER = 'NULL';}
			if(empty($BUDGET)){ $BUDGET = 'NULL';}
			if(empty($DEADLINE)){ $DEADLINE = 'NULL';} else {$DEADLINE = "'".$DEADLINE."'";}
		}

		if($DELETE_ITEM == "1"){
			for($f=0; $f < sizeof($ID_ITEM); $f++)
			{
				$SQL = "SELECT name FROM tproject WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND id = $ID_ITEM[$f]";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
				$ARRAY = pg_fetch_array($RS);

				$SQL = "DELETE FROM tproject ";
				$SQL .= "WHERE id = $ID_ITEM[$f] AND id_instance = ".$_SESSION['INSTANCE_ID'];
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

				if(pg_affected_rows($RS) > 0){
					$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
					destroySession(array('ID_SEL','ID_PROJECT','BESTPRATICES','DEADLINE','BUDGET','MANAGER','SPONSOR','DETAIL','NAME'));
				} else {
					$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
				}

				insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DE,$_SESSION['user_name'],$ARRAY['name']);
			}
		} else {
			if((empty($NAME) || empty($BESTPRATICES)) && ($DUPLICATE_ITEM != 1)){
				$_SESSION['MSG_TOP'] = 'LANG_MSG_NEED_FILL_UNDERLINED';
			} else {
				$CREATOR = $_SESSION['user_id'];
				$STATUS = 'o';
				
				if(empty($ID_ITEM[0]) && ($DUPLICATE_ITEM == 0)){
					$SQL = "INSERT INTO tproject(name, detail, id_instance, id_sponsor, id_manager, budget, deadline, ";
					$SQL .= "id_creator, creation_date, status, id_best_pratices) ";
					$SQL .= "VALUES ('$NAME', '$DETAIL', ".$_SESSION['INSTANCE_ID'].", $SPONSOR, $MANAGER, $BUDGET, $DEADLINE,  ";
					$SQL .= "$CREATOR, CURRENT_DATE, '$STATUS', $BESTPRATICES)";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					if(pg_affected_rows($RS) > 0){
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
						destroySession(array('ID_SEL','ID_PROJECT','BESTPRATICES','DEADLINE','BUDGET','MANAGER','SPONSOR','DETAIL','NAME'));
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
					}
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_IN,$_SESSION['user_name'],$NAME);
				} elseif($DUPLICATE_ITEM == 1){
					for($f=0; $f < sizeof($ID_ITEM); $f++){
						$SQL = "SELECT name FROM tproject WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND id = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$ARRAY = pg_fetch_array($RS);

						$SQL = "SELECT id FROM tproject WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND name LIKE '%".$ARRAY['name']."%'";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$NUM_COPY = pg_affected_rows($RS);

						$SQL = "INSERT INTO tproject(name, detail, id_instance, id_sponsor, id_manager, budget, deadline, ";
						$SQL .= "id_creator, creation_date, status, id_best_pratices) ";
						$SQL .= "SELECT '".'copy('.$NUM_COPY.') - '.$ARRAY['name']."', detail, id_instance, id_sponsor, id_manager, budget, deadline, ";
						$SQL .= "$CREATOR, CURRENT_DATE, '$STATUS', id_best_pratices ";
						$SQL .= "FROM tproject WHERE id = $ID_ITEM[$f]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','ID_PROJECT','BESTPRATICES','DEADLINE','BUDGET','MANAGER','SPONSOR','DETAIL','NAME'));
							insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DUPLIC,$_SESSION['user_name'],$ARRAY['name']);
						} else {
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
						}
					}
				} else {
					if (($CONTROL_FINISH == 1) && ($STATUS['status'] == 'o')){
						$STATUS = 't';
					} elseif (($CONTROL_FINISH == 1) && ($STATUS['status'] == 't')){
						$STATUS = 'c';
					} elseif (($CONTROL_FINISH == 1) && ($STATUS['status'] == 'c')){
						$STATUS = 'o';
					} 
					
					$SQL = "UPDATE tproject SET name='$NAME', detail='$DETAIL', id_sponsor=$SPONSOR, id_manager=$MANAGER, budget=$BUDGET, ";
					$SQL .= "deadline=$DEADLINE, status = '$STATUS' ";
					$SQL .= "WHERE id = $ID_ITEM[0] AND id_instance = ".$_SESSION['INSTANCE_ID'];
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

					if(pg_affected_rows($RS) > 0){
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
						destroySession(array('ID_SEL','ID_PROJECT','BESTPRATICES','DEADLINE','BUDGET','MANAGER','SPONSOR','DETAIL','NAME'));
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