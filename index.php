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
			$('#middle').load('addentry.php',{type: 'person'},function(){$('span.rollup').click(function(){$('div.separator ~ table').toggle('normal'); $(this).html('[+]');})
						});
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
				var sklen = skname.length;
				if (sklen > 0) {
					if ($target.attr('id') == 'sk_add') {
						if ($('td[innerHTML="' + skname + '"]').size() == 0)
							$('#middle').load('getskills.php',{sk_name : skname});
					}else if ($target.attr('id') == 'sk_change'){
						var skid = $('#ed_sk_id').val();
						if (skid != skname)
							$('#middle').load('getskills.php',{sk_name: skname, id: skid});				
					}
				}
			}
		}else if (tagname == 'td'){
			if ($('table.edit_skills_tb').size() > 0) {
				var clname = $target.html();
				$('#sk_name').val(clname);
				if ($('#sk_change').size() == 0) 
					$('#sk_add').before('<input type=\'button\' id=\'sk_change\' value=\'change\'>');
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
			<a class="add_person" href="">Add Entry</a> | <a href="">Upload</a> | <a class="show_connection" href="">Connections</a> | <a class="skill_ln" href=""> Skills</a>
	</div>
		
<div id="container">
	
	<!-- This list should grow dynamically with the newest item on top. aka Chronological order. -->
	<div id="left">
		<div>
			<h1>Leads</h1> <p>People with <em>leads</em> on work.</p>
		</div>
		<!-- <a class="prev" href="">prev</a> -->
		<div class="scrollable">
			<ul id="river">
				<li>
					<a class="tips" href="fragment.html" rel="fragment.html" title="A lead from Bob">Bob</a>
				</li>
				
				<li>
					<a class="tips" href="fragment.html" rel="fragment.html" title="A lead from Bob">David Thomas</a>
				</li>
				
				<li>
					<a class="tips" href="fragment.html" rel="fragment.html" title="A lead from Bob">James Protzman</a>
				</li>
				
				<li>
					<a class="tips" href="fragment.html" rel="fragment.html" title="A lead from Bob">Google</a>
				</li>
				
				<li>
					<a class="tips" href="fragment.html" rel="fragment.html" title="A lead from Bob">John Smith</a>
				</li>
				
				<li>
					<a class="tips" href="fragment.html" rel="fragment.html" title="A lead from Bob">Jane Jones</a>
				</li>
				
				<li>
					<a class="tips" href="fragment.html" rel="fragment.html" title="A lead from Bob">SAS</a>
				</li>
				
				<li>
					<a class="tips" href="fragment.html" rel="fragment.html" title="A lead from Bob">IBM</a>
				</li>

				<li>
					<a class="tips" href="fragment.html" rel="fragment.html" title="A lead from Bob">IBM</a>
				</li>

				<li>
					<a class="tips" href="fragment.html" rel="fragment.html" title="A lead from Bob">IBM</a>
				</li>
			</ul>
		</div>
		<!-- <a class="next">next</a> -->
	</div>

	<!-- This list should grow dynamically with the newest item on top. aka Chronological order. -->
	<div id="right">
		<h1>Needs</h1> <p>People who <em>need</em> work.</p>
		<ul id="river">
			<li>
				<a class="tips" href="fragment.html" rel="fragment.html" title="A need of John's">John Henry</a>
			</li>
			
			<li>
				<a class="tips" href="fragment.html" rel="fragment.html" title="A need of John's">Rick James</a>
			</li>
			
			<li>
				<a class="tips" href="fragment.html" rel="fragment.html" title="A need of John's">Matt Busy</a>
			</li>
			
			<li>
				<a class="tips" href="fragment.html" rel="fragment.html" title="A need of John's">Carl Coworker</a>
			</li>
			
			<li>
				<a class="tips" href="fragment.html" rel="fragment.html" title="A need of John's">Sue Simple</a>
			</li>
			
			<li>
				<a class="tips" href="fragment.html" rel="fragment.html" title="A need of John's">Bobby Coder</a>
			</li>
			
			<li>
				<a class="tips" href="fragment.html" rel="fragment.html" title="A need of John's">Jim Thomas</a>
			</li>
			
			<li>
				<a class="tips" href="fragment.html" rel="fragment.html" title="A need of John's">Bono</a>
			</li>
			
		</ul>
	</div>

	<div id="middle">
	<!-- This form is based on hcard. Its a microformat. http://microformats.org/wiki/hcard -->
	
	<!-- The names of the form fields also describe the database field names. -->
	
	<!-- several types of data entry into the database is possible. Manually - addentry.php, Automatically - upload.php, -->
	
	<!-- You can display both the leads and needs and their relationships on - connections.php -->
	
	<?php
		//include 'connections.php';
	?>
	
	</div>
	
</div>
<div id="footer">
	Footer stuff --> I love Signal37 design. Can you tell?
</div>
</body>
</html>
