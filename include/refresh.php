<?
$db->query("attach database '$GSP_INBOX_DB_PATH' as 'gsp_inbox'");
$COUNT_EVENT_MINE=return_query($db, "SELECT count(*) from event_vw where owner_usercode='$_COOKIE[GSP_USER]' and state='open';");
$COUNT_EVENT_ALL=return_query($db, "SELECT count(*) from event where owner_id isnull=0 and closed_d isnull;");
$COUNT_EVENT_UNASSIGNED=return_query($db, "SELECT count(*) from event where owner_id isnull and closed_d isnull;");
$COUNT_EVENT_CLOSED_MINE=return_query($db, "SELECT count(*) from event_vw where owner_usercode='$_COOKIE[GSP_USER]' and state='closed';");
$COUNT_EVENT_CLOSED_ALL=return_query($db, "SELECT count(*) from event where owner_id isnull=0 and closed_d isnull=0;");
$COUNT_EVENT_CLOSED_UNASSIGNED=return_query($db, "SELECT count(*) from event where owner_id isnull and closed_d isnull=0;");
if ($_REQUEST[PROJECT_ID] != "")
{
	$CONDPROJECT="and (id = $_REQUEST[PROJECT_ID] or project_id = $_REQUEST[PROJECT_ID])";
	$COUNT_EVENT_PROJECT_MINE=return_query($db, "SELECT count(*) from event_vw where owner_usercode='$_COOKIE[GSP_USER]' $CONDPROJECT and state='open';");
	$COUNT_EVENT_PROJECT_ALL=return_query($db, "SELECT count(*) from event where owner_id isnull=0 $CONDPROJECT and closed_d isnull;");
	$COUNT_EVENT_PROJECT_CLOSED_MINE=return_query($db, "SELECT count(*) from event_vw where owner_usercode='$_COOKIE[GSP_USER]' $CONDPROJECT and state='closed';");
	$COUNT_EVENT_PROJECT_CLOSED_ALL=return_query($db, "SELECT count(*) from event where owner_id isnull=0 $CONDPROJECT and closed_d isnull=0;");

}
$COUNT_MAIL=return_query($db, "SELECT count(*) from gsp_inbox.$GSP_INBOX_DB_TABLE where treated_f = 0 and hidden_f = 0");


$list_sla=return_query_array($db, "SELECT id, code from sla where active_f = 1 order by rank_n");
$count_sla=0;
$event_sla="";
foreach ($list_sla as $id => $sla_data)
{
	if ($count_sla==0)
	{
		$sla_results="<b>SLA</b> : ";
	}
	$sla=new sla();
	$sla->set_id($sla_data[0]);
	if ($count_sla==0)
	{
		$events_sla = $sla->verif_events(true);
		foreach ($events_sla as $count_event_sla => $event_sla)
		{
			if ($event_sla[3] != "" || $event_sla[4] != ""  || $event_sla[5] != "")
			{
				 $grid_update_sla .= "\nif(parent.mygrid3.doesRowExist($event_sla[0])){parent.mygrid3.cells($event_sla[0],parent.mygrid3.getColumnCount() - 1).setValue('<b>SLA " . str_replace("1","!","$event_sla[3]$event_sla[4]$event_sla[5]") . "</b>')};";
			}
		}
	
		$events_sla = $sla->verif_events(false);
		foreach ($events_sla as $count_event_sla => $event_sla)
		{
			if ($event_sla[1] != "" || $event_sla[2] != "")
			{
				 $grid_update_sla .= "\nif(parent.mygrid2.doesRowExist($event_sla[0])){parent.mygrid2.cells($event_sla[0],parent.mygrid3.getColumnCount() - 1).setValue('<b>SLA " . str_replace("1","!","$event_sla[1]$event_sla[2]") . "</b>')};";
			}
		}
	}
	$sla_pct=$sla->alert();
	$sla_results.="<a href=\"javascript:parent.menu_call_module('VSLA','',800,600,'Vérification des SLA',1)\">$sla_pct</a> % ";
	$count_sla++;
}

$db->query("detach database 'gsp_inbox'");

$web_page->add_meta("refresh","180");
$web_page->render();
echo "</head><body bgcolor='#ebebeb' style='overflow:hidden;'></center>
<script type='text/javascript' src='ext/dhtmlx/dhtmlxcommon.js'></script>
<script type='text/javascript'>
    $sla_notif
    $grid_update_sla
    function outputResponse(loader)
    {
	parent.sb.setText('Chargement...');

	if(parent.win1.mode == 'NORMAL')
	{
		parent.mygrid1.updateFromXML('index.php?MODL=GETX&OBJECT=mailbox&GSP_INBOX_DB_PATH=$GSP_INBOX_DB_PATH&GSP_INBOX_DB_TABLE=$GSP_INBOX_DB_TABLE&TREATED=0&HIDDEN=0','top',true,afterload);
	}
    }

    function afterload()
    {
	parent.mygrid1.enableStableSorting(true);
	parent.mygrid1.sortRows(1,'str','des');
	parent.mygrid1.sortRows(0,'str','des');
	parent.mygrid1.enableStableSorting(false);

	var expr_reg=/\(/g;
	var begin_title=parent.win1.getText().split(expr_reg);
	parent.win1.setText(begin_title[0] + ' (' + parent.mygrid1.getRowsNum() +')');

	parent.sb.setText('OK');

	var new_mail_nbr = parent.mygrid1.getRowsNum() - $COUNT_MAIL;

	parent.win1.style.zIndex=0;
	
	if (new_mail_nbr > 0)
	{
		parent.dhtmlx.message.defPosition='bottom';
   		if (new_mail_nbr == 1)
   		{
			parent.dhtmlx.message({text:new_mail_nbr + ' nouveau mail !'});
		}
		else
		{
			parent.dhtmlx.message({text:new_mail_nbr + ' nouveaux mails !'});
		}
	}
    }

    function getmails()
    {
	parent.sb.setText('Recherche des nouveaux mails en cours...');
	dhtmlxAjax.post('index.php','MODL=IMAP',outputResponse);
    }

    function putstat()
    {
    	var expr_reg=/\(/g;
    	var begin_title3=parent.win3.getText().split(expr_reg);
	var begin_title2=parent.win2.getText().split(expr_reg);
	var begin_title1=parent.win1.getText().split(expr_reg);
	var count_project_all='';
	var count_project_mine='';
        var count_project_closed_all='';
        var count_project_closed_mine='';


	if(parent.win3.project != '')
	{
		count_project_all=' - Vue projet ($COUNT_EVENT_PROJECT_ALL)';
		count_project_mine=' - Vue projet ($COUNT_EVENT_PROJECT_MINE / $COUNT_EVENT_PROJECT_ALL)';
                count_project_closed_all=' - Vue projet ($COUNT_EVENT_PROJECT_CLOSED_ALL)';
                count_project_closed_mine=' - Vue projet ($COUNT_EVENT_PROJECT_CLOSED_MINE / $COUNT_EVENT_PROJECT_CLOSED_ALL)';
	}
		
	switch (parent.win3.mode)
	{
		case 'EVENT_OPEN' : 	parent.win3.setText(begin_title3[0] + ' ($COUNT_EVENT_ALL)' + count_project_all); 
					parent.win2.setText(begin_title2[0] + ' ($COUNT_EVENT_UNASSIGNED)');
					break;
		case 'MY_EVENT_OPEN' : 	parent.win3.setText(begin_title3[0] + ' ($COUNT_EVENT_MINE / $COUNT_EVENT_ALL)' + count_project_mine);
					parent.win2.setText(begin_title2[0] + ' ($COUNT_EVENT_UNASSIGNED)');
					break;
		case 'EVENT_CLOSED' : 	parent.win3.setText(begin_title3[0] + ' ($COUNT_EVENT_CLOSED_ALL)' + count_project_closed_all);
					parent.win2.setText(begin_title2[0] + ' ($COUNT_EVENT_UNASSIGNED)');
					break;
		case 'MY_EVENT_CLOSED': parent.win3.setText(begin_title3[0] + ' ($COUNT_EVENT_CLOSED_MINE / $COUNT_EVENT_CLOSED_ALL)' + count_project_closed_mine);
					parent.win2.setText(begin_title2[0] + ' ($COUNT_EVENT_UNASSIGNED)');
					break;
		default	: 		parent.win3.setText(begin_title3[0] + ' ($COUNT_EVENT_MINE / $COUNT_EVENT_ALL)' + count_project_mine);
					parent.win2.setText(begin_title2[0] + ' ($COUNT_EVENT_UNASSIGNED)');
					break;
	}
    	parent.win2.setText(begin_title2[0] + ' ($COUNT_EVENT_UNASSIGNED)');
    	parent.win1.setText(begin_title1[0] + ' ($COUNT_MAIL)');
    }
    putstat();
    getmails();
</script>
<img src='pict/logo_text.png'>
<div class=S12><table cellpadding=0 cellspacing=0 align=center><tr><td>$sla_results</td></tr></table></div>
</body>
";
?>
