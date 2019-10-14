setTimeout(function(){
    $(".alert").each(function(index){
        $(this).delay(200*index).fadeTo(1500,0).slideUp(500,function(){
            $(this).remove();
        });
    });
},4000);

// Start disable right-click
function disableAllRigthClick(e)
{
	if (window.Event)
	{
		if (e.which == 2 || e.which == 3)
		return false;
	}
	else
	{
		event.cancelBubble = true
		event.returnValue = false;
		return false;
	}
}

if ( window.Event )
document.captureEvents(Event.MOUSEUP);
if ( document.layers )
document.captureEvents(Event.MOUSEDOWN);
document.oncontextmenu = disableAllRigthClick;
// End disable right-click

window.onload = function() {
	show_loading();
};

$(window).scroll(function () {
	closeAllJustify();
});

$(function() {
	$('#section1').click(function(){
		$(this).find('i').toggleClass('fa-plus-square-o fa-plus-square-o');
	});
});

function postViewForm(){
	document.getElementById("view_form").submit();
}

function postViewFormAux(){
	document.getElementById("view_form_aux").submit();
}

function postForm(){
	document.getElementById("main_form").submit();
}

function clearForm(){
	document.getElementById("checkeditem").value = "";
	postViewFormAux();
}

function selectTableItem($item){
	document.getElementById("checkeditem").value = $item;
	postViewForm();
}

function selectTableRelatedItem($item){
	document.getElementById("relateditem").value = $item;
	document.getElementById("submit_to_net").submit();
}

function pathVerify($target){
	pathname = window.location.pathname;
	dirPath = pathname.split('/');
	var ret_val = "./";
	if($target == "impr"){
		if (dirPath[1] != "improvement"){
			ret_val = "../improvement/";
		}
	} else if($target == "risk"){
		if (dirPath[1] != "risk"){
			ret_val = "../risk/";
		}
	} else if($target == "root"){
		if (dirPath[1] != "/"){
			ret_val = "../../";
		}
	}
	return ret_val;
}

function backToRelatedObj($item,$dest){
	$.extend(
	{
		redirectPost: function (location, args) {
			var form = $('<form>', { action: location, method: 'post' });
			$.each(args,
				function (key, value) {
					$(form).append(
						$('<input>', { type: 'hidden', name: key, value: value })
					);
				});
			$(form).appendTo('body').submit();
		}
	});
	if($dest == 'area'){
		var $dest = 'area.php';
	} else if($dest == 'proc'){
		var $dest = 'process.php';
	}
	$.redirectPost($dest, {itemBackRelated: $item});
}

function deleteItem($target_item){
	if($target_item != undefined){
		document.getElementById("id_item_selected").value = $target_item;
	}
	$("#deleteBox").modal();
}

function deleteItemCancel(){
	document.getElementById("id_item_selected").value = "";
}

function changeMultiSelect(){
	document.getElementById("change_multi_sel").value = 1;
	postViewFormAux();
}

function show_loading(){
	document.getElementById("loading_box").style.display = "none";
}

function finishItemControl(){
	document.getElementById("mark_finishitem").value = 1;
	document.getElementById("main_form").submit();
}

function openTaskFromMenu($target_item){
	if($target_item != undefined){
		document.getElementById("checkeditemtask").value = $target_item;
		document.getElementById("formExclusiveTask").submit();
	}
}

function accessProjet($item){
	document.getElementById("id_project").value = $item;
	document.getElementById("project_form").submit();
}

function showAssets($item){
	document.getElementById("id_process").value = $item;
	document.getElementById("asset_form").submit();
}

function accessBack($page){
	window.location=$page;
}

/*function showTaskRelated($item,$idControl){
	document.getElementById("mark_task_related").value = $item;
	document.getElementById("mark_control_related").value = $idControl;
	document.getElementById("show_task_related").value = 1;
	
	document.getElementById("form_show_task").submit();
}*/

function openToInsertTask($idControl){
	document.getElementById("form_show_task").reset();
	document.getElementById("mark_control_related").value = $idControl;
	document.getElementById("show_task_related").value = 1;
	
	document.getElementById("form_show_task").submit();
}

/*
function deletetTaskCancel($name_insert){
	document.getElementById("form_show_task").reset();
	document.getElementById("id_task_selected").value = "";
	document.getElementById("btn_task_delete").style.visibility = "hidden";
	if($name_insert != "") {
		document.getElementById("btn_task_insert").innerText = $name_insert;
	}
}*/

function resetFormTask($name_insert){
	document.getElementById("task_form").reset();
}

function finishTaskControl($id_source,$source,$resp_type){
	document.getElementById("mark_finishitem").value = 1;
	submitTaskRelated($id_source,$source,$resp_type);
}

function showJustifyItem($target){
	if($target.style.visibility != "visible"){
		var $show = "visible";
	} else {
		var $show = "hidden";
	}
	document.getElementById("justify_impact1").style.visibility = "hidden";
	document.getElementById("justify_impact2").style.visibility = "hidden";
	document.getElementById("justify_impact3").style.visibility = "hidden";
	document.getElementById("justify_impact4").style.visibility = "hidden";
	document.getElementById("justify_probability").style.visibility = "hidden";
	$($target).insertAfter("#show_justify_box");
	$target.style.visibility = $show;
}

function closeJustifyItem($target){
	$target.style.visibility = "hidden";
}

function showJustifyMit($target){
	if($target.style.visibility != "visible"){
		var $show = "visible";
	}
	document.getElementById("justify_mitigate1").style.visibility = "hidden";
	document.getElementById("justify_mitigate2").style.visibility = "hidden";
	document.getElementById("justify_mitigate3").style.visibility = "hidden";
	document.getElementById("justify_mitigate4").style.visibility = "hidden";
	document.getElementById("justify_prob_mit").style.visibility = "hidden";
	$($target).insertAfter("#justify_mitigation");
	$target.style.visibility = $show;
}

function closeJustifyMit($target){
	$target.style.visibility = "hidden";
	$("#justify_mitigation").html("");
}

function showAnyItemHiddenBox($target){
	if($target.style.visibility == "visible"){
		$target.style.visibility = "hidden";
	} else {
		$target.style.visibility = "visible";
	}
}

function showTaskRelatedAj($id_task,$id_source,$source,$resp_type){
	if(($id_source != "") && ($source != "")){
		$.ajax({
			url: pathVerify("impr")+"box_task.php",
			type: "post",
			data: {id_task: $id_task, id_source: $id_source, source: $source, response_type: $resp_type},
			success: function(data) {
				$("#panel_task").html(data);
				$('#taskBoxRelated').modal();
			},
			error: function(xhr) {
				$("#panel_task").html(xhr.responseText);
			}
		})
	}
}

function showListTaskRelated($id_source,$source,$resp_type){
	if(($id_source > 0) && ($source != "")){
		$.ajax({
			url: pathVerify("impr")+"box_task_related.php",
			type: "post",
			data: {id_source: $id_source, source: $source, response_type: $resp_type},
			success: function(data) {
				if($resp_type == 's'){
					$("#listRelatedTaskBox2").html(data);
				} else if($source == 'proj') {
					$("#listRelatedTask"+$resp_type).html(data);
				} else {
					$("#listRelatedTaskBox").html(data);
				}
			},
			error: function(xhr) {
				$("#listRelatedTaskBox").html(xhr.responseText);
			}
		})
	}
}

function submitTaskRelated($id_source,$source,$resp_type){
	if (($('#responsible').val() != "") && ($('#name').val() != "")){
		$.ajax({
			url: pathVerify("impr")+"task_run.php",
			type: "post",
			data: $('#task_form').serialize(),
			success: function() {
				$('#taskBoxRelated').modal('hide');
				showListTaskRelated($id_source,$source,$resp_type);
				showMSGTop();
			},
			error: function(xhr) {
				$('#taskBoxRelated').modal('hide');
				$('#panel_task').html(xhr.responseText);
				showListTaskRelated($id_source,$source,$resp_type);
				showMSGTop();
			}
		})
	} else {
		if ($('#responsible').val() == ""){
			$('#la_responsible').css("color", "#DC143C"); //Crimson
		} else{
			$('#la_responsible').css("color", "#000000"); //black
		} if ($('#name').val() == ""){
			$('#la_name').css("color", "#DC143C"); //Crimson
		} else{
			$('#la_name').css("color", "#000000"); //black
		}
	}
}

function addTaskRelated($id_source,$source,$resp_type){
	showTaskRelatedAj(0,$id_source,$source,$resp_type);
}

function deletetTask($target_item,$id_source,$source,$resp_type){
	if($target_item != undefined){
		$GLOBAL_id_task_selected = $target_item;
		$GLOBAL_id_source = $id_source;
		$GLOBAL_source = $source;
		$GLOBAL_resp_type = $resp_type;
		$('#deleteBoxTask').modal();
	}
}

function deleteConfirmedTask(){
	if(($GLOBAL_id_source > 0) && ($GLOBAL_id_source,$GLOBAL_id_source != "")){
		$.ajax({
			url: pathVerify("impr")+"task_run.php",
			type: "post",
			data: {mark_deletetask:1, id_task_selected:$GLOBAL_id_task_selected},
			success: function() {
				showListTaskRelated($GLOBAL_id_source,$GLOBAL_source,$GLOBAL_resp_type);
				$('#deleteBoxTask').modal('toggle');
				$GLOBAL_id_task_selected = "";
				$GLOBAL_id_source = "";
				$GLOBAL_source = "";
				$GLOBAL_resp_type = "";
				showMSGTop();
			},
			error: function(xhr) {
				$('#panel_task').html(xhr.responseText);
				showMSGTop();
			}
		})
	}
}

function duplicateTask($target_item,$id_source,$source,$resp_type){
	if ($source == 'risk'){
		$.ajax({
			url: pathVerify("impr")+"task_run.php",
			type: "post",
			data: {mark_duplicatetask: 1, id_task_selected: $target_item, id_risk_selected:$id_source},
			success: function() {
				showListTaskRelated($id_source,$source);
				showMSGTop();
			},
			error: function(xhr) {
				$('#panel_task').html(xhr.responseText);
				showMSGTop();
			}
		})
	} else if ($source == 'cont'){
		$.ajax({
			url: pathVerify("impr")+"task_run.php",
			type: "post",
			data: {mark_duplicatetask: 1, id_task_selected: $target_item, id_control_mech_selected:$id_source},
			success: function() {
				showListTaskRelated($id_source,$source);
				showMSGTop();
			},
			error: function(xhr) {
				$('#panel_task').html(xhr.responseText);
				showMSGTop();
			}
		})
	} else if ($source == 'inci'){
		$.ajax({
			url: "task_run.php",
			type: "post",
			data: {mark_duplicatetask: 1, id_task_selected: $target_item, id_incident_selected:$id_source, response:$resp_type},
			success: function() {
				showListTaskRelated($id_source,$source,$resp_type);
				showMSGTop();
			},
			error: function(xhr) {
				$('#panel_task').html(xhr.responseText);
				showMSGTop();
			}
		})
	} else if ($source == 'nonc'){
		$.ajax({
			url: "task_run.php",
			type: "post",
			data: {mark_duplicatetask: 1, id_task_selected: $target_item, id_nonconformity_selected:$id_source, response:$resp_type},
			success: function() {
				showListTaskRelated($id_source,$source,$resp_type);
				showMSGTop();
			},
			error: function(xhr) {
				$('#panel_task').html(xhr.responseText);
				showMSGTop();
			}
		})
	} else if ($source == 'proj'){
		$.ajax({
			url: "task_run.php",
			type: "post",
			data: {mark_duplicatetask: 1, id_task_selected: $target_item, id_project_selected:$id_source, id_control_selected:$resp_type},
			success: function() {
				showListTaskRelated($id_source,$source,$resp_type);
				showMSGTop();
			},
			error: function(xhr) {
				$('#panel_task').html(xhr.responseText);
				showMSGTop();
			}
		})
	}
}

function clear_responsibles(){
	document.getElementById("control_responsible").value = "";
	document.getElementById("control_risk_responsible").value = "";
}

function showConfRevision(){
	document.getElementById("box_conf_revision").style.visibility = "visible";
}

function showloadItem(){
	if(document.getElementById("loadItem").style.visibility == "visible"){
		document.getElementById("loadItem").style.visibility = "hidden";
	} else {
		document.getElementById("loadItem").style.visibility = "visible";
	}
}

function showloadItem2(){
	if(document.getElementById("loadItem2").style.visibility == "visible"){
		document.getElementById("loadItem2").style.visibility = "hidden";
	} else {
		document.getElementById("loadItem2").style.visibility = "visible";
	}
}

// function to show mitigation of risk
function mitigateRisk($item){
	if( !$('#mitigate_box').is(':visible') ) {
		$('#mitigate_box').modal();
	}
	$GLOBAL_id_risk_sel_mit = $item;
	
	$.ajax({
		url: "mitigation_action.php",
		type: "post",
		data: {id_selected: $item},
		success: function(data) {
			$("#showListMitControl").html(data);
		},
		error: function(xhr) {
			$("#showListMitControl").html(xhr.responseText);
		}
	})
}

function controlLoad(){
	var $idRisk = $GLOBAL_id_risk_sel_mit;
	$('#addMitControl').modal();
	
	$.ajax({
		url: "control_load.php",
		type: "post",
		data: {id_selected: $idRisk},
		success: function(data) {
			$("#show_controls").html(data);
		},
		error: function(xhr) {
			$("#show_controls").html(xhr.responseText);
		}
	})
}

function dissociateContRisk(){
	var $idControl = $("input:radio[name=listAssContRadio]:checked").val();
	var $idRisk = $GLOBAL_id_risk_sel_mit;
	
	if(($idControl != "") && ($idRisk != "")){
		$.ajax({
			url: "associateControlRisk.php",
			type: "post",
			data: {idRisk: $idRisk, idControl: $idControl, ass_dis: "d"},
			success: function(data) {
				mitigateRisk($idRisk);
			},
			error: function(xhr) {
				$("#show_controls").html(xhr.responseText);
			}
		})
	}
}

function associateContRisk(){
	var $idControl = $("input:radio[name=controlCkdRadio]:checked").val();
	var $idRisk = $GLOBAL_id_risk_sel_mit;
	
	if(($idControl != "") && ($idRisk != "")){
		$.ajax({
			url: "associateControlRisk.php",
			type: "post",
			data: {idRisk: $idRisk, idControl: $idControl, ass_dis: "a"},
			success: function(data) {
				mitigateRisk($idRisk);
				$('#addMitControl').modal('toggle');
			},
			error: function(xhr) {
				$("#show_controls").html(xhr.responseText);
			}
		})
	}
}

function showMitigationDetail($idControl){
	//var $idControl = $("input:radio[name=controlCkdRadio]:checked").val();
	var $idRisk = $GLOBAL_id_risk_sel_mit;
	
	if(($idControl != "") && ($idRisk != "")){
		$.ajax({
			url: "detail_mitigation.php",
			type: "post",
			data: {idRisk: $idRisk, idControl: $idControl},
			success: function(data) {
				$("#panel_modal_detail").html(data);
				$('#panel_modal_detail').collapse("show");
			},
			error: function(xhr) {
				$("#panel_modal_detail").html(xhr.responseText);
			}
		})
	}
}

function updateMitigationDetail($idControl,$idRisk){
	
	if(($idControl != "") && ($idRisk != "")){
		$.ajax({
			url: "updateDetailMitigation.php",
			type: "post",
			data: $('#formDetailMitigation').serialize(),
			success: function() {
				$('#panel_modal_detail').collapse("hide");
				mitigateRisk($idRisk);
				showListRisk();
				//$("#panel_modal_detail").html(data);
			},
			error: function(xhr) {
				$("#panel_modal_detail").html(xhr.responseText);
			}
		})
	}
}

function confirmRiskTreatment($idRisk,$treatment){
	$('#confirmTreatBox').modal();
	$GLOBAL_idRisk = $idRisk;
	$GLOBAL_treatment = $treatment;
}

function riskTreatment(){
	var $idRisk = $GLOBAL_idRisk;
	$.ajax({
		url: "risk_treat_dif_mit.php",
		type: "post",
		data: {idRisk:$idRisk, treatment:$GLOBAL_treatment},
		success: function() {
			$('#show_risk_treatment').collapse("hide");
			$('#confirmTreatBox').modal("toggle");
			addTaskRelated($idRisk,'risk');
		}
	})
	$GLOBAL_idRisk = "";
	$GLOBAL_treatment = "";
}

function showListEfficacyRev($id_cont,$showAll){
	if($id_cont > 0){
		$.ajax({
			url: "box_revision_related.php",
			type: "post",
			data: {id_cont: $id_cont,show_all: $showAll},
			success: function(data) {
				$("#listEfficacyRevision").html(data);
			},
			error: function(xhr) {
				$("#listEfficacyRevision").html(xhr.responseText);
			}
		})
	}
}

function addRevisionRelated($id_cont){
	showRevisionRelatedAj(0,$id_cont);
}

function showRevisionRelatedAj($prevision,$id_cont){
	if($id_cont != ""){
		$.ajax({
			url: "box_revision.php",
			type: "post",
			data: {prevision: $prevision, id_cont: $id_cont},
			success: function(data) {
				$("#panel_task").html(data);
				$('#taskBoxRelated').modal();
			},
			error: function(xhr) {
				$("#panel_task").html(xhr.responseText);
			}
		})
	}
}

function showPersonalSettings(){
	$.ajax({
		url: "box_personal_setting.php",
		type: "post",
		success: function(data) {
			$("#panel_personal_settings").html(data);
			$('#personal_setting_box').modal();
		},
		error: function(xhr) {
			$("#panel_personal_settings").html(xhr.responseText);
		}
	})
}

function updatePersonalSettings(){
	$.ajax({
		url: "box_personal_setting_run.php",
		type: "post",
		data: $('#personal_setting_form').serialize(),
		success: function() {
			$('#personal_setting_box').modal('toggle');
		},
		error: function(xhr) {
			$('#panel_personal_settings').html(xhr.responseText);
		}
	})
}

function showDataDashboard($datatype){
	$.ajax({
		url: "dashboard_info.php",
		type: "post",
		data: {datatype: $datatype},
		success: function(data) {
			$("#"+$datatype).html(data);
		},
		error: function(xhr) {
			$("#"+$datatype).html(xhr.responseText);
		}
	})
}

function showListRisksRelationship($id_source,$source){
	if(($id_source > 0) && ($source != "")){
		$.ajax({
			url: "box_risk_related.php",
			type: "post",
			data: {id_source: $id_source, source: $source},
			success: function(data) {
				$("#listRelationshipRiskBox").html(data);
			},
			error: function(xhr) {
				$("#listRelationshipRiskBox").html(xhr.responseText);
			}
		})
	}
}

function showRiskRelatedAj($id_risk,$id_source,$source){
	if(($id_source != "") && ($source != "")){
		$.ajax({
			url: "box_risk.php",
			type: "post",
			data: {id_risk: $id_risk, id_source: $id_source, source: $source},
			success: function(data) {
				$("#front_modal_panel").html(data);
				$('#showFrontModalRelated').modal();
			},
			error: function(xhr) {
				$("#front_modal_panel").html(xhr.responseText);
			}
		})
	}
}

function addRiskRelated($id_source,$source){
	if(($id_source != "") && ($source != "")){
		$.ajax({
			url: "risk_load.php",
			type: "post",
			data: {id_source: $id_source, source: $source},
			success: function(data) {
				$("#panel_related").html(data);
				$('#modalRelated').modal();
			},
			error: function(xhr) {
				$("#panel_related").html(xhr.responseText);
			}
		})
	}
}

function associanteRiskObjRelated($id_risk,$id_source,$source,$ass_dis){
	if(($id_risk != "") && ($id_source != "") && ($source != "")){
		$.ajax({
			url: "associateRiskObj.php",
			type: "post",
			data: {id_risk: $id_risk, id_source: $id_source, source: $source, ass_dis: $ass_dis},
			success: function() {
				showListRisksRelationship($id_source,$source);
				$('.showFrontModalRelated').remove()  
				$('#showFrontModalRelated').modal('hide');
				$('#modalRelated').modal('hide');
			}
		})
	}
}

function showListControlRelationship($id_source,$source){
	if(($id_source > 0) && ($source != "")){
		$.ajax({
			url: "box_control_related.php",
			type: "post",
			data: {id_source: $id_source, source: $source},
			success: function(data) {
				$("#listRelationshipControlBox").html(data);
			},
			error: function(xhr) {
				$("#listRelationshipControlBox").html(xhr.responseText);
			}
		})
	}
}

function addControlRelated($id_source,$source){
	if(($id_source != "") && ($source != "")){
		$.ajax({
			url: "control_load.php",
			type: "post",
			data: {id_source: $id_source, source: $source},
			success: function(data) {
				$("#panel_related").html(data);
				$('#modalRelated').modal();
			},
			error: function(xhr) {
				$("#modalRelated").html(xhr.responseText);
			}
		})
	}
}

function showControlRelatedAj($id_control,$id_source,$source){
	if(($id_source != "") && ($source != "")){
		$.ajax({
			url: "box_control.php",
			type: "post",
			data: {id_control: $id_control, id_source: $id_source, source: $source},
			success: function(data) {
				$("#front_modal_panel").html(data);
				$('#showFrontModalRelated').modal();
			},
			error: function(xhr) {
				$("#front_modal_panel").html(xhr.responseText);
			}
		})
	}
}

function associanteControlObjRelated($id_control,$id_source,$source,$ass_dis){
	if(($id_control != "") && ($id_source != "") && ($source != "")){
		$.ajax({
			url: "associateControlObj.php",
			type: "post",
			data: {id_control: $id_control, id_source: $id_source, source: $source, ass_dis: $ass_dis},
			success: function() {
				showListControlRelationship($id_source,$source);
				$('.showFrontModalRelated').remove()  
				$('#showFrontModalRelated').modal('hide');
				$('#modalRelated').modal('hide');
			}
		})
	}
}

function openEspecificControl(keycode,$id_source,$source)
{
	if (keycode == 13)
	{
		var $id_control = $("#especificControlNo").val();
		showControlRelatedAj($id_control,$id_source,$source);
	}
}

function openEspecificRisk(keycode,$id_source,$source)
{
	if (keycode == 13)
	{
		var $id_risk = $("#especificRiskNo").val();
		showRiskRelatedAj($id_risk,$id_source,$source);
	}
}

<!-- Key <enter> press -->
function submitenter(keycode)
{
	if (keycode == 13)
	{
		postForm();
	}
}

function refreshScreen($screen){
	if($screen != ""){
		$.ajax({
			url: "refresh_screen.php",
			type: "post",
			data: {screen: $screen},
			success: function(data) {
				location.reload();
			}
		})
	}
}

function showFileRelationship($id_source,$source){
	if(($id_source > 0) && ($source != "")){
		$.ajax({
			url: "box_files_related.php",
			type: "post",
			data: {id_source: $id_source, source: $source},
			success: function(data) {
				$("#fileZone").html(data);
			},
			error: function(xhr) {
				$("#fileZone").html(xhr.responseText);
			}
		})
	}
}

function showMSGTop(){
	$.ajax({
		url: pathVerify("root")+"box_msg_top.php",
		type: "post",
		success: function(data) {
			$("#msg_box").html(data);
			setTimeout(function(){
				$(".alert").each(function(index){
					$(this).delay(200*index).fadeTo(1500,0).slideUp(500,function(){
						$(this).remove();
					});
				});
			},4000);
		},
		error: function(xhr) {
			$("#msg_box").html(xhr.responseText);
			setTimeout(function(){
				$(".alert").each(function(index){
					$(this).delay(200*index).fadeTo(1500,0).slideUp(500,function(){
						$(this).remove();
					});
				});
			},4000);
		}
	})
}