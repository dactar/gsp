<?
echo "
<div id='gridbox3' width='100%' height='100%' style='background-color:white;overflow:hidden;'></div>
<div style='display:none'>
	<div id='type_flt_box'><select style='width:90%; font: 10px verdana,arial,helvetica;' onclick='(arguments[0]||window.event).cancelBubble=true;' onchange='xgrid_filter()'></select></div>

	<div id='appl_flt_box'><select style='width:90%; font: 10px verdana,arial,helvetica;' onclick='(arguments[0]||window.event).cancelBubble=true;' onchange='xgrid_filter()'></select></div>


	<div id='package_flt_box'><select style='width:90%; font: 10px verdana,arial,helvetica;' onclick='(arguments[0]||window.event).cancelBubble=true;' onchange='xgrid_filter()'></select></div>
	<div id='segment_flt_box'><select style='width:90%; font: 10px verdana,arial,helvetica;' onclick='(arguments[0]||window.event).cancelBubble=true;' onchange='xgrid_filter()'></select></div>

	<div id='ext_code_flt_box'><input type='90%' size='10' style='border:1px solid gray;font: 10px verdana,arial,helvetica;' onclick='(arguments[0]||window.event).cancelBubble=true;' onkeyup='xgrid_filter()'></input></div>

	<div id='summary_flt_box'><input type='90%' size='45' style='border:1px solid gray;font: 10px verdana,arial,helvetica;' onclick='(arguments[0]||window.event).cancelBubble=true;' onkeyup='xgrid_filter()'></input></div>

	<div id='limit_d_flt_box'><input type='90%' size='10' style='border:1px solid gray;font: 10px verdana,arial,helvetica;' onclick='(arguments[0]||window.event).cancelBubble=true;' onkeyup='xgrid_filter()'></input></div>

	<div id='owner_flt_box'><select style='width:90%; font: 10px verdana,arial,helvetica;' onclick='(arguments[0]||window.event).cancelBubble=true;' onchange='xgrid_filter()'></select></div>

	<div id='status_flt_box'><select style='width:90%; font: 10px verdana,arial,helvetica;' onclick='(arguments[0]||window.event).cancelBubble=true;' onchange='xgrid_filter()'></select></div>

	<div id='daycnt_gt_flt_box'><input type='90%' size='1' style='width:20px;border:1px solid gray; font: 10px verdana,arial,helvetica;' onclick='this.value=\"ok\";(arguments[0]||window.event).cancelBubble=true;' onkeyup='xgrid_filter()'></input></div>
	<div id='daycnt_lt_flt_box'><input type='90%' size='1' style='width:20px;border:1px solid gray; font: 10px verdana,arial,helvetica;' onclick='this.value=\"ok\";(arguments[0]||window.event).cancelBubble=true;' onkeyup='xgrid_filter()'></input></div>


	<div id='open_d_flt_box'><input type='90%' size='10' style='border:1px solid gray; font: 10px verdana,arial,helvetica;' onclick='(arguments[0]||window.event).cancelBubble=true;' onkeyup='xgrid_filter()'></input></div>


	<div id='priority_flt_box'><select style='width:90%; font: 10px verdana,arial,helvetica;' onclick='(arguments[0]||window.event).cancelBubble=true;' onchange='xgrid_filter()'></select></div>

	<div id='contact_flt_box'><select style='width:90%; font: 10px verdana,arial,helvetica;' onclick='(arguments[0]||window.event).cancelBubble=true;' onchange='xgrid_filter()'></select></div>

</div>
<script>
                mygrid3 = new dhtmlXGridObject('gridbox3');
                mygrid3.setImagePath('pict/');
		mygrid3.enableMultiline(false);
                mygrid3.enableMultiselect(false);
		mygrid3.setStyle('color:black','color:black','border:1px solid gray','background-color:#FAFAD2');
";
		return_query_grid_options($db,"mygrid3",1, "select dict_id, code from dict_vw where parent_code = 'type' order by rank_n");
		return_query_grid_options($db,"mygrid3",9, "select dict_id, code from dict_vw where parent_code = 'open' and active_f=1");
		return_query_grid_options($db,"mygrid3",9, "select dict_id, code from dict_vw where parent_code = 'closed' and active_f=1");
		return_query_grid_options($db,"mygrid3",12,"select dict_id, description from dict_vw where parent_code = 'priority' and active_f=1");
echo "
                mygrid3.init();

                mygrid3.loadXML('index.php?MODL=GETX&OBJECT=event&STATE=$STATE&MODL_OPTION=$_REQUEST[MODL_OPTION]&PROJECT_ID=$_REQUEST[PROJECT_ID]', xgrid_filter_create);
		mygrid3.attachEvent('onEditCell',xgrid_on_edit);
</script>
<div id=return_ajax></div>
";
