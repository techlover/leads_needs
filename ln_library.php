<?php
	$ptype = $_POST['ptype'];
	
	if (!class_exists('DBWrap')) include('dbwrap.php');
	if (isset($_POST['fn'])){
		switch ($_POST['fn']) {
			case 'get_plist':{
				echo get_plist($ptype);
				break;
			}case 'print_sk':{
				echo print_skills($cols,$chbox,$all_skills,$pers_skills='');
/*				$good = array(1,2,3);
				if (!in_array($_POST['fid'],$good)) die ('bad filter id');
				$sk_q1 = "select group_concat(s.skill) as skills from skills as s group by '' union select f.filter_str from filter as f where f.id=" . $_POST['fid'];
				$selection = $db->DoDBQueryEx($sk_q1);
				$count = $db->GetDBQueryRowCount();
				if ($count == 0) {
					$sk = "<table class='sinfo'><tr><td>no skills were found in databse</td></tr></table>";
				}else{
					$row = $db->GetDBQueryRowEx(0);
					$all_skills = explode(',',$row['skills']);
					$row = $db->GetDBQueryRowEx(1);
					$filt_skills = '';
					$sb = strpos('sk=>',$row['filter_str']);
					if ($sb > 0) {
						$se = strpos(';',$row['filter_str'],$sb+4);
						if ($se > 0) $filtr_str = substr($row['filter_str'],$sb+4,$se-$sb-4);
					}
					return $filtr_str;
					$sk = print_skills(3,1,$all_skills,$filt_skills);
				}
				echo $sk;
*/				break;
			}
	/*		case 'get_connlist':{
				echo get_connlist();
				break;
			}
	*/		
		}
	}

// ----------------------------------- functions --------------------------------------------------
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

	function print_skills($cols,$chbox,$all_skills,$pers_skills=''){
		$count = count($all_skills);
		$t = 1;
		$sk = "<table cellspacing='3' class='sinfo'>";
		for($i = 0; $i < $count; $i++){
			if ($t == 1) $sk .= "<tr>";
			$sk .= "<td>";
			if ($pers_skills){
				if (in_array($all_skills[$i],$pers_skills)) $ch = 'checked';
				else $ch = '';
			}
			if ($chbox) $sk .= "<input type='checkbox' name='s_" . $all_skills[$i] . "' " . $ch . "> ";
			$sk .= $all_skills[$i] . "</td>";
			if ($t == $cols){
				$t = 0;
				$sk .= "</tr>";
			}
			$t++;
		}
		$sk .= "</table>";
		return $sk;
	}
?>

