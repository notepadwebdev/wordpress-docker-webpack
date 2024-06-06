<?php

function getUniqueIdFromLabel($label) {
  $id = str_replace(array(',', '& '), '', $label);
  $id = strtolower($id); 
  $id = str_replace(' ', '-', $id);

  return $id;
}

function splitStringIntoSpans($str) {
  $re = "/([^\\s>])(?!(?:[^<>]*)?>)/u"; 
  $subst = "<span>$1</span>"; 
  $result = preg_replace($re, $subst, $str);
  return $result;
}

?>