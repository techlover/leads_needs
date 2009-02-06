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
		$('#middle').load('addentry.php',{action: 'new'});
		event.preventDefault();
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
					var but_id = $target.attr('id');
					//if (but_id == 'cont_add')
					//	$('#middle').load('addentry.php',{action: 'new', id: 0});
					if (but_id == 'cont_edit')
						$('#middle').load('addentry.php',{action: 'edit', id: $('#person_id').val(), tp: $('#person_tp').val()});
					if (but_id == 'cont_save'){
						//$('#middle').load('addentry.php',{action: 'save', id: $('#person_id').val(), tp: $('#person_tp').val()});					
						var skills = $('[input:checkbox][checked]');
						var size = skills.size();
						var r_data = "action=save&id=" + $('#person_id').val() + "&tp=" + $('#person_tp').val()+ "&mode=1" + 
										"&gname=" + $('#gname').val() + "&lname=" + $('#lname').val() + 
										"&address=" + $('#address').val() + "&zip=" + $('#zip').val() + 
										"&phone=" + $('#phone').val() + "&email=" + $('#email').val() + 
										"&url=" + $('#url').val();
						for (i = 0; i < size; i++){
							r_data += "&" + skills.eq(i).attr('name') + "=on";
						}
						
						$.ajax({
								type: 'POST',
								url: 'addentry.php',
								data: "" + r_data + "",
								cache: false,
								success: function(new_content){
									$('#middle').html(new_content);
								}
								});
					}
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

	if ($_POST['ptype']) { $tbname = 'demander'; $pers_tp = 1;}
	else { $tbname = 'leader'; $pers_tp = 0;}
	
	switch ($action) {
		case 'cont_new':{
			$m_query = "insert into " . $tbname . " (status,gname,lname,address,zip,phone,email,url) values(1,'" . addslashes($_POST['gname']) ."','" . addslashes($_POST['lname']) ."','" . addslashes($_POST['address']) ."'," . number_format($_POST['zip']) .",'" . addslashes($_POST['phone']) ."','" . addslashes($_POST['email']) ."','" . addslashes($_POST['url']) ."')";
			//echo $m_query;
			$db = new DBWrap();
			$db->DoDBQueryEx($m_query) or die('error in query 1');
			$id = $db->GetLastInsId();

			$sk_query = "";
			foreach($_POST as $key=>$value){
				if (stripos($key,'_') === 1) 
					$sk_query .= "(" . $id .",'" . substr(addslashes($key),2) . "'),";
			}

			$len = strlen($sk_query);
			if ($len > 0) {
				$sk_query = substr($sk_query,0,$len - 1);
				$sk_query = "insert into " . $tbname . "_skills (person_id, skill) values " . $sk_query;
				$db->DoDBQueryEx($sk_query) or die ('error in query 2');
			}
			break;
		}
	}
?>		
<div id="container">
	
	<!-- This list should grow dynamically with the newest item on top. aka Chronological order. -->
	<div id="left">
		<div>
			<h1>Leads</h1> <p>People with <em>leads</em> on work.</p>
		</div>
		<!-- <a class="prev" href="">prev</a> -->
		<div class="scrollable">
			<ul id="river">
			<?php
				$db = new DBWrap();
				
				$lead_query = "select id, gname, lname from leader where status > 0";
				$selection = $db->DoDBQueryEx($lead_query);
				if (!$selection) die ('database error while retrieving leads');
				
				$count = $db->GetDBQueryRowCount();
				if ($count)
					for ($i = 0; $i < $count; $i++){
						$row = $db->GetDBQueryRowEx($i);
						$name = $row["gname"] . " " . $row["lname"];
						echo "<li><a class='tips' href='fragment.php?id=" . $row['id'] . "&t=l' rel='fragment.php?id=" . $row['id'] . "&t=l' title='A lead from " . $name . "'>" . $name . "</a></li>";
					}
				else echo "<li>Leads list is empty</li><li><a class='add_person' href='#'>Add lead</a></li>";
			?>
			</ul>
		</div>
		<!-- <a class="next">next</a> -->
	</div>

	<!-- This list should grow dynamically with the newest item on top. aka Chronological order. -->
	<div id="right">
		<h1>Needs</h1> <p>People who <em>need</em> work.</p>
		<ul id="river">
			<?php
				$db = new DBWrap();
				
				$need_query = "select id, gname, lname from demander where status > 0";
				$selection = $db->DoDBQueryEx($need_query);
				if (!$selection) die ('database error while retrieving needs');
				
				$count = $db->GetDBQueryRowCount();
				if ($count)
					for ($i = 0; $i < $count; $i++){
						$row = $db->GetDBQueryRowEx($i);
						$name = $row["gname"] . " " . $row["lname"];
						echo "<li><a class='tips' href='fragment.php?id=" . $row['id'] . "&t=d' rel='fragment.php?id=" . $row['id'] . "&t=d' title='A need from " . $name . "'>" . $name . "</a></li>";
					}
				else echo "<li>Needs list is empty</li><li><a class='add_person' href='#'>Add need</a></li>";
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
			case 'cont_new':{
				// now show what exactly we added in contact
				$ch_query = "select gname,lname,address,zip,phone,email,url,(select group_concat(skill) from " . $tbname . "_skills where person_id = " . $id . " group by person_id) as skills from " . $tbname . " where id = " . $id;
				$selection = $db->DoDBQueryEx($ch_query);
				if (!$selection) die ('database error while retrieving saved data');
				
				$count = $db->GetDBQueryRowCount();
				if ($count)	$row = $db->GetDBQueryRowEx(0);
				else die ("contact with id=" . $id . " not found");
						
				echo "<h3>Contact added successfuly</h3>",
					"<div class='separator'>Personal information</div>",
					"<table cellspacing='3'>",
					"<tr><td>Given name</td><td>",$row['gname'],"</td></tr>",
					"<tr><td>Last name</td><td>",$row['lname'],"</td></tr>",
					"<tr><td>Address</td><td>",$row['address'],"</td></tr>",
					"<tr><td>Zip</td><td>",$row['zip'],"</td></tr>",
					"<tr><td>Email</td><td>",$row['email'],"</td></tr>",
					"<tr><td>Url</td><td>",$row['url'],"</td></tr></table>",
					"<div class='separator'>Skills</div>",
					"<table cellspacing='3'>";
				$skills = explode(',', $row['skills']);
				$count = count($skills);
				$t = 1;
				$sk ="";
				for($i = 0; $i < $count; $i++){
					if ($t == 1) $sk .= "<tr>";
					$sk .= "<td>" . $skills[$i] . "</td>";
					if ($t == 3){
						$t = 0;
						$sk .= "</tr>";
					}
					$t++;
				}
				echo $sk,"</table><input type='button' value='<-- edit' id='cont_edit'> <input type='button' value='continue -->'> <input type='button' value='disable contact'>",
					"<input type='hidden' id='person_id' value=" . $id . "><input type='hidden' id='person_tp' value='",$pers_tp,"'>";
				break;
			}
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
