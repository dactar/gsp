<?
function xml_grid_table($db,$query)
{
        $row = $db->prepare($query);
        $row->execute();
        $Cols = $row->columnCount();
        $countrows = 1;
        print "<rows>\n";

        while ($result=$row->fetch(PDO::FETCH_ASSOC))
        {
                $result=str_replace("&","&amp;",$result);

                if ($countrows == 1)
                {
                        $countcols = -1;
                        foreach(array_keys($result) as $key)
                        {
                                print "<userdata name=\"sql_column_" . $countcols . "\">";
                                echo $key;
                                print "</userdata>";
                                $countcols++;
                        }
                        print "<head>";
if($_REQUEST[OBJECT] == "mailbox")
{
print '
<column width="80" type="link" align="center" sort="str">Date</column>
<column width="50" type="ro" align="center" sort="str">Heure</column>
<column width="400" type="ro" align="left" sort="str">Emetteur</column>
<column width="625" type="ro" align="left" sort="str">Sujet</column>
<column width="80" type="ro" align="center" sort="int">Taille</column>';
}
else
{
print '
<column width="50" type="link" align="left" sort="str">No</column>
<column width="50" type="coro" align="left" sort="str">Type</column>
<column width="75" type="ro" align="left" sort="str">Appl</column>
<column width="70" type="ro" align="center" sort="str">Version</column>
<column width="95" type="ro" align="center" sort="str">Objet</column>
<column width="85" type="ro" align="center" sort="str">Code externe</column>
<column width="325" type="ed" align="left" sort="str">Description</column>
<column width="70" type="ro" align="center" sort="str">Date limite</column>
<column width="50" type="ro" align="center" sort="str">Suivi</column>
<column width="75" type="coro" align="left" sort="str">Statut</column>
<column width="52" type="ro" align="center" sort="int">Jours</column>
<column width="75" type="ro" align="center" sort="str">Ouvert le</column>
<column width="70" type="coro" align="left" sort="str">Urgence</column>
<column width="50" type="ro" align="left" sort="str">Contact</column>
<column width="45" type="ro" align="center" sort="str">Update</column>';
}
                        //print "<column>";
                        //print stripslashes(join(array_keys($result),"</column>\n<column>"));
                        //print "</column>";
                        print "</head>\n";
                        $header=array_keys($result);
                }
                $countrows++;

		if ($result[priority_dict_id] != "")
		{
                	$rank=preg_replace('@.*<pri_rank>@si','',str_replace('</pri_rank>','',$result[priority_dict_id]));
                	$style='TD2R' . $rank;
                	$result[priority_dict_id]=preg_replace('@<pri_rank>.*@si','',$result[priority_dict_id]);
		}
		if ($result[id] != "empty")
		{
                	print "<row class=\"$style\" id=\"";
                	print stripslashes(preg_replace("<</cell>>","\">",join(array_values($result),"</cell><cell>"),1));
                	print "</cell></row>\n";
		}
        }
        print "</rows>";
}
?>
