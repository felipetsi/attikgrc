$(function() {

	if (localStorage.chkbx && localStorage.chkbx != '') {
		$('#remember_me').attr('checked', 'checked');
		$('#login').val(localStorage.usrname);
		$('#password').val(localStorage.password);
	} else {
		$('#remember_me').removeAttr('checked');
		$('#login').val('');
	}

	$('#remember_me').click(function() {

		if ($('#remember_me').is(':checked')) {
			// save login
			localStorage.usrname = $('#login').val();
			localStorage.password = $('#password').val();
			localStorage.chkbx = $('#remember_me').val();
		} else {
			localStorage.usrname = '';
			localStorage.password = '';
			localStorage.chkbx = '';
		}
	});
});
