<?
$web_page->render();

$_REQUEST[ID]="0";
if ($_REQUEST[ACTION] == "EVENT_CREATE_CONFIRM")
{
	$db->query("attach database '$GSP_INBOX_DB_PATH' as 'gsp_inbox'");
	$event = new event();
	$event->create_submit();
	$db->query("detach database 'gsp_inbox'");

	$STAT=getstat($db);

       	echo "<script>parent.xgrid_update_data('$CONDPROJECT', '$_REQUEST[MODL_OPTION]', $STAT[MAILBOX], $STAT[EVENT_UNASSIGNED], $STAT[EVENT_MINE], $STAT[EVENT_ALL], '$GSP_INBOX_DB_PATH', '$GSP_INBOX_DB_TABLE')</script>";

	if ($_REQUEST[MAIL_ID] != "")
	{
		echo "<script>parent.dhxWins.window('win_mail_id_" . $_REQUEST[MAIL_ID] . "').close();</script>";
	}
	else
	{
		echo "<script>parent.dhxWins.window('win_module_CVNT').close();</script>";
	}
}
else
{
	$event = new event();
	echo "
	<form action = 'index.php' method='post' name='detevent' id='detevent'>
	<input type='hidden' name='MODL' value='CVNT'></input>";
	$event->create_prepare();
	echo "</form>";
}
?>
