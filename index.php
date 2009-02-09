<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Leads & Needs</title>
	<meta name="author" content="Brian Russell">
	<link rel="stylesheet" href="css/jquery.cluetip.css" type="text/css" />
	<link rel="stylesheet" href="css/style.css" type="text/css" />		
	<script src="js/jquery-1.3.min.js" type="text/javascript"></script>
	<script src="js/jquery.dimensions.js" type="text/javascript"></script>
	<script src="js/jquery.hoverIntent.js" type="text/javascript"></script> <!-- optional -->
	<script src="js/jquery.cluetip.js" type="text/javascript"></script>
	<!-- <script src="js/jquery.scrollable-1.0.1.min.js" type="text/javascript"></script> -->
	<script type="text/javascript">	
	$(document).ready(function() {
		$('a.tips').cluetip();

	$('#houdini').cluetip({
	    splitTitle: '|', // use the invoking element's title attribute to populate the clueTip...
	                     // ...and split the contents into separate divs where there is a "|"
	    showTitle: false // hide the clueTip's heading
	});	

	$("a.add_person").click(function(event){
		$('#middle').load('contact.php',{action: 'new'});
		event.preventDefault();
		$.ajax({
				type: 'GET',
				url: 'js/ln.js',
				dataType: 'script',
				cache: false,
				success: function(){
					$('#zip,#phone').keyup(function(event){
							var val = filterDigitField($(this).val());
							$(this).val(val);
						});				
				}
		});
	});

	$("a.skill_ln").click(function(event){
		$('#middle').load('getskills.php');
		event.preventDefault();
	});

	$('#middle').click(function(event){
		var $target = $(event.target);
		var tagname = $target[0].tagName.toLowerCase();
		if (tagname == 'input') {
			var type = $target[0].type.toLowerCase();
			if (type == 'button') {
				var skname = $('#sk_name').val();
				if (skname) {
					var sklen = skname.length;
					if (sklen > 0) {
						var but_id = $target.attr('id');
						if (but_id == 'sk_add') {
							if ($('td[innerHTML="' + skname + '"]').size() == 0)
								$('#middle').load('getskills.php',{sk_name : skname});
						}else if (but_id == 'sk_change'){
							var skid = $('#ed_sk_id').val();
							if (skid != skname)
								$('#middle').load('getskills.php',{sk_name: skname, id: skid, type: 1});				
						}else if (but_id == 'sk_remove'){
							var skid = $('#ed_sk_id').val();
							$('#middle').load('getskills.php',{sk_name : skname, id: skid, type: 0});
						}
					}
				}else {
					var r_data = "";
					var pt = 0;
					var panel = '';
					var pt2 = 0;
					var panel2 = '';
					var after = function(){};
					var but_id = $target.attr('id');
					//if (but_id == 'cont_add'){
					//	$('#middle').load('contact.php',{action: 'new', id: 0});
					if (but_id == 'cont_edit'){
						r_data = "action=edit&id=" + $('#person_id').val();// + "&ptype=" + $('#person_tp').val();
						after = function(new_content){
							$('#middle').html(new_content);
							$('#zip,#phone').keyup(function(event){
								var val = filterDigitField($(this).val());
								$(this).val(val);
							});
						}
					}
					if (but_id == 'cont_cancel'){
						$('#middle').load('connections.php');
						return;
					}
					if (but_id == 'cont_disable'){
						r_data = "action=disable&id=" + $('#person_id').val();
						var old = Number($('#old_ptype').val());
						after = function(new_content){
							$('#middle').html(new_content);
							var pan = '#lpanel';						
							if (old == 2) pan = '#rpanel'; 
							$(pan).load('ln_library.php',{ptype: old, fn: 'get_plist'},function(){$('a.tips').cluetip()});
						};
					}
					if ((but_id == 'cont_save') || (but_id == 'cont_add')){
						if (!validate_cont_info()) return;
						var mode = 0;
						var pers_ident = "";
						if (but_id == 'cont_save') {
							mode = '1';
							pers_ident = "&id=" + $('#person_id').val();
						}
						if ($('#leader').attr('checked')) {pers_ident += "&ptype=1"; panel = '#lpanel'; pt = 1; panel2 = '#rpanel'; pt2 = 2;}
						else {pers_ident += "&ptype=2"; panel = '#rpanel'; pt = 2; panel2 = '#lpanel'; pt2 = 1;}
						var skills = $('[input:checkbox][checked]');
						var size = skills.size();
						var r_data = "action=save" + pers_ident + "&mode=" + mode +
										"&gname=" + $('#gname').val() + "&lname=" + $('#lname').val() + 
										"&address=" + $('#address').val() + "&zip=" + $('#zip').val() + 
										"&phone=" + $('#phone').val() + "&email=" + $('#email').val() + 
										"&url=" + $('#url').val();
						for (i = 0; i < size; i++){
							r_data += "&" + skills.eq(i).attr('name') + "=on";
						}
						after = function(new_content){
							$('#middle').html(new_content);
							if ((but_id == 'cont_save') || (but_id == 'cont_add')) {
								$(panel).load('ln_library.php',{ptype: pt, fn: 'get_plist'},function(){$('a.tips').cluetip()});
								$(panel2).load('ln_library.php',{ptype: pt2, fn: 'get_plist'},function(){$('a.tips').cluetip()});
							}
							$('#zip,#phone').keyup(function(event){
								var val = filterDigitField($(this).val());
								$(this).val(val);
							})
						};
					}
					$.ajax({
						type: 'POST',
						url: 'contact.php',
						data: r_data,
						cache: false,
						success: after
					})
				}
			}
		}else if (tagname == 'td'){
			if ($('table.edit_skills_tb').size() > 0) {
				var clname = $target.html();
				$('#sk_name').val(clname);
				if ($('#sk_change').size() == 0) {
					$('#sk_add').before('<input type=\'button\' id=\'sk_change\' value=\'change\'>');
					$('#sk_add').after('<input type=\'button\' id=\'sk_remove\' value=\'remove\'>');
				}
				$('#ed_sk_id').val(clname);
				var old = $('#ed_sk_act').val();
				if (old.length > 0) $('#' + old).css('background-color','');
				$target.css('background-color','#B0C4DE');
				$('#ed_sk_act').val($target.attr('id'));
			}
		}
	});
		
	  
	$("a.show_connection").click(function(event){
			$('#middle').load('connections.php');
			event.preventDefault();
	  });
	});
	</script>	
</head>
<body>
	<div id="top">
			<strong>Welcome to Leads and Needs.</strong>
			<a class="show_connection" href="">Connections</a> | <a class="add_person" href="">Add contact</a> | <a href="">Upload</a> | <a class="skill_ln" href=""> Skills</a>
	</div>
	
<?php
	include('DBWrap.php');
	
	if(isset($_POST['action'])) $action = $_POST['action'];
	else $action = 'connect_show';
?>		
<div id="container">
	
	<!-- This list should grow dynamically with the newest item on top. aka Chronological order. -->
	<div id="left">
		<div>
			<h1>Leads</h1> <p>People with <em>leads</em> on work.</p>
		</div>
		<!-- <a class="prev" href="">prev</a> -->
		<div class="scrollable">
			<ul class="river" id='lpanel'>
			<?php
				include_once('ln_library.php');
				echo get_plist(1);
			?>
			</ul>
		</div>
		<!-- <a class="next">next</a> -->
	</div>

	<!-- This list should grow dynamically with the newest item on top. aka Chronological order. -->
	<div id="right">
		<h1>Needs</h1> <p>People who <em>need</em> work.</p>
		<ul class="river" id='rpanel'>		
			<?php
				echo get_plist(2);
			?>
		</ul>
	</div>

	<div id="middle">
	<!-- This form is based on hcard. Its a microformat. http://microformats.org/wiki/hcard -->
	
	<!-- The names of the form fields also describe the database field names. -->
	
	<!-- several types of data entry into the database is possible. Manually - addentry.php, Automatically - upload.php, -->
	
	<!-- You can display both the leads and needs and their relationships on - connections.php -->
	
	<?php
		switch ($action) {
			case 'connect_show':{
				include 'connections.php';
				break;
			}
		}
	?>
	
	</div>
	
</div>
<div id="footer">
	Footer stuff --> I love Signal37 design. Can you tell?
	<p>.Net i changed to _Net because there was problems with .Net as a name</p>
	<p>in leader_skills database skills should be saved by "id" but not by "name", because now skills doesn't connect to their database</p>
</div>
</body>
</html>
