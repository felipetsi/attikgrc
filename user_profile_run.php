<?php
session_start();
$DESTINATIONPAGE_NO_LOGIN = "login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THISPAGE = "user_profile_run.php";
$DESTINATION_PAGE = "user_profile.php";
$CODE_SUCCESSFUL_IN = 'SIPROF0001';
$CODE_SUCCESSFUL_UP = 'SUPROF0001';
$CODE_SUCCESSFUL_DE = 'SDPROF0001';
$CODE_FAILED_NAME_EXISTS = 'FUPROF0002';
$CODE_SUCCESSFUL_DUPLIC = 'SUPROF0002';

if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once('include/function.php');
	
	$PERMITIONS_NAME_1 = "profile_manager@";
	if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false){
		$_SESSION['MSG_TOP'] = 'LANG_YOU_NOT_HAVE_PERMISSION';
	} else {
		if($_SESSION['STATUS_MULT_SEL'] == 1){
			$f = 0;
			foreach ($_POST['optcheckitem'] as $key => $value) {
				$ID_ITEM[$f] = substr(trim(addslashes($value)),0,3);
				$f++;
			}
			if(isset($_POST['mark_deleteitem_view_form'])){$DELETE_ITEM = substr(trim(addslashes($_POST['mark_deleteitem_view_form'])),0,1);} else {$DELETE_ITEM = 0;}
			if(isset($_POST['mark_duplicateitem_view_form'])){$DUPLICATE_ITEM = substr(trim(addslashes($_POST['mark_duplicateitem_view_form'])),0,1);} else
				{$DUPLICATE_ITEM = 0;}
		} else {
			$ID_ITEM[0] = trim(addslashes($_POST['id_item_selected']));$_SESSION['ID_SEL'] = $ID_ITEM[0];
			$NAME = substr(trim(addslashes($_POST['name'])),0,255);$_SESSION['NAME'] = $NAME;
			if(isset($_POST['mark_deleteitem'])){$DELETE_ITEM = substr(trim(addslashes($_POST['mark_deleteitem'])),0,1);} else {$DELETE_ITEM = 0;}
			if(isset($_POST['mark_duplicateitem'])){$DUPLICATE_ITEM = substr(trim(addslashes($_POST['mark_duplicateitem'])),0,1);} else {$DUPLICATE_ITEM = 0;}
		}
		
		if($DELETE_ITEM == "1"){
			for($f=0; $f < sizeof($ID_ITEM); $f++)
			{
				$SQL = "UPDATE tperson SET id_profile = NULL WHERE id_profile = $ID_ITEM[$f]";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
				
				$SQL = "DELETE FROM taprofile_itemprofile WHERE id_profile = $ID_ITEM[$f]";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

				$SQL = "DELETE FROM tprofile WHERE id = $ID_ITEM[$f] AND id_instance = ".$_SESSION['INSTANCE_ID'];
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
				if(pg_affected_rows($RS) > 0){
					$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
					destroySession(array('ID_SEL','NAME'));
				} else {
					$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
				}
				insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DE,$_SESSION['user_name'],$NAME);
			}
		} else {
			$f = 0;
			foreach ($_POST['permition_profile'] as $key => $value) {
				$ITEMPROFILE_SELECTED[$f] = substr(trim(addslashes($value)),0,3);
				$f++;
			}

			if((empty($NAME))&&(($DUPLICATE_ITEM == 0) && ($DELETE_ITEM == 0))){
				$_SESSION['MSG_TOP'] = 'LANG_MSG_NEED_FILL_UNDERLINED';
			}elseif(((sizeof($ITEMPROFILE_SELECTED) == 0))&&(($DUPLICATE_ITEM == 0) && ($DELETE_ITEM == 0))){
				$_SESSION['MSG_TOP'] = 'LANG_MSG_NECESSARY_SELECT_ONE';
			} else {
				if(!empty($ID_ITEM[0])){
					$SQL_COMPL = "AND id != ".$ID_ITEM[0];
				} else {
					$SQL_COMPL = "";
				}
				$SQL = "SELECT name FROM tprofile WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND name = '$NAME' $SQL_COMPL";
				$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
				if(pg_affected_rows($RS) > 0) {
					$_SESSION['MSG_TOP'] = 'LANG_MSG_NAME_EXISTS';
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_FAILED_NAME_EXISTS,$_SESSION['user_name'],$ARRAY['name']);
				} else {
					if(empty($ID_ITEM[0])){
						$SQL = "INSERT INTO tprofile(id_instance, name) ";
						$SQL .= "VALUES (".$_SESSION['INSTANCE_ID'].", '$NAME')";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						$LAST_ID_ARRAY = pg_fetch_array(pg_query("SELECT CURRVAL('tprofile_id_seq')"));
						$ID_PROFILE = $LAST_ID_ARRAY[0];
						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','NAME'));
						} else {
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
						}
						insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_IN,$_SESSION['user_name'],$NAME);

						for($f=0; $f < sizeof($ITEMPROFILE_SELECTED); $f++){
							$SQL = "INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) ";
							$SQL .= "VALUES ($ID_PROFILE, ".$ITEMPROFILE_SELECTED[$f].")";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						}
					} elseif($DUPLICATE_ITEM == 1){
							for($f=0; $f < sizeof($ID_ITEM); $f++){
								$SQL = "SELECT name FROM tprofile WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND id = $ID_ITEM[$f]";
								$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								$ARRAY = pg_fetch_array($RS);
								
								$SQL = "SELECT id FROM tprofile WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND name LIKE '%".$ARRAY['name']."%'";
								$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								$NUM_COPY = pg_affected_rows($RS);

								$SQL = "INSERT INTO tprofile(id_instance, name) ";
								$SQL .= "SELECT id_instance, '".'copy('.$NUM_COPY.') - '.$ARRAY['name']."' FROM tprofile WHERE id = $ID_ITEM[$f]";
								$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								$LAST_ID_ARRAY = pg_fetch_array(pg_query("SELECT CURRVAL('tprofile_id_seq')"));
								$ID_NEW_PROFILE = $LAST_ID_ARRAY[0];
								
								$SQL = "INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) ";
								$SQL .= "SELECT $ID_NEW_PROFILE, id_itemprofile FROM taprofile_itemprofile WHERE id_profile = $ID_ITEM[$f] ";
								$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								
								if(pg_affected_rows($RS) > 0){
									$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
									destroySession(array('ID_SEL','NAME'));
									insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DUPLIC,$_SESSION['user_name'],$ARRAY['login']);
								} else {
									$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
								}
							}
					} else {
						$SQL = "UPDATE tprofile	SET name = '$NAME' WHERE id = $ID_ITEM[0]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

						if(pg_affected_rows($RS) > 0){
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
							destroySession(array('ID_SEL','NAME'));
						} else {
							$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
						}
						insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_UP,$_SESSION['user_name'],$NAME);

						$SQL = "DELETE FROM  taprofile_itemprofile WHERE id_profile = $ID_ITEM[0]";
						$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

						for($f=0; $f < sizeof($ITEMPROFILE_SELECTED); $f++){
							$SQL = "INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) ";
							$SQL .= "VALUES ($ID_ITEM[0], ".$ITEMPROFILE_SELECTED[$f].")";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
						}
					}
				}
			}
		}
	}
	header("Location:$DESTINATION_PAGE");
}
?>