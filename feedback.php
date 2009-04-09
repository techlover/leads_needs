<?php
	$fmistake = $fgood = 0;
	if (!class_exists('DBWrap')) include('dbwrap.php');
	$db = new DBWrap();

	function get_broadcast_list($db){
		$query = "select con.id as con_id, con.leader_id, con.seeker_id, con.l_feed, con.n_feed, concat(lead.gname,' ',lead.lname) as leader," .
				" lead.email as leader_email, need.email as seeker_email, concat(need.gname,' ',need.lname) as seeker" .
				" from connections as con" .
				" left join person as lead on lead.id=con.leader_id" .
				" left join person as need on need.id=con.seeker_id" .
				" where con.status>0 and con.letter_stat=2 and ((con.l_feed in (1,3,7)) or (con.n_feed in (1,3,7)))";
		$bselection = $db->DoDbQueryEx($query);
		if (!$bselection) return false;
		else return $bselection;		
	}

	function ask_feedback($db,$cid,$pt,$person_name1,$person_name2,$email,$templ){
		global $fgood, $fmistake;
		// we evaluate string from database to insert real names
		// insted person_name1, and person_name2
		$link_base = 'http://localhost/leads_needs/feedback.php?tp=' . $pt . '&id=' . $cid . '&feed=';
		$link_good = $link_base . 'good';
		$link_pend = $link_base . 'pend';
		$link_bad = $link_base . 'bad';
		$link_more = $link_base . 'more';
		$link_stop = $link_base . 'stop';
		$mess = $templ['text'];
		eval("\$message=\"$mess\";");
		$subj = $templ['subject'];
		eval("\$subj=\"$subj\";");
		
		include_once("mailer/class.phpmailer.php");
		$mail = new PHPMailer();					
		$mail->IsSMTP();
		$mail->SMTPAuth   = true;                   // enable SMTP authentication
		$mail->SMTPSecure = "ssl";                  // sets the prefix to the servier
		$mail->Host       = "smtp.gmail.com";       // sets GMAIL as the SMTP server
		$mail->Port       = 465;                    // set the SMTP port
		$mail->Username   = "vslbogdan@gmail.com";  // GMAIL username
		$mail->Password   = "??????????";        	// GMAIL password
		$mail->From       = $templ['from_email'];	//"vslbogdan@gmail.com";
		$mail->FromName   = $templ['from_name'];
		$mail->Subject    = $subj;
		//$mail->AltBody    = "This is the body when user views in plain text format"; //Text Body
		//$mail->AddReplyTo();
		$mail->AddAddress("vbogdan81@yandex.ru",$person_name1);
		//$mail->AddAddress($email,$person_name1);
		//$mail->WordWrap   = 50; // set word wrap
		$mail->IsHTML(true); // send as HTML
		$mail->MsgHTML($message);
		
		$mail->Send();
		//die($mail->ErrorInfo);
		if (strlen($mail->ErrorInfo)) {
			// need optimization using temporaly table for 1 update query
			// or using mysql case construction
			$fmistake++;
			$val = 7;
		}else {
			$fgood++;
			$val = 1;
		}		
		if ($pt == 1) $pref = 'l_';
		else $pref = 'n_';
		$query = 'update connections set ' . $pref . 'feed=' . $val . ', ' . $pref . 'feed_date=NOW() where id=' . $cid;
		$db->DoDbQueryEx($query);
	}
	
	if ($_POST['fn'] == 'ask_feedbacks'){
		$broadcast = get_broadcast_list($db);
		if (!$broadcast) die('error retrieving broadcast list');
		$bcount = $db->GetDBQueryRowCount($broadcast);
		if ($bcount == 0) die ('broadcast list is empty');
		
		$query = "select from_email, from_name, subject, text from letter_templates where id=2 limit 1";
		//$selection = $db->DoDBQueryEx($query);
		if (!$db->DoDBQueryEx($query)) die('Error retrieving letter template.');
		$count = $db->GetDBQueryRowCount();
		if ($count == 0) die('No feedback letter template found in database.');
		else {
			$templ = $db->GetDBQueryRowEx(0);
			$res = 'sending feedback request to list:<br>';			
			for ($i = 0; $i < $bcount; $i++) {
				$row = $db->GetDBQueryRowEx($i,$broadcast);
				if (($row['l_feed'] == 1) || ($row['l_feed'] == 3) || ($row['l_feed'] == 7))
					ask_feedback($db,$row['con_id'],1,$row['leader'],$row['seeker'],$row['leader_email'],$templ);
					//$res .= $row['leader_id'] . " " . $row['leader'] . " | " . $row['leader_email'] . "<br>";
				if (($row['n_feed'] == 1) || ($row['n_feed'] == 3) || ($row['n_feed'] == 7))
					ask_feedback($db,$row['con_id'],2,$row['seeker'],$row['leader'],$row['seeker_email'],$templ);
					//$res .= $row['seeker_id'] . " " . $row['seeker'] . " | " . $row['seeker_email'] . "<br>";				
			}
			echo 'sent ' . $fgood . ' errors: ' . $fmistake;
/*				$person_name1 = 'YYYYY';
				$person_name2 = 'ZZZZZ';
				$message = $row['text'];
				eval("\$message=\"$message\";");

				include("mailer/class.phpmailer.php");
				$mail = new PHPMailer();					
				$mail->IsSMTP();
				$mail->SMTPAuth   = true;                  // enable SMTP authentication
				$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
				$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
				$mail->Port       = 465;                   // set the SMTP port
				$mail->Username   = "ncncndcjd@gmail.com";  // GMAIL username
				$mail->Password   = "ksdhfksdjfhskd";            // GMAIL password
				$mail->From       = "blabla@gmail.com";
				$mail->FromName   = "Webmaster";
				$mail->Subject    = $row['subject'];
				$mail->AltBody    = "This is the body when user views in plain text format"; //Text Body
				$mail->AddReplyTo("alskdj@yandex.ru","Webmaster23");
				//$mail->AddAddress("skdjfhdsk@yahoo.com","bodya vasya");
				$mail->AddAddress("woeiruowru@yandex.ru","bodya vasya");
				$mail->WordWrap   = 50; // set word wrap
				$mail->IsHTML(true); // send as HTML
				$mail->MsgHTML($message);
				
				if ($mail->Send()) echo "Message has been sent";
				else echo "Mailer Error: " . $mail->ErrorInfo;
			}
*/			
	/*		$to = 'vslbogdan@gmail.com'; // $_POST['sendto'];
			$headers = 'From: wiwiwiwi@yahoo.com' . "\r\n" .
				'Reply-To: hdhhfhfh@yandex.ru' . "\r\n" .
				'MIME-version: 1.0' . "\n\r" .
				'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
				
			//$mail_result = mail($to, 'Dear Vasya', $message, $headers);
			
	/*		return $mail_result;
			/*
			if ($mail_result) {$res = '0#letter saved and sent'; $st = 2;}
			else {$res = '1#failed to send'; $st = 3;}
			
			$query="update connections as con set con.letter_stat=" . $st . " where con.id=" . $conn_id;
			if (!$db->DoDbQueryEx($query)) $res = '1#error updating status';
			*/
		}
	}else{
		$stat = array('good'=>2,'pend'=>3,'bad'=>4,'more'=>5,'stop'=>6);
		$query = "update connections as con set con.l_feed=" . $stat[$_GET['feed']] . ", con.l_feed_date=NOW() where con.id=" . $_GET['id'];
		if (!$db->DoDbQueryEx($query)) echo '1#error updating status<br>' . $query;
		else echo "Thank you for partisipating!";
	}
?>