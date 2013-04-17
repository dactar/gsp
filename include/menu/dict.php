<?
if (isset($web_page))
{
        $web_page->render();
}

if ($_REQUEST[ACTION] == "Sauvegarder" && $_POST[code] != "")
{
        $attribute = new attribute();
        $attribute->insert_submit();
	exit;
}
if ($_REQUEST[ACTION] == "Valider" && $_POST[dict_id] != "" && $_POST[code] != "")
{
        $attribute = new attribute();
        $attribute->update_submit();
	exit;
}
if ($_REQUEST[ACTION] == "Supprimer" && $_POST[sel_dict_id] != "")
{
        $attribute = new attribute();
        $attribute->delete_submit();
	exit;
}

?>

<script type="text/javascript" src="ext/dhtmlx/dhtmlxcommon.js"></script>
<script type="text/javascript" src="ext/dhtmlx/dhtmlxtree.js"></script>

<script type="text/javascript">

function fill_form(id)
{
		form.setItemValue('sel_dict_id',id);
		form.setItemValue('SEL_CODE',tree.getUserData(id,'code'));	
		form.setItemValue('SEL_DESC',tree.getUserData(id,'description'));
		form.setItemValue('SEL_ACTIVE_F',tree.getUserData(id,'active_f'));
		form.setItemValue('SEL_RANK_N',tree.getUserData(id,'rank_n'));
		form.setItemValue('SEL_PARENT_DICT_ID',tree.getUserData(id,'parent_dict_id'));
		form.setItemValue('SEL_PARENT_CODE',tree.getUserData(id,'parent_code'));
		form.setItemValue('SEL_PARENT_F',tree.getUserData(id,'parent_f'));
		form.setItemValue('SEL_MANDATORY_F',tree.getUserData(id,'mandatory_f'));
		form.setItemLabel('SEL_REMARK','Dernière modification');
		form.setItemValue('SEL_REMARK',tree.getUserData(id,'last_modif_d') + " / " + tree.getUserData(id,'last_user_code'));
		
		if (tree.getUserData(id,'mandatory_f') == 1 || tree.getUserData(id,'parent_f') == '')
		{
			form.disableItem('delete');	
		}
		else
		{
			form.enableItem('delete');
		}
}

function move_form(ACTION)
{
	if (ACTION == 'CODE')
	{
                form.setItemValue('dict_id',form.getItemValue('sel_dict_id'));
                form.setItemValue('code',form.getItemValue('SEL_CODE'));
                form.setItemValue('description',form.getItemValue('SEL_DESC'));
                form.setItemValue('active_f',form.getItemValue('SEL_ACTIVE_F'));
                form.setItemValue('rank_n',form.getItemValue('SEL_RANK_N'));
                form.setItemValue('parent_dict_id',form.getItemValue('SEL_PARENT_DICT_ID'));
		form.setItemValue('PARENT_CODE',form.getItemValue('SEL_PARENT_CODE'));
		
		if(form.getItemValue('SEL_MANDATORY_F') == '1')
		{
			form.disableItem('code');
			form.disableItem('copy_parent_attribute');
		}
		else
		{
			form.enableItem('code');
			form.enableItem('copy_parent_attribute');
		}

	}
	if (ACTION == 'PARENT_CODE')
	{
                form.setItemValue('parent_dict_id',form.getItemValue('sel_dict_id'));
                form.setItemValue('PARENT_CODE',form.getItemValue('SEL_CODE'));

		if(form.getItemValue('SEL_PARENT_F') == '0')
		{
			form.disableItem('insert');
		}
		else
		{
			form.enableItem('insert');
		}
	}
}

function refresh_tree(ITEM_TO_OPEN)
{
	tree.deleteChildItems(0);
	tree.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=dict', function (){
	tree.openItem(ITEM_TO_OPEN);
	});
}

function delete_item_tree(ITEM_TO_DEL)
{
	tree.deleteItem(ITEM_TO_DEL,false);
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
	<div id="dict_treeID"></div>
        <script>
                        tree=new dhtmlXTreeObject("dict_treeID","100%","100%",0);
                        tree.setImagePath("ext/dhtmlx/imgs/");
                        tree.setOnClickHandler(fill_form);
                        tree.loadXML("index.php?MODL=GETX&TYPE=tree&OBJECT=dict");
        </script>
	</td>
	<td valign="top">
<?
        $web_form = new web_form("dict","all");
        $web_form->display();
?>
	</td>
</tr>
<tr>
	<td colspan="3">
<?
if ($_REQUEST[AFFICHE] != "" ) display_table($db,"SELECT * from dict_vw order by dict_id;");
?>
	</td>
</tr>
</table>
