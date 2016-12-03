<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


class Char {
 public $firstName;
 public $lastName;
 public $imageUrl;
 public $ressortName;
 public $ressortDescription;
 public $id;

 public function __construct($contactRow) {
   $this->firstName = $contactRow->getValueForKey('first_name');
   $this->lastName = $contactRow->getValueForKey('last_name');
   $this->imageUrl = wp_get_attachment_image_src($contactRow->getValueForKey('image'), $size='')[0];
   $this->ressortName = $contactRow->getValueForKey('ressortName');
   $this->ressortDescription = $contactRow->getValueForKey('ressortDescription');
   $this->id = uniqid('', true);
 }
}