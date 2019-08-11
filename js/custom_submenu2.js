// Submenu2 custom
	(function ($, window) {
		var menus = {};
		$.fn.box_submenu2 = function (settings) {
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

	$("#dataTable2 tbody tr").box_submenu2({
		menuSelector: "#box_submenu2",
		menuSelected: function ($invokedOn, $selectedMenu) {
			var $id_value = $invokedOn.attr('data-id');
			var str = $invokedOn.attr('data-parent');
			var $parameters = str.split(",");
			if($selectedMenu.attr("id") == "e2"){ //Edit
				showTaskRelatedAj($id_value,$parameters[0],$parameters[1],$parameters[2]);
				$('#taskBoxRelated').modal({show:true});
			}
			else if($selectedMenu.attr("id") == "i2"){ //Insert
				showTaskRelatedAj(0,$parameters[0],$parameters[1],$parameters[2]);
			}
			else if($selectedMenu.attr("id") == "d2"){ //Delete task from several pages, ex: risk
				deletetTask($id_value,$parameters[0],$parameters[1],$parameters[2]);
			}
			else if($selectedMenu.attr("id") == "u2"){ //Duplicate task from several pages, ex: risk
				duplicateTask($id_value,$parameters[0],$parameters[1],$parameters[2]);
			}
		}
	});
