<?php
	if (isset($_POST['conn_id'])) $id = $_POST['conn_id'];
	else die('bad connection id');
	include('dbwrap.php');
	$db = new DBWrap();
	
	$person = "select concat(l.email,', ',n.email) as emails, concat(l.gname,' ',l.lname) as lname, concat(n.gname,' ',n.lname) as nname, con.letter as letter from connections as con left join person as l on l.id=con.leader_id left join person as n on n.id=con.seeker_id where con.id=" . $id;
	$selection = $db->DoDBQueryEx($person);
	if (!$selection) die ('database error while retrieving persons emails data');
	
	$row = $db->GetDBQueryRowEx(0);
	//die($person."<br>letter = ".$row['letter']);

	echo "<input type='hidden' id='conn_id' value='" . $id . "'>",
		"<div>introducing " . $row['nname'] . " to " . $row['lname'] . "</div><br>",
		"<table><col width='90px'><col>",
		"<tr><td>From</td><td><input type='text' size='40' value='brian@carrborocoworking.com' disabled></td></tr>",
		"<tr><td>To</td><td><input type='text' size='40' value='" . $row['emails'] . "' id='sendto'></td></tr>",
		"<tr><td>Subject</td><td><input type='text' size='40' value='Introduction letter from Brian Russell' id='subject'></td></tr>",
		"<tr><td>Letter</td><td><textarea rows='12' cols='43' id='letter'>" . $row['letter'] . "</textarea></td></tr>",
		"<tr><td>&nbsp;</td><td><input type='button' value='Save' id='let_save'><input type='button' value='Save&Send' id='let_ss'>&nbsp;&nbsp;&nbsp;<div class='progress'>&nbsp;saving...</div></td></tr>",
		"</table>";
?>