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

function showImpactRelatedAj($cret_cont_sel){
	var $id_type = $("#impact_type").val();
	var $id_risk = $("#id_item_selected").val();
	if($id_type != ""){
		$.ajax({
			url: pathVerify("risk")+"box_show_impact.php",
			type: "post",
			data: {id_risk: $id_risk,id_impact_type: $id_type, cret_cont_sel: $cret_cont_sel},
			success: function(data) {
				$("#impact_related").html(data);
			},
			error: function(xhr) {
				$("#impact_related").html(xhr.responseText);
			}
		})
	}
}

function showListRisk(){
	$.ajax({
		url: pathVerify("risk")+"risk_list.php",
		type: "post",
		data: {},
		success: function(data) {
			$("#show_risk_list").html(data);
		},
		error: function(xhr) {
			$("#show_risk_list").html(xhr.responseText);
		}
	})
}

function closeAllJustify(){
	document.getElementById("justify_impact1").style.visibility = "hidden";
	document.getElementById("justify_impact2").style.visibility = "hidden";
	document.getElementById("justify_impact3").style.visibility = "hidden";
	document.getElementById("justify_impact4").style.visibility = "hidden";
	document.getElementById("justify_probability").style.visibility = "hidden";
}

