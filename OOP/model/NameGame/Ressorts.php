<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


class Ressorts {
   public $ressorts;

   public function __construct() {
     // Get new base data controller for fetching the database
     $base = new BaseDataController();

     // Get all ressorts as rows
     $ressortsRows = $base->selectMultipleRowsByQuery("select * from ressort where name != 'unbekannt'");

     // Create ressorts from rows
     $ressorts = [];
     foreach ($ressortsRows as $value) {
       array_push($ressorts, new Ressort($value));
     }

     // Set ressorts
     $this->ressorts = $ressorts;
   }
 }