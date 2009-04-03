function validate_cont_info(){
	var text = $(':text');
	var s = text.size();
	for (i = 0; i < s; i++){
		var current = text.eq(i);
		var val = current.val().replace(/^\s\s*/,'').replace(/\s\s*$/,'');
		current.val(val);
		/*
		//if (val.length == 0){
			var id = current.attr('id');
			if ((id == 'gname') && (val.length == 0)) alert('Please input Given name');
			if ((id == 'lname') && (val.length == 0)) alert('Please input Last name');
			if ((id == 'zip') && (val.length < 5)) alert ('ZIP code should has 5 digits');
			if ((id == 'phone') && (val.length < 10)) alert ('Phone number should has at least 10 digits');
			if ((id == 'email') && ()field = 'email';
						else continue;
			alert('Please input ' + field);
			current.focus();
			return false;
		//}
		*/
	}
	
	var input = $('#gname');
	if (input.val().length == 0) {
		alert('Please enter Given name');
		input.focus();
		return false;
	}
	input = $('#lname');
	if (input.val().length == 0) {
		alert('Please enter Last name');
		input.focus();
		return false;
	}
	input = $('#zip');
	var val = input.val().length;
	if ((val > 0)&&(val < 5)) {
		alert('ZIP code should has 5 digits');
		input.focus();
		return false;
	}
	input = $('#phone');
	var val = input.val().length;
	if ((val > 0)&&(val < 10)) {
		alert('Phone number should has at least 10 digits');
		input.focus();
		return false;
	}
	input = $('#email');
	var val = input.val();
	if (val.length == 0) {
		alert('Please enter email');
		input.focus();
		return false;
	}
	var reg = new RegExp(/^\w+\.?\w+@(\w+\.{1})+[a-zA-Z]+$/);
	if (!reg.test(val)) {
		alert('Please enter correct email address');
		input.focus();
		return false;
	}
	
	return true;
}

function filterDigitField(value) {
	l = value.length;
	ret = '';
	for (i=0; i<l; i++) {
		c = value.charAt(i);
		if (c >= '0' && c <='9') ret += c;
	}
	return ret;
}

var message = function(div_id, display, msg, background, fade){
	var let = $('#' + div_id); 
	let.css('background-color',background); 
	let.html(msg); 
	if (display != ''){
		let.css({'display' : display, 'opacity' : '1'}); 
	}else if (fade) let.fadeOut(2000);};

//-------------------------------------------------------------------
function workContacts(){
// load content for work with contacts
	alert('contacts');
}
//-------------------------------------------------------------------

//-------------------------------------------------------------------
function workSkills(){
// load content for work with skills
	alert('skills');
}
//-------------------------------------------------------------------

//-------------------------------------------------------------------
function workFeedbacks(){
// load content for work with feedbacks
	message('feed_progr','inline-block','sending emails...','#8FBC8F',false);
/*	var progr = document.getElementById('feed_progr');
	progr.style.display = 'inline-block';
	progr.style.backgroundColor = '#8FBC8F';
	progr.style.opacity = 1;
	progr.innerHTML = 'sending emails...';
*/	
	$.ajax({
		type: 'POST',
		dataType: 'html',
		cache: false,
		url: 'feedback.php',
		data: 'fn=ask_feedbacks',
		success: function(new_content){message('feed_progr','',new_content,'#8FBC8F',true);},//$('#middle').html('result = ' + new_content); message('feed_progr','','list is ready','#8FBC8F',true);},
		error: function(new_content){message('feed_progr','','connection failed','#D74E4E',false);}
	});
}
//-------------------------------------------------------------------

//-------------------------------------------------------------------
function settings(){
// load content for work with settings
alert('settings');
}
//-------------------------------------------------------------------

//-------------------------------------------------------------------
function showDialog(context,conn_id){
// show dialog window with letter information
	var sheet = document.getElementById('sheet');
	sheet.style.display = 'block';
	var dialog = document.getElementById('div_dialog');
	dialog.style.display = 'block';
	dialog.innerHTML = context;
	
	$.ajax({
		type: 'POST',
		dataType: 'html',
		cache: false,
		url: 'ln_library.php',
		data: 'fn=get_letter&conn_id=' + conn_id,
		success: function(new_content){$('#div_dialog').html(new_content); },
		error: function(new_content){$('#div_dialog').html('can\'t reach destination');}
	});
}

function closeDialog(){
// clear content of dialog block and hide it; hide sheet layer;
	var sheet = document.getElementById('sheet');
	sheet.style.display = 'none';
	var dialog = document.getElementById('div_dialog');
	dialog.style.display = 'none';
	dialog.innerHTML = '';	
}

function letter_save(conn_id, send){
// save or send letter
	message('let_progr','inline-block','error sending request','#D74E4E',false);
/*	var progr = document.getElementById('let_progr');
	progr.style.display = 'inline-block';
	progr.style.backgroundColor = '#8FBC8F';
	progr.style.opacity = 1;
	progr.innerHTML = 'saving...';
*/	$.ajax({
		type: 'POST',
		dataType: 'html',
		cache: false,
		url: 'ln_library.php',
		data: 'fn=save_letter&conn_id=' + conn_id + '&subject=' + $('#subject').val() + '&let_text=' + $('#letter').val() + '&send=' + send,
		success: function(new_content){
				var result = new_content.split('#');
				var err_status = result[0];
				var mess = result[1];
				if (err_status == 0) message('let_progr','',mess,'#8FBC8F',true);
				else message('let_progr','',mess,'#D74E4E',false);
		},
		error: function(new_content){ message('let_progr','','error sending request','#D74E4E',false);}
	});	
}
//-------------------------------------------------------------------