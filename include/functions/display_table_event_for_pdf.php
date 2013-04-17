<?
function display_table_event_for_pdf($db,$query)
{
	$row = $db->prepare($query);
        $row->execute();
       	$Cols = $row->columnCount();
       	$countrows = 1;
       	print "<table>\n";
       	while ($result=$row->fetch(PDO::FETCH_ASSOC))
       	{
               	$countrows++;
		$rank=preg_replace('@.*<pri_rank>@si','',str_replace('</pri_rank>','',$result[Urgence]));
		$style='TD2R' . $rank;
		$result[Urgence]=preg_replace('@<pri_rank>.*@si','',$result[Urgence]);
		print "<tr><td><table class='T1'>";
		print "<tr valign=top>";
		print "<td class='$style' width=50>$result[No]</td>";
		print stripslashes("<td class='$style' width=580>$result[Description]</td>");
		print "<td class='$style' width=30>$result[Suivi]</td>";
		print "<td class='$style' width=60>$result[Statut]</td>";
		print "<td class='$style' width=40>$result[Jours]</td>";
		print "<td class='$style' width=70>$result[Ouverture]</td>";
		print "<td class='$style' width=50>$result[Urgence]</td>";
		print "<td class='$style' width=50>$result[Contact]</td>";
       	        print "</tr><tr><td></td><td height=30 colspan=8></td></tr>\n";
		print "</table></td></tr>";	
       	}
	print "</table>";
}
?>
