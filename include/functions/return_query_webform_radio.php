<?
function return_query_webform_radio($db,$inputname,$current,$query)
{
	echo "<script type='text/javascript'>\nfunction webform_load_radio_static_$inputname()\n{\n";
	$row = $db->prepare($query);
        $row->execute();
       	$Cols = $row->columnCount();
       	$countrows = 1;
       	while ($result=$row->fetch(PDO::FETCH_NUM))
       	{
               	$countrows++;
		$selected="";
		if ($current == $result[0])
		{
			$selected="selected";
		}
		echo "form.setItemLabel(\"$inputname\", $result[0],\"" . stripslashes($result[1]) . "\");\n";	
       	}
	echo "}\n</script>";
}
?>
