<?
function xml_subtree($db,$table,$dict_id,$parent_id)
{
	if ($table == "package")
	{
		$appl_dict_id = $dict_id;

		($parent_id=="" ? $whereid="and parent_id isnull" : $whereid="and parent_id = $parent_id");
		$query="SELECT * from package_vw where appl_dict_id = $appl_dict_id $whereid order by rank_n;";
        	$subrow = $db->query($query);
        	while ($subresult=$subrow->fetch(PDO::FETCH_ASSOC))
        	{
                        $subresult=str_replace("<","~##lt;",$subresult);
                        $subresult=str_replace(">","~##gt;",$subresult);
                        $subresult=str_replace("&","&amp;",$subresult);
                        $subresult=str_replace("~##","&",$subresult);

			print "<item id=\"sub$subresult[id]\" text=\"" . stripslashes($subresult[code]) . "\" tooltip=\"$subresult[description]\">\n";
			print "<userdata name=\"parent_colid\">$appl_dict_id</userdata>\n";
			print "<userdata name=\"code\">$subresult[code]</userdata>\n";
			print "<userdata name=\"description\">$subresult[description]</userdata>\n";
			print "<userdata name=\"appl_dict_id\">$subresult[appl_dict_id]</userdata>\n";
			print "<userdata name=\"appl_code\">$subresult[appl_code]</userdata>\n";
			print "<userdata name=\"parent_id\">$subresult[parent_id]</userdata>\n";
			print "<userdata name=\"parent_code\">$subresult[parent_code]</userdata>\n";
			print "<userdata name=\"prod_f\">$subresult[prod_f]</userdata>\n";
			print "<userdata name=\"rank_n\">$subresult[rank_n]</userdata>\n";
			print "<userdata name=\"planif_d\">$subresult[planif_d]</userdata>\n";
			print "<userdata name=\"type_dict_id\">$subresult[type_dict_id]</userdata>\n";
			print "<userdata name=\"type_code\">$subresult[type_code]</userdata>\n";
			print "<userdata name=\"last_user_code\">$subresult[last_user_code]</userdata>\n";
			print "<userdata name=\"last_modif_d\">$subresult[last_modif_d]</userdata>\n";
			xml_subtree($db,$table,$appl_dict_id,$subresult[id]);
                	$space = $oldspace;
			print $space . "        </item>\n";
        	}	
	}

	if ($table == "segment")
	{
		$appl_dict_id = $dict_id;

		($parent_id=="" ? $whereid="and parent_id isnull" : $whereid="and parent_id = $parent_id");
		$query="SELECT * from segment_vw where appl_dict_id = $appl_dict_id $whereid order by rank_n;";
		$subrow = $db->query($query);
		while ($subresult=$subrow->fetch(PDO::FETCH_ASSOC))
		{
                        $subresult=str_replace("<","~##lt;",$subresult);
                        $subresult=str_replace(">","~##gt;",$subresult);
                        $subresult=str_replace("&","&amp;",$subresult);
                        $subresult=str_replace("~##","&",$subresult);

			print "<item id=\"sub$subresult[id]\" text=\"" . stripslashes($subresult[code]) . "\" tooltip=\"$subresult[description]\">\n";
			print "<userdata name=\"parent_colid\">$appl_dict_id</userdata>\n";
			print "<userdata name=\"code\">$subresult[code]</userdata>\n";
			print "<userdata name=\"description\">$subresult[description]</userdata>\n";
			print "<userdata name=\"appl_dict_id\">$subresult[appl_dict_id]</userdata>\n";
			print "<userdata name=\"appl_code\">$subresult[appl_code]</userdata>\n";
			print "<userdata name=\"parent_id\">$subresult[parent_id]</userdata>\n";
			print "<userdata name=\"parent_code\">$subresult[parent_code]</userdata>\n";
			print "<userdata name=\"prod_f\">$subresult[prod_f]</userdata>\n";
			print "<userdata name=\"rank_n\">$subresult[rank_n]</userdata>\n";
			print "<userdata name=\"supported_f\">$subresult[supported_f]</userdata>\n";
			print "<userdata name=\"default_f\">$subresult[default_f]</userdata>\n";
			print "<userdata name=\"type_dict_id\">$subresult[type_dict_id]</userdata>\n";
			print "<userdata name=\"type_code\">$subresult[type_code]</userdata>\n";
			print "<userdata name=\"last_user_code\">$subresult[last_user_code]</userdata>\n";
			print "<userdata name=\"last_modif_d\">$subresult[last_modif_d]</userdata>\n";
			xml_subtree($db,$table,$appl_dict_id,$subresult[id]);
			$space = $oldspace;
			print $space . "        </item>\n";
		}	
	}

	if ($table == "contact")
	{
		$group_dict_id = $dict_id;

		$query="SELECT * from contact_vw where group_dict_id = $group_dict_id and active_f = 1 order by name;";
		$subrow = $db->query($query);
		while ($subresult=$subrow->fetch(PDO::FETCH_ASSOC))
		{
                        $subresult=str_replace("<","~##lt;",$subresult);
                        $subresult=str_replace(">","~##gt;",$subresult);
                        $subresult=str_replace("&","&amp;",$subresult);
                        $subresult=str_replace("~##","&",$subresult);

                        print "<item id=\"sub$subresult[id]\" text=\"" . stripslashes($subresult[name]) . "\" tooltip=\"$subresult[code]\">\n";
			print "<userdata name=\"parent_colid\">$group_dict_id</userdata>\n";
                        print "<userdata name=\"code\">$subresult[code]</userdata>\n";
                        print "<userdata name=\"name\">$subresult[name]</userdata>\n";
			print "<userdata name=\"language_dict_id\">$subresult[language_dict_id]</userdata>\n";
			print "<userdata name=\"group_dict_id\">$subresult[group_dict_id]</userdata>\n";
                        print "<userdata name=\"group_code\">$subresult[group_code]</userdata>\n";
                        print "<userdata name=\"organisation_code\">$subresult[organisation_code]</userdata>\n";
                        print "<userdata name=\"active_f\">$subresult[active_f]</userdata>\n";
                        print "<userdata name=\"phone\">$subresult[phone]</userdata>\n";
			print "<userdata name=\"mobile\">$subresult[mobile]</userdata>\n";
                        print "<userdata name=\"email\">$subresult[email]</userdata>\n";
                        print "<userdata name=\"url\">$subresult[url]</userdata>\n";
                        print "<userdata name=\"last_user_code\">$subresult[last_user_code]</userdata>\n";
                        print "<userdata name=\"last_modif_d\">$subresult[last_modif_d]</userdata>\n";
			print "</item>\n";
		}	
	}
}
?>
