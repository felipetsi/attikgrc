<?php
session_start();
// Start - individual configuration
$_SESSION['LP'] = "./"; //Level path
$CONF_DIRECTORY_NAME = "attikgrc";
$DESTINATIONPAGE_NO_LOGIN = "login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THIS_PAGE = "user_profile.php";
$_SESSION['THIS_PAGE'] = $THIS_PAGE;
$DESTINATION_PAGE = "user_profile_run.php";
// END - individual configuration
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once('include/function.php');

	if(isset($_GET['lang'])){
		$CHECK_CHANGE_LANG = substr(trim(addslashes($_GET['lang'])),0,2);
		$_SESSION['lang_default'] = $CHECK_CHANGE_LANG;
		$LANG = $_SESSION['lang_default'];
	} elseif(!empty($_SESSION['lang_default'])){
		$LANG = $_SESSION['lang_default'];
	} else {
		$LANG = $CONF_DEFAULT_SYSTEM_LANG;
	}
	require_once("include/lang/$LANG/general.php");
	
	// Start - individual configuration	
	$PERMITIONS_NAME_1 = "profile_manager@";
	
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
	$SQL = "SELECT p.name AS profile_name, i.name AS item_profile, i.id AS itemprofile_id FROM tprofile p, titemprofile i, taprofile_itemprofile ta ";
	$SQL .= "WHERE ta.id_profile = p.id AND ta.id_itemprofile = i.id AND p.id = $ID_ITEM_SELECTED ";
	$SQL .= "AND id_instance=".$_SESSION['INSTANCE_ID'];
	$RS = pg_query($conn, $SQL);
	$ARRAYSELECTION = pg_fetch_array($RS);

	$ID_ITEM_PROF_SELECTED = "";
	do {
		$ID_ITEM_PROF_SELECTED .= $ARRAYSELECTION['itemprofile_id']."@";
		$NAME_PROFILE = $ARRAYSELECTION['profile_name'];
	}while($ARRAYSELECTION = pg_fetch_array($RS));

		// End - individual configuration	
	} else {
		$ID_ITEM_SELECTED = '';
	}
	?>
	<!DOCTYPE html>
	<html lang="en">
		<?php
		print_general_head($LANG);
		?>
		<body class="fixed-nav sticky-footer bg-dark" id="page-top">
			<!-- Navigation -->
			<?php
			require_once('include/full_menu.php');
			?>
			<!-- Start - individual configuration - Start submenu -->
			<div id="box_submenu" role="menu">
				<ul class="submenu dropdown-menu">
					<li><a id="e" href="#"><i class="fa fa-edit"></i> <?php echo $LANG_EDIT;?></a></li>
					<li><a id="d" href="#"><i class="fa fa-minus-square-o"></i> <?php echo $LANG_DELETE;?></a></li>
					<li><a id="u" href="#"><i class="fa fa-clone"></i> <?php echo $LANG_DUPLICATE;?></a></li>
						<div class="sumenu_divider"></div>
					<li><a id="m" href="#"><i class="fa fa-tasks"></i> <?php echo ${'LANG_MULTI_SELECT_'.$_SESSION['STATUS_MULT_SEL'].''};?></a></li>
				</ul>
			</div>
			<!-- Start - individual configuration - End submenu -->
			<div class="content-wrapper">
				<div class="container-fluid">
					<?php require_once('include/sub_menu_configuration.php'); ?>
					<!-- edit item-->
					<div class="mb-0 mt-4"></div>
						<hr class="mt-2">
						<div class="card-header">
							<div class="card mb-3">
								<label class="control-label"><center><i><u><?php echo $LANG_PROFILE;?></u></i></center></label>
								<div class="row">
									<div class="col-md-12">
										<button id="btn_collapse_panel" type="button" class="btn btn-default  btn-block" data-toggle="collapse" data-target="#editPanel"><i id="btn_collapse_panel_icon_up" class="fa fa-angle-double-down"></i></button>
									</div>
								</div>
								
								<div id= "editPanel" class="<?php if(isset($_SESSION['NAME'])){echo 'collapse_in';}else{echo 'collapse';}?>">
									<?php if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false){
										echo'<center> '.$LANG_YOU_NOT_HAVE_PERMISSION.'</center>';
									} else {?>
									<form action="<?php echo $DESTINATION_PAGE;?>" method="post" name="main_form" id="main_form" onkeydown="javascript:submitenter(event.keyCode);" autocomplete="off">
										<input type="hidden" name="id_item_selected" id="id_item_selected" value="<?php echo $ID_ITEM_SELECTED;?>">
										<input type="hidden" name="mark_deleteitem" id="mark_deleteitem" value="0">
										<input type="hidden" name="mark_duplicateitem" id="mark_duplicateitem" value="0">
											
										<div class="row">
											<div class="col-md-3">
												<label class="control-label"><u><?php echo $LANG_NAME;?>:</u></label>
												<input class="form-control input-sm" type="text" id="name" name="name" 
													   placeholder="<?php echo $LANG_NAME;?>" 
													   value ="<?php if(isset($_SESSION['NAME'])){ echo $_SESSION['NAME']; } 
																elseif(isset($ARRAYSELECTION)){echo $NAME_PROFILE;} ?>" />
											</div>
										</div>
										<?php
										echo '
										<div class="row">
											<div class="col-md-5">
											<div class="col-md">
												<input type="checkbox" id="checkAll"/> '.$full.'
											</div>
											<fieldset>';

											$SQL = "SELECT * FROM tgroup_itemprofile";
											$RSGROUP = pg_query($conn, $SQL);
											$ARRAYGROUP = pg_fetch_array($RSGROUP);
											do{
												echo '
												<div class="row">
													<div class="col-md"><strong>'.${$ARRAYGROUP['name']}.'</strong></div>
												</div>';
												$SQL = "SELECT i.id, i.name AS item_profile FROM titemprofile i WHERE id_group = ".$ARRAYGROUP['id'];
												$RS = pg_query($conn, $SQL);
												$ARRAYPERMISION = pg_fetch_array($RS);
												do{
													if (!empty($ID_ITEM_SELECTED)){
														if(in_array($ARRAYPERMISION['id'],explode("@",$ID_ITEM_PROF_SELECTED))){
															$sel = 'checked="checked"';
														} else {
															$sel = '';
														}
													} else {
														$sel = '';
													}

													echo '
													<div class="col-md">
														<input type="checkbox" name="permition_profile[]" id="permition_profile[]" value="'.$ARRAYPERMISION['id'].'" '.$sel.'>  '.${$ARRAYPERMISION['item_profile']}.'
													</div>';
												}while($ARRAYPERMISION = pg_fetch_array($RS));
											}while($ARRAYGROUP = pg_fetch_array($RSGROUP));
											echo '
											</fieldset>
											</div>
										</div>';
										// End - individual configuration	
										?>
										<div class="panel panel-default">
											<div class="row">
												<div class="col-md-3">
													<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:postForm();"><i class="fa fa-plus-square-o"></i> <?php if(empty($ID_ITEM_SELECTED)){echo "$LANG_INSERT";} else {echo "$LANG_UPDATE";}?></button>
												</div>
												<div class="col-md-3">
													<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:clearForm();"><i class="fa fa-square-o"></i> <?php if(empty($ID_ITEM_SELECTED)){echo "$LANG_CLEAR";} else {echo "$LANG_UNSELECT";}?></button>
												</div>
												<?php if(isset($ARRAYSELECTION)){
												echo '
												<div class="col-md-3">
													<button type="button" class="btn btn-secondary  btn-block" onclick="javascript:deleteItem();">
													<i class="fa fa-minus-square-o"></i> '.$LANG_DELETE.'</button>
												</div>';}?>
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
									<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
										<thead>
											<tr>
												<th><input type="checkbox" name="select_all_itens" id="select_all_itens"></th>
												<th><?php echo $LANG_NAME;?></th>
											</tr>
										</thead>
										<?php
										// Select this page item
										$SQL = "SELECT p.id, p.name AS profile_name FROM tprofile p WHERE id_instance=".$_SESSION['INSTANCE_ID'];
										$RS = pg_query($conn, $SQL);
										$ARRAY = pg_fetch_array($RS);
										if(pg_affected_rows($RS) == 0){?>
											<tr class="odd gradeX">
												<td></td>
												<td><?php echo $LANG_NO_HAVE_DATE;?></td>
											</tr>
										<?php } elseif((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false){
												echo'
												<tr class="odd gradeX">
													<th></th>
													<th><center>'.$LANG_YOU_NOT_HAVE_PERMISSION.'</center></th>
												</tr>';
										} else {
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
												<tr class="odd gradeX">
													<td data-id="<?php echo $ARRAY['id'];?>"><input type="checkbox" name="optcheckitem[]" value="<?php echo $ARRAY['id']; ?>" <?php echo $sel; ?> >
														<a href="<?php echo 'javascript:selectTableItem('.$ARRAY['id'].')';?>"></a></td>
													<td data-id="<?php echo $ARRAY['id'];?>"><?php echo $ARRAY['profile_name'];?></td>
												</tr>
													<?php
											} while($ARRAY = pg_fetch_array($RS));
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
			<?php echo '<script src="'.$_SESSION['LP'].'js/configuration.js"></script>';
			print_end_page_inside($LANG,$ID_ITEM_SELECTED);?>

		</body>

	</html>
<?php 
	destroySession(array('ID_SEL','NAME'));
	$_SESSION['LAST_PAGE'] = $THIS_PAGE;
} ?>