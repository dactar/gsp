<?
function trimdot ($max_length,$string) {
  if (strlen($string) <= $max_length) {
   return $string;
  }

  $string_f = substr($string, 0, $max_length - 3);
  $string_f .= "...";
  return $string_f;
}
?>
