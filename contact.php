<?php
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

	if (empty($_POST['action'])) $action = 'new';
	else $action = $_POST['action'];
		
	$sk = "";
	$bt = "";
	$hid="";
	$ptype = 1;
	
	include('DBWrap.php');
	$db = new DBWrap();
	
	switch ($action){
		case 'new': {			
			$query = "select group_concat(skill) as skills from skills group by ''";
			$selection = $db->DoDBQueryEx($query);
			$count = $db->GetDBQueryRowCount();
			if ($count == 0) {
				$sk = "<table class='pinfo'><tr><td>no skills were found in databse</td></tr></table>";
			}else{
				$row = $db->GetDBQueryRowEx(0);
				$sk = print_skills(3,1,explode(',',$row['skills']));
			}
			list($gname,$lname,$address,$zip,$phone,$email,$url) = array();
			$bt = "<input type=\"button\" value=\"Add contact\" id='cont_add'> <input type=\"reset\" value=\"Clear form\"> <input type=\"button\" value=\"Cancel\" id='cont_cancel'>";
			$hid = "";
			break;
		}case 'edit': {
			if (empty($_POST['id'])) die('no id passed');
			else $id = $_POST['id'];
			
			$query = "select ptype,gname,lname,address,zip,phone,email,url from person where id=" . $id;
			//echo $query;
			$selection = $db->DoDBQueryEx($query);
			$count = $db->GetDBQueryRowCount();
			if ($count == 0) {
				die ("no person were found with id=" . $id);
			}else{
				list($ptype,$gname,$lname,$address,$zip,$phone,$email,$url) = $db->GetDBQueryRowEx(0);

				$query = "select group_concat(skill) as skills from person_skills where person_id=" . $id;
				$selection = $db->DoDBQueryEx($query);
				$row = $db->GetDBQueryRowEx(0);
				$p_skills = explode(',',$row['skills']);
				$query = "select group_concat(skill) as skills from skills group by ''";
				$selection = $db->DoDBQueryEx($query);
				$count = $db->GetDBQueryRowCount();
				if ($count == 0) {
					$sk = "<table class='sinfo'><tr><td>no skills were found in databse</td></tr></table>";
				}else{
					$row = $db->GetDBQueryRowEx(0);
					$sk = print_skills(3,1,explode(',',$row[0]),$p_skills);
				}
				$bt = "<input type=\"button\" value=\"Save\" id='cont_save'> <input type=\"reset\" value=\"Clear form\"> <input type=\"button\" value=\"Cancel\">";
				$hid = "<input type='hidden' id='person_id' value=" . $id . "><input type='hidden' id='old_ptype' value=" . $ptype . ">";
			}
			break;
		}case 'save': {
			//in future mode=0: new;
			//			mode=1: save;
		
			//foreach ($_POST as $key=>$value) echo $key," = ",$value,"<br>";
			//exit;
			if ($_POST['mode'] > 0) {
				$id = $_POST['id'];
				$m_query = "update person set status=1,ptype=" . $_POST['ptype'] . ",gname='" . addslashes($_POST['gname']) . "',lname='" . addslashes($_POST['lname']) . "',address='" . addslashes($_POST['address']) ."',zip='" . (empty($_POST['zip']) ? 0 : $_POST['zip']) . "',phone='" . addslashes($_POST['phone']) ."',email='" . addslashes($_POST['email']) ."',url='" . addslashes($_POST['url']) ."' where id=" . $id;
			}else{
				$id = 0;
				$m_query = "insert into person (status,ptype,created,gname,lname,address,zip,phone,email,url) values(1," . $_POST['ptype'] . ",NOW(),'" . addslashes($_POST['gname']) ."','" . addslashes($_POST['lname']) ."','" . addslashes($_POST['address']) ."'," . (empty($_POST['zip']) ? 0 : $_POST['zip']) . ",'" . addslashes($_POST['phone']) ."','" . addslashes($_POST['email']) ."','" . addslashes($_POST['url']) ."')";
			}
			//echo $m_query;
			//exit;
			
			$db = new DBWrap();
			$db->DoDBQueryEx($m_query) or die('error in query 1');
			if ($id == 0) $id = $db->GetLastInsId();
			
			if ($_POST['mode'] > 0){
				//here need to be improvement
				//get 2 arrays. 1-current skills, 2-saved skills in database
				//take array_diff(1,2) - skills need to be removed
				//take array_diff(2,1) - skills need to be saved
				//now it is worse
				$del_sk = "delete from person_skills where person_id=" . $id;
				$db->DoDBQueryEx($del_sk) or die('error in while deleting skills');		
			}
			
			$sk_query = "";
			foreach($_POST as $key=>$value){
				if (stripos($key,'_') === 1) {
					//echo "key = ",$key," | mkey = ",addslashes($key),"<br>";
					$sk_query .= "(" . $_POST['ptype'] . "," . $id .",'" . substr(addslashes($key),2) . "'),";
				}
			}

			$len = strlen($sk_query);
			if ($len > 0) {
				$sk_query = substr($sk_query,0,$len - 1);
				$sk_query = "insert into person_skills (ptype,person_id, skill) values " . $sk_query;
				$db->DoDBQueryEx($sk_query) or die ('error in query 2');
			}

			// now show what exactly we saved in contact
			$ch_query = "select ptype,gname,lname,address,zip,phone,email,url,(select group_concat(skill) from person_skills where person_id = " . $id . " group by person_id) as skills from person where id = " . $id;
			$selection = $db->DoDBQueryEx($ch_query);
			if (!$selection) die ('database error while retrieving saved data');
			
			$count = $db->GetDBQueryRowCount();
			if ($count)	$row = $db->GetDBQueryRowEx(0);
			else die ("contact with id=" . $id . " not found");
					
			echo "<h3>Contact saved successfuly</h3>",
				"<div class='separator'>Personal information</div>",
				"<table class='pinfo'>",
				"<tr><td>Given name</td><td>",$row['gname'],"</td></tr>",
				"<tr><td>Last name</td><td>",$row['lname'],"</td></tr>",
				"<tr><td>Address</td><td>",$row['address'],"</td></tr>",
				"<tr><td>Zip</td><td>",($row['zip'] ? $row['zip'] : ''),"</td></tr>",
				"<tr><td>Phone</td><td>",$row['phone'],"</td></tr>",
				"<tr><td>Email</td><td>",$row['email'],"</td></tr>",
				"<tr><td>Url</td><td>",$row['url'],"</td></tr></table>",
				"<div class='separator'>Skills</div>",
				"<table class='sinfo'>";
				
			$skills = explode(',', $row['skills']);
			sort($skills,SORT_STRING);
			$sk = print_skills(3,0,$skills);
			$bt = "<input type=\"button\" value=\"<-- edit\" id='cont_edit'> <input type=\"button\" value=\"continue -->\" id='cont_cancel'> <input type=\"button\" value=\"disable contact\" id='cont_disable'>";
			$hid = "<input type='hidden' id='person_id' value=" . $id . "><input type='hidden' id='old_ptype' value='" . $row['ptype'] . "'>";
			echo $sk,"</table><br>",$bt,$hid;
			break;
		}case 'disable': {
			//$query = "update person as p, person_skills as ps set p.status=0, ps.status=0 where p.id=" . $id . " and ps.person_id=" . $id;
			$query1 = "update person set status=0 where id=" . $id;
			$db->DoDBQueryEx($query1) or die('error while disabling contact');
			$query2 = "update person_skills set status=0 where person_id=" . $id;
			$db->DoDBQueryEx($query2) or die('error while disabling contact\'s skills');			
			include('connections.php');
			exit;
		}
	}

	$lead = '';
	$need = '';
	if (($_POST['ptype'] == 2) || ($ptype == 2)) $need = 'checked';
	else $lead = 'checked';
	
	//list($gname,$lname,$address,$zip,$phone,$email,$url) = new array();
	if ($action != 'save') echo "<h3>Adding personal information</h3><br>",
		//"<form action='index.php' method='POST'>",
		"<form>",
		"<input type='hidden' name='action' value='cont_new'>",
		"<div class='separator'>Personal data</div>",
		"<table cellpadding='5' id='pinfo' class='pinfo'>",
		"<tr><td colspan=\"2\">Provider <input type=\"radio\" name=\"pt\" id=\"leader\" ",$lead,"> &nbsp;&nbsp;Demander <input type=\"radio\" name=\"pt\" id=\"demander\" ",$need,"></td></tr>",
		"<tr><td>Given Name<sup class='red'>*</sup></td><td><input type=\"text\" size=20 maxlength=40 id=\"gname\" name=\"gname\" value='",$gname,"'></td></tr>",
		"<tr><td>Last Name<sup class='red'>*</sup></td><td><input type=\"text\" size=20 maxlength=40 id=\"lname\" name=\"lname\" value='",$lname,"'></td></tr>",
		"<tr><td>Address</td><td><input type=\"text\" size=40 maxlength=100 id=\"address\" name=\"address\" value='",$address,"'></td></tr>",
		"<tr><td>ZIP</td><td><input type=\"text\" size=5 maxlength=5 id=\"zip\" name=\"zip\" value='",($zip ? $zip : ''),"'></td></tr>",
		"<tr><td>Phone</td><td><input type=\"text\" size=12 maxlength=12 id=\"phone\" name=\"phone\" value='",$phone,"'></td></tr>",
		"<tr><td>Email<sup class='red'>*</sup></td><td><input type=\"text\" size=30 maxlength=40 id=\"email\" name=\"email\" value='",$email,"'></td></tr>",
		"<tr><td>URL</td><td><input type=\"text\" size=30 maxlength=60 id=\"url\" name=\"url\" value='",$url,"'></td></tr>",
		"</table>",
		"<div class='separator'><div id='ldiv'>Skills</div> &nbsp;&nbsp;&nbsp;<div id='rdiv'>select all | inverse | clear all</div></div>",
		"<table cellpadding='5' id='skill_table' class='sinfo'>",
		$sk,
		"</table><br><hr>",
		$bt,$hid,
		"</form>";
	
?>
