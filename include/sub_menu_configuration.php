<?php
echo'
	<!-- Breadcrumbs -->
	<ol class="breadcrumb">
	  <li class="breadcrumb-item">
		<a href="user.php">'.$LANG_USER_MANAGEMENT; if ($_SESSION['THIS_PAGE'] == "user.php"){$PAGE_NAME = $LANG_USER_MANAGEMENT;} echo '</a>
	  </li>
	  <li class="breadcrumb-item">
		<a href="user_profile.php">'.$LANG_PROFILE_MANAGEMENT; if ($_SESSION['THIS_PAGE'] == "user_profile.php"){$PAGE_NAME = $LANG_PROFILE_MANAGEMENT;} echo '</a>
	  </li>
	  <li class="breadcrumb-item">
		<a href="actions_history.php">'.$LANG_ACTION_HISTORY; if ($_SESSION['THIS_PAGE'] == "actions_history.php"){$PAGE_NAME = $LANG_ACTION_HISTORY;} echo '</a>
	  </li>
	  <li class="breadcrumb-item">
		<a href="configuration.php">'.$LANG_GENERAL_CONFIGURATION; if ($_SESSION['THIS_PAGE'] == "configuration.php"){$PAGE_NAME = $LANG_GENERAL_CONFIGURATION;} echo '</a>
	  </li>
	  <li class="breadcrumb-item active">'.$PAGE_NAME.'</li>
	</ol>';
?>