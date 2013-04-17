<?
set_time_limit(240);
$NEWCNT=mailstream($db,$GSP_INBOX_DB_ID,$GSP_INBOX_DB_PATH,$GSP_INBOX_DB_TABLE);
return $NEWCNT;
?>
