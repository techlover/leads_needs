<?php
	if (empty($_POST['action'])) $action = 'new';
	else $action = $_POST['action'];

	if ($action == 'new'){
		include('DBWrap.php');
		
		$db = new DBWrap();
		$query = "select skill from skills order by skill asc";
		$selection = $db->DoDBQueryEx($query);
		$count = $db->GetDBQueryRowCount();
		if ($count == 0) {
			$sk = "<tr><td>no skills were found in databse</td></tr>";
		}else{
			$t = 1;
			$sk ="";
			for($i = 0; $i < $count; $i++){
				$row = $db->GetDBQueryRowEx($i);
				if ($t == 1) $sk .= "<tr>";
				$sk .= "<td><input type='checkbox' name='s_" . htmlspecialchars($row["skill"],ENT_QUOTES) . "'> " . $row["skill"] . "</td>";
				if ($t == 3){
					$t = 0;
					$sk .= "</tr>";
				}
				$t++;
			}
		}
		echo "<h3>Adding personal information</h3><br>",
			"<form action='index.php' method='POST'>",
			"<input type='hidden' name='action' value='cont_new'>",
			"<div class='separator'>Personal data</div>",
			"<table border=0 width=100% cellspacing='5' id='pinfo'>",
			"<tr><td colspan=\"2\">Provider <input type=\"radio\" name=\"ptype\" id=\"provider\" checked value='0'> &nbsp;&nbsp;Demander <input type=\"radio\" name=\"ptype\" id=\"demander\" value='1'></td></tr>",
			"<tr><td>Given Name</td><td><input type=\"text\" size=20 maxlength=40 id=\"gname\" name=\"gname\"></td></tr>",
			"<tr><td>Last Name</td><td><input type=\"text\" size=20 maxlength=40 id=\"lname\" name=\"lname\"></td></tr>",
			"<tr><td>Address</td><td><input type=\"text\" size=40 maxlength=100 id=\"street\" name=\"street\"></td></tr>",
			"<tr><td>ZIP</td><td><input type=\"text\" size=5 maxlength=5 id=\"zip\" name=\"zip\"></td></tr>",
			"<tr><td>Phone</td><td><input type=\"text\" size=10 maxlength=15 id=\"phone\" name=\"phone\"></td></tr>",
			"<tr><td>EMail</td><td><input type=\"text\" size=20 maxlength=40 id=\"email\" name=\"email\"></td></tr>",
			"<tr><td>URL</td><td><input type=\"text\" size=30 maxlength=60 id=\"url\" name=\"url\"></td></tr>",
			"</table>",
			"<div class='separator'><span class='rollup'>[-]</span> Skills</div>",
			"<table border=0 width=100% cellspacing='5' id='skill_table'>",
			$sk,
			"</table><br><hr>",
			"<input type=\"submit\" value=\"Add contact\" id='cont_add'> <input type=\"reset\" value=\"Clear form\"> <input type=\"button\" value=\"Cancel\">",
			"</form>";
	}else if ($action == 'add'){
		echo 1;
	}else if ($action == 'confirm'){
		echo 2;
	}
?>
