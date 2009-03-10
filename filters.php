<?php
	$good = array(1,2,3);
	if ($_POST['area'] > 0) $area = $_POST['area'];
	elseif ($_GET['area'] > 0) $area = $_GET['area'];
	else $area = 0;
	if (!in_array($area,$good)) die ('bad filter area');
	
	if (empty($_POST['action'])) $action = 'f_show';
	else $action = $_POST['action'];
	
	include('ln_library.php');
	$db = new DBWrap();
	switch ($action){
		case 'f_show': {
			//$db = new DBWrap();
			$fquery = "select active,fvalue from filters where id=" . $area . " union select '',group_concat(id) from skills";
			//die($sk_q1);
			$selection = $db->DoDBQueryEx($fquery);
			if (!$selection) die('error fetching filter');
			$row = $db->GetDBQueryRowEx(0);
			
			$filter_arr1 = explode('=>',$row['fvalue']);
			$k = explode(';',$filter_arr1[0]);
			$v = explode('#',$filter_arr1[1]);
			$filter = array_combine($k,$v);
			/*
			$sk = '';
			foreach ($filter as $key=>$value){
				$sk .= $key . "=>" . $value . "<br>";
			}
			*/
			if ($row['active']) $act = 'checked';
			else $act = '';
			$lnm = "";
			$nnm = "";
			if ($area == 1) {
				$lnm = "<div>Lead <input type='text' size='20' value='" . $filter['lnm'] . "' id='flname'></div><br>";
			}elseif ($area == 2){
				$nnm = "<div>Need <input type='text' size='20' value='" . $filter['nnm'] . "' id='fnname'></div><br>";
			}else{
				$lnm = "<div>Lead <input type='text' size='20' value='" . $filter['lnm'] . "' id='flname'></div>";
				$nnm = "<div>Need <input type='text' size='20' value='" . $filter['nnm'] . "' id='fnname'></div><br>";
			}
			//if (array_key_exists('lnm',$filter)) $lnm = "<div>Lead <input type='text' size='20' value='" . $filter['lnm'] . "' id='flname'></div><br>";
			//else $lnm = '';
			//if (array_key_exists('nnm',$filter)) $nnm = "<div>Need <input type='text' size='20' value='" . $filter['nnm'] . "' id='fnname'></div><br>";
			//else $nnm = '';
			if ($filter['con'] > 0) $con = 'checked';
			else $con = '';
			if ($filter['joi'] > 0) $joi = 'checked';
			else $joi = '';
			
			$sk .= "<div class='f_block' id='f_block'><div><input type='checkbox' " . $act . " id='fapply'> use filter</div><input type='hidden' value='" . $area . "' id='farea'><br>". $lnm . $nnm .
				"<div><input type='checkbox' " . $con . " id='fintrs'>connected &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<input type='checkbox' " . $joi . " id='fintrn'>joined</div><br>" .
				"<div>Skills</div>";
			
			$l = strlen($filter['sk']);
			if ($l > 2){
				$spart = substr($filter['sk'],1,$l-2); // remove '(' and ')'
			}
			
			$squery = "select group_concat(id) as ids,group_concat(skill) as skills from skills group by ''";
			$selection = $db->DoDBQueryEx($squery);
			$count = $db->GetDBQueryRowCount();
			if ($count == 0) {
				$sk .= "no skills were found in databse";
			}else{
				$row = $db->GetDBQueryRowEx(0);
				$sk .= print_skills(4,1,$row['ids'],$row['skills'],$spart,'edit_skills_tb');
			}
			
			$sk .= "<br><input type='button' value='save' id='filt_save'>";// <input type='button' value='cancel' id='filt_canc'>";
			echo $sk,"</div>";
			break;
		}case 'f_save':{
			$k = '';
			$v = '';
			if ($_POST['apply'] > 0) $act = 1;
			else $act = 0;
			if ($_POST['lname']) { $k .= 'lnm;'; $v .= addslashes($_POST['lname']) . '#'; }
			if ($_POST['nname']) { $k .= 'nnm;'; $v .= addslashes($_POST['nname']) . '#'; }
			if (isset($_POST['intrs'])) { $k .= 'con;'; $v .= $_POST['intrs'] . '#'; }
			if (isset($_POST['intrn'])) { $k .= 'joi;'; $v .= $_POST['intrn'] . '#'; }
			if (isset($_POST['skills'])) { $k .= 'sk;'; $v .= $_POST['skills'] . '#'; }
			
			$l = strlen($k);
			$k = substr($k,0,$l-1);
			$l = strlen($v);
			$v = substr($v,0,$l-1);
			
			$fquery = "update filters set active=" . $act . ", fvalue='" . $k . "=>" . $v ."' where id=" . $area;
			//die($fquery);
			if (!$db->DoDBQueryEx($fquery)) die('error saving filter');
			if (($area == 1) || ($area == 2)) echo get_plist($area,1);
			elseif ($area == 3) echo get_connlist();
			break;
		}
	}
?>