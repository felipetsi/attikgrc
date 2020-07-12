	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
	  <a class="navbar-brand" href="/dashboard.php">Attik GRC</a>
	  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse" id="navbarResponsive">
		<ul class="navbar-nav navbar-sidenav" id="risk_session">
		  <li class="nav-item active" data-toggle="tooltip" data-placement="right" title="Dashboard">
			<a class="nav-link" href="/dashboard.php">
			  <i class="fa fa-fw fa-dashboard"></i>
			  <span class="nav-link-text">
				<?php echo $LANG_DASHBOARD;?></span>
			</a>
		  </li>
		  <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Risk">
            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#risk_session_component" data-parent="#risk_session_component">
              <i class="fa fa-exclamation-triangle"></i>
              <span class="nav-link-text">
                <?php echo $LANG_RISKS;?></span>
            </a>
            <ul class="sidenav-second-level collapse" id="risk_session_component">
              <li>
                <a href="/module/risk/area.php"><?php echo $LANG_AREA;?></a>
              </li>
              <li>
                <a href="/module/risk/process.php"><?php echo $LANG_PROCESS;?></a>
              </li>
              <li>
                <a href="/module/risk/asset.php"><?php echo $LANG_ASSET;?></a>
              </li>
              <li>
                <a href="/module/risk/risk.php"><?php echo $LANG_RISK;?></a>
              </li>
              <li>
                <a href="/module/risk/control.php"><?php echo $LANG_CONTROL;?></a>
              </li>
            </ul>
          </li>
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Improvement">
            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#improvement_session" data-parent="#improvement_session">
              <i class="fa fa-fw fa-line-chart"></i>
              <span class="nav-link-text">
                <?php echo $LANG_IMPROVEMENT;?></span>
            </a>
            <ul class="sidenav-second-level collapse" id="improvement_session">
              <li>
                <a href="/module/improvement/task.php"><?php echo $LANG_TASKS;?></a>
              </li>
              <li>
                <a href="/module/improvement/incident.php"><?php echo $LANG_SECURITY_INCIDENT;?></a>
              </li>
              <li>
                <a href="/module/improvement/nonconformity.php"><?php echo $LANG_NONCONFORMITY;?></a>
              </li>
              <li>
                <a href="/module/improvement/project.php"><?php echo $LANG_PROJECT;?></a>
              </li>
            </ul>
          </li>
		  <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Reports">
			<a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#multiReport" data-parent="#report_session">
			  <i class="fa fa-fw fa-file-o"></i>
			  <span class="nav-link-text">
				<?php echo $LANG_REPORTS;?></span>
			</a>
			<ul class="sidenav-second-level collapse" id="multiReport">
			  <li>
				<a class="nav-link-collapse collapsed" data-toggle="collapse" href="#compReport"><?php echo $LANG_COMPLIANCE;?></a>
				<ul class="sidenav-third-level collapse" id="compReport">
				  <li>
					<a href="/module/report/soa.php"><?php echo $LANG_SOA;?></a>
				  </li>
				</ul>
			  </li>
			  <li>
				<a class="nav-link-collapse collapsed" data-toggle="collapse" href="#riskReport"><?php echo $LANG_RISKS;?></a>
				<ul class="sidenav-third-level collapse" id="riskReport">
				  <li>
					<a href="/module/report/reports.php?datatype=riskbyarea"><?php echo $LANG_RISK_BY_AREA;?></a>
				  </li>
				  <li>
					<a href="/module/report/reports.php?datatype=riskbyprocess"><?php echo $LANG_RISK_BY_PROCESS;?></a>
				  </li>
				  <li>
					<a href="/module/report/reports.php?datatype=riskbyrf"><?php echo $LANG_RISK_BY_FACTOR;?></a>
				  </li>
				  <li>
					<a href="/module/report/reports.php?datatype=riskbylabel"><?php echo $LANG_RISK_BY_LABEL;?></a>
				  </li>
					
				</ul>
				<a class="nav-link-collapse collapsed" data-toggle="collapse" href="#controlReport"><?php echo $LANG_CONTROLS;?></a>
				<ul class="sidenav-third-level collapse" id="controlReport">
				  <li>
					<a href="/module/report/reports.php?datatype=controlNotEvaluated"><?php echo $LANG_NOT_EVALUATED;?></a>
				  </li>
				  <li>
					<a href="/module/report/reports.php?datatype=controlDelayedRev"><?php echo $Ce;?></a>
				  </li>
				  <li>
					<a href="/module/report/reports.php?datatype=controlNotEffective"><?php echo $Cn;?></a>
				  </li>
				</ul>
				<a class="nav-link-collapse collapsed" data-toggle="collapse" href="#taskReport"><?php echo $LANG_TASK;?></a>
				<ul class="sidenav-third-level collapse" id="taskReport">
				  <li>
					<a href="/module/report/reports.php?datatype=pedingtask"><?php echo $LANG_PENDING_TASKS;?></a>
				  </li>
				  <li>
					<a href="/module/report/reports.php?datatype=taskbyperson"><?php echo $LANG_TASK_BY_PERSON;?></a>
				  </li>
				  <li>
					<a href="/module/report/reports.php?datatype=taskdelayed"><?php echo $LANG_TASKS_DELAYED;?></a>
				  </li>
				</ul>
			  </li>
			</ul>
		  </li>
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Configuration">
            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#configuration_session" data-parent="#configuration_session">
              <i class="fa fa-gears"></i>
              <span class="nav-link-text">
                <?php echo $LANG_CONFIGURATION;?></span>
            </a>
            <ul class="sidenav-second-level collapse" id="configuration_session">
              <li>
                <a href="/user.php"><?php echo $LANG_USER_MANAGEMENT;?></a>
              </li>
              <li>
                <a href="/user_profile.php"><?php echo $LANG_PROFILE_MANAGEMENT;?></a>
              </li>
              <li>
                <a href="/actions_history.php"><?php echo ($LANG_ACTION_HISTORY." (".$LANG_LOG.")");?></a>
              </li>
              <li>
                <a href="/configuration.php"><?php echo $LANG_GENERAL_CONFIGURATION;?></a>
              </li>
            </ul>
          </li>
		</ul>
		<ul class="navbar-nav sidenav-toggler">
		  <li class="nav-item">
			<a class="nav-link text-center" id="sidenavToggler">
			  <i class="fa fa-fw fa-angle-left"></i>
			</a>
		  </li>
		</ul>
		<ul class="navbar-nav ml-auto">

		<div id="msg_box" >
			<script> showMSGTop(); </script>
		</div>

		<div id="loading_box" >
			<i class="fa fa-spinner fa-spin loading_box_img"></i>

		</div>
			<?php $TEMP_ARRAY_TASK = $_SESSION['task_logged']; ?>
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle mr-lg-2" href="#" id="alertsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			  <i class="fa fa-fw fa-bell"></i>
			  <span class="d-lg-none"><?php echo $LANG_TASKS;?>
				<span class="badge badge-pill badge-warning"><?php echo $TEMP_ARRAY_TASK[0]." ".$LANG_TASKS;?></span>
			  </span>
				<?php
				if($TEMP_ARRAY_TASK[0] > 0){
					echo '
			  <span class="new-indicator text-warning d-none d-lg-block">
				<i class="fa fa-fw fa-circle"></i>
				<span class="number">'.$TEMP_ARRAY_TASK[0].'</span>
			  </span>';
				}?>
			</a>
			<div class="dropdown-menu" aria-labelledby="alertsDropdown">
			  <h6 class="dropdown-header"><?php echo ($LANG_TASKS);?>:</h6>
			  <div class="dropdown-divider"></div>
				  <form action="/module/improvement/task.php" method="post" name="formExclusiveTask" id="formExclusiveTask">
					  <input type="hidden" name="checkeditemtask" id="checkeditemtask" value="">
					  <?php
					  for ($i=1; $i <= $TEMP_ARRAY_TASK[0]; $i++)
					  {
						  $TEMP_ARRAY_INSIDE = explode("@&",$TEMP_ARRAY_TASK[$i]);
						  echo '
						  <a class="dropdown-item" href="javascript:openTaskFromMenu('.$TEMP_ARRAY_INSIDE[0].')">
							<span class="text-info">
							  <strong>
								<i class="fa fa-tasks"></i>
								'.$TEMP_ARRAY_INSIDE[1].'</strong>
							</span>
							<span class="small float-right text-muted">'.$TEMP_ARRAY_INSIDE[3].'</span>
							<div class="dropdown-message small">'.$TEMP_ARRAY_INSIDE[2].'.</div>
						  </a>';
					  }?>
				  </form>
			  </div>
		  </li>
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle mr-lg-2" href="#" id="messagesDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			  <i class="fa fa-comments-o"></i>
			  <span class="d-lg-none"><?php echo $LANG_LANGUAGE;?>
				<span class="badge badge-pill badge-primary">3 <?php echo $LANG_OPTIONS;?></span>
			  </span>
			  <span class="new-indicator text-primary d-none d-lg-block">
				
			  </span>
			</a>
			<div class="dropdown-menu" aria-labelledby="messagesDropdown">
			  <h6 class="dropdown-header"><?php echo $LANG_LANGUAGE;?></h6>
			  <div class="dropdown-divider"></div>
			  <a class="dropdown-item" href="?lang=en">
				<strong>English</strong>
				<div class="dropdown-message small">Welcome</div>
			  </a>
			  <div class="dropdown-divider"></div>
			  <a class="dropdown-item" href="?lang=pt">
				<strong>Português</strong>
				<div class="dropdown-message small">Bem-vindo</div>
			  </a>
			  <div class="dropdown-divider"></div>
			  <a class="dropdown-item" href="?lang=es">
				<strong>Español</strong>
				<div class="dropdown-message small">Bienvenido</div>
			  </a>
			</div>
		  </li>
		<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle mr-lg-2" href="#" id="userSetting" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		  <i class="fa fa-user fa-fw"></i>
		</a>
		<div class="dropdown-menu" aria-labelledby="userSetting">
		  <h6 class="dropdown-header"><?php echo $LANG_USER_SETTINGS;?></h6>
		  <div class="dropdown-divider"></div>
		  <a class="dropdown-item" href="javascript:showPersonalSettings();">
			  <i class="fa fa-user fa-fw">
				<strong><?php echo $LANG_SETTINGS;?></strong>
			  </i>
		  </a>
		  <div class="dropdown-divider"></div>
		  <a class="dropdown-item" data-toggle="modal" data-target="#logout_box" href="#logout_box">
			  <i class="fa fa-fw fa-sign-out">
				<strong><?php echo $LANG_LOGOUT;?></strong>
			  </i>
		  </a>
		</div>
		</li>
		</ul>
		<div class="col-md-1"></div>
	  </div>
	</nav>