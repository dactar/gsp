<?
function return_query($db,$query)
{
	$row = $db->prepare($query);
        $row->execute();
	$result = $row->fetchAll(PDO::FETCH_COLUMN);
	return stripslashes($result[0]);
}
?>
