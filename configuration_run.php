<?php
session_start();
$DESTINATIONPAGE_NO_LOGIN = "login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THISPAGE = "configuration_run.php";
$DESTINATION_PAGE = "configuration.php";
$CODE_SUCCESSFUL = 'SUCONF0001'; // S-U-CONF-0001: 1º= Success or Fail, 2º opration type (update, insert, etc), 3º short name of page with four character, 4º sequencial number by page
$CODE_FAILED = 'FUCONF0001';

if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once('include/function.php');
	
	$PERMITIONS_NAME_1 = "instance_conf@";
	
	if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1) === false)){
		$_SESSION['MSG_TOP'] = 'LANG_YOU_NOT_HAVE_PERMISSION';
	} else {
	
		$LANG_DEFAULT = substr(trim(addslashes($_POST['language_default'])),0,2);
		$APPR_DEFAULT = trim(addslashes($_POST['approver_default']));
		$ACCEPTANCE_LEVEL = substr(trim(addslashes($_POST['acceptance_level'])),0,4);
		$LANG_LIMIT_ERROR_LOGIN = substr(trim(addslashes($_POST['limit_error_login'])),0,3);
		$LANG_MAX_PASSWORD_LIFETIME = substr(trim(addslashes($_POST['maximum_password_lifetime'])),0,3);
		$LANG_MIN_PASSWORD_LIFETIME = substr(trim(addslashes($_POST['minimum_password_lifetime'])),0,3);
		$TIME_CHANGE_TEMP_PASSOWRD = substr(trim(addslashes($_POST['time_change_temp_password'])),0,3);
		$LANG_DELETE_CASCADE = substr(trim(addslashes($_POST['delete_cascade'])),0,1);
		$LANG_CLOSE_SYSTEM = substr(trim(addslashes($_POST['close_sytem'])),0,1);
		$IMPACT_TYPE_DEFAULT = trim(addslashes($_POST['impact_type_default']));
		//$LANG_LOGO_FILE = substr(trim(addslashes($_POST['logo_file'])),0,100);

		if(empty($LANG_DEFAULT) || empty($APPR_DEFAULT) || empty($ACCEPTANCE_LEVEL) || empty($LANG_DELETE_CASCADE)){
			$_SESSION['MSG_TOP'] = 'LANG_MSG_NEED_FILL_UNDERLINED';
		} else {
			$SQL = "SELECT * FROM tinstance_impact_money WHERE id_instance = ".$_SESSION['INSTANCE_ID'];
			$RS = pg_query($conn, $SQL);
			$ARRAY = pg_fetch_array($RS);
			do{
				$value_s = trim(addslashes($_POST['impact_money_'.$ARRAY['impact_level'].'_s']));
				$value_e = trim(addslashes($_POST['impact_money_'.$ARRAY['impact_level'].'_e']));

				$SQL = "UPDATE tinstance_impact_money SET ";
				$SQL .= "value_start = $value_s, value_end = $value_e ";
				$SQL .= "WHERE impact_level = '".$ARRAY['impact_level']."' AND id_instance = ".$_SESSION['INSTANCE_ID'];
				$RSINSIDE = pg_query($conn, $SQL);

			}while($ARRAY = pg_fetch_array($RS));
			
			// Update status of impact type
			$SQL = "UPDATE timpact_type SET ";
			$SQL .= "default_type = '' ";
			$SQL .= "WHERE id_instance = ".$_SESSION['INSTANCE_ID'];
			$RSINSIDE = pg_query($conn, $SQL);
			
			$SQL = "SELECT id FROM timpact_type WHERE id_instance = ".$_SESSION['INSTANCE_ID'];
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
			$ARRAY = pg_fetch_array($RS);
			do {
				$ID = $ARRAY['id'];
				if($IMPACT_TYPE_DEFAULT == $ID) {
					$COMP_SQL = ",default_type = 'y' ";
				} else {
					$COMP_SQL = "";
				}
				$IMPACT_TYPE = trim(addslashes($_POST['impact-'.$ID]));
				if(!empty($IMPACT_TYPE)){
					$status = "a";
				} else {
					$status = "d";
				}
				$SQL = "UPDATE timpact_type SET ";
				$SQL .= "status = '$status' $COMP_SQL";
				$SQL .= "WHERE id = ".$ARRAY['id']." AND id_instance = ".$_SESSION['INSTANCE_ID'];
				$RSINSIDE = pg_query($conn, $SQL);
			}while($ARRAY = pg_fetch_array($RS));
			
			// Update status of best practices
			$SQL = "SELECT id FROM tbest_pratice WHERE id_instance = ".$_SESSION['INSTANCE_ID'];
			$RS = pg_query($conn, $SQL) or (die("INTERNAL ERROR SYSTEM"));
			$ARRAY = pg_fetch_array($RS);
			do {
				$ID = $ARRAY['id'];
				$BP = trim(addslashes($_POST['bp-'.$ID]));
				if(!empty($BP)){
					$status = "a";
				} else {
					$status = "d";
				}
				$SQL = "UPDATE tbest_pratice SET ";
				$SQL .= "status = '$status' ";
				$SQL .= "WHERE id = ".$ARRAY['id']." AND id_instance = ".$_SESSION['INSTANCE_ID'];
				$RSINSIDE = pg_query($conn, $SQL);
			}while($ARRAY = pg_fetch_array($RS));

			// Update especial user - Default approver
			$SQL = "SELECT id_person FROM tespecial_person WHERE name LIKE 'defau_appr' AND id_instance = ".$_SESSION['INSTANCE_ID'];
			$RS = pg_query($conn, $SQL);
			if(pg_affected_rows($RS) == 0){
				$SQL = "INSERT INTO tespecial_person (id_person,id_instance,name) VALUES ";
				$SQL .= "($APPR_DEFAULT,".$_SESSION['INSTANCE_ID'].",'defau_appr')";
				$RS = pg_query($conn, $SQL);
			} else {
				$SQL = "UPDATE tespecial_person SET id_person = $APPR_DEFAULT WHERE name LIKE 'defau_appr' AND ";
				$SQL .= "id_instance = ".$_SESSION['INSTANCE_ID'];
				$RS = pg_query($conn, $SQL);	
			}

			$SQL = "UPDATE tinstance ";
			$SQL .= "SET language_default='$LANG_DEFAULT', acceptance_risk_level=$ACCEPTANCE_LEVEL, limit_error_login=$LANG_LIMIT_ERROR_LOGIN, ";
			$SQL .= "max_password_lifetime=$LANG_MAX_PASSWORD_LIFETIME, ";
			$SQL .= "enable_delete_cascade='$LANG_DELETE_CASCADE', min_password_lifetime=$LANG_MIN_PASSWORD_LIFETIME, ";
			$SQL .= "time_change_temp_password=$TIME_CHANGE_TEMP_PASSOWRD, close_system='$LANG_CLOSE_SYSTEM', last_update = CURRENT_DATE ";
			$SQL .= "WHERE id = ".$_SESSION['INSTANCE_ID'];
			$RS = pg_query($conn, $SQL);

			if(pg_affected_rows($RS) > 0){
				$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_SUCESS_NEXT_LOGIN';
				insertHistory($_SESSION['INSTANCE_ID'],$CODE_SUCCESSFUL,$_SESSION['user_name'],"");
			} else {
				$_SESSION['MSG_TOP'] = 'LANG_MSG_OPERATION_ERRO';
				insertHistory($_SESSION['INSTANCE_ID'],$CODE_FAILED,$_SESSION['user_name'],"");
			}
		}
	}
	header("Location:$DESTINATION_PAGE");
}
?>