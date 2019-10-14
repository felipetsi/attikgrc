$("#checkAll").change(function () {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
});

function showActHistDetail($item){
	if($item != ""){
		$.ajax({
			url: "actions_history_run.php",
			type: "post",
			data: {checkeditem: $item},
			success: function(data) {
				$("#panel_detail").html(data);
                $("#showDetail").modal();
			},
			error: function(xhr) {
				$("#panel_detail").html(xhr.responseText);
			}
		})
	}
}