<?
function mailmove($db,$GSP_INBOX_DB_ID,$GSP_INBOX_DB_PATH,$GSP_INBOX_DB_TABLE,$MAIL_ID)
{
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
	$MAILSERVER_TREATED_FOLDER=return_query($db,"select mailbox_treated_folder from config_inbox where id = $GSP_INBOX_DB_ID");

	if ($MAILSERVER_TREATED_FOLDER != "")
	{
		$mbox=imap_open($MAILSERVER_STRING,$MAILSERVER_USERCODE,$MAILSERVER_USERPASS);
	
		$db_inbox = new PDO("sqlite:$GSP_INBOX_DB_PATH");

		$mail_uid=return_query($db_inbox,"select origin_code from $GSP_INBOX_DB_TABLE where id = $MAIL_ID");
		$mail_num=imap_msgno($mbox, $mail_uid);
		imap_mail_move($mbox, $mail_num, $MAILSERVER_TREATED_FOLDER);
		imap_expunge($mbox);
	}
}
?>
