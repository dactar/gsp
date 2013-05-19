<?
function return_dynamic_webform_options($form,$inputname,$db,$table,$criteria,$value,$text,$return=FALSE)
{
	$query="select distinct $criteria from $table";
	$output= "
<script language='javascript'>
function webform_load_optlist_$form" . "_$inputname(CRITERIA_ID)
{
	var opts=form.getOptions('$inputname');
	opts.length = 0;
       	switch(CRITERIA_ID)
	{
       	";

	$countrows=0;
	$row = $db->prepare($query);
	$row->execute();
	while ($result=$row->fetch(PDO::FETCH_NUM))
	{
		$countrows++;
		$output.="	case '$result[0]' : opts.add(new Option('',0));";
		$subquery="select $value, $text from $table where $criteria = $result[0]";
		$subrow = $db->prepare($subquery);
		$subrow->execute();
		while ($subresult=$subrow->fetch(PDO::FETCH_NUM))
		{
			$output.="opts.add(new Option('$subresult[1]','$subresult[0]'));";
		}
		$output.="break\n	";
	}
	
        $output.= "	default : opts.add(new Option('',0));
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
