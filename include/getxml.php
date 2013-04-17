<?
require_once("include/functions/xml_grid_table.php");
require_once("include/functions/xml_tree.php");
require_once("include/functions/xml_subtree.php");
require_once("include/functions/xml_tree_query.php");
include ("include/xml_header.php");

$type     = $_REQUEST["TYPE"];
$object   = $_REQUEST["OBJECT"];

$STATE    = $_REQUEST["STATE"];
$ASSIGN   = $_REQUEST["ASSIGN"];

$TREATED    = $_REQUEST["TREATED"];
$HIDDEN     = $_REQUEST["HIDDEN"];

$GSP_INBOX_DB_PATH  = $_REQUEST["GSP_INBOX_DB_PATH"];
$GSP_INBOX_DB_TABLE = $_REQUEST["GSP_INBOX_DB_TABLE"];

if ("$ASSIGN" == "null")
{
	$OWNER="isnull=1";
}
else
{
	$OWNER="isnull=0";
	if ($_REQUEST[MODL_OPTION] != "ALL" && $_REQUEST[MODL_OPTION] != "EVENT_CLOSED")
	{
	        if($_COOKIE[GSP_USER] == "")
	        {
	                $_COOKIE[GSP_USER]=$_POST["USER"];
	        }
		$OWNERUSER="and owner_usercode='$_COOKIE[GSP_USER]'";
	}
}
if ($_REQUEST[PROJECT_ID] != "")
{
       	if ($_REQUEST[PROJECT_ID] == "none")
       	{
               	$CONDPROJECT="and project_id isnull";
	}
       	else
       	{
               	$CONDPROJECT="and (id = $_REQUEST[PROJECT_ID] or project_id = $_REQUEST[PROJECT_ID])";
       	}
}

if($_REQUEST["APPL"])
{
        $appl     = "and dict_id = " . $_REQUEST["APPL"];
}

if ($type == "tree")
{
	if ($object == "event")
	{
		$cond="owner $OWNER and state='$STATE' $OWNERUSER $CONDPROJECT";
	}
	print "<tree id=\"0\">\n";
	switch ($object)
	{
		case "dict" 	: xml_tree($db,'dict_vw','dict_id','NULL','1=1','is NULL','','','',''); break;
		case "contact"	: xml_tree($db,'dict_vw','dict_id','NULL','1=1','= 20','','','1','contact'); break;
		case "package"	: xml_tree($db,'dict_vw','dict_id','NULL','1=1',"= 13 $appl",'','','1','package'); break;
		case "segment"  : xml_tree($db,'dict_vw','dict_id','NULL','1=1',"= 13 $appl",'','','1','segment'); break;
		case "event"	: xml_tree($db,'event_vw','id','NULL',"$cond",'is NULL','','','',''); break;
		case "owner"	: xml_tree_query($db,"select id, name, code from user_vw where active_f = 1"); break;
	}
	print "</tree>\n";
}


if ($object == "event" && $type !="tree")
{
	xml_grid_table($db,"SELECT id, code || '^' || 'javascript:open_event(' || id || ')^' || '_self' as code, type_dict_id, appl_code, package_code, segment_code, main_ext_code, summary, strftime('%Y.%m.%d',max(asked_d,planif_d)), owner, status_dict_id, last_modif_status_d, strftime('%Y.%m.%d',opened_d) as opened_d, priority_dict_id || '<pri_rank>' || priority_rank || '</pri_rank>' as priority_dict_id, contact from event_vw where owner $OWNER and state='$STATE' $OWNERUSER $CONDPROJECT union select 'empty',1,2,3,4,5,6,7,8,9,11,11,12,13,14 order by id desc");
}

if ($object == "mailbox")
{
	$db->query("attach database '$GSP_INBOX_DB_PATH' as 'gsp_inbox'");

	xml_grid_table($db,"SELECT id, strftime('%Y.%m.%d',mail_date)  || '^' || 'javascript:open_mail(' || id || ')^' || '_self' as mail_date,strftime('%H:%M',mail_date) as _time, mail_from, mail_subject, mail_size from gsp_inbox.$GSP_INBOX_DB_TABLE where treated_f = $TREATED and hidden_f = $HIDDEN and complete_f = 1 union select 'empty',1,2,3,4,5 order by mail_date desc;");
}

?>
