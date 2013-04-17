<?
class web_chart
{
	public $db;
	public $query;
	public $type;
	public $web_page;

	function __tostring()
	{
	    return "Cette classe permet de gérer un graphique";
	}

	function __construct($type, $query)
	{
	    global $db;
            global $web_page;
	    $this->db = $db;
            $this->type = $type;
            $this->web_page = $web_page;
	    $this->web_page->add_css("ext/dhtmlx/css/dhtmlxchart.css");
	    $this->web_page->add_jsfile("ext/dhtmlx/dhtmlxchart.js");

	    $split_query=explode(";",$query);
		
	    if($split_query[1] == "")
	    {
            	$this->query = $query;
	    }
	    else
	    {
		$this->query = $split_query;
            }
        }

	function display()
	{
	    if(!is_array($this->query))
	    {
	    	$data=return_query_array($this->db,"$this->query");
	    }
	    else
	    {
		$query=$this->query[0];
		$data1=return_query_array($this->db,"$query",false);
		$query=$this->query[1];
		$data2=return_query_array($this->db,"$query",false);
                $array_merge=array_fill_keys(array_merge(array_keys($data1),array_keys($data2)),0);

		echo "<pre>";
		$count=0;
                $array_fusion = array();
                foreach($array_merge as $key => $value)
                {
                        $array_fusion[] = array($key,($array_fusion[$count-1][1]) + $data1[$key] - $data2[$key]);
                        $oldkey=$key;
			$count++;
                }
		$data=$array_fusion;
	    }
	    $jsdata="[";

            $count=0;
            foreach ($data as $id => $row)
            {
		if ($count!=0) $jsdata .= ",";
                $jsdata .= "['$row[0]','$row[1]']";
		$count++;
            }

	    $jsdata .= "]";

            $this->web_page->add_div("<div id='chart'></div>");

            if ($this->type == "pie3D")
	    {
		$chart="
var chart = dhxLayout.cells('b').attachChart({
        view: '$this->type',
        container: 'chart',
        value: '#data1#',
        details: '#data0#',
        pieInnerText: '#data1#',
        label: '#data0#'

});";
	    }
	    
            if ($this->type == "line")
	    {
	        $chart="
var chart =  dhxLayout.cells('b').attachChart({
		view:'$this->type',
		container:'chart',
	        value:'#data1#',
                tooltip: '#data1#',
                item:{
		     radius:0
                },
                line:{
                     color:'#ff9900',
                     width:1
                },
                xAxis:{
		     title:'Date',
		     template:function(obj, common, data)
		     {
			var count = ((chart.dataCount()*obj.data0.length)/30).toFixed();
			if (obj.id%count == 0 || obj.id == 1) return obj.data0;
			return '';
		     }
		},
                yAxis:{
		     title:'Nombre'
	        },
 
});";
	   }
      

$this->web_page->add_script("
<style>
    .dhx_chart_title{
        padding-left:43px
    }
</style>

<script>
var data = $jsdata;

window.onload = function() {

$chart

chart.parse(data,'jsarray');

//chart.attachEvent('onItemclick', function (id){
//var data = chart.get(id);
//alert(data.data0);
//});
}

</script>
");

	    
            return "On affiche le graphique"; 
	}
}
?>
