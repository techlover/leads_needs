<?php
	include('dbwrap.php');
	
	$db = new DBWrap();
	if (isset($_POST['sk_name'])){
		if (isset($_POST['id']))
			if ($_POST['type']) 
				$query = "update skills set skill = '" . addslashes($_POST['sk_name']) . "' where skill='" . addslashes($_POST['id']) . "'";
			else $query = "delete from skills where skill='" . addslashes($_POST['id']) . "'";
		else $query = "insert into skills (skill) values('" . addslashes($_POST['sk_name']) . "')";
		//echo $query;
		//exit;
		$selection = $db->DoDBQueryEx($query);
	}
	
	$query = "select id,skill from skills order by skill asc";
	$selection = $db->DoDBQueryEx($query);
	$count = $db->GetDBQueryRowCount();
	if ($count == 0) {
		echo "no skills were found in databse";
		exit;
	}
	
	$t = 1;
	$ret = "<p>Current skills avaliable...</p>";
	$ret .= "<input type='hidden' name='ed_sk_id' id='ed_sk_id' value=''>";
	$ret .= "<input type='hidden' name='ed_sk_act' id='ed_sk_act' value=''>";
	$ret .= "<table width='100%' cellspacing=5 class='edit_skills_tb'>";
	for($i = 0; $i < $count; $i++){
		$row = $db->GetDBQueryRowEx($i);
		if ($t == 1) $ret .= "<tr>";
		$ret .= "<td id='" . $row['id'] . "'>" . $row["skill"] . "</td>";
		if ($t == 3){
			$t = 0;
			$ret .= "</tr>";
		}
		$t++;
	}
	$ret .= "</table><br>";
	$ret .= "<input type='text' size='20' maxlength='20' name='sk_name' id='sk_name'> <input type='button' value='add new skill' id='sk_add'>";
	
	echo $ret;
?>