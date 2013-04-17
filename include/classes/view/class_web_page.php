<?
class web_page
{
	public $title;
	public $css;
        public $meta;
	public $jsfile;
	public $script;
	public $html;
	public $div;
     

	function __tostring()
	{
	    return "Cette classe permet de gérer une page WEB";
	}

	function __construct($title)
	{
            $this->title = $title;
	    $this->css = array();
	    $this->meta = array();
	    $this->jsfile = array();
        }

	function add_css($file)
	{
	    $this->css[]=$file;
	    return "on ajoute un fichier aux css";
	}

	function add_jsfile($file)
	{
	    $this->jsfile[]=$file;
	    return "on ajoute un fichier aux fichiers javascript";
	}

        function add_meta($http_equiv,$content)
        {
	    $this->meta[$http_equiv]=$content;
        }

	function add_html($string)
	{
	    $this->html .= $string . "\n";
	}

	function add_div($string)
        {
            $this->div .= $string . "\n";
        }


	function add_script($string)
	{
	    $this->script .= $string;
	}

	function display_css()
	{
	    foreach ($this->css as $i => $value) 
	    {
    		echo "\t<link rel='stylesheet' type='text/css' href='$value'>\n";
	    }	
	}

        function display_jsfile()
        {
            foreach ($this->jsfile as $i => $value)
            {
                echo "\t<script type='text/javascript' src='$value'></script>\n";
	    }
        }

	function display_meta()
        {
	    foreach ($this->meta as $http_equiv => $content)
	    {
		echo "\t<meta http-equiv='$http_equiv' content='$content'>\n";
	    }
        }

	function render()
	{
            echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
            echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"fr\" xml:lang=\"fr\">\n";
            echo "<head>\n";
            echo "\t<link rel='shortcut icon' href='favicon.ico' type='image/x-icon'>\n";
	    $this->display_css();
	    $this->display_meta();
	    echo "\t<title>$this->title</title>\n";
            echo "</head>\n<body>\n";
	    $this->display_jsfile();
            echo "<html>\n";
	    echo $this->div;
	    echo $this->script;
            echo $this->html;
            //echo "</html>";
	}
}
?>
