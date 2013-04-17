<?
class user
{
	public $db;
	public $id;
	public $code;
	public $name;
	public $alias;
	public $contact_id;
	public $admin_f;
	public $active_f;
	public $last_modif_d;
	public $last_user_code;

	function __tostring()
	{
	    return "Cette classe permet de définir et manipuler un utilisateur<br/>";
	}

	function __construct()
	{
	    global $db;
	    $this->db = $db;
	    if($_REQUEST[ID] != "")
	    {
	    	$this->id		= $_REQUEST[ID];
	    	$this->code 		= return_query($this->db,"SELECT code from user where id = $this->id");
	    	$this->name 		= return_query($this->db,"SELECT name from user where id = $this->id");
	    	$this->alias 		= return_query($this->db,"SELECT alias from user where id = $this->id");
		$this->contact_id 	= return_query($this->db,"SELECT contact_id from user where id = $this->id");
	    	$this->admin_f 		= return_query($this->db,"SELECT admin_f from user where id = $this->id");
	    	$this->active_f 	= return_query($this->db,"SELECT active_f from user where id = $this->id");
		$this->last_modif	= return_query($this->db,"SELECT last_modif_d || ' / ' || last_user_code from user_vw where id = $this->id");
	    }

	}

	function getxml()
	{
            $object=$this;
            $array = (array) $object;
            unset($array[db]);
            $xml=array2xml("data",$array);
	    echo $xml;
            return "retourne l'utilisateur sous forme xml";
	}

	function display_actions($admin)
	{
	    echo "<center>";
	    echo "<form id='USER_DETAIL_ACTION_FORM' action = '' method='post'>";
	    if ($admin == 1)
	    {
		if ($_REQUEST[ACTION] == "Modification")
		{
			echo "<br>";
			if ($this->active_f == 1)
			{
	    			echo "<input type='submit' name='ACTION' value='Désactiver'></input>";
			}
			else
			{
				echo "<input type='submit' name='ACTION' value='Activer'></input>";
			}
			echo "<input type='submit' name='ACTION' value='Changer mot de passe'></input>";
			echo "<br><br><a href='index.php?MODL=$_REQUEST[MODL]' title='RETOUR'><img src='pict/back.png' border=0 alt='RETOUR'></a>";
		}
		else
		{
			if ($_REQUEST[MODL] != "CPWD")
			{
				echo "<input type='submit' name='ACTION' value='Créer utilisateur'></input>";
			}
		}
	    }
	    echo "</form>";
	    echo "</center>";
	    return "On affiche les actions disponibles";
	}

	function display_data()
	{
		if ($_REQUEST[MODL] == "CPWD")
		{
			$this->update_prepare();
		}
	}

	function list_users($admin)
	{
	    if ($admin == 1)
	    {
	    manage_table("MODL=AUSR&ACTION=Modification","ID",$this->db,"SELECT id, code as 'Code', name as 'Name', alias as 'Alias', phone as 'Phone', email as 'E-mail', active_f as 'Active', admin_f as 'Admin' from user_vw",0,0);
	    }
	    else
	    {
	    display_table($this->db,"SELECT code as 'Code', name as 'Name', alias as 'Alias', phone as 'Phone', email as 'E-mail', active_f as 'Active', admin_f as 'Admin' from user_vw");
	    }
	    return "On affiche les utilisateurs";
	}

	function list_users_with_contact()
	{
	    echo "<br/>";
	    manage_table("MODL=MAIN&ACTION=Assigner_validation&EVENT_ID=$_REQUEST[EVENT_ID]","APPL_USER_ID",$this->db,"SELECT id, code, name from user_vw where active_f = 1 and contact_id isnull = 0",0,0);
	    return "On affiche les utilisateurs reliés à des contacts";
	}

	function display()
	{
	    display_table($this->db,"SELECT * from user");
	    return "On affiche l'utilisateur";
	}

	function create_prepare()
	{
            $web_form = new web_form("user","create");
            $web_form->display();

	    echo"
            <script type='text/javascript' src='ext/dhtmlx/dhtmlxcommon.js'></script>
            <script type='text/javascript' src='ext/dhtmlx/dhtmlxtree.js'></script>
";
	    echo "<script language='javascript'>";
            echo "
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
                    form.setItemValue('CONTACT_CODE',get_field(id,'code'));
                    form.setItemValue('CONTACT_NAME',get_field(id,'name'));
                    form.setItemValue('CONTACT_PHONE',get_field(id,'phone'));
                    form.setItemValue('CONTACT_EMAIL',get_field(id,'email'));
            }
	    </script>";

	    echo "<div id='contact_treeID' style='width:100%;height:100%;overflow:auto;visibility:hidden;'><form><br><center><input type='button' onclick='code: dhxWins.window(\"win_tree\").hide();' value='Annuler'></form><br></center></div>";


            echo "       
                <script>
                        tree=new dhtmlXTreeObject('contact_treeID','100%','100%',0);
                        tree.setImagePath('ext/dhtmlx/imgs/');
                        tree.setOnClickHandler(go_fill);
                        tree.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=contact');
                </script>
            ";

	    return "On affiche le formulaire de création l'utilisateur";
	}

	function create_submit()
	{
	    $_POST=utf8_array_decode($_POST); 
	    if ($_POST[code] == ""){print "<div class='ERR'>ERREUR : AUCUN CODE INDIQUE</div>"; exit;}
	    if ($_POST[contact_id] == ""){print "<div class='ERR'>ERREUR : AUCUN CONTACT INDIQUE</div>"; exit;}
	    if ($_POST[PASS_NEW] == ""){print "<div class='ERR'>ERREUR : AUCUN MOT DE PASSE INDIQUE</div>"; exit;}
	    if ($_POST[PASS_NEW_CONFIRM] == ""){print "<div class='ERR'>ERREUR : AUCUNE CONFIRMATION DU MOT DE PASSE INDIQUE</div>"; exit;}
	    if ($_POST[PASS_NEW] != $_POST[PASS_NEW_CONFIRM]){print "<div class='ERR'>ERREUR : MOT DE PASSE ET CONFIRMATION DU MOT DE PASSE NON IDENTIQUES</div>"; exit;}
	    $query="INSERT INTO user (code, name, alias, active_f, admin_f, contact_id) values (:CODE,:NAME,:ALIAS,:ACTIF,:ADMIN,:CONTACT_ID)";
            $row = $this->db->prepare($query);
	    sqlerror($this->db,$query);
	    $row->bindParam(':CODE',            $_POST[code],                   PDO::PARAM_STR);
            $row->bindParam(':NAME',            $_POST[name],                   PDO::PARAM_STR);
            $row->bindParam(':ALIAS',           $_POST[alias],                  PDO::PARAM_STR);
            $row->bindParam(':ACTIF',           $_POST[active_f],                  PDO::PARAM_INT);
            $row->bindParam(':ADMIN',           $_POST[admin_f],                  PDO::PARAM_INT);
            $row->bindParam(':CONTACT_ID',      $_POST[contact_id],             PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);


	    $query='SELECT id from user where code = :CODE';
            $row = $this->db->prepare($query);
            sqlerror($this->db,$query);
            $row->bindParam(':CODE',            $_POST[code],                   PDO::PARAM_STR);
            $row->execute();
            sqlerror($this->db,$query);
            $uid=$row->fetchAll(PDO::FETCH_COLUMN, 0);

	    $query='UPDATE user set password_c = :PASSWORD where id = :UID';
	    $row = $this->db->prepare($query);
            sqlerror($this->db,$query);
            $row->bindParam(':PASSWORD',        $_POST[PASS_NEW_CONFIRM],       PDO::PARAM_STR);
	    $row->bindParam(':UID',             $uid[0],                        PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);

            if (!isset($uid[0]))
	    {
		echo "<div class='ERR'>CREATION DE L'UTILISATEUR ECHOUEE</div>";
	    }
            return "On crée l'utilisateur";

	}
	function update_prepare()
	{
	    if ($_REQUEST[MODL] == "CPWD" || $_REQUEST[ACTION] == "Changer mot de passe")
	    {
		if ($_REQUEST[MODL] == "CPWD")
		{
			$web_form = new web_form("user","change_password");
			$web_form->display();
		}
		else
		{
			echo "<b>Changement de mot de passe</b><br><br><center>$this->code : $this->name";
			echo "<br><br><a href='index.php?MODL=$_REQUEST[MODL]' title='RETOUR'><img src='pict/back.png' border=0 alt='RETOUR'></a>";
			$web_form = new web_form("user","reset_password");
			$web_form->display($this->id);
		}
	    }
	    else
	    {
            	$web_form = new web_form("user","edit");
            	$web_form->display($this->id);
            	echo"
            	<script type='text/javascript' src='ext/dhtmlx/dhtmlxcommon.js'></script>
            	<script type='text/javascript' src='ext/dhtmlx/dhtmlxtree.js'></script>
";
            	echo "<script language='javascript'>";
            	echo "
			if (parent.dhxWins.window('win_tree')) {parent.dhxWins.window('win_tree').close()}
                	function go_fill(id)
                	{
                        	if(id.substring(0,3) == 'sub')
                        	{
                                	fill_subform(id);
					if (parent.dhxWins.window('win_tree')) {parent.dhxWins.window('win_tree').hide()};
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
                    		form.setItemValue('CONTACT_CODE',get_field(id,'code'));
                    		form.setItemValue('CONTACT_NAME',get_field(id,'name'));
                    		form.setItemValue('CONTACT_PHONE',get_field(id,'phone'));
                    		form.setItemValue('CONTACT_EMAIL',get_field(id,'email'));
            		}

            		</script>";

	    
   	    	echo "<div id='contact_treeID' style='width:100%;height:100%;overflow:auto;visibility:hidden;'><form><br><center><input type='button' onclick='code: dhxWins.window(\"win_tree\").hide();' value='Annuler'></form><br></center></div>";

	    	echo "
                	<script>
                        	tree=new dhtmlXTreeObject('contact_treeID','100%','100%',0);
                        	tree.setImagePath('ext/dhtmlx/imgs/');
                        	tree.setOnClickHandler(go_fill);
                        	tree.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=contact',function(){go_fill('sub$this->contact_id');});
                	</script>";
	    }
            return "On met à jour l'utilisateur";
	}

	function update_submit()
	{
            $_POST=utf8_array_decode($_POST);
            if ($_REQUEST[MODL] == "CPWD")
	    {
		if ($_POST[PASS_CURR] != $_COOKIE[GSP_PASS]){print "<div class='ERR'>ERREUR : MOT DE PASSE COURANT INCORRECT</div>"; exit;}
		if ($_POST[PASS_NEW] != $_POST[PASS_NEW_CONFIRM]){print "<div class='ERR'>ERREUR : MOT DE PASSE ET CONFIRMATION DU MOT DE PASSE NON IDENTIQUES</div>"; exit;}
            	$query='UPDATE user set password_c = :PASSWORD where code = :CODE';
            	$row = $this->db->prepare($query);
            	sqlerror($this->db,$query);
            	$row->bindParam(':PASSWORD',        $_POST[PASS_NEW_CONFIRM],       PDO::PARAM_STR);
            	$row->bindParam(':CODE',            $_COOKIE[GSP_USER],             PDO::PARAM_STR);
            	$row->execute();
            	sqlerror($this->db,$query);
		setcookie("GSP_PASS","$_POST[PASS_NEW_CONFIRM]",FALSE,"/");
	    }
	    else
	    {
		if ($_REQUEST[PASS_NEW] != "")
		{
			if ($_POST[PASS_NEW] != $_POST[PASS_NEW_CONFIRM]){print "<div class='ERR'>ERREUR : MOT DE PASSE ET CONFIRMATION DU MOT DE PASSE NON IDENTIQUES</div>"; exit;}
	                $query='UPDATE user set password_c = :PASSWORD where id = :ID';
       			$row = $this->db->prepare($query);
                	sqlerror($this->db,$query);
                	$row->bindParam(':PASSWORD',        $_POST[PASS_NEW_CONFIRM],       PDO::PARAM_STR);
                	$row->bindParam(':ID',              $_POST[id],                     PDO::PARAM_INT);
                	$row->execute();
                	sqlerror($this->db,$query);
			if ($_POST[code] == $_COOKIE[GSP_USER])
			{
				setcookie("GSP_PASS","$_POST[PASS_NEW_CONFIRM]",FALSE,"/");
			}
		}
		else
		{
	    		if ($_POST[code] == ""){print "<div class='ERR'>ERREUR : AUCUN CODE INDIQUE</div>"; exit;}
            		if ($_POST[contact_id] == ""){print "<div class='ERR'>ERREUR : AUCUN CONTACT INDIQUE</div>"; exit;}
            		$query="UPDATE user set code = :CODE, name = :NAME, alias = :ALIAS, admin_f = :ADMIN, contact_id = :CONTACT_ID where id = :ID";
            		$row = $this->db->prepare($query);
            		sqlerror($this->db,$query);
	    		$row->bindParam(':ID',              $_POST[id],			    PDO::PARAM_INT);
            		$row->bindParam(':CODE',            $_POST[code],                   PDO::PARAM_STR);
            		$row->bindParam(':NAME',            $_POST[name],                   PDO::PARAM_STR);
            		$row->bindParam(':ALIAS',           $_POST[alias],                  PDO::PARAM_STR);
            		$row->bindParam(':ADMIN',           $_POST[admin_f],                PDO::PARAM_INT);
            		$row->bindParam(':CONTACT_ID',      $_POST[contact_id],             PDO::PARAM_INT);
            		$row->execute();
            		sqlerror($this->db,$query);
		}
	    }
	    return "On met à jour le user";
	}

	function disable()
	{
            $query="UPDATE user set active_f=0 where id = :ID";
       	    $row = $this->db->prepare($query);
	    $row->bindParam(':ID', $this->id, PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);
	    return "On désactive l'utilisateur";
	}

	function enable()
	{
	    $query="UPDATE user set active_f=1 where id = :ID";
	    $row = $this->db->prepare($query);
	    $row->bindParam(':ID', $this->id, PDO::PARAM_INT);
	    $row->execute();
	    sqlerror($this->db,$query);
	    return "On active l'utilisateur";
	}
}
?>
