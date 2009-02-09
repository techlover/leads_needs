<?php
	$ptype = $_POST['ptype'];
	
	switch ($_POST['fn']) {
		case 'get_plist':{
			include('dbwrap.php');
			echo get_plist($ptype);
			break;
		}
	}

// functions
	function get_plist($ptype){
		if ($ptype == 1) $category = 'lead';
		else if ($ptype == 2) $category = 'need';
			else return 'wrong person type passed should be in {1,2}';
		$res = '';
		$db = new DBWrap();

		$query = "select id, gname, lname from person where status > 0 and ptype=" . $ptype . " order by created desc";
		$selection = $db->DoDBQueryEx($query);
		if (!$selection) die ('database error while retrieving list of persons');
		
		$count = $db->GetDBQueryRowCount();
		if ($count)
			for ($i = 0; $i < $count; $i++){
				$row = $db->GetDBQueryRowEx($i);
				$name = $row["gname"] . " " . $row["lname"];
				$res .= "<li><a class='tips' href='fragment.php?id=" . $row['id'] . "&t=" . $ptype . "' rel='fragment.php?id=" . $row['id'] . "&t=" . $ptype . "' title='A " . $category . " from " . $name . "'>" . $name . "</a></li>";
			}
		else $res .= "<li>" . $category . "s list is empty</li><li><a class='add_person' href='#'>Add " . $category . "</a></li>";
		
		return $res;
	}
?>

