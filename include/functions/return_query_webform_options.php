<?
function return_query_webform_options($db,$inputname,$current,$query,$return=FALSE)
{
	$output="<script type='text/javascript'>\nfunction webform_load_optlist_static_$inputname()\n{\n";
	$output.="var opts=form.getOptions('$inputname');";
	$output.="opts.length = 0;";
	$output.="\nopts.add(new Option('',0))";
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
		$output.="\nopts.add(new Option(\"" . stripslashes($result[1]) . "\",$result[0]));";
       	}
	$output.="\n}\n</script>";
	if ($return)
	{
		return $output;
	}
	else
	{
		echo $output;
	}
}
?>
