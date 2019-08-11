// table formating
    
$(function(){

  $('table > tbody > tr:odd').addClass('odd');

  $('table > tbody > tr').hover(function(){
	$(this).toggleClass('hover');
  });

  $('#select_all_itens').click(function(){
	$('table > tbody > tr > td > :checkbox')
	  .attr('checked', $(this).is(':checked'))
	  .trigger('change');
  });

  $('table > tbody > tr > td > :checkbox').bind('click change', function(){
	var tr = $(this).parent().parent();
	if($(this).is(':checked')) $(tr).addClass('selected');
	else $(tr).removeClass('selected');
  });

  $('form').submit(function(e){ e.preventDefault(); });

  $('#pesquisar').keydown(function(){
	var encontrou = false;
	var termo = $(this).val().toLowerCase();
	$('table > tbody > tr').each(function(){
	  $(this).find('td').each(function(){
		if($(this).text().toLowerCase().indexOf(termo) > -1) encontrou = true;
	  });
	  if(!encontrou) $(this).hide();
	  else $(this).show();
	  encontrou = false;
	});
  });

  $("table") 
	.tablesorter({
	  dateFormat: 'uk',
	  headers: {
		0: {
		  sorter: false
		},
		5: {
		  sorter: false
		}
	  }
	}) 
	.tablesorterPager({container: $("#pager")})
	.bind('sortEnd', function(){
	  $('table > tbody > tr').removeClass('odd');
	  $('table > tbody > tr:odd').addClass('odd');
	});

});
// END table formating

// Submenu custom
	(function ($, window) {
		var menus = {};
		$.fn.box_submenu = function (settings) {
			var $menu = $(settings.menuSelector);
			$menu.data("menuSelector", settings.menuSelector);
			if ($menu.length === 0) return;

			menus[settings.menuSelector] = {$menu: $menu, settings: settings};

			//make sure menu closes on any click
			$('body').click(function () {
				hideAll();
			});

			$(document).on("contextmenu", function (e) {
				var $ul = $(e.target).closest("ul");
				if ($ul.length === 0 || !$ul.data("menuSelector")) {
					hideAll();
				}
			});

			// Open context menu
			(function(element, menuSelector){
				element.on("contextmenu", function (e) {
					// return native menu if pressing control
					if (e.ctrlKey) return;

					hideAll();
					var menu = getMenu(menuSelector);

					//open menu
					menu.$menu
					.data("invokedOn", $(e.target))
					.show()
					.css({
						position: "absolute",
						left: getMenuPosition(e.clientX, 'width', 'scrollLeft'),
						top: getMenuPosition(e.clientY, 'height', 'scrollTop')
					})
					.off('click')
					.on('click', 'a', function (e) {
						menu.$menu.hide();

						var $invokedOn = menu.$menu.data("invokedOn");
						var $selectedMenu = $(e.target);

						callOnMenuHide(menu);
						menu.settings.menuSelected.call(this, $invokedOn, $selectedMenu);
					});

					callOnMenuShow(menu);
					return false;
				});
			})($(this), settings.menuSelector);

			function getMenu(menuSelector) {
				var menu = null;
				$.each( menus, function( i_menuSelector, i_menu ){
					if (i_menuSelector == menuSelector) {
						menu = i_menu
						return false;
					}
				});
				return menu;
			}
			function hideAll() {
				$.each( menus, function( menuSelector, menu ){
					menu.$menu.hide();
					callOnMenuHide(menu);
				});
			}

			function callOnMenuShow(menu) {
				var $invokedOn = menu.$menu.data("invokedOn");
				if ($invokedOn && menu.settings.onMenuShow) {
					menu.settings.onMenuShow.call(this, $invokedOn);
				}
			}
			function callOnMenuHide(menu) {
				var $invokedOn = menu.$menu.data("invokedOn");
				menu.$menu.data("invokedOn", null);
				if ($invokedOn && menu.settings.onMenuHide) {
					menu.settings.onMenuHide.call(this, $invokedOn);
				}
			}

			function getMenuPosition(mouse, direction, scrollDir) {
				var win = $(window)[direction](),
					scroll = $(window)[scrollDir](),
					menu = $(settings.menuSelector)[direction](),
					position = mouse + scroll;

				// opening menu would pass the side of the page
				if (mouse + menu > win && menu < mouse) {
					position -= menu;
				}

				return position;
			}    
					return this;
		};
	})(jQuery, window);

	$("#dataTable tbody tr").box_submenu({
		menuSelector: "#box_submenu",
		menuSelected: function ($invokedOn, $selectedMenu) {
			var $id_value = $invokedOn.attr('data-id');
			if($selectedMenu.attr("id") == "e"){ //Edit
				selectTableItem($id_value);
			}
			else if($selectedMenu.attr("id") == "d"){ //Delete
				deleteItem($id_value);
			}
			else if($selectedMenu.attr("id") == "u"){ //Duplicate
				duplicateItem($id_value);
			}
			else if($selectedMenu.attr("id") == "s"){ //Disable
				disableItem($id_value);
			}
			else if($selectedMenu.attr("id") == "m"){ //Multi-select
				changeMultiSelect();
			}
			else if($selectedMenu.attr("id") == "p"){ //Access Project
				accessProjet($id_value);
			}
			else if($selectedMenu.attr("id") == "i"){ //Mitigate Risk
				mitigateRisk($id_value);
			}
			else if($selectedMenu.attr("id") == "a"){ //Asset
				showAssets($id_value);
			}
			else if($selectedMenu.attr("id") == "c"){ //Accept Risk
				confirmRiskTreatment($id_value,'c');
			}
			else if($selectedMenu.attr("id") == "v"){ //Avoid Risk
				confirmRiskTreatment($id_value,'v');
			}
			else if($selectedMenu.attr("id") == "t"){ //Transfer Risk
				confirmRiskTreatment($id_value,'t');
			}
			else if($selectedMenu.attr("id") == "it"){ //Insert
				var $id_control = $invokedOn.attr('data-parent');
				openToInsertTask($id_control);
			}
			else if($selectedMenu.attr("id") == "et"){ //Edit task from several pages, ex: project
				var $id_control = $invokedOn.attr('data-parent');
				showTaskRelated($id_value,$id_control);
			}
			else if($selectedMenu.attr("id") == "dt"){ //Delete task from several pages, ex: project
				deletetTask($id_value);
			}
			else if($selectedMenu.attr("id") == "ut"){ //Duplicate task from several pages, ex: project
				duplicateTask($id_value);
			}
		}
	});

	// Function to show revision parameter in Control screen
	document.getElementById("enable_revision").onclick = function () {

		if(document.getElementById("box_conf_revision").style.visibility == "visible"){
			document.getElementById("box_conf_revision").style.visibility = "hidden";
		} else {
			document.getElementById("box_conf_revision").style.visibility = "visible";
		}
	};

// END - Submenu custom

// Function to run the one click in tables
$(document).ready(function() {

	$('#dataTable3 tr').click(function() {
		var href = $(this).find("a").attr("href");
		if(href) {
			window.location = href;
		}
	});

});

// Restrict only number
$("#goal").keyup(function() {
	var num = $("#goal").val().replace(/[^0-9.]+/g,"");
	$("#goal").val(num);
});