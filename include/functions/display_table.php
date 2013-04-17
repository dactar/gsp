<?
function display_table($db,$query)
{
	$row = $db->prepare($query);
        $row->execute();
       	$Cols = $row->columnCount();
       	$countrows = 1;
       	print "<br/>\n<table class='T2'>\n";
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
                       	print "<tr><td class='TD2'><b>";
                       	print join("</b></td><td class='TD2'><b>", array_keys($result));
                       	print "</b></td></tr>\n";
               	}
               	$countrows++;
		if ($countrows % 2 == 0) {$style='TD2A';} else {$style='TD2B';}
		print "<tr><td class='$style'>";
               	print stripslashes(str_replace("></td",">&nbsp;</td",join("</td><td class='$style'>", array_values($result))));
               	print "</td></tr>\n";
       	}
	print "</table>";
}
?>
