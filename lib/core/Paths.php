<?php
/**
 * Path Class
 * This class provides a way to retain get parameters but also edit 
 * or add onto them. 
 * 
 * @package SCRIPTLOG/LIB/CORE/Paths
 * @category Core class
 * @source PHP.Net PHP parse_url
 * @see   https://secure.php.net/manual/en/function.parse-url.php#121392
 * 
 */
class Paths
{
  
 private $url;

 public function __construct($url)
 {
   if (isset($url) || array_key_exists($url)) {
     $this->url = parse_url($url);
   }
 }

 public function turnBackUrl()
 {
   $query_string = (array_key_exists('query', $this->url) ? $this->url['query'] : null);
   $turnBack = $this->url['path'].'?'.$query_string;
   $turnBack = (substr($turnBack,-1) == "&") ? substr($turnBack, 0, -1) : $turnBack;
   $this->resetQuery();
   return $turnBack;
 }

 public function changePath($path)
 {
   $this->url['path'] = $path;
 }

 public function editQuery($get, $value)
 {
   $parts = explode("&",$this->url['query']);
   $turnBack = "";
   foreach($parts as $p) {
     $paramData = explode("=",$p);
     if ($paramData[0] == $get) {
       $paramData[1] = $value;
     }

     $turnBack .= implode("&",$paramData).'&';

   }

   $this->url['query'] = $turnBack;

 }

 public function addQuery($get, $value)
 {
   $part = $get."=".$value;
   $and = (isset($this->url['query']) && $this->url['query'] == "?") ? "" : "&";
   $this->url['query'] .= $and.$part;
 }
 
 public function checkQuery($get) 
 {
   $parts = explode("&",$this->url['query']);
   foreach($parts as $p) {
    $paramData = explode("=",$p);
    if($paramData[0] == $get) 
         return true;
   }
   return false;
 }

 public function buildQuery($get, $value)
 {
   if($this->checkQuery($get)) {

     $this->editQuery($get, $value);

    } else {

     $this->addQuery($get, $value);

    } 
     
 }

 public function resetQuery()
 {
   $this->url = parse_url($_SERVER['REQUEST_URI']);
 }

}