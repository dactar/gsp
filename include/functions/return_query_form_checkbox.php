<?
function return_query_form_checkbox($db,$current,$var,$query)
{
	$row = $db->prepare($query);
        $row->execute();
       	$Cols = $row->columnCount();
       	$countrows = 0;
       	while ($result=$row->fetch(PDO::FETCH_NUM))
       	{
               	$countrows++;
		$checked="";
		if ($current == $result[0] || $current == 'all')
		{
			$checked="checked";
		}
		echo "<input type='checkbox' name='$var" . "[" . $countrows . "]' value=$result[0] $checked>$result[1]</input><br/>";
       	}
}
?>
