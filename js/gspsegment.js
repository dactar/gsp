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
                webform_load_optlist_detsegment_parent_id(get_field(id,'appl_dict_id'));
                form.setItemValue('id',id.substring(3,id.length));
                form.setItemValue('code',get_field(id,'code'));
                form.setItemValue('description',get_field(id,'description'));
                form.setItemValue('appl_dict_id',get_field(id,'appl_dict_id'));
                form.setItemValue('APPL_CODE',get_field(id,'appl_code'));
                form.setItemValue('parent_id',get_field(id,'parent_id'));
                form.setItemValue('prod_f',get_field(id,'prod_f'));
		form.setItemValue('supported_f',get_field(id,'supported_f'));
		form.setItemValue('default_f',get_field(id,'default_f'));
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
		webform_load_optlist_detsegment_parent_id(form.getItemValue('SEL_APPL_ID'));
	}
}

function refresh_tree(ITEM_TO_OPEN)
{
	tree.deleteChildItems(0);
	tree.loadXML('index.php?MODL=GETX&TYPE=tree&OBJECT=segment', function (){
	tree.openItem(ITEM_TO_OPEN);
	});
}

function delete_item_tree(ITEM_TO_DEL)
{
	tree.deleteItem('sub'+ITEM_TO_DEL,false);
}
