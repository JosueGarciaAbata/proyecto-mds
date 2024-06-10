<?php
function cleanText($text)
{
  // Retirar etiquetas HTML
  $clean_text = strip_tags($text);

  // Retirar etiquetas de script
  $clean_text = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $clean_text);

  // Retornar el texto limpio
  return $clean_text;
}

?>