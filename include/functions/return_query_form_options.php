<?
function return_query_form_options($db,$current,$query)
{
	$row = $db->prepare($query);
        $row->execute();
       	$Cols = $row->columnCount();
       	$countrows = 1;
	$options="";
       	while ($result=$row->fetch(PDO::FETCH_NUM))
       	{
               	$countrows++;
		$selected="";
		if ($current == $result[0])
		{
			$selected="selected";
		}
		$options .= "<option id='" . stripslashes($result[1]) ."' value=$result[0] $selected>" . stripslashes($result[1]) . "</option>\n";
       	}
	return $options;
}
?>
