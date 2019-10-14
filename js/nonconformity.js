function showListProcessRelationship($id_source,$source){
	if(($id_source > 0) && ($source != "")){
		$.ajax({
			url: pathVerify("impr")+"box_process_related.php",
			type: "post",
			data: {id_source: $id_source, source: $source},
			success: function(data) {
				$("#listProcessBox").html(data);
			},
			error: function(xhr) {
				$("#listProcessBox").html(xhr.responseText);
			}
		})
	}
}

function addProcessRelated($id_source,$source){
	if(($id_source != "") && ($source != "")){
		$.ajax({
			url: pathVerify("impr")+"process_load.php",
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

function showProcessRelatedAj($id_process,$id_source,$source){
	if(($id_source != "") && ($source != "")){
		$.ajax({
			url: pathVerify("impr")+"box_process.php",
			type: "post",
			data: {id_process: $id_process, id_source: $id_source, source: $source},
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

function associanteProcessObjRelated($id_process,$id_source,$source,$ass_dis){
	if(($id_process != "") && ($id_source != "") && ($source != "")){
		$.ajax({
			url: pathVerify("impr")+"associateProcessObj.php",
			type: "post",
			data: {id_process: $id_process, id_source: $id_source, source: $source, ass_dis: $ass_dis},
			success: function() {
				showListProcessRelationship($id_source,$source);
				$('.showFrontModalRelated').remove()  
				$('#showFrontModalRelated').modal('hide');
				$('#modalRelated').modal('hide');
			}
		})
	}
}
