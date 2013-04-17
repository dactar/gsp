<?
	$web_page->render();
?>
<center><table cellpadding=3 cellspacing=0>
<tr>
	<td class=TD2>
		<table bgcolor="#FFFFFF">
			<tr><td align=center><img src="pict/logo.png" alt="GSP"/></td></tr>
			<tr><td align=center><img src="pict/logo_text.png" alt="Global Support Platform"/></td></tr>
		</table>
	</td>
</tr>
<tr>
	<td class=TD2A align=center>
		<table>
			<tr><td align=right><tt>Site :</tt></td><td align=left><tt><a href="http://gsp.dactar.ch">http://gsp.dactar.ch</a></tt></td></tr>
			<tr><td align=right><tt>Version :</tt></td><td align=left><tt><? echo $GSP_VERSION; ?></tt></td></tr>
			<tr><td align=right><tt>Utilisateur :</tt></td><td align=left><tt><? echo $USER; ?></tt></td></tr>
		</table>
	<td>
</tr>
<tr>
	<td class=TD2 align=center>
		<br/>
		<form>
			<input type="button" value="Fermer" onclick="parent.dhxWins.window('win_module_<? echo $_REQUEST[MODL]?>').close();"></input>
		</form>
	</td>
</tr>
</table></center>
