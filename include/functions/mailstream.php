<?
function mailstream($db,$GSP_INBOX_DB_ID,$GSP_INBOX_DB_PATH,$GSP_INBOX_DB_TABLE)
{
	$GSP_INBOX_DB_TABLE_ATT = $GSP_INBOX_DB_TABLE . "_attachment";
	$MAILSERVER_NAME=return_query($db,"select mailbox_server from config_inbox where id = $GSP_INBOX_DB_ID");
	$MAILSERVER_PORT=return_query($db,"select mailbox_server_port from config_inbox where id = $GSP_INBOX_DB_ID");
	$MAILSERVER_READONLY_FLAG=return_query($db,"select mailbox_readonly_f from config_inbox where id = $GSP_INBOX_DB_ID");
	$MAILSERVER_PROTOCOL_ID=return_query($db,"select mailbox_server_protocol_id from config_inbox where id = $GSP_INBOX_DB_ID");
	$MAILSERVER_PROTOCOL_CODE=return_query($db,"select code from config_server_protocol where id = $MAILSERVER_PROTOCOL_ID");
	if ($MAILSERVER_READONLY_FLAG == "1")
	{
		$MAILSERVER_READONLY_CODE="/readonly";
	}
	$MAILSERVER_STRING="{" . $MAILSERVER_NAME . ":" . $MAILSERVER_PORT . "/" . $MAILSERVER_PROTOCOL_CODE . $MAILSERVER_READONLY_CODE . "}";
	$MAILSERVER_USERCODE=return_query($db,"select user_code from config_inbox where id = $GSP_INBOX_DB_ID");
	$MAILSERVER_USERPASS=return_query($db,"select password from config_inbox where id = $GSP_INBOX_DB_ID");
	
	$mbox=imap_open($MAILSERVER_STRING,$MAILSERVER_USERCODE,$MAILSERVER_USERPASS);

	try
	{
        	if (! @include_once( 'Mail/mimeDecode.php' ))
        	{
                	throw new Exception ();
        	}
	}
	catch(Exception $e)
	{
        	set_include_path(get_include_path() . PATH_SEPARATOR . "ext/pear");
        	require_once 'Mail/mimeDecode.php';
	}

	$mimeParams = array();
	$mimeParams['decode_headers'] = true;
	$mimeParams['include_bodies'] = true;
	$mimeParams['decode_bodies'] = true;

	$count=imap_num_msg($mbox);
	$headers=imap_headers($mbox);

	$newcnt=0;
	$db_inbox = new PDO("sqlite:$GSP_INBOX_DB_PATH");

	// exit if last insert in db < 60 seconds
	$last_insert=return_query($db_inbox,"SELECT julianday ( datetime ( 'now' , 'localtime' ) ) * 86400 - julianday ( MAX ( creation_d ) ) * 86400 from inbox");
	if($last_insert != "" && $last_insert < 60)
	{
		return "Waiting...";
		exit;
	};

	// delete all incomplete mails who have partially created before
	return_query($db_inbox,"delete from $GSP_INBOX_DB_TABLE where complete_f = 0");

	// look into mail server's inbox
	for($x=0; $x < count($headers); $x++)
	{
	   $idx=($x+1);

	   // we take uid for origin code
	   $origin_code=imap_uid($mbox,$idx);
	   if ($origin_code == "")
	   {
		// we need additional info

		$header = imap_fetchheader($mbox, $idx, FT_PREFETCHTEXT);
		$body = imap_body($mbox, $idx, FT_PEEK);
		$mimeParams['input'] = $header.$body;
		$message = Mail_mimeDecode::decode($mimeParams);

		// we take message-id for origin code if uid is not available

	  	$origin_code=trim($message->headers["message-id"]);
		if ($origin_code == "")
		{
			// we take md5 sum of header if uid and message-id are both unavailable
			$origin_code=md5($header);
		}
	   }

	   // insert mail in database only if the mail is new

	   if (return_query($db_inbox,"select id from $GSP_INBOX_DB_TABLE where origin_code = '$origin_code'") == "")
	   {
		$newcnt++;

		$utf8=0;

	   	$header = imap_fetchheader($mbox, $idx, FT_PREFETCHTEXT);

	   	$body = imap_body($mbox, $idx, FT_PEEK);
	   	$mimeParams['input'] = $header.$body;
	   	$message = Mail_mimeDecode::decode($mimeParams);

		$overview=imap_fetch_overview($mbox,$idx,0);
		$mail_size=$overview[0]->size;

		$headerinfo=imap_headerinfo($mbox,$idx);

		$mail_date=date("Y-m-d H:i:s", $headerinfo->udate);

		if ($message->headers["sender"] != "")
		{
			$mail_from=addslashes(htmlspecialchars($message->headers["sender"]));
		}
		else
		{
			$mail_from=addslashes(htmlspecialchars($message->headers["from"]));
		}
	   	$mail_to=htmlspecialchars($message->headers["to"]);
	   	$mail_cc=htmlspecialchars($message->headers["cc"]);
	   	$mail_subject=htmlspecialchars($message->headers["subject"]);

	   	$query="INSERT or ignore into $GSP_INBOX_DB_TABLE (config_id, origin_code, mail_date, mail_from, mail_to, mail_cc, mail_subject, mail_size) values (:CONFIG_ID,:ORIGIN_CODE,:MAIL_DATE,:MAIL_FROM,:MAIL_TO,:MAIL_CC,:MAIL_SUBJECT,:MAIL_SIZE)";
           	$row = $db_inbox->prepare($query);
           	$row->bindParam(':CONFIG_ID',    $GSP_INBOX_DB_ID, PDO::PARAM_INT);
           	$row->bindParam(':ORIGIN_CODE',  $origin_code,     PDO::PARAM_STR);
	   	$row->bindParam(':MAIL_DATE',    $mail_date,       PDO::PARAM_STR);
	   	$row->bindParam(':MAIL_FROM',    $mail_from,       PDO::PARAM_STR);
	   	$row->bindParam(':MAIL_TO',      $mail_to,         PDO::PARAM_STR);
	   	$row->bindParam(':MAIL_CC',      $mail_cc,         PDO::PARAM_STR);
	   	$row->bindParam(':MAIL_SUBJECT', $mail_subject,    PDO::PARAM_STR);
		$row->bindParam(':MAIL_SIZE',    $mail_size,       PDO::PARAM_INT);
           	$row->execute();
           	sqlerror($db_inbox,$query);

		$lastid=return_query($db_inbox,"select id from $GSP_INBOX_DB_TABLE where origin_code = '$origin_code'");

		if($message->ctype_primary == "multipart")
		{
			for ($partcnt = 0; $partcnt < count($message->parts); $partcnt++)
			{
				$part_ctype_primary=$message->parts[$partcnt]->ctype_primary;
				$part_ctype_secondary=$message->parts[$partcnt]->ctype_secondary;
				
				$query="INSERT or ignore into $GSP_INBOX_DB_TABLE_ATT (mail_id, rank_n, type, subtype, name, data) values (:MAIL_ID,:RANK,:TYPE,:SUBTYPE,:NAME,:DATA)";
				$part_name="";
				if($part_ctype_primary == "application" || $part_ctype_primary == "image")
				{
					$part_name=$message->parts[$partcnt]->d_parameters[filename];
				
					if($part_name == "")
					{
						$part_name = "attach" . $lastid . "_" . $partcnt;	
					}
					if($part_ctype_primary == "image")
					{
						$part_pict_search[] = "@\(Embedded image moved to file: $part_name\)@si";
						$part_pict_replace[] = "<img src='index.php?MODL=GETA&OBJECT=mail&ID=$lastid&FILENAME=$part_name'/>";
					}
					else
					{
						switch ($part_ctype_secondary)
						{
							case "msexcel": $part_pict_ext="att_xls.png"; break;
							case "msword" : $part_pict_ext="att_doc.png"; break;
							default : $part_pict_ext="unknown.gif"; break;
						}
						$part_pict_search[] = "@\(See attached file: $part_name\)@si";
						$part_pict_replace[] = "<a href='index.php?MODL=GETA&OBJECT=mail&TYPE=$part_ctype_secondary&ID=$lastid&FILENAME=$part_name' target='GSP_ATT'><img src='pict/$part_pict_ext' border=0/></a>";
					}
					$row = $db_inbox->prepare($query);
					$row->bindParam(':MAIL_ID',      $lastid,               PDO::PARAM_INT);
					$row->bindParam(':RANK',         $partcnt,              PDO::PARAM_STR);
					$row->bindParam(':TYPE',         $part_ctype_primary,   PDO::PARAM_STR);
					$row->bindParam(':SUBTYPE',      $part_ctype_secondary, PDO::PARAM_STR);
					$row->bindParam(':NAME',         $part_name,            PDO::PARAM_STR);
					$row->bindParam(':DATA',         $message->parts[$partcnt]->body, PDO::PARAM_LOB);
					$row->execute();
					sqlerror($db_inbox,$query);
				}
			}
		}

		$mail_body="ERREUR : PAS DE BODY TROUVE";
		if($message->ctype_primary == "multipart")
		{
			for ($partcnt = 0; $partcnt < count($message->parts); $partcnt++)
			{
				$part_ctype_primary=$message->parts[$partcnt]->ctype_primary;
				$part_ctype_secondary=$message->parts[$partcnt]->ctype_secondary;
				$part_ctype_charset=$message->parts[$partcnt]->ctype_parameters[charset];
				
				$query="INSERT or ignore into $GSP_INBOX_DB_TABLE_ATT (mail_id, rank_n, type, subtype, name, data) values (:MAIL_ID,:RANK,:TYPE,:SUBTYPE,:NAME,:DATA)";
				$part_name="";
				if($part_ctype_primary == "text")
				{
					if($part_ctype_secondary == "plain")
					{
						$mail_body="<tt>" . str_replace("  ","&nbsp;&nbsp;",nl2br(trim($message->parts[$partcnt]->body))) . "</tt>";
						// decode UTF-8 in body if needed
						if($part_ctype_charset == "UTF-8") {$utf8=1;$mail_body=utf8_decode($mail_body);}
					}
					if($part_ctype_secondary == "html")
					{
						$mail_body_html = $message->parts[$partcnt]->body;
						if($part_pict_search != "") {$mail_body_html = preg_replace($part_pict_search,$part_pict_replace,$mail_body_html);}
						$row = $db_inbox->prepare($query);
						$row->bindParam(':MAIL_ID',      $lastid,               PDO::PARAM_INT);
						$row->bindParam(':RANK',         $partcnt,              PDO::PARAM_STR);
						$row->bindParam(':TYPE',         $part_ctype_primary,   PDO::PARAM_STR);
						$row->bindParam(':SUBTYPE',      $part_ctype_secondary, PDO::PARAM_STR);
						$row->bindParam(':NAME',         $part_name,		PDO::PARAM_STR);
						$row->bindParam(':DATA',	 $mail_body_html,       PDO::PARAM_LOB);
						$row->execute();
						sqlerror($db_inbox,$query);
					}
				}

				// oh no...another multipart...it could be better to coding this with recursive method...

				if($part_ctype_primary == "multipart")
				{
					for ($spartcnt = 0; $partcnt < count($message->parts); $partcnt++)
                        		{
                                		$spart_ctype_primary=$message->parts[$partcnt]->parts[$spartcnt]->ctype_primary;
                                		$spart_ctype_secondary=$message->parts[$partcnt]->parts[$spartcnt]->ctype_secondary;
						$spart_ctype_charset=$message->parts[$partcnt]->parts[$spartcnt]->ctype_parameters[charset];

                                		$query="INSERT or ignore into $GSP_INBOX_DB_TABLE_ATT (mail_id, rank_n, type, subtype, name, data) values (:MAIL_ID,:RANK,:TYPE,:SUBTYPE,:NAME,:DATA)";
                                		$spart_name="";
                                		if($spart_ctype_primary == "text")
                                		{
                                        		if($spart_ctype_secondary == "plain")
                                        		{
                                                		$mail_body="<tt>" . str_replace("  ","&nbsp;&nbsp;",nl2br(trim($message->parts[$partcnt]->parts[$spartcnt]->body))) . "</tt>";
								// decode UTF-8 in body if needed
								if($spart_ctype_charset == "UTF-8") {$utf8=1;$mail_body=utf8_decode($mail_body);}
                                        		}
                                        		if($spart_ctype_secondary == "html")
                                        		{
                                                		$mail_body_html = $message->parts[$partcnt]->parts[$spartcnt]->body;
                                                		if($part_pict_search != "") {$mail_body_html = preg_replace($part_pict_search,$part_pict_replace,$mail_body_html);}
                                                		$row = $db_inbox->prepare($query);
                                                		$row->bindParam(':MAIL_ID',      $lastid,               PDO::PARAM_INT);
                                                		$row->bindParam(':RANK',         $partcnt,              PDO::PARAM_STR);
                                                		$row->bindParam(':TYPE',         $spart_ctype_primary,   PDO::PARAM_STR);
                                                		$row->bindParam(':SUBTYPE',      $spart_ctype_secondary, PDO::PARAM_STR);
                                                		$row->bindParam(':NAME',         $spart_name,            PDO::PARAM_STR);
                                                		$row->bindParam(':DATA',         $mail_body_html,       PDO::PARAM_LOB);
                                                		$row->execute();
                                                		sqlerror($db_inbox,$query);
                                        		}
                                		}
					}	
				}
			}
		}
		else
		{
			$mail_body="<tt>" . str_replace("  ","&nbsp;&nbsp;",nl2br(trim($message->body))) . "</tt>";
		}

		// decode UTF-8 in body if needed
		if($message->ctype_parameters[charset] == "UTF-8") {$utf8=1;$mail_body=utf8_decode($mail_body);}

		if($utf8 == 1)
		{
			$mail_subject=utf8_decode($message->headers["subject"]);
			$query="UPDATE $GSP_INBOX_DB_TABLE set mail_subject = :MAIL_SUBJECT where id = :ID";
			$row = $db_inbox->prepare($query);
			$row->bindParam(':ID',    $lastid,          PDO::PARAM_INT);
			$row->bindParam(':MAIL_SUBJECT', $mail_subject,       PDO::PARAM_STR);
			$row->execute();
			sqlerror($db_inbox,$query);
		}
		if($part_pict_search != "") {$mail_body = preg_replace($part_pict_search,$part_pict_replace,$mail_body);}

		$query="UPDATE $GSP_INBOX_DB_TABLE set mail_body = :MAIL_BODY where id = :ID";
           	$row = $db_inbox->prepare($query);
		$row->bindParam(':ID',    $lastid,          PDO::PARAM_INT);
	   	$row->bindParam(':MAIL_BODY',    $mail_body,       PDO::PARAM_STR);
           	$row->execute();
           	sqlerror($db_inbox,$query);

		$query="UPDATE $GSP_INBOX_DB_TABLE set complete_f = 1 where id = :ID and (mail_from != '' or mail_to != '' or mail_subject != '')";
           	$row = $db_inbox->prepare($query);
		$row->bindParam(':ID',    $lastid,          PDO::PARAM_INT);
           	$row->execute();
           	sqlerror($db_inbox,$query);

	   }
	}  
	return "New messages : $newcnt";
}
?>
