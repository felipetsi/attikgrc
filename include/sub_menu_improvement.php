<?php
echo'
	<!-- Breadcrumbs -->
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="task.php">'.$LANG_TASKS; if ($_SESSION['THIS_PAGE'] == "task.php"){$PAGE_NAME = $LANG_TASKS;} echo '</a>
		</li>
		<li class="breadcrumb-item">
			<a href="incident.php">'.$LANG_SECURITY_INCIDENT; if ($_SESSION['THIS_PAGE'] == "incident.php"){$PAGE_NAME = $LANG_SECURITY_INCIDENT;} echo '</a>
		</li>
		<li class="breadcrumb-item">
			<a href="nonconformity.php">'.$LANG_NONCONFORMITY; if ($_SESSION['THIS_PAGE'] == "nonconformity.php"){$PAGE_NAME = $LANG_NONCONFORMITY;} echo '</a>
		</li>
		<li class="breadcrumb-item">
			<a href="project.php">'.$LANG_PROJECT; if ($_SESSION['THIS_PAGE'] == "project.php"){$PAGE_NAME = $LANG_PROJECT;} echo '</a>
		</li>
		<li class="breadcrumb-item active" aria-pressed="true">'.$PAGE_NAME.'</li>
	</ol>';
?>