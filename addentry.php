<?php
if ($_POST['type'] == 'person'){
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
			$sk .= "<td><input type='checkbox' name='" . htmlspecialchars($row["skill"],ENT_QUOTES) . "'> " . $row["skill"] . "</td>";
			if ($t == 3){
				$t = 0;
				$sk .= "</tr>";
			}
			$t++;
		}
	}
	
	
echo "<h2>Adding personal information</h2><br>",
	"<form>",
	"<div class='separator'><span class='rollup'>[-]</span> Personal data</div>",
	"<table border=0 width=100% cellspacing='5' id='pinfo'>",
	"<tr><td colspan=\"2\">Provider <input type=\"radio\" name=\"ptype\" id=\"provider\"> &nbsp;&nbsp;Demander <input type=\"radio\" name=\"ptype\" id=\"demander\"></td></tr>",
	"<tr><td>Given Name</td><td><input type=\"text\" size=20 maxlength=40 id=\"gname\" name=\"gname\"></td></tr>",
	"<tr><td>Middle Name</td><td><input type=\"text\" size=20 maxlength=40 id=\"mname\" name=\"mname\"></td></tr>",
	"<tr><td>Last Name</td><td><input type=\"text\" size=20 maxlength=40 id=\"lname\" name=\"lname\"></td></tr>",
	"<tr><td>Street</td><td><input type=\"text\" size=40 maxlength=100 id=\"street\" name=\"street\"></td></tr>",
	"<tr><td>ZIP</td><td><input type=\"text\" size=5 maxlength=5 id=\"zip\" name=\"zip\"></td></tr>",
	"<tr><td>Phone</td><td><input type=\"text\" size=10 maxlength=15 id=\"phone\" name=\"phone\"></td></tr>",
	"<tr><td>EMail</td><td><input type=\"text\" size=20 maxlength=40 id=\"email\" name=\"email\"></td></tr>",
	"<tr><td>URL</td><td><input type=\"text\" size=30 maxlength=60 id=\"url\" name=\"url\"></td></tr>",
	"</table>",
	"<div class='separator'><span class='rollup'>[-]</span> Skills</div>",
	"<table border=0 width=100% cellspacing='5' id='skill_table'>",
	$sk,
	"</table><br><hr>",
	"<input type=\"submit\" value=\"Add contact\"> <input type=\"reset\" value=\"Clear form\"> <input type=\"button\" value=\"Cancel\">",
	"</form>",
	"<script></script>";
}else if ($_POST['type'] == 'skill'){
	echo "<h2>Adding skill</h2><br>",
		"<form method='POST'>",
		"<table border=0 width=100% cellspacing='5' id='skill'>",
		"<tr><td>Skill </td><td><input type=\"text\" size=20 maxlength=40 id=\"gname\" name=\"gname\"></td></tr>",
		"</table><br><hr>",
		"<input type=\"submit\" value=\"Add skill\"> <input type=\"reset\" value=\"Clear form\"> <input type=\"button\" value=\"Cancel\">",
		"</form>";
}
?>
