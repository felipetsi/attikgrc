<?php

	require("./include/conn_db.php");


    
		$uploadfilecontent = addslashes($_FILES['file']['tmp_name']);
		$uploadfilename = addslashes($_FILES['file']['name']);
		$uploadfilecontent = file_get_contents($uploadfilecontent);
		$uploadfilecontent = base64_encode($uploadfilecontent);
		// File extension
		//$file_type = pathinfo($file_name, PATHINFO_EXTENSION);



// File name
/*$uploadfilecontent = $_POST['file'];
$uploadfilecontent = file_get_contents($uploadfilecontent);
$uploadfilecontent = base64_encode($uploadfilecontent);
$uploadfilename = $_POST['filename'];
*/


	saveimage($uploadfilecontent,$uploadfilename);

		
		
	
	
	function saveimage($content,$name){
		require("./include/conn_db.php");
		$SQL = "INSERT INTO tincident_file(id_incident, content, name) ";
		$SQL .= "VALUES (1, '$content','$name')";
		$RS = pg_query($conn, $SQL);	
	}
	
?>