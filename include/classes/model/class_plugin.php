<?
class plugin
{
	public $db;
	public $web_page;

	public $id;
	public $code;
	public $name;
	public $version;
	public $active_f;
	public $installed_f;
	public $path;
	public $db_file;
	
	function __tostring()
	{
	    return "Cette classe permet de gérer les plugins<br/>";
	}

	function __construct($plugin_code="")
	{
	    global $db;
            global $web_page;
	    global $GSP_DB;
	    $this->db = $db;

	    if($_REQUEST[id] != "" || $_REQUEST[MODL] == "LPLG" || $plugin_code != "")
	    {
		if($_REQUEST[id] != "" && $_REQUEST[MODL] != "LPLG" && $plugin_code == "")
		{
			$this->id = $_REQUEST[id];
	    		$this->code		= return_query($this->db,"SELECT code from plugin where id = $this->id");
		}
		else
		{
			if($plugin_code=="")
			{
				$plugin_code = $_REQUEST[MODL_OPTION];
			}
			$this->code = $plugin_code;
			$this->id  		= return_query($this->db,"SELECT id from plugin where code = '$this->code'");
		}
	    	$this->name 			= return_query($this->db,"SELECT name from plugin where id = $this->id");
	    	$this->active_f 		= return_query($this->db,"SELECT active_f from plugin where id = $this->id");
		$this->installed_f		= return_query($this->db,"SELECT installed_f from plugin where id = $this->id");
		$this->path			= "plugins/plugin_" . $this->code;
		$this->db_file			= $GSP_DB . "_plugin_" . $this->code;

	    }

	    $this->web_page = $web_page;
	    $this->plugins = array();
	}

        function getxml()
        {
            require_once($this->path . "/class_plugin_" . $this->code . ".php");
            $class="plugin_$this->code";
	    if(file_exists($this->db_file))
            {
                $db = new PDO("sqlite:$this->db_file");
                $plugin = new $class($db);
            }
            else
            {
                $plugin = new $class();
            }
            $plugin->getxml();
        }


	function display_actions($admin)
	{
            $this->web_page->add_html("<b>Configuration des plugins</b><br><br>");
	    return "On affiche les actions disponibles";
	}

	function load_method($method,$param="")
	{
		require_once($this->path . "/class_plugin_" . $this->code . ".php");
		$reflection = new ReflectionClass("plugin_" . $this->code);
	        try
        	{
			$reflection->getMethod("$method");
			$class="plugin_$this->code";
			if(file_exists($this->db_file))
			{
				$db = new PDO("sqlite:$this->db_file");
				$plugin = new $class($db);
			}
			else
			{
				$plugin = new $class();	
			}
			if ($param != "")
			{
				$plugin->$method($param);
			}
			else
			{
				$plugin->$method();
			}
        	}
        	catch (Exception $e)
        	{
        	}
	}

	function create_prepare()
	{
            require_once($this->path . "/class_plugin_" . $this->code . ".php");
            $class="plugin_$this->code";
            if(file_exists($this->db_file))
            {
                $db = new PDO("sqlite:$this->db_file");
                $plugin = new $class($db);
            }
            else
            {
                $plugin = new $class();
            }
	    $plugin->create_prepare();
	}

        function create_submit()
        {
            require_once($this->path . "/class_plugin_" . $this->code . ".php");
            $class="plugin_$this->code";
            if(file_exists($this->db_file))
            {
                $db = new PDO("sqlite:$this->db_file");
                $plugin = new $class($db);
            }
            else
            {
                $plugin = new $class();
            }
	    $plugin->create_submit();
        }

        function update_prepare()
        {
            require_once($this->path . "/class_plugin_" . $this->code . ".php");
            $class="plugin_$this->code";
            if(file_exists($this->db_file))
            {
                $db = new PDO("sqlite:$this->db_file");
                $plugin = new $class($db);
            }
            else
            {
                $plugin = new $class();
            }
            $plugin->update_prepare();
        }

        function update_submit()
        {
            require_once($this->path . "/class_plugin_" . $this->code . ".php");
            $class="plugin_$this->code";
            if(file_exists($this->db_file))
            {
                $db = new PDO("sqlite:$this->db_file");
                $plugin = new $class($db);
            }
            else
            {
                $plugin = new $class();
            }
            $plugin->update_submit();
        }

	function display_data($admin)
	{
	    $plugins_path = "plugins";
	    $dir = opendir($plugins_path); 

            $plugin_list_fs = array();

     	    while($file = readdir($dir))
            {
		if(substr($file,0,7) == "plugin_" && is_dir($plugins_path . "/" . $file))
		{
			$code=substr($file,7,strlen($file)-7);

			$subdir = opendir($plugins_path . "/" . $file); 

			while($subdirfile = readdir($subdir))
			{
				if($subdirfile == "class_plugin_" . $code . ".php")
				{
					require_once($plugins_path . "/" . $file . "/" . $subdirfile);
					$reflection = new ReflectionClass("plugin_" . $code);
					$name=$reflection->getConstant("PLUGIN_NAME");
					$version=$reflection->getConstant("PLUGIN_VERSION");

					$this->id=return_query($this->db,"select id from plugin where code = '$code'");
					if ($this->id == "")
					{
					        $query="INSERT INTO plugin (code, name, version) values (:CODE,:NAME,:VERSION)";
            					$row = $this->db->prepare($query);
            					sqlerror($this->db,$query);
            					$row->bindParam(':CODE',            $code,                   PDO::PARAM_STR);
            					$row->bindParam(':NAME',            $name,                   PDO::PARAM_STR);
            					$row->bindParam(':VERSION',         $version,                PDO::PARAM_STR);
            					$row->execute();
            					sqlerror($this->db,$query);
					}
					$plugin_list_fs["$code"] = array($name,$version);
				}
			}
			closedir($subdir);
		}
	    }

	    closedir($dir);

            $list_plugin=return_query_array($this->db,"select id, code, name, version, active_f, installed_f from plugin order by code");

            $this->web_page->add_html("<form action = 'index.php?MODL=$_REQUEST[MODL]' method='post'>");
            $this->web_page->add_html("<input type='hidden' id='plugin_id' name='id'>");

            $this->web_page->add_html("<center><table border=1 cellpadding=5 cellspacing=5>");
	    $this->web_page->add_html("<th>Plugin</th><th>Description</th><th>Etat</th><th>Action</th>");
            foreach ($list_plugin as $id => $plugin)
            {
                $this->web_page->add_html("<tr>");
		$this->web_page->add_html("<td>$plugin[1]</td>");
		$this->web_page->add_html("<td>$plugin[2]</td>");
		if ($plugin_list_fs[$plugin[1]])
		{
			if($plugin[3] != $plugin_list_fs[$plugin[1]][1])
			{
				$etat="Obsolet"; $action="Mettre à jour";
			}
			else
			{
				if($plugin[5] == 1)
				{
					if($plugin[4] == 1)
					{
						$etat="Actif"; $action="Inactiver";
					}
					else
					{
						$etat="Inactif"; $action="Activer";
					}
				}
				else
				{
					$etat="Déclaré"; $action="Installer";
				}
			}
		}
		else
		{
			$etat="Introuvable"; $action="Supprimer";
		}
		$this->web_page->add_html("<td>$etat</td>");
		$this->web_page->add_html("<td align=center><input type='submit' name='ACTION' value='$action' onclick='code:document.getElementById(\"plugin_id\").value=\"$plugin[0]\"'>");
		if ($etat == "Inactif")
		{
			$this->web_page->add_html("<input type='submit' name='ACTION' value='Supprimer' onclick='code:document.getElementById(\"plugin_id\").value=\"$plugin[0]\"'>");
		}
		$this->web_page->add_html("</td></tr>");
            }
	    $this->web_page->add_html("</table></center>");

	    if($_REQUEST[ACTION] == "Activer" || $_REQUEST[ACTION] == "Inactiver")
	    {
		$this->web_page->add_html("<br><center><input type='submit' value='Red&eacute;marrer GSP' onclick='code:parent.document.location.href=\"index.php\"'></center>");
	    }
	    $this->web_page->add_html("</form>");

	    $this->web_page->render();

	    return "On affiche les plugins";
	}
	
	function launch($action)
	{
	    require_once($this->path . "/class_plugin_" . $this->code . ".php");		
	    $class="plugin_$this->code";
	    if(file_exists($this->db_file))
            {
		$db = new PDO("sqlite:$this->db_file");	
	    	$plugin = new $class($db);
		$plugin->$action();
	    }
	    else
	    {
		$plugin = new $class();	
		$plugin->$action();
	    }
	}

	function install()
	{
	    if (file_exists($this->path . "/install.sql"))
	    {
		if (file_exists($this->db_file))
		{	
			unlink($this->db_file);
		}
		$db = new PDO("sqlite:$this->db_file");
		$sql=explode(";",str_replace("NOT NULL;", "NOT NULL#", file_get_contents($this->path . "/install.sql")));
		$error=false;
		foreach($sql as $query)
		{
			$result = $db->prepare(str_replace("NOT NULL#", "NOT NULL;", $query));
			if (!$result) 
			{
				echo "<pre>";	
				echo $query . "\n => ";
    				$msg=$db->errorInfo();
				echo $msg[2];
				$error=true;
				echo "</pre>";
				if (file_exists($this->db_file))
				{
					unlink($this->db_file);
				}
			}
			else
			{
				$result->execute();
			}
		}
                if ($error)
                {
			if (file_exists($this->db_file))
                	{
                        	unlink($this->db_file);
                	}
		}
		else
		{
			return_query($this->db,"UPDATE plugin set installed_f=1 where id = $this->id");
		}
            }   
	    else
	    {
	    	return_query($this->db,"UPDATE plugin set installed_f=1 where id = $this->id");
	    }
	}

	function enable()
	{
	    return_query($this->db,"UPDATE plugin set active_f=1 where id = $this->id");
	}

	function disable()
	{
	    return_query($this->db,"UPDATE plugin set active_f=0 where id = $this->id");
	}

	function delete()
	{
	    if($_REQUEST[MODL] == "LPLG")
	    {
	        require_once($this->path . "/class_plugin_" . $this->code . ".php");
            	$class="plugin_$this->code";
            	if(file_exists($this->db_file))
            	{
                	$db = new PDO("sqlite:$this->db_file");
                	$plugin = new $class($db);
            	}
            	else
            	{
                	$plugin = new $class();
            	}
            	$plugin->delete();
	    }
	    else
            {
            	if (file_exists($this->db_file))
            	{
			unlink($this->db_file);
            	}
	    	return_query($this->db,"DELETE from plugin where id = $this->id");
	    }
	}
}
?>
