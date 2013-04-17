<?
if (isset($web_page))
{
        $web_page->render();
}

if ($_REQUEST[ACTION] == "Sauvegarder" && $_POST[code] != "")
{
	if ($_POST[group_dict_id] != "")
	{
		$_POST=utf8_array_decode($_POST);
        	$query="INSERT into contact (code, name, group_dict_id, active_f, phone, mobile, email, url) VALUES (:CODE,:NAME,:GROUP_ID,:ACTIF,:PHONE, :MOBILE, :EMAIL,:URL)";
		$row = $db->prepare($query);
		$row->bindParam(':CODE', $_POST[code], PDO::PARAM_STR);
		$row->bindParam(':NAME', $_POST[name], PDO::PARAM_STR);
		$row->bindParam(':GROUP_ID', $_POST[group_dict_id], PDO::PARAM_INT);
		$row->bindParam(':ACTIF', $_POST[active_f], PDO::PARAM_INT);
		$row->bindParam(':PHONE', $_POST[phone], PDO::PARAM_STR);
		$row->bindParam(':MOBILE', $_POST[mobile], PDO::PARAM_STR);
		$row->bindParam(':EMAIL', $_POST[email], PDO::PARAM_STR);
                $row->bindParam(':URL', $_POST[url], PDO::PARAM_STR);
        	$row->execute(); 
		sqlerror($db,$query);
	}
	else
	{
		print "<div class='ERR'>Erreur : le contact n'a pas de groupe</div>";
	}
	exit;
}
if ($_REQUEST[ACTION] == "Valider" && $_POST[id] != "" && $_POST[code] != "")
{
	$_POST=utf8_array_decode($_POST);
	$query="UPDATE contact set code = :CODE, name = :NAME, group_dict_id = :GROUP_ID, active_f = :ACTIF, 
	                           phone = :PHONE, mobile = :MOBILE, email = :EMAIL, url = :URL where id = :ID";
	$row = $db->prepare($query);
	$row->bindParam(':ID', $_POST[id], PDO::PARAM_INT);
	$row->bindParam(':CODE', $_POST[code], PDO::PARAM_STR);
	$row->bindParam(':NAME', $_POST[name], PDO::PARAM_STR);
	$row->bindParam(':GROUP_ID', $_POST[group_dict_id], PDO::PARAM_INT);
	$row->bindParam(':ACTIF', $_POST[active_f], PDO::PARAM_INT);
	$row->bindParam(':PHONE', $_POST[phone], PDO::PARAM_STR);
	$row->bindParam(':MOBILE', $_POST[mobile], PDO::PARAM_STR);
	$row->bindParam(':EMAIL', $_POST[email], PDO::PARAM_STR);
        $row->bindParam(':URL', $_POST[url], PDO::PARAM_STR);
	$row->execute();
	sqlerror($db,$query);
	exit;
}
if ($_REQUEST[ACTION] == "Supprimer" && $_POST[id] != "")
{
	$query="DELETE from contact where id = :ID";
	$row = $db->prepare($query);
	$row->bindParam(':ID', $_POST[id], PDO::PARAM_INT);
	$row->execute();
	sqlerror($db,$query);
	exit;
}

?>
<script type="text/javascript" src="ext/dhtmlx/dhtmlxcommon.js"></script>
<script type="text/javascript" src="ext/dhtmlx/dhtmlxtree.js"></script>

<script type="text/javascript">

function go_fill(id)
{
	if(id.substring(0,3) == "sub")
	{
		fill_subform(id)
	}
	else
	{
		fill_form(id)
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

function fill_form(id)
{
                form.setItemValue('SEL_ORGA_ID',id);
                form.setItemValue('SEL_ORGA_CODE',get_field(id,'code'));
                form.setItemValue('SEL_ORGA_DESC',get_field(id,'description'));
		form.setItemValue('SEL_ORGA_PARENT_CODE',get_field(id,'parent_code'));

		form.setItemLabel('SEL_REMARK','Dernière modification');
		form.setItemValue('SEL_REMARK',get_field(id,'last_modif_d') + " / " + get_field(id,'last_user_code'));
}

function fill_subform(id)
{
		form.setItemValue('id',id.substring(3,id.length));
                form.setItemValue('code',get_field(id,'code'));
                form.setItemValue('name',get_field(id,'name'));
                form.setItemValue('group_dict_id',get_field(id,'group_dict_id'));
                form.setItemValue('ORGA_GROUP_CODE',get_field(id,'group_code'));
                form.setItemValue('ORGA_PARENT_CODE',get_field(id,'organisation_code'));
                form.setItemValue('active_f',get_field(id,'active_f'));
                form.setItemValue('phone',get_field(id,'phone'));
		form.setItemValue('mobile',get_field(id,'mobile'));
                form.setItemValue('email',get_field(id,'email'));
                form.setItemValue('url',get_field(id,'url'));

                form.setItemLabel('REMARK','Dernière modification');
                form.setItemValue('REMARK',get_field(id,'last_modif_d') + " / " + get_field(id,'last_user_code'));
}

function move_form(ACTION)
{
	if (ACTION == 'CODE')
	{
                form.setItemValue('group_dict_id',form.getItemValue('SEL_ORGA_ID'));
                form.setItemValue('ORGA_GROUP_CODE',form.getItemValue('SEL_ORGA_CODE'));
		form.setItemValue('ORGA_PARENT_CODE',form.getItemValue('SEL_ORGA_PARENT_CODE'));
	}
}

function refresh_tree(ITEM_TO_OPEN)
{
        tree.deleteChildItems(0);
        tree.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=contact', function (){
        tree.openItem(ITEM_TO_OPEN);
        });
}

function delete_item_tree(ITEM_TO_DEL)
{
        tree.deleteItem('sub'+ITEM_TO_DEL,false);
}


</script>

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
	<div id="contact_treeID"></div>
        <script>
                        tree=new dhtmlXTreeObject("contact_treeID","100%","100%",0);
                        tree.setImagePath("ext/dhtmlx/imgs/");
                        tree.setOnClickHandler(go_fill);
                        tree.loadXML("index.php?MODL=GETX&TYPE=tree&OBJECT=contact");
        </script>
	</td>
	<td valign="top">
<?
        $web_form = new web_form("contact","all");
        $web_form->display();
?>
	</td>
</tr>
<tr>
	<td colspan="3">
<?
if ($_REQUEST[AFFICHE] != "" ) display_table($db,"SELECT * from contact_vw;");
?>
	</td>
</tr>
</table>
