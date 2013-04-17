<?
function return_query_grid_options($db,$grid,$col,$query)
{
	$row = $db->prepare($query);
        $row->execute();
       	$Cols = $row->columnCount();
       	$countrows = 1;
       	while ($result=$row->fetch(PDO::FETCH_NUM))
       	{
               	$countrows++;
		echo "\n$grid.getCombo($col).put('$result[0]','$result[1]');";
       	}
}
?>
