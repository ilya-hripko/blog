<?php

function hkeh_debug_backtrace($call_back, $unset_level=0) {

   $data = debug_backtrace(false);
   foreach ($data as $k => &$v) {
     if ($k<$unset_level)
     {
        unset($data[$k]);
        continue;
     }
     array_walk_recursive($v, $call_back);
   }
   return $data;
}

function hkeh_shortest_backtrace($item, $key) {
   if (is_object($item))
     $item = '<--OBJECT-->';
}