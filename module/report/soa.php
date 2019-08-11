<?php
session_start();
// Start - individual configuration
$_SESSION['LP'] = "../../"; //Level path
$CONF_DIRECTORY_NAME = "attikgrc";
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THIS_PAGE = "soa.php";
$DESTINATION_PAGE = "soa_run.php";
$_SESSION['THIS_PAGE'] = $THIS_PAGE;
// This session variable is used in box_task with 4 character
$_SESSION['PAGE_FROM'] = "soa";

$_SESSION['STATUS_MULT_SEL'] = 0;

// END - individual configuration
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP'].'include/function.php');

	if(isset($_GET['lang'])){
		$CHECK_CHANGE_LANG = substr(trim(addslashes($_GET['lang'])),0,2);
		$_SESSION['lang_default'] = $CHECK_CHANGE_LANG;
		$LANG = $_SESSION['lang_default'];
	} elseif(!empty($_SESSION['lang_default'])){
		$LANG = $_SESSION['lang_default'];
	} else {
		$LANG = $CONF_DEFAULT_SYSTEM_LANG;
	}
	require_once($_SESSION['LP']."include/lang/$LANG/general.php");

	$PERMITIONS_NAME_1 = "create_report@";
	$PERMITIONS_NAME_2 = "read_report@";
	
	if(!empty($_POST['checkeditem'])){
		$ID_ITEM_SELECTED = trim(addslashes($_POST['checkeditem']));
	}
	?>
	<!DOCTYPE html>
	<html lang="en">
		<?php
		echo '<script src="'.$_SESSION['LP'].'js/reports.js"></script>';
		print_general_head($LANG);
		?>
		<body class="fixed-nav sticky-footer bg-dark" id="page-top">
			<!-- Navigation -->
			<?php
			require_once($_SESSION['LP'].'include/full_menu.php');
			?>
			<div class="content-wrapper">
				<div class="container-fluid">
					<!-- edit item-->
					<hr class="mt-2">
					<div class="card-header">
						<div id="boxReport" >
							<form action="<?php echo $DESTINATION_PAGE;?>" method="post" name="main_form" id="main_form">
								<?php
								if(!isset($ID_ITEM_SELECTED)){
									$SQL = "SELECT r.id, p.name AS created_by, TO_CHAR(r.creation_date,'".$LANG_DATE_FORMAT_UPPERCASE."') AS creation_date, ";
									$SQL .= "r.version, r.status FROM treport r, tperson p WHERE p.id = r.created_by AND ";
									$SQL .= "r.status = 'a' AND r.name = 'soa' AND ";
									$SQL .= "r.id_instance = ".$_SESSION['INSTANCE_ID']." ORDER BY r.id DESC LIMIT 1";
									
									$CRET_CONT_INP = "";
									$CRET_CONT_SEL = "";
								} else {
									$SQL = "SELECT r.id, p.name AS created_by, TO_CHAR(r.creation_date,'".$LANG_DATE_FORMAT_UPPERCASE."') AS creation_date, ";
									$SQL .= "r.version, r.status FROM treport r, tperson p WHERE p.id = r.created_by AND ";
									$SQL .= "r.id = $ID_ITEM_SELECTED AND r.name = 'soa' AND ";
									$SQL .= "r.id_instance = ".$_SESSION['INSTANCE_ID']." ORDER BY r.id DESC LIMIT 1";
								}
								$RS = pg_query($conn, $SQL);
								$ARRAY_RP = pg_fetch_array($RS);
								if($ARRAY_RP['status'] == 'a'){
									$CRET_CONT_INP = "";
									$CRET_CONT_SEL = "";
								}else{
									$CRET_CONT_INP = "readonly";
									$CRET_CONT_SEL = "disabled";
								}
								
								if(pg_affected_rows($RS) == 1){
									$SQL = "SELECT * FROM titem_report WHERE id_report=".$ARRAY_RP['id']." ORDER BY id ASC";
									$RS = pg_query($conn, $SQL);
									$ARRAY = pg_fetch_array($RS);
									echo '
									<input type="hidden" name="id_item_selected" id="id_item_selected" value="'.$ARRAY_RP['id'].'">
									<input type="hidden" name="mark_deleteitem" id="mark_deleteitem" value="0">
									<div class="col-md box_information">
										<div class="row">
											<div class="col-md-5">
											<label class="control-label"><u>'.$LANG_CREATOR.'</u>:</label>'.$ARRAY_RP['created_by'].'
											</div>
											<div class="col-md-4">
											<label class="control-label"><u>'.$LANG_CREATION_DATE.'</u>:</label>'.$ARRAY_RP['creation_date'].'
											</div>
											<div class="col-md">
											<label class="control-label"><u>'.$LANG_VERSION.'</u>:</label>'.$ARRAY_RP['version'].'
											</div>
										</div>
									</div>
									<div class="card mb-3">
										<label class="control-label"><center><i><u>'.$LANG_SOA.'</u></i></center></label>
									</div>
									<div class="panel panel-default">
										<div class="row">
											<div class="col-md">
												<div class="col-xl">
													<table class="table table-bordered">
														<thead>
															<tr>
																<th>'.$LANG_ITEM.'</th>
																<th>'.$LANG_CONTROL.'</th>
																<th>'.$LANG_APPLICABLE.'</th>
																<th>'.$LANG_JUSTIFY.'</th>
																<th>'.$LANG_IMPLEMENTED.'</th>
															</tr>
														</thead>';
									do{
										$DATA_COL = explode('@c',$ARRAY['content']);
										$DATA_ROW = explode('@r',$DATA_COL[2]);
										echo '
														<tr class="odd gradeX">
															<td>'.$DATA_COL[0].'</td>
															<td>'.$DATA_COL[1].'</td>
															<td>
																<select class="form-control" id="applicable'.$ARRAY['id'].'" 
																name="applicable'.$ARRAY['id'].'" '.$CRET_CONT_SEL.'>';
																	foreach ($CONF_BOOLEAN_OP as $item_op) {
																		$sub_item_op = explode("@",$item_op);
																		if ($sub_item_op[1] == $ARRAY['status']) {
																			$sel = 'selected="selected"';
																		} else {
																			$sel = '';
																		}
																		echo '<option value="'.$sub_item_op[1].'" '.$sel.'>'.${$sub_item_op[0]}.'</option>';
																	} echo '
																</select>
															</td>
															<td>
																<textarea class="form-control input-sm" rows="2" id="justify'.$ARRAY['id'].'"
																name="justify'.$ARRAY['id'].'" 
															  '.$CRET_CONT_INP.'>'.$ARRAY['justification'].'</textarea>
															</td>
															<td>';
																foreach($DATA_ROW as $key => $value){
																	if(!empty($value)){
																		echo '
																		<li>'.$value.'</li>';
																	}
																} echo '
															</td>
														</tr>';
									}while($ARRAY = pg_fetch_array($RS));
									echo '
													</table>
												</div>
											</div>
										</div>
									</div>';
								}
								echo '
									<div class="card mb-3">
										<div class="row">
											<div class="col-md-3">
												<button type="button" class="btn btn-secondary btn-block" onclick="window.location=\''.$DESTINATION_PAGE.'\';">
												<i class="fa fa-plus-square-o"></i> '.$LANG_CREATE_VERSION.'</button>
											</div>';
										if (!empty($ARRAY_RP['id'])){
											echo '
											<div class="col-md-3">
												<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:postForm();">
												<i class="fa fa-pencil-square-o"></i> '.$LANG_UPDATE.'</button>
											</div>
											<div class="col-md-3">
												<button type="button" class="btn btn-secondary  btn-block"
												onclick="javascript:deleteItem('.$ARRAY_RP['id'].');">
												<i class="fa fa-minus-square-o"></i> '.$LANG_DELETE.'</button>
											</div>
											<div class="col-md-3">
												<button type="button" class="btn btn-secondary  btn-block"
												onclick="javascript:showReportHistory();">
												<i class="fa fa-history"></i> '.$LANG_HISTORY.'</button>
											</div>
											';
										}echo '
										</div>
									</div>';
								?>
							</form>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</div><!-- /.content-wrapper -->
			
			<div class="modal fade" id="historyBox" tabindex="-1" role="dialog" aria-labelledby="history_boxLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="bp_boxLabel"><?php echo $LANG_HISTORY;?></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body" id="panel_history"></div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" onclick="javascript:saveReportHistory();"><i class="fa fa-pencil-square-o"></i>
							<?php echo $LANG_UPDATE;?></button>
							<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $LANG_CANCEL;?></button>
						</div>
					</div>
				</div>
			</div>
			
			<?php printDeleteBox($LANG);
			print_end_page_inside($LANG,0);?>
		</body>
	</html>
<?php 
}?>