<?php
	if (!class_exists('DBWrap')) include('dbwrap.php');
	$db = new DBWrap();
	
	$amountpp = 3; // amount of connections to show per page
	$linkspp = 5;  // amount of numbered links in line to show on page exmpl. 1 2 3 4 ...
	
	if (empty($_POST['action']))
		if (empty($_GET['action'])) $action = 'view';
		else $action = $_GET['action'];
	else $action = $_POST['action'];

	function get_pglinks($start,$end,$am_per_page,$show_pages){
		if ($end <= 0) return "";
		if ($start > $end) {$tmp = $start; $start = $end; $end = $tmp;}
		$ab = "<a href='#' id='xpage'>";
		$res = '';
		if ($start > 1) {$res .= " prev ";}
		$amount = $end - $start + 1;
		$pages = floor($amount / $am_per_page);
		$remainder = $amount % $am_per_page;
		if ($pages > $show_pages) { $pages = $show_pages; $newstart = $show_pages*$am_per_page; }
		else $newstart = 0;
		$t = $start;
		for ($i = 0; $i < $pages; $i++){
			$res .= $ab . "[" . $t . "-" . ($t+$am_per_page-1) . "]</a> ";
			$t += $am_per_page;
		}
		if ($remainder == 1) $res .= $ab . "[" . $t . "]</a>";
		if ($remainder > 1) $res .= $ab . "[" . $t . "-" . ($t+$remainder-1) . "]</a>";
		
		if ($newstart) $res .= " next ";
		return $res;
	}

	function get_pglinks2($active_pg,$total_rows,$rows_per_page,$links_toshow){
		if (($total_rows <= 0) or ($rows_per_page <= 0)) return "incorect arguments";
		if ($active_pg > $total_rows) $active_pg = 0;
		$ab = "<a href='#' id='xpage2'>";
		$res = '';
		
		$max_page = floor($total_rows/$rows_per_page); // max amount of pages
		$remainder = $total_rows % $rows_per_page;		
		if ($remainder) $max_page++; //increase page amount if one not full page left
		
		$skip = floor($active_pg/$links_toshow); // how many pages to skip
		$start_n = $skip*$links_toshow;	//start links from this number
		$end_n = $start_n + $links_toshow;
		if ($end_n > $max_page) $end_n = $max_page;
		//if (floor($active_pg/$links_toshow) > 0) $res .= "prev ";
		//$end_n++;
		//return $max_page . ", " . $skip . ", " . $start_n . ", " . $end_n;
		for ($i = $start_n; $i < $end_n; $i++){
			if ($i == $active_pg) $res .= ($i+1) . " ";
			else $res .= $ab . ($i+1) . "</a> ";
		}
		$res .= "&nbsp;&nbsp;&nbsp;(of " .$max_page . ")";
		return $res;
	}
	
//	function get_connlist($db,$start,$app){
	function get_connlist($db,$page,$app,$linkspp){
		$res = "<div id='conn_menu'><div id='conn_showlist'>connections</div><div id='conn_showfilt'>filter</div></div>".
			"<div id='conn_filt' style='display: none'></div>" .
			"<div id='conn_cont'>".
		//	"<table width='100%' id='conn_h' cellspacing='0'>".
		//	"<thead id='conn_h'><tr><td>Lead</td><td>Need</td></tr></thead></table><br>".
			"<table width='100%' id='conn' cellspacing='2'>".
			"<col class='def_col'><col class='sep_col'><col><col class='del_col'>";
		$conn_query = "select SQL_CALC_FOUND_ROWS UNIX_TIMESTAMP(conn.letter_date) as letter_date, UNIX_TIMESTAMP(conn.intro_date) as intro_date, conn.letter_stat as letter_stat, conn.id as conn_id, l.id as leader_id, concat(l.gname,' ' ,l.lname) as leader, n.id as seeker_id, concat(n.gname,' ',n.lname) as seeker, ifnull((select group_concat(' ',skill) from person_skills where person_id=l.id and ((skill) in (select ps2.skill as skills from person_skills as ps2 where ps2.person_id=n.id)) group by l.id),'<div style=\'color: #DC143C\'>no matching</div>') as skills from connections as conn left join person as l on l.id=conn.leader_id left join person as n on n.id=conn.seeker_id where conn.status > 0 limit " . $page*$app . "," . $app; //. $start . "," . $app;
//		return $conn_query;
		$selection = $db->DoDBQueryEx($conn_query);
		if (!$selection) die('error while retrieving connections data' . mysql_error());
		$rtotal = $db->DoDBQuery("SELECT FOUND_ROWS() as cnt");
		$rtotal = $rtotal->cnt;
		$count = $db->GetDBQueryRowCount($selection);
		if ($count) {
			$tb_cont ="";
			//$p = 0;
			for ($i = 0; $i < $count; $i++){
				$row = $db->GetDBQueryRowEx($i,$selection);
				$tb_cont .= "<tbody id='cb" . $row['conn_id'] . "'>";
				$tb_cont .= "<tr><td><a class='tips' href='fragment.php?id=" . $row['leader_id'] . "&t=1' rel='fragment.php?id=" . $row['leader_id'] . "&t=1' title='" . $row['leader'] . "'>" . $row['leader'] . "</a></td><td><=></td><td><a class='tips' href='fragment.php?id=" . $row['seeker_id'] . "&t=2' rel='fragment.php?id=" . $row['seeker_id'] . "&t=2' title='" . $row['seeker'] . "'>" . $row['seeker'] . "</td><td><a href='#' title='remove connection' id='conn_remove'>[X]</a></td></tr>";//<input type='hidden' id='conn" . $p . "_id' value='" . $row['conn_id'] . "'></td>";
				$tb_cont .= "<tr class='sep_row1'><td colspan='4'></td></tr>";
				$out1 = "";
				$out2 = "";
				if ($row['letter_date']) $out1 = "<br>letter saved " . date("m.d.Y",$row['letter_date']);
				if ($row['intro_date']) $out2 = "<br>letter sent " . date("m.d.Y",$row['intro_date']);				
				if ($row['letter_stat'] == 3){
					$tb_cont .= "<tr class='status_row'><td>status</td><td></td><td>waiting introduction | <a href='#' rel='intro.php?conn_id=" . $row['conn_id'] . "' title='Introduction letter' class='intro_lnk'>introduce</a></td><td></td></tr>";
				}elseif ($row['letter_stat'] == 2){
					$tb_cont .= "<tr class='status_row'><td>status</td><td></td><td>waiting introduction | <a href='#' rel='intro.php?conn_id=" . $row['conn_id'] . "' title='Introduction letter' class='intro_lnk'>introduce</a>" . $out1 . "</td><td></td></tr>";
				}elseif ($row['letter_stat'] == 1){
					$tb_cont .= "<tr class='status_row'><td>status</td><td></td><td>introduced | <a href='#' rel='intro.php?conn_id=" . $row['conn_id'] . "' title='Introduction letter' class='intro_lnk'>letter</a>" . $out2 . "</td><td></td></tr>";
				}elseif ($row['letter_stat'] == 0){
					$tb_cont .= "<tr class='status_row'><td>status</td><td></td><td>waiting introduction | <a href='#' rel='intro.php?conn_id=" . $row['conn_id'] . "' title='Introduction letter' class='intro_lnk'>introduce</a><br><div style='color: #DC143C'>error sending letter</div></td><td></td></tr>";
				}
				$tb_cont .= "<tr class='status_row'><td>matching</td><td></td><td class='code_text'>" . $row['skills'] . "</td><td></td></tr>";
				$tb_cont .= "<tr class='sep_row2'><td colspan='4'></td></tr>";
				$tb_cont .= "</tbody>";
				//$p++;
			}				
			$res .= $tb_cont;
		}else $res .= "<tr><td colspan='2'>No connections found.<br><a href=''>Connect two people...</a></td>";
		$res .= "</table>";
		//$res .= "<div class='pages'>" . get_pglinks(1,$rtotal,$app,1) . "</div>";
		$res .= "<div class='pages'>" . get_pglinks2($page,$rtotal,$app,$linkspp) . "</div></div>";
		return $res;
	}
	
	//include ('ln_library.php');
	switch ($action) {
		case 'view':{
			//die($_POST['page']);
			if (empty($_POST['page'])) $page = 0;
			else $page = $_POST['page'] - 1;
			echo get_connlist($db,$page,$amountpp,$linkspp);
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
			$col = array(1=>'leader_id=' . $_POST['id'] . ' and seeker_id=ps.person_id',2=>'leader_id=ps.person_id and seeker_id=' . $_POST['id']);
			$match = "select distinct(ps.person_id) as person_id,concat(p.gname,' ',p.lname) as person_name, ifnull((select id from connections where status>0 and " . $col[$_POST['ptype']] . "),0) as connected from person_skills as ps left join person as p on p.id=ps.person_id where ps.ptype=" . $ptype . " and ps.status > 0 and (ps.skill) in (select ps2.skill as skills from person_skills as ps2 where ps2.person_id = " . $_POST['id'] . ")";
//			echo $match;
//			exit;
			$selection = $db->DoDBQueryEx($match);
			if (!$selection) die('error while retrieving connections data');
			$count = $db->GetDBQueryRowCount();
			if ($count) {
				$t = 1;
				$tb_cont ="<table width='100%'>";
				for ($i = 0; $i < $count; $i++){
					$row = $db->GetDBQueryRowEx($i);
					$ch ='';
					$jn = '';
					if ($row['connected']) {$dis = 'disabled'; $jn = ' (joined)';}
					else {
						$dis = '';
						if ($t) {$ch = 'checked'; $t = 0;}
					}
					$tb_cont .= "<tr><td><input type='radio' name='matched_p' value='" . $row['person_id'] . "'id='m_" . $row['person_id'] . "' " . $ch . " " . $dis . "> " . $row['person_name'] . $jn . "</td></tr>";
				}				
				echo $tb_cont,"</table>";
			}else echo "No matches found";
			break;
		}case 'join':{
			if ($_POST['ptype'] == 1) {$leader_id = $_POST['id']; $seeker_id = $_POST['mid'];}
			else {$leader_id = $_POST['mid']; $seeker_id = $_POST['id'];}
			$join_q = "insert into connections (leader_id,seeker_id) values(" . $leader_id . "," . $seeker_id . ")";
			echo $join_q;
			if (!$db->DoDBQueryEx($join_q)) die('error creating new connection<br>' . mysql_error());
			echo get_connlist($db,0,$amountpp,$linkspp);
			break;
		}case 'remove':{
			$remove_q = "update connections set status=0 where id=" . $_POST['conn_id'];
			if (!$db->DoDBQueryEx($remove_q)) die('error deleting connection');
			//echo get_connlist($db);
			break;
		}case 'letsave' or 'letss':{
			$lquery = "update connections as con set con.letter_subj='" . addslashes($_POST['subject']) . "', con.letter='" . addslashes($_POST['letter']) . "', letter_stat=2, letter_date=NOW() where con.id=" . $_POST['conn_id'];
			if (!$db->DoDBQueryEx($lquery)) die('error saving letter');
			if ($action == 'letsave') echo "&nbsp;saved";
			else {
				$to = 'vvcslbogdan@gmail.coms'; // $_POST['sendto'];
				$subject = $_POST['subject'];
				$message = $_POST['letter'];
				$headers = 'From: brian@carrborocoworking.com' . "\r\n" .
					'Reply-To: brian@carrborocoworking.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();

				$res = mail($to, $subject, $message, $headers);
				
				$str1 = "letter_stat=" . $res;
				if ($res){
					$str1 .= ", intro_date=NOW()"; 
					$str2 = "&nbsp;saved and sent";//accepted to delivery;
				}else $str2 = "&nbsp;error sending letter";
				$resq = "update connections set " . $str1;
				if (!$db->DoDBQueryEx($resq)) die('error updating letter status');
				echo $str2;
			}
			break;
		}
	}
?>