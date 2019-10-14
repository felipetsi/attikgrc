<?php
session_start();
// Start - individual configuration
$_SESSION['LP'] = "../../"; //Level path
$CONF_DIRECTORY_NAME = "attikgrc";
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THIS_PAGE = "asset.php";
$_SESSION['THIS_PAGE'] = $THIS_PAGE;
$DESTINATION_PAGE = "asset_run.php";
$DESTINATION_PAGE_NEXT = "risk.php";
// This session variable is used in box_task with 4 character
$_SESSION['PAGE_FROM'] = "asset";

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
	
	// Start - individual configuration	
	$PERMITIONS_NAME_1 = "create_asset@";
	$PERMITIONS_NAME_2 = "read_own_asset@";
	$PERMITIONS_NAME_3 = "read_all_asset@";
	
	// Verify if multi-select is enable
	if(isset($_POST['change_multi_sel'])&&($_POST['change_multi_sel'] == 1)){if($_SESSION['STATUS_MULT_SEL'] == 0){$_SESSION['STATUS_MULT_SEL'] = 1;}
																			 else {$_SESSION['STATUS_MULT_SEL'] = 0;}}
	else {$_SESSION['STATUS_MULT_SEL'] = 0;}
	// Verify if multi-select is enable
	
	if(!empty($_POST['id_process'])){
		$ID_ITEM_RELATED = trim(addslashes($_POST['id_process']));
		$_SESSION['ID_ITEM_RELATED'] = $ID_ITEM_RELATED;
	} elseif ($_SESSION['LAST_PAGE'] != $THIS_PAGE){
		unset($_SESSION['ID_ITEM_RELATED']);
	} elseif(!empty($_SESSION['ID_ITEM_RELATED'])) {
		$ID_ITEM_RELATED = $_SESSION['ID_ITEM_RELATED'];
	}
	
	if(!empty($_POST['checkeditem'])){
		$ID_ITEM_SELECTED = trim(addslashes($_POST['checkeditem']));
	} elseif(isset($_SESSION['ID_SEL'])) {
		$ID_ITEM_SELECTED = $_SESSION['ID_SEL'];
	}
	
	if(!empty($ID_ITEM_SELECTED)){
		// Start - individual configuration
		$SQL = "SELECT a.id, a.id_process, e.name AS responsible, a.name, a.detail, a.status ";
		$SQL .= "FROM tasset a, tprocess p, tperson e ";
		$SQL .= "WHERE p.id = a.id_process AND p.id_risk_responsible = e.id AND a.id = $ID_ITEM_SELECTED ";
		$SQL .= "AND a.id_process IN(SELECT id FROM tprocess WHERE id_area IN ";
		$SQL .= "(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")) ORDER BY a.name ASC";
		$RS = pg_query($conn, $SQL);
		$ARRAYSELECTION = pg_fetch_array($RS);
		
		$SQL = "SELECT * FROM taasset_impact WHERE id_asset = $ID_ITEM_SELECTED ";
		$RS = pg_query($conn, $SQL);
		$ARRAY = pg_fetch_array($RS);
		do{
			$IMPACT_SEL_VALUE[$ARRAY['id_impact']] = $ARRAY['value'];
		}while($ARRAY = pg_fetch_array($RS));
		
		// End - individual configuration
	} else {
		$ID_ITEM_SELECTED = '';
	}
	?>
	<!DOCTYPE html>
	<html lang="en">
		<?php print_general_head($LANG); 
	
		// Start - individual configuration
		echo '<script src="'.$_SESSION['LP'].'js/nonconformity.js"></script>';
		// End - individual configuration 
		?>
		<body class="fixed-nav sticky-footer bg-dark" id="page-top">
			<!-- Navigation -->
			<?php
			require_once($_SESSION['LP'].'include/full_menu.php');
			?>
			<!-- Start - individual configuration - Start submenu -->
			<div id="box_submenu" role="menu">
				<ul class="submenu dropdown-menu">
					<li><a id="e" href="#"><i class="fa fa-edit"></i> <?php echo $LANG_EDIT;?></a></li>
					<li><a id="d" href="#"><i class="fa fa-minus-square-o"></i> <?php echo $LANG_DELETE;?></a></li>
					<li><a id="u" href="#"><i class="fa fa-clone"></i> <?php echo $LANG_DUPLICATE;?></a></li>
					<li><a id="s" href="#"><i class="fa fa-square-o"></i> <?php echo $LANG_DIS_ENA;?></a></li>
						<div class="sumenu_divider"></div>
					<li><a id="m" href="#"><i class="fa fa-tasks"></i> <?php echo ${'LANG_MULTI_SELECT_'.$_SESSION['STATUS_MULT_SEL'].''};?></a></li>
				</ul>
			</div>
			<!-- End - individual configuration - End submenu -->
				
			<div class="content-wrapper">
				<div class="container-fluid">
					<?php require_once($_SESSION['LP'].'include/sub_menu_risk.php'); ?>
					<!-- edit item-->
					<div class="mb-0 mt-4"></div>
						<hr class="mt-2">
						<?php
						if(!empty($ID_ITEM_RELATED)){
							$SQL = "SELECT a.id AS id_area, a.name AS area, p.id AS id_process, p.name AS process FROM tarea a, tprocess p WHERE ";
							$SQL .= "p.id = $ID_ITEM_RELATED AND p.id_area = a.id";
							$RS = pg_query($conn, $SQL);
							$ARRAY = pg_fetch_array($RS);
							
							echo '
							<form action="area.php" method="post" name="formBackRelatedArea" id="formBackRelatedArea">
								<input type="hidden" name="itemBackRelated" id="itemBackRelated">
							</form>

							<div class="row small">
								<div class="col-md">
									<a href="javascript:backToRelatedArea('.$ARRAY['id_area'].');">'.substr($ARRAY['area'],0,30).'</a>
								</div>
								<div class="col-md-1 small"> <i class="fa fa-caret-right"></i></div>
								<div class="col-md">
									<a href="javascript:backToRelatedArea('.$ARRAY['id_process'].');">'.substr($ARRAY['process'],0,30).'</a>
								</div>
									<div class="col-md-1 small"> <i class="fa fa-caret-left"></i></div>
							</div>';
						}?>
						<div class="card-header">
							<div class="card mb-3">
								<label class="control-label"><center><i><u><?php echo $LANG_ASSET;?></u></i></center></label>
								<div class="row">
									<div class="col-md-12">
										<button id="btn_collapse_panel" type="button" class="btn btn-default  btn-block" data-toggle="collapse" data-target="#editPanel"><i id="btn_collapse_panel_icon_up" class="fa fa-angle-double-down"></i></button>
									</div>
								</div>
								 	
								<div id= "editPanel" class="<?php if(isset($_SESSION['NAME_ASSET'])){echo 'collapse_in';}else{echo 'collapse';}?>">
									<?php if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false &&
											 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2)) === false &&
											 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3)) === false){
										echo '<center>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</center>';
									} else {
										if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false) {
											$CRET_CONT_INP = "readonly";
											$CRET_CONT_SEL = "disabled";
										} else {
											// Start variables
											$CRET_CONT_INP = "";
											$CRET_CONT_SEL = "";
										}
				
										if(isset($ARRAYSELECTION)){
											if($ARRAYSELECTION['status'] == 'd')
											{
												$CRET_CONT_INP = "readonly";
												$CRET_CONT_SEL = "disabled";
											}
										}
									
									$_SESSION['CRET_CONT_INP'] = $CRET_CONT_INP;
									$_SESSION['CRET_CONT_SEL'] = $CRET_CONT_SEL;
									?>
										<form action="<?php echo $DESTINATION_PAGE;?>" method="post" name="main_form" id="main_form"  autocomplete="off">
											<input type="hidden" name="id_item_selected" id="id_item_selected" value="<?php echo $ID_ITEM_SELECTED;?>">
											<input type="hidden" name="mark_deleteitem" id="mark_deleteitem" value="0">
											<input type="hidden" name="mark_duplicateitem" id="mark_duplicateitem" value="0">
											<input type="hidden" name="mark_disableitem" id="mark_disableitem" value="0">
											<?php // Start - individual configuration	?>
											
											<div class="row">
												<div class="col-md-4">
													<div class="row">
														<div class="col-md">
															<label class="control-label"><u><?php echo $LANG_NAME;?>:</u></label>
															<input class="form-control input-sm" type="text" id="asset_name" name="asset_name" 
																   placeholder="<?php echo $LANG_NAME;?>" 
																   value ="<?php if(!empty($_SESSION['NAME_ASSET'])){ echo $_SESSION['NAME_ASSET'];} elseif(isset($ARRAYSELECTION)){
																	echo $ARRAYSELECTION['name'];} ?>" <?php echo ($CRET_CONT_INP); ?> 
																   onkeydown="javascript:submitenter(event.keyCode);"/>
														</div>
													</div>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_DETAIL;?>:</label>
															<textarea class="form-control input-sm" rows="5" id="asset_detail" name="asset_detail" 
																	  placeholder="<?php echo $LANG_DESCRIPTION;?>" <?php echo ($CRET_CONT_INP);?>><?php if(!empty($_SESSION['DETAIL_ASSET'])){ echo $_SESSION['DETAIL_ASSET'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['detail'];} ?></textarea>
														</div>
													</div>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_RESPONSIBLE;?>:</label>
															<input class="form-control input-sm" type="text" id="asset_responsible" name="asset_responsible" 
																   placeholder="<?php echo $LANG_AUTO_FILL;?>" 
																   value ="<?php if(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['responsible'];} ?>"
																   readonly />
														</div>
													</div>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><u><?php echo $LANG_PROCESS;?>:</u></label>
															<select class="form-control" id="asset_process" name="asset_process" 
																	<?php if(!empty($_SESSION['ID_ITEM_RELATED'])){echo 'readonly';} else { echo ($CRET_CONT_SEL); }?>>
																<option></option>
																<?php
																$SQL = "SELECT id,name FROM tprocess WHERE id_area IN (SELECT id FROM tarea WHERE ";
																$SQL .= "id_instance = ".$_SESSION['INSTANCE_ID'].") ";
																$SQL .= "AND status = 'a' ORDER BY name";
																$RS = pg_query($conn, $SQL);
																$ARRAY = pg_fetch_array($RS);

																if(!empty($_SESSION['ID_ITEM_RELATED'])){ $PARAMETER_SEL = $_SESSION['ID_ITEM_RELATED'];}
																elseif(!empty($_SESSION['PROCESS'])){ $PARAMETER_SEL = $_SESSION['PROCESS'];}
																else{$PARAMETER_SEL = $ARRAYSELECTION['id_process'];}

																do{
																	if ($ARRAY['id'] == $PARAMETER_SEL) {
																		$sel = 'selected="selected"';
																	} else {
																		$sel = '';
																	}
																	echo '<option value="'.$ARRAY['id'].'" '.$sel.'>'.$ARRAY['name'].'</option>';
																}while($ARRAY = pg_fetch_array($RS)); ?>
															</select>
														</div>
													</div>
													<div class="row">
													</div>
													
												</div> <!-- End firt column-->
												
												
												<div class="col-md">
												<?php
												$SQL = "SELECT id,name FROM timpact WHERE id_impact_type IN ";
												$SQL .= "(SELECT id FROM timpact_type WHERE ";
												$SQL .= "id_instance =".$_SESSION['INSTANCE_ID']." AND ";
												$SQL .= "name LIKE 'security')";
												$RS = pg_query($conn, $SQL);
												$ARRAY = pg_fetch_array($RS);
												$i=0;
							
												do{
													$i++;
													echo '
													<div class="row">
													<div class="col-md-5">
														<label class="control-label">'.${$ARRAY['name']}.':</label>
													</div>
													';
													if($ARRAY['name'] != 'financial'){
														echo '
														<div class="col-md">
															<select class="form-control" id="impact_'.$ARRAY['id'].'" name="impact_'.$ARRAY['id'].'" 
															'.$CRET_CONT_SEL.'>';
																if(!empty($_SESSION['IMPACT'.$ARRAY['id'].''])){ 
																	$PARAMETER_SEL = $_SESSION['IMPACT'.$ARRAY['id'].''];}
																elseif(isset($IMPACT_SEL_VALUE[$ARRAY['id']])){
																	$PARAMETER_SEL = $IMPACT_SEL_VALUE[$ARRAY['id']];
																}
																foreach ($CONF_IMPACT_LEVELS as $item_op) {
																$temp_array = explode("@",$item_op);
																	if ($temp_array[0] == $PARAMETER_SEL) {
																		$sel = 'selected="selected"';
																	} else {
																		$sel = '';
																	}
																	echo '
																	<option value="'.$temp_array[0].'" '.$sel.'>'.${$temp_array[1]}.'</option>';
																}
																echo '
															</select>
														</div>';
													} else {
														echo '
														<div class="col-md">
															<div class="input-group">
															<span class="input-group-addon">'.$LANG_MONEY.'</span>
															<input class="form-control input-sm" type="text" id="impact_'.$ARRAY['id'].'" name="impact_'.$ARRAY['id'].'"  
															   placeholder = "'.$LANG_FINANCIAL_IMPACT.'" 
															   value ="';
																	if(!empty($_SESSION['IMPACT'.$ARRAY['id'].''])){ echo $_SESSION['IMPACT'.$ARRAY['id'].''];}
																	elseif(isset($IMPACT_SEL_VALUE[$ARRAY['id']])){ echo $IMPACT_SEL_VALUE[$ARRAY['id']];}
																echo '" '.$CRET_CONT_INP.' />
															</div>
														</div>';
													}
													echo '	
													</div>';
													unset($_SESSION['IMPACT'.$ARRAY['id'].'']);
												}while($ARRAY = pg_fetch_array($RS));?>
												</div>
												<div class="col-md">
													<?php
													if(isset($ARRAYSELECTION)){
													echo '
													<div class="row">
														<div class="col-md ">
															<div class="col-md">
																<button type="button" class="btn btn  btn-block" data-toggle="collapse" 
																	data-target="#show_risks">
																<i class="fa fa-exclamation-triangle"></i> '.$LANG_PROCESS.'</button>
															</div>
														
															<div class="collapse box_show_task_side_right" id="show_risks">
																<label class="control-label">'.$LANG_PROCESS.':</label>
																<div id="listProcessBox"></div>
																<div class="row">
																	<div class="col-md-4">
																		<button type="button" class="btn btn-default btn-block" data-toggle="tooltip" 
																		data-placement="top" title="'.$LANG_ADD.'"
																		onclick="javascript: addProcessRelated('.$ID_ITEM_SELECTED.',\'asse\');">
																		<i class="fa fa-plus"></i></button>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<script>
														showListProcessRelationship('.$ID_ITEM_SELECTED.',\'asse\');
													</script>';
													}?>

												</div>
											</div>
											
											
											<?php // End - individual configuration
											echo '
											<div class="panel panel-default">
												<div class="row">
													<div class="col-md-3">
														<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:postForm();">
														<i class="fa fa-plus-square-o"></i> '; 
														if(empty($ID_ITEM_SELECTED)){echo "$LANG_INSERT";} else {echo "$LANG_UPDATE";} echo '</button>
													</div>
													<div class="col-md-3">
														<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:clearForm();">
														<i class="fa fa-square-o"></i> '; 
														if(empty($ID_ITEM_SELECTED)){echo "$LANG_CLEAR";} else {echo "$LANG_UNSELECT";} echo '</button>
													</div>';
													if(isset($ARRAYSELECTION)) {
														echo '
													<div class="col-md-3">
														<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:deleteItem();">
														<i class="fa fa-minus-square-o"></i> '.$LANG_DELETE.'</button>
													</div>';
													} echo '
												</div>
											</div>';?>
										</form>
									<?php }?>
								</div>
							</div>
						</div>
					<!-- Item list -->
					<div class="card mb-3">
						<div class="card-header"></div>
						<div class="card-body">
							<div class="table-responsive">
								<form action="<?php echo $THIS_PAGE;?>" 
									  method="post" name="view_form_aux" id="view_form_aux"> 
									<input type="hidden" name="change_multi_sel" id="change_multi_sel" value="">
								</form>
								<form action="<?php echo $DESTINATION_PAGE_NEXT;?>" method="post" name="submit_to_net" id="submit_to_net"> 
									<input type="hidden" name="relateditem" id="relateditem" value="">
								</form>
								<form action="<?php if($_SESSION['STATUS_MULT_SEL'] == 0){ echo $THIS_PAGE;} else {echo $DESTINATION_PAGE;}?>" 
									  method="post" name="view_form" id="view_form">
									<input type="hidden" name="checkeditem" id="checkeditem" value="">
									<input type="hidden" name="mark_deleteitem_view_form" id="mark_deleteitem_view_form" value="0">
									<input type="hidden" name="mark_duplicateitem_view_form" id="mark_duplicateitem_view_form" value="0">
									<input type="hidden" name="mark_disableitem_view_form" id="mark_disableitem_view_form" value="0">
									<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
										<thead>
											<tr>
												<th><input type="checkbox" name="select_all_itens" id="select_all_itens"></th>
												<th><?php echo $LANG_NAME;?></th>
												<th><?php echo $LANG_PROCESS;?></th>
												<th><?php echo $LANG_STATUS;?></th>
											</tr>
										</thead>
										<?php
										// Start - individual configuration
										if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2) === false) &&
											(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3) === false)){
											echo '
											<tr class="odd gradeX">
												<th><input type="checkbox" name="select_all_itens" id="select_all_itens"></th>
												<th></th>
												<th>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</th>
												<th></th>
											</tr>';
										} else {
											// Select this page item
											if(!empty($ID_ITEM_RELATED)){
												$SQL_COMPL = " AND (a.id_process = $ID_ITEM_RELATED OR a.id IN ";
												$SQL_COMPL .= "(SELECT id_asset FROM taasset_process WHERE id_process = $ID_ITEM_RELATED)) ";
											} else {
												$SQL_COMPL = "";
											}
											
											// Verify permission if can read all or only own
											if(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_3) !== false){
												$COMMPL_CONTROL = "";
											} else {
												$COMMPL_CONTROL = "AND a.id_process IN (SELECT id FROM tprocess WHERE id_responsible = ".$_SESSION['user_id'];
												$COMMPL_CONTROL .= " OR id_risk_responsible = ".$_SESSION['user_id'].") ";
											}
											$SQL = "SELECT a.id, a.name, p.name AS process, a.status ";
											$SQL .= "FROM tasset a, tprocess p, tperson e ";
											$SQL .= "WHERE p.id = a.id_process AND p.id_risk_responsible = e.id $SQL_COMPL $COMMPL_CONTROL ";
											$SQL .= "AND a.id_process IN(SELECT id FROM tprocess WHERE id_area IN ";
											$SQL .= "(SELECT id FROM tarea WHERE id_instance = ".$_SESSION['INSTANCE_ID'].")) ";
											$SQL .= "ORDER BY a.name ASC";
											$RS = pg_query($conn, $SQL);
											$ARRAY = pg_fetch_array($RS);
											if(pg_affected_rows($RS) == 0){?>
												<tr class="odd gradeX">
													<td><input type="checkbox" name="optcheckitem"></td>
													<td><?php echo $LANG_NO_HAVE_DATE;?></td>
													<td></td>
													<td></td>
												</tr>
											<?php } else {
													do{
														if (!empty($ID_ITEM_SELECTED)){
															if($ARRAY['id'] == $ID_ITEM_SELECTED) {
																//$sel = 'selected="selected"';
																$sel = 'checked="checked"';
															} else {
																$sel = '';
															}
														} else {
															$sel = '';
														}
												?>
													<tr class="odd gradeX" id="item_<?php echo $ARRAY['id'];?>">
														<td data-id="<?php echo $ARRAY['id'];?>"><input type="checkbox" name="optcheckitem[]" id="optcheckitem[]" value="<?php echo $ARRAY['id']; ?>" <?php echo $sel; ?> ><a href="<?php echo 'javascript:selectTableItem('.$ARRAY['id'].')';?>"></a></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo substr($ARRAY['name'],0,50);?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo substr($ARRAY['process'],0,50);?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo ${$ARRAY['status']};?></td>
													</tr>
														<?php
												} while($ARRAY = pg_fetch_array($RS));
											}
										}
											// END - individual configuration
										?>
									</table>
								</form>
								<?php printDeleteBox($LANG);?>
							</div>
						</div>
					</div>
					<div class="card-footer small text-muted"><?php echo ($LANG_SYSTEM_VERSION.": ".$CONF_VERSION);?></div>
				</div>	
			</div>
			
			<div class="modal fade" id="showFrontModalRelated" data-backdrop="static">
				<div class="modal-dialog" role="document">
					<div class="modal-content" id="front_modal_panel">
					</div>
				</div>
			</div>
			
			<div class="modal fade" id="modalRelated" tabindex="-1" role="dialog" aria-labelledby="process_boxLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content" id="panel_related">
					</div>
				</div>
			</div>
		
			<!-- /.container-fluid -->
			<?php print_end_page_inside($LANG,$ID_ITEM_SELECTED);
			printDeleteTaskBox($LANG);?>
			
		</body>
	</html>
<?php 
	destroySession(array('ID_SEL','CRET_CONT_INP','CRET_CONT_SEL','PROCESS','DETAIL_ASSET','NAME_ASSET'));
	$_SESSION['LAST_PAGE'] = ("/$CONF_DIRECTORY_NAME/module/risk/$THIS_PAGE");
}?>