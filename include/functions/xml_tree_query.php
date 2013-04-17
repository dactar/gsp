<?
function xml_tree_query($db,$query)
{
	$row = $db->prepare($query);
        $row->execute();
        while ($result=$row->fetch(PDO::FETCH_NUM))
        {
		print "<item id=\"$result[0]\" text=\"" . stripslashes($result[1]) . "\" tooltip=\"" . stripslashes($result[2]) . "\"><userdata name=\"name\">" . stripslashes($result[1]) . "</userdata></item>\n";
        }
}
?>
