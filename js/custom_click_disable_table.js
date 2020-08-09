function deleteItemConfirmed(){
	document.getElementById("mark_deleteitem_view_form").value = 1;
	postViewForm();
}

function duplicateItem($target_item){
	document.getElementById("mark_duplicateitem_view_form").value = 1;
	postViewForm();
}

function disableItem($target_item){
	document.getElementById("mark_disableitem_view_form").value = 1;
	postViewForm();
}

$(document).ready(function() {
	$('#dataTable').DataTable( {
		stateSave: true
	} );
} );