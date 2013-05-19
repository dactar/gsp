<?
function return_query_dyn_opt_list($form,$inputname,$db,$table,$criteria,$value,$text,$return=FALSE)
{
	$query="select distinct $criteria from $table";
	$output="
<script language='javascript'>
function dynoptlist_$form" . "_$inputname(CRITERIA_ID)
{
    	var selbox = document.$form.$inputname;
       	selbox.options.length = 0;
       	switch(CRITERIA_ID)
	{
       	";

	$countrows=0;
	$row = $db->prepare($query);
	$row->execute();
	while ($result=$row->fetch(PDO::FETCH_NUM))
	{
		$countrows++;
		$output.="	case '$result[0]' : selbox.options[selbox.options.length] = new Option('','');";
		$subquery="select $value, $text from $table where $criteria = $result[0]";
		$subrow = $db->prepare($subquery);
		$subrow->execute();
		while ($subresult=$subrow->fetch(PDO::FETCH_NUM))
		{
			$output.= "selbox.options[selbox.options.length] = new Option('$subresult[1]','$subresult[0]');";
		}
		$output.= "break\n	";
	}
	
        $output.= "	default : selbox.options[selbox.options.length] = new Option('','');
	}
}
</script>";

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
