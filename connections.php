<?php
	if (!class_exists('DBWrap')) include('dbwrap.php');
	$db = new DBWrap();

	if (empty($_POST['action'])) $action = 'view';
	else $action = $_POST['action'];

	function get_connlist($db){
		$res = "<h2>Connections</h2>".
			"<table width='100%' id='conn' cellspacing='0'>".
			"<thead id='conn_h'><tr><td>Lead</td><td>Need</td></tr></thead>".
			"<tbody id='conn_b'>";
		$conn_query = "select concat(l.gname,' ' ,l.lname) as leader, concat(n.gname,' ',n.lname) as seeker, (select group_concat(' ',skill) from person_skills where person_id=l.id group by l.id) as lskills, (select group_concat(' ',skill) from person_skills where person_id=n.id group by n.id) as nskills from connections as conn left join person as l on l.id=conn.leader_id left join person as n on n.id=conn.seeker_id where conn.status > 0";
		
		$selection = $db->DoDBQueryEx($conn_query);
		if (!$selection) die('error while retrieving connections data');
		$count = $db->GetDBQueryRowCount();
		if ($count) {
			$tb_cont ="";
			for ($i = 0; $i < $count; $i++){
				$row = $db->GetDBQueryRowEx($i);
				$tb_cont .= "<tr><td>" . $row['leader'] . "<br><div class='skstr'>" . $row['lskills'] . "</div></td><td>" . $row['seeker'] . "<br><div class='skstr'>" . $row['nskills'] . "</div></td></tr>";
			}				
			$res .= $tb_cont;
		}else $res .= "<tr><td colspan='2'>No connections found.<br><a href=''>Connect two people...</a></td>";
		$res .= "</tbody></table>";	
		return $res;
	}
	
	//include ('ln_library.php');
	switch ($action) {
		case 'view':{
			echo get_connlist($db);
/*		
			echo "<h2>Connections</h2>",
				"<table width='100%' id='conn' cellspacing='0'>",
				"<thead id='conn_h'><tr><td>Lead</td><td>Need</td></tr></thead>",
				"<tbody id='conn_b'>";
			$conn_query = "select concat(l.gname,l.lname) as leader, concat(n.gname,n.lname) as seeker from connections as conn left join person as l on l.id=conn.leader_id left join person as n on n.id=conn.seeker_id where conn.status > 0";
			$selection = $db->DoDBQueryEx($conn_query);
			if (!$selection) die('error while retrieving connections data');
			$count = $db->GetDBQueryRowCount();
			if ($count) {
				$tb_cont ="";
				for ($i = 0; $i < $count; $i++){
					$row = $db->GetDBQueryRowEx($i);
					$tb_cont .= "<tr><td>" . $row['leader'] . "</td><td>" . $row['seeker'] . "</td></tr>";
				}				
				echo $tb_cont;
			}else echo "<tr><td colspan='2'>No connections found.<br><a href=''>Connect two people...</a></td>";
			echo "</tbody></table>";
*/			
			break;
		}case 'add':{
			echo "Jim";
			break;
		}case 'match':{
			if ($_POST['ptype'] == 1) $ptype = 2;
			else if ($_POST['ptype'] == 2) $ptype = 1;
				else die('Error. wrong person type');
			$match = "select distinct(ps.person_id) as person_id,concat(p.gname,' ',p.lname) as person_name from person_skills as ps left join person as p on p.id=ps.person_id where ps.ptype=" . $ptype . " and ps.status > 0 and (ps.skill) in (select ps2.skill as skills from person_skills as ps2 where ps2.person_id = " . $_POST['id'] . ")";
			//echo $match;
			//exit;
			$selection = $db->DoDBQueryEx($match);
			if (!$selection) die('error while retrieving connections data');
			$count = $db->GetDBQueryRowCount();
			if ($count) {
				$ch = 'checked';
				$tb_cont ="<table width='100%'>";
				for ($i = 0; $i < $count; $i++){
					$row = $db->GetDBQueryRowEx($i);
					$tb_cont .= "<tr><td><input type='radio' name='matched_p' value='" . $row['person_id'] . "'id='m_" . $row['person_id'] . "' " . $ch . "> " . $row['person_name'] . "</td></tr>";
					if ($i == 0) $ch = '';
				}				
				echo $tb_cont,"</table>";
			}else echo "No matches found";
			break;
		}case 'join':{
			if ($_POST['ptype'] == 1) {$leader_id = $_POST['id']; $seeker_id = $_POST['mid'];}
			else {$leader_id = $_POST['mid']; $seeker_id = $_POST['id'];}
			$join_q = "insert into connections (leader_id,seeker_id) values(" . $leader_id . "," . $seeker_id . ")";
			//echo $join_q;
			if (!$db->DoDBQueryEx($join_q)) die('error creating new connection');
			echo get_connlist($db);
			break;
		}
	}
?>