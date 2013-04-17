<?
echo "
<div id='gridbox2' width='100%' height='100%' style='background-color:white;overflow:hidden;'></div>
<script>
                mygrid2 = new dhtmlXGridObject('gridbox2');
                mygrid2.setImagePath('pict/');
		mygrid2.enableMultiline(false);
                mygrid2.enableMultiselect(false);
		mygrid2.setStyle('color:black','color:black','color:black','background-color:#FAFAD2');
";
		return_query_grid_options($db,"mygrid2",1, "select dict_id, code from dict_vw where parent_code = 'type' order by rank_n");
		return_query_grid_options($db,"mygrid2",9, "select dict_id, code from dict_vw where parent_code = 'open' and active_f=1");
		return_query_grid_options($db,"mygrid2",9, "select dict_id, code from dict_vw where parent_code = 'closed' and active_f=1");
		return_query_grid_options($db,"mygrid2",12,"select dict_id, description from dict_vw where parent_code = 'priority' and active_f=1");
echo "
                mygrid2.init();

		mygrid2.loadXML('index.php?MODL=GETX&OBJECT=event&STATE=$STATE&ASSIGN=null&PROJECT_ID=$_REQUEST[PROJECT_ID]');

		mygrid2.attachEvent('onEditCell',function(){return false});
</script>
";
