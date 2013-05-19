<?
if (isset($web_page))
{
        $web_page->render();
}

$segment = new segment();

?>

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
	<div id="segment_treeID"></div>
        <script>
                        tree=new dhtmlXTreeObject("segment_treeID","100%","100%",0);
                        tree.setImagePath("ext/dhtmlx/imgs/");
                        tree.setOnClickHandler(go_fill);
                        tree.loadXML("index.php?MODL=GETX&TYPE=tree&OBJECT=segment");
        </script>
	</td>
	<td valign="top">
<?
        $web_form = new web_form("segment","all");
	$web_form->set_list_options("type_dict_id");
        $web_form->display();
?>
	</td>
</tr>
<tr>
	<td colspan="3">
<?
if ($_REQUEST[AFFICHE] != "" ) display_table($db,"SELECT * from segment_vw;");
?>
	</td>
</tr>
</table>
