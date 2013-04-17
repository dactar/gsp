<?
function return_query_webform_options($db,$inputname,$current,$query)
{
	echo "<script type='text/javascript'>\nfunction webform_load_optlist_static_$inputname()\n{\n";
	echo "var opts=form.getOptions('$inputname');";
	echo "opts.length = 0;";
	echo "\nopts.add(new Option('',0))";
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
		echo "\nopts.add(new Option(\"" . stripslashes($result[1]) . "\",$result[0]));";
       	}
	echo "\n}\n</script>";
}
?>
