<?
function writelog($START,$END,$MODL,$USER,$RESULT)
{
	global $LOGFILE;
	global $REMOTE_HOST;
	if ($MODL == "")
	{
		$MODL="    ";
	}
	$FILE=fopen("$LOGFILE","a");
	fputs($FILE,"$START => $END / $MODL : $USER@$REMOTE_HOST $RESULT\n");
	fclose($FILE);
}
?>
