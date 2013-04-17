<?
class db_query
{
	public $db;
        public $query;

	function __tostring()
	{
	    return "Cette classe d'exécuter une requête sur la base de donnée";
	}

	function __construct($query)
	{
	    global $db;
	    $this->db = $db;
	    $this->query = $query;
        }

	function execute()
	{
            $row = $this->db->prepare($this->query);
            $row->execute();
            $result = $row->fetchAll(PDO::FETCH_COLUMN);
            return $result;
	}
}
?>
