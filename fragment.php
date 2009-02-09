<?php
	if (isset($_GET['id'])) $id = $_GET['id'];
	else die('bad person id');
	
	include('dbwrap.php');
	$db = new DBWrap();
	
	$person = "select gname,lname,address,zip,phone,email,url,(select group_concat(skill) from person_skills where person_id = " . $id . " group by person_id) as skills from person where id = " . $id . " and ptype=" . $_GET['t'];
	$selection = $db->DoDBQueryEx($person);
	if (!$selection) die ('database error while retrieving saved data');
	
	$count = $db->GetDBQueryRowCount();
	if ($count) $row = $db->GetDBQueryRowEx(0);
	else die('no contact with id=' . $id);
	
	echo "<a href='#'>edit</a> | <a href='#'>disable</a> | <a href='#'>add to connection</a><br>";
	echo "Given Name &nbsp;&nbsp;<b>",$row['gname'],"</b><br>";
	echo "Last Name &nbsp;&nbsp;<b>",$row['lname'],"</b><br>";
	echo "Address &nbsp;&nbsp;<b>",$row['address'],"</b><br>";
	echo "zip &nbsp;&nbsp;<b>",$row['zip'],"</b><br>";
	echo "Phone &nbsp;&nbsp;<b>",$row['phone'],"</b><br>";
	echo "email &nbsp;&nbsp;<b>",$row['email'],"</b><br>";
	echo "url &nbsp;&nbsp;<b>",$row['url'],"</b><br>";
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