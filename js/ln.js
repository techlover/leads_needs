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
