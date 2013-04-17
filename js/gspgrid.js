function xgrid_on_edit(stage,row,col,newval,oldval)
{
     if(stage==2 && newval != oldval)
     {
        mygrid3.cells(row,mygrid3.getColumnCount() - 1).setValue("<div id='upd'><a href='javascript:xgrid_update_db()'>ok</a></div>");
     }
     return true;
}

function xgrid_update_db()
{
     var i=0;
     var uid=escape(Math.random());
     var data="uid=" + uid;
     var data=data + "&" + "id=" + mygrid3.getSelectedId();
     while (i < mygrid3.getColumnCount() - 1)
     {
         var data=data + "&" + escape(mygrid3.getUserData(0,"sql_column_" + i)) + "=" + escape(mygrid3.cells(mygrid3.getSelectedId(),i).getValue());
         i++;
     }
     send_data(data, 'index.php?MODL=PUTX&OBJECT=MAIN_BOX1', 'post', 'return_ajax');
     mygrid3.cells(mygrid3.getSelectedId(),i).setValue();
}

//filter grid contnet based on values of two filter fields
function xgrid_filter()
{
	var type_val = document.getElementById("type_flt").childNodes[0].value.toLowerCase();
	var appl_val = document.getElementById("appl_flt").childNodes[0].value.toLowerCase();
	var package_val = document.getElementById("package_flt").childNodes[0].value.toLowerCase();
	var segment_val = document.getElementById("segment_flt").childNodes[0].value.toLowerCase();
	var ext_code_val = document.getElementById("ext_code_flt").childNodes[0].value.toLowerCase();
	var summary_val = document.getElementById("summary_flt").childNodes[0].value.toLowerCase();
	var limit_d_val = document.getElementById("limit_d_flt").childNodes[0].value.toLowerCase();
	var owner_val = document.getElementById("owner_flt").childNodes[0].value.toLowerCase();
	var status_val = document.getElementById("status_flt").childNodes[0].value.toLowerCase();
	var daycnt_gt_val = document.getElementById("daycnt_gt_flt").childNodes[0].value;
	var daycnt_lt_val = document.getElementById("daycnt_lt_flt").childNodes[0].value;
	var open_d_val = document.getElementById("open_d_flt").childNodes[0].value.toLowerCase();
	var priority_val = document.getElementById("priority_flt").childNodes[0].value.toLowerCase();
	var contact_val = document.getElementById("contact_flt").childNodes[0].value.toLowerCase();
	
	for(var i=0; i< mygrid3.getRowsNum();i++)
	{
		var type_str = mygrid3.cells2(i,1).getValue().toString().toLowerCase();
		var appl_str = mygrid3.cells2(i,2).getValue().toString().toLowerCase();
		var package_str = mygrid3.cells2(i,3).getValue().toString().toLowerCase();
		var segment_str = mygrid3.cells2(i,4).getValue().toString().toLowerCase();
		var ext_code_str = mygrid3.cells2(i,5).getValue().toString().toLowerCase();
		var summary_str = mygrid3.cells2(i,6).getValue().toString().toLowerCase();
		var limit_d_str = mygrid3.cells2(i,7).getValue().toString().toLowerCase();
		var owner_str = mygrid3.cells2(i,8).getValue().toString().toLowerCase();
		var status_str = mygrid3.cells2(i,9).getValue().toString().toLowerCase();
		var daycnt_nbr = parseInt(mygrid3.cells2(i,10).getValue().toString());
		var open_d_str = mygrid3.cells2(i,11).getValue().toString().toLowerCase();
		var priority_str = mygrid3.cells2(i,12).getValue().toString().toLowerCase();
		var contact_str = mygrid3.cells2(i,13).getValue().toString().toLowerCase();

		if(
		    (type_val=="" || type_str == type_val) && 
		    (appl_val=="" || appl_str == appl_val) && 
		    (package_val=="" || package_str == package_val) && 
		    (segment_val=="" || segment_str == segment_val) &&
	            (ext_code_val == "" || ext_code_str.indexOf(ext_code_val)>=0) &&
		    (summary_val=="" || summary_str.indexOf(summary_val)>=0) &&
                    (limit_d_val=="" || limit_d_str.indexOf(limit_d_val)>=0) &&
		    (owner_val=="" || owner_str == owner_val) &&
		    (status_val=="" || status_str == status_val) &&
		    ((daycnt_gt_val=="" && daycnt_lt_val=="") || 
		     (daycnt_gt_val=="" && daycnt_nbr <= daycnt_lt_val)  || 
		     (daycnt_lt_val=="" && daycnt_nbr >= daycnt_gt_val)  ||
		     (daycnt_lt_val!="" && daycnt_gt_val!="" && daycnt_nbr <= daycnt_lt_val && daycnt_nbr >= daycnt_gt_val)
		    ) &&
                    (open_d_val=="" || open_d_str.indexOf(open_d_val)>=0) &&
		    (priority_val=="" || priority_str == priority_val) &&
		    (contact_val=="" || contact_str == contact_val)

		  )
			mygrid3.setRowHidden(mygrid3.getRowId(i),false)
		else
		  	mygrid3.setRowHidden(mygrid3.getRowId(i),true)
		  
	}
	xgrid_filter_box_init(document.getElementById('type_flt').childNodes[0],1);
	xgrid_filter_box_init(document.getElementById('appl_flt').childNodes[0],2);
        xgrid_filter_box_init(document.getElementById('package_flt').childNodes[0],3);
	xgrid_filter_box_init(document.getElementById('segment_flt').childNodes[0],4);
	xgrid_filter_box_init(document.getElementById('owner_flt').childNodes[0],8);
	xgrid_filter_box_init(document.getElementById('status_flt').childNodes[0],9);
	xgrid_filter_box_init(document.getElementById('priority_flt').childNodes[0],12);
	xgrid_filter_box_init(document.getElementById('contact_flt').childNodes[0],13);
}
	
//populate filter select box with possible column values
function xgrid_filter_box_init(selObj,col)
{
	old_val=selObj.value;
	selObj.options.length = 0;
	selObj.options.add(new Option("",""))
	var usedValues = new dhtmlxArray();
	for(var i=0;i<mygrid3.getRowsNum();i++)
	{
		var usedValue = mygrid3.cells2(i,col).getValue();
		var hidden_state = mygrid3.getRowById(mygrid3.getRowId(i)).style.display
		if(usedValues._dhx_find(usedValue)==-1 && hidden_state != 'none')
		{
			if(mygrid3.getCombo(col).get(usedValue) != null)
			{
				selObj.options.add(new Option(mygrid3.getCombo(col).get(usedValue),usedValue));
			}
			else
			{
				selObj.options.add(new Option(usedValue,usedValue));
			}
			usedValues[usedValues.length] = usedValue;
		}
	}
	selObj.value=old_val;
}

function xgrid_filter_create()
{
        mygrid3.attachHeader("#rspan,<div id='type_flt'></div>,<div id='appl_flt'></div>,<div id='package_flt'></div>,<div id='segment_flt'></div>,<div id='ext_code_flt'></div>,<div id='summary_flt'></div>,<div id='limit_d_flt'></div>,<div id='owner_flt'></div>,<div id='status_flt'></div>,<table><tr><td style='border:0px'><div id='daycnt_gt_flt'></div></td><td style='border:0px'><div id='daycnt_lt_flt'></div></td></tr></table>,<div id='open_d_flt'></div>,<div id='priority_flt'></div>,<div id='contact_flt'></div>,#rspan");
                        
        var typeFlt = document.getElementById('type_flt').appendChild(document.getElementById('type_flt_box').childNodes[0]);
        xgrid_filter_box_init(typeFlt,1);

        var applFlt = document.getElementById('appl_flt').appendChild(document.getElementById('appl_flt_box').childNodes[0]);
        xgrid_filter_box_init(applFlt,2);

        var packageFlt = document.getElementById('package_flt').appendChild(document.getElementById('package_flt_box').childNodes[0]);
        xgrid_filter_box_init(packageFlt,3);

        var segmentFlt = document.getElementById('segment_flt').appendChild(document.getElementById('segment_flt_box').childNodes[0]);
        xgrid_filter_box_init(segmentFlt,4);

	document.getElementById('ext_code_flt').appendChild(document.getElementById('ext_code_flt_box').childNodes[0]);

        document.getElementById('summary_flt').appendChild(document.getElementById('summary_flt_box').childNodes[0]);

	document.getElementById('limit_d_flt').appendChild(document.getElementById('limit_d_flt_box').childNodes[0]);

        var ownerFlt = document.getElementById('owner_flt').appendChild(document.getElementById('owner_flt_box').childNodes[0]);
        xgrid_filter_box_init(ownerFlt,8);

        var statusFlt = document.getElementById('status_flt').appendChild(document.getElementById('status_flt_box').childNodes[0]);
        xgrid_filter_box_init(statusFlt,9);

        document.getElementById('daycnt_gt_flt').appendChild(document.getElementById('daycnt_gt_flt_box').childNodes[0]);
        document.getElementById('daycnt_lt_flt').appendChild(document.getElementById('daycnt_lt_flt_box').childNodes[0]);

        document.getElementById('open_d_flt').appendChild(document.getElementById('open_d_flt_box').childNodes[0]);

        var priorityFlt = document.getElementById('priority_flt').appendChild(document.getElementById('priority_flt_box').childNodes[0]);
        xgrid_filter_box_init(priorityFlt,12);

        var contactFlt = document.getElementById('contact_flt').appendChild(document.getElementById('contact_flt_box').childNodes[0]);
        xgrid_filter_box_init(contactFlt,13);

        mygrid3.setSizes();
}

function xgrid_update_data(project_id, modl_option, stat_mailbox, stat_event_unassigned, stat_event_mine, stat_event_all, gsp_inbox_db_path, gsp_inbox_db_table)
{
        mygrid3.updateFromXML('index.php?MODL=GETX&OBJECT=event&STATE=open&MODL_OPTION=' + modl_option + '&PROJECT_ID=' + project_id,'top',true,xgrid_update_stat(3,stat_event_mine,stat_event_all));
        mygrid2.updateFromXML('index.php?MODL=GETX&OBJECT=event&STATE=open&ASSIGN=null&PROJECT_ID=' + project_id,'top',true,xgrid_update_stat(2,stat_event_unassigned,0));
        mygrid1.updateFromXML('index.php?MODL=GETX&OBJECT=mailbox&GSP_INBOX_DB_PATH=' + gsp_inbox_db_path + '&GSP_INBOX_DB_TABLE=' + gsp_inbox_db_table + '&TREATED=0&HIDDEN=0','top',true,xgrid_update_stat(1,stat_mailbox,0));
}

function xgrid_update_stat(grid,nbr1,nbr2)
{
       var expr_reg=/\(/g;
       if (grid == 3)
       {
               var begin_title=parent.win3.getText().split(expr_reg);
               win3.setText(begin_title[0] + ' ('+nbr1+ '/ ' +nbr2+')');
       }
       else if (grid == 2)
       {
               var begin_title=parent.win2.getText().split(expr_reg);
               win2.setText(begin_title[0] + ' ('+nbr1+')');
       }
       else if (grid == 1)
       {
               var begin_title=parent.win1.getText().split(expr_reg);
               win1.setText(begin_title[0] + ' ('+nbr1+')');
       }
}

