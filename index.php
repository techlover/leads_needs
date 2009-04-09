<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Leads & Needs</title>
	<meta name="author" content="Brian Russell">
	<!-- <link rel="stylesheet" href="css/jquery.cluetip.css" type="text/css" /> -->
	<link rel="stylesheet" href="css/style.css" type="text/css" />
</head>
<body>
	<div id='sheet' class='sheet'></div>
	<div id='div_dialog' class="div_dialog"></div>
	<div id="top">
			<b>Welcome to Leads and Needs.</b>
			&nbsp;&nbsp;&nbsp;<a href="">Connections</a> | <a href="" onclick='javascript: workContacts(); return false;'>Contacts</a> | <a href="" onclick='javascript: workSkills(); return false;'> Skills</a> | <a href="" onclick='javascript: workFeedbacks(); return false;'>Get feedback</a> | <a href="" onclick='javascript: settings(); return false;'> Settings</a>&nbsp;&nbsp;&nbsp;<div class='progress' id='feed_progr'></div>
	</div>
	<?php
		if ($_POST['action'] == 'import_contacts'){
			include('contact.php');
		}
	?>
	
<div id="container">
	
	<!-- This list should grow dynamically with the newest item on top. aka Chronological order. -->
	<div id="left">
		<div class='list_header'>People with leads on work</div>
		<div class='list_menu'><table class='left_right' width='100%'><tr><td><a href='' class='add_person'>add</a> | <a href='' rel='filters.php?area=1' class='filter_lnk' title='Filter for lead'>filter</a> | <a>upload</a></td><td>sort <a href='' class='ps' value='1'>name</a> | <a href='' class='ps_act' value='1'>date</a></td></tr></table></div>
		<div class='scrollable'>
			<ul class='river' id='lpanel'>
			<?php
				include_once('ln_library.php');
				$cont = explode('##',get_plist(1,1));
				echo $cont[0];
			?>
			</ul>
		</div>
		<div class='list_status' id='lpanel_summ'>
			<?php
				echo $cont[1];
			?>
		</div>
	</div>
	<!-- <div id='rblock'> -->
		<div id="middle">
		<!-- This form is based on hcard. Its a microformat. http://microformats.org/wiki/hcard -->
		
		<!-- The names of the form fields also describe the database field names. -->
		
		<!-- several types of data entry into the database is possible. Manually - addentry.php, Automatically - upload.php, -->
		
		<!-- You can display both the leads and needs and their relationships on - connections.php -->
		
		<?php
			//include 'connections.php';
			echo get_connlist();
		?>
		
		</div>			
		<!-- This list should grow dynamically with the newest item on top. aka Chronological order. -->
		<div id="right">
			<div>People with <em>needs</em> on work.</div>
			<div class='list_menu'><table class='left_right' width='100%'><tr><td><a href='' class='add_person'>add</a> | <a href='' rel='filters.php?area=2' class='filter_lnk' title='Filter for need'>filter</a></td><td>sort <a href='' class='ps' value='2'>name</a> | <a href='' class='ps_act' value='2'>date</a></td></tr></table></div>
			<div class='scrollable'>
				<ul class='river' id='rpanel'>
				<?php
					$cont = explode('##',get_plist(2,1));
					echo $cont[0];
				?>
				</ul>
			</div>
			<div class='list_status' id='rpanel_summ'>
				<?php
					echo $cont[1];
				?>
			</div>
		</div>
	<!-- </div> -->
</div>
<div id="footer">
	Footer stuff --> I love Signal37 design. Can you tell?
	<div class='status_rainbow'>Connections --> <div class='col_0'></div> draft | <div class='col_1'></div> no feedback | <div class='col_2'></div> good | <div class='col_3'></div> pending | <div class='col_4'></div> bad</div>
</div>

<script src="js/jquery-1.3.min.js" type="text/javascript"></script>
<script src="js/ln.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#conn a').bind('click',function(event){ event.preventDefault();});
		
		$('#conn tbody>tr:first-child').bind('click',function(event){
			var tb = $(this)[0].parentNode.id;
			if ($('#' + tb + '>tr').size() == 1){
				$('#' + tb + '>tr:first-child').after('<tr><td colspan="4" class="con_inf_cell"><div class="con_inf_div">Loading...</div></td></tr>');
				var new_content = "";
				$.ajax({
					type: 'POST',
					dataType: 'html',
					cache: true,
					url: 'ln_library.php',
					data: 'fn=get_connstatus&conn_id=' + tb.replace(/[a-z]*/,''),
					success: function(new_content){$('#' + tb + " div.con_inf_div").html(new_content); },
					error: function(new_content){$('#' + tb + " div.con_inf_div").html('can\'t reach destination');}
				});
			}else {
				var last = $('#' + tb + '>tr:hidden').size();
				if (last > 0) $('#' + tb + '>tr:last').show();
				else $('#' + tb + '>tr:last').hide();
			}
		})
	});
	</script>	
</body>
</html>
