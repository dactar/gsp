function menu_call_module(MODL,MODL_OPTION,WIN_WIDTH,WIN_HEIGHT,WIN_TITLE,WIN_MAXIMIZE)
{
        if(WIN_WIDTH!=null)
        {
                if (WIN_WIDTH==0)
                {
                        WIN_WIDTH=win_width;
                        WIN_HEIGHT=win_height;
                }
		if (dhxWins.window('win_module_'+MODL))
		{
			dhxWins.window('win_module_'+MODL).bringToTop();
		}
		else
                {
			var win_module=dhxWins.createWindow('win_module_'+MODL,(win_width-WIN_WIDTH)/2,(win_height-WIN_HEIGHT)/2,WIN_WIDTH,WIN_HEIGHT);
                	win_module.setText(WIN_TITLE);
                	win_module.stick();
                	win_module.button('sticked').hide();
                	win_module.attachURL('index.php?MODL='+MODL+'&MODL_OPTION='+MODL_OPTION);
                	if (WIN_MAXIMIZE==1)
                	{
                        	win_module.maximize();
                	}
			win_module.attachEvent('onClose', function close(){if (dhxWins.window('win_tree')) {dhxWins.window('win_tree').close()};return true});
		}
        }
        else
        {
		document.gofrommenu.MODL.value = MODL;
		document.gofrommenu.MODL_OPTION.value = MODL_OPTION;
		document.gofrommenu.submit();
        }
}
