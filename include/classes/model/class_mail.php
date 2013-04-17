<?
class mail
{
	public $db;
	public $inbox_table;
	public $id;
	public $mail_date;
	public $mail_from;
	public $mail_to;
	public $mail_cc;
	public $mail_subject;
	public $locked_f;
	public $hidden_f;
	public $treated_f;
	public $mail_body;
	public $gsp_user_id;
	public $last_user_id;
	public $last_modif_d;
        public $file_nbr;
	public $mail_size;

	function __tostring()
	{
	    return "Cette classe permet de définir et manipuler un mail<br/>";
	}

	function __construct()
	{
	    if ($_REQUEST[MAIL_ID] != "")
	    {
	    	global $db;
	    	global $GSP_INBOX_DB_TABLE;

	    	$this->db = $db;
	    	$this->inbox_table = $GSP_INBOX_DB_TABLE;
	    	$this->inbox_table_att = $this->inbox_table . "_attachment";
	    	$this->id = $_REQUEST[MAIL_ID];
	    	$this->mail_date    = return_query($this->db,"SELECT mail_date from gsp_inbox.$this->inbox_table where id = $this->id");
	    	$this->mail_from    = return_query($this->db,"SELECT mail_from from gsp_inbox.$this->inbox_table where id = $this->id");
	    	$this->mail_to      = return_query($this->db,"SELECT mail_to from gsp_inbox.$this->inbox_table where id = $this->id");
	    	$this->mail_cc      = return_query($this->db,"SELECT mail_cc from gsp_inbox.$this->inbox_table where id = $this->id");
	    	$this->mail_subject = htmlspecialchars(return_query($this->db,"SELECT mail_subject from gsp_inbox.$this->inbox_table where id = $this->id"));
	    	$this->locked_f     = return_query($this->db,"SELECT locked_f from gsp_inbox.$this->inbox_table where id = $this->id");
	    	$this->hidden_f     = return_query($this->db,"SELECT hidden_f from gsp_inbox.$this->inbox_table where id = $this->id");
	    	$this->treated_f    = return_query($this->db,"SELECT treated_f from gsp_inbox.$this->inbox_table where id = $this->id");
	    	$this->gsp_user_id  = return_query($this->db,"SELECT id from user where code = '$_COOKIE[GSP_USER]'");
	    	$this->last_user_id = return_query($this->db,"SELECT last_user_id from gsp_inbox.$this->inbox_table where id = $this->id");
	    	$this->last_modif_d = return_query($this->db,"SELECT last_modif_d from gsp_inbox.$this->inbox_table where id = $this->id");
	    	if($_REQUEST[TEXTTYPE] == "HTML")
	    	{
			$this->mail_body    = return_query($this->db,"SELECT data from gsp_inbox.$this->inbox_table_att where mail_id = $this->id and subtype='html'");
	    	}
	    	else
	    	{
	    		$this->mail_body    = return_query($this->db,"SELECT mail_body from gsp_inbox.$this->inbox_table where id = $this->id");
	    	}
	    	$this->file_nbr     = return_query($this->db,"SELECT count(id) from gsp_inbox.$this->inbox_table_att where mail_id = $this->id and type != 'text'");
	    	$this->mail_size	= return_query($this->db,"SELECT mail_size from gsp_inbox.$this->inbox_table where id = $this->id");
	    }
	}

	function display_actions()
	{
	    echo "<center>";
	    echo "<input type='hidden' name='MAIL_ID' value='$this->id'></input>";
	    echo "<input type='hidden' name='ACTION' id='ACTION'>";
		
	    if ($this->treated_f == '0')
	    {
	    	if ($this->hidden_f == '0')
	    	{
	    		echo "<a href='javascript:if(document.getElementById(\"mail\").TEXTTYPE.value!=\"\"){parent.dhxWins.window(\"win_mail_id_$_REQUEST[MAIL_ID]\").maximize();document.getElementById(\"mail\").ACTION.value=\"EVENT_CREATE\";document.getElementById(\"mail\").submit()}else{alert(\"TEXT / HTML ?\")}'><img src='pict/event_create.png' border=0 alt='Créer événement' title='Créer événement'></a>";
	    		echo "<a href='javascript:document.getElementById(\"mail\").ACTION.value=\"HIDE\";document.getElementById(\"mail\").submit()'><img src='pict/mail_hide.png' border=0 alt='Cacher' title='Cacher'></a>\n";

	    		echo "<a href='javascript:if(document.getElementById(\"mail\").TEXTTYPE.value!=\"\"){parent.dhxWins.window(\"win_mail_id_$_REQUEST[MAIL_ID]\").maximize();document.getElementById(\"mail\").ACTION.value=\"EVENT_ATTACH_MAIL\";document.getElementById(\"mail\").submit()}else{alert(\"TEXT / HTML ?\")}'><img src='pict/event_new_history.png' border=0 alt='Attacher à un événement existant' title='Attacher à un événement existant'></a>";
	    	}
	    	else
	    	{
	    		echo "<a href='javascript:document.getElementById(\"mail\").ACTION.value=\"UNHIDE\";document.getElementById(\"mail\").submit()'><img src='pict/mail_unhide.png' border=0 alt='Remontrer' title='Remontrer'></a>\n";
	    	}
	    }
	    else
	    {
	    	echo "<a href='javascript:document.getElementById(\"mail\").ACTION.value=\"RETREAT\";document.getElementById(\"mail\").submit()'>RETRAITER</a>\n";
	    }
	    echo "</center>";
	    return "On affiche les actions disponibles"; 
	}

	function reserve()
	{
	    echo "<script language='javascript'>alert('FONCTION NON ENCORE CODEE !!!')</script>;";
	    return "On réserve le mail'";
	}

	function open()
	{
	    echo "<script language='javascript'>alert('FONCTION NON ENCORE CODEE !!!')</script>;";
	    return "On ouvre le mail'";
	}

	function display()
	{
            display_table($this->db,"SELECT mail_date as Date, mail_from as De, mail_to as Pour, mail_cc as Copie from gsp_inbox.$this->inbox_table where id = $this->id");
   	    display_table($this->db,"SELECT mail_subject as Sujet from gsp_inbox.$this->inbox_table where id = $this->id");

	    if($this->file_nbr != "0")
	    {
	    	display_table($this->db,"SELECT '<a href=''index.php?MODL=GETA&amp;OBJECT=mail&amp;TYPE=' || subtype || '&amp;ID=' || mail_id || '&amp;FILENAME=' || name || ''' target=''GSP_ATT''>' || name || '</a>' as 'Pi&egrave;ces jointes' from gsp_inbox.$this->inbox_table_att where mail_id = $this->id and type != 'text'");
	    }
	    $html=return_query($this->db,"SELECT id from gsp_inbox.$this->inbox_table_att where mail_id = $this->id and subtype='html'");
	    if($html != "")
	    {
	    	echo "<table align=center width=95%><tr><td valign=top align=center>";
		echo "<input type=hidden name=TEXTTYPE>";
		echo "<input type=radio name=TEXTTYPECHOICE onclick='document.getElementById(\"mail\").TEXTTYPE.value=\"TEXT\"'>";
		echo "</td><td valign=top align=center>";
		echo "<input type=radio name=TEXTTYPECHOICE onclick='document.getElementById(\"mail\").TEXTTYPE.value=\"HTML\"'>";
		echo "</td></tr><tr><td width=50% valign=top>";
	    	display_table($this->db,"SELECT mail_body as 'Description au format texte' from gsp_inbox.$this->inbox_table where id = $this->id");
	    	echo "</td><td width=50% valign=top>";
	    	display_table($this->db,"SELECT data as 'Description au format html' from gsp_inbox.$this->inbox_table_att where mail_id = $this->id and subtype='html'");
	    	echo "</td></tr></table>";
	    }
	    else
	    {
		echo "<input type=hidden name=TEXTTYPE value=TEXT>";
		display_table($this->db,"SELECT mail_body as Description from gsp_inbox.$this->inbox_table where id = $this->id");
	    }
	    return "On affiche le mail";
	}

	function retreat()
	{
	    $query="UPDATE gsp_inbox.$this->inbox_table set treated_f = 0, last_user_id=:GSP_USER_ID where id = :ID";
	    $row = $this->db->prepare($query);
            $row->bindParam(':ID', $this->id, PDO::PARAM_INT);
            $row->bindParam(':GSP_USER_ID', $this->gsp_user_id , PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);
            return "On flag le mail pour retraitement'";
	}
	function hide()
	{
            $query="UPDATE gsp_inbox.$this->inbox_table set hidden_f = 1, last_user_id=:GSP_USER_ID where id = :ID";
       	    $row = $this->db->prepare($query);
            $row->bindParam(':ID', $this->id, PDO::PARAM_INT);
	    $row->bindParam(':GSP_USER_ID', $this->gsp_user_id , PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);
	    return "On n'affiche plus le mail'";
	}

	function unhide()
        {
            $query="UPDATE gsp_inbox.$this->inbox_table set hidden_f = 0, last_user_id=:GSP_USER_ID where id = :ID";
            $row = $this->db->prepare($query);
            $row->bindParam(':ID', $this->id, PDO::PARAM_INT);
	    $row->bindParam(':GSP_USER_ID', $this->gsp_user_id , PDO::PARAM_INT);
            $row->execute();
            sqlerror($this->db,$query);
            return "On affiche à nouveau le mail'";
        }

	function send($mail_to, $mail_subject, $mail_body)
	{
	    global $GSP_MAIL_FROM;

	    $mail_headers='From: ' . $GSP_MAIL_FROM . "\n";
	    $mail_headers.='Return-Path: ' . $GSP_MAIL_FROM . "\n";
	    $mail_headers.='Cc: ' . $GSP_MAIL_FROM . "\n";
            $mail_headers.='Content-Type: text/html; charset="ISO-8859-1"'."\n";
            $mail_headers.='Content-Disposition: inline';

            mail($mail_to, $mail_subject, $mail_body, $mail_headers);
	}

	function delete()
	{
	    echo "<script language='javascript'>alert('FONCTION NON ENCORE CODEE !!!')</script>;";
	    return "On détruit le mail'";
	}
}
?>
