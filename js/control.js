function showListComplianceRelated($id_source){
	if($id_source != ""){
		$.ajax({
			url: "box_compliance_related.php",
			type: "post",
			data: {id_source: $id_source},
			success: function(data) {
				$("#listRelatedComplianceBox").html(data);
			},
			error: function(xhr) {
				$("#listRelatedComplianceBox").html(xhr.responseText);
			}
		})
	}
}

function addComplianceRelated($id_source){
	if($id_source != ""){
		$.ajax({
			url: "compliance_load.php",
			type: "post",
			data: {id_source: $id_source},
			success: function(data) {
				$("#panel_related").html(data);
				$('#bp_box').modal();
			},
			error: function(xhr) {
				$("#panel_related").html(xhr.responseText);
			}
		})
	}
}

// End of page
$("#best_pratices").on('change', function() {
	if ($("#best_pratices").val() !== ""){
		$.ajax({
			url: "bp_load.php",
			type: "post",
			data: {bp: $("#best_pratices").val(), id_selected: $("#id_item_selected").val()},
			beforeSend: function(){
				showloadItem();
			},
			success: function(data) {
				showloadItem();
				$("#show_item_bp").html(data);
			}
		})
	} else {
		$("#show_item_bp").html("");
	}
});

function selectedBP($id_source){
	var $bpchecked = "";
	$("input:checkbox[name=bpchecked]:checked").each(function () {
		$bpchecked += this.value+"@";
	});
	var $id_bp = $("#bp").val();
	
	if ($id_source != ""){
		$.ajax({
			url: "associateControlItemBP.php",
			type: "post",
			data: {id_source: $id_source, bpchecked: $bpchecked, id_bp: $id_bp},
			success: function(data) {
				$("#show_item_bp").html(data);
				showListComplianceRelated($id_source);
				$('#bp_box').modal('toggle');
			},
			error: function(xhr) {
				$("#show_item_bp").html(xhr.responseText);
				//$("#panel_related").html(xhr.responseText);
			}
		})
	}
}

function resetFormRevision(){
	document.getElementById("efficacy_revision_form").reset();
}

function submitRevisionRelated($id_cont){
	$.ajax({
		url: "revision_run.php",
		type: "post",
		data: $('#efficacy_revision_form').serialize(),
		success: function() {
			showListEfficacyRev($id_cont,'n');
			$('#taskBoxRelated').modal('toggle');
		},
		error: function(xhr) {
			$('#panel_task').html(xhr.responseText);
		}
	})
}

function deleteConfirmedRevision(){
	if(($GLOBAL_id_cont > 0) && ($GLOBAL_prevision != "")){
		$.ajax({
			url: "revision_run.php",
			type: "post",
			data: {mark_deleterevision:1, id_control:$GLOBAL_id_cont, prevision_date:$GLOBAL_prevision},
			success: function() {
				showListEfficacyRev($GLOBAL_id_cont,'n');
				$('#deleteBoxRevision').modal('toggle');
				$GLOBAL_prevision = "";
				$GLOBAL_id_cont = "";
			},
			error: function(xhr) {
				$('#panel_task').html(xhr.responseText);
			}
		})
	}
}