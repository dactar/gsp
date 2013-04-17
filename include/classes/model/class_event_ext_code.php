<?
class ext_code
{
	public $db;

	function __construct()
	{
	    global $db;
	    $this->db = $db;
	}

        function insert_prepare()
	{
            echo "<input type='hidden' name='EVENT_ID' value='$_REQUEST[EVENT_ID]'></input>";
	    echo "<input type='hidden' name='ACTION' value='EXT_CODE_SUBMIT'>";

	    echo "<b>Assigner un code externe</b><br><br><br>";

            echo "<center>";
            echo "<a href='javascript:history.go(-1)' title='RETOUR'><img src='pict/back.png' border=0 alt='RETOUR'></a>";
            echo "</center>";

            echo "<table class='T2'><tr><td>";

            echo "<table class='T2'><tr><td class='TD2'>";
            echo return_query($this->db,"select description from dict_vw where code = 'code externe'");
            echo "</tr><tr><td class='TD2A'>";
            echo " <select size='1' name = 'EXT_CODE'>";
            echo return_query_form_options($this->db, $this->type, "select dict_id, code from dict_vw where parent_code = 'code externe' order by rank_n");
            echo "</select>";
            echo "</td></tr></table>";

            echo "</td><td>";

            echo "<table class='T2'><tr><td class='TD2'>";
            echo "No d'incident";
            echo "</tr><tr><td class='TD2A'>";
            echo "<input name='NO_INCIDENT' type='text' size=14></input><br>";
            echo "</td></tr></table>";

            echo "</td><td>";

            echo "</td></tr></table><br>";

	    echo "<input type= 'submit' value='Sauver'>";
            return "On prÈpare la crÈation d'un code externe";
	}

        function insert_submit()
        {
	     $query="INSERT into event_ext_code(event_id, code, type_dict_id) VALUES (:EVENT_ID, :CODE, :TYPE_DICT_ID)";
             $row = $this->db->prepare($query);
             $row->bindParam(':EVENT_ID', $_POST[EVENT_ID], PDO::PARAM_INT);
             $row->bindParam(':CODE', $_POST[NO_INCIDENT], PDO::PARAM_STR);
             $row->bindParam(':TYPE_DICT_ID', $_POST[EXT_CODE], PDO::PARAM_INT);
             $row->execute();
             sqlerror($this->db,$query);

             return "on cr≈e le nouveau code externe";
	}

	function update_submit()
	{
            echo "<script language='javascript'>alert('FONCTION NON ENCORE CODEE !!!')</script>;";
	    return "On met ‡ jour l'attribut";
	}

	function delete_submit()
	{
            echo "<script language='javascript'>alert('FONCTION NON ENCORE CODEE !!!')</script>;";
	    return "On dÈtruit l'attribut";
	}
}
?>
