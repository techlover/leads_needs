<?php
	$ptype = $_POST['ptype'];
	
	if (!class_exists('DBWrap')) include('dbwrap.php');
	switch ($_POST['fn']) {
		case 'get_plist':{
			echo get_plist($ptype);
			break;
		}
/*		case 'get_connlist':{
			echo get_connlist();
			break;
		}
*/		
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
/*	
	function get_connlist(){
		$ret = "<h2>Connections</h2>" .
			"<table width='100%' id='conn' cellspacing='0'>".
			"<thead id='conn_h'><tr><td>Lead</td><td>Need</td></tr></thead>".
			"<tbody id='conn_b'>";
		$conn_query = "select concat(l.gname,l.lname) as leader, concat(n.gname,n.lname) as seeker from connections as conn left join person as l on l.id=conn.leader_id left join person as n on n.id=conn.seeker_id where conn.status > 0";
		$db = new DBWrap();
		$selection = $db->DoDBQueryEx($conn_query);
		if (!$selection) die('error while retrieving connections data');
		$count = $db->GetDBQueryRowCount();
		if ($count) {
			//$tb_cont ="";
			for ($i = 0; $i < $count; $i++){
				$row = $db->GetDBQueryRowEx($i);
				$ret .= "<tr><td>" . $row['leader'] . "</td><td>" . $row['seeker'] . "</td></tr>";
			}				
			//echo $tb_cont;
		}else $ret .= "<tr><td colspan='2'>No connections found.<br><a href=''>Connect two people...</a></td>";
		$ret .= "</tbody></table>";
		return $ret;
	}
*/
?>

