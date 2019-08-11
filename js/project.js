function showListTaskContProj($id_source,$source,$resp_type){
		$.ajax({
			url: "box_task_related.php",
			type: "post",
			data: {id_source: $id_source, source: $source, response_type: $resp_type},
			success: function(data) {
				$("#listRelatedTask"+$resp_type).html(data);
			},
			error: function(xhr) {
				$("#listRelatedTaskBox").html(xhr.responseText);
			}
		})
}