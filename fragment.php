<?php
	if (isset($_GET['id'])) $id = $_GET['id'];
	else die('bad person id');
	
	include('dbwrap.php');
	$db = new DBWrap();
	
	$person = "select ptype,gname,lname,address,zip,phone,email,url,(select group_concat(skill) from person_skills where person_id = " . $id . " group by person_id) as skills from person where id = " . $id . " and ptype=" . $_GET['t'];
	$selection = $db->DoDBQueryEx($person);
	if (!$selection) die ('database error while retrieving saved data');
	
	$count = $db->GetDBQueryRowCount();
	if ($count) $row = $db->GetDBQueryRowEx(0);
	else die('no contact with id=' . $id);
	
	echo "<input type='hidden' id='tipp_id' value='" . $id . "'><input type='hidden' id='tipp_type' value='" . $row['ptype'] . "'>";
	echo "<a id='cl_cont_edit' href='#'>edit</a> | <a id='cl_cont_match' href='#'>match&join</a> | <a id='cl_cont_dis' href='#'>disable</a><br>";
	echo "<table><col width='90px'><col>";
	echo "<tr><td>Given Name</td><td><b>",$row['gname'],"</b></td></tr>";
	echo "<tr><td>Last Name</td><td><b>",$row['lname'],"</b></td></tr>";
	echo "<tr><td>Organization</td><td><b>?</b></td></tr>";
	echo "<tr><td>Address</td><td><b>",$row['address'],"</b></td></tr>";
	echo "<tr><td>zip</td><td><b>",$row['zip'],"</b></td></tr>";
	echo "<tr><td>Phone</td><td><b>",$row['phone'],"</b></td></tr>";
	echo "<tr><td>email</td><td><b>",$row['email'],"</b></td></tr>";
	echo "<tr><td>url</td><td><b>",$row['url'],"</b></td></tr>";
	echo "</table>";
	echo "<p class='header'>Skills</p><table class='sinfo'>";

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
	echo $sk,"</table>";
	
	
	/*
Middle Name Family Name<br />
Organization<br />
Street<br />
City, State<br />
Postal Code<br />
Country Name<br />
Phone<br />
Email<br />
URL<br />
Skills:<br />
<p>-PHP;  -C++;  -Ruby;  -Photoshop</p>

<form>
<label for="leeds">Select</label>
<input type="checkbox" name="browser" value="leed"><br>
</form>
*/
?>