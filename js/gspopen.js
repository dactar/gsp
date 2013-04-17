function open_event(id)
{
   if (dhxWins.isWindow('win_event_id_'+id))
   {
	dhtmlx.message.defPosition='bottom';
   	if (toolbar.getType(id) == null)
   	{
		dhtmlx.message({type:'notif_error',text:'Ouverture #'+ id + ' impossible : DEJA OUVERT'});
   	}
	else
	{
		dhtmlx.message({type:'notif_error',text:'Ouverture #'+ id + ' impossible : DEJA OUVERT DANS LE CONTAINER'});
	}
   }
   else
   {
   	var win_event=dhxWins.createWindow('win_event_id_'+id,150,40,1000,660); 
   	win_event.addUserButton('dock', 10, 'Dock Window', 'dock');
   	win_event.setText('#' + id);
   	win_event.stick();
   	win_event.button('sticked').hide();
   	win_event.button('park').hide();

   	win_event.attachEvent('onClose', function()
	{
	    toolbar.removeItem(id);
	    if (mygrid3.doesRowExist(id))
	    {
		mygrid3.lockRow(id,false);
	    }
	    return true;
	});
   	win_event.button('dock').attachEvent('onClick', function()
   	{
            toolbar.addButtonTwoState(id, 1,'#'+id,'','');
	    if (mygrid3.doesRowExist(id))
	    {
		toolbar.setItemToolTip(id, mygrid3.cells(id,5).getValue());
		toolbar.setItemImage(id, '../../../pict/box3.png');
	    }
	    else
	    {
		if (mygrid2.doesRowExist(id))
		{
			toolbar.setItemToolTip(id, mygrid2.cells(id,5).getValue());
			toolbar.setItemImage(id, '../../../pict/box2.png');
		}
		else
		{
			toolbar.setItemImage(id, '../../../pict/box3.png');
		}
	    }
	    if (toolbar.count == 0)
            {
		tabbar.setCustomStyle('a2', 'black', 'black', 'font-weight:normal');
                tabbar.enableTab('a2'); 
	    }
	    toolbar.count++;
            win_event.hide()
	    tabbar.setLabel('a2','Container ('+toolbar.count+')');
	    tabbar.setTabActive('a2');
	});
	if (mygrid3.doesRowExist(id))
	{
		mygrid3.lockRow(id,true);	
	}
   	win_event.attachURL('index.php?BOX=1&MODL=OPNE&EVENT_ID=' + id);
   }
}

function open_mail(id)
{
   if (dhxWins.isWindow('win_mail_id_'+id))
   {
        dhtmlx.message.defPosition='top';
        if (toolbar.getType(id) == null)
        {
                dhtmlx.message({type:'notif_error',text:'Ouverture mail '+ id + ' impossible : DEJA OUVERT'});
        }
        else
        {
                dhtmlx.message({type:'notif_error',text:'Ouverture mail '+ id + ' impossible : DEJA OUVERT DANS LE CONTAINER'});
        }
   }
   else
   {
   	var win_mail=dhxWins.createWindow('win_mail_id_'+id,220,20,800,680);
   	win_mail.setText('mail');
   	win_mail.stick();
   	win_mail.button('sticked').hide();
   	win_mail.addUserButton('dock', 10, 'Dock Window', 'dock');
   	win_mail.button('park').hide();
   	win_mail.attachEvent('onClose', function()
	{
	    toolbar.removeItem(id);
	    return true;
	});
   	win_mail.button('dock').attachEvent('onClick', function()
   	{
            toolbar.addButtonTwoState(id, 1,'mail','','');
	    toolbar.setItemToolTip(id, mygrid1.cells(id,3).getValue());
	    toolbar.setItemImage(id, '../../../pict/box1.png');
	    if (toolbar.count == 0)
            {
		tabbar.setCustomStyle('a2', 'black', 'black', 'font-weight:normal');
                tabbar.enableTab('a2');
	    }
	    toolbar.count++;
            win_mail.hide()
	    tabbar.setLabel('a2','Container ('+toolbar.count+')');
	    tabbar.setTabActive('a2');
	});
	win_mail.attachURL('index.php?BOX=3&MODL=OPNM&MAIL_ID=' + id);
   }
}
