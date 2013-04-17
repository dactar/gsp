<?
        $web_page->render();
?>
<form action = "" method="post">
<input type="hidden" name="MODL" value='IBOX'></input>
<input type="submit" name="AFFICHE" value='Afficher la table'></input>
</form>
<?
if ($_POST[AFFICHE] != "" )
{
	$db->query("attach database '$GSP_INBOX_DB_PATH' as 'gsp_inbox'");
	display_table($db,"SELECT id, mail_date, mail_from, mail_to, mail_cc, mail_subject, mail_body from gsp_inbox.$GSP_INBOX_DB_TABLE where hidden_f = 0 and treated_f = 0 order by mail_date desc;");
	$db->query("detach database 'gsp_inbox'");
}
?>
