<?
$web_page -> add_css("ext/dhtmlx/css/dhtmlxlayout.css");
$web_page -> add_css("ext/dhtmlx/css/dhtmlxlayout_dhx_skyblue.css");

$web_page -> add_css("ext/dhtmlx/css/dhtmlxwindows.css");
$web_page -> add_css("ext/dhtmlx/css/dhtmlxwindows_dhx_skyblue.css");

$web_page -> add_css("ext/dhtmlx/css/dhtmlxgrid.css");

$web_page -> add_css("ext/dhtmlx/css/dhtmlxmenu_dhx_skyblue.css");

$web_page -> add_css("ext/dhtmlx/css/dhtmlxtabbar.css");
$web_page -> add_css("ext/dhtmlx/css/dhtmlxtoolbar_dhx_skyblue.css");

if ($_REQUEST[EVENT_ID] == "" && $_REQUEST[MAIL_ID] == "")
{
        $web_page -> add_css("css/dhtmlx_layout_strict.css");
}

$web_page -> add_css("css/gsp_dhtmlx_main_windows.css");

$web_page -> add_jsfile("js/gspmenu.js");
$web_page -> add_jsfile("js/gspgrid.js");
$web_page -> add_jsfile("js/gspopen.js");
$web_page -> add_jsfile("js/ajax.js");

$web_page -> add_jsfile("ext/dhtmlx/dhtmlxcommon.js");

$web_page -> add_jsfile("ext/dhtmlx/dhtmlxcontainer.js");
$web_page -> add_jsfile("ext/dhtmlx/dhtmlxlayout.js");

$web_page -> add_jsfile("ext/dhtmlx/dhtmlxmenu.js");
$web_page -> add_jsfile("ext/dhtmlx/ext/dhtmlxmenu_ext.js");

$web_page -> add_jsfile("ext/dhtmlx/dhtmlxwindows.js");

$web_page -> add_jsfile("ext/dhtmlx/dhtmlxtree.js");

$web_page -> add_jsfile("ext/dhtmlx/dhtmlxgrid.js");
$web_page -> add_jsfile("ext/dhtmlx/dhtmlxgridcell.js");
$web_page -> add_jsfile("ext/dhtmlx/excells/dhtmlxgrid_excell_link.js");
$web_page -> add_jsfile("ext/dhtmlx/dhtmlxgrid_export.js");

$web_page -> add_jsfile("ext/dhtmlx/dhtmlxtabbar.js");
$web_page -> add_jsfile("ext/dhtmlx/dhtmlxtoolbar.js");

$web_page -> add_jsfile("ext/shortcut.js");

$web_page -> render();

if (($_REQUEST[EVENT_ID] != "" || $_REQUEST[MAIL_ID] != "") && $_REQUEST[ACTION] == "")
{
	if ($_REQUEST[BOX] == 1 || $_REQUEST[BOX] == 2)
	{
		if ($_REQUEST[BOX] == 1)
		{
			echo "<b>Evénement en cours</b><br>";
		}
		else
		{
			echo "<b>Evénement non assigné</b><br>";
		}
		$event = new event();
		echo "
		<form action = '' method='post' id='event'>
		<input type='hidden' name='MODL' value='MAIN'></input>
		<input type='hidden' name='BOX' value=''></input>";
		$event->display_actions();
		echo "</form>";
		$event->display();
	}
	if ($_REQUEST[BOX] == 3 )
	{
		echo "<b>Visualisation d'un mail</b><br>";
		$db->query("attach database '$GSP_INBOX_DB_PATH' as 'gsp_inbox'");
		$mail = new mail();
		echo "
		<form action = '' method='post' id='mail'>
		<input type='hidden' name='MODL' value='MAIN'></input>
		<input type='hidden' name='BOX' value=''></input>";
		$mail->display_actions();
		$mail->display();
		echo "</form>";
		$db->query("detach database 'gsp_inbox'");
	}
	exit;
}

if ($_REQUEST[ACTION] == "RETREAT")
{
	$db->query("attach database '$GSP_INBOX_DB_PATH' as 'gsp_inbox'");
	$mail = new mail();
	$mail->retreat();

	$db->query("detach database 'gsp_inbox'");

        echo "<script>
                      parent.mygrid1.deleteRow(" . $_REQUEST[MAIL_ID] . ");
                      parent.dhxWins.window('win_mail_id_" . $_REQUEST[MAIL_ID] . "').close();
              </script>";
        exit;

}
if ($_REQUEST[ACTION] == "HIDE")
{
	$db->query("attach database '$GSP_INBOX_DB_PATH' as 'gsp_inbox'");
	$mail = new mail();
	$mail->hide();

	$db->query("detach database 'gsp_inbox'");

        echo "<script>
                      parent.mygrid1.deleteRow(" . $_REQUEST[MAIL_ID] . ");
                      parent.xgrid_update_stat(1,parent.mygrid1.getRowsNum(),0);
                      parent.dhxWins.window('win_mail_id_" . $_REQUEST[MAIL_ID] . "').close();
              </script>";
        exit;
}

if ($_REQUEST[ACTION] == "UNHIDE")
{
	$db->query("attach database '$GSP_INBOX_DB_PATH' as 'gsp_inbox'");
	$mail = new mail();
	$mail->unhide();

	$db->query("detach database 'gsp_inbox'");

        echo "<script>
                      parent.mygrid1.deleteRow(" . $_REQUEST[MAIL_ID] . ");
                      parent.xgrid_update_stat(1,parent.mygrid1.getRowsNum(),0);
                      parent.dhxWins.window('win_mail_id_" . $_REQUEST[MAIL_ID] . "').close();
              </script>";
        exit;

}

if ($_REQUEST[ACTION] == "ASSIGN")
{
	echo "<center>";
	echo "Assigner cet événement à : <br>";
        $event = new event();
        $event->assign_prepare();
        exit;
}

if ($_REQUEST[ACTION] == "Assigner_validation")
{
	$event = new event();
	$event->assign_submit();

	$STAT=getstat($db);

        echo "<script>
	parent.xgrid_update_data('$CONDPROJECT', '$_REQUEST[MODL_OPTION]', $STAT[MAILBOX], $STAT[EVENT_UNASSIGNED], $STAT[EVENT_MINE], $STAT[EVENT_ALL], '$GSP_INBOX_DB_PATH', '$GSP_INBOX_DB_TABLE')
	parent.dhxWins.window('win_event_id_" . $_REQUEST[EVENT_ID] . "').close();
	</script>";
	exit;
}

if ($_REQUEST[ACTION] == "UNASSIGN")
{
	$event = new event();
	$event->unassign();

	$STAT=getstat($db);

        echo "<script>
        parent.xgrid_update_data('$CONDPROJECT', '$_REQUEST[MODL_OPTION]', $STAT[MAILBOX], $STAT[EVENT_UNASSIGNED], $STAT[EVENT_MINE], $STAT[EVENT_ALL], '$GSP_INBOX_DB_PATH', '$GSP_INBOX_DB_TABLE')
        parent.dhxWins.window('win_event_id_" . $_REQUEST[EVENT_ID] . "').close();
        </script>";

	exit;
}

if ($_REQUEST[ACTION] == "CLOSE")
{
	$event = new event();
	$event->close();

        $STAT=getstat($db);

        echo "<script>
        parent.xgrid_update_data('$CONDPROJECT', '$_REQUEST[MODL_OPTION]', $STAT[MAILBOX], $STAT[EVENT_UNASSIGNED], $STAT[EVENT_MINE], $STAT[EVENT_ALL], '$GSP_INBOX_DB_PATH', '$GSP_INBOX_DB_TABLE')
        parent.dhxWins.window('win_event_id_" . $_REQUEST[EVENT_ID] . "').close();
        </script>";

        exit;
}

if ($_REQUEST[ACTION] == "REOPEN")
{
	$event = new event();
	$event->reopen();

	echo "<script>parent.dhxWins.window('win_event_id_" . $_REQUEST[EVENT_ID] . "').close();</script>";
        exit;
}

if ($_REQUEST[ACTION] == "EVENT_CREATE")
{
	$db->query("attach database '$GSP_INBOX_DB_PATH' as 'gsp_inbox'");
	$event = new event();
        echo "
	<form action = 'index.php' method='post' name='detevent' id='detevent'>
	<input type='hidden' name='MODL' value='CVNT'></input>";
	$event->create_prepare();
	echo "</form></body></html>";
	$db->query("detach database 'gsp_inbox'");
	exit;
}

if ($_REQUEST[ACTION] == "EVENT_ATTACH_MAIL")
{
	echo "<center>";
	echo "<a href='javascript:history.go(-1)' alt='RETOUR'><img src='pict/back.png' border=0></a><br><br>";
	echo "Sélectionnez l'événement : <br>";
	echo "</center>";
	$event = new event();
	$event->list_for_attach();
	exit;
}

if ($_REQUEST[ACTION] == "EVENT_ATTACH_MAIL_PREPARE")
{
	$db->query("attach database '$GSP_INBOX_DB_PATH' as 'gsp_inbox'");
	$event = new event();
	echo "
	<form action = 'index.php' method='post' name='detevent' id='detevent'>
	<input type='hidden' name='MODL' value='MAIN'></input>
	<input type='hidden' name='BOX' value=''></input>";
	$event->new_history_prepare();
	echo "</form></body></html>";
	$db->query("detach database 'gsp_inbox'");
	exit;
}

if ($_REQUEST[ACTION] == "NEW_HISTORY_PREPARE")
{
        $event = new event();
        echo "
        <form action = 'index.php' method='post' name='detevent' id='detevent'>
        <input type='hidden' name='MODL' value='MAIN'></input>
        <input type='hidden' name='BOX' value=''></input>";
        $event->new_history_prepare();
        echo "</form></body></html>";
        exit;
}

if ($_REQUEST[ACTION] == "NEW_HISTORY_SUBMIT")
{
	$db->query("attach database '$GSP_INBOX_DB_PATH' as 'gsp_inbox'");
	$event = new event();
	$event->new_history_submit();
	$db->query("detach database 'gsp_inbox'");
	
	$STAT=getstat($db);

        echo "<script>parent.xgrid_update_data('$CONDPROJECT', '$_REQUEST[MODL_OPTION]', $STAT[MAILBOX], $STAT[EVENT_UNASSIGNED], $STAT[EVENT_MINE], $STAT[EVENT_ALL], '$GSP_INBOX_DB_PATH', '$GSP_INBOX_DB_TABLE')</script>";

	if ($_REQUEST[MAIL_ID] != "")
	{
		echo "<script>parent.dhxWins.window('win_mail_id_" . $_REQUEST[MAIL_ID] . "').close();</script>";
	}
	else
	{
		echo "<script>parent.dhxWins.window('win_event_id_" . $_REQUEST[EVENT_ID] . "').close();</script>";
	}
	exit;
}

if ($_REQUEST[ACTION] == "UPDATE_PREPARE")
{
	$event = new event();
	$event->update_prepare();
	exit;
}

if ($_REQUEST[ACTION] == "UPDATE_SUBMIT")
{
	$event = new event();
	$event->update_submit();
	echo "<script>parent.dhxWins.window('win_event_id_" . $_REQUEST[EVENT_ID] . "').close();</script>";
	exit;
}

if ($_REQUEST[ACTION] == "HISTORY_UPDATE_PREPARE")
{
        $event = new event();
        echo "
        <form action = 'index.php' method='post' name='detevent' id='detevent'>
        <input type='hidden' name='MODL' value='MAIN'></input>
        <input type='hidden' name='BOX' value=''></input>";
        $event->history_update_prepare();
        echo "</form>";
        exit;
}

if ($_REQUEST[ACTION] == "HISTORY_UPDATE_SUBMIT")
{
        $event = new event();
        $event->history_update_submit();
	echo "<script>parent.dhxWins.window('win_event_id_" . $_REQUEST[EVENT_ID] . "').close();</script>";
	exit;
}

if ($_REQUEST[ACTION] == "EXT_CODE_PREPARE")
{
	$event = new event();
        echo "
        <form action = 'index.php' method='post' name='detevent' id='detevent'>
        <input type='hidden' name='MODL' value='MAIN'></input>
        <input type='hidden' name='BOX' value=''></input>";
        $event->assign_ext_code();
        echo "</form>";
        exit;
}

if ($_REQUEST[ACTION] == "EXT_CODE_SUBMIT")
{
	$ext_code = new ext_code();
	$ext_code->insert_submit();

	$STAT=getstat($db);

        echo "<script>
	parent.xgrid_update_data('$CONDPROJECT', '$_REQUEST[MODL_OPTION]', $STAT[MAILBOX], $STAT[EVENT_UNASSIGNED], $STAT[EVENT_MINE], $STAT[EVENT_ALL], '$GSP_INBOX_DB_PATH', '$GSP_INBOX_DB_TABLE')
	parent.dhxWins.window('win_event_id_" . $_REQUEST[EVENT_ID] . "').close();
	</script>";
	exit;
}

if ($_REQUEST[BOX] == "" ) 
if ($_REQUEST[MODL_OPTION] != "ALL" && $_REQUEST[MODL_OPTION] != "HIER")
{
	if($_COOKIE[GSP_USER] == "")
	{
		$_COOKIE[GSP_USER]=$_POST["USER"];
	}
	$CONDUSER=" and owner_usercode='$_COOKIE[GSP_USER]'";
	$box3_title="Mes événements en cours";
	$box3_icon="box4.png";
}
if ($_REQUEST[MODL_OPTION] == "EVENT_CLOSED")
{
	$STATE="closed";
	$CONDUSER="";
	$box3_title="Tous les événements fermés";
	$box3_icon="box3.png";
}
else
{
	if ($_REQUEST[MODL_OPTION] == "MY_EVENT_CLOSED")
	{
		$STATE="closed";
		$CONDUSER=" and owner_usercode='$_COOKIE[GSP_USER]'";
		$box3_title="Mes événements fermés";
		$box3_icon="box4.png";
	}
	else
	{
		$STATE="open";
		if ($CONDUSER == "")
		{
			$box3_title="Tous les événements en cours";
			$box3_icon="box3.png";
		}
	}
}
if ($_REQUEST[PROJECT_ID] != "")
{
	if ($_REQUEST[PROJECT_ID] == "none")
	{
		$CONDPROJECT="and parent_id isnull";
	}
	else
	{
		$CONDPROJECT="and parent_id = $_REQUEST[PROJECT_ID]";
	}
}

// Chargement des plugins

$list_plugin=return_query_array($db,"select id, code, name from plugin where active_f = 1 and installed_f = 1 order by code");

$count_plugin=1;
echo "<script>";
echo "function load_plugins()\n{";
foreach ($list_plugin as $id => $plugin)
{
        if ($count_plugin==1)
        {
                echo "gspmenu.addNewSibling('div_1_3','plugins','Plugins');\n";
        }
        echo "gspmenu.addNewChild('plugins',$count_plugin,'plugin_$plugin[1]','$plugin[2]');\n";
	echo "gspmenu.setUserData('plugin_$plugin[1]', 'MODL', 'LPLG');\n";
	echo "gspmenu.setUserData('plugin_$plugin[1]', 'MODL_OPTION', '$plugin[1]');\n";
	echo "gspmenu.setUserData('plugin_$plugin[1]', 'WIN_WIDTH', 800);\n";
	echo "gspmenu.setUserData('plugin_$plugin[1]', 'WIN_HEIGHT', 600);\n";
	echo "gspmenu.setUserData('plugin_$plugin[1]', 'WIN_TITLE', '$plugin[2]');\n";
        $count_plugin++;
}
echo "}\n</script>";


$box2_title="Evénements non assign&eacute;s";
$box1_title="Bo&icirc;te de r&eacute;ception";

$box2_icon="box2.png";
$box1_icon="box1.png";

if ($_REQUEST[MODL_OPTION] == "HIER")
{
$box3_title="Mes événements en cours";
$box3_object="event_treeID1";
echo "       
<div id='event_treeID1' style='width:100%;height:100%;overflow:auto;'></div>
<script>
    tree=new dhtmlXTreeObject('event_treeID1','100%','100%',0);
    tree.setImagePath('ext/dhtmlx/imgs/');
    tree.enableTreeImages(0);
    tree.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=event&STATE=$STATE&MODL_OPTION=$_REQUEST[MODL_OPTION]&PROJECT_ID=$_REQUEST[PROJECT_ID]');
</script>
";

}
else
{

if ($_REQUEST[MODL_OPTION] == "EVENT_CLOSED")
{
        $EVENT_MODE="EVENT_CLOSED";
}
else
{
        if ($_REQUEST[MODL_OPTION] == "MY_EVENT_CLOSED")
        {
                $EVENT_MODE="MY_EVENT_CLOSED";
        }
        else
        {
		if($CONDUSER != "")
		{
                	$EVENT_MODE="MY_EVENT_OPEN";
		}
		else
		{
			$EVENT_MODE="EVENT_OPEN";
		}
        }
}

// Construit la box 3 "Evenements en cours"

$box3_object="gridbox3";
include("include/main_box3.php");
}

if ($_REQUEST[MODL_OPTION] == "HIER")
{
$box2_object="event_treeID2";
echo "       
<div id='event_treeID2' style='width:100%;height:100%;overflow:auto;'></div>
<script>
    tree=new dhtmlXTreeObject('event_treeID2','100%','100%',0);
    tree.setImagePath('ext/dhtmlx/imgs/');
    tree.enableTreeImages(0);
    tree.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=event&STATE=$STATE&ASSIGN=null&PROJECT_ID=$_REQUEST[PROJECT_ID]');
</script>
";

}
else
{
// Construit la box 2 "Evenements non assignes"

$box2_object="gridbox2";
include("include/main_box2.php");
}
if ($_REQUEST[MODL_OPTION] == "MAIL_HIDDEN")
{
        $MAIL_MODE="HIDDEN";
        $TREATED=0;
        $HIDDEN=1;
}
else
{
        $HIDDEN=0;
        if ($_REQUEST[MODL_OPTION] == "MAIL_TREATED")
        {
                $MAIL_MODE="TREATED";
                $TREATED=1;
        }
        else
        {
		$MAIL_MODE="NORMAL";
                $TREATED=0;
        }
}

// Construit la box 1 "Boite de reception"

$box1_object="gridbox1";
include("include/main_box1.php");

include("include/main_project.php");
echo "
</div>
<br><br><br><br>
<form action = 'index.php' method='post'>
<input type='hidden' name='MODL' value='MAIN'></input>
<input type='hidden' name='MODL_OPTION' value='$_REQUEST[MODL_OPTION]'></input>
<input type='hidden' name='PROJECT_ID' value='$_REQUEST[PROJECT_ID]'></input>
</form>
";
?>

<?
echo "
<div id='winVP' style='position: relative; width: 100%; height: 100%; overflow: hidden; display: none;'></div>
<div id='menuObj'></div>
<div id='toolbarObj'></div>

<form name='gofrommenu' action='index.php' method='post'>
	<input type=hidden name='MODL'>
        <input type=hidden name='MODL_OPTION'>
	<input type=hidden name='PROJECT_ID' value='$_REQUEST[PROJECT_ID]'>
</form>

<script>

var toolbar = new dhtmlXToolbarObject('toolbarObj');
toolbar.setIconsPath('ext/dhtmlx/imgs/');
toolbar.attachEvent('onStateChange', function(id, state)
{
        if(toolbar.getItemText(id) == 'mail')
        {
                dhxWins.window('win_mail_id_'+id).show();
                dhxWins.window('win_mail_id_'+id).bringToTop();
        }
        else
        {
                dhxWins.window('win_event_id_'+id).show();
                dhxWins.window('win_event_id_'+id).bringToTop();
        }
        toolbar.removeItem(id);
        toolbar.count--;
        if (toolbar.count == 0)
        {
                tabbar.setLabel('a2','Container');
                tabbar.setTabActive('a1');
		tabbar.setCustomStyle('a2', 'grey', 'black', 'font-weight:normal');
                tabbar.disableTab('a2');                
        }
        else
        {
                tabbar.setLabel('a2','Container ('+toolbar.count+')');
        }
});
toolbar.count=0;

var dhxLayout=new dhtmlXLayoutObject(document.body, '4U');
dhxLayout.setEffect('resize', false);
dhxLayout.setEffect('collapse', false);
dhxLayout.setEffect('highlight', false);

dhxLayout.cells('a').hideHeader();
dhxLayout.cells('b').hideHeader();
dhxLayout.cells('c').hideHeader();
dhxLayout.cells('d').hideHeader();

dhxLayout.cells('a').setHeight(53);
dhxLayout.cells('a').setWidth(280);
dhxLayout.cells('c').setWidth(340);

dhxLayout.cells('a').fixSize(true, true);
dhxLayout.cells('b').fixSize(true, true);
dhxLayout.cells('c').fixSize(true, true);
dhxLayout.cells('d').fixSize(true, true);

dhxLayout.cells('a').attachObject('menuObj');
dhxLayout.cells('c').attachURL('index.php?MODL=RFSH&PROJECT_ID=$_REQUEST[PROJECT_ID]');
dhxLayout.cells('d').attachObject('winVP');

var tabbar = dhxLayout.cells('b').attachTabbar();
tabbar.setImagePath('ext/dhtmlx/imgs/');
tabbar.setSkin('default');
tabbar.setOffset((dhxLayout.cells('b').getWidth()-190)/2);
tabbar.setSkinColors('#ebebeb', '#fafafa');
tabbar.setCustomStyle('a1', 'black', 'black', 'font-weight:normal');
tabbar.setCustomStyle('a2', 'grey', 'black', 'font-weight:normal');
tabbar.addTab('a1','Projet','90px');
tabbar.addTab('a2','Container','90px');
tabbar.setTabActive('a1');
tabbar.setContent('a1','projectObj');
tabbar.setContent('a2','toolbarObj');
tabbar.disableTab('a2',true);

var sb = dhxLayout.attachStatusBar();
sb.setText('OK');

gspmenu=new dhtmlXMenuObject('menuObj','dhx_skyblue');
gspmenu.setIconsPath('ext/dhtmlx/imgs/');
gspmenu.loadXML('xml/menu.xml', function(){load_plugins()})
gspmenu.attachEvent('onClick', function(id)
{
       	menu_call_module(gspmenu.getUserData(id,'MODL'),gspmenu.getUserData(id,'MODL_OPTION'), gspmenu.getUserData(id,'WIN_WIDTH'),gspmenu.getUserData(id,'WIN_HEIGHT'),gspmenu.getUserData(id,'WIN_TITLE'),gspmenu.getUserData(id,'WIN_MAXIMIZE'));
});

shortcut.add('Ctrl+1',function(){id='event_new';menu_call_module(gspmenu.getUserData(id,'MODL'),gspmenu.getUserData(id,'MODL_OPTION'),gspmenu.getUserData(id,'WIN_WIDTH'),gspmenu.getUserData(id,'WIN_HEIGHT'),gspmenu.getUserData(id,'WIN_TITLE'),gspmenu.getUserData(id,'WIN_MAXIMIZE'));});


var dhxWins = new dhtmlXWindows();
dhxWins.setSkin('dhx_skyblue');
dhxWins.setImagePath('ext/dhtmlx/imgs/');
dhxWins.enableAutoViewport(false);
dhxWins.attachViewportTo('winVP');

var win_width=dhxLayout.cells('d').getWidth();
var win_height=dhxLayout.cells('d').getHeight();

var win3 = dhxWins.createWindow(1,0,0,win_width,win_height/100*50);
var win2 = dhxWins.createWindow(2,0,win_height/100*50,win_width,win_height/100*25);
var win1 = dhxWins.createWindow(3,0,win_height/100*75,win_width,win_height/100*25);

win3.button('park').hide();
win2.button('park').hide();
win1.button('park').hide();

win3.button('close').hide();
win2.button('close').hide();
win1.button('close').hide();

win3.setText('$box3_title');
win2.setText('$box2_title');
win1.setText('$box1_title');

win3.denyMove();
win2.denyMove();
win1.denyMove();

win3.style.zIndex=0;
win2.style.zIndex=0;
win1.style.zIndex=0;

win3.mode='$EVENT_MODE';
win2.mode='$EVENT_MODE';
win1.mode='$MAIL_MODE';

win3.project='$_REQUEST[PROJECT_ID]';
win2.project='$_REQUEST[PROJECT_ID]';

dhxWins.window(1).attachObject('$box3_object');
dhxWins.window(2).attachObject('$box2_object');
dhxWins.window(3).attachObject('$box1_object');

dhxWins.window(1).setIcon('../../../pict/$box3_icon', '../../../pict/$box3_icon');
dhxWins.window(2).setIcon('../../../pict/$box2_icon', '../../../pict/$box2_icon');
dhxWins.window(3).setIcon('../../../pict/$box1_icon', '../../../pict/$box1_icon');

//dhxWins.window(1).addUserButton('close',3,'Exporter au format excel','close');

//dhxWins.window(1).button('close').attachEvent('onClick', function(){
//	mygrid3.toExcel('ext/dhtmlx/grid2excel/generate.php', 'full_color');
//    }); 

win3.attachEvent('onMinimize', function resize(){mygrid3.setSizes()});
win2.attachEvent('onMinimize', function resize(){mygrid2.setSizes()});
win1.attachEvent('onMinimize', function resize(){mygrid1.setSizes()});

win3.attachEvent('onMaximize', function resize(){mygrid3.setSizes()});
win2.attachEvent('onMaximize', function resize(){mygrid2.setSizes()});
win1.attachEvent('onMaximize', function resize(){mygrid1.setSizes()});

win3.bringToTop();
</script>
";
?>
