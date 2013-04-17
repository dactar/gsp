<?
class attribute
{
	public $db;

	function __tostring()
	{
	    return "Cette classe permet de définir et manipuler les attributs du dictionnaire<br/>";
	}

	function __construct()
	{
	    global $db;
	    $this->db = $db;
	}

	function insert_submit()
	{
	    $_REQUEST=utf8_array_decode($_REQUEST);
	    $query="INSERT into dict (code, description, active_f, rank_n, parent_dict_id) VALUES (:CODE, :DESCRIPTION, :ACTIVE_F, :RANK_N, :PARENT_ID)";
	    $row = $this->db->prepare($query);
	    $row->bindParam(':CODE',        $_REQUEST[code],        PDO::PARAM_STR);
	    $row->bindParam(':DESCRIPTION', $_REQUEST[description], PDO::PARAM_STR);
	    $row->bindParam(':ACTIVE_F',    $_REQUEST[active_f],    PDO::PARAM_INT);
	    $row->bindParam(':RANK_N',      $_REQUEST[rank_n],      PDO::PARAM_INT);
	    $row->bindParam(':PARENT_ID',   $_REQUEST[parent_dict_id],   PDO::PARAM_INT);
	    $row->execute();
	    sqlerror($this->db,$query);

	    return "On crée l'attribut";
	}
	function update_submit()
	{
	    $_REQUEST=utf8_array_decode($_REQUEST);
	    $query="UPDATE dict set code=:CODE, description=:DESCRIPTION, rank_n=:RANK_N, active_f=:ACTIVE_F, parent_dict_id=:PARENT_ID where dict_id = :ID";
	    $row = $this->db->prepare($query);
	    $row->bindParam(':ID',          $_REQUEST[dict_id],          PDO::PARAM_INT);
	    $row->bindParam(':CODE',        $_REQUEST[code],        PDO::PARAM_STR);
	    $row->bindParam(':DESCRIPTION', $_REQUEST[description], PDO::PARAM_STR);
            $row->bindParam(':ACTIVE_F',    $_REQUEST[active_f],    PDO::PARAM_INT);
	    $row->bindParam(':RANK_N',      $_REQUEST[rank_n],      PDO::PARAM_INT);
	    if ( $_REQUEST[parent_dict_id] != "" )
	    {
            	$row->bindParam(':PARENT_ID',   $_REQUEST[parent_dict_id],   PDO::PARAM_INT);
	    }
	    $row->execute();
	    sqlerror($this->db,$query);

	    return "On met à jour l'attribut";
	}

	function delete_submit()
	{
	    $query="DELETE from dict where dict_id = :ID";
	    $row = $this->db->prepare($query);
	    $row->bindParam(':ID',          $_REQUEST[sel_dict_id],          PDO::PARAM_STR);
	    $row->execute();
	    sqlerror($this->db,$query);
	    return "On détruit l'attribut";
	}
}
?>
