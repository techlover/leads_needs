<?php
	include('ln_library.php');

	if (empty($_POST['action'])) $action = 'new';
	else $action = $_POST['action'];
	
	$sk = "";
	$bt = "";
	$hid="";
	$ptype = 1;
	
//	include('DBWrap.php');
	$db = new DBWrap();
	
	switch ($action){
		case 'new': {			
			$query = "select group_concat(id) as ids,group_concat(skill) as skills from skills group by ''";
			$selection = $db->DoDBQueryEx($query);
			$count = $db->GetDBQueryRowCount();
			if ($count == 0) {
				$sk = "<table class='pinfo'><tr><td>no skills were found in databse</td></tr></table>";
			}else{
				$row = $db->GetDBQueryRowEx(0);
				$sk = print_skills(4,1,$row['ids'],$row['skills']);
			}
			list($gname,$lname,$address,$zip,$phone,$email,$url) = array();
			$bt = "<input type=\"submit\" value=\"Add contact\" id='cont_add'> <input type=\"reset\" value=\"Clear form\"> <input type=\"button\" value=\"Cancel\" id='cont_cancel'>";
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

				$query = "select group_concat(ps.skill_id) as ids from person_skills as ps where ps.person_id=" . $id;
				$selection = $db->DoDBQueryEx($query);
				$row = $db->GetDBQueryRowEx(0);
				$p_skills = $row['ids'];
				$query = "select group_concat(id) as ids,group_concat(skill) as skills from skills group by ''";
				$selection = $db->DoDBQueryEx($query);
				$count = $db->GetDBQueryRowCount();
				if ($count == 0) {
					$sk = "<table class='sinfo'><tr><td>no skills were found in databse</td></tr></table>";
				}else{
					$row = $db->GetDBQueryRowEx(0);
					$sk = print_skills(4,1,$row['ids'],$row['skills'],$p_skills);
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
			$skills = explode(',',$_POST['skills']);
			$c = count($skills);
			$sk_query = "";
			for ($i = 0; $i < $c; $i++){
				$sk_query .= "(" . $_POST['ptype'] . "," . $id .",'" . ($skills[$i] ? $skills[$i] : 0) . "'),";
			}
			/*
			foreach($_POST as $key=>$value){
				if (stripos($key,'_') === 1) {
					//echo "key = ",$key," | mkey = ",addslashes($key),"<br>";
					$sk_query .= "(" . $_POST['ptype'] . "," . $id .",'" . substr(addslashes($key),2) . "'),";
				}
			}
			*/
			$len = strlen($sk_query);
			if ($len > 0) {
				$sk_query = substr($sk_query,0,$len - 1);
				$sk_query = "insert into person_skills (ptype,person_id,skill_id) values " . $sk_query;
				//die($sk_query);
				$db->DoDBQueryEx($sk_query) or die ('error in query 2');
			}

			// now show what exactly we saved in contact
			$ch_query = "select ptype,gname,lname,address,zip,phone,email,url,(select concat(group_concat(ps.skill_id),'/',group_concat(s.skill)) from person_skills as ps left join skills as s on s.id=ps.skill_id where ps.person_id = " . $id . " group by '') as skills from person where id = " . $id;
			//die($ch_query);
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
				
			//$skills = explode(',', $row['skills']);
			$s = explode('/',$row['skills']);
			//sort($skills,SORT_STRING);
			$sk = print_skills(3,0,$s[0],$s[1]);
			$bt = "<input type=\"button\" value=\"<-- edit\" id='cont_edit'> <input type=\"button\" value=\"continue -->\" id='cont_cancel'> <input type=\"button\" value=\"disable contact\" id='cont_disable'>";
			$hid = "<input type='hidden' id='person_id' value=" . $id . "><input type='hidden' id='old_ptype' value='" . $row['ptype'] . "'>";
			echo $sk,"</table><br>",$bt,$hid;
			break;
		}case 'disable': {
			//$query = "update person as p, person_skills as ps set p.status=0, ps.status=0 where p.id=" . $id . " and ps.person_id=" . $id;
			if ($_POST['ptype'] == 1) $s = 'leader_id';
			elseif ($_POST['ptype'] == 2) $s = 'seeker_id';
			else die('wrong person type passed');
			$query = "update person set status=0 where id=" . $id;
			$db->DoDBQueryEx($query) or die('error while disabling contact');
			$query = "update person_skills set status=0 where person_id=" . $id;
			$db->DoDBQueryEx($query) or die('error while disabling contact\'s skills');			
			$query = "update connections set status=0 where " . $s . "=" . $id;
			$db->DoDBQueryEx($query) or die('error while disabling contact\'s skills');			
			include('connections.php');
			exit;
		}case 'upl_cfile':{
			include ('fupload.php');
			if ($_FILES['cont_file']['error'] === UPLOAD_ERR_OK) { 
				$store_file = 'uploads/' . basename($_FILES['cont_file']['name']);
				if (move_uploaded_file($_FILES['cont_file']['tmp_name'],$store_file)) {$res = 0; $mess = 'file uploaded successfuly';}
				$parse = parse_cont_file($store_file);
			}else {
				$res = $_FILES['cont_file']['error'];
				$mess = fuplode_codeToMessage($_FILES['cont_file']['error']);
				$parse = '';
			}
			echo '<script language="javascript" type="text/javascript">',
				'window.top.window.uploadResult(' . $res . ',"' . $mess . '","' . $parse . '");',
				'</script>';			
			break;
		}case 'import_contacts':{
			$ids = '';
			$pts = '';
			foreach ($_POST as $key=>$val) {
				//echo $key . ' => ' . $val . '<br>';
				$tmp = explode('_',$key);
				if ($tmp[0] == 'p') { $ids .= $tmp[1] . ','; $pts .= $_POST['pt_' . $tmp[1]] . ',';}
			}
			$ids = rtrim($ids,',');
			$pts = rtrim($pts,',');
			$query = "insert into person(ptype,created,gname,lname,address,zip,phone,email,url,company) select ELT(FIELD(tmp.id," . $ids . ")," . $pts . "), NOW(), tmp.gname, tmp.lname, tmp.address, tmp.zip, tmp.phone, tmp.email, tmp.url, tmp.company from tmp_persons as tmp where tmp.id in (" . $ids . ")";
			//echo $query;
			if (!$db->DoDBQueryEx($query)) echo 'Could not import contacts!';
			break;
		}
	}

	$lead = '';
	$need = '';
	if (($_POST['ptype'] == 2) || ($ptype == 2)) $need = 'checked';
	else $lead = 'checked';
	
	//list($gname,$lname,$address,$zip,$phone,$email,$url) = new array();
	if (($action != 'save') && ($action != 'import_contacts')) echo "Contacts",//"<div class='separator'>Contacts</div>",
		"<div class='cont_choise'><input type='radio' name='add_cont' id='man_cadd' checked onmouseup=change_cont_choise(this)> add contact manually&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;<input type='radio' name='add_cont' id='upl_cadd' onmouseup=change_cont_choise(this)> import from vCard file</div><br>",
		//"<form action='index.php' method='POST'>",
		"<form id='upl_cform' enctype='multipart/form-data' method='POST' action='contact.php' onsubmit='return uploadFile()' target='upl_fake_target'><p>Please select vCard file to upload</p><input type='hidden' name='MAX_FILE_SIZE' value='20000'><input type='hidden' name='action' value='upl_cfile'><input type='file' name='cont_file' id='cont_file' size='40' accept='text/html'><br><input type='submit' value='View contacts'>&nbsp;&nbsp;&nbsp;<div class='progress' id='upl_fstatus'></div></form><div id='parse_result'></div><iframe id='upl_fake_target' name='upl_fake_target' src='#' style='display: none'></iframe>",
		"<form id='manual_cform' method='POST' action='contact.php' onsubmit='return validate_cont_info()'>",
		"<input type='hidden' name='action' value='cont_new'>",
		"<div class='separator'>Personal data</div>",
		"<table cellpadding='5' id='pinfo' class='pinfo'>",
		"<tr><td colspan=\"2\">Provider <input type=\"radio\" name=\"pt\" id=\"leader\" ",$lead,"> &nbsp;&nbsp;Demander <input type=\"radio\" name=\"pt\" id=\"demander\" ",$need,"></td></tr>",
		"<tr><td>Given Name<sup class='red'>*</sup></td><td><input type=\"text\" size=20 maxlength=40 id=\"gname\" name=\"gname\" value='",$gname,"'></td></tr>",
		"<tr><td>Last Name<sup class='red'>*</sup></td><td><input type=\"text\" size=20 maxlength=40 id=\"lname\" name=\"lname\" value='",$lname,"'></td></tr>",
		"<tr><td>Company</td><td><input type=\"text\" size=20 maxlength=40 id=\"company\" name=\"company\" value='",$company,"'></td></tr>",
		"<tr><td>Address</td><td><input type=\"text\" size=30 maxlength=100 id=\"address\" name=\"address\" value='",$address,"'>&nbsp;&nbsp;",
		"ZIP&nbsp;&nbsp;&nbsp;<input type=\"text\" size=5 maxlength=5 id=\"zip\" name=\"zip\" value='",($zip ? $zip : ''),"' onkeyup='this.value=filterDigitField(this.value)'></td></tr>",
		"<tr><td>Phone</td><td><input type=\"text\" size=12 maxlength=12 id=\"phone\" name=\"phone\" value='",$phone,"' onkeyup='this.value=filterDigitField(this.value)'></td></tr>",
		"<tr><td>Email<sup class='red'>*</sup></td><td><input type=\"text\" size=30 maxlength=40 id=\"email\" name=\"email\" value='",$email,"'></td></tr>",
		"<tr><td>URL</td><td><input type=\"text\" size=30 maxlength=60 id=\"url\" name=\"url\" value='",$url,"'></td></tr>",
		"</table>",
		//"<div class='separator'><div id='ldiv'>Skills</div>&nbsp;&nbsp;&nbsp;<div id='rdiv'>select all | inverse | clear all</div></div>",
		"<div class='separator'>Skills</div>",
		"<table cellpadding='5' id='skill_table' class='sinfo'>",
		$sk,
		"</table><hr>",
		$bt,$hid,
		"</form>";
	
?>
