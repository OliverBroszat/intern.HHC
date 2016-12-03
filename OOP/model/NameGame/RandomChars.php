<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


class RandomChars {
 public $chars;

 public function __construct($chars, $count, $ressortName, $allRessortName) {
   $byRessortName = function ($var) use ($ressortName, $allRessortName) {
     return $ressortName === $allRessortName || $var->ressortName === $ressortName;
   };

   // Filter all chars by ressort name
   $chars = array_filter($chars, $byRessortName);

   // Not enough chars
   if(sizeof($chars) < $count) {
      $this->chars = NULL;
      return;
   }

   // Get random chars
   $randomChars = [];
   for ($i = 0; $i < $count; $i++) {
     $charIndex = rand(0, sizeof($chars) - 1);
     array_push($randomChars, $chars[$charIndex]);
     array_splice($chars, $charIndex, 1);
   }

   // Set random chars
   $this->chars = $randomChars;
 }

 public function getRandomChar() {
   if(is_null($this->chars)) {
     return NULL;
   }

   return $this->chars[rand(0, sizeof($this->chars) - 1)];
 }
}