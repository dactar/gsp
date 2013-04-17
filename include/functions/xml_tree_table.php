<?
function xml_tree_table($db,$table,$colid,$id,$cond,$nullcond,$space,$width,$subaction)
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
                        $jsaction="javascript:fill_form($result[$colid],\"$result[code]\")";
                        $jsaction=str_replace("\"","'",str_replace("'","\\\'",$jsaction));
                        $result[summary]=str_replace("\"","'",str_replace("'","\\\'",$result[summary]));
                        $result[summary]=trimdot(75,$result[summary]);
                        $TRRANK=return_query($db,"SELECT rank_n from dict WHERE dict_id = $result[priority_dict_id]");
                        $codewidth=(57 + $width);
                        print $space . "        [null, \"<table cellpadding=0 cellspacing=1><tr class='TRLEVEL" . $TRRANK . "'><td class=TD2A align=right width=$codewidth>$result[code]</td><td class=TD2A width=400>$result[summary]</td><td>$result[priority_dict_id]</td></tr></table>\",\"$jsaction\",null, \"$result[description]\"";
                }
                if ($table == "dict")
                {
                        //$jsaction="javascript:fill_form(\"$result[$colid]\",\"$result[$parent_colid]\",\"$result[code]\",\"$result[description]\",\"$result[parent_code]\",\"$result[last_user_code] / $result[last_modif_d]\",\"$result[parent_f]\",\"$result[mandatory_f]\",\"$result[active_f]\",\"$result[rank_n]\")";
                        //$jsaction=str_replace("\"","'",str_replace("'","\\\'",$jsaction));
                        //print $space . "        [null, \"$result[code]\",\"$jsaction\",null, \"$result[description]\"";
                        print $space . "        <item child=\"1\" id=\"$result[$colid]\" text=\"$result[code]\" tooltip=\"$result[description]\">\n";
                        print $space . "                <userdata name=\"parent_colid\">$result[parent_colid]</userdata>\n";
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
                if ($table == "package" || $table == "segment")
                {
                        $jsaction="javascript:fill_form(\"$result[$colid]\",\"$result[description]\")";
                        $jsaction=str_replace("\"","'",str_replace("'","\\\'",$jsaction));
                        print $space . "        [null, \"$result[code]\",\"$jsaction\",null, \"$result[description]\"";
                }
                if ($count[0] == 0)
                {
                        if ($subaction == 1)
                        {
                                treesubaction($db,$result[dict_id],"");
                        }
                        if ($table != "dict")
                        {
                                print "],\n";
                        }
                        else
                        {
                                print $space . "        </item>\n";
                        }
                }
                else
                {
                        if ($table != "dict")
                        {
                                print ",\n";
                                $end=$space . "        ],\n";
                        }
                        else
                        {
                                print "\n";
                                $end=$space . "        </item>\n";
                        }
                        $oldspace=$space;
                        $space=$space . "        ";
                        //tree($db,$table,$colid,$result[$colid],$cond,$nullcond,$space,$width,$subaction);
                        $space = $oldspace;
                        print $end;
                }
        }
}
?>
