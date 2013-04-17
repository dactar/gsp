<?php
function utf8_array_decode($array)
{
    $return = array();

    foreach ($array as $key => $val)
    {
        if( is_array($val) )
        {
            $return[$key] = utf8_array_decode($val);
        }
        else
        {
            $return[$key] = utf8_decode($val);
        }
    }
    return $return;          
}
?>
