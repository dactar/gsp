<?
        $web_page->render();
?>
<form action = "" method="post">
<input type="hidden" name="MODL" value='EVNT'></input>
<input type="submit" name="AFFICHE" value='Afficher la table'></input>
<input type="submit" name="EXPORT_PDF" value='Exporter ouverts en PDF'></input>
</form>
<br>
<b>Module de Recherche</b>
<br><br>
<form action = "" method="post">
<input type="hidden" name="MODL" value='EVNT'></input>
Rechercher : <input type="text" size=20 name="STRING"></input>
<br><br>
dans &eacute;v&eacute;nements : 
<input type="radio" name="STATE" value="open" checked=checked></input>
Ouverts
<input type="radio" name="STATE" value="closed"></input>
Ferm&eacute;s
<br><br>

<input type="submit" name="SEARCH" value='Rechercher'></input>
</form>
<?
if ($_POST[AFFICHE] != "" ) display_table($db,"SELECT * from event_vw;");
if ($_POST[SEARCH] != "" ) 
{
	display_table_href($db,"SELECT distinct '<a href=\"javascript:parent.open_event(' || e.id || ')\">' || e.code || '</a>' as No, e.type_code as Type, e.appl_code as Appl, e.package_code as Version, e.segment_code as Objet, e.summary as Description, e.owner as Suivi, e.status_code as Statut, strftime('%Y.%m.%d',e.opened_d) as 'Ouvert le', e.priority_code as Urgence, e.contact as Contact from event_vw e left join event_history eh on e.id = eh.event_id left join event ee on e.id = ee.id where e.state='$_REQUEST[STATE]' and (e.code like :PARAM or e.summary like :PARAM or ee.remark like :PARAM or eh.description like :PARAM) order by e.id desc", "%".$_REQUEST[STRING]."%");
}
?>
