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
    require_once($_SESSION['LP']."include/conn_db.php");
	require_once($_SESSION['LP']."include/lang/".$_SESSION['lang_default']."/general.php");
	require_once('include/function.php');
	
	$PERMITIONS_NAME_1 = "view_history@";
	if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false){
		$_SESSION['MSG_TOP'] = 'LANG_YOU_NOT_HAVE_PERMISSION';
	} else {
        
        $ID_ITEM_SELECTED = substr(trim(addslashes($_POST['checkeditem'])),0,20);
        
        $SQL = "SELECT code, to_char(execution_time,'".$LANG_SQL_TIMESTAMP_FORMAT."') as execution_time, ";
        $SQL .= "name_person FROM thistory WHERE id = $ID_ITEM_SELECTED AND id_instance=".$_SESSION['INSTANCE_ID'];
        $RS = pg_query($conn, $SQL);
        $ARRAYSELECTION = pg_fetch_array($RS);

        //<!-- Start Modal -->
        echo '
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">'.$LANG_DETAIL.'</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <b>'.$LANG_EXECUTOR.':</b>
                        '.$ARRAYSELECTION['name_person'].'
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <b>'.$LANG_ACTION.':</b>
                        '. ${$ARRAYSELECTION['code']}.'
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <b>'.$LANG_EXECUTION_TIME.':</b>
                        '.$ARRAYSELECTION['execution_time'].'
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        ';
        //<!-- End Modal -->
	}
}
?>