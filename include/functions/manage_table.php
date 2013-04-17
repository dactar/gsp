<?
function manage_table($arg,$destvar,$db,$query,$sort,$filter)
{
	$row = $db->prepare($query);
        $row->execute();
       	$Cols = $row->columnCount();
       	$countrows = 1;
	if ($sort == 1) {$sortable="sortable";}
	if ($filter == 1) {$filterable="filterable";}
       	print "<table class='T4 $sortable $filterable'>\n";
       	while ($result=$row->fetch(PDO::FETCH_ASSOC))
       	{
               	if ($countrows == 1)
               	{
                       	print "<thead><tr class=sortHeader>";
                       	print str_replace("selectFilter'>No<","'>No<",str_replace("selectFilter'>Desc","'>Desc",str_replace("selectFilter'>Jour","'>Jour",str_replace("selectFilter'>Contact","'>Contact",preg_replace('@id</td>@',"",join("</td><td class='TH2 selectFilter'>", array_keys($result)),1)))));
                       	print "</td></tr></thead><tbody id='TB4'>\n";
               	}
               	$countrows++;
		if ($result[Urgence] == "")
		{
			if ($countrows % 2 == 0) {$style='TD2A';} else {$style='TD2B';}
		}
		else
		{
			$rank=preg_replace('@.*<pri_rank>@si','',str_replace('</pri_rank>','',$result[Urgence]));
			$style='TD2R' . $rank;
			$result[Urgence]=preg_replace('@<pri_rank>.*@si','',$result[Urgence]);
		}
		print "<tr onDblClick=\"location.href='index.php?$arg&$destvar=$result[id]'\" ONMOUSEOVER=\"this.style.cursor='hand'\">";
		print stripslashes(preg_replace('@[0-9]*</td>@',"",str_replace("></td",">&nbsp;</td",join("</td><td class='$style'>", array_values($result))),1));
               	print "</td></tr>\n";
       	}
	print "</tbody></table>";
}
?>
