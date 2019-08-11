<?php
session_start();
// Start - individual configuration
$_SESSION['LP'] = "../../"; //Level path
$CONF_DIRECTORY_NAME = "attikgrc";
$DESTINATIONPAGE_NO_LOGIN = $_SESSION['LP']."login.php?instance=".$_SESSION['INSTANCE_NAME'];
$THIS_PAGE = "project_access.php";
$_SESSION['THIS_PAGE'] = "project.php";
$DESTINATION_PAGE = "project.php";
// This session variable is used in box_task with 4 character
$_SESSION['PAGE_FROM'] = "project";

// END - individual configuration
if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once($_SESSION['LP'].'include/function_project.php');
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
	// Task permitions
	$PERMITIONS_NAME_6 = "create_task@";
	$PERMITIONS_NAME_7 = "read_own_task@";
	$PERMITIONS_NAME_8 = "read_all_task@";
	$PERMITIONS_NAME_9 = "approver_task@";
	$PERMITIONS_NAME_10 = "treatment_task@";
	
	// Verify if multi-select is enable
	if(isset($_POST['change_multi_sel'])&&($_POST['change_multi_sel'] == 1)){if($_SESSION['STATUS_MULT_SEL'] == 0){$_SESSION['STATUS_MULT_SEL'] = 1;}
																			 else {$_SESSION['STATUS_MULT_SEL'] = 0;}}
	else {$_SESSION['STATUS_MULT_SEL'] = 0;}
	// Verify if multi-select is enable
	
	if(!empty($_POST['relateditem'])){
		$ID_ITEM_RELATED = trim(addslashes($_POST['relateditem']));
		$_SESSION['ID_ITEM_RELATED'] = $ID_ITEM_RELATED;
	} elseif ($_SESSION['LAST_PAGE'] != $THIS_PAGE){
		unset($_SESSION['ID_ITEM_RELATED']);
	} elseif(!empty($_SESSION['ID_ITEM_RELATED'])) {
		$ID_ITEM_RELATED = $_SESSION['ID_ITEM_RELATED'];
	}
	
	if(!empty($_POST['id_project'])){
		$ID_ITEM_SELECTED = trim(addslashes($_POST['id_project']));
		$_SESSION['ID_PROJECT'] = $ID_ITEM_SELECTED;
	} else {
		$ID_ITEM_SELECTED = $_SESSION['ID_PROJECT'];
	}
	
	if(!empty($ID_ITEM_SELECTED)){
		// Start - individual configuration
		$SQL = "SELECT id, name, id_sponsor, id_manager, id_best_pratices, status, ";
		$SQL .= "TO_CHAR(deadline,'".$LANG_DATE_FORMAT_UPPERCASE."') AS deadline ";
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

		// End - individual configuration 
		?>
		<body class="fixed-nav sticky-footer bg-dark" id="page-top">
			<!-- Navigation -->
			<?php
			require_once($_SESSION['LP'].'include/full_menu.php');
			echo '
			<div id="box_submenu2" role="menu">
				<ul class="submenu2 dropdown-menu">
					<li><a id="e2" href="#"><i class="fa fa-edit"></i>'.$LANG_EDIT.'</a></li>
					<li><a id="i2" href="#"><i class="fa fa-plus-square-o"></i>'.$LANG_INSERT.'</a></li>
					<li><a id="d2" href="#"><i class="fa fa-minus-square-o"></i>'.$LANG_DELETE.'</a></li>
					<li><a id="u2" href="#"><i class="fa fa-clone"></i>'.$LANG_DUPLICATE.'</a></li>
				</ul>
			</div>';
			?>
			<!-- End - individual configuration - End submenu -->
				
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
									<div class="panel panel-default">	
										<?php if((strpos($_SESSION['user_permission'],$PERMITIONS_NAME_1)) === false &&
												 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_2)) === false &&
												 (strpos($_SESSION['user_permission'],$PERMITIONS_NAME_8)) === false){
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
												$CRET_CONT_INP = "readonly";
												$CRET_CONT_SEL = "disabled";
											}?>
												<?php // Start - individual configuration	?>

												<div class="table_less_row_header">
													<div class="col-md-2">
														<!-- <a data-toggle="collapse" data-parent="#section" href="#section'.$ARRAYSECTION['id'].'"> 
															<i id="btn_collapse_panel_icon_down" class="fa fa-minus-square-o"></i> 
														</a> 
														<a href="javascript:void(0)" class="toggle-accordion active" accordion-id="#accordion">
															<i id="btn_collapse_panel_icon_down" class="fa fa-plus-square"></i> 
														</a> -->
														<?php echo $LANG_SECTION;?>:
													</div>
													<div class="col-md-3 ">
														<?php echo $LANG_CATEGORY;?>:
													</div>
													<div class="col-md-3">
														<?php echo $LANG_CONTROL;?>:
													</div>
													<div class="col-md-4">
														<?php echo $LANG_ACTION_PLAN;?>:
													</div>
												</div>
												<?php
												$SQL = "SELECT id,name FROM tbest_pratice WHERE id_instance = ".$_SESSION['INSTANCE_ID']." ";
												$SQL .= "AND status = 'a' AND id = ".$ARRAYSELECTION['id_best_pratices'];
												$RSBESTPRATICES = pg_query($conn, $SQL);
												
												if(pg_affected_rows($RSBESTPRATICES) == 0){
													print_r($LANG_MSG_PROJECT_BP_DISABLE);
												} else {
													$ARRAYBESTPRATICES = pg_fetch_array($RSBESTPRATICES);
												
													do {
														$SQL = "SELECT id,CAST (replace(replace(substring(replace(item,'.',''),1,6),'-','1'),'A','') AS integer) as ord_item, item, name ";
														$SQL .= "FROM tsection_best_pratice WHERE id_best_pratice = ".$ARRAYBESTPRATICES['id'];
														$SQL .= "ORDER BY ord_item"; 
														$RSSECTION = pg_query($conn, $SQL);
														$ARRAYSECTION = pg_fetch_array($RSSECTION);
														do {
															echo '
															<div class="table_less_row_edge">
																<div class="col-md-2">
																	<div class="table_less_row">
																		<a data-toggle="collapse" data-parent="#accordion" href="#accordion'.$ARRAYSECTION['id'].'"> 
																			<i id="btn_collapse_panel_icon_down" class="fa fa-minus-square-o"></i> 
																		</a>
																	'.$ARRAYSECTION['item'].' - '.$ARRAYSECTION['name'].'
																	</div>
																</div>
																<div id="accordion'.$ARRAYSECTION['id'].'" class="col-md-10 panel-collapse">';// Category

																	$SQL = "SELECT id,CAST(REPLACE(SUBSTRING(item FROM 2 FOR 6),'.','') as integer) as ord_item, 
																			item, name ";
																	$SQL .= "FROM tcategory_best_pratice WHERE id_section = ".$ARRAYSECTION['id'];
																	$SQL .= "ORDER BY ord_item"; 
																	$RSCATEGORY = pg_query($conn, $SQL);
																	$ARRAYCATEGORY = pg_fetch_array($RSCATEGORY);
																	do{
																		echo '
																	<div class="table_less_row_middle">
																		<div class="col-md-3 table_less_collunm_middle">
																			'.$ARRAYCATEGORY['item'].' - '.$ARRAYCATEGORY['name'].'
																		</div>
																		<div class="col-md table_less_collunm_middle">';

																		$SQL = "SELECT id,CAST (replace(replace(substring(replace(item,'.',''),1,6),'-','1'),'A','') AS integer) as ord_item, item, ";
																		$SQL .= "name FROM tcontrol_best_pratice WHERE id_category = ".$ARRAYCATEGORY['id'];
																		$SQL .= "ORDER BY ord_item"; 
																		$RSCONTROL = pg_query($conn, $SQL);
																		$ARRAYCONTROL = pg_fetch_array($RSCONTROL);
																		do{
																			echo '
																			
																			<div class="table_less_row_right">
																				<div class="col-md-6 table_less_collunm_middle">
																					'.$ARRAYCONTROL['item'].' - '.$ARRAYCONTROL['name'].'
																				</div>
																				<div class="col-md table_less_collunm_end">';
																					echo '
																					<div id="listRelatedTask'.$ARRAYCONTROL['id'].'">';
																					showListTaskProjet($ID_ITEM_SELECTED,'proj',$ARRAYCONTROL['id']);
																					echo '
																					</div>
																				</div>
																			</div>';
																		}while($ARRAYCONTROL = pg_fetch_array($RSCONTROL));
																		echo '
																		</div>
																	</div>';																	
																	}while($ARRAYCATEGORY = pg_fetch_array($RSCATEGORY));
																echo '
																</div> <!-- Category -->
															</div>';
														}while($ARRAYSECTION = pg_fetch_array($RSSECTION));

													}while($ARRAYBESTPRATICES = pg_fetch_array($RSBESTPRATICES));
												}
												// End - individual configuration
												?>
										<?php }?>
									</div>
								</div>
							</div>
						</div>
					<!-- Item list -->

					<?php printDeleteBox($LANG);?>
					<div class="card-footer small text-muted"><?php echo ($LANG_SYSTEM_VERSION.": ".$CONF_VERSION);?></div>
				</div>	
			</div>
			
			<div class="modal fade" id="taskBoxRelated" tabindex="-1" role="dialog" aria-labelledby="task_boxLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content" id="panel_task">
					</div>
				</div>
			</div>
			
			<!-- /.container-fluid -->
			<?php print_end_page_inside($LANG,$ID_ITEM_SELECTED);
			printDeleteTaskBox($LANG);?>
		</body>
	</html>
<?php 
	unset($ID_ITEM_SELECTED);
	unset($_SESSION['NAME']);
	unset($_SESSION['DETAIL']);
	unset($_SESSION['ACTION']);
	unset($_SESSION['RESPONSIBLE']);
	unset($_SESSION['PREVISION']);
	unset($_SESSION['CONNECTED_ITEM']);
	unset($_SESSION['CRET_CONT_INP']);
	unset($_SESSION['CRET_CONT_SEL']);
	unset($_SESSION['ID_ITEM_FROM_TASK']);
	$_SESSION['LAST_PAGE'] = ("/$CONF_DIRECTORY_NAME/module/improvement/$THIS_PAGE");
}?>