<?
class inbox
{
	public $db;
	public $id;
	public $inbox_code;
	public $inbox_description;
	public $inbox_db_path;
	public $inbox_db_table;
	public $automatic_f;
	public $mailbox_readonly_f;
	public $mailbox_server;
	public $mailbox_server_protocol_id;
	public $mailbox_server_port;
	public $mailbox_treated_folder;
	public $user_code;
	public $password;
	public $active_f;
	public $default_f;

	function __tostring()
	{
	    return "Cette classe permet de définir et manipuler une configuration mail<br/>";
	}

	function __construct()
	{
	    global $db;
	    $this->db = $db;
	    if($_REQUEST[ID] != "")
	    {
	    	$this->id				= $_REQUEST[ID];
	    	$this->inbox_code 			= return_query($this->db,"SELECT inbox_code from config_inbox where id = $this->id");
	    	$this->inbox_description 		= return_query($this->db,"SELECT inbox_description from config_inbox where id = $this->id");
	    	$this->inbox_db_path 			= return_query($this->db,"SELECT inbox_db_path from config_inbox where id = $this->id");
	    	$this->inbox_db_table 			= return_query($this->db,"SELECT inbox_db_table from config_inbox where id = $this->id");
	    	$this->automatic_f 			= return_query($this->db,"SELECT automatic_f from config_inbox where id = $this->id");
	    	$this->mailbox_readonly_f 		= return_query($this->db,"SELECT mailbox_readonly_f from config_inbox where id = $this->id");
	    	$this->mailbox_server 			= return_query($this->db,"SELECT mailbox_server from config_inbox where id = $this->id");
	    	$this->mailbox_server_protocol_id	= return_query($this->db,"SELECT mailbox_server_protocol_id from config_inbox where id = $this->id");
	    	$this->mailbox_server_port		= return_query($this->db,"SELECT mailbox_server_port from config_inbox where id = $this->id");
	    	$this->mailbox_treated_folder		= return_query($this->db,"SELECT mailbox_treated_folder from config_inbox where id = $this->id");
	    	$this->user_code			= return_query($this->db,"SELECT user_code from config_inbox where id = $this->id");
	    	$this->password				= return_query($this->db,"SELECT password from config_inbox where id = $this->id");
	    	$this->active_f 			= return_query($this->db,"SELECT active_f from config_inbox where id = $this->id");
		$this->default_f                        = return_query($this->db,"SELECT default_f from config_inbox where id = $this->id");
	    }
	}

	function getxml()
	{
            $object=$this;
            $array = (array) $object;
            unset($array[db]);
            $xml=array2xml("data",$array);
	    echo $xml;
            return "retourne le config_inbox sous forme xml";
	}

	function display_actions($admin)
	{
	    echo "<center>";
	    echo "<form id='SLA_DETAIL_ACTION_FORM' action = '' method='post'>";
	    if ($admin == 1)
	    {
		echo "<input type='hidden' name='ACTION' value='create'></input>";
		echo "<input type='submit' name='submit' value='Créer'></input>";
		echo "<br><br>";	
	    }
	    echo "</form>";
	    echo "</center>";
	    return "On affiche les actions disponibles";
	}

	function display_data($admin)
	{
	    if ($admin == 1)
	    {
	    manage_table("MODL=$_REQUEST[MODL]&ACTION=Modification","ID",$this->db,"SELECT id, inbox_code as 'Code', inbox_description as 'Nom', mailbox_server as 'Serveur', active_f as 'Actif', default_f as 'Principal' from config_inbox",0,0);
	    }
	    else
	    {
	    display_table($this->db,"SELECT inbox_code as 'Code', inbox_description as 'Nom', mailbox_server as 'Serveur', active_f as 'Actif', default_f as 'Principal' from config_inbox");
	    }
	    return "On affiche les config_inboxs";
	}

	function create_prepare()
	{
	    echo "<center><a href='index.php?MODL=$_REQUEST[MODL]' title='RETOUR'><img src='pict/back.png' border=0 alt='RETOUR'></a></center>";
            $web_form = new web_form("config_inbox","create");
	    $web_form->set_list_options("mailbox_server_protocol_id");
            $web_form->display();

	    return_query_webform_options($this->db, "mailbox_server_protocol_id", "", "select id, description from config_server_protocol order by id");

	    return "On affiche le formulaire de création du config_inbox";
	}

	function create_submit()
	{
	    $_POST=utf8_array_decode($_POST); 	 
	    if ($_POST[inbox_code] == ""){print "<div class='ERR'>ERREUR : AUCUN CODE INDIQUE</div>"; exit;}
	    $query="INSERT INTO config_inbox (inbox_code, inbox_description, inbox_db_path, inbox_db_table, automatic_f, mailbox_readonly_f, mailbox_server, mailbox_server_protocol_id, mailbox_server_port, mailbox_treated_folder, user_code, password, active_f, default_f) values (:INBOX_CODE,:INBOX_DESC,:INBOX_DB_PATH,:INBOX_DB_TABLE,:AUTO,:MAILBOX_READONLY,:MAILBOX_SERVER,:MAILBOX_SERVER_PROT,:MAILBOX_SERVER_PORT,:MAILBOX_TREATED_FOLDER,:USER,:PASS,:ACTIVE,:DEFAULT)";
            $row = $this->db->prepare($query);
	    sqlerror($this->db,$query);
	    $row->bindParam(':INBOX_CODE',                      $_POST[inbox_code],                   	PDO::PARAM_STR);
            $row->bindParam(':INBOX_DESC',                      $_POST[inbox_description],            	PDO::PARAM_STR);
            $row->bindParam(':INBOX_DB_PATH',                   $_POST[inbox_db_path],                	PDO::PARAM_STR);
            $row->bindParam(':INBOX_DB_TABLE',                  $_POST[inbox_db_table],                	PDO::PARAM_STR);
            $row->bindParam(':AUTO',                            $_POST[automatic_f],                   	PDO::PARAM_STR);
            $row->bindParam(':MAILBOX_READONLY',                $_POST[mailbox_readonly_f],           	PDO::PARAM_INT);
            $row->bindParam(':MAILBOX_SERVER',                  $_POST[mailbox_server],                	PDO::PARAM_STR);
            $row->bindParam(':MAILBOX_SERVER_PROT',             $_POST[mailbox_server_protocol_id],   	PDO::PARAM_INT);
            $row->bindParam(':MAILBOX_SERVER_PORT',             $_POST[mailbox_server_port],          	PDO::PARAM_INT);
            $row->bindParam(':MAILBOX_TREATED_FOLDER',          $_POST[mailbox_treated_folder], 	PDO::PARAM_STR);
            $row->bindParam(':USER',                            $_POST[user_code],                   	PDO::PARAM_STR);
            $row->bindParam(':PASS',                            $_POST[password],                   	PDO::PARAM_STR);
            $row->bindParam(':ACTIVE',                          $_POST[active_f],                   	PDO::PARAM_INT);
            $row->bindParam(':DEFAULT',                         $_POST[default_f],                   	PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);

            return "On crée le config_inbox";

	}
	function update_prepare()
	{
	    echo "<center><a href='index.php?MODL=$_REQUEST[MODL]' title='RETOUR'><img src='pict/back.png' border=0 alt='RETOUR'></a></center>";
            $web_form = new web_form("config_inbox","edit");
	    $web_form->set_list_options("mailbox_server_protocol_id");
            $web_form->display($this->id);

	    return_query_webform_options($this->db, "mailbox_server_protocol_id", "", "select id, description from config_server_protocol order by id");

            return "On met à jour le config_inbox";
	}

	function update_submit()
	{
            $_POST=utf8_array_decode($_POST);
	    if ($_POST[inbox_code] == ""){print "<div class='ERR'>ERREUR : AUCUN CODE INDIQUE</div>"; exit;}
            $query="UPDATE config_inbox set inbox_code = :INBOX_CODE, inbox_description = :INBOX_DESC, inbox_db_path = :INBOX_DB_PATH, inbox_db_table = :INBOX_DB_TABLE, automatic_F = :AUTO, mailbox_readonly_f = :MAILBOX_READONLY, mailbox_server = :MAILBOX_SERVER, mailbox_server_protocol_id = :MAILBOX_SERVER_PROT, mailbox_server_port = :MAILBOX_SERVER_PORT, mailbox_treated_folder = :MAILBOX_TREATED_FOLDER, user_code = :USER, password = :PASS, active_f = :ACTIVE, default_f = :DEFAULT where id = :ID";
            $row = $this->db->prepare($query);
            sqlerror($this->db,$query);
	    $row->bindParam(':ID',                              $_POST[id],			        PDO::PARAM_INT);
	    $row->bindParam(':INBOX_CODE',                      $_POST[inbox_code],                   	PDO::PARAM_STR);
            $row->bindParam(':INBOX_DESC',                      $_POST[inbox_description],            	PDO::PARAM_STR);
            $row->bindParam(':INBOX_DB_PATH',                   $_POST[inbox_db_path],                	PDO::PARAM_STR);
            $row->bindParam(':INBOX_DB_TABLE',                  $_POST[inbox_db_table],                	PDO::PARAM_STR);
            $row->bindParam(':AUTO',                            $_POST[automatic_f],                   	PDO::PARAM_STR);
            $row->bindParam(':MAILBOX_READONLY',                $_POST[mailbox_readonly_f],           	PDO::PARAM_INT);
            $row->bindParam(':MAILBOX_SERVER',                  $_POST[mailbox_server],                	PDO::PARAM_STR);
            $row->bindParam(':MAILBOX_SERVER_PROT',             $_POST[mailbox_server_protocol_id],   	PDO::PARAM_INT);
            $row->bindParam(':MAILBOX_SERVER_PORT',             $_POST[mailbox_server_port],          	PDO::PARAM_INT);
            $row->bindParam(':MAILBOX_TREATED_FOLDER',          $_POST[mailbox_treated_folder], 	PDO::PARAM_STR);
            $row->bindParam(':USER',                            $_POST[user_code],                   	PDO::PARAM_STR);
            $row->bindParam(':PASS',                            $_POST[password],                   	PDO::PARAM_STR);
            $row->bindParam(':ACTIVE',                          $_POST[active_f],                   	PDO::PARAM_INT);
            $row->bindParam(':DEFAULT',                         $_POST[default_f],                   	PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);

	    return "On met à jour le user";
	}

	function delete()
	{
	    $query="delete from config_inbox where id= :ID";
	    $row = $this->db->prepare($query);
	    sqlerror($this->db,$query);
            $row->bindParam(':ID',              $_POST[id],                     PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);

	    return "On détruit le config_inbox";
	}
}
?>
