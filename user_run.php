<?php
session_start();
$DESTINATIONPAGE_NO_LOGIN = "login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THISPAGE = "user_run.php";
$DESTINATION_PAGE = "user.php";
$CODE_SUCCESSFUL_IN = 'SIUSER0001';
$CODE_SUCCESSFUL_UP = 'SUUSER0001';
$CODE_SUCCESSFUL_DUPLIC = 'SUUSER0002';
$CODE_SUCCESSFUL_DISABLE = 'SUUSER0003';
$CODE_SUCCESSFUL_DE = 'SDUSER0001';
$CODE_FAILED_NAME_EXISTS = 'FUUSER0002';
$CODE_FAILED_USER_CURRENT = 'FUUSER0003';

if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once('include/function.php');
	
	$PERMITIONS_NAME_1 = "user_manager@";
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
			if(isset($_POST['mark_disableitem_view_form'])){$DISABLE_ITEM = substr(trim(addslashes($_POST['mark_disableitem_view_form'])),0,1);} else {$DISABLE_ITEM = 0;}
		} else {
			$ID_ITEM[0] = trim(addslashes($_POST['id_item_selected']));$_SESSION['ID_SEL'] = $ID_ITEM[0];
			$NAME = substr(trim(addslashes($_POST['name'])),0,255);$_SESSION['NAME'] = $NAME;
			$DETAIL = str_replace("\'","''",substr(trim(addslashes($_POST['detail'])),0,500)); $_SESSION['DETAIL'] = $DETAIL;
			$LOGIN = substr(trim(addslashes($_POST['login'])),0,30);$_SESSION['LOGIN'] = $LOGIN;
			$EMAIL = substr(trim(addslashes($_POST['email'])),0,100);$_SESSION['EMAIL'] = $EMAIL;
			$ID_PROFILE = trim(addslashes($_POST['profile']));if(empty($ID_PROFILE)){$ID_PROFILE = 'NULL';};$_SESSION['PROFILE'] = $ID_PROFILE;
			$LANG_DEFAULT = substr(trim(addslashes($_POST['language_default'])),0,2);$_SESSION['USER_LANGUAGE'] = $LANG_DEFAULT;
			$PASSWD = sha1($_POST['passwd']);
			$PASSWD_RE = sha1($_POST['re_passwd']);
			$PASSWD_RAW = substr(trim(addslashes($_POST['passwd'])),0,1);
			if(isset($_POST['change_password_n'])){$CHANGE_PASSWD = substr(trim(addslashes($_POST['change_password_n'])),0,1);} else {$CHANGE_PASSWD = 'n';}
			if(isset($_POST['status'])){$STATUS = substr(trim(addslashes($_POST['status'])),0,1);} else {$STATUS = 'a';}
			if(isset($_POST['mark_deleteitem'])){$DELETE_ITEM = substr(trim(addslashes($_POST['mark_deleteitem'])),0,1);} else {$DELETE_ITEM = 0;}
			if(isset($_POST['mark_duplicateitem'])){$DUPLICATE_ITEM = substr(trim(addslashes($_POST['mark_duplicateitem'])),0,1);} else {$DUPLICATE_ITEM = 0;}
			if(isset($_POST['mark_disableitem'])){$DISABLE_ITEM = substr(trim(addslashes($_POST['mark_disableitem'])),0,1);} else {$DISABLE_ITEM = 0;}
		}

		if($DELETE_ITEM == "1"){
			for($f=0; $f < sizeof($ID_ITEM); $f++)
			{
				if($_SESSION['user_id'] == $ID_ITEM[$f]) {
					$_SESSION['MSG_TOP'] = 'LANG_MSG_NOT_DELETE_CURRENT_USER';
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_FAILED_USER_CURRENT,$_SESSION['user_name'],$LOGIN);
				} else {
					// If delete selected, only status is updated, because the name user is keeping in log records
					$STATUS_EXCLUDED = "e";
					$SQL = "UPDATE tperson SET status = '$STATUS_EXCLUDED' ";
					$SQL .= "WHERE id = $ID_ITEM[$f] AND id_instance = ".$_SESSION['INSTANCE_ID'];
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));

					if(pg_affected_rows($RS) > 0){
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
						destroySession(array('ID_SEL','USER_LANGUAGE','USER_LANGUAGE','PROFILE','EMAIL','LOGIN','DETAIL','NAME'));
					} else {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
					}
					
					$SQL = "SELECT login FROM tperson WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND id = $ID_ITEM[$f]";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					$ARRAY = pg_fetch_array($RS);
					
					insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DE,$_SESSION['user_name'],$ARRAY['login']);
				}
			}
		} else {
			if(((empty($NAME)) || (empty($LOGIN)) || (empty($EMAIL)) || 
			   (empty($LANG_DEFAULT)) || (empty($PASSWD_RAW) && (empty($ID_ITEM)))) && (($DUPLICATE_ITEM == 0)&&($DISABLE_ITEM == 0))){
				$_SESSION['MSG_TOP'] = 'LANG_MSG_NEED_FILL_UNDERLINED';
			} else {
			if(($PASSWD != $PASSWD_RE)&& (($DUPLICATE_ITEM == 0)&&($DISABLE_ITEM == 0))){
					$_SESSION['MSG_TOP'] = 'LANG_MSG_PASSWORDS_DIFFERENT';
				} else {
					if(empty($ID_ITEM[0])){
						$SQL_COMPL = "";
					} else {
						$SQL_COMPL = "AND id != ".$ID_ITEM[0];
					}
					$SQL = "SELECT login FROM tperson WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND (login = '$LOGIN' OR email = '$EMAIL') $SQL_COMPL";
					$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
					if(pg_affected_rows($RS) > 0) {
						$_SESSION['MSG_TOP'] = 'LANG_MSG_LOGIN_EMAIL_EXISTS';
						insertHistory($_SESSION['INSTANCE_ID'],$CODE_FAILED_NAME_EXISTS,$_SESSION['user_name'],$ARRAY['login']);
					} else {
						if(empty($ID_ITEM[0])){
							$SQL = "INSERT INTO tperson(id_profile, id_instance, language_default, name, detail, email, change_password_next_login, ";
							$SQL .= "login, password, status, erro_access_login,date_last_change_password) ";
							$SQL .= "VALUES ($ID_PROFILE, ".$_SESSION['INSTANCE_ID'].", '$LANG_DEFAULT', '$NAME', '$DETAIL', '$EMAIL', '$CHANGE_PASSWD', ";
							$SQL .= "'$LOGIN', '$PASSWD', '$STATUS', 0, CURRENT_DATE)";
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							if(pg_affected_rows($RS) > 0){
								$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
								destroySession(array('ID_SEL','USER_LANGUAGE','USER_LANGUAGE','PROFILE','EMAIL','LOGIN','DETAIL','NAME'));
							} else {
								$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
							}
							insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_IN,$_SESSION['user_name'],$NAME);
						} elseif($DUPLICATE_ITEM == 1){
							for($f=0; $f < sizeof($ID_ITEM); $f++){
								$SQL = "SELECT login, email FROM tperson WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND id = $ID_ITEM[$f]";
								$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								$ARRAY = pg_fetch_array($RS);
								
								$SQL = "SELECT id FROM tperson WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND login LIKE '%".$ARRAY['login']."%'";
								$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								$NUM_COPY_LOGIN = pg_affected_rows($RS);
								
								$SQL = "SELECT id FROM tperson WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND email LIKE '%".$ARRAY['email']."%'";
								$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								$NUM_COPY_EMAIL = pg_affected_rows($RS);

								$SQL = "INSERT INTO tperson(id_profile, id_instance, language_default, name, detail, email, change_password_next_login, ";
								$SQL .= "login, password, status, erro_access_login,date_last_change_password) ";
								$SQL .= "SELECT id_profile, id_instance, language_default, name, detail, '".'copy('.$NUM_COPY_EMAIL.') - '.$ARRAY['email']."', ";
								$SQL .= "change_password_next_login, ";
								$SQL .= "'".'copy('.$NUM_COPY_LOGIN.') - '.$ARRAY['login']."', password, status, 0, CURRENT_DATE FROM tperson WHERE id = $ID_ITEM[$f]";
								$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								if(pg_affected_rows($RS) > 0){
									$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
									destroySession(array('ID_SEL','USER_LANGUAGE','USER_LANGUAGE','PROFILE','EMAIL','LOGIN','DETAIL','NAME'));
									insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DUPLIC,$_SESSION['user_name'],$ARRAY['login']);
								} else {
									$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
								}
							}
						} elseif($DISABLE_ITEM == 1){
							for($f=0; $f < sizeof($ID_ITEM); $f++){
								$SQL = "SELECT status,login FROM tperson WHERE id_instance = ".$_SESSION['INSTANCE_ID']." AND id = $ID_ITEM[$f]";
								$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								$ARRAY = pg_fetch_array($RS);
								
								if($ARRAY['status'] == 'a'){
									$SQL = "UPDATE tperson SET status = 'd' ";
									$SQL .= "WHERE id = $ID_ITEM[$f] AND id_instance = ".$_SESSION['INSTANCE_ID'];
									$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								} else {
									$SQL = "UPDATE tperson SET status = 'a' ";
									$SQL .= "WHERE id = $ID_ITEM[$f] AND id_instance = ".$_SESSION['INSTANCE_ID'];
									$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
								}

								if(pg_affected_rows($RS) > 0){
									$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
									destroySession(array('ID_SEL','USER_LANGUAGE','USER_LANGUAGE','PROFILE','EMAIL','LOGIN','DETAIL','NAME'));
									insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_DISABLE,$_SESSION['user_name'],$ARRAY['login']);
									
								} else {
									$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
								}
							}
						} else {
							if(empty($PASSWD_RAW)){
								$SQL_COMPL = "";
							} else {
								$SQL_COMPL = ", password = '$PASSWD', date_last_change_password = CURRENT_DATE ";
							}
							$SQL = "UPDATE tperson SET id_profile = $ID_PROFILE, language_default = '$LANG_DEFAULT', ";
							$SQL .= "name = '$NAME', detail = '$DETAIL', ";
							$SQL .= "email = '$EMAIL', change_password_next_login = '$CHANGE_PASSWD', login = '$LOGIN', status = '$STATUS' $SQL_COMPL";
							$SQL .= "WHERE id = $ID_ITEM[0] AND id_instance = ".$_SESSION['INSTANCE_ID'];
							$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
							
							if(pg_affected_rows($RS) > 0){
								$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS';
								destroySession(array('ID_SEL','USER_LANGUAGE','USER_LANGUAGE','PROFILE','EMAIL','LOGIN','DETAIL','NAME'));
							} else {
								$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
							}
							insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL_UP,$_SESSION['user_name'],$NAME);
						}
					}
				}
			}
		}
	}
	header("Location:$DESTINATION_PAGE");
}
?>