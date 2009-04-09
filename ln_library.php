<?php
	$ptype = $_POST['ptype'];
	$show_summary = $_POST['summary'];
	
	if (!class_exists('DBWrap')) include('dbwrap.php');

	if (isset($_POST['fn'])){
		switch ($_POST['fn']) {
			case 'get_plist':{
				echo get_plist($ptype,$show_summary);
				break;
			}case 'print_sk':{
				echo print_skills($cols,$chbox,$all_skid,$all_skname);
				break;
			}case 'get_lsummary':{
				echo get_lsummary($ptype);
				break;
			}case 'get_connstatus':{
				echo get_connstatus($_POST['conn_id']);
				break;
			}case 'get_letter':{
				echo get_letter($_POST['conn_id']);
				break;
			}case 'save_letter':{
				echo save_letter($_POST['conn_id'],$_POST['subject'],$_POST['let_text'],$_POST['send']);
				break;
			}
		}
	}

// ----------------------------------- functions --------------------------------------------------
	function get_plist($ptype,$show_summary){
	// get list of leads or need, depending on $ptype
		$db = new DBWrap();
		$cls = array('ps','ps');
		if (!isset($_POST['sortby'])) {
			$sort = "select fvalue from filters where id=" . ($ptype+3);
			if (!$db->DoDbQueryEx($sort)) die('error retrieving sort order');
			$row = $db->GetDBQueryRowEx(0);
			list($sortby,$v) = explode(';',$row['fvalue']);
		}else{
			if ($_POST['sortby'] == 'name') {$sortby = 'order by name'; $v = 0;}
			else {$sortby = 'order by p.created desc'; $v = 1;}
			$sort = "update filters set fvalue='" . $sortby . ";" . $v . "' where id=" . ($ptype+3);
			if (!$db->DoDbQueryEx($sort)) die('error saving sort order');
		}
		$cls[$v] = 'ps_act';

		$fquery = "select fvalue from filters where id=" . $ptype . " and active=1";
		$selection = $db->DoDBQueryEx($fquery);
		if (!$selection) die('error fetching filter from database');
		$lnm = "";
		$nnm = "";
		$con = "";
		$joi = "";
		$sk = "";
		if ($db->GetDBQueryRowCount() > 0){
			$row = $db->GetDBQueryRowEx(0);
			$filter_arr1 = explode('=>',$row['fvalue']);
			$k = explode(';',$filter_arr1[0]);
			$v = explode('#',$filter_arr1[1]);
			$filter = array_combine($k,$v);
			
			if (array_key_exists('lnm',$filter)) $lnm = " and (lcase(concat(p.gname,' ',p.lname)) like '%" . strtolower($filter['lnm']) . "%')";
			if (array_key_exists('nnm',$filter)) $nnm = " and (lcase(concat(p.gname,' ',p.lname)) like '%" . strtolower($filter['nnm']) . "%')";
			
			if ($filter['con'] > 0) $con = '(select count(if(c.letter_stat=1,1,NULL)) from connections as c where c.leader_id=p.id)';
			if ($filter['joi'] > 0) $joi = '(select count(if(c.letter_stat!=1,1,NULL)) from connections as c where c.leader_id=p.id)';
			$l1 = strlen($con);
			$l2 = strlen($joi);
			if ($l1 && $l2) $str = " and (" . $con . " or " . $joi . ")";
			elseif($l1 || $l2) $str = " and " . $con . $joi;
			
			if (strlen($filter['sk']) > 2)	$sk = " and (select count(*) from person_skills as ps where ps.status>0 and ps.person_id=p.id and (ps.skill_id in " . $filter['sk'] . ") group by ps.person_id)";
		}

		if ($ptype == 1) $category = 'lead';
		else if ($ptype == 2) $category = 'need';
			else return 'wrong person type passed should be in {1,2}';
		$res = "";//"<div>People with <em>leads</em> on work.</div><div class='list_menu'><table class='left_right' width='100%'><tr><td><a href='' class='add_person'>add</a> | <a href=''>filter</a></td><td>sort <a href='' class='" . $cls[0] . "' value='" . $ptype . "'>name</a> | <a href='' class='" . $cls[1] . "' value='" . $ptype . "'>date</a></td></tr></table></div>".
					//"<div class='scrollable'><ul class='river' id='lpanel'>";

		$query = "select p.id, concat(p.gname,' ',p.lname) as name from person as p where p.status > 0 and p.ptype=" . $ptype . $lnm . $nnm . $sk . $str . " " . $sortby;
		//return $query;
		$selection = $db->DoDBQueryEx($query);
		if (!$selection) die ('database error while retrieving list of persons');
		
		$count = $db->GetDBQueryRowCount();
		if ($count)
			for ($i = 0; $i < $count; $i++){
				$row = $db->GetDBQueryRowEx($i);
				$res .= "<li><a class='tips' href='fragment.php?id=" . $row['id'] . "&t=" . $ptype . "' rel='fragment.php?id=" . $row['id'] . "&t=" . $ptype . "' title='A " . $category . " from " . $row['name'] . "'>" . $row['name'] . "</a><div class='persi'>sdjfhg sjfgsd</div></li>";
			}
		else $res .= "<li>" . $category . "s list is empty</li>";//<li><a class='add_person' href='#'>Add " . $category . "</a></li>";
		$res .= "</ul>";
		if ($show_summary) $res .= "##" . get_lsummary($ptype,$filter) . "";
		
		return $res;
	}

	function get_lsummary($ptype,$filter=''){
		if ($ptype == 1) {$category = 'leader_id'; $cat2 = 'lead';}
		else if ($ptype == 2) {$category = 'seeker_id'; $cat2 = 'need';}
			else return 'wrong person type passed should be in {1,2}';
		$res = '';
		$lnm = "";
		$nnm = "";
		$sk = "";
		$fstr = "filter disabled";
		if ($filter) {
			if (array_key_exists('lnm',$filter)) $lnm = " and (lcase(concat(p.gname,' ',p.lname)) like '%" . strtolower($filter['lnm']) . "%')";
			if (array_key_exists('nnm',$filter)) $nnm = " and (lcase(concat(p.gname,' ',p.lname)) like '%" . strtolower($filter['nnm']) . "%')";

			if ($filter['con'] > 0) $con = '(select count(if(c.letter_stat=1,1,NULL)) from connections as c where c.leader_id=p.id)';
			if ($filter['joi'] > 0) $joi = '(select count(if(c.letter_stat!=1,1,NULL)) from connections as c where c.leader_id=p.id)';
			$l1 = strlen($con);
			$l2 = strlen($joi);
			if ($l1 && $l2) $str = " and (" . $con . " or " . $joi . ")";
			elseif ($l1 || $l2) $str = " and " . $con . $joi;

			if (strlen($filter['sk']) > 2)	$sk = " and (select count(*) from person_skills as ps where ps.status>0 and ps.person_id=p.id and (ps.skill_id in " . $filter['sk'] . ") group by ps.person_id)";
			$fstr = "filter enabled";
		}

		$db = new DBWrap();
		$query ="select ifnull(count(distinct p.id),0) as A, count(c.id) as J,count(if(c.letter_stat=1,1,NULL)) as C" .
				" from person as p left join connections as c on c." . $category . "=p.id and c.status>0 where p.status > 0 and p.ptype=" . $ptype . $lnm . $nnm . $sk . $str .//" group by ''" .
				" union select count(id),'0','0' from person where status>0 and ptype=" . $ptype;
		//return $query;
		$selection = $db->DoDBQueryEx($query);
		if (!$selection) die ('database error while retrieving list summary');
		
		$row1 = $db->GetDBQueryRowEx(0);
		$row2 = $db->GetDBQueryRowEx(1);
		$res .= $cat2 . " <b>" . $row1["A"] . "/" . $row2["A"] . "</b>; &nbsp;" . $fstr . "<br>";
		$row = $db->GetDBQueryRowEx(1);
		$res .= "joins <b>" . $row1["J"] . "</b> | connections <b>" . $row1["C"] . "</b>";
		return $res;
	}

	function print_skills($cols,$chbox,$all_sk_id,$all_sk_name,$pers_sk_id='',$tb_id=''){
		$all_skills_id = explode(',',$all_sk_id);
		$all_skills_name = explode(',',$all_sk_name);
		if ($pers_sk_id) $pers_skills_id = explode(',',$pers_sk_id);

		$count = count($all_skills_id);
		$t = 1;
		$sk = "<table cellspacing='3' class='sinfo' id='" . $tb_id . "'>";
		for($i = 0; $i < $count; $i++){
			if ($t == 1) $sk .= "<tr>";
			$sk .= "<td id='" . $all_skills_id[$i] . "'>";
			if ($chbox) {
				if ($pers_skills_id){
					if (in_array($all_skills_id[$i],$pers_skills_id)) $ch = 'checked';
					else $ch = '';
				}
				$sk .= "<input type='checkbox' id='s_" . $all_skills_id[$i] . "' " . $ch . "> ";
			}
			$sk .= $all_skills_name[$i] . "</td>";
			if ($t == $cols){
				$t = 0;
				$sk .= "</tr>";
			}
			$t++;
		}
		$sk .= "</table>";
		return $sk;
	}
	
	function get_connlist($amountpp=15,$linkspp=5){
		if (empty($_POST['page'])) $page = 0;
		else $page = $_POST['page'] - 1;
		//$amountpp = 3; // amount of connections to show per page
		//$linkspp = 5;  // amount of numbered links in line to show on page exmpl. 1 2 3 4 ...
		$db = new DBWrap();
		$sort = "select id,fvalue from filters where id in (3,6) and active=1";
		if (!$db->DoDBQueryEx($sort)) die ('error retrieving sort and filter data');
		$sortby = "";
		$lnm = "";
		$nnm = "";
		$con = "";
		$joi = "";
		//$sk = "";
		$str = "";
		$cls = array('cs','cs','cs');
		$c = $db->GetDBQueryRowCount();
		for ($i = 0; $i < $c; $i++){
			$row = $db->GetDBQueryRowEx($i);
			if ($row['id'] == 6){
				$sort = explode(';',$row['fvalue']);
				$sortby = $sort[0];
				$cls[$sort[1]] = 'cs_act';
			}
			if ($row['id'] == 3){
				$filter_arr1 = explode('=>',$row['fvalue']);
				$k = explode(';',$filter_arr1[0]);
				$v = explode('#',$filter_arr1[1]);
				$filter = array_combine($k,$v);
				//foreach ($filter as $key=>$value) echo $key . "=>" . $value . "<br>";
				
				if (array_key_exists('lnm',$filter)) $lnm = " and (lcase(concat(l.gname,' ',l.lname)) like '%" . strtolower($filter['lnm']) . "%')";
				if (array_key_exists('nnm',$filter)) $nnm = " and (lcase(concat(n.gname,' ',n.lname)) like '%" . strtolower($filter['nnm']) . "%')";
				
				if ($filter['con'] > 0) $con = "conn.letter_stat=1";
				if ($filter['joi'] > 0) $joi = "conn.letter_stat!=1";
				if ($filter['con'] && $filter['joi']) $str = "";
				elseif($filter['con'] || $filter['joi']) $str = " and " . $con . $joi;
				
				//if (strlen($filter['sk']) > 2)	$sk = " and (select count(*) from person_skills as ps where ps.status>0 and ps.person_id=p.id and (ps.skill_id in " . $filter['sk'] . ") group by ps.person_id)";				
			}
			//echo $row['fvalue'] . "<br>";
		}

		$res = //"<div id='conn_menu'><div id='conn_showlist'>connections</div><div id='conn_showfilt'>filter</div></div>".
			//"<div id='conn_filt' style='display: none'></div>" .
			"<div class='separator'><table width=100% class='left_right'><tr><td>Connections</td><td><a href='' rel='filters.php?area=3' class='filter_lnk' title='Filter for connections'>filter</a> | &nbsp;&nbsp;sort by&nbsp;&nbsp;<a href='' class='" . $cls[0] . "'>lead</a> | <a href='' class='" . $cls[1] . "'>need</a> | <a href='' class='" . $cls[2] . "'>date</a></td></tr></table></div><br>".
			"<div id='conn_cont'>".
		//	"<table width='100%' id='conn_h' cellspacing='0'>".
		//	"<thead id='conn_h'><tr><td>Lead</td><td>Need</td></tr></thead></table><br>".
			"<table width='100%' id='conn' cellspacing='2'>".
			"<col class='l_col'><col class='sep_col'><col class='n_col'><col class='del_col'>";
		//$conn_query = "select SQL_CALC_FOUND_ROWS UNIX_TIMESTAMP(conn.letter_date) as letter_date, UNIX_TIMESTAMP(conn.intro_date) as intro_date, conn.letter_stat as letter_stat, conn.id as conn_id, l.id as leader_id, concat(l.gname,' ' ,l.lname) as leader, n.id as seeker_id, concat(n.gname,' ',n.lname) as seeker, ifnull((select group_concat(' ',s.skill) from person_skills as ps left join skills as s on s.id=ps.skill_id where ps.status>0 and ps.person_id=l.id and ((ps.skill_id) in (select ps2.skill_id as skill_id from person_skills as ps2 where ps2.person_id=n.id)) group by l.id),'<div style=\'color: #DC143C\'>no matching</div>') as skills from connections as conn left join person as l on l.id=conn.leader_id left join person as n on n.id=conn.seeker_id where conn.status>0 and n.status>0 and l.status>0 " . $sortby . " limit " . $page*$app . "," . $app; //. $start . "," . $app;
		//$conn_query = "select SQL_CALC_FOUND_ROWS UNIX_TIMESTAMP(conn.letter_date) as letter_date, UNIX_TIMESTAMP(conn.intro_date) as intro_date, conn.letter_stat as letter_stat, conn.id as conn_id, l.id as leader_id, concat(l.gname,' ' ,l.lname) as leader, n.id as seeker_id, concat(n.gname,' ',n.lname) as seeker, ifnull((select group_concat(' ',s.skill) from person_skills as ps left join skills as s on s.id=ps.skill_id where ps.status>0 and ps.person_id=l.id and ((ps.skill_id) in (select ps2.skill_id as skill_id from person_skills as ps2 where ps2.person_id=n.id)) group by l.id),'<div style=\'color: #DC143C\'>no matching</div>') as skills from connections as conn left join person as l on l.id=conn.leader_id left join person as n on n.id=conn.seeker_id where conn.status>0 and n.status>0 and l.status>0 " . $lnm . $nnm . $str . " " . $sortby . " limit " . $page*$amountpp . "," . $amountpp;
		$conn_query = "select SQL_CALC_FOUND_ROWS conn.id as conn_id, concat(l.gname,' ' ,l.lname) as leader, concat(n.gname,' ',n.lname) as seeker, conn.l_feed, conn.n_feed from connections as conn left join person as l on l.id=conn.leader_id left join person as n on n.id=conn.seeker_id where conn.status>0 and n.status>0 and l.status>0 " . $lnm . $nnm . $str . " " . $sortby . " limit " . $page*$amountpp . "," . $amountpp;
		//return $conn_query;
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
				//$tb_cont .= "<tr><td><a class='tips' href='fragment.php?id=" . $row['leader_id'] . "&t=1' rel='fragment.php?id=" . $row['leader_id'] . "&t=1' title='" . $row['leader'] . "'>" . $row['leader'] . "</a></td><td><=></td><td><a class='tips' href='fragment.php?id=" . $row['seeker_id'] . "&t=2' rel='fragment.php?id=" . $row['seeker_id'] . "&t=2' title='" . $row['seeker'] . "'>" . $row['seeker'] . "</td><td><a href='#' title='remove connection' id='conn_remove'>[X]</a></td></tr>";//<input type='hidden' id='conn" . $p . "_id' value='" . $row['conn_id'] . "'></td>";
				$tb_cont .= "<tr><td><a href=''>" . $row['leader'] . "</a></td><td><div class='col_" . $row['l_feed'] . " rbw_el'></div><=><div class='col_" . $row['n_feed'] . " rbw_el'></div></td><td class='text_align_right'><a href=''>" . $row['seeker'] . "</td><td class='text_align_right'><a href='#' title='remove connection' id='conn_remove'>[X]</a></td></tr>";//<input type='hidden' id='conn" . $p . "_id' value='" . $row['conn_id'] . "'></td>";
			/*	$tb_cont .= "<tr class='sep_row1'><td colspan='4'></td></tr>";
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
			*/	$tb_cont .= "</tbody>";
				//$p++;
			}				
			$res .= $tb_cont;
		}else $res .= "<tr><td colspan='2'>No connections found.<br><a href=''>Connect two people...</a></td>";
		$res .= "</table>";
		//$res .= "<div class='pages'>" . get_pglinks(1,$rtotal,$app,1) . "</div>";
		$res .= "<div class='pages'>" . get_pglinks2($page,$rtotal,$amountpp,$linkspp) . "</div></div>";
		return $res;
	}
	
	function get_connstatus($conn_id){
		if ($conn_id < 1) return('wrong connection id');
		$res = "<table width='100%'>";
		$db = new DBWrap();
		$stat = "select UNIX_TIMESTAMP(con.letter_date) as letter_date, con.letter_stat, con.l_feed, con.n_feed from connections as con where con.id=" . $conn_id;
		
		$selection = $db->DoDBQueryEx($stat);
		if (!$selection) $res .= '<tr><td>database error while retrieving letter data</td></tr>';
		else {
			$row = $db->GetDBQueryRowEx(0);
			$let_stat = array('no letter', 'draft', 'letter sent', 'failed to send letter');
			$c = count($let_stat);
			if ($row['letter_stat'] > $c) $st = '? status';
			else $st = $let_stat[$row['letter_stat']];
			
			//die ('stat=' . $row['letter_stat'] . " --> " . $let_stat[$row['letter_stat']]);
	/*		if ($row['letter_stat'] == 0) $st = 'no letter';
			elseif ($row['letter_stat'] == 1) $st = 'draft';
			elseif ($row['letter_stat'] == 2) $st = 'letter sent';
			elseif ($row['letter_stat'] == 3) $st = 'failed to send letter';
			else $st = '? status';
	*/		
			$feed_stat = array('draft', 'no feedback', 'good','pending','bad','more info','dont bug','error asking feed');
			$c = count($feed_stat);
			if ($row['l_feed'] > $c) $lf = '? status';
			else $lf = $feed_stat[$row['l_feed']];
			if ($row['n_feed'] > $c) $nf = '? status';
			else $nf = $feed_stat[$row['n_feed']];
			
			$res .= "<tr><td width='130px'>status</td><td width='140px' class='status_text'>" . $st . "</td><td><a href='' id='let_" . $conn_id . "' onclick='javascript: showDialog(\"Loading letter...\"," . $conn_id . "); return false;'>letter</a></td></tr>".
				"<tr><td>matching on</td><td class='code_text' colspan='2'>?, php,.....</td></tr></tr>".
				"<tr><td>feedback from lead</td><td class='status_text'>" . $lf . "</td></tr>".
				"<tr><td>feedback form need</td><td class='status_text'>" . $nf . "</td></tr>";
		}
		$res .= "</table>";
		return $res;
	}

	function get_letter($conn_id){
		if ($conn_id < 1) return('wrong connection id');
		
		$res = "<div class='div_dialog_close' onclick='closeDialog()'>close</div>";
		
		$db = new DBWrap();
		$person = "select concat(l.email,', ',n.email) as emails, concat(l.gname,' ',l.lname) as lname, concat(n.gname,' ',n.lname) as nname, con.letter_subj as subject, con.letter as letter from connections as con left join person as l on l.id=con.leader_id left join person as n on n.id=con.seeker_id where con.id=" . $conn_id;
		
		$selection = $db->DoDBQueryEx($person);
		if (!$selection) $res .= 'database error while retrieving persons emails data';
		else {
			$row = $db->GetDBQueryRowEx(0);

			$res .= //"<input type='hidden' id='conn_id' value='" . $conn_id . "'>".
				"<div class='let_dialheader'>introducing " . $row['nname'] . " to " . $row['lname'] . "</div><br>".
				"<table class='let_table'><col width='70px'><col>".
				"<tr><td>From</td><td><input type='text' size='40' value='brian@carrborocoworking.com' disabled></td></tr>".
				"<tr><td>To</td><td><input type='text' size='40' value='" . $row['emails'] . "' id='sendto' disabled></td></tr>".
				"<tr><td>Subject</td><td><input type='text' size='40' value='" . (empty($row['subject']) ? 'Introduction letter from Brian Russell 2' : $row['subject']) . "' id='subject'></td></tr>".
				"<tr><td>Letter</td><td><textarea rows='12' cols='55' id='letter' wrap='hard'>" . $row['letter'] . "</textarea></td></tr>".
				"<tr><td>&nbsp;</td><td><input type='button' value='Save draft' id='let_save' onclick='letter_save(" . $conn_id . ",0)'><input type='button' value='Send' id='let_ss' onclick='letter_save(" . $conn_id . ",1)'>&nbsp;&nbsp;&nbsp;<div class='progress' id='let_progr'></div></td></tr>".
				"</table>";
		}
		return $res;
	}

	function save_letter($conn_id,$let_subject,$let_text,$send){
		if ($conn_id < 1) return('wrong connection id');
		$let_subject = addslashes($let_subject);
		$let_text = addslashes($let_text);
		
		$db = new DBWrap();
		$query = "update connections as con set con.letter_date=NOW(), con.letter_subj='" . $let_subject . "', con.letter='" . $let_text . "', con.letter_stat=1 where con.id=" . $conn_id;
		
		$selection = $db->DoDBQueryEx($query);
		if (!$selection) return '1#error saving letter';
		else $res = '0#letter saved';
		
		if ($send > 0) {
			$to = 'vslbogdan@gmail.com'; // $_POST['sendto'];
			$headers = 'From: vslbogdan@gmail.com' . "\r\n" .
				'Reply-To: vslbogdan@gmail.com' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();

			$mail_result = mail($to, $let_subject, $let_text, $headers);
			
			if ($mail_result) {$res = '0#letter saved and sent'; $st = 2;}
			else {$res = '1#failed to send'; $st = 3;}
			
			$query="update connections as con set con.letter_stat=" . $st . " where con.id=" . $conn_id;
			if (!$db->DoDbQueryEx($query)) $res = '1#error updating status';
		}
		return $res;
	}
	
	function get_pglinks2($active_pg,$total_rows,$rows_per_page,$links_toshow){
		if (($total_rows <= 0) or ($rows_per_page <= 0)) return "incorect arguments";
		if ($active_pg > $total_rows) $active_pg = 0;
		$ab = "<a href='' class='xpage'>";
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
			if ($i == $active_pg) $res .= "<b id='curr_pg'>" . ($i+1) . "</b> ";
			else $res .= $ab . ($i+1) . "</a> ";
		}
		$res .= "&nbsp;&nbsp;&nbsp;(of " .$max_page . ")";
		return $res;
	}
	
?>

