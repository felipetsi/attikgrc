<?php
echo'
	<!-- Breadcrumbs -->
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="area.php">'.$LANG_AREA; if ($_SESSION['THIS_PAGE'] == "area.php"){$PAGE_NAME = $LANG_AREA;} echo '</a>
		</li>
		<li class="breadcrumb-item">
			<a href="process.php">'.$LANG_PROCESS; if ($_SESSION['THIS_PAGE'] == "process.php"){$PAGE_NAME = $LANG_PROCESS;} echo '</a>
		</li>
		<li class="breadcrumb-item">
			<a href="asset.php">'.$LANG_ASSET; if ($_SESSION['THIS_PAGE'] == "asset.php"){$PAGE_NAME = $LANG_ASSET;} echo '</a>
		</li>
		<li class="breadcrumb-item">
			<a href="risk.php">'.$LANG_RISK; if ($_SESSION['THIS_PAGE'] == "risk.php"){$PAGE_NAME = $LANG_RISK;} echo '</a>
		</li>
		<li class="breadcrumb-item">
			<a href="control.php">'.$LANG_CONTROL; if ($_SESSION['THIS_PAGE'] == "control.php"){$PAGE_NAME = $LANG_CONTROL;} echo '</a>
		</li>
		<li class="breadcrumb-item active" aria-pressed="true">'.$PAGE_NAME.'</li>
	</ol>';
?>