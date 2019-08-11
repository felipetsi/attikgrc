<?php
session_start();
// Start - individual configuration
$_SESSION['LP'] = "../../"; //Level path
$CONF_DIRECTORY_NAME = "attikgrc";
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THIS_PAGE = "project.php";
$_SESSION['THIS_PAGE'] = $THIS_PAGE;
$DESTINATION_PAGE = "project_run.php";
$DESTINATION_PAGE_PROJECT = "project_access.php";
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
	$PERMITIONS_NAME_1 = "create_project@";
	$PERMITIONS_NAME_2 = "read_project@";
	
	// Verify if multi-select is enable
	if(isset($_POST['change_multi_sel'])&&($_POST['change_multi_sel'] == 1)){if($_SESSION['STATUS_MULT_SEL'] == 0){$_SESSION['STATUS_MULT_SEL'] = 1;}
																			 else {$_SESSION['STATUS_MULT_SEL'] = 0;}}
	else {$_SESSION['STATUS_MULT_SEL'] = 0;}
	// Verify if multi-select is enable
	
	if(!empty($_POST['checkeditem'])){
		$ID_ITEM_SELECTED = substr(trim(addslashes($_POST['checkeditem'])),0,50);
	} elseif(isset($_SESSION['ID_SEL'])) {
		$ID_ITEM_SELECTED = $_SESSION['ID_SEL'];
	}
	
	if(!empty($ID_ITEM_SELECTED)){
		// Start - individual configuration
		$SQL = "SELECT id, name, detail, id_sponsor, id_manager, budget, id_best_pratices, status, ";
		$SQL .= "TO_CHAR(deadline,'".$LANG_DATE_FORMAT_UPPERCASE."') AS deadline, creation_date, id_creator ";
		$SQL .= "FROM tproject ";
		$SQL .= "WHERE id = $ID_ITEM_SELECTED ";
		$SQL .= "AND id_instance=".$_SESSION['INSTANCE_ID'];
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
		echo '
		<script type="text/javascript">	
			$(document).ready(function(){
				var date_input=$(\'input[name="deadline"]\'); //our date input has the name "date"
				var container=$(\'.bootstrap-iso form\').length>0 ? $(\'.bootstrap-iso form\').parent() : "body";
				var options={
					format: \''.$LANG_DATE_FORMAT.'\',
					container: container,
					todayHighlight: true,
					autoclose: true,
				};
				date_input.datepicker(options);
			})
		</script>';
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
					<li><a id="p" href="#"><i class="fa fa-inbox"></i> <?php echo $LANG_ACCESS_PROJECT;?></a></li>
						<div class="sumenu_divider"></div>
					<li><a id="m" href="#"><i class="fa fa-tasks"></i> <?php echo ${'LANG_MULTI_SELECT_'.$_SESSION['STATUS_MULT_SEL'].''};?></a></li>
				</ul>
			</div>
			<!-- End - individual configuration - End submenu -->
			<!-- Start - Project form -->
			<form action="<?php echo $DESTINATION_PAGE_PROJECT;?>" method="post" name="project_form" id="project_form">
				<input type="hidden" name="id_project" id="id_project" value="<?php echo $ID_ITEM_SELECTED;?>">
			</form>
			<!-- End - Project form -->
				
			<div class="content-wrapper">
				<div class="container-fluid">
					<?php require_once($_SESSION['LP'].'include/sub_menu_improvement.php'); ?>
					<!-- edit item-->
					<div class="mb-0 mt-4"></div>
						<hr class="mt-2">
						<div class="card-header">
							<div class="card mb-3">
								<label class="control-label"><center><i><u><?php echo $LANG_PROJECT;?></u></i></center></label>
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
												($ARRAYSELECTION['status'] == 'c'))
											{
												$CRET_CONT_INP = "readonly";
												$CRET_CONT_SEL = "disabled";
											}
										} else {
											if(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1) === false)
											{
												$CRET_CONT_INP = "readonly";
												$CRET_CONT_SEL = "disabled";
											}
										}?>
										<form action="<?php echo $DESTINATION_PAGE;?>" method="post" name="main_form" id="main_form" >
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
														   <?php echo ($CRET_CONT_INP); ?> onkeydown="javascript:submitenter(event.keyCode);"/>
												</div>
												<div class="col-md-3">
													<?php
													if(isset($ARRAYSELECTION)){
														echo '
														<div class = "row">
															<label class="control-label"><u>'.$LANG_PROJECT_NUM.':</u></label> 
																'.str_pad($ARRAYSELECTION['id'], $CONF_LENGTH_NUM, "0", STR_PAD_LEFT).'
														</div>
														<div class = "row">
															<div class="col-md">
																<button type="button" class="btn btn  btn-block"
																onclick="javascript:accessProjet('.$ARRAYSELECTION['id'].');">
																<i class="fa fa-inbox"></i> '.$LANG_ACCESS_PROJECT.'</button>
															</div>
														</div>';
													}?>
												</div>
												<div class="col-md-2 alert-info text-center">
													<strong>
														<?php if(isset($ARRAYSELECTION)){echo ${$ARRAYSELECTION['status']};} ?>
													</strong>
												</div>
											</div>
											<div class="row">
												<div class="col-md-5">
													<label class="control-label"><?php echo $LANG_DETAIL;?>:</label>
													<textarea class="form-control input-sm" rows="5" id="detail" name="detail" 
															  placeholder="<?php echo $LANG_DESCRIPTION;?>" <?php echo ($CRET_CONT_INP);?>><?php if(!empty($_SESSION['DETAIL'])){ echo $_SESSION['DETAIL'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['detail'];} ?></textarea>
												</div>
												<div class="col-md-5">
													<label class="control-label"><u><?php echo $LANG_BEST_PRATICES;?>:</u></label>
													<?php if((isset($ARRAYSELECTION))&&(empty($CRET_CONT_SEL))){ $CONTROL_ITEM = "readonly";}?>
													<select class="form-control" id="bestpratices" name="bestpratices" <?php echo ($CRET_CONT_SEL.$CONTROL_ITEM);?>>
														<option></option>
														<?php
														$SQL = "SELECT id,name FROM tbest_pratice WHERE id_instance = ".$_SESSION['INSTANCE_ID']." ";
														$SQL .= "AND status = 'a' ORDER BY name";
														$RSPRATICES = pg_query($conn, $SQL);
														$ARRAYPRATICES = pg_fetch_array($RSPRATICES);

														if(!empty($_SESSION['BESTPRATICES'])){ $PARAMETER_SEL = $_SESSION['BESTPRATICES'];}
														else{$PARAMETER_SEL = $ARRAYSELECTION['id_best_pratices'];}
				
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
												<div class="col-md-5">
													<label class="control-label"><?php echo $LANG_SPONSOR;?>:</label>
													<select class="form-control" id="sponsor" name="sponsor" <?php echo $CRET_CONT_SEL?>>
														<option></option>
														<?php
														$SQL = "SELECT id,name FROM tperson WHERE id_instance = ".$_SESSION['INSTANCE_ID']."  ";
														$SQL .= "AND status <> 'e' ORDER BY name";
														$RSPERSON = pg_query($conn, $SQL);
														$ARRAYPERSON = pg_fetch_array($RSPERSON);

														if(!empty($_SESSION['SPONSOR'])){ $PARAMETER_SEL = $_SESSION['SPONSOR'];}
														else{$PARAMETER_SEL = $ARRAYSELECTION['id_sponsor'];}
				
														do{
															if ($ARRAYPERSON['id'] == $PARAMETER_SEL) {
																$sel = 'selected="selected"';
															} else {
																$sel = '';
															}
															echo '<option value="'.$ARRAYPERSON['id'].'" '.$sel.'>'.$ARRAYPERSON['name'].'</option>';
														}while($ARRAYPERSON = pg_fetch_array($RSPERSON)); ?>
													</select>
												</div>
												<div class="col-md-5">
													<label class="control-label"><?php echo $LANG_PROJECT_MANAGER;?>:</label>
													<select class="form-control" id="manager" name="manager" <?php echo $CRET_CONT_SEL?>>
														<option></option>
														<?php
														$SQL = "SELECT id,name FROM tperson WHERE id_instance = ".$_SESSION['INSTANCE_ID']."  ";
														$SQL .= "AND status <> 'e' ORDER BY name";
														$RSPERSON = pg_query($conn, $SQL);
														$ARRAYPERSON = pg_fetch_array($RSPERSON);

														if(!empty($_SESSION['MANAGER'])){ $PARAMETER_SEL = $_SESSION['MANAGER'];}
														else{$PARAMETER_SEL = $ARRAYSELECTION['id_manager'];}
				
														do{
															if ($ARRAYPERSON['id'] == $PARAMETER_SEL) {
																$sel = 'selected="selected"';
															} else {
																$sel = '';
															}
															echo '<option value="'.$ARRAYPERSON['id'].'" '.$sel.'>'.$ARRAYPERSON['name'].'</option>';
														}while($ARRAYPERSON = pg_fetch_array($RSPERSON)); ?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-md-5">
													<label class="control-label"><?php echo $LANG_BUDGET;?>:</label>
													<div class="input-group">
														<span class="input-group-addon"><?php echo $LANG_MONEY;?></span>
														<input class="form-control input-sm" type="text" id="budget" name="budget" 
															   placeholder="<?php echo $LANG_BUDGET;?>" 
															   value ="<?php if(!empty($_SESSION['BUDGET'])){ 
																echo number_format($_SESSION['BUDGET'], 2, ',', '.');} 
																elseif(isset($ARRAYSELECTION)){
																	echo $ARRAYSELECTION['budget'];} ?>"
															   <?php echo ($CRET_CONT_INP); ?> />
													</div>
												</div>
												<div class="col-md-5">
													<label class="control-label"><?php echo $LANG_DEADLINE;?>:</label>
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<input class="form-control input-sm" type="text" id="deadline" name="deadline" 
															   placeholder="<?php echo $LANG_DEADLINE;?>" 
															   value ="<?php if(!empty($_SESSION['DEADLINE'])){ echo $_SESSION['DEADLINE'];} elseif(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['deadline'];} ?>"
															   <?php echo ($CRET_CONT_INP); ?> />
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-5"></div>
												<div class="col-md-5 box_information">
													<div class="row">
														<label class="control-label"><u><?php echo $LANG_CREATOR;?></u>:</label> 
														<?php
															if(isset($ARRAYSELECTION)){
																if(!empty($ARRAYSELECTION['id_creator'])){
																	$SQL = "SELECT name FROM tperson WHERE id = ".$ARRAYSELECTION['id_creator'];
																	$RSINSIDE = pg_query($conn, $SQL);
																	$ARRAYINSIDE = pg_fetch_array($RSINSIDE);
																	$CREATOR = $ARRAYINSIDE['name'];
																	echo $CREATOR; 
																} else {echo $LANG_SYSTEM;}
															} else {echo $_SESSION['user_name'];}?>
													</div>
													<div class="row">
														<label class="control-label"><u><?php echo $LANG_CREATION_DATE;?></u>:</label> 
														<?php if(isset($ARRAYSELECTION)){echo $ARRAYSELECTION['creation_date'];} ?>
													</div>
												</div>
											</div>
												
											<?php // End - individual configuration	?>
											<div class="panel panel-default">
												<div class="row">
													<div class="col-md-3">
														<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:postForm();"><i class="fa fa-plus-square-o"></i> <?php if(empty($ID_ITEM_SELECTED)){echo "$LANG_INSERT";} else {echo "$LANG_UPDATE";}?></button>
													</div>
													<div class="col-md-3">
														<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:clearForm();"><i class="fa fa-square-o"></i> <?php if(empty($ID_ITEM_SELECTED)){echo "$LANG_CLEAR";} else {echo "$LANG_UNSELECT";}?></button>
													</div>
													<div class="col-md-3">
														<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:deleteItem();"><i class="fa fa-minus-square-o"></i> <?php if(empty($ID_ITEM_SELECTED)){echo "$LANG_NO_SELECTED";} else {echo "$LANG_DELETE";}?></button>
													</div>
													<?php
													if(isset($ARRAYSELECTION)){
														if(strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1) !== false){
														echo '
														<div class="col-md-3">
															<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:finishItemControl();">
															<i class="fa fa-check-square-o"></i>';
																if($ARRAYSELECTION['status'] == 'o'){echo $LANG_START;}
																elseif($ARRAYSELECTION['status'] == 't'){echo $LANG_CLOSE;}
																elseif($ARRAYSELECTION['status'] == 'c'){echo $LANG_REOPEN;}
															echo '
															</button>
														</div>';
														}
													}?>
												</div>
											</div>
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
												<th><?php echo $LANG_No;?></th>
												<th><?php echo $LANG_NAME;?></th>
												<th><?php echo $LANG_SPONSOR;?></th>
												<th><?php echo $LANG_PROJECT_MANAGER;?></th>
												<th><?php echo $LANG_BUDGET;?></th>
												<th><?php echo $LANG_BEST_PRATICES;?></th>
												<th><?php echo $LANG_DEADLINE;?></th>
												<th><?php echo $LANG_STATUS;?></th>
											</tr>
										</thead>
										<?php
										// Start - individual configuration
										if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2) === false)){
											echo '
											<tr class="odd gradeX">
												<th><input type="checkbox" name="select_all_itens" id="select_all_itens"></th>
												<th></th>
												<th>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
											</tr>';
										} else {
											// Select this page item
											$SQL = "SELECT id, name, id_sponsor, id_manager, budget, id_best_pratices, status, ";
											$SQL .= "TO_CHAR(deadline,'".$LANG_DATE_FORMAT_UPPERCASE."') AS deadline ";
											$SQL .= "FROM tproject ";
											$SQL .= "WHERE  id_instance = ".$_SESSION['INSTANCE_ID'];
											$SQL .= " ORDER BY id ASC";
											$RS = pg_query($conn, $SQL);
											$ARRAY = pg_fetch_array($RS);
											if(pg_affected_rows($RS) == 0){?>
												<tr class="odd gradeX">
													<td><input type="checkbox" name="optcheckitem"></td>
													<td></td>
													<td><?php echo $LANG_NO_HAVE_DATE;?></td>
													<td></td>
													<td></td>
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
														
														if(!empty($ARRAY['id_sponsor'])){
															$SQL = "SELECT name FROM tperson WHERE id = ".$ARRAY['id_sponsor'];
															$RSINSIDE = pg_query($conn, $SQL);
															$ARRAYINSIDE = pg_fetch_array($RSINSIDE);
															$SPONSOR = $ARRAYINSIDE['name'];
														} else {
															$SPONSOR = "";
														}
														if(!empty($ARRAY['id_manager'])){
															$SQL = "SELECT name FROM tperson WHERE id = ".$ARRAY['id_manager'];
															$RSINSIDE = pg_query($conn, $SQL);
															$ARRAYINSIDE = pg_fetch_array($RSINSIDE);
															$MANAGER = $ARRAYINSIDE['name'];
														} else {
															$MANAGER = "";
														}
														$SQL = "SELECT id,name FROM tbest_pratice WHERE id = ".$ARRAY['id_best_pratices'];
														$RSINSIDE = pg_query($conn, $SQL);
														$ARRAYINSIDE = pg_fetch_array($RSINSIDE);
														$BESTPRATICES = $ARRAYINSIDE['name'];
												?>
													<tr class="odd gradeX" id="item_<?php echo $ARRAY['id'];?>">
														<td data-id="<?php echo $ARRAY['id'];?>"><input type="checkbox" name="optcheckitem[]" id="optcheckitem[]" value="<?php echo $ARRAY['id']; ?>" <?php echo $sel; ?> ><a href="<?php echo 'javascript:selectTableItem('.$ARRAY['id'].')';?>"></a></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo str_pad($ARRAY['id'], 8, "0", STR_PAD_LEFT);?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo substr($ARRAY['name'],0,50);?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo $SPONSOR;?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo $MANAGER;?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo $ARRAY['budget'];?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo $BESTPRATICES;?></td>
														<td data-id="<?php echo $ARRAY['id'];?>"><?php echo $ARRAY['deadline'];?></td>
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
	destroySession(array('ID_SEL','ID_PROJECT','BESTPRATICES','DEADLINE','BUDGET','MANAGER','SPONSOR','DETAIL','NAME'));
	$_SESSION['LAST_PAGE'] = $THIS_PAGE;
} ?>