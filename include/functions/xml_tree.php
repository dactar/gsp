<?
function xml_tree($db,$table,$colid,$id,$cond,$nullcond,$space,$width,$subaction,$subtable)
{
        ($id=="NULL" ? $whereid=$nullcond : $whereid="= $id");

	$parent_colid="parent_" . $colid;
        $query="SELECT * from $table WHERE $parent_colid $whereid and $cond";
        $row = $db->query($query);
        $width = $width - 19;
        while ($result=$row->fetch(PDO::FETCH_ASSOC))
        {
                $query="SELECT count(*) from $table WHERE $parent_colid = $result[$colid] and $cond";
                $cnt = $db->query($query);
                $error=$db->errorInfo();
                $count=$cnt->fetch();
		if ($table == "event_vw")
		{
			require_once("include/functions/return_query.php");
			$TRRANK=return_query($db,"SELECT rank_n from dict WHERE dict_id = $result[priority_dict_id]");	
			$codewidth=(100 + $width);
			$rowtable="<div style='width:" . $codewidth . "px; float: left; text-decoration:underline;'>$result[code]</div><div style='width:700px; float: left; margin-left: 20px; '>$result[summary]</div><div style='width:300px; float: left; margin-left: 20px; '>$result[priority_code]</div>";
			$rowtable=str_replace('"','&quot;',$rowtable);
			$rowtable=str_replace('&','&amp;',$rowtable);
			$rowtable=str_replace('<','&lt;',$rowtable);
			$rowtable=stripslashes(str_replace('>','&gt;',$rowtable));
			print $space . "        <item id=\"$result[$colid]\" text=\"$rowtable\">";
		}
		if ($table == "dict_vw")
		{
                        $result=str_replace("<","~##lt;",$result);
                        $result=str_replace(">","~##gt;",$result);
                        $result=str_replace("&","&amp;",$result);
                        $result=str_replace("~##","&",$result);

			print $space . "        <item id=\"$result[$colid]\" text=\"$result[code]\" tooltip=\"$result[description]\">\n";
			if ( $result[$parent_colid] != 20 )
			print $space . "                <userdata name=\"parent_$colid\">$result[$parent_colid]</userdata>\n";
			print $space . "                <userdata name=\"code\">$result[code]</userdata>\n";
			print $space . "                <userdata name=\"parent_code\">$result[parent_code]</userdata>\n";
			print $space . "                <userdata name=\"last_user_code\">$result[last_user_code]</userdata>\n";
			print $space . "                <userdata name=\"last_modif_d\">$result[last_modif_d]</userdata>\n";
			print $space . "                <userdata name=\"parent_f\">$result[parent_f]</userdata>\n";
			print $space . "                <userdata name=\"mandatory_f\">$result[mandatory_f]</userdata>\n";
			print $space . "                <userdata name=\"active_f\">$result[active_f]</userdata>\n";
			print $space . "                <userdata name=\"rank_n\">$result[rank_n]</userdata>\n";
			print $space . "                <userdata name=\"description\">$result[description]</userdata>\n";
		}
                if ($count[0] == 0)
                {
			if ($subaction == 1)
			{
				xml_subtree($db,$subtable,$result[dict_id],"");
			}
			print $space . "        </item>\n";
                }
                else
                {
			print "\n";
			$end=$space . "        </item>\n";
                       	$oldspace=$space;
                        $space=$space . "        ";
                        xml_tree($db,$table,$colid,$result[$colid],$cond,$nullcond,$space,$width,$subaction,$subtable);
                        $space = $oldspace;
                        print $end;
                }
        }
}
?>
