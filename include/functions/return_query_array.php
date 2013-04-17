<?
function return_query_array($db,$query,$subarray=true)
{
        $array=array();
	$row = $db->prepare($query);
        $row->execute();
       	while ($result=$row->fetch(PDO::FETCH_NUM))
       	{
		if ($subarray)
		{
			$array[]=$result;
		}
		else
		{
			$array["$result[0]"]=$result[1];
		}
       	}
	return $array;
}
?>
