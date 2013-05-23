<?
class package
{
	public $db;
	public $web_page;
	public $id;

	function __tostring()
	{
	    return "Cette classe permet de définir et manipuler un package<br/>";
	}

	function __construct()
	{
		global $db;
		global $web_page;

                $this->db = $db;
                $this->web_page = $web_page;
	}

	function display_actions()
	{
                $this->web_page->add_jsfile("ext/dhtmlx/dhtmlxcommon.js");
                $this->web_page->add_jsfile("ext/dhtmlx/dhtmlxtree.js");

                $this->web_page->add_script(return_query_webform_options($this->db, "type_dict_id", "", "select dict_id, code from dict_vw where parent_code = 'packaging' and active_f = 1 order by rank_n",TRUE));
                $this->web_page->add_script(return_query_dyn_opt_list(detpackage,PARENT_ID,$this->db,package,appl_dict_id,id,code,TRUE));
                $this->web_page->add_script(return_dynamic_webform_options(detpackage,parent_id,$this->db,package,appl_dict_id,id,code,TRUE));

                $this->web_page->add_jsfile("js/gsppackage.js");

	}

	function display_data()
        {
                if ($_REQUEST[AFFICHE] != "" )
                {
                        $this->web_page->add_html(return_table($this->db,"SELECT * from package_vw;"));
                        $this->web_page->add_html("<center><a href='index.php?MODL=$_REQUEST[MODL]' title='RETOUR'><img src='pict/back.png' border=0 alt='RETOUR'></a></center>");
                }
                else
                {
                        $this->web_page->add_html('
<table>
<tr>
        <td valign="top" class="TDW200">
        <form action="">
                <p>
                <input type="button" value="-" onclick="javascript:tree.closeAllItems(0);"></input>
                <input type="button" value="+" onclick="javascript:tree.openAllItems(0);"></input>
                </p>
        </form>
        </td>
</tr>
<tr>
        <td valign="top">
        <div id="package_treeID"></div>
        <script>
                        tree=new dhtmlXTreeObject("package_treeID","100%","100%",0);
                        tree.setImagePath("ext/dhtmlx/imgs/");
                        tree.setOnClickHandler(go_fill);
                        tree.loadXML("index.php?MODL=GETX&TYPE=tree&OBJECT=package");
        </script>
        </td>
        <td valign="top">');

        $web_form = new web_form("package","all",TRUE);
        $web_form->set_list_options("type_dict_id");
        $this->web_page->add_html($web_form->display());

        $this->web_page->add_html('</td>
</tr>
<tr>
        <td colspan="3"></td>
</tr>
</table>');
                }
                $this->web_page->render();
                return "On affiche les actions disponibles";
        }

	function create_submit()
	{
		$_POST=utf8_array_decode($_POST);
		$query="INSERT into package (parent_id, appl_dict_id, code, description, rank_n, type_dict_id, prod_f, planif_d) VALUES (:PARENT_ID,:APPL_ID,:CODE,:DESCRIPTION,:RANK_N,:TYPE_ID,:PROD_F,:PLANIF_D)";
                $row = $this->db->prepare($query);
                if ( $_POST[parent_id] != "0" )
                {
                    $row->bindParam(':PARENT_ID',   $_POST[parent_id],   PDO::PARAM_INT);
                }
                $row->bindParam(':APPL_ID',     $_POST[appl_dict_id],     PDO::PARAM_INT);
                $row->bindParam(':CODE',        $_POST[code],             PDO::PARAM_STR);
                $row->bindParam(':DESCRIPTION', $_POST[description],      PDO::PARAM_STR);
                $row->bindParam(':RANK_N',      $_POST[rank_n],           PDO::PARAM_INT);
                $row->bindParam(':TYPE_ID',     $_POST[type_dict_id],     PDO::PARAM_INT);
                $row->bindParam(':PROD_F',      $_POST[prod_f],           PDO::PARAM_INT);
                $row->bindParam(':PLANIF_D',    $_POST[planif_d],         PDO::PARAM_STR);
                $row->execute();
                sqlerror($this->db,$query);
		return "On crée le package";	
	}
	function update_submit()
	{
		$_POST=utf8_array_decode($_POST);
		$query="UPDATE package set parent_id = :PARENT_ID, appl_dict_id = :APPL_ID, code = :CODE, description = :DESCRIPTION, rank_n = :RANK_N, type_dict_id = :TYPE_ID, prod_f = :PROD_F, planif_d = :PLANIF_D where id = :ID";

                $row = $this->db->prepare($query);
                $row->bindParam(':ID', $_POST[id], PDO::PARAM_INT);
                if ( $_POST[parent_id] != "0" )
                {
                $row->bindParam(':PARENT_ID',   $_POST[parent_id],   PDO::PARAM_INT);
                }
                $row->bindParam(':APPL_ID',     $_POST[appl_dict_id],     PDO::PARAM_INT);
                $row->bindParam(':CODE',        $_POST[code],             PDO::PARAM_STR);
                $row->bindParam(':DESCRIPTION', $_POST[description],      PDO::PARAM_STR);
                $row->bindParam(':RANK_N',      $_POST[rank_n],           PDO::PARAM_INT);
                $row->bindParam(':TYPE_ID',     $_POST[type_dict_id],     PDO::PARAM_INT);
                $row->bindParam(':PROD_F',      $_POST[prod_f],           PDO::PARAM_INT);
                $row->bindParam(':PLANIF_D',    $_POST[planif_d],         PDO::PARAM_STR);
                $row->execute();
                sqlerror($this->db,$query);
	
	    	return "On met à jour le package";
	}

	function delete()
	{
	    $query="DELETE from package where id = :ID";
	    $row = $this->db->prepare($query);
	    $row->bindParam(':ID', $_POST[id], PDO::PARAM_INT);
	    $row->execute();
	    sqlerror($this->db,$query);

	    return "On détruit le package";
	}
}
?>
