<?php
	include('ln_library.php');
//	include('dbwrap.php');
	
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

	
	$query = "select count(*) as count, group_concat(id) as ids,group_concat(skill) as skills from skills group by ''";
	$selection = $db->DoDBQueryEx($query);
	$count = $db->GetDBQueryRowCount();
	if ($count == 0) {
		echo "no skills were found in databse";
		exit;
	}
	$row = $db->GetDBQueryRowEx(0);
	
//	$t = 1;
	$ret = "<p class='header'>Skills avaliable (" . $row['count'] . ")</p>";
	$ret .= "<input type='hidden' name='ed_sk_id' id='ed_sk_id' value=''>";
	$ret .= "<input type='hidden' name='ed_sk_act' id='ed_sk_act' value=''>";
	$ret .= print_skills(4,0,$row['ids'],$row['skills'],'','edit_skills_tb');
/*	$ret .= "<table width='100%' cellspacing=5 class='edit_skills_tb'>";
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
*/	$ret .= "<br><input type='text' size='20' maxlength='20' name='sk_name' id='sk_name'> <input type='button' value='add new skill' id='sk_add'>";
	
	echo $ret;
?>