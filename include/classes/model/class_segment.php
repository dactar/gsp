<?
class segment
{
	public $db;
	public $id;

	function __tostring()
	{
	    return "Cette classe permet de définir et manipuler un segment<br/>";
	}

	function __construct()
	{
	    global $db;
	    $this->db = $db;
	}

	function display_actions()
	{
	    	echo "
	    	<input type='submit' name='AFFICHE' value='Afficher la table'></input>
	    	<input type='submit' name='MODIFY' value='Modifier'></input>
	    	<input type='submit' name='INSERT' value='Ins&eacute;rer'></input>
	    	<input type='submit' name='DELETE' value='Supprimer'></input>";

	    	return "On affiche les actions disponibles"; 
	}

	function insert_submit()
	{
		$_POST=utf8_array_decode($_POST);
		$query="INSERT into segment (parent_id, appl_dict_id, code, description, rank_n, type_dict_id, prod_f, supported_f, default_f) VALUES (:PARENT_ID,:APPL_ID,:CODE,:DESCRIPTION,:RANK_N,:TYPE_ID,:PROD_F,:SUPPORTED_F,:DEFAULT_F)";
                $row = $this->db->prepare($query);
                if ( $_POST[parent_id] != "0" )
                {
                    $row->bindParam(':PARENT_ID',   $_POST[parent_id],   PDO::PARAM_INT);
                }
                $row->bindParam(':APPL_ID',     $_POST[appl_dict_id],     PDO::PARAM_INT);
                $row->bindParam(':CODE',        $_POST[code],        PDO::PARAM_STR);
                $row->bindParam(':DESCRIPTION', $_POST[description], PDO::PARAM_STR);
                $row->bindParam(':RANK_N',      $_POST[rank_n],      PDO::PARAM_INT);
                $row->bindParam(':TYPE_ID',     $_POST[type_dict_id],     PDO::PARAM_INT);
                $row->bindParam(':PROD_F',      $_POST[prod_f],      PDO::PARAM_INT);
                $row->bindParam(':SUPPORTED_F', $_POST[supported_f], PDO::PARAM_INT);
                $row->bindParam(':DEFAULT_F',   $_POST[default_f], PDO::PARAM_INT);
                $row->execute();
                sqlerror($this->db,$query);
		return "On crée le segment";	
	}
	function update_submit()
	{
		$_POST=utf8_array_decode($_POST);	
		$query="UPDATE segment set parent_id = :PARENT_ID, appl_dict_id = :APPL_ID, code = :CODE, description = :DESCRIPTION, rank_n = :RANK_N, type_dict_id = :TYPE_ID, prod_f = :PROD_F, supported_f = :SUPPORTED_F, default_f = :DEFAULT_F where id = :ID";

                $row = $this->db->prepare($query);
                $row->bindParam(':ID', $_POST[id], PDO::PARAM_INT);
                if ( $_POST[parent_id] != "0" )
                {
                $row->bindParam(':PARENT_ID',   $_POST[parent_id],   PDO::PARAM_INT);
                }
                $row->bindParam(':APPL_ID',     $_POST[appl_dict_id],     PDO::PARAM_INT);
                $row->bindParam(':CODE',        $_POST[code],        PDO::PARAM_STR);
                $row->bindParam(':DESCRIPTION', $_POST[description], PDO::PARAM_STR);
                $row->bindParam(':RANK_N',      $_POST[rank_n],      PDO::PARAM_INT);
                $row->bindParam(':TYPE_ID',     $_POST[type_dict_id],     PDO::PARAM_INT);
                $row->bindParam(':PROD_F',      $_POST[prod_f],      PDO::PARAM_INT);
                $row->bindParam(':SUPPORTED_F', $_POST[supported_f], PDO::PARAM_INT);
		$row->bindParam(':DEFAULT_F',   $_POST[default_f], PDO::PARAM_INT);
                $row->execute();
                sqlerror($this->db,$query);
	
	    	return "On met à jour le segment";
	}

	function delete_submit()
	{
	    $query="DELETE from segment where id = :ID";
	    $row = $this->db->prepare($query);
	    $row->bindParam(':ID', $_POST[id], PDO::PARAM_INT);
	    $row->execute();
	    sqlerror($this->db,$query);

	    return "On détruit le segment";
	}
}
?>
