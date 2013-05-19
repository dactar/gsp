<?
function return_table($db,$query)
{
	$row = $db->prepare($query);
        $row->execute();
       	$Cols = $row->columnCount();
       	$countrows = 1;
       	$output="<br/>\n<table class='T2'>\n";
       	while ($result=$row->fetch(PDO::FETCH_ASSOC))
       	{
		if ( $_GET[EVENT_ID] == "" &&  $_GET[MAIL_ID] == "" )
		{
			$result=str_replace("<","~##lt;",$result);
			$result=str_replace(">","~##gt;",$result);
			$result=str_replace("&","&amp;",$result);
			$result=str_replace("~##","&",$result);
		}
               	if ($countrows == 1)
               	{
                       	$output.= "<tr><td class='TD2'><b>";
                       	$output.= join("</b></td><td class='TD2'><b>", array_keys($result));
                       	$output.= "</b></td></tr>\n";
               	}
               	$countrows++;
		if ($countrows % 2 == 0) {$style='TD2A';} else {$style='TD2B';}
		$output.= "<tr><td class='$style'>";
               	$output.= stripslashes(str_replace("></td",">&nbsp;</td",join("</td><td class='$style'>", array_values($result))));
               	$output.= "</td></tr>\n";
       	}
	$output.= "</table>";
	return $output;
}
?>
