<div id='projectObj' style='background-color: #ebebeb; margin: 0px;'>
<table align='center'>
<tr><td>
<form action = 'index.php?MODL_OPTION=<?echo $_REQUEST[MODL_OPTION]?>' method='post'>
<select class='SL1' name="PROJECT_ID" size="1" onchange="javascript:submit()">
<option></option>
echo $_REQUEST[PROJECT_ID];
<option id='< Aucun >' value='none'<? if ($_REQUEST[PROJECT_ID] == 'none'){echo 'selected';}?>>< Aucun ></option>
<?echo return_query_form_options($db, $_REQUEST[PROJECT_ID], "select id, summary from event_vw where type_code = 'PR' and state='open' order by id");?>
</select>
</form>
</td></tr>
</table>
<br><br>
</div>
