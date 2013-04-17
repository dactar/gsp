<? 
require_once("include/functions/return_query.php");

$object = $_REQUEST["OBJECT"];
$filename = $_REQUEST["FILENAME"];
$type = $_REQUEST["TYPE"];

if ($type != "")
{
	if ($type == "msexcel"){$type = "x-msexcel";}
	header("Content-Type: application/$type");
	header("Content-Disposition: attachment; filename=$filename");
}

if ($object = "mail")
{
	$mail_id = $_REQUEST["ID"];
	$GSP_INBOX_DB_PATH=return_query($db,"select inbox_db_path from config_inbox where default_f = 1 and active_f = 1");
	$GSP_INBOX_DB_TABLE=return_query($db,"select inbox_db_table from config_inbox where default_f = 1 and active_f = 1");
	$GSP_INBOX_DB_TABLE_ATT = $GSP_INBOX_DB_TABLE . "_attachment";
	$db_inbox = new PDO("sqlite:$GSP_INBOX_DB_PATH");

	$query="select data from $GSP_INBOX_DB_TABLE_ATT where mail_id = $mail_id and name = '$filename'";
	$row = $db_inbox->query($query);
	$result=$row->fetch(PDO::FETCH_ASSOC);
	print $result[data];
}
if ($object = "event")
{
	$event_id = $_REQUEST["ID"];
	$event_history_id = $_REQUEST["HISTORY_ID"];
        
	$query="select data from event_history_file where event_id = $event_id and event_history_id = $event_history_id and name = '$filename'";
	$row = $db->query($query);
	$result=$row->fetch(PDO::FETCH_ASSOC);
	print $result[data];
}
?>
