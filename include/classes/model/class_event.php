<?
class event
{
	public $db;
	public $id;
	public $code;
	public $parent_id;
	public $parent_code;
	public $project_id;
	public $owner_id;
	public $owner_name;
	public $contact_id;
	public $contact_name;
	public $date;
	public $type;
	public $status;
	public $summary;
	public $appl;
	public $package_id;
	public $package_code;
	public $segment_id;
	public $segment_code;
	public $impact;
	public $priority;
	public $severity;
	public $blocking_f;
	public $prod_f;
	public $opened_d;
	public $logged_d;
	public $asked_d;
	public $planif_d;
	public $closed_d;	
	public $gsp_user_id;
	public $file_nbr;
	public $default_history_type;
	public $main_history_type;

	function __tostring()
	{
	    return "Cette classe permet de définir et manipuler un événement<br/>";
	}

	function __construct()
	{
	    global $db;
	    $this->db = $db;
	    $this->gsp_user_id      = return_query($this->db,"SELECT id from user where code = '$_COOKIE[GSP_USER]'");
	    $this->default_history_type     = return_query($this->db,"SELECT dict_id from dict_vw where parent_code = 'historique' and code = 'retour'");
	    $this->main_history_type        = return_query($this->db,"SELECT dict_id from dict_vw where code = 'description' and parent_code = 'historique'");
	    if($_REQUEST[EVENT_ID] != "" || $_REQUEST[ID] != "")
	    {
		if($_REQUEST[EVENT_ID] != "")
		{
	    		$this->id               = $_REQUEST[EVENT_ID];
		}
		else
		{
			$this->id               = $_REQUEST[ID];
		}
		$this->code		= return_query($this->db,"SELECT code from event where id = $this->id");
		$this->parent_id	= return_query($this->db,"SELECT parent_id from event where id = $this->id");
		if ($this->parent_id != "")
		{
			$this->parent_code 	= return_query($this->db,"SELECT code from event where id = $this->parent_id");
		}
		$this->project_id	= return_query($this->db,"SELECT project_id from event where id = $this->id");
	    	$this->type     	= return_query($this->db,"SELECT type_dict_id from event where id = $this->id");
	    	$this->status   	= return_query($this->db,"SELECT status_dict_id from event where id = $this->id");
	    	$this->summary  	= htmlspecialchars(return_query($this->db,"SELECT summary from event where id = $this->id"));
		$this->remark		= htmlspecialchars(return_query($this->db,"SELECT remark from event where id = $this->id"));
	    	$this->appl     	= return_query($this->db,"SELECT appl_dict_id from event where id = $this->id");
		$this->package_id       = return_query($this->db,"SELECT package_id from event where id = $this->id");
		$this->package_code	= return_query($this->db,"SELECT package_code from event_vw where id = $this->id");
		$this->segment_id       = return_query($this->db,"SELECT segment_id from event where id = $this->id");
		$this->segment_code     = return_query($this->db,"SELECT segment_code from event_vw where id = $this->id");
	    	$this->impact   	= return_query($this->db,"SELECT impact_dict_id from event where id = $this->id");
	    	$this->priority 	= return_query($this->db,"SELECT priority_dict_id from event where id = $this->id");
	    	$this->severity 	= return_query($this->db,"SELECT severity_dict_id from event where id = $this->id");
		$this->blocking_f       = return_query($this->db,"SELECT blocking_f from event where id = $this->id");
		$this->prod_f		= return_query($this->db,"SELECT prod_f from event where id = $this->id");
		$this->opened_d		= return_query($this->db,"SELECT opened_d from event where id = $this->id");
		$this->logged_d		= return_query($this->db,"SELECT logged_d from event where id = $this->id");
		$this->asked_d		= return_query($this->db,"SELECT asked_d from event where id = $this->id");
		$this->planif_d		= return_query($this->db,"SELECT planif_d from event where id = $this->id");
		$this->closed_d		= return_query($this->db,"SELECT closed_d from event where id = $this->id");
	    	$this->owner_id    	= return_query($this->db,"SELECT owner_id from event where id = $this->id");
		if ($this->owner_id != "")
		{
			$this->owner_name       = return_query($this->db,"SELECT name from contact where id = (select contact_id from user where id = $this->owner_id)");
		}
	   	$this->contact_id  	= return_query($this->db,"SELECT contact_id from event where id = $this->id");    
		if ($this->contact_id !="")
		{
			$this->contact_name	= return_query($this->db,"SELECT name from contact where id = $this->contact_id");
		}
		$this->file_nbr         = return_query($this->db,"SELECT count(id) from event_history_file where event_id = $this->id");
	    }
	}

        function getxml()
        {
            $object=$this;
            $array = (array) $object;
            unset($array[db]);
            $xml=array2xml("data",$array);
            echo $xml;
            return "retourne l'événement sous forme xml";
        }

	function display_actions()
	{
	    $status=return_query($this->db,"SELECT status_dict_id from event where id = $this->id");
	    $parent_status=return_query($this->db,"SELECT parent_code from dict_vw where dict_id = $status");

            echo "<center>";
            echo "<input type='hidden' name='ACTION' id='ACTION'>";

	    if ($parent_status == "open")
	    {
		if ($this->owner_id == "")
		{
	    		echo "<input type='image' src='pict/event_assign.png' alt='Assigner événement' title='Assigner événement' onclick='code:getElementById(\"event\").ACTION.value=\"ASSIGN\";'>\n";
		}
		else
		{
	    		echo "<input type='image' src='pict/event_unassign.png' alt='Désassigner événement' title='Désassigner événement' onclick='code:getElementById(\"event\").ACTION.value=\"UNASSIGN\";'>\n";
	    		echo "<input type='image' src='pict/event_assign.png' alt='Réassigner événement' title='Réassigner événement' onclick='code:getElementById(\"event\").ACTION.value=\"ASSIGN\";'>\n";
		}
            	echo "<input type='image' src='pict/event_close.png' alt='Fermer événement' title='Fermer événement' onclick='code:getElementById(\"event\").ACTION.value=\"CLOSE\";'\>\n";
            	echo "<input type='image' src='pict/event_edit.png' alt='Editer événement' title='Editer événement' onclick='code:getElementById(\"event\").ACTION.value=\"UPDATE_PREPARE\";getElementById(\"event\").MODL.value=\"OPNE\"'>\n";
            	echo "<input type='image' src='pict/event_ext_code.png' alt='Attribuer un code externe' title='Attribuer un code externe' onclick='code:getElementById(\"event\").ACTION.value=\"EXT_CODE_PREPARE\";'>\n";
            	echo "<input type='image' src='pict/event_new_history.png' alt='Créer un nouvel historique' title='Créer un nouvel historique' onclick='code:getElementById(\"event\").ACTION.value=\"NEW_HISTORY_PREPARE\";'>\n";
	    }
	    else
	    {
		echo "<input type='image' src='pict/event_reopen.png' alt='Ré-ouvrir événement' title='Ré-ouvrir événement' onclick='code:getElementById(\"event\").ACTION.value=\"REOPEN\";'>\n";	
		echo "<input type='image' src='pict/event_edit.png' alt='Editer événement' title='Editer événement' onclick='code:getElementById(\"event\").ACTION.value=\"UPDATE_PREPARE\";getElementById(\"event\").MODL.value=\"OPNE\"'>\n";
	    }

	    echo "</center>";
	    return "On affiche les actions disponibles"; 
	}

	function reserve()
	{
	    echo "<script language='javascript'>alert('FONCTION NON ENCORE CODEE !!!')</script>;";
	    return "On réserve l'événement";
	}

	function open()
	{
	    echo "<script language='javascript'>alert('FONCTION NON ENCORE CODEE !!!')</script>;";
	    return "On ouvre l'événement";
	}

	function display()
	{
	    display_table($this->db,"SELECT code as No, summary as Description, status_code as Statut, owner as Suivi, opened_d as Date, priority_code as 'Urgence', contact_name as Contact from event_vw where id = $this->id");
	   
	    $list_plugin=return_query_array($this->db,"select id, code, name from plugin where active_f = 1 and installed_f = 1 order by code");
	    foreach ($list_plugin as $id => $plugin)
	    {       
		$curr_plugin=new plugin("$plugin[1]");
		$curr_plugin->load_method("event_display");
	    }

	    if ($this->remark != "")
	    {
	    	display_table($this->db,"SELECT remark as Remarque from event where id = $this->id");
	    }
	    display_table($this->db,"SELECT code as 'Code externe' from event_ext_code_vw where event_id = $this->id");
            if ($this->parent_id != "")
	    {
	            display_table_href($this->db,"SELECT '<a href=index.php?BOX=1&EVENT_ID=' || id || '>' || type_code || ' > ' || substr(status_code || '__________',1,12) || code || ' ' || summary || '</a>' as 'Est rattaché à' from event_vw where id = $this->parent_id","");
            }
	    display_table_href($this->db,"SELECT '<a href=index.php?BOX=1&EVENT_ID=' || id || '>' || type_code || ' > ' || substr(status_code || '__________',1,12) || code || ' ' || summary || '</a>' as 'Rattachements' from event_vw where parent_id = $this->id","");
	    if ($this->file_nbr != '0')
	    {
		display_table($this->db,"SELECT '<a href=''index.php?MODL=GETA&amp;OBJECT=event&amp;TYPE=' || subtype || '&amp;ID=' || event_id || '&amp;HISTORY_ID=' || event_history_id || '&amp;FILENAME=' || name || ''' target=''GSP_ATT''>' || name || '</a>' as 'Pi&egrave;ces jointes tout historique confondu' from event_history_file where event_id = $this->id");

	    }
	    manage_table("&MODL=MAIN&ACTION=HISTORY_UPDATE_PREPARE&TEXTTYPE=$_REQUEST[TEXTTYPE]","HISTO_ID",$this->db,"SELECT id, strftime('%Y.%m.%d',date) as Date,strftime('%H:%M',date) as Heure, contact_code as De, mail_to as A, description as Description, type_code as Type from event_history_vw where event_id = $this->id order by 2 desc, 3 desc, 1 desc",0,0);
	    return "On affiche l'événement";
	}

        function list_for_attach()
	{
		echo "<br/>";
		manage_table("&MODL=MAIN&ACTION=EVENT_ATTACH_MAIL_PREPARE&MAIL_ID=$_REQUEST[MAIL_ID]&TEXTTYPE=$_REQUEST[TEXTTYPE]&TABLE1=TABLE1","EVENT_ID",$this->db,"SELECT id, code as No, appl_code as Appl, segment_code as Objet, main_ext_code as 'Code externe', summary as Description, owner as Suivi, status_code as Statut, last_modif_status_d || ' j' as Depuis, priority_code || '<pri_rank>' || priority_rank || '</pri_rank>' as 'Urgence', contact as Contact from event_vw where state='open' and owner_usercode='$_COOKIE[GSP_USER]' order by id desc",1,1);
		echo "<br/>";
		manage_table("&MODL=MAIN&ACTION=EVENT_ATTACH_MAIL_PREPARE&MAIL_ID=$_REQUEST[MAIL_ID]&TEXTTYPE=$_REQUEST[TEXTTYPE]&TABLE2=TABLE2","EVENT_ID",$this->db,"SELECT id, code as No, appl_code as Appl, segment_code as Objet, main_ext_code as 'Code externe', summary as Description, owner as Suivi, status_code as Statut, last_modif_status_d || ' j' as Depuis, priority_code || '<pri_rank>' || priority_rank || '</pri_rank>' as 'Urgence', contact as Contact from event_vw where state='open' and (owner_usercode!='$_COOKIE[GSP_USER]' or owner_usercode isnull) order by id desc",1,1);
	}

	function attach()
	{
	    echo "<script language='javascript'>alert('FONCTION NON ENCORE CODEE !!!')</script>;";
	    return "on attache l'événement a un autre";
	}

	function detach()
	{
	    echo "<script language='javascript'>alert('FONCTION NON ENCORE CODEE !!!')</script>;";
	    return "on détache l'événement d'un autre";
        }

	function create_prepare()
	{
            echo "<div id='dict_treeID' style='position:absolute;z-index:1;visibility:hidden;border-style:solid;border-width:2px;background-color:white; border-color:black;padding:5px;margin:5px'></div>";
	    echo "<input type='hidden' name='ACTION' value='EVENT_CREATE_CONFIRM'>";
            echo "<input type='hidden' name='SELCURR' disabled='disabled'>";

	    if($_REQUEST[MAIL_ID] == "0" || $_REQUEST[MAIL_ID] == "")
	    {
		echo "<b>Nouvel Ev&eacute;nement</b><br>";
	    }
	    else
	    {
		echo "<b>Nouvel Ev&eacute;nement à partir d'un mail</b><br>";
		$mail = new mail();

		preg_match("`\w(?:[-_.]?\w)*@\w(?:[-_.]?\w)*\.(?:[a-z]{2,4})`",$mail->mail_from,$contact_email_array);
		$contact_email=addslashes($contact_email_array[0]);	

		$contact_id=return_query($this->db,"select id from contact where email = \"$contact_email\"");
		if($mail->locked_f != 1)
		{
            		$query="UPDATE gsp_inbox.inbox set locked_f=1, last_user_id=:GSP_USER_ID where id=:MAIL_ID";
            		$row = $this->db->prepare($query);
            		$row->bindParam(':MAIL_ID', $_REQUEST[MAIL_ID] , PDO::PARAM_INT);
			$row->bindParam(':GSP_USER_ID', $this->gsp_user_id , PDO::PARAM_INT);
            		$row->execute();
            		sqlerror($this->db,$query);
		}
		else
		{
			$last_user_name=return_query($this->db,"select name from user where id = $mail->last_user_id");
			$gsp_user_name=return_query($this->db,"select name from user where code = '$_COOKIE[GSP_USER]'");
			if($gsp_user_name != $last_user_name)
			{
				echo "<div class='ERR'>ATTENTION : Ce mail est en cours de traitement par $last_user_name depuis le $mail->last_modif_d</div><br>";
			}
		}
		if($contact_id == "")
		{
			echo "<div class='ERR'>ATTENTION : Aucun contact existant avec comme email : " . stripslashes($contact_email) . "</div></br>";
		}
		else
		{
			$contact_name=return_query($this->db,"select name from contact where id = $contact_id");
		}
		if($mail->file_nbr != "0")
		{
			$arefiles="yes";
	    	}
		if($mail->mail_size >= "2000000")
		{
			echo "<div class='ERR'>ATTENTION : Les fichiers joints sont trop volumineux / Ne les sauvegarder qu'en cas d'absolue nécessité</div></br>";
			echo "<script language='javascript'>alert('ATTENTION : TAILLE DU MAIL \> 2MB !!!')</script>";
		}
	    }
            echo "<input type='hidden' name='MAIL_ID' value='$mail->id'></input>";
	    echo "<input type='hidden' name='MAIL_DATE' value='$mail->mail_date'></input>";
	    echo "<input type='hidden' name='MAIL_FROM' value='$mail->mail_from'>";
	    echo "<input type='hidden' name='MAIL_TO' value='$mail->mail_to'>";
	    echo "<input type='hidden' name='MAIL_CC' value='$mail->mail_cc'>";

            echo "<center>";
	    if($_REQUEST[MAIL_ID] == "0" || $_REQUEST[MAIL_ID] == "")
	    {
			
		echo "<a href='javascript:parent.dhxWins.window(\"win_module_$_REQUEST[MODL]\").close();' title='FERMER'><img src='pict/back.png' border=0 alt='RETOUR'></a>";	
	    }
	    else
	    {
		echo "<a href='javascript:history.go(-1)' title='RETOUR'><img src='pict/back.png' border=0 alt='RETOUR'></a>";
	    }

	    echo "</center>";

	    echo "<table class='T2'><tr><td>";

	    echo "<table class='T2'><tr><td class='TD2'>";
	    echo return_query($this->db,"select description from dict_vw where code = 'type'");
	    echo "</tr><tr><td class='TD2A'>";
	    echo " <select size='1' name = 'TYPE'>";
	    echo return_query_form_options($this->db, $this->type, "select dict_id, description from dict_vw where parent_code = 'type' order by rank_n");
 	    echo "</select>";
	    echo "</td></tr></table>";

	    echo "</td><td>";
	
            if(return_query($this->db,"select active_f from dict_vw where code = 'impact'") == '1')
	    {
	    	echo "<table class='T2'><tr><td class='TD2'>";
	    	echo return_query($this->db,"select description from dict_vw where code = 'impact'");
	    	echo "</tr><tr><td class='TD2A'>";
	    	echo " <select size='1' name = 'IMPACT'>";
	    	echo return_query_form_options($this->db, $this->type, "select dict_id, description from dict_vw where parent_code = 'impact' order by rank_n");
 	    	echo "</select>";
	    	echo "</td></tr></table>";

	    	echo "</td><td>";
	    }

	    echo "<table class='T2'><tr><td class='TD2'>";
            echo return_query($this->db,"select description from dict_vw where code = 'status'");
	    echo "</tr><tr><td class='TD2A'>";
            echo " <select size='1' name = 'STATUS'>";
            echo "<optgroup label='" . return_query($this->db,"select code from dict_vw where parent_code = 'status' and code = 'open'") . "'>";
            echo return_query_form_options($this->db, $this->status, "select dict_id, code from dict_vw where parent_code = 'open' and active_f=1");
            echo "</optgroup>";
            echo "<optgroup label='" . return_query($this->db,"select code from dict_vw where parent_code = 'status' and code = 'closed'") . "'>";
            echo return_query_form_options($this->db, $this->status, "select dict_id, code from dict_vw where parent_code = 'closed' and active_f=1");
            echo "</optgroup>";
            echo "</select>";
            echo "</td></tr></table>";            

            echo "</td><td>";

            echo "<table class='T2'><tr><td class='TD2'>";
            echo "Auto-assignation";
            echo "</tr><tr><td class='TD2A' align='center'>";
            echo "<input type='checkbox' name='AUTOASSIGN'>";
            echo "</select>";
            echo "</td></tr></table>";

	    echo "</td><td colspan=3>";

	    echo "<table class='T2'><tr><td class='TD2'>";
	    echo "R&eacute;sum&eacute;";
            echo "</tr><tr><td class='TD2A'>";
	    echo "<input name='SUMMARY' type='text' size=79 value=\"$mail->mail_subject\"></input><br>";
	    echo "</td></tr></table>";

	    echo "</td><td>";

	    echo "<table class='T2'><tr><td class='TD2'>";
            echo "Contact ";
	    echo "</tr><tr><td class='TD2A'>";
	    echo "<input type=hidden name='CONTACT' value='$contact_id'>";
	    echo "<input type=text name='CONTACT_NAME' size='25' value=\"$contact_name\" disabled='disabled'>";
	    echo "<input type='button' value='...' onclick='code:draw_tree_contact();document.getElementById(\"dict_treeID\").style.visibility = \"visible\"';>";
            echo "</td></tr></table>";

	    echo "</td></tr></table><table class='T2'><tr><td>";

	    echo "<table class='T2'><tr><td class='TD2'>";
	    echo return_query($this->db,"select description from dict_vw where code = 'appl'");
	    echo "</tr><tr><td class='TD2A'>";
	    echo " <select size='1' name = 'APPL' onchange='code:prepare_form()'>";
	    echo "<option></option>";
	    echo return_query_form_options($this->db, $this->appl, "select dict_id, description from dict_vw where parent_code = 'appl' and active_f = 1");
	    echo "</select>";
	    echo "</td></tr></table>";

	    echo "</td><td>";

	    echo "<table class='T2'><tr><td class='TD2'>";
	    echo return_query($this->db,"select description from dict_vw where code = 'packaging' and active_f = 1");
	    echo "</tr><tr><td class='TD2A'>";
	    echo "<input type=hidden name='PACK'>";
	    echo "<input type=text name='PACK_NAME' size=30 disabled='disabled'>";
	    echo "<input type='button' value='...' name='SEL_PACK' disabled='disabled' onclick='code:if (document.detevent.APPL.value != \"\"){draw_tree_packaging(dict_tree_packaging[document.detevent.APPL.value]);document.getElementById(\"dict_treeID\").style.visibility = \"visible\"}';>";
	    echo "<br>";
	    echo "</td></tr></table>";

	    echo "</td><td>";

	    echo "<table class='T2'><tr><td class='TD2'>";
            echo return_query($this->db,"select description from dict_vw where code = 'segmentation' and active_f = 1");
	    echo "</tr><tr><td class='TD2A'>";
	    echo "<input type=hidden name='SEGM'>";
	    echo "<input type=text name='SEGM_NAME' size=30 disabled='disabled'>";
	    echo "<input type='button' value='...' name='SEL_SEGM' disabled='disabled' onclick='code:if (document.detevent.APPL.value != \"\"){draw_tree_segmentation(dict_tree_segmentation[document.detevent.APPL.value]);document.getElementById(\"dict_treeID\").style.visibility = \"visible\"}';>";
            echo "</td></tr></table>";

	    echo "</td><td>";

	    echo "<table class='T2'><tr><td class='TD2'>";
            echo return_query($this->db,"select description from dict_vw where code = 'severity'");
	    echo "</tr><tr><td class='TD2A'>";
            echo " <select size='3' name = 'SEVERITY'>";
            echo return_query_form_options($this->db, $this->priority, "select dict_id, description from dict_vw where parent_code = 'severity' and active_f = 1");
            echo "</select>";
            echo "</td></tr></table>";

	    echo "</td><td>";

	    echo "<table class='T2'><tr><td class='TD2'>";
            echo return_query($this->db,"select description from dict_vw where code = 'priority'");
	    echo "</tr><tr><td class='TD2A'>";
            echo " <select size='3' name = 'PRIORITY'>";
            echo return_query_form_options($this->db, $this->priority, "select dict_id, description from dict_vw where parent_code = 'priority' and active_f = 1");
            echo "</select>";
            echo "</td></tr></table>";

	    echo "</td><td>";

	    echo "<table class='T2'><tr><td class='TD2'>";
	    echo "Bloquant";
	    echo "</tr><tr><td class='TD2A' align='center'>";
	    echo "<input type='checkbox' name='BLOCKING'>";
	    echo "</select>";
            echo "</td></tr></table>";

            if($arefiles=="yes")
            {
            	echo "</td><td>";

            	echo "<table class='T2'><tr><td class='TD2'>";
            	echo "Pi&egrave;ces jointes";
            	echo "</tr><tr><td class='TD2A'>";
                echo "<input type='hidden' name='FILE_NBR' value='$mail->file_nbr'>";
                return_query_form_checkbox($this->db,"all","FILE","select id, name from gsp_inbox.$mail->inbox_table_att where mail_id = $mail->id and name != ''");
            	echo "</td></tr></table>";
            }

            echo "</td></tr></table><table class='T2'><tr><td>";


            echo "<table class='T2'><tr><td class='TD2'>";
            echo "Notification";
            echo "</tr><tr><td class='TD2A' align='center'>";
            echo "Envoyer notification au contact <input type='checkbox' name='SEND_NOTIF'>";
            echo "</select>";                                                                                                                                             echo "</td></tr></table>";

	    echo "</td><td>";

            echo "<table class='T2'><tr><td class='TD2'>";
            echo "Remarque";
            echo "</tr><tr><td class='TD2A' align='center'>";
            echo "<input type='text' size=120 name='REMARK'>";
            echo "</select>";                                                                                                                                             echo "</td></tr></table>";

	    echo "</td></tr></table>";
	    
	    $list_plugin=return_query_array($this->db,"select id, code, name from plugin where active_f = 1 and installed_f = 1 order by code");
	    foreach ($list_plugin as $id => $plugin)
	    {
		$curr_plugin=new plugin("$plugin[1]");
		$curr_plugin->load_method("event_create_prepare");
	    }

	    echo " 
	    <table class='T2'>
	    <tr><td class='TD2'>
	    Description
	    </td></tr>
	    <tr><td class='TD2A' width='1200'>";
	    include("ext/ckeditor/ckeditor.php");
	    $CKEditor = new CKEditor();
	    $CKEditor->basePath = 'ext/ckeditor/';
	    $CKEditor->config['height'] = 140;
	    $CKEditor->editor("bodytext", $mail->mail_body);

	    echo "</tr></td>
	    </table>";

            echo "
            <script type='text/javascript' src='ext/dhtmlx/dhtmlxcommon.js'></script>
            <script type='text/javascript' src='ext/dhtmlx/dhtmlxtree.js'></script>
            ";

            echo "<script language='javascript'>

            function prepare_form()
            {
                if (document.detevent.APPL.value != '')
                {
                	document.getElementById('detevent').SEL_PACK.disabled = '';
                	document.getElementById('detevent').SEL_SEGM.disabled = '';
                }
		else
		{
			document.getElementById('detevent').SEL_PACK.disabled = 'disabled';
                        document.getElementById('detevent').SEL_SEGM.disabled = 'disabled';
		}
                document.getElementById('detevent').PACK.value = '';
                document.getElementById('detevent').PACK_NAME.value = '';
                document.getElementById('detevent').SEGM.value = '';
                document.getElementById('detevent').SEGM_NAME.value = '';
                document.getElementById('dict_treeID').style.visibility = 'hidden';
            }
            function fill_form(id)
            {
                if(id.substring(0,3) == 'sub' && document.getElementById('detevent').SELCURR.value == 'PACK')
                {
                        document.getElementById('detevent').PACK.value = id.substring(3,id.length);
                        document.getElementById('detevent').PACK_NAME.value = tree.getUserData(id,'description');
                        document.getElementById('dict_treeID').style.visibility = 'hidden';
                }
                if(id.substring(0,3) == 'sub' && document.getElementById('detevent').SELCURR.value == 'SEGM')
                {
                        document.getElementById('detevent').SEGM.value = id.substring(3,id.length);
                        document.getElementById('detevent').SEGM_NAME.value = tree.getUserData(id,'description');
                        document.getElementById('dict_treeID').style.visibility = 'hidden';
                }
                if(id.substring(0,3) == 'sub' && document.getElementById('detevent').SELCURR.value == 'CONTACT')
                {
                        document.getElementById('detevent').CONTACT.value = id.substring(3,id.length);
                        document.getElementById('detevent').CONTACT_NAME.value = tree.getUserData(id,'name')
                        document.getElementById('dict_treeID').style.visibility = 'hidden';
                }
            }
            </script>";

            echo "\n<script language='javascript'>";
	    echo "var dict_tree_packaging = new Array();";
            echo "var dict_tree_segmentation = new Array();";
		
            echo "
                 tree=new dhtmlXTreeObject('dict_treeID','100%','100%',0);
                 tree.setImagePath('ext/dhtmlx/imgs/');
                 tree.setOnClickHandler(fill_form);
               </script>
            ";

            echo "<script type='text/javascript'><!--
                  function draw_tree_contact()
                  {
                      document.getElementById('detevent').SELCURR.value = 'CONTACT';
                      tree.deleteChildItems(0);
                      tree.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=contact', function(){open_items(tree);});
                      document.getElementById('dict_treeID').style.left = (((document.body.offsetWidth - 100) / 2) + 'px') ;
                      document.getElementById('dict_treeID').style.top  = (((document.body.offsetHeight - 350) / 2) + 'px');
                  }

                  function draw_tree_packaging(dict_tree_packaging)
                  {
                      document.getElementById('detevent').SELCURR.value = 'PACK';
                      tree.deleteChildItems(0);
                      tree.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=package&APPL=' + document.detevent.APPL.value, function(){open_items(tree);});
                      document.getElementById('dict_treeID').style.left = (((document.body.offsetWidth - 100) / 2) + 'px') ;
                      document.getElementById('dict_treeID').style.top  = (((document.body.offsetHeight - 350) / 2) + 'px');
                  }

                  function draw_tree_segmentation(dict_tree_segmentation)
                  {
                      document.getElementById('detevent').SELCURR.value = 'SEGM';
                      tree.deleteChildItems(0);
                      tree.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=segment&APPL=' + document.detevent.APPL.value, function(){open_items(tree);});
                      document.getElementById('dict_treeID').style.left = (((document.body.offsetWidth - 100) / 2) + 'px') ;
                      document.getElementById('dict_treeID').style.top  = (((document.body.offsetHeight - 350) / 2) + 'px');
                  }

                  function open_items(tree)
                  {
			tab_item=tree.getSubItems(0).split(',');
			for (var i=0;i<tab_item.length;i++)
			{
				tree.openItem(tab_item[i]);
			}
                  }
                  --></script>";

	    echo "</tr></table>";

	    echo "<input type= 'submit' value='Sauver'>";
	    return "on prÅpare la crÅation de l'événement";
	}

	function create_submit()
	{
	    if($_REQUEST[CONTACT] == "" || $_REQUEST[PRIORITY] == "" || $_REQUEST[APPL] == "" || $_REQUEST[SEGM] == "" || $_REQUEST[PACK] == "" || $_REQUEST[PRIORITY] == "")
	    {
		if($_REQUEST[CONTACT] == ""){echo "<div class='ERR'>Erreur : Aucun contact choisi</div>";}
		if($_REQUEST[APPL] == ""){echo "<div class='ERR'>Erreur : Aucune application choisie</div>";}
		if($_REQUEST[PACK] == ""){echo "<div class='ERR'>Erreur : Aucun package choisi</div>";}
		if($_REQUEST[SEGM] == ""){echo "<div class='ERR'>Erreur : Aucune segmentation choisie</div>";}
		if($_REQUEST[PRIORITY] == ""){echo "<div class='ERR'>Erreur : Aucune priorité choisie</div>";}
		echo "<br/><a href='javascript:history.go(-1)' title='RETOUR'><img src='pict/back.png' border=0 alt='RETOUR'></a>";
		exit;
            }

	    if($_REQUEST[BLOCKING] == "on")
	    {
		$_REQUEST[BLOCKING]="1";
	    }
	    else
	    {
		$_REQUEST[BLOCKING]="0";
	    }

	    
	    if($_REQUEST[SEND_NOTIF] == "on")
	    {
		$_REQUEST[SEND_NOTIF]="1";
	    }
	    else
	    {
		$_REQUEST[SEND_NOTIF]="0";
	    }

            $query="INSERT INTO event (type_dict_id, summary, remark, status_dict_id, owner_id, contact_id, appl_dict_id, package_id, segment_id, impact_dict_id, priority_dict_id, severity_dict_id, blocking_f, notified_f) VALUES (:TYPE, :SUMMARY, :REMARK, 0, :AUTOASSIGN, :CONTACT, :APPL, :PACK, :SEGM, :IMPACT, :PRIORITY, :SEVERITY, :BLOCKING, :NOTIFIED)";
            $row = $this->db->prepare($query);
            $row->bindParam(':TYPE',     $_REQUEST[TYPE],     PDO::PARAM_INT);
            if ($_POST[AUTOASSIGN] == "on")
            {
                $row->bindParam(':AUTOASSIGN', $this->gsp_user_id,   PDO::PARAM_INT);
            }
            $row->bindParam(':SUMMARY',  $_REQUEST[SUMMARY],    PDO::PARAM_STR);
            $row->bindParam(':REMARK',   $_REQUEST[REMARK],     PDO::PARAM_STR);
            $row->bindParam(':CONTACT',  $_REQUEST[CONTACT],    PDO::PARAM_INT);
            $row->bindParam(':APPL',     $_REQUEST[APPL],       PDO::PARAM_INT);
	    $row->bindParam(':PACK',     $_REQUEST[PACK],       PDO::PARAM_INT);
	    $row->bindParam(':SEGM',     $_REQUEST[SEGM],       PDO::PARAM_INT);
            $row->bindParam(':IMPACT',   $_REQUEST[IMPACT],     PDO::PARAM_INT);
            $row->bindParam(':PRIORITY', $_REQUEST[PRIORITY],   PDO::PARAM_INT);
	    $row->bindParam(':SEVERITY', $_REQUEST[SEVERITY],   PDO::PARAM_INT);
	    $row->bindParam(':BLOCKING', $_REQUEST[BLOCKING],   PDO::PARAM_INT);
	    $row->bindParam(':NOTIFIED', $_REQUEST[SEND_NOTIF], PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);

            $NEW_EVENT_ID=$this->db->lastInsertId();

            $query="UPDATE event set status_dict_id = :STATUS where id = $NEW_EVENT_ID";
            $row = $this->db->prepare($query);
            $row->bindParam(':STATUS',   $_REQUEST[STATUS],   PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);

            $query="INSERT into event_history(event_id, date_d, mail_from, mail_to, mail_cc, type_dict_id, status_dict_id, description, contact_id) VALUES (:EVENT_ID, :MAIL_DATE, :MAIL_FROM, :MAIL_TO, :MAIL_CC, :HISTORY_TYPE, :STATUS, :DESCRIPTION, :CONTACT)";
            $row = $this->db->prepare($query);
            $row->bindParam(':EVENT_ID',     $NEW_EVENT_ID, PDO::PARAM_INT);
	    if ($_POST[MAIL_DATE] != "")
	    {
	    	$row->bindParam(':MAIL_DATE',    $_POST[MAIL_DATE], PDO::PARAM_STR);
	    }

	    $row->bindParam(':MAIL_FROM',    $_POST[MAIL_FROM], PDO::PARAM_STR);
	    $row->bindParam(':MAIL_TO',      $_POST[MAIL_TO], PDO::PARAM_STR);
	    $row->bindParam(':MAIL_CC',      $_POST[MAIL_CC], PDO::PARAM_STR);
            $row->bindParam(':HISTORY_TYPE', $this->main_history_type, PDO::PARAM_INT);
            $row->bindParam(':STATUS',       $_POST[STATUS], PDO::PARAM_INT);
            $row->bindParam(':DESCRIPTION',  $_POST["bodytext"], PDO::PARAM_STR);
            $row->bindParam(':CONTACT',      $_POST[CONTACT], PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);


	    $list_plugin=return_query_array($this->db,"select id, code, name from plugin where active_f = 1 and installed_f = 1 order by code");
	    foreach ($list_plugin as $id => $plugin)
	    {       
		$curr_plugin=new plugin("$plugin[1]");
		$curr_plugin->load_method("event_create_submit",$NEW_EVENT_ID);
	    }

	    if($_REQUEST[FILE] != "")
	    {
		$NEW_EVENT_HISTORY_ID=$this->db->lastInsertId();
	    	$file_id=$_REQUEST[FILE];
		$file_nbr=$_REQUEST[FILE_NBR];
		$countrecord=0;
		$countfile=0;
		while($countrecord < $file_nbr)
		{
			$countrecord++;
			if($file_id[$countrecord] != "")
			{
				$countfile++;
                        	$query="INSERT into event_history_file(event_id, event_history_id, rank_n, type, subtype, name, data) select $NEW_EVENT_ID, $NEW_EVENT_HISTORY_ID, $countfile, type, subtype, name, data from gsp_inbox.inbox_attachment where id = :FILE_ID";
				$row = $this->db->prepare($query);
				$row->bindParam(':FILE_ID',$file_id[$countrecord], PDO::PARAM_INT);
			        $row->execute();
				sqlerror($this->db,$query);
			}
		}
		$file_search=array("@;ID=$_POST[MAIL_ID]@si","@amp;OBJECT=mail@si","@&amp;@si");
		$file_replace=array(";ID=$NEW_EVENT_ID","amp;OBJECT=event&HISTORY_ID=$NEW_EVENT_HISTORY_ID","&");
                $DESCRIPTION=preg_replace($file_search,$file_replace,$_POST["bodytext"]);

		$query="UPDATE event_history set description = :DESCRIPTION where id = :EVENT_HISTORY_ID";
		$row = $this->db->prepare($query);
		$row->bindParam(':DESCRIPTION',      $DESCRIPTION, PDO::PARAM_STR);
		$row->bindParam(':EVENT_HISTORY_ID', $NEW_EVENT_HISTORY_ID, PDO::PARAM_INT);
		$row->execute();
		sqlerror($this->db,$query);
	    }

	    if($_POST[MAIL_ID] != "")
	    {
            	$query="UPDATE gsp_inbox.inbox set treated_f=1, last_user_id=:GSP_USER_ID where id=:MAIL_ID";
            	$row = $this->db->prepare($query);
            	$row->bindParam(':MAIL_ID',     $_POST[MAIL_ID], PDO::PARAM_INT);
	    	$row->bindParam(':GSP_USER_ID', $this->gsp_user_id , PDO::PARAM_INT);
            	$row->execute();
            	sqlerror($this->db,$query);

		global $GSP_INBOX_DB_ID;
		global $GSP_INBOX_DB_PATH;
		global $GSP_INBOX_DB_TABLE;
	       	mailmove($this->db,$GSP_INBOX_DB_ID,$GSP_INBOX_DB_PATH,$GSP_INBOX_DB_TABLE,$_POST[MAIL_ID]);
	    }

	    if($_REQUEST[SEND_NOTIF] == "1")
	    {
		$MAIL_EVENT_CODE=return_query($this->db,"select code from event where id = $NEW_EVENT_ID");
		$MAIL_EVENT_TYPE=return_query($this->db,"select description from dict where dict_id = $_REQUEST[TYPE]");
		$MAIL_EVENT_SUMMARY=$_REQUEST[SUMMARY];
		$MAIL_EVENT_DESCRIPTION=$_POST["bodytext"];

		$mail_to=return_query($this->db,"select email from contact where id = $_REQUEST[CONTACT]");
		$mail_file='config_notification.html';
		$mail_subject="$MAIL_EVENT_CODE - Votre demande a été prise en compte";

                $fc=file_get_contents($mail_file);
                eval("\$mail_body = \"$fc\";");

		$mail = new mail();
		$mail->send($mail_to,$mail_subject,stripslashes($mail_body));
	    }
	    return "on crÅe l'événement";
	}

	function new_history_submit()
	{
            	if($_REQUEST[SEND_RETURN] == "on")
            	{
			$_REQUEST[SEND_RETURN]="1";
            	}
            	else
            	{
                	$_REQUEST[SEND_RETURN]="0";
            	}

		$query="INSERT into event_history(event_id, date_d, mail_from, mail_to, mail_cc, type_dict_id, status_dict_id, description, contact_id, self_sent_f) VALUES (:EVENT_ID, :MAIL_DATE, :MAIL_FROM, :MAIL_TO, :MAIL_CC, :HISTORY_TYPE, :STATUS, :DESCRIPTION, :CONTACT, :SELF_SENT)";
		$row = $this->db->prepare($query);
		$row->bindParam(':EVENT_ID',     $this->id, PDO::PARAM_INT);
                if ($_POST[MAIL_DATE] != "")
                {
                	$row->bindParam(':MAIL_DATE',    $_POST[MAIL_DATE], PDO::PARAM_STR);
                }
                $row->bindParam(':MAIL_FROM',    $_POST[MAIL_FROM], PDO::PARAM_STR);
                $row->bindParam(':MAIL_TO',      $_POST[MAIL_TO], PDO::PARAM_STR);
                $row->bindParam(':MAIL_CC',      $_POST[MAIL_CC], PDO::PARAM_STR);
		$row->bindParam(':HISTORY_TYPE', $_POST[HISTO_TYPE], PDO::PARAM_INT);
		$row->bindParam(':STATUS',       $_POST[STATUS], PDO::PARAM_INT);
		$row->bindParam(':DESCRIPTION',  $_POST["bodytext"], PDO::PARAM_STR);
		$row->bindParam(':CONTACT',      $_POST[CONTACT], PDO::PARAM_INT);
		$row->bindParam(':SELF_SENT',    $_REQUEST[SEND_RETURN], PDO::PARAM_INT);
		$row->execute();
		sqlerror($this->db,$query);

		if($_REQUEST[FILE] != "")
            	{
                	$NEW_EVENT_HISTORY_ID=$this->db->lastInsertId();
                	$file_id=$_REQUEST[FILE];
                	$file_nbr=$_REQUEST[FILE_NBR];
                	$countrecord=0;
                	$countfile=0;
                	while($countrecord < $file_nbr)
                	{
                        	$countrecord++;
                        	if($file_id[$countrecord] != "")
                        	{
                                	$countfile++;
                                	$query="INSERT into event_history_file(event_id, event_history_id, rank_n, type, subtype, name, data) select :EVENT_ID, $NEW_EVENT_HISTORY_ID, $countfile, type, subtype, name, data from gsp_inbox.inbox_attachment where id = :FILE_ID";
                                	$row = $this->db->prepare($query);
					$row->bindParam(':EVENT_ID',     $this->id, PDO::PARAM_INT);
                                	$row->bindParam(':FILE_ID',$file_id[$countrecord], PDO::PARAM_INT);
                                	$row->execute();
                                	sqlerror($this->db,$query);
                        	}
                	}
                	$file_search=array("@;ID=$_POST[MAIL_ID]@si","@amp;OBJECT=mail@si","@&amp;@si");
                	$file_replace=array(";ID=$this->id","amp;OBJECT=event&HISTORY_ID=$NEW_EVENT_HISTORY_ID","&");
                	$DESCRIPTION=preg_replace($file_search,$file_replace,$_POST["bodytext"]);

                	$query="UPDATE event_history set description = :DESCRIPTION where id = :EVENT_HISTORY_ID";
                	$row = $this->db->prepare($query);
                	$row->bindParam(':DESCRIPTION',      $DESCRIPTION, PDO::PARAM_STR);
                	$row->bindParam(':EVENT_HISTORY_ID', $NEW_EVENT_HISTORY_ID, PDO::PARAM_INT);
                	$row->execute();
                	sqlerror($this->db,$query);
            	}

		if($_POST[MAIL_ID] != "")
		{
			$query="UPDATE gsp_inbox.inbox set treated_f=1, last_user_id=:GSP_USER_ID where id=:MAIL_ID";
			$row = $this->db->prepare($query);
			$row->bindParam(':MAIL_ID',     $_POST[MAIL_ID], PDO::PARAM_INT);
			$row->bindParam(':GSP_USER_ID', $this->gsp_user_id , PDO::PARAM_INT);
                	$row->execute();
                	sqlerror($this->db,$query);

                    	global $GSP_INBOX_DB_ID;
                    	global $GSP_INBOX_DB_PATH;
                    	global $GSP_INBOX_DB_TABLE;
                    	mailmove($this->db,$GSP_INBOX_DB_ID,$GSP_INBOX_DB_PATH,$GSP_INBOX_DB_TABLE,$_POST[MAIL_ID]);
		}

	 	if($_REQUEST[SEND_RETURN] == "1")
	    	{
			$MAIL_USER_NAME=return_query($this->db,"select name from user_vw where code = '$_COOKIE[GSP_USER]'");
			$MAIL_EVENT_CODE=return_query($this->db,"select code from event where id = $this->id");
			$MAIL_EVENT_SUMMARY=$this->summary;
			$MAIL_RETURN_TEXT=$_POST["bodytext"];

			$mail_to=return_query($this->db,"select email from contact where id = $this->contact_id");
			$mail_file=$_SERVER['DOCUMENT_ROOT'] . '/config_return.html';
			$mail_subject="$MAIL_EVENT_CODE:$MAIL_EVENT_SUMMARY";

                	$fc=file_get_contents($mail_file);
                	eval("\$mail_body = \"$fc\";");

			$mail = new mail();
			$mail->send($mail_to,$mail_subject,stripslashes($mail_body));
	    	}
		return "on crÅe le nouvel historique";
	}

	function new_history_prepare()
	{
	    echo "<div id='dict_treeID' style='position:absolute;z-index:1;visibility:hidden;border-style:solid;border-width:2px;background-color:white; border-color:black;padding:5px;margin:5px'></div>";

	    echo "<input type='hidden' name='EVENT_ID' value='$this->id'></input>";
            echo "<input type='hidden' name='ACTION' value='NEW_HISTORY_SUBMIT'></input>";
            if($_REQUEST[MAIL_ID] == "0" || $_REQUEST[MAIL_ID] == "")
            {
                echo "<b>Nouvel Historique</b>";
		$contact_id=return_query($this->db,"select contact_id from user_vw where code = '$_COOKIE[GSP_USER]'");
		$contact_name=return_query($this->db,"select name from contact where id = '$contact_id'");
		$form_send_return="<input type='checkbox' name='SEND_RETURN'>";
            }
            else
            {
                echo "<b>Nouvel Historique à partir d'un mail</b>";
                $mail = new mail();

                preg_match("`\w(?:[-_.]?\w)*@\w(?:[-_.]?\w)*\.(?:[a-z]{2,4})`",$mail->mail_from,$contact_email_array);
		$contact_email=addslashes($contact_email_array[0]);

                $contact_id=return_query($this->db,"select id from contact where email = \"$contact_email\"");
                if($contact_id == "")
		{
			echo "<div class='ERR'>ATTENTION : Aucun contact existant avec comme email : " . stripslashes($contact_email) . "</div></br>";
		}
		else
		{
			$contact_name=return_query($this->db,"select name from contact where id = $contact_id");
		}
		if($mail->locked_f != 1)
                {
                        $query="UPDATE gsp_inbox.inbox set locked_f=1, last_user_id=:GSP_USER_ID where id=:MAIL_ID";
                        $row = $this->db->prepare($query);
                        $row->bindParam(':MAIL_ID', $_REQUEST[MAIL_ID] , PDO::PARAM_INT);
                        $row->bindParam(':GSP_USER_ID', $this->gsp_user_id , PDO::PARAM_INT);
                        $row->execute();
                        sqlerror($this->db,$query);
                }
                else
                {
                        $last_user_name=return_query($this->db,"select name from user where id = $mail->last_user_id");
                        $gsp_user_name=return_query($this->db,"select name from user where code = '$_COOKIE[GSP_USER]'");
                        if($gsp_user_name != $last_user_name)
                        {
                                echo "<div class='ERR'>ATTENTION : Ce mail est en cours de traitement par $last_user_name depuis le $mail->last_modif_d</div><br>";
                        }
                }
		if($mail->file_nbr != "0")
                {
                        $arefiles="yes";
                }
                if($mail->mail_size >= "2000000")
                {
			echo "<div class='ERR'>ATTENTION : Les fichiers joints sont trop volumineux / Ne les sauvegarder qu'en cas d'absolue nécessité</div></br>";
                        echo "<script language='javascript'>alert('ATTENTION : TAILLE DU MAIL \> 2MB !!!')</script>";
                }
            }
            echo "<input type='hidden' name='MAIL_ID' value='$mail->id'></input>";
	    echo "<input type='hidden' name='MAIL_DATE' value='$mail->mail_date'></input>";
            echo "<input type='hidden' name='MAIL_FROM' value='$mail->mail_from'></input>";
            echo "<input type='hidden' name='MAIL_TO' value='$mail->mail_to'></input>";
            echo "<input type='hidden' name='MAIL_CC' value='$mail->mail_cc'></input>";

            echo "<center>";
	    display_table($this->db,"SELECT code as No, summary as Description, status_code as Statut, owner as Suivi, opened_d as Date, priority_code as 'Urgence', contact_name as Contact from event_vw where id = $this->id");
            echo "<a href='javascript:history.go(-1)' title='RETOUR'><img src='pict/back.png' border=0 alt='RETOUR'></a>";
            echo "</center>";

            echo "<table class='T2'><tr><td>";

            echo "<table class='T2'><tr><td class='TD2'>";
            echo "Auteur";
            echo "</tr><tr><td class='TD2A'>";
            echo "<input type=hidden name='CONTACT' value='$contact_id'>";
            echo "<input type=text name='CONTACT_NAME' size='30' value=\"$contact_name\" disabled='disabled'>";
            echo "<input type='button' value='...' onclick='code:draw_tree_contact();document.getElementById(\"dict_treeID\").style.visibility = \"visible\"';>";
            echo "</td></tr></table>";

            echo "</td><td>";

            echo "
            <script type='text/javascript' src='ext/dhtmlx/dhtmlxcommon.js'></script>
            <script type='text/javascript' src='ext/dhtmlx/dhtmlxtree.js'></script>
                ";

            echo "<script type='text/javascript'>";
            echo "
                function fill_form(id)
        	{
                        document.getElementById('detevent').CONTACT.value = id.substring(3,id.length);
                        document.getElementById('detevent').CONTACT_NAME.value = tree.getUserData(id,'name')
                        document.getElementById('dict_treeID').style.visibility = 'hidden';
        	} 


                 tree=new dhtmlXTreeObject('dict_treeID','100%','100%',0);
                 tree.setImagePath('ext/dhtmlx/imgs/');
                 tree.setOnClickHandler(fill_form);
                 tree.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=contact', function(){tree.openItem(tree.getSubItems(0));});
               </script>
            ";


            echo "<script type='text/javascript'>
            function fill_subform(ID, NAME)
            {
                  document.getElementById('detevent').CONTACT.value = ID;
                  document.getElementById('detevent').CONTACT_NAME.value = NAME;
                  document.getElementById('dict_treeID').style.visibility = 'hidden';
	    ";
	    if($_REQUEST[MAIL_ID] == "0" || $_REQUEST[MAIL_ID] == "")
	    {
		  echo "	
		  if (ID == $contact_id)
		  {
			document.getElementById('detevent').SEND_RETURN.disabled = '';
		  }
		  else
		  {
			 document.getElementById('detevent').SEND_RETURN.checked = false;
			 document.getElementById('detevent').SEND_RETURN.disabled = 'DISABLED';
		  }";
	    }
            echo "
	    }
            </script>";

            echo "<script type='text/javascript'><!--
		  function draw_tree_contact()
		  {
                      document.getElementById('dict_treeID').style.left = (((document.body.offsetWidth - 100) / 2) + 'px') ;
                      document.getElementById('dict_treeID').style.top  = (((document.body.offsetHeight - 350) / 2) + 'px');
                  }

                  --></script>";

            echo "<table class='T2'><tr><td class='TD2'>";
            echo return_query($this->db,"select description from dict_vw where code = 'historique'");
            echo "</tr><tr><td class='TD2A'>";
            echo " <select size='1' name = 'HISTO_TYPE'>";
            echo return_query_form_options($this->db, $this->default_history_type, "select dict_id, description from dict_vw where code IN('retour','note')");
            echo "</select>";
            echo "</td></tr></table>";

            echo "</td><td>";

	    echo "<table class='T2'><tr><td class='TD2'>";
            echo "Nouveau statut";
	    echo "</tr><tr><td class='TD2A'>";
            echo " <select size='1' name = 'STATUS'>";
            echo "<optgroup label='" . return_query($this->db,"select code from dict_vw where parent_code = 'status' and code = 'open'") . "'>";
            echo return_query_form_options($this->db, $this->status, "select dict_id, code from dict_vw where parent_code = 'open' and active_f=1");
            echo "</optgroup>";
            echo "<optgroup label='" . return_query($this->db,"select code from dict_vw where parent_code = 'status' and code = 'closed'") . "'>";
            echo return_query_form_options($this->db, $this->status, "select dict_id, code from dict_vw where parent_code = 'closed' and active_f=1");
            echo "</optgroup>";
            echo "</select>";
	    echo "</td></tr></table>";

	    if(isset($form_send_return))
            {
			
                echo "</td><td>";

                echo "<table class='T2'><tr><td class='TD2'>";
                echo "Envoyer retour au contact";
                echo "</tr><tr><td class='TD2A' align=CENTER>";
		echo "$form_send_return";
                echo "</td></tr></table>";
	    }

	    if($arefiles=="yes")
	    {
                echo "</td><td>";

                echo "<table class='T2'><tr><td class='TD2'>";
                echo "Pi&egrave;ces jointes";
                echo "</tr><tr><td class='TD2A'>";
                echo "<input type='hidden' name='FILE_NBR' value='$mail->file_nbr'>";
                return_query_form_checkbox($this->db,"all","FILE","select id, name from gsp_inbox.$mail->inbox_table_att where mail_id = $mail->id and name != ''");
                echo "</td></tr></table>";
	    }

            echo "</td></tr></table>";

            echo "
            <table class='T2'>
            <tr><td class='TD2'>
            Description
            </td></tr>
            <tr><td class='TD2A' width='1200'>";

            include("ext/ckeditor/ckeditor.php");
            $CKEditor = new CKEditor();
            $CKEditor->basePath = 'ext/ckeditor/';
	    $CKEditor->config['height'] = 253;
	    $CKEditor->config['removePlugins']='elementspath';
            $CKEditor->editor("bodytext", $mail->mail_body);

            echo "</tr></td>
            </table>";

            echo "<input type= 'submit' value='Sauver'>";
		
	    if($_REQUEST[SEND_RETURN] == "on")
	    {
		$MAIL_USER_NAME=return_query($this->db,"select name from user_vw where code = '$_COOKIE[GSP_USER]'");
		$MAIL_EVENT_CODE=return_query($this->db,"select code from event where id = $this->id");
		$MAIL_EVENT_SUMMARY=$_REQUEST[SUMMARY];
		$MAIL_EVENT_DESCRIPTION=$_POST["bodytext"];

		$mail_to=return_query($this->db,"select email from contact where id = $this->contact_id");
		$mail_file=$_SERVER['DOCUMENT_ROOT'] . '/config_signature.html';
		$mail_subject="$MAIL_EVENT_CODE:$MAIL_EVENT_SUMMARY";

                $fc=file_get_contents($mail_file);
                eval("\$mail_body = \"$fc\";");

		$mail = new mail();
		$mail->send($mail_to,$mail_subject,stripslashes($mail_body));
	    }

            return "on prÅpare le rattachement du mail à un événement";
		
	}
	function update_prepare()
	{
	    echo "<div id='contact_treeID' style='position:absolute;z-index:1;visibility:hidden;border-style:solid;border-width:2px;background-color:white; border-color:black;padding:5px;margin:5px'><center><b>Contacts</b></center><hr></div>";
	    echo "<div id='owner_treeID' style='position:absolute;z-index:1;visibility:hidden;border-style:solid;border-width:2px;background-color:white; border-color:black;padding:5px;margin:5px'><center><b>Utilisateurs</b></center><hr></div>";
	    echo "<div id='segment_treeID' style='position:absolute;z-index:1;visibility:hidden;border-style:solid;border-width:2px;background-color:white; border-color:black;padding:5px;margin:5px'><center><b>Segments applicatifs</b></center><hr></div>";
	    echo "<div id='package_treeID' style='position:absolute;z-index:1;visibility:hidden;border-style:solid;border-width:2px;background-color:white; border-color:black;padding:5px;margin:5px'><center><b>Packaging</b></center><hr></div>";

	    echo "<center>";
            echo "<a href='javascript:history.go(-1)' title='RETOUR'><img src='pict/back.png' border=0 alt='RETOUR'></a>";
            echo "</center>";

	    $list_plugin=return_query_array($this->db,"select id, code, name from plugin where active_f = 1 and installed_f = 1 order by code");
	    foreach ($list_plugin as $id => $plugin)
	    {       
		$curr_plugin=new plugin("$plugin[1]");
		$curr_plugin->load_method("event_update_prepare");
	    }

            $web_form = new web_form("event","edit");
            $web_form->set_list_options("project_id, status, type, appl, severity, priority");
	    //$web_form->set_list_radio("severity, priority");
	    $web_form->set_calendar();
	    $web_form->set_lang_calendar("opened_d, logged_d, asked_d, planif_d, closed_d","fr");
	    
            return_query_webform_options($this->db, "project_id", "", "select id, summary from event_vw where type_code = 'PR' and state = 'open' order by id");
            return_query_webform_options($this->db, "status", "", "select dict_id, code from dict_vw where parent_code in('open','closed')  and active_f = 1 order by parent_code desc, rank_n");
            return_query_webform_options($this->db, "type", "", "select dict_id, description from dict_vw where parent_code = 'type' and active_f = 1 order by rank_n");
            return_query_webform_options($this->db, "appl", "", "select dict_id, description from dict_vw where parent_code = 'appl' and active_f = 1 order by rank_n");
            //return_query_webform_radio($this->db, "severity", "", "select dict_id, description from dict_vw where parent_code = 'severity' and active_f = 1 order by rank_n");
            //return_query_webform_radio($this->db, "priority", "", "select dict_id, description from dict_vw where parent_code = 'priority' and active_f = 1 order by rank_n");

	    
            return_query_webform_options($this->db, "severity", "", "select dict_id, description from dict_vw where parent_code = 'severity' and active_f = 1 order by rank_n");
            return_query_webform_options($this->db, "priority", "", "select dict_id, description from dict_vw where parent_code = 'priority' and active_f = 1 order by rank_n");



	    $web_form->set_freeze_calendar("opened_d, logged_d","fr");
	    $web_form->display($this->id);

            echo "<script language='javascript'>

                function go_fill_contact(id)
                {
                        if(id.substring(0,3) == 'sub')
                        {
				document.getElementById('contact_treeID').style.visibility = 'hidden';
                                fill_form(tree_contact,id);
                        }
                }

                function go_fill_segment(id)
                {
                        if(id.substring(0,3) == 'sub')
                        {
                                document.getElementById('segment_treeID').style.visibility = 'hidden';
                                fill_form(tree_segment,id);
                        }
                }

                function go_fill_package(id)
                {
                        if(id.substring(0,3) == 'sub')
                        {
                                document.getElementById('package_treeID').style.visibility = 'hidden';
                                fill_form(tree_package,id);
                        }
                }

		function go_fill_owner(id)
		{
			document.getElementById('owner_treeID').style.visibility = 'hidden';
			fill_form(tree_owner,id);
		}
                function get_field(tree,id,field)
                {
                        value=tree.getUserData(id,field);
                        if(value==undefined)
                        {
                                value='';
                        }
                        return value;
                }

            	function fill_form(tree,id)
            	{
			if(tree==tree_contact)
			{	    
                    		form.setItemValue('contact_id',id.substring(3,id.length));
                    		form.setItemValue('contact_name',get_field(tree,id,'name'));
			}
			if(tree==tree_owner)
			{
				form.setItemValue('owner_id',id);
                                form.setItemValue('owner_name',get_field(tree,id,'name'));
			}
                        if(tree==tree_segment)
                        {           
                                form.setItemValue('segment_id',id.substring(3,id.length));
                                form.setItemValue('segment_code',get_field(tree,id,'code'));
                        }
                        if(tree==tree_package)
                        {           
                                form.setItemValue('package_id',id.substring(3,id.length));
                                form.setItemValue('package_code',get_field(tree,id,'code'));
                        }
            	}

            </script>";

            echo "\n<script language='javascript'>";

            echo "
                 tree_contact=new dhtmlXTreeObject('contact_treeID','100%','100%',0);
                 tree_contact.setImagePath('ext/dhtmlx/imgs/');
                 tree_contact.setOnClickHandler(go_fill_contact);

                 tree_owner=new dhtmlXTreeObject('owner_treeID','100%','100%',0);
                 tree_owner.setImagePath('ext/dhtmlx/imgs/');
                 tree_owner.setOnClickHandler(go_fill_owner);

                 tree_segment=new dhtmlXTreeObject('segment_treeID','100%','100%',0);
                 tree_segment.setImagePath('ext/dhtmlx/imgs/');
                 tree_segment.setOnClickHandler(go_fill_segment);

                 tree_package=new dhtmlXTreeObject('package_treeID','100%','100%',0);
                 tree_package.setImagePath('ext/dhtmlx/imgs/');
                 tree_package.setOnClickHandler(go_fill_package);

               </script>
            ";

            echo "<script type='text/javascript'><!--
                  function draw_tree_contact()
                  {
                      tree_contact.deleteChildItems(0);
                      tree_contact.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=contact', function(){open_items(tree_contact);});
                      document.getElementById('contact_treeID').style.left = (((parent.document.body.offsetWidth - 480) / 2) + 'px') ;
                      document.getElementById('contact_treeID').style.top  = (((parent.document.body.offsetHeight - 550) / 2) + 'px');
                  }

                  function draw_tree_owner()
                  {
                      tree_owner.deleteChildItems(0);
                      tree_owner.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=owner', function(){open_items(tree_owner);});
                      document.getElementById('owner_treeID').style.left = (((parent.document.body.offsetWidth - 480) / 2) + 'px') ;
                      document.getElementById('owner_treeID').style.top  = (((parent.document.body.offsetHeight - 550) / 2) + 'px');
                  }

                  function draw_tree_package()
                  {
                      tree_package.deleteChildItems(0);
                      tree_package.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=package&APPL=' + form.getItemValue('appl'), function(){open_items(tree_package);});
                      document.getElementById('package_treeID').style.left = (((parent.document.body.offsetWidth - 480) / 2) + 'px') ;
                      document.getElementById('package_treeID').style.top  = (((parent.document.body.offsetHeight - 550) / 2) + 'px');
                  }

                  function draw_tree_segment()
                  {
                      tree_segment.deleteChildItems(0);
                      tree_segment.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=segment&APPL=' + form.getItemValue('appl'), function(){open_items(tree_segment);});
                      document.getElementById('segment_treeID').style.left = (((parent.document.body.offsetWidth - 480) / 2) + 'px') ;
                      document.getElementById('segment_treeID').style.top  = (((parent.document.body.offsetHeight - 550) / 2) + 'px');
                  }

                  function open_items(tree)
                  {
                        tab_item=tree.getSubItems(0).split(',');
                        for (var i=0;i<tab_item.length;i++)
                        {
                                tree.openItem(tab_item[i]);
                        }
                  }
                  --></script>";

	    return "On prépare la mise à jour de l'événement";
	}

	function update_submit()
	{
	    $_REQUEST=utf8_array_decode($_REQUEST); 
	    if($_REQUEST[APPL] == "" || $_REQUEST[SEGM] == "" || $_REQUEST[PACK] == "")
	    {
		if($_REQUEST[appl] == ""){echo "<div class='ERR'>Erreur : Aucune application choisie</div>";}
		if($_REQUEST[package_id] == ""){echo "<div class='ERR'>Erreur : Aucun package choisi</div>";}
		if($_REQUEST[segment_id] == ""){echo "<div class='ERR'>Erreur : Aucune segmentation choisie</div>";}
            }
	    $query="UPDATE event set parent_id = :PARENT_ID, project_id = :PROJECT_ID, type_dict_id = :TYPE, status_dict_id = :STATUS, summary = :SUMMARY, remark = :REMARK, contact_id = :CONTACT, appl_dict_id = :APPL, package_id = :PACK, segment_id = :SEGM, impact_dict_id = :IMPACT, priority_dict_id = :PRIORITY, severity_dict_id = :SEVERITY, blocking_f = :BLOCKING, prod_f = :PROD, asked_d = :ASKED, planif_d = :PLANIF, owner_id = :OWNER where id = :ID";
	    $row = $this->db->prepare($query);


 	    if($_REQUEST[asked_d] == "null") $_REQUEST[asked_d] = "";
	    if($_REQUEST[planif_d] == "null") $_REQUEST[planif_d] = "";

	    $row->bindParam(':ID',       	$_REQUEST[id], 		PDO::PARAM_INT);
	    if($_REQUEST[parent_id] != "")
	    {
            	$row->bindParam(':PARENT_ID',	$_REQUEST[parent_id],	PDO::PARAM_INT);
	    }
	    if($_REQUEST[project_id] != "0")
            {
            	$row->bindParam(':PROJECT_ID',	$_REQUEST[project_id],	PDO::PARAM_INT);
            }
	    $row->bindParam(':TYPE',     	$_REQUEST[type],     	PDO::PARAM_INT);
	    $row->bindParam(':STATUS',   	$_REQUEST[status],   	PDO::PARAM_INT);
	    $row->bindParam(':SUMMARY',  	$_REQUEST[summary],  	PDO::PARAM_STR);
	    $row->bindParam(':REMARK',   	$_REQUEST[remark],   	PDO::PARAM_STR);
	    $row->bindParam(':CONTACT',  	$_REQUEST[contact_id],  PDO::PARAM_INT);
	    $row->bindParam(':APPL',     	$_REQUEST[appl],     	PDO::PARAM_INT);
            $row->bindParam(':PACK',     	$_REQUEST[package_id], 	PDO::PARAM_INT);
            $row->bindParam(':SEGM',     	$_REQUEST[segment_id], 	PDO::PARAM_INT);
	    $row->bindParam(':IMPACT',   	$_REQUEST[IMPACT],   	PDO::PARAM_INT);
	    $row->bindParam(':PRIORITY', 	$_REQUEST[priority], 	PDO::PARAM_INT);
	    $row->bindParam(':SEVERITY', 	$_REQUEST[severity], 	PDO::PARAM_INT);
            $row->bindParam(':BLOCKING', 	$_REQUEST[blocking_f], 	PDO::PARAM_INT);
            $row->bindParam(':PROD', 		$_REQUEST[prod_f], 	PDO::PARAM_INT);
	    $row->bindParam(':ASKED',           $_REQUEST[asked_d],	PDO::PARAM_STR);
	    $row->bindParam(':PLANIF',          $_REQUEST[planif_d],	PDO::PARAM_STR);
	    if($_REQUEST[owner_id] != "")
	    {
	    	$row->bindParam(':OWNER',           $_REQUEST[owner_id],    PDO::PARAM_INT);
	    }
   	    $row->execute();
	    sqlerror($this->db,$query);

	    $list_plugin=return_query_array($this->db,"select id, code, name from plugin where active_f = 1 and installed_f = 1 order by code");
	    foreach ($list_plugin as $id => $plugin)
	    {       
		$curr_plugin=new plugin("$plugin[1]");
		$curr_plugin->load_method("event_update_submit");
	    }

	    return "On met à jour l'événement";
	}

        function update_from_grid()
        {
            $query="UPDATE event set type_dict_id = :TYPE, summary = :SUMMARY, status_dict_id = :STATUS, priority_dict_id = :PRIORITY where id = :ID";

            $row = $this->db->prepare($query);
            $row->bindParam(':ID',       $_REQUEST[id],               PDO::PARAM_INT);
            $row->bindParam(':TYPE',     $_REQUEST[type_dict_id],     PDO::PARAM_INT);
            $row->bindParam(':SUMMARY',  $_REQUEST[summary],          PDO::PARAM_STR);
            $row->bindParam(':STATUS',   $_REQUEST[status_dict_id],   PDO::PARAM_INT);
            $row->bindParam(':PRIORITY', $_REQUEST[priority_dict_id], PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);

	    return "On met à jour l'événement directement depuis la grille";
        }

        function history_update_prepare()
        {
	    echo "<div id='dict_treeID' style='position:absolute;z-index:1;visibility:hidden;border-style:solid;border-width:2px;background-color:white; border-color:black;padding:5px;margin:5px'></div>";
	    echo "<input type='hidden' name='HISTO_ID' value='$_REQUEST[HISTO_ID]'></input>";

            echo "<center>";
            echo "<a href='javascript:history.go(-1)' title='RETOUR'><img src='pict/back.png' border=0 alt='RETOUR'></a>";
            echo "</center>";

            echo "<table class='T2'><tr><td>";

            $histo_id = $_REQUEST[HISTO_ID];
            $histo_type_id=return_query($this->db,"select type_dict_id from event_history where id = $histo_id");
            $histo_type_code=return_query($this->db,"select code from dict where dict_id = $histo_type_id");
            $histo_date=return_query($this->db,"select strftime('%Y-%m-%d',date_d) from event_history where id = $histo_id");
            $histo_time=return_query($this->db,"select strftime('%H:%M:%S',date_d) from event_history where id = $histo_id");
            $contact_id=return_query($this->db,"select contact_id from event_history where id = $histo_id");
            $contact_name=return_query($this->db,"select name from contact where id = '$contact_id'");
	    $event_id=return_query($this->db,"select event_id from event_history where id = $histo_id");

            echo "<table class='T2'><tr><td class='TD2'>";
            echo "Date et heure";
            echo "</tr><tr><td class='TD2A'>";
            echo "<input type=text name='HISTO_DATE' size=9 value='$histo_date'>";
            echo "<input type=text name='HISTO_TIME' size=7 value='$histo_time'>";
	    echo "<input type=hidden name='EVENT_ID' value='$event_id'";
            echo "</td></tr></table></td><td>";

            if ($histo_type_code == "description" || $histo_type_code == "retour" || $histo_type_code == "note")
            {
            	echo "<table class='T2'><tr><td class='TD2'>";
            	echo "Contact ";
            	echo "</tr><tr><td class='TD2A'>";
            	echo "<input type=hidden name='CONTACT' value='$contact_id'>";
            	echo "<input type=text name='CONTACT_NAME' value='$contact_name' disabled='disabled'>";
            	echo "<input type='button' value='...' onclick='code:draw_tree_contact();document.getElementById(\"dict_treeID\").style.visibility = \"visible\"';>";
            	echo "</td></tr></table>";

            	echo "</td><td>";

            echo "
            <script type='text/javascript' src='ext/dhtmlx/dhtmlxcommon.js'></script>
            <script type='text/javascript' src='ext/dhtmlx/dhtmlxtree.js'></script>
                ";

            echo "<script type='text/javascript'>";
            echo "
                function fill_form(id)
        	{
                        document.getElementById('detevent').CONTACT.value = id.substring(3,id.length);
                        document.getElementById('detevent').CONTACT_NAME.value = tree.getUserData(id,'name')
                        document.getElementById('dict_treeID').style.visibility = 'hidden';
        	} 


                 tree=new dhtmlXTreeObject('dict_treeID','100%','100%',0);
                 tree.setImagePath('ext/dhtmlx/imgs/');
                 tree.setOnClickHandler(fill_form);
                 tree.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=contact', function(){tree.openItem(tree.getSubItems(0));});
               </script>
            ";

            echo "<script type='text/javascript'>
                  function draw_tree_contact()
                  {
                      document.getElementById('dict_treeID').style.left = (((document.body.offsetWidth - 100) / 2) + 'px') ;
                      document.getElementById('dict_treeID').style.top  = (((document.body.offsetHeight - 350) / 2) + 'px');
                  }
                  </script>";


            	echo "</td><td>";

            	echo "<table class='T2'><tr><td class='TD2'>";
            	echo return_query($this->db,"select description from dict_vw where code = 'historique'");
            	echo "</tr><tr><td class='TD2A'>";
	    	if($histo_type_id == $this->main_history_type)
	    	{
			$history_type_choice="= 'description'";
	    	}
	    	else
	    	{
			$history_type_choice="IN('retour','note')";
	    	}
	    	echo " <select size='1' name = 'HISTO_TYPE'>";
	    	echo return_query_form_options($this->db, $histo_type_id, "select dict_id, description from dict_vw where code $history_type_choice");
	    	echo "</select>";
            	echo "</td></tr></table>";

            	echo "</td><td>";

            	echo "</td></tr></table>";

            	$DESCRIPTION=return_query($this->db,"select description from event_history where id = $histo_id");

            	echo "
            	<table class='T2'>
            	<tr><td class='TD2'>
            	Description
            	</td></tr>
            	<tr><td class='TD2A' width='1200'>";
                include("ext/ckeditor/ckeditor.php");
                $CKEditor = new CKEditor();
                $CKEditor->basePath = 'ext/ckeditor/';
		$CKEditor->config['height'] = 300;
                $CKEditor->editor("bodytext", $DESCRIPTION);
            	echo "</tr></td>
            	</table>";
            }
            else
	    {
		echo "<input type='hidden' name='TYPE' value='MINIMAL'>";
	    }
	    echo "<input type='hidden' name='ACTION' value='HISTORY_UPDATE_SUBMIT'>";
	    echo "<input type= 'submit' value='Sauver'>";

	    return "On prépare la mise à jour de l'historique";
	}

        function history_update_submit()
	{
	    $histo_date=$_REQUEST[HISTO_DATE] . " " . $_REQUEST[HISTO_TIME];
	    if ($_REQUEST[TYPE] == "MINIMAL")
	    {
		$query="UPDATE event_history set date_d = :DATE where id = :ID";
		$row = $this->db->prepare($query);
	        $row->bindParam(':ID',  $_REQUEST[HISTO_ID],    PDO::PARAM_INT);
                $row->bindParam(':DATE',    $histo_date, PDO::PARAM_STR);
	    }
	    else
	    {
            	$query="UPDATE event_history set type_dict_id = :HISTO_TYPE, date_d = :DATE, description = :DESCRIPTION, contact_id = :CONTACT where id = :ID";
            	$row = $this->db->prepare($query);
            	$row->bindParam(':ID',	$_REQUEST[HISTO_ID],	PDO::PARAM_INT);
            	$row->bindParam(':DATE',    $histo_date, PDO::PARAM_STR);
            	$row->bindParam(':HISTO_TYPE',	$_REQUEST[HISTO_TYPE],	PDO::PARAM_INT);
            	$row->bindParam(':DESCRIPTION',	$_REQUEST[bodytext],	PDO::PARAM_STR);
            	$row->bindParam(':CONTACT',	$_REQUEST[CONTACT],	PDO::PARAM_INT);
            }
            $row->execute();
            sqlerror($this->db,$query);

	    return "On met à jour l'historique";
	}

	function assign_prepare()
	{
	    $assign=new user();
	    $assign->list_users_with_contact();
	    return "On prepare l'assignation de l'événement";
        }

	function assign_submit()
	{
	    $STATUS=return_query($this->db,"select dict_id from dict_vw where code = 'Assigné' and parent_code = (select code from dict_vw where parent_code = 'status' and code = 'open')");

	    $query="UPDATE event set owner_id = :OWNER, status_dict_id = :STATUS where id = :ID";
	    $row = $this->db->prepare($query);
	    $row->bindParam(':ID', $_REQUEST[EVENT_ID], PDO::PARAM_INT);
	    $row->bindParam(':OWNER', $_REQUEST[APPL_USER_ID], PDO::PARAM_INT);
	    if ($this->status == return_query($this->db,"select dict_id from dict_vw where code = 'Nouveau' and parent_code = (select code from dict_vw where parent_code = 'status' and code = 'open')"))
	    {
	        $row->bindParam(':STATUS', $STATUS, PDO::PARAM_INT);
	    }
	    else
	    {
                $row->bindParam(':STATUS', $this->status, PDO::PARAM_INT);
	    }
	    $row->execute();
	    sqlerror($this->db,$query);
	    return "On enregistre l'assignation de l'événement";
	}

	function unassign()
	{
		$query="UPDATE event set owner_id = NULL where id = :ID";
		$row = $this->db->prepare($query);
		$row->bindParam(':ID', $_REQUEST[EVENT_ID], PDO::PARAM_INT);
        	$row->execute();
	}

	function close()
	{
            $query="UPDATE event set status_dict_id = 56 where id = :ID";
       	    $row = $this->db->prepare($query);
            $row->bindParam(':ID', $this->id, PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);
	    return "On ferme l'événement";
	}

	function reopen()
	{
	    $query="UPDATE event set status_dict_id = 50 where id = :ID";
	    $row = $this->db->prepare($query);
	    $row->bindParam(':ID', $this->id, PDO::PARAM_INT);
	    $row->execute();
	    sqlerror($this->db,$query);
	    return "On rouvre l'événement";
	}

        function assign_ext_code()
	{
	    $ext_code = new ext_code();
	    $ext_code->insert_prepare();
	    return "On prépare l'assignation du code externe";
	}

	function delete()
	{
	    echo "<script language='javascript'>alert('FONCTION NON ENCORE CODEE !!!')</script>;";
	    return "On détruit l'événement";
	}

}
?>
