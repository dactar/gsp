<?
class stat
{
	public $db;
	public $id;
	public $web_page;
	public $query;
	public $out_type;

	function __tostring()
	{
	    return "Cette classe permet de d'afficher des statistiques<br/>";
	}

	function __construct()
	{
	    global $db;
            global $web_page;
	    $this->db = $db;
	    $this->id = $_REQUEST[STAT_ID];
	    $this->web_page = $web_page;

            if ($this->id == "")
            {
		$this->out_type="pie3D";
	    	$this->query="SELECT status_code, count(*) FROM event_vw WHERE closed_d IS NULL GROUP BY status_code";
	    }
            else
            {
		if($this->id=="hist_open_all" || $this->id=="hist_open_user" || $this->id=="hist_curr_user")
		{
			$this->out_type="line";
			if($this->id=="hist_open_all")
			{
				$this->query="select distinct date(e.logged_d), sum((select count(*) from event where logged_d = e.logged_d)) from event e group by date(e.logged_d) order by 1"; 
			}
			else
			{
				$userid=return_query($this->db,"select id from user where code = '$_COOKIE[GSP_USER]'");
				if ($this->id=="hist_open_user")
				{
					$this->query="select distinct date(e.logged_d), sum((select count(*) from event where logged_d = e.logged_d and owner_id = $userid)) from event e group by date(e.logged_d) order by 1";
				}
				else
				{
					$this->query="select distinct date(e.logged_d), sum((select count(*) from event where logged_d = e.logged_d and owner_id = $userid)) from event e group by date(e.logged_d) order by 1;select distinct date(e.closed_d), sum((select count(*) from event where closed_d = e.closed_d and owner_id = $userid)) from event e group by date(e.closed_d) order by 1";
				}
			}
		}
		else
		{
			$this->out_type="pie3D";
			$selection=$this->id;
			if($this->id=="group_contact_code") $selection="(select group_code from contact_vw c where c.id = event_vw.contact_id)";
			$this->query="SELECT $selection, count(*) FROM event_vw WHERE closed_d IS NULL GROUP BY 1";
		}
	    }
	}

	function display_actions($admin)
	{
            if($this->id=="status_code") $choice1="selected";
	    if($this->id=="owner") $choice2="selected";
	    if($this->id=="priority_code") $choice3="selected";
            if($this->id=="severity_code") $choice4="selected";
            if($this->id=="type_code") $choice5="selected";
            if($this->id=="appl_code") $choice6="selected";
            if($this->id=="group_contact_code") $choice7="selected";
            if($this->id=="hist_open_user") $choice8="selected";
            if($this->id=="hist_open_all") $choice9="selected";
	    if($this->id=="hist_curr_user") $choice10="selected";


	    $this->web_page->add_div("<div id='stat'>");
            $this->web_page->add_div("<center>Sélection de la statistique<br><br>");
            $this->web_page->add_div("<form action = 'index.php?MODL=$_REQUEST[MODL]' method='post'>");
            $this->web_page->add_div("<select name='STAT_ID' size='1' onchange='javascript:submit()'>");
	    $this->web_page->add_div("<option id='1' value='status_code' $choice1>Evénements ouverts par statut</option>");
            $this->web_page->add_div("<option id='2' value='owner' $choice2>Evénements ouverts par utilisateur</option>");
	    $this->web_page->add_div("<option id='3' value='priority_code' $choice3>Evénements ouverts par priorité</option>");
	    $this->web_page->add_div("<option id='4' value='severity_code' $choice4>Evénements ouverts par importance</option>");
	    $this->web_page->add_div("<option id='5' value='type_code' $choice5>Evénements ouverts par type</option>");
	    $this->web_page->add_div("<option id='6' value='appl_code' $choice6>Evénements ouverts par application</option>");
	    $this->web_page->add_div("<option id='7' value='group_contact_code' $choice7>Evénements ouverts par groupe des contacts</option>");
	    $this->web_page->add_div("<option id='8' value='hist_open_user' $choice8>Historique de mes  événements ouverts</option>");
	    $this->web_page->add_div("<option id='9' value='hist_open_all' $choice9>Historique de tous les événements ouverts</option>");
	    $this->web_page->add_div("<option id='10' value='hist_curr_user' $choice10>Historique de tous mes événements en cours</option>");
            $this->web_page->add_div("</select></form></center>");
	    $this->web_page->add_div("</div>");

	    return "On affiche les actions disponibles";
	}

	function display_data($admin)
	{
            $web_chart = new web_chart($this->out_type,$this->query);
            $web_chart->display();

	    $this->web_page -> add_css("css/dhtmlx_layout_strict.css");
	    $this->web_page -> add_css("ext/dhtmlx/css/dhtmlxlayout.css");
	    $this->web_page -> add_css("ext/dhtmlx/css/dhtmlxlayout_dhx_skyblue.css");

	    $this->web_page->add_jsfile("ext/dhtmlx/dhtmlxlayout.js");
	    $this->web_page->add_jsfile("ext/dhtmlx/dhtmlxcontainer.js");

	    $this->web_page->add_script("
		<script>
		dhxLayout = new dhtmlXLayoutObject(document.body, '2E');
	
		dhxLayout.setEffect('resize', false);
		dhxLayout.setEffect('collapse', false);
		dhxLayout.setEffect('highlight', false);

		dhxLayout.cells('a').setHeight(80);

		dhxLayout.cells('a').fixSize(true, true);
		dhxLayout.cells('b').fixSize(true, true);

		dhxLayout.cells('a').hideHeader();
		dhxLayout.cells('b').hideHeader();

		dhxLayout.cells('a').attachObject('stat');
		</script>
	    ");

            $this->web_page->render();
	    return "On affiche les slat";
	}
}
?>
