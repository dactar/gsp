<?
echo "
<div id='gridbox1' width='100%' height='100%' style='background-color:white;overflow:hidden;'></div>
<script>
                mygrid1 = new dhtmlXGridObject('gridbox1');
                mygrid1.setImagePath('pict/');
		mygrid1.enableMultiline(false);
                mygrid1.enableMultiselect(false);
		mygrid1.enableAlterCss('TD2A','TD2B')
		mygrid1.setStyle('color:black','color:black','color:black','background-color:#FAFAD2');

                mygrid1.init();

                mygrid1.loadXML('index.php?MODL=GETX&OBJECT=mailbox&GSP_INBOX_DB_PATH=$GSP_INBOX_DB_PATH&GSP_INBOX_DB_TABLE=$GSP_INBOX_DB_TABLE&TREATED=$TREATED&HIDDEN=$HIDDEN');
</script>
";
