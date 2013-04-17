<?
class journal
{
	public $db;
	public $web_page;
	public $date;

	function __tostring()
	{
	    return "Cette classe permet de d'afficher le journal<br/>";
	}

	function __construct()
	{
	    global $db;
            global $web_page;
	    $this->db = $db;
	    $this->web_page = $web_page;
	    if(isset($_REQUEST[date]))
	    {
		$this->date=$_REQUEST[date];
	    }
	    else
	    {
	    	$this->date=date('Y-m-d',time());
	    }
	}

	function display_actions($admin)
	{
	    echo "<center>";
	    echo "<br>";
            echo "<form action = 'index.php?MODL=$_REQUEST[MODL]' method='post'>";
            echo "<input type=text size=10 name=date value=$this->date>";
            echo "<input type='submit' value='ok'></form></center>";
	}

	function display_data($admin)
	{
	    display_table($this->db,"select * from func_journal_vw where date(date) = '$this->date'");
	    return "On affiche le journal";
	}
}
?>
