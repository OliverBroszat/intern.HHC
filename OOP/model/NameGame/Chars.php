<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


class Chars {
 public $chars;

 public function __construct() {
   // Get new base data controller for fetching the database
   $base = new BaseDataController();

   // Get all contacts with an image as rows
   $contactsWithImageRows = $base->selectMultipleRowsByQuery("
   select *
   from
   	(select *
   	from
   		(select * from contact where image is not null) as t1
   		join
   		(select ressort, contact from member) as t2
   		on t1.id = t2.contact) as t3
   	join
   	(select name as ressortName, id as ressortID, description as ressortDescription from ressort) as t4
   	on t3.ressort = t4.ressortID
   ");

   // Create chars from rows
   $chars = [];
   foreach ($contactsWithImageRows as $value) {
     array_push($chars, new Char($value));
   }

   // Set chars
   $this->chars = $chars;
 }

 public function getRandomChars($count, $ressortName, $allRessortName) {
   return new RandomChars($this->chars, $count, $ressortName, $allRessortName);
 }
}