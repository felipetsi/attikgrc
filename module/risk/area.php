<?php
session_start();
// Start - individual configuration
$_SESSION['LP'] = "../../"; //Level path
$CONF_DIRECTORY_NAME = "attikgrc";
$DESTINATIONPAGE_NO_LOGIN = $CONF_DIRECTORY_NAME.$_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THIS_PAGE = "area.php";
$_SESSION['THIS_PAGE'] = $THIS_PAGE;
$DESTINATION_PAGE = "area_run.php";
$DESTINATION_PAGE_NEXT = "process.php";

// END - individual configuration
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:".$_SESSION['LP']."$DESTINATIONPAGE_NO_LOGIN");
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
	$PERMITIONS_NAME_1 = "create_area@";
	$PERMITIONS_NAME_2 = "read_area@";
	
	// Verify if multi-select is enable
	if(isset($_POST['change_multi_sel'])&&($_POST['change_multi_sel'] == 1)){if($_SESSION['STATUS_MULT_SEL'] == 0){$_SESSION['STATUS_MULT_SEL'] = 1;}
																			 else {$_SESSION['STATUS_MULT_SEL'] = 0;}}
	else {$_SESSION['STATUS_MULT_SEL'] = 0;}
	// Verify if multi-select is enable
	
	if(!empty($_POST['checkeditem'])){
		$ID_ITEM_SELECTED = trim(addslashes($_POST['checkeditem']));
	} elseif(isset($_SESSION['ID_SEL'])) {
		$ID_ITEM_SELECTED = $_SESSION['ID_SEL'];
	} elseif(!empty($_POST['itemBackRelated'])){
		$ID_ITEM_SELECTED = trim(addslashes($_POST['itemBackRelated']));
	}
	
	
	if(!empty($ID_ITEM_SELECTED)){
		// Start - individual configuration
		$SQL = "SELECT id, name, detail, id_responsible, status, relevancy ";
		$SQL .= "FROM tarea ";
		$SQL .= "WHERE id = $ID_ITEM_SELECTED AND ";
		$SQL .= "id_instance=".$_SESSION['INSTANCE_ID'];
		$RS = pg_query($conn, $SQL);
		$ARRAYSELECTION = pg_fetch_array($RS);
		// End - individual configuration
	} else {
		$ID_ITEM_SELECTED = '';
	}
	?>
	<!DOCTYPE html>
	<html lang="en">
		<?php print_general_head($LANG); 
	
		// Start - individual configuration
		
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
						<div class="card-header">
							<div class="card mb-3">
								<label class="control-label"><center><i><u><?php echo $LANG_AREA;?></u></i></center></label>
								<div class="row">
									<div class="col-md-12">
										<button id="btn_collapse_panel" type="button" class="btn btn-default  btn-block" data-toggle="collapse" data-target="#editPanel"><i id="btn_collapse_panel_icon_up" class="fa fa-angle-double-down"></i></button>
									</div>
								</div>
								 	
								<div id= "editPanel" class="<?php if(isset($_SESSION['NAME'])){echo 'collapse_in';}else{echo 'collapse';}?>">
									<?php if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false &&
											 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2)) === false){
										echo '<center>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</center>';
									} else {
										// Start variables
										$CRET_CONT_INP = "";
										$CRET_CONT_SEL = "";
				
										if(isset($ARRAYSELECTION)){
											if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false ||
												($ARRAYSELECTION['status'] == 'd'))
											{
												$CRET_CONT_INP = "readonly";
												$CRET_CONT_SEL = "disabled";
											}
										}?>
										<form action="<?php echo $DESTINATION_PAGE;?>" method="post" name="main_form" id="main_form"  autocomplete="off">
											<input type="hidden" name="id_item_selected" id="id_item_selected" value="<?php echo $ID_ITEM_SELECTED;?>">
											<input type="hidden" name="mark_deleteitem" id="mark_deleteitem" value="0">
											<input type="hidden" name="mark_duplicateitem" id="mark_duplicateitem" value="0">
											<input type="hidden" name="mark_disableitem" id="mark_disableitem" value="0">
											<input type="hidden" name="mark_finishitem" id="mark_finishitem" value="0">
											<?php // Start - individual configuration	?>
											
											<div class="row">
												<div class="col-md-5">
													<label class="control-label"><u><?php echo $LANG_NAME;?>:</u></label>
													<input class="form-control input-sm" type="text" id="name" name="name" 
														   placeholder="<?php echo $LANG_NAME;?>" 
														   value ="<?php if(!empty($_SESSION['NAME'])){ echo $_SESSION['NAME'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['name'];} ?>"
														   <?php echo ($CRET_CONT_INP); ?> onkeydown="javascript:submitenter(event.keyCode);" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-5">
													<label class="control-label"><?php echo $LANG_DETAIL;?>:</label>
													<textarea class="form-control input-sm" rows="5" id="detail" name="detail" 
															  placeholder="<?php echo $LANG_DESCRIPTION;?>" <?php echo ($CRET_CONT_INP);?>><?php if(!empty($_SESSION['DETAIL'])){ echo $_SESSION['DETAIL'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['detail'];} ?></textarea>
												</div>
												<div class="col-md-5">
													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_RESPONSIBLE;?>:</label>
															<select class="form-control" id="responsible" name="responsible" <?php echo ($CRET_CONT_SEL);?>>
																<option></option>
																<?php
																$SQL = "SELECT id,name FROM tperson WHERE id_instance = ".$_SESSION['INSTANCE_ID']." ";
																$SQL .= "AND status = 'a' ORDER BY name";
																$RSPRATICES = pg_query($conn, $SQL);
																$ARRAYPRATICES = pg_fetch_array($RSPRATICES);

																if(!empty($_SESSION['RESPONSIBLE'])){ $PARAMETER_SEL = $_SESSION['RESPONSIBLE'];}
																else{$PARAMETER_SEL = $ARRAYSELECTION['id_responsible'];}

																do{
																	if ($ARRAYPRATICES['id'] == $PARAMETER_SEL) {
																		$sel = 'selected="selected"';
																	} else {
																		$sel = '';
																	}
																	echo '<option value="'.$ARRAYPRATICES['id'].'" '.$sel.'>'.$ARRAYPRATICES['name'].'</option>';
																}while($ARRAYPRATICES = pg_fetch_array($RSPRATICES)); ?>
															</select>
														</div>
													</div>
													<div class="row">
														<div class="col-md">
															<label class="control-label"><?php echo $LANG_RELEVANCY;?>:</label>
															<select class="form-control" id="relevancy" name="relevancy" <?php echo $CRET_CONT_SEL?>>
																<option></option>
																<?php
																if(!empty($_SESSION['RELEVANCY'])){ $PARAMETER_SEL = $_SESSION['RELEVANCY'];}
																else{$PARAMETER_SEL = $ARRAYSELECTION['relevancy'];}

																foreach ($CONF_RELEVANCY_SCALE as $item_op) {
																	if ($item_op == $PARAMETER_SEL) {
																		$sel = 'selected="selected"';
																	} else {
																		$sel = '';
																	}
																	echo '<option value="'.$item_op.'" '.$sel.'>'.${"L".$item_op}.'</option>';
																}
																?>
															</select>
														</div>
													</div>
													<div class="row">
														<div class="col-md">
															<?php
															if(!empty($ARRAYSELECTION['status'])){
																if ($ARRAYSELECTION['status'] == 'd'){
																	$sel = 'checked="checked"';
																} else {
																	$sel = '';
																}
															} else {
																$sel = '';
															}?>
															<input type="checkbox" name="status" id="status" value="d" <?php echo $sel;?> 
																   onclick="javascript:disableItemInForm(<?php echo $ID_ITEM_SELECTED?>);">
															<?php echo $LANG_DISABLE;?>
															<div class="small"><?php // $LANG_TEXT_WARNING_DISABLE_AREA;?></div>
														</div>
													</div>
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
												<th><?php echo $LANG_RESPONSIBLE;?></th>
												<th><?php echo $LANG_RELEVANCY;?></th>
												<th><?php echo $LANG_STATUS;?></th>
											</tr>
										</thead>
										<?php
										// Start - individual configuration
										if(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2) === false){
											echo '
											<tr class="odd gradeX">
												<th><input type="checkbox" name="select_all_itens" id="select_all_itens"></th>
												<th>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</th>
												<th></th>
												<th></th>
												<th></th>
											</tr>';
										} else {
											// Select this page item
											$SQL = "SELECT d.id, d.name, p.name AS responsible, d.status, d.relevancy ";
											$SQL .= "FROM tarea d LEFT OUTER JOIN tperson p ON p.id = d.id_responsible ";
											$SQL .= "WHERE d.id_instance=".$_SESSION['INSTANCE_ID'];
											$SQL .= " ORDER BY d.name ASC"; 
											$RS = pg_query($conn, $SQL);
											$ARRAY = pg_fetch_array($RS);
											if(pg_affected_rows($RS) == 0){?>
												<tr class="odd gradeX">
													<td><input type="checkbox" name="optcheckitem"></td>
													<td><?php echo $LANG_NO_HAVE_DATE;?></td>
													<td></td>
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
														<td data-id="<?php echo $ARRAY['id'];?>"><input type="checkbox" name="optcheckitem[]" id="optcheckitem[]" value="<?php echo $ARRAY['id']; ?>" <?php echo $sel; ?> ><a href="<?php echo 'javascript:selectTableRelatedItem('.$ARRAY['id'].')';?>"></a></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo substr($ARRAY['name'],0,50);?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo substr($ARRAY['responsible'],0,50);?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo ${"L".$ARRAY['relevancy']};?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo ${$ARRAY['status']};?></td>
													</tr>
														<?php
												} while($ARRAY = pg_fetch_array($RS));
											}
											// END - individual configuration
										}
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
			<!-- /.container-fluid -->
			<?php print_end_page_inside($LANG,$ID_ITEM_SELECTED);?>
		</body>
	</html>
<?php 
	destroySession(array('ID_SEL','RELEVANCY','RESPONSIBLE','DETAIL','NAME'));
	$_SESSION['LAST_PAGE'] = $THIS_PAGE;
}?>