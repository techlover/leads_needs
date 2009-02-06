<?php
	if (empty($_POST['action'])) $action = 'new';
	else $action = $_POST['action'];
	
	$lead = '';
	$need = '';
	if ($_POST['tp'] > 0) { $need = 'checked'; $tbname = 'demander'; $pers_tp = 1;}
	else { $lead = 'checked'; $tbname = 'leader'; $pers_tp = 0;}
	
	$sk = "";
	$bt = "";
	$hid="";
	
	include('DBWrap.php');
	$db = new DBWrap();
	
	switch ($action){
		case ('new'): {			
			$query = "select skill from skills order by skill asc";
			$selection = $db->DoDBQueryEx($query);
			$count = $db->GetDBQueryRowCount();
			if ($count == 0) {
				$sk = "<tr><td>no skills were found in databse</td></tr>";
			}else{
				$t = 1;
				$sk ="";
				for($i = 0; $i < $count; $i++){
					$row = $db->GetDBQueryRowEx($i);
					if ($t == 1) $sk .= "<tr>";
					$sk .= "<td><input type='checkbox' name='s_" . $row["skill"] . "'> " . $row["skill"] . "</td>";
					if ($t == 3){
						$t = 0;
						$sk .= "</tr>";
					}
					$t++;
				}
			}
			list($gname,$lname,$address,$zip,$phone,$email,$url) = array();
			$bt = "<input type=\"submit\" value=\"Add contact\" id='cont_add'> <input type=\"reset\" value=\"Clear form\"> <input type=\"button\" value=\"Cancel\">";
			$hid = "";
			break;
		}case 'edit': {
			if (empty($_POST['id'])) die('no id passed');
			else $id = $_POST['id'];
			
			$query = "select gname,lname,address,zip,phone,email,url from " . $tbname . " where id=" . $id;
			$selection = $db->DoDBQueryEx($query);
			$count = $db->GetDBQueryRowCount();
			if ($count == 0) {
				die ("no person were found in " . $tbname . " with id=" . $id);
			}else{
				list($gname,$lname,$address,$zip,$phone,$email,$url) = $db->GetDBQueryRowEx(0);

				$query = "select group_concat(skill) as skills from " . $tbname . "_skills where person_id=" . $id;
				$selection = $db->DoDBQueryEx($query);
				$row = $db->GetDBQueryRowEx(0);
				$p_skills = explode(',',$row['skills']);
				$query = "select skill from skills order by skill asc";
				$selection = $db->DoDBQueryEx($query);
				$count = $db->GetDBQueryRowCount();
				if ($count == 0) {
					$sk = "<tr><td>no skills were found in databse</td></tr>";
				}else{
					$t = 1;
					$sk ="";
					for($i = 0; $i < $count; $i++){
						$row = $db->GetDBQueryRowEx($i);
						if ($t == 1) $sk .= "<tr>";
						if (in_array($row['skill'],$p_skills)) $ch = 'checked';
						else $ch = '';
						$sk .= "<td><input type='checkbox' name='s_" . $row["skill"] . "'" . $ch . "> " . $row["skill"] . "</td>";
						if ($t == 3){
							$t = 0;
							$sk .= "</tr>";
						}
						$t++;
					}
				}
				$bt = "<input type=\"button\" value=\"Save\" id='cont_save'> <input type=\"reset\" value=\"Clear form\"> <input type=\"button\" value=\"Cancel\">";
				$hid = "<input type='hidden' id='person_id' value=" . $id . "><input type='hidden' id='person_tp' value='" . $pers_tp . "'>";
			}
			break;
		}case 'save': {
			//in future mode=0: new;
			//			mode=1: save;
		
			//foreach ($_POST as $key=>$value) echo $key," = ",$value,"<br>";
			//exit;
			
			if (empty($_POST['id'])) $id = 0;
			else $id = $_POST['id'];

			if ($id > 0) $m_query = "update " . $tbname . " set status=1,gname='" . addslashes($_POST['gname']) . "',lname='" . addslashes($_POST['lname']) . "',address='" . addslashes($_POST['address']) ."',zip='" . $_POST['zip'] . "',phone='" . addslashes($_POST['phone']) ."',email='" . addslashes($_POST['email']) ."',url='" . addslashes($_POST['url']) ."' where id=" . $id;
			else $m_query = "insert into " . $tbname . " (status,gname,lname,address,zip,phone,email,url) values(1,'" . addslashes($_POST['gname']) ."','" . addslashes($_POST['lname']) ."','" . addslashes($_POST['address']) ."'," . number_format($_POST['zip']) .",'" . addslashes($_POST['phone']) ."','" . addslashes($_POST['email']) ."','" . addslashes($_POST['url']) ."')";
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
				$del_sk = "delete from " . $tbname . "_skills where person_id=" . $id;
				$db->DoDBQueryEx($del_sk) or die('error in while deleting skills');		
			}
			
			$sk_query = "";
			foreach($_POST as $key=>$value){
				if (stripos($key,'_') === 1) 
					$sk_query .= "(" . $id .",'" . substr(addslashes($key),2) . "'),";
			}

			$len = strlen($sk_query);
			if ($len > 0) {
				$sk_query = substr($sk_query,0,$len - 1);
				$sk_query = "insert into " . $tbname . "_skills (person_id, skill) values " . $sk_query;
				$db->DoDBQueryEx($sk_query) or die ('error in query 2');
			}

			// now show what exactly we saved in contact
			$ch_query = "select gname,lname,address,zip,phone,email,url,(select group_concat(skill) from " . $tbname . "_skills where person_id = " . $id . " group by person_id) as skills from " . $tbname . " where id = " . $id;
			$selection = $db->DoDBQueryEx($ch_query);
			if (!$selection) die ('database error while retrieving saved data');
			
			$count = $db->GetDBQueryRowCount();
			if ($count)	$row = $db->GetDBQueryRowEx(0);
			else die ("contact with id=" . $id . " not found");
					
			echo "<h3>Contact saved successfuly</h3>",
				"<div class='separator'>Personal information</div>",
				"<table cellspacing='3'>",
				"<tr><td>Given name</td><td>",$row['gname'],"</td></tr>",
				"<tr><td>Last name</td><td>",$row['lname'],"</td></tr>",
				"<tr><td>Address</td><td>",$row['address'],"</td></tr>",
				"<tr><td>Zip</td><td>",$row['zip'],"</td></tr>",
				"<tr><td>Email</td><td>",$row['email'],"</td></tr>",
				"<tr><td>Url</td><td>",$row['url'],"</td></tr></table>",
				"<div class='separator'>Skills</div>",
				"<table cellspacing='3'>";
			$skills = explode(',', $row['skills']);
			$count = count($skills);
			$t = 1;
			$sk ="";
			for($i = 0; $i < $count; $i++){
				if ($t == 1) $sk .= "<tr>";
				$sk .= "<td>" . $skills[$i] . "</td>";
				if ($t == 3){
					$t = 0;
					$sk .= "</tr>";
				}
				$t++;
			}
			
			$bt = "<input type=\"button\" value=\"<-- edit\" id='cont_edit'> <input type=\"button\" value=\"continue -->\"> <input type=\"button\" value=\"disable contact\">";
			$hid = "<input type='hidden' id='person_id' value=" . $id . "><input type='hidden' id='person_tp' value='" . $pers_tp . "'>";
			echo $sk,"</table>",$bt,$hid;
			break;
		}case 'remove': {
			echo 2;
			break;
		}
	}
	
	//list($gname,$lname,$address,$zip,$phone,$email,$url) = new array();
	if ($action != 'save') echo "<h3>Adding personal information</h3><br>",
		"<form action='index.php' method='POST'>",
		"<input type='hidden' name='action' value='cont_new'>",
		"<div class='separator'>Personal data</div>",
		"<table cellpadding='5' cellspacing='3' id='pinfo'>",
		"<tr><td colspan=\"2\">Provider <input type=\"radio\" name=\"ptype\" id=\"provider\" ",$lead," value='0'> &nbsp;&nbsp;Demander <input type=\"radio\" name=\"ptype\" id=\"demander\" value='1'",$need,"></td></tr>",
		"<tr><td>Given Name</td><td><input type=\"text\" size=20 maxlength=40 id=\"gname\" name=\"gname\" value='",$gname,"'></td></tr>",
		"<tr><td>Last Name</td><td><input type=\"text\" size=20 maxlength=40 id=\"lname\" name=\"lname\" value='",$lname,"'></td></tr>",
		"<tr><td>Address</td><td><input type=\"text\" size=40 maxlength=100 id=\"street\" name=\"street\" value='",$address,"'></td></tr>",
		"<tr><td>ZIP</td><td><input type=\"text\" size=5 maxlength=5 id=\"zip\" name=\"zip\" value='",$zip,"'></td></tr>",
		"<tr><td>Phone</td><td><input type=\"text\" size=10 maxlength=15 id=\"phone\" name=\"phone\" value='",$phone,"'></td></tr>",
		"<tr><td>EMail</td><td><input type=\"text\" size=20 maxlength=40 id=\"email\" name=\"email\" value='",$email,"'></td></tr>",
		"<tr><td>URL</td><td><input type=\"text\" size=30 maxlength=60 id=\"url\" name=\"url\" value='",$url,"'></td></tr>",
		"</table>",
		"<div class='separator'>Skills &nbsp;&nbsp;&nbsp;select all | inverse | clear all</div>",
		"<table cellpadding='5' cellspacing='3' id='skill_table'>",
		$sk,
		"</table><br><hr>",
		$bt,$hid,
		"</form>";
	
?>
