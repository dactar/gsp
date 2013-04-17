<?php
function array2xml($root,$array) 
{
    if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") )
    {
        header("Content-type: application/xhtml+xml");
    }
    else
    {
        header("Content-type: text/xml");
    }

    $xml  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    $xml .= "<$root>\n";
    foreach ($array as $key => $value) 
    {
            $xml .= "<{$key}>".utf8_encode($value)."</{$key}>\n";
    }
    $xml .= "</$root>\n";
    return $xml;
}
?>
