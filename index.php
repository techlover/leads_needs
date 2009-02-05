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
					if (but_id == 'cont_add'){
							$('#middle').load('addentry.php',{sk_name : skname, id: skid, type: 0});
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
			<a href="">Home</a> | <a class="add_person" href="">Add contact</a> | <a href="">Upload</a> | <a class="show_connection" href="">Connections</a> | <a class="skill_ln" href=""> Skills</a>
	</div>
	
<?php
	include('DBWrap.php');
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
				
				$lead_query = "select gname, lname from leader where status > 0";
				$selection = $db->DoDBQueryEx($lead_query);
				if (!$selection) die ('database error while retrieving leads');
				
				$count = $db->GetDBQueryRowCount();
				if ($count)
					for ($i = 0; $i < $count; $i++){
						$row = $db->GetDBQueryRowEx($i);
						$name = $row["gname"] . " " . $row["lname"];
						echo "<li><a class='tips' href='fragment.html' rel='fragment.html' title='A lead from " . $name . "'>" . $name . "</a></li>";
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
				
				$lead_query = "select gname, lname from demander where status > 0";
				$selection = $db->DoDBQueryEx($lead_query);
				if (!$selection) die ('database error while retrieving needs');
				
				$count = $db->GetDBQueryRowCount();
				if ($count)
					for ($i = 0; $i < $count; $i++){
						$row = $db->GetDBQueryRowEx($i);
						$name = $row["gname"] . " " . $row["lname"];
						echo "<li><a class='tips' href='fragment.html' rel='fragment.html' title='A need from " . $name . "'>" . $name . "</a></li>";
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
		if(isset($_POST['action'])) $action = $_POST['action'];
		else $action = 'connect_show';
		
		switch ($action) {
			case 'cont_new':{
				if ($_POST['ptype']) $tbname = 'demander';
				else $tbname = 'leader';
			
				$m_query = "insert into " . $tbname . " (status,gname,lname,address,zip,phone,email,url) values(1,'" . addslashes($_POST['gname']) ."','" . addslashes($_POST['lname']) ."','" . addslashes($_POST['address']) ."','" . addslashes($_POST['zip']) ."','" . addslashes($_POST['phone']) ."','" . addslashes($_POST['email']) ."','" . addslashes($_POST['url']) ."')";
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
				}
				$db->DoDBQueryEx($sk_query) or die ('error in query 2');

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
</div>
</body>
</html>
