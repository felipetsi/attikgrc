// Functions of action of mainly submenu.
function deleteItemConfirmed(){
	document.getElementById("mark_deleteitem").value = 1;
	postForm();
}

function duplicateItem($target_item){
	if($target_item != undefined){
		document.getElementById("mark_duplicateitem").value = 1;
		document.getElementById("id_item_selected").value = $target_item;
	}
	postForm();
}

function disableItem($target_item){
	if($target_item != undefined){
		document.getElementById("mark_disableitem").value = 1;
		document.getElementById("id_item_selected").value = $target_item;
	}
	postForm();
}

function disableItemInForm($target_item){
	if($target_item != undefined){
		document.getElementById("mark_disableitem").value = 1;
		document.getElementById("id_item_selected").value = $target_item;
	}
}

// Enable onclick to select item in table
$(document).ready(function() {
	$('#dataTable tr').click(function() {
		var href = $(this).find("a").attr("href");
		if(href) {
			window.location = href;
		}
	});
});

$(document).ready(function() {
	$('#dataTable').DataTable( {
		stateSave: true
	} );
} );