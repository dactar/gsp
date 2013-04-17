<?
class module
{
	public $code;
	public $action;
	public $class;
	public $call_admin;
	public $error;

	function __tostring()
	{
	    return "Cette classe permet de gérer un module GSP";
	}

	function __construct($code, $action)
	{
	    global $db;

	    $this->error = false;
            $this->code = $code;
	    $this->action = $action;
            $this->call_admin = return_query($db,"SELECT admin_f from user where code = '$_COOKIE[GSP_USER]'");

            switch ($this->code)
            {
		case "OPNE":	$this->class="event"; break;
                case "CSLA":	$this->class="sla"; break;
		case "TSLA":	$this->class="sla_compo"; break;
		case "VSLA":    $this->class="sla_compo"; break;
		case "ESLA":    $this->class="sla_compo"; break;
		case "STAT":	$this->class="stat"; break;
		case "CPLG":	$this->class="plugin"; break;
		case "LPLG":    $this->class="plugin"; break;
		case "JRNL":	$this->class="journal"; break;
		case "CPWD":	$this->class="user"; break;
		case "CBOX":	$this->class="inbox"; break;
                default :       $this->error=true;echo "Module $this->code : classe inconnue\n<br>"; break;
            }
            
	    switch ($this->action)
	    {
		case "":		$this->display(); break;
		case "display":		$this->display_data(); break;
	        case "create":          $this->create_prepare(); break;
		case "Sauvegarder":	$this->create_submit(); break;
		case "Modification":	$this->update_prepare(); break;
		case "Valider":         $this->update_submit(); break;
		case "Supprimer":	$this->delete(); break;
		case "Installer":	$this->install(); break;
		case "Activer":		$this->enable(); break;
		case "Inactiver":	$this->disable(); break;
		case "getxml":		$this->getxml(); break;
		case "launch":		$this->launch(); break;
		default : 		$this->error=true;echo "Module $this->code : Action $action inconnue\n<br>"; break;
	    }
        }

	function display()
	{
	    if (!$this->error)
	    {
	    	if ($this->code == "LPLG")
	    	{
			$this->launch("display_actions");
	    	}
	    	else
	    	{
	    		$class=new $this->class();

            		if ($this->code != "STAT" && $this->code != "CPLG")
            		{
            			global $web_page;
            			$web_page->render();
            		}

	    		$class->display_actions($this->call_admin);
            		$class->display_data($this->call_admin);
		}
	    }
	}

	function display_data()
	{
	    if ($this->code == "LPLG")
	    {
		$this->launch("display_data");
	    }
	}

	function getxml()
	{
	    $class=new $this->class();
            $class->getxml();
            exit;
	}

        function create_prepare()
        {
	    $class=new $this->class();

	    global $web_page;
	    $web_page->render();            

            $class->create_prepare();
        }

	function create_submit()
	{
	    $class=new $this->class();
            
            $class->create_submit();
	}

	function update_prepare()
	{
	    $class=new $this->class();

	    global $web_page;
	    $web_page->render();

	    $class->update_prepare();
	}

	function update_submit()
	{
	    $class=new $this->class();

            $class->update_submit();
        }

	function delete()
	{
	    $class=new $this->class();
            $class->delete();

	    if($this->code == "CPLG")
	    {
		$class->display_actions($this->call_admin);
		$class->display_data($this->call_admin);	
	    } 
	}

	function install()
	{
	    $class=new $this->class();
	    $class->install();
            $class->display_actions($this->call_admin);
            $class->display_data($this->call_admin);
	}

	function uninstall()
        {
            $class=new $this->class();
            $class->uninstall();
            $class->display_actions($this->call_admin);
            $class->display_data($this->call_admin);
        }

	function enable()
        {
            $class=new $this->class();
            $class->enable();
            $class->display_actions($this->call_admin);
            $class->display_data($this->call_admin);
        }

	function disable()
        {
            $class=new $this->class();
            $class->disable();
            $class->display_actions($this->call_admin);
            $class->display_data($this->call_admin);
        }

	function launch($action)
	{
	    $class=new $this->class();

            global $web_page;

	    $class->launch($action);
	    $web_page->render();
	}

}
