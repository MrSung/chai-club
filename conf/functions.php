<?php

function ifSessionExists() {
  if (isset($_SESSION)) {
    return true;
  } else {
    return false;
  }
}

function hsc($sth) {
  return htmlspecialchars($sth, ENT_QUOTES, 'UTF-8');
}

function input_validate($input) {
  $input = trim($input);
  $input = stripslashes($input);
  $input = htmlspecialchars($input, ENT_QUOTES);
  return $input;
}

function in_array_r($needle, $haystack, $strict = false) {
  foreach ($haystack as $item) {
    if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
      return true;
    }
  }
  return false;
}
