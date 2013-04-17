<?
class plugin_hardware
{
	const PLUGIN_NAME = "Gestion du matériel";
	const PLUGIN_VERSION = "1.0";

	public $db;
	public $web_page;

	public $id;
	public $description;
	public $status_dict_id;
	public $hostname;
	public $ip_address;
	public $internal_reference;
	public $serial_n;
	public $brand_dict_id;
	public $model;
	public $type_dict_id;
	public $location;
	public $acquisition_d;
	public $install_d;
	public $network_id;
	public $contact_id;

	public $hardware_id;
	public $port_n;
	public $connection_dict_id;

	public $dict_id;
	public $parent_dict_id;
	public $code;
	public $active_f;
	public $rank_n;

	function __construct($db)
	{
	    global $web_page;
	    $this->web_page = $web_page;

	    $this->db=$db;
            if($_REQUEST[ID] != "")
            {
		if ($_REQUEST[NATURE] == "hardware")
		{
			$this->id                       = $_REQUEST[ID];
                	$this->description              = return_query($this->db,"SELECT description from hardware where id = $this->id");
                	$this->status_dict_id           = return_query($this->db,"SELECT status_dict_id from hardware where id = $this->id");
                	$this->hostname     		= return_query($this->db,"SELECT hostname from hardware where id = $this->id");
                	$this->ip_address               = return_query($this->db,"SELECT ip_address from hardware where id = $this->id");
                	$this->internal_reference       = return_query($this->db,"SELECT internal_reference from hardware where id = $this->id");
			$this->serial_n			= return_query($this->db,"SELECT serial_n from hardware where id = $this->id");
        		$this->brand_dict_id		= return_query($this->db,"SELECT brand_dict_id from hardware where id = $this->id");
        		$this->model			= return_query($this->db,"SELECT model from hardware where id = $this->id");
        		$this->type_dict_id		= return_query($this->db,"SELECT type_dict_id from hardware where id = $this->id");
        		$this->location			= return_query($this->db,"SELECT location from hardware where id = $this->id");
        		$this->acquisition_d		= return_query($this->db,"SELECT acquisition_d from hardware where id = $this->id");
        		$this->install_d		= return_query($this->db,"SELECT install_d from hardware where id = $this->id");
        		$this->network_id		= return_query($this->db,"SELECT network_id from hardware where id = $this->id");
        		$this->contact_id		= return_query($this->db,"SELECT contact_id from hardware where id = $this->id");
		}

		if ($_REQUEST[NATURE] == "network")
		{
			$this->id                       = $_REQUEST[ID];
			$this->hardware_id		= return_query($this->db,"SELECT hardware_id from network where id = $this->id");
			$this->port_n			= return_query($this->db,"SELECT port_n from network where id = $this->id");
			$this->connection_dict_id	= return_query($this->db,"SELECT connection_dict_id from network where id = $this->id");
		}

		if ($_REQUEST[NATURE] == "attribute")
		{
			$this->dict_id                  = $_REQUEST[ID];
			$this->parent_dict_id           = return_query($this->db,"SELECT parent_dict_id from dict where dict_id = $this->dict_id");
			$this->code                     = return_query($this->db,"SELECT code from dict where dict_id = $this->dict_id");
			$this->description              = return_query($this->db,"SELECT description from dict where dict_id = $this->dict_id");
			$this->active_f                 = return_query($this->db,"SELECT active_f from dict where dict_id = $this->dict_id");
			$this->rank_n                   = return_query($this->db,"SELECT rank_n from dict where dict_id = $this->dict_id");
		}	
            }
	
	}

	function getxml()
	{
            $object=$this;
            $array = (array) $object;
            unset($array[db]);
            $xml=array2xml("data",$array);
            echo $xml;
            return "retourne le hardware sous forme xml";
	}

	function display_actions()
	{
		$data=$this->display_data();
                $this->web_page->add_css("ext/dhtmlx/css/dhtmlxhwtab.css");
                $this->web_page->add_jsfile("ext/dhtmlx/dhtmlxhwtab.js");
                $this->web_page->add_script("
<script>
hwtab = parent.dhxWins.window('win_module_$_REQUEST[MODL]').attachTabbar();
hwtab.setHrefMode('iframes-on-demand');
hwtab.setSkin('default');
hwtab.setImagePath('ext/dhtmlx/imgs/');
hwtab.setSkinColors('#ebebeb', '#fafafa');
hwtab.addTab('a1', 'Matériel', '100px');
hwtab.addTab('a2', 'Réseau', '100px');
hwtab.addTab('a3', 'Attributs', '100px');
hwtab.setContentHref('a1', 'index.php?MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=display&NATURE=hardware');
hwtab.setContentHref('a2', 'index.php?MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=display&NATURE=network');
hwtab.setContentHref('a3', 'index.php?MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=display&NATURE=attribute');
hwtab.setTabActive('a1');
</script>
");

            if($_REQUEST["ACTION"] == "create")
	    {
                echo "<center>";
                echo "<form id='PLUGIN_HARDWARE_DETAIL_ACTION_FORM' action = '' method='post'>";
                echo "<input type='submit' value='Retour'>";
		echo "</form>";
		echo "</center>";
		$this->create_prepare();
            }

	    return "On affiche les actions disponibles";
	}

	function display_data()
	{
	    $this->web_page->add_html("<center><form>");
	    if ($_REQUEST[NATURE]=="hardware")
	    {
		$this->web_page->add_html("<input type='button' value='Créer matériel' onclick='javascript:location.href = \"index.php?MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=create&NATURE=hardware\"'>");
		$this->web_page->add_html("<input type='button' value='Liste par contacts' onclick='javascript:location.href = \"index.php?MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=display&NATURE=hardware_contact_select\"'>");
	        $this->web_page->add_html(return_manage_table("MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=Modification&NATURE=hardware","ID",$this->db,"SELECT id, description as Description, status_description as Statut, hostname as Hostname, internal_reference as 'N° interne', serial_n as 'N° série', brand_description as 'Marque', model as Modèle, type_description as Type, location as Lieu, acquisition_d as Acquisition, install_d as Installation from hardware_vw",0,0));
	    }
	    if ($_REQUEST[NATURE]=="hardware_contact_select")
	    {
		$this->web_page->add_div("<div id='header'><center><input type='button' value='Liste complète' onclick='javascript:location.href = \"index.php?MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=display&NATURE=hardware\"'></center></div>");
		$this->web_page->add_div("<div id='contact_treeID' style='width:100%;height:100%;overflow:auto;'></div>");
                $this->web_page -> add_css("css/dhtmlx_layout_strict.css");
                $this->web_page -> add_css("ext/dhtmlx/css/dhtmlxlayout.css");
                $this->web_page -> add_css("ext/dhtmlx/css/dhtmlxlayout_dhx_skyblue.css");
		$this->web_page->add_jsfile("ext/dhtmlx/dhtmlxtree.js");
                $this->web_page->add_jsfile("ext/dhtmlx/dhtmlxlayout.js");
                $this->web_page->add_jsfile("ext/dhtmlx/dhtmlxcontainer.js");


                $this->web_page->add_script("
                <script>
                        function action_tree(id)
                        {
                                if(id.substring(0,3) == 'sub')
                                {
					var contact_id=id.substring(3,id.length);
					plgLayout.cells('c').attachURL('index.php?MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=display&NATURE=hardware_contact_list&CONTACT=' + contact_id);
                                }
                        }

                        tree=new dhtmlXTreeObject('contact_treeID','100%','100%',0);
                        tree.setImagePath('ext/dhtmlx/imgs/');
                        tree.setOnClickHandler(action_tree);
                        tree.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=contact');
                </script>");

                $this->web_page->add_script("
                <script>
                plgLayout = new dhtmlXLayoutObject(document.body, '3T');
        
                plgLayout.setEffect('resize', false);
                plgLayout.setEffect('collapse', false);
                plgLayout.setEffect('highlight', false);

                plgLayout.cells('a').setHeight(30);
		plgLayout.cells('b').setWidth(300);

                plgLayout.cells('a').fixSize(true, true);
                plgLayout.cells('b').fixSize(true, true);
                plgLayout.cells('c').fixSize(true, true);

                plgLayout.cells('a').hideHeader();
                plgLayout.cells('b').hideHeader();
                plgLayout.cells('c').hideHeader();

                plgLayout.cells('a').attachObject('header');
                plgLayout.cells('b').attachObject('contact_treeID');
                </script>

	   	");
	    }

	    if ($_REQUEST[NATURE]=="hardware_contact_list")
	    {
		$this->web_page->add_html(return_manage_table("MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=Modification&NATURE=hardware","ID",$this->db,"SELECT id, description as Description, status_description as Statut, hostname as Hostname, internal_reference as 'N° interne', serial_n as 'N° série', brand_description as 'Marque', model as Modèle, type_description as Type, location as Lieu, acquisition_d as Acquisition, install_d as Installation from hardware_vw where contact_id = $_REQUEST[CONTACT]",0,0));	
	    }	
	    if ($_REQUEST[NATURE]=="network")
	    {
		$this->web_page->add_html("<input type='button' value='Créer configuration réseau' onclick='javascript:location.href = \"index.php?MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=create&NATURE=network\"'>");
		$this->web_page->add_html(return_manage_table("MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=Modification&NATURE=network","ID",$this->db,"SELECT id, hardware_description as Description, port_n as 'N° Port', connection_description as 'Type connexion' from network_vw",0,0));
	    }
	    if ($_REQUEST[NATURE]=="attribute")
	    {
		$this->web_page->add_html("<input type='button' value='Créer attribut' onclick='javascript:location.href = \"index.php?MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=create&NATURE=attribute\"'>");
		$this->web_page->add_html(str_replace("dict_<td","<td",return_manage_table("MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=Modification&NATURE=attribute","ID",$this->db,"SELECT dict_id, parent_description as Type, code as Code, description as Description, active_f as Actif, rank_n as Rang from dict_vw where parent_dict_id isnull = 0",0,0)));
	    }
	    $this->web_page->add_html("</form></center>");
	}

	function create_prepare()
	{
	    if ($_REQUEST[NATURE]=="hardware")
	    {
		$this->web_page->add_html("<center><form>");
		$this->web_page->add_html("<input type='button' value='Liste' onclick='javascript:location.href = \"index.php?MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=display&NATURE=hardware\"'>");
		$this->web_page->add_html("</form></center>");

		$this->web_page->add_jsfile("ext/dhtmlx/dhtmlxtree.js");
		$this->web_page->add_div("<div id='contact_treeID' style='position:absolute;width:98%;height:98%;overflow:auto;visibility:hidden;'><form><br><center><input type='button' onclick='code: dhxWins.window(\"win_tree\").hide();' value='Annuler'></form><br></center></div>");

		$this->web_page->add_script("
                <script>
                	if (parent.dhxWins.window('win_tree')) {parent.dhxWins.window('win_tree').close()}
                	function go_fill(id)
                	{
                        	if(id.substring(0,3) == 'sub')
                        	{
                                	fill_subform(id);
                                	win_close();
                        	}
                	}

                	function get_field(id,field)
                	{
                        	value=tree.getUserData(id,field);
                        	if(value==undefined)
                        	{
                                	value='';
                        	}
                        	return value;
                	}

                	function win_close()
                	{
                        	parent.dhxWins.window('win_tree').hide();
                	}

                	function fill_subform(id)
                	{
                    		form.setItemValue('contact_id',id.substring(3,id.length));
                    		form.setItemValue('CONTACT_NAME',get_field(id,'name'));
                	}

                        tree=new dhtmlXTreeObject('contact_treeID','100%','100%',0);
                        tree.setImagePath('ext/dhtmlx/imgs/');
                        tree.setOnClickHandler(go_fill);
                        tree.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=contact');
                </script>
");
		$this->web_page->render();

            	$web_form = new web_form("hardware","create");
		$web_form->set_list_options("status_dict_id, brand_dict_id, type_dict_id, network_id");
		$web_form->set_calendar();
		$web_form->set_lang_calendar("acquisition_d, install_d","fr");
            	$web_form->display();

            	return_query_webform_options($this->db, "status_dict_id", "", "select dict_id, description from dict_vw where parent_code = 'status' and active_f = 1 order by rank_n");
	    	return_query_webform_options($this->db, "brand_dict_id", "", "select dict_id, description from dict_vw where parent_code = 'brand' and active_f = 1 order by rank_n");
	    	return_query_webform_options($this->db, "type_dict_id", "", "select dict_id, description from dict_vw where parent_code = 'type' and active_f = 1 order by rank_n");
            	return_query_webform_options($this->db, "network_id", "", "select id, hardware_description || ' / Port N° ' || port_n || ' / ' || connection_description from network_vw order by 2");
	    }

	    if ($_REQUEST[NATURE]=="network")
	    {
                $this->web_page->add_html("<center><form>");
                $this->web_page->add_html("<input type='button' value='Liste' onclick='javascript:location.href = \"index.php?MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=display&NATURE=network\"'>");
                $this->web_page->add_html("</form></center>");

                $this->web_page->render();

		$web_form = new web_form("network","create");
		$web_form->set_list_options("hardware_id, connection_dict_id");
		$web_form->display();

		return_query_webform_options($this->db, "hardware_id", "", "select id, description from hardware_vw where type_code = 'switch' order by description");
		return_query_webform_options($this->db, "connection_dict_id", "", "select dict_id, description from dict_vw where parent_code = 'connection' and active_f = 1 order by rank_n");
	    }

	    if ($_REQUEST[NATURE]=="attribute")
	    {
                $this->web_page->add_html("<center><form>");
                $this->web_page->add_html("<input type='button' value='Liste' onclick='javascript:location.href = \"index.php?MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=display&NATURE=attribute\"'>");
                $this->web_page->add_html("</form></center>");

                $this->web_page->render();

		$web_form = new web_form("dict","create");
		$web_form->set_list_options("parent_dict_id");
		$web_form->display();
	    
                return_query_webform_options($this->db, "parent_dict_id", "", "select dict_id, description from dict where parent_dict_id isnull");
	    }
	}

	function create_submit()
	{
	    $_POST=utf8_array_decode($_POST);
            if ($_REQUEST[NATURE]=="hardware")
            {
            	if ($_POST[description] == ""){print "<div class='ERR'>ERREUR : AUCUNE DESCRIPTION INDIQUEE</div>"; exit;}
            	$query="INSERT INTO hardware (description, status_dict_id, hostname, ip_address, internal_reference, serial_n, brand_dict_id, model, type_dict_id, location, acquisition_d, install_d, network_id, contact_id) values (:DESC,:STATUS,:HOSTNAME,:IP,:INT_REF,:SERIAL_NR,:BRAND,:MODEL,:TYPE,:LOCATION,:ACQUI_DATE,:INSTALL_DATE, :NETWORK, :CONTACT)";
            	$row = $this->db->prepare($query);
            	sqlerror($this->db,$query);
            	$row->bindParam(':DESC',          $_POST[description],                  PDO::PARAM_STR);
            	$row->bindParam(':STATUS',        $_POST[status_dict_id],               PDO::PARAM_INT);
            	$row->bindParam(':HOSTNAME',      $_POST[hostname],                     PDO::PARAM_STR);
            	$row->bindParam(':IP',            $_POST[ip_address],                   PDO::PARAM_STR);
            	$row->bindParam(':INT_REF',       $_POST[internal_reference],           PDO::PARAM_STR);
            	$row->bindParam(':SERIAL_NR',     $_POST[serial_n],                     PDO::PARAM_STR);
            	$row->bindParam(':BRAND',         $_POST[brand_dict_id],                PDO::PARAM_INT);
            	$row->bindParam(':MODEL',         $_POST[model],                        PDO::PARAM_STR);
            	$row->bindParam(':TYPE',          $_POST[type_dict_id],                 PDO::PARAM_INT);
            	$row->bindParam(':LOCATION',      $_POST[location],                     PDO::PARAM_STR);
            	$row->bindParam(':ACQUI_DATE',    $_POST[acquisition_d],                PDO::PARAM_STR);
            	$row->bindParam(':INSTALL_DATE',  $_POST[install_d],                    PDO::PARAM_STR);
                if ($_POST[network_id] != 0)
                {
                        $row->bindParam(':NETWORK',       $_POST[network_id],                   PDO::PARAM_INT);
                }
                if ($_POST[contact_id] != "")
                {
                        $row->bindParam(':CONTACT',       $_POST[contact_id],                   PDO::PARAM_INT);
                }
            	$row->execute();
            	sqlerror($this->db,$query);

            	return "On crée le matériel";
	   }

	   if ($_REQUEST[NATURE]=="network")
	   {
		if ($_POST[hardware_id] == "0"){print "<div class='ERR'>ERREUR : AUCUN SWITCH INDIQUE</div>"; exit;}
		$query="INSERT INTO network (hardware_id, port_n, connection_dict_id) values (:HARDWARE,:PORT,:CONNECTION)";	
		$row = $this->db->prepare($query);
		sqlerror($this->db,$query);
		$row->bindParam(':HARDWARE',        $_POST[hardware_id],                PDO::PARAM_INT);
		$row->bindParam(':PORT',            $_POST[port_n],                     PDO::PARAM_INT);
		$row->bindParam(':CONNECTION',      $_POST[connection_dict_id],         PDO::PARAM_INT);
		$row->execute();
		sqlerror($this->db,$query);

		return "On crée le réseau";
	   }

	   if ($_REQUEST[NATURE]=="attribute")
	   {
		if ($_POST[code] == ""){print "<div class='ERR'>ERREUR : AUCUN CODE INDIQUE</div>"; exit;}
		$query="INSERT INTO dict (parent_dict_id, code, description, active_f, rank_n) values (:PARENT,:CODE,:DESCRIPTION,:ACTIF,:RANK)";
		$row = $this->db->prepare($query);
		sqlerror($this->db,$query);
		$row->bindParam(':PARENT',          $_POST[parent_dict_id],             PDO::PARAM_INT);
		$row->bindParam(':CODE',            $_POST[code],                       PDO::PARAM_STR);
		$row->bindParam(':DESCRIPTION',     $_POST[description],                PDO::PARAM_STR);
		$row->bindParam(':ACTIF',           $_POST[active_f],                   PDO::PARAM_INT);
		$row->bindParam(':RANK',            $_POST[rank_n],                     PDO::PARAM_INT);
		$row->execute();
		sqlerror($this->db,$query);

		return "On crée l'attribut";
	   }
	}

        function update_prepare()
        {
	    if ($_REQUEST[NATURE]=="hardware")
            {
                $this->web_page->add_html("<center><form>");
                $this->web_page->add_html("<input type='button' value='Liste' onclick='javascript:if(parent.plgLayout){parent.location.href=\"index.php?MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=display&NATURE=hardware\"}else{location.href = \"index.php?MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=display&NATURE=hardware\"}'>");
                $this->web_page->add_html("</form></center>");

                $this->web_page->add_jsfile("ext/dhtmlx/dhtmlxtree.js");
                $this->web_page->add_div("<div id='contact_treeID' style='position:absolute;width:98%;height:98%;overflow:auto;visibility:hidden;'><form><br><center><input type='button' onclick='code: dhxWins.window(\"win_tree\").hide();' value='Annuler'></form><br></center></div>");

                $this->web_page->add_script("
                <script>
                        if (parent.dhxWins.window('win_tree')) {parent.dhxWins.window('win_tree').close()}
                        function go_fill(id)
                        {
                                if(id.substring(0,3) == 'sub')
                                {
                                        fill_subform(id);
					if (parent.dhxWins.window('win_tree')) {parent.dhxWins.window('win_tree').hide()}
                                }
                        }

                        function get_field(id,field)
                        {
                                value=tree.getUserData(id,field);
                                if(value==undefined)
                                {
                                        value='';
                                }
                                return value;
                        }

                        function fill_subform(id)
                        {
                                form.setItemValue('contact_id',id.substring(3,id.length));
                                form.setItemValue('CONTACT_NAME',get_field(id,'name'));
                        }

                </script>

");
                $this->web_page->render();

            	$web_form = new web_form("hardware","edit");
	    	$web_form->set_list_options("status_dict_id, brand_dict_id, type_dict_id, network_id");
	    	$web_form->set_calendar();
	    	$web_form->set_lang_calendar("acquisition_d, install_d","fr");
            	$web_form->display($this->id);

            	return_query_webform_options($this->db, "status_dict_id", "", "select dict_id, description from dict_vw where parent_code = 'status' and active_f = 1 order by rank_n");
            	return_query_webform_options($this->db, "brand_dict_id", "", "select dict_id, description from dict_vw where parent_code = 'brand' and active_f = 1 order by rank_n");
            	return_query_webform_options($this->db, "type_dict_id", "", "select dict_id, description from dict_vw where parent_code = 'type' and active_f = 1 order by rank_n");
		return_query_webform_options($this->db, "network_id", "", "select id, hardware_description || ' / Port N° ' || port_n || ' / ' || connection_description from network_vw order by 2");

		echo "<script>
			tree=new dhtmlXTreeObject('contact_treeID','100%','100%',0);
                        tree.setImagePath('ext/dhtmlx/imgs/');
                        tree.setOnClickHandler(go_fill);
                        tree.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=contact',function(){go_fill('sub$this->contact_id');});
</script>";
            	return "On met à jour le matériel";
	    }

	    
	    if ($_REQUEST[NATURE]=="network")
            {
                $this->web_page->add_html("<center><form>");
                $this->web_page->add_html("<input type='button' value='Liste' onclick='javascript:location.href = \"index.php?MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=display&NATURE=network\"'>");
                $this->web_page->add_html("</form></center>");

                $this->web_page->render();

            	$web_form = new web_form("network","edit");
	    	$web_form->set_list_options("hardware_id, connection_dict_id");
            	$web_form->display($this->id);

                return_query_webform_options($this->db, "hardware_id", "", "select id, description from hardware_vw where type_code = 'switch' order by description");
                return_query_webform_options($this->db, "connection_dict_id", "", "select dict_id, description from dict_vw where parent_code = 'connection' and active_f = 1 order by rank_n");

            	return "On met à jour le matériel";
	    }

	    if ($_REQUEST[NATURE]=="attribute")
	    {
                $this->web_page->add_html("<center><form>");
                $this->web_page->add_html("<input type='button' value='Liste' onclick='javascript:location.href = \"index.php?MODL=$_REQUEST[MODL]&MODL_OPTION=$_REQUEST[MODL_OPTION]&ACTION=display&NATURE=attribute\"'>");
                $this->web_page->add_html("</form></center>");

                $this->web_page->render();

                $web_form = new web_form("dict","edit");
                $web_form->set_list_options("parent_dict_id");
                $web_form->display($this->dict_id);

		return_query_webform_options($this->db, "parent_dict_id", "", "select dict_id, description from dict where parent_dict_id isnull");

                return "On met à jour l'attribut";
	    }
        }

        function update_submit()
        {
	    $_POST=utf8_array_decode($_POST);
	    if ($_REQUEST[NATURE]=="hardware")
	    {
            	if ($_POST[description] == ""){print "<div class='ERR'>ERREUR : AUCUNE DESCRIPTION INDIQUEE</div>"; exit;}
            	$query="UPDATE hardware set description = :DESC, status_dict_id = :STATUS, hostname = :HOSTNAME, ip_address = :IP, internal_reference = :INT_REF, serial_n = :SERIAL_NR, brand_dict_id = :BRAND, model = :MODEL, type_dict_id = :TYPE, location = :LOCATION, acquisition_d = :ACQUI_DATE, install_d = :INSTALL_DATE, network_id = :NETWORK, contact_id = :CONTACT where id = :ID";
            	$row = $this->db->prepare($query);
            	sqlerror($this->db,$query);
		
		if($_POST[acquisition_d] == "null") $_POST[acquisition_d] = "";
		if($_POST[install_d] == "null") $_POST[install_d] = "";

	    	$row->bindParam(':ID',            $_POST[id],                           PDO::PARAM_INT);
            	$row->bindParam(':DESC',          $_POST[description],                  PDO::PARAM_STR);
            	$row->bindParam(':STATUS',        $_POST[status_dict_id],               PDO::PARAM_INT);
            	$row->bindParam(':HOSTNAME',      $_POST[hostname],                     PDO::PARAM_STR);
            	$row->bindParam(':IP',            $_POST[ip_address],                   PDO::PARAM_STR);
            	$row->bindParam(':INT_REF',       $_POST[internal_reference],           PDO::PARAM_STR);
            	$row->bindParam(':SERIAL_NR',     $_POST[serial_n],                     PDO::PARAM_STR);
            	$row->bindParam(':BRAND',         $_POST[brand_dict_id],                PDO::PARAM_INT);
            	$row->bindParam(':MODEL',         $_POST[model],                        PDO::PARAM_STR);
            	$row->bindParam(':TYPE',          $_POST[type_dict_id],                 PDO::PARAM_INT);
            	$row->bindParam(':LOCATION',      $_POST[location],                     PDO::PARAM_STR);
            	$row->bindParam(':ACQUI_DATE',    $_POST[acquisition_d],                PDO::PARAM_STR);
            	$row->bindParam(':INSTALL_DATE',  $_POST[install_d],                    PDO::PARAM_STR);
		if ($_POST[network_id] != 0)
		{
            		$row->bindParam(':NETWORK',       $_POST[network_id],                   PDO::PARAM_INT);
		}
		if ($_POST[contact_id] != "")
		{
			$row->bindParam(':CONTACT',       $_POST[contact_id],                   PDO::PARAM_INT);
		}
            	$row->execute();
            	sqlerror($this->db,$query);

            	return "On met à jour le matériel";
	    }

            if ($_REQUEST[NATURE]=="network")
            {
                if ($_POST[hardware_id] == "0"){print "<div class='ERR'>ERREUR : AUCUN SWITCH INDIQUE</div>"; exit;}
		$query="UPDATE network set hardware_id = :HARDWARE, port_n = :PORT, connection_dict_id = :CONNECTION where id = :ID";
                $row = $this->db->prepare($query);
                sqlerror($this->db,$query);
                $row->bindParam(':ID',              $_POST[id],                         PDO::PARAM_INT);
                $row->bindParam(':HARDWARE',        $_POST[hardware_id],                PDO::PARAM_INT);
                $row->bindParam(':PORT',            $_POST[port_n],                     PDO::PARAM_INT);
                $row->bindParam(':CONNECTION',      $_POST[connection_dict_id],         PDO::PARAM_INT);
                $row->execute();
                sqlerror($this->db,$query);

                return "On met à jour le réseau";
	    }

            if ($_REQUEST[NATURE]=="attribute")
            {
                if ($_POST[code] == ""){print "<div class='ERR'>ERREUR : AUCUN CODE INDIQUE</div>"; exit;}
		$query="UPDATE dict set parent_dict_id = :PARENT, code = :CODE, description = :DESCRIPTION, active_f = :ACTIF, rank_n = :RANK where dict_id = :ID";
                $row = $this->db->prepare($query);
                sqlerror($this->db,$query);
                $row->bindParam(':ID',              $_POST[dict_id],                    PDO::PARAM_INT);
		$row->bindParam(':PARENT',          $_POST[parent_dict_id],             PDO::PARAM_INT);
		$row->bindParam(':CODE',            $_POST[code],                       PDO::PARAM_STR);
		$row->bindParam(':DESCRIPTION',     $_POST[description],                PDO::PARAM_STR);
		$row->bindParam(':ACTIF',           $_POST[active_f],                   PDO::PARAM_INT);
		$row->bindParam(':RANK',            $_POST[rank_n],                     PDO::PARAM_INT);
		$row->execute();
                sqlerror($this->db,$query);

                return "On met à jour l'attribut";
	    }

	}

        function delete()
        {
            if ($_REQUEST[NATURE]=="hardware")
	    {
            	$query="delete from hardware where id= :ID";
            	$row = $this->db->prepare($query);
            	sqlerror($this->db,$query);
            	$row->bindParam(':ID',              $_POST[id],                     PDO::PARAM_INT);
            	$row->execute();
            	sqlerror($this->db,$query);
	    }
	    if ($_REQUEST[NATURE]=="network")
	    {
		$query="delete from network where id= :ID";
            	$row = $this->db->prepare($query);
            	sqlerror($this->db,$query);
            	$row->bindParam(':ID',              $_POST[id],                     PDO::PARAM_INT);
            	$row->execute();
            	sqlerror($this->db,$query);
	    }
	    if ($_REQUEST[NATURE]=="attribute")
	    {
		$query="delete from dict where dict_id= :ID";
            	$row = $this->db->prepare($query);
            	sqlerror($this->db,$query);
            	$row->bindParam(':ID',              $_POST[dict_id],                PDO::PARAM_INT);
            	$row->execute();
            	sqlerror($this->db,$query);
	    }

        }

	function event_display()
	{
		display_table($this->db,"select distinct h.type_description as Type, h.description as Matériel from event_hardware eh, hardware_vw h where eh.hardware_id=h.id and eh.event_id = $_REQUEST[EVENT_ID]");
	}

	function event_create_prepare()
	{
		echo "<script type='text/javascript'>
			function filterall()
			{
				choice=document.getElementById('detevent').hardware_id;
				choice.options.length=0;
				hd=document.getElementById('detevent').hardware_description;
				
    				for ( var i=0;i<hd.length;i++ )
    				{   
					choice.options[choice.options.length] = new Option (hd.options[i].text,hd.options[i].value);
				}
			}
                        function filtercontact()
                        {

				choice=document.getElementById('detevent').hardware_id;
				choice.options.length=0;
				hd=document.getElementById('detevent').hardware_description;
				hc=document.getElementById('detevent').hardware_contact;
				
    				for ( var i=0;i<hc.length;i++ )
    				{   
					if(hc.options[i].text == document.getElementById('detevent').CONTACT.value)
					{	
						choice.options[choice.options.length] = new Option (hd.options[i].text,hd.options[i].value);
					}
				}
                        }

		     </script>";
	
		echo "<center>Hardware : ";
                echo "<select size='1' name = 'hardware_id'>";
                echo "</select>";
		echo "<select size='1' name = 'hardware_description' style='display:none;'>";
		echo return_query_form_options($this->db, 0, "select id, description from hardware_vw order by description");
		echo "</select>";
                echo "<select size='1' name = 'hardware_contact' style='display:none;'>";
                echo return_query_form_options($this->db, 0, "select id, contact_id from hardware_vw order by description");
                echo "</select>";
		echo "<input type=button value='Tous' onclick=\"filterall()\")>";
		echo "<input type=button value='Liés au contact' onclick=\"filtercontact()\")>";
		echo "</center>";
	}

	function event_create_submit($event_id)
	{
		if ($_REQUEST[hardware_id] != "")
		{
			$query="INSERT into event_hardware (event_id, hardware_id) VALUES (:EVENT_ID,:HARDWARE_ID)";
			$row = $this->db->prepare($query);
			$row->bindParam(':EVENT_ID',     $event_id             , PDO::PARAM_INT);
			$row->bindParam(':HARDWARE_ID',  $_REQUEST[hardware_id], PDO::PARAM_INT);
			$row->execute();
			sqlerror($this->db,$query);	
		}
	}

	function event_update_prepare()
	{
		echo "<script type='text/javascript'>
			function filterall()
			{
				choice=document.getElementById('detevent').hardware_id;
				choice.options.length=0;
				hd=document.getElementById('detevent').hardware_description;
				
    				for ( var i=0;i<hd.length;i++ )
    				{   
					choice.options[choice.options.length] = new Option (hd.options[i].text,hd.options[i].value);
				}
				setpluginfield();
			}
                        function filtercontact()
                        {

				choice=document.getElementById('detevent').hardware_id;
				choice.options.length=0;
				hd=document.getElementById('detevent').hardware_description;
				hc=document.getElementById('detevent').hardware_contact;
				
    				for ( var i=0;i<hc.length;i++ )
    				{   
					if(hc.options[i].text == form.getItemValue('contact_id'))
					{	
						choice.options[choice.options.length] = new Option (hd.options[i].text,hd.options[i].value);
					}
				}
				setpluginfield();
                        }
			function setpluginfield()
			{
				form.setItemValue('plugin_string',document.getElementById('detevent').hardware_id.value);
			}
		     </script>";
	
		$hardware_id=return_query($this->db,"select hardware_id from event_hardware where event_id = $_REQUEST[EVENT_ID]");
		echo "<form id='detevent'>";
		echo "<center>Hardware : ";
                echo "<select size='1' name = 'hardware_id' onchange='setpluginfield()'>";
		if ($hardware_id != "")
		{
			echo return_query_form_options($this->db, $hardware_id, "select id, description from hardware where id = $hardware_id");
		}
                echo "</select>";
		echo "<select size='1' name = 'hardware_description' style='display:none;'>";
		echo return_query_form_options($this->db, 0, "select id, description from hardware_vw order by description");
		echo "</select>";
                echo "<select size='1' name = 'hardware_contact' style='display:none;'>";
                echo return_query_form_options($this->db, 0, "select id, contact_id from hardware_vw order by description");
                echo "</select>";
		echo "<input type=button value='Tous' onclick=\"filterall()\")>";
		echo "<input type=button value='Liés au contact' onclick=\"filtercontact()\")>";
		echo "</center>";
		echo "</form>";
	}

	function event_update_submit()
	{
		$hardware_id=$_REQUEST[plugin_string];
		if ($hardware_id != "")
		{
			$curr=return_query($this->db,"select hardware_id from event_hardware where event_id = $_REQUEST[id]");
			if ($curr=="")
			{
				$query="INSERT into event_hardware (event_id, hardware_id) VALUES (:EVENT_ID,:HARDWARE_ID)";
			}
			else
			{
				$query="UPDATE event_hardware set hardware_id = :HARDWARE_ID where event_id = :EVENT_ID";
			}
			$row = $this->db->prepare($query);
			$row->bindParam(':EVENT_ID',     $_REQUEST[id]         , PDO::PARAM_INT);
			$row->bindParam(':HARDWARE_ID',  $hardware_id          , PDO::PARAM_INT);
			$row->execute();
			sqlerror($this->db,$query);	
		}
	}

}
?>
