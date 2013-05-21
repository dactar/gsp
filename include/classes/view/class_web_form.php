<?
class web_form
{
	public $entity;
	public $action;
	public $list_options;
	public $list_radio;
	public $list_calendars;
	public $opt_calendar;
	public $opt_lang_calendar;
	public $opt_freeze_calendar;
	public $opt_editor;

	public $output;
	public $return;

	function __tostring()
	{
	    return "Cette classe permet de gérer un formulaire HTML";
	}

	function __construct($entity, $action, $return=FALSE)
	{
	    $this->entity = $entity;
            $this->action = $action;
	    $this->list_options = array();
	    $this->list_radio = array();
            $this->list_calendars = array();
	    $this->opt_calendar = "";
	    $this->opt_lang_calendar = "";
	    $this->opt_editor = "";

	    $this->return = $return;
        }

	function set_list_options($fields)
	{
	    $this->list_options = explode(",",$fields);
	}

	function set_list_radio($fields)
	{
	    $this->list_radio = explode(",",$fields);
	}

	function set_calendar()
	{
	    $this->opt_calendar="<link rel='stylesheet' type='text/css' href='ext/dhtmlx/css/dhtmlxcalendar.css'></link>\n";
	    $this->opt_calendar.="<link rel='stylesheet' type='text/css' href='ext/dhtmlx/css/dhtmlxcalendar_dhx_skyblue.css'></link>\n";
	    $this->opt_calendar.="<script src='ext/dhtmlx/dhtmlxcalendar.js'></script>\n";
	    $this->opt_calendar.="<script src='ext/dhtmlx/ext/dhtmlxform_item_calendar.js'></script>\n";
	    $this->opt_calendar.="<script src='js/calendar_lang.js'></script>\n";
	}

	function set_lang_calendar($fields,$lang)
	{
	    $this->list_calendars = explode(",",$fields);

	    $this->opt_lang_calendar="";

            foreach($this->list_calendars as $field)
            {
		$this->opt_lang_calendar.="\n\tvar calendar = form.getCalendar('" . trim($field) . "');";
		$this->opt_lang_calendar.="\n\tcalendar.loadUserLanguage('$lang');";
            }
	}

	function set_freeze_calendar($fields)
	{
	    $this->list_calendars = explode(",",$fields);

	    $this->opt_freeze_calendar="";

            foreach($this->list_calendars as $field)
            {
		$this->opt_freeze_calendar.="\n\tvar calendar = form.getCalendar('" . trim($field) . "');";
		$this->opt_freeze_calendar.="\n\tvar caldate = calendar.getDate()";
		$this->opt_freeze_calendar.="\n\tcalendar.setSensitiveRange(caldate,caldate);";
            }
	}

	function set_editor($editor)
	{
	    if($editor=="ckeditor")
	    {
	    	$this->opt_editor="<script src='js/dhtmlxform_item_ckeditor.js'></script>\n";
	    }
	}

	function display($id=0)
	{
	    if ($_REQUEST[MODL] == "OPNE")
	    {
		global $db;
		global $GSP_INBOX_DB_PATH;
		global $GSP_INBOX_DB_TABLE;
	    	$STAT=getstat($db);
	    }

	    if ($_REQUEST[MODL] != "LPLG")
            {
	    	$xmlformfile="xml/web_form_" . $this->entity . "_" . $this->action . ".xml";
	    }
	    else
	    {
	        $xmlformfile="plugins/plugin_" . $_REQUEST[MODL_OPTION] . "/xml/web_form_" . $this->entity . "_" . $this->action . ".xml";
            }
            $this->output .= "
<style>
.dhxlist_obj_dhx_skyblue div.dhx_list_btn td.btn_m div.btn_txt {
padding:1px 5px;
}
</style>
<div id='response'></div>
<div id='form' style='position:absolute;'></div>
<script src='ext/dhtmlx/dhtmlxform.js'></script>
<script src='ext/dhtmlx/dhtmlxmessage.js'></script>
$this->opt_calendar
$this->opt_editor
<script type='text/javascript'>
function load_optlist()
{";
           foreach($this->list_options as $field)
           {
		$this->output .= "\n\twebform_load_optlist_static_" . trim($field) . "();";
	   }

           foreach($this->list_radio as $field)
           {
		$this->output .= "\n\twebform_load_radio_static_" . trim($field) . "();";
	   }

		
$this->output .= "\n}\n
	var form;
        var id=$id;
	var MODL='$_REQUEST[MODL]';
	var MODL_OPTION='$_REQUEST[MODL_OPTION]';
	var NATURE='$_REQUEST[NATURE]';
	form = new dhtmlXForm('form');
	form.attachEvent('onXLE',function (){	
		$this->opt_lang_calendar
		$this->opt_freeze_calendar
		if (MODL != 'DICT' && MODL != 'PACK' && MODL != 'SEGM' && MODL != 'CONT')
		{
			form_width=form.cont.firstChild.offsetWidth;
			document.getElementById('form').style.left = (((document.body.offsetWidth - form_width) / 2) + 'px') ;
		}
	});

	form.loadStruct('$xmlformfile', function(loader, response){
		load_optlist();
		if (id != 0) 
		{
			form.load('index.php?MODL='+MODL+'&MODL_OPTION='+MODL_OPTION+'&NATURE='+NATURE+'&ACTION=getxml&ID=$id', function(loader, response)
			{
				if (MODL == 'CSLA' || MODL == 'CBOX')
				{
			                if (form.getItemValue('active_f') == 1)
					{
						form.disableItem('delete');
					}
					else
					{
						form.enableItem('delete');
					}
				}
			});
		}
		});
	form.attachEvent('onButtonClick', function(id) {
        if (id == 'contact_search')
	{
		if (parent.dhxWins.window('win_tree'))
		{
			parent.dhxWins.window('win_tree').show();
			parent.dhxWins.window('win_tree').bringToTop();	
		}
		else
		{
			document.getElementById('contact_treeID').style.visibility = 'visible';
                	var win_tree=parent.dhxWins.createWindow('win_tree',650,100,300,600);
                	win_tree.setText('Contact');
                	win_tree.stick();
                	win_tree.button('sticked').hide();
			win_tree.button('close').hide();
		
                	parent.dhxWins.window('win_tree').attachObject(document.getElementById('contact_treeID'));
		}
        }

	if (id == 'contact_list')
	{
		draw_tree_contact();
		document.getElementById('contact_treeID').style.visibility = 'visible';
	}

	if (id == 'owner_list')
	{
		draw_tree_owner();
                document.getElementById('owner_treeID').style.visibility = 'visible';
	}

	if (id == 'segment_list')
	{
		draw_tree_segment();
		document.getElementById('segment_treeID').style.visibility = 'visible';
	}

	if (id == 'package_list')
	{
		draw_tree_package();
                document.getElementById('package_treeID').style.visibility = 'visible';
	}

        if (id == 'insert')
	{
        	form.send('index.php?MODL='+MODL+'&MODL_OPTION='+MODL_OPTION+'&ACTION=Sauvegarder',function(loader, response){
		document.getElementById('response').innerHTML=response;
		if (response.substring(0,4) != '<div')
		{
			dhtmlx.message('Donn&eacute;e ins&eacute;r&eacute;e');
		}
		if (MODL == 'CSLA' && response.substring(0,4) != '<div')
		{
			parent.dhxWins.window('win_module_CSLA').attachURL('index.php?MODL=CSLA');
		}
		if ((MODL == 'DICT' || MODL == 'PACK' || MODL == 'SEGM' || MODL == 'CONT') && response.substring(0,4) != '<div')
		{
			if (MODL == 'DICT')
			{
				refresh_tree(form.getItemValue('parent_dict_id'));
			}
			else
			{
				if (MODL == 'CONT')
				{
					refresh_tree(form.getItemValue('group_dict_id'));
				}
				else
				{
					refresh_tree(form.getItemValue('appl_dict_id'));
				}
			}
		}
		});
	}
	if (id == 'edit')
	{
		form.send('index.php?MODL='+MODL+'&MODL_OPTION='+MODL_OPTION+'&ACTION=Valider',function(loader, response){
		document.getElementById('response').innerHTML=response;
		if (response.substring(0,4) != '<div')
		{
			dhtmlx.message('Donn&eacute;e sauvegard&eacute;e');
		}
		if ((MODL == 'DICT' || MODL == 'PACK' || MODL == 'SEGM' || MODL == 'CONT') && response.substring(0,4) != '<div')
		{
			if (MODL == 'DICT')
			{
				refresh_tree(form.getItemValue('parent_dict_id'));
			}
			else
			{
				if (MODL == 'CONT')
				{
					refresh_tree(form.getItemValue('group_dict_id'));
				}
				else
				{
					refresh_tree(form.getItemValue('appl_dict_id'));
				}
			}
		}
		if (MODL == 'OPNE' && response.substring(0,4) != '<div')
		{
			parent.xgrid_update_data('$CONDPROJECT', '$_REQUEST[MODL_OPTION]', '$STAT[MAILBOX]', '$STAT[EVENT_UNASSIGNED]', '$STAT[EVENT_MINE]', '$STAT[EVENT_ALL]', '$GSP_INBOX_DB_PATH', '$GSP_INBOX_DB_TABLE');
			parent.dhxWins.window('win_event_id_$_REQUEST[EVENT_ID]').close();
		}
		});
	
	}
	if (id == 'delete')
	{
		form.send('index.php?MODL='+MODL+'&MODL_OPTION='+MODL_OPTION+'&ACTION=Supprimer',function(loader, response){
		document.getElementById('response').innerHTML=response;
                if (response.substring(0,4) != '<div')
                {
                        dhtmlx.message('Donn&eacute;e supprim&eacute;e');
                }

		if (MODL == 'CSLA' && response.substring(0,4) != '<div')
		{
			parent.dhxWins.window('win_module_CSLA').attachURL('index.php?MODL=CSLA');	
		}
		if ((MODL == 'DICT' || MODL == 'PACK' || MODL == 'SEGM' || MODL == 'CONT') && response.substring(0,4) != '<div')
		{
			if (MODL == 'DICT')
			{
				delete_item_tree(form.getItemValue('sel_dict_id'));
			}
			else
			{
				delete_item_tree(form.getItemValue('id'));
			}
		}
		});
	}
	if (id == 'assign_appl' || id == 'assign_group' || id == 'copy_attribute')
	{
		move_form('CODE');
	}
	if (id == 'copy_parent_attribute')
	{
		move_form('PARENT_CODE');
	}
	if (id == 'display_table')
	{
		document.location.href='index.php?MODL='+MODL+'&AFFICHE=GO';
	}
	if (id == 'sla_edit_time')
	{
		document.location.href='index.php?MODL=TSLA&ACTION=Modification&SLA_ID='+form.getItemValue('id');
	}
    });
</script>
";
	    if ($this->return)
	    {
            	return $this->output;
	    }
	    else
	    {
		echo $this->output;
	    }
	}
}
?>
