<?php
//	if (!class_exists('DBWrap')) include('dbwrap.php');
	include('ln_library.php');
	$db = new DBWrap();
	
	$amountpp = 3; // amount of connections to show per page
	$linkspp = 5;  // amount of numbered links in line to show on page exmpl. 1 2 3 4 ...
	
	if (empty($_POST['action']))
		if (empty($_GET['action'])) $action = 'view';
		else $action = $_GET['action'];
	else $action = $_POST['action'];
	
	switch ($action) {
		case 'sort':{
			if ($_POST['sortby'] == 'date') {$sortby = 'conn.id desc'; $v = 2;}
			elseif ($_POST['sortby'] == 'lead') {$sortby = 'leader'; $v = 0;}
			elseif ($_POST['sortby'] == 'need') {$sortby = 'seeker'; $v = 1;}
			else die('wrong sort by parameter');
			$sort = "update filters set fvalue='order by " . $sortby . ";" . $v . "' where id=6";
			if (!$db->DoDbQueryEx($sort)) die('error saving sort order');
			$action = 'view';
			//break;
		}case 'view':{
			//die($_POST['page']);
			//if (empty($_POST['page'])) $page = 0;
			//else $page = $_POST['page'] - 1;
			echo get_connlist($amountpp,$linkspp);
			break;
		}case 'add':{
			echo "Jim";
			break;
		}case 'match':{
			if ($_POST['ptype'] == 1) $ptype = 2;
			else if ($_POST['ptype'] == 2) $ptype = 1;
				else die('Error. wrong person type');
			$col = array(1=>'leader_id=' . $_POST['id'] . ' and seeker_id=ps.person_id',2=>'leader_id=ps.person_id and seeker_id=' . $_POST['id']);
			$match = "select distinct(ps.person_id) as person_id,concat(p.gname,' ',p.lname) as person_name, ifnull((select id from connections where status>0 and " . $col[$_POST['ptype']] . "),0) as connected from person_skills as ps left join person as p on p.id=ps.person_id where ps.ptype=" . $ptype . " and ps.status > 0 and p.status>0 and (ps.skill_id) in (select ps2.skill_id as skills from person_skills as ps2 where ps2.person_id = " . $_POST['id'] . ")";
			//die($match);
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
			echo get_connlist($amountpp,$linkspp);
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