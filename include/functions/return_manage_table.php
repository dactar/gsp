<?
function return_manage_table($arg,$destvar,$db,$query,$sort,$filter)
{
	$output="";
	$row = $db->prepare($query);
        $row->execute();
       	$Cols = $row->columnCount();
       	$countrows = 1;
	if ($sort == 1) {$sortable="sortable";}
	if ($filter == 1) {$filterable="filterable";}
       	$output.="<table class='T4 $sortable $filterable'>\n";
       	while ($result=$row->fetch(PDO::FETCH_ASSOC))
       	{
               	if ($countrows == 1)
               	{
                       	$output.="<thead><tr class=sortHeader>";
                       	$output.=str_replace("selectFilter'>No<","'>No<",str_replace("selectFilter'>Desc","'>Desc",str_replace("selectFilter'>Jour","'>Jour",str_replace("selectFilter'>Contact","'>Contact",preg_replace('@id</td>@',"",join("</td><td class='TH2 selectFilter'>", array_keys($result)),1)))));
                       	$output.="</td></tr></thead><tbody id='TB4'>\n";
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
		$output.="<tr onDblClick=\"location.href='index.php?$arg&$destvar=$result[id]$result[dict_id]'\" ONMOUSEOVER=\"this.style.cursor='hand'\">";
		$output.=stripslashes(preg_replace('@[0-9]*</td>@',"",str_replace("></td",">&nbsp;</td",join("</td><td class='$style'>", array_values($result))),1));
               	$output.="</td></tr>\n";
       	}
	$output.="</tbody></table>";
	return $output;
}
?>
