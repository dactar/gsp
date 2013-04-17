<?
function getstat($db)
{
	global $GSP_INBOX_DB_PATH;
	global $GSP_INBOX_DB_TABLE;
	$db->query("attach database '$GSP_INBOX_DB_PATH' as 'gsp_inbox'");

	$STAT[EVENT_MINE]=return_query($db, "SELECT count(*) from event_vw where owner_usercode='$_COOKIE[GSP_USER]' and state='open';");
	$STAT[EVENT_ALL]=return_query($db, "SELECT count(*) from event where owner_id isnull=0 and closed_d isnull;");
	$STAT[EVENT_UNASSIGNED]=return_query($db, "SELECT count(*) from event where owner_id isnull and closed_d isnull;");
	$STAT[MAILBOX]=return_query($db, "SELECT count(*) from gsp_inbox.$GSP_INBOX_DB_TABLE where treated_f = 0 and hidden_f = 0");

	$db->query("detach database 'gsp_inbox'");

	return $STAT;
}
?>
