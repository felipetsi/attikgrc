<?php
//session_start();
$_SESSION['LP'] = "./";
$DESTINATIONPAGE = "login_procurator_run.php";
$DESTINATIONPAGE_NO_LOGIN = "login.php?instance=".$_SESSION['INSTANCE_NAME'];

if(!isset($_SESSION['user_id'])||(!isset($_SESSION['INSTANCE_ID']))){
	header("Location:$DESTINATIONPAGE_NO_LOGIN");
} else {
	require_once('include/function.php');
	require_once("include/lang/".$_SESSION['lang_default']."/general.php");
	echo '
	<!DOCTYPE html>
	<html lang="en">';
		print_general_head($_SESSION['lang_default']);
	echo '
		<body class="bg-dark">

			<div class="container">
			  <div class="card card-login mx-auto mt-5">
				  <h5>'.$LANG_HAVE_PROCURATOR.'</h5>
				<div class="card-header">
				  '.$LANG_PROCURATOR_OF.':
				</div>
				<div class="card-body">
				  <form action="'.$DESTINATIONPAGE.'" name="main_form" id="main_form" method="post">
				  <input type="hidden" name="login_myself" id="login_myself" value="1">';
					$now = date('Y-m-d');
					$SQL = "SELECT p.id, p.name FROM tprocurator r, tperson p WHERE p.id = r.id_person AND r.status = 'a' AND '$now' >= r.date_start ";
					$SQL .= "AND '$now' <= r.date_end AND r.id_procurator = ".$ARRAY['id'];
					$RSPROC = pg_query($conn, $SQL);
					$ARRAYPROC = pg_fetch_array($RSPROC);
					do{
						echo'
						<table class="table table-bordered" width="100%" id="dataTable" name="dataTable" cellspacing="0">
							<thead>
								<tr>
									<th></th>
									<th>'.$LANG_NAME.'</th>
								</tr>
							</thead>
							<tr class="gradeX">
								<td data-id="'.$ARRAYPROC['id'].'"><input type="radio" name="procurator_as" value="'.$ARRAYPROC['id'].'"></td>
								<td data-id="'.$ARRAYPROC['id'].'">'.$ARRAYPROC['name'].'</td>
							</tr>
						</table>';
					}while($ARRAYPROC = pg_fetch_array($RSPROC));
					echo '
					<a class="btn btn-primary" href="javascript:login_proc_sel_user();">'.$LANG_LOGIN_SEL_USER.'</a>
					<input type="submit" class="btn btn-info" value="'.$LANG_LOGIN_MY_USER.'">
				  </form>
				</div>
			  </div>
			</div>
			<?php print_end_page();?>
		</body>
	</html>
	<script>
	function login_proc_sel_user(){
		document.getElementById("login_myself").value = "0";
		document.getElementById("main_form").submit();
	}
	</script>
	';
}?>