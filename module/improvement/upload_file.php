<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP'].'include/function.php');

	$ID_SOURCE = trim(addslashes($_POST['id_source']));
	$SOURCE = trim(addslashes($_POST['source']));
	$OP_TYPE = trim(addslashes($_POST['op_type']));
	if($OP_TYPE == "add"){
		// Upload files
		if(!empty($_FILES)){
			$uploadfilecontent = addslashes($_FILES['file']['tmp_name']);
			$uploadfilename = addslashes($_FILES['file']['name']);
			$uploadfilecontent = file_get_contents($uploadfilecontent);
			$uploadfilecontent = base64_encode($uploadfilecontent);
			$file_ext = pathinfo($uploadfilename, PATHINFO_EXTENSION);

			$CONF_MAX_LEN_FILE = 1024*1024*100; // 5MB
			$CONF_EXT_ACCE_FILE = array('png','jpg','jpeg','gif','bmp','doc','docx','xls','xlsx','txt');
	//		$EXT_FILE = strtolower(substr($uploadfile, -4)); 

			if($SOURCE == "task"){
				$SQL = "INSERT INTO ttask_workflow_file(id_task, content, name) ";
				$SQL .= "VALUES ($ID_SOURCE, '$uploadfilecontent','$uploadfilename')";
			}elseif($SOURCE == "inci"){
				$SQL = "INSERT INTO tincident_file(id_incident, content, name) ";
				$SQL .= "VALUES ($ID_SOURCE, '$uploadfilecontent','$uploadfilename')";
			}elseif($SOURCE == "nonc"){
				$SQL = "INSERT INTO tnonconformity_file(id_nonconformity, content, name) ";
				$SQL .= "VALUES ($ID_SOURCE, '$uploadfilecontent','$uploadfilename')";
			}
			$RS = pg_query($conn, $SQL);
		}
	} elseif($OP_TYPE == "del"){
		$ID_FILE = trim(addslashes($_POST['idFile']));
		if($SOURCE == "task"){
			$SQL = "DELETE FROM ttask_workflow_file WHERE id = $ID_FILE ";
			$SQL .= "AND id_task IN (SELECT id FROM tincident WHERE id_instance = ".$_SESSION['INSTANCE_ID']."";
			$SQL .= "AND id = $ID_SOURCE)";
		}elseif($SOURCE == "inci"){
			$SQL = "DELETE FROM tincident_file WHERE id = $ID_FILE ";
			$SQL .= "AND id_incident IN (SELECT id FROM tincident WHERE id_instance = ".$_SESSION['INSTANCE_ID']."";
			$SQL .= "AND id = $ID_SOURCE)";
		}elseif($SOURCE == "nonc"){
			$SQL = "DELETE FROM tnonconformity_file WHERE id = $ID_FILE ";
			$SQL .= "AND id_nonconformity IN (SELECT id FROM tnonconformity WHERE id_instance = ".$_SESSION['INSTANCE_ID']."";
			$SQL .= "AND id = $ID_SOURCE)";
		}
		$RS = pg_query($conn, $SQL);
	}
	//header("Location:incident.php");
}
?>