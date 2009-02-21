<?php
	$good = array(1,2,3);
	if (!in_array($_POST['fid'],$good)) die ('bad filter id');
	if (empty($_POST['action'])) die ('no filter action');
	
	include('ln_library.php');
	switch ($_POST['action']){
		case 'f_show': {
			$db = new DBWrap();
			$sk_q1 = "select group_concat(s.skill) as skills from skills as s group by '' union select f.filter_str as filter_str from filters as f where f.id=" . $_POST['fid'];
			//die($sk_q1);
			$selection = $db->DoDBQueryEx($sk_q1);
			if (!$selection) die('error fetching skills for filter');
			$count = $db->GetDBQueryRowCount();
			$sk = "<div>Select skills for viewing connections (none=nofilter)</div>";
			if ($count == 0) {
				$sk .= "<table class='sinfo'><tr><td>no skills were found in databse</td></tr></table>";
			}else{
				$row = $db->GetDBQueryRowEx(0);
				$all_skills = explode(',',$row['skills']);
				$row = $db->GetDBQueryRowEx(1);
				$filt_skills = '';
				$sb = strpos($row['skills'],'sk=>');
				if ($sb === false) $filt_skills = '';
				else{
					$se = strpos($row['skills'],';',$sb+5); 
					if ($se > 0) $filtr_str = substr($row['skills'],$sb+5,$se-$sb-6); //    sk=>(    5 symbols
				}
				$filtr_skills = explode(',',$filtr_str);
				$sk .= print_skills(3,1,$all_skills,$filtr_skills);
			}
			echo $sk;
			break;
		}case 'f_save':{
			break;
		}
	}
?>