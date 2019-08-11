function showReport($datatype){
	$.ajax({
		url: "reports_data.php",
		type: "post",
		data: {datatype: $datatype},
		success: function(data) {
			$("#boxReport").html(data);
		},
		error: function(xhr) {
			$("#boxReport").html(xhr.responseText);
		}
	})
}

function showReportHistory(){
	$.ajax({
		url: "box_history_report.php",
		type: "post",
		data: {},
		success: function(data) {
			$("#panel_history").html(data);
			$("#historyBox").modal();
		},
		error: function(xhr) {
			$("#boxReport").html(xhr.responseText);
		}
	})
}
function saveReportHistory(){
	$.ajax({
		url: "update_history_report.php",
		type: "post",
		data: $('#history_form').serialize(),
		success: function(data) {
			$("#historyBox").modal('toggle');
		},
		error: function(xhr) {
			$("#boxReport").html(xhr.responseText);
		}
	})
}