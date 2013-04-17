<?
function sqlerror($db,$query)
{
	$error[1] = "";

        $error=$db->errorInfo();

        if ($error[1] != "") 
	{
		print "<div class='ERR'>$error[2] : $query</div>";
        	if (preg_grep("/.*contact is referenced in table event.*/",$error))
        	{
                	display_table($db,"select code as event, owner, contact from event_vw where owner_id = $_POST[id] or contact_id = $_POST[id];");
			display_table($db,"select event_id, contact_code from event_history_vw where contact_id = $_POST[id];");
        	}
		exit;
	}
}
?>
