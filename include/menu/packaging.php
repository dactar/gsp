<?
if (isset($web_page))
{
        $web_page->render();
}

$package = new package();
function form_err()
{
	if ($_POST[APPL_ID] == "")
	{
		print "<div class='ERR'>Erreur : le package n'a pas d'application</div>";
	}
	if ($_POST[TYPE_ID] == "")
	{
		print "<div class='ERR'>Erreur : le package n'a pas de type</div>";
	}
        if ($_REQUEST[appl_dict_id] == "")
        {
                print "<div class='ERR'>Erreur : le package n'a pas d'application</div>";
        }
        if ($_REQUEST[type_dict_id] == "")
        {
                print "<div class='ERR'>Erreur : le package n'a pas de type</div>";
        }
}

if ($_REQUEST[ACTION] == "Sauvegarder"  && $_REQUEST[code] != "")
{
        if ($_REQUEST[appl_dict_id] != "" && $_REQUEST[type_dict_id] != "")
        {
                $package->insert_submit();
        }
        else
        {
                form_err();
        }
        exit;
}

if ($_REQUEST[ACTION] == "Valider" && $_REQUEST[id] != "" && $_REQUEST[code] != "")
{
        if ($_REQUEST[appl_dict_id] != "" && $_REQUEST[type_dict_id] != "")
        {
                $package->update_submit();
        }
        else
        {
                form_err();
        }
	exit;
}

if ($_REQUEST[ACTION] == "Supprimer" && $_REQUEST[id] != "")
{
        $package->delete_submit();
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
		form.setItemValue('SEL_APPL_ID',id);
                form.setItemValue('SEL_APPL_CODE',get_field(id,'code'));
		form.setItemValue('SEL_APPL_DESC',get_field(id,'description'));

                form.setItemLabel('SEL_REMARK','Dernière modification');
                form.setItemValue('SEL_REMARK',get_field(id,'last_modif_d') + " / " + get_field(id,'last_user_code'));
}

function fill_subform(id)
{
		webform_load_optlist_detpackage_parent_id(get_field(id,'appl_dict_id'));
		form.setItemValue('id',id.substring(3,id.length));
		form.setItemValue('code',get_field(id,'code'));
		form.setItemValue('description',get_field(id,'description'));
		form.setItemValue('appl_dict_id',get_field(id,'appl_dict_id'));
		form.setItemValue('APPL_CODE',get_field(id,'appl_code'));
		form.setItemValue('parent_id',get_field(id,'parent_id'));
		form.setItemValue('prod_f',get_field(id,'prod_f'));
		form.setItemValue('planif_d',get_field(id,'planif_d'));
		form.setItemValue('rank_n',get_field(id,'rank_n'));
		form.setItemValue('type_dict_id',get_field(id,'type_dict_id'));

                form.setItemLabel('REMARK','Dernière modification');
                form.setItemValue('REMARK',get_field(id,'last_modif_d') + " / " + get_field(id,'last_user_code'));
}

function move_form(ACTION)
{
	if (ACTION == 'CODE')
	{
                form.setItemValue('appl_dict_id',form.getItemValue('SEL_APPL_ID'));
                form.setItemValue('APPL_CODE',form.getItemValue('SEL_APPL_CODE'));
		webform_load_optlist_detpackage_parent_id(form.getItemValue('SEL_APPL_ID'));
	}
}

function refresh_tree(ITEM_TO_OPEN)
{
	tree.deleteChildItems(0);
	tree.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=package', function (){
	tree.openItem(ITEM_TO_OPEN);
	});
}

function delete_item_tree(ITEM_TO_DEL)
{
	tree.deleteItem('sub'+ITEM_TO_DEL,false);
}

</script>

<?return_query_webform_options($db, "type_dict_id", "", "select dict_id, code from dict_vw where parent_code = 'packaging' and active_f = 1 order by rank_n");?>
<?return_query_dyn_opt_list(detpackage,PARENT_ID,$db,package,appl_dict_id,id,code)?>
<?return_dynamic_webform_options(detpackage,parent_id,$db,package,appl_dict_id,id,code)?>

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
	<td valign="top">
<?
        $web_form = new web_form("package","all");
	$web_form->set_list_options("type_dict_id");
        $web_form->display();
?>
	</td>
</tr>
<tr>
	<td colspan="3">
<?
if ($_REQUEST[AFFICHE] != "" ) display_table($db,"SELECT * from package_vw;");
?>
	</td>
</tr>
</table>
