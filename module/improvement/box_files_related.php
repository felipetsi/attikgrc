<?php
session_start();
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/variable.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");

	$ID_RELATED_ITEM = trim(addslashes($_POST['id_source'])); // ID Incident, Task, etc
	$SOURCE = trim(addslashes($_POST['source'])); // Incident, Task, etc
	
	$PERMITIONS_NAME_1 = "create_incident@";
	$PERMITIONS_NAME_3 = "read_all_incident@";


	// Load files
	if($SOURCE == "task"){
		$SQL = "SELECT * FROM ttask_workflow_file WHERE id_task  = $ID_RELATED_ITEM ";
	}elseif($SOURCE == "inci"){
		$SQL = "SELECT * FROM tincident_file WHERE id_incident  = $ID_RELATED_ITEM ";
	}elseif($SOURCE == "nonc"){
		$SQL = "SELECT * FROM tnonconformity_file WHERE id_nonconformity  = $ID_RELATED_ITEM ";
	}
		$RS = pg_query($conn, $SQL);
	
	echo '
<script>
function uploadFile(){
	// validate type of file
    if(["image/jpeg", "image/jpg", "image/png", "image/gif", "application/pdf", "application/zip", "application/rar" , "application/msword", "application/rtf", "application/vnd.ms-excel", "application/vnd.ms-powerpoint", "application/vnd.oasis.opendocument.text", "application/vnd.oasis.opendocument.spreadsheet", "text/plain" ].indexOf($("#userfile").get(0).files[0].type) == -1) {
        alert("'.$LANG_MSG_FILE_NOT_ALLOWED.'");
        return;
    }
	
	var $data = new FormData();
	$data.append(\'file\', $("#userfile").get(0).files[0]);
	$data.append(\'id_source\', '.$ID_RELATED_ITEM.');
	$data.append(\'source\', \''.$SOURCE.'\');
	$data.append(\'op_type\', \'add\');

	// processData & contentType should be set to false
	$.ajax({
		type: \'POST\',
		url: \'upload_file.php\',
		data: $data,
		success: function(response) {
			showFileRelationship('.$ID_RELATED_ITEM.',\''.$SOURCE.'\');
		},
		error: function(response) {

		},
		processData: false,
		contentType: false
	});
}

function removeFile(idFileDel){
	var $data = new FormData();
	$data.append(\'id_source\', '.$ID_RELATED_ITEM.');
	$data.append(\'source\', \''.$SOURCE.'\');
	$data.append(\'op_type\', \'del\');
	$data.append(\'idFile\', idFileDel);

	// processData & contentType should be set to false
	$.ajax({
		type: \'POST\',
		url: \'upload_file.php\',
		data: $data,
		success: function(response) {
			showFileRelationship('.$ID_RELATED_ITEM.',\''.$SOURCE.'\');
		},
		error: function(response) {

		},
		processData: false,
		contentType: false
	});
}
</script>
	<form action="" method="post" enctype="multipart/form-data">
		<label class="control-label">'.$LANG_FILE.':</label>
		<div class="small">'.$LANG_EXT_FILE_ALLOWED.'</div>
<!--<input name="files" id="files" type="file" data-file="" onchange="javascript:uploadFile();" multiple /> -->
			<input name="userfile" id="userfile" name="userfile" type="file" onchange="javascript:uploadFile();">
		<div class="dropdown-divider"></div>
		';
	while($ARRAYSELECTION = pg_fetch_array($RS)){
		echo '
			<div class="col-sm">
				<button type="button" class="btn-secondary btn-sm" data-toggle="tooltip" 
					data-placement="top" title="'.$LANG_DELETE.'"
					onclick="javascript:removeFile('.$ARRAYSELECTION['id'].');">
					<i class="fa fa-remove"></i>
				</button>
				<a href="data:image;base64,'.($ARRAYSELECTION[3]).' " download="'.$ARRAYSELECTION[2].'" data-toggle="tooltip" title="'.$ARRAYSELECTION[2].'">'.substr($ARRAYSELECTION[2],0,35).'</a>
			</div>';
		}
	echo '
		
	</form>';
	
}?>